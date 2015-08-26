<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

if (!function_exists('check_access')) {

    function check_access()
    {
        if (INSTALL_MODULE_DONE) {
            $CI = &get_instance();
            $module = $CI->router->fetch_class();
            $controller = $CI->router->fetch_class(true);
            $method = $CI->router->fetch_method();


            if (!$CI->pg_module->is_module_installed($module) && $module != 'install') {
                if (!$CI->router->is_api_class) {
                    show_404();
                } else {
                    $CI->set_api_content('code', 404);
                }
            }

            $is_private_method = substr($method, 0, 1) == '_';
            $access = $CI->pg_module->get_module_method_access($module, $controller, $method);
            if (!$access && !$is_private_method && $CI->pg_module->is_module_method_exists($module, $controller, $method)) {
                $access = 2;
                $CI->pg_module->set_module_methods_access($module, $controller, array($method => $access));
            }

            if (!$access) {
                if (!$CI->router->is_api_class) {
                    show_404();
                } else {
                    $CI->set_api_content('code', 404);
                }
            }

            $auth_type = $CI->session->userdata("auth_type");
            if ($auth_type == 'user') {
                $CI->load->model('users/models/Auth_model');
                if (!$CI->Auth_model->update_user_session_data($CI->session->userdata("user_id"))) {
                    $CI->session->sess_destroy();
                    $auth_type = '';
                }
            }

            switch ($auth_type) {
                case "module": $allow = ($access <= 1 || $access == 4);
                    break;
                case "admin": $allow = ($access <= 1 || $access == 3);
                    break;
                case "user": $allow = ($access <= 2);
                    break;
                default: $allow = ($access <= 1);
            }

            if (!$allow) {
                if (!$CI->router->is_api_class) {
                    if ($CI->input->is_ajax_request() && !$CI->is_pjax) {
                        $return = array('errors' => 'ajax_login_link');
                        exit(json_encode($return));
                    } else {
                        redirect(site_url() . 'users/login_form');
                    }
                } else {
                    $CI->set_api_content('code', 403);
                }
            }
        }
    }

}