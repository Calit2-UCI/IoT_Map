<?php

/**
 * IM controller
 *
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @author Dmitry Popenov
 * @version $Revision: 2 $ $Date: 2013-01-30 10:07:07 +0400 $
 * */
class Class_im extends Controller
{

    protected $user_id;

    public function __construct()
    {
        parent::__construct();
        $this->user_id = intval($this->session->userdata('user_id'));
        $this->load->model('im/models/Im_model');
        $this->load->model('im/models/Im_contact_list_model');
        $this->load->model('im/models/Im_messages_model');
    }

    protected function _check_new_messages()
    {
        $im_status = $this->Im_model->im_status($this->user_id);
        if ($im_status['im_on'] && $im_status['im_service_access']) {
            $result = $this->Im_contact_list_model->check_new_messages($this->user_id);
        }
        $result['im_status'] = $im_status;
        return $result;
    }

    protected function _get_contact_list($with_messages = false)
    {
        $im_status = $this->Im_model->im_status($this->user_id);
        if ($im_status['im_on'] && $im_status['im_service_access']) {
            $params['formatted'] = intval($this->input->get_post('formatted'));
            $result = $this->Im_contact_list_model->backend_get_contact_list($params);
        }
        if ($with_messages) {
            $result['im_messages'] = $this->Im_messages_model->get_last_messages($this->user_id);
        }
        $result['im_status'] = $im_status;
        return $result;
    }

    protected function _set_site_status()
    {
        $site_status = intval($this->input->get_post('site_status'));
        $this->load->model('users/models/Users_statuses_model');
        $result = $this->Users_statuses_model->set_status($this->user_id, $site_status);
        return $result;
    }

    protected function _get_im_status()
    {
        return $this->Im_model->im_status($this->user_id);
    }

    private function get_messages($type = '')
    {
        $id_contact = intval($this->input->get_post('id_contact', true));
        $from_id = ($type == 'history') ? intval($this->input->get_post('min_id', true)) : intval($this->input->get_post('max_id', true));
        if ($type == 'history') {
            $result['msg'] = $this->Im_messages_model->get_history($this->user_id, $id_contact, $from_id);
        } else {
            if (!$from_id) {
                $count = filter_input(INPUT_POST, 'count', FILTER_VALIDATE_INT);
                $result['msg'] = $this->Im_messages_model->get_last_messages($this->user_id, $id_contact, $count);
            } else {
                $result['msg'] = $this->Im_messages_model->get_new_messages($this->user_id, $id_contact, $from_id);
            }
        }
        $this->Im_messages_model->check_is_read($this->user_id, $id_contact);

        $result['min_id'] = $result['max_id'] = 0;
        foreach ($result['msg'] as $msg) {
            if (intval($msg['id']) > $result['max_id']) {
                $result['max_id'] = intval($msg['id']);
            }
            if (intval($msg['id']) < $result['min_id'] || !$result['min_id']) {
                $result['min_id'] = intval($msg['id']);
            }
        }

        if (!$from_id || $type == 'history') {
            if (!$result['min_id']) {
                $result['history_exists'] = 0;
            } else {
                $result['history_exists'] = count($this->Im_messages_model->get_history($this->user_id, $id_contact, $result['min_id'], 1));
            }
        }

        return $result;
    }

    protected function _get_messages()
    {
        $im_status = $this->Im_model->im_status($this->user_id);
        if ($im_status['im_on'] && $im_status['im_service_access']) {
            $result = $this->get_messages();
        }
        $result['im_status'] = $im_status;
        return $result;
    }

    protected function _post_message()
    {
        $result = array();
        $post_access = true;
        $im_status = $this->Im_model->im_status($this->user_id);
        if ($im_status['im_on'] && $im_status['im_service_access']) {
            $text = trim(strip_tags($this->input->post('text', true)));
            $id_contact = intval($this->input->post('id_contact', true));
            if ($this->pg_module->is_module_installed('blacklist')) {
                $this->load->model('Blacklist_model');
                // if you are in blacklist
                if ($this->Blacklist_model->is_blocked($id_contact, $this->user_id)) {
                    $post_access = false;
                    $result['errors'][] = l('post_denied', 'im');
                    // if user in your blacklist
                } elseif ($this->Blacklist_model->is_blocked($this->user_id, $id_contact)) {
                    $result['notices'][] = l('user_cant_answer', 'im');
                }
            }
            if ($post_access) {
                $result = array_merge_recursive($this->Im_messages_model->add_message($this->user_id, $id_contact, $text), $result);
                $result['messages'] = $this->get_messages();
            }
        }
        $result['im_status'] = $im_status;
        return $result;
    }

    protected function _get_history()
    {
        $im_status = $this->Im_model->im_status($this->user_id);
        if ($im_status['im_on'] && $im_status['im_service_access']) {
            $result = $this->get_messages('history');
        }
        $result['im_status'] = $im_status;
        return $result;
    }

    protected function _clear_history()
    {
        $id_contact = intval($this->input->get_post('id_contact', true));
        $result = $this->Im_messages_model->delete_messages($this->user_id, $id_contact);
        return $result;
    }

    protected function _get_init()
    {
        $data['new_msgs'] = array('count_new' => 0, 'contacts' => array());
        if ($this->session->userdata('auth_type') == 'user') {
            $data['id_user'] = $this->session->userdata('user_id');
            $this->load->model('users/models/Users_statuses_model');
            $data['user_status'] = $this->Users_statuses_model->get_user_statuses($data['id_user']);
            if ($data['user_status']['current_site_status']) {
                $this->load->model('im/models/Im_contact_list_model');
                $data['new_msgs'] = $this->Im_contact_list_model->check_new_messages($data['id_user']);
            }
            $data['user_name'] = $this->session->userdata('output_name');
        } else {
            $data['id_user'] = 0;
        }
        return $data;
    }

}
