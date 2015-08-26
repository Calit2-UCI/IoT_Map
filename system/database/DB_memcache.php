<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

/**
 * Memcache wrapper
 */
class CI_DB_memcache extends Memcache {

	/**
	 * Server config
	 * @var array
	 */
	private $_config = array(
		'server' => 'localhost',
		'port' => '11211',
		'timeout' => 86400
	);

	/**
	 * Connection flag
	 * @var bool
	 */
	public $is_connected = false;

	/**
	 * Prepare key
	 * @param string $key
	 * @return string
	 */
	private function _prepare_key($key) {
		return md5(preg_replace('/[\n\s\r\t]+/', ' ', trim(strtolower($key))));
	}

	/**
	 * Set config
	 * @param array $config
	 */
	public function set_config($config) {
		$this->_config += $config;
	}

	/**
	 * Open memcached server connection
	 * @param type $config
	 * @return boolean
	 */
	public function connect($config = array()) {
		if(!INSTALL_DONE) {
			return false;
		}
		if (!empty($config)) {
			$this->set_config($config);
		}

		$failure_callback = function($host, $port) {
			log_message('error', 'Can\'t connect to memcached server at '. $host . ':' . $port);
		};
		$this->is_connected = (bool) parent::addserver(
				$this->_config['server'], 
				$this->_config['port'], 
				true,	// persistent
				1,		// weight
				1,		// timeout
				15,		// retry_interval
				true,	// status
				$failure_callback);
		return $this->is_connected;
	}

	/**
	 * Store data at the server
	 * @param string $key
	 * @param mixed $data
	 * @param int $flag
	 * @param int $expire
	 * @return bool
	 */
	public function set($key, $data, $flag = null, $expire = null) {
		if (is_null($expire)) {
			$expire = $this->_config['timeout'];
		}
		return parent::set($key, $data, $flag, $expire);
	}

	/**
	 * Delete top-level item from the server
	 * @param string $parent_key
	 * @param int $timeout
	 * @return bool
	 */
	public function delete_parent($parent_key, $timeout = 0) {
		$children = parent::get($parent_key);
		if($children) {
			foreach($children as $child) {
				$this->delete($child, $timeout);
			}
			$this->delete($parent_key, $timeout);
		}
		return $this;
	}

	/**
	 * Retrieve item from the server
	 * @param string $parent_key
	 * @param string $key
	 * @return mixed
	 */
	public function read($key) {
		$key = $this->_prepare_key($key);
		$data = parent::get($key);
		return $data; 
	}

	/**
	 * Store two-level data at the server
	 * @param string $key
	 * @param mixed $data
	 * @param string $parent_key
	 * @return bool
	 */
	public function write($key, $data, $parent_key) {
		$key = $this->_prepare_key($key);
		$this->_save_parent($key, $parent_key);
		return parent::set($key, $data);
	}

	/**
	 * Save parent
	 * @param string $key
	 * @param string $parent_key
	 * @return \CI_DB_memcache
	 */
	private function _save_parent($key, $parent_key) {
		$parent = parent::get($parent_key);
		if($parent && !in_array($key, $parent)) {
			$parent[] = $key;
			parent::set($parent_key, $parent);
		}
		return $this;
	}

}