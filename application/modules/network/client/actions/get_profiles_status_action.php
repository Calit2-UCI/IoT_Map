<?php

class Get_profiles_status_action extends Abstract_action 
{
	public function run(){
		$data = $this->send('get_profiles_status');
		if(!empty($data['log']['status'])) {
			$this->log('info', 'set_profiles_status: ' . serialize($data['log']['status']));
			$this->local_action('set_profiles_status', $data['log']['status']);
		}
		return array('log' => $data);
	}
}
