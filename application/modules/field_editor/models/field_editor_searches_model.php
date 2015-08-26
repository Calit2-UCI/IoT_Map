<?php
/**
* Field Editor Searches Model
* 
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

if (!defined('FIELD_EDITOR_SAVED_SEARCHES')) define('FIELD_EDITOR_SAVED_SEARCHES', DB_PREFIX.'field_editor_saved_searches');

class Field_editor_searches_model extends Model{

	private $CI;
	private $DB;
	
	function __construct(){
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;
	}

	public function get_search_by_id($id){
		$result = $this->DB->select("id, id_user, form_gid, editor_type_gid, name, criteria, date_add")->from(FIELD_EDITOR_SAVED_SEARCHES)->where("id", $id)->get()->result_array();
		$return = (!empty($result))?$result[0]:array();
		if(!empty($return["criteria"])){
			$return["criteria"] = unserialize($return["criteria"]);
		}
		return $return;		
	}
	
	public function get_searches($params=array(), $order_by=array()){
		$result = $this->DB->select("id, id_user, form_gid, editor_type_gid, name, criteria, date_add")->from(FIELD_EDITOR_SAVED_SEARCHES);

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
			return $results;
		}
		return array();
		
	}
	
	public function get_searches_count($params=array()){
		$result = $this->DB->select("COUNT(*) AS cnt")->from(FIELD_EDITOR_SAVED_SEARCHES);

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
	
	public function save_search($id, $data){
		if (empty($id)){
			$data["date_add"] = date('Y-m-d H:i:s');
			$this->DB->insert(FIELD_EDITOR_SAVED_SEARCHES, $data);
			$id = $this->DB->insert_id();
		}else{
			$this->DB->where('id', $id);
			$this->DB->update(FIELD_EDITOR_SAVED_SEARCHES, $data);
		}
		return $id;
	}
	
	public function delete_search($id){
		$this->DB->where('id', $id);
		$this->DB->delete(FIELD_EDITOR_SAVED_SEARCHES);
		return;
	}
	
	public function validate_search($id, $data){
		$return = array("errors"=> array(), "data" => array());
		
		if(isset($data["editor_type_gid"])){
			$return["data"]["editor_type_gid"] = strval($data["editor_type_gid"]);
		}
		if(isset($data["form_gid"])){
			$return["data"]["form_gid"] = strval($data["form_gid"]);
		}
		if(isset($data["id_user"])){
			$return["data"]["id_user"] = intval($data["id_user"]);
		}
		if(isset($data["name"])){
			$return["data"]["name"] = trim(strip_tags($data["name"]));
			if(empty($return["data"]["name"])){
				$return["errors"][] = l('error_empty_search_name', 'field_editor');
			}
		}
		if(isset($data["criteria"])){
			$return["data"]["criteria"] = $data["criteria"];
			if(empty($return["data"]["criteria"])){
				$return["errors"][] = l('error_empty_search_criteria', 'field_editor');
			}
			$return["data"]["criteria"] = serialize($return["data"]["criteria"]);
		}
		return $return;
	}
}