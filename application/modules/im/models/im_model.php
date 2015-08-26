<?php

namespace Pg\Modules\Im\Models;

/**
 * IM model
 *
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @author Dmitry Popenov
 * @version $Revision: 2 $ $Date: 2013-01-30 10:07:07 +0400 $
 * */
if (!defined('TABLE_IM')) {
    define('TABLE_IM', DB_PREFIX . 'im');
}

class Im_model extends \Model
{

    private $CI;
    private $DB;
    private $fields = array(
        'id_user',
        'date_add',
        'date_update',
        'date_end',
    );
    private $fields_str;
    public $services_buy_gids = array('im');

    public function __construct()
    {
        parent::__construct();
        $this->CI = &get_instance();
        $this->DB = &$this->CI->db;
        $this->fields_str = implode(', ', $this->fields);
    }

    public function get_user_im($id_user)
    {
        $result = $this->DB->select($this->fields_str)->from(TABLE_IM)->where('id_user', $id_user)->get()->result_array();
        $return = $result ? $result[0] : array();
        return $return;
    }

    private function _save($data)
    {
        $update_str = '';
        $fields_upd = array();
        foreach ($data as $field => $val) {
            $fields_upd[] = "`{$field}` = " . $this->DB->escape($val);
        }
        $update_str = implode(', ', $fields_upd);
        $sql = $this->DB->insert_string(TABLE_IM, $data) . " ON DUPLICATE KEY UPDATE {$update_str}";
        $this->DB->query($sql);

        return $this->DB->affected_rows();
    }

    public function im_status($id_user)
    {
        $result['id_user'] = $this->CI->session->userdata('auth_type') == 'user' ? $this->CI->session->userdata('user_id') : 0;
        $result['im_on'] = $this->pg_module->get_module_config('im', 'status');
        $result['im_service_access'] = 0;
        if ($result['im_on'] && $this->pg_module->is_module_installed('services')) {
            $this->CI->load->model('Services_model');
            $im_service_access = $this->CI->Services_model->get_service_by_gid('im');
            if (!empty($im_service_access['status'])) {
                $user_im = $this->get_user_im($id_user);
                if ($user_im && strtotime($user_im['date_end']) > time()) {
                    $result['im_service_access'] = 1;
                }
            } else {
                $result['im_service_access'] = 1;
            }
        }
        return $result;
    }

    public function service_available_im_action($id_user)
    {
        $return['available'] = 0;
        $return['content'] = '';
        $return['content_buy_block'] = false;

        $this->CI->load->model('Services_model');
        $services_params['where']['gid'] = 'im';
        $services_params['where']["status"] = 1;
        $services_count = $this->CI->Services_model->get_service_count($services_params);
        if ($services_count) {
            $return['available'] = 0;
            $return['content_buy_block'] = true;
        } else {
            $return['content'] = 'services not found';
            $return['available'] = 1;
        }

        return $return;
    }

    public function service_validate_im($id_user, $data, $service_data = array(), $price = '')
    {
        $return = array('errors' => array(), 'data' => $data);
        return $return;
    }

    public function service_buy_im($id_user, $price, $service, $template, $payment_data, $users_package_id = 0, $count = 1)
    {
        $service_data = array(
            'id_user' => $id_user,
            'service_gid' => $service['gid'],
            'template_gid' => $template['gid'],
            'service' => $service,
            'template' => $template,
            'payment_data' => $payment_data,
            'id_users_package' => $users_package_id,
            'id_users_membership' => !empty($payment_data['id_users_membership']) ? (int) $payment_data['id_users_membership'] : 0,
            'status' => 1,
            'count' => $count,
        );
        $this->CI->load->model('services/models/Services_users_model');
        return $this->CI->Services_users_model->save_service(null, $service_data);
    }

    public function service_activate_im($id_user, $id_user_service)
    {
        $id_user_service = intval($id_user_service);
        $return = array('status' => 0, 'message' => '');

        $this->CI->load->model('services/models/Services_users_model');
        $user_service = $this->CI->Services_users_model->get_user_service_by_id($id_user, $id_user_service);
        if (empty($user_service) || !$user_service["status"] || $user_service['count'] < 1 
            || !isset($user_service['service']['data_admin']['period'])) {
            $return['status'] = 0;
            $return['message'] = l('error_service_activating', 'services');
        } else {
            $user_service['date_expired'] = $this->service_set_im($id_user, $user_service['service']['data_admin']['period']);
            if (--$user_service['count'] < 1) {
                $user_service['status'] = 0;
                $user_service['count'] = 0;
            }
            $this->CI->Services_users_model->save_service($id_user_service, $user_service);
            $return['status'] = 1;
            $return['message'] = l('success_service_activating', 'services');
        }
        return $return;
    }

    /**
     * 
     * @param int $id_user
     * @param int $period
     * @return type
     */
    public function service_set_im($id_user, $period)
    {
        if (empty($id_user)) {
            throw new \Exception('Empty user id');
        } elseif (empty($period)) {
            throw new \Exception('Empty period');
        }
        if ($user_im = $this->get_user_im($id_user)) {
            if (strtotime($user_im['date_end']) > time()) {
                $data['date_end'] = date('Y-m-d H:i:s', strtotime($user_im['date_end'] . ' +' . $period . ' day'));
            } else {
                $data['date_end'] = date('Y-m-d H:i:s', strtotime('+' . $period . ' day'));
            }
        } else {
            $data['date_add'] = date('Y-m-d H:i:s');
            $data['date_end'] = date('Y-m-d H:i:s', strtotime('+' . $period . ' day'));
        }
        $data['date_update'] = date('Y-m-d H:i:s');
        $data['id_user'] = $id_user;
        $result = $this->_save($data);
        return $data['date_end'];
    }

	/**
     *  Module category action
     *  @return array
     */
    public function moduleCategoryAction()
    {
        $action = array(
            'name' => l('im_chat', 'im'),
            'helper' => 'im_chat_add_button',
        );
        return $action;
    }

}
