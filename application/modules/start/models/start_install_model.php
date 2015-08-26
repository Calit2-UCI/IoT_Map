<?php

namespace Pg\Modules\Start\Models;

class Start_install_model extends \Model
{

    public $CI;
    private $menu = array(
        // admin menu
        'admin_menu' => array(
            'name' => 'Admin area menu',
            "action" => "create",
            "items" => array(
                'main_items' => array(
                    "action" => "create", 'link' => 'admin/start', 'status' => 1, 'sorter' => 1,
                    "items" => array(
                        'admin-home-item' => array("action" => "create", 'link' => 'admin/start', 'icon' => 'home', 'status' => 1, 'sorter' => 1),
                    ),
                ),
                'system_items' => array(
                    "action" => "create", 'link' => 'admin/start', 'status' => 1, 'sorter' => 2,
                    "items" => 'none',
                ),
                'settings_items' => array(
                    "action" => "create", 'link' => 'admin/start', 'status' => 1, 'sorter' => 3,
                    "items" => array(
                        'interface-items' => array("action" => "create", 'link' => 'admin/start/menu/interface-items', 'icon' => 'paint-brush', 'status' => 1, 'sorter' => 1),
                        'content_items' => array("action" => "create", 'link' => 'admin/start/menu/content_items', 'icon' => 'font', 'status' => 1, 'sorter' => 2),
                        'system-items' => array(
                            "action" => "create", 'link' => 'admin/start/menu/system-items', 'icon' => 'cog', 'status' => 1, 'sorter' => 3,
                            "items" => array(
                                'system-numerics-item' => array("action" => "create", 'link' => 'admin/start/settings', 'icon' => '', 'status' => 1, 'sorter' => 6),
                            //'system-geolocation-item' => array("action" => "create", 'link' => 'admin/start/geolocation','icon'=>'', 'status' => 1, 'sorter' => 6),
                            ),
                        ),
                    ),
                ),
                'other_items' => array(
                    "action" => "create", 'link' => 'admin/start', 'status' => 1, 'sorter' => 4,
                    "items" => array(
                        'admin-modules-item' => array("action" => "create", 'link' => 'admin/start/mod_login', 'icon' => 'power-off', 'status' => 1, 'sorter' => 1),
                        "add_ons_items" => array("action" => "create", 'link' => 'admin/start/menu/add_ons_items', 'icon' => 'puzzle-piece', 'status' => 1, 'sorter' => 2),
                    ),
                ),
            ),
        ),
        // guest main menu
        'guest_main_menu' => array(
            'name' => 'Guest main menu',
            "action" => "create",
            "items" => array(
                'main-menu-home-item' => array("action" => "create", 'link' => 'start/index', 'status' => 1, 'sorter' => 1),
            ),
        ),
        // footer menu
        'user_footer_menu' => array(
            'name' => 'Footer menu',
            "action" => "create",
            "items" => array(
                'footer-menu-help-item' => array("action" => "create", 'link' => '/', 'status' => 1, 'sorter' => 1),
                'footer-menu-about-item' => array("action" => "create", 'link' => '/', 'status' => 1, 'sorter' => 2),
                'footer-menu-policy-item' => array("action" => "create", 'link' => '/', 'status' => 1, 'sorter' => 3),
                'footer-menu-links-item' => array("action" => "create", 'link' => '/', 'status' => 1, 'sorter' => 4),
            ),
        ),
        // user top menu
        'user_top_menu' => array(
            'name' => 'User top menu',
            "action" => "create",
            "items" => array(
                'user-menu-people' => array("action" => "create", 'link' => '', 'status' => 1, 'sorter' => 1),
                'user-menu-communication' => array("action" => "create", 'link' => '', 'status' => 1, 'sorter' => 2),
                'user-menu-activities' => array("action" => "create", 'link' => '', 'status' => 1, 'sorter' => 3),
            ),
        ),
        // settings top menu
        'settings_menu' => array(
            'name' => 'Settings menu',
            "action" => "create",
            "items" => array(
                'settings-menu-home' => array("action" => "create", 'link' => 'start/homepage', 'status' => 1, 'sorter' => 1),
            ),
        ),
    );
    protected $dynamic_blocks = array(
        'site_stat_block' => array(
            'gid' => 'site_stat_block',
            'module' => 'start',
            'model' => 'Start_model',
            'method' => '_dynamic_block_get_stat_block',
            'views' => array(
                array(
                    'gid' => 'default',
                ),
            ),
            'params' => '',
        ),
        'search_form_block' => array(
            'gid' => 'search_form_block',
            'module' => 'start',
            'model' => 'Start_model',
            'method' => '_dynamic_block_get_search_form',
            'views' => array(
                array(
                    'gid' => 'default',
                ),
            ),
            'params' => '',
            'area' => array(
                array(
                    'gid' => 'mediumturquoise',
                    'params' => 'a:0:{}',
                    'view_str' => 'default',
                    'width' => '100',
                    'sorter' => '11',
                    'cache_time' => '0',
                ),
                array(
                    'gid' => 'lavender',
                    'params' => 'a:0:{}',
                    'view_str' => 'default',
                    'width' => '100',
                    'sorter' => '11',
                    'cache_time' => '600',
                ),
                array(
                    'gid' => 'jewish',
                    'params' => 'a:0:{}',
                    'view_str' => 'default',
                    'width' => '100',
                    'sorter' => '5',
                    'cache_time' => '0',
                ),
                array(
                    'gid' => 'lovers',
                    'params' => 'a:0:{}',
                    'view_str' => 'default',
                    'width' => '100',
                    'sorter' => '5',
                    'cache_time' => '0',
                ),
                array(
                    'gid' => 'blackonwhite',
                    'params' => 'a:0:{}',
                    'view_str' => 'default',
                    'width' => '50',
                    'sorter' => '6',
                    'cache_time' => '0',
                ),
                array(
                    'gid' => 'deepblue',
                    'params' => 'a:0:{}',
                    'view_str' => 'default',
                    'width' => '100',
                    'sorter' => '5',
                    'cache_time' => '0',
                ),
                array(
                    'gid' => 'community',
                    'params' => 'a:0:{}',
                    'view_str' => 'default',
                    'width' => '100',
                    'sorter' => '4',
                    'cache_time' => '0',
                ),
                array(
                    'gid' => 'christian',
                    'params' => 'a:0:{}',
                    'view_str' => 'default',
                    'width' => '100',
                    'sorter' => '7',
                    'cache_time' => '0',
                ),
                array(
                    'gid' => 'persimmon_red',
                    'params' => 'a:0:{}',
                    'view_str' => 'default',
                    'width' => '100',
                    'sorter' => '10',
                    'cache_time' => '0',
                ),
            ),
            'presets' => array(
                array(
                    'gid_preset' => 'mediumturquoise',
                    'gid_area' => 'mediumturquoise',
                    'params' => 'a:0:{}',
                    'view_str' => 'default',
                    'width' => '100',
                    'cache_time' => '0',
                    'sorter' => '11',
                ),
                array(
                    'gid_preset' => 'lavender',
                    'gid_area' => 'lavender',
                    'params' => 'a:0:{}',
                    'view_str' => 'default',
                    'width' => '100',
                    'cache_time' => '600',
                    'sorter' => '11',
                ),
                array(
                    'gid_preset' => 'jewish',
                    'gid_area' => 'jewish',
                    'params' => 'a:0:{}',
                    'view_str' => 'default',
                    'width' => '100',
                    'cache_time' => '0',
                    'sorter' => '5',
                ),
                array(
                    'gid_preset' => 'lovers',
                    'gid_area' => 'lovers',
                    'params' => 'a:0:{}',
                    'view_str' => 'default',
                    'width' => '100',
                    'cache_time' => '0',
                    'sorter' => '5',
                ),
                array(
                    'gid_preset' => 'blackonwhite',
                    'gid_area' => 'blackonwhite',
                    'params' => 'a:0:{}',
                    'view_str' => 'default',
                    'width' => '50',
                    'cache_time' => '0',
                    'sorter' => '6',
                ),
                array(
                    'gid_preset' => 'deepblue',
                    'gid_area' => 'deepblue',
                    'params' => 'a:0:{}',
                    'view_str' => 'default',
                    'width' => '100',
                    'cache_time' => '0',
                    'sorter' => '5',
                ),
                array(
                    'gid_preset' => 'community',
                    'gid_area' => 'community',
                    'params' => 'a:0:{}',
                    'view_str' => 'default',
                    'width' => '100',
                    'cache_time' => '0',
                    'sorter' => '4',
                ),
                array(
                    'gid_preset' => 'christian',
                    'gid_area' => 'christian',
                    'params' => 'a:0:{}',
                    'view_str' => 'default',
                    'width' => '100',
                    'cache_time' => '0',
                    'sorter' => '7',
                ),
                array(
                    'gid_preset' => 'persimmon_red',
                    'gid_area' => 'persimmon_red',
                    'params' => 'a:0:{}',
                    'view_str' => 'default',
                    'width' => '100',
                    'sorter' => '10',
                    'cache_time' => '0',
                ),
            ),
        ),
    );

