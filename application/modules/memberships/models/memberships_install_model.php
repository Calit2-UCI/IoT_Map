<?php

namespace Pg\Modules\Memberships\Models;

/**
 * Memberships module
 *
 * @package 	PG_Dating
 * @copyright 	Copyright (c) 2000-2014 PG Dating Pro - php dating software
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */

/**
 * Install model
 * 
 * @package 	PG_Dating
 * @subpackage 	Memberships
 * @category	models
 * @copyright 	Copyright (c) 2000-2014 PG Dating Pro - php dating software
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
class Memberships_install_model extends \Model
{

    /**
     * Link to CodeIgniter object
     * 
     * @var object
     */
    protected $CI;

    /**
     * Menu configuration
     * 
     * @var array
     */
    protected $menu = array(
        'admin_payments_menu' => array(
            'action' => 'none',
            'items' => array(
                'memberships_menu_item' => array('action' => 'create', 'link' => 'admin/memberships/index', 'status' => 1, 'sorter' => 4),
            ),
        ),
        'settings_menu' => array(
            'action' => 'none',
            'items' => array(
                'account-item' => array(
                    'action' => 'none',
                    'items' => array(
                        'memberships_item' => array("action" => "create", 'link' => 'memberships/index', 'status' => 1, 'sorter' => 1),
                    ),
                ),
            ),
        ),
    );

    /**
     * Moderators configuration
     * 
     * @var array
     */
    protected $moderators = array(
        array("module" => "memberships", "method" => "index", "is_default" => 0),
    );

    /**
     * Fields depended on languages
     * 
     * @var array
     */
    protected $lang_dm_data = array(
        array(
            "module" => "memberships",
            "model" => "Memberships_model",
            "method_add" => "langDedicateModuleCallbackAdd",
            "method_delete" => "langDedicateModuleCallbackDelete",
        ),
    );

    /**
     * Payment configuration
     * 
     * @var array
     */
    protected $payment_types = array(
        array(
            'gid' => 'memberships',
            'callback_module' => 'memberships',
            'callback_model' => 'Memberships_model',
            'callback_method' => 'paymentMembershipStatus',
        ),
    );

    /**
     * Cronjob configuration
     * 
     * @var array
     */
    protected $cron_data = array(
        array(
            "name" => "Update users memberships",
            "module" => "memberships",
            "model" => "Memberships_users_model",
            "method" => "cron_update_packages",
            "cron_tab" => "*/10 * * * *",
            "status" => "1",
        ),
    );

    /**
     * Class constructor
     *
     * @return Memberships_Install
     */
    public function __construct()
    {
        parent::Model();
        $this->CI = & get_instance();
        $this->CI->load->model('Install_model');
    }

    /**
     * Install menu data of video chats
     * 
     * @return void
     */
    public function install_menu()
    {
        $this->CI->load->helper("menu");
        foreach ($this->menu as $gid => $menu_data) {
            $this->menu[$gid]["id"] = linked_install_set_menu($gid, $menu_data["action"], isset($menu_data["name"]) ? $menu_data["name"] : '');
            linked_install_process_menu_items($this->menu, "create", $gid, 0, $this->menu[$gid]["items"]);
        }
    }

    /**
     * Import menu languages of memberships
     * 
     * @param array $langs_ids languages identifiers
     * @return void
     */
    public function install_menu_lang_update($langs_ids = null)
    {
        if (empty($langs_ids)) {
            return false;
        }
        $langs_file = $this->CI->Install_model->language_file_read("memberships", "menu", $langs_ids);

        if (!$langs_file) {
            log_message("info", "Empty menu langs data (memberships)");
            return false;
        }

        $this->CI->load->helper("menu");

        foreach ($this->menu as $gid => $menu_data) {
            linked_install_process_menu_items($this->menu, "update", $gid, 0, $this->menu[$gid]["items"], $gid, $langs_file);
        }
        return true;
    }

    /**
     * Export menu languages of memberships
     * 
     * @param array $langs_ids languages identifiers
     * @return array
     */
    public function install_menu_lang_export($langs_ids)
    {
        if (empty($langs_ids)) {
            return false;
        }
        $this->CI->load->helper("menu");

        $return = array();
        foreach ($this->menu as $gid => $menu_data) {
            $temp = linked_install_process_menu_items($this->menu, "export", $gid, 0, $this->menu[$gid]["items"], $gid, $langs_ids);
            $return = array_merge($return, $temp);
        }
        return array("menu" => $return);
    }

    /**
     * Uninstall menu data of video chats
     * 
     * @return void
     */
    public function deinstall_menu()
    {

        $this->CI->load->helper("menu");
        foreach ($this->menu as $gid => $menu_data) {
            if ($menu_data["action"] == "create") {
                linked_install_set_menu($gid, "delete");
            } else {
                linked_install_delete_menu_items($gid, $this->menu[$gid]["items"]);
            }
        }
    }

    /**
     * Install data of moderators module
     * 
     * @return void
     */
    public function install_moderators()
    {
        // install moderators permissions
        $this->CI->load->model('Moderators_model');

        foreach ($this->moderators_methods as $method) {
            $this->CI->Moderators_model->save_method(null, $method);
        }
    }

    /**
     * Import languages of moderators module
     * 
     * @param array $langs_ids languages identifiers
     * @return void
     */
    public function install_moderators_lang_update($langs_ids = null)
    {
        $langs_file = $this->CI->Install_model->language_file_read('memberships', 'moderators', $langs_ids);

        // install moderators permissions
        $this->CI->load->model('Moderators_model');
        $params['where']['module'] = 'memberships';
        $methods = $this->CI->Moderators_model->get_methods_lang_export($params);

        foreach ($methods as $method) {
            if (!empty($langs_file[$method['method']])) {
                $this->CI->Moderators_model->save_method($method['id'], array(), $langs_file[$method['method']]);
            }
        }
    }

    /**
     * Export languages of moderators module
     * 
     * @param array $langs_ids languages identifiers
     * @return array
     */
    public function install_moderators_lang_export($langs_ids)
    {
        $this->CI->load->model('Moderators_model');
        $params['where']['module'] = 'memberships';
        $methods = $this->CI->Moderators_model->get_methods_lang_export($params, $langs_ids);
        foreach ($methods as $method) {
            $return[$method['method']] = $method['langs'];
        }
        return array('moderators' => $return);
    }

    /**
     * Uninstall data of moderators module
     * 
     * @return void
     */
    public function deinstall_moderators()
    {
        // delete moderation methods in moderators
        $this->CI->load->model('Moderators_model');
        $params['where']['module'] = 'memberships';
        $this->CI->Moderators_model->delete_methods($params);
    }

    /**
     * Install data of payments module
     * 
     */
    public function install_payments()
    {
        // add account payment type
        $this->CI->load->model("Payments_model");
        foreach ($this->payment_types as $payment_type) {
            $data = array(
                'gid' => $payment_type['gid'],
                'callback_module' => $payment_type['callback_module'],
                'callback_model' => $payment_type['callback_model'],
                'callback_method' => $payment_type['callback_method'],
            );
            $this->CI->Payments_model->save_payment_type(null, $data);
        }
    }

    /**
     * Import data of payment module depended on language
     * 
     * @param array $langs_ids languages identifiers
     * @return boolean
     */
    public function install_payments_lang_update($langs_ids = null)
    {
        if (empty($langs_ids)) {
            return false;
        }
        $langs_file = $this->CI->Install_model->language_file_read('memberships', 'payments', $langs_ids);
        if (!$langs_file) {
            log_message('info', 'Empty payments langs data (memberships)');
            return false;
        }
        $this->CI->load->model('Payments_model');
        $this->CI->Payments_model->update_langs($this->payment_types, $langs_file, $langs_ids);
        return true;
    }

    /**
     * Export data of payment module depended on language
     * 
     * @param array $langs_ids languages identifiers
     * @return array
     */
    public function install_payments_lang_export($langs_ids = null)
    {
        $this->CI->load->model('Payments_model');
        $return = $this->CI->Payments_model->export_langs($this->payment_types, $langs_ids);
        return array("payments" => $return);
    }

    /**
     * Uninstall data of payments module
     * 
     * @return void
     */
    public function deinstall_payments()
    {
        $this->CI->load->model('Payments_model');
        foreach ($this->payment_types as $payment_type) {
            $this->CI->Payments_model->delete_payment_type_by_gid($payment_type['gid']);
        }
    }

    /**
     * Install cronjob data
     * 
     * @return void
     */
    public function install_cronjob()
    {
        $this->CI->load->model('Cronjob_model');
        foreach ($this->cronjob as $cronjob_data) {
            $this->CI->Cronjob_model->save_cron(null, $cronjob_data);
        }
    }

    /**
     * Uninstall cronjob data
     * 
     * @return void
     */
    public function deinstall_cronjob()
    {
        $this->CI->load->model('Cronjob_model');
        $cron_data = array();
        $cron_data["where"]["module"] = "memberships";
        $this->CI->Cronjob_model->delete_cron_by_param($cron_data);
    }

    public function deinstall_services()
    {
        $this->CI->load->model('Services_model');
        $this->CI->db->where('type', 'membership');
        $this->CI->db->delete(SERVICES_TABLE);
    }

    /**
     * Install fields of dedicated languages
     * 
     * @return void
     */
    public function _prepare_installing()
    {
        $this->CI->load->model("Memberships_model");
        foreach ($this->CI->pg_language->languages as $lang_id => $value) {
            $this->CI->Memberships_model->langDedicateModuleCallbackAdd($lang_id);
        }
    }

    /**
     * Install module data
     * 
     * @return void
     */
    public function _arbitrary_installing()
    {
        // add entries for lang data updates
        foreach ($this->lang_dm_data as $lang_dm_data) {
            $this->CI->pg_language->add_dedicate_modules_entry($lang_dm_data);
        }

        // SEO
        $seo_data = array(
            'module_gid' => 'memberships',
            'model_name' => 'Memberships_model',
            'get_settings_method' => 'getSeoSettings',
            'get_rewrite_vars_method' => 'requestSeoRewrite',
            'get_sitemap_urls_method' => 'getSitemapXmlUrls',
        );
        $this->CI->pg_seo->set_seo_module('memberships', $seo_data);

        $this->installDemoContent();
    }

    protected function installDemoContent()
    {
        $demo_memberships = include MODULEPATH . 'memberships/install/demo_content.php';
        if (empty($demo_memberships)) {
            return false;
        } else {
            $this->CI->load->model('Memberships_model');
            $this->Memberships_model->installBatch($demo_memberships);
        }
    }

    /**
     * Import module languages
     * 
     * @param array $langs_ids languages identifiers
     * @return void
     */
    public function _arbitrary_lang_install($langs_ids = null)
    {
        $langs_file = $this->CI->Install_model->language_file_read("memberships", "arbitrary", $langs_ids);
        if (!$langs_file) {
            log_message("info", "Empty memberships arbitrary langs data");
            return false;
        }

        $post_data = array(
            'title' => $langs_file["seo_tags_index_title"],
            'keyword' => $langs_file["seo_tags_index_keyword"],
            'description' => $langs_file["seo_tags_index_description"],
            'header' => $langs_file["seo_tags_index_header"],
            'og_title' => $langs_file["seo_tags_index_og_title"],
            'og_type' => $langs_file["seo_tags_index_og_type"],
            'og_description' => $langs_file["seo_tags_index_og_description"],
        );
        $this->CI->pg_seo->set_settings('user', 'memberships', $page, $post_data);

        $post_data = array(
            'title' => $langs_file["seo_tags_form_title"],
            'keyword' => $langs_file["seo_tags_form_keyword"],
            'description' => $langs_file["seo_tags_form_description"],
            'header' => $langs_file["seo_tags_form_header"],
            'og_title' => $langs_file["seo_tags_form_og_title"],
            'og_type' => $langs_file["seo_tags_form_og_type"],
            'og_description' => $langs_file["seo_tags_form_og_description"],
        );
        $this->CI->pg_seo->set_settings('user', 'memberships', $page, $post_data);

        $post_data = array(
            'title' => $langs_file["seo_tags_my_title"],
            'keyword' => $langs_file["seo_tags_my_keyword"],
            'description' => $langs_file["seo_tags_my_description"],
            'header' => $langs_file["seo_tags_my_header"],
            'og_title' => $langs_file["seo_tags_my_og_title"],
            'og_type' => $langs_file["seo_tags_my_og_type"],
            'og_description' => $langs_file["seo_tags_my_og_description"],
        );
        $this->CI->pg_seo->set_settings('user', 'memberships', $page, $post_data);
    }

    /**
     * Export module languages
     * 
     * @param array $langs_ids languages identifiers
     * @return array
     */
    public function _arbitrary_lang_export($langs_ids = null)
    {
        if (!is_array($langs_ids)) {
            $langs_ids = (array) $langs_ids;
        }

        $arbitrary_return = array();

        $arbitrary_return = array();

        $lang_ids = array_keys($this->CI->pg_language->languages);
        $seo_settings = $this->pg_seo->get_all_settings_from_cache('user', 'memberships');

        $prefix = 'seo_tags_' . $seo_page['method'];
        foreach ($lang_ids as $lang_id) {
            $arbitrary_return['seo_tags_index_title'][$lang_id] = $seo_page['meta_' . $lang_id]['title'];
            $arbitrary_return['seo_tags_index_keyword'][$lang_id] = $seo_page['meta_' . $lang_id]['keyword'];
            $arbitrary_return['seo_tags_index_description'][$lang_id] = $seo_page['meta_' . $lang_id]['description'];
            $arbitrary_return['seo_tags_index_header'][$lang_id] = $seo_page['meta_' . $lang_id]['header'];
            $arbitrary_return['seo_tags_index_og_title'][$lang_id] = $seo_page['og_' . $lang_id]['og_title'];
            $arbitrary_return['seo_tags_index_og_type'][$lang_id] = $seo_page['og_' . $lang_id]['og_type'];
            $arbitrary_return['seo_tags_index_og_description'][$lang_id] = $seo_page['og_' . $lang_id]['og_description'];

            $arbitrary_return['seo_tags_form_title'][$lang_id] = $seo_page['meta_' . $lang_id]['title'];
            $arbitrary_return['seo_tags_form_keyword'][$lang_id] = $seo_page['meta_' . $lang_id]['keyword'];
            $arbitrary_return['seo_tags_form_description'][$lang_id] = $seo_page['meta_' . $lang_id]['description'];
            $arbitrary_return['seo_tags_form_header'][$lang_id] = $seo_page['meta_' . $lang_id]['header'];
            $arbitrary_return['seo_tags_form_og_title'][$lang_id] = $seo_page['og_' . $lang_id]['og_title'];
            $arbitrary_return['seo_tags_form_og_type'][$lang_id] = $seo_page['og_' . $lang_id]['og_type'];
            $arbitrary_return['seo_tags_form_og_description'][$lang_id] = $seo_page['og_' . $lang_id]['og_description'];

            $arbitrary_return['seo_tags_my_title'][$lang_id] = $seo_page['meta_' . $lang_id]['title'];
            $arbitrary_return['seo_tags_my_keyword'][$lang_id] = $seo_page['meta_' . $lang_id]['keyword'];
            $arbitrary_return['seo_tags_my_description'][$lang_id] = $seo_page['meta_' . $lang_id]['description'];
            $arbitrary_return['seo_tags_my_header'][$lang_id] = $seo_page['meta_' . $lang_id]['header'];
            $arbitrary_return['seo_tags_my_og_title'][$lang_id] = $seo_page['og_' . $lang_id]['og_title'];
            $arbitrary_return['seo_tags_my_og_type'][$lang_id] = $seo_page['og_' . $lang_id]['og_type'];
            $arbitrary_return['seo_tags_my_og_description'][$lang_id] = $seo_page['og_' . $lang_id]['og_description'];
        }

        return array("arbitrary" => $arbitrary_return);
    }

    /**
     * Uninstall module data
     * 
     * @return void
     */
    public function _arbitrary_deinstalling()
    {
        $this->CI->pg_seo->delete_seo_module('memberships');

        // delete entries in dedicate modules
        foreach ($this->lang_dm_data as $lang_dm_data) {
            $this->CI->pg_language->delete_dedicate_modules_entry(array('where' => $lang_dm_data));
        }
    }

}
