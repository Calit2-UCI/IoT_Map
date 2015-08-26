<?php
/**
* Video uploads config model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

if (!defined('VIDEOS_CONFIG_TABLE')) {
	define('VIDEOS_CONFIG_TABLE', DB_PREFIX.'videos_config');
}

class Video_uploads_config_model extends Model
{
	public $CI;
	public $DB;

	public $fields_all = array(
		'id',
		'gid',
		'name',
		'max_size',
		'file_formats',
		'upload_type',
		'use_convert',
		'use_thumbs',
		'default_img',
		'thumbs_settings',
		'local_settings',
		'youtube_settings',
		'module',
		'model',
		'method_status',
		'date_add'
	);


	public $file_formats = array('avi', 'flv', 'mkv', 'wmv', 'asf', 'mpeg', 'mpg', 'mpe', 'qt', 'mov', 'movie', 'rv', 'mp4');
	public $default_img_formats = array('jpg', 'gif', 'png');

	public $default_path = "";
	public $default_url = "";

	/**
	 * Constructor
	 *
	 * @return Uploads object
	 */
	public function __construct()
	{
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;

		$this->default_path = SITE_PHYSICAL_PATH . UPLOAD_DIR . "video-default/";
		$this->default_url = SITE_VIRTUAL_PATH . UPLOAD_DIR . "video-default/";

		$this->DB->memcache_tables(array(VIDEOS_CONFIG_TABLE));
	}

	public function get_config_by_id($config_id){
		$data = array();
		$result = $this->DB->select(implode(", ", $this->fields_all))
				->from(VIDEOS_CONFIG_TABLE)
				->where("id", $config_id)
				->get()->result_array();
		if(!empty($result)){
			$data = $this->format_config($result[0]);
		}
		return $data;
	}

	public function get_config_by_gid($config_gid){
		$data = array();
		$result = $this->DB->select(implode(", ", $this->fields_all))
				->from(VIDEOS_CONFIG_TABLE)
				->where("gid", $config_gid)
				->get()->result_array();
		if(!empty($result)){
			$data = $this->format_config($result[0]);
		}
		return $data;
	}

	public function get_config_list(){
		$data = array();
		$this->DB->select(implode(", ", $this->fields_all))
				->from(VIDEOS_CONFIG_TABLE)
				->order_by('gid ASC');
		$results = $this->DB->get()->result_array();

		if(!empty($results) && is_array($results)){
			foreach($results as $r){
				$data[] = $this->format_config($r);
			}
		}
		return $data;
	}

	public function get_config_count(){
		$this->DB->select(implode(", ", $this->fields_all))
				->from(VIDEOS_CONFIG_TABLE);
		$result = $this->DB->get()->result();
		if(!empty($result)){
			return intval($result[0]->cnt);
		}else{
			return 0;
		}
	}

	public function save_config($config_id, $data, $upload_gid=''){
		if (is_null($config_id)){
			$data["date_add"] = date("Y-m-d H:i:s");
			$this->DB->insert(VIDEOS_CONFIG_TABLE, $data);
			$config_id = $this->DB->insert_id();
		}else{
			$this->DB->where('id', $config_id);
			$this->DB->update(VIDEOS_CONFIG_TABLE, $data);
		}

		$config = $this->get_config_by_id($config_id);
		$this->CI->load->model('Video_uploads_model');
		if(!empty($config["default_img"]) && !empty($config["use_thumbs"]) && !empty($config["thumbs_settings"])){
			$this->CI->Video_uploads_model->create_thumbs($config["default_img"], $this->default_path, $config["thumbs_settings"]);
		}

		if($upload_gid && isset($_FILES[$upload_gid]) && is_array($_FILES[$upload_gid]) && is_uploaded_file($_FILES[$upload_gid]["tmp_name"])){

			if(!empty($config["default_img"])){
				$this->CI->Video_uploads_model->delete_thumbs($config["default_img"], $this->default_path, $config['thumbs_settings']);
			}

			$this->CI->load->helper('upload');
			$upload_config = array( 'allowed_types' => implode('|', $this->default_img_formats), 'overwrite' => true );
			$image_return = upload_file($upload_gid, $this->default_path, $upload_config);

			if(empty($image_return["error"])){
				$image_data["default_img"] = $image_return["data"]["file_name"];
				$this->save_config($config_id, $image_data);
			}
		}
		return $config_id;
	}

	public function delete_config($config_id){
		$data = $this->get_config_by_id($config_id);
		if(empty($data)) return false;

		$this->DB->where('id', $config_id);
		$this->DB->delete(VIDEOS_CONFIG_TABLE);

		$this->CI->load->model('Video_uploads_model');
		if(!empty($data["default_img"]) && !empty($data["use_thumbs"]) && !empty($data["thumbs_settings"])){
			$this->CI->Video_uploads_model->create_thumbs($data["default_img"], $this->default_path, $data["thumbs_settings"]);
		}

		return;
	}

	public function validate_config($config_id, $data=array()){
		$this->CI->load->model('video_uploads/models/Video_uploads_settings_model');
		$settings = $this->CI->Video_uploads_settings_model->get_settings();

		$return = array("errors"=> array(), "data" => array());

		if(isset($data["name"])){
			$return["data"]["name"] = strip_tags($data["name"]);
			if(empty($return["data"]["name"]) ){
				$return["errors"][] = l('error_title_invalid', 'video_uploads');
			}
		}

		if(isset($data["max_size"])){
			$return["data"]["max_size"] = intval($data["max_size"]);
		}

		if(isset($data["file_formats"])){
			if(!is_array($data["file_formats"])){
				$return["errors"][] = l('error_empty_file_formats', 'video_uploads');
			} else {
				$return["data"]["file_formats"] = serialize($data["file_formats"]);
			}
		}

		if(isset($data["upload_type"])){
			$return["data"]["upload_type"] = trim(strip_tags($data["upload_type"]));
			if(!$return["data"]["upload_type"] || (!$settings["use_youtube_converting"] && $return["data"]["upload_type"] == 'youtube') ){
				$return["data"]["upload_type"] = "local";
			}
		}

		if(isset($return["data"]["upload_type"]) && $return["data"]["upload_type"] == 'local'){
			if(isset($data["use_convert"])){
				$return["data"]["use_convert"] = intval($data["use_convert"]);
			}
			if(!$settings["use_local_converting_video"]){
				$return["data"]["use_convert"] = 0;
			}

			if(isset($data["local_settings"]["width"])){
				$return["data"]["local_settings"]["width"] = intval($data["local_settings"]["width"]);
				if($return["data"]["local_settings"]["width"] <= 0 ){
					$return["errors"][] = l('error_local_width_invalid', 'video_uploads');
				}
			}

			if(isset($data["local_settings"]["height"])){
				$return["data"]["local_settings"]["height"] = intval($data["local_settings"]["height"]);
				if($return["data"]["local_settings"]["height"] <= 0 ){
					$return["errors"][] = l('error_local_height_invalid', 'video_uploads');
				}
			}

			if(isset($data["local_settings"]["audio_freq"])){
				$return["data"]["local_settings"]["audio_freq"] = trim(strip_tags($data["local_settings"]["audio_freq"]));
				if(!$return["data"]["local_settings"]["audio_freq"]){
					$return["data"]["local_settings"]["audio_freq"] = 22050;
				}
			}

			if(isset($data["local_settings"]["audio_brate"])){
				$return["data"]["local_settings"]["audio_brate"] = trim(strip_tags($data["local_settings"]["audio_brate"]));
				if(!$return["data"]["local_settings"]["audio_brate"]){
					$return["data"]["local_settings"]["audio_brate"] = '64k';
				}
			}

			if(isset($data["local_settings"]["video_brate"])){
				$return["data"]["local_settings"]["video_brate"] = trim(strip_tags($data["local_settings"]["video_brate"]));
				if(!$return["data"]["local_settings"]["video_brate"]){
					$return["data"]["local_settings"]["video_brate"] = '300k';
				}
			}

			if(isset($data["local_settings"]["video_rate"])){
				$return["data"]["local_settings"]["video_rate"] = trim(strip_tags($data["local_settings"]["video_rate"]));
				if(!$return["data"]["local_settings"]["video_rate"]){
					$return["data"]["local_settings"]["video_rate"] = '50';
				}
			}
			$return["data"]["local_settings"] = serialize($return["data"]["local_settings"]);
		}

		if(isset($return["data"]["upload_type"]) && $return["data"]["upload_type"] == 'youtube'){
			if(isset($data["youtube_settings"]["width"])){
				$return["data"]["youtube_settings"]["width"] = intval($data["youtube_settings"]["width"]);
				if($return["data"]["youtube_settings"]["width"] <= 0 ){
					$return["errors"][] = l('error_youtube_width_invalid', 'video_uploads');
				}
			}

			if(isset($data["youtube_settings"]["height"])){
				$return["data"]["youtube_settings"]["height"] = intval($data["youtube_settings"]["height"]);
				if($return["data"]["youtube_settings"]["height"] <= 0 ){
					$return["errors"][] = l('error_youtube_height_invalid', 'video_uploads');
				}
			}
			$return["data"]["youtube_settings"] = serialize($return["data"]["youtube_settings"]);
		}

		if(isset($data["default_img"]) && is_array($data["default_img"]) && is_uploaded_file($data["default_img"]["tmp_name"])){
			$this->CI->load->helper('upload');

			$upload_config = array(
				'allowed_types' => implode('|', $this->default_img_formats),
				'overwrite' => true

			);

			$image_return = validate_file('default_img', $upload_config);

			if(!empty($image_return["error"])){
				foreach($image_return["error"] as $imgError){
					$return["errors"][] = $imgError;
				}
				$img_name = "";
			}
		}

		if(isset($data["use_thumbs"])){
			$return["data"]["use_thumbs"] = intval($data["use_thumbs"]);
		}
		if(!$settings["use_local_converting_thumbs"]){
			$return["data"]["use_thumbs"] = 0;
		}

		if(isset($data["thumbs_settings"])){
			$gids = array();
			$return["data"]["thumbs_settings"] = array();
			foreach($data["thumbs_settings"] as $thumb){
				$thumb["gid"] = strip_tags(trim($thumb["gid"]));
				if(empty($thumb["gid"])){
					continue;
				}

				if(!empty($gids) && in_array($thumb["gid"], $gids)){
					$return["errors"][] = l('error_thumb_gid_exists', 'video_uploads');
				}

				$thumb["width"] = intval($thumb["width"]);
				if($thumb["width"] <= 0 ){
					$return["errors"][] = l('error_thumb_width_invalid', 'video_uploads');
				}

				$thumb["height"] = intval($thumb["height"]);
				if($thumb["height"] <= 0 ){
					$return["errors"][] = l('error_thumb_height_invalid', 'video_uploads');
				}

				$thumb["animated"] = intval($thumb["animated"]);
				$return["data"]["thumbs_settings"][] = $thumb;
			}
			$return["data"]["thumbs_settings"] = serialize($return["data"]["thumbs_settings"]);
		}

		return $return;
	}

	public function format_config($data){
		if(!empty($data["local_settings"])){
			$data["local_settings"] = unserialize($data["local_settings"]);
		}

		if(!empty($data["youtube_settings"])){
			$data["youtube_settings"] = unserialize($data["youtube_settings"]);
		}

		if(!empty($data["thumbs_settings"])){
			$data["thumbs_settings"] = unserialize($data["thumbs_settings"]);
		}

		if(!empty($data["default_img"])){
			$data["default_img_data"]["url"] = $this->default_url;
			$data["default_img_data"]["path"] = $this->default_path;
			$data["default_img_data"]["file_path"] = $this->default_path . $data["default_img"];
			$data["default_img_data"]["file_url"] = $this->default_url . $data["default_img"];
		}

		if(!empty($data["file_formats"])){
			$data["file_formats"] = unserialize($data["file_formats"]);
			if(!empty($data["file_formats"])){
				foreach($data["file_formats"] as $format){
					$data["enable_formats"][$format] = 1;
				}
				$this->CI->load->helper('upload');
				$data['allowed_mimes'] = get_mimes_types_by_files_types($data["file_formats"]);
			}
			$data["file_formats_str"] = implode("|", $data["file_formats"]);
		}else{
			$data["file_formats_str"] = "";
		}

		return $data;
	}



}