    /**
     * Constructor
     *
     * @return Install object
     */
    public function __construct()
    {
        parent::__construct();
        $this->CI = &get_instance();
        // load langs
        $this->CI->load->model('Install_model');
    }

    public function _validate_requirements()
    {
        $result = array('data' => array(), 'result' => true);

        // php 5.3
        $good = phpversion() >= '5.3.0';
        $result["data"][] = array(
            "name" => "PHP version >= 5.3.0 ",
            "value" => $good ? "Yes" : "No",
            "result" => $good,
        );
        $result["result"] = $result["result"] && $good;

        // json
        $good = extension_loaded('json');
        $result["data"][] = array(
            "name" => "PECL json",
            "value" => $good ? "Yes" : "No",
            "result" => $good,
        );

        $result["result"] = $result["result"] && $good;

        return $result;
    }

    public function _validate_settings_form()
    {
        $errors = array();
        $data["product_order_key"] = $this->CI->input->post('product_order_key', true);

        if (empty($data["product_order_key"])) {
            $errors[] = $this->CI->pg_language->get_string('start', 'error_product_key_incorrect');
        }

        $return = array(
            "data" => $data,
            "errors" => $errors,
        );
        return $return;
    }

    public function _save_settings_form($data)
    {
        foreach ($data as $setting => $value) {
            $this->CI->pg_module->set_module_config('start', $setting, $value);
        }
        return;
    }

