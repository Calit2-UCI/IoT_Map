<?php

/**
 * Winks admin side controller
 *
 * @package PG_DatingPro
 * @subpackage Winks
 * @category	controllers
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * */
class Winks extends Controller {

	private $_user_id;

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::Controller();
		$this->load->model('Menu_model');
		$this->load->model('Winks_model');
		if ('user' === $this->session->userdata('auth_type')) {
			$this->_user_id = intval($this->session->userdata('user_id'));
		}
	}

	public function index($page = 1) {
		$params = array(
			'where' => array(
				'id_to' => $this->_user_id,
				'type !=' => 'ignored'
			)
		);
		$winks_count = $this->Winks_model->get_count($params);
		if ($winks_count) {
			$this->load->helper('sort_order');
			$this->load->helper('navigation');
			$items_on_page = $this->pg_module->get_module_config('winks', 'items_per_page');
			$page = get_exists_page_number($page, $winks_count, $items_on_page);
			$winks = $this->Winks_model->format(
					$this->Winks_model->get($params, $page, $items_on_page, array('date' => 'DESC')));

			$this->config->load('date_formats', TRUE);
			$page_data = get_user_pages_data(site_url() . 'winks/index/', $winks_count, $items_on_page, $page, 'briefPage');
			$page_data['date_time_format'] = $this->pg_date->get_format('date_time_literal', 'st');
			$this->template_lite->assign('page_data', $page_data);
			$this->template_lite->assign('winks', $winks);
		}
		$this->Menu_model->breadcrumbs_set_active(l('winks', 'winks'));
		$this->template_lite->assign('user_id', $this->_user_id);
		$this->template_lite->view('list');
	}

	public function ajax_wink() {
		$id_to = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
		if (!$id_to) {
			log_message('error', '(winks) Empty recipient id');
			$errors[] = l('error_unauthorized', 'winks');
		} elseif ($id_to == $this->_user_id) {
			$errors[] = l('error_wink_someone_else', 'winks');
		}
		if (empty($errors)) {
			$data = array(
				'id_from' => $this->_user_id,
				'id_to' => $id_to,
				'type' => 'new'
			);
			$result = $this->Winks_model->save($data);
			if ($result) {
				echo json_encode('ok');
			} else {
				echo json_encode('error');
			}
		} else {
			echo json_encode(array('errors' => $errors));
		}
	}

	public function ajax_reply() {
		$id_to = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
		if (!$id_to) {
			log_message('error', '(winks) Empty recipient id');
			$errors[] = l('error_unauthorized', 'winks');
		}
		$type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
		if (empty($type)) {
			$type = 'replied';
		} elseif (!in_array($type, $this->Winks_model->types)) {
			log_message('error', '(winks) Wrong type');
			echo json_encode('error');
			return false;
		}
		if (empty($errors)) {
			$wink = $this->Winks_model->get_by_pair($this->_user_id, $id_to);
			$wink['id_from'] = $this->_user_id;
			$wink['id_to'] = $id_to;
			$wink['type'] = $type;
			$result = $this->Winks_model->save($wink, $wink['id']);
			if ($result) {
				echo json_encode('ok');
			} else {
				echo json_encode('error');
			}
		} else {
			echo json_encode($errors);
		}
	}

	private function _get_criteria($search_string, $except_ids = null) {
		$params = array();
		if (empty($search_string)) {
			return $params;
		}

		if ($this->pg_module->get_module_config('users', 'hide_user_names')) {
			$params['where']['nickname LIKE'] = '%' . $search_string . '%';
		} else {
			$search_string_escape = $this->db->escape('%' . $search_string . '%');
			$params['where_sql'][] = '(nickname LIKE ' . $search_string_escape
					. ' OR fname LIKE ' . $search_string_escape
					. ' OR sname LIKE ' . $search_string_escape . ')';
		}
		if (!empty($except_ids)) {
			$params['where_sql'][] = 'id NOT IN (' . implode(', ', $except_ids) . ')';
		}
		return $params;
	}

	/*private function _search_users_lists($search_string, $except_ids) {
		$return = array();
		$return['all'] = 0;

		$this->load->model('Users_lists_model');
		$friends_ids = $this->Users_lists_model->get_friendlist_users_ids($this->_user_id);
		if (empty($friends_ids)) {
			return $return;
		}
		$params = $this->_get_criteria($search_string, $except_ids);
		$params['where_in']['id'] = $friends_ids;

		$items_on_page = $this->pg_module->get_module_config('users', 'items_per_page');
		$return['all'] = $this->Users_model->get_users_count($params);
		if ($return['all']) {
			$this->load->model('Users_model');
			$return['items'] = $this->Users_model->get_users_list(1, $items_on_page, array('nickname' => 'asc'), $params, array(), true, true);
		} else {
			$return['items'] = array();
		}

		return $return;
	}*/

	private function _search_friendlist($search_string, $except_ids) {
		$return = array();
		$return['all'] = 0;
		$this->load->model('Friendlist_model');
		$friends_ids = $this->Friendlist_model->get_friendlist_users_ids($this->_user_id);
		if (empty($friends_ids)) {
			return $return;
		}
		$params = $this->_get_criteria($search_string, $except_ids);
		$params['where_in']['id'] = $friends_ids;

		$items_on_page = $this->pg_module->get_module_config('users', 'items_per_page');
		$return['all'] = $this->Users_model->get_users_count($params);
		if ($return['all']) {
			$this->load->model('Users_model');
			$return['items'] = $this->Users_model->get_users_list_by_key(1, $items_on_page, array('nickname' => 'asc'), $params, array(), true, true);
		} else {
			$return['items'] = array();
		}

		return $return;
	}

	private function _search_friends($search_string, $except_ids) {
		/*if ($this->pg_module->is_module_installed('users_lists')) {
			return $this->_search_users_lists($search_string, $except_ids);
		} else*/if ($this->pg_module->is_module_installed('friendlist')) {
			return $this->_search_friendlist($search_string, $except_ids);
		}
	}

	public function ajax_get_users_data() {
		$search_string = $this->input->post('search', true);

		$winkers_ids = $this->Winks_model->get_winkers($this->_user_id);
		$return = $this->_search_friends($search_string, $winkers_ids);
		$except_ids = array_merge($winkers_ids, array_keys($return['items']));
		$except_ids[] = $this->_user_id;
		$items_on_page = $this->pg_module->get_module_config('start', 'admin_items_per_page');
		if ($return['all'] < $items_on_page) {
			$items_on_page -= $return['all'];
			$params = $this->_get_criteria($search_string, $except_ids);

			$items = $this->Users_model->get_users_list(1, $items_on_page, array('nickname' => 'asc'), $params, array(), true, true);

			foreach ($items as $user) {
				$return['items'][] = $user;
			}
			$return['all'] += $this->Users_model->get_users_count($params);
		}
		echo json_encode($return);
		return;
	}

}
