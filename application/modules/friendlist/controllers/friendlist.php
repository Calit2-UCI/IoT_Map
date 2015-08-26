<?php

/**
 * Friendlist controller
 *
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * */
class Friendlist extends Controller {

	private $list_types = array(
		'friendlist' => 'accept',
		'friends_requests' => 'request_in',
		'friends_invites' => 'request_out',
	);
	private $list_methods;

	public function __construct() {
		parent::Controller();
		$this->load->model('Friendlist_model');
		foreach ($this->list_types as $method => $type) {
			$this->list_methods[$type] = $method;
		}
	}

	private function _list_block($type = 'accept', $action = 'view', $page = 1) {
		$list = array();
		$user_id = $this->session->userdata('user_id');
		$action = trim(strip_tags($action));
		$is_search = $this->input->post('search', true) !== false;
		$search = $this->input->post('search', true);
		if ($is_search) {
			$action = 'search';
			$this->session->set_userdata('friendlist_search', $search);
		}
		if ($action == 'search') {
			$search = $this->session->userdata('friendlist_search');
		}

		$order_by['date_update'] = 'DESC';

		$items_count = $this->Friendlist_model->get_list_count($user_id, $type, $search);
		$items_on_page = $this->pg_module->get_module_config('users', 'items_per_page');
		$this->load->helper('sort_order');
		$page = intval($page) < 1 ? 1 : get_exists_page_number(intval($page), $items_count, $items_on_page);

		if ($items_count) {
			$list = $this->Friendlist_model->get_list($user_id, $type, $page, $items_on_page, $order_by, $search);
		}

		$url = site_url() . "friendlist/{$this->list_methods[$type]}/{$action}/";
		$this->load->helper('navigation');
		$page_data = get_user_pages_data($url, $items_count, $items_on_page, $page, 'briefPage');
		$page_data['date_format'] = $this->pg_date->get_format('date_literal', 'st');
		$page_data['date_time_format'] = $this->pg_date->get_format('date_time_literal', 'st');

		$this->template_lite->assign('counts', $this->_get_counts());
		$this->template_lite->assign('search', $search);
		$this->template_lite->assign('page_data', $page_data);
		$this->template_lite->assign('list', $list);
		$this->template_lite->assign('type', $type);
		$this->template_lite->assign('method', $this->list_methods[$type]);
		$this->template_lite->view('friendlist');
	}

	public function index($action = 'view', $page = 1) {
		$this->load->model('Menu_model');
		$this->Menu_model->breadcrumbs_set_parent('friendlist_item');
		$this->_list_block($this->list_types['friendlist'], $action, $page);
	}

	public function friends_requests($action = 'view', $page = 1) {
		$this->load->model('Menu_model');
		$this->Menu_model->breadcrumbs_set_parent('friendlist_item');
		$this->_list_block($this->list_types['friends_requests'], $action, $page);
	}

	public function friends_invites($action = 'view', $page = 1) {
		$this->load->model('Menu_model');
		$this->Menu_model->breadcrumbs_set_parent('friendlist_item');
		$this->_list_block($this->list_types['friends_invites'], $action, $page);
	}

	private function _get_helper_links_html($id_dest_user) {
		$id_user = $this->session->userdata('user_id');
		$statuses = $this->Friendlist_model->get_statuses($id_user, $id_dest_user);
		$buttons = array();
		foreach ($statuses['allowed_btns'] as $btn => $params) {
			if ($params['allow']) {
				$buttons[$btn] = $params;
			}
		}
		$this->template_lite->assign('id_user', $id_user);
		$this->template_lite->assign('id_dest_user', $id_dest_user);
		$this->template_lite->assign('buttons', $buttons);
		return $this->template_lite->fetch('helper_lists_links', 'user', 'friendlist');
	}

	public function request($id_dest_user, $ajax = false) {
		$id_user = $this->session->userdata('user_id');
		$comment = $this->input->post('comment', true);
		$result = $this->Friendlist_model->request($id_user, intval($id_dest_user), $comment);
		if (!empty($result['errors'])) {
			return $result;
		}
		if ($result['status'] === 'request') {
			// send notification
			$this->load->model('Notifications_model');
			$this->load->model('Users_model');
			$dest_user_data = $this->Users_model->get_user_by_id($id_dest_user);
			$user_data = $this->Users_model->format_user($this->Users_model->get_user_by_id($id_user));
			$notification_data['fname'] = $dest_user_data['fname'];
			$notification_data['sname'] = $dest_user_data['sname'];
			$notification_data['user'] = $user_data['output_name'];
			$notification_data['comment'] = $comment;
			$this->Notifications_model->send_notification($dest_user_data['email'], 'friends_request', $notification_data, '', $dest_user_data['lang_id']);
			if (!$ajax) {
				$this->system_messages->add_message('success', l('friends_request_send', 'friendlist'));
			}
		}
		if ($ajax) {
			return $result;
		} else {
			redirect(site_url() . 'friendlist/friends_invites/');
		}
		return;
	}

	public function ajax_request($id_dest_user) {
		$result = $this->request($id_dest_user, true);
		$result['html'] = $this->_get_helper_links_html($id_dest_user);
		exit(json_encode($result));
	}

	public function ajax_request_block($id_dest_user) {
		$id_dest_user = intval($id_dest_user);
		$this->template_lite->assign('id_dest_user', $id_dest_user);
		$this->template_lite->assign('request_max_chars', $this->pg_module->get_module_config('friendlist', 'request_max_chars'));
		exit($this->template_lite->fetch('request_block', 'user', 'friendlist'));
	}

