<?php

use Pg\Modules\Network\Models\Network_users_model;

class Put_updated_action extends Abstract_action 
{
	public function run() {
		$update_records = $this->local_action('get_temp_profiles', 
				Network_users_model::ACTION_UPDATE, array('data', 'local_id')
		);
		$updated_data = array();
		$local_ids = array();
		foreach ($update_records as $update_record) {
			$updated_data[] = unserialize($update_record['data']);
			$local_ids[] = $update_record['local_id'];
		}
		if(!empty($updated_data)) {
			$this->log('info', 'put_updated: ' . serialize($updated_data));
		}
		$data = $this->send('put_updated', array('profiles' => serialize($updated_data)), 'POST');
		$this->local_action('delete_temp_records',
				null, $local_ids, Network_users_model::ACTION_UPDATE, Network_users_model::TYPE_OUT);
		return array('log' => $data);
	}
}
