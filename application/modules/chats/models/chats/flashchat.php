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

define('FLASHCHAT_TABLE', DB_PREFIX . '123flashchat');

class Flashchat extends Chat_abstract {

	const FREE_HOST = 'http://free.123flashchat.com/freechat.php';
	const ADMIN_FILE = 'admin_123flashchat.swf';
	const LOGIN_URL = 'chats/call/flashchat/login/%username%/%password%';
	const SERVER_HOSTED_BY_USER = 'by_user';
	const SERVER_HOSTED_BY_FLASHCHAT = 'by_flashchat';
	const SERVER_HOSTED_BY_FLASHCHAT_FREE = 'by_flashchat_free';

	protected $_name = '123Flashchat';
	protected $_gid = 'flashchat';
	protected $_activities = array('own_page');
	protected $_settings = array(
		'chat_server_mode' => self::SERVER_HOSTED_BY_FLASHCHAT_FREE,
		'server_settings' => array(
			self::SERVER_HOSTED_BY_USER => array(
				'server_type' => 'flashchat',
				'client_type' => 'htmlchat',
				'init_host' => '',
				'init_port' => 51127,
				'init_port_h' => 35555,
				'client_location' => '',
			),
			self::SERVER_HOSTED_BY_FLASHCHAT => array(
				'client_location' => '',
				'client_type' => 'htmlchat',
			),
			self::SERVER_HOSTED_BY_FLASHCHAT_FREE => array(
				'client_location' => self::FREE_HOST,
				'chat_room' => '',
				'skin' => '',
				'lang' => '',
			),
		),
	);

	public function __construct() {
		parent::__construct();
		$this->CI->load->model('Chats_model');
	}

	public function include_block() {
		return false;
	}

	private function _get_user_url() {
		$chat_server_mode = $this->_settings['chat_server_mode'];
		$url = $client_location = $this->_settings['server_settings'][$chat_server_mode]['client_location'];

		if (self::SERVER_HOSTED_BY_FLASHCHAT_FREE !== $chat_server_mode) {
			$server_type = isset($this->_settings['server_settings'][$chat_server_mode]['server_type']) ? $this->_settings['server_settings'][$chat_server_mode]['server_type'] : '';
			$client_type = isset($this->_settings['server_settings'][$chat_server_mode]['client_type']) ? $this->_settings['server_settings'][$chat_server_mode]['client_type'] : '';

			$url .= ($server_type == 'ppvsoftware') ? 'htmlchat/123flashchat.html' : ( ($client_type == 'flashchat') ? '123flashchat.swf' : 'htmlchat/123flashchat.html');
		}

		if (self::SERVER_HOSTED_BY_USER === $chat_server_mode) {
			$url.= "?init_host=" . $this->_settings['server_settings'][$chat_server_mode]['init_host'];
			$url.= "&init_port=" . $this->_settings['server_settings'][$chat_server_mode]['init_port'];
			$url.= "&init_host_h=" . $this->_settings['server_settings'][$chat_server_mode]['init_host'];
			$url.= "&init_port_h=" . $this->_settings['server_settings'][$chat_server_mode]['init_port_h'];
			$url.= "&init_group=default";
			$url.= "&init_root=" . $client_location;
		}

		if (self::SERVER_HOSTED_BY_FLASHCHAT === $chat_server_mode) {
			$client_location_str = substr($client_location, 7, -1);
			$client_location_array = explode('/', $client_location_str);
			$url.= "?init_host=" . $client_location_array[0];

			$serverinfo = $this->_getServerHostPortHost($client_location);
			$url.= "&init_port=" . (isset($serverinfo['fc_server_port']) ? $serverinfo['fc_server_port'] : "");
			$url.= "&init_host_s=" . (isset($serverinfo['fc_server_host_s']) ? $serverinfo['fc_server_host_s'] : "");
			$url.= "&init_port_s=" . (isset($serverinfo['fc_server_port_s']) ? $serverinfo['fc_server_port_s'] : "");
			$url.= "&init_host_h=" . (isset($serverinfo['fc_server_host_h']) ? $serverinfo['fc_server_host_h'] : "");
			$url.= "&init_port_h=" . (isset($serverinfo['fc_server_port_h']) ? $serverinfo['fc_server_port_h'] : "");
			$url.= "&init_group=" . $client_location_array[1];
		}

		if (self::SERVER_HOSTED_BY_FLASHCHAT_FREE === $chat_server_mode) {
			$url.= "?room=" . urlencode($this->_settings['server_settings'][$chat_server_mode]['chat_room']);
			$url.= "&skin=" . $this->_settings['server_settings'][$chat_server_mode]['skin'];
			$url.= "&lang=" . (($this->_settings['server_settings'][$chat_server_mode]['lang'] == '*') ? "auto" : $this->_settings['server_settings'][$chat_server_mode]['lang']);
			$url.= "&width=100%";
		}

		if (self::SERVER_HOSTED_BY_FLASHCHAT_FREE !== $chat_server_mode) {
			$user_info = $this->_getUserInfo();
			$url .= (!empty($user_info)) ? '&init_user=' . $user_info['email'] . '&init_password=' . $user_info['password'] : '';
		}

		return $url;
	}

