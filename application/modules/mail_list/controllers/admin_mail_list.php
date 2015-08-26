<?php
Class Admin_Mail_list extends Controller {

	private $_date_format = null;
	private $_items_on_page = null;

	/**
	 * Constructor
	 */
	function __construct() {
		parent::Controller();
		$this->load->model(array('Menu_model', 'Mail_list_model'));
		$this->Menu_model->set_menu_active_item('admin_menu', 'content_items');

		// Date format
		$this->_date_format = $this->pg_date->get_format('date_time_literal', 'st');

		$this->_items_on_page = $this->pg_module->get_module_config('start', 'admin_items_per_page');
		$this->system_messages->set_data('header', l('mailing_lists', 'mail_list'));
		return true;
	}

	public function index($filter = 'all', $page = 1) {
		$this->users($filter, $page);
		return true;
	}

	public function users($filter = 'all', $page = 1, $user_type = 0) {

		// Set default filter
		if (!in_array($filter, array('all', 'subscribed', 'not_subscribed'))) {
			// ...or page if filter isn't specified
			if ($page < (intval($filter))) {
				$page = $filter;
			}
			$filter = 'all';
		}
		$page = floor($page);
		if ($page < 1) {
			$page = 1;
		}

		$this->load->model('Subscriptions_model');
		$subscriptions = $this->Subscriptions_model->get_subscriptions_list();

		// Clear settings if btn_cancel pressed
		if ($this->input->post('btn_cancel', true)) {
			$current_settings = array();
		} else {
			// Apply filter if sent
			if ($this->input->post('id_filter', true)) {
				$_SESSION['mail_list'] =
					$this->Mail_list_model->get_filter($this->input->post('id_filter', true));
			}
			if (isset($_SESSION['mail_list'])) {
				$current_settings = $_SESSION['mail_list'];
			} else {
				$current_settings = array();
			}
			$fields = array('email', 'name', 'date', 'user_type',
							'id_country', 'id_region', 'id_city');

			// Fill current_settings with values from $_POST
			foreach($fields as $field) {
				// We do need identity comparison
				if(false !== $this->input->post($field, true)) {
					$current_settings[$field] = $this->input->post($field, true);
				}
			}
		}

		if(false !== $this->input->post('id_subscription', true)) {
			$current_settings['id_subscription'] = $this->input->post('id_subscription', true);
		} elseif(!$current_settings['id_subscription']) {
			// We need subscription id, so if $_POST doesn't contain it, we take first one from database
			$current_settings['id_subscription'] = $subscriptions[0]['id'];
		}
		$current_settings['filter'] = $filter;
		
		// Select user_type
		$this->load->model('Properties_model');
		$user_types = $this->Properties_model->get_property('user_type');
		$this->template_lite->assign('user_types', $user_types);
		$this->template_lite->assign('user_type', $user_type);
		
		
		$current_settings['page']	= $page;

		$search_attrs = $this->Mail_list_model->format_search_attrs($current_settings);
		$users = $this->Mail_list_model->get_users($search_attrs, $page, $this->_items_on_page);

		// Save settings for further use
		$_SESSION['mail_list'] = $current_settings;
		$_SESSION['mail_list']['search_attrs'] = $search_attrs;

		// Count users
		unset($search_attrs['where']['id_subscription']);
		unset($search_attrs['where_not']['id_subscription']);
		$users_count['all'] = $this->Mail_list_model->get_users_count($search_attrs);
		$search_attrs['where']['id_subscription'] = $current_settings['id_subscription'];
		$users_count['subscribed'] = $this->Mail_list_model->get_users_count($search_attrs);
		$users_count['not_subscribed'] = $users_count['all'] - $users_count['subscribed'];

		// Pagination
		$this->load->helper('navigation');
		$url = site_url() . "admin/mail_list/users/$filter/";
		$page_data = get_admin_pages_data($url, $users_count[$filter], $this->_items_on_page, $page, 'briefPage');

		$this->pg_theme->add_js('admin-mail-list.js', 'mail_list');
		$this->template_lite->assign('users_count', $users_count);
		$this->template_lite->assign('page_data', $page_data);
		$this->template_lite->assign('data', $current_settings);
		$this->template_lite->assign('date_format', $this->_date_format);
		$this->template_lite->assign('users', $users);
		$this->template_lite->assign('subscriptions', $subscriptions);

		$this->template_lite->view('users');
		return true;
	}

	public function filters($page = 1) {
		$filters = $this->Mail_list_model->get_filters($page, $this->_items_on_page);
		$filters_count = $this->Mail_list_model->get_filters_count();

		// Pagination
		$this->load->helper('navigation');
		$url = site_url() . 'admin/mail_list/filters/';
		$page_data = get_admin_pages_data($url, $filters_count, $this->_items_on_page, $page, 'briefPage');

		// Select user_type
		$this->load->model('Properties_model');
		$user_types = $this->Properties_model->get_property('user_type');
		$this->template_lite->assign('user_types', $user_types);
		
		$this->pg_theme->add_js('admin-mail-list.js', 'mail_list');
		$this->template_lite->assign('filters', $filters);
		$this->template_lite->assign('page_data', $page_data);
		$this->template_lite->assign('date_format', $this->_date_format);
		$this->template_lite->view('filters');
		return true;
	}

	public function ajax_save_filter() {
		$fields = array('email', 'name', 'date', 'user_type',
			'id_subscription', 'id_country', 'id_region', 'id_city');

		// Fill $data with values from $_POST
		foreach($fields as $field) {
			if($this->input->post($field, true)) {
				$data[$field] = $this->input->post($field, true);
			}
		}

		if(is_array($data)) {
			$this->Mail_list_model->save_filter($data);
		}
		return true;
	}

	public function ajax_delete_filter() {
		$id_filter = $this->input->post('id_filter', true);
		echo $this->Mail_list_model->delete_filter($id_filter);
		return true;
	}

	public function ajax_subscribe() {
		$action = $this->input->post('action', true);
		$id_subscription = (int)$this->input->post('id_subscription', true);
		switch($action) {
			case 'subscribe_one' :
				$id_user = array($this->input->post('id_user', true));
				echo $this->Mail_list_model->subscribe_users($id_subscription, $id_user);
				break;
			case 'unsubscribe_one' :
				$id_user = array($this->input->post('id_user', true));
				echo $this->Mail_list_model->unsubscribe_users($id_subscription, $id_user);
				break;
			case 'subscribe_selected' :
				$id_users = $this->input->post('id_users', true);
				echo $this->Mail_list_model->subscribe_users($id_subscription, $id_users);
				break;
			case 'unsubscribe_selected' :
				$id_users = $this->input->post('id_users', true);
				echo $this->Mail_list_model->unsubscribe_users($id_subscription, $id_users);
				break;
			case 'subscribe_all' :
				$id_users = $this->Mail_list_model->get_users($_SESSION['mail_list']['search_attrs'], null, null, true);
				echo $this->Mail_list_model->subscribe_users($id_subscription, $id_users);
				break;
			case 'unsubscribe_all' :
				$id_users = $this->Mail_list_model->get_users($_SESSION['mail_list']['search_attrs'], null, null, true);
				echo $this->Mail_list_model->unsubscribe_users($id_subscription, $id_users);
				break;
		}
		return true;
	}

}