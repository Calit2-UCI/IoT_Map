<?php

class Get_removed_action extends Abstract_action 
{
	public function run(){
		$data = $this->send('get_removed');
		if(!empty($data['log']['removed'])) {
			$this->log('info', 'get_removed: ' . serialize($data['log']['removed']));
			$this->local_action('set_temp_profiles_remove', $data['log']['removed']);
		}
		return array('log' => $data);
	}
}
