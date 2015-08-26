<?php
/**
* Languages api controller
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Alexander Batukhtin <abatukhtin@pilotgroup.net>
**/


Class Api_Languages extends Controller
{

	/**
	 * Constructor
	 */
	function Languages()
	{
		parent::Controller();
	}

	/**
	 * Change user language
	 *
	 * @param int $lang_id
	 */
	function change_lang(){
		$lang_id = intval($this->input->post('lang_id', true));
		if (!$lang_id){
			log_message('error', 'languages API: Empty lang id');
			$this->set_api_content('error', l('api_error_empty_lang_id', 'languages'));
			return false;
		}
		$this->session->set_userdata('lang_id', $lang_id);
		$this->set_api_content('data', array('lang_id' => $lang_id));
	}

}