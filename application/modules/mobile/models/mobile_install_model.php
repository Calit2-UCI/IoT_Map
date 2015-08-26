<?php

namespace Pg\Modules\Mobile\Models;

use Pg\Modules\Mobile\Models\Mobile_model;

/**
 * Mobile install model
 *
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * */
class Mobile_install_model extends \Model
{

    protected $CI;

    /**
     * Menu configuration
     */
    protected $menu = array(
        'admin_menu' => array(
            'action' => 'none',
            'name' => 'Mobile section menu',
            'items' => array(
                'content_items' => array(
                    'action' => 'none',
                    'name' => '',
                    'items' => array(
                        'add_ons_items' => array(
                            'action' => 'none',
                            'name' => '',
                            'items' => array(
                                'mobile_menu_item' => array(
                                    'action' => 'create',
                                    'link' => 'admin/mobile',
                                    'status' => 1,
                                    'sorter' => 9
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    );

    public function __construct()
    {
        parent::__construct();
        $this->CI = get_instance();
    }

    protected function setPaths()
    {
        $mobile_path = SITE_PHYSICAL_PATH . 'm/';
        $files = array(
            array(
                'path' => $mobile_path . 'index.html',
                'replace' => array(
                    '[m_subfolder]' => '/' . SITE_SUBFOLDER . 'm'
                )
            ),
            array(
                'path' => $mobile_path . 'scripts/app.js',
                'replace' => array(
                    '[api_virtual_path]' => SITE_VIRTUAL_PATH . 'api'
                )
            )
        );
        foreach ($files as $file) {
            $file_contents = file_get_contents($file['path']);
            if ($file_contents) {
                $file_contents = str_replace(array_keys($file['replace']), array_values($file['replace']), $file_contents);
                file_put_contents($file['path'], $file_contents);
            }
        }
    }

    public function _arbitrary_installing()
    {
        $this->setPaths();
    }

    /**
     * Install menu data
     */
    public function install_menu()
    {
        $this->CI->load->helper('menu');
        foreach ($this->menu as $gid => $menu_data) {
            $this->menu[$gid]['id'] = linked_install_set_menu($gid, $menu_data['action'], $menu_data['name']);
            linked_install_process_menu_items($this->menu, 'create', $gid, 0, $this->menu[$gid]['items']);
        }
    }

    /**
     * Install menu languages
     */
    public function install_menu_lang_update($langs_ids = null)
    {
        if (empty($langs_ids)) {
            return false;
        }
        $langs_file = $this->CI->Install_model->language_file_read(Mobile_model::MODULE_GID, 'menu', $langs_ids);

        if (!$langs_file) {
            log_message('info', 'Empty menu langs data');
            return false;
        }

        $this->CI->load->helper('menu');

        foreach ($this->menu as $gid => $menu_data) {
            linked_install_process_menu_items($this->menu, 'update', $gid, 0, $this->menu[$gid]['items'], $gid, $langs_file);
        }
        return true;
    }

    /**
     * Export menu languages
     */
    public function install_menu_lang_export($langs_ids)
    {
        if (empty($langs_ids)) {
            return false;
        }
        $this->CI->load->helper('menu');

        $return = array();
        foreach ($this->menu as $gid => $menu_data) {
            $temp = linked_install_process_menu_items($this->menu, 'export', $gid, 0, $this->menu[$gid]['items'], $gid, $langs_ids);
            $return = array_merge($return, $temp);
        }
        return array('menu' => $return);
    }

    /**
     * Uninstall menu languages
     */
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

}
