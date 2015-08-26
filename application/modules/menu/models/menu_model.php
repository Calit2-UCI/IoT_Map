<?php

namespace Pg\Modules\Menu\Models;

/**
 * Menu main model
 *
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * */
if (!defined('MENU_TABLE')) {
    define('MENU_TABLE', DB_PREFIX . 'menu');
}
if (!defined('MENU_ITEMS_TABLE')) {
    define('MENU_ITEMS_TABLE', DB_PREFIX . 'menu_items');
}

class Menu_model extends \Model
{

    public $CI;
    public $DB;
    public $menu_fields_all = array(
        "id",
        "gid",
        "name",
        "check_permissions",
        'date_created',
        'date_modified'
    );
    public $items_fields_all = array(
        "id",
        "menu_id",
        "parent_id",
        "gid",
        "link",
        "icon",
        "sorter",
        "status",
        "indicator_gid"
    );
    public $menu_cache = array();
    private $menu_items_cache = array();
    public $menu_raw_items_cache = array();
    public $curent_active_item_id = array();
    public $temp_generate_raw_menu = array();
    public $temp_generate_raw_items = array();
    public $breadcrumbs = array();
    private $max_length = 50;
    public $indicators = array();

    public function __construct()
    {
        parent::__construct();
        $this->CI = &get_instance();
        $this->DB = &$this->CI->db;
        if (INSTALL_DONE) {
            $this->DB->memcache_tables(array(MENU_TABLE, MENU_ITEMS_TABLE));
        }
    }

    // menu functions
    public function get_menus_list($page = null, $items_on_page = null, $order_by = array())
    {
        $this->DB->select(implode(", ", $this->menu_fields_all));
        $this->DB->from(MENU_TABLE);

        if (is_array($order_by) && count($order_by) > 0) {
            foreach ($order_by as $field => $dir) {
                if (in_array($field, $this->fields_all)) {
                    $this->DB->order_by($field . " " . $dir);
                }
            }
        }

        if (!is_null($page)) {
            $page = intval($page) ? intval($page) : 1;
            $this->DB->limit($items_on_page, $items_on_page * ($page - 1));
        }

        $results = $this->DB->get()->result_array();
        if (!empty($results) && is_array($results)) {
            foreach ($results as $r) {
                $data[] = $r;
            }
            return $data;
        }
        return false;
    }