	private function _getUserInfo() {
		$this->load->model("users/models/Users_model");
		$user_id = $this->session->userdata('user_id');
		return $this->Users_model->get_user_by_id($user_id);
	}

	private function _get_admin_url() {
		$chat_server_mode = $this->_settings['chat_server_mode'];
		if (self::SERVER_HOSTED_BY_FLASHCHAT_FREE !== $chat_server_mode) {
			$client_location = $this->_settings['server_settings'][$chat_server_mode]['client_location'];
			$admin_url = $client_location . self::ADMIN_FILE;

			if (self::SERVER_HOSTED_BY_USER == $chat_server_mode) {
				$admin_url.= "?init_host=" . $this->_settings['server_settings'][$chat_server_mode]['init_host'];
				$admin_url.= "&init_port=" . $this->_settings['server_settings'][$chat_server_mode]['init_port'];
			}

			if (self::SERVER_HOSTED_BY_FLASHCHAT == $chat_server_mode) {
				$client_location_str = substr($client_location, 7, -1);
				$client_location_array = explode('/', $client_location_str);
				$admin_url.= "?init_host=" . $client_location_array[0];

				$serverinfo = $this->_getServerHostPortHost($client_location);
				$admin_url.= "&init_port=" . (isset($serverinfo['fc_server_port']) ? $serverinfo['fc_server_port'] : "");
				$admin_url.= '&init_group=' . $client_location_array[1];
			}
			return $admin_url;
		} else {
			return false;
		}
	}

