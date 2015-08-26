<?php

if (!function_exists('services_buy_list')) {

    function services_buy_list($params = array())
    {
        $CI = & get_instance();
        $CI->load->model('Services_model');
        
        $where['where']['type'] = 'tariff';
        $where['where']['status'] = 1;
        
        if (!empty($params['template_gid'])) {
            $where['where']['template_gid'] = $params['template_gid'];
        }
        
        $services = $CI->Services_model->get_service_list($where);
        $services_modules = array();
        foreach ($services as $key => $service) {
            if ($service['template']['price_type'] > 2) {
                unset($services[$key]);
            } else {
                $model = strtolower($service['template']['callback_model']);
                $services_modules[$service['template']['callback_module']][$model] = ucfirst($model);
            }
        }
        /*$buy_gids = array();
        foreach ($services_modules as $module => $models) {
            foreach ($models as $model) {
                $CI->load->model("$module/models/$model");
                if (!empty($CI->$model->services_buy_gids)) {
                    $buy_gids = array_merge($buy_gids, $CI->$model->services_buy_gids);
                }
            }
        }
        foreach ($services as $key => $service) {
            if (!in_array($service['gid'], $buy_gids)) {
                unset($services[$key]);
            }
        }*/

        $CI->template_lite->assign('services_block_services', $services);
        return $CI->template_lite->fetch('helper_services_buy_list', 'user', 'services');
    }

}

if (!function_exists('user_services_list')) {

    function user_services_list($params)
    {
        $CI = & get_instance();
        $CI->load->model('services/models/Services_users_model');
        
        if (empty($params['id_user'])) {
            if ($CI->session->userdata('auth_type') == 'user') {
                $params['id_user'] = $CI->session->userdata('user_id');
            } else {
                return '';
            }
        }
        
        $order_by = array(
            'status' => 'DESC',
            'count' => 'DESC',
            'date_created' => 'DESC'
        );
        $where = array();
        $where['where_sql'][] = "id_user = {$params['id_user']} AND (id_users_membership = '0' AND (id_users_package = '0' OR status = '0'))";
        
        if (!empty($params['template_gid'])) {
            $where['where']['template_gid'] = $params['template_gid'];
        }
        
        $services = $CI->Services_users_model->get_services_list($where, $order_by);
        $CI->template_lite->assign('services_block_services', $services);
        $date_formats = array(
            'date_format' => $CI->pg_date->get_format('date_literal', 'st'),
            'date_time_format' => $CI->pg_date->get_format('date_time_literal', 'st')
        );
        $CI->template_lite->assign('services_block_date_formats', $date_formats);
        return $CI->template_lite->fetch('helper_user_services_list', 'user', 'services');
    }

}

if (!function_exists('service_form')) {

    function service_form($params)
    {
        $CI = & get_instance();
        if (empty($params['gid'])) {
            log_message('error', '(services) Empty $params["gid"]');
            show_404();
            return;
        }
        $CI->load->model('Services_model');
        $user_id = $CI->session->userdata("user_id");
        $CI->load->model('users/models/Auth_model');
        $CI->Auth_model->update_user_session_data($user_id);
        $data = $CI->Services_model->format_service($CI->Services_model->get_service_by_gid($params['gid']));
        if ($data["template"]["price_type"] == "2" || $data["template"]["price_type"] == "3") {
            $data["price"] = $CI->input->post('price', true);
        }
        if (!empty($data["template"]["data_user_array"])) {
            foreach ($data["template"]["data_user_array"] as $gid => $temp) {
                $value = "";
                if ($temp["type"] == "hidden") {
                    $value = $CI->input->get_post($gid, true);
                }
                if (isset($user_form_data[$gid])) {
                    $value = $user_form_data[$gid];
                }
                $data["template"]["data_user_array"][$gid]["value"] = $value;
            }
        }
        //// get payments types
        $data["free_activate"] = false;
        if ($data["price"] <= 0) {
            $data["free_activate"] = true;
        }
        if ($data["pay_type"] == 1 || $data["pay_type"] == 2) {
            $CI->load->model("Users_payments_model");
            $data["user_account"] = $CI->Users_payments_model->get_user_account($user_id);
            if ($data["user_account"] <= 0 && $data["price"] > 0) {
                $data["disable_account_pay"] = true;
            } elseif (($data["template"]["price_type"] == 1 || $data["template"]["price_type"] == 3) && $data["price"] > $data["user_account"]) {
                $data["disable_account_pay"] = true;
            }
        }
        if ($data["pay_type"] == 2 || $data["pay_type"] == 3) {
            $CI->load->model("payments/models/Payment_systems_model");
            $billing_systems = $CI->Payment_systems_model->get_active_system_list();
            $CI->template_lite->assign('billing_systems', $billing_systems);
        }
        $CI->template_lite->assign('is_module_installed', $CI->pg_module->is_module_installed('users_payments'));
        $CI->template_lite->assign('data', $data);
        return $CI->template_lite->fetch('helper_service_form', 'user', 'services');
    }

}
