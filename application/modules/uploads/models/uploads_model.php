<?php
/**
* Uploads main model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

class Uploads_model extends Model
{
	var $CI;
	var $DB;
	var $media_path = "";
	var $media_url = "";
	var $media_type = "";

	var $config_cache = array();
	
	/**
	 * Mime types
	 */
	var $mimes = array();
	
	/**
	 * Constructor
	 *
	 * @return Uploads object
	 */
	function Uploads_model()
	{
		parent::Model();
		$this->CI = & get_instance();
		$this->media_path = SITE_PHYSICAL_PATH . UPLOAD_DIR;
		$this->media_url = SITE_VIRTUAL_PATH . UPLOAD_DIR;
		
		if (count($this->mimes) == 0) {
			$this->CI->load->library('upload');
			$this->CI->upload->mimes_types('');
			$this->mimes = $this->CI->upload->mimes;
		}
	}

	function get_media_path($config_gid, $postfix=''){
		if(!empty($postfix)) $postfix = $this->formatPostfix($postfix);
		$path = $this->media_path . str_replace('//', '/', $config_gid . '/' . $postfix);
		return $path;
	}

	function get_media_url($config_gid, $postfix=''){
		if(!empty($postfix)) $postfix = $this->formatPostfix($postfix);
		return $this->media_url . $config_gid . '/' . $postfix;
	}
	
	function formatPostfix($postfix = '')
	{
        $postfix = rtrim($postfix, '/');
		$result = $postfix . '/';
		if (strval(intval($postfix)) == $postfix) {
			$result = (($postfix / 10000) % 10000) . '/' . (($postfix / 1000) % 1000) . '/' . (($postfix / 100) % 100) . '/' . $postfix . '/';
		}
		return $result;
	}


	function get_config($config_gid){
		if(empty($this->config_cache[$config_gid])){
			$this->config_cache[$config_gid] = array();
			$this->CI->load->model('uploads/models/Uploads_config_model');
			$config_data = $this->CI->Uploads_config_model->get_config_by_gid($config_gid);
			if(!empty($config_data)){
				$config_data = $this->CI->Uploads_config_model->format_config($config_data);
				$config_data['thumbs'] = $this->CI->Uploads_config_model->get_config_thumbs($config_data["id"]);
				
				$this->config_cache[$config_gid] = $config_data;
			}
		}
		return $this->config_cache[$config_gid];
	}

	function upload($config_gid, $postfix, $upload_gid){
		$return["errors"] = array(); $return["file"] = "";

		$config_data = $this->get_config($config_gid);

		$this->CI->load->helper('upload');
		$path = $this->get_media_path($config_gid, $postfix);
		if(!is_dir($path)){
			@mkdir($path, 0777, TRUE);
		}

		//// upload src file
		$upload_config = array(
			'max_size' => $config_data["max_size"]/1024,
			'min_width' => $config_data["min_width"],
			'min_height' => $config_data["min_height"],
			'max_width' => $config_data["max_width"],
			'max_height' => $config_data["max_height"],
			'allowed_types' => $config_data["file_formats_str"],
			'overwrite' => true

		);
		
		$type_check = $this->pre_check_file_type($_FILES[$upload_gid]['name'], $_FILES[$upload_gid]['tmp_name'], $_FILES[$upload_gid]['type'], $config_data["file_formats"]);

		if (!$type_check || !isset($_FILES[$upload_gid]['name'])) {
			$image_return["error"] = "invalid filetype";
		} else {
			$image_return = upload_file($upload_gid, $path, $upload_config);

			if($image_return["error"] != ''){
				$return["errors"] = $image_return["error"];
			}else{
				$file_name = $image_return["data"]["file_name"];
				$file_name_ext = $image_return["data"]["file_ext"];

				switch($config_data["name_format"]){
					case "generate": $new_file_name = $this->generate_filename($file_name_ext); break;
					case "format": $new_file_name = $this->format_filename($file_name); break;
				}

				@copy($path.$file_name, $path.$new_file_name);
				if($file_name != $new_file_name) @unlink($path.$file_name);

				$return["file"] = $new_file_name;

				if(isset($config_data["thumbs"]) && !empty($config_data["thumbs"])){
					$this->create_thumbs($new_file_name, $path, $config_data["thumbs"]);
				}
			}
		}

		return $return;
	}

	function upload_url($config_gid, $postfix, $file_url, $upload_gid){
		$return["errors"] = array(); $return["file"] = "";

		$path_parts = pathinfo($file_url);
		
		if(empty($path_parts["extension"])) return $return;
		
		$image_return = array(
			"error" => "",
			"data" => array(
				'file_name' => $path_parts["basename"],
				'file_ext' => ".".$path_parts["extension"],
			),
		);
		
		$config_data = $this->get_config($config_gid);

		$this->CI->load->helper('upload');
		$path = $this->get_media_path($config_gid, $postfix);
		if(!is_dir($path)){
			@mkdir($path, 0777, TRUE);
		}

		//// upload src file
		/*$upload_config = array(
			'max_size' => $config_data["max_size"]/1024,
			'min_width' => $config_data["min_width"],
			'min_height' => $config_data["min_height"],
			'max_width' => $config_data["max_width"],
			'max_height' => $config_data["max_height"],
			'allowed_types' => $config_data["file_formats_str"],
			'overwrite' => true,
		);*/

		
		$content = file_get_contents($file_url);
		file_put_contents($path.$path_parts["basename"], $content);
		unset($content);
		
		//$image_return = upload_file($upload_gid, $path, $upload_config);
		
		//if($image_return["error"] != ''){
		//	$return["errors"] = $image_return["error"];
		//}else{
			$file_name = $image_return["data"]["file_name"];
			$file_name_ext = $image_return["data"]["file_ext"];

			switch($config_data["name_format"]){
				case "generate": $new_file_name = $this->generate_filename($file_name_ext); break;
				case "format": $new_file_name = $this->format_filename($file_name); break;
			}

			if($file_name != $new_file_name){
				@copy($path.$file_name, $path.$new_file_name);
				@unlink($path.$file_name);
			}

			$return["file"] = $new_file_name;

			if(isset($config_data["thumbs"]) && !empty($config_data["thumbs"])){
				$this->create_thumbs($new_file_name, $path, $config_data["thumbs"]);
			}
		//}

		return $return;
	}
	
	function upload_exist($config_gid, $postfix, $file_path){
		$return["errors"] = array(); $return["file"] = "";

		$config_data = $this->get_config($config_gid);

		$this->CI->load->helper('upload');
		$path = $this->get_media_path($config_gid, $postfix);
		if(!is_dir($path)){
			@mkdir($path, 0777, TRUE);
		}

		//// upload src file
		$upload_config = array(
			'max_size' => $config_data["max_size"]/1024,
			'min_width' => $config_data["min_width"],
			'min_height' => $config_data["min_height"],
			'max_width' => $config_data["max_width"],
			'max_height' => $config_data["max_height"],
			'allowed_types' => $config_data["file_formats_str"],
			'overwrite' => true,
		);

		$path_parts = pathinfo($file_path);
		$image_return = array(
			"error" => "",
			"data" => array(
				'file_name' => $path_parts["basename"],
				'file_ext' => ".".$path_parts["extension"],
			),
		);
		
		@copy($file_path, $path.$path_parts["basename"]);


		if($image_return["error"] != ''){
			$return["errors"] = $image_return["error"];
		}else{
			$file_name = $image_return["data"]["file_name"];
			$file_name_ext = $image_return["data"]["file_ext"];

			switch($config_data["name_format"]){
				case "generate": $new_file_name = $this->generate_filename($file_name_ext); break;
				case "format": $new_file_name = $this->format_filename($file_name); break;
			}

			if($file_name != $new_file_name){
				@copy($path.$file_name, $path.$new_file_name);
				@unlink($path.$file_name);
			}

			$return["file"] = $new_file_name;

			if(isset($config_data["thumbs"]) && !empty($config_data["thumbs"])){
				$this->create_thumbs($new_file_name, $path, $config_data["thumbs"]);
			}
		}

		return $return;
	}
	
	/**
	 * Upload slide show from sources
	 * @param string $config_gid upload configuration guid
	 * @param string $postfix upload postfix
	 * @param string $file_path file path
	 * @param array $sources source images
	 */
	function upload_anim($config_gid, $postfix, $file_path, $sources){
		$return["errors"] = array(); $return["file"] = "";

		$config_data = $this->get_config($config_gid);
		
		$path = $this->get_media_path($config_gid, $postfix);
		if(!is_dir($path)){
			@mkdir($path, 0777, TRUE);
		}
		
		$path_parts = pathinfo($path.$file_path);
		
		$image_return = array(
			"error" => "",
			"data" => array(
				'file_ext' => ".gif" ,
			),
		);
		
		if(!empty($image_return["error"])){
			$return["errors"] = $image_return["error"];
		}else{
			$file_name_ext = $image_return["data"]["file_ext"];

			/*switch($config_data["name_format"]){
				case "generate": $new_file_name = $this->generate_filename($file_name_ext); break;
				case "format": $new_file_name = $this->format_filename($file_name); break;
			}*/
	
			if($path_parts["extension"]){
				$basename = preg_replace("/\.".preg_quote($path_parts["extension"])."$/i", '', $path_parts["basename"]);
			}else{
				$basename = $path_parts["extension"];
			}
			$new_file_name = $basename.$file_name_ext;

			$this->create_anim($new_file_name, $path, $config_data["thumbs"], $sources);
			
			$return["file"] = $new_file_name;
		}
		
		return $return;
	}

	function validate_upload($config_gid, $upload_gid){
		$return["errors"] = array(); $return["file"] = "";

		$config_data = $this->get_config($config_gid);

		$this->CI->load->helper('upload');

		//// upload src file
		$upload_config = array(
			'max_size' => $config_data["max_size"]/1024,
			'min_width' => $config_data["min_width"],
			'min_height' => $config_data["min_height"],
			'max_width' => $config_data["max_width"],
			'max_height' => $config_data["max_height"],
			'allowed_types' => $config_data["file_formats_str"],
			'overwrite' => true,
		);

		$type_check = $this->pre_check_file_type($_FILES[$upload_gid]['name'], $_FILES[$upload_gid]['tmp_name'], $_FILES[$upload_gid]['type'], $config_data["file_formats"]);

		if (!$type_check) {
			$image_return["error"][] = "invalid filetype";
		} else {
			$image_return = validate_file($upload_gid, $upload_config);
		}
		
		return $image_return;
	}

	function format_upload($config_gid, $postfix, $file_name=''){
		if(empty($file_name)){
			return $this->format_default_upload($config_gid);
		}else{
			$config_data = $this->get_config($config_gid);
            
            if (preg_match('#http(s)?:#', $file_name)) {
                $path = $url = dirname($file_name);
                $file_name = str_replace($url . "/", '', $file_name);
                $path .= DIRECTORY_SEPARATOR;
                $url .= "/";
            } else {
                $path = $this->get_media_path($config_gid, $postfix);
                $url = $this->get_media_url($config_gid, $postfix);
            }
			$upload = array(
				"path" => $path,
				"url" => $url,
				"file_name" => $file_name,
				"file_path" => $path.$file_name,
				"file_url" => $url.$file_name
			);
			if(isset($config_data["thumbs"]) && !empty($config_data["thumbs"])){
				foreach($config_data["thumbs"] as $thumb_data){
					if($thumb_data["animation"]){
						$path_parts = pathinfo($file_name);
						if($path_parts["extension"]){
							$basename = preg_replace("/\.".preg_quote($path_parts["extension"])."$/i", '', $path_parts["basename"]);
						}else{
							$basename = $path_parts["extension"];
						}
						$thumb_file_name = $basename.".gif";
					}else{
						$thumb_file_name = $file_name;
					}
					$upload["thumbs"][$thumb_data["prefix"]] = $url . $thumb_data["prefix"] . '-' . $thumb_file_name;
					$upload["thumbs_data"][$thumb_data["prefix"]] = array(
						"file_name" => $thumb_data["prefix"] . '-' . $thumb_file_name,
						"file_path" => $path. $thumb_data["prefix"] . '-' . $thumb_file_name,
						"file_url" => $url. $thumb_data["prefix"] . '-' . $thumb_file_name
					);
				}
			}
			
			return $upload;
		}
	}

	function format_default_upload($config_gid){
		$config_data = $this->get_config($config_gid);
		$upload = array(
			"path" => isset($config_data["default_path"])?$config_data["default_path"]:'',
			"url" => isset($config_data["default_url"])?$config_data["default_url"]:'',
			"file_name" => isset($config_data["default_img"])?$config_data["default_img"]:'',
			"file_path" => isset($config_data["default_img_path"])?$config_data["default_img_path"]:"",
			"file_url" => isset($config_data["default_img_url"])?$config_data["default_img_url"]:'',
		);
		if(isset($config_data["thumbs"]) && !empty($config_data["thumbs"])){
			foreach($config_data["thumbs"] as $thumb_data){
				$upload["thumbs"][$thumb_data["prefix"]] = $config_data["default_url"] . $thumb_data["prefix"] . '-' . $config_data["default_img"];
				$upload["thumbs_data"][$thumb_data["prefix"]] = array(
					"file_name" => $thumb_data["prefix"] . '-' . $config_data["default_img"],
					"file_path" => $config_data["default_path"] . $thumb_data["prefix"] . '-' . $config_data["default_img"],
					"file_url" => $config_data["default_url"] . $thumb_data["prefix"] . '-' . $config_data["default_img"]
				);
			}
		}
		return $upload;
	}

	function delete_upload($config_gid, $postfix, $file_name){
		$config_data = $this->get_config($config_gid);

		$path = $this->get_media_path($config_gid, $postfix);
		$file = $path . $file_name;
		if(file_exists($file))@unlink($file);

		if(isset($config_data["thumbs"]) && !empty($config_data["thumbs"])){
			foreach($config_data["thumbs"] as $thumb_data){
				if($thumb_data["animation"]){
					$path_parts = pathinfo($file_name);
					if($path_parts["extension"]){
						$basename = preg_replace("/\.".preg_quote($path_parts["extension"])."$/i", '', $path_parts["basename"]);
					}else{
						$basename = $path_parts["extension"];
					}
					$thumb = $path . $thumb_data["prefix"] . '-' . $basename.".gif";
				}else{
					$thumb = $path . $thumb_data["prefix"] . '-' . $file_name;
				}
				if(file_exists($thumb))@unlink($thumb);
			}
		}
	}

	function recrop_upload($config_gid, $postfix, $file_name, $recrop_data, $thumb_prefix=""){
		$config_data = $this->get_config($config_gid);

		@ini_set("memory_limit", "512M");
		$this->CI->load->library('image_lib');

		$path = $this->get_media_path($config_gid, $postfix);
		$src = $path . $file_name;
		$temp_src = $path . 'recrop_temp_' . $file_name;
		@copy($src, $temp_src);

		$resize_config['source_image'] = $temp_src;
		$resize_config['create_thumb'] = false;
		$resize_config['width'] = $recrop_data['width'];
		$resize_config['height'] = $recrop_data['height'];
		$resize_config['maintain_ratio'] = false;
		$resize_config['x_axis'] = $recrop_data['x1'];
		$resize_config['y_axis'] = $recrop_data['y1'];

		$this->CI->image_lib->initialize($resize_config);
		$this->CI->image_lib->crop();

		if($thumb_prefix){
			foreach($config_data["thumbs"] as $i => $thumb_config){
				if($thumb_config["prefix"] != $thumb_prefix) 
					unset($config_data["thumbs"][$i]);
			}
		}
		
		if(isset($config_data["thumbs"]) && !empty($config_data["thumbs"])){
			$this->create_thumbs('recrop_temp_' . $file_name, $path, $config_data["thumbs"]);
			foreach($config_data["thumbs"] as $i => $thumb_config){
				@copy($path.$thumb_config["prefix"]."-recrop_temp_".$file_name, $path.$thumb_config["prefix"]."-".$file_name);
				@unlink($path.$thumb_config["prefix"]."-recrop_temp_".$file_name);
			}
		}
		@unlink($temp_src);
	}
	
	/**
	 * Rotate upload
	 * @param string $config_gid configuration type GUID
	 * @param string $prefix file name prefix
	 * @param string $file_name file name
	 * @param integer/string $angle rotate angle
	 */
	public function rotate_upload($config_gid, $postfix, $file_name, $angle){
		$config_data = $this->get_config($config_gid);

		@ini_set("memory_limit", "512M");
		$this->CI->load->library('image_lib');

		$path = $this->get_media_path($config_gid, $postfix);
		$src = $path . $file_name;
		$temp_src = $path . 'rotate_temp_' . $file_name;
		@copy($src, $temp_src);
		
		$rotate_config['source_image'] = $temp_src;
		$rotate_config['create_thumb'] = false;
		$rotate_config['rotation_angle'] = intval($angle);
		
		$this->CI->image_lib->initialize($rotate_config);
		$this->CI->image_lib->rotate();
	
		if(isset($config_data["thumbs"]) && !empty($config_data["thumbs"])){
			$this->create_thumbs('rotate_temp_' . $file_name, $path, $config_data["thumbs"]);
			foreach($config_data["thumbs"] as $i => $thumb_config){
				@copy($path.$thumb_config["prefix"]."-rotate_temp_".$file_name, $path.$thumb_config["prefix"]."-".$file_name);
				@unlink($path.$thumb_config["prefix"]."-rotate_temp_".$file_name);
			}
		}
		
		@copy($temp_src, $src);		
		@unlink($temp_src);
	}
	
	function format_filename($filename){
		$path_parts = pathinfo($filename);
		$extension = $path_parts["extension"];
		$str = substr($filename, 0, -(strlen($extension)+1));
		$str = preg_replace('/\s+/', '-', $str);
		$str = preg_replace('/[^a-z0-9\-]/i', '', $str);
		if(empty($str)){
			$return = $this->generate_filename(".".$extension);
		}else{
			$return = $str.'.'.$extension;
		}
		return $return;
	}

	function generate_filename($file_type){
		srand();
		return substr(md5(date('Y-m-d H:i:s').rand(1, 9999)), 0, 10) . $file_type;
	}

	function create_thumbs($file_name, $file_path, $thumbs_data, $animation=false){
		if(empty($thumbs_data)) return false;
		foreach($thumbs_data as $thumb_data){
			if(!$animation && $thumb_data["animation"]) continue;
			$thumb_file = $file_path . $thumb_data["prefix"] . '-' . $file_name;
			if(file_exists($thumb_file)) unlink($thumb_file);
			@copy($file_path.$file_name, $thumb_file);
			$this->action($thumb_file, $thumb_data);
			if($thumb_data['watermark_id']){
				$this->watermark($thumb_file, $thumb_data['watermark_id']);
			}
		}
	}

	function create_default($file_name, $file_path, $thumbs_data){
		if(empty($thumbs_data)) return false;
		foreach($thumbs_data as $thumb_data){
			$thumb_file = $file_path . $thumb_data["prefix"] . '-' . $file_name;
			if(file_exists($thumb_file)) unlink($thumb_file);
			copy($file_path.$file_name, $thumb_file);
			$this->action($thumb_file, $thumb_data);
		}
	}

	////// graph function
	function action($file, $thumb_data=array(), $dynamic_output=false){
		@ini_set("memory_limit", "512M");
		$this->CI->load->library('image_lib');
		$this->CI->image_lib->clear();

		switch($thumb_data["crop_param"]){
			case "resize":
				$error = (array)$this->resize($file, $thumb_data["width"], $thumb_data["height"], 'in', true, false, $thumb_data["effect"], $dynamic_output);
			break;
			case "color":
				$error = (array)$this->resize($file, $thumb_data["width"], $thumb_data["height"], 'in', true, $thumb_data["crop_color"], $thumb_data["effect"], $dynamic_output);
			break;
			case "crop":
				$error = (array)$this->crop($file, $thumb_data["width"], $thumb_data["height"], $dynamic_output, $thumb_data["effect"]);
			break;
			case "extend":
				$error = $this->resize($file, $thumb_data["width"], $thumb_data["height"], 'in', false, false, $thumb_data["effect"], $dynamic_output);
			break;
			case "rotate":
				$error = $this->crop($file, $thumb_data["width"], $thumb_data["height"], $dynamic_output, $thumb_data["effect"]);
				$error_rotate = (array)$this->rotate($file, $thumb_data["rotation_angle"], $dynamic_output);
				$error = array_merge($error, $error_rotate);
			break;
			case 'static_height':
				$error = (array)$this->resize($file, 0, $thumb_data["height"], 'in', true, false, $thumb_data["effect"], $dynamic_output);
			break;
		}
		return $error;
	}

	function resize($file, $width, $height, $type='in', $maintain=false, $color=false, $effect=false, $dynamic_output=false){
		$this->CI->load->library('image_lib');
		$this->CI->image_lib->clear();
		if(!is_file($file)) {
			log_message('ERROR', 'Not a file');
			return false;
		}
		
		if(!$width){
			list($twidth, $theight) = @getimagesize($file);
			$width = $twidth*$height/$theight;
		}
		
		if(!$height){
			list($twidth, $theight) = @getimagesize($file);
			$height = $theight*$width/$twidth;
		}
		
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

		if($effect !== false){
			switch($effect){
				case "grayscale": $resize_config['grayscale'] = true; break;
			}
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
		$this->CI->image_lib->resize();
		return $this->CI->image_lib->error_msg;
	}

	function crop($file, $width, $height, $dynamic_output=false, $effect=false){
		$this->CI->load->library('image_lib');
		$this->CI->image_lib->clear();

		list($twidth, $theight) = @getimagesize($file);
		
		if( ($twidth > $width) || ($theight > $height) ){
			$error = (array)$this->resize($file, $width, $height, 'out', true, false, $effect, $dynamic_output);
			if( !empty($error) ) return $error;
		
			list($twidth, $theight) = @getimagesize($file);
		
		}
		
		$x_axis = ceil(($twidth - $width)/2);
		$y_axis = ceil(($theight - $height)/2);

		$crop_config['source_image'] = $file;
		$crop_config['dynamic_output'] = $dynamic_output;
		$crop_config['create_thumb'] = false;
		$crop_config['width'] = $width;
		$crop_config['height'] = $height;
		$crop_config['x_axis'] = $x_axis;
		$crop_config['y_axis'] = $y_axis;
		$crop_config['maintain_ratio'] = false;
		$this->CI->image_lib->initialize($crop_config);
		$this->CI->image_lib->crop();
		return $this->CI->image_lib->error_msg;
	}
	
	function rotate($file, $rotation_angle, $dynamic_output=false){
		$this->CI->load->library('image_lib');
		$this->CI->image_lib->clear();
		
		$crop_config['source_image'] = $file;
		$crop_config['dynamic_output'] = $dynamic_output;
		$crop_config['create_thumb'] = false;
		$crop_config['rotation_angle'] = intval($rotation_angle);
		$this->CI->image_lib->initialize($crop_config);
		$this->CI->image_lib->rotate();
		return $this->CI->image_lib->error_msg;
	}

	function watermark($file, $wm_id=null, $data=array(), $dynamic_output=false){
		@ini_set("memory_limit", "512M");
		$this->CI->load->library('image_lib');
		$this->CI->load->model('uploads/models/Uploads_config_model');
		$this->CI->image_lib->clear();

		$wm_settings = array();
		if($wm_id){
			$wm_settings = $this->CI->Uploads_config_model->get_watermark_by_id($wm_id);
			if(empty($wm_settings)) return false;
			$wm_settings = $this->CI->Uploads_config_model->format_watermark($wm_settings);
		}
		if(!empty($data)){
			$wm_settings = array_merge($wm_settings, $data);
		}
		$wm_config['wm_opacity'] = $wm_settings["alpha"];
		$wm_config['dynamic_output'] = $dynamic_output;
		$wm_config['create_thumb'] = false;
		$wm_config['source_image'] = $file;
		$wm_config['padding'] = 5;

		switch($wm_settings["position_hor"]){
			case 'left': $wm_config['wm_hor_alignment'] = "L"; break;
			case 'right': $wm_config['wm_hor_alignment'] = "R"; break;
			case 'center': $wm_config['wm_hor_alignment'] = "C"; break;
		}
		switch($wm_settings["position_ver"]){
			case 'top': $wm_config['wm_vrt_alignment'] = "T"; break;
			case 'middle': $wm_config['wm_vrt_alignment'] = "M"; break;
			case 'bottom': $wm_config['wm_vrt_alignment'] = "B"; break;
		}

		if($wm_settings["wm_type"] == 'img'){
			$wm_config['wm_type'] = "overlay";
			$wm_config['wm_overlay_path'] = $wm_settings["img_path"];
		}else{
			$wm_config['wm_type'] = "text";
			$wm_config['wm_text'] = $wm_settings['font_text'];
			$wm_config['wm_font_path'] = $this->CI->Uploads_config_model->fonts_folder . $wm_settings['font_face'].'.ttf' ;
			$wm_config['wm_font_size'] = $wm_settings['font_size'];
			$wm_config['wm_font_color'] = $wm_settings['font_color'];
			$wm_config['wm_shadow_color'] = $wm_settings['shadow_color'];
			$wm_config['wm_shadow_distance'] = $wm_settings['shadow_distance'];
		}

		$this->CI->image_lib->initialize($wm_config);
		$result = $this->CI->image_lib->watermark();
		return $this->CI->image_lib->error_msg;
	}
	
	/**
	 * Convert images to gif format
	 * @param string $file_name file name
	 * @param string $file_path file path
	 * @param array $sources source images
	 * @retrun array
	 */
	private function _convert2gif($file_name, $file_path, $sources){
		$imagegif = function_exists("imagegif");
		if(!$imagegif) return array();
		foreach($sources as $i=>$source){
			$image_info = @getimagesize($source);
			$image_type = $image_info[2];
			switch($image_type){
				// GIF
				case "1" :
					$image = @ImageCreateFromGif($source);
				break; 
				// JPG
				case "2" :
					$image = @imagecreatefromjpeg($source);
				break; 
				// PNG
				case "3" :
					$image = @imagecreatefrompng($source);
				break; 
				// BMP
				case "6" :
					$image = @imagecreatefromwbmp($source);
				break; 
				default:
					$image = null;
				break;
			}
			if(!$image){unset($sources[$i]); continue;}
			$sources[$i] = $file_name."_".$i."_temp.gif";
			imagegif($image, $file_path.$sources[$i]);
			imagedestroy($image);				
		}		
		return $sources;
	}
	
	/**
	 * Create slide show
	 * @param string $file_name file name
	 * @param string $file_path file path
	 * @param array $thumbs_data slide show data
	 * @param array $sources source images
 	 */
	public function create_anim($file_name, $file_path, $thumbs_data, $sources){
		if(empty($thumbs_data)) return false;
		$sources = $this->_convert2gif($file_name, $file_path, $sources);
		$source_count = count($sources);
		if(!$source_count) return;
		foreach($thumbs_data as $i=>$thumb_data){
			if(!$thumb_data["animation"]) unset($thumbs_data[$i]);
		}		
		$files = array();
		foreach($sources as $i=>$source){
			$this->create_thumbs($source, $file_path, $thumbs_data, true);
		}
		foreach($thumbs_data as $thumb_data){
			foreach($sources as $i=>$source){
				$thumb_file = $file_path.$thumb_data["prefix"].'-'.$source;
				$files[] = $thumb_file;
			}
			$anim_file = $file_path.$thumb_data["prefix"].'-'.$file_name;
			if(file_exists($anim_file)) unlink($anim_file);
			$this->CI->load->library("PG_GIFEncoder");
			$params["GIF_src"] = $files;
			$params["GIF_dly"] = array_fill(0, $source_count, $thumb_data["delay"]);
			$params["GIF_lop"] = $thumb_data["loops"];
			$params["GIF_dis"] = $thumb_data["disposal"];
			$params["GIF_red"] = hexdec(substr($thumb_data["color"], 0, 2));
			$params["GIF_grn"] = hexdec(substr($thumb_data["color"], 2, 2));;
			$params["GIF_blu"] = hexdec(substr($thumb_data["color"], 4, 2));;
			$params["GIF_mod"] = "url";
			$content = $this->CI->pg_gifencoder->create($params);
			file_put_contents($anim_file, $content);		
			foreach($sources as $i=>$source){
				$thumb_file = $file_path.$thumb_data["prefix"].'-'.$source;
				unlink($thumb_file);
			}
		}
		foreach($sources as $i=>$source){
			unlink($file_path.$source);
		}
	}
	
	/**
	 * Check allowed file type
	 * @param string $file_name file name
	 * @param string $file file path
	 * @param string $post_file_type file type by post
	 * @param array $allowed_extensions allowed file extensions
	 */
	public function pre_check_file_type($file_name, $file, $post_file_type, $allowed_extensions){
		if(empty($allowed_extensions)){
			return false;
		}
		$extension = strtolower($this->_get_extension($file_name));
		
		if(!in_array($extension, $allowed_extensions)){
			return false;
		}

		$ext_in_allowed = false;

		///// следующим шагом пытаемся определить mime-Тип с поммощью fileinfo, если настройка включена
		$use_fileinfo = $this->pg_module->get_module_config('file_uploads', 'use_fileinfo');

		if($use_fileinfo){
			$file_type = '';

			if(function_exists('finfo_open')){
				$_fileinfo = @finfo_open(FILEINFO_MIME);
				if($_fileinfo){
					$finfo_data = finfo_file($_fileinfo, $file);
					$t = explode(";", $finfo_data);
					$file_type = trim($t[0]);
				}
			}elseif(function_exists('mime_content_type')){
				$file_type = mime_content_type($file);
			}

			if($file_type){
				$ext_in_allowed = false;
				foreach($allowed_extensions as $ext){
					$mime = $this->mimes[$ext];
					if(is_array($mime)){
						if(in_array($file_type, $mime, true)){
							$ext_in_allowed = true;
						}
					}else{
						if($mime == $file_type){
							$ext_in_allowed = true;
						}
					}
				}
				if($ext_in_allowed){
					return true;
				}else{
					return false;
				}
			}
		}else{
			//// проверяем $post_file_type в $allowed_extensions
			foreach($allowed_extensions as $ext){
				$mime = $this->mimes[$ext];
				if (is_array($mime)) {
					if(in_array($post_file_type, $mime, true)){
						$ext_in_allowed = true;
					}
				}else{
					if($mime == $post_file_type){
						$ext_in_allowed = true;
					}
				}
			}
	
			if(!$ext_in_allowed){
				return false;
			}
		}

		return true;
	}
	
	/**
	 * Return file extension
	 * @param string $filename file name
	 */
	private function _get_extension($filename) {
		$x = explode('.', $filename);
		return end($x);
	}
	
	/**
	 * Remove object path
	 * @param type $config_gid GUID of upload config
	 * @param type $postfix object prefix
	 */
	public function delete_path($config_gid, $postfix){
		$path = $this->get_media_path($config_gid, $postfix);
		if(file_exists($path))@rmdir($path);
	}
}
