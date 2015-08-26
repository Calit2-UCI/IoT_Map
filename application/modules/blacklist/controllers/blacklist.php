<?php

/**
 * Users lists controller
 *
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * */
class Blacklist extends Controller {

	public function __construct() {
		parent::Controller();
		$this->load->model('Blacklist_model');
		$this->load->model('Menu_model');
	}

	public function index($action = 'view', $page = 1) {
		$list = array();
		$user_id = $this->session->userdata('user_id');
		$action = trim(strip_tags($action));
		$is_search = $this->input->post('search', true) !== false;
		$search = $this->input->post('search', true);
		if ($is_search) {
			$action = 'search';
			$this->session->set_userdata('blacklist_search', $search);
		}
		if ($action == 'search') {
			$search = $this->session->userdata('blacklist_search');
		}

		$order_by['date_add'] = 'DESC';

		$items_count = $this->Blacklist_model->get_list_count($user_id, $search);
		$items_on_page = $this->pg_module->get_module_config('users', 'items_per_page');
		$this->load->helper('sort_order');
		$page = get_exists_page_number(intval($page), $items_count, $items_on_page);

		if ($items_count) {
			$list = $this->Blacklist_model->get_list($user_id, $page, $items_on_page, $order_by, $search);
		}

		$url = site_url() . "blacklist/index/$action";
		$this->load->helper('navigation');
		$page_data = get_user_pages_data($url, $items_count, $items_on_page, $page, 'briefPage');
		$page_data['date_format'] = $this->pg_date->get_format('date_literal', 'st');
		$page_data['date_time_format'] = $this->pg_date->get_format('date_time_literal', 'st');
		
		$this->Menu_model->breadcrumbs_set_active(l('blacklist', 'blacklist'));
		$this->template_lite->assign('count', $this->Blacklist_model->get_list_count($user_id));
		$this->template_lite->assign('search', $search);
		$this->template_lite->assign('page_data', $page_data);
		$this->template_lite->assign('list', $list);
		$this->template_lite->view('list');
	}

	public function add($id_dest_user, $ajax = false) {
		$id_user = $this->session->userdata('user_id');
		$result = $this->Blacklist_model->add($id_user, intval($id_dest_user));
		if ($ajax) {
			return $result;
		} else {
			redirect(site_url() . 'blacklist/index/');
		}
		return true;
	}

	public function remove($id_dest_user, $ajax = false) {
		$id_user = $this->session->userdata('user_id');
		$result = $this->Blacklist_model->remove($id_user, intval($id_dest_user));
		if ($ajax) {
			return $result;
		} else {
			redirect(site_url() . 'blacklist/index/');
		}
		return true;
	}

	public function ajax_add($id_dest_user) {
		if ($this->add($id_dest_user, true)) {
			$result = array('success' => l('success_blacklist_add', 'blacklist'));
		} else {
			$result = array('errors' => 'error');
		}
		exit(json_encode($result));
	}

	public function ajax_remove($id_dest_user) {
		if ($this->remove($id_dest_user, true)) {
			$result = array('success' => l('success_blacklist_remove', 'blacklist'));
		} else {
			$result = array('error' => 'error');
		}
		exit(json_encode($result));
	}

}
