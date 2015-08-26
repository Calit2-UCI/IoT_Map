<?php

/**
 * Services api controller
 *
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * */
Class Api_Services extends Controller
{

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->load->model("Services_model");
    }

    function form()
    {
        $user_id = $this->session->userdata('user_id');
        $service_gid = filter_input(INPUT_POST, 'service_gid');

        $this->load->model('users/models/Auth_model');
        $this->Auth_model->update_user_session_data($user_id);

        $data = $this->Services_model->get_service_by_gid($service_gid);

        if (!$data['status']) {
            log_message('error', 'services API: Wrong service status');
            $this->set_api_content('errors', l('error_service_code_incorrect', 'services'));
            return false;
        }

        $data = array_shift($this->Services_model->format_service(array($data)));
        $data['template'] = $this->Services_model->format_template($data['template']);

        if (!empty($data['data_admin_array'])) {
            foreach ($data['template']['data_admin_array'] as $gid => $temp) {
                if (!empty($data['data_admin_array'][$gid])) {
                    $data['template']['data_admin_array'][$gid]['value'] = $data['data_admin_array'][$gid];
                }
            }
        }

        if ($data['template']['price_type'] == '2' || $data['template']['price_type'] == '3') {
            $data['price'] = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_INT);
        }
        $errors = array();
        $messages = array();
        $type = filter_input(INPUT_POST, 'payment_type', FILTER_SANITIZE_STRING);
        $user_form_data = filter_input(INPUT_POST, 'data_user', FILTER_REQUIRE_ARRAY);
        if ($type) {
            $service_return = $this->Services_model->validate_service_payment($data['id'], $user_form_data, $data['price']);
            if (!empty($service_return['errors'])) {
                $errors[] = $service_return['errors'];
            } else {
                $origin_return = $this->Services_model->validate_service_original_model($data['id'], $user_form_data, $user_id, $data['price']);
                if (!empty($origin_return['errors'])) {
                    $errors[] = $origin_return['errors'];
                } else {
                    $activate_immediately = filter_input(INPUT_POST, 'activate_immediately', FILTER_VALIDATE_BOOLEAN);
                    if ('account' === $type) {
                        $payment = $this->Services_model->account_payment($data['id'], $user_id, $user_form_data, $data['price'], $activate_immediately);
                        if ($payment !== true) {
                            $errors[] = $payment;
                        } else {
                            $messages[] = l('success_services_apply', 'services');
                            //$redirect = $this->session->userdata('service_redirect');
                            //$this->session->set_userdata(array('service_redirect' => ''));
                            $this->load->model('users/models/Auth_model');
                            $this->Auth_model->update_user_session_data($user_id);
                        }
                        $this->set_api_content('data', $data);
                        $this->set_api_content('errors', $errors);
                        $this->set_api_content('messages', $messages);
                        return true;
                    } /* elseif ('system' === $type) {
                      $system_gid = $this->input->post('system_gid', true);
                      if (empty($system_gid)) {
                      $errors[] = l('error_select_payment_system', 'services');
                      } else {
                      $this->Services_model->system_payment($system_gid, $user_id, $data["id"], $user_form_data, $data["price"], $activate_immediately);
                      $redirect = $this->session->userdata('service_redirect');
                      $this->session->set_userdata(array('service_redirect' => ''));
                      $this->load->model('users/models/Auth_model');
                      $this->Auth_model->update_user_session_data($user_id);
                      return true;
                      }
                      } */
                }
            }
        }

        if (!empty($data["template"]["data_user_array"])) {
            foreach ($data["template"]["data_user_array"] as $gid => $temp) {
                $value = "";
                if ($temp["type"] == "hidden") {
                    $value = filter_input(INPUT_POST, $gid);
                } elseif (isset($user_form_data[$gid])) {
                    $value = $user_form_data[$gid];
                }
                $data["template"]["data_user_array"][$gid]["value"] = $value;
            }
        }

        // get payments types
        $data["free_activate"] = false;
        if ($data["price"] <= 0) {
            $data["free_activate"] = true;
        }
        if ($data["pay_type"] == 1 || $data["pay_type"] == 2) {
            $this->load->model("Users_payments_model");
            $data["user_account"] = $this->Users_payments_model->get_user_account($user_id);
            if ($data["user_account"] <= 0 && $data["price"] > 0) {
                $data["disable_account_pay"] = true;
            } elseif (($data["template"]["price_type"] == 1 || $data["template"]["price_type"] == 3) && $data["price"] > $data["user_account"]) {
                $data["disable_account_pay"] = true;
            }
        }
        if ($data["pay_type"] == 2 || $data["pay_type"] == 3) {
            $this->load->model("payments/models/Payment_systems_model");
            $billing_systems = $this->Payment_systems_model->get_active_system_list();
            $data['billing_systems'] = $billing_systems;
        }
        $data['is_module_installed'] = $this->pg_module->is_module_installed('users_payments');
        $this->set_api_content('data', $data);
        $this->set_api_content('errors', $errors);
        $this->set_api_content('messages', $messages);
    }

    public function buy_list()
    {
        $this->load->model('Services_model');
        $lang_id = filter_input(INPUT_POST, 'lang_id', FILTER_VALIDATE_INT);
        $services = $this->Services_model->get_service_list(array('where' => array('status' => 1)), null, null, $lang_id);
        $services = $this->_format_service($services);
        $this->set_api_content('data', $services);
    }

    public function get()
    {
        $gid = filter_input(INPUT_POST, 'gid');
        if (!$gid) {
            $this->set_api_content('errors', array('Empty service gid'));
            return false;
        }
        $this->load->model('Services_model');
        $service = $this->Services_model->get_service_list(array('where' => array('gid' => $gid)), null, null, $lang_id);
        $service = $this->_format_service($service);
        if (count($service)) {
            $service = array_shift($service);
        }
        $this->set_api_content('data', $service);
    }

    public function my()
    {
        $user_id = $this->session->userdata('user_id');
        $lang_id = filter_input(INPUT_POST, 'lang_id', FILTER_VALIDATE_INT);
        $CI = &get_instance();
        $CI->load->model('services/models/Services_users_model');
        $order_by = array(
            'status' => 'DESC',
            'count' => 'DESC',
            'date_created' => 'DESC'
        );
        $where = array();
        $where['where_sql'][] = "id_user = $user_id AND (id_users_package = '0' OR status = '0')";
        $services = $CI->Services_users_model->get_services_list($where, $order_by, null, $lang_id);
        $this->set_api_content('data', $services);
        /* $date_formats = array(
          'date_format' => $CI->pg_date->get_format('date_literal', 'st'),
          'date_time_format' => $CI->pg_date->get_format('date_time_literal', 'st')
          );
          $CI->template_lite->assign('services_block_date_formats', $date_formats); */
    }

    public function user_service_activate()
    {
        $id_user = $this->session->userdata('user_id');
        if (!$id_user) {
            $this->set_api_content('errors', l('error_service_activating', 'services'));
            return false;
        }
        $id_user_service = filter_input(INPUT_POST, 'id_user_service', FILTER_VALIDATE_INT);
        $gid = filter_input(INPUT_POST, 'gid', FILTER_SANITIZE_STRING);

        $this->load->model('services/models/Services_users_model');
        $user_service = $this->Services_users_model->get_service_by_id($id_user_service);

        //check for free services
        if (!$user_service && $gid) {
            $this->load->model('Services_model');
            $service = $this->Services_model->format_service($this->Services_model->get_service_by_gid($gid));
            if ($service && !$service['price'] && $service['template']['price_type'] == 1) {
                $user_service = array(
                    'id_user' => $id_user,
                    'service_gid' => $service['gid'],
                    'template_gid' => $service['template_gid'],
                    'service' => $service,
                    'template' => $service['template'],
                    'payment_data' => array(),
                    'id_users_package' => 0,
                    'status' => 1,
                    'count' => 1,
                );
                $id_user_service = $this->Services_users_model->save_service(null, $user_service);
            }
        }
        if (!$user_service) {
            $this->set_api_content('errors', l('error_service_activating', 'services'));
            return false;
        }
        $module = $user_service['template']['callback_module'];
        $model = $user_service['template']['callback_model'];
        $method = $user_service['template']['callback_activate_method'];

        $this->load->model($module . '/models/' . $model);
        if (!method_exists($this->$model, $method)) {
            $this->set_api_content('errors', 'callback not found');
            return false;
        }

        $result = $this->$model->$method($id_user, $id_user_service);
        $this->set_api_content('messages', $result['message']);
        $this->set_api_content('data', $result);
    }

    private function _format_service($services)
    {
        $services_modules = array();
        foreach ($services as $key => $service) {
            if ($service['template']['price_type'] > 2) {
                unset($services[$key]);
            } else {
                $services[$key]['price'] = (float) $services[$key]['price'];
                $model = strtolower($service['template']['callback_model']);
                $services_modules[$service['template']['callback_module']][$model] = ucfirst($model);
            }
        }
        $buy_gids = array();
        foreach ($services_modules as $module => $models) {
            foreach ($models as $model) {
                $this->load->model("$module/models/$model");
                if (!empty($this->$model->services_buy_gids)) {
                    $buy_gids = array_merge($buy_gids, $this->$model->services_buy_gids);
                }
            }
        }
        foreach ($services as $key => $service) {
            if (!in_array($service['gid'], $buy_gids)) {
                unset($services[$key]);
            }
        }
        return $services;
    }

}
