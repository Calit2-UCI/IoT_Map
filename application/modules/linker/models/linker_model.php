<?php
/**
* Linker Model
* 
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/



/*
*
*    обязательно нужен модуль linker/models/Linker_type_model
*
*/

class Linker_model extends Model
{

	/**
	 * link to CodeIgniter object
	 * @var object
	 */
	private $CI;

	/**
	 * link to DataBase object
	 * @var object
	 */
	private $DB;

	/**
	 * Cache array for used types
	 * @var array
	 */
	private $linker_types;

	/**
	 * Constructor
	 *
	 * return Linker object
	 * required Linker_type_model
	 */
	function __construct()
	{
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;
		$this->CI->load->model('linker/models/Linker_type_model', 'linker_type');
	}

	/**
	 * Get link type by GID
	 * @param string $type_gid
	 * @return array
	 */
	public function get_linker_type_by_gid($type_gid){

		if(!isset($this->linker_types[$type_gid])){
			$type_data = $this->CI->linker_type->get_type_by_gid($type_gid);
			if(!is_array($type_data) || !count($type_data)) return false;
			$this->linker_types[$type_data["id"]] = $type_data;
			$this->linker_types[$type_gid] = $type_data;

			////// each time, when we are ask a type properties, 
			////// we check its lifetime for the links and clean the base, if it is different from 0
			////// make this only one time, when inquire type's data
			$lifetime = intval($this->linker_types[$type_gid]["lifetime"]);
			if($lifetime > 0){
				$params["where"]["date_add <"] = date("Y-m-d H:i:s", time()-$lifetime);
				$this->delete_links($type_gid, $params);
			}
		}

		if(is_array($this->linker_types[$type_gid]) && count($this->linker_types[$type_gid])){

			return $this->linker_types[$type_gid];
		}else{
			return false;
		}
	}


	/**
	 * Add link
	 * @param string $type_gid
	 * @param integer $link_1
	 * @param integer $link_2
	 */
	public function add_link($type_gid, $link_1, $link_2){
		$link_1 = intval($link_1);
		$link_2 = intval($link_2);
		if(!$link_1 || !$link_2){
			return false;
		}
		$type = $this->get_linker_type_by_gid($type_gid);
		if(!$type){
			return false;
		}

		if($type["unique_type"] != 'no'){
			//// need to get links count, if links are unique
			$link_count = $this->get_links_simple_count($type_gid, $link_1, $link_2);
		}

		if($type["unique_type"] == 'no' || $link_count == 0){
			$data = array(
				'id_link_1' => $link_1,
				'id_link_2' => $link_2,
				'date_add' => date("Y-m-d H:i:s"),
			);
			if(!$type["separated"]){
				$data["id_type"] = $type["id"];
			}

			$this->DB->insert($type["table_name"], $data);

		}elseif($type["unique_type"] == 'update'){
			if(!$type["separated"]){
				$this->DB->where('id_type', $type["id"]);
			}
			$this->DB->where('id_link_1', $link_1);
			$this->DB->where('id_link_2', $link_2);
			$data = array(
				'date_add' => date("Y-m-d H:i:s"),
			);
			$this->DB->update($type["table_name"], $data);
		}
		return;
	}

	/**
	 * Add links
	 * @param string $type_gid
	 * @param array $links_arr array(key=>array($link1, $link2))
	 */ 
	public function add_links($type_gid, $links_arr){
		if(!is_array($links_arr) || !count($links_arr)){
			return false;
		}

		foreach($links_arr as $link_pair){
			$link_1 = intval($link_pair[0]);
			$link_2 = intval($link_pair[1]);
			if(!$link_1 || !$link_2){
				continue;
			}
			$this->add_link($type_gid, $link_1, $link_2);
		}
		return;
	}

	/**
	 * Delete link by LinkID
	 * @param string $type_gid
	 * @param intval $id link ID
	 */ 
	public function delete_link_by_id($type_gid, $id){
		$id = intval($id);
		$type = $this->get_linker_type_by_gid($type_gid);
		if(!$type || !$id){
			return false;
		}
		$this->DB->where('id', $id);
		$this->DB->delete($type["table_name"]);
	}

	/**
	 * Delete links by params
	 * @param string $type_gid
	 * @param array $params array( "where" => array( "field( <>>=<=)" => value ), "where_in" => array( "field" => value(array) ));
	 */ 
	public function delete_links($type_gid, $params){
		$type = $this->get_linker_type_by_gid($type_gid);
		if(!$type){
			return false;
		}
		if(!$type["separated"]){
			$this->DB->where('id_type', $type["id"]);
		}
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
		$this->DB->delete($type["table_name"]);
		return;
	}
	
