<?php

use Pg\Modules\Mobile\Models\Mobile_model;

/**
 * Mobile version admin controller
 *
 * @package PG_Dating
 * @subpackage application
 * @category modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * */
Class Admin_Mobile extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('PG_Output');
        $this->load->model('Mobile_model');

        $this->load->model('Menu_model');
        $this->Menu_model->set_menu_active_item('admin_menu', 'add_ons_items');
        $this->system_messages->set_data('header', l('admin_header_mobile', Mobile_model::MODULE_GID));
    }

    public function index()
    {
        if(filter_input(INPUT_POST, 'btn_save')) {
            $validation_args = array(
                'android_url' => FILTER_SANITIZE_URL,
                'ios_url' => FILTER_SANITIZE_URL,
            );
            $settings = filter_input_array(INPUT_POST, $validation_args);
            $this->Mobile_model->setSettings($settings);
        } else {
            $settings = $this->Mobile_model->getSettings();
        }

        $this->pg_output->setOutputData('mobile_settings', $settings);
    }

}
