<?php
/**
* Get token install model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Mikhail Chernov <mchernov@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: mchernov $
**/

class Get_token_install_model extends Model
{
	var $CI;

	/**
	 * Constructor
	 *
	 * @return Install object
	 */
	function Get_token_install_model()
	{
		parent::Model();
		$this->CI = & get_instance();
	}

	function _arbitrary_installing(){
		if ((extension_loaded("xmlwriter"))) {
			$this->CI->pg_module->set_module_config('get_token', 'use_xml', TRUE);
		}
	}

	function _arbitrary_deinstalling(){

	}
}