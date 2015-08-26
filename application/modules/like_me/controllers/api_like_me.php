<?php
/**
* Api Like me controller
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Nikita Savanaev <nsavanaev@pilotgroup.net>
**/

class Api_Like_me extends Controller {

	/**
	 * Constructor
	 * 
	 * @return Api_Like_me
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('like_me/models/Like_me_model');
	}

	
}
