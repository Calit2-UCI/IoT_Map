<?php

/**
 * Chats controller
 *
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @author Alexander Batukhtin <abatukhtin@pilotgroup.net>
 * */
class Chats extends Controller {

	/**
	 * Controller
	 */
	public function __construct() {
		parent::Controller();
		$this->load->model('Chats_model');
	}

	public function call($chat_gid, $method_name) {
		$method_name = 'call_' . $method_name;
		$chat = $this->Chats_model->get($chat_gid);
		if (!$chat || !method_exists($chat, $method_name)) {
			show_404();
		} else {
			if (func_num_args() > 2) {
				$args = array_slice(func_get_args(), 2);
			} else {
				$args = null;
			}
			call_user_func_array(array($chat, $method_name), $args);
		}
	}

	public function index($subpage = '') {
		$chat = $this->Chats_model->get_active();
		if (empty($chat)) {
			show_404();
		}
		$this->Menu_model->breadcrumbs_set_active(l('chat', 'chats'));
		$this->template_lite->assign('chat_block', $chat->user_page($subpage));
		$this->template_lite->view('chat');
	}

}
