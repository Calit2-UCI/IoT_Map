<?php

use Pg\Modules\Memberships\Models\Memberships_model;

/**
 * Memberships module
 *
 * @package 	PG_Dating
 * @copyright 	Copyright (c) 2000-2014 PG Dating Pro - php dating software
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */

/**
 * Memberships user side controller
 * 
 * @package 	PG_Dating
 * @subpackage 	Memberships
 * @category	controllers
 * @copyright 	Copyright (c) 2000-2014 PG Dating Pro - php dating software
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
Class Memberships extends Controller
{

    /**
     * Class constructor
     * 
     * @return Memberships
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Memberships_model");
        $this->load->library('PG_Output');
    }

    /**
     * Module management
     * 
     * @return void
     */
    public function index()
    {
        $where['is_active'] = 1;
        $memberships = $this->Memberships_model->getMembershipsList($where, null, null, array('priority' => 'ASC'));
        $this->pg_output->setOutputData('memberships', $memberships);

        $this->load->model('Menu_model');
        $this->Menu_model->breadcrumbs_set_parent('memberships_item');
    }

    /**
     * Membership management
     * 
     * @param string $membership_gid membership GUID
     */
    public function form($membership_gid)
    {
        $user_id = $this->session->userdata('user_id');

        $this->load->model('users/models/Auth_model');
        $this->Auth_model->update_user_session_data($user_id);

        $membership = $this->Memberships_model->getMembershipByGid($membership_gid);
        if (!$membership['is_active']) {
            show_404();
            return;
        }
        $this->Memberships_model->setFormatSettings('get_services', true);
        $membership = $this->Memberships_model->formatMembership($membership);
        $this->Memberships_model->setFormatSettings('get_services', false);

        if ($this->input->post('btn_account')) {
            
            $return = $this->Memberships_model->accountMembershipPayment(
                $membership["id"], 
                $user_id, 
                $membership["price"]
            );
            if ($return !== true) {
                $this->system_messages->add_message('error', $return);
            } else {
                $this->system_messages->add_message('success', l('success_memberships_apply', 'memberships'));

                $redirect = $this->session->userdata('service_redirect');
                $this->session->set_userdata(array('service_redirect' => ''));
                $this->load->model('users/models/Auth_model');
                $this->Auth_model->update_user_session_data($user_id);
                $this->pg_output->setOutputContent('redirect', $redirect);
            }
        } elseif ($this->input->post('btn_system')) {
            $system_gid = $this->input->post('system_gid', true);
            if (empty($system_gid)) {
                $this->system_messages->add_message('error', l('error_select_payment_system', 'memberships'));
            } else {
                $this->Memberships_model->systemMembershipPayment(
                    $system_gid, 
                    $user_id, 
                    $membership["id"], 
                    $membership["price"]
                );
                $redirect = $this->session->userdata('service_redirect');
                $this->session->set_userdata(array('service_redirect' => ''));
                $this->load->model('users/models/Auth_model');
                $this->Auth_model->update_user_session_data($user_id);
                //$this->pg_output->setOutputContent('redirect', $redirect);
            }
        }

        if ($membership["pay_type"] == Memberships_model::PAYMENT_TYPE_ACCOUNT 
                || $membership["pay_type"] == Memberships_model::PAYMENT_TYPE_ACCOUNT_AND_DIRECT) {
            $this->load->model("Users_payments_model");
            $membership["user_account"] = $this->Users_payments_model->get_user_account($user_id);
            if ($membership["user_account"] <= 0 && $membership["price"] > 0 
                    || $membership["price"] > $membership["user_account"]) {
                $membership["disable_account_pay"] = true;
            }
        }

        if ($membership["pay_type"] == Memberships_model::PAYMENT_TYPE_ACCOUNT_AND_DIRECT 
                || $membership["pay_type"] == Memberships_model::PAYMENT_TYPE_DIRECT) {
            $this->load->model("payments/models/Payment_systems_model");
            $billing_systems = $this->Payment_systems_model->get_active_system_list();
            $this->pg_output->setOutputData('billing_systems', $billing_systems);
        }

        $this->load->model('memberships/models/Memberships_users_model');
        $this->pg_output->setOutputData('show_ms_change_warning', $this->Memberships_users_model->userHasMembership($user_id));
        $this->pg_output->setOutputData('membership', $membership);
        $this->load->model('Menu_model');
        $this->Menu_model->breadcrumbs_set_parent('memberships_item');
        $this->Menu_model->breadcrumbs_set_active($membership['name']);
    }

    /**
     * My memberships
     * 
     * @param integer $page page of results
     * @return void
     */
    public function my($page = 1)
    {
        $id_user = $this->session->userdata('user_id');

        $params['where']['id_user'] = $id_user;
        $this->load->model('memberships/models/Memberships_users_model');
        $user_memberships_count = $this->Memberships_users_model->getUserMembershipsCount($params);

        $items_on_page = 20;
        $this->load->helper('sort_order');
        $page = get_exists_page_number($page, $user_memberships_count, $items_on_page);

        $user_memberships = $this->Memberships_users_model->getUserMembershipsList(null, $params);
        $this->pg_output->setOutputData('user_memberships', $user_memberships);

        $this->load->helper("navigation");
        $page_data = get_user_pages_data(site_url() . 'memberships/my/', $user_memberships_count, $items_on_page, $page, 'briefPage');
        $this->pg_output->setOutputData('page_data', $page_data);

        $this->load->model('Menu_model');
        $this->Menu_model->breadcrumbs_set_parent('my_mmeberships_item');
    }

}
