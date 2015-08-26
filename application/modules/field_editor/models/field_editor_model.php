<?php
/**
* Field Editor Model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

if (!defined('FIELD_EDITOR_SECTIONS')) define('FIELD_EDITOR_SECTIONS', DB_PREFIX.'field_editor_sections');
if (!defined('FIELD_EDITOR_FIELDS')) define('FIELD_EDITOR_FIELDS', DB_PREFIX.'field_editor_fields');


class Field_editor_model extends Model{

	private $CI;
	private $DB;
	private $_default_editor_type = '';
	public $settings = array();

	private $_cache_section_by_id;
	private $_cache_section_by_gid;

	public $fields;

	public function __construct(){
		parent::Model();
		$this->CI = &get_instance();
		$this->DB = &$this->CI->db;

		include_once(MODULEPATH . "field_editor/models/field_type_loader_model".EXT);
		$this->fields = new Field_types_loader_model();

		$this->get_default_editor_type(true);
		$this->initialize($this->_default_editor_type);
	}

	public function initialize($type=null){
		$this->CI->config->load('field_editor', TRUE);
		$settings = $this->CI->config->item('editor_type', 'field_editor');
		if(!$type) $type = $this->get_default_editor_type(true);
		$this->settings = $settings[$type];
	}

	public function get_settings(){
		return $this->settings;
	}

	public function get_editor_types($installed_only = false){
		$this->config->load('field_editor', TRUE);
		$settings = $this->config->item('editor_type', 'field_editor');

		$return = array();
		if ($installed_only){
			foreach ($settings as $type => $sett) {
				if ($this->CI->pg_module->is_module_installed($sett['module'])){
					$return[$type] = $sett;
				}
			}
		} else {
			$return = $settings;
		}
		return $return;
	}

	public function get_default_editor_type($installed_only = false){
		if ($installed_only){
			$editor_types = $this->get_editor_types($installed_only);
			if (!isset($editor_types[$this->_default_editor_type])){
				$keys = array_keys($editor_types);
				$this->_default_editor_type = array_shift($keys);
			}
		}
		return $this->_default_editor_type;
	}

	public function get_field_settings($field_type){
		return $this->fields->$field_type->manage_field_param;
	}

	/*
	* Sections methods
	*
	*/

	public function get_section_by_id($id){
		if(empty($this->_cache_section_by_id[$id])){
			$result = $this->DB->select("id, gid, editor_type_gid, sorter")->from(FIELD_EDITOR_SECTIONS)->where("id", $id)->get()->result_array();
			$return = (!empty($result))?$this->format_section($result[0]):array();
			$this->_cache_section_by_id[$id] = $this->_cache_section_by_gid[$return["gid"]] = $return;
		}
		return $this->_cache_section_by_id[$id];
	}

	public function get_section_by_gid($gid){
		if(empty($this->_cache_section_by_gid[$gid])){
			$result = $this->DB->select("id, gid, editor_type_gid, sorter")->from(FIELD_EDITOR_SECTIONS)->where("gid", $gid)->where('editor_type_gid', $this->settings['gid'])->get()->result_array();
			$return = (!empty($result))?$this->format_section($result[0]):array();
			if(!empty($return)){
				$this->_cache_section_by_gid[$gid] = $this->_cache_section_by_id[$return["id"]] = $return;
			}
		}
		return $this->_cache_section_by_gid[$gid];
	}

	public function format_section($data, $lang_id = ''){
		if(is_array($lang_id)){
			foreach($lang_id as $lid){
				$data["name_".$lid] = l('section_'.$data["id"], 'field_editor_sections', $lid);
			}
			$lang_id = $this->CI->pg_language->current_lang_id;
		}
		$data["name"] = l('section_'.$data["id"], 'field_editor_sections', $lang_id);
		return $data;
	}

	public function get_section_list($params=array(), $order_by=array(), $lang_id = ''){
		$params["where"]["editor_type_gid"] = $this->settings["gid"];

		$this->DB->select("id, gid, editor_type_gid, sorter")->from(FIELD_EDITOR_SECTIONS);

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
		}else{
			$this->DB->order_by("sorter ASC");
		}
		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			foreach($results as $k=>$r){
				$return[$r["gid"]] = $this->format_section($r, $lang_id);
			}
			return $return;
		}
		return array();

	}

	public function get_section_count($params=array()){
		$params["where"]["editor_type_gid"] = $this->settings["gid"];

		$this->DB->select("COUNT(*) AS cnt")->from(FIELD_EDITOR_SECTIONS);

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

		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			return intval($results[0]["cnt"]);
		}
		return 0;
	}

	public function save_section($id, $data, $name=null){
		if (is_null($id)){
			$this->DB->insert(FIELD_EDITOR_SECTIONS, $data);
			$id = $this->DB->insert_id();
		}else{
			$this->DB->where('id', $id);
			$this->DB->update(FIELD_EDITOR_SECTIONS, $data);
		}

		if(!empty($name)){
			$this->CI->pg_language->pages->set_string_langs('field_editor_sections', "section_".$id, $name, array_keys($name));
		}
		unset($this->_cache_section_by_id[$id]);
		if(!empty($data["gid"])) unset($this->_cache_section_by_gid[$data["gid"]]);
		return $id;
	}

	public function set_section_sorter($id, $sorter){
		$data["sorter"] = intval($sorter);
		$this->DB->where("id", $id);
		$this->DB->update(FIELD_EDITOR_SECTIONS, $data);
	}

	public function validate_section($id, $data, $lang_data=array()){
		$return = array("errors"=> array(), "data" => array(), "lang" => array());

		if(isset($data["gid"])){
			$data["gid"] = strtolower(strip_tags($data["gid"]));
			$data["gid"] = preg_replace("/[\n\s\t]+/i", '-', $data["gid"]);
			$data["gid"] = preg_replace("/[^a-z0-9\-_]+/i", '', $data["gid"]);
			$data["gid"] = preg_replace("/[\-]{2,}/i", '-', $data["gid"]);

			$return["data"]["gid"] = $data["gid"];

			if(empty($return["data"]["gid"]) ){
				$return["errors"][] = l('error_section_code_incorrect', 'field_editor');
			}else{
				$param["where"]["gid"] = $return["data"]["gid"];
				if($id) $param["where"]["id <>"] = $id;
				$gid_counts = $this->get_section_count($param);
				if($gid_counts > 0){
					$return["errors"][] = l('error_section_code_exists', 'field_editor');
				}
			}
		}

		if(isset($data["editor_type_gid"])){
			$return["data"]["editor_type_gid"] = strval($data["editor_type_gid"]);
		}

		if(!empty($lang_data)){
			/// $lang_data is a array(lang_id => value)
			$languages = $this->CI->pg_language->languages;
			$cur_lang_id = $this->CI->pg_language->current_lang_id;
			$default_lang = isset($lang_data[$cur_lang_id]) ? (trim(strip_tags($lang_data[$cur_lang_id]))) : '';
			if($default_lang == ''){
				$return["errors"][] = l('error_section_name_empty', 'field_editor');
			}

			foreach($languages as $id_lang => $lang_settings){
				$return["lang"][$id_lang] = trim(strip_tags($lang_data[$id_lang]));
				if(empty($return["lang"][$id_lang])) $return["lang"][$id_lang] = $default_lang;
			}

		}

		if(isset($data["sorter"])){
			$return["data"]["sorter"] = strval($data["sorter"]);
		}

		return $return;
	}

	public function delete_section($id){
		$data = $this->get_section_by_id($id);
		$params["where"]["section_gid"] = $data["gid"];
		$fields = $this->get_fields_list($params);

		foreach($fields as $field){
			$this->delete_field($field["id"]);
		}

		$this->DB->where('id', $id);
		$this->DB->delete(FIELD_EDITOR_SECTIONS);

		$this->CI->pg_language->pages->delete_string('field_editor_sections', "section_".$id);
		return;
	}

	public function delete_section_by_gid($gid){
		$data = $this->get_section_by_gid($gid);
		$params["where"]["section_gid"] = $data["gid"];
		$params["where"]["editor_type_gid"] = $data["editor_type_gid"];
		$fields = $this->get_fields_list($params);

		foreach($fields as $field){
			$this->delete_field($field["id"]);
		}

		$this->DB->where('gid', $gid);
		$this->DB->delete(FIELD_EDITOR_SECTIONS);

		$this->CI->pg_language->pages->delete_string('field_editor_sections', "section_".$data["id"]);
		return;
	}

	/*
	* BASE Fields methods
	*
	*/
	public function base_field_create($table_name, $field_name, $field_settings){
		$this->CI->load->dbforge();
		$fields[$field_name] = $field_settings;
		$this->CI->dbforge->add_column($table_name, $fields);
		return;
	}

	public function base_field_update($table_name, $field_name, $field_settings){
		$this->CI->load->dbforge();
		$fields[$field_name] = $field_settings;
		$fields[$field_name]["name"] = $field_name;
		$this->CI->dbforge->modify_column($table_name, $fields);
		return;
	}

	public function base_field_delete($table_name, $field_name){
		$this->CI->load->dbforge();
		$fields = $this->DB->list_fields($table_name);
		if(in_array($field_name, $fields)){
			$this->CI->dbforge->drop_column($table_name, $field_name);
		}
		return;
	}

	public function base_update_fulltext_field($id, $content){
		$fields = $this->settings['fulltext_field'];
		$tables = $this->settings['tables'];
		foreach($fields as $table => $field_name){
			$data[$field_name] = trim($content);
			$this->DB->where("id", $id);
			$this->DB->update($tables[$table], $data);
		}
		return;
	}

	/*
	* Manage Fields methods
	*
	*/
	public function get_field_by_id($id, $lang_id=''){
		if(empty($this->cache_field_by_id[$id])){
			$result = $this->DB->select("id, gid, section_gid, editor_type_gid, field_type, fts, settings_data, sorter")->from(FIELD_EDITOR_FIELDS)->where("id", $id)->get()->result_array();
			$return = (!empty($result))?$this->format_field($result[0], $lang_id):array();
			$this->cache_field_by_id[$id] = $this->cache_field_by_gid[$return["gid"]] = $return;
		}
		return $this->cache_field_by_id[$id];
	}

	public function get_field_by_gid($gid, $lang_id=''){
		if(empty($this->cache_field_by_gid[$gid])){
			$result = $this->DB->select("id, gid, section_gid, editor_type_gid, field_type, fts, settings_data, sorter")->from(FIELD_EDITOR_FIELDS)->where("gid", $gid)->get()->result_array();
			$return = (!empty($result))?$this->format_field($result[0], $lang_id):array();
			$this->cache_field_by_gid[$gid] = $this->cache_field_by_id[$return["id"]] = $return;
		}
		return $this->cache_field_by_gid[$gid];
	}

	public function format_field($data, $lang_id=''){
		$data["field_name"] = $this->get_field_select_name($data["gid"]);
		$data["name"] = $this->fields->$data["field_type"]->format_field_name($data, $lang_id);
		$data["settings_data_array"] = unserialize($data["settings_data"]);
		$data = $this->fields->$data["field_type"]->format_field($data, $lang_id);
		return $data;
	}

	public function format_field_name($data, $lang_id=''){
		return $this->fields->$data["field_type"]->format_field_name($data, $lang_id);
	}

	public function get_field_select_name($gid){
		return $this->settings['field_prefix'].$gid;
	}

	public function get_fields_list($params=array(), $filter_object_ids = null, $order_by=array(), $format=true, $lang_id=''){
		$this->DB->select("id, gid, section_gid, editor_type_gid, field_type, fts, settings_data, sorter")->from(FIELD_EDITOR_FIELDS);

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
				$this->DB->order_by($field." ".$dir);
			}
		}else{
			$this->DB->order_by("section_gid ASC");
			$this->DB->order_by("sorter ASC");
		}
		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			foreach($results as $k=>$r){
				$return[$r["gid"]] = ($format)?$this->format_field($r, $lang_id):$r;
			}
			return $return;
		}
		return array();

	}

	public function get_fields_count($params=array(), $filter_object_ids = null){
		$this->DB->select("COUNT(*) AS cnt")->from(FIELD_EDITOR_FIELDS);

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

	public function save_field($id, $type, $section, $data, $name = null){
		if (is_null($id)){
			$this->DB->insert(FIELD_EDITOR_FIELDS, $data);
			$id = $this->DB->insert_id();

			foreach($this->settings["tables"] as $table_gid => $table_name){
				$this->base_field_create($table_name, $this->get_field_select_name($data["gid"]), $this->fields->$data["field_type"]->base_field_param);
			}
			$this->inc_field_index();
		}else{
			$this->DB->where('id', $id);
			$this->DB->update(FIELD_EDITOR_FIELDS, $data);
		}

		if(!empty($name)){
			$field = $this->get_field_by_id($id);
			$this->fields->$data["field_type"]->update_field_name($field, $name);
		}

		unset($this->cache_field_by_id[$id]);
		if(!empty($data["gid"])) unset($this->cache_field_by_gid[$data["gid"]]);
		return $id;
	}

	public function set_field_sorter($id, $sorter){
		$data["sorter"] = intval($sorter);
		$this->DB->where("id", $id);
		$this->DB->update(FIELD_EDITOR_FIELDS, $data);
	}

	public function validate_field_option($id_field, $option_gid, $data){
		$field_data = $this->get_field_by_id($id_field);
		return $this->fields->$field_data["field_type"]->validate_field_option($data);
	}

	public function set_field_option($id_field, $option_gid, $data){
		$field_data = $this->get_field_by_id($id_field);
		$this->fields->$field_data["field_type"]->set_field_option($field_data, $option_gid, $data);
	}

	public function delete_field_option($id_field, $option_gid){
		$field_data = $this->get_field_by_id($id_field);
		$this->fields->$field_data["field_type"]->delete_field_option($field_data, $option_gid);
	}

	public function sorter_field_option($id_field, $sorter_data){
		$field_data = $this->get_field_by_id($id_field);
		$this->fields->$field_data["field_type"]->sorter_field_option($field_data, $sorter_data);
	}

	public function validate_field($id, $field_type, $data, $langs=array()){
		$return = array("errors"=> array(), "data" => array());

		if(empty($id)){
			if(isset($data["section_gid"])){
				$return["data"]["section_gid"] = strval($data["section_gid"]);
			}else{
				$return["errors"][] = l('error_section_empty', 'field_editor');
				$return["data"]["section_gid"] = "";
			}

			if(isset($data["editor_type_gid"])){
				$return["data"]["editor_type_gid"] = strval($data["editor_type_gid"]);
			}else{
				$return["errors"][] = l('error_editor_type_empty', 'field_editor');
				$return["data"]["editor_type_gid"] = "";
			}

			$gid_exists = true;
			while($gid_exists){
				$data['gid'] = $this->get_field_gid();
				$param["where"]["gid"] = $data["gid"];
				$gid_counts = $this->get_fields_count($param);
				if($gid_counts > 0){
					$this->inc_field_index();
				}else{
					$gid_exists = false;
				}
			}
			$return["data"]["gid"] = $data["gid"];

			if(isset($data["fts"])){
				$return["data"]["fts"] = intval($data["fts"]);
			}

			if(isset($data["field_type"])){
				$return["data"]["field_type"] = strval($data["field_type"]);
			}else{
				$return["errors"][] = l('error_field_type_empty', 'field_editor');
				$return["data"]["field_type"] = "text";
			}

			if(isset($data["sorter"])){
				$return["data"]["sorter"] = intval($data["sorter"]);
			}

			$temp = $this->fields->$return["data"]["field_type"]->manage_field_param;
			foreach($temp as $param => $param_data){
				$return["data"]["settings_data"][$param] = $param_data["default"];
			}
			$return["data"]["settings_data"] = serialize($return["data"]["settings_data"]);
		}else{

			if(isset($data["fts"])){
				$return["data"]["fts"] = intval($data["fts"]);
			}

			if(isset($data["settings_data"])){
				$validate_types = $this->fields->$field_type->validate_field_type($data["settings_data"]);
				$return["data"]["settings_data"] = $validate_types["data"];
				if(!empty($validate_types["errors"])){
					foreach($validate_types["errors"] as $err) $return["errors"][] = $err;
				}
				$return["data"]["settings_data"] = serialize($return["data"]["settings_data"]);
			}
		}
		if(!empty($langs)){
			$validate_lang = $this->fields->$field_type->validate_field_name($langs);
			$return = array_merge_recursive($return, $validate_lang);
		}
		return $return;
	}


	public function delete_field($id){
		$data = $this->get_field_by_id($id);

		$this->DB->where('id', $id);
		$this->DB->delete(FIELD_EDITOR_FIELDS);

		unset($this->cache_field_by_gid[$data["gid"]]);

		foreach($this->settings["tables"] as $table_gid => $table_name){
			$this->base_field_delete($table_name, $this->get_field_select_name($data["gid"]));
		}
		$this->fields->$data['field_type']->delete_field_lang($data["editor_type_gid"], $data["section_gid"], $data["gid"]);
		return;
	}

	public function delete_field_by_gid($gid){
		$data = $this->get_field_by_gid($gid);
		if(empty($data)) return;
		$this->DB->where('id', $data["id"]);
		$this->DB->delete(FIELD_EDITOR_FIELDS);

		if(!empty($this->settings["tables"])) {
			foreach($this->settings["tables"] as $table_gid => $table_name){
				$this->base_field_delete($table_name, $this->get_field_select_name($data["gid"]));
			}
		}

		$this->fields->$data['field_type']->delete_field_lang($data["editor_type_gid"], $data["section_gid"], $data["gid"]);
		return;
	}

	/*
	*
	* Data methods
	*/

	public function get_fields_for_select($section_gids=array(), $field_gids=array()){
		$this->DB->select("gid")->from(FIELD_EDITOR_FIELDS);

		if(!empty($field_gids)){
			if(!is_array($field_gids)) $field_gids = array(0=>$field_gids);
			$this->DB->or_where_in('gid', $field_gids);
		}

		if(!empty($section_gids)){
			if(!is_array($section_gids)) $section_gids = array(0=>$section_gids);
			$this->DB->or_where_in('section_gid', $section_gids);
		}

		$results = $this->DB->get()->result_array();
		$return = array();
		foreach($results as $k=>$r){
			$return[] = $this->get_field_select_name($r['gid']);
		}
		return $return;
	}

	public function get_form_fields_list($content, $params=array(), $filter_object_ids = null, $order_by=array(), $lang_id=''){
		$fields = $this->get_fields_list($params, $filter_object_ids, $order_by, true, $lang_id);

		foreach($fields as $key=>$field){
			$fields[$key] = $this->fields->$field["field_type"]->format_form_fields($field, $content[$field["field_name"]]);
		}

		return $fields;
	}

	public function get_fields_names_for_search($form_data){
		$fields_names_for_search = array();
		if($form_data['field_data']){
			foreach($form_data['field_data'] as $key => $item){
				if($item['type'] == 'section'){
					foreach($item['section']['fields'] as $skey => $sitem){
						$field_for_search = $this->fields->$field_type->get_field_name_for_search($sitem);
						if(is_array($field_for_search)){
							foreach($field_for_search as $field_name){
								$fields_names_for_search[] = $field_name;
							}
						}else{
							$fields_names_for_search[] = $this->get_field_select_name($field_for_search);
						}
					}
				}else{
					$field_type = $item['field']['type'];
					$field_for_search = $this->fields->$field_type->get_field_name_for_search($item);
					if(is_array($field_for_search)){
						foreach($field_for_search as $field_name){
							$fields_names_for_search[] = $this->get_field_select_name($field_name);
						}
					}else{
						$fields_names_for_search[] = $this->get_field_select_name($field_for_search);
					}
				}
			}
		}
		return $fields_names_for_search;
	}

	public function format_item_fields_for_view($params, $data, $lang_id=''){
		$temp = $this->format_list_fields_for_view($params, array(0=>$data), $lang_id);
		if(count($temp)) {
			return $temp[0];
		} else {
			return null;
		}
	}

	public function format_list_fields_for_view($params, $data, $lang_id=''){
		$return = array();
		$fields = $this->get_fields_list($params, null, array(), true, $lang_id);
		if(empty($fields)) return $return;

		foreach($data as $key => $item){
			foreach($fields as $field){
				if(empty($item[$field["field_name"]])) {
					continue;
				}
				$temp = array(
					"name" => $field["name"],
					"field_type" => $field["field_type"],
					'section_gid' => $field['section_gid'],
				);
				$value = $item[$field["field_name"]];
				$return[$key][$field["gid"]] = $this->fields->$field["field_type"]->format_view_fields($field, $temp, $value);
			}
		}
		return $return;
	}

	// search
	public function validate_fields_for_select($params = array(), $data = array()){
		return $this->_validate_fields($params, $data, 'select');
	}

	// save user profile
	public function validate_fields_for_save($params = array(), $data = array()){
		return $this->_validate_fields($params, $data, 'save');
	}

	// save search criteria
	public function validate_fields_for_select_save($params = array(), $data = array()){
		return $this->_validate_fields($params, $data, 'select_save');
	}

	private function _validate_fields($params = array(), $data = array(), $for = 'select'){
		$return = array("errors"=> array(), "data" => array());
		$fields = $this->get_fields_list($params);
		switch($for){
			case 'select_save': $validate_method = 'validate_field_for_save'; break;
			case 'select':
			case 'save':
			default: $validate_method = 'validate_field'; break;
		}

		foreach($fields as $field){
			if(isset($data[$field["field_name"]])){
				$temp = $this->fields->$field["field_type"]->$validate_method($field, $data[$field["field_name"]]);
				if(!is_null($temp['data'])){
					$return["data"][$field["field_name"]] = $temp["data"];
					if(!empty($temp["errors"])){
						$return["errors"] = array_merge($return["errors"], $temp["errors"]);
					}
				}
			}elseif($for != 'save'){
				if(isset($data[$field["field_name"].'_min'])){
					$temp = $this->fields->$field["field_type"]->$validate_method($field, $data[$field["field_name"].'_min']);
					if(!is_null($temp['data'])){
						$return["data"][$field["field_name"].'_min'] = $temp["data"];
						if(!empty($temp["errors"])){
							$return["errors"] = array_merge($return["errors"], $temp["errors"]);
						}
					}
				}
				if(isset($data[$field["field_name"].'_max'])){
					$temp = $this->fields->$field["field_type"]->$validate_method($field, $data[$field["field_name"].'_max']);
					if(!is_null($temp['data'])){
						$return["data"][$field["field_name"].'_max'] = $temp["data"];
						if(!empty($temp["errors"])){
							$return["errors"] = array_merge($return["errors"], $temp["errors"]);
						}
					}
				}
			}
		}

		return $return;
	}

	/**
	 * Updates langs data
	 *
	 * @param array $fields_data
	 * @param array $langs_data
	 * @return boolean
	 */
	public function update_sections_langs($sections_data, $langs_data) {

		foreach($sections_data as $section){
			if($section["data"]["editor_type_gid"] != $this->settings['gid']) continue;
			$s = $this->get_section_by_gid($section["data"]["gid"]);
			if(!$s) continue;
			$lang_data = $langs_data[$section["data"]["gid"]];
			$this->CI->pg_language->pages->set_string_langs('field_editor_sections', 'section_'.$s['id'], $lang_data, array_keys($lang_data));
		}
		return true;
	}

	public function import_type_structure($editor_type_gid, $sections_data, $fields_data, $forms_data){
		$this->initialize($editor_type_gid);

		foreach((array)$sections_data as $section){
			if($section["data"]["editor_type_gid"] != $editor_type_gid) continue;
			$this->CI->Field_editor_model->save_section(null, $section["data"]);
		}

		foreach((array)$fields_data as $field){
			if($field["data"]["editor_type_gid"] != $editor_type_gid) continue;
			unset($field["data"]['options']);
			$this->CI->Field_editor_model->save_field(NULL, $editor_type_gid, '', $field["data"]);
		}

		$this->CI->load->model('field_editor/models/Field_editor_forms_model');
		foreach((array)$forms_data as $form){
			if($form["data"]["editor_type_gid"] != $editor_type_gid) continue;
			$this->CI->Field_editor_forms_model->save_form(NULL, $form["data"]);
		}
		return;
	}

	public function export_type_structure($editor_type_gid, $file_path=''){
		$content = "<?php\n\n";
		$this->initialize($editor_type_gid);

		// sections
		$sections = $this->get_section_list();

		$content .= '$fe_sections = array('."\n";
		foreach($sections as $section){
			$fe_sections[] = array(
				'data' => array(
					'gid' => $section['gid'],
					'editor_type_gid' => $section['editor_type_gid'],
					'sorter' => $section['sorter'],
				),
			);
			$content .= "\t".'array("data" => array( "gid" => "'.$section['gid'].'", "editor_type_gid" => "'.$section['editor_type_gid'].'")),'."\n";
		}
		$content .= ');'."\n\n";

		// fields
		$params["where"]['editor_type_gid'] = $editor_type_gid;
		$params["where_in"]['section_gid'] = array_keys($sections);
		$fields = $this->get_fields_list($params);

		$content .= '$fe_fields = array('."\n";
		foreach($fields as $field){

			$options = (!empty($field['options']['option'])) ? array_keys($field['options']['option']) : '';
			$fe_fields[] = array(
				'data' => array(
					'gid' => $field['gid'],
					'section_gid' => $field['section_gid'],
					'editor_type_gid' => $field['editor_type_gid'],
					'field_type' => $field['field_type'],
					'fts' => $field['fts'],
					'settings_data' => $field['settings_data'],
					'sorter' => $field['sorter'],
					'options' => $options,
				),
			);

			$options_str = '""';
			if(!empty($options)){
				$options_str = "array('".implode("', '", $options)."')";
			}
			$content .= "\t".'array("data" => array( "gid" => "'.$field['gid'].'", "section_gid" => "'.$field['section_gid'].'", "editor_type_gid" => "'.$field['editor_type_gid'].'", "field_type" => "'.$field['field_type'].'", "fts" => "'.$field['fts'].'", "settings_data" => \''.$field['settings_data'].'\', "sorter" => "'.$field['sorter'].'", "options" => '.$options_str.')),'."\n";
		}
		$content .= ');'."\n\n";

		// forms
		$this->CI->load->model('field_editor/models/Field_editor_forms_model');
		$fparams["where"]["editor_type_gid"] = $editor_type_gid;
		$forms = $this->CI->Field_editor_forms_model->get_forms_list($fparams);

		$content .= '$fe_forms = array('."\n";
		foreach($forms as $form){
			$fe_forms[] = array(
				'data' => array(
					'gid' => $form['gid'],
					'editor_type_gid' => $form['editor_type_gid'],
					'name' => htmlspecialchars($form['name']),
					'field_data' => $form['field_data'],
				),
			);

			$content .= "\t".'array("data" => array( "gid" => "'.$form['gid'].'", "editor_type_gid" => "'.$form['editor_type_gid'].'", "name" => "'.htmlspecialchars($form['name']).'", "field_data" => \''.$form['field_data'].'\')),'."\n";
		}
		$content .= ');'."\n\n";

		if($file_path && isset($this->CI->zip) && !in_array($file_path, $this->CI->zip->data_arr)){
			$this->CI->zip->add_data($file_path, $content);
		}

		return array($fe_sections, $fe_fields, $fe_forms);
	}

	public function export_sections_langs($sections_data, $langs_ids = null) {

		$strings = array();
		foreach($sections_data as $section){
			$s = $this->get_section_by_gid($section["data"]["gid"]);
			$strings['section_'.$s['id']] = $section["data"]["gid"];
		}

		$langs_db = $this->CI->pg_language->export_langs('field_editor_sections', array_keys($strings), $langs_ids);
		$lang_codes = array_keys($langs_db);
		foreach($lang_codes as $lang_code) {
			$lang_data[$strings[$lang_code]] = $langs_db[$lang_code];
		}
		return $lang_data;
	}

	public function update_fields_langs($editor_type_gid, $fields_data, $langs_data) {
		$field_gids = array();
		$this->initialize($editor_type_gid);
		$module_gid = $this->settings['module'];

		foreach($fields_data as $field){
			if($field['data']['editor_type_gid'] != $editor_type_gid) continue;
			$field_gids[] = $field['data']['gid'];
			$field_options[$field['data']['gid']] = $field['data']['options'];
		}

		$params["where_in"]['gid'] = $field_gids;
		$fields = $this->get_fields_list($params);
		foreach($fields as $field){
			$index = 'fields_'.$module_gid.'_'.$field['section_gid'].'_'.$field['gid'];
			$this->fields->$field["field_type"]->update_field_name($field, $langs_data[$index]);
			$options = $field_options[$field['gid']];
			if(!empty($options)){
				$value = array();
				foreach($options as $option_gid){
					$value[$option_gid] = $langs_data[$index.'_'.$option_gid];
				}
				$this->fields->$field["field_type"]->set_field_option($field, $options, $value);
			}
		}
		return true;
	}

	public function export_fields_langs($editor_type_gid, $fields_data, $langs_ids = null) {
		if(!$langs_ids) $langs_ids = array_keys($this->CI->pg_language->languages);
		$field_gids = array();
		$this->initialize($editor_type_gid);
		$module_gid = $this->settings['module'];

		foreach($fields_data as $field){
			$field_gids[] = $field['data']['gid'];
		}

		$params["where_in"]['gid'] = $field_gids;
		$fields = $this->get_fields_list($params, null, array(), false);

		foreach($fields as $field){
			$index = 'fields_'.$module_gid.'_'.$field['section_gid'].'_'.$field['gid'];
			foreach($langs_ids as $lid){
				$fdata = $this->format_field($field, $lid);

				$lang_data[$index][$lid] = $fdata["name"];
				if(!empty($fdata['options']['option'])){
					foreach($fdata['options']['option'] as $ogid => $ovalue){
						$lang_data[$index.'_'.$ogid][$lid] = $ovalue;
					}
				}
			}
		}
		return $lang_data;
	}

	/*
	* update fulltext indexed field in base
	* callback have to return array in format:
	*	main_fields => array (field=>text_value, field=>text_value)
	*	fe_fields => array (field=>value_for_format, field=>value_for_format)
	*	default_lang_id => int
	*	object_lang_id => int
	*/
	public function update_fulltext_field($id){
		$sections = $this->get_section_list();
		$fields_for_select = empty($sections) ? array() : $this->get_fields_for_select(array_keys($sections));

		$model_name = ucfirst($this->settings["fulltext_model"]);
		$model_path = strtolower($this->settings["module"]."/models/").$model_name;
		$this->CI->load->model($model_path);
		$callback = $this->settings["fulltext_callback"];
		$model_data = $this->CI->$model_name->$callback($id, $fields_for_select);
		if(empty($model_data["fe_fields"])){
			$model_data["fe_fields"] = array();
		}
		$content = '';
		if(!empty($model_data["main_fields"])){
			array_map('trim', $model_data["main_fields"]);
			foreach($model_data["main_fields"] as $key => $main_field){
				if(empty($main_field)){
					unset($model_data["main_fields"][$key]);
				}
			}
			$content = trim(implode('; ', $model_data["main_fields"]));
		}
		$params = array();
		if(!empty($sections)){
			$params["where_in"]["section_gid"] = array_keys($sections);
		}
		$object_lang_data = $this->format_item_fields_for_fulltext($params, $model_data['fe_fields'], $model_data['object_lang_id']);
		$default_lang_data = $this->format_item_fields_for_fulltext($params, $model_data['fe_fields'], $model_data['default_lang_id']);
		if($object_lang_data && $content){
			$content .= ';';
		}
		foreach($object_lang_data as $field_gid => $value){
			$content .= $value ? ' '.$value : '';
			if($value != $default_lang_data[$field_gid]) $content .= $default_lang_data[$field_gid] ? ' '.$default_lang_data[$field_gid] : '';
		}
		$this->base_update_fulltext_field($id, $content);
		return;
	}

	public function format_item_fields_for_fulltext($params, $data, $lang_id=''){
		$temp = $this->format_list_fields_for_fulltext($params, array(0=>$data), $lang_id);
		return $temp[0];
	}

	public function format_list_fields_for_fulltext($params, $data, $lang_id=''){
		$return = array();
		$fields = $this->get_fields_list($params, null, array(), true, $lang_id);
		if(empty($fields)) return $return;

		foreach($data as $key => $item){
			foreach($fields as $field){
				$temp = array(
					"name" => $field["name"],
					"field_type" => $field["field_type"],
				);
				$value = $item[$field["field_name"]];
				$return[$key][$field["gid"]] = $this->fields->$field["field_type"]->format_fulltext_fields($field, $temp, $value);
			}
		}
		return $return;
	}

	public function return_fulltext_criteria($text, $mode=null){	
		$fields = $this->settings['fulltext_field'];		
		$word_count = str_word_count($text);
		$arr_text = explode(" ", $text);
		$word_count	= count($arr_text);	
		$text = ($word_count < 2 ? $text."*" : $text);
		$escape_text = $this->DB->escape($text);
		$mode = ($mode && $word_count < 2 ? " IN ".$mode : "");		
		foreach($fields as $table => $field){
			$return[$table] = array(
				'field' => "MATCH (search_field) AGAINST (".$escape_text.") AS fields",
				'where_sql' => "MATCH (search_field) AGAINST (".$escape_text.$mode.")"
			);
		}
		return $return;
	}

	public function get_field_index(){
		return intval($this->CI->pg_module->get_module_config('field_editor', 'field_counter'));
	}

	public function inc_field_index(){
		$index = $this->get_field_index();
		$index++;
		$this->CI->pg_module->set_module_config('field_editor', 'field_counter', $index);
	}

	public function get_field_gid(){
		$index = $this->get_field_index();
		return "field".$index;
	}
}

