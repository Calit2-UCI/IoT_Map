<?php

/**
 * Users user side controller
 *
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @author Mikhail Chernov <mchernov@pilotgroup.net>
 * @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: mchernov $
 * */
Class Api_get_token extends Controller {

	/**
	 * Constructor
	 */
	function __construct() {
		parent::Controller();
		$this->load->model("users/models/Auth_model");
	}

	public function index() {
		$errors = array();
		$return = array();
		$token = '';

		$data = array(
			"email" => trim(strip_tags($this->input->get_post('email', true))),
			"password" => trim(strip_tags($this->input->get_post('password', true))),
		);
		
		if (empty($data['email']) && empty($data['password'])) {
			$token = $this->session->sess_create_token();
			$this->set_api_content('data', array('token' => $token));
		} else {
			$login_return = $this->Auth_model->login_by_email_password($data["email"], md5($data["password"]));
			if (!empty($login_return["errors"])) {
				$errors = $login_return["errors"];
				$this->set_api_content('errors', $errors);
				$token = $this->session->sess_destroy();
			} else {
				$token = $this->session->sess_create_token();
				$this->set_api_content('data', array('token' => $token, 'user_data', $login_return['user_data']));
			}		
		}		
		
		/*
		$validate = $this->Auth_model->validate_login_data($data);
		if (!empty($validate["errors"])) {
			if (empty($data['email']) && empty($data['password'])) {
				$token = $this->session->sess_create_token();
				$this->set_api_content('data', array('token' => $token));
			} else {
				$errors = $validate["errors"];
				$this->set_api_content('errors', $errors);
				$token = $this->session->sess_destroy();
			}
		} else {
			$login_return = $this->Auth_model->login_by_email_password($validate["data"]["email"], md5($validate["data"]["password"]));
			if (!empty($login_return["errors"])) {
				$errors = $login_return["errors"];
				$this->set_api_content('errors', $errors);
				$token = $this->session->sess_destroy();
			} else {
				$token = $this->session->sess_create_token();
				$this->set_api_content('data', array('token' => $token, 'user_data', $login_return['user_data']));
			}
		}
		*/
	}

}
