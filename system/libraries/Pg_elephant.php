<?php 

/**
 * Libraries
 *
 * @package 	PG_Core
 * @copyright 	Copyright (c) 2000-2014 PG Core
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

use ElephantIO\Client,
    ElephantIO\Engine\SocketIO\Version1X,
	ElephantIO\Engine\SocketIO\Version0X;

/**
 * PG Elephant Model
 * 
 * @package 	PG_Core
 * @subpackage 	Libraries
 * @category	libraries
 * @copyright 	Copyright (c) 2000-2014 PG Core
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
class CI_Pg_elephant {
	/**
	 * Link to CodeIgniter object
	 * 
	 * @var object
	 */
	public $CI;
	private $_server = 'http://localhost';
	private $_port = '8888';
	private $_client;

	static function autoload() {
		spl_autoload_register(function($className){
			$libName = BASEPATH . 'libraries' . DIRECTORY_SEPARATOR . $className . '.php';
			if(file_exists($libName)) {
				require $libName;
			}
		});
	}

	public function __construct(){
		self::autoload();
		$this->CI = &get_instance();
		$this->_connect();
	}

	public function __destruct() {
		$this->_client->close();
	}

	private function _connect() {
		if(empty($this->_client)) {
			try {
				$this->_client = new Client(new Version1X($this->_server . ':' . $this->_port));
				$this->_client->initialize(false);
			} catch (Exception $e) {
				log_message('error', '(CI_Pg_elephant) ' . $e->getMessage());
			}
		}
	}

	public function emit($event, $data = array()) {
		if(empty($this->_client)) {
			return false;
		}
		$this->_client->emit($event, $data);
	}

	public function read() {

	}

	public function test() {
		$r = $this->_client->read();
		var_dump($r);
	}

}