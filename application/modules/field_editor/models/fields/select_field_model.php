<?php  
/**
* Select field model
* 
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Select_field_model extends Field_type_model{
	public $base_field_param = array(
		'type' => 'INT',
		'constraint' => 3,
		'null' => FALSE,
		'default' => 0,
	);

	public $manage_field_param = array(
		'default_value' => array( 'type'=>'int', 'min' => 0, "default"=>0 ),
		'view_type' => array( 'type'=>'string', 'options' => array('select', 'radio'), "default"=>"select" ),
		'empty_option' => array( 'type'=>'bool', "default"=>true ),
	);
	
	public $form_field_settings = array(
		"search_type" => array("values"=>array('one', 'many'), "default"=>'many'),
		"view_type" => array("values"=>array('select', 'radio'), "default"=>'select')
	);
	
	function __construct(){
		$this->CI = & get_instance();
	}
	
	public function format_field($data, $lang_id=''){
		$data = parent::format_field($data, $lang_id);
		$data["option_module"] = $data["section_gid"].'_lang';
		$data["option_gid"] = 'field_'.$data["gid"].'_opt';
		$data["options"] = ld($data["option_gid"], $data["option_module"], $lang_id);
		return $data;
	}

	public function update_field_name($field, $name){
		parent::update_field_name($field, $name);

		$languages = $this->CI->pg_language->languages;
		$cur_lang_id = $this->CI->pg_language->current_lang_id;
		$default_lang = isset($name[$cur_lang_id]) ? (trim(strip_tags($name[$cur_lang_id]))) : '';
		
		foreach($languages as $lid => $lang_settings){
			$name[$lid] = trim(strip_tags($name[$lid]));
			if(empty($name[$lid])) $name[$lid] = $default_lang;

			$reference = $this->CI->pg_language->get_reference($field['section_gid'].'_lang', 'field_'.$field['gid'].'_opt', $lid);
			$reference["header"] = $name[$lid];
			$this->CI->pg_language->ds->set_module_reference($field['section_gid'].'_lang', 'field_'.$field['gid'].'_opt', $reference, $lid);
		}
		return;
	}

	
	public function delete_field_lang($type, $section, $gid){
		parent::delete_field_lang($type, $section, $gid);
		$this->CI->pg_language->ds->delete_reference($section.'_lang', 'field_'.$gid.'_opt');
	}
	
	public function validate_field_type($settings_data){
		$return = parent::validate_field_type($settings_data);

		$settings = $this->manage_field_param;
		if(!in_array($return["data"]["view_type"], $settings["view_type"]["options"])){
			$return["data"]["view_type"] = $settings["view_type"]["default"];
		}

		return $return;
	}
	
	public function format_view_fields($settings, $field, $value){
		$field["value_int"] = $value;
		if(isset($settings["options"]["option"][$value])){
			$field["value"] = $settings["options"]["option"][$value];
		}elseif(strval($value === '0')){
			$field["value"] = '';
		}elseif(isset($settings["options"]["option"][$settings["settings_data_array"]["default_value"]])){
			$field["value"] = $settings["options"]["option"][$settings["settings_data_array"]["default_value"]];
		}else{
			$field["value"] = '';
		}
		return $field;
	}
	
	public function format_form_fields($field, $content = null){
		$field["value"] = !is_null($content) ? $content : "";
		if(empty($field["value"]) && strval($field["value"] !== '0')){
			$field["value"] = $field["settings_data_array"]["default_value"];
		}
		return $field;
	}
	
	public function format_fulltext_fields($settings, $field, $value){
		if(!empty($settings["options"]["option"][$value])){
			$return = $field["name"]." ".trim($settings["options"]["option"][$value]).";";
		}elseif(!empty($settings["options"]["option"][$settings["settings_data_array"]["default_value"]])){
			$return = $field["name"]." ".trim($settings["options"]["option"][$settings["settings_data_array"]["default_value"]]).";";
		}else{
			$return = '';
		}
		return $return;
	}
	
	public function validate_field($settings, $value){
		$return['errors'] = array();
		if($value === ''){
			$return['data'] = null;
			return $return;
		}
		if(is_array($value)){
			$return['data'] = array_map(function($val){return strval(trim(strip_tags($val)));}, $value);
		}else{
			$return['data'] = strval(trim(strip_tags($value)));
		}
		return $return;
	}

	public function validate_field_for_save($settings, $value){
		$data = is_array($value) ? $this->arr_to_dec($value) : ($value === '' ? null : $value);
		$return = array("errors"=> array(), "data" => $data);
		return $return;
	}

	public function get_search_field_criteria($field, $settings, $data, $prefix){
		$criteria = array();
		$gid = $field['gid'];
		if($settings["search_type"] == "one"){
			if(!empty($data[$gid])) $criteria["where"][$prefix.$gid] = intval($data[$gid]);
		}elseif($settings["view_type"] == 'slider'){
			if(!empty($data[$gid.'_min'])) $criteria["where"][$prefix.$gid." >= "] = intval($data[$gid.'_min']); 
			if(!empty($data[$gid.'_max'])) $criteria["where"][$prefix.$gid." <= "] = intval($data[$gid.'_max']); 
		}elseif(is_array($data[$gid])){
			if(!empty($data[$gid])) $criteria["where_in"][$prefix.$gid] = $data[$gid];
		}
		return $criteria;
	}
	
}