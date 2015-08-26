<?php

namespace Pg\Modules\Network\Models;

/**
 * Network main model
 *
 * @package PG_Dating
 * @subpackage application
 * @category modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * */
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

require_once MODULEPATH . "network/install/config.php";

/**
 * Network model
 */
class Network_model extends \Model {

	const MODULE_GID = 'network';
	const HEADER_KEY = 'x-pgn-key';
	const HEADER_DOMAIN = 'x-pgn-domain';
	const CLIENT_FAST = 'fast';
	const CLIENT_SLOW = 'slow';
	const FAST_SERVICE_EXECUTABLE = 'fast-client-service.php';

	private $_events_dir;
	private $_logs_dir;

	protected $CI;
	protected $DB;
	protected $_slow_server = NETWORK_SLOW_SERVER;
	protected $_fast_server = NETWORK_FAST_SERVER;
	protected $_key = '';
	protected $_log_files = array(
		self::CLIENT_SLOW => 'slow_server.log',
		self::CLIENT_FAST => 'fast_server.log'
	);
	protected $_domain = '';
	protected $_client_path = '';
	protected $_cfg_data = array(
		'slow_server',
		'fast_server',
		'key',
		'domain',
        'is_upload_photos',
	);

	/**
	 * Constructor
	 *
	 * @return Install object
	 */
	public function __construct() {
		parent::Model();
		$this->CI = &get_instance();
		$this->DB = &$this->CI->db;

		$this->_client_path = MODULEPATH . 'network/client/';
		$this->_events_dir = TEMPPATH . self::MODULE_GID . '/events/';
		$this->_logs_dir = TEMPPATH . 'logs/' . self::MODULE_GID . '/';
	}

	/**
	 * Get logs dir
	 * @return string
	 */
	public function get_logs_dir() {
		return $this->_logs_dir;
	}

	/**
	 * Get events dir
	 * @return string
	 */
	public function get_events_dir() {
		return $this->_events_dir;
	}

	/**
	 * Get authentication data
	 * @return array
	 */
	public function get_auth_data() {
		$data = $this->get_config();
		$this->_slow_server = $data['slow_server'];
		$this->_fast_server = $data['fast_server'];
		$this->_key = $data['key'];
		$this->_domain = $data['domain'];
		return $data;
	}

	/**
	 * Get network settings from database
	 * @param mixed $cfg_gids
	 * @return array
	 */
	public function get_config($cfg_gids = null) {
		if (empty($cfg_gids)) {
			$cfg_gids = $this->_cfg_data;
		} elseif (!is_array($cfg_gids)) {
			$cfg_gids = array($cfg_gids);
		}
		$settings = array();
		foreach ($cfg_gids as $cfg_gid) {
			$val = $this->CI->pg_module->get_module_config(self::MODULE_GID, $cfg_gid);
			if (!$val) {
				$val = '';
			} elseif ((false !== ($unser = @unserialize($val))) || 'b:0;' === $unser) {
				$val = $unser;
			}
			$settings[$cfg_gid] = $val;
		}
		return $settings;
	}

	/**
	 * Save network settings
	 * @param array $data
	 * @return array
	 */
	public function set_config(array $data) {
		foreach ($data as $config_gid => $value) {
			if (is_array($value)) {
				$value = serialize($value);
			} else {
				$value = (string) $value;
			}
			$this->CI->pg_module->set_module_config(self::MODULE_GID, $config_gid, $value);
		}
		return $data;
	}

	/**
	 * Execute shell command
	 * @param string $file
	 * @param string $command
	 * @param string $preparams
	 * @param string $postparams
	 * @return string
	 */
	private function _command($file, $command = 'sh', $preparams = null, $postparams = null) {
		if ($preparams) {
			$preparams = $preparams . ' ';
		}
		if ($postparams) {
			$postparams = ' ' . $postparams;
		}
		return `$command {$preparams}{$this->_client_path}$file{$postparams}`;
	}

