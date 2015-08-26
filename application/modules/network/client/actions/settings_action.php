<?php

class Settings_action extends Abstract_action 
{
	public function run() {
		$settings = $this->local_action('get_settings');
		$this->log('info', 'settings: ' . serialize($settings));
		$data = $this->send('settings', $settings);
		return array('log' => $data);
	}
}
