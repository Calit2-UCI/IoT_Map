<?php

/**
 * Chats model
 *
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @author Alexander Batukhtin <abatukhtin@pilotgroup.net>
 * */
require_once('chat_abstract.php');

class Cometchat extends Chat_abstract {

	private $_admin_url = 'admin/index.php';
	private $_install_url = 'install.php';

	protected $_name = 'Cometchat';
	protected $_gid = 'cometchat';
	protected $_activities = array('include');
	protected $_settings = array();

	public function __construct() {
		parent::__construct();
		$this->CI->load->model('Chats_model');
		$this->_dir = SITE_VIRTUAL_PATH . $this->CI->Chats_model->path . $this->get_gid() . '/';
	}

	public function user_page() {
		return false;
	}

	public function include_block() {
		$this->template_lite->assign('chat', $this->as_array());
		$this->template_lite->assign('url', $this->_dir . $this->_admin_url);
		return $this->template_lite->fetch('cometchat_include', 'user', 'chats');
	}

	public function admin_page() {
		$this->template_lite->assign('chat', $this->as_array());
		$this->template_lite->assign('url', $this->_dir . $this->_admin_url);
		$this->template_lite->assign('width', 1014);
		$this->template_lite->assign('height', 737);
		return $this->template_lite->fetch('cometchat_admin', 'admin', 'chats');
	}

	public function install_page() {
		$this->template_lite->assign('chat', $this->as_array());
		$this->template_lite->assign('url', $this->_dir . $this->_install_url);
		$this->template_lite->assign('width', 380);
		$this->template_lite->assign('height', 456);
		return $this->template_lite->fetch('cometchat_install', 'admin', 'chats');
	}

	public function validate_settings() {
		return true;
	}

}