    public function _get_settings_form($submit = false)
    {
        $data = array(
            'product_order_key' => $this->CI->pg_module->get_module_config('start', 'product_order_key'),
        );
        if ($submit) {
            $validate = $this->_validate_settings_form();
            if (!empty($validate["errors"])) {
                $this->CI->template_lite->assign('settings_errors', $validate["errors"]);
                $data = $validate["data"];
            } else {
                $this->_save_settings_form($validate["data"]);
                return false;
            }
        }

        $this->CI->template_lite->assign('settings_data', $data);
        $html = $this->CI->template_lite->fetch('install_settings_form', 'admin', 'start');
        return $html;
    }

    /*
     * Menu module methods
     *
     */

    public function install_menu()
    {
        $this->CI->load->model('Menu_model');
        $this->CI->load->helper('menu');

        foreach ($this->menu as $gid => $menu_data) {
            $this->menu[$gid]['id'] = linked_install_set_menu($gid, $menu_data["action"], $menu_data["name"]);
            linked_install_process_menu_items($this->menu, 'create', $gid, 0, $this->menu[$gid]["items"]);
        }
    }

    public function install_menu_lang_update($langs_ids = null)
    {
        if (empty($langs_ids)) {
            return false;
        }
        $langs_file = $this->CI->Install_model->language_file_read('start', 'menu', $langs_ids);

        if (!$langs_file) {
            log_message('info', 'Empty menu langs data');
            return false;
        }

        $this->CI->load->model('Menu_model');
        $this->CI->load->helper('menu');

        foreach ($this->menu as $gid => $menu_data) {
            linked_install_process_menu_items($this->menu, 'update', $gid, 0, $this->menu[$gid]["items"], $gid, $langs_file);
        }

        return true;
    }

