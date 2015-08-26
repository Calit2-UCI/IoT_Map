<?php

/**
 * Winks admin side controller
 *
 * @package PG_DatingPro
 * @subpackage Winks
 * @category	controllers
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * */
class Admin_Winks extends Controller {

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::Controller();
		$this->load->model('Menu_model');
		$this->Menu_model->set_menu_active_item('admin_menu', 'winks_menu_item');
	}

	public function index() {
		$this->load->model('Winks_model');
		$winks = $this->Winks_model->get_list();
		$this->template_lite->assign('winks', $winks);
		$this->system_messages->set_data('header', l('admin_header_winks_list', 'winks'));
		$this->template_lite->view('list');
	}

}
