<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

define('KISSES_TABLE', DB_PREFIX . 'kisses');
define('KISSES_USERS_TABLE', DB_PREFIX . 'kisses_users');

/**
 * Kisses model
 * 
 * @package PG_DatingPro
 * @subpackage Kisses
 * @category	models
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @developer Andrey Kopaev <akopaev@pilotgroup.net>
 * */
class Kisses_model extends Model {
	
	/**
	 * Link to Code Igniter object
	 */
	protected $CI;
	
	/**
	 * Table fields kisses
	 * @var array
	 */
	private $_fields_kisses = array(
		'id',
		'image',
		'sorter',
		'date_created',
	);
	
	/**
	 * Table fields users kisses
	 * @var array
	 */
	private $_fields_user_kisses = array(
		'id',
		'image',
		'user_to',
		'user_from',
		'message',
		'date_created',
		'mark',
	);
	
	public $image_upload_gid = 'kisses-file';
	
	private $upload_config;
	
	private $moderation_type = "kisses";
	
	/**
	 * Constructor
	 *
	 * @return Kisses_model
	 */
	public function __construct() {
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;
		
		$this->CI->load->model('uploads/models/Uploads_config_model');
		$upload_config = $this->CI->Uploads_config_model->get_config_by_gid($this->image_upload_gid);
		$this->upload_config[$this->image_upload_gid] = $this->CI->Uploads_config_model->format_config($upload_config);
		
		foreach($this->CI->pg_language->languages as $id=>$value){
			$this->_fields_kisses[] = 'name_'.$value['id'];
		}
		
	}
	
	/**
	 * save kisses
	 * @param array $data
	 * @param integer $id
	 * @return bool
	 */
	public function save($id = null, $data) {
		
		if (is_null($id)) {
			
			if(empty($data["date_created"])) {
				$data["date_created"] = date(self::DB_DATE_FORMAT);
			}
			
			$this->DB->insert(KISSES_TABLE, $data);
		} else {
			
			$this->DB->where('id', $id)
					->update(KISSES_TABLE, $data);
					
		}
		
		return true;
	}
	
	/**
	 * Return list kisses
	 * @param array $params
	 * @param integer $page
	 * @param integer $items_on_page
	 * @param array $order_by
	 * @param array $filter_object_ids
	 * @param array $filter_object_not_ids
	 * @return array
	 */
	public function get_list($page = 1, $items_on_page = 100, $params = array(), $order_by = array('sorter'=>'ASC'), $filter_object_ids = null, $filter_object_not_ids = null) {
		$this->DB->select(implode(', ', $this->_fields_kisses))->from(KISSES_TABLE);
		
		if (!empty($params['where']) && is_array($params['where']) && count($params['where'])) {
			foreach ($params['where'] as $field => $value) {
				$this->DB->where($field, $value);
			}
		}
		
		if (!empty($params['where_in']) && is_array($params['where_in']) && count($params['where_in'])) {
			foreach ($params['where_in'] as $field => $value) {
				$this->DB->where_in($field, $value);
			}
		}
		
		if (!empty($params['where_sql']) && is_array($params['where_sql']) && count($params['where_sql'])) {
			foreach ($params['where_sql'] as $value) {
				$this->DB->where($value, null, false);
			}
		}
		
		if (is_array($filter_object_ids) && count($filter_object_ids)) {
			$this->DB->where_in('id', $filter_object_ids);
		}
		
		if (is_array($filter_object_not_ids) && count($filter_object_not_ids)) {
			$this->DB->where_not_in('id', $filter_object_not_ids);
		}
		
		if (is_array($order_by) && count($order_by) > 0) {
			foreach ($order_by as $field => $dir) {
				if (in_array($field, $this->_fields_kisses)) {
					$this->DB->order_by($field . ' ' . $dir);
				}
			}
		}
		
		$page = intval($page);
		$items_on_page = intval($items_on_page);
		if (!empty($page) && $items_on_page>0) {
			$this->DB->limit($items_on_page, $items_on_page * ($page - 1));
		}
		$results = $this->DB->get()->result_array();
		return $results;
	}
	
