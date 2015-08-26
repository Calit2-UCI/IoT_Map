<?php
/**
* Payments api controller
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Alexander Batukhtin <abatukhtin@pilotgroup.net>
**/

Class Api_Payments extends Controller
{
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::Controller();
	}

	/**
	 * Payments statistic
	 *
	 * @param int $page
	 */
	public function statistic(){
		if($this->session->userdata('auth_type') != 'user'){
			log_message('error', 'payments API: Wrong auth type ("' . $this->session->userdata('auth_type') . '")');
			$this->set_api_content('errors', l('api_error_wrong_auth_type', 'payments'));
			return false;
		}
		$user_id = $this->session->userdata('user_id');
		$this->load->model('Payments_model');

		$params = array();
		$params['where']['id_user'] = $user_id;
		$payments_count = $this->Payments_model->get_payment_count($params);

		$page = $this->input->post('page', true);
		$items_on_page = $this->pg_module->get_module_config('payments', 'items_per_page');
		$this->load->helper('sort_order');
		$page = get_exists_page_number($page, $payments_count, $items_on_page);

		$payments = $this->Payments_model->get_payment_list( $page, $items_on_page, array('date_add' => 'DESC'), $params);

		$data['payments'] = $payments;
		$data['date_format'] = $this->pg_date->get_format('date_time_literal', 'st');
		$this->set_api_content('data', $data);
	}

	/**
	 * Response
	 *
	 * @param string $system_gid
	 * @param array $data
	 * @return boolean
	 */
	public function response(){
		$system_gid = $this->input->post('system_gid', true);
		if(!$system_gid) {
			log_message('error', 'payments API: Empty system gid');
			$this->set_api_content('errors', l('error_system_gid_incorrect', 'payments'));
			return false;
		}
		$data = $this->input->post('data', true);
		$this->load->helper('payments');
		$return = receive_payment($system_gid, $data);

		if($return['type'] == 'html'){
			if(!empty($return['info'])){
				$this->set_api_content('messages', $return['info']);
			}
			if(!empty($return['errors'])){
				$this->set_api_content('errors', $return['errors']);
				return false;
			}
			$this->load->model('Payments_model');
			$payment = $this->Payments_model->get_payment_by_id($return['data']['id_payment']);
			$temp = $this->Payments_model->format_payments(array(0 => $payment));
			$this->set_api_content('data', array('payment' => $temp[0]));
		}
	}

	/**
	 * Payment form
	 *
	 * @param string $currency_gid
	 * @param string $system_gid
	 */
	public function form(){
		$data['currency_gid'] = $this->input->post('currency_gid', true);
		$data['system_gid'] = $this->input->post('system_gid', true);
		if(!$data['system_gid']) {
			log_message('error', 'payments API: Empty system gid');
			$this->set_api_content('errors', l('error_system_gid_incorrect', 'payments'));
			return false;
		}
		if(!$data['currency_gid']) {
			log_message('error', 'payments API: Empty currency gid');
			$this->set_api_content('errors', l('error_currency_code_incorrect', 'payments'));
			return false;
		}

		$this->load->model('payments/models/Payment_systems_model');
		$data['system'] = $this->Payment_systems_model->get_system_by_gid($data['system_gid']);
		$this->Payment_systems_model->load_driver($data['system_gid']);
		$data['map'] = $this->Payment_systems_model->get_html_data_map();

		$this->load->model('payments/models/Payment_currency_model');
		$data['currency'] = $this->Payment_currency_model->get_currency_by_gid($data["currency_gid"]);

		$this->set_api_content('data', $data);
	}

	/**
	 * Sends payment request
	 *
	 * @param string $payment_type_gid
	 * @param float $amount
	 * @param array $payment_data
	 * @param string $currency_gid
	 * @param string $system_gid
	 * @param array $map
	 */
	public function send() {
		$data['id_user'] = $this->session->userdata('user_id');

		$data['payment_type_gid'] = $this->input->post('payment_type_gid', true);
		$data['amount'] = $this->input->post('amount', true);
		$data['payment_data'] = $this->input->post('payment_data', true);
		$data['currency_gid'] = $this->input->post('currency_gid', true);
		$data['system_gid'] = $this->input->post('system_gid', true);

		$map = $this->input->post('map', true);
		if(empty($map)){
			$this->set_api_content('errors', true);
			return false;
		}
		foreach($map as $key => $value){
			$data['payment_data'][$key] = $value;
		}
		$this->load->helper('payments');
		$return = send_payment($data['payment_type_gid'], $data['id_user'], $data['amount'],
				$data['currency_gid'], $data['system_gid'], $data['payment_data'], 'validate');
		$this->set_api_content('data', array('payment_data' => $return['data']));
		if(!empty($return['errors'])){
			$this->set_api_content('errors', $return['errors']);
		}else{
			$this->set_api_content('messages', array(l('success_payment_send', 'payments'), $return['info']));
		}
	}

}