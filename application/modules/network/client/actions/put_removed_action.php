<?php

use Pg\Modules\Network\Models\Network_users_model;

class Put_removed_action extends Abstract_action 
{
	public function run(){
		$net_ids = $this->local_action('get_processed_net_ids', 
				Network_users_model::ACTION_REMOVE, 'net_id');
		if(!empty($net_ids)) {
			$this->log('info', 'put_removed: ' . serialize($net_ids));
		}
		$data = $this->send('put_removed', array('id' => serialize($net_ids)), 'POST');
		$this->local_action('delete_temp_records', 
				$net_ids, null, Network_users_model::ACTION_REMOVE, Network_users_model::TYPE_OUT);
		return array('log' => $data);
	}
}