	public function accept($id_dest_user, $ajax = false) {
		$id_user = $this->session->userdata('user_id');
		$result = $this->Friendlist_model->accept($id_user, intval($id_dest_user));
		if ($ajax) {
			return $result;
		} else {
			redirect(site_url() . 'friendlist/friends_requests/');
		}
		return;
	}

	public function ajax_accept($id_dest_user) {
		$result = $this->accept($id_dest_user, true);
		$result['html'] = $this->_get_helper_links_html($id_dest_user);
		exit(json_encode($result));
	}

	public function decline($id_dest_user, $ajax = false) {
		$id_user = $this->session->userdata('user_id');
		$result = $this->Friendlist_model->decline($id_user, intval($id_dest_user));
		if ($ajax) {
			return $result;
		} else {
			redirect(site_url() . 'friendlist/friends_requests/');
		}
		return;
	}

	public function ajax_decline($id_dest_user) {
		$result = $this->decline($id_dest_user, true);
		$result['html'] = $this->_get_helper_links_html($id_dest_user);
		exit(json_encode($result));
	}

	public function remove($id_dest_user, $ajax = false) {
		$id_user = $this->session->userdata('user_id');
		$result = $this->Friendlist_model->remove($id_user, intval($id_dest_user));
		if ($ajax) {
			return $result;
		} else {
			$redirect_method = $result['redirect'] ? $result['redirect'] : 'index';
			redirect(site_url() . "friendlist/$redirect_method/");
		}
		return;
	}

	public function ajax_remove($id_dest_user) {
		$result = $this->remove($id_dest_user, true);
		$result['html'] = $this->_get_helper_links_html($id_dest_user);
		exit(json_encode($result));
	}

	private function _get_counts() {
		$id_user = $this->session->userdata('user_id');
		$counts = array();
		foreach ($this->list_types as $method => $type) {
			$counts[$method] = $this->Friendlist_model->get_list_count($id_user, $type);
		}
		return $counts;
	}

	/**
	 * Return friends by ajax
	 * @return array
	 */
	public function ajax_get_friends_data() {
		$return = array();

		$user_id = $this->session->userdata('user_id');
		$friends_ids = $this->Friendlist_model->get_friendlist_users_ids($user_id);

		if (!empty($friends_ids)) {
			$params = array('where_in' => array('id' => $friends_ids));

			$search_string = $this->input->post('name', true);
			if (!empty($search_string)) {
				if ($this->pg_module->get_module_config('users', 'hide_user_names')) {
					$params['where']['nickname LIKE'] = "%$search_string%";
				} else {
					$search_string_escape = $this->db->escape("%$search_string%");
					$params['where_sql'][] = "(nickname LIKE $search_string_escape OR fname LIKE $search_string_escape OR sname LIKE $search_string_escape)";
				}
			}

			$selected = array_slice(array_unique(
							array_map('intval', (array) $this->input->post('selected', true))), 0, 1000);
			if (!empty($selected)) {
				$params['where_not_in'][] = $selected;
			}

			$user_type = intval($this->input->post('user_type', true));
			if ($user_type) {
				$params['where']['user_type'] = $user_type;
			}

			$page = intval($this->input->post('page', true));
			if (!$page) {
				$page = 1;
			}
			$items_on_page = 100;

			$this->load->model('Users_model');
			$items = $this->Users_model->get_users_list_by_key($page, $items_on_page, array('nickname' => 'asc'), $params, array(), true, true);

			$return['all'] = $this->Users_model->get_users_count($params);
			$return['items'] = $items;
			$return['current_page'] = $page;
			$return['pages'] = ceil($return['all'] / $items_on_page);
		}

		echo json_encode($return);
		return;
	}

	/**
	 * Return selected friends by ajax
	 * @param integer $page page of results
	 * @return array
	 */
	public function ajax_get_selected_friends() {
		$return = array();

		$selected = array_slice(array_unique(
						array_map('intval', (array) $this->input->post('selected', true))), 0, 1000);
		if (!empty($selected)) {
			$user_id = $this->session->userdata('user_id');
			$friends_ids = $this->Friendlist_model->get_friendlist_users_ids($user_id);
			$selected = array_intersect($selected, $friends_ids);
			if (!empty($selected)) {
				$this->load->model('Users_model');
				$return = $this->Users_model->get_users_list_by_key(null, null, array('nickname' => 'asc'), array(), $selected, true, true);
			}
		}

		echo json_encode($return);
		return;
	}

	/**
	 * Return friends from by ajax
	 * @param integer $max_select
	 * @return array
	 */
	public function ajax_get_friends_form($max_select = 1) {
		$data = array();

		$selected = $this->input->post('selected', true);
		if (!empty($selected)) {
			$user_id = $this->session->userdata('user_id');
			$friends_ids = $this->Friendlist_model->get_friendlist_users_ids($user_id);
			$selected = array_intersect($selected, $friends_ids);
			$this->load->model('Users_model');
			$selected_users = $this->Users_model->get_users_list_by_key(null, null, array('nickname' => 'asc'), array(), $selected);
			$data['selected'] = $selected_users;
		} else {
			$data['selected'] = array();
		}
		$data['max_select'] = $max_select ? $max_select : 0;

		$this->template_lite->assign('select_data', $data);
		$this->template_lite->view('ajax_friend_select_form');
	}

}
