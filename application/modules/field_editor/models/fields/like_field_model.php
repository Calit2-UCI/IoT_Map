<?php  
/**
* Like field model
* 
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Like_field_model {
	public $base_field_param = array(
		'type' => 'TINYINT',
		'constraint' => 3,
		'null' => FALSE,
		'default' => 0,
	);

	public $manage_field_param = array(
		'default_value' => array( 'type'=>'int', "default"=>0 ),
	);

	public $form_field_settings = array();
	
	public function set_field_option($field, $option_gid, $data){
		return;
	}

	public function delete_field_option($field, $option_gid){
		return;
	}	

	public function sorter_field_option($field, $sorter_data){
		return;
	}


}
