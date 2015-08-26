<?php

/**
 * Services user side controller
 *
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @author Katya Kashkova <katya@pilotgroup.net>
 * @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
 * */
Class Services extends Controller
{
    /**
     * link to CodeIgniter object
     * @var object
     */

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Services_model");
    }
    
    /**
     * Services list
     * 
     * @param string $template_gid template GUID
     * @return void
     */
    public function index($template_gid='')
    {
        $this->template_lite->assign('template_gid', $template_gid);
        $this->template_lite->view('index');
    }

    public function form($service_gid, $user_form_data = array())
    {
        $user_id = $this->session->userdata('user_id');
        if (empty($service_gid)) {
            log_message('error', '(services) Empty $service_gid');
            show_404();
            return;
        }
        $this->load->model('users/models/Auth_model');
        $this->Auth_model->update_user_session_data($user_id);

        $data = $this->Services_model->format_service($this->Services_model->get_service_by_gid($service_gid));

        if (empty($data['status'])) {
            $msg = str_replace('[service_name]', $data['name'], l('error_services_inactive', 'services'));
            $this->system_messages->add_message('error', $msg);
            redirect(site_url() . 'users/account/services');
            show_404();
            return;
        }

        $data["template"] = $this->Services_model->format_template($data["template"]);

        if (!empty($data["data_admin_array"])) {
            foreach ($data["template"]["data_admin_array"] as $gid => $temp) {
                if (!empty($data["data_admin_array"][$gid])) {
                    $data["template"]["data_admin_array"][$gid]["value"] = $data["data_admin_array"][$gid];
                }
            }
        }

        if ($data["template"]["price_type"] == "2" || $data["template"]["price_type"] == "3") {
            $data["price"] = $this->input->post('price', true);
        }

        if ($this->input->post('btn_system') || $this->input->post('btn_account')) {
            $user_form_data = $this->input->post("data_user", true);
            $activate_immediately = $this->input->post('activate_immediately') ? true : false;
            $without_activation = $this->input->post('without_activation') ? true : false;

            $service_return = $this->Services_model->validate_service_payment($data["id"], $user_form_data, $data["price"]);
            if (!empty($service_return["errors"])) {
                $this->system_messages->add_message('error', $service_return["errors"]);
            } else {
                $origin_return = $this->Services_model->validate_service_original_model($data["id"], $user_form_data, $user_id, $data["price"]);
                if (!empty($origin_return["errors"])) {
                    $this->system_messages->add_message('error', $origin_return["errors"]);
                } else {
                    if ($this->input->post('btn_account')) {
                        $return = $this->Services_model->account_payment($data["id"], $user_id, $user_form_data, $data["price"], $activate_immediately);
                        if ($return !== true) {
                            $this->system_messages->add_message('error', $return);
                        } else {
                            if (!$without_activation) {
                                if ($activate_immediately) {
                                    $this->system_messages->add_message('success', l('success_services_activated', 'services'));
                                } else {
                                    $this->system_messages->add_message('success', l('success_services_can_activate', 'services'));
                                }
                            }

                            $redirect = $this->session->userdata('service_redirect');
                            $this->session->set_userdata(array('service_redirect' => ''));
                            $this->load->model('users/models/Auth_model');
                            $this->Auth_model->update_user_session_data($user_id);
                            redirect($redirect);
                        }
                    } elseif ($this->input->post('btn_system')) {
                        $system_gid = $this->input->post('system_gid', true);
                        if (empty($system_gid)) {
                            $this->system_messages->add_message('error', l('error_select_payment_system', 'services'));
                        } else {
                            $this->Services_model->system_payment($system_gid, $user_id, $data["id"], $user_form_data, $data["price"], $activate_immediately);

                            $redirect = $this->session->userdata('service_redirect');
                            $this->session->set_userdata(array('service_redirect' => ''));
                            $this->load->model('users/models/Auth_model');
                            $this->Auth_model->update_user_session_data($user_id);
                            redirect($redirect);
                        }
                    }
                }
            }
        }

        if (!empty($data["template"]["data_user_array"])) {
            foreach ($data["template"]["data_user_array"] as $gid => $temp) {
                $value = "";
                if ($temp["type"] == "hidden") {
                    $value = $this->input->get_post($gid, true);
                }
                if (isset($user_form_data[$gid])) {
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
            $this->template_lite->assign('billing_systems', $billing_systems);
        }

        $this->load->model('Menu_model');
        $this->Menu_model->breadcrumbs_set_parent('my_payments_item');
        $this->Menu_model->breadcrumbs_set_active($data['name']);

        $this->template_lite->assign('is_module_installed', $this->pg_module->is_module_installed('users_payments'));
        $this->template_lite->assign('data', $data);
        $this->template_lite->view('service_form');
    }

    public function user_service_activate($id_user, $id_user_service, $gid = '')
    {
        $redirect = $this->session->userdata('service_activate_redirect');
        $id_user_session = $this->session->userdata('user_id');
        if ($id_user_session !== $id_user) {
            $this->system_messages->add_message('error', l('error_service_activating', 'services'));
            redirect($redirect);
        }
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
            $this->system_messages->add_message('error', l('error_service_activating', 'services'));
            redirect($redirect);
        }

        $this->load->model($user_service['template']['callback_module'] . '/models/' . $user_service['template']['callback_model']);
        if (method_exists($this->$user_service['template']['callback_model'], $user_service['template']['callback_activate_method'])) {
            $result = $this->$user_service['template']['callback_model']->$user_service['template']['callback_activate_method']($id_user, $id_user_service);
        } else {
            $result['status'] = 0;
            $result['message'] = 'callback not found';
        }
        $this->system_messages->add_message(($result['status'] ? 'success' : 'error'), $result["message"]);
        redirect($redirect);
    }

}
