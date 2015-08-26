<?php

/**
 * Libraries
 *
 * @package 	PG_Core
 * @copyright 	Copyright (c) 2000-2014 PG Core
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

define('LIBRARIES_TABLE', DB_PREFIX . 'libraries');

/**
 * PG Libraries Model
 * 
 * @package 	PG_Core
 * @subpackage 	Libraries
 * @category 	libraries
 * @copyright 	Copyright (c) 2000-2014 PG Core
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
class CI_Pg_library {
	/**
	 * Link to CodeIgniter object
	 * 
	 * @var object
	 */
	public $CI;

	/**
	 * Libraries cache
	 * 
	 * @var array
	 */
	public $libraries = array();

	/**
	 * Constructor
	 *
	 * @return CI_PG_Module Object
	 */
	public function CI_Pg_library() {
		$this->CI = & get_instance();
		$this->CI->db->memcache_tables(array(LIBRARIES_TABLE));
	}

	/**
	 * Get installed libraries data from base, put into the $this->libraries
	 * 
	 * @return array
	 */
	public function get_libraries() {
		unset($this->libraries);
		$this->libraries = array();

		$this->CI->db->select('id, gid, name, date_add')->from(LIBRARIES_TABLE)->order_by("name DESC");
		$results = $this->CI->db->get()->result_array();
		if (!empty($results)) {
			foreach ($results as $result) {
				$this->libraries[$result["id"]] = $result;
			}
		}
		return $this->libraries;
	}

	/**
	 * Execute get_libraries, if libraries cache not exists
	 * 
	 * @return array
	 */
	public function return_libraries() {
		if (!isset($this->libraries) || empty($this->libraries)) {
			$this->get_libraries();
		}
		return $this->libraries;
	}

	/**
	 * Return module by identifier
	 * 
	 * @return integer $library_id library identifier
	 * @return array
	 */
	public function get_module_by_id($library_id) {
		$libraries = $this->return_libraries();
		return $libraries[$library_id];
	}

	/**
	 * Return library object by guid
	 * 
	 * @param string $library_gid library guid
	 * @return array/false
	 */
	public function get_library_by_gid($library_gid) {
		$libraries = $this->return_libraries();
		foreach ($libraries as $library) {
			if ($library["gid"] == $library_gid) {
				return $library;
			}
		}
		return false;
	}

	/**
	 * Return status of library installed
	 *  
	 * @param string $library_gid library guid
	 * @return boolean
	 */
	public function is_library_installed($library_gid) {
		$this->return_libraries();

		if (empty($this->libraries)) {
			return false;
		}

		foreach ($this->libraries as $library) {
			if ($library["gid"] == $library_gid) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Install library data
	 *  
	 * @param array $data library data
	 * @return void
	 */
	public function set_library_install($data) {
		$this->CI->db->insert(LIBRARIES_TABLE, $data);
		$this->get_libraries();
		return;
	}

	/**
	 * Update library data
	 *  
	 * @param string $library_gid library guid
	 * @param array $data library data
	 * @return void
	 */
	public function set_library_update($library_gid, $data) {
		$this->CI->db->where('gid', $library_gid);
		$this->CI->db->update(LIBRARIES_TABLE, $data);
		$this->get_libraries();
		return;
	}

	/**
	 * Uninstall library data
	 *  
	 * @param string $library_gid labrary guid
	 * @return void
	 */
	public function set_library_uninstall($library_gid) {
		$this->CI->db->where("gid", $library_gid);
		$this->CI->db->delete(LIBRARIES_TABLE);
		$this->get_libraries();
		return;
	}

}