	/**
	 * Return number of kisses
	 * @param array $params
	 * @return array
	 */
	public function get_count($params = array()) {
		if (isset($params['where']) && is_array($params['where']) && count($params['where'])) {
			foreach ($params['where'] as $field => $value) {
				$this->DB->where($field, $value);
			}
		}
		if (isset($params['where_in']) && is_array($params['where_in']) && count($params['where_in'])) {
			foreach ($params['where_in'] as $field => $value) {
				$this->DB->where_in($field, $value);
			}
		}
		if (isset($params['where_sql']) && is_array($params['where_sql']) && count($params['where_sql'])) {
			foreach ($params['where_sql'] as $value) {
				$this->DB->where($value, null, false);
			}
		}
		return $this->DB->count_all_results(KISSES_TABLE);
	}
	
	public function format($kisses) {
		
		return $kisses;
	}
	
	private function _get_upload_gid($file_name) {
		$extension = strtolower(end(explode('.', $file_name)));
		$result = '';
		
		foreach($this->upload_config as $upload_gid => $upload_config){
			foreach($upload_config['file_formats'] as $file_format){
				if($file_format == $extension){
					$result = $upload_gid;
					break 2;
				}
			}
		}
		return $result;
	}
	
	/**
	 * Method validate data
	 * @var $file_name
	 * @var $_POST
	 * return error or succes
	 */
	public function validate($file_name) {
		
		if(isset($_FILES[$file_name]) && is_array($_FILES[$file_name]) && !($_FILES[$file_name]["error"])){
			
			$upload_gid = $this->_get_upload_gid($_FILES[$file_name]['name']);
			
			if($upload_gid == $this->image_upload_gid){
				$this->CI->load->model("Uploads_model");
				$file_return = $this->CI->Uploads_model->validate_upload($this->image_upload_gid, $file_name);
				
				if(!empty($file_return["error"])){
					$validate_data["errors"][] = (is_array($file_return["error"])) ? implode("<br>", $file_return["error"]) : $file_return["error"];
				}
			}else{
				$validate_data["errors"][] = l('error_invalid_file_type', 'kisses');
			}
			
			$validate_data["data"]['mime'] = $_FILES[$file_name]["type"];
			
		}elseif(!empty($_FILES[$file_name]["error"])){
			$validate_data["errors"][] = $_FILES[$file_name]["error"];
		}
	}
	
	/**
	 * Method upload and save files
	 * @var $file_name
	 * @var $_POST
	 * return error or succes
	 */
	public function _post_upload($file_name) {
		
		$result = array();
		$data = array();
		
		$this->CI->load->model('Uploads_model');
		$upload_return = $this->CI->Uploads_model->upload($this->image_upload_gid, '', $file_name);
		//print_r($upload_return);
		//		exit;
					
					/*$thumb_data["crop_param"] = "resize";
					$thumb_data["width"] = 100;
					$thumb_data["height"] = 100;
					$thumb_data["effect"] = "";
					
					$return = $this->CI->Uploads_model->action('uploads/kisses-file/'.$upload_return['file'], $thumb_data, false);
					print_r($upload_return);
					exit;*/
					
		if(empty($upload_return['errors'])){
			$data['image'] = $upload_return['file'];
			$data['date_created'] = date("Y-m-d H:i:s");
			$result['name'] = $upload_return['file'];
		} else {
			$result['errors'][] = $upload_return["errors"];
			return $result;
		}
		$result['is_saved'] = $this->_save_kisses($data, array());
		
		return $result;
	}
	
	/**
	 * Method upload and save files
	 * @var $file_name
	 * @var $_POST
	 * return error or succes
	 */
	private function _save_kisses($attrs, $where = array()){
		
		if(!empty($where) && is_array($where)){
			$this->DB->where($where)->update(KISSES_TABLE, $attrs);
			return $this->DB->affected_rows();
		}else{
			
			$this->DB->insert(KISSES_TABLE, $attrs);
			$return = $this->DB->insert_id();
			
			$this->DB->set('sorter', 'sorter + 1', FALSE);
			$this->DB->update(KISSES_TABLE);
			
			return $return;
		}
		
	}
	
	/**
	 * Method deleted kisses files
	 * @var $kisses_id integer kisses id
	 * return string
	 */
	public function delete_kisses($kisses_id){
		$result = array();
		
		$kisses = $this->get_kisses_by_id($kisses_id);
		if(!$kisses){
			$result['error'] = $this->system_messages->add_message('error', l('error_empty_kiss_id', 'kisses'));
			return $result;
		}
		
		$this->DB->where('id', $kisses['id']);
		$this->DB->delete(KISSES_TABLE);
		$this->CI->load->model('Uploads_model');
		$result = $this->CI->Uploads_model->delete_upload($this->image_upload_gid, '', $kisses['image']);
		return true;
	}
	
