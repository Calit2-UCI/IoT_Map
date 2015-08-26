<?php

/**
 * Content module
 *
 * @package 	PG_Dating
 * @copyright 	Copyright (c) 2000-2014 PilotGroup.NET Powered by PG Dating Pro
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */

if (!defined('CONTENT_PROMO_TABLE')) define('CONTENT_PROMO_TABLE', DB_PREFIX.'content_promo');

/**
 * Content promo data model
 * 
 * @package 	PG_Dating
 * @subpackage 	Content
 * @category	models
 * @copyright 	Copyright (c) 2000-2014 PilotGroup.NET Powered by PG Dating Pro
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
class Content_promo_model extends Model
{
	/**
	 * Link to CodeIgniter object
	 * 
	 * @var object
	 */
	public $CI;
	
	/**
	 * Link to database object
	 * 
	 * @var object
	 */
	public $DB;

	/**
	 * Properties of promo object in data source
	 * 
	 * @var array
	 */
	public $fields = array(
		"id",
		"id_lang",
		"content_type",
		"promo_text",
		"promo_image",
		"promo_flash",
		"block_width",
		"block_width_unit",
		"block_height",
		"block_height_unit",
		"block_align_hor",
		"block_align_ver",
		"block_image_repeat",
		"promo_video",
		"promo_video_image",
		"promo_video_data",
	);
	
	/**
	 * Upload photo (GUID)
	 * 
	 * @var string
	 */
	public $upload_gid = "promo-content-img";
	
	/**
	 * Upload promo photo (GUID)
	 * 
	 * @var string
	 */
	public $file_upload_gid = "promo-content-flash";

	/**
	 * Upload promo video (GUID)
	 * 
	 * @var string
	 */
	public $video_gid = 'promo-video';

	/**
	 * Settings for formatting promo object
	 * 
	 * @var array
	 */
	protected $format_settings = array(
		'use_format' => true,
		'get_output' => false,
	);

	/**
	 * Class constructor
	 * 
	 * @return Content_promo_model
	 */
	public function __construct(){
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;
	}

	/**
	 * Return promo object by language
	 * 
	 * @param integer $id_lang language identifier
	 * @return array
	 */
	public function get_promo($id_lang){
		$data = array();
		$result = $this->DB->select(implode(", ", $this->fields))
						   ->from(CONTENT_PROMO_TABLE)
						   ->where("id_lang", $id_lang)
						   ->get()
						   ->result_array();
		if(!empty($result)){
			$data = $this->format_promo($result[0]);
		}
		return $data;	
	}
	
	/**
	 * Format promo object data
	 * 
	 * @param array $data promo data
	 * @return array
	 */
	public function format_promo($data){
		$data["postfix"] = $data["id_lang"];

		if(!empty($data["promo_image"])){
			$this->CI->load->model('Uploads_model');
			$data["media"]["promo_image"] = $this->CI->Uploads_model->format_upload($this->upload_gid, $data["postfix"], $data["promo_image"]);
		}
		
		if(!empty($data["promo_flash"])){
			$this->CI->load->model('File_uploads_model');
			$data["media"]["promo_flash"] = $this->CI->File_uploads_model->format_upload($this->file_upload_gid, $data["postfix"], $data["promo_flash"]);
		}
		
		if($data["block_width_unit"] != "auto"){
			$styles["width"] = $data["block_width"].$data["block_width_unit"];
		}
		
		if($data["block_height_unit"] != "auto"){
			$styles["height"] = $data["block_height"].$data["block_height_unit"];
		}
		
		if(!empty($data["promo_image"]) && $data["content_type"] == 't'){
			$styles["background-image"] = "url('".$data["media"]["promo_image"]["file_url"]."')";
			$styles["background-position"] = $data["block_align_hor"]." ".$data["block_align_ver"];
			$styles["background-repeat"] = $data["block_image_repeat"];
		}
		if(!empty($styles)){
			$data["styles"] = $styles;
			$data["style_str"] = '';
			foreach($styles as $selector=>$value){
				$data["style_str"] .= $selector.': '.$value."; ";
			}
		}
		
		// get_video
		if($this->CI->pg_module->is_module_installed('video_uploads')){
			if(!empty($data['promo_video_data'])){
				$data['promo_video_data'] = $data['promo_video_data'] ? unserialize($data['promo_video_data']) : array();
			}
			if(!empty($data['promo_video']) && $data['promo_video_data']['data']['upload_type'] == 'embed'){
				$this->CI->load->model('Video_uploads_model');
				$data['promo_video_content'] = $this->CI->Video_uploads_model->format_upload($this->video_gid, 
					$data['postfix'], $data['promo_video_data']['data'], $data['promo_video_image'], 
				$data['promo_video_data']['data']['upload_type']);
			}elseif(!empty($data['promo_video']) && $data['promo_video_data']['status'] == 'end'){
				$this->CI->load->model('Video_uploads_model');
				$data['promo_video_content'] = $this->CI->Video_uploads_model->format_upload($this->video_gid, 
					$data['postfix'], $data['promo_video'], $data['promo_video_image'], 
					$data['promo_video_data']['data']['upload_type']);
			}
		}

		return $data;
	}
	
	/**
	 * Save promo object to data source
	 * 
	 * @param integer $id_lang language identifier
	 * @param array $data promo data
	 * @param string $img_file photo upload
	 * @param string $flash_file flash upload
	 * @return
	 */
	public function save_promo($id_lang, $data, $img_file='', $flash_file=''){
		if(!$id_lang) return false;
		
		$data['id_lang'] = $id_lang;	
	
		if (!$this->exists_promo($id_lang)){
			$this->DB->insert(CONTENT_PROMO_TABLE, $data);
		}else{
			$this->DB->where('id_lang', $id_lang);
			$this->DB->update(CONTENT_PROMO_TABLE, $data);
		}
		
		if(!empty($img_file) && isset($_FILES[$img_file]) && is_array($_FILES[$img_file]) && is_uploaded_file($_FILES[$img_file]["tmp_name"])){
			$this->CI->load->model("Uploads_model");
			$img_return = $this->CI->Uploads_model->upload($this->upload_gid, $id_lang."/", $img_file);

			if(empty($img_return["errors"])){
				$img_data["promo_image"] = $img_return["file"];
				$this->save_promo($id_lang, $img_data);
			}
		}

		if(!empty($flash_file) && isset($_FILES[$flash_file]) && is_array($_FILES[$flash_file]) && is_uploaded_file($_FILES[$flash_file]["tmp_name"])){
			$this->CI->load->model("File_uploads_model");
			$flash_return = $this->CI->File_uploads_model->upload($this->file_upload_gid, $id_lang."/", $flash_file);

			if(empty($flash_return["errors"])){
				$flash_data["promo_flash"] = $flash_return["file"];
				$this->save_promo($id_lang, $flash_data);
			}
		}

		return true;
	}

	/**
	 * Validate promo object for saving to data source
	 * 
	 * @param array $data promo data
	 * @param string $img_file photo upload
	 * @param string $flash_file flash upload
	 * @return array
	 */
	public function validate_promo($data, $img_file='', $flash_file=''){
		$return = array("errors"=> array(), "data" => array());

		if(isset($data["id_lang"])){
			$return["data"]["id_lang"] = intval($data["id_lang"]);
		}

		if(isset($data["content_type"])){
			$return["data"]["content_type"] = trim(strip_tags($data["content_type"]));
		}

		if(isset($data["block_width"])){
			$return["data"]["block_width"] = intval($data["block_width"]);
		}

		if(isset($data["block_width_unit"])){
			$return["data"]["block_width_unit"] = strval($data["block_width_unit"]);
			if(!$return["data"]["block_width_unit"]){
				$return["data"]["block_width_unit"] = 'auto';
			}
		}

		if(isset($data["block_height"])){
			$return["data"]["block_height"] = intval($data["block_height"]);
		}

		if(isset($data["block_height_unit"])){
			$return["data"]["block_height_unit"] = strval($data["block_height_unit"]);
			if(!$return["data"]["block_height_unit"]){
				$return["data"]["block_height_unit"] = 'auto';
			}
		}

		if(isset($data["block_align_hor"])){
			$return["data"]["block_align_hor"] = strval($data["block_align_hor"]);
			if(!$return["data"]["block_align_hor"]){
				$return["data"]["block_align_hor"] = 'center';
			}
		}

		if(isset($data["block_align_ver"])){
			$return["data"]["block_align_ver"] = strval($data["block_align_ver"]);
			if(!$return["data"]["block_align_ver"]){
				$return["data"]["block_align_ver"] = 'center';
			}
		}

		if(isset($data["block_image_repeat"])){
			$return["data"]["block_image_repeat"] = strval($data["block_image_repeat"]);
			if(!$return["data"]["block_image_repeat"]){
				$return["data"]["block_image_repeat"] = 'no-repeat';
			}
		}

		if(isset($data["promo_text"])){
			$return["data"]["promo_text"] = trim($data["promo_text"]);
		}
		
		if(isset($data['promo_video'])){
			$return['data']['promo_video'] = strval($data['promo_video']);
		}
	
		if(isset($data['promo_video_image'])){
			$return['data']['promo_video_image'] = strval($data['promo_video_image']);
		}
		
		if(isset($data['promo_video_data'])){
			$return['data']['promo_video_data'] = serialize($data['promo_video_data']);
		}

		if(!empty($img_file) && isset($_FILES[$img_file]) && is_array($_FILES[$img_file]) && is_uploaded_file($_FILES[$img_file]["tmp_name"])){
			$this->CI->load->model("Uploads_model");
			$img_return = $this->CI->Uploads_model->validate_upload($this->upload_gid, $img_file);
			if(!empty($img_return["error"])){
				$return["errors"][] = implode("<br>", $img_return["error"]);
			}
		}

		if(!empty($flash_file) && isset($_FILES[$flash_file]) && is_array($_FILES[$flash_file]) && is_uploaded_file($_FILES[$flash_file]["tmp_name"])){
			$this->CI->load->model("File_uploads_model");
			$flash_return = $this->CI->File_uploads_model->validate_upload($this->file_upload_gid, $flash_file);

			if(!empty($flash_return["error"])){
				$return["errors"][] = implode("<br>", $flash_return["error"]);
			}
		}

		return $return;	
	}
	
	/**
	 * Remove promo object by language
	 * 
	 * @param integer $id_lang language identifier
	 * @return void
	 */
	public function delete_promo($id_lang){
		$this->DB->where('id_lang', $id_lang);
		$this->DB->delete(CONTENT_PROMO_TABLE);
		return;		
	}
	
	/**
	 * Check promo object is exists by language
	 * 
	 * @param integer $id_lang language identifier
	 * @return boolean
	 */
	public function exists_promo($id_lang){
		$this->DB->select('COUNT(*) AS cnt')->from(CONTENT_PROMO_TABLE)->where('id_lang', $id_lang);
		$result = $this->DB->get()->result();
		if(!empty($result) && intval($result[0]->cnt) > 0){
			return true;
		}else{
			return false;
		}
	}
	
	//// dynamic blocks methods
	
	/**
	 * Return content of promo block
	 * 
	 * Dynamic blocks callback method
	 * 
	 * @param array $params block parameters
	 * @param string $view block view
	 * @param integer $width block size
	 * @return string
	 */
	public function _dynamic_block_get_content_promo($params=array(), $view=''){
		$this->CI->load->helper('content');
		return get_content_promo($view);
	}
	
	//// methods for langs
	
	/**
	 * Processing events on added language
	 * 
	 * Add fields depended on language
	 * 
	 * @param integer $id_lang language identifier
	 * @return void
	 */
	public function lang_dedicate_module_callback_add($id_lang) {
		$default_id_lang = $this->CI->pg_language->get_default_lang_id();
		$default_data = $this->get_promo($default_id_lang);

		$data = array(
			"id_lang" => $id_lang,
			"content_type" => isset($default_data["content_type"])?$default_data["content_type"]:"",
			"promo_text" => isset($default_data["promo_text"])?$default_data["promo_text"]:"",
			"block_width" => isset($default_data["block_width"])?$default_data["block_width"]:"",
			"block_width_unit" => isset($default_data["block_width_unit"])?$default_data["block_width_unit"]:"",
			"block_height" => isset($default_data["block_height"])?$default_data["block_height"]:"",
			"block_height_unit" => isset($default_data["block_height_unit"])?$default_data["block_height_unit"]:"",
			"block_align_hor" => isset($default_data["block_align_hor"])?$default_data["block_align_hor"]:"",
			"block_align_ver" => isset($default_data["block_align_ver"])?$default_data["block_align_ver"]:"",
			"block_image_repeat" => isset($default_data["block_image_repeat"])?$default_data["block_image_repeat"]:"",
		);
		
		$validate_data = $this->validate_promo($data);
		if(empty($validate_data["errors"]) && !$this->exists_promo($id_lang)){
			$this->save_promo($id_lang, $validate_data["data"]);
		}
	}
	
	/**
	 * Processing events on remove language
	 * 
	 * Remove fields depended on language
	 * 
	 * @param integer $id_lang language identifier
	 * @return void
	 */
	public function lang_dedicate_module_callback_delete($id_lang) {
		$this->delete_promo($id_lang);
	}
	
	/**
	  * Processing event on upload video
	  * 
	  * @param integer $lang_id language identifier
	  * @param string $status upload status
	  * @param array $data upload data
	  * @param array $errors upload errors
	  * @return void
	  */
	public function video_callback($lang_id, $status, $data, $errors){
		$promo_data = $this->get_promo($lang_id);
		
		if(isset($data['video'])) $update['promo_video'] = $data['video'];
		if(isset($data['image'])) $update['promo_video_image'] = $data['image'];
		$update['promo_video_data'] = $promo_data['promo_video_data'];
		if($status == 'start' || !isset($update['promo_video_data']['data'])){ 
			$update['promo_video_data']['data'] = array();
		}
		if(!empty($data)){
			$update['promo_video_data']['data'] = array_merge($update['promo_video_data']['data'], $data);
		}

		$update['promo_video_data']['status'] = $status;
		$update['promo_video_data']['errors'] = $errors;
		
		$validate_data = $this->validate_promo($update);
		$this->save_promo($lang_id, $validate_data['data']);
		return;
	}
	
	/**
	 * Save promo video object from upload file
	 * 
	 * @param integer $lang_id language identifier
	 * @param string $video_name video upload name
	 * @param string $embed embed data
	 * @return array
	 */
	public function save_video($lang_id, $video_name, $embed){
		if(!$this->CI->pg_module->is_module_installed('video_uploads')) return $return;
		
		$return = array('errors'=>array(), 'data'=>array(), 'success'=>false);

		if(!$lang_id) $lang_id = $this->CI->pg_language->current_lang_id;
		
		$promo_data = $this->get_promo($lang_id);
		
		$embed_data = array();
		if(!empty($embed)){
			$this->load->library('VideoEmbed');
			$embed_data = $this->videoembed->get_video_data($embed);
			if($embed_data !== false){
				$embed_data['string_to_save'] = $this->videoembed->get_string_from_video_data($embed_data);
				$embed_data['upload_type'] = 'embed';
				$embed_data['name'] = '';
				$embed_data['description'] = '';
			}else{
				$return["errors"][] = l('error_embed_wrong', 'content');
			}
		}
		
		if(isset($_FILES[$video_name]) && is_array($_FILES[$video_name]) && $_FILES[$video_name]['error'] != 4){
			if(!is_uploaded_file($_FILES[$video_name]['tmp_name'])){
				$return['errors'][] = l('error_upload_video', 'content'); 
				return $return;
			}
		}else{
			$video_name = '';
		}
			
		if(!empty($data['promo_video'])){
			if($data['promo_video_data']['data']['upload_type'] == 'embed'){
				if($video_name){
					$return['errors'][] = l('error_max_video_reached', 'content');
				}
			}elseif($video_name || $embed_data){
				$return['errors'][] = l('error_max_video_reached', 'content');
			}
		}	
			
		if(empty($return['errors'])){
			if($video_name){
				$this->CI->load->model('Video_uploads_model');
				$video_data = array(
					'name' => '',
					'description' => '',
				);
	
				$video_return = $this->CI->Video_uploads_model->upload($this->video_gid, $promo_data['postfix'], $video_name, $lang_id, $video_data);
				if(!empty($video_return['errors'])){
					$return['errors'] = $video_return['errors']; 
				}else{
					$return['data']['file'] = $video_return['file'];
				}
				$return['success'] = true;
			}elseif($embed_data){
				$this->CI->load->model('Uploads_model');
				$save_data["promo_video_image"] = $this->CI->Uploads_model->generate_filename('.jpg');
				$save_data["promo_video_data"] = serialize(array('data' => $embed_data));
				$save_data["promo_video"] = 'embed';
				$this->save_promo($lang_id, $save_data);
				$return['success'] = true;
			}
		}
	
		return $return;
	}
	
	/**
	 * Save promo video object from local file
	 * 
	 * @param integer $lang_id language identifier
	 * @param string $video_name video upload name
	 * @return array
	 */
	public function save_local_video($lang_id, $video_name){
		if(!$this->CI->pg_module->is_module_installed('video_uploads')) return $return;
		
		$return = array('errors'=>array(), 'data'=>array());

		if(empty($video_name)){
			$return['errors'][] = l('error_empty_video', 'content'); 
			return $return;
		}
		
		$promo_data = $this->get_promo($lang_id);
		
		if(!empty($promo_data['promo_video'])){
			$return['errors'][] = l('error_max_video_reached', 'content');
		}else{
			$this->CI->load->model('Video_uploads_model');
			$video_data = array(
				'name' => '',
				'description' => '',
			);
	
			$video_return = $this->CI->Video_uploads_model->upload_exists($this->video_gid, $promo_data['postfix'], $video_name, $lang_id, $video_data);
			if(!empty($video_return['errors'])){
				$return['errors'] = $video_return['errors']; 
			}else{
				$return['data']['file'] = $video_return['file'];
			}
		}

		return $return;
	}
	
	/**
	 * Remove video object by language
	 * 
	 * @param integer $lang_id language identifier
	 * @return void
	 */
	public function delete_video($lang_id){
		if(!$lang_id) $lang_id = $this->CI->pg_language->current_lang_id;
		
		$promo_data = $this->get_promo($lang_id);
		
		if($promo_data['promo_video_data']['data']['upload_type'] != 'embed'){
			$this->CI->load->model('Video_uploads_model');
			$this->CI->Video_uploads_model->delete_upload($this->video_config_id, $promo_data['postfix'], $promo_data['promo_video'], $promo_data['promo_video_image'], $promo_data['promo_video_data']['data']['upload_type']);
		}
		
		$save_data = array(
			'promo_video' => '',
			'promo_video_image' => '',
			'promo_video_data' => '',
		);
		$this->save_promo($lang_id, $save_data);
	}
	
	/**
	 * Return data of promo video type
	 * 
	 * @return array
	 */
	public function get_video_type(){
		$this->CI->load->model('Video_uploads_model');
		return $this->CI->Video_uploads_model->get_config($this->video_gid);
	}
	
	/**
	 * Change settings for promo formatting
	 * 
	 * @param string $name parameter name
	 * @param mixed $value parameter value
	 * @return void
	 */
	public function set_format_settings($name, $value=false){
		if(!is_array($name)) $name = array($name=>$value);
		foreach($name as $key => $item)	$this->format_settings[$key] = $item;
	}
}
