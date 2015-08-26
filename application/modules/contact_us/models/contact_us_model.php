<?php
/**
* Contact us main model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

if (!defined('BASEPATH')) exit('No direct script access allowed');

define('CONTACT_REASONS_TABLE', DB_PREFIX.'contact_us');

class Contact_us_model extends Model
{
	private $CI;
	private $DB;

	private $fields = array(
		'id',
		'mails',
		'date_add',
	);

	private $_moderation_type = 'contact_us';

	function __construct()
	{
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;
	}

	public function get_reason_by_id($id){
		$result = $this->DB->select(implode(", ", $this->fields))->from(CONTACT_REASONS_TABLE)->where("id", $id)->get()->result_array();
		if(!empty($result)){
			$result = $this->format_reasons($result);
			$data = $result[0];
			return $data;
		}
		return array();
	}

	public function get_reason_list($params=array(), $filter_object_ids=null, $order_by=null){
		$this->DB->select(implode(", ", $this->fields));
		$this->DB->from(CONTACT_REASONS_TABLE);

		if(isset($params["where"]) && is_array($params["where"]) && count($params["where"])){
			foreach($params["where"] as $field=>$value){
				$this->DB->where($field, $value);
			}
		}

		if(isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])){
			foreach($params["where_in"] as $field=>$value){
				$this->DB->where_in($field, $value);
			}
		}

		if(isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])){
			foreach($params["where_sql"] as $value){
				$this->DB->where($value);
			}
		}

		if(isset($filter_object_ids) && is_array($filter_object_ids) && count($filter_object_ids)){
			$this->DB->where_in("id", $filter_object_ids);
		}

		if(is_array($order_by) && count($order_by)>0){
			foreach($order_by as $field => $dir){
				if(in_array($field, $this->fields_news)){
					$this->DB->order_by($field." ".$dir);
				}
			}
		}

		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			foreach($results as $r){
				$data[] = $r;
			}
			return $this->format_reasons($data);
		}
		return array();
	}

	public function get_reason_count($params=array(), $filter_object_ids=null){
		$this->DB->select("COUNT(*) AS cnt");
		$this->DB->from(CONTACT_REASONS_TABLE);

		if(isset($params["where"]) && is_array($params["where"]) && count($params["where"])){
			foreach($params["where"] as $field=>$value){
				$this->DB->where($field, $value);
			}
		}

		if(isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])){
			foreach($params["where_in"] as $field=>$value){
				$this->DB->where_in($field, $value);
			}
		}

		if(isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])){
			foreach($params["where_sql"] as $value){
				$this->DB->where($value);
			}
		}

		if(isset($filter_object_ids) && is_array($filter_object_ids) && count($filter_object_ids)){
			$this->DB->where_in("id", $filter_object_ids);
		}

		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			return intval($results[0]["cnt"]);
		}
		return 0;
	}

	public function save_reason($id, $data, $langs){
		if (is_null($id)){
			$data["date_add"] = date("Y-m-d H:i:s");
			$this->DB->insert(CONTACT_REASONS_TABLE, $data);
			$id = $this->DB->insert_id();
		}else{
			$this->DB->where('id', $id);
			$this->DB->update(CONTACT_REASONS_TABLE, $data);
		}

		if(!empty($langs)){
			$languages = $this->CI->pg_language->languages;
			if(!empty($languages)){
				foreach($languages as $language){
					$lang_ids[] = $language["id"];
				}
				$this->CI->pg_language->pages->set_string_langs('contact_us', "contact_us_reason_".$id, $langs, $lang_ids);
			}
		}
		return $id;
	}

	public function validate_reason($id, $data, $langs){
		$return = array("errors"=> array(), "data" => array(), 'temp' => array(), 'langs' => array());

		if(isset($data["mails"])){
			if(empty($data["mails"])) {
				$return['errors'][] = l('error_email_mandatory_field', 'contact_us');
			}
			$data["mails"] = explode(',', $data["mails"]);
			foreach($data["mails"] as $k => $mail){
				$mail = trim(strip_tags($mail));
				if(!empty($mail)){
					$data["mails"][$k] = $mail;
				}else{
					unset($data["mails"][$k]);
				}
			}
			$return["data"]["mails"] = serialize($data["mails"]);
		}

		if(!empty($langs)){
			$return["langs"] = $langs;
			foreach($langs as $lang_id => $name) {
				if(empty($name)) {
					$return["errors"][] = l('error_reason_mandatory_field', 'contact_us') . ': ' . $this->pg_language->languages[$lang_id]['name'];
				}
			}
		}
		return $return;
	}

	public function delete_reason($id){
		$this->DB->where("id", $id);
		$this->DB->delete(CONTACT_REASONS_TABLE);

		$this->CI->pg_language->pages->delete_string("contact_us", "contact_us_reason_".$id);
		return;
	}

	public function format_reasons($data){
		$languages = $this->CI->pg_language->languages;
		foreach($data as $k => $reason){
			$reason["name"] = l('contact_us_reason_'.$reason["id"], 'contact_us');
			foreach($languages as $lang){
				$reason["names"][$lang['id']] = l('contact_us_reason_'.$reason["id"], 'contact_us', $lang['id']);
			}
			$reason["mails"] = unserialize($reason["mails"]);
			if(!empty($reason["mails"]) && is_array($reason["mails"])){
				$reason["mails_string"] = implode(", ", $reason["mails"]);
			}else{
				$reason["mails_string"] = "";
			}
			$data[$k] = $reason;
		}
		return $data;
	}

	public function validate_settings($data){
		$return = array("errors"=> array(), "data" => array());

		if(isset($data["default_alert_email"])){
			$return["data"]["default_alert_email"] = trim(strip_tags($data["default_alert_email"]));

			$this->CI->config->load('reg_exps', TRUE);
			$email_expr =  $this->CI->config->item('email', 'reg_exps');
			if(empty($return["data"]["default_alert_email"]) || !preg_match($email_expr, $return["data"]["default_alert_email"])){
				$return["errors"][] = l('error_default_alert_email_incorrect', 'contact_us');
			}
		}

		return $return;
	}

	public function get_settings(){
		$data = array(
			"default_alert_email" => $this->CI->pg_module->get_module_config('contact_us', 'default_alert_email'),
		);
		return $data;
	}


	public function set_settings($data){
		foreach($data as $setting => $value){
			$this->CI->pg_module->set_module_config('contact_us', $setting, $value);
		}
		return;
	}

	public function send_contact_form($data){
		$return = array("errors"=> array(), "data" => array());

		$this->CI->load->model('Notifications_model');

		if(!empty($data["reason_data"]) && !empty($data["reason_data"]["mails"])){
			$mails = $data["reason_data"]["mails"];
		}else{
			$mails[] = $this->CI->pg_module->get_module_config('contact_us', 'default_alert_email');
		}

		if(empty($mails)){
			$return["errors"][] = l('error_no_recipients', 'contact_us');
		}else{
			foreach($mails as $mail){
				$send_data = $this->CI->Notifications_model->send_notification($mail, 'contact_us_form', $data);
				if(!empty($send_data["errors"])){
					foreach($send_data["errors"] as $error) $return["errors"][] = $error;
				}
			}
		}
		return $return;
	}

	private function _check_badwords($string) {
		$this->CI->load->model('moderation/models/Moderation_badwords_model');
		return $this->CI->Moderation_badwords_model->check_badwords($this->_moderation_type, $string);
	}

	public function validate_contact_form($data) {
		$return = array("errors" => array(), "data" => array());

		if (isset($data["user_name"])) {
			$return["data"]["user_name"] = trim(strip_tags($data["user_name"]));

			if (empty($return["data"]["user_name"])) {
				$return["errors"]['user_name'] = l('error_user_name_incorrect', 'contact_us');
			} elseif ($this->_check_badwords($return['data']['user_name'])) {
				$return['errors']['user_name'] = l('error_badwords_message', 'contact_us');
			}
		}

		if (isset($data["user_email"])) {
			$return["data"]["user_email"] = trim(strip_tags($data["user_email"]));

			$this->CI->config->load('reg_exps', TRUE);
			$email_expr = $this->CI->config->item('email', 'reg_exps');
			if (empty($return["data"]["user_email"]) || !preg_match($email_expr, $return["data"]["user_email"])) {
				$return["errors"]['user_email'] = l('error_user_email_incorrect', 'contact_us');
			}
		}

		if (isset($data["subject"])) {
			$return["data"]["subject"] = trim(strip_tags($data["subject"]));

			if (empty($return["data"]["subject"])) {
				$return["errors"]['subject'] = l('error_subject_incorrect', 'contact_us');
			} elseif ($this->_check_badwords($return['data']['subject'])) {
				$return['errors']['subject'] = l('error_badwords_message', 'contact_us');
			}
		}

		if (isset($data["message"])) {
			$return["data"]["message"] = trim(strip_tags($data["message"]));

			if (empty($return["data"]["message"])) {
				$return["errors"]['message'] = l('error_message_incorrect', 'contact_us');
			} elseif ($this->_check_badwords($return['data']['message'])) {
				$return['errors']['message'] = l('error_badwords_message', 'contact_us');
			}
		}

		if (isset($data["id_reason"])) {
			$return["data"]["id_reason"] = intval($data["id_reason"]);
			if (!empty($return["data"]["id_reason"])) {
				$return["data"]["reason_data"] = $this->get_reason_by_id($return["data"]["id_reason"]);
				$return["data"]["reason"] = $return["data"]["reason_data"]["name"];
			} else {
				$return["data"]["reason"] = l('no_reason_filled', 'contact_us');
			}
		}

		if (isset($data["captcha_code"])) {
			$return["data"]["captcha_code"] = trim(strip_tags($data["captcha_code"]));

			if (empty($return["data"]["captcha_code"]) || $return["data"]["captcha_code"] != $_SESSION["captcha_word"]) {
				$return["errors"]['captcha_code'] = l('error_captcha_code_incorrect', 'contact_us');
			}
		}

		$data["data"]["form_date"] = date("Y-m-d H:i:s");
		return $return;
	}

	////// seo
	function get_seo_settings($method='', $lang_id=''){
		if(!empty($method)){
			return $this->_get_seo_settings($method, $lang_id);
		}else{
			$actions = array('index');
			$return = array();
			foreach($actions as $action){
				$return[$action] = $this->_get_seo_settings($action, $lang_id);
			}
			return $return;
		}
	}

	function _get_seo_settings($method, $lang_id=''){
		if($method == "index"){
			return array(
				'templates' => array(),
				'url_vars' => array(),
				'url_postfix' => array(),
				'optional' => array(),
			);
		}
	}

	function request_seo_rewrite($var_name_from, $var_name_to, $value){
		$user_data = array();

		if($var_name_from == $var_name_to){
			return $value;
		}
		
		show_404();
	}

	function get_sitemap_xml_urls(){
		$this->CI->load->helper('seo');
		$return = array(
			array(
				"url" => rewrite_link('contact_us', 'index'),
				"priority" => 0.1
			)
		);
		return $return;
	}

	function get_sitemap_urls(){
		$this->CI->load->helper('seo');
		$auth = $this->CI->session->userdata("auth_type");

		$block[] = array(
			"name" => l('header_contact_us_form', 'contact_us'),
			"link" => rewrite_link('contact_us', 'index'),
			"clickable" => true,
		);
		return $block;
	}

	////// banners callback method
	public function _banner_available_pages(){
		$return[] = array("link"=>"contact_us/index", "name"=> l('header_contact_us_form', 'contact_us'));
		return $return;
	}

}
