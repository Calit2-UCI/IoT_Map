<?php

namespace Pg\Modules\Mobile\Models;

/**
 * Mobile main model
 *
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Mobile_model extends \Model
{

    const MODULE_GID = 'mobile';

    private $settings_keys = array(
        'ios_url',
        'android_url',
    );
    protected $CI;
    protected $DB;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->DB = &$this->CI->db;
    }

    /**
     * Backend method to get menu indicators.
     *
     * @param array $params
     * @return array
     */
    public function backend_get_indicators($params)
    {
        $this->id_user = $params['id_user'];
        $results = array();
        foreach ($params['indicators'] as $indicator) {
            if (method_exists($this, $indicator)) {
                // TODO: refactor to use modules own methods
                $results[$indicator] = $this->$indicator();
            }
        }
        return $results;
    }

    private function new_messages()
    {
        $this->CI->load->model('im/models/Im_messages_model');
        return $this->CI->Im_messages_model->get_unread_count($this->id_user, 'i');
    }

    private function new_friends()
    {
        if ($this->pg_module->is_module_installed('friendlist')) {
            $this->CI->load->model('Friendlist_model');
            return $this->CI->Friendlist_model->get_list_count($this->id_user, 'request_in');
        }
    }

    public function getSettings($force = false)
    {
        if ($force || empty($this->settings)) {
            foreach ($this->settings_keys as $settings_key) {
                $this->settings[$settings_key] = $this->CI->pg_module->get_module_config(self::MODULE_GID, $settings_key);
            }
        }
        return $this->settings;
    }

    public function setSettings($settings)
    {
        foreach ($settings as $key => $value) {
            if (in_array($key, $this->settings_keys)) {
                $this->pg_module->set_module_config(self::MODULE_GID, $key, $value);
            }
        }
    }

}