    public function install_menu_lang_export($langs_ids)
    {
        if (empty($langs_ids)) {
            return false;
        }
        $this->CI->load->model('Menu_model');
        $this->CI->load->helper('menu');

        $return = array();
        foreach ($this->menu as $gid => $menu_data) {
            $temp = linked_install_process_menu_items($this->menu, 'export', $gid, 0, $this->menu[$gid]["items"], $gid, $langs_ids);
            $return = array_merge($return, $temp);
        }

        return array("menu" => $return);
    }

    public function deinstall_menu()
    {
        $this->CI->load->model('Menu_model');

        foreach ($this->menu as $gid => $menu_data) {
            $menu = $this->CI->Menu_model->get_menu_by_gid($gid);
            if ($menu["id"]) {
                $this->CI->Menu_model->delete_menu($menu["id"]);
            }
        }
    }

    /*
     * Arbitrary methods
     *
     */

    public function _arbitrary_installing()
    {
        
    }

    public function _arbitrary_lang_install($langs_ids)
    {
        // admin_home_page
        if (!is_array($langs_ids)) {
            $langs_ids = (array) $langs_ids;
        }
        $langs_file = $this->CI->Install_model->language_file_read('start', 'admin_home_page', $langs_ids);

        foreach ($langs_file as $gid => $ldata) {
            if (!empty($ldata)) {
                $this->CI->pg_language->pages->set_string_langs('admin_home_page', $gid, $ldata, array_keys($ldata));
            }
        }

        $langs_file = $this->CI->Install_model->language_file_read("start", "arbitrary", $langs_ids);
        if (!$langs_file) {
            log_message("info", "Empty arbitrary langs data");
            return false;
        }

        $post_data = array(
            "title" => $langs_file["seo_tags_index_title"],
            "keyword" => $langs_file["seo_tags_index_keyword"],
            "description" => $langs_file["seo_tags_index_description"],
            "header" => $langs_file["seo_tags_index_header"],
            "og_title" => $langs_file["seo_tags_index_og_title"],
            "og_type" => $langs_file["seo_tags_index_og_type"],
            "og_description" => $langs_file["seo_tags_index_og_description"],
            "url_template" => "",
        );
        $this->CI->pg_seo->set_settings("user", "", "", $post_data);

        $post_data = array(
            "title" => $langs_file["seo_tags_admin_title"],
            "keyword" => $langs_file["seo_tags_admin_keyword"],
            "description" => $langs_file["seo_tags_admin_description"],
            "header" => $langs_file["seo_tags_admin_header"],
            "og_title" => $langs_file["seo_tags_admin_og_title"],
            "og_type" => $langs_file["seo_tags_admin_og_type"],
            "og_description" => $langs_file["seo_tags_admin_og_description"],
            "url_template" => "",
        );
        $this->CI->pg_seo->set_settings("admin", "", "", $post_data);
    }

