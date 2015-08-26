<?php

if ( ! function_exists('network_emit'))
{
	function network_emit($event, $data){
		$this->load->model('network/models/Network_events_model');
		return $this->Network_events_model->emit($event, $data);
	}
}
