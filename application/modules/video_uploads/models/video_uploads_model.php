<?php
/**
* Video uploads main model
* 
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

class Video_uploads_model extends Model
{
	var $CI;
	var $DB;
	var $media_path = "";
	var $media_url = "";
	var $media_type = "";

	var $config_cache = array();
	var $mimes = array();
	/**
	 * Constructor
	 *
	 * @return Uploads object
	 */
	function __construct()
	{
		parent::Model();
		$this->CI = & get_instance();
		$this->media_path = SITE_PHYSICAL_PATH . UPLOAD_DIR . 'video/';
		$this->media_url = SITE_VIRTUAL_PATH . UPLOAD_DIR . 'video/';
		
		if (count($this->mimes) == 0) {
			$this->CI->load->library('upload');
			$this->CI->upload->mimes_types('');
			$this->mimes = $this->CI->upload->mimes;
		}
	}
	
	public function get_media_path($config_gid, $postfix=''){
		if(!empty($postfix)) $postfix = $postfix . '/';
		$path = $this->media_path . str_replace('//', '/', $config_gid . '/' . $postfix);
		return $path;
	}

	public function get_media_url($config_gid, $postfix=''){
		if(!empty($postfix)) $postfix = $postfix . '/';
		return $this->media_url . $config_gid . '/' . $postfix;
	}
	
	public function get_config($config_gid){
		if(empty($this->config_cache[$config_gid])){
			$this->config_cache[$config_gid] = array();
			$this->CI->load->model('video_uploads/models/Video_uploads_config_model');
			$this->config_cache[$config_gid] = $this->CI->Video_uploads_config_model->get_config_by_gid($config_gid);
		}
		return $this->config_cache[$config_gid];
	}
	
	
	public function upload($config_gid, $postfix, $upload_gid, $object_id, $file_data, $name_format = 'format'){
		$return["errors"] = array();
		$return["file"] = "";
		$file_return = array('error' => '');

		$config_data = $this->get_config($config_gid);

		$this->CI->load->helper('upload');
		$path = $this->get_media_path($config_gid, $postfix);
		if (!is_dir($path)) {
			@mkdir($path, 0777, TRUE);
			@chmod($path, 0777);
		}

		//// upload src file
		$upload_config = array(
			'max_size' => $config_data["max_size"]/1024, //// in kb
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
			switch($name_format){
				case "generate": $new_file_name = $this->generate_filename($file_name_ext); break;
				case "format":
				default: $new_file_name = $this->format_filename($file_name); break;
			}
			$file_mime_type = $this->get_validate_file_type($_FILES[$upload_gid]['tmp_name'], $_FILES[$upload_gid]['type']);
			
			$_FILES[$upload_gid]['name'] = $new_file_name;
			$file_return = upload_file($upload_gid, $path, $upload_config);
		}
		if ($file_return["error"] != '') {
			$return["errors"] = $file_return["error"];
		} else {
			$file_name = $file_return["data"]["file_name"];
			$file_name_ext = $file_return["data"]["file_ext"];
			@chmod($path . $new_file_name, 0777);

			$return["file"] = $new_file_name;
			
			$file_data["ext"] = $this->get_extension($new_file_name);
			$file_data["type"] = $file_mime_type;
			$this->CI->load->model('video_uploads/models/Video_uploads_process_model');	
			$this->CI->Video_uploads_process_model->prepare($new_file_name, $path, $file_data, $object_id, $config_gid);
		}
		return $return;
	}
	
	private function get_validate_file_type($file, $post_file_type){
		$file_type = $post_file_type;
		///// следующим шагом пытаемся определить mime-Тип с поммощью fileinfo, если настройка включена
		$use_fileinfo = $this->pg_module->get_module_config('video_uploads', 'use_fileinfo');

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
		}
		return $file_type;
	}
	
	private function pre_check_file_type($file_name, $file, $post_file_type, $allowed_extensions) {
		if (empty($allowed_extensions)) {
			return false;
		}
		$extension = strtolower($this->get_extension($file_name));
		
		if(!in_array($extension, $allowed_extensions)){
			return false;
		}

		$ext_in_allowed = false;

		///// пытаемся определить mime-Тип с поммощью fileinfo, если настройка включена
		$use_fileinfo = $this->pg_module->get_module_config('video_uploads', 'use_fileinfo');

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
			//// проверяем $post_file_type в $allowed_extensions
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
	
	
	public function upload_exists($config_gid, $postfix, $file_path, $object_id, $file_data){
		$return["errors"] = array();
		$return["file"] = "";

		$config_data = $this->get_config($config_gid);

		$this->CI->load->helper('upload');
		$path = $this->get_media_path($config_gid, $postfix);
		if (!is_dir($path)) {
			@mkdir($path, 0777, TRUE);
			@chmod($path, 0777);
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
			$new_file_name = $this->format_filename($file_name);
			
			if ($file_name != $new_file_name) {
				@copy($path . $file_name, $path . $new_file_name);
				@unlink($path . $file_name);
			}
			@chmod($path . $new_file_name, 0777);
		
			$file_mime_type = $this->get_validate_file_type($_FILES[$upload_gid]['tmp_name'], 'video/avi');
	
			$return["file"] = $new_file_name;
			
			$file_data["ext"] = $this->get_extension($new_file_name);
			$file_data["type"] = $file_mime_type;
			$this->CI->load->model('video_uploads/models/Video_uploads_process_model');	
			$this->CI->Video_uploads_process_model->prepare($new_file_name, $path, $file_data, $object_id, $config_gid);
		}

		return $return;
		
	}
	
	public function validate_upload($config_gid, $upload_gid) {
		$file_return = array('error'=>array());

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
	
	/**
	 * Format video object
	 * 
	 * Possible upload types: local or youtube
	 * 
	 * @param string $config_gid configuration GUID
	 * @param string $postfix path postfix
	 * @param string $video file name of video
	 * @param string $image file name of thumbnail
	 * @param string $upload_type upload type
	 * @param string $isHTML5 use html5 player
	 * @return array
	 */
	public function format_upload($config_gid, $postfix, $video, $image, $upload_type='', $isHTML5=false){
		$upload = array();
		if(!$video){
			return array();
		}
		
		$config_data = $this->get_config($config_gid);
		$upload_type = (!$upload_type)?$config_data["upload_type"]:$upload_type;

		$path = $this->get_media_path($config_gid, $postfix);
		$url = $this->get_media_url($config_gid, $postfix);
		
		if($upload_type == 'youtube'){
			$this->CI->load->model('video_uploads/models/Video_uploads_youtube_model');	
			$upload = array(
				"file_name" => $video,
				"width" => $config_data["youtube_settings"]["width"],
				"height" => $config_data["youtube_settings"]["height"],
				"embed" => $this->CI->Video_uploads_youtube_model->get_default_embed($video, $config_data["youtube_settings"]["width"], $config_data["youtube_settings"]["height"])
			);
		}
		
		if($upload_type == 'local'){
			$this->CI->load->model('video_uploads/models/Video_uploads_local_model');	
			$upload = array(
				"path" => $path,
				"url" => $url,
				"file_name" => $video,
				"file_path" => $path.$video,
				"file_url" => $url.$video,
				"width" => $config_data["local_settings"]["width"],
				"height" => $config_data["local_settings"]["height"],
				"embed" => $this->CI->Video_uploads_local_model->get_default_embed($url.$video, $config_data["local_settings"]["width"], $config_data["local_settings"]["height"], $isHTML5)
			);
		}
		
		if($upload_type == 'embed'){
			$this->load->library('VideoEmbed');
			$video['width'] = $config_data['local_settings']['width'];
			$video['height'] = $config_data['local_settings']['height'];
			$upload = array(
				'file_name' => 'embed',
				'width' => $config_data['local_settings']['width'],
				'height' => $config_data['local_settings']['height'],
				'embed' => $this->videoembed->get_embed_code($video),
			);
		}
		
		if($config_data["use_thumbs"]){
			if(!empty($image)){
				$thumbs = $this->format_thumbs($image, $url, $path, $config_data["thumbs_settings"]);
			}elseif($config_data["default_img"]){
				$thumbs = $this->format_thumbs($config_data["default_img"], $config_data["default_img_data"]["url"], $config_data["default_img_data"]["path"], $config_data["thumbs_settings"]);
			}
			if(!empty($thumbs)){
				$upload = array_merge($upload, $thumbs);
			}
		}
		return $upload;
	}

	public function delete_upload($config_gid, $postfix, $video, $image='', $upload_type=''){
		$config_data = $this->get_config($config_gid);
		if(!empty($upload_type)){
			$config_data["upload_type"] = $upload_type;
		}		
		
		if($config_data["upload_type"] == 'local'){
			$path = $this->get_media_path($config_gid, $postfix);
			$file = $path . $video;
			
			$arr_type = array('flv', 'mp4', 'webm', 'ogg');
			
			$pathinfo = pathinfo($file);
			
			foreach($arr_type as $ext){
				
				$file = $pathinfo['dirname'] . '/' . $pathinfo['filename'] . '.' . $ext;
				if(file_exists($file)){
					@unlink($file);
				}
			}
			
			if($image){
				$this->delete_thumbs($image, $path, $config_data["thumbs_settings"]);
			}
		}
		if($config_data["upload_type"] == 'youtube'){
			$this->CI->load->model('video_uploads/models/Video_uploads_youtube_model');	
			$this->CI->Video_uploads_youtube_model->delete_method($video);
		}
		return;
	}

	private function format_filename($filename) {
		$path_parts = pathinfo($filename);
		$extension = $path_parts["extension"];
		$str = substr($filename, 0, -(strlen($extension) + 1));
		$str = preg_replace('/\s+/', '-', $str);
		$str = preg_replace('/[^a-z0-9\-_]/i', '', $str);
		if (empty($str) || strlen($str) < 3) {
			$return = $this->generate_filename("." . $extension);
		} else {
			$return = $str . '.' . $extension;
		}
		return $return;
	}

	private function generate_filename($file_type) {
		srand();
		return substr(md5(date('Y-m-d H:i:s').rand(1, 9999)), 0, 10) . $file_type;
	}
	
	private function get_extension($filename) {
		$x = explode('.', $filename);
		return end($x);
	}
		
	public function create_thumbs($file_name, $file_path, $thumbs_data){
		if(empty($thumbs_data)) return false;
		foreach($thumbs_data as $thumb){
			$thumb_file = $file_path . $thumb["gid"] . '-' . $file_name;
			if(file_exists($thumb_file)) unlink($thumb_file);
			copy($file_path.$file_name, $thumb_file);
			$this->action($thumb_file, $thumb);
		}
	}
	
	public function format_thumbs($file_name, $url, $path, $thumbs_data){
		$upload = array();
		foreach($thumbs_data as $thumb_data){
			$upload["thumbs"][$thumb_data["gid"]] = $url . $thumb_data["gid"] . '-' . $file_name;
			$upload["thumbs_data"][$thumb_data["gid"]] = array(
				"file_name" => $thumb_data["gid"] . '-' . $file_name,
				"file_path" => $path. $thumb_data["gid"] . '-' . $file_name,
				"file_url" => $url. $thumb_data["gid"] . '-' . $file_name
			);
		}
		return $upload;
	}

	public function delete_thumbs($file_name, $file_path, $thumbs_data){
		$image = $file_path . $file_name;
		if(file_exists($image))@unlink($image);

		if(isset($thumbs_data) && !empty($thumbs_data)){
			foreach($thumbs_data as $thumb){
				$thumb = $file_path . $thumb["gid"] . '-' . $file_name;
				if(file_exists($thumb))@unlink($thumb);
			}
		}
	}

	////// graph function
	private function action($file, $thumb=array(), $dynamic_output=false){
		@ini_set("memory_limit", "512M");
		$this->CI->load->library('image_lib');
		$this->CI->image_lib->clear();
		$error = $this->resize($file, $thumb["width"], $thumb["height"], 'in', true, '000000', $dynamic_output);
		return $error;
	}

	private function resize($file, $width, $height, $type='in', $maintain=false, $color=false, $dynamic_output=false){
		$this->CI->load->library('image_lib');
		$this->CI->image_lib->clear();
		
		$resize_config['source_image'] = $file;
		$resize_config['dynamic_output'] = $dynamic_output;
		$resize_config['create_thumb'] = false;
		$resize_config['maintain_ratio'] = $maintain;
		$resize_config['width'] = $width;
		$resize_config['height'] = $height;

		if($color !== false){
			$resize_config['background'] = true;
			$resize_config['bgcolor'] = $color;
			$resize_config['bgwidth'] = $width;
			$resize_config['bgheight'] = $height;
		}

		list($twidth, $theight) = getimagesize($file);
		$k = $width/$height;
		$k1 = $twidth/$theight;

		switch($type){
			case "in": $master = ($k1<=$k)?"height":"width"; break;
			case "out": $master = ($k1<=$k)?"width":"height"; break;
		}
		$resize_config['master_dim'] = $master;

		$this->CI->image_lib->initialize($resize_config);
		$result = $this->CI->image_lib->resize();
		return $this->CI->image_lib->error_msg;
	}
	
	public function upload_embed_video_image($video_config_gid, $data){
		$service = unserialize($data['media_video_data']);
		$config_data = $this->get_config($video_config_gid);
		$new_file_name = $data['media_video_image'];
		$path = $this->get_media_path($video_config_gid, $data['id_owner']);
		if (!is_dir($path)) {
			@mkdir($path, 0777, TRUE);
			@chmod($path, 0777);
		}
		switch($service['data']['service']){
			case "youtu.be":
			case "youtube.com": {
				$images = 'http://img.youtube.com/vi/'.$service['data']['video'].'/0.jpg';
				$check_file = @get_headers($images);
				if(preg_match("|200|", $check_file[0])){
					$media_video_image = file_get_contents($images);
					if(@file_put_contents($path.$new_file_name, $media_video_image)){
						$this->create_thumbs($new_file_name, $path, $config_data["thumbs_settings"]);
					} else {
						$data['media_video_image'] = '';
					}
				} else {
					$data['media_video_image'] = '';
				}
				break;
			}
			case "vimeo.com": {
				if ($xml = simplexml_load_file('http://vimeo.com/api/v2/video/'.$service['data']['video'].'.xml')) {
					$images = $xml->video->thumbnail_large;
					$check_file = @get_headers($images);
					if(preg_match("|200|", $check_file[0])){
						$media_video_image = file_get_contents($images);
						if(@file_put_contents($path.$new_file_name, $media_video_image)){
							$this->create_thumbs($new_file_name, $path, $config_data["thumbs_settings"]);
						} else {
							$data['media_video_image'] = '';
						}
					} else {
						$data['media_video_image'] = '';
					}
				}
				break;
			}
		}
		return $data;
	}
	
	public function delete_embed_video_image($config_gid, $postfix, $image){
		$config_data = $this->get_config($config_gid);
		$path = $this->get_media_path($config_gid, $postfix);
		$this->delete_thumbs($image, $path, $config_data["thumbs_settings"]);
	}

}