    public function _arbitrary_lang_export($langs_ids = null)
    {
        if (empty($langs_ids)) {
            return false;
        }
        if (!is_array($langs_ids)) {
            $langs_ids = (array) $langs_ids;
        }

        $admin_home_page_return = array();

        // admin_home_page
        foreach ($langs_ids as $lang_id) {
            $mod_langs = $this->CI->pg_language->pages->return_module('admin_home_page', $lang_id);
            foreach ($mod_langs as $gid => $value) {
                $admin_home_page_return[$gid][$lang_id] = $value;
            }
        }

        // arbitrary
        $settings = $this->CI->pg_seo->get_settings("user", "", "", $langs_ids);
        $arbitrary_return["seo_tags_index_title"] = $settings["title"];
        $arbitrary_return["seo_tags_index_keyword"] = $settings["keyword"];
        $arbitrary_return["seo_tags_index_description"] = $settings["description"];
        $arbitrary_return["seo_tags_index_header"] = $settings["header"];
        $arbitrary_return["seo_tags_index_og_title"] = $settings["og_title"];
        $arbitrary_return["seo_tags_index_og_type"] = $settings["og_type"];
        $arbitrary_return["seo_tags_index_og_description"] = $settings["og_description"];

        $settings = $this->CI->pg_seo->get_settings("admin", "", "", $langs_ids);
        $arbitrary_return["seo_tags_admin_title"] = $settings["title"];
        $arbitrary_return["seo_tags_admin_keyword"] = $settings["keyword"];
        $arbitrary_return["seo_tags_admin_description"] = $settings["description"];
        $arbitrary_return["seo_tags_admin_header"] = $settings["header"];
        $arbitrary_return["seo_tags_admin_og_title"] = $settings["og_title"];
        $arbitrary_return["seo_tags_admin_og_type"] = $settings["og_type"];
        $arbitrary_return["seo_tags_admin_og_description"] = $settings["og_description"];

        return array("admin_home_page" => $admin_home_page_return, "arbitrary" => $arbitrary_return);
    }

    public function _arbitrary_deinstalling()
    {
        $this->CI->pg_language->pages->delete_module('admin_home_page');
    }

    /*
     * Banners module methods
     *
     */

    public function install_banners()
    {
        // add banners module
        $this->CI->load->model('Start_model');
        $this->CI->load->model('banners/models/Banner_group_model');
        $this->CI->load->model('banners/models/Banner_place_model');
        $this->CI->Banner_group_model->set_module("start", "Start_model", "_banner_available_pages");

        // create banner groups
        $group_attrs = array(
            'date_created' => date("Y-m-d H:i:s"),
            'date_modified' => date("Y-m-d H:i:s"),
            'price' => 1,
            'gid' => 'contact_groups',
            'name' => 'Contact pages'
        );
        $group_id = $this->CI->Banner_group_model->create_unique_group($group_attrs);
        $all_places = $this->CI->Banner_place_model->get_all_places();
        if ($all_places) {
            foreach ($all_places as $key => $value) {
                if ($value['keyword'] != 'bottom-banner' && $value['keyword'] != 'top-banner' &&
                    $value['keyword'] != 'big-right-banner' && $value['keyword'] != 'right-banner') {
                    continue;
                }
                $this->CI->Banner_place_model->save_place_group($value['id'], $group_id);
            }
        }

        $group_attrs = array(
            'date_created' => date("Y-m-d H:i:s"),
            'date_modified' => date("Y-m-d H:i:s"),
            'price' => 1,
            'gid' => 'content_groups',
            'name' => 'Content pages'
        );
        $group_id = $this->CI->Banner_group_model->create_unique_group($group_attrs);
        $all_places = $this->CI->Banner_place_model->get_all_places();
        if ($all_places) {
            foreach ($all_places as $key => $value) {
                if ($value['keyword'] != 'bottom-banner' && $value['keyword'] != 'top-banner' &&
                    $value['keyword'] != 'big-left-banner' && $value['keyword'] != 'left-banner') {
                    continue;
                }
                $this->CI->Banner_place_model->save_place_group($value['id'], $group_id);
            }
        }

        $group_attrs = array(
            'date_created' => date("Y-m-d H:i:s"),
            'date_modified' => date("Y-m-d H:i:s"),
            'price' => 1,
            'gid' => 'users_groups',
            'name' => 'Users pages'
        );
        $group_id = $this->CI->Banner_group_model->create_unique_group($group_attrs);
        $all_places = $this->CI->Banner_place_model->get_all_places();
        if ($all_places) {
            foreach ($all_places as $key => $value) {
                if ($value['keyword'] != 'bottom-banner' && $value['keyword'] != 'top-banner') {
                    continue;
                }
                $this->CI->Banner_place_model->save_place_group($value['id'], $group_id);
            }
        }

        $group_attrs = array(
            'date_created' => date("Y-m-d H:i:s"),
            'date_modified' => date("Y-m-d H:i:s"),
            'price' => 1,
            'gid' => 'start_groups',
            'name' => 'Start pages'
        );

        $group_id = $this->CI->Banner_group_model->create_unique_group($group_attrs);
        // add pages in group
        $pages = $this->CI->Start_model->_banner_available_pages();
        if ($pages) {
            foreach ($pages as $key => $value) {
                $page_attrs = array(
                    'group_id' => $group_id,
                    'name' => $value['name'],
                    'link' => $value['link'],
                );
                $this->CI->Banner_group_model->add_page($page_attrs);
            }
        }

        // add places in group
        $all_places = $this->CI->Banner_place_model->get_all_places();
        if ($all_places) {
            foreach ($all_places as $key => $value) {
                if ($value['keyword'] != 'bottom-banner') {
                    continue;
                }
                $this->CI->Banner_place_model->save_place_group($value['id'], $group_id);
            }
        }
    }

