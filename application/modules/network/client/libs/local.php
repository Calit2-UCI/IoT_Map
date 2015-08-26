<?php

use Pg\Modules\Network\Models\Network_model;

class Local {

	private static $_obj;
	private $CI;

	public function __construct() {
		$this->_get_ci();
		$this->CI->load->model('network/models/Network_events_model');
		$this->CI->load->model('network/models/Network_actions_model');
	}

	public static function single() {
		if(empty(self::$_obj)) {
			self::$_obj = new Local;
		}
		return self::$_obj;
	}

	private function _get_ci() {
		$uri = 'start';
		$_SERVER['PATH_INFO'] = $_SERVER['REQUEST_URI'] = $uri;
		$index = realpath(__DIR__ . '/../../../../../index.php');
		ob_start();
		include($index);
		ob_end_clean();
		$this->CI = &get_instance();
		if(empty($this->CI)) {
			throw new Exception('Can\'t init codeigniter');
		}
		$this->CI->db->initialize();
	}

	public function action($action, $params = array()) {
		$result = call_user_func_array(array($this->CI->Network_actions_model, $action), $params ?: array());
		return $result;
	}

	public function get_events_dir() {
		return $this->CI->Network_events_model->get_events_dir();
	}

	public function get_logs_dir() {
		return $this->CI->Network_events_model->get_logs_dir();
	}

	public function get_events() {
		return $this->CI->Network_events_model->get_events();
	}

	public function get_handler($event) {
		return $this->CI->Network_events_model->get_cache($event);
	}

	public function handle($event, $data) {
		return $this->CI->Network_events_model->handle($event, $data);
	}

	public function get_connection_data() {
		$data = $this->CI->Network_events_model->get_connection_data();
		$url_arr = parse_url($data['url']);
		return array(
			'url' => $url_arr['scheme'] . '://' . $url_arr['host'] 
				. (isset($url_arr['port']) ? ':' . $url_arr['port'] : ''),
			'namespace' => rtrim($url_arr['path'], '/'),
			'key' => $data['key'],
			'domain' => $data['domain'],
		);
	}

	public function save_daemon_pid($pid) {
		$this->CI->load->model('Network_model');
		$this->CI->Network_model->set_config(array('daemon_pid' => $pid));
	}

	public function is_debug() {
		return (bool)DISPLAY_ERRORS;
	}

	public function log($level, $message, $file = 'log') {
		log_message($level, $message, Network_model::MODULE_GID, $file);
		return $this;
	}

}