	/**
	 * Start slow client
	 * @return string
	 */
	public function start_slow() {
		if($this->is_started_slow()) {
			return true;
		}
		$result = $this->_command('slow-client.start', 'sh', null, ' 2>/dev/null');
		// TODO: Проверка успешности
		return $result;
	}

	/**
	 * Start fast client
	 * @return mixed
	 */
	public function start_fast() {
		if($this->is_started_fast()) {
			return true;
		}
		$cmd_result = $this->_command(self::FAST_SERVICE_EXECUTABLE, 'php', '-f');
        //return ('0' === $cmd_result) ? false : $cmd_result;
        return $this->is_started_fast();
	}

	/**
	 * Start client
	 * @return string
	 */
	public function start() {
		return array(
			self::CLIENT_SLOW => $this->start_slow(),
			self::CLIENT_FAST => $this->start_fast()
		);
	}

	/**
	 * Stop slow server
	 * @return string
	 */
	public function stop_slow() {
		$result = $this->_command('slow-client.stop');
		// TODO: Проверка успешности
		return $result;
	}

	/**
	 * Stop fast server
	 * @return boolean
	 */
	public function stop_fast() {
		if(!$this->is_started_fast()) {
			return true;
		}
		$pid = $this->_get_pid_fast();
		if($pid == 0) {
			return false;
		}
		// 15 — SIGTERM
		return posix_kill($pid, 15);
	}

	/**
	 * Stop both clients
	 * @return array
	 */
	public function stop() {
		return array(
			self::CLIENT_SLOW => $this->stop_slow(),
			self::CLIENT_FAST => $this->stop_fast()
		);
	}

	/**
	 * Get fast client status
	 * @param int $lines
	 * @return array
	 */
	public function get_status($lines = 10) {
		$LOG =& load_class('Log');
		$result = array(
			self::CLIENT_FAST => array(
				'is_started' => $this->is_started_fast(),
				'log' => $LOG->read_log(self::MODULE_GID, self::CLIENT_FAST, $lines),
			),
			self::CLIENT_SLOW => array(
				'is_started' => $this->is_started_slow(),
				'log' => $LOG->read_log(self::MODULE_GID, self::CLIENT_SLOW, $lines),
			),
		);
		return $result;
	}

	/**
	 * Get process id of the slow server
	 * @return int
	 */
	private function _get_pid_slow() {
		$result = $this->_command('slow-client.status', 'sh', null, ' 2>/dev/null');
        return (int) $result;
	}

	/**
	 * Get process id of the fast server
	 * @return int
	 */
	private function _get_pid_fast() {
		// TODO: Читать pid из базы
		$pid_file = $this->get_logs_dir() . 'daemon.pid';
		if(is_file($pid_file)) {
			$pid = (int)file_get_contents($pid_file);
		} else {
			$pid = 0;
		}
		return $pid;
	}

	/**
	 * Is slow server running
	 * @return bool
	 */
	public function is_started_slow() {
		$result = (bool)$this->_get_pid_slow();
        return $result;
	}

	/**
	 * Is slow server running
	 * @return bool
	 */
	public function is_started_fast() {
		//TODO: Передумать.
		$pid = $this->_get_pid_fast();
		if(empty($pid)) {
			return false;
		}
		$result = shell_exec("ps aux | grep -w " . $pid . " | grep -v grep");
		//return false !== stripos($result, (string)$pid);
        return (bool)$result;
	}

	/**
	 * Are both servers running
	 * @return array
	 */
	public function is_started() {
		return array(
			self::CLIENT_SLOW => $this->is_started_slow(),
			self::CLIENT_FAST => $this->is_started_fast()
		);
	}

	/**
	 * Check client data
	 * @param string $domain
	 * @param string $key
	 * @return bool
	 */
	public function check_auth_data($domain = null, $key = null) {
		if (!is_null($key)) {
			$this->_key = $key;
		}
		if (!is_null($domain)) {
			$this->_domain = $domain;
		}
		$result = $this->_send('test');
		return !empty($result['test']);
	}

