<?php  

/**
* Payment system driver main model
* 
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Payment_driver_model extends Model
{

	public $settings = array();

	public $html_fields = array();

	protected $variables = array();
	
	public $request_return_type = "redirect";	 /// redirect, text

	public $CI;

	function __construct()
	{
		parent::Model();
		$this->CI = & get_instance();
	}
	
	public function func_request($payment_data, $system_settings){
		$return = array("errors" => array(), "info" => array(), "data"=>$payment_data);
		return $return;
	}

	public function func_responce($payment_data, $system_settings){
		$return = array("errors" => array(), "info" => array(), "data"=>$payment_data);
		return $return;
	}
	
	public function func_html(){
		return false;
	}

	public function func_validate($payment_data, $system_settings){
		$return = array("errors" => array(), "info" => array(), "data"=>$payment_data);
		return $return;
	}
	
	public function validate_settings($data){
		$return = array("errors"=> array(), "data" => array());

		if(!empty($this->settings)){
			foreach($this->settings as $param_id => $param_data){
				$value = isset($data[$param_id])?$data[$param_id]:"";
				switch($param_data["content"]){
					case "float": $value = floatval($value); break;
					case "int": $value = intval($value); break;
					case "string": $value = trim(strip_tags($value)); break;
					case "html": break;
				}
				$return["data"][$param_id] = $value;
			}
		}

		return $return;
	}
	
	public function get_settings_map(){
		return $this->settings;
	}

	public function get_html_map(){
		return $this->html_fields;
	}

	public function send_data($url, $data=array(), $method="post"){
		if($method === "get"){
			$get = array();
			foreach($data as $key => $value){
				$get[] = "$key=$value";
			}
			$get = implode('&', $get);
			if($this->CI->is_pjax){
				redirect("{$url}?{$get}", 'hard');
			}else{
				header("Location: {$url}?{$get}");
			}
			exit;
		}elseif($method === "post"){
			$retHTML = '';
			if(!$this->CI->is_pjax){
				$retHTML .= '<html><body onLoad="document.send_form.submit();">';
			}
			$retHTML .= '<form method="post" name="send_form" id="send_form" action="'.$url.'">';
			foreach($data as $key => $value){
				$retHTML .= '<input type="hidden" name="'.$key.'" value="'.$value.'">';
			}
			if($this->CI->is_pjax){
				$retHTML .= '</form><script>document.getElementById("send_form").submit();</script>';
			}else{
				$retHTML .= '</form></body></html>';
			}
			print $retHTML;
			exit;
		}
		return false;
	}
	
	public function func_js(){
		return false;
	}
	
	public function get_js($payment_data, $system_settings){
		return '';
	}

}