	/**
	 * Method return info about kisses record
	 * @var $kisses_id integer kisses id
	 * return array
	 */
	function get_kisses_by_id($kisses_id) {
		
		if(!$kisses_id){
			return array();
		}
		
		$result = $this->DB->select(implode(", ", $this->_fields_kisses))->from(KISSES_TABLE)->where("id", $kisses_id)->get()->result_array();
		if(empty($result)){
			return array();
		}else{
			return $result[0];
		}
	}
	
	/**
	 * Install wish list fields depended on language 
	 * 
	 * @param integer $lang_id language identifier
	 * @return void
	 */
	public function lang_dedicate_module_callback_add($lang_id=false){
		if(!$lang_id) return;
		
		$this->CI->load->dbforge();
		
		$fields['name_'.$lang_id] = array('type'=>'TEXT', 'null'=>TRUE);
		$this->CI->dbforge->add_column(KISSES_TABLE, $fields);
		
		$default_lang_id = $this->CI->pg_language->get_default_lang_id();
		if($lang_id != $default_lang_id){
			$this->CI->db->set('name_'.$lang_id, 'name_'.$default_lang_id, false);
			$this->CI->db->update(KISSES_TABLE);
		}
	}
	
	/**
	 * Uninstall wish list fields depended on language
	 * 
	 * @param integer $lang_id language identifier
	 * @return void
	 */
	public function lang_dedicate_module_callback_delete($lang_id=false){
		if(!$lang_id) return;
		
		$this->CI->load->dbforge();
		
		$fields_exists = $this->CI->db->list_fields(KISSES_TABLE);
		
		$fields = array('name_'.$lang_id);
		foreach($fields as $field_name){
			if(!in_array($field_name, $fields_exists)) continue;
			$this->CI->dbforge->drop_column(KISSES_TABLE, $field_name);
		}
	}
	
	/**
	 * Validate kisses object for saving to data source
	 * 
	 * @param integer $kisses_id wish list identifier 
	 * @param array $data kisses data
	 * @return array
	 */
	public function validate_kisses($kisses_id, $data){
		$return = array('errors'=>array(), 'data'=>array());
		
		
		if(isset($data['id'])){
			$return['data']['id'] = intval($data['id']);
			if(empty($return['data']['id'])) unset($return['data']['id']);
		}
		
		if(!$data['object_id']){
			$return['errors'][] = l('error_invalid_user_to', 'kisses');
		} else {
			$return['data']['object_id'] = $data['object_id'];
		}
		
		$kisses = $this->get_kisses_by_id($kisses_id);
		
		if( empty($kisses) ){
			$return['errors'][] = l('error_empty_record', 'kisses');
		} else {
			$return['data']['kisses'] = $kisses;
		}
		
		if(!empty($data['message'])){
			
			$return['data']['message'] = mb_substr($data['message'], 0, $this->pg_module->get_module_config('kisses', 'number_max_symbols'), 'UTF-8');
			$this->CI->load->model('moderation/models/Moderation_badwords_model');
			$bw_count = $this->CI->Moderation_badwords_model->check_badwords($this->moderation_type, $return['data']['message'] );
			if($bw_count){
				$return["errors"][] = l('error_badwords_message', 'kisses');
			}
		}
		
		
		if(isset($data['date_created'])){
			$value = strtotime($data['date_created']);
			if($value > 0) $return['data']['date_created'] = date('Y-m-d', $value);
		}
		
		$default_lang_id = $this->CI->pg_language->current_lang_id;
		if(isset($data['name_'.$default_lang_id])){
			$return['data']['name_'.$default_lang_id] = trim(strip_tags($data['name_'.$default_lang_id]));
			if(empty($return['data']['name_'.$default_lang_id])){
				$return['errors'][] = l('error_empty_kisses_name', 'kisses');
			}else{				
				foreach($this->CI->pg_language->languages as $lid=>$lang_data){
					if($lid == $default_lang_id) continue;
					if(!isset($data['name_'.$lid]) || empty($data['name_'.$lid])){
						$return['data']['name_'.$lid] = $return['data']['name_'.$default_lang_id];
					}else{
						$return['data']['name_'.$lid] = trim(strip_tags($data['name_'.$lid]));
						if(empty($return['data']['name_'.$lid])){
							$return['errors'][] = l('error_empty_kisses_name', 'kisses');
							break;
						}
					}
				}
			}
		}
		
		return $return;
	}
	
