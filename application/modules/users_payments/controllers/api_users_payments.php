<?php
/**
* Users payments api controller
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Alexander Batukhtin <abatukhtin@pilotgroup.net>
**/
Class Api_users_payments extends Controller
{

	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::Controller();
	}

	public function save_payment() {

		$user_id = $this->session->userdata('user_id');
		$amount = $this->input->post('amount', true);
		$system_gid = $this->input->post('system_gid', true);

		if (empty($amount)) {
			$this->set_api_content('errors', l('error_empty_amount', 'users_payments'));
		} elseif (empty($system_gid)) {
			$this->set_api_content('errors', l('error_empty_system_gid', 'users_payments'));
		} else {
			$this->load->model('payments/models/Payment_currency_model');
			$this->load->helper('payments');
			$additional['name'] = l('header_add_funds', 'users');
			$payment_data = send_payment('account',
										$user_id,
										$amount,
										$this->Payment_currency_model->default_currency['gid'],
										$system_gid,
										$additional,
										'form');
			if (!empty($payment_data['errors'])) {
				$this->set_api_content('errors', $payment_data['errors']);
			}
			if (!empty($payment_data['info'])) {
				$this->set_api_content('messages', $payment_data['info']);
			}
		}
		$this->set_api_content('data', array('amount' => $amount, 'system_gid' => $system_gid));
	}
}