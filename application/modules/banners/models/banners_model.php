<?php
/**
* Banners main model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

if (!defined('TABLE_BANNERS')) define('TABLE_BANNERS', DB_PREFIX.'banners');
if (!defined('TABLE_BANNERS_BANNER_GROUP')) define('TABLE_BANNERS_BANNER_GROUP', DB_PREFIX.'banners_banner_group');

class Banners_model extends Model {

	private $CI;
	private $DB;

	private $fields = array(
		'id',
		'date_created',
		'date_modified',
		'alt_text',
		'approve',
		'banner_image',
		'banner_place_id',
		'banner_type',
		'decline_reason',
		'expiration_date',
		'html',
		'link',
		'name',
		'new_window',
		'is_admin',
		'number_of_clicks',
		'number_of_views',
		'stat_clicks',
		'stat_views',
		'status',
		'user_id',
//		'user_activate_info'
	);

	public $upload_config_id = "banner";
	
	private $moderation_type = "banners";
	
	/**
	 * Format settings
	 * @var array
	 */
	private $format_settings = array(
		'use_format' => true,
		'get_user' => false,
	);
	
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::Model();
		$this->CI = &get_instance();
		$this->DB = &$this->CI->db;
		$this->CI->load->model('banners/models/Banner_group_model');
	}

	/**
	 * Update existing banner object
	 *
	 * @param int $id
	 * @param array $attrs
	 * @return bool
	 */
	public function save($id, $data, $file_name=""){
		if(isset($data["banner_groups"])){
			$groups_copy_attrs = $data["banner_groups"]; unset($data["banner_groups"]);
		}else{
			$groups_copy_attrs = false;
		}

		if(!empty($id)){
			$data["date_modified"] = date('Y-m-d H:i:s');
			$this->DB->where('id', $id);
			$this->DB->update(TABLE_BANNERS, $data);
		}else{
			if($this->CI->session->userdata("auth_type") == "user"){
				$data["user_id"] = $this->CI->session->userdata('user_id');
			}else{
				$data["user_id"] = 0;
			}
			$data["date_created"] = date('Y-m-d H:i:s');
			$data["date_modified"] = date('Y-m-d H:i:s');
			$this->DB->insert(TABLE_BANNERS, $data);
			$id = $this->DB->insert_id();
		}

		if(!empty($groups_copy_attrs)){
			$this->add_banner_groups($id, $groups_copy_attrs, $data["banner_place_id"], $data["is_admin"]);
		}

		if(!empty($file_name) && !empty($id) && isset($_FILES[$file_name]) && is_array($_FILES[$file_name]) && is_uploaded_file($_FILES[$file_name]["tmp_name"])){
			$banner_data = $this->get($id);

			$this->CI->load->model("Uploads_model");
			$img_return = $this->CI->Uploads_model->upload($this->upload_config_id, $banner_data["prefix"], $file_name);

			if(empty($img_return["errors"])){
				$img_data["banner_image"] = $img_return["file"];
				$this->save($id, $img_data);
			}
		}
		return $id;
	}

	public function save_banner_status($id, $status){

		if(!empty($id)){
			$attrs["status"] = intval($status);
			$attrs["date_modified"] = date('Y-m-d H:i:s');
			$this->DB->where('id', $id);
			$this->DB->update(TABLE_BANNERS, $attrs);
			if(0 === $attrs["status"]) {
				$this->save_banner_views($id, 0);
				$this->save_banner_clicks($id, 0);
			}
		}

		return;
	}

	public function get_banners_overall_stat($banner_ids){
		if(empty($banner_ids) || !is_array($banner_ids)) return false;
		$this->DB->select('id, stat_views, stat_clicks')->from(TABLE_BANNERS)->where_in("id", $banner_ids);
		$results = $this->DB->get()->result();
		if(!empty($results) && is_array($results)){
			foreach($results as $result){
				$objects[$result->id] = get_object_vars($result);
			}
			return $objects;
		}
		return false;
	}

	public function get_banner_overall_stat($banner_id){
		if(empty($banner_id) ) return false;
		$this->DB->select('id, stat_views, stat_clicks')->from(TABLE_BANNERS)->where("id", $banner_id);
		$results = $this->DB->get()->result();
		if(!empty($results) && is_array($results)){
			$object = get_object_vars($results[0]);
			return $object;
		}
		return false;
	}

	public function save_banner_views($id, $views){

		if(!empty($id)){
			$attrs["stat_views"] = intval($views);
			$this->DB->where('id', $id);
			$this->DB->update(TABLE_BANNERS, $attrs);
		}

		return;
	}

	public function save_banner_clicks($id, $clicks){

		if(!empty($id)){
			$attrs["stat_clicks"] = intval($clicks);
			$this->DB->where('id', $id);
			$this->DB->update(TABLE_BANNERS, $attrs);
		}

		return;
	}

	public function validate_banner($id, $data, $file_name=""){
		$return = array("errors"=> array(), "data" => array());

		if(isset($data["name"])){
			$return["data"]["name"] = strip_tags($data["name"]);

			if(empty($return["data"]["name"]) ){
				$return["errors"][] = l('banner_edit_error_name_empty', 'banners');
			}else{
				$this->CI->load->model('moderation/models/Moderation_badwords_model');
				$bw_count = $this->CI->Moderation_badwords_model->check_badwords($this->moderation_type, $return['data']['name']);
				if($bw_count){
					$return['errors'][] = l('error_badwords_name', 'banners');
				}
			}
		}

		if(isset($data["banner_type"])){
			$return["data"]["banner_type"] = intval($data["banner_type"]);

			if(empty($return["data"]["banner_type"]) ){
				$return["errors"][] = l('banner_edit_error_type_empty', 'banners');
			}
		}

		if(isset($data["banner_place_id"])){
			$return["data"]["banner_place_id"] = intval($data["banner_place_id"]);

			if(empty($return["data"]["banner_place_id"]) ){
				$return["errors"][] = l('banner_edit_error_place_empty', 'banners');
			}
		}

		if(isset($data["status"])){
			$return["data"]["status"] = intval($data["status"]);
		}

		if(isset($data["banner_groups"])){
			$return["data"]["banner_groups"] = $data["banner_groups"];
		}

		//// IMAGE
		if($return["data"]["banner_type"] == 1){

			if(isset($data["link"])){
				$return["data"]["link"] = $data["link"];

				if(empty($return["data"]["link"]) ){
					$return["errors"][] = l('banner_edit_error_link_empty', 'banners');
				}
			}

			if(isset($data["alt_text"])){
				$return["data"]["alt_text"] = $data["alt_text"];

				if(empty($return["data"]["alt_text"]) ){
					$return["errors"][] = l('banner_edit_error_alt_text_empty', 'banners');
				}else{
					$this->CI->load->model('moderation/models/Moderation_badwords_model');
					$bw_count = $this->CI->Moderation_badwords_model->check_badwords($this->moderation_type, $return['data']['alt_text']);
					if($bw_count){
						$return['errors'][] = l('error_badwords_alt_text', 'banners');
					}
				}
			}

			if(isset($data["number_of_clicks"])){
				$return["data"]["number_of_clicks"] = intval($data["number_of_clicks"]);
			}

			if(isset($data["number_of_views"])){
				$return["data"]["number_of_views"] = intval($data["number_of_views"]);
			}

			if(isset($data["new_window"])){
				$return["data"]["new_window"] = intval($data["new_window"]);
			}

			if(isset($data["expiration_date_on"]) && false == $data["expiration_date_on"]) {
				$return["data"]["expiration_date"] = "0000-00-00 00:00:00";
			}elseif(isset($data["expiration_date"])){
				$return["data"]["expiration_date"] = $data["expiration_date"];
			}

			if(!empty($file_name) && isset($_FILES[$file_name]) && is_array($_FILES[$file_name]) && is_uploaded_file($_FILES[$file_name]["tmp_name"])){
				$this->CI->load->model("Uploads_model");
				$img_return = $this->CI->Uploads_model->validate_upload($this->upload_config_id, $file_name);
				if(!empty($img_return["error"])){
					if (preg_match("/size/i", $img_return["error"][0])){
						$return["errors"][] = l('error_size', 'banners');
					}else{
						$return["errors"][] = implode("<br>", $img_return["error"]);
					}
				}
			}elseif(empty($id)){
				$return["errors"][] = l('banner_edit_error_filename_empty', 'banners');
			}
		}

		//// HTML
		if($return["data"]["banner_type"] == 2){
			if(isset($data["html"])){
				$return["data"]["html"] = $data["html"];

				if(empty($return["data"]["html"]) ){
					$return["errors"][] = l('banner_edit_error_html_empty', 'banners');
				}
			}
		}


		return $return;
	}

	/**
	 * Get banner objects
	 * banners - default return all object
	 * @return array
	 */
	public function get_banners($page = 1, $items_on_page = 20, $order_by = null, $params = array(), $filter_object_ids = null){
		$objects = array();
		$this->DB->select(implode(", ", $this->fields));
		$this->DB->from(TABLE_BANNERS);

		if(isset($params["where"]) && is_array($params["where"]) && count($params["where"])){
			foreach($params["where"] as $field=>$value){
				$this->DB->where($field, $value);
			}
		}

		if(isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])){
				$this->DB->where_in($field, $value);
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
		}

		if(!is_null($page) ){
			$page = intval($page)?intval($page):1;
			$this->DB->limit($items_on_page, $items_on_page*($page-1));
		}
		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			foreach($results as $result){
				$objects[$result['id']] = $result;
			}
			$objects = $this->format_banners($objects);
		}
		return $objects;

	}

	/*
	 * Work like get_banners method, but return number of objects
	 * necessary for pagination
	 * banners - default return all object
	 */
	public function cnt_banners($params = array(), $filter_object_ids = null)
	{
		$this->DB->select("COUNT(*) AS cnt");
		$this->DB->from(TABLE_BANNERS);

		if(isset($params["where"]) && is_array($params["where"]) && count($params["where"])){
			foreach($params["where"] as $field=>$value){
				$this->DB->where($field, $value);
			}
		}

		if(isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])){
				$this->DB->where_in($field, $value);
		}

		if(isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])){
			foreach($params["where_sql"] as $value){
				$this->DB->where($value);
			}
		}

		if(isset($filter_object_ids) && is_array($filter_object_ids) && count($filter_object_ids)){
			$this->DB->where_in("id", $filter_object_ids);
		}

		$results = $this->DB->get()->result();
		if(!empty($results) && is_array($results)){
			return intval($results[0]->cnt);
		}
		return false;
	}

	public function get_banners_join_groups($page = 1, $items_on_page = 20, $order_by = null, $params = array(), $filter_object_ids = null){

		//// unset unused
		foreach($this->fields as $attr){
			$select_fields[] = TABLE_BANNERS.".".$attr;
		}

		$this->DB->select(implode(", ", $select_fields));
		$this->DB->from(TABLE_BANNERS);

		if(isset($params["where"]) && is_array($params["where"]) && count($params["where"])){
			foreach($params["where"] as $field=>$value){
				if($field == "banner_groups"){
					$this->DB->join(TABLE_BANNERS_BANNER_GROUP, " ".TABLE_BANNERS_BANNER_GROUP.".group_id='".intval($value)."' AND ".TABLE_BANNERS_BANNER_GROUP.".banner_id=".TABLE_BANNERS.".id");
				}else{
					$this->DB->where($field, $value);
				}
			}
		}

		if(isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])){
			foreach($params["where_in"] as $field=>$value){
				if($field == "banner_groups"){
					$this->DB->join(TABLE_BANNERS_BANNER_GROUP, " ".TABLE_BANNERS_BANNER_GROUP.".group_id IN (".implode(', ', $value).") AND ".TABLE_BANNERS_BANNER_GROUP.".banner_id=".TABLE_BANNERS.".id");
				}else{
					$this->DB->where_in($field, $value);
				}
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
		}

		if(!is_null($page) ){
			$page = intval($page)?intval($page):1;
			$this->DB->limit($items_on_page, $items_on_page*($page-1));
		}

		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			foreach($results as $result){
				$objects[] = $result;
			}
			$objects = $this->format_banners($objects);
			return $objects;
		}
		return false;

	}

	public function show_rotation_banners($groups_id, $place_id, $positions){
		$this->DB->select("banner_id AS id, positions, alt_text, approve, banner_image, banner_type, expiration_date, html, link, name, new_window, number_of_clicks, number_of_views, status, user_id, stat_clicks, stat_views, ".TABLE_BANNERS_BANNER_GROUP.".is_admin")->from(TABLE_BANNERS_BANNER_GROUP);
		$this->DB->join(TABLE_BANNERS, TABLE_BANNERS.'.id = '.TABLE_BANNERS_BANNER_GROUP.'.banner_id AND '.TABLE_BANNERS.'.status = 1');
		$this->DB->where_in('group_id', $groups_id);
		$this->DB->where('place_id', $place_id);
		$this->DB->order_by(TABLE_BANNERS_BANNER_GROUP.'.is_admin ASC');
		$results = $this->DB->get()->result_array();

		$banners_deactivated_users_ids = array();

		$banners = array();
		if(!empty($results) && is_array($results)){
			
			$used_positions = 0;
			$is_admin_banners = false;
			$this->CI->load->model('banners/models/Banners_stat_model');

			foreach($results as $result){
				if($used_positions <= $positions || $is_admin_banners == false){
					$used_positions+=$result["positions"];
					$is_admin_banners = $result["is_admin"]==1?true:false;

					$this->CI->Banners_stat_model->add_view($result['id']);
					$tmp_array[] = $result['id'];

					if (intval($result['number_of_clicks']) && $result["stat_clicks"] >= $result['number_of_clicks']){
						$this->save_banner_status($result['id'], 0);
						$this->delete_all_banner_group($result['id']);
						$banners_deactivated_users_ids[$result['id']] = $result['user_id'];
						continue;
					}

					if (intval($result['number_of_views']) && $result["stat_views"]+1 >= $result['number_of_views']){
						$this->save_banner_status($result['id'], 0);
						$this->delete_all_banner_group($result['id']);
						$banners_deactivated_users_ids[$result['id']] = $result['user_id'];
						continue;
					}

					// check expiration date
					if ($result['expiration_date'] and strtotime($result['expiration_date']) > 0){
						if (mktime() + 24 * 60 >= strtotime($result['expiration_date'])){
							$this->save_banner_status($result['id'], 0);
							$this->delete_all_banner_group($result['id']);
							$banners_deactivated_users_ids[$result['id']] = $result['user_id'];
							continue;
						}
					}

					$this->save_banner_views($result['id'], $result["stat_views"]+1);
					$result = $this->format_banner($result);
					for($i=1; $i<=$result["positions"]; $i++){
						$banners[] = $result;
					}
				}else{
					break;
				}
			}
	
			if(!empty($banners_deactivated_users_ids) && $this->CI->pg_module->is_module_installed("users")){
				$this->CI->load->model('Users_model');
				$this->CI->load->model("Notifications_model");
				
				$users_ids = array_unique($banners_deactivated_users_ids);
				$users = $this->CI->Users_model->get_users_list_by_key(null, null, null, null, $users_ids);
				foreach($results as $result){
					if(!isset($banners_deactivated_users_ids[$result['id']]) || !isset($users[$banners_deactivated_users_ids[$result['id']]])) continue;
					$user_data = $users[$banners_deactivated_users_ids[$result['id']]];
					$banner_data = array(
						'user' => $user_data['fname'],
						'banner' => $result['name'],
					);
					$this->CI->Notifications_model->send_notification($user_data['email'], "banner_status_expired", $banner_data, '', $user_data['lang_id']);
				}
			}
			
			return $banners;
		}

	}
	/*
	 * Count banners that wait admin approve
	 */
	public function cnt_not_approve_banners()
	{
		$params["where"]['approve'] = 0;
		return $this->cnt_banners($params);
	}

	/**
	 * Get banner object by id
	 *
	 * @param int $id
	 * @return array
	 */
	public function get($id){
		$object = array();

		if ($id){
			$this->DB->select(implode(", ", $this->fields))->from(TABLE_BANNERS)->where('id', $id);
			$results = $this->DB->get()->result_array();
			if(!empty($results) && is_array($results)){
				$object = $this->format_banner($results[0]);
				$object["banner_groups"] = $this->get_banner_group_ids($id);
			}
		}
		return $object;
	}

	public function format_banner($data){
		$formatted = $this->format_banners(array($data));
		return array_shift($formatted);
	}

	public function format_banners($data){
		if(empty($data) || !is_array($data)) return;

		if(!$this->format_settings['use_format']){
			return $data;
		}

		$user_ids = array();

		$this->CI->load->model('Uploads_model');
		foreach($data as $key => $banner){
			if(!isset($banner["expiration_date_on"])) {
				$banner["expiration_date_on"] = (strtotime($banner["expiration_date"]) > 0)?true:false;
			}
			if(!empty($banner["id"])){
				$banner["prefix"] = $banner["id"];
			}

			if(!empty($banner["banner_image"])){
				$banner["media"]["banner_image"] = $this->CI->Uploads_model->format_upload($this->upload_config_id, $banner["prefix"], $banner["banner_image"]);
			}else{
				$banner["media"]["banner_image"] = $this->CI->Uploads_model->format_default_upload($this->upload_config_id);
			}
			
			if($banner["user_id"]) $user_ids[] = $banner["user_id"];

			$data[$key] = $banner;
		}
		
		if($this->format_settings['get_user'] && !empty($user_ids)){
			$this->CI->load->model('Users_model');
			$users = $this->CI->Users_model->get_users_list_by_key(null, null, null, array(), array_unique($user_ids), false);
			$users = $this->CI->Users_model->format_users($users, true);
			if($this->format_settings['get_user']){
				foreach($data as $key=>$banner){
					if(!$banner['user_id']) continue;
					$data[$key]['user'] = (isset($users[$banner['user_id']])) ? $users[$banner['user_id']] :
						$this->CI->Users_model->format_default_user($banner['user_id']);
				}
			}
		}
		
		return $data;
	}

	/**
	 * Change format settings
	 * @param string $name parameter name
	 * @param mixed $value parameter value
	 */
	public function set_format_settings($name, $value=false){
		if(!is_array($name)) $name = array($name=>$value);
		foreach($name as $key => $item)	$this->format_settings[$key] = $item;
	}

	public function get_user_activate_info($banner_id){
		$this->DB->select("user_activate_info")->from(TABLE_BANNERS)->where('id', $banner_id);
		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			$return = unserialize($results[0]["user_activate_info"]);
		}else{
			$return = "";
		}
		return $return;
	}

	public function set_user_activate_info($banner_id, $activate_info){
		$data["user_activate_info"] = serialize($activate_info);
		$this->DB->where('id', $banner_id);
		$this->DB->update(TABLE_BANNERS, $data);
	}

	/**
	 * Delete banner object
	 *
	 * @param int $id
	 * @return bool
	 */
	public function delete($id){
		if ($id){
			$banner_data = $this->get($id);
			$this->delete_all_banner_group($id);

			$this->DB->where('id', $id);
			$this->DB->delete(TABLE_BANNERS);

			if(!empty($banner_data["banner_image"])){
				$this->CI->load->model("Uploads_model");
				$this->CI->Uploads_model->delete_upload($this->upload_config_id, $banner_data["prefix"], $banner_data["banner_image"]);
			}

			$this->DB->where('banner_id', $id);
			$this->DB->delete(TABLE_BANNERS_BANNER_GROUP);

			$this->CI->load->model('banners/models/Banners_stat_model');
			$this->CI->Banners_stat_model->delete_statistic($id);
		}
		return;
	}

	public function delete_all_banner_group($banner_id){
		$this->DB->where('banner_id', $banner_id);
		$this->DB->delete(TABLE_BANNERS_BANNER_GROUP);
		return;
	}

	public function add_banner_groups($banner_id, $banner_groups, $place_id, $is_admin, $group_position=array()){
		$this->delete_all_banner_group($banner_id);
		if(!empty($banner_groups) && count($banner_groups)>0){
			foreach($banner_groups as $group_id){
				if($group_id){
					$data = array(
						"banner_id" => intval($banner_id),
						"group_id" => intval($group_id),
						"place_id" => intval($place_id),
						"is_admin" => intval($is_admin),
						"positions" => isset($group_position[$group_id])?intval($group_position[$group_id]):1,
					);
					$this->DB->insert(TABLE_BANNERS_BANNER_GROUP, $data);
				}
			}
		}
		return;
	}

	public function get_banner_group_ids($banner_id){
		$object = array();
		$this->DB->select("group_id")->from(TABLE_BANNERS_BANNER_GROUP)->where("banner_id", $banner_id);
		$results = $this->DB->get()->result();
		if(!empty($results) && is_array($results)){
			foreach($results as $result){
				$object[] = $result->group_id;
			}
		}
		return $object;
	}

	public function update_statistic(){
		$this->CI->load->model('banners/models/Banners_stat_model');
		$date = date("Y-m-d");
		$this->CI->Banners_stat_model->update_file_statistic();
		$this->CI->Banners_stat_model->update_day_statistic($date);
		$this->CI->Banners_stat_model->update_week_statistic($date);
		$this->CI->Banners_stat_model->update_month_statistic($date);
		$this->CI->Banners_stat_model->update_year_statistic($date);
		return;
	}

	///// service functions
	public function service_validate_banner($user_id, $data, $service_data=array(), $price=''){
		$return = array("errors"=> array(), "data" => $data);
		return $return;
	}

	public function service_buy_banner($id_user, $price, $service, $template, $payment_data, $users_package_id = 0, $count = 1){
		$banner_id = $payment_data['user_data']['id_banner_payment'];
		$banner = $this->get($banner_id);
		$info = $this->get_user_activate_info($banner_id);

		if(floatval($info["sum"]) != floatval($price)){
			return false;
		}

		$group_position = $info["positions"];
		$banner_groups = array_keys($group_position);

		$this->add_banner_groups($banner_id, $banner_groups, $banner["banner_place_id"], 0, $group_position);

		$period = $this->CI->pg_module->get_module_config("banners", "period");

		$data = array(
			'expiration_date' => date('Y-m-d H:i:s', time()+$period*86400),
			'status' => 1
		);

		$this->save($banner_id, $data);
		return;
	}

	public function service_activate_banner(){

	}

	//// dynamic blocks methods
	public function _dynamic_block_banner_place_method($params=array(), $view=''){
		$this->CI->load->helper('banners');
		if(empty($params["area_gid"])) $params["area_gid"] = 'left-banner';
		$html = show_banner_place($params["area_gid"]);
		return $html;
	}

	/**
	 * Validate banners settings
	 * 
	 * @param array $data banner settings
	 * @return array
	 */
	public function validate_settings($data){
		$return = array("errors"=> array(), "data" => array());

		if(isset($data['period'])){
			$return["data"]["period"] = intval($data["period"]);
			if(empty($return["data"]["period"])){
				$return["errors"][] = l("error_empty_period", "banners");
			}
		}

		if(isset($data['moderation_send_mail'])){
			$return["data"]["moderation_send_mail"] = $data["moderation_send_mail"] ? 1 : 0;
		}

		/// email
		if(isset($data['admin_moderation_emails'])){
			$return["data"]["admin_moderation_emails"] = trim(strip_tags($data["admin_moderation_emails"]));

			if(!empty($return["data"]["admin_moderation_emails"])){
				$this->CI->config->load("reg_exps", TRUE);
				$email_expr = $this->CI->config->item("email", "reg_exps");
				$chunks = explode(',', $return["data"]["admin_moderation_emails"]);
				foreach($chunks as $chunk){
					if(empty($chunk) || !preg_match($email_expr, trim($chunk))){
						$return["errors"][] = l("error_invalid_email", "banners");
						break;
					}
				}
			}elseif($return["data"]["moderation_send_mail"]){
				$return["errors"][] = l("error_empty_email", "banners");
			}
		}

		return $return;
	}
}