	/**
	 * Save kisses object to data source
	 * 
	 * @param integer $kisses_id wish list identifier
	 * @param array $data wish list data
	 * @return integer
	 */
	public function save_kisses($kisses_id, $data){
		
		$this->DB->where('id', $kisses_id);
		$this->DB->update(KISSES_TABLE, $data);
		
		return $kisses_id;
	}
	
	/**
	 * Save kisses object to data source from user
	 * 
	 * @param integer $kisses_id wish list identifier
	 * @param array $data wish list data
	 * @return integer
	 */
	public function save_user_kisses($data){
		
		$data['date_created'] = date("Y-m-d H:i:s");
		
		$this->DB->insert(KISSES_USERS_TABLE, $data);
		$kisses_id = $this->DB->insert_id();
		
		return $kisses_id;
	}
	
	/**
	 * Return number of new kisses for user
	 * @param integer $id_user user identifier
	 * @return integer
	 */
	public function new_kisses_count($id_user = null){
		
		if(!$id_user) {
			$id_user = $CI->session->userdata("user_id");
		}
		$this->DB->select('COUNT(*) AS cnt')
				 ->from(KISSES_USERS_TABLE)
				 ->where('user_to', $id_user)
				 ->where('mark', 0);

		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			return intval($results[0]["cnt"]);
		}
		return 0;
	}
	
	/**
	 * Return number of new kisses for user
	 * @return array
	 */
	public function backend_kisses_count(){
		$id_user = $this->CI->session->userdata('user_id');
		$kisses_count = $this->new_kisses_count($id_user);
		return array('count' => $kisses_count);
	}
	
	/**
	 * Return list kisses
	 * @param array $params
	 * @param integer $page
	 * @param integer $items_on_page
	 * @param array $order_by
	 * @param array $filter_object_ids
	 * @return array
	 */
	public function get_list_user_kisses($params = array(), $page = 1, $items_on_page = 20, $order_by = null) {
		
		$this->DB->select(implode(', ', $this->_fields_user_kisses))->from(KISSES_USERS_TABLE);
		
		if (!empty($params['where']) && is_array($params['where']) && count($params['where'])) {
			foreach ($params['where'] as $field => $value) {
				$this->DB->where($field, $value);
			}
		}
		
		if(is_array($order_by) && count($order_by)>0){
			foreach($order_by as $field => $dir){
				if(in_array($field, $this->_fields_user_kisses)){
					$this->DB->order_by($field." ".$dir);
				}
			}
		}
		
		$page = intval($page);
		if (!empty($page)) {
			$this->DB->limit($items_on_page, $items_on_page * ($page - 1));
		}
		$results = $this->DB->get()->result_array();
		return $results;
	}
	
	/**
	 * Return number of kisses for user
	 * @param array $params
	 * @return array
	 */
	public function get_count_kisses_users($params = array()) {
		if (isset($params['where']) && is_array($params['where']) && count($params['where'])) {
			foreach ($params['where'] as $field => $value) {
				$this->DB->where($field, $value);
			}
		}
		return $this->DB->count_all_results(KISSES_USERS_TABLE);
	}
	
	/**
	 * Mark as read
	 * @param integer $kisses_id id record
	 * @param array $data
	 * @return array
	 */
	public function _mark_as_read($kisses_id = null, $data = array()) {
		$this->DB->where('id', $kisses_id);
		$this->DB->update(KISSES_USERS_TABLE, $data);
	}
	
	/**
	 * Save position on page
	 * @param integer $page_id id record
	 * @param array $data
	 * @return integer
	 */
	public function save_page($page_id, $attrs){
		
		$this->DB->where('id', $page_id);
		$this->DB->update(KISSES_TABLE, $attrs);
		
		return $page_id;
	}
	
	/**
     *  Module category action
     *  @return array
     */
    public function moduleCategoryAction()
	{
		$action = array(
			'name' 	 => l('kiss', 'kisses'),
			'helper' => 'kisses_list',
		);
		return $action;
	}
}
