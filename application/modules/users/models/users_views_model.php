<?php

namespace Pg\Modules\Users\Models;

/**
* Users views model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Dmitry Popenov
* @version $Revision: 2 $ $Date: 2013-04-11 15:07:07 +0300 $ $Author: dpopenov $
**/

if (!defined('BASEPATH')) exit('No direct script access allowed');

define('VIEWS_TABLE', DB_PREFIX.'users_views');
define('VIEWERS_TABLE', DB_PREFIX.'users_viewers');

class Users_views_model extends \Model
{
	private $CI;
	private $DB;
	private $fields_views = array(
		'id_user',
		'id_viewer',
		'view_date'
	);
	private $fields_viewers = array(
		'id_user',
		'id_viewer',
		'view_date'
	);
	private $field_date = 'view_date';


	public function __construct()
	{
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;
	}

	public function update_views($id_user, $id_viewer){
		if(!$id_user && !$id_viewer){
			return false;
		}
		$params['id_user'] = intval($id_user);
		$params['id_viewer'] = intval($id_viewer);
		$params['view_date'] = date("Y-m-d");
		$sql = $this->DB->insert_string(VIEWS_TABLE, $params) . " ON DUPLICATE KEY UPDATE `view_date`='{$params['view_date']}'";
		$this->DB->query($sql);
		
		$params['view_date'] = date("Y-m-d H:i:s");
		$sql = $this->DB->insert_string(VIEWERS_TABLE, $params) . " ON DUPLICATE KEY UPDATE `view_date`='{$params['view_date']}'";
		$this->DB->query($sql);
		
		$views_count = $this->get_viewers_count_daily($id_user);
		$this->CI->load->model('Users_model');
		$this->CI->Users_model->save_user($id_user, array('views_count' => $views_count));
		return true;
	}

	public function get_viewers_count_unique($id_user){
		$params['id_user'] = intval($id_user);
		return $this->_get_views_count('unique', $params);
	}
	
	public function get_views_count_unique($id_user){
		$params['id_viewer'] = intval($id_user);
		return $this->_get_views_count('unique', $params);
	}

	/**
	 * 
	 * @param type $id_user
	 * @param type $page
	 * @param type $items_on_page
	 * @param type $order_by
	 * @param array $params
	 * @return array who view me unique for all time
	 */
	public function get_viewers_unique($id_user, $page = null, $items_on_page = null, $order_by = null, $params = array()){
		$params['where']['id_user'] = intval($id_user);
		$result = $this->_get_views('unique', $page, $items_on_page, $order_by, $params);
		return $result;
	}

	/**
	 * 
	 * @param type $id_user
	 * @param type $page
	 * @param type $items_on_page
	 * @param type $order_by
	 * @param array $params
	 * @return array who i view unique for all time
	 */
	public function get_views_unique($id_user, $page = null, $items_on_page = null, $order_by = null, $params = array()){
		$params['where']['id_viewer'] = intval($id_user);
		$result = $this->_get_views('unique', $page, $items_on_page, $order_by, $params);
		return $result;
	}
	
	public function get_viewers_count_daily($id_user, $period = 'all'){
		$params['id_user'] = intval($id_user);
		return $this->_get_views_count('daily', $params, $period);
	}
	
	public function get_views_count_daily($id_user, $period = 'all'){
		$params['id_viewer'] = intval($id_user);
		return $this->_get_views_count('daily', $params, $period);
	}

	public function backend_get_viewers_count(){
		$user_id = $this->CI->session->userdata('user_id');
		return array('count' => count($this->get_viewers_daily_unique($user_id, null, null, array('view_date' => 'DESC') ,array(), 'all')));
	}

	private function _get_views_count($type = 'unique', $params, $period = 'all'){
		if($type == 'unique'){
			$db_table = VIEWERS_TABLE;
		}else{
			$date_field = $this->field_date;
			$db_table = VIEWS_TABLE;
			$dates = $this->_get_period_dates($period);
			if(!empty($dates['from'])){
				$params["{$date_field} >="] = $dates['from'];
			}
			if(!empty($dates['to'])){
				$params["{$date_field} <="] = $dates['to'];
			}
		}

		$result = $this->DB->where($params)->from($db_table)->count_all_results();
		return $result;
	}
	
	private function _get_period_dates($period){
		$dates = array();
		switch($period){
			case 'today': 
				$dates['from'] = $dates['to'] = date("Y-m-d");
				break;
			case 'week': 
				$dates['from'] = date("Y-m-d", time() - 3600*24*7);
				$dates['to'] = date("Y-m-d");
				break;
			case 'month': 
				$dates['from'] = date("Y-m-d", time() - 3600*24*30);
				$dates['to'] = date("Y-m-d");
				break;
			case 'all':
			default:
				break;
		}
		return $dates;
	}

