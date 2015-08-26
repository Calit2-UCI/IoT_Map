<?php

/**
 * Admin chats controller
 *
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @author Alexander Batukhtin <abatukhtin@pilotgroup.net>
 * */
class Admin_Chats extends Controller {

	public function __construct() {
		parent::Controller();
		$this->load->model('Menu_model');
		$this->load->model('Chats_model');
		$this->Menu_model->set_menu_active_item('admin_menu', 'add_ons_items');
	}

	public function index() {
		$chats = $this->Chats_model->get_list(true);
		$this->system_messages->set_data('header', l('admin_header_chats', 'chats'));
		$this->template_lite->assign('chats', $chats);
		$this->template_lite->view('list');
	}

	public function settings($gid, $subpage = '') {
		$chat = $this->Chats_model->get($gid);
		if ($this->input->post('btn_save')) {
			$post_data = filter_input_array(INPUT_POST, array('settings' => array('flags' => FILTER_REQUIRE_ARRAY)));
			$chat->set_settings($post_data['settings']);
			if ($chat->validate_settings()) {
				$this->Chats_model->save($chat);
			}
		}
		$this->template_lite->assign('chat_block', $chat->admin_page($subpage));
		$this->template_lite->view('view');
	}

	public function activate($gid) {
		$this->Chats_model->set_active($gid, true);
		redirect(site_url() . 'admin/chats');
	}

	public function deactivate($gid) {
		$this->Chats_model->set_active($gid, false);
		redirect(site_url() . 'admin/chats');
	}

	public function installation($gid) {
		$chat = $this->Chats_model->get($gid);
		$tpl = $chat->install_page();
		if (is_null($tpl)) {
			redirect(site_url() . 'admin/chats');
		} else {
			$this->template_lite->assign('chat_block', $tpl);
			$this->template_lite->view('install');
		}
	}

	public function set_install($gid) {
		$this->Chats_model->set_installed($gid);
		redirect(site_url() . 'admin/chats');
	}

}
