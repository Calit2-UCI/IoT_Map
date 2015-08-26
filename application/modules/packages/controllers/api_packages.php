<?php
/**
* Packages user side controller
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Mikhail Chernov <mchernov@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: mchernov $
**/

Class Api_Packages extends Controller
{
	/**
	 * link to CodeIgniter object
	 * @var object
	 */

	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::Controller();
		$this->load->model("Packages_model");
	}

	public function index(){
		$order_by["gid"] = "ASC";
		$param['where']['status'] = 1;
		$packages = $this->Packages_model->get_packages_list($param, null, $order_by);
		$this->set_api_content('data', $packages);
	}

	public function package() {

		$package_gid = filter_input(INPUT_POST, 'package_gid', FILTER_SANITIZE_STRING);
		$user_id = $this->session->userdata('user_id');

		$this->load->model('users/models/Auth_model');
		$this->Auth_model->update_user_session_data($user_id);

		$package = $this->Packages_model->get_package_by_gid($package_gid);

		if(!$package['status']){
			$this->set_api_content('errors', 'Bad package status');
			return false;
		}
		$errors = array();
		$messages = array();
		$type = filter_input(INPUT_POST, 'payment_type', FILTER_SANITIZE_STRING);
		if ($type) {
			if ('account' === $type) {
				$result = $this->Packages_model->account_package_payment($package["id"], $user_id, $package["price"]);
				if($result !== true){
					$errors[] = $result;
				}else{
					$messages[] = l('success_services_apply', 'services');
					//$redirect = $this->session->userdata('service_redirect');
					//$this->session->set_userdata(array('service_redirect'=>''));
					$this->load->model('users/models/Auth_model');
					$this->Auth_model->update_user_session_data($user_id);
				}
				$this->set_api_content('data', array('package' => $package, 'payment_type' => $type));
				$this->set_api_content('errors', $errors);
				$this->set_api_content('messages', $messages);
				return true;
			}/*elseif($this->input->post('btn_system')){
				$system_gid = $this->input->post('system_gid', true);
				if(empty($system_gid)){
					$errors[] = l('error_select_payment_system', 'services');
				}else{
					$this->Packages_model->system_package_payment($system_gid, $user_id, $package["id"], $package["price"]);
					$redirect = $this->session->userdata('service_redirect');
					$this->session->set_userdata(array('service_redirect'=>''));
					$this->load->model('users/models/Auth_model');
					$this->Auth_model->update_user_session_data($user_id);
					//redirect($redirect);
				}
			}*/
		}

		if($package["pay_type"] == 1 || $package["pay_type"] == 2){
			$this->load->model("Users_payments_model");
			$package["user_account"] = $this->Users_payments_model->get_user_account($user_id);
			if($package["user_account"] <= 0 && $package["price"] > 0){
				$package["disable_account_pay"] = true;
			}elseif($package["price"] > $package["user_account"]){
				$package["disable_account_pay"] = true;
			}
		}

		if($package["pay_type"] == 2 || $package["pay_type"] == 3){
			$this->load->model("payments/models/Payment_systems_model");
			$billing_systems = $this->Payment_systems_model->get_active_system_list();
			$data['billing_systems'] = $billing_systems;
		}

		$data['is_payments_module_installed'] = $this->pg_module->is_module_installed('users_payments');
		$data['package'] = $package;

		$this->set_api_content('data', $data);
		$this->set_api_content('errors', $errors);
		$this->set_api_content('messages', $messages);
	}

	public function my(){
		$this->load->model('packages/models/Packages_users_model');

		$params = array();
		$params['where']['id_user'] = $this->session->userdata('user_id');
		$user_packages_count = $this->Packages_users_model->get_user_packages_count($params);

		$page = filter_input(INPUT_POST, 'page', FILTER_VALIDATE_INT) || 1;
		$items_on_page = filter_input(INPUT_POST, 'items_on_page', FILTER_VALIDATE_INT) || 10;
		$this->load->helper('sort_order');
		$page = get_exists_page_number($page, $user_packages_count, $items_on_page);
		$user_packages = $this->Packages_users_model->get_user_packages_list(null, $params);
		foreach($user_packages as &$package) {
			$package['till_date_ts'] = strtotime($package['till_date']);
		}
		/*$data['user_packages'] = $user_packages;
		$this->load->helper('navigation');
		$page_data = get_user_pages_data(site_url() . 'packages/my/', $user_packages_count, $items_on_page, $page, 'briefPage');
		$data['page_data'] = $page_data;*/
		$this->set_api_content('data', $user_packages);
	}

	public function get() {
		$gid = filter_input(INPUT_POST, 'gid');
		if(!$gid) {
			$this->set_api_content('errors', array('Empty package gid'));
			return false;
		}
		$package = $this->Packages_model->get_package_by_gid($gid);
		$this->set_api_content('data', $package);
	}

}