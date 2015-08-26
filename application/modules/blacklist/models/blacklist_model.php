<?php

/**
 * Blacklist model
 *
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * */
if (!defined('BLACKLIST_TABLE')) {
	define('BLACKLIST_TABLE', DB_PREFIX . 'blacklist');
}

class Blacklist_model extends Model {

	private $CI;
	private $DB;
	private $fields = array(
		'id',
		'id_user',
		'id_dest_user',
		'date_add',
	);
	private $fields_str;

	public function __construct() {
		parent::Model();
		$this->CI = &get_instance();
		$this->DB = &$this->CI->db;
		$this->fields_str = implode(', ', $this->fields);
	}

	public function get_sitemap_urls() {
		$this->CI->load->helper('seo');
		$auth = $this->CI->session->userdata('auth_type');
		$block = array(
			array(
				'name' => l('blacklist', 'blacklist'),
				'link' => rewrite_link('blacklist', 'index'),
				'clickable' => $auth === 'user',
				'items' => array(),
			)
		);
		return $block;
	}

	public function get_sitemap_xml_urls() {
		$this->CI->load->helper('seo');
		return array();
	}

	public function request_seo_rewrite($var_name_from, $var_name_to, $value) {
		if ($var_name_from == $var_name_to) {
			return $value;
		}
	}

	public function get_seo_settings($method = '', $lang_id = '') {
		if (!empty($method)) {
			return $this->_get_seo_settings($method, $lang_id);
		} else {
			$actions = array('index', 'add', 'remove');
			$return = array();
			foreach ($actions as $action) {
				$return[$action] = $this->_get_seo_settings($action, $lang_id);
			}
			return $return;
		}
	}

	public function _get_seo_settings($method, $lang_id = '') {
		switch ($method) {
			case 'index':
				return array(
					"templates" => array(),
					"url_vars" => array(),
					'url_postfix' => array(
						'action' => array('action'=>'literal'),
						'page' => array('page'=>'numeric'),
					),
					'optional' => array(),
				);
			case 'add':
				return array(
					"templates" => array(),
					"url_vars" => array(
						'destination_user_id' => array('destination_user_id'=>'literal'),
					),
					'url_postfix' => array(),
					'optional' => array(),
				);
			case 'remove':
				return array(
					"templates" => array(),
					"url_vars" => array(
						'destination_user_id' => array('destination_user_id'=>'literal'),
					),
					'url_postfix' => array(),
					'optional' => array(),
				);
		}
	}

	public function _banner_available_pages() {
		return array(
			array("link" => "blacklist/index", "name" => l('blacklist', 'blacklist')),
		);
	}

	private function _set_callback($type, $id_user, $id_dest_user) {
		$this->CI->load->model('blacklist/models/Blacklist_callbacks_model');
		$this->CI->Blacklist_callbacks_model->execute_callbacks($type, $id_user, $id_dest_user);
	}

	private function _delete($params) {
		$this->DB->where($params)->delete(BLACKLIST_TABLE);
		return $this->DB->affected_rows();
	}

	private function _get($params, $page = null, $items_on_page = null, $order_by = null) {
		if (!empty($params["where"]) && is_array($params["where"])) {
			$this->DB->where($params["where"]);
		}

		if (!empty($params["where_in"]) && is_array($params["where_in"])) {
			foreach ($params["where_in"] as $field => $value) {
				$this->DB->where_in($field, $value);
			}
		}

		if (is_array($order_by) && count($order_by)) {
			foreach ($order_by as $field => $dir) {
				if (in_array($field, $this->fields)) {
					$this->DB->order_by($field, $dir);
				}
			}
		}

		if (!is_null($page) && !is_null($items_on_page)) {
			$page = intval($page) > 0 ? intval($page) : 1;
			$this->DB->limit($items_on_page, $items_on_page * ($page - 1));
		}

		return $this->DB->select($this->fields_str)->from(BLACKLIST_TABLE)->get()->result_array();
	}

	public function add($id_user, $id_dest_user) {
		$data = array(
			'id_user' => $id_user,
			'id_dest_user' => $id_dest_user,
			'date_add' => date('Y-m-d H:i:s')
		);
		$this->DB->ignore()->query($this->DB->insert_string(BLACKLIST_TABLE, $data));
		$this->_set_callback('blacklist_add', $id_user, $id_dest_user);
		return true;
	}

	public function remove($id_user, $id_dest_user) {
		$this->_delete(array(
			'id_user' => $id_user,
			'id_dest_user' => $id_dest_user));
		$this->_set_callback('blacklist_remove', $id_user, $id_dest_user);
		return true;
	}

