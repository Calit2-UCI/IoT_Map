<?php
/**
* Shoutbox controller
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Dmitry Popenov
* @version $Revision: 2 $ $Date: 2013-01-30 10:07:07 +0400 $
**/

class Shoutbox extends Class_shoutbox {
	
	public function ajax_check_new_messages(){
		exit(json_encode($this->_check_new_messages()));
	}
	
	public function ajax_get_messages(){
		exit(json_encode($this->_get_messages()));
	}
	
	public function ajax_post_message(){
		exit(json_encode($this->_post_message()));
	}
	
}