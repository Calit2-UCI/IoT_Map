<?php

namespace Pg\Modules\Dynamic_blocks\Models;

/**
 * Dynamic blocks module
 *
 * @package 	PG_RealEstate
 * @copyright 	Copyright (c) 2000-2014 PG Real Estate - php real estate listing software
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

define('DYNBLOCKS_AREAS_TABLE', DB_PREFIX . 'dynblocks_areas');
define('DYNBLOCKS_AREA_BLOCK_TABLE', DB_PREFIX . 'dynblocks_area_block');
define('DYNBLOCKS_BLOCKS_TABLE', DB_PREFIX . 'dynblocks_blocks');
define('DYNBLOCKS_PRESETS_TABLE', DB_PREFIX . 'dynblocks_presets');
define('DYNBLOCKS_PRESETS_BLOCK_TABLE', DB_PREFIX . 'dynblocks_presets_block');

/**
 * Dynamic blocks main model
 *
 * @subpackage 	Dynamic blocks
 * @category	models
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
class Dynamic_blocks_model extends \Model
{

    /**
     * Link to CodeIgniter object
     * 
     * @var object
     */
    protected $CI;

    /**
     * Link to database object
     * 
     * @var object
     */
    protected $DB;

    /**
     * Properties dynamic block object in data source
     * 
     * @var array
     */
    protected $block_attrs = array(
        'id',
        'gid',
        'module',
        'model',
        'method',
        'min_width',
        'params',
        'views',
        'date_add',
        'date_modified'
    );

    /**
     * Area block object properties in data source
     * 
     * @var array
     */
    protected $fields_area_block = array(
        "id",
        "id_area",
        "id_block",
        "params",
        "view_str",
        "width",
        "sorter",
        "cache_time",
    );

    /**
     * Properties of area object in data source
     * 
     * @param array
     */
    protected $_fields_area = array(
        "id",
        "name",
        "gid",
        "date_add",
    );

    /**
     * Preset object properties in data source
     * 
     * @var array
     */
    protected $fields_preset = array(
        'id',
        'gid',
        'gid_area',
        'name',
        'logo',
        'comment',
        'editable',
        'date_add',
    );

    /**
     * Preset block object properties in data source
     * 
     * @var array
     */
    protected $fields_preset_block = array(
        'id',
        'id_preset',
        'gid_area',
        'id_block',
        'params',
        'view_str',
        'width',
        'sorter',
        'cache_time',
    );

    /**
     * Cache directory of dynamic blocks
     * 
     * @var string
     */
    protected $cache_dir = '';

    /**
     * Module name (GUID)
     * 
     * @param string
     */
    public $module_gid = "dynamic_blocks";

    /**
     * Block types (GUID)
     * 
     * @param string
     */
    public $block_types_gid = "block_types";

    /**
     * Preset upload type (GUID)
     * 
     * @param string
     */
    public $preset_upload_type = "preset-logo";

    /**
     * Constructor
     *
     * @return Dynamic_blocks_model
     */
    public function __construct()
    {
        parent::__construct();
        $this->CI = &get_instance();
        $this->DB = &$this->CI->db;
        $this->cache_dir = TEMPPATH . "dynamic_blocks/";
        $this->DB->memcache_tables(array(
            DYNBLOCKS_AREAS_TABLE,
            DYNBLOCKS_AREA_BLOCK_TABLE,
            DYNBLOCKS_BLOCKS_TABLE));
    }

    /**
     * Return area object by identifier
     * 
     * @param integer $id area identifier
     * @return array
     */
    public function get_area_by_id($id)
    {
        $data = array();
        $this->DB->select(implode(",", $this->_fields_area));
        $this->DB->from(DYNBLOCKS_AREAS_TABLE);
        $this->DB->where("id", $id);
        $result = $this->DB->get()->result_array();
        if (!empty($result)) {
            $areas = $this->format_area(array($result[0]));
            $data = array_shift($areas);
        }
        return $data;
    }

    /**
     * Return area object by guid
     * 
     * @param string $gid area guid
     * @return array
     */
    public function get_area_by_gid($gid)
    {
        $data = array();
        $this->DB->select(implode(",", $this->_fields_area));
        $this->DB->from(DYNBLOCKS_AREAS_TABLE);
        $this->DB->where("gid", $gid);
        $result = $this->DB->get()->result_array();
        if (!empty($result)) {
            $data = current($this->format_area(array($result[0])));
        }
        return $data;
    }

    /**
     * Save area object to data source
     * 
     * @param integer $id area identifier
     * @param array $data area data
     * @return integer
     */
    public function save_area($id, $data)
    {
        if (empty($id)) {
            $data["date_add"] = date("Y-m-d H:i:s");
            $this->DB->insert(DYNBLOCKS_AREAS_TABLE, $data);
            $id = $this->DB->insert_id();
        } else {
            $this->DB->where('id', $id);
            $this->DB->update(DYNBLOCKS_AREAS_TABLE, $data);
        }
        return $id;
    }

    /**
     * Validate area object for saving to data source
     * 
     * @param integer $id area identifier
     * @param array $data area data
     * @return array
     */
    public function validate_area($id, $data)
    {
        $return = array("errors" => array(), "data" => array());

        if (isset($data["name"])) {
            $return["data"]["name"] = strip_tags($data["name"]);
            if (empty($return["data"]["name"])) {
                $return["errors"][] = l('error_name_mandatory_field', 'dynamic_blocks');
            }
        }

        if (isset($data["gid"])) {
            $return["data"]["gid"] = trim(strip_tags($data["gid"]));
            $return["data"]["gid"] = preg_replace('/[^a-z\-_0-9]+/i', '', $return["data"]["gid"]);
            $return["data"]["gid"] = preg_replace('/[\s\n\t\r]+/', '-', $return["data"]["gid"]);
            $return["data"]["gid"] = preg_replace('/\-{2,}/', '-', $return["data"]["gid"]);

            if (empty($return["data"]["gid"])) {
                $return["errors"][] = l('error_gid_mandatory_field', 'dynamic_blocks');
            } else {
                $this->DB->select('COUNT(*) AS cnt')->from(DYNBLOCKS_AREAS_TABLE)->where("gid", $return["data"]["gid"]);
                if (!empty($id)) {
                    $this->DB->where("id <>", $id);
                }
                $result = $this->DB->get()->result_array();
                if (!empty($result) && $result[0]["cnt"] > 0) {
                    $return["errors"][] = l('error_area_already_exists', 'dynamic_blocks');
                }
            }
        }

        return $return;
    }

    /**
     * Format area data
     * 
     * @param array $data area data
     * @return array
     */
    public function format_area($data)
    {
        return $data;
    }

    /**
     * Remove area data by identifier
     * 
     * @param integer $id area identifier
     * @return void
     */
    public function delete_area($id)
    {
        $this->DB->where("id", $id);
        $this->DB->delete(DYNBLOCKS_AREAS_TABLE);

        $this->DB->where("id_area", $id);
        $this->DB->delete(DYNBLOCKS_AREA_BLOCK_TABLE);
        return;
    }

    /**
     * Remove area data by guid
     * 
     * @param string $gid area guid
     * @return void
     */
    public function delete_area_by_gid($gid)
    {
        $area_data = $this->get_area_by_gid($gid);
        if (empty($area_data)) {
            return false;
        }

        $this->delete_area($area_data["id"]);
    }

    /**
     * Return areas' objects as array
     * 
     * @param integer $page page of results
     * @param integer $items_on_page results per page
     * @param array $order_by sorting data
     * @param array $params filters data
     * @param array $filter_object_ids filters identifiers
     * @return array
     */
    public function get_areas_list($page = null, $items_on_page = null, $order_by = null, $params = array(), $filter_object_ids = null)
    {
        $this->DB->select(implode(',', $this->_fields_area))->from(DYNBLOCKS_AREAS_TABLE);

        if (isset($params["where"]) && is_array($params["where"]) && count($params["where"])) {
            foreach ($params["where"] as $field => $value)
                $this->DB->where($field, $value);
        }

        if (isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])) {
            foreach ($params["where_in"] as $field => $value)
                $this->DB->where_in($field, $value);
        }

        if (isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])) {
            foreach ($params["where_sql"] as $value)
                $this->DB->where($value);
        }

        if (isset($filter_object_ids) && is_array($filter_object_ids) && count($filter_object_ids)) {
            $this->DB->where_in("id", $filter_object_ids);
        }

        if (is_array($order_by) && count($order_by) > 0) {
            foreach ($order_by as $field => $dir) {
                if (in_array($field, $this->_fields_area)) {
                    $this->DB->order_by($field . " " . $dir);
                }
            }
        }

        if (!is_null($page)) {
            $page = intval($page) ? intval($page) : 1;
            $this->DB->limit($items_on_page, $items_on_page * ($page - 1));
        }

        $data = array();
        $results = $this->DB->get()->result_array();
        if (!empty($results) && is_array($results)) {
            $data = $results;
        }
        return $data;
    }

    /**
     * Return number of areas
     * 
     * @param array $params filters data
     * @param array $filter_object_ids filters identifiers
     * @return integer
     */
    public function get_areas_count($params = array(), $filter_object_ids = null)
    {
        $this->DB->select('COUNT(*) AS cnt')->from(DYNBLOCKS_AREAS_TABLE);

        if (isset($params["where"]) && is_array($params["where"]) && count($params["where"])) {
            foreach ($params["where"] as $field => $value)
                $this->DB->where($field, $value);
        }

        if (isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])) {
            foreach ($params["where_in"] as $field => $value)
                $this->DB->where_in($field, $value);
        }

        if (isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])) {
            foreach ($params["where_sql"] as $value)
                $this->DB->where($value);
        }

        if (isset($filter_object_ids) && is_array($filter_object_ids) && count($filter_object_ids)) {
            $this->DB->where_in("id", $filter_object_ids);
        }

        $results = $this->DB->get()->result_array();
        if (!empty($results) && is_array($results)) {
            return intval($results[0]["cnt"]);
        }
        return 0;
    }

    /**
     * Return area block object by identifier
     * 
     * @param integer $id area block identifier
     * @return array
     */
    public function get_area_block_by_id($id)
    {
        $data = array();
        $this->DB->select(implode(",", $this->fields_area_block))->from(DYNBLOCKS_AREA_BLOCK_TABLE)->where("id", $id);
        $results = $this->DB->get()->result_array();
        if (!empty($results) && is_array($results)) {
            $results = $this->format_area_block(array($results[0]));
            $data = $results[0];
        }
        return $data;
    }

    /**
     * Return area blocks as array
     * 
     * @param integer $id_area area identifier
     * @return array
     */
    public function get_area_blocks($id_area)
    {
        $data = array();
        $this->DB->select(implode(",", $this->fields_area_block))->from(DYNBLOCKS_AREA_BLOCK_TABLE)->where("id_area", $id_area)->order_by("sorter ASC");
        $results = $this->DB->get()->result_array();
        if (!empty($results) && is_array($results)) {
            $data = $this->format_area_block($results);
        }
        return $data;
    }

    /**
     * Return number of area blocks
     * 
     * @param integer $id_area area idnetifier
     * @return integer
     */
    public function get_area_blocks_count($id_area)
    {
        $this->DB->select('COUNT(*) AS cnt')->from(DYNBLOCKS_AREA_BLOCK_TABLE)->where("id_area", $id_area);
        $results = $this->DB->get()->result_array();
        if (!empty($results) && is_array($results)) {
            return intval($results[0]["cnt"]);
        }
        return 0;
    }

    /**
     * Save area block object to data source
     * 
     * @param integer $id area block identifier
     * @param array $data area block data
     * @return integer
     */
    public function save_area_block($id, $data)
    {
        if (empty($id)) {
            if (empty($data["width"])) {
                $data["width"] = 100;
            }
            $this->DB->insert(DYNBLOCKS_AREA_BLOCK_TABLE, $data);
            $id = $this->DB->insert_id();
            if (!isset($data['sorter'])) {
                $this->refresh_area_block_sorter($data["id_area"]);
            }
        } else {
            $this->DB->where('id', $id);
            $this->DB->update(DYNBLOCKS_AREA_BLOCK_TABLE, $data);
        }
        return $id;
    }

    /**
     * Set sorting value of area block
     * 
     * @param integer $id area block identifier
     * @param integer $sorter sorting value
     * @param integer $width width value
     * @return void
     */
    public function save_area_block_sorter($id, $sorter, $width = 0)
    {
        $data["sorter"] = $sorter;
        if ($width) {
            $data["width"] = $width;
        }
        $this->DB->where('id', $id);
        $this->DB->update(DYNBLOCKS_AREA_BLOCK_TABLE, $data);
        return;
    }

    /**
     * Remove area block data
     * 
     * @param integer $id area block identifier
     * @return void
     */
    public function delete_area_block($id)
    {
        $area_block_data = $this->get_area_block_by_id($id);

        $this->DB->where('id', $id);
        $this->DB->delete(DYNBLOCKS_AREA_BLOCK_TABLE);

        $this->CI->load->model('Start_model');
        $this->CI->Start_model->clear_wysiwyg_uploads_dir('dynamic_blocks', $id);
        $this->refresh_area_block_sorter($area_block_data["id_area"]);
    }

    /**
     * Validate data of area block
     * @param array $data 
     * @param boolean $required
     * @return array
     */
    public function validate_area_block($data, $required = false)
    {
        $return = array("errors" => array(), "data" => array());
        foreach ($this->fields_area_block as $field) {
            if (isset($data[$field])) {
                $return["data"][$field] = $data[$field];
            }
        }
        return $return;
    }

    /**
     * Format area block data
     * 
     * @param array $data area block data
     * @return array
     */
    public function format_area_block($data)
    {
        foreach ($data as $i => $block) {
            $data[$i]["params"] = $block["params"] ? unserialize($block["params"]) : array();
            $data[$i]["width"] = $block["width"] ? $block["width"] : 100;
        }
        return $data;
    }

    /**
     * Resort area blocks
     * 
     * @param integer $id_area area identifier
     * @return void
     */
    public function refresh_area_block_sorter($id_area)
    {
        $blocks = $this->get_area_blocks($id_area);
        if (empty($blocks)) {
            return false;
        }

        $sorter = 0;
        $prev_block = null;
        $row_width = 0;
        foreach ($blocks as $block) {
            $row_width += $block['width'];
            if ($row_width > 100) {
                $prev_block["width"] += 100 - ($row_width - $block["width"]);
                $this->save_area_block_sorter($prev_block["id"], $sorter, $prev_block['width']);
                $row_width = $block["width"];
            } elseif ($row_width == 100) {
                $row_width = 0;
            }
            $prev_block = $block;
            $this->save_area_block_sorter($block["id"], ++$sorter, $block['width']);
        }

        if ($row_width) {
            $prev_block["width"] += 100 - $row_width;
            $this->save_area_block_sorter($prev_block["id"], $sorter, $prev_block['width']);
        }
    }

    /**
     * Return block object by identifier
     * 
     * @param integer $id block identifier
     * @return array
     */
    public function get_block_by_id($id)
    {
        $data = array();
        $this->DB->select(implode(', ', $this->block_attrs))->from(DYNBLOCKS_BLOCKS_TABLE);
        if (is_array($id)) {
            $this->DB->where_in("id", $id);
        } else {
            $this->DB->where("id", $id);
        }
        $result = $this->DB->get()->result_array();
        if (!empty($result)) {
            $data = $result[0];
        }
        return $data;
    }

    /**
     * Return block object by guid
     * 
     * @param string $gid block guid
     * @return array
     */
    public function get_block_by_gid($gid)
    {
        $data = array();
        $result = $this->DB->select(implode(', ', $this->block_attrs))->from(DYNBLOCKS_BLOCKS_TABLE)->where("gid", $gid)->get()->result_array();
        if (!empty($result)) {
            $data = $result[0];
        }
        return $data;
    }

    /**
     * Save block object to data source
     * 
     * @param integer $id block identifier
     * @param array $data block data
     * @param array $langs languages data
     * @return integer
     */
    public function save_block($id, $data, $langs = null)
    {
        if (is_null($id)) {
            $data["date_add"] = $data["date_modified"] = date("Y-m-d H:i:s");
            $this->DB->insert(DYNBLOCKS_BLOCKS_TABLE, $data);
            $id = $this->DB->insert_id();
        } else {
            $data["date_add"] = date("Y-m-d H:i:s");
            $this->DB->where('id', $id);
            $this->DB->update(DYNBLOCKS_BLOCKS_TABLE, $data);
        }

        if (!empty($langs)) {
            $languages = $this->CI->pg_language->languages;
            if (!empty($languages)) {
                foreach ($languages as $language) {
                    $lang_ids[] = $language["id"];
                }
                foreach ($langs as $gid => $strings_data) {
                    foreach ($strings_data as $string => $lang_data) {
                        $this->CI->pg_language->pages->set_string_langs($gid, $string, $lang_data, $lang_ids);
                    }
                }
            }
        }
        return $id;
    }

    /**
     * Validate block object for saving to data source
     * 
     * @param integer $id block identifier
     * @param array $data block data
     * @param array $langs languages data
     * @return array
     */
    public function validate_block($id, $data, $langs = null)
    {
        $return = array("errors" => array(), "data" => array(), 'temp' => array(), 'langs' => array());

        if (isset($data["gid"])) {
            $return["data"]["gid"] = strtolower(preg_replace('/[\s\n\r_]{1,}/', '_', trim(preg_replace('/[^a-z_0-9]/i', '_', strip_tags($data["gid"])))));
            if (empty($return["data"]["gid"])) {
                $return["errors"][] = l('error_gid_empty', 'dynamic_blocks');
            }
        }

        if (isset($data["module"])) {
            $return["data"]["module"] = strtolower(preg_replace("/[\s\n\r]{1,}/", '_', $data["module"]));
        }
        if (isset($data["model"])) {
            $return["data"]["model"] = ucfirst(preg_replace("/[\s\n\r]{1,}/", '_', $data["model"]));
        }
        if (isset($data["method"])) {
            $return["data"]["method"] = preg_replace("/[\s\n\r]{1,}/", '_', $data["method"]);
        }

        if (empty($id) && ( empty($return["data"]["module"]) || empty($return["data"]["model"]) || empty($return["data"]["method"]) )) {
            $return["errors"][] = l('error_function_empty', 'dynamic_blocks');
        }

        if (!(empty($return["data"]["module"]) || empty($return["data"]["model"]) || empty($return["data"]["method"]) )) {
            $result = $this->is_method_callable($return["data"]["module"], $return["data"]["model"], $return["data"]["method"]);
            if (!$result) {
                $return["errors"][] = l('error_function_invalid', 'dynamic_blocks');
            }
        }

        if (isset($data["min_width"]) && !empty($data["min_width"])) {
            $return["data"]["min_width"] = $data['min_width'] ? $data['min_width'] : '30';
        }

        if (isset($data["params"]) && !empty($data["params"])) {
            foreach ($data["params"] as $key => $param) {
                $gid = strtolower(preg_replace('/[\s\n\r_]{1,}/', '_', trim(preg_replace('/[^a-z_0-9]/i', '_', strip_tags($param["gid"])))));
                if (!empty($gid)) {
                    $return["data"]["params"][$gid]["type"] = $param["type"] ? $param['type'] : 'text';
                    $return["data"]["params"][$gid]["default"] = trim($param["default"]);
                    $return["temp"]["params"][$key] = $gid;
                }
            }
            $return["data"]["params"] = serialize($return["data"]["params"]);
        }

        if (isset($data["views"]) && !empty($data["views"])) {
            foreach ($data["views"] as $key => $view) {
                $gid = strtolower(preg_replace('/[\s\n\r_]{1,}/', '_', trim(preg_replace('/[^a-z_0-9]/i', '_', strip_tags($view["gid"])))));
                if (!empty($gid)) {
                    $return["data"]["views"][] = $gid;
                    $return["temp"]["views"][$key] = $gid;
                }
            }
            $return["data"]["views"] = serialize($return["data"]["views"]);
        }

        if (!empty($langs)) {
            $languages = $this->CI->pg_language->languages;
            $current_lang_id = $this->CI->pg_language->current_lang_id;
            $lang_gid = $return["data"]["module"] . "_dynamic_blocks";

            if (isset($langs["name"])) {
                $lang_i = "block_" . $return["data"]["gid"];
                foreach ($languages as $language) {
                    $str = (!empty($langs["name"][$language["id"]])) ? $langs["name"][$language["id"]] : $langs["name"][$current_lang_id];
                    $str = trim(strip_tags($str));

                    $return["langs"][$lang_gid][$lang_i][$language["id"]] = $str;
                }
            }

            if (isset($langs["params"])) {
                foreach ($langs["params"] AS $key => $param) {
                    if (empty($return["temp"]["params"][$key])) {
                        continue;
                    }
                    $lang_i = 'param_' . $return["data"]["gid"] . '_' . $return["temp"]["params"][$key];
                    foreach ($languages as $language) {
                        $str = (!empty($langs["params"][$key][$language["id"]])) ? $langs["params"][$key][$language["id"]] : $langs["params"][$key][$current_lang_id];
                        $str = trim(strip_tags($str));

                        $return["langs"][$lang_gid][$lang_i][$language["id"]] = $str;
                    }
                }
            }

            if (isset($langs["views"])) {
                foreach ($langs["views"] AS $key => $param) {
                    if (empty($return["temp"]["views"][$key])) {
                        continue;
                    }
                    $lang_i = 'view_' . $return["data"]["gid"] . '_' . $return["temp"]["views"][$key];
                    foreach ($languages as $language) {
                        $str = (!empty($langs["views"][$key][$language["id"]])) ? $langs["views"][$key][$language["id"]] : $langs["views"][$key][$current_lang_id];
                        $str = trim(strip_tags($str));

                        $return["langs"][$lang_gid][$lang_i][$language["id"]] = $str;
                    }
                }
            }
        }
        return $return;
    }

    /**
     * Format block data
     * 
     * @param array $data block data
     * @param boolean $get_langs with languages data
     * @return array
     */
    public function format_block($data, $get_langs = false)
    {
        $data["params"] = $data["params"] ? (array) unserialize($data["params"]) : array();
        $data["views"] = $data["views"] ? (array) unserialize($data["views"]) : array();

        $data["lang_gid"] = $data["module"] . "_dynamic_blocks";
        $data["name_i"] = "block_" . $data["gid"];
        $data["name"] = ($get_langs) ? (l($data["name_i"], $data["lang_gid"])) : "";

        if (!empty($data["params"])) {
            foreach ($data["params"] as $param_gid => $param_data) {
                $param_i = 'param_' . $data["gid"] . '_' . $param_gid;
                $data["params_data"][] = array(
                    "gid" => $param_gid,
                    "type" => isset($param_data["type"]) ? $param_data["type"] : '',
                    "default" => isset($param_data["default"]) ? $param_data["default"] : '',
                    "i" => $param_i,
                    "name" => ($get_langs) ? (l($param_i, $data["lang_gid"])) : ""
                );
            }
        }

        if (!empty($data["views"])) {
            foreach ($data["views"] as $view_gid) {
                $param_i = 'view_' . $data["gid"] . '_' . $view_gid;
                $data["views_data"][] = array(
                    "gid" => $view_gid,
                    "i" => $param_i,
                    "name" => ($get_langs) ? (l($param_i, $data["lang_gid"])) : ""
                );
            }
        }

        return $data;
    }

    /**
     * Remove block object by identifier
     * 
     * @param integer $id block identifier
     * @return void
     */
    public function delete_block($id)
    {
        $block_data = $this->get_block_by_id($id);
        if (empty($block_data)) {
            return false;
        }

        $block_data = $this->format_block($block_data);

        $this->DB->where("id", $id);
        $this->DB->delete(DYNBLOCKS_BLOCKS_TABLE);

        $this->DB->where("id_block", $id);
        $this->DB->delete(DYNBLOCKS_AREA_BLOCK_TABLE);

        $this->CI->pg_language->pages->delete_string($block_data['lang_gid'], $block_data['name_i']);
        if (!empty($block_data["params_data"])) {
            foreach ($block_data["params_data"] as $param) {
                $this->CI->pg_language->pages->delete_string($block_data['lang_gid'], $param['i']);
            }
        }
        if (!empty($block_data["views_data"])) {
            foreach ($block_data["views_data"] as $view) {
                $this->CI->pg_language->pages->delete_string($block_data['lang_gid'], $view['i']);
            }
        }
        return;
    }

    /**
     * Remove block object by guid
     * 
     * @param string $gid block guid
     * @return void
     */
    public function delete_block_by_gid($gid)
    {
        $block_data = $this->get_block_by_gid($gid);
        return $this->delete_block($block_data["id"]);
    }

    /**
     * Return blocks objects as array
     * 
     * @param integer $page page of results
     * @param integer $items_on_page results per page
     * @param array $order_by sorting data
     * @param array $params filters data
     * @param array $filter_object_ids filters identifiers
     * @return array
     */
    public function get_blocks_list($page = null, $items_on_page = null, $order_by = null, $params = array(), $filter_object_ids = null)
    {
        $this->DB->select(implode(', ', $this->block_attrs))->from(DYNBLOCKS_BLOCKS_TABLE);

        if (isset($params["where"]) && is_array($params["where"]) && count($params["where"])) {
            foreach ($params["where"] as $field => $value)
                $this->DB->where($field, $value);
        }

        if (isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])) {
            foreach ($params["where_in"] as $field => $value)
                $this->DB->where_in($field, $value);
        }

        if (isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])) {
            foreach ($params["where_sql"] as $value)
                $this->DB->where($value);
        }

        if (isset($filter_object_ids) && is_array($filter_object_ids) && count($filter_object_ids)) {
            $this->DB->where_in("id", $filter_object_ids);
        }

        if (is_array($order_by) && count($order_by) > 0) {
            foreach ($order_by as $field => $dir) {
                if (in_array($field, $this->block_attrs)) {
                    $this->DB->order_by($field . " " . $dir);
                }
            }
        }

        if (!is_null($page)) {
            $page = intval($page) ? intval($page) : 1;
            $this->DB->limit($items_on_page, $items_on_page * ($page - 1));
        }

        $data = array();
        $results = $this->DB->get()->result_array();
        if (!empty($results) && is_array($results)) {
            foreach ($results as $result) {
                $data[] = $this->format_block($result);
            }
        }
        return $data;
    }

    /**
     * Return blocks objects as asscoiated array (<id> => <data>)
     * 
     * @param integer $page page of results
     * @param integer $items_on_page results per page
     * @param array $order_by sorting data
     * @param array $params filters data
     * @param array $filter_object_ids filters identifiers
     * @return array
     */
    public function get_blocks_list_by_id($page = null, $items_on_page = null, $order_by = null, $params = array(), $filter_object_ids = null)
    {
        $return_blocks = array();
        $blocks = $this->get_blocks_list($page, $items_on_page, $order_by, $params, $filter_object_ids);
        if (!empty($blocks)) {
            foreach ($blocks as $block) {
                $return_blocks[$block["id"]] = $block;
            }
        }
        return $return_blocks;
    }

    /**
     * Return number of dynamic blocks
     * 
     * @param array $params filters data
     * @param array $filter_object_ids filters identifiers
     * @return integer
     */
    public function get_blocks_count($params = array(), $filter_object_ids = null)
    {
        $this->DB->select("COUNT(*) AS cnt")->from(DYNBLOCKS_BLOCKS_TABLE);

        if (isset($params["where"]) && is_array($params["where"]) && count($params["where"])) {
            foreach ($params["where"] as $field => $value)
                $this->DB->where($field, $value);
        }

        if (isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])) {
            foreach ($params["where_in"] as $field => $value)
                $this->DB->where_in($field, $value);
        }

        if (isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])) {
            foreach ($params["where_sql"] as $value)
                $this->DB->where($value);
        }

        if (isset($filter_object_ids) && is_array($filter_object_ids) && count($filter_object_ids)) {
            $this->DB->where_in("id", $filter_object_ids);
        }

        $results = $this->DB->get()->result_array();
        if (!empty($results) && is_array($results)) {
            return intval($results[0]["cnt"]);
        }
        return 0;
    }

    /**
     * Check method is callable
     * 
     * @param string $module module name
     * @param string $model model name
     * @param string $method method name
     * @return boolean
     */
    protected function is_method_callable($module, $model, $method)
    {
        $result = false;

        $model_url = $module . "/models/" . $model;
        $model_path = MODULEPATH . strtolower($model_url) . EXT;

        if (file_exists($model_path)) {
            $this->CI->load->model($model_url);
            $object = array($this->CI->$model, $method);
            $result = is_callable($object);
        }

        return $result;
    }

    /**
     * Return file name of content cache 
     * 
     * @param integer $id_area_block area block identifier
     * @param integer $lang_id language idnetifier
     * @return string
     */
    protected function cache_get_file_name($id_area_block, $lang_id = null)
    {
        if (!$lang_id) {
            $lang_id = $this->CI->pg_language->current_lang_id;
        }
        return $this->cache_dir . "block_content_" . $id_area_block . "_" . $lang_id . ".txt";
    }

    /**
     * Return content from cache
     * 
     * @param string $file_path file path
     * @param integer $cache_time cache time
     * @return string/false
     */
    protected function cache_get_content($file_path, $cache_time = 0)
    {
        if (!$cache_time) {
            return false;
        }

        if (!file_exists($file_path)) {
            return false;
        }
        clearstatcache();
        $stat = stat($file_path);
        $mod_time = $stat["mtime"];

        if (time() < $mod_time + $cache_time) {
            return file_get_contents($file_path);
        }

        return false;
    }

    /**
     * Write content to cache
     * 
     * @param string $file_path file path
     * @param string $content block content
     * @return void
     */
    protected function cache_set_content($file_path, $content)
    {
        $h = fopen($file_path, 'w');
        if ($h) {
            fwrite($h, $content);
            fclose($h);
        }
        return;
    }

    /**
     * Clear overall cache
     * 
     * @return void
     */
    public function cache_clear()
    {
        foreach (new \DirectoryIterator($this->cache_dir) as $fileInfo) {
            if ($fileInfo->isDot() || 'txt' !== pathinfo($fileInfo->getFilename(), PATHINFO_EXTENSION)) {
                continue;
            }
            unlink($fileInfo->getRealPath());
        }
    }

    /**
     * Clear area cache
     * 
     * @param integer $area_id area identifier
     * @return void
     */
    public function cache_area_clear($area_id)
    {
        $languages = $this->CI->pg_language->languages;
        $blocks = $this->get_area_blocks($area_id);
        foreach ($blocks as $block) {
            foreach ($languages as $lang) {
                $filename = $this->cache_get_file_name($block['id'], $lang['id']);
                if (file_exists($filename)) {
                    unlink($filename);
                }
            }
        }
    }

    /**
     * Clear all cache
     * 
     * @return void
     */
    public function cache_all_clear()
    {
        $languages = $this->CI->pg_language->languages;

        $cache = scandir($this->cache_dir);
        foreach ($cache as $file) {
            if ($file == '.' || $file == '..' || $file == 'index.html') {
                continue;
            }
            unlink($this->cache_dir . $file);
        }
    }

    /**
     * Return content of dynamic blocks area by identifier
     * 
     * @param integer $id_area area identifier
     * @return string
     */
    public function html_area_blocks($id_area)
    {

        $area = $this->get_area_by_id($id_area);

        $area_blocks = $this->get_area_blocks($id_area);
        if (empty($area_blocks)) {
            return "";
        }

        $html = "";

        foreach ($area_blocks as $block) {
            $block_ids[] = $block["id_block"];
        }

        $blocks = $this->get_blocks_list_by_id(null, null, null, array(), $block_ids);

        $layout = array();
        $row = array();
        $row_width = 0;

        foreach ($area_blocks as $index => $block) {
            $row_width += $block["width"];
            $row[] = $block;
            if ($row_width < 100) {
                continue;
            }

            if ($row_width > 100) {
                array_pop($row);
                $count = count($row);
                $row[$count - 1]["width"] += 100 - ($row_width - $block["width"]);
                $layout[] = $row;
                $row = array($block);
                $row_width = $block["width"];
            } else {
                $layout[] = $row;
                $row = array();
                $row_width = 0;
            }
        }

        if ($row_width) {
            $count = count($row);
            $row[$count - 1]["width"] += 100 - $row_width;
            $layout[] = $row;
        }

        foreach ($layout as $row => $layout_blocks) {
            $block_html = '';
            $first = false;
            $empty = true;

            foreach ($layout_blocks as $key => $area_block) {
                $temp_content = false;
                if ($area_block["cache_time"]) {
                    $file_name = $this->cache_get_file_name($area_block["id"]);
                    $temp_content = $this->cache_get_content($file_name, $area_block["cache_time"]);
                }

                if (!$temp_content) {
                    $module = $blocks[$area_block["id_block"]]['module'];
                    $model = $blocks[$area_block["id_block"]]['model'];
                    $method = $blocks[$area_block["id_block"]]['method'];
                    $model_url = $module . "/models/" . $model;
                    $this->CI->load->model($model_url);
                    $temp_content = $this->CI->$model->$method($area_block["params"], $area_block["view_str"], $area_block['width']);

                    if ($area_block["cache_time"]) {
                        $this->cache_set_content($file_name, $temp_content);
                    }
                }


                $class = "dynamic_block_content";

                $w = ($area_block["width"] != 40 ? $area_block["width"] : 30);

                $class .= ($area_block["width"] < 100) ? ' col' . $w : ' col100';

                if (!$key) {
                    $class .= " first";
                    $first = true;
                } else if ($area_block["width"] < 100 && $w == 30 && $first == true) {
                    $class .= " middle";
                    $first = false;
                }

                if ($temp_content) {
                    $empty = false;
                }

                $block_html .= '<div class="' . $class . '" data-gid="' . $blocks[$area_block['id_block']]['gid'] . '">' . $temp_content . '</div>';
            }
            if (!$block_html) {
                continue;
            }
            $html .= '<div class="dynamic_block_row' . ($empty ? ' empty' : '') . '">' . $block_html . '</div><div class="clr"></div>';
        }

        return $html;
    }

    /**
     * Return content of dynamic blocks area by guid
     * 
     * @param string $gid_area area guid
     * @return string
     */
    public function html_area_blocks_by_gid($gid_area)
    {
        $area = $this->get_area_by_gid($gid_area);
        return $this->html_area_blocks($area["id"]);
    }

    /**
     * Update langs
     * @param array $module_blocks
     * @param array|null $langs_ids
     * @return boolean
     */
    public function updateLangsByModuleBlocks(array $module_blocks, $langs_ids = null)
    {
        $installed_blocks = $this->checkBlocksInstalled($module_blocks);
        if (empty($installed_blocks)) {
            return false;
        }
        $module = $installed_blocks[array_shift(array_keys($installed_blocks))]['module'];
        $this->CI->load->model('Install_model');
        $langs_file = $this->CI->Install_model->language_file_read($module, 'dynamic_blocks', $langs_ids);
        if (!$langs_file) {
            log_message('info', 'Empty dynamic_blocks langs data (' . $module . ')');
            return false;
        }

        $this->updateLangs($installed_blocks, $langs_file, $langs_ids);
        return true;
    }

    /**
     * Import languages data to dynamic blocks
     * 
     * @param array $blocks languages data
     * @param array $langs_file languages data
     * @param array $langs_ids languages identifiers
     * @return void
     */
    private function updateLangs($blocks, $langs_file, $langs_ids)
    {
        $module = $blocks[array_shift(array_keys($blocks))]['module'];
        $langs_module = $module . '_dynamic_blocks';
        foreach ($blocks as $block) {
            $lang_gid = 'block_' . $block['gid'];
            $this->CI->pg_language->pages->set_string_langs($langs_module, $lang_gid, $langs_file[$lang_gid], (array) $langs_ids);
            if (!empty($block['views']) && is_array($views = unserialize($block['views']))) {
                foreach ($views as $value) {
                    $lang_gid = 'view_' . $block['gid'] . '_' . $value;
                    $this->CI->pg_language->pages->set_string_langs($langs_module, $lang_gid, $langs_file[$lang_gid], (array) $langs_ids);
                }
            }
            if (!empty($block['params']) && is_array($params = unserialize($block['params']))) {
                foreach ($params as $key => $value) {
                    $lang_gid = 'param_' . $block['gid'] . '_' . $key;
                    $this->CI->pg_language->pages->set_string_langs($langs_module, $lang_gid, $langs_file[$lang_gid], (array) $langs_ids);
                }
            }
        }
    }

    /**
     * Export languages from dynamic blocks
     * 
     * @param array $data languages data
     * @param array $langs_ids languages identifiers
     * @return array
     */
    public function export_langs($data, $langs_ids = null)
    {
        $installed_blocks = $this->checkBlocksInstalled($data);
        if (empty($installed_blocks)) {
            return false;
        }
        $gids = array();
        $module = $installed_blocks[0]['module'] . '_dynamic_blocks';
        foreach ($installed_blocks as $block) {
            $gids[] = 'block_' . $block['gid'];
            if (!empty($block['views'])) {
                $views = unserialize($block['views']);
                if (is_array($views)) {
                    foreach ($views as $value) {
                        $gids[] = 'view_' . $block['gid'] . '_' . $value;
                    }
                }
            }
            if (!empty($block['params'])) {
                $params = unserialize($block['params']);
                if (is_array($params)) {
                    foreach ($params as $key => $value) {
                        $gids[] = 'param_' . $block['gid'] . '_' . $key;
                    }
                }
            }
        }
        return $this->CI->pg_language->export_langs($module, $gids, $langs_ids);
    }

    /**
     * Return content of html dynamic block
     * 
     * Dynamic block callback method
     * 
     * @param array $params block parametes
     * @param string $view block view 
     * @param integer $width block size
     * @return string
     */
    public function _dynamic_block_get_html_code($params, $view = 'default', $width = 100)
    {
        if (!$params['html']) {
            return '';
        }
        $this->CI->template_lite->assign('block_title', $params['title_' . $this->pg_language->current_lang_id]);
        $this->CI->template_lite->assign('block_html', $params['html']);
        return $this->CI->template_lite->fetch("helper_html_block", "user", "dynamic_blocks");
    }

    /**
     * Return dynamic blocks presets as array
     * 
     * @param integer $page page of results
     * @param integer $items_on_page results per page
     * @param array $order_by sorting data
     * @param array $params sql criteria
     * @param array $filter_object_ids filter idnetifiers
     * @return array
     */
    public function get_presets_list($page = null, $items_on_page = null, $order_by = null, $params = array(), $filter_object_ids = null)
    {
        $this->DB->select(implode(',', $this->fields_preset))->from(DYNBLOCKS_PRESETS_TABLE);

        if (isset($params["where"]) && is_array($params["where"]) && count($params["where"])) {
            foreach ($params["where"] as $field => $value)
                $this->DB->where($field, $value);
        }

        if (isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])) {
            foreach ($params["where_in"] as $field => $value)
                $this->DB->where_in($field, $value);
        }

        if (isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])) {
            foreach ($params["where_sql"] as $value)
                $this->DB->where($value);
        }

        if (isset($filter_object_ids) && is_array($filter_object_ids) && count($filter_object_ids)) {
            $this->DB->where_in("id", $filter_object_ids);
        }

        if (is_array($order_by) && count($order_by) > 0) {
            foreach ($order_by as $field => $dir) {
                if (in_array($field, $this->fields_preset)) {
                    $this->DB->order_by($field . " " . $dir);
                }
            }
        }

        if (!is_null($page)) {
            $page = intval($page) ? intval($page) : 1;
            $this->DB->limit($items_on_page, $items_on_page * ($page - 1));
        }

        $data = array();
        $results = $this->DB->get()->result_array();
        if (!empty($results) && is_array($results)) {
            $data = $this->format_preset($results);
        }
        return $data;
    }

    /**
     * Return number of dynamic blocks presets
     * 
     * @param array $params sql criteria
     * @param array $filter_object_ids filter idnetifiers
     * @return integer
     */
    public function get_presets_count($params = array(), $filter_object_ids = null)
    {
        $this->DB->select('COUNT(*) as cnt')->from(DYNBLOCKS_PRESETS_TABLE);

        if (isset($params["where"]) && is_array($params["where"]) && count($params["where"])) {
            foreach ($params["where"] as $field => $value)
                $this->DB->where($field, $value);
        }

        if (isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])) {
            foreach ($params["where_in"] as $field => $value)
                $this->DB->where_in($field, $value);
        }

        if (isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])) {
            foreach ($params["where_sql"] as $value)
                $this->DB->where($value);
        }

        if (isset($filter_object_ids) && is_array($filter_object_ids) && count($filter_object_ids)) {
            $this->DB->where_in("id", $filter_object_ids);
        }

        $results = $this->DB->get()->result_array();
        if (!empty($results) && is_array($results)) {
            return intval($results[0]["cnt"]);
        }
        return 0;
    }

    /**
     * Return dynamic blocks presets as array
     * 
     * @param integer $page page of results
     * @param integer $items_on_page results per page
     * @param array $order_by sorting data
     * @param array $params sql criteria
     * @param array $filter_object_ids filter idnetifiers
     * @return array
     */
    protected function _get_presets_blocks_list($page = null, $items_on_page = null, $order_by = null, $params = array(), $filter_object_ids = null)
    {
        $this->DB->select(implode(',', $this->fields_preset_block))->from(DYNBLOCKS_PRESETS_BLOCK_TABLE);

        if (isset($params["where"]) && is_array($params["where"]) && count($params["where"])) {
            foreach ($params["where"] as $field => $value)
                $this->DB->where($field, $value);
        }

        if (isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])) {
            foreach ($params["where_in"] as $field => $value)
                $this->DB->where_in($field, $value);
        }

        if (isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])) {
            foreach ($params["where_sql"] as $value)
                $this->DB->where($value);
        }

        if (isset($filter_object_ids) && is_array($filter_object_ids) && count($filter_object_ids)) {
            $this->DB->where_in("id", $filter_object_ids);
        }

        if (is_array($order_by) && count($order_by) > 0) {
            foreach ($order_by as $field => $dir) {
                if (in_array($field, $this->fields_preset_block)) {
                    $this->DB->order_by($field . " " . $dir);
                }
            }
        }

        if (!is_null($page)) {
            $page = intval($page) ? intval($page) : 1;
            $this->DB->limit($items_on_page, $items_on_page * ($page - 1));
        }

        $data = array();
        $results = $this->DB->get()->result_array();
        if (!empty($results) && is_array($results)) {
            $data = $this->format_preset_block($results);
        }
        return $data;
    }

    /**
     * Set area preset
     * 
     * @param integer $area_id area idnetifier
     * @param integer $preset_id preset identifier
     * @return void
     */
    public function set_area_preset($area_id, $preset_id)
    {
        $this->cache_area_clear($area_id);
        $this->DB->where('id_area', $area_id);
        $this->DB->delete(DYNBLOCKS_AREA_BLOCK_TABLE);
        $params = array('where' => array('id_preset' => $preset_id));
        $blocks = $this->_get_presets_blocks_list(null, null, null, $params);
        foreach ($blocks as $block) {
            unset($block['id']);
            unset($block['gid_area']);
            $block['id_area'] = $area_id;
            $validate_data = $this->validate_area_block($block);
            $this->save_area_block(null, $validate_data['data']);
        }
        $this->refresh_area_block_sorter($area_id);
    }

    /**
     * Return preset object by identifier
     * 
     * @param integer $id preset identifier
     * @return array
     */
    public function get_preset_by_id($id)
    {
        $data = array();
        $this->DB->select(implode(",", $this->fields_preset));
        $this->DB->from(DYNBLOCKS_PRESETS_TABLE);
        $this->DB->where("id", $id);
        $result = $this->DB->get()->result_array();
        if (!empty($result)) {
            $data = array_shift($this->format_preset(array($result[0])));
        }
        return $data;
    }

    /**
     * Return preset object by guid
     * 
     * @param string $gid area guid
     * @return array
     */
    public function get_preset_by_gid($gid)
    {
        $data = array();
        $this->DB->select(implode(",", $this->fields_preset));
        $this->DB->from(DYNBLOCKS_PRESETS_TABLE);
        $this->DB->where("gid", $gid);
        $result = $this->DB->get()->result_array();
        if (!empty($result)) {
            $data = array_shift($this->format_preset(array($result[0])));
        }
        return $data;
    }

    /**
     * Save preset object to data source
     * 
     * @param integer $preset_id preset identifier
     * @param array $data preset data
     * @return integer
     */
    public function save_preset($preset_id, $data)
    {
        if (empty($preset_id)) {
            $data['date_add'] = date('Y-m-d H:i:s');
            $this->DB->insert(DYNBLOCKS_PRESETS_TABLE, $data);
            $preset_id = $this->DB->insert_id();
        } else {
            $this->DB->where('id', $id);
            $this->DB->update(DYNBLOCKS_PRESETS_TABLE, $data);
        }
        return $preset_id;
    }

    /**
     * Remove preset block object
     * 
     * @param integer $preset_id preset identifier
     * @return void
     */
    public function remove_preset($preset_id)
    {
        $preset_data = $this->get_preset_by_id($preset_id);

        $this->DB->where('id', $preset_id);
        $this->DB->delete(DYNBLOCKS_PRESETS_TABLE);

        $this->DB->where('id_preset', $preset_id);
        $this->DB->delete(DYNBLOCKS_PRESETS_BLOCK_TABLE);

        if (!$preset_data['logo']) {
            return;
        }

        $this->CI->load->model("Uploads_model");
        $this->CI->Uploads_model->delete_upload($this->upload_config_id, $preset_data["prefix"], $preset_data["logo"]);
    }

    /**
     * Upload preset logo
     * 
     * @param integer $preset_id preset identifier
     * @param string $upload_name upload name
     * @return array
     */
    public function save_preset_logo($preset_id, $upload_name)
    {
        $return = array('errors' => array(), 'data' => array());

        if (empty($upload_name) || empty($_FILES[$upload_name]) || !is_array($_FILES[$upload_name]) ||
            !is_uploaded_file($_FILES[$upload_name]["tmp_name"]))
            return $return;

        $preset_data = $this->get_preset_by_id($preset_id);

        $this->CI->load->model("Uploads_model");
        $return = $this->CI->Uploads_model->upload($this->preset_upload_type, $preset_data["prefix"], $upload_name);

        if (!empty($return["errors"])) {
            return $return;
        }

        $save_data = array("logo" => $return["file"]);
        $this->save_preset($id, $return);

        return $return;
    }

    /**
     * Remove preset logo
     * 
     * @param integer $preset_id preset identifier
     * @return void
     */
    public function delete_preset_logo($preset_id)
    {
        $preset_data = $this->get_preset_by_id($preset_id);
        if (!$preset_data['logo']) {
            return;
        }
        $this->CI->load->model("Uploads_model");
        $this->CI->Uploads_model->delete_upload($this->upload_config_id, $preset_data["prefix"], $preset_data["logo"]);
    }

    /**
     * Format preset object
     * 
     * @param array $data preset data
     * @return array
     */
    public function format_preset($data)
    {
        if (empty($data)) {
            return $data;
        }

        $is_upload_installed = $this->CI->pg_module->is_module_installed('uploads');
        if ($is_upload_installed) {
            $this->CI->load->model('Uploads_model');
        }

        foreach ($data as $key => $preset) {
            $preset['prefix'] = $preset['id'];

            if ($is_upload_installed) {
                $preset["media"]["logo"] = $this->CI->Uploads_model->format_upload($this->preset_upload_type, $preset["prefix"], $preset["logo"]);
            }

            $data[$key] = $preset;
        }

        return $data;
    }

    /**
     * Validate preset object for saving to data source
     * 
     * @param integer $preset_id preset idnetifier
     * @param array $data preset data
     * @return array
     */
    public function validate_preset($preset_id, $data)
    {
        $return = array("errors" => array(), "data" => array());

        if (isset($data['id'])) {
            $return['data']['id'] = intval($data['id']);
        }

        if (isset($data['gid'])) {
            $return['data']['gid'] = trim(strip_tags($data['gid']));
        }

        if (isset($data['gid_area'])) {
            $return['data']['gid_area'] = trim(strip_tags($data['gid_area']));
        }

        if (isset($data['name'])) {
            $return['data']['name'] = trim(strip_tags($data['name']));
        }

        if (isset($data['logo'])) {
            $return['data']['logo'] = trim(strip_tags($data['logo']));
        }

        if (isset($data['comment'])) {
            $return['data']['comment'] = trim(strip_tags($data['comment']));
        }

        if (isset($data['editable'])) {
            $return['data']['editable'] = trim(strip_tags($data['editable']));
        }

        return $return;
    }

    /**
     * Save preset block object to data source
     * 
     * @param integer $id preset block identifier
     * @param array $data preset block data
     * @return integer
     */
    public function save_preset_block($id, $data)
    {
        if (empty($id)) {
            if (!isset($data["width"]) || !$data["width"]) {
                $data["width"] = 100;
            }
            $this->DB->insert(DYNBLOCKS_PRESETS_BLOCK_TABLE, $data);
            $id = $this->DB->insert_id();
        } else {
            $this->DB->where('id', $id);
            $this->DB->update(DYNBLOCKS_PRESETS_BLOCK_TABLE, $data);
        }
        return $id;
    }

    /**
     * Format area block data
     * 
     * @param array $data area block data
     * @return array
     */
    public function format_preset_block($data)
    {
        if (empty($data)) {
            return;
        }

        $area_gids = array();

        foreach ($data as $i => $block) {
            $data[$i]["params"] = $block["params"] ? unserialize($block["params"]) : array();
            $data[$i]["width"] = $block["width"] ? $block["width"] : 100;
            if (!in_array($block['gid_area'], $area_gids)) {
                $area_gids[] = $block['gid_area'];
            }
        }

        $areas_by_gid = array();
        $areas = $this->get_areas_list(null, null, null, array('where_in' => array('gid' => $area_gids)));
        foreach ($areas as $i => $area) {
            $areas_by_gid[$area['gid']] = $area['id'];
        }

        foreach ($data as $i => $block) {
            $data[$i]['id_area'] = $areas_by_gid[$block['gid_area']];
        }

        return $data;
    }

    /**
     * Validate preset block object for saving to data source
     * 
     * @param integer $block_id preset block idnetifier
     * @param array $data preset block data
     * @return array
     */
    public function validate_preset_block($block_id, $data)
    {
        $return = array("errors" => array(), "data" => array());

        if (isset($data['id'])) {
            $return['data']['id'] = intval($data['id']);
        }

        if (isset($data['id_preset'])) {
            $return['data']['id_preset'] = intval($data['id_preset']);
        }

        if (isset($data['gid_area'])) {
            $return['data']['gid_area'] = trim(strip_tags($data['gid_area']));
        }

        if (isset($data['id_block'])) {
            $return['data']['id_block'] = intval($data['id_block']);
        }

        if (isset($data['params'])) {
            $return['data']['params'] = serialize($data['params']);
        }

        if (isset($data['view_str'])) {
            $return['data']['view_str'] = trim(strip_tags($data['view_str']));
        }

        if (isset($data['width'])) {
            $return['data']['width'] = intval($data['width']);
        }

        if (isset($data['sorter'])) {
            $return['data']['sorter'] = intval($data['sorter']);
        }

        if (isset($data['cache_time'])) {
            $return['data']['cache_time'] = intval($data['cache_time']);
        }

        return $return;
    }

    private function getDynamicBlockText($block_params, $prefix)
    {
        if (!empty($block_params[$prefix . $this->CI->pg_language->current_lang_id])) {
            // By current lang id
            return $block_params[$prefix . $this->CI->pg_language->current_lang_id];
        } elseif (!empty($block_params[$prefix . $this->CI->pg_language->languages[$this->CI->pg_language->current_lang_id]['code']])) {
            // By current lang code
            return $block_params[$prefix . $this->CI->pg_language->languages[$this->CI->pg_language->current_lang_id]['code']];
        } elseif (!empty($block_params[$prefix . $this->CI->pg_language->get_default_lang_id()])) {
            // By default lang id
            return $block_params[$prefix . $this->CI->pg_language->get_default_lang_id()];
        } else if (!empty($block_params[$prefix . $this->CI->pg_language->languages[$this->CI->pg_language->get_default_lang_id()]['code']])) {
            // By default lang code
            return $block_params[$prefix . $this->CI->pg_language->languages[$this->CI->pg_language->get_default_lang_id()]['code']];
        } else {
            return '';
        }
    }

    public function _dynamic_block_get_rich_text($params, $view, $width)
    {
        $data['title'] = $this->getDynamicBlockText($params, 'title_');
        $data['html'] = $this->getDynamicBlockText($params, 'html_');
        $this->CI->template_lite->assign('dynamic_block_rich_text_data', $data);
        return $this->CI->template_lite->fetch("dynamic_block_rich_text", "user", "dynamic_blocks");
    }

    public function _dynamic_block_get_empty_block($params, $view, $width)
    {
        $height = !empty($params['height']) ? intval($params['height']) : 1;
        return '<div style="height: ' . $height . 'px;">&nbsp;</div>';
    }

    public function lang_dedicate_module_callback_add($lang_id = false)
    {
        $this->cache_clear();
    }

    public function lang_dedicate_module_callback_delete($lang_id = false)
    {
        $this->cache_clear();
    }

    /**
     * Install dynamic blocks
     * All blocks should belong to the same module
     * @param array $module_blocks
     */
    public function installBatch(array $module_blocks)
    {
        $area_ids = array();
        foreach ((array) $module_blocks as $block_data) {

            $validate_data = $this->validate_block(null, $block_data);
            if (!empty($validate_data['errors'])) {
                continue;
            }
            $id_block = $this->save_block(null, $validate_data['data']);
            if (!empty($block_data['area'])) {
                foreach ($block_data['area'] as $block_area) {
                    if (!isset($area_ids[$block_area['gid']])) {
                        $area = $this->get_area_by_gid($block_area['gid']);
                        $area_ids[$block_area['gid']] = $area['id'];
                    }
                    $block_area['id_area'] = $area_ids[$block_area['gid']];
                    $block_area['id_block'] = $id_block;
                    $validate_data = $this->validate_area_block($block_area);
                    if (empty($validate_data['errors'])) {
                        $this->save_area_block(null, $validate_data['data']);
                    }
                }
            }
            if (!empty($block_data['presets'])) {
                foreach ($block_data['presets'] as $preset_data) {
                    $preset = $this->get_preset_by_gid($preset_data['gid_preset']);
                    $preset_data['id_preset'] = $preset['id'];
                    $preset_data['id_block'] = $id_block;
                    $validate_data = $this->validate_preset_block(null, $preset_data);
                    if (!empty($validate_data['errors'])) {
                        continue;
                    }
                    $this->save_preset_block(null, $validate_data['data']);
                }
            }
        }
    }

    /**
     * Check that dynamic blocks are installed
     * @param array $blocks
     * @return array
     */
    private function checkBlocksInstalled(array $blocks)
    {
        $data = array();
        foreach ($blocks as $block) {
            $block = $this->get_block_by_gid($block['gid']);
            if ($block) {
                $data[] = $block;
            }
        }
        return $data;
    }

}
