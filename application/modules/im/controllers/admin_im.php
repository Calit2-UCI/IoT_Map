<?php

/**
 * IM admin side controller
 *
 * @package PG_DatingPro
 * @subpackage Kisses
 * @category	controllers
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * */
class Admin_Im extends Controller
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Menu_model');
        $this->Menu_model->set_menu_active_item('admin_menu', 'add_ons_items');
    }

    /**
     * Main page
     */
    public function index()
    {
        return $this->settings();
    }

    /**
     * Setting module
     * 
     * @return template
     */
    public function settings()
    {

        $this->system_messages->set_data('header', l('admin_header_im_settings', 'im'));

        $data = array(
            'status' => $this->pg_module->get_module_config('im', 'status'),
            'message_max_chars' => $this->pg_module->get_module_config('im', 'message_max_chars'),
        );

        if ($this->input->post('btn_save')) {

            $data['status'] = $this->input->post('status', true);
            $data['message_max_chars'] = $this->input->post('message_max_chars', true);

            foreach ($data as $setting => $value) {
                $this->pg_module->set_module_config('im', $setting, (int) $value);
            }

            $this->system_messages->add_message('success', l('success_update', 'im'));
        }

        $this->system_messages->set_data('header', l('admin_header_im_chat_settings', 'im'));

        $this->template_lite->assign('settings_data', $data);
        $this->template_lite->view('settings');
    }

}
