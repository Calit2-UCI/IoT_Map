<?php

/**
 * Users lists controller
 *
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * */
class Favourites extends Controller {

	private $_tabs = array('i_am_their_fav', 'my_favs');

	public function __construct() {
		parent::Controller();
		$this->load->model('Favourites_model');
		$this->load->model('Menu_model');
	}

	private function _view($tab = 'my_favs', $page = 1) {
		if (!in_array($tab, $this->_tabs)) {
			show_404();
		}
		$list = array();
		$user_id = $this->session->userdata('user_id');
		$search = $this->input->post('search', true);
		$incoming = $tab === 'i_am_their_fav';
		if ($search) {
			$this->session->set_userdata('favourites_search', $search);
		} else {
			$search = $this->session->set_userdata('favourites_search', $search);
		}
		$order_by['date_add'] = 'DESC';
		$items_count = $this->Favourites_model->get_list_count($user_id, $search, $incoming);

		$items_on_page = $this->pg_module->get_module_config('users', 'items_per_page');
		$this->load->helper('sort_order');
		$page = get_exists_page_number(intval($page), $items_count, $items_on_page);
		if ($items_count) {
			$list = $this->Favourites_model->get_list($user_id, $page, $items_on_page, $order_by, $search, true, $incoming);
		}
		$url = site_url() . 'favourites/' . $tab . '/';
		$this->load->helper('navigation');
		$page_data = get_user_pages_data($url, $items_count, $items_on_page, $page, 'briefPage');
		$page_data['date_format'] = $this->pg_date->get_format('date_literal', 'st');
		$page_data['date_time_format'] = $this->pg_date->get_format('date_time_literal', 'st');
		if ($search) {
			$items_count = $this->Favourites_model->get_list_count($user_id, '', $incoming);
		}
		$this->Menu_model->breadcrumbs_set_active(l('favourites', 'favourites'));
		$this->template_lite->assign('count', $items_count);
		$this->template_lite->assign('search', $search);
		$this->template_lite->assign('tab', $tab);
		$this->template_lite->assign('page_data', $page_data);
		$this->template_lite->assign('list', $list);
		$this->template_lite->view('list');
	}

	public function index($page = 1) {
		$this->my_favs($page);
	}

	public function i_am_their_fav($page = 1) {
		$this->_view('i_am_their_fav', $page);
	}

	public function my_favs($page = 1) {
		$this->_view('my_favs', $page);
	}

	public function add($id_dest_user, $ajax = false) {
		$id_user = $this->session->userdata('user_id');
		$result = $this->Favourites_model->add($id_user, intval($id_dest_user));
		if ($ajax) {
			return $result;
		} else {
			redirect(site_url() . 'favourites/index/');
		}
		return true;
	}

	public function remove($id_dest_user, $ajax = false) {
		$id_user = $this->session->userdata('user_id');
		$result = $this->Favourites_model->remove($id_user, intval($id_dest_user));
		if ($ajax) {
			return $result;
		} else {
			redirect(site_url() . 'favourites/index/');
		}
		return true;
	}

	public function ajax_add($id_dest_user) {
		if ($this->add($id_dest_user, true)) {
			$result = array('success' => l('success_favourites_add', 'favourites'));
		} else {
			$result = array('errors' => 'error');
		}
		exit(json_encode($result));
	}

	public function ajax_remove($id_dest_user) {
		if ($this->remove($id_dest_user, true)) {
			$result = array('success' => l('success_favourites_remove', 'favourites'));
		} else {
			$result = array('errors' => 'error');
		}
		exit(json_encode($result));
	}

}
