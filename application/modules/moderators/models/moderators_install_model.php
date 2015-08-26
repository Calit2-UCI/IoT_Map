<?php
/**
* Moderators install model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Nikita Savanaev <nsavanaev@pilotgroup.net>
**/
class Moderators_install_model extends Model
{
	var $CI;

	/**
	 * Constructor
	 *
	 * @return Install object
	 */
	function Moderators_install_model()
	{
		parent::Model();
		$this->CI = & get_instance();
		//// load langs
		$this->CI->load->model('Install_model');
	}
	
	public function _arbitrary_deinstalling(){
		$this->CI->load->model('Moderators_model');
		$users_id = $this->CI->Moderators_model->delete_user();
	}

}
