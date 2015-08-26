<?php
/**
* FIle uploads main model
* 
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

class File_uploads_model extends Model {

	var $CI;
	var $DB;
	var $media_path = "";
	var $media_url = "";
	var $media_type = "";
	var $config_cache = array();
	var $mimes = array();

	function File_uploads_model() {
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;
		$this->media_path = SITE_PHYSICAL_PATH . UPLOAD_DIR . 'file-uploads/';
		$this->media_url = SITE_VIRTUAL_PATH . UPLOAD_DIR . 'file-uploads/';

		if (count($this->mimes) == 0) {
			$this->CI->load->library('upload');
			$this->CI->upload->mimes_types('');
			$this->mimes = $this->CI->upload->mimes;
		}
	}

	function get_media_path($config_gid, $postfix = '') {
		if (!empty($postfix))
			$postfix = $postfix . '/';
		$path = $this->media_path . str_replace('//', '/', $config_gid . '/' . $postfix);
		return $path;
	}

	function get_media_url($config_gid, $postfix = '') {
		if (!empty($postfix))
			$postfix = $postfix . '/';
		$url = $this->media_url . str_replace('//', '/', $config_gid . '/' . $postfix);
		return $url;
	}

	function get_config($config_gid) {
		if (empty($this->config_cache[$config_gid])) {
			$this->config_cache[$config_gid] = array();
			$this->CI->load->model('file_uploads/models/File_uploads_config_model');
			$config_data = $this->CI->File_uploads_config_model->get_config_by_gid($config_gid);
			if (!empty($config_data)) {
				$config_data = $this->CI->File_uploads_config_model->format_config($config_data);
				$this->config_cache[$config_gid] = $config_data;
			}
		}
		return $this->config_cache[$config_gid];
	}

	function get_data_path($config_gid, $postfix = '') {
		if (!empty($postfix)) {
			$postfix = $postfix . '/';
		}
		$path = $this->media_path . $config_gid . '/' . $postfix;
		$path = str_replace("//", '/', $path);
		return $path;
	}

	function get_data_url($config_gid, $postfix = '') {
		if (!empty($postfix)) {
			$postfix = $postfix . '/';
		}
		return $this->media_url . $config_gid . '/' . $postfix;
	}

	function upload($config_gid, $postfix, $upload_gid) {
		$return["errors"] = array();
		$return["file"] = "";
		$file_return = array('error' => '');

		$config_data = $this->get_config($config_gid);

		$this->CI->load->helper('upload');
		$path = $this->get_data_path($config_gid, $postfix);
		if (!is_dir($path)) {
			@mkdir($path, 0777, TRUE);
		}

		//// upload src file
		$upload_config = array(
			'max_size' => $config_data["max_size"]/1024,
			'use_file_type_check' => false,
			'allowed_types' => $config_data["file_formats_str"],
			'overwrite' => true
		);

		$type_check = $this->pre_check_file_type($_FILES[$upload_gid]['name'], $_FILES[$upload_gid]['tmp_name'], $_FILES[$upload_gid]['type'], $config_data["file_formats"]);

		if (!$type_check || !isset($_FILES[$upload_gid]['name'])) {
			$file_return["error"] = "invalid filetype";
		} else {
			$file_name = $_FILES[$upload_gid]['name'];
			$file_name_ext = '.' . $this->get_extension($file_name);
			switch ($config_data["name_format"]) {
				case "generate": $new_file_name = $this->generate_filename($file_name_ext); break;
				case "format": $new_file_name = $this->format_filename($file_name); break;
			}
			if (is_file($path . $new_file_name)) {
				$new_file_name = str_replace($file_name_ext, '', $new_file_name) . '-' . time() . $file_name_ext;
			}
			$_FILES[$upload_gid]['name'] = $new_file_name;
			$file_return = upload_file($upload_gid, $path, $upload_config);
		}
		if ($file_return["error"] != '') {
			$return["errors"] = $file_return["error"];
		} else {
			$file_name = $file_return["data"]["file_name"];
			$file_name_ext = $file_return["data"]["file_ext"];

			$return["file"] = $new_file_name;
		}
		return $return;
	}

	function pre_check_file_type($file_name, $file, $post_file_type, $allowed_extensions) {
		if (empty($allowed_extensions)) {
			return false;
		}
		$extension = strtolower($this->get_extension($file_name));
		
		if(!in_array($extension, $allowed_extensions)){
			return false;
		}

		$ext_in_allowed = false;

		///// следующим шагом пытаемся определить mime-Тип с поммощью fileinfo, если настройка включена
		$use_fileinfo = $this->pg_module->get_module_config('file_uploads', 'use_fileinfo');

		if ($use_fileinfo) {
			$file_type = '';

			if (function_exists('finfo_open')) {
				$_fileinfo = @finfo_open(FILEINFO_MIME);
				if ($_fileinfo) {
					$finfo_data = finfo_file($_fileinfo, $file);
					$t = explode(";", $finfo_data);
					$file_type = trim($t[0]);
				}
			} elseif (function_exists('mime_content_type')) {
				$file_type = mime_content_type($file);
			}

			if ($file_type) {
				$ext_in_allowed = false;
				foreach ($allowed_extensions as $ext) {
					$mime = $this->mimes[$ext];
					if (is_array($mime)) {
						if (in_array($file_type, $mime, true)) {
							$ext_in_allowed = true;
						}
					} else {
						if ($mime == $file_type) {
							$ext_in_allowed = true;
						}
					}
				}
				if ($ext_in_allowed) {
					return true;
				} else {
					return false;
				}
			}
		}else{
			////  проверяем $post_file_type в $allowed_extensions
			foreach ($allowed_extensions as $ext) {
				$mime = $this->mimes[$ext];
				if (is_array($mime)) {
					if (in_array($post_file_type, $mime, true)) {
						$ext_in_allowed = true;
					}
				} else {
					if ($mime == $post_file_type) {
						$ext_in_allowed = true;
					}
				}
			}
	
			if (!$ext_in_allowed) {
				return false;
			}
			
		}

		return true;
	}

	function format_filename($filename) {
		$path_parts = pathinfo($filename);
		$extension = $path_parts["extension"];
		$str = substr($filename, 0, -(strlen($extension) + 1));
		$str = preg_replace('/\s+/', '-', $str);
		$str = preg_replace('/[^a-z0-9\-_]/i', '', $str);
		if (empty($str)) {
			$return = $this->generate_filename("." . $extension);
		} else {
			$return = $str . '.' . $extension;
		}
		return $return;
	}

	function generate_filename($file_type) {
		srand();
		return substr(md5(date('Y-m-d H:i:s').rand(1, 9999)), 0, 10) . $file_type;
	}

	function upload_exist($config_gid, $postfix, $file_path) {
		$return["errors"] = array();
		$return["file"] = "";

		$config_data = $this->get_config($config_gid);

		$this->CI->load->helper('upload');
		$path = $this->get_media_path($config_gid, $postfix);
		if (!is_dir($path)) {
			@mkdir($path, 0777, TRUE);
		}

		$path_parts = pathinfo($file_path);
		$file_return = array(
			"error" => "",
			"data" => array(
				'file_name' => $path_parts["basename"],
				'file_ext' => "." . $path_parts["extension"],
			),
		);
		@copy($file_path, $path . $path_parts["basename"]);

		if ($file_return["error"] != '') {
			$return["errors"] = $file_return["error"];
		} else {
			$file_name = $file_return["data"]["file_name"];
			$file_name_ext = $file_return["data"]["file_ext"];

			switch ($config_data["name_format"]) {
				case "generate": $new_file_name = $this->generate_filename($file_name_ext);
					break;
				case "format": $new_file_name = $this->format_filename($file_name);
					break;
			}

			if ($file_name != $new_file_name) {
				@copy($path . $file_name, $path . $new_file_name);
				@unlink($path . $file_name);
			}

			$return["file"] = $new_file_name;
		}

		return $return;
	}

	function validate_upload($config_gid, $upload_gid) {
		$return["errors"] = array();
		$return["file"] = "";

		$config_data = $this->get_config($config_gid);

		$this->CI->load->helper('upload');

		//// upload src file
		$upload_config = array(
			'max_size' => $config_data["max_size"]/1024,
			'use_file_type_check' => false,
			'allowed_types' => $config_data["file_formats_str"],
			'overwrite' => true
		);

		$type_check = $this->pre_check_file_type($_FILES[$upload_gid]['name'], $_FILES[$upload_gid]['tmp_name'], $_FILES[$upload_gid]['type'], $config_data["file_formats"]);

		if (!$type_check) {
			$file_return["error"][] = "invalid filetype";
		} else {
			$file_return = validate_file($upload_gid, $upload_config);
		}

		return $file_return;
	}

	function format_upload($config_gid, $postfix, $file_name = '') {
		if (!empty($file_name)) {
			$config_data = $this->get_config($config_gid);
			$path = $this->get_media_path($config_gid, $postfix);
			$url = $this->get_media_url($config_gid, $postfix);
			$upload = array(
				"path" => $path,
				"url" => $url,
				"file_name" => $file_name,
				"file_path" => $path . $file_name,
				"file_url" => $url . $file_name
			);
			return $upload;
		}
	}

	function delete_upload($config_gid, $postfix, $file_name) {
		$config_data = $this->get_config($config_gid);

		$path = $this->get_media_path($config_gid, $postfix);
		$file = $path . $file_name;
		if (file_exists($file))
			@unlink($file);
	}

	function set_error($msg) {
		$this->CI->lang->load('upload');

		if (is_array($msg)) {
			foreach ($msg as $val) {
				$msg = ($this->CI->lang->line($val) == FALSE) ? $val : $this->CI->lang->line($val);
				$this->error_msg[] = $msg;
				log_message('error', $msg);
			}
		} else {
			$msg = ($this->CI->lang->line($msg) == FALSE) ? $msg : $this->CI->lang->line($msg);
			$this->error_msg[] = $msg;
			log_message('error', $msg);
		}
	}

	function get_extension($filename) {
		$x = explode('.', $filename);
		return end($x);
	}

}
