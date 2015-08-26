<?php

class Integration {

	private $_session;
	private $_db;
	private $_template_lite;

	public function __construct() {
		$config_path = realpath(dirname(__FILE__) . '/../../../../config.php');
		require_once($config_path);
		define('BASEPATH', SITE_PHYSICAL_PATH . 'system/');
		define('APPPATH', SITE_PHYSICAL_PATH . 'application/');
		define('TEMPPATH', SITE_PHYSICAL_PATH . 'temp/');
		define('EXT', '.' . pathinfo(__FILE__, PATHINFO_EXTENSION));
		define('CI_SERVER_API', 'module');
		require_once(BASEPATH . 'codeigniter/Base5.php');
		require_once(BASEPATH . 'codeigniter/Common.php');
		$this->_session_read();
	}

	private function _session_read() {
		if(empty($this->_session)) {
			$config = get_config();
			$session = filter_input(INPUT_COOKIE, $config['sess_cookie_name']);

			require_once(BASEPATH . 'libraries/Encrypt.php');
			$encrypt = new CI_Encrypt();
			$this->_session = unserialize($encrypt->decode($session, $config['encryption_key']));
		}
		return $this->_session;
	}

	public function db() {
		if(empty($this->_db)) {
			require_once(BASEPATH . 'database/DB.php');
			$this->_db = &DB(array(
				'hostname' => DB_HOSTNAME,
				'username' => DB_USERNAME,
				'password' => DB_PASSWORD,
				'database' => DB_DATABASE,
				'dbdriver' => DB_DRIVER,
			));
		}
		return $this->_db;
	}

	public function get_db_data() {
		return array(
			'host' => DB_HOSTNAME,
			'name' => DB_DATABASE,
			'uname' => DB_USERNAME,
			'pass' => DB_PASSWORD,
			'table_prefix' => DB_PREFIX,
			'user_table' => 'users',
			'user_name' => 'nickname',
			'user_id' => 'id'
		);
	}
	
	public function template_lite() {
		if(empty($this->_template_lite)) {
			require_once(BASEPATH . 'libraries/Template_Lite.php');
			$template_lite = new Template_Lite();
			$this->_template_lite = $template_lite;
		}
		return $this->_template_lite;
	}

	public function get_user_id() {
		if (!empty($this->_session['session_id'])) {
			$result = $this->db()->select('user_data')
				->from(TABLE_PREFIX . 'sessions ')
				->where('session_id', $this->_session['session_id'])
				->get()->result_array();
			$user_data = unserialize($result[0]['user_data']);

			if(!empty($user_data['user_id'])) {
				return intval($user_data['user_id']);
			}
		}
		return 0;
	}

}