	/**
	 * 
	 * @param type $id_user
	 * @param type $page
	 * @param type $items_on_page
	 * @param type $order_by
	 * @param array $params
	 * @return array who view me unique for every day
	 */
	public function get_viewers_daily($id_user, $page = null, $items_on_page = null, $order_by = null, $params = array(), $period = 'all'){
		$params['where']['id_user'] = intval($id_user);
		$result = $this->_get_views('daily', $page, $items_on_page, $order_by, $params, $period);
		return $result;
	}

	/**
	 * 
	 * @param type $id_user
	 * @param type $page
	 * @param type $items_on_page
	 * @param type $order_by
	 * @param array $params
	 * @return array who i view unique for every day
	 */
	public function get_views_daily($id_user, $page = null, $items_on_page = null, $order_by = null, $params = array(), $period = 'all'){
		$params['where']['id_viewer'] = intval($id_user);
		$result = $this->_get_views('daily', $page, $items_on_page, $order_by, $params, $period);
		return $result;
	}

	public function get_views_daily_unique($id_user, $page = null, $items_on_page = null, $order_by = null, $params = array(), $period = 'all'){
		if($period == 'all'){
			return $this->get_views_unique($id_user, $page, $items_on_page, $order_by, $params);
		}
		
		$views_daily = $this->get_views_daily($id_user, null, null, $order_by, $params, $period);
		$views_ids = array();
		foreach($views_daily as $view){
			$views_ids[$view['id_user']] = $view['id_user'];
		}
		$result = array();
		if($views_ids){
			$params['where_in']['id_user'] = $views_ids;
			$result = $this->get_views_unique($id_user, $page, $items_on_page, $order_by, $params);
		}
		return $result;
	}
	
	public function get_viewers_daily_unique($id_user, $page = null, $items_on_page = null, $order_by = null, $params = array(), $period = 'all'){
		if($period == 'all'){
			return $this->get_viewers_unique($id_user, $page, $items_on_page, $order_by, $params);
		}
		
		$viewers_daily = $this->get_viewers_daily($id_user, null, null, $order_by, $params, $period);
		$viewers_ids = array();
		foreach($viewers_daily as $viewer){
			$viewers_ids[$viewer['id_viewer']] = $viewer['id_viewer'];
		}
		$result = array();
		if($viewers_ids){
			$params['where_in']['id_viewer'] = $viewers_ids;
			$result = $this->get_viewers_unique($id_user, $page, $items_on_page, $order_by, $params);
		}
		return $result;
	}
	
	public function get_viewers_count_daily_unique($id_user, $period = 'all'){
		if($period == 'all'){
			return $this->get_viewers_count_unique($id_user);
		}
		
		$viewers_daily = $this->get_viewers_daily($id_user, null, null, null, array(), $period);
		$viewers_ids = array();
		foreach($viewers_daily as $viewer){
			$viewers_ids[$viewer['id_viewer']] = $viewer['id_viewer'];
		}
		return count($viewers_ids);
	}
	
	public function get_views_count_daily_unique($id_user, $period = 'all'){
		if($period == 'all'){
			return $this->get_views_count_unique($id_user);
		}
		
		$viewers_daily = $this->get_views_daily($id_user, null, null, null, array(), $period);
		$viewers_ids = array();
		foreach($viewers_daily as $viewer){
			$viewers_ids[$viewer['id_user']] = $viewer['id_user'];
		}
		return count($viewers_ids);
	}
	
	private function _get_views($type = 'unique', $page = null, $items_on_page = null, $order_by = null, $params = array(), $period = 'all'){
		if($type == 'unique'){
			$db_fields = $this->fields_viewers;
			$db_table = VIEWERS_TABLE;
		}else{
			$db_fields = $this->fields_views;
			$date_field = $this->field_date;
			$db_table = VIEWS_TABLE;
			$dates = $this->_get_period_dates($period);
			if(!empty($dates['from'])){
				$this->DB->where("{$date_field} >=", $dates['from']);
			}
			if(!empty($dates['to'])){
				$this->DB->where("{$date_field} <=", $dates['to']);
			}
		}
		$this->DB->select(implode(", ", $db_fields))->from($db_table);
		
		if (isset($params["where"]) && is_array($params["where"]) && count($params["where"])) {
			foreach ($params["where"] as $field => $value) {
				$this->DB->where($field, $value);
			}
		}

		if (isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])) {
			foreach ($params["where_in"] as $field => $value) {
				$this->DB->where_in($field, $value);
			}
		}

		if (isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])) {
			foreach ($params["where_sql"] as $value) {
				$this->DB->where($value, null, false);
			}
		}

		if (is_array($order_by) && count($order_by) > 0) {
			foreach ($order_by as $field => $dir) {
				if (in_array($field, $db_fields)) {
					$this->DB->order_by($field . " " . $dir);
				}
			}
		}

		if (!is_null($page)) {
			$page = intval($page) ? intval($page) : 1;
			$this->DB->limit($items_on_page, $items_on_page * ($page - 1));
		}
		$result = $this->DB->get()->result_array();

		return $result;
	}
}