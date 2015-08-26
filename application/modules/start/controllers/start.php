<?php

namespace Pg\Modules\Start\Controllers;

/**
 * Start user side controller
 *
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * */
Class Start extends \Controller
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Menu_model');
    }

    public function index()
    {
        if ($this->session->userdata("auth_type") == "user" && $this->session->userdata('user_id')) {
            redirect(site_url() . "start/homepage");
        }
        $this->session->set_userdata('demo_user_type', 'user');
        $this->template_lite->view('index');
    }

    public function homepage()
    {
        $this->load->model('Users_model');
        $this->Menu_model->breadcrumbs_set_parent('user-main-home-item');
        $this->template_lite->assign('user_id', $this->session->userdata('user_id'));
        $this->template_lite->view('homepage');
    }

    public function error()
    {

        $this->Menu_model->breadcrumbs_set_active(l('header_error', 'start'));
        $this->template_lite->view('error');
    }

    public function print_version()
    {
        echo $this->pg_module->get_module_config('start', 'product_version');
    }

    // test methods
    public function test_file_upload()
    {

        $this->load->model("file_uploads/models/File_uploads_config_model");

        $configs = $this->File_uploads_config_model->get_config_list();
        $this->template_lite->assign('configs', $configs);

        if ($this->input->post('btn_save') && $this->input->post('config')) {
            $config = $this->input->post('config');
            $file_name = 'file';

            if (isset($_FILES[$file_name]) && is_array($_FILES[$file_name]) && is_uploaded_file($_FILES[$file_name]["tmp_name"])) {
                $this->load->model("File_uploads_model");
                $return = $this->File_uploads_model->upload($config, '', $file_name);

                if (!empty($return["errors"])) {
                    $this->system_messages->add_message('error', $return["errors"]);
                } else {
                    $this->system_messages->add_message('success', $return["file"]);
                }
            }
        }

        $this->template_lite->view('test_file_upload');
    }

    public function demo($type = 'user')
    {
        $this->session->set_userdata('demo_user_type', $type);
        redirect();
    }

    public function ajax_backend()
    {
        $data = (array) $this->input->post('data');
        $user_session_id = ($this->session->userdata('auth_type') == 'user') ? intval($this->session->userdata('user_id')) : 0;
        $return_arr['user_session_id'] = $user_session_id;
        foreach ($data as $gid => $params) {
            $return_arr[$gid] = array();
            if (
                !(empty($params['module']) && empty($params['model']) && empty($params['method'])) && $this->pg_module->is_module_installed($params['module']) && $this->load->model($params['module'] . '/models/' . $params['model'], $gid . '_backend_model', false, true, true) && method_exists($this->{$gid . '_backend_model'}, 'backend_' . $params['method'])
            ) {
                $return_arr[$gid] = $this->{$gid . '_backend_model'}->{'backend_' . $params['method']}($params);
                $return_arr[$gid]['user_session_id'] = $user_session_id;
            }
        }

        exit(json_encode($return_arr));
    }

    public function multi_request_script()
    {
        //$js = file_get_contents(APPPATH.'modules/users_lists/js/users_lists_multi_request.js');
        $js = file_get_contents(APPPATH . 'modules/friendlist/js/friendlist_multi_request.js');
        //header('Content-Type: text/javascript');
        echo $js;
    }

}
