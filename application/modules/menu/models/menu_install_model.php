<?php

namespace Pg\Modules\Menu\Models;

/**
 * Menu install model
 *
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * */
class Menu_install_model extends \Model
{

    protected $CI;
    protected $menu = array(
        // admin menu
        'admin_menu' => array(
            "action" => "none",
            "items" => array(
                'interface-items' => array(
                    "action" => "none",
                    "items" => array(
                        'admin-menus-item' => array("action" => "create", 'link' => 'admin/menu', 'icon' => '', 'status' => 1, 'sorter' => 4),
                    )
                )
            )
        )
    );
    protected $moderators_methods = array(
        array("module" => 'menu', 'method' => 'index', 'is_default' => 1),
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
        $this->CI->load->model('Install_model');
    }

    /*
     * Moderators module methods
     *
     */
    public function install_moderators()
    {
        // install moderators permissions
        $this->CI->load->model("moderators/models/Moderators_model");

        foreach ($this->moderators_methods as $method) {
            $this->CI->Moderators_model->save_method(null, $method);
        }
    }

    public function install_moderators_lang_update($langs_ids = null)
    {
        $langs_file = $this->CI->Install_model->language_file_read('menu', 'moderators', $langs_ids);

        // install moderators permissions
        $this->CI->load->model("moderators/models/Moderators_model");
        $params['where']['module'] = 'menu';
        $methods = $this->CI->Moderators_model->get_methods_lang_export($params);

        foreach ($methods as $method) {
            if (!empty($langs_file[$method['method']])) {
                $this->CI->Moderators_model->save_method($method["id"], array(), $langs_file[$method['method']]);
            }
        }
    }

    public function install_moderators_lang_export($langs_ids)
    {
        $this->CI->load->model("moderators/models/Moderators_model");
        $params['where']['module'] = 'menu';
        $methods = $this->CI->Moderators_model->get_methods_lang_export($params, $langs_ids);
        foreach ($methods as $method) {
            $return[$method['method']] = $method['langs'];
        }
        return array('moderators' => $return);
    }

    public function deinstall_moderators()
    {
        // delete moderation methods in moderators
        $this->CI->load->model("moderators/models/Moderators_model");
        $params['where']['module'] = 'menu';
        $this->CI->Moderators_model->delete_methods($params);
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
        $langs_file = $this->CI->Install_model->language_file_read('menu', 'menu', $langs_ids);

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

    function _arbitrary_installing()
    {
        $lang_dm_data = array(
            'module' => 'menu',
            'model' => 'Indicators_model',
            'method_add' => 'lang_dedicate_module_callback_add',
            'method_delete' => 'lang_dedicate_module_callback_delete'
        );
        $this->CI->pg_language->add_dedicate_modules_entry($lang_dm_data);
        $this->CI->load->model("menu/models/Indicators_model");
        foreach ($this->CI->pg_language->languages as $value) {
            $this->CI->Indicators_model->lang_dedicate_module_callback_add($value['id']);
        }
        return;
    }

    function _arbitrary_deinstalling()
    {
        $lang_dm_data['where'] = array(
            'module' => 'menu',
            'model' => 'Indicators_model',
        );
        $this->CI->pg_language->delete_dedicate_modules_entry($lang_dm_data);
    }

    public function install_cronjob()
    {
        // Remove old indicators
        $this->CI->load->model('Cronjob_model');
        $cron_data = array(
            'name' => 'Remove old menu indicators',
            'module' => 'menu',
            'model' => 'Indicators_model',
            'method' => 'delete_old',
            'cron_tab' => '0 3 * * *',
            'status' => '1'
        );
        $this->CI->Cronjob_model->save_cron(null, $cron_data);
    }

    public function deinstall_cronjob()
    {
        $this->CI->load->model('Cronjob_model');
        $cron_data = array();
        $cron_data['where']['module'] = 'menu';
        $this->CI->Cronjob_model->delete_cron_by_param($cron_data);
    }

}