	/**
	 * Get link by Link ID
	 * @param string $type_gid
	 * @param intval $id link ID
	 */ 
	public function get_link_by_id($type_gid, $id){
		$id = intval($id);
		$type = $this->get_linker_type_by_gid($type_gid);
		if(!$type || !$id){
			return false;
		}
		$this->DB->select('id, id_link_1, id_link_2, date_add, sorter');
		$this->DB->from($type["table_name"]);
		$this->DB->where("id", $id);
		//_compile_select;
		$result = $this->DB->get()->result();
		if(!empty($result)){
			$rt = get_object_vars($result[0]);
			return $rt;
		}else
			return false;
	}

	/**
	 * Get links by params
	 * @param string $type_gid
	 * @param array $params array( "where" => array( "field( <>>=<=)" => value ), "where_in" => array( "field" => value(array) ));
	 * @param integer/null $page
	 * @param integer/null $max_count
	 * @param string $order_by
	 */ 
	public function get_links($type_gid, $params, $page=1, $max_count=20, $order_by="date_add DESC"){
		$type = $this->get_linker_type_by_gid($type_gid);
		if(!$type){
			return false;
		}
		$this->DB->select('id, id_link_1, id_link_2, date_add, sorter');
		$this->DB->from($type["table_name"]);
		if(!$type["separated"]){
			$this->DB->where("id_type", $type["id"]);
		}
		if(is_array($params) && isset($params["where"]) && is_array($params["where"]) && count($params["where"])){
			foreach($params["where"] as $field=>$value){
				$this->DB->where($field, $value);
			}
		}
		if(is_array($params) && isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])){
			foreach($params["where_in"] as $field=>$value){
				$this->DB->where_in($field, $value);
			}
		}
		$this->DB->order_by($order_by);
		if(!is_null($page) ){
			$page = intval($page)?intval($page):1;
			$this->DB->limit($max_count, $max_count*($page-1));
		}
		$result = $this->DB->get()->result();
		if(!empty($result)){
			foreach($result as $res_obj){
				$rt[] = get_object_vars($res_obj);
			}
			return $rt;
		}else
			return false;
	}

	/**
	 * Get links directly on them id
	 * @param string $type_gid
	 * @param integer $link_1
	 * @param integer $link_2
	 * @param boolean $distinct
	 * @param integer/null $page
	 * @param integer/null $max_count
	 * @param string $order_by
	 */ 
	public function get_links_simple($type_gid, $link_1=0, $link_2=0, $distinct=false, $page=null, $max_count=20, $order_by="date_add DESC"){
		$type = $this->get_linker_type_by_gid($type_gid);
		if(!$type){
			return false;
		}
		if($distinct){
			$this->DB->select('id_link_1, id_link_2');
			$this->DB->distinct();
		}else{
			$this->DB->select('id, id_link_1, id_link_2, date_add, sorter');
		}
		$this->DB->from($type["table_name"]);
		if(!$type["separated"]){
			$this->DB->where("id_type", $type["id"]);
		}
		if($link_1){
			$this->DB->where("id_link_1", $link_1);
		}
		if($link_2){
			$this->DB->where("id_link_2", $link_2);
		}
		$this->DB->order_by($order_by);
		if(!is_null($page) ){
			$page = intval($page)?intval($page):1;
			$this->DB->limit($max_count, $max_count*($page-1));
		}
		$result = $this->DB->get()->result();
		if(!empty($result)){
			foreach($result as $res_obj){
				$rt[] = get_object_vars($res_obj);
			}
			return $rt;
		}else
			return false;
	}

	/**
	 * Get links count 
	 * @param string $type_gid
	 * @param integer $link_1
	 * @param integer $link_2
	 */ 
	public function get_links_simple_count($type_gid, $link_1=0, $link_2=0){
		$type = $this->get_linker_type_by_gid($type_gid);
		if(!$type){
			return false;
		}
		$this->DB->select('COUNT(id) AS cnt');
		$this->DB->from($type["table_name"]);
		if(!$type["separated"]){
			$this->DB->where("id_type", $type["id"]);
		}
		if($link_1){
			$this->DB->where("id_link_1", $link_1);
		}
		if($link_2){
			$this->DB->where("id_link_2", $link_2);
		}
		$result = $this->DB->get()->result();
		if(!empty($result)){
			return intval($result[0]->cnt);
		}else
			return false;
	}


	/**
	 * Get links count 
	 * @param string $type_gid
	 * @param integer $link_1
	 * @param integer $link_2
	 */ 
	public function get_links_simple_count_group($type_gid, $link_ids=array(), $link_type=1){
		$type = $this->get_linker_type_by_gid($type_gid);
		if(!$type){
			return false;
		}
		if($link_type == 1){
			$field_name = "id_link_1";
		}else{
			$field_name = "id_link_2";
		}
		$this->DB->select($field_name.', COUNT(*) AS cnt');
		$this->DB->from($type["table_name"]);
		if(!$type["separated"]){
			$this->DB->where("id_type", $type["id"]);
		}
		$this->DB->where_in($field_name, $link_ids);
		$this->DB->group_by($field_name);

		$result = $this->DB->get()->result();
		if(!empty($result)){
			foreach($result as $r){
				$return[$r->$field_name] = $r->cnt;
			}
			return $return;
		}else
			return false;
	}

	/**
	 * Get links count 
	 * @param string $type_gid
	 * @param array $links_1
	 * @param array $links_2
	 */ 
	public function get_links_simple_arr_count($type_gid, $links_1=array(), $links_2=array()){
		$type = $this->get_linker_type_by_gid($type_gid);
		if(!$type){
			return false;
		}
		$this->DB->select('id, id_link_1, id_link_2');
		$this->DB->from($type["table_name"]);
		if(!$type["separated"]){
			$this->DB->where("id_type", $type["id"]);
		}
		if(is_array($links_1) && count($links_1)>0){
			$this->DB->where_in("id_link_1", $links_1);
		}
		if(is_array($links_2) && count($links_2)>0){
			$this->DB->where_in("id_link_2", $links_2);
		}
		$result = $this->DB->get()->result();
		if(!empty($result)){
			foreach($result as $r){
				$return[$r->id_link_1][$r->id_link_2] = $r->id;
			}
			return $return;
		}else
			return false;
	}

	/**
	 * Refresh link sorter
	 * @param string $type_gid
	 * @param integer $link_1
	 * @param integer $link_2
	 */ 
	public function refresh_sorter($type_gid, $link_1=0, $link_2=0){
		$type = $this->get_linker_type_by_gid($type_gid);
		if(!$type){
			return false;
		}

		$this->DB->select('id');
		$this->DB->from($type["table_name"]);
		if(!$type["separated"]) $this->DB->where("id_type", $type["id"]);
		if($link_1) $this->DB->where("id_link_1", $link_1);
		if($link_2) $this->DB->where("id_link_2", $link_2);
		$this->DB->order_by("sorter, date_add DESC");
		$result = $this->DB->get()->result();
		if(!empty($result)){
			$i=1;
			foreach($result as $res_obj){
				$this->db->where('id', $res_obj->id);
				$this->db->update($type["table_name"], array("sorter"=>$i));
				$i++;
			}
		}
		return;
	}

	/**
	 * Update link sorter
	 * @param string $type_gid
	 * @param integer $sorter_new
	 * @param integer $link_1
	 * @param integer $link_2
	 */ 
	public function update_sorter($type_gid, $id, $sorter_new, $link_1=0, $link_2=0){
		$type = $this->get_linker_type_by_gid($type_gid);
		if(!$type){
			return false;
		}
		$this->DB->select('id, sorter')->from($type["table_name"])->where("id", $id);
		$result = $this->DB->get()->result();
		if(empty($result)) return false;
		$sorter_old = intval($result[0]->sorter);

		if($sorter_new-$sorter_old == 0) return false;

		$this->DB->select('id, sorter');
		$this->DB->from($type["table_name"]);
		if(!$type["separated"]) $this->DB->where("id_type", $type["id"]);
		if($link_1) $this->DB->where("id_link_1", $link_1);
		if($link_2) $this->DB->where("id_link_2", $link_2);
		if($sorter_new-$sorter_old > 0){
			$this->DB->where("sorter >=", $sorter_old);
			$this->DB->where("sorter <=", $sorter_new);
			$dir = -1;
		}else{
			$this->DB->where("sorter >=", $sorter_new);
			$this->DB->where("sorter <=", $sorter_old);
			$dir = 1;
		}
		$this->DB->where("id <>", $id);
		$this->DB->order_by("sorter, date_add DESC");
		$result = $this->DB->get()->result();
		if(!empty($result)){
			foreach($result as $res_obj){
				$this->db->where('id', $res_obj->id);
				$this->db->update($type["table_name"], array("sorter"=>$res_obj->sorter+$dir));
			}
		}
		$this->db->where('id', $id);
		$this->db->update($type["table_name"], array("sorter"=>$sorter_new));
	}
}