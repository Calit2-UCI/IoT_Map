<?php
/**
* Field Editor Forms Model
* 
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

if (!defined('FIELD_EDITOR_FORMS')) define('FIELD_EDITOR_FORMS', DB_PREFIX.'field_editor_forms');

class Field_editor_forms_model extends Model{

	private $CI;
	private $DB;

	private $form_section_lang_prefix = 'fs_';
	private $form_section_module_prefix = 'fslng_';
	
	private $fields;
	
	private $form_fields = array(
		'id',
		'gid',
		'editor_type_gid',
		'name',
		'field_data',
		'is_system',
	);
	private $form_fields_str;
	
	function __construct(){
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;
		$this->form_fields_str = implode(', ', $this->form_fields);
		
		include_once(MODULEPATH . "field_editor/models/field_type_loader_model".EXT);
		$this->fields = new Field_types_loader_model();
	}

	public function get_form_by_id($id){
		$result = $this->DB->select($this->form_fields_str)->from(FIELD_EDITOR_FORMS)->where("id", $id)->get()->result_array();
		$return = (!empty($result))?$result[0]:array();
		if(!empty($return["field_data"])){
			$return["field_data"] = unserialize($return["field_data"]);
		}
		return $return;		
	}
	
	public function get_form_by_gid($gid, $editor_type_gid=""){
		$this->DB->select($this->form_fields_str)->from(FIELD_EDITOR_FORMS)->where("gid", $gid);
		if(!empty($editor_type_gid)){
			$this->DB->where("editor_type_gid", $editor_type_gid);
		}
		$result = $this->DB->get()->result_array();
		$return = (!empty($result))?$result[0]:array();
		if(!empty($return["field_data"])){
			$return["field_data"] = unserialize($return["field_data"]);
		}
		return $return;		
	}
	
	public function format_form($data){
		list($section_gids, $field_gids) = $this->get_form_field_gids($data["field_data"]);

		if(!empty($field_gids)){
			$this->CI->load->model('Field_editor_model');
			$this->CI->Field_editor_model->initialize($data["editor_type_gid"]);
			$params["where"]["editor_type_gid"] = $data["editor_type_gid"];
			$params["where_in"]["gid"] = $field_gids;
			$fields = $this->CI->Field_editor_model->get_fields_list($params);

			foreach($data["field_data"] as $key => $item){
				if($item["type"] == 'section'){
					foreach($item["section"]["fields"] as $skey => $sitem){
						if(!isset($fields[$sitem["field"]["gid"]])){
							unset($data["field_data"][$key]["section"]["fields"][$skey]);
						}
					}
				}else{
					if(!isset($fields[$item["field"]["gid"]])){
						unset($data["field_data"][$key]);
					}
				}
			}
		}

		return $data;
	}
	
	public function format_output_form($data, $content = array(), $defaults = false){

		list($section_gids, $field_gids) = $this->get_form_field_gids($data["field_data"]);

		if(!empty($section_gids)){
			$sections = $this->get_form_sections($data["editor_type_gid"]);

			foreach($data["field_data"] as $key => $item){
				if($item["type"] == 'section'){
					$data["field_data"][$key]["section"]["name"] = $sections[$item["section"]["gid"]];
				}
			}
		}
		
		if(!empty($field_gids)){
			$this->CI->load->model('Field_editor_model');
			$this->CI->Field_editor_model->initialize($data["editor_type_gid"]);
			$params["where"]["editor_type_gid"] = $data["editor_type_gid"];
			$params["where_in"]["gid"] = $field_gids;
			$fields = $this->CI->Field_editor_model->get_fields_list($params);

			foreach($data["field_data"] as $key => $item){
				if($item["type"] == 'section'){
					foreach($item["section"]["fields"] as $skey => $sitem){
						if(isset($fields[$sitem["field"]["gid"]])){
							$field_content = $fields[$sitem["field"]["gid"]];
							if(isset($content[$field_content["field_name"]])){
								$field_content = $this->CI->Field_editor_model->fields->$field_content["field_type"]->format_form_fields($field_content, $content[$field_content["field_name"]]);
							}elseif($defaults){
								$field_content = $this->CI->Field_editor_model->fields->$field_content["field_type"]->format_form_fields($field_content);
							}
							$data["field_data"][$key]["section"]["fields"][$skey]["field_content"] = $field_content;
						}else{
							unset($data["field_data"][$key]["section"]["fields"][$skey]);
						}
					}
				}else{
					if(isset($fields[$item["field"]["gid"]])){
						$field_content = $fields[$item["field"]["gid"]];
						if(isset($content[$field_content["field_name"]])){
							$field_content = $this->CI->Field_editor_model->fields->$field_content["field_type"]->format_form_fields($field_content, $content[$field_content["field_name"]]);
						}elseif($defaults){
							$field_content = $this->CI->Field_editor_model->fields->$field_content["field_type"]->format_form_fields($field_content);
						}
						$data["field_data"][$key]["field_content"] = $field_content;
					}else{
						unset($data["field_data"][$key]);
					}
				}
			}
		}
		return $data;
	}
	
	public function get_forms_list($params=array(), $order_by=array()){
		$this->DB->select($this->form_fields_str)->from(FIELD_EDITOR_FORMS);

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

		if(is_array($order_by) && count($order_by)>0){
			foreach($order_by as $field => $dir){
				$this->DB->order_by($field." ".$dir);
			}
		}
		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			foreach($results as $k=>$r){
				$return[$r["gid"]] = $r;
			}
			return $return;
		}
		return array();
		
	}
	
	public function get_forms_count($params=array()){
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
		return $this->DB->count_all_results(FIELD_EDITOR_FORMS);
	}
	
	public function validate_form($id, $data){
		$return = array("errors"=> array(), "data" => array());
	
		if(isset($data["gid"])){
			$data["gid"] = strtolower(strip_tags($data["gid"]));
			$data["gid"] = preg_replace("/[\n\s\t]+/i", '-', $data["gid"]);
			$data["gid"] = preg_replace("/[^a-z0-9\-_]+/i", '', $data["gid"]);
			$data["gid"] = preg_replace("/[\-]{2,}/i", '-', $data["gid"]);

			$return["data"]["gid"] = $data["gid"];
			
			if(empty($return["data"]["gid"]) ){
				$return["errors"][] = l('error_form_code_incorrect', 'field_editor');
			}else{
				$param["where"]["gid"] = $return["data"]["gid"];
				if($id) $param["where"]["id <>"] = $id;
				$gid_counts = $this->get_forms_count($param);
				if($gid_counts > 0){
					$return["errors"][] = l('error_form_code_exists', 'field_editor');
				}
			}
		}

		if(isset($data["editor_type_gid"])){
			$return["data"]["editor_type_gid"] = strval($data["editor_type_gid"]);
			if(empty($return["data"]["editor_type_gid"]) ){
				$return["errors"][] = l('error_form_editor_type_incorrect', 'field_editor');
			}
		}

		if(isset($data["name"])){
			$return["data"]["name"] = trim(strip_tags($data["name"]));
			if(empty($return["data"]["name"]) ){
				$return["errors"][] = l('error_form_name_incorrect', 'field_editor');
			}
		}
		
		if(isset($data["field_data"]) && is_array($data["field_data"])){
			foreach($data["field_data"] as $field){
				if(is_array($field)){
					if($field["type"] == 'section'){
						$section_fields = $field["section"]['fields'];
						$field["section"]['fields'] = array();
						if(is_array($section_fields) && !empty($section_fields)){
							foreach($section_fields as $sfield){
								if(is_array($sfield)){
									$field["section"]['fields'][] = $sfield;
								}
							}
						}
					}
					$return["data"]["field_data"][] = $field;
				}
			}
			$return["data"]["field_data"] = serialize($return["data"]["field_data"]);
		}elseif(isset($data["field_data"])){
			$return["data"]["field_data"] = serialize(array());
		}
		return $return;
		
	}
	
	public function save_form($id, $data){
		if (empty($id)){
			$this->DB->insert(FIELD_EDITOR_FORMS, $data);
			$id = $this->DB->insert_id();
		}else{
			$this->DB->where('id', $id);
			$this->DB->update(FIELD_EDITOR_FORMS, $data);
		}
		return $id;
	}
	
	public function delete_form_by_id($id){
		$this->DB->where('id', $id);
		$this->DB->delete(FIELD_EDITOR_FORMS);
		return;
	}
	
	public function delete_form_by_gid($gid){
		$this->DB->where('gid', $gid);
		$this->DB->delete(FIELD_EDITOR_FORMS);
		return;
	}
	
	public function form_section_save($form_editor_type_gid, $section_gid, $name){
		if(!$section_gid){
			$section_gid = substr(md5(date('Y-m-d H:i:s')), 0, 6);	
		}
		$module_gid = $this->form_section_module_prefix.$form_editor_type_gid;
		$section = $this->form_section_lang_prefix.$section_gid;
		if(!empty($name)){
			$languages = $this->CI->pg_language->languages;
			if(!empty($languages)){
				$lang_ids = array_keys($languages);
				$this->CI->pg_language->pages->set_string_langs($module_gid, $section, $name, $lang_ids);
			}
		}
		return $section_gid;
	}
	
	public function form_section_delete($form_editor_type_gid, $section_gid){
		$module_gid = $this->form_section_module_prefix.$form_editor_type_gid;
		$section = $this->form_section_lang_prefix.$section_gid;

		$this->CI->pg_language->pages->delete_string($module_gid, $section);		
	}
	
	public function get_form_sections($form_editor_type_gid, $lang_id=''){
		$module_gid = $this->form_section_module_prefix.$form_editor_type_gid;
		$raw_sections = $this->CI->pg_language->pages->return_module($module_gid, $lang_id);
		if(!empty($raw_sections)){
			foreach($raw_sections as $gid => $value){
				$sections[str_replace($this->form_section_lang_prefix, '', $gid)] = $value;
			}
			return $sections;
		}
		return false;
	}
	
	public function get_form_section($form_editor_type_gid, $section_gid, $lang_id=''){
		$module_gid = $this->form_section_module_prefix.$form_editor_type_gid;
		$section = $this->form_section_lang_prefix.$section_gid;

		if($lang_id == 'all'){
			$languages = $this->CI->pg_language->languages;
			if(!empty($languages)){
				foreach($languages as $lang_id => $lang){
					$data[$lang_id] = l($section, $module_gid, $lang_id);
				}
			}
		}else{
			$data = l($section, $module_gid, $lang_id);
		}
		return $data;
		
	}
	
	public function get_form_field_settings($field_type){
		return $this->fields->$field_type->form_field_settings;
	}
	
	public function get_form_field_gids($field_data){
		$disallowed_fields = array();
		$disallowed_sections = array();
		if(!empty($field_data)){
			foreach($field_data as $item){
				if($item["type"] == 'section'){
					$disallowed_sections[] = $item["section"]['gid'];
					
					if(!empty($item["section"]["fields"])){
						foreach($item["section"]["fields"] as $field){
							if(is_array($field)) $disallowed_fields[] = $field["field"]['gid'];
						}
					}
				}elseif($item["type"] == 'field'){
					$disallowed_fields[] = $item["field"]['gid'];
				}
			}		
		}
		return array($disallowed_sections, $disallowed_fields);
	}
	
	public function get_search_criteria($form_gid, $data, $editor_type_gid="", $use_fe_prefix = true){
		$criteria = array();
		if(empty($data)){
			return $criteria;
		}
		$form = $this->get_form_by_gid($form_gid, $editor_type_gid);
		if(empty($form["field_data"])){
			return $criteria;
		}

		$this->CI->load->model('Field_editor_model');
		$this->CI->Field_editor_model->initialize($form["editor_type_gid"]);
		$field_gid_prefix = $use_fe_prefix ? '' : $this->CI->Field_editor_model->settings['field_prefix'];
		$prefix = $use_fe_prefix ? $this->CI->Field_editor_model->settings['field_prefix'] : '';

		foreach($form["field_data"] as $item){
			if($item["type"] == 'section'){
				if(!empty($item["section"]["fields"])){
					foreach($item["section"]["fields"] as $field){
						$field['field']['gid'] = $field_gid_prefix.$field['field']['gid'];
						$field_criteria = $this->fields->$field["field"]["type"]->get_search_field_criteria($field["field"], $field["settings"], $data, $prefix);
						if(!empty($field_criteria)) $criteria = array_merge_recursive($criteria, $field_criteria);
					}
				}
			}elseif($item["type"] == 'field'){
				$item['field']['gid'] = $field_gid_prefix.$item['field']['gid'];
				$field_criteria = $this->fields->$item["field"]["type"]->get_search_field_criteria($item["field"], $item["settings"], $data, $prefix);
				if(!empty($field_criteria)) $criteria = array_merge_recursive($criteria, $field_criteria);
			}
		}	
		return $criteria;	
	}
}