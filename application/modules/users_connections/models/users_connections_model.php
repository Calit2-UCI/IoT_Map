<?php
/**
* User connections(social networking) model
* 
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

if (!defined('USER_CONNECTIONS_TABLE'))
	define('USER_CONNECTIONS_TABLE', DB_PREFIX . 'user_connections');

class Users_connections_model extends Model {

	var $CI;
	var $DB;
	var $fields_all = array(
		'id',
		'user_id',
		'service_id',
		'access_token',
		'access_token_secret',
		'data',
		'date_end'
	);

	function Users_connections_model() {
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;
	}

	function save_connection($connection_id = false, $data = array()) {
		if (!$connection_id) {
			$this->DB->insert(USER_CONNECTIONS_TABLE, $data);
			$connection_id = $this->DB->insert_id();
		} else {
			$this->DB->where('id', $connection_id);
			$this->DB->update(USER_CONNECTIONS_TABLE, $data);
		}
		return $connection_id;
	}

	function get_connection_by_user_id($service_id = false, $user_id = false) {
		$data = array();
		$select_attrs = $this->fields_all;
		$this->DB->select(implode(", ", $select_attrs))->from(USER_CONNECTIONS_TABLE)->where(array('service_id' => $service_id, 'user_id' => $user_id))->order_by('id ASC');
		$result = $this->DB->get()->result_array();
		if (!empty($result)) {
			$data = $result[0];
		}
		return $data;
	}

	function get_connection_by_id($connection_id = false) {
		$data = array();
		$select_attrs = $this->fields_all;
		$this->DB->select(implode(", ", $select_attrs))->from(USER_CONNECTIONS_TABLE)->where(array('id' => $connection_id))->order_by('id ASC');
		$result = $this->DB->get()->result_array();
		if (!empty($result)) {
			$data = $result[0];
		}
		return $data;
	}

	function get_connection_by_data($service_id = false, $odata = false) {
		$data = array();
		$select_attrs = $this->fields_all;
		$this->DB->select(implode(", ", $select_attrs))->from(USER_CONNECTIONS_TABLE)->where(array('service_id' => $service_id, 'data' => $odata))->order_by('id ASC');
		$result = $this->DB->get()->result_array();
		if (!empty($result)) {
			$data = $result[0];
		}
		return $data;
	}

	function delete_connection($connection_id = false) {
		$data = $this->get_connection_by_id($connection_id);
		if (empty($data))
			return false;
		$this->DB->where('id', $connection_id);
		$this->DB->delete(USER_CONNECTIONS_TABLE);

		return;
	}
	
	public function delete_user_connections($user_id){
		$this->DB->where('user_id', $user_id)->delete(USER_CONNECTIONS_TABLE);
		return;
	}
}
