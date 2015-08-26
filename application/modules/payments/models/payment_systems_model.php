<?php
/**
* Payment systems model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

if (!defined('BASEPATH')) exit('No direct script access allowed');

define('PAYMENTS_SYSTEMS_TABLE', DB_PREFIX.'payments_systems');
define('PAYMENTS_LOG_TABLE', DB_PREFIX.'payments_log');

class Payment_systems_model extends Model
{
	private $CI;
	private $DB;

	private $fields = array(
		'id',
		'gid',
		'name',
		'in_use',
		'date_add',
		'settings_data',
                'tarifs_type',
                'tarifs_editable',
                'tarifs_status',
                'tarifs_data',
		'logo',
	);

	private $current_driver_name = "";
	private $driver;

	private $systems_cache = array();

	private $log_data = true;

	private $_upload_folder = 'payments-logo';
        
        	/**
	 * Module guid
	 * 
	 * @var array
	 */
	protected $module_gid = 'payment_systems';

	function __construct()
	{
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;
		foreach($this->CI->pg_language->languages as $id=>$value){
			$this->fields[] = 'info_data_'.$value['id'];
		}
		$this->DB->memcache_tables(array(PAYMENTS_SYSTEMS_TABLE));
	}

	public function get_system_by_gid($gid){
		if(empty($this->systems_cache[$gid])){
			$result = $this->DB->select(implode(", ", $this->fields))->from(PAYMENTS_SYSTEMS_TABLE)->where("gid", $gid)->get()->result_array();
			if(!empty($result)){
				$this->systems_cache[$gid] = $this->format_system($result[0]);
			}else{
				$this->systems_cache[$gid] = array();
			}
		}
		return $this->systems_cache[$gid];
	}

	public function format_system($data){
		$data["settings_data"] = unserialize($data["settings_data"]);
                
                $reference = $this->pg_language->ds->get_reference($this->module_gid, $data['gid'].'_operators');
		$data["operators_data"] = isset($reference['option']) ? $reference['option'] : array();
		$data["tarifs_status"] = unserialize($data["tarifs_status"]);
		$data["tarifs_data"] = unserialize($data["tarifs_data"]);
                
		$data["info_data"] = $data["info_data_".$this->pg_language->current_lang_id];

		if(!empty($data["logo"])) {
			$data["logo_path"] = realpath(FRONTEND_PATH . $this->_upload_folder
					. DIRECTORY_SEPARATOR . $data['logo']);
			if($data["logo_path"]) {
				$data["logo_url"] = FRONTEND_URL . $this->_upload_folder . '/'
						. $data['logo'] . '?random=' . rand(1, 999);
			} else {
				// Delete logo if the file does not exist
				log_message("error", '(payments) File "' . $data["logo"] . '" does not exist');
				$this->delete_logo($data['gid']);
			}
		}
		return $data;
	}

	public function get_system_list($params=array(), $filter_object_ids=null, $order_by=null){
		$this->DB->select(implode(", ", $this->fields));
		$this->DB->from(PAYMENTS_SYSTEMS_TABLE);

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
				if(in_array($field, $this->fields)){
					$this->DB->order_by($field." ".$dir);
				}
			}
		}

		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			foreach($results as $r){
				$data[] = $this->systems_cache[$r["gid"]] = $this->format_system($r);
			}
			return $data;
		}
		return array();

	}

	public function get_active_system_list($order_by=array("name"=>"ASC")){
		$params["where"]["in_use"] = 1;
		return $this->get_system_list($params, null, $order_by);
	}

	public function get_system_count($params=array(), $filter_object_ids=null){
		$this->DB->select("COUNT(*) AS cnt");
		$this->DB->from(PAYMENTS_SYSTEMS_TABLE);

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

	public function validate_system($id, $data){
		$return = array("errors"=> array(), "data" => array());

		if(isset($data["gid"])){
			$data["gid"] = strip_tags($data["gid"]);
			$data["gid"] = preg_replace("/[^a-z0-9]+/i", '', $data["gid"]);

			$return["data"]["gid"] = $data["gid"];

			if(empty($return["data"]["gid"]) ){
				$return["errors"][] = l('error_system_gid_incorrect', 'payments');
			}

			$param["where"]["gid"] = $return["data"]["gid"];
			if(!empty($id)) $param["where"]["id <>"] = $id;
			$gid_count = $this->get_system_count($param);
			if($gid_count > 0){
				$return["errors"][] = l('error_system_gid_already_exists', 'payments');
			}
		}

		if(isset($data["name"])){
			$return["data"]["name"] = strip_tags($data["name"]);
			if(empty($return["data"]["name"]) ){
				$return["errors"][] = l('error_system_name_incorrect', 'payments');
			}
		}

		if(isset($data["settings_data"])){
			$return["data"]["settings_data"] = serialize($data["settings_data"]);
		}
                
                if(isset($data['tarifs_type'])){
			$return["data"]["tarifs_type"] = intval($data["tarifs_type"]);
		}
		
		if(isset($data['tarifs_editable'])){
			$return["data"]["tarifs_editable"] = $data["tarifs_editable"] ? 1 : 0;
		}
                
                if(isset($data["tarifs_status"])){
			$return["data"]["tarifs_status"] = serialize($data["tarifs_status"]);
		}
		
		if(isset($data["tarifs_data"])){
			$return["data"]["tarifs_data"] = serialize($data["tarifs_data"]);
		}

		if(isset($data["in_use"])){
			$return["data"]["in_use"] = intval($data["in_use"]);
		}

		if(isset($data["info_data"])){
			$return['data'] = array_merge($return['data'], $data["info_data"]);
		}

		if(isset($data["logo"])){
			$return["data"]["logo"] = strip_tags($data["logo"]);
			if(empty($return["data"]["logo"]) ){
				$return["errors"][] = l('error_system_logo_incorrect', 'payments');
			}
		}

		return $return;
	}

	public function validate_info_data($data){
		$return = array("errors"=> array(), "data" => array());

		$default_lang_id = $this->CI->pg_language->current_lang_id;
		if(isset($data['info_data_'.$default_lang_id])){
			$return['data']['info_data_'.$default_lang_id] = trim(strip_tags($data['info_data_'.$default_lang_id]));
			foreach($this->pg_language->languages as $lid=>$lang_data){
				if($lid == $default_lang_id) continue;
				if(!isset($data['info_data_'.$lid]) || empty($data['info_data_'.$lid])){
					$return['data']['info_data_'.$lid] = $return['data']['info_data_'.$default_lang_id];
				}else{
					$return['data']['info_data_'.$lid] = trim(strip_tags($data['info_data_'.$lid]));
				}
			}
		}

		return $return;
	}

	public function save_system($id, $data){
		if (is_null($id)){
			$data["date_add"] = date("Y-m-d H:i:s");
			$this->DB->insert(PAYMENTS_SYSTEMS_TABLE, $data);
			$id = $this->DB->insert_id();
		}else{
			$this->DB->where('id', $id);
			$this->DB->update(PAYMENTS_SYSTEMS_TABLE, $data);
		}
		unset($this->systems_cache[$data["gid"]]);

		return $id;
	}

	public function use_system($gid, $use){
		$data["in_use"] = intval($use);
		$this->DB->where('gid', $gid);
		$this->DB->update(PAYMENTS_SYSTEMS_TABLE, $data);
		unset($this->systems_cache[$gid]);
	}

	public function delete_system($id){
		$this->DB->where("id", $id);
		$this->DB->delete(PAYMENTS_SYSTEMS_TABLE);
		return;
	}

	public function delete_system_by_gid($gid){
		$this->DB->where("gid", $gid);
		$this->DB->delete(PAYMENTS_SYSTEMS_TABLE);
		unset($this->systems_cache[$gid]);
		return;
	}

	///// drivers methods
	public function load_driver($system_gid){
		if(!empty($this->current_driver_name) && $this->current_driver_name == $system_gid){
			return true;
		}

		include_once(MODULEPATH . "payments/models/payment_driver_model".EXT);
		$driver = strtolower($system_gid)."_model";
		$driver_file = MODULEPATH . "payments/models/systems/".$driver.EXT;
		if(file_exists($driver_file)){
			include_once($driver_file);
			$this->driver = new $driver();
			$this->current_driver_name = $system_gid;
		}else{
			$this->current_driver_name = "";
			$this->driver = "";
			return false;
		}
	}

	public function validate_system_settings($data){
		if(empty($this->current_driver_name)){
			return false;
		}
		return $this->driver->validate_settings($data);
	}
        
        /**
	 * Validate operator object of payment system for saving to data source
	 * 
	 * @param string $system_gid system guid
	 * @param string $operator_gid operator guid
	 * @param array $data operator data
	 * @return array
	 */
	public function validate_system_operator($system_gid, $operator_gid, $data){
		$return = array('errors'=>array(), 'data'=>array());
		
		if(empty($operator_gid)){
			$reference = $this->CI->pg_language->ds->get_reference($this->module_gid, $system_gid.'_operators');
			if(!empty($reference["option"])){
				$array_keys = array_keys($reference["option"]);
			}else{
				$array_keys = array(0);
			}
			$return['data']['gid'] = strval(max($array_keys) + 1);
		}else{
			$return['data']['gid'] = strval($operator_gid);
		}
		
		$default_lang_id = $this->CI->pg_language->current_lang_id;
		if(isset($data['name_'.$default_lang_id])){
			$return['data']['name'][$default_lang_id] = trim(strip_tags($data['name_'.$default_lang_id]));
			if(empty($return['data']['name'][$default_lang_id])){
				$return['errors'][] = l('error_empty_system_operator', 'payments');
			}else{				
				foreach($this->CI->pg_language->languages as $lid=>$lang_data){
					if($lid == $default_lang_id) continue;
					if(!isset($data['name_'.$lid]) || empty($data['name_'.$lid])){
						$return['data']['name'][$lid] = $return['data']['name'][$default_lang_id];
					}else{
						$return['data']['name'][$lid] = trim(strip_tags($data['name_'.$lid]));
						if(empty($return['data']['name'][$lid])){
							$return['errors'][] = l('error_empty_system_operator', 'payments');
							break;
						}
					}
				}
			}
		}
		
		return $return;
	}
        
        /**
	 * Save operator data to data source
	 * 
	 * @param string $system_gid system guid
	 * @param string $operator_gid operator guid
	 * @param array $data operator data
	 * @return array
	 */
	public function save_system_operator($system_gid, $operator_gid, $data){
		foreach($data as $lid => $string){
			$reference = $this->CI->pg_language->ds->get_reference($this->module_gid, $system_gid.'_operators', $lid);
			$reference["option"][$operator_gid] = $string;
			$this->CI->pg_language->ds->set_module_reference($this->module_gid, $system_gid.'_operators', $reference, $lid);
		}
	}
        
        /**
	 * Change sorting order of payment system operators
	 * 
	 * @param string $system_gid system guid
	 * @param array $sorter_data sorter data
	 * @return void
	 */
	public function save_system_operators_sorter($system_gid, $sorter_data){
		$this->CI->pg_language->ds->set_reference_sorter($this->module_gid, $system_gid.'_operators', $sorter_data);
	}
        
        /**
	 * Save operator data to data source
	 * 
	 * @param string $system_gid system guid
	 * @param string $operator_gid operator guid
	 * @return array
	 */
	public function delete_system_operator($system_gid, $operator_gid){
		$system_data = $this->get_system_by_gid($system_gid);
		if($system_data['tarifs_status'][$operator_gid]) unset($system_data['tarifs_status'][$operator_gid]);
		if($system_data['tarifs_data'][$operator_gid]) unset($system_data['tarifs_data'][$operator_gid]);
		$save_data = array('gid'=>$system_data['gid'], 'tarifs_status'=>$system_data['tarifs_status'], 'tarifs_data'=>$system_data['tarifs_data']);
		$validate_data = $this->validate_system($system_data['id'], $save_data);
		$this->save_system($system_data['id'], $validate_data['data']);
		
		foreach($this->CI->pg_language->languages as $lid => $lang_data){
			$reference = $this->CI->pg_language->ds->get_reference($this->module_gid, $system_gid.'_operators', $lid);
			if(isset($reference["option"][$operator_gid])) unset($reference["option"][$operator_gid]);
			$this->CI->pg_language->ds->set_module_reference($this->module_gid, $system_gid.'_operators', $reference, $lid);
		}
	}
        
	/**
	 * Validate payment system tarifs for saving to data source
	 * 
	 * @param array $data tarifs data
	 * @return array
	 */
	public function validate_tarifs_data($data){
		$return = array('errors'=>array(), 'data'=>array());
		
		foreach($data as $key=>$values){
			if(!is_array($values)) continue;
			foreach($values as $k=>$v){
				$return['data'][$key][$k] = floatval($v);
				if(empty($return['data'][$key][$k])) unset($return['data'][$key][$k]);
			}
		}
		
		return $return;
	}

	public function get_system_data_map(){
		if(empty($this->current_driver_name)){
			return false;
		}
		return $this->driver->get_settings_map();
	}

	public function get_html_data_map(){
		if(empty($this->current_driver_name)){
			return false;
		}
		return $this->driver->get_html_map();
	}
	
	public function get_system_data(){
		return $this->driver->payment_data;
	}

	public function action_request($system_gid, $payment_data){
		if(empty($this->current_driver_name) || $this->current_driver_name != $system_gid){
			$this->load_driver($system_gid);
		}
		if(!empty($this->current_driver_name)){
			$system_settings = $this->get_system_by_gid($this->current_driver_name);
		}else{
			$system_settings = array();
		}
		if($this->log_data){
			$this->log_data($system_gid, "request", $payment_data);
		}
		if(method_exists($this->driver, 'func_request')) {
			return $this->driver->func_request($payment_data, $system_settings);
		} else {
			return false;
		}
	}

	public function action_responce($system_gid, $payment_data){
		if(empty($this->current_driver_name) || $this->current_driver_name != $system_gid){
			$this->load_driver($system_gid);
		}
		if(!empty($this->current_driver_name)){
			$system_settings = $this->get_system_by_gid($this->current_driver_name);
		}else{
			$system_settings = array();
		}
		if($this->log_data){
			$this->log_data($system_gid, "responce", $payment_data);
		}
		return $this->driver->func_responce($payment_data, $system_settings);
	}

	public function action_html($system_gid){
		if(empty($this->current_driver_name) || $this->current_driver_name != $system_gid){
			$this->load_driver($system_gid);
		}
		if(method_exists($this->driver, 'func_html')) {
			return $this->driver->func_html();
		} else {
			return false;
		}
	}

	public function action_validate($system_gid, $payment_data){
		if(empty($this->current_driver_name) || $this->current_driver_name != $system_gid){
			$this->load_driver($system_gid);
		}
		if(!empty($this->current_driver_name)){
			$system_settings = $this->get_system_by_gid($this->current_driver_name);
		}else{
			$system_settings = array();
		}
		return $this->driver->func_validate($payment_data, $system_settings);
	}

	public function log_data($system_gid, $log_type="request", $log_data=array()){
		$data["system_gid"] = $system_gid;
		$data["date_add"] = date("Y-m-d H:i:s");
		$data["log_type"] = $log_type;
		$data["payment_data"] = serialize($log_data);
		$this->DB->insert(PAYMENTS_LOG_TABLE, $data);
		return;
	}

	/**
	 * Upload payment system's logo
	 * @param string $system_gid
	 * @param array $size
	 * @param string $field_name
	 * @return boolean
	 */
	public function upload_logo($system_gid, $size = array('width' => 100, 'height' => 100), $field_name = 'logo'){
		$return = array('error'=>'', 'success'=>false);
		$this->CI->load->helper('upload');

		// upload src file
		$upload_config = array(
			'allowed_types' => 'jpg|gif|png',
			'overwrite' => true
		);

		$path = FRONTEND_PATH . $this->_upload_folder . DIRECTORY_SEPARATOR;
		$image_return = upload_file($field_name, $path, $upload_config);
		if(!empty($image_return['error'])){
			$return['error'] = implode('<br>', $image_return['error']);
		}else{
			$new_name = $field_name . '_' . $system_gid . $image_return['data']['file_ext'];
			copy($image_return['data']['full_path'], $path . $new_name);
			@unlink($image_return['data']['full_path']);
			@ini_set('memory_limit', '512M');
			$this->CI->load->library('image_lib');

			$resize_config = array(
				'width' => $size['width'],
				'height' => $size['height'],
				'source_image' => $path . $new_name,
				'create_thumb' => false,
				'maintain_ratio' => true,
				'master_dim' => 'height'
			);

			$this->CI->image_lib->initialize($resize_config);
			$this->CI->image_lib->resize();

			if(!empty($this->CI->image_lib->error_msg)){
				$return['error'] = implode('<br>', $this->CI->image_lib->error_msg);
			}else{
				$return['success'] = true;
				$data['logo'] = $new_name;
				$this->DB->where('gid', $system_gid)->update(PAYMENTS_SYSTEMS_TABLE, $data);
			}
		}
		return $return;
	}

	/**
	 * Delete payment system's logo
	 * @param string $system_gid
	 * @return bool
	 */
	public function delete_logo($system_gid){
		$result = $this->DB->select('logo')
					->from(PAYMENTS_SYSTEMS_TABLE)
					->where('gid', $system_gid)->get()->result_array();
		$file_name = realpath(FRONTEND_PATH . $this->_upload_folder
				. DIRECTORY_SEPARATOR . $result[0]['logo']);
		if($file_name) {
			unlink($file_name);
		}
		$this->DB->where('gid', $system_gid)
			->update(PAYMENTS_SYSTEMS_TABLE, array('logo' => ''));
		return true;
	}

	/**
	 * Add payment system info language field
	 * @param integer $lang_id language identifier
	 */
	public function lang_dedicate_module_callback_add($lang_id=false){
		if(!$lang_id) return;

		$this->CI->load->dbforge();

		$fields['info_data_'.$lang_id] = array('type'=>'TEXT', 'null'=>TRUE);
		$this->CI->dbforge->add_column(PAYMENTS_SYSTEMS_TABLE, $fields);

		$default_lang_id = $this->CI->pg_language->get_default_lang_id();
		if($lang_id != $default_lang_id){
			$this->CI->db->set('info_data_'.$lang_id, 'info_data_'.$default_lang_id, false);
			$this->CI->db->update(PAYMENTS_SYSTEMS_TABLE);
		}
	}

	/**
	 * Remove payment system info language field
	 * @param integer $lang_id language identifier
	 */
	public function lang_dedicate_module_callback_delete($lang_id=false){
		if(!$lang_id) return;

		$this->CI->load->dbforge();

		$table_query = $this->CI->db->get(PAYMENTS_SYSTEMS_TABLE);
		$fields_exists = $table_query->list_fields();

		$fields = array('info_data_'.$lang_id);
		foreach($fields as $field_name){
			if(!in_array($field_name, $fields_exists)) continue;
			$this->CI->dbforge->drop_column(PAYMENTS_SYSTEMS_TABLE, $field_name);
		}
	}

	public function action_js($system_gid){
		if(empty($this->current_driver_name) || $this->current_driver_name != $system_gid){
			$this->load_driver($system_gid);
		}
		if(method_exists($this->driver, 'func_js')) {
			return $this->driver->func_js();
		} else {
			return false;
		}
	}

	public function get_js($payment_data, $system_settings){
		if(empty($this->current_driver_name)){
			return false;
		}
		return $this->driver->get_js($payment_data, $system_settings);
	}
	
}
