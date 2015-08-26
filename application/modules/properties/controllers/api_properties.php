<?php
/**
* Properties api controller
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Alexander Batukhtin <abatukhtin@pilotgroup.net>
**/
Class Api_Properties extends Controller {

	public function get_property(){
		$gid = trim(strip_tags($this->input->post('gid', true)));
		$lang_id = filter_input(INPUT_POST, 'lang_id');
		$this->load->model('Properties_model');
		$propertie = $this->Properties_model->get_property($gid, $lang_id);
		$this->set_api_content('data', $propertie);
	}

}