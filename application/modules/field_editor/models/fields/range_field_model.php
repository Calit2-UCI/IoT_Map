<?php  
/**
* Text field model
* 
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Dmitry Popenov
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: dpopenov $
**/

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Range_field_model extends Field_type_model{
	public $base_field_param = array(
		'type' => 'DECIMAL',
		'constraint' => '15,3',
		'null' => FALSE,
		'default' => 0,
	);

	public $manage_field_param = array(
		'default_value' => array('type'=>'int', "default"=>0),
		'min_val' => array('type'=>'int', 'min' => -2147483648, 'max' => 2147483648, "default"=>0),
		'max_val' => array('type'=>'int', 'min' => -2147483648, 'max' => 2147483648, "default"=>100),
		'template' => array('type'=>'string', 'options' => array('intval', 'floatval'), "default"=>'intval'),
		'format' => array('type'=>'string', 'options' => array('none', 'year', 'price'), "default"=>'none'),
	);
	
	public $form_field_settings = array(
		"search_type" => array("values"=>array('number', 'range'), "default"=>'range'),
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
		if($return["data"]["min_val"] < $settings["min_val"]["min"]){
			$return["data"]["min_val"] = $settings["min_val"]["min"];
		}
		if($return["data"]["min_val"] > $settings["min_val"]["max"]){
			$return["data"]["min_val"] = $settings["min_val"]["max"];
		}
		if($return["data"]["max_val"] < $settings["max_val"]["min"]){
			$return["data"]["max_val"] = $settings["max_val"]["min"];
		}
		if($return["data"]["max_val"] > $settings["max_val"]["max"]){
			$return["data"]["max_val"] = $settings["max_val"]["max"];
		}

		return $return;
	}
	
	public function format_view_fields($settings, $field, $value){
		$field["value_original"] = $value;
		$value = ($settings["settings_data_array"]['template'] == 'floatval') ? floatval($value) : intval($value);
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
	
	public function validate_field($settings, $value){
		$return = array("errors"=> array(), "data" => $value);
		if($value === ''){
			$return['data'] = null;
			return $return;
		}
		switch($settings["settings_data_array"]["template"]){
			case "intval": $return["data"] = intval($return["data"]); break;
			case "floatval": $return["data"] = floatval(str_replace(',', '.', $return["data"])); break;
		}
		if($settings["settings_data_array"]["min_val"] > $return["data"]){
			$return["data"] = $settings["settings_data_array"]["min_val"];
			//$return["errors"][] = str_replace("[length]", $settings["settings_data_array"]["min_val"], l('error_field_length_more_than', 'field_editor'));
		}
		if($settings["settings_data_array"]["max_val"] < $return["data"]){
			$return["data"] = $settings["settings_data_array"]["max_val"];
			//$return["errors"][] = str_replace("[length]", $settings["settings_data_array"]["max_val"], l('error_field_length_less_than', 'field_editor'));
		}
		return $return;
	}
	
	public function get_search_field_criteria($field, $settings, $data, $prefix){
		$criteria = array();
		$gid = $field['gid'];
		if($settings["search_type"] == "number"){
			if(!empty($data[$gid])){
				$criteria["where"][$prefix.$gid] = $this->_get_search_field_value($data[$gid]);
			}			
		}else{
			if(!empty($data[$gid."_min"])){
				$data[$gid."_min"] = $this->_get_search_field_value($data[$gid."_min"]);
				$criteria["where_sql"][] = "`{$prefix}{$gid}` >= {$data[$gid.'_min']}";
			}			
			if(!empty($data[$gid."_max"])){
				$data[$gid."_max"] = $this->_get_search_field_value($data[$gid."_max"]);
				$criteria["where_sql"][] = "`{$prefix}{$gid}` <= {$data[$gid.'_max']}";
			}			
		}
		return $criteria;
	}
	
	private function _get_search_field_value($value){
		$value = floatval(str_replace(',', '.', $value));
		if($value < $this->manage_field_param["min_val"]["min"]){
			$value = $this->manage_field_param["min_val"]["min"];
		}
		if($value > $this->manage_field_param["max_val"]["max"]){
			$value = $this->manage_field_param["max_val"]["max"];
		}
		return $value;
	}
	
	public function format_form_fields($field, $content = null){
		parent::format_form_fields($field, $content);
		$field["value"] = ($field['settings_data_array']['template'] == 'floatval') ? floatval($content) : intval($content);
		return $field;
	}
	
	public function get_field_name_for_search($field_data){
		if($field_data['settings']["search_type"] == "number"){
			return $field_data['field']['gid'];
		}else{
			return array($field_data['field']['gid'].'_min', $field_data['field']['gid'].'_max');
		}
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
