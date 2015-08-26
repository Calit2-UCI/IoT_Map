<?php
/**
* Properties main model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/
class Properties_model extends Model {
	private $CI;
	private $DB;

	public $properties = array(
		'user_type'
	);

	public $module_gid = 'data_properties';

	function __construct()
	{
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;
	}

	public function get_property($ds_gid, $lang_id = null){
		if (!$ds_gid){
			return null;
		}
		if(!$lang_id) {
			$lang_id = $this->CI->session->userdata("lang_id");
		}
		return $this->pg_language->ds->get_reference($this->module_gid, $ds_gid, $lang_id);
	}
}