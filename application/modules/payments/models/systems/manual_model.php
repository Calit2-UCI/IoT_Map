<?php  
/**
* Manual payment driver model
* 
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Manual_model extends Payment_driver_model
{

	public $payment_data = array();

	public $settings = array(
	);

	protected $variables = array(
		
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
		$return = array("errors" => array(), "info" => array(), "data"=>$payment_data, "type"=>"html");
		return $return;
	}
	
	public function get_settings_map(){
		return array();
	}
}
