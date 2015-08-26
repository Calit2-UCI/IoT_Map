<?php
/**
* Banners place model
* 
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

if (!defined('TABLE_BANNERS_PLACES')) define('TABLE_BANNERS_PLACES', DB_PREFIX.'banners_places');
if (!defined('TABLE_BANNERS_PLACE_GROUP')) define('TABLE_BANNERS_PLACE_GROUP', DB_PREFIX.'banners_place_group');
if (!defined('TABLE_BANNERS_BANNER_GROUP')) define('TABLE_BANNERS_BANNER_GROUP', DB_PREFIX.'banners_banner_group');

/*
*
*	Для области ставим в соответствие группы 
*	Нет необходимости делить вывод областей на страницы , поэтому параметры поиска(list_per_page, page, $search_param..) не используем
*
*/

class Banner_place_model extends Model {
	
	private $CI;
	private $DB;

	private $fields = array(
		'id',
		'date_created',
		'date_modified',
		'keyword',
		'name',
		'places_in_rotation',
		'rotate_time',
		'width',
		'height',
		'access'
	);
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;
		$this->DB->memcache_tables(array(TABLE_BANNERS_PLACES));
	}
	
	/**
	 * Get all banner place objects
	 *
	 * @return array
	 */
	public function get_all_places($access=false){
		$objects = array();
		
		$this->DB->select(implode(", ", $this->fields))->from(TABLE_BANNERS_PLACES)->order_by("date_created ASC");
		
		if($access !== false){
			$this->DB->where('access <=', intval($access));	
		}

		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			foreach($results as $result){
				$objects[] = $this->format_place($result);
			}
		}
		return $objects;
	}
	
	public function format_place($data){
		return $data;
	}

	public function validate_place($id, $data){
		$return = array("errors"=> array(), "data" => array());

		if(isset($data["name"])){
			$return["data"]["name"] = trim(strip_tags($data["name"]));
			if(empty($return["data"]["name"])){
				$return["errors"][] = l('place_edit_error_name_empty', 'banners');
			}
		}

		if(isset($data["keyword"])){
			$return["data"]["keyword"] = preg_replace("/[^a-z\-_0-9]+/i", '', trim(strip_tags($data["keyword"])));

			if(empty($return["data"]["keyword"])){
				$return["errors"][] = l('place_edit_error_keyword_empty', 'banners');
			}else{
				$this->DB->select("COUNT(*) AS cnt")->from(TABLE_BANNERS_PLACES)->where("keyword", $return["data"]["keyword"]);
				if(!empty($id)) $this->DB->where("id <>", $id);
				$results = $this->DB->get()->result();
				if(!empty($results) && is_array($results) && $results[0]->cnt > 0){
					$return["errors"][] = l('place_edit_error_keyword_exists', 'banners');
				}
			}
		}

		if(isset($data["width"])){
			$return["data"]["width"] = intval($data["width"]);
			if(empty($return["data"]["width"])){
				$return["errors"][] = l('place_edit_error_width_empty', 'banners');
			}
		}

		if(isset($data["height"])){
			$return["data"]["height"] = intval($data["height"]);
			if(empty($return["data"]["height"])){
				$return["errors"][] = l('place_edit_error_height_empty', 'banners');
			}
		}

		if(isset($data["rotate_time"])){
			$return["data"]["rotate_time"] = intval($data["rotate_time"]);
		//	if(empty($return["data"]["rotate_time"])){
		//		$return["errors"][] = l('place_edit_error_rotate_time_empty', 'banners');
		//	}
		}

		if(isset($data["places_in_rotation"])){
			$return["data"]["places_in_rotation"] = intval($data["places_in_rotation"]);
			if(empty($return["data"]["places_in_rotation"])){
				$return["errors"][] = l('place_edit_error_places_in_rotation_empty', 'banners');
			}
			if($return["data"]["rotate_time"] == 0) $return["data"]["places_in_rotation"] = 1;
		}

		if(isset($data["place_groups"]) && is_array($data["place_groups"])){
			$return["data"]["place_groups"] = $data["place_groups"];
		}
		
		if(isset($data["access"])){
			$return["data"]["access"] = intval($data["access"]);
		}

		return $return;
	}

	/**
	 * Save banner place object
	 *
	 * @param int $id
	 * @param array $data
	 * @return int
	 */
	public function save_place($id, $data){
		///// categories
		if(isset($data["place_groups"]) && !empty($data["place_groups"])){
			$saved_place_groups = $data["place_groups"];
			unset($data["place_groups"]);
		}

		////save
		if(!empty($id)){
			$data["date_modified"] = date('Y-m-d H:i:s');
			$this->DB->where('id', $id);
			$this->DB->update(TABLE_BANNERS_PLACES, $data);
		}else{
			$data["date_created"] = date('Y-m-d H:i:s');
			$data["date_modified"] = date('Y-m-d H:i:s');
			$this->DB->insert(TABLE_BANNERS_PLACES, $data);
			$id = $this->DB->insert_id();
		}

		///// update categories
		if(isset($saved_place_groups) && is_array($saved_place_groups) && count($saved_place_groups)>0){
			$this->DB->where('place_id', $id);
			$this->DB->delete(TABLE_BANNERS_PLACE_GROUP);
			foreach($saved_place_groups as $group_id){
				$this->DB->insert(TABLE_BANNERS_PLACE_GROUP, array('place_id'=>$id, 'group_id'=>$group_id));
			}
		}

		return $id;
	}
	
	public function save_place_group($place_id, $group_id){
		if ($place_id && $group_id){
			$this->DB->insert(TABLE_BANNERS_PLACE_GROUP, array('place_id'=>$place_id, 'group_id'=>$group_id));
		}
	}
	
	/**
	 * Delete banner_place object
	 *
	 * @param int $id
	 * @return void
	 */
	public function delete($id = null){

		if ($id){
			$this->DB->where('id', $id);
			$this->DB->delete(TABLE_BANNERS_PLACES);

			$this->DB->where('place_id', $id);
			$this->DB->delete(TABLE_BANNERS_PLACE_GROUP);

			$this->DB->where('place_id', $id);
			$this->DB->delete(TABLE_BANNERS_BANNER_GROUP);
		}
		
		return;
	}
	
	/**
	 * Get banner place object by id
	 *
	 * @param int $id
	 * @return array
	 */
	public function get($id){
		$id = (is_numeric($id) and $id > 0) ? intval($id) : 0;
		$object = false;
		
		if ($id){
			$this->DB->select(implode(", ", $this->fields))->from(TABLE_BANNERS_PLACES)->where('id', $id);
			$results = $this->DB->get()->result();
			if(!empty($results) && is_array($results)){
				$object = get_object_vars($results[0]);
				$this->format_place($object);
//				$object["place_groups"] = $this->get_place_group_ids($id);
			}
		}
		return $object;
	}

	public function get_by_id($id){
		return $this->get($id);
	}

	public function get_by_keyword($keyword){
		$object = false;

		$this->DB->select(implode(", ", $this->fields))->from(TABLE_BANNERS_PLACES)->where('keyword', $keyword);
		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			$object = $this->format_place($results[0]);
	//			$object["place_groups"] = $this->get_place_group_ids($object["id"]);
		}

		return $object;
	}

	public function get_place_group_ids($place_id){
		$object = array();
		$this->DB->select("group_id")->from(TABLE_BANNERS_PLACE_GROUP)->where("place_id", $place_id);
		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			foreach($results as $result){
				$object[] = $result["group_id"];
			}
		}
		return $object;
	}

}