	private function _curlRequestRemote($url, $mode) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_TIMEOUT, 3);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		if ($mode === 0) {
			curl_setopt($ch, CURLOPT_HEADER, true);
		}
		$r = curl_exec($ch);
		curl_close($ch);
		if ($mode === 0) {
			return substr($r, 0, 17);
		} else {
			return $r;
		}
	}

	private function _getServerHostPortHost($client_location) {
		$content = $this->_curlRequestRemote($client_location, 1);
		if (!$content) {
			return false;
		}
		$matches = array();
		// server port
		preg_match('/init_port=([0-9]*)/', $content, $matches);
		if (isset($matches[1])) {
			$data['fc_server_port'] = $matches[1];
		}
		// server port s
		preg_match('/init_port_s=([0-9]*)/', $content, $matches);
		if (isset($matches[1])) {
			$data['fc_server_port_s'] = $matches[1];
		}
		// server port h
		preg_match('/init_port_h=([0-9]*)/', $content, $matches);
		if (isset($matches[1])) {
			$data['fc_server_port_h'] = $matches[1];
		}
		// server host
		preg_match('/init_host=([a-zA-Z0-9.]*)/', $content, $matches);
		if (isset($matches[1])) {
			$data['fc_server_host'] = $matches[1];
		}
		// server host s
		preg_match('/init_host_s=([a-zA-Z0-9.]*)&/', $content, $matches);
		if (isset($matches[1])) {
			$data['fc_server_host_s'] = $matches[1];
		}
		// server host h
		preg_match('/init_host_h=([a-zA-Z0-9.]*)&/', $content, $matches);
		if (isset($matches[1])) {
			$data['fc_server_host_h'] = $matches[1];
		}
		return $data;
	}

	public function user_page() {
		$this->template_lite->assign('settings', $this->_settings);
		$this->template_lite->assign('url', $this->_get_user_url());
		$this->template_lite->assign('chat', $this->as_array());
		return $this->template_lite->fetch($this->get_tpl_name());
	}

	public function admin_page() {
		$this->template_lite->assign('chat_server_modes', array(
			self::SERVER_HOSTED_BY_USER,
			self::SERVER_HOSTED_BY_FLASHCHAT,
			self::SERVER_HOSTED_BY_FLASHCHAT_FREE,
		));

		$this->template_lite->assign('free_host', self::FREE_HOST);
		$this->template_lite->assign('login_url', SITE_VIRTUAL_PATH . self::LOGIN_URL);
		$this->template_lite->assign('admin_file', self::ADMIN_FILE);
		$this->template_lite->assign('url', $this->_get_admin_url());
		$this->template_lite->assign('chat', $this->as_array());
		return $this->template_lite->fetch($this->get_tpl_name());
	}

	public function install_page() {
		$this->CI->Chats_model->set_installed($this->_gid);
		return null;
	}

	public function call_login($email, $password) {
		$LOGIN_SUCCESS = 0;
		$LOGIN_PASSWD_ERROR = 1;
		$LOGIN_NICK_EXIST = 2;
		$LOGIN_ERROR = 3;
		$LOGIN_ERROR_NOUSERID = 4;
		$LOGIN_SUCCESS_ADMIN = 5;
		$LOGIN_NOT_ALLOW_GUEST = 6;
		$LOGIN_USER_BANED = 7;

		if (empty($email) || empty($password)) {
			echo $LOGIN_ERROR_NOUSERID;
			return false;
		}

		$this->load->model("users/models/Auth_model");
		$validate = $this->CI->Auth_model
				->validate_login_data(array('email' => $email, 'password', $password));

		if (empty($validate['errors'])) {
			echo $LOGIN_ERROR, '|', implode(', ', $validate['errors']);
			return false;
		}

		$pw_array = array($password, md5($password));
		$this->CI->load->model('Users_model');
		$flag = false;
		foreach ($pw_array as $passwd) {
			$user = $this->CI->Users_model->get_user_by_email_password($email, $passwd);
			$flag = (!$user) ? true : false;
			if (!$flag) {
				break;
			}
		}
		if ($flag) {
			echo $LOGIN_PASSWD_ERROR;
			return false;
		}

		$this->CI->load->model('Uploads_model');
		$logo = $this->CI->Uploads_model->format_upload($this->CI->Users_model->upload_config_id, $user["id"], $user["user_logo"]);

		$this->CI->load->helper('countries');
		$location = cities_output_format(
				array($user['id'] => array($user['id_country'], $user['id_region'], $user['id_city'])), $user['lang_id']);

		echo "$LOGIN_SUCCESS|"
		. "eml={$user['email']}&"
		. "a={$user['age']}&"
		. "s={$user['user_type']}&"
		. "l={$location[$user['id']]}&"
		. "avt={$logo['thumbs']['small']}";
		return true;
	}

	public function validate_settings() {
		return true;
	}

	public function has_files() {
		return true;
	}

}
