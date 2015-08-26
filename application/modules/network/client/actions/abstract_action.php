<?php

abstract class Abstract_action 
{
	private $_local;

	public abstract function run();

	public function __construct(Local $local = null) {
		$this->_local = $local;
	}

	protected function send($action, $data = array(), $method = 'get') {
		$result = Loader::get_instance()->api->send($action, $data, $method);
		return array('log' => $result);
	}

	protected function local_action($action) {
		$params = array_slice(func_get_args(), 1);
		return $this->_local->action($action, $params ?: null);
	}

	protected function log($level, $message) {
		$this->_local->log($level, $message, 'slow');
	}
}