	/**
	 *
	 * @param type $id_user int
	 * @param type $id_dest_user int
	 * @param type $field string, 'user' - determine if dest_user is blocked by user, 'dest_user' - if user is blocked by dest_user
	 * @return boolean
	 */
	public function is_blocked($id_user, $id_dest_user) {
		return (bool)$this->_get(array(
			'where' => array(
				'id_user' => $id_user,
				'id_dest_user' => $id_dest_user,
			)
		));
	}

	/**
	 * Return user list
	 * @param type $id_user int
	 * @param type $page int
	 * @param type $items_on_page int
	 * @param type $order_by array
	 * @param type $search string
	 * @param boolean $formatted boolean
	 * @return type array
	 */
	public function get_list($id_user = null, $page = null, $items_on_page = null, $order_by = null, $search = '', $formatted = true) {
		$list_params = $this->_get_list_params($id_user);
		if ($search) {
			$list = $this->_get($list_params['params'], null, null, $order_by);
			$formatted = true;
		} else {
			$list = $this->_get($list_params['params'], $page, $items_on_page, $order_by);
		}

		if ($formatted) {
			$list = $this->_format_list($list, $list_params['user_field'], $search);
			if ($search && $page && $items_on_page) {
				$list = array_slice($list, ($page - 1) * $items_on_page, $items_on_page);
			}
		}

		return $list;
	}

	public function get_list_users_ids($id_user) {
		$list_params = $this->_get_list_params($id_user);
		$list = $this->_get($list_params['params']);
		$ids = array();
		foreach ($list as $list_entry) {
			$ids[] = $list_entry[$list_params['user_field']];
		}
		return $ids;
	}

	public function get_list_count($id_user = null, $search = '') {
		$count = 0;
		$list_params = $this->_get_list_params($id_user);
		$user_field = $list_params['user_field'];
		$params = $list_params['params'];
		if ($search) {
			$list = $this->_get($params);
			$user_ids = array();
			foreach ($list as $key => $l) {
				$user_ids[$l[$user_field]] = $l[$user_field];
			}
			if ($user_ids) {
				$criteria = $this->_get_search_criteria($search);
				$this->CI->load->model('Users_model');
				$count = $this->CI->Users_model->get_users_count($criteria, $user_ids);
			}
		} else {
			if (!empty($params['where']) && is_array($params['where'])) {
				$this->DB->where($params['where']);
			}
			if (!empty($params['where_in']) && is_array($params['where_in'])) {
				foreach ($params['where_in'] as $field => $value) {
					$this->DB->where_in($field, $value);
				}
			}
			$count = $this->DB->count_all_results(BLACKLIST_TABLE);
		}

		return intval($count);
	}

	private function _get_list_params($id_user = null) {
		$result['user_field'] = 'id_dest_user';
		$where_user_field = 'id_user';
		if ($id_user) {
			$result['params']['where'][$where_user_field] = $id_user;
		}
		return $result;
	}

	private function _format_list($list, $user_field = 'id_user', $search = '') {
		$user_ids = $users = array();
		foreach ($list as $key => $l) {
			$user_ids[$l[$user_field]] = $l[$user_field];
		}
		$this->CI->load->model('Users_model');
		if ($user_ids) {
			$criteria = $search ? $this->_get_search_criteria($search) : array();
			$users = $this->CI->Users_model->get_users_list_by_key(null, null, null, $criteria, $user_ids);
		}
		$users[0] = $this->CI->Users_model->format_default_user(1);
		foreach ($list as $key => &$l) {
			$l['user_field'] = $user_field;
			if (!empty($users[$l[$user_field]])) {
				$l['user'] = $users[$l[$user_field]];
			} elseif ($search) {
				unset($list[$key]);
			} else {
				$l['user'] = $users[0];
			}
		}
		return $list;
	}

	private function _get_search_criteria($search) {
		$search = trim(strip_tags($search));
		$this->CI->load->model('Users_model');
		$this->CI->load->model('Field_editor_model');
		$this->CI->Field_editor_model->initialize($this->CI->Users_model->form_editor_type);
		$temp_criteria = $this->CI->Field_editor_model->return_fulltext_criteria($search);
		$criteria = array(
			'fields' => array(
				$temp_criteria['user']['field']
			),
			'where_sql' => array(
				$temp_criteria['user']['where_sql']
			)
		);
		return $criteria;
	}

}
