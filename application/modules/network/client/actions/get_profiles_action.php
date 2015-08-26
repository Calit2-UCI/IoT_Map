<?php

class Get_profiles_action extends Abstract_action 
{
	public function run() {
		$last_id = $this->local_action('get_last_id');
		$users = $this->send('get_profiles', array('last_id' => $last_id));
		if(!empty($users['log']['profiles'])) {
			$this->log('info', 'get_profiles: ' . serialize($users['log']['profiles']));
			$this->local_action('set_temp_profiles_add', $users['log']['profiles']);
		}
		return array('log' => $users);
	}
}
