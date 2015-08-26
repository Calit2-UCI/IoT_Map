<?php
/**
* Banners groups model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/
if (!defined('TABLE_BANNERS_GROUPS')) define('TABLE_BANNERS_GROUPS', DB_PREFIX.'banners_groups');
if (!defined('TABLE_BANNERS_PAGES')) define('TABLE_BANNERS_PAGES', DB_PREFIX.'banners_pages');
if (!defined('TABLE_BANNERS_MODULES')) define('TABLE_BANNERS_MODULES', DB_PREFIX.'banners_modules');

if (!defined('TABLE_BANNERS_PLACE_GROUP')) define('TABLE_BANNERS_PLACE_GROUP', DB_PREFIX.'banners_place_group');
if (!defined('TABLE_BANNERS_BANNER_GROUP')) define('TABLE_BANNERS_BANNER_GROUP', DB_PREFIX.'banners_banner_group');

class Banner_group_model extends Model {

	private $CI;
	private $DB;

	private $group_fields = array(
		'id',
		'date_created',
		'date_modified',
		'price',
		'gid',
		'name'
	);

	private $group_get_cache = array();
	private $group_search_cache = array();

	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;
		$this->DB->memcache_tables(array(
			TABLE_BANNERS_GROUPS, 
			TABLE_BANNERS_PAGES, 
			TABLE_BANNERS_MODULES, 
			TABLE_BANNERS_PLACE_GROUP
		));
	}

	public function get_all_groups(){
		$this->DB->select(implode(", ", $this->group_fields))->from(TABLE_BANNERS_GROUPS)->order_by("gid ASC");
		$results = $this->DB->get()->result_array();

		if(!empty($results) && is_array($results)){
			$results = $this->format_group($results);
		}
		return $results;
	}

	public function get_all_groups_key_id(){
		$objects = array();

		$this->DB->select(implode(", ", $this->group_fields))->from(TABLE_BANNERS_GROUPS)->order_by("gid ASC");
		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			$results = $this->format_group($results);
		}
		if(!empty($results) && is_array($results)){
			foreach($results as $result){
				$objects[$result["id"]] = $result;
			}
		}
		return $objects;
	}

	public function get_groups($ids){
		$this->DB->select(implode(", ", $this->group_fields))->from(TABLE_BANNERS_GROUPS);
		if(!empty($ids) && count($ids)>0){
			$this->DB->where_in("id", $ids);
		}
		$this->DB->order_by("gid ASC");
		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			$results = $this->format_group($results);
		}
		return $results;
	}

	public function validate_group($id, $data, $langs=null){
		$return = array("errors"=> array(), "data" => array(), 'langs'=>array());

		if(isset($data["name"])){
			$return["data"]["name"] = trim(strip_tags($data["name"]));
			if(empty($return["data"]["name"])){
				$return["errors"][] = l('error_group_name_empty', 'banners');
			} else {
				$gid = strtolower(strip_tags($data["name"]));
				$gid = preg_replace("/[\n\s\t]+/i", '-', $gid);
				$gid = preg_replace("/[^a-z0-9\-_]+/i", '', $gid);
				$gid = preg_replace("/[\-]{2,}/i", '-', $gid);
				$return["data"]["gid"] = $gid . '_groups';
				if($this->get_group_id_by_gid($return["data"]["gid"])) {
					$return["errors"][] = l('error_group_name_exists', 'banners');
				}elseif(!empty($langs)){
					foreach($this->CI->pg_language->languages as $lid => $lang_data){
						if(!isset($langs[$lid])){
							$return['errors'][] = l('error_group_name_empty', "banners");
							break;
						}else{
							$return["langs"][$lid] = trim(strip_tags($langs[$lid]));
							if(empty($return["langs"][$lid])){
								$return['errors'][] = l('error_group_name_empty', "banners");
								break;
							}
						}
					}
				}
			}
		}elseif(!empty($langs)){
			$default_lang_id = $this->CI->pg_language->current_lang_id;
			if(!isset($langs[$default_lang_id])){
				$return['errors'][] = l('error_group_name_empty', "banners");
			}else{
				$return["langs"][$default_lang_id] = trim(strip_tags($langs[$default_lang_id]));
				if(empty($return["langs"][$default_lang_id])){
					$return['errors'][] = l('error_group_name_empty', "banners");
				}else{
					foreach($this->CI->pg_language->languages as $lid => $lang_data){
						if($lid == $default_lang_id) continue;
						if(!isset($langs[$lid])){
							$return["langs"][$lid] = $return["langs"][$default_lang_id];
						}else{
							$return["langs"][$lid] = trim(strip_tags($langs[$lid]));
							if(empty($return["langs"][$lid])){
								$return["langs"][$lid] = $return["langs"][$default_lang_id];
							}	
						}
					}
				}
			}
		}
		
		if(isset($data["gid"])){
			$return["data"]["gid"] = trim(strip_tags($data["gid"]));
			if(empty($return["data"]["gid"])){
				$return["errors"][] = l('error_group_gid_empty', 'banners');
			} else {
				$gid = strtolower(strip_tags($data["gid"]));
				$gid = preg_replace("/[\n\s\t]+/i", '-', $gid);
				$gid = preg_replace("/[^a-z0-9\-_]+/i", '', $gid);
				$gid = preg_replace("/[\-]{2,}/i", '-', $gid);
				$group_id  = $this->get_group_id_by_gid($return["data"]["gid"]);
				if($group_id && $group_id != $id) {
					$return["errors"][] = l('error_group_gid_exists', 'banners');
				}
			}
		}
		
		if(isset($data["price"])){
			$return["data"]["price"] = floatval($data["price"]);
			if(empty($return["data"]["price"])){
				$return["errors"][] = l('error_group_price_empty', 'banners');
			}
		}

		return $return;
	}
	
	public function format_group($data){
		foreach($data as $k => $group){
			if(isset($group["gid"])) $data[$k]["name"] = l('banners_group_'.$group["gid"], 'banners');
		}

		return $data;
	}

	/**
	 * Simple save group object
	 *
	 * @param int $id
	 * @param array $attrs
	 */

	public function save($id, $attrs, $name=array())
	{
		$id = (is_numeric($id) and $id > 0) ? intval($id) : 0;

		//// unset all unused fields
		foreach($attrs as $field => $value){
			if(!in_array($field, $this->group_fields)){
				unset($attrs[$field]);
			}
		}

		////save
		if(!empty($id)){
			$attrs["date_modified"] = date('Y-m-d H:i:s');
			$this->DB->where('id', $id);
			$this->DB->update(TABLE_BANNERS_GROUPS, $attrs);
		}else{
			$attrs["date_created"] = date('Y-m-d H:i:s');
			$attrs["date_modified"] = date('Y-m-d H:i:s');
			$this->DB->insert(TABLE_BANNERS_GROUPS, $attrs);
			$id = $this->DB->insert_id();
		}
		if(!empty($name)){
			$languages = $this->CI->pg_language->languages;
			if(!empty($languages)){
				$lang_ids = array_keys($languages);
				$this->CI->pg_language->pages->set_string_langs('banners', "banners_group_".$attrs['gid'], $name, $lang_ids);
			}
		}
		
		return $id;
	}

	public function delete($id = null)
	{
		$id = (is_numeric($id) and $id > 0) ? intval($id) : 0;

		if ($id){
			$this->DB->where('id', $id);
			$this->DB->delete(TABLE_BANNERS_GROUPS);

			$this->DB->where('group_id', $id);
			$this->DB->delete(TABLE_BANNERS_PLACE_GROUP);

			$this->DB->where('group_id', $id);
			$this->DB->delete(TABLE_BANNERS_BANNER_GROUP);

			$this->delete_all_pages($id);
		}

		return;
	}

	/**
	 * Get group
	 *
	 * @param int $group_id
	 * @return array
	 */
	public function get_group($group_id){
		$group_id = (is_numeric($group_id) and $group_id > 0) ? intval($group_id) : 0;
		$object = false;

		if ($group_id){
			$this->DB->select(implode(", ", $this->group_fields))->from(TABLE_BANNERS_GROUPS)->where('id', $group_id);
			$results = $this->DB->get()->result_array();
			if(!empty($results) && is_array($results)){
				$results = $this->format_group($results);
			}
			
			if(!empty($results) && is_array($results)){
				$object = $results[0];
			}
		}
		return $object;
	}

	public function get_group_id_by_gid($gid){
		$this->DB->select('id')->from(TABLE_BANNERS_GROUPS)->where('gid', $gid);
		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			return $results[0]['id'];
		}

		return 0;
	}

	public function get_group_pages($group_id){
		$group_id = (is_numeric($group_id) and $group_id > 0) ? intval($group_id) : 0;
		$objects = false;

		if ($group_id){
			$this->DB->select('id, name, link')->from(TABLE_BANNERS_PAGES)->where('group_id', $group_id);
			$results = $this->DB->get()->result();
			if(!empty($results) && is_array($results)){
				foreach($results as $result){
					$objects[] = get_object_vars($result);
				}
			}
		}
		return $objects;
	}

	public function get_group_id_by_page_link($link){
		if(!isset($this->group_get_cache[$link])){
			$this->DB->select('id, group_id')->from(TABLE_BANNERS_PAGES)->where('link', $link);
			$results = $this->DB->get()->result();
			if(!empty($results) && is_array($results)){
				$this->group_get_cache[$link] = intval($results[0]->group_id);
			}else{
				$this->group_get_cache[$link] = false;
			}
		}
		return $this->group_get_cache[$link];
	}

	public function search_groups_id_by_page_link($link){
		$link = addslashes($this->CI->input->xss_clean($link));
		if(!isset($this->group_search_cache[$link])){
			$results = $this->DB->query("SELECT id, link, group_id FROM ".TABLE_BANNERS_PAGES." WHERE \"".$link."\" LIKE CONCAT( link,  '%' ) AND link!='".addslashes($link)."' ORDER BY link DESC")->result();
			if(!empty($results) && is_array($results)){
				foreach($results as $result){
					$objects[] = $result->group_id;
				}
				$this->group_search_cache[$link] = $objects;
			}else{
				$this->group_search_cache[$link] = false;
			}
		}
		return $this->group_search_cache[$link];
	}

	public function get_used_modules(){
		$objects = array();

		$this->DB->select('id, module_name, model_name, method_name')->from(TABLE_BANNERS_MODULES)->order_by("module_name ASC");
		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			foreach($results as $result){
				$objects[] = $result;
			}
		}
		return $objects;
	}

	public function set_module($module_name, $model_name, $method_name ){
		$attrs = array(
			"module_name" => $module_name,
			"model_name" => $model_name,
			"method_name" => $method_name,
		);
		$this->DB->insert(TABLE_BANNERS_MODULES, $attrs);
		return;
	}

	public function delete_module($module_name){
		$this->DB->where("module_name", $module_name);
		$this->DB->delete(TABLE_BANNERS_MODULES);
	}

	public function get_used_module($module_id){
		$object = array();

		$this->DB->select('id, module_name, model_name, method_name');
		$this->DB->from(TABLE_BANNERS_MODULES);
		$this->DB->where("id", $module_id);

		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			$object = $results[0];
		}
		return $object;
	}

	public function get_module_pages($module_id){
		$module_data = $this->get_used_module($module_id);
		if(empty($module_data)) return false;

		$model_name = ucfirst($module_data["model_name"]);
		$model_path = strtolower($module_data["module_name"]."/models/").$model_name;
		$this->CI->load->model($model_path);
		$links = $this->CI->$model_name->$module_data["method_name"]();

		if(!empty($links) && is_array($links) && count($links)>0){
			foreach($links as $link_data){
				$link_search[] = $link_data["link"];
			}

			if(!empty($link_search)){
				$results = $this->DB->select("group_id, link")->from(TABLE_BANNERS_PAGES)->where_in('link', $link_search)->get()->result_array();
				if(!empty($results) && is_array($results)){
					foreach($results as $r){
						$pages_links[$r["link"]] = $r["group_id"];
					}
				}

				if(!empty($pages_links)){
					$groups = $this->get_all_groups_key_id();
					foreach($links AS $k => $link){
						if(!empty($pages_links[$link["link"]])){
							$links[$k]["group_id"] = $group_id = $pages_links[$link["link"]]["group_id"];
							$links[$k]["group_name"] = $groups[$group_id]["name"];
						}
					}
				}
			}
		}
		return $links;
	}

	public function add_page($attrs){
		$insert["group_id"] = intval($attrs["group_id"]);
		$insert["name"] = $attrs["name"];
		$insert["link"] = $attrs["link"];

		if(!$insert["group_id"] || !$insert["link"]) return;

		$insert["date_created"] = date('Y-m-d H:i:s');
		$insert["date_modified"] = date('Y-m-d H:i:s');

		$this->DB->insert(TABLE_BANNERS_PAGES, $insert);
		return;
	}

	public function delete_page($group_id, $link){
		$this->DB->where("group_id", $group_id);
		$this->DB->where("link", $link);
		$this->DB->delete(TABLE_BANNERS_PAGES);
		return;
	}

	public function delete_all_pages($group_id){
		$this->DB->where("group_id", $group_id);
		$this->DB->delete(TABLE_BANNERS_PAGES);
		return;
	}

	public function get_fill_positions($group_ids, $place_id, $exclude_banner_id=0){
		$return = array();
		$this->DB->select('group_id, SUM(positions) AS sumall')->from(TABLE_BANNERS_BANNER_GROUP)
				 ->where_in('group_id', $group_ids)
				 ->where('place_id', $place_id);
		if($exclude_banner_id) $this->DB->where('banner_id !=', $exclude_banner_id);
		$this->DB->group_by('group_id');
		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			foreach($results as $r){
				$return[$r["group_id"]] = $r["sumall"];
			}
		}
		return $return;
	}

	public function create_unique_group($attrs = array()){
		$group_id = $this->get_group_id_by_gid($attrs['gid']);
		if ($group_id){
			return $group_id;
		} else {
			return $this->save(null, $attrs);
		}
	}
	
	public function update_langs($banners_groups, $langs_file, $langs_ids) {
		foreach($banners_groups as $key => $value){
			$this->CI->pg_language->pages->set_string_langs('banners',
															$value,
															$langs_file[$value],
															(array)$langs_ids);
		}
	}
	
	public function export_langs($banners_groups, $langs_ids = null) {
		return $this->CI->pg_language->export_langs('banners', $banners_groups, $langs_ids);
	}

}
