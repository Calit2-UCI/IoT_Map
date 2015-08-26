<?php

class Get_updated_action extends Abstract_action 
{
	public function run(){
		$users = $this->send('get_updated', array(), 'GET');
		if(!empty($users['log']['profiles'])) {
			$this->log('info', 'get_updated: ' . serialize($users['log']['profiles']));
			$this->local_action('set_temp_profiles_update', $users['log']['profiles']);
		}
		return array('log' => $users);
	}
}
