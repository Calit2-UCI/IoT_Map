<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

define('WIDGETS_TABLE', DB_PREFIX . 'widgets');

/**
 * Widgets model
 * 
 * @package PG_DatingPro
 * @subpackage Widgets
 * @category	models
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * */
class Widgets_model extends Model {

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
		'gid',
		'module',
		'url',
		'size',
		'colors',
		'settings',
		'date_created',
		'date_modified',
	);

	/**
	 * Format settings
	 * @var array
	 */
	private $format_settings = array(
		'use_format' => true,
		'get_content' => false,
	);

	/**
	 * Constructor
	 *
	 * @return Widgets_model
	 */
	public function __construct() {
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;
		foreach ($this->CI->pg_language->languages as $id => $value) {
			$this->_fields[] = 'title_' . $value['id'];
			$this->_fields[] = 'footer_' . $value['id'];
		}
	}

	/**
	 * Get widget by ID
	 * @param integer $widget_id widget identifier
	 * @return array
	 */
	public function get_widget_by_id($widget_id) {
		$this->DB->select(implode(', ', $this->_fields));
		$this->DB->from(WIDGETS_TABLE);
		$this->DB->where('id', $widget_id);
		$results = $this->DB->get()->result_array();
		if (!empty($results) && is_array($results)) {
			return $this->format_widget($results[0]);
		}
		return array();
	}

	/**
	 * Get widget by GUID
	 * @param string $widget_gid widget guid
	 * @return array
	 */
	public function get_widget_by_gid($widget_gid, $url = null) {
		$this->DB->select(implode(', ', $this->_fields));
		$this->DB->from(WIDGETS_TABLE);
		$this->DB->where('gid', $widget_gid);
		if (!empty($url)) {
			$this->DB->where('(url=' . $this->DB->escape($url) . ' OR url=' . $this->DB->escape('') . ')');
		}
		$this->DB->order_by('url DESC');
		$results = $this->DB->get()->result_array();
		if (!empty($results) && is_array($results)) {
			return $this->format_widget($results[0]);
		}
		return array();
	}

	/**
	 * Return search criteria
	 * @param array $filters filters data
	 */
	private function _get_search_criteria($filters) {
		$params = array();

		$fields = array_flip($this->_fields);
		foreach ($filters as $filter_name => $filter_data) {
			if (!$filter_data) {
				continue;
			}
		}

		return $params;
	}

	/**
	 * Return widgets as array
	 * @param integer $page page of results
	 * @param string $limits results limit
	 * @param array $order_by sorting order
	 * @param array $params filter criteria
	 * @param boolean $formatted results formatting
	 * @return array
	 */
	private function _get_widgets_list($page = null, $limits = null, $order_by = null, $params = array(), $formatted = true) {
		$this->DB->select(implode(', ', $this->_fields));
		$this->DB->from(WIDGETS_TABLE);

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

		if (isset($params['where_not_in']) && is_array($params['where_not_in']) && count($params['where_not_in'])) {
			foreach ($params['where_not_in'] as $field => $value) {
				$this->DB->where_not_in($field, $value);
			}
		}

		if (isset($params['where_sql']) && is_array($params['where_sql']) && count($params['where_sql'])) {
			foreach ($params['where_sql'] as $value) {
				$this->DB->where($value);
			}
		}

		if (is_array($order_by) && count($order_by) > 0) {
			foreach ($order_by as $field => $dir) {
				if (in_array($field, $this->_fields)) {
					$this->DB->order_by($field . ' ' . $dir);
				}
			}
		} elseif ($order_by) {
			$this->DB->order_by($order_by);
		}

		if (!is_null($page)) {
			$page = intval($page) ? intval($page) : 1;
			$this->DB->limit($limits, $limits * ($page - 1));
		}

		$results = $this->DB->get()->result_array();
		if (!empty($results) && is_array($results)) {
			if ($formatted) {
				$results = $this->format_widgets($results);
			}
			return $results;
		}
		return array();
	}

	/**
	 * Return number of widgets
	 * @param array $params filters criteria
	 * @return integer
	 */
	private function _get_widgets_count($params = null) {
		$this->DB->select('COUNT(*) AS cnt');
		$this->DB->from(WIDGETS_TABLE);

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
				$this->DB->where($value);
			}
		}

		$results = $this->DB->get()->result_array();
		if (!empty($results) && is_array($results)) {
			return intval($results[0]['cnt']);
		}
		return 0;
	}

	/**
	 * Return list of filtered widgets
	 * @param array $filters filter criteria
	 * @param integer $page page of results
	 * @param integer $items_on_page items per page
	 * @param string $order_by sorting order
	 * @param boolean $formatted results formatting
	 */
	public function get_widgets_list($filters = array(), $page = null, $items_on_page = null, $order_by = null, $formatted = true) {
		$params = $this->_get_search_criteria($filters);
		return $this->_get_widgets_list($page, $items_on_page, $order_by, $params, $formatted);
	}

	/**
	 * Return number of filtered widgets
	 * @param array $filters filter criteria
	 * @return array
	 */
	public function get_widgets_count($filters = array()) {
		$params = $this->_get_search_criteria($filters);
		return $this->_get_widgets_count($params);
	}

	/**
	 * Save widget
	 * @param integer $widget_id widget identifier
	 * @param array $data widget data
	 * @return integer 
	 */
	public function save_widget($widget_id, $data) {
		if (!$widget_id) {
			$data['date_created'] = $data['date_modified'] = date('Y-m-d H:i:s');
			$this->DB->insert(WIDGETS_TABLE, $data);
			$widget_id = $this->DB->insert_id();
		} else {
			$data['date_modified'] = date('Y-m-d H:i:s');
			$this->DB->where('id', $widget_id);
			$this->DB->update(WIDGETS_TABLE, $data);
		}
		return $widget_id;
	}

	/**
	 * Remove widget
	 * @param string $widget_gid widget guid
	 * @param array $data widget data
	 */
	public function delete_widget($widget_gid) {
		$this->DB->where('gid', $widget_gid);
		$this->DB->delete(WIDGETS_TABLE);
	}

	/**
	 * Validate widget data
	 * @param integer $widget_id widget identifier
	 * @param array $data
	 * @return array
	 */
	public function validate_widget($widget_id, $data) {
		$return = array('errors' => array(), 'data' => array());

		if (isset($data['id'])) {
			$return['data']['id'] = intval($data['id']);
			if (empty($return['data']['id'])) {
				unset($return['data']['id']);
			}
		}

		if (isset($data['gid'])) {
			$return['data']['gid'] = trim(strip_tags($data['gid']));
			if (empty($return['data']['gid'])) {
				unset($return['data']['gid']);
			}
		}

		if (isset($data['module'])) {
			$return['data']['module'] = trim(strip_tags($data['module']));
		}

		if (isset($data['model'])) {
			$return['data']['model'] = trim(strip_tags($data['model']));
		}

		if (isset($data['title'])) {
			$return['data']['title'] = trim(strip_tags($data['title']));
		}

		if (isset($data['footer'])) {
			$return['data']['footer'] = trim(strip_tags($data['footer']));
		}

		if (isset($data['size'])) {
			$return['data']['size'] = intval($data['size']);
		}

		if (isset($data['colors'])) {
			$return['data']['colors'] = serialize($data['colors']);
		}

		if (isset($data['settings'])) {
			$return['data']['settings'] = serialize($data['settings']);
		}

		if (isset($data['date_created'])) {
			$value = strtotime($data['date_created']);
			if ($value > 0) {
				$return['data']['date_created'] = date('Y-m-d', $value);
			} else {
				$return['data']['date_created'] = '0000-00-00 00:00:00';
			}
		}

		if (isset($data['date_modified'])) {
			$value = strtotime($data['date_modified']);
			if ($value > 0) {
				$return['data']['date_modified'] = date('Y-m-d', $value);
			} else {
				$return['data']['date_modified'] = '0000-00-00 00:00:00';
			}
		}

		$default_lang_id = $this->CI->pg_language->current_lang_id;
		if (isset($data['title_' . $default_lang_id])) {
			$return['data']['title_' . $default_lang_id] = trim(strip_tags($data['title_' . $default_lang_id]));
			foreach ($this->pg_language->languages as $lid => $lang_data) {
				if ($lid == $default_lang_id) {
					continue;
				}
				if (!isset($data['title_' . $lid]) || empty($data['title_' . $lid])) {
					$return['data']['title_' . $lid] = $return['data']['title_' . $default_lang_id];
				} else {
					$return['data']['title_' . $lid] = trim(strip_tags($data['title_' . $lid]));
				}
			}
		}

		$default_lang_id = $this->CI->pg_language->current_lang_id;
		if (isset($data['footer_' . $default_lang_id])) {
			$return['data']['footer_' . $default_lang_id] = trim(strip_tags($data['footer_' . $default_lang_id]));
			foreach ($this->pg_language->languages as $lid => $lang_data) {
				if ($lid == $default_lang_id) {
					continue;
				}
				if (!isset($data['footer_' . $lid]) || empty($data['footer_' . $lid])) {
					$return['data']['footer_' . $lid] = $return['data']['footer_' . $default_lang_id];
				} else {
					$return['data']['footer_' . $lid] = trim(strip_tags($data['footer_' . $lid]));
				}
			}
		}

		return $return;
	}

	/**
	 * Change format settings
	 * @param string $name parameter name
	 * @param mixed $value parameter value
	 */
	public function set_format_settings($name, $value = false) {
		if (!is_array($name)) {
			$name = array($name => $value);
		}
		if (empty($name)) {
			return;
		}
		foreach ($name as $key => $item) {
			$this->format_settings[$key] = $item;
		}
	}

	/**
	 * Format widgets data
	 * @param array $data set of widgets
	 * @return array
	 */
	public function format_widget($data) {
		return array_shift($this->format_widgets(array($data)));
	}

	/**
	 * Format widgets data
	 * @param array $data set of widgets
	 * @return array
	 */
	public function format_widgets($data) {
		if (!$this->format_settings['use_format']) {
			return $data;
		}

		foreach ($data as $key => $widget) {
			$widget['title'] = $widget['title_' . $this->pg_language->current_lang_id];
			$widget['footer'] = $widget['footer_' . $this->pg_language->current_lang_id];
			$widget['colors'] = $widget['colors'] ? unserialize($widget['colors']) : array();
			$widget['settings'] = $widget['settings'] ? unserialize($widget['settings']) : array();

			if ($this->format_settings['get_content']) {
				if ($this->CI->pg_module->is_module_installed($widget['module'])) {
					$model_name = ucfirst($widget['gid']);
					$this->CI->load->model($widget['module'] . '/widgets/' . $model_name, $model_name);
					$widget['content'] = $this->CI->{$model_name}->generate($widget['settings']);
				}
			}

			$data[$key] = $widget;
		}

		return $data;
	}

	/**
	 * Add widgets language fields
	 * @param integer $lang_id language identifier
	 */
	public function lang_dedicate_module_callback_add($lang_id = false) {
		if (!$lang_id)
			return;

		$this->CI->load->dbforge();

		$field = array('title_' . $lang_id => array('type' => 'TEXT', 'null' => TRUE));
		$this->CI->dbforge->add_column(WIDGETS_TABLE, $field);

		$field = array('footer_' . $lang_id => array('type' => 'TEXT', 'null' => TRUE));
		$this->CI->dbforge->add_column(WIDGETS_TABLE, $field);

		$default_lang_id = $this->CI->pg_language->get_default_lang_id();
		if ($lang_id != $default_lang_id) {
			$this->CI->db->set('title_' . $lang_id, 'title_' . $default_lang_id, false);
			$this->CI->db->set('footer_' . $lang_id, 'footer_' . $default_lang_id, false);
			$this->CI->db->update(WIDGETS_TABLE);
		}
	}

	/**
	 * Remove widgets language fields
	 * @param integer $lang_id language identifier
	 */
	public function lang_dedicate_module_callback_delete($lang_id = false) {
		if (!$lang_id) {
			return;
		}
		$this->CI->load->dbforge();

		$table_query = $this->CI->db->get(WIDGETS_TABLE);
		$fields_exists = $table_query->list_fields();

		$fields = array('title_' . $lang_id, 'footer_' . $lang_id);
		foreach ($fields as $field_name) {
			if (!in_array($field_name, $fields_exists)) {
				continue;
			}
			$this->CI->dbforge->drop_column(WIDGETS_TABLE, $field_name);
		}
	}

}
