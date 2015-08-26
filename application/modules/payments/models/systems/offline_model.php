<?php  
/**
* Offline payment driver model
* 
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Offline_model extends Payment_driver_model
{
	
	public $payment_data = array(
		'gid' => 'offline',
		'name' => 'Offline payment',
		'settings_data' => '',
		'logo' => 'logo_offline.png',
	);

	public $settings = array(
		
	);

	protected $variables = array(
		
	);

	public $html_fields = array(
		"comment" => array("type"=>"textarea", "content"=>"string", "size"=>"long"),
	);
	
	public $info_fields = array(
		"comment" => array("type"=>"textarea", "content"=>"string", "size"=>"long")
	);

	function __construct()
	{
		parent::__construct();
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
		return true;
	}

	public function func_validate($payment_data, $system_settings){
		$return = array("errors" => array(), "info" => array(), "data"=>$payment_data);

		if(!empty($this->html_fields)){
			foreach($this->html_fields as $param_id => $param_data){
				if($param_data["content"] == "string"){
					$return["data"][$param_id] = trim(strip_tags($payment_data[$param_id]));
				}elseif($param_data["content"] == "int"){
					$return["data"][$param_id] = intval($payment_data[$param_id]);
				}else{
					$return["data"][$param_id] = $payment_data[$param_id];
				}
			}
		}
		return $return;
	}

	public function get_settings_map(){
		foreach($this->settings as $param_id => $param_data){
			$this->settings[$param_id]["name"] = l('system_field_'.$param_id, 'payments');
		}
		return $this->settings;
	}

	public function get_html_map(){
		foreach($this->html_fields as $param_id => $param_data){
			$this->html_fields[$param_id]["name"] = l('html_field_'.$param_id, 'payments');
		}
		return $this->html_fields;
	}
}
