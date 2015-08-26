<?php

class Put_removed_status_action extends Abstract_action 
{
	public function run(){
		$net_ids = $this->local_action('get_processed_net_ids', 'remove');
		if(empty($net_ids)) {
			return array('log' => array());
		}
		$this->log('info', 'put_removed_status: ' . serialize($net_ids));
		$data = $this->send('put_removed_status', array('id' => serialize($net_ids)), 'POST');
		$this->local_action('delete_temp_records', $net_ids, null, 'remove', 'out');
		return array('log' => $data);
	}
}
