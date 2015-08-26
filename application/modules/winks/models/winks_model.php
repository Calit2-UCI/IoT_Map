<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

define('WINKS_TABLE', DB_PREFIX . 'winks');

/**
 * Winks model
 * 
 * @package PG_DatingPro
 * @subpackage Winks
 * @category	models
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * */
class Winks_model extends Model {

	/**
	 * Link to Code Igniter object
	 */
	protected $CI;

	/**
	 * Table fields
	 * @var array
	 */
	private $_fields = array(
		'id',
		'id_from',
		'id_to',
		'type',
		'date',
	);

	public $types = array('new', 'replied', 'ignored');

	/**
	 * Constructor
	 *
	 * @return Widgets_model
	 */
	public function __construct() {
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;
	}

	public function delete($user1, $user2) {
		$this->DB->where('id_from', $user1)
				->where('id_to', $user2)
				->or_where('id_from', $user2)
				->where('id_to', $user1)
				->delete(WINKS_TABLE);
	}

	public function save($data, $id = null) {
		if (empty($id)) {
			if (empty($data['id_from'])) {
				log_message('error', '(winks) Empty sender id');
				return false;
			} elseif (empty($data['id_to'])) {
				log_message('error', '(winks) Empty recipient id');
				return false;
			} /*elseif('replied' === $data['type']) {
				$this->DB->where('id_from', $data['id_from'])
						->where('id_to', $data['id_to'])
						->delete(WINKS_TABLE);
				return true;
			}*/

			if(empty($data['date'])) {
				$data['date'] = date('Y-m-d H:i:s');
			}
			foreach($data as $field => $val){
				$fields_upd[] = "`$field` = " . $this->DB->escape($val);
			}
			$update_str = implode(', ', $fields_upd);
			$sql = $this->DB->insert_string(WINKS_TABLE, $data) . " ON DUPLICATE KEY UPDATE {$update_str}";
			$this->DB->query($sql);
		} else {
			if(!empty($data['type']) && !in_array($data['type'], $this->types)) {
				log_message('error', '(winks) Wrong type');
				return false;
			}
			$this->DB->where('id', $id)
					->update(WINKS_TABLE, $data);
		}
		return true;
	}

	public function get_winkers($user_id) {
		$winks = $this->DB->select('id_from, id_to')
							->from(WINKS_TABLE)
							->where('id_to', $user_id)
							->or_where('id_from', $user_id)
							->where('type !=', 'ignored')
							->get()->result_array();
		$user_ids = array();
		foreach($winks as $wink) {
			if($wink['id_from'] == $user_id) {
				$user_ids[] = (int)$wink['id_to'];
			} else {
				$user_ids[] = (int)$wink['id_from'];
			}
		}
		return $user_ids;
	}

	public function get_by_pair($user1, $user2) {
		return array_shift($this->DB->select(implode(', ', $this->_fields))
						->from(WINKS_TABLE)
						->where('id_from', $user1)
						->where('id_to', $user2)
						->or_where('id_from', $user2)
						->where('id_to', $user1)
						->get()->result_array());
	}

