<?php
/**
* Linker admin side controller
* 
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/


class Admin_linker extends Controller {
	
	/**
	 * Constructor
	 *
	 * @return Admin_linker
	 */
	function __construct()
	{
		parent::Controller();
		$this->load->model('Linker_model', '', true);
	}
	
	/**
	 *
	 */
	public function index()
	{

	}

	public function create_linker_type($gid, $separated=0){
		$ret = $this->Linker_model->linker_type->create_type($gid, $separated);
		echo $ret;
		return;
	}

}