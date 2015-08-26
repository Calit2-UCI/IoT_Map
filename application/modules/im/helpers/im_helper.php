<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

if (!function_exists('im_chat_button')) {

    function im_chat_button()
    {
        $CI = &get_instance();
        $CI->load->model('Im_model');
        $im_status = $CI->Im_model->im_status(0);
        if (!$im_status['im_on']) {
            return false;
        }

        $data['user_name'] = '';
        $data['new_msgs'] = array('count_new' => 0, 'contacts' => array());
        $CI->load->model('users/models/Users_statuses_model');
        if ($CI->session->userdata('auth_type') == 'user') {
            $data['id_user'] = $CI->session->userdata('user_id');
            $data['user_status'] = $CI->Users_statuses_model->get_user_statuses($data['id_user']);
            if ($data['user_status']['current_site_status']) {
                $CI->load->model('im/models/Im_contact_list_model');
                $data['new_msgs'] = $CI->Im_contact_list_model->check_new_messages($data['id_user']);
            }
            $data['user_name'] = $CI->session->userdata('output_name');
        } else {
            $data['id_user'] = 0;
            $data['user_status'] = array(
                'current_site_status' => '',
                'current_site_status_text' => '',
                'site_status' => '',
            );
        }
        $data['statuses'] = array();
        foreach ($CI->Users_statuses_model->statuses as $key => $status) {
            $data['statuses'][$key]['val'] = $key;
            $data['statuses'][$key]['text'] = $status;
            $data['statuses'][$key]['lang'] = l('status_site_' . $key, 'users');
        }
        $data['age_lang'] = l('age', 'im');
        $data['history_lang'] = l('show_history', 'im');
        $data['clear_confirm_lang'] = l('clear_confirm', 'im');
        $CI->template_lite->assign('im_data', $data);
        $CI->template_lite->assign('im_json_data', json_encode($data));
        return $CI->template_lite->fetch('helper_im', 'user', 'im');
    }

}


if (!function_exists('im_chat_add_button')) {

    function im_chat_add_button($id_contact)
    {
        $CI = &get_instance();
        $CI->load->model('Im_model');
        $im_status = $CI->Im_model->im_status(0);
        if ($CI->session->userdata('auth_type') != 'user' || !$im_status['im_on']) {
            return false;
        }

        $user_id = $CI->session->userdata('user_id');

        $CI->load->model('im/models/Im_contact_list_model');
        $list[0] = is_array($id_contact) ? array('id_contact' => intval($id_contact['id_contact'])) : array('id_contact' => $id_contact);
        $data['contact_list']['list'] = $CI->Im_contact_list_model->format_list($list);
        $data['contact_list']['time'] = time();
        $data['id_contact'] = $list[0]['id_contact'];
        $data['id_user'] = $CI->session->userdata('user_id');
        $CI->template_lite->assign('im_data', $data);
        $CI->template_lite->assign('im_json_data', json_encode($data));
        return $CI->template_lite->fetch('helper_im_add', 'user', 'im');
    }

}
