<?php

/**
 * File uploads config model
 *
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @author Katya Kashkova <katya@pilotgroup.net>
 * @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
 * */
if (!defined('FILE_UPLOADS_TABLE'))
	define('FILE_UPLOADS_TABLE', DB_PREFIX . 'file_uploads');

class File_uploads_config_model extends Model {

	public $CI;
	public $DB;
	public $fields_all = array(
		'id',
		'gid',
		'name',
		'max_size',
		'name_format',
		'file_formats',
		'date_add'
	);
	public $file_formats = array();
	public $file_categories = array();

	public function __construct() {
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;

		if (count($this->file_categories) == 0) {
			if (@require(APPPATH . 'config/file_uploads_mimes' . EXT)) {
				$this->file_categories = $mimes_categories;
			}
		}

		if (count($this->file_formats) == 0) {
			$this->CI->load->library('upload');
			$this->CI->upload->mimes_types('');
			$this->file_formats = $this->CI->upload->mimes;
		}

		$this->DB->memcache_tables(array(FILE_UPLOADS_TABLE));
	}

	public function get_config_list() {
		$data = array();
		$this->DB->select(implode(", ", $this->fields_all))->from(FILE_UPLOADS_TABLE)->order_by('gid ASC');
		$results = $this->DB->get()->result_array();

		if (!empty($results) && is_array($results)) {
			foreach ($results as $r) {
				$data[] = $this->format_config($r);
			}
		}
		return $data;
	}

	public function get_config_by_id($config_id) {
		$data = array();
		$result = $this->DB->select(implode(", ", $this->fields_all))->from(FILE_UPLOADS_TABLE)->where("id", $config_id)->get()->result_array();
		if (!empty($result)) {
			$data = $result[0];
		}
		return $data;
	}

	public function get_config_by_gid($config_gid) {
		$data = array();
		$result = $this->DB->select(implode(", ", $this->fields_all))->from(FILE_UPLOADS_TABLE)->where("gid", $config_gid)->get()->result_array();
		if (!empty($result)) {
			$data = $result[0];
		}
		return $data;
	}

	public function save_config($config_id, $data) {
		if (is_null($config_id)) {
			$data["date_add"] = date("Y-m-d H:i:s");
			$this->DB->insert(FILE_UPLOADS_TABLE, $data);
			$config_id = $this->DB->insert_id();
		} else {
			$this->DB->where('id', $config_id);
			$this->DB->update(FILE_UPLOADS_TABLE, $data);
		}

		return $config_id;
	}

	public function delete_config($config_id) {
		$data = $this->get_config_by_id($config_id);
		if (empty($data))
			return false;

		$data = $this->format_config($data);

		$this->DB->where('id', $config_id);
		$this->DB->delete(FILE_UPLOADS_TABLE);

		return;
	}

	public function format_config($data) {
		if (!empty($data["file_formats"])) {
			$data["file_formats"] = unserialize($data["file_formats"]);
			if (!empty($data["file_formats"])) {
				foreach ($data["file_formats"] as $format) {
					$data["enable_formats"][$format] = 1;
				}
				$this->CI->load->helper('upload');
				$data['allowed_mimes'] = get_mimes_types_by_files_types($data["file_formats"]);
			}
			$data["file_formats_str"] = implode("|", $data["file_formats"]);
		} else {
			$data["file_formats_str"] = "";
		}
		return $data;
	}

	public function validate_config($config_id = null, $data = array()) {
		$return = array("errors" => array(), "data" => array());

		if (isset($data["name"])) {
			$return["data"]["name"] = strip_tags($data["name"]);
			if (empty($return["data"]["name"])) {
				$return["errors"][] = l('error_title_invalid', 'file_uploads');
			}
		}

		if (isset($data["gid"])) {
			$return["data"]["gid"] = strip_tags($data["gid"]);
			$return["data"]["gid"] = preg_replace("/[\n\t\s]{1,}/", "-", trim($return["data"]["gid"]));
			if (empty($return["data"]["gid"])) {
				$return["errors"][] = l('error_gid_invalid', 'file_uploads');
			}
		} elseif (!$config_id) {
			$return["errors"][] = l('error_gid_invalid', 'file_uploads');
		}

		if (isset($data["max_size"])) {
			$return["data"]["max_size"] = intval($data["max_size"]);
		}

		if (isset($data["name_format"])) {
			$return["data"]["name_format"] = $data["name_format"];
		}

		if (isset($data["file_formats"])) {
			if (!is_array($data["file_formats"])) {
				$return["errors"][] = l('error_empty_file_formats', 'file_uploads');
			} else {
				$return["data"]["file_formats"] = serialize($data["file_formats"]);
			}
		}

		return $return;
	}

}