    public function install_banners_lang_update($langs_ids = null)
    {
        if (empty($langs_ids)) {
            return false;
        }
        if (!is_array($langs_ids)) {
            $langs_ids = (array) $langs_ids;
        }

        $langs_file = $this->CI->Install_model->language_file_read('start', 'banners', $langs_ids);

        if (!$langs_file) {
            log_message('info', 'Empty banners langs data');
            return false;
        }
        $this->CI->load->model('banners/models/Banner_group_model');

        $banners_groups[] = 'banners_group_contact_groups';
        $banners_groups[] = 'banners_group_content_groups';
        $banners_groups[] = 'banners_group_users_groups';
        $banners_groups[] = 'banners_group_start_groups';

        $this->CI->Banner_group_model->update_langs($banners_groups, $langs_file, $langs_ids);
        return true;
    }

    public function install_banners_lang_export($langs_ids)
    {
        if (!is_array($langs_ids)) {
            $langs_ids = (array) $langs_ids;
        }
        $this->CI->load->model('banners/models/Banner_group_model');
        $banners_groups[] = 'banners_group_contact_groups';
        $banners_groups[] = 'banners_group_content_groups';
        $banners_groups[] = 'banners_group_users_groups';
        $banners_groups[] = 'banners_group_start_groups';
        $langs = $this->CI->Banner_group_model->export_langs($banners_groups, $langs_ids);

        return array('banners' => $langs);
    }

    public function deinstall_banners()
    {
        // delete banners module
        $this->CI->load->model("banners/models/Banner_group_model");
        $this->CI->Banner_group_model->delete_module("start");
    }

    /*
     * Dynamic blocks methods
     *
     */

    public function install_dynamic_blocks()
    {
        $this->CI->load->model('Dynamic_blocks_model');
        $this->CI->Dynamic_blocks_model->installBatch($this->dynamic_blocks);
    }

    public function install_dynamic_blocks_lang_update($langs_ids = null)
    {
        $this->CI->load->model('Dynamic_blocks_model');
        return $this->CI->Dynamic_blocks_model->updateLangsByModuleBlocks($this->dynamic_blocks, $langs_ids);
    }

    public function install_dynamic_blocks_lang_export($langs_ids = null)
    {
        $this->CI->load->model('Dynamic_blocks_model');
        return array(
            'dynamic_blocks' => $this->CI->Dynamic_blocks_model->export_langs($this->dynamic_blocks, $langs_ids),
        );
    }

    public function deinstall_dynamic_blocks()
    {
        $this->CI->load->model('Dynamic_blocks_model');
        foreach ($this->dynamic_blocks as $block) {
            $this->CI->Dynamic_blocks_model->delete_block_by_gid($block['gid']);
        }
    }

}
