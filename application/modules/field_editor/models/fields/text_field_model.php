<?php  
/**
* Text field model
* 
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Text_field_model extends Field_type_model{
	public $base_field_param = array(
		'type' => 'VARCHAR',
		'constraint' => '255',
		'null' => FALSE,
		'default' => '',
	);

	public $manage_field_param = array(
		'default_value' => array( 'type'=>'string', "default"=>'' ),
		'min_char' => array( 'type'=>'int', 'min' => 0, 'max' => 255, "default"=>0 ),
		'max_char' => array( 'type'=>'int', 'min' => 1, 'max' => 255, "default"=>255 ),
		'template' => array( 'type'=>'string', 'options' => array('string', 'intval', 'floatval', 'email', 'url', 'price'), "default"=>'string'),
		'format' => array( 'type'=>'string', 'options' => array('none', 'year', 'price'), "default"=>'none'),
	);
	private $moderation_type = 'field_editor';
	public $form_field_settings = array(
		"search_type" => array("values"=>array('text', 'number'), "default"=>'text'),
		"view_type" => array("values"=>array('equal', 'range'), "default"=>'equal')
	);
	
	public function validate_field_type($settings_data){
		$return = parent::validate_field_type($settings_data);

		$settings = $this->manage_field_param;
		if(!in_array($return["data"]["template"], $settings["template"]["options"])){
			$return["data"]["template"] = $settings["template"]["default"];
		}
		if(!in_array($return["data"]["format"], $settings["format"]["options"])){
			$return["data"]["format"] = $settings["format"]["default"];
		}
		if($return["data"]["min_char"] < $settings["min_char"]["min"]){
			$return["data"]["min_char"] = $settings["min_char"]["min"];
		}
		if($return["data"]["min_char"] > $settings["min_char"]["max"]){
			$return["data"]["min_char"] = $settings["min_char"]["max"];
		}
		if($return["data"]["max_char"] < $settings["max_char"]["min"]){
			$return["data"]["max_char"] = $settings["max_char"]["min"];
		}
		if($return["data"]["max_char"] > $settings["max_char"]["max"]){
			$return["data"]["max_char"] = $settings["max_char"]["max"];
		}

		return $return;
	}
	
	public function format_view_fields($settings, $field, $value){
		$field["value_original"] = $value;
		switch($settings["settings_data_array"]["format"]){
			case "price":
				$field["value"] = sprintf("%01.2f", $value);
			break;
			case "year":
				$field["value"] = sprintf("%4d", $value);
			break;
			case "none":
				$field["value"] = $value;
			break;
		}
		return $field;
	}
	
	public function format_form_fields($field, $content = null){
		$field["value"] = !is_null($content) ? $content : $field["settings_data_array"]["default_value"];
		return $field;
	}
	
	public function validate_field($settings, $value){
		$return = array("errors"=> array(), "data" => $value);
		switch($settings["settings_data_array"]["template"]){
			case "string": $return["data"] = trim(strip_tags($return["data"])); break;
			case "intval": $return["data"] = intval($return["data"]); break;
			case "floatval": $return["data"] = floatval($return["data"]); break;
			case "email":
				$return["data"] = trim(strip_tags($return["data"]));
				$this->CI->config->load('reg_exps', TRUE);
				$email_expr =  $this->CI->config->item('email', 'reg_exps');
				if(!preg_match($email_expr, $return["data"])){
					$return["errors"][] = l('error_email_incorrect', 'field_editor');
				}
			break;
			case "url":
				$return["data"] = trim(strip_tags($return["data"]));
				$this->CI->config->load('reg_exps', TRUE);
				$url_expr =  $this->CI->config->item('url', 'reg_exps');
				if(!preg_match($url_expr, $return["data"])){
					$return["errors"][] = l('error_url_incorrect', 'field_editor');
				}
			break;
			case "price":
				$return["data"] = sprintf("%01.2f", $return["data"]);
			break;
		}
		$string_length = strlen($return["data"]);
		if($settings["settings_data_array"]["min_char"] > $string_length){
			$return["errors"][] = str_replace("[length]", $settings["settings_data_array"]["min_char"], l('error_field_length_more_than', 'field_editor'));
		}
		if($settings["settings_data_array"]["max_char"] < $string_length){
			$return["errors"][] = str_replace("[length]", $settings["settings_data_array"]["max_char"], l('error_field_length_less_than', 'field_editor'));
		}
		$this->CI->load->model('moderation/models/Moderation_badwords_model');
		$bw_count = $this->CI->Moderation_badwords_model->check_badwords($this->moderation_type, $return['data']);
		if($bw_count){
			$return['errors'][] = l('error_badwords_text', 'field_editor');
		}
		return $return;
	}
	
	public function get_field_name_for_search($field_data){
		if($field_data['settings']["view_type"] == "range"){
			return array($field_data['field']['gid'].'_min', $field_data['field']['gid'].'_max');
		}else{
			return $field_data['field']['gid'];
		}
	}

	public function get_search_field_criteria($field, $settings, $data, $prefix){
		$criteria = array();
		$gid = $field['gid'];
		if($settings["search_type"] == "text"){
			if(!empty($data[$gid])){
				if($settings["view_type"] == "equal"){
					$criteria["where"][$prefix.$gid] = trim(strip_tags($data[$gid]));
				}else{
					$criteria["where"][$prefix.$gid." LIKE"] = '%'.trim(strip_tags($data[$gid])).'%';
				}
			}			
		}else{
			if($settings["view_type"] == "equal"){
				if(!empty($data[$gid])){
					$data[$gid] = (!is_numeric($data[$gid]))?floatval($data[$gid]):$data[$gid];
					$criteria["where"][$prefix.$gid] = $data[$gid];
				}			
			}else{
				if(!empty($data[$gid."_min"])){
					$data[$gid."_min"] = (!is_numeric($data[$gid."_min"]))?floatval($data[$gid."_min"]):$data[$gid."_min"];
					$criteria["where_sql"][] = "CONVERT(`{$prefix}{$gid}`, DECIMAL(22,10)) >= {$data[$gid.'_min']}";
				}			
				if(!empty($data[$gid."_max"])){
					$data[$gid."_max"] = (!is_numeric($data[$gid."_max"]))?floatval($data[$gid."_max"]):$data[$gid."_max"];
					$criteria["where_sql"][] = "CONVERT(`{$prefix}{$gid}`, DECIMAL(22,10)) <= {$data[$gid.'_max']}";
				}			
			}
		}
		return $criteria;
	}

	public function set_field_option($field, $option_gid, $data){
		return;
	}

	public function delete_field_option($field, $option_gid){
		return;
	}	

	public function sorter_field_option($field, $sorter_data){
		return;
	}
	
}
