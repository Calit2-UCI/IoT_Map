<?php
/**
* Users connections install model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Alexander Batukhtin <abatukhtin@pilotgroup.net>
* @version $Revision: 1 $ $Date: 2012-09-21 11:44:47 +0300 (Пт, 21 сент 2012) $ $Author: abatukhtin $
**/

class Users_connections_install_model extends Model
{
	var $CI;

	/**
	 * Constructor
	 *
	 * @return Install object
	 */
	function __construct()
	{
		parent::Model();
		$this->CI = & get_instance();
	}

	function _arbitrary_installing(){

	}

	function _arbitrary_deinstalling(){

	}

}