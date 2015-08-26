<?php
/**
* Comments types model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Dmitry Popenov
* @version $Revision: 2 $ $Date: 2013-01-30 10:07:07 +0400 $
**/
if (!defined('TABLE_COMMENTS_TYPES')) define('TABLE_COMMENTS_TYPES', DB_PREFIX.'comments_types');

class Comments_types_model extends Model {

	private $CI;
	private $DB;
	private $fields_types = array(
		'id',
		'gid',
		'status',
		'module',
		'model',
		'method_count',
		'method_object',
		'settings',
	);
	private $fields_types_str;
	
	private $default_types_params = array(
		'use_likes'		=> 1,
		'guest_access'	=> 0,
		'char_count'	=> 1000
	);
	
	private $cached_types_by_gid = array();
	private $cached_types_by_id = array();
	
	/**
	 * Controller
	 */
	function __construct()
	{
		parent::Model();
		$this->CI = &get_instance();
		$this->DB = &$this->CI->db;
		$this->fields_types_str = implode(', ', $this->fields_types);
		$this->DB->memcache_tables(array(TABLE_COMMENTS_TYPES));
	}

	/*
	 * COMMENTS TYPES FUNCTIONS
	 */

	public function add_comments_type($params, $status = '1'){
		$comments_types = $this->get_comments_type_by_gid($params['gid']);
		if($comments_types['gid']){
			return false;
		}
		$params['status'] = $status;
		$this->_set_prepared_types_params($params);
		$this->DB->insert(TABLE_COMMENTS_TYPES, $params);
		return $this->DB->affected_rows();
	}
	
	public function delete_comments_type($gid){
		$this->CI->load->model('Comments_model');
		$this->CI->Comments_model->delete_comments_by_gid($gid);
		$this->DB->where('gid', $gid)->delete(TABLE_COMMENTS_TYPES);
		return $this->DB->affected_rows();
	}
	
	public function get_comments_type_by_gid($gid){
		if(empty($this->cached_types_by_gid[$gid])){
			$this->_set_comments_types_cache();
		}
		if(empty($this->cached_types_by_gid[$gid])){
			return array();
		}else{
			return $this->cached_types_by_gid[$gid];
		}
	}

	public function get_comments_type_by_id($id){
		if(empty($this->cached_types_by_id[$id])){
			$this->_set_comments_types_cache();
		}
		if(empty($this->cached_types_by_id[$id])){
			return array();
		}else{
			return $this->cached_types_by_id[$id];
		}
	}
	
	public function get_comments_types($page = 1, $items_on_page = 20){
		if(empty($this->cached_types_by_id)){
			$this->_set_comments_types_cache();
		}
		if($page && $items_on_page){
			$page = intval($page);
			if($page <= 0){
				$page = 1;
			}
			$result = array_slice($this->cached_types_by_id, $page-1, $items_on_page);
		}else{
			$result = $this->cached_types_by_id;
		}
		
		return $result;
	}
	
	private function _set_comments_types_cache(){
		$this->DB->select($this->fields_types_str)->from(TABLE_COMMENTS_TYPES);
		$result = $this->DB->get()->result_array();
		$this->cached_types_by_id = $this->cached_types_by_gid = array();
		foreach($result as $key => $type){
			$this->_get_prepared_types_params($type);
			$this->cached_types_by_gid[$type['gid']] = $this->cached_types_by_id[$type['id']] = $type;
		}
		return $result;
	}
	
	/*public function set_comments_types_cache($params = array(), $page = 1, $items_on_page = 20){
		$this->DB->select($this->fields_types_str)->from(TABLE_COMMENTS_TYPES);
		if($params){
			$this->DB->where($params);
		}
		if(isset($params['gid']) || isset($params['id'])){
			$result = $this->DB->get()->row_array();
			if(!empty($result)){
				$this->_get_prepared_types_params($result);
			}
			return $result;
		}else{
			if($page && $items_on_page){
				$this->DB->limit($items_on_page, $items_on_page*($page-1));
			}
			$result = $this->DB->get()->result_array();
			$result_format = array();
			foreach($result as $key => $type){
				$result_format[$type['gid']] = $type;
				$this->_get_prepared_types_params($result_format[$type['gid']]);
			}
			return $result_format;
		}
	}*/
	
	public function get_comments_types_cnt(){
		$count = $this->DB->count_all(TABLE_COMMENTS_TYPES);
		return $count;
	}
	
	public function save_comments_type($id, $params = array()){
		$this->_set_prepared_types_params($params, $id);
		$this->DB->where('id', $id)->update(TABLE_COMMENTS_TYPES, $params);
		return $this->DB->affected_rows();
	}
	
	private function _set_prepared_types_params(&$params, $id = 0){
		if(!isset($params['settings']) || !is_array($params['settings'])){
			$params['settings'] = array();
		}
		if($id){
			$comments_type = $this->get_comments_type_by_id($id);
			$params['settings'] = array_merge($comments_type['settings'], $params['settings']);
		}
		foreach($this->default_types_params as $key=>$value){
			if(!isset($params['settings'][$key])){
				$params['settings'][$key] = $value;
			}
			if($params['settings'][$key] === false){
				$params['settings'][$key] = 0;
			}
		}
		$params['settings'] = serialize($params['settings']);
		foreach($params as $param => $value){
			if(!in_array($param, $this->fields_types)){
				unset($params[$param]);
			}
		}
	}
	
	private function _get_prepared_types_params(&$params){
		if($params){
			$settings = unserialize($params['settings']);
			if(!is_array($settings)){
				$settings = array();
			}
			$params['settings'] = array_merge($this->default_types_params, $settings);
		}
	}
	
	public function update_langs($ctypes, $langs_file){
		foreach($ctypes as $ctype) {
			$this->CI->pg_language->pages->set_string_langs('comments', 'ctype_' . $ctype, $langs_file['ctype_' . $ctype], array_keys($langs_file['ctype_' . $ctype]));
		}
	}

	public function export_langs($ctypes, $langs_ids){
		$gids= array();
		foreach($ctypes as $ctype) {
			$gids[] = 'ctype_' . $ctype;
		}
		return $this->CI->pg_language->export_langs('comments', $gids, $langs_ids);
	}

}