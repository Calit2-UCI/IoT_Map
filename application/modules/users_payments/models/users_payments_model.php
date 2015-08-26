<?php
/**
* Users payments model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Mikhail Chernov <mchernov@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: mchernov $
**/

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

define('USER_ACCOUNT_STAT_TABLE', DB_PREFIX . 'user_account_list');

class Users_payments_model extends Model {

	var $CI;
	var $DB;

	/**
	 * Constructor
	 *
	 * @return users object
	 */
	function Users_payments_model() {
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;

		$this->CI->load->model('Users_model');
	}

	///// user account functions
	function get_user_account($user_id) {
		$result = $this->DB->select("account")->from(USERS_TABLE)->where("id", $user_id)->get()->result_array();
		if (empty($result)) {
			return 0;
		} else {
			return $result[0]["account"];
		}
	}

	function update_user_account($payment_data, $payment_status) {
		if ($payment_status == 1) {
			$user_id = $payment_data["id_user"];
			$user_data = $this->CI->Users_model->get_user_by_id($user_id);
			$data["account"] = $user_data["account"] + $payment_data["amount"];
			$this->CI->Users_model->save_user($user_id, $data);
			$this->account_add_spend_entry($user_id, $payment_data["amount"], "add", l('account_funds_add_message', 'users_payments'));

			$this->CI->load->model('Notifications_model');
			$this->load->helper('start');
			$mail_data = array(
				'fname' => $user_data['fname'],
				'sname' => $user_data['sname'],
				'nickname' => $user_data['fname']." ".$user_data['sname'],
				'received' => currency_format_output(array('value' => $payment_data["amount"], 'no_tags' => true)),
				'account' => currency_format_output(array('value' => $data["account"], 'no_tags' => true))
			);
			$this->CI->Notifications_model->send_notification($user_data["email"], 'users_update_account', $mail_data, '', $user_data['lang_id']);
		}
		return;
	}

	function write_off_user_account($id_user, $amount, $message) {
		$user_data = $this->CI->Users_model->get_user_by_id($id_user);
		$data["account"] = $user_data["account"] - $amount;
		if ($data["account"] < 0) {
			return l('error_money_not_sufficient', 'users_payments');
		} else {
			$this->CI->Users_model->save_user($id_user, $data);
			$this->account_add_spend_entry($id_user, $amount, "spend", $message);
			return true;
		}
	}

	function account_add_spend_entry($id_user, $price, $price_type = "add", $message = "") {
		if (empty($message)) {
			$message = ($price_type = "add") ? l('account_funds_add_message', 'users_payments') : l('account_funds_spend_message', 'users_payments');
		}

		$data = array(
			"id_user" => $id_user,
			"date_add" => date("Y-m-d H:i:s"),
			"price_type" => $price_type,
			"price" => $price,
			"message" => $message,
		);
		$this->DB->insert(USER_ACCOUNT_STAT_TABLE, $data);
	}
}
