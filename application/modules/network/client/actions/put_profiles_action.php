<?php

class Put_profiles_action extends Abstract_action 
{
	public function run(){
		$profiles = $this->local_action('get_profiles');
		$data = $this->send('put_profiles', array('profiles' => serialize($profiles) ), 'POST');
		if(!empty($data['log']['profiles'])) {
			$this->log('info', 'put_profiles: ' . serialize($data['log']['profiles']));
			$this->local_action('set_profiles_status', $data['log']['profiles']);
		}
		return array('log' => $data);
	}
}
