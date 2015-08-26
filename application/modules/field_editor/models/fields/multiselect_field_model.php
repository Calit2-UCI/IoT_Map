<?php  
/**
* Multiselect field model
* 
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Multiselect_field_model extends Field_type_model{
	public $base_field_param = array(
		'type' => 'INT',
		'constraint' => 3,
		'null' => FALSE,
		'default' => 0,
	);

	public $manage_field_param = array(
		'default_value' => array( 'type'=>'array', "default"=>'' ),
		'view_type' => array( 'type'=>'string', 'options' => array('mselect', 'checkbox'), "default"=>"checkbox" ),
	);
	
	public $form_field_settings = array(
		"search_type" => array("values"=>array('one', 'many'), "default"=>'one'),
		"view_type" => array("values"=>array('mselect', 'radio'), "default"=>'mselect'),
		"search_match" => array("values"=>array('strict', 'notstrict'), "default"=>'notstrict')
	);
	
	function __construct(){
		$this->CI = & get_instance();
	}
	
	public function format_field($data, $lang_id=''){
		$data = parent::format_field($data);
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

	public function format_form_fields($field, $content = null){
		$field["value"] = !is_null($content) ? $content : "";
		if($field["value"] === ''){
			$field["value"] = $field["settings_data_array"]["default_value"];
		}else{
			$field["value"] = $this->dec_to_arr($field["value"]);
		}
		return $field;
	}
	
	public function format_view_fields($settings, $field, $value){
		$field["value_dec"] = $value;
		$field["value_array"] = $this->dec_to_arr($value);
		$field["value"] = array();
		foreach($field["value_array"] as $v){
			if(isset($settings["options"]["option"][$v])){
				$field["value"][$v] = $settings["options"]["option"][$v];
			}			
		}
		$field["value_str"] = implode(', ', $field["value"]);
		return $field;
	}

	public function format_fulltext_fields($settings, $field, $value){
		$field_array = $this->dec_to_arr($value);
		foreach($field_array as $v){
			if(!empty($settings["options"]["option"][$v])){
				$values[] = trim($settings["options"]["option"][$v]);
			}			
		}
		$return = (!empty($values)) ? ($field["name"]." ".implode(', ', $values).";") : '';
		return $return;
	}

	public function validate_field($settings, $value){
		$data = is_array($value) ? $this->arr_to_dec($value) : ($value === '' ? null : $value);
		$return = array("errors"=> array(), "data" => $data);
		return $return;
	}

	public function get_search_field_criteria($field, $settings, $data, $prefix){
		$criteria = array();
		$gid = $field['gid'];
		if(!empty($data[$gid])){
			if($settings["search_type"] == "one" && !is_array($data[$gid])){
				$temp = $data[$gid];
			}elseif(is_array($data[$gid])){
				$temp = $this->arr_to_dec($data[$gid]);
			}
			if(!empty($settings['search_match']) && $settings['search_match'] == 'strict'){
				$criteria["where_sql"][] = "{$prefix}{$gid} & {$temp} = {$temp}";
			}elseif($temp){
				$criteria["where_sql"][] = "{$prefix}{$gid} & {$temp} != 0";
			}
		}
		return $criteria;
	}
}