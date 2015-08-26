<?php

namespace Pg\Modules\Countries\Models;

/**
 * Countries install model
 *
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * */
class Countries_install_model extends \Model
{

    private $CI;
    private $menu = array(
        'admin_menu' => array(
            'action' => 'none',
            'items' => array(
                'settings_items' => array(
                    'action' => 'none',
                    'items' => array(
                        'content_items' => array(
                            'action' => 'none',
                            'items' => array(
                                'countries_menu_item' => array('action' => 'create', 'link' => 'admin/countries', 'status' => 1, 'sorter' => 1)
                            )
                        )
                    )
                )
            )
        ),
        'admin_countries_menu' => array(
            'action' => 'create',
            'name' => 'Countries section menu',
            'items' => array(
                'countries_list_item' => array('action' => 'create', 'link' => 'admin/countries', 'status' => 1),
                'countries_install_item' => array('action' => 'create', 'link' => 'admin/countries/install', 'status' => 1)
            )
        )
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

    public function install_menu()
    {
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
        $langs_file = $this->CI->Install_model->language_file_read('countries', 'menu', $langs_ids);

        if (!$langs_file) {
            log_message('info', 'Empty menu langs data');
            return false;
        }

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
        $this->CI->load->helper('menu');
        foreach ($this->menu as $gid => $menu_data) {
            if ($menu_data['action'] == 'create') {
                linked_install_set_menu($gid, 'delete');
            } else {
                linked_install_delete_menu_items($gid, $this->menu[$gid]['items']);
            }
        }
    }

    public function _arbitrary_installing()
    {
        // add entries for lang data updates
        $lang_dm_data = array(
            'module' => 'countries',
            'model' => 'Countries_model',
            'method_add' => 'lang_dedicate_module_callback_add',
            'method_delete' => 'lang_dedicate_module_callback_delete'
        );
        $this->CI->pg_language->add_dedicate_modules_entry($lang_dm_data);

        foreach ($this->CI->pg_language->languages as $id => $value) {
            $this->CI->Countries_model->lang_dedicate_module_callback_add($value['id']);
        }
        return;
    }

    public function _arbitrary_deinstalling()
    {

        // delete entries in dedicate modules
        $lang_dm_data['where'] = array(
            'module' => 'countries',
            'model' => 'Countries_model',
        );
        $this->CI->pg_language->delete_dedicate_modules_entry($lang_dm_data);
    }

}