	public function get($params = array(), $page = 1, $items_on_page = 20, $order_by = null, $filter_object_ids = null) {
		$this->DB->select(implode(', ', $this->_fields))->from(WINKS_TABLE);

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

		if (is_array($order_by) && count($order_by) > 0) {
			foreach ($order_by as $field => $dir) {
				if (in_array($field, $this->_fields)) {
					$this->DB->order_by($field . ' ' . $dir);
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
	 * Return number of winks
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
		return $this->DB->count_all_results(WINKS_TABLE);
	}
	
	/*public function get_user_list($user_id, $page, $items_on_page, $order_by) {
		$result = array('count' => 0, 'list' => array());

		$params = array(
			'where_sql' => array("(id_to = '$user_id' AND type != 'ignored') OR "
						. "(id_from = '$user_id' AND type = 'replied')")
		);
		$result['count'] = $this->Winks_model->get_count($params);
		if($result['count']) {
			$result['list'] = $this->get($params, $page, $items_on_page, $order_by);
		}
	}*/

	public function format($winks) {
		$user_ids = array();
		foreach($winks as $wink) {
			if(!in_array($wink['id_from'], $user_ids)) {
				$user_ids[] = $wink['id_from'];
			}
			if(!in_array($wink['id_to'], $user_ids)) {
				$user_ids[] = $wink['id_to'];
			}
		}
		if($user_ids) {
			$users = $this->CI->Users_model->get_users_list_by_key(null, null, null, array(), $user_ids, true);
			foreach($winks as &$wink) {
				$wink['from'] = $users[$wink['id_from']];
				$wink['to'] = $users[$wink['id_to']];
			}
		}
		return $winks;
	}
	
	public function backend_winks_count(){
		$params = array(
			'where' => array(
				'id_to' => $this->CI->session->userdata('user_id'),
				'type !=' => 'ignored'
			),
		);
		$winks_count = $this->get_count($params);
		return array('count' => $winks_count);
	}

	// seo
	function get_seo_settings($method = '', $lang_id = '') {
		if (!empty($method)) {
			return $this->_get_seo_settings($method, $lang_id);
		} else {
			$actions = array('index');
			$return = array();
			foreach ($actions as $action) {
				$return[$action] = $this->_get_seo_settings($action, $lang_id);
			}
			return $return;
		}
	}

	function _get_seo_settings($method, $lang_id = '') {
		if ($method == 'index') {
			return array(
				'title' => l('seo_tags_index_title', 'winks', $lang_id, 'seo'),
				'keyword' => l('seo_tags_index_keyword', 'winks', $lang_id, 'seo'),
				'description' => l('seo_tags_index_description', 'winks', $lang_id, 'seo'),
				'templates' => array(),
				'header' => l('seo_tags_index_header', 'winks', $lang_id, 'seo'),
				'url_vars' => array(),
				'url_postfix' => array(
					'page' => array('page'=>'numeric'),
				),
			);
		}
	}

	function request_seo_rewrite($var_name_from, $var_name_to, $value) {
		if ($var_name_from == $var_name_to) {
			return $value;
		}
	}

	function get_sitemap_xml_urls() {
		$this->CI->load->helper('seo');
		if ($this->CI->pg_module->is_module_installed('seo')) {
			$lang_canonical = $this->CI->pg_module->get_module_config('seo', 'lang_canonical');
		} else {
			$lang_canonical = true;
		}
		if ($lang_canonical) {
			$default_lang_id = $this->CI->pg_language->get_default_lang_id();
			$default_lang_code = $this->CI->pg_language->get_lang_code_by_id($default_lang_id);
			$langs[$default_lang_id] = $default_lang_code;
		} else {
			foreach ($this->CI->pg_language->languages as $lang_id => $lang_data) {
				$langs[$lang_id] = $lang_data['code'];
			}
		}
		$return = array();
		$user_settings = $this->pg_seo->get_settings('user', 'winks', 'index');
		if (!$user_settings['noindex']) {
			foreach ($langs as $lang_id => $lang_code) {
				$return[] = array(
					'url' => rewrite_link('winks', 'index', array(), false, $lang_code, $lang_canonical),
					'priority' => 0.8
				);
			}
		}

		return $return;
	}

	function get_sitemap_urls() {
		$this->CI->load->helper('seo');
		$auth = $this->CI->session->userdata('auth_type');
		$block = array();
		if('user' === $auth) {
			$block[] = array(
				'name' => l('header_winks', 'winks'),
				'link' => rewrite_link('winks', 'index'),
				'clickable' => true,
			);
		}
		return $block;
	}
	
	/**
     *  Module category action
     *  @return array
     */
    public function moduleCategoryAction()
	{
		$action = array(
			'name' 	 => l('wink', 'winks'),
			'helper' => 'wink',
		);
		return $action;
	}

}
