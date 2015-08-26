<?php  
/**
* Textarea field model
* 
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Textarea_field_model extends Field_type_model{
	public $base_field_param = array(
		'type' => 'TEXT',
		'null' => FALSE,
		'default' => '',
	);

	public $manage_field_param = array(
		'default_value' => array( 'type'=>'text', "default"=>'' ),
		'min_char' => array( 'type'=>'int', 'min' => 0, "default"=>0 ),
		'max_char' => array( 'type'=>'int', 'min' => 0, "default"=>'' ),
	);
	
	private $moderation_type = 'field_editor';
	public $form_field_settings = array();
	
	public function validate_field_type($settings_data){
		$return = parent::validate_field_type($settings_data);

		$settings = $this->manage_field_param;

		if($return["data"]["min_char"] < $settings["min_char"]["min"]){
			$return["data"]["min_char"] = $settings["min_char"]["min"];
		}
		if($return["data"]["max_char"] < $settings["max_char"]["min"]){
			$return["data"]["max_char"] = $settings["max_char"]["min"];
		}

		return $return;
	}
	
	public function format_form_fields($field, $content = null){
		$field["value"] = !is_null($content) ? $content : $field["settings_data_array"]["default_value"];
		return $field;
	}
	
	public function validate_field($settings, $value){
		$return = array("errors"=> array(), "data" => $value);
		$return["data"] = trim(strip_tags($return["data"]));
		$string_length = strlen($return["data"]);
		if($settings["settings_data_array"]["min_char"] > $string_length){
			$return["errors"][] = str_replace("[length]", $settings["settings_data_array"]["min_char"], l('error_field_length_more_than', 'field_editor'));
		}
		if($settings["settings_data_array"]["max_char"] && $settings["settings_data_array"]["max_char"] < $string_length){
			$return["errors"][] = str_replace("[length]", $field["settings_data_array"]["max_char"], l('error_field_length_less_than', 'field_editor'));
		}
		$this->CI->load->model('moderation/models/Moderation_badwords_model');
		$bw_count = $this->CI->Moderation_badwords_model->check_badwords($this->moderation_type, $return['data']);
		if($bw_count){
			$return['errors'][] = l('error_badwords_text', 'field_editor');
		}
		return $return;
	}

	public function get_search_field_criteria($field, $settings, $data, $prefix){
		$criteria = array();
		$gid = $field['gid'];
		if(!empty($data[$gid])){
			$criteria["where"][$prefix.$gid." LIKE"] = "%".trim(strip_tags($data[$gid]))."%";
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

