<?php

class Loader {

	private $config_data = array();
	private $models_data = array();
	private static $instance;
	public $api;
	private $_local = null;

	function __construct(Local &$local = null) {
		if($local) {
			$this->_local = $local;
		}
		self::$instance = $this;
	}

	public static function &get_instance() {
		return self::$instance;
	}

	public function load_api($key, $domain) {
		if (empty($this->api)) {
			include_once NET_CLIENT_PATH . '/libs/api.php';
			$this->api = new Api($key, $domain);
		}
		return $this->api;
	}

	public function model($name, $load = 'dyn') {
		$name = trim(str_replace('_model', '', strtolower($name)));
		if (!isset($this->models_data[$name])) {
			include_once NET_CLIENT_PATH . '/libs/' . $name . '_model.php';
			if ($load == 'static') {
				$this->models_data[$name] = true;
			} else {
				$model_name = $name . '_model';
				$Model_name = ucfirst($model_name);
				$this->models_data[$name] = $this->$model_name = new $Model_name();
			}
		}
		return $this->models_data[$name];
	}

	/* TODO: Удалить, если ↑ работает
	 public function model($name, $load = 'dyn') {
		$name = trim(str_replace('_model', '', strtolower($name)));
		if (isset($this->models_data[$name]))
			return $this->models_data[$name];
		include_once NET_CLIENT_PATH . '/libs/' . $name . '_model.php';
		if ($load == 'static') {
			$this->models_data[$name] = true;
			return true;
		} else {
			$model_name = $name . '_model';
			$Model_name = ucfirst($model_name);
			$this->models_data[$name] = $this->$model_name = new $Model_name();
			return $this->models_data[$name];
		}
	}*/

	public function static_model($name) {
		return $this->model($name, 'static');
	}

	public function config($name) {
		if (!isset($this->config_data[$name])) {
			$config = array();
			include_once NET_CLIENT_PATH . '/configs/' . $name . '.php';
			$this->config_data[$name] = $config;
			unset($config);
		}
		return $this->config_data[$name];
	}

	public function action($name) {
		include_once NET_CLIENT_PATH . '/actions/abstract_action.php';
		$action_file = NET_CLIENT_PATH . '/actions/' . $name . '_action.php';
		if(!is_file($action_file)) {
			throw new Exception('Wrong action (' . $name . ')');
		}
		include_once $action_file;
		$modelname = ucfirst($name) . '_action';
		$action = new $modelname($this->_local);
		return $action->run();
	}

}