	/**
	 * Validate network settings
	 * @param array $settings
	 * @return array
	 */
	public function validate_settings(array $settings) {
		$errors = array();
		if (isset($settings['domain'])) {
			if (empty($settings['domain'])) {
				$errors['domain'] = l('admin_error_domain_empty', self::MODULE_GID);
			}
		}
		if (isset($settings['key'])) {
			if (empty($settings['key'])) {
				$errors['key'] = l('admin_error_key_empty', self::MODULE_GID);
			} /* elseif(32 !== strlen($data['key'])) {
			  $errors['key'] = l('admin_key_wrong_length', self::MODULE_GID);
			  } elseif(!ctype_xdigit($data['key'])) {
			  $errors['key'] = l('admin_key_wrong_format', self::MODULE_GID);
			  } */
		}
		/* if (!empty($settings['domain']) && !empty($settings['key']) && !$this->check_auth_data($settings['domain'], $settings['key'])) {
		  $errors['data'] = l('admin_error_invalid_data', self::MODULE_GID);
		  } */
		return $errors;
	}

	/**
	 * Get auth headers for a POST request
	 * @return array
	 */
	private function _get_auth_headers() {
		return array(CURLOPT_HTTPHEADER => array(
				self::HEADER_KEY . ': ' . $this->_key,
				self::HEADER_DOMAIN . ': ' . $this->_domain,
		));
	}

	/**
	 * Send request to the server
	 * @param string $action
	 * @param array $data
	 * @return mixed
	 */
	private function _send($action, array $data = array()) {
		if (empty($this->_key) || empty($this->_domain) || empty($this->_slow_server)) {
			return -1;
		}
		$result = $this->curl_post($this->_slow_server . $action, $data);
		return json_decode($result, true);
	}

	/**
	 * Send POST request
	 * @param string $url
	 * @param data $data
	 * @param array $options
	 * @return type
	 */
	private function curl_post($url, $data = null, array $options = array()) {
		// TODO: добавить библиотеку Request и положить этот метод туда.
		$defaults = array(
			CURLOPT_POST => 1,
			CURLOPT_POSTFIELDS => http_build_query($data),
			CURLOPT_URL => $url,
			CURLOPT_HEADER => 0,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT => 60,
			CURLOPT_CONNECTTIMEOUT => 60,
			CURLOPT_SSL_VERIFYPEER => false,
		) + $this->_get_auth_headers();
		$ch = curl_init();
		curl_setopt_array($ch, ($options + $defaults));
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}

    /**
     * Validate module requirements
     * 
     * @return array
     */
    public function validateRequirements() {
		$return = array('data' => array(), 'result' => true);
		$check_list = array(
			array(
                'func' => function () {return (bool) (string)extension_loaded('pcntl');},
                'msg' => 'PCNTL extension is loaded',
            ),
            array(
                'func' => function () {return (bool) (string)function_exists('pcntl_fork');},
                'msg' => 'pcntl_fork() function is available',
            ),
            array(
                'func' => function () {return (bool) (string)function_exists('pcntl_signal');},
                'msg' => 'pcntl_signal() function is available',
            ),
            array(
                'func' => function () {return (bool) (string)function_exists('pcntl_signal_dispatch');},
                'msg' => 'pcntl_signal_dispatch() function is available',
            ),
            array(
                'func' => function () {return (bool) (string)function_exists('posix_setsid');},
                'msg' => 'posix_setsid() function is available',
            ),
            array(
                'func' => function () {return (bool) (string)function_exists('posix_kill');},
                'msg' => 'posix_kill() function is available',
            ),
		);
		foreach ($check_list as $ckeck) {
			$suit = $ckeck['func']();
			$return['data'][] = array(
				'name' => $ckeck['msg'],
				'value' => $suit ? 'Yes' : 'No',
				'result' => $suit,
			);
			$return['result'] = $return['result'] && $suit;
		}
		return $return;
	}
}
