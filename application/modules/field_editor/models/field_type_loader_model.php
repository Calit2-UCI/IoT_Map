<?php
/**
* Field types loader Model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

class Field_types_loader_model{
	private $CI;

	function __construct(){
		$this->CI = & get_instance();
	}

	function __get($var){
		if(!$var) return '';
		include_once(MODULEPATH . "field_editor/models/fields/field_type_model".EXT);
		$driver = strtolower($var)."_field_model";
		$driver_file = MODULEPATH . "field_editor/models/fields/".$driver.EXT;
		if(file_exists($driver_file)){
			$model_name = ucfirst($var)."_field_model";
			include_once($driver_file);
			$this->$var = new $model_name();
		}else{
			$this->$var = new Field_type_model();
		}
		return $this->$var;
	}
}
?>