    public function get_menus_count($params = array())
    {
        $this->DB->select("COUNT(*) AS cnt");
        $this->DB->from(MENU_TABLE);

        if (isset($params["where"]) && is_array($params["where"]) && count($params["where"])) {
            foreach ($params["where"] as $field => $value) {
                $this->DB->where($field, $value);
            }
        }

        if (isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])) {
            foreach ($params["where_in"] as $field => $value) {
                $this->DB->where_in($field, $value);
            }
        }

        if (isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])) {
            foreach ($params["where_sql"] as $value) {
                $this->DB->where($value);
            }
        }

        $result = $this->DB->get()->result();
        if (!empty($result)) {
            return intval($result[0]->cnt);
        } else {
            return 0;
        }
    }

    public function get_menu_by_id($menu_id)
    {
        if (!isset($this->menu_cache[$menu_id])) {
            $result = $this->DB->select(implode(", ", $this->menu_fields_all))->from(MENU_TABLE)->where("id", $menu_id)->get()->result_array();
            if (!empty($result)) {
                $this->menu_cache[$menu_id] = $this->menu_cache[$result[0]["gid"]] = $result[0];
            }
        }
        return $this->menu_cache[$menu_id];
    }

    public function get_menu_by_gid($gid)
    {
        if (!isset($this->menu_cache[$gid])) {
            $result = $this->DB->select(implode(", ", $this->menu_fields_all))->from(MENU_TABLE)->where("gid", $gid)->get()->result_array();
            if (!empty($result)) {
                $this->menu_cache[$gid] = $this->menu_cache[$result[0]["id"]] = $result[0];
            }
        }
        return $this->menu_cache[$gid];
    }

    public function save_menu($menu_id, $attrs)
    {
        if (is_null($menu_id)) {
            $attrs["date_created"] = $attrs["date_modified"] = date("Y-m-d H:i:s");
            $this->DB->insert(MENU_TABLE, $attrs);
            $menu_id = $this->DB->insert_id();
        } else {
            $attrs["date_modified"] = date("Y-m-d H:i:s");
            $this->DB->where('id', $menu_id);
            $this->DB->update(MENU_TABLE, $attrs);
        }
        return $menu_id;
    }

    public function validate_menu($menu_id, $data)
    {
        $return = array("errors" => array(), "data" => array());

        if (isset($data["name"])) {
            $return["data"]["name"] = strip_tags($data["name"]);
            if (empty($return["data"]["name"])) {
                $return["errors"][] = l('error_menu_name_invalid', 'menu');
            }
        }

        if (isset($data["gid"])) {
            $return["data"]["gid"] = strip_tags($data["gid"]);
            if (empty($return["data"]["gid"])) {
                $return["errors"][] = l('error_menu_gid_invalid', 'menu');
            }
        }
        if (isset($data["check_permissions"])) {
            $return["data"]["check_permissions"] = intval($data["check_permissions"]);
        }
        return $return;
    }

    /**
     * Delete menu by gid
     * @param string $gid
     * @return boolean
     */
    public function delete_menu_by_gid($gid)
    {
        $results = $this->DB
                        ->select('id')
                        ->from(MENU_TABLE)->where('gid', $gid)
                        ->get()->result_array();
        if (0 === count($results)) {
            return false;
        } else {
            foreach ($results as $result) {
                $this->delete_menu($result['id']);
            }
            return true;
        }
    }

    public function delete_menu($menu_id)
    {
        $this->DB->where('id', $menu_id);
        $this->DB->delete(MENU_TABLE);
        $this->delete_menu_items($menu_id);
        return;
    }

    public function delete_menu_items($menu_id)
    {
        $this->DB->where('menu_id', $menu_id);
        $this->DB->delete(MENU_ITEMS_TABLE);

        $this->CI->pg_language->pages->delete_module("menu_lang_" . $menu_id);
        return;
    }

    // menu items functions
    public function get_menu_items_list($menu_id, $check_permissions = false, $params = array(), $parent_id = 0, $permissions = array())
    {

        $_key = md5($menu_id . $check_permissions . serialize($params) . $parent_id . serialize($permissions));

        if (!isset($this->menu_items_cache[$_key])) {

            $this->DB->select(implode(", ", $this->items_fields_all));
            $this->DB->from(MENU_ITEMS_TABLE);

            $menu_id = intval($menu_id);
            $params["where"]["menu_id"] = $menu_id;

            if (isset($params["where"]) && is_array($params["where"]) && count($params["where"])) {
                foreach ($params["where"] as $field => $value) {
                    $this->DB->where($field, $value);
                }
            }

            if (isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])) {
                foreach ($params["where_in"] as $field => $value) {
                    $this->DB->where_in($field, $value);
                }
            }

            if (isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])) {
                foreach ($params["where_sql"] as $value) {
                    $this->DB->where($value);
                }
            }

            $this->DB->order_by("parent_id ASC");
            $this->DB->order_by("sorter ASC");

            $this->temp_generate_raw_items = $this->temp_generate_raw_menu = array();
            $results = $this->DB->get()->result_array();

            $menu_lang_gid = "menu_lang_" . $menu_id;
            $auth_type = $this->CI->session->userdata("auth_type");

            if (!empty($results) && is_array($results)) {
                $active_parent_id = array();

                foreach ($results as $r) {
                    $r = $this->_parse_link($r);
                    if ($check_permissions && !empty($permissions)) {
                        if (!$this->_is_moderate_access_to_item($r, $permissions)) {
                            continue;
                        }
                    } elseif ($check_permissions && !$this->_is_access_to_item($r, $auth_type)) {
                        continue;
                    }
                    $r["active"] = $this->_is_active_item($r);
                    $r["value"] = $this->CI->pg_language->get_string($menu_lang_gid, "menu_item_" . $r["id"]);
                    $r["tooltip"] = $this->CI->pg_language->get_string($menu_lang_gid, "menu_tooltip_item_" . $r["id"]);

                    if ($r["active"]) {
                        $active_parent_id[] = $r["parent_id"];
                    }
                    $this->temp_generate_raw_items[$r["id"]] = $r;
                }

                if (!empty($active_parent_id)) {
                    $this->_set_active_chain($active_parent_id);
                }

                foreach ($this->temp_generate_raw_items as $r) {
                    if (isset($r["parent_id"])) {
                        $this->temp_generate_raw_menu[$r["parent_id"]][] = $r;
                    }
                }
                $this->menu_raw_items_cache = (!empty($this->menu_raw_items_cache)) ? array_merge($this->menu_raw_items_cache, $this->temp_generate_raw_items) : $this->temp_generate_raw_items;
                $this->menu_items_cache[$_key] = $this->generateMenu($parent_id);
            } else {
                $this->menu_items_cache[$_key] = false;
            }
        }
        return $this->menu_items_cache[$_key];
    }

    public function get_menu_items_count($menu_id, $params = array())
    {
        $this->DB->select("COUNT(*) AS cnt");
        $this->DB->from(MENU_ITEMS_TABLE);

        $params["where"]["menu_id"] = intval($menu_id);

        if (isset($params["where"]) && is_array($params["where"]) && count($params["where"])) {
            foreach ($params["where"] as $field => $value) {
                $this->DB->where($field, $value);
            }
        }

        if (isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])) {
            foreach ($params["where_in"] as $field => $value) {
                $this->DB->where_in($field, $value);
            }
        }

        if (isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])) {
            foreach ($params["where_sql"] as $value) {
                $this->DB->where($value);
            }
        }

        $results = $this->DB->get()->result_array();
        if (!empty($results) && is_array($results)) {
            return intval($results[0]["cnt"]);
        }
        return 0;
    }

    public function get_menu_active_items_list($menu_id, $check_permissions = false, $params = array(), $parent_id = 0, $permissions = array())
    {
        $params["where"]["status"] = 1;
        return $this->get_menu_items_list($menu_id, $check_permissions, $params, $parent_id, $permissions);
    }

    public function get_menu_item_by_id($item_id, $get_langs = false)
    {
        $result = $this->DB->select(implode(", ", $this->items_fields_all))->from(MENU_ITEMS_TABLE)->where("id", $item_id)->get()->result_array();
        if (empty($result)) {
            return false;
        } else {
            $data = $this->_parse_link($result[0]);
            $data["value"] = $this->CI->pg_language->get_string("menu_lang_" . $data["menu_id"], "menu_item_" . $item_id);
            $data["tooltip"] = $this->CI->pg_language->get_string("menu_lang_" . $data["menu_id"], "menu_tooltip_item_" . $item_id);
            if ($get_langs) {
                $data["langs"] = $this->_get_item_string_data($data["menu_id"], $item_id);
            }
            return $data;
        }
    }

    public function get_menu_item_by_gid($gid, $menu_id = null, $get_langs = false)
    {
        $this->DB->select(implode(", ", $this->items_fields_all))->from(MENU_ITEMS_TABLE)->where("gid", $gid);
        if (!empty($menu_id)) {
            $this->DB->where("menu_id", $menu_id);
        }
        $result = $this->DB->get()->result_array();
        if (empty($result)) {
            return false;
        } else {
            $data = $this->_parse_link($result[0]);
            $data["value"] = $this->CI->pg_language->get_string("menu_lang_" . $data["menu_id"], "menu_item_" . $data["id"]);
            $data["tooltip"] = $this->CI->pg_language->get_string("menu_lang_" . $data["menu_id"], "menu_tooltip_item_" . $data["id"]);
            if ($get_langs) {
                $data["langs"] = $this->_get_item_string_data($data["menu_id"], $data["id"]);
            }
            return $data;
        }
    }

    public function save_menu_item($item_id, $data, $lang_data = array(), $lang_tooltip_data = array())
    {
        if (is_null($item_id)) {
            if (!isset($data["status"])) {
                $data["status"] = 1;
            }
            if (!isset($data["sorter"]) && isset($data["menu_id"])) {
                $sorter_params["where"]["parent_id"] = isset($data["parent_id"]) ? $data["parent_id"] : 0;
                $data["sorter"] = $this->get_menu_items_count($data["menu_id"], $sorter_params) + 1;
            }
            $this->DB->insert(MENU_ITEMS_TABLE, $data);
            $item_id = $this->DB->insert_id();
        } else {
            $this->DB->where('id', $item_id);
            $this->DB->update(MENU_ITEMS_TABLE, $data);
        }

        // langs
        if (isset($lang_data) && !empty($lang_data) && isset($data["menu_id"])) {

            $default_lang_id = $this->CI->pg_language->get_default_lang_id();
            $default_value = (isset($lang_data[$default_lang_id])) ? $lang_data[$default_lang_id] : current($lang_data);

            foreach ($this->CI->pg_language->languages as $lang_id => $language) {
                if (!isset($lang_data[$lang_id])) {
                    $lang_data[$lang_id] = $default_value;
                }
            }

            $this->CI->pg_language->pages->set_string_langs("menu_lang_" . $data["menu_id"], "menu_item_" . $item_id, $lang_data, array_keys($this->CI->pg_language->languages));
        }

        // tooltips
        if (isset($lang_tooltip_data) && !empty($lang_tooltip_data) && isset($data["menu_id"])) {

            $default_lang_id = $this->CI->pg_language->get_default_lang_id();
            $default_value = (isset($lang_tooltip_data[$default_lang_id])) ? $lang_tooltip_data[$default_lang_id] : current($lang_data);

            foreach ($this->CI->pg_language->languages as $lang_id => $language) {
                if (!isset($lang_tooltip_data[$lang_id])) {
                    $lang_tooltip_data[$lang_id] = $default_value;
                }
            }

            $this->CI->pg_language->pages->set_string_langs("menu_lang_" . $data["menu_id"], "menu_tooltip_item_" . $item_id, $lang_tooltip_data, array_keys($this->CI->pg_language->languages));
        }

        return $item_id;
    }

    public function save_menu_item_lang($item_id, $menu_id, $lang_data, $lang_tooltip_data = array())
    {
        $this->CI->pg_language->pages->set_string_langs("menu_lang_" . $menu_id, "menu_item_" . $item_id, $lang_data, array_keys($lang_data));
        if (!empty($lang_tooltip_data)) {
            $this->CI->pg_language->pages->set_string_langs("menu_lang_" . $menu_id, "menu_tooltip_item_" . $item_id, $lang_tooltip_data, array_keys($lang_tooltip_data));
        }
    }

    public function validate_menu_item($item_id = null, $data = array())
    {
        $return = array("errors" => array(), "data" => array());

        if (isset($data["menu_id"]) || !$item_id) {
            if (!isset($data["menu_id"]) || empty($data["menu_id"])) {
                $return["errors"][] = l('error_menu_item_menu_required', 'menu');
                $return["data"]["menu_id"] = 0;
            } else {
                $return["data"]["menu_id"] = intval($data["menu_id"]);
            }
        }

        if (!empty($data["link"])) {
            $return["data"]["link"] = strip_tags($data["link"]);
            $return["data"]["link"] = str_replace(site_url(), "", $return["data"]["link"]);
        } else {
            $return["errors"][] = l('error_menu_item_link_required', 'menu');
        }

        if (isset($data["icon"])) {
            $return["data"]["icon"] = strip_tags($data["icon"]);
        }

        if (!empty($data["gid"])) {
            $return["data"]["gid"] = strip_tags($data["gid"]);
        } else {
            $return["errors"][] = l('error_menu_item_gid_required', 'menu');
        }

        if (isset($data["parent_id"])) {
            $return["data"]["parent_id"] = intval($data["parent_id"]);
        }

        if (isset($data["sorter"])) {
            $return["data"]["sorter"] = intval($data["sorter"]);
        }

        if (isset($data["status"])) {
            $return["data"]["status"] = intval($data["status"]);
        }

        if (isset($data["indicator_gid"])) {
            $return["data"]["indicator_gid"] = strip_tags($data["indicator_gid"]);
        }

        return $return;
    }

    public function delete_menu_item($item_id)
    {
        $item_data = $this->get_menu_item_by_id($item_id);
        if (!empty($item_data)) {
            $this->DB->where('id', $item_id);
            $this->DB->delete(MENU_ITEMS_TABLE);
            $this->resort_menu_items($item_data["menu_id"], $item_data["parent_id"]);
            $this->CI->pg_language->pages->delete_string("menu_lang_" . $item_data["menu_id"], "menu_item_" . $item_id);
            $this->CI->pg_language->pages->delete_string("menu_lang_" . $item_data["menu_id"], "menu_tooltip_item_" . $item_id);

            ///// delete sub items
            $results = $this->DB->select("id")->from(MENU_ITEMS_TABLE)->where("parent_id", $item_id)->order_by('sorter ASC')->get()->result_array();
            if (!empty($results)) {
                foreach ($results as $r) {
                    $this->delete_menu_item($r["id"]);
                }
            }
        }
        return;
    }

    public function activate_menu_item($item_id, $status = 1)
    {
        $attrs["status"] = intval($status);
        $this->DB->where('id', $item_id);
        $this->DB->update(MENU_ITEMS_TABLE, $attrs);
    }

    public function resort_menu_items($menu_id, $parent_id = 0)
    {
        $results = $this->DB->select("id, sorter")->from(MENU_ITEMS_TABLE)->where("menu_id", $menu_id)->where("parent_id", $parent_id)->order_by('sorter ASC')->get()->result_array();
        if (!empty($results)) {
            $i = 1;
            foreach ($results as $r) {
                $data["sorter"] = $i;
                $this->DB->where('id', $r["id"]);
                $this->DB->update(MENU_ITEMS_TABLE, $data);
                $i++;
            }
        }
    }

    public function set_menu_active_item($menu_id, $item_id)
    {
        if (!is_numeric($menu_id)) {
            $menu = $this->get_menu_by_gid($menu_id);
            $menu_id = $menu["id"];
        }
        if (!$menu_id) {
            return false;
        }
        if (!is_numeric($item_id)) {
            $item = $this->get_menu_item_by_gid($item_id, $menu_id);
            $item_id = $item["id"];
        }
        if (!$item_id) {
            return false;
        }
        $this->curent_active_item_id[$menu_id] = $item_id;
        return;
    }

    public function _get_item_string_data($menu_id, $item_id, $lang_ids = array(), $type = "value")
    {
        $data = array();
        if (empty($lang_ids)) {
            $lang_ids = array_keys($this->CI->pg_language->languages);
        }
        foreach ($lang_ids as $lang_id) {
            if ($type == "value") {
                $data[$lang_id] = $this->CI->pg_language->get_string("menu_lang_" . $menu_id, "menu_item_" . $item_id, $lang_id);
            } elseif ($type == "tooltip") {
                $data[$lang_id] = $this->CI->pg_language->get_string("menu_lang_" . $menu_id, "menu_tooltip_item_" . $item_id, $lang_id);
            }
        }
        return $data;
    }

    public function _is_moderate_access_to_item($item, $permissions)
    {
        if (!$item["controller"] && !$item["method"]) {
            return true;
        }

        if (!isset($permissions[$item["module"]][$item["method"]]) || $permissions[$item["module"]][$item["method"]] != 1) {
            return false;
        }
        return true;
    }

    public function _is_access_to_item($item, $auth_type = null)
    {
        if (!$item["controller"] && !$item["method"]) {
            return true;
        }
        $access = $this->CI->pg_module->get_module_method_access($item["module"], $item["controller"], $item["method"]);

        if (!$access) {
            return false;
        }

        $auth_type = !empty($auth_type) ? $auth_type : $this->CI->session->userdata("auth_type");

        switch ($auth_type) {
            case "module": $allow = ($access <= 1 || $access == 4) ? true : false;
                break;
            case "admin": $allow = ($access <= 1 || $access == 3) ? true : false;
                break;
            case "user": $allow = ($access <= 2) ? true : false;
                break;
            default: $allow = ($access <= 1) ? true : false;
        }

        if (!$allow) {
            return false;
        }
        return true;
    }

    public function _parse_link($item)
    {
        $item["module"] = $item["controller"] = $item["method"] = $item["link_out"] = $item["link_in"] = "";
        $link = str_replace(site_url(), "", $item["link"]);
        if (substr($link, 0, 1) == "/") {
            $link = substr($link, 1);
        }

        if (preg_match("/^([a-z]{3,5}:\/\/)/i", $link)) {
            $item["link_out"] = $link;
            return $item;
        }

        $params = explode("/", $link);
        if ($params[0] == "admin") {
            $params[1] = !empty($params[1]) ? $params[1] : $this->CI->router->default_controller;
            $item["controller"] = "admin_" . $params[1];
            $item["module"] = $params[1];
            $item["method"] = !empty($params[2]) ? $params[2] : "index";
            $item["params"] = array_slice($params, 3);
            $is_admin = true;
        } else {
            $params[0] = !empty($params[0]) ? $params[0] : $this->CI->router->default_controller;
            $item["controller"] = $item["module"] = $params[0];
            $item["method"] = !empty($params[1]) ? $params[1] : "index";
            $item["params"] = array_slice($params, 2);
            $is_admin = false;
        }
        $item["link_in"] = $item["link"];

        if (!empty($item["module"]) && !empty($item["method"]) && $this->CI->pg_module->get_module_by_gid($item["module"])) {
            $params_str = (!empty($item["params"])) ? implode("|", $item["params"]) : "";
            $this->CI->load->helper("seo");
            $item["link"] = rewrite_link($item["module"], $item["method"], $params_str, $is_admin);
        } else {
            $item["link"] = site_url() . $item["link"];
        }

        return $item;
    }

    public function _is_active_item($item)
    {
        if (!empty($this->curent_active_item_id[$item["menu_id"]])) {
            if ($this->curent_active_item_id[$item["menu_id"]] == $item["id"]) {
                return true;
            }
        } else {
            if (!$item["controller"] && !$item["method"]) {
                return false;
            }
            if ($item["controller"] == $this->router->fetch_class(true) && $item["method"] == $this->router->fetch_method()) {
                return true;
            }
        }

        return false;
    }

    public function _set_active_chain($parent_ids)
    {
        foreach ($parent_ids as $id) {
            $parent_id = $id;
            do {
                $this->temp_generate_raw_items[$parent_id]["in_chain"] = true;
                if (!empty($this->temp_generate_raw_items[$parent_id]["parent_id"])) {
                    $parent_id = $this->temp_generate_raw_items[$parent_id]["parent_id"];
                } else {
                    $parent_id = 0;
                }
            } while ($parent_id > 0);
        }
    }

    private function generateMenu($parent_id)
    {

        if (empty($this->temp_generate_raw_menu) || empty($this->temp_generate_raw_menu[$parent_id])) {
            return array();
        }

        $menu = array();
        foreach ($this->temp_generate_raw_menu[$parent_id] as $subitem) {
            if (isset($this->temp_generate_raw_menu[$subitem['id']]) && !empty($this->temp_generate_raw_menu[$subitem['id']])) {
                $subitem['sub'] = $this->generateMenu($subitem['id']);
            }
            // Indicators
            if (!empty($this->indicators[$subitem['indicator_gid']])) {
                $subitem['indicator'] = $this->indicators[$subitem['indicator_gid']];
            } else {
                $subitem['indicator'] = '';
            }
            $menu[] = $subitem;
        }

        return $menu;
    }

    // breadcrumbs
    public function breadcrumbs_set_parent($item_gid, $profile_section = '')
    {
        $item = $this->get_menu_item_by_gid($item_gid);
        if (empty($item)) {
            return $item;
        }
        if ($profile_section) {
            $profile_section = " > " . $profile_section;
        }
        $this->get_menu_active_items_list($item["menu_id"]);
        $parent_id = $item['id'];
        unset($this->breadcrumbs['chain']);
        do {
            if (!empty($this->menu_raw_items_cache[$parent_id])) {
                $item = $this->menu_raw_items_cache[$parent_id];
                if (!empty($item["link_in"]) && $item["link_in"] != "/") {
                    $breadcrumbs[] = array(
                        "text" => $item["value"] . $profile_section,
                        "url" => $item["link"]
                    );
                }

                $parent_id = $item['parent_id'];
            } else {
                $parent_id = 0;
            }
        } while ($parent_id != 0);

        if (isset($breadcrumbs)) {
            $chain_size = count($breadcrumbs);
            for ($i = $chain_size - 1; $i >= 0; $i--) {
                $this->breadcrumbs['chain'][] = $breadcrumbs[$i];
            }
        }
        return;
    }

    public function breadcrumbs_set_active($text, $url = '')
    {
        // Cut
        if (mb_strlen($text, 'utf-8') > $this->max_length + 3) {
            $text = mb_substr($text, 0, $this->max_length, 'utf-8') . '...';
        }
        $this->breadcrumbs['active'][] = array(
            "text" => $text,
            "url" => $url
        );
        return;
    }

    public function get_breadcrumbs()
    {
        $return = array();

        if (!empty($this->breadcrumbs['chain'])) {
            $return = $this->breadcrumbs['chain'];
        }
        if (!empty($this->breadcrumbs['active'])) {
            foreach ($this->breadcrumbs['active'] as $active) {
                $return[] = $active;
            }
        }
        $size = count($return);
        if (!empty($return)) {
            $return[$size - 1]["url"] = '';
        }
        return $return;
    }

    /**
     * Returns items gids as they are in database
     *
     * @param string $menu_gid
     * @param array $items_gids
     * @return array
     */
    private function getLangGids($menu_gid, $items_gids)
    {
        $menu = $this->get_menu_by_gid($menu_gid);
        $gids['menu'] = 'menu_lang_' . $menu['id'];
        foreach ($items_gids as $item_gid) {
            $menu_item = $this->get_menu_item_by_gid($item_gid);
            $gids['items'][] = 'menu_item_' . $menu_item['id'];
        }
        return $gids;
    }

    /**
     * Returns langs data
     *
     * @param array $menus
     * @param array $langs_ids
     * @return array
     */
    public function export_langs($menus, $langs_ids = null)
    {
        $lang_data = array();
        foreach ($menus as $menu_gid => $menu_items) {
            $gids_db = $this->getLangGids($menu_gid, $menu_items);
            $langs_db = $this->CI->pg_language->export_langs($gids_db['menu'], $gids_db['items'], $langs_ids);
            $lang_codes = array_keys($langs_db);
            foreach ($lang_codes as $lang_code) {
                $lang_data[$lang_code][$menu_gid] = array_combine($menu_items, $langs_db[$lang_code]);
            }
        }
        return $lang_data;
    }

    /**
     * Updates langs data
     *
     * @param array $menus
     * @param array $langs_data
     * @return boolean
     */
    public function update_langs($menus, $langs_data)
    {
        foreach ($menus as $menu_gid => $menu_items) {
            $menu = $this->get_menu_by_gid($menu_gid);
            foreach ($menu_items as $item_gid) {
                $lang_data = $langs_data[$item_gid];
                $menu_item = $this->get_menu_item_by_gid($item_gid);
                $this->CI->pg_language->pages->set_string_langs('menu_lang_' . $menu['id'], 'menu_item_' . $menu_item['id'], $lang_data, array_keys($lang_data));
            }
        }
        return true;
    }

}
