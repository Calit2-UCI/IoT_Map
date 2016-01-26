<?php

namespace Pg\Modules\Users\Models;

/**
* User auth model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Auth_model extends \Model
{
	public $CI;
	public $DB;
    
    const NEED_ASK = 'need_ask';

	public function __construct()
	{
		parent::__construct();
		$this->CI = & get_instance();
		$this->CI->load->model("Users_model");
	}

	function login($id){
		$data["errors"] = array();
		$data["user_data"] = array();
		$data["login"] = false;
		$user_data = $this->CI->Users_model->get_user_by_id($id);
		if(empty($user_data)){
			$data["errors"][] = l('error_login_invalid_data', 'users');
		}else{
			if($user_data["confirm"] == 0){
				$data["errors"][] = l('error_unconfirmed_user', 'users', $user_data['lang_id']);
				$data["user_data"]["confirm"] = 0;
			}elseif(($user_data["approved"] == 0 && $this->pg_module->get_module_config('users', 'user_approve') != 2) || ($user_data["approved"] == 0 && $this->pg_module->get_module_config('users', 'user_approve') == 1)){
				//$data["errors"][] = l('error_approve_need_wait', 'users', $user_data['lang_id']);
				redirect(site_url().'users/blocked');
			}else{
				$data["user_data"] = $user_data;
				$data["login"] = true;
				$this->update_user_session_data($id);
                
                $is_network_installed = $this->CI->pg_module->is_module_installed('network');
                if ($is_network_installed) {
                    if ($user_data['net_status'] == self::NEED_ASK) {
                        redirect(site_url().'network/register');
                        exit;
                    }
                }
			}
		}
		return $data;
	}

	function update_user_session_data($id){
		$user_data = $this->CI->Users_model->get_user_by_id($id);
		if($user_data){
			$this->CI->Users_model->set_user_output_name($user_data);
			$session = array(
				"auth_type" => 'user',
				"approved" => $user_data['approved'],
				"user_id" => $user_data["id"],
				"user_type" => $user_data["user_type"],
				"fname" => $user_data["fname"],
				"sname" => $user_data["sname"],
				"nickname" => $user_data["nickname"],
				"output_name" => $user_data["output_name"],
				"lang_id" => $this->CI->pg_language->current_lang_id,
				"online_status" => $user_data["online_status"],
				"site_status" => $user_data["site_status"],
				"show_adult" => $user_data["show_adult"],
				"website" => $user_data['website'],
			);
		}else{
			$session = array();
		}
		$this->CI->session->set_userdata($session);
		$this->CI->template_lite->assign('user_session_data', $this->CI->session->all_userdata());
		return $session;
	}

	function login_by_email_password($email, $password){
		$user_data = $this->CI->Users_model->get_user_by_email_password($email, $password);
		if(empty($user_data)){
			$data["errors"][] = l('error_login_invalid_data', 'users');
		}else{
			$data = $this->login($user_data["id"]);
		}
		return $data;
	}

	/**
	 * Don't delete (openid)
	 *
	 */
	/*function open_id_request($openid_identifier, $policies=false){
		$this->CI->load->library('openid');
		$this->CI->config->load('openid');

		if (!$policies){
			$policies = array();
		}

		$req = $this->CI->config->item('openid_required');
		$opt = $this->CI->config->item('openid_optional');
		$policy = site_url($this->CI->config->item('openid_policy'));
		$request_to = site_url($this->CI->config->item('openid_request_to'));

		$this->CI->openid->set_request_to($request_to);
		$this->CI->openid->set_trust_root(base_url());
		$this->CI->openid->set_args(null);
		$this->CI->openid->set_sreg(true, $req, $opt, $policy);
		$this->CI->openid->set_pape(true, $policies);
		$return = $this->CI->openid->authenticate($openid_identifier);
		if(strlen($return)){
			$data["errors"][] = l($return, 'users');
			return $data;
		}
		return true;
	}*/

	/**
	 * Don't delete (openid)
	 *
	 */
	/*function open_id_response(){
		$this->CI->load->library('openid');
		$this->CI->config->load('openid');

		$request_to = site_url($this->CI->config->item('openid_request_to'));

		$this->CI->openid->set_request_to($request_to);
		$response = $this->CI->openid->getResponse();

		switch ($response->status)
		{
			case Auth_OpenID_CANCEL: $data['errors'] = l('openid_cancel', 'users'); break;
			case Auth_OpenID_FAILURE: $data['errors'][] = str_replace('%s', $response->message, l('openid_failure', 'users')); break;
			case Auth_OpenID_SUCCESS:
				$openid = $response->getDisplayIdentifier();
				$esc_identity = htmlspecialchars($openid, ENT_QUOTES);
				$data["data"]["openid"] = $esc_identity;

				$data['success'] = str_replace(array('%s','%t'), array($esc_identity, $esc_identity), l('openid_success', 'users'));

				$sreg_resp = Auth_OpenID_SRegResponse::fromSuccessResponse($response);
				$sreg = $sreg_resp->contents();
				foreach ($sreg as $key => $value){
					$data["data"][$key] = $value;
				}

				break;
		}

		if(!empty($data["success"])){

			//// get user_id by open_id
			$user_data = $this->CI->Users_model->get_user_by_open_id($data["data"]["openid"]);

			//// if user not exists - check email - create account
			if(empty($user_data)){
				$open_id_data = $this->CI->Users_model->validate_open_id_data($data["data"]);
				$validate = $this->CI->Users_model->validate_user(null, $open_id_data);
				if(!empty($validate["errors"]) ){
					foreach($validate["errors"] as $error){
						$data['errors'][] = $error;
					}
				}else{
					$user_id = $this->CI->Users_model->save_user(null, $validate["data"]);
				}
			}else{
				$user_id = $user_data["id"];
			}

			if($user_id){
				$user_login = $this->login($user_id);
				if ($user_login !== TRUE){
					foreach($user_login["errors"] as $error){
						$data['errors'][] = $error;
					}
				}
			}
		}
		return $data;
	}*/

	function logoff(){
		$this->CI->session->sess_destroy();
	}

	function validate_login_data($data){
		$return = array("errors"=> array(), "data" => array());

		// Don't delete (openid)
		/*if(isset($data["user_open_id"]) && !empty($data["user_open_id"])){
			$return["data"]["user_open_id"] = trim($data["user_open_id"]);
		}else*/
		if(isset($data["email"]) && isset($data["password"])){
			$this->CI->config->load('reg_exps', TRUE);

			$email_expr =  $this->CI->config->item('email', 'reg_exps');
			$return["data"]["email"] = strip_tags($data["email"]);
			if(empty($return["data"]["email"]) || !preg_match($email_expr, $return["data"]["email"])){
				$return["errors"][] = l('error_email_incorrect', 'users');
			}

			$password_expr =  $this->CI->config->item('password', 'reg_exps');
			$data["password"] = trim(strip_tags($data["password"]));
			if(!preg_match($password_expr, $data["password"])){
				$return["errors"][] = l('error_password_incorrect', 'users');
			}else{
				$return["data"]["password"] = $data["password"];
			}

		}else{
			$return["errors"][] = l('error_login_invalid_data', 'users');
		}

		return $return;
	}
}
