<?php

use Pg\Modules\Network\Models\Network_users_model;

class Put_profiles_status_action extends Abstract_action 
{
	public function run(){
		$net_ids = $this->local_action('get_processed_net_ids', 'add');
		if(!empty($net_ids)) {
			$this->log('info', 'put_profiles_status: ' . serialize($net_ids));
			$data = $this->send('put_profiles_status', array('id' => $net_ids), 'POST');
			$this->local_action('delete_temp_records', 
					$net_ids, null, Network_users_model::ACTION_ADD, Network_users_model::TYPE_OUT);
		} else {
			$data = array();
		}
		return array('log' => $data);
	}
}
