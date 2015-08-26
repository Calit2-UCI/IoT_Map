<?php
/**
* Upload config model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

if (!defined('UPLOADS_TABLE')) define('UPLOADS_TABLE', DB_PREFIX.'uploads');
if (!defined('UPLOADS_THUMB_TABLE')) define('UPLOADS_THUMB_TABLE', DB_PREFIX.'uploads_thumb');
if (!defined('UPLOADS_WATERMARK_TABLE')) define('UPLOADS_WATERMARK_TABLE', DB_PREFIX.'uploads_watermark');

class Uploads_config_model extends Model
{
	public $CI;
	public $DB;

	public $fields_all = array(
		'id',
		'gid',
		'name',
		'min_height',
		'min_width',
		'max_height',
		'max_width',
		'max_size',
		'name_format',
		'file_formats',
		'default_img',
		'date_add'
	);

	public $watermark_fields_all = array(
		'id',
		'gid',
		'name',
		'wm_type',
		'img',
		'font_text',
		'font_color',
		'shadow_color',
		'shadow_distance',
		'font_face',
		'font_size',
		'position_hor',
		'position_ver',
		'alpha',
		'date_add'
	);

	public $thumb_fields_all = array(
		'id',
		'config_id',
		'prefix',
		'width',
		'height',
		'effect',
		'watermark_id',
		'crop_param',
		'crop_color',
		'animation',
		'delay',
		'loops',
		'disposal',
		'transparent_color',
		'rotation_angle',
		'date_add'
	);

	public $default_watermark = array(
		'wm_type' => 'text',
		'font_color' => 'ffffff',
		'shadow_color' => '777777',
		'shadow_distance' => '3',
		'font_face' => 'arial',
		'font_size' => '16',
		'position_hor' => 'left',
		'position_ver' => 'top',
		'alpha' => 100,
		'font_text' => 'Test string'
	);

	public $wm_text_limits = array(
		'min_font_size' => 9,
		'max_font_size' => 90,
		'min_shadow_distance' => 0,
		'max_shadow_distance' => 20,
	);

	public $file_formats = array("jpg", 'jpeg', 'gif', 'png');

	public $watermark_path = "";
	public $watermark_url = "";

	public $default_path = "";
	public $default_url = "";

	public $watermark_test_image = "watermark_test.jpg";

	public $fonts_folder = '';

	/**
	 * Constructor
	 *
	 * @return Uploads object
	 */
	public function Uploads_config_model()
	{
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;

		$this->watermark_path = SITE_PHYSICAL_PATH . UPLOAD_DIR . "watermark/";
		$this->watermark_url = SITE_VIRTUAL_PATH . UPLOAD_DIR . "watermark/";
		$this->default_path = SITE_PHYSICAL_PATH . UPLOAD_DIR . "default/";
		$this->default_url = SITE_VIRTUAL_PATH . UPLOAD_DIR . "default/";
		$this->fonts_folder = BASEPATH . "fonts/";

		$this->CI->db->memcache_tables(array(
			UPLOADS_TABLE, 
			UPLOADS_THUMB_TABLE, 
			UPLOADS_WATERMARK_TABLE
		));
	}

	public function get_config_by_id($config_id){
		$data = array();
		$result = $this->DB->select(implode(", ", $this->fields_all))->from(UPLOADS_TABLE)->where("id", $config_id)->get()->result_array();
		if(!empty($result)){
			$data = $result[0];
		}
		return $data;
	}

	public function get_config_by_gid($config_gid){
		$data = array();
		$result = $this->DB->select(implode(", ", $this->fields_all))->from(UPLOADS_TABLE)->where("gid", $config_gid)->get()->result_array();
		if(!empty($result)){
			$data = $result[0];
		}
		return $data;
	}

	public function get_config_list(){
		$data = array();
		$this->DB->select(implode(", ", $this->fields_all))->from(UPLOADS_TABLE)->order_by('gid ASC');
		$results = $this->DB->get()->result_array();

		if(!empty($results) && is_array($results)){
			foreach($results as $r){
				$data[] = $this->format_config($r);
			}
		}
		return $data;
	}

	public function get_config_count(){
		$this->DB->select(implode(", ", $this->fields_all))->from(UPLOADS_TABLE);
		$result = $this->DB->get()->result();
		if(!empty($result)){
			return intval($result[0]->cnt);
		}else{
			return 0;
		}
	}

	public function save_config($config_id, $data){
		if (is_null($config_id)){
			$data["date_add"] = date("Y-m-d H:i:s");
			$this->DB->insert(UPLOADS_TABLE, $data);
			$config_id = $this->DB->insert_id();
		}else{
			$this->DB->where('id', $config_id);
			$this->DB->update(UPLOADS_TABLE, $data);
		}

		if(!empty($data["default_img"])){
			$this->CI->load->model('Uploads_model');
			$thumbs_data = $this->get_config_thumbs($config_id);
			if(!empty($thumbs_data)){
				$this->CI->Uploads_model->create_default($data["default_img"], $this->default_path, $thumbs_data);
			}
		}
		return $config_id;
	}

	public function delete_config($config_id){
		$data = $this->get_config_by_id($config_id);
		if(empty($data)) return false;

		$data = $this->format_config($data);
		/*if(!empty($data["default_img_path"]) && file_exists($data["default_img_path"])){
			unlink($data["default_img_path"]);
		}*/
		$this->DB->where('id', $config_id);
		$this->DB->delete(UPLOADS_TABLE);

		if(!empty($data["default_img"])){
			$thumbs = $this->get_config_thumbs($config_id);
			if(!empty($thumbs)){
				foreach($thumbs as $thumb){
					if(file_exists($this->default_path . $thumb["prefix"] . '-' . $data["default_img"])){
						unlink($this->default_path . $thumb["prefix"] . '-' . $data["default_img"]);
					}
					if(file_exists($this->default_path . $thumb["prefix"] . '-animation-' . $data["default_img"])){
						unlink($this->default_path . $thumb["prefix"] . '-animation-' . $data["default_img"]);
					}
					$this->delete_thumb($thumb["id"]);
				}
			}
		}
		return;
	}

	public function validate_config($config_id=null, $data=array()){
		$return = array("errors"=> array(), "data" => array());

		if(isset($data["name"])){
			$return["data"]["name"] = strip_tags($data["name"]);
			if(empty($return["data"]["name"]) ){
				$return["errors"][] = l('error_title_invalid', 'uploads');
			}
		}

		if(isset($data["gid"])){
			$return["data"]["gid"] = strip_tags($data["gid"]);
			$return["data"]["gid"] = preg_replace("/[\n\t\s]{1,}/", "-", trim($return["data"]["gid"]));
			if(empty($return["data"]["gid"]) ){
				$return["errors"][] = l('error_gid_invalid', 'uploads');
			}
		}elseif(!$config_id){
			$return["errors"][] = l('error_gid_invalid', 'uploads');
		}

		if(isset($data["min_width"])){
			$return["data"]["min_width"] = intval($data["min_width"]);
		}

		if(isset($data["min_height"])){
			$return["data"]["min_height"] = intval($data["min_height"]);
		}

		if(isset($data["max_width"])){
			$return["data"]["max_width"] = intval($data["max_width"]);
		}

		if(isset($data["max_height"])){
			$return["data"]["max_height"] = intval($data["max_height"]);
		}

		if(isset($data["max_size"])){
			$return["data"]["max_size"] = intval($data["max_size"]);
		}

		if(isset($data["name_format"])){
			$return["data"]["name_format"] = $data["name_format"];
		}

		if(isset($data["file_formats"])){
			if(!is_array($data["file_formats"])){
				$return["errors"][] = l('error_empty_file_formats', 'uploads');
			} else {
				$return["data"]["file_formats"] = serialize($data["file_formats"]);
			}
		}

		if(isset($data["default_img"]) && is_array($data["default_img"]) && is_uploaded_file($data["default_img"]["tmp_name"])){
			$this->CI->load->helper('upload');
			$config = array('allowed_types' => 'gif|jpg|png');
			$img_return = upload_file('default_img', $this->default_path, $config);
			if(!empty($img_return["error"])){
				foreach($img_return["error"] as $imgError){
					$return["errors"][] = $imgError;
				}
				$img_name = "";
			}else{
				$old_name = $img_return["data"]["file_name"];
				$new_name = "default_".$return["data"]["gid"].$img_return["data"]["file_ext"];
				$rename = rename_file($img_return["data"]["file_path"].$old_name, $img_return["data"]["file_path"].$new_name);
				$img_name = ($rename)?$new_name:$old_name;
			}
			$return["data"]["default_img"] = $img_name;
		}

		return $return;
	}

	public function format_config($data){
		if(!empty($data["default_img"])){
			$data["default_url"] = $this->default_url;
			$data["default_path"] = $this->default_path;
			$data["default_img_path"] = $this->default_path . $data["default_img"];
			$data["default_img_url"] = $this->default_url . $data["default_img"];
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

	public function get_thumb_by_id($thumb_id){
		$data = array();
		$result = $this->DB->select(implode(", ", $this->thumb_fields_all))->from(UPLOADS_THUMB_TABLE)->where("id", $thumb_id)->get()->result_array();
		if(!empty($result)){
			$data = $result[0];
		}
		return $data;
	}

	public function get_config_thumbs($config_id){
		$data = array();
		$this->DB->select(implode(", ", $this->thumb_fields_all))->from(UPLOADS_THUMB_TABLE)->where("config_id", $config_id)->order_by('id ASC');
		$results = $this->DB->get()->result_array();

		if(!empty($results) && is_array($results)){
			foreach($results as $r){
				$data[] = $this->format_thumb($r);
			}
		}
		return $data;
	}

	public function save_thumb($thumb_id, $data){
		if (is_null($thumb_id)){
			$data["date_add"] = date("Y-m-d H:i:s");
			$this->DB->insert(UPLOADS_THUMB_TABLE, $data);
			$thumb_id = $this->DB->insert_id();
		}else{
			$this->DB->where('id', $thumb_id);
			$this->DB->update(UPLOADS_THUMB_TABLE, $data);
		}

		$this->CI->load->model('Uploads_model');
		$thumb_data = $this->format_thumb($this->get_thumb_by_id($thumb_id));
		$config_data = $this->get_config_by_id($thumb_data['config_id']);

		if($config_data["default_img"]){
			$thumb_file = $this->default_path . $thumb_data["prefix"] . '-' . $config_data["default_img"];
			if(file_exists($thumb_file)) unlink($thumb_file);
			copy($this->default_path.$config_data["default_img"], $thumb_file);
			$this->CI->Uploads_model->action($thumb_file, $thumb_data);
		}
		return $thumb_id;
	}

	public function delete_thumb($thumb_id){
		$thumb_data = $this->get_thumb_by_id($thumb_id);
		if(empty($thumb_data)) return false;

		$config_data = $this->get_config_by_id($thumb_data["config_id"]);
		$config_data = $this->format_config($config_data);

		if(!empty($config_data["default_img"]) && file_exists($this->default_path . $thumb_data["prefix"] . '-' . $config_data["default_img"])){
			unlink($this->default_path . $thumb_data["prefix"] . '-' . $config_data["default_img"]);
		}

		$this->DB->where('id', $thumb_id);
		$this->DB->delete(UPLOADS_THUMB_TABLE);

		return;
	}

	public function validate_thumb($thumb_id=null, $data=array()){
		$return = array("errors"=> array(), "data" => array());

		if(isset($data["prefix"])){
			$return["data"]["prefix"] = strip_tags($data["prefix"]);
			if(empty($return["data"]["prefix"]) ){
				$return["errors"][] = l('error_prefix_invalid', 'uploads');
			}
		}

		if(isset($data["width"])){
			$return["data"]["width"] = intval($data["width"]);
			if(empty($return["data"]["width"]) ){
				$return["errors"][] = l('error_width_invalid', 'uploads');
			}
		}

		if(isset($data["height"])){
			$return["data"]["height"] = intval($data["height"]);
			if(empty($return["data"]["height"]) ){
				$return["errors"][] = l('error_height_invalid', 'uploads');
			}
		}

		if(isset($data["watermark_id"])){
			$return["data"]["watermark_id"] = intval($data["watermark_id"]);
		}

		if(isset($data["config_id"])){
			$return["data"]["config_id"] = intval($data["config_id"]);
		}

		if(isset($data["effect"])){
			$return["data"]["effect"] = $data["effect"];
		}

		if(isset($data["crop_param"])){
			$return["data"]["crop_param"] = $data["crop_param"];
		}

		if(isset($data["crop_color"])){
			$return["data"]["crop_color"] = $data["crop_color"];
			if(empty($return["data"]["height"]) ){
				$return["data"]["crop_color"] = "ffffff";
			}
		}

		if(isset($data["animation"])){
			$return["data"]["animation"] = $data["animation"] ? 1 : 0;
		}

		if(isset($data["delay"])){
			$return["data"]["delay"] = intval($data["delay"]);
		}

		if(isset($data["loops"])){
			$return["data"]["loops"] = intval($data["loops"]);
		}

		if(isset($data["disposal"])){
			$return["data"]["disposal"] = intval($data["disposal"]);
		}

		if(isset($data["transparent_color"])){
			$return["data"]["transparent_color"] = substr($data["transparent_color"], 0, 6);
		}

		return $return;
	}

	public function format_thumb($data){
		if(empty($data["crop_color"])){
			$data["crop_color"] = "ffffff";
		}
		if(empty($data["crop_param"])){
			$data["crop_param"] = 'resize';
		}
		if(empty($data["transparent_color"])){
			$data["transparent_color"] = 'ffffff';
		}
		return $data;
	}

	public function get_watermark_by_id($wm_id){
		$data = array();
		$result = $this->DB->select(implode(", ", $this->watermark_fields_all))->from(UPLOADS_WATERMARK_TABLE)->where("id", $wm_id)->get()->result_array();
		if(!empty($result)){
			$data = $result[0];
		}
		return $data;
	}

	public function get_watermark_by_gid($wm_gid){
		$data = array();
		$result = $this->DB->select(implode(", ", $this->watermark_fields_all))->from(UPLOADS_WATERMARK_TABLE)->where("gid", $wm_gid)->get()->result_array();
		if(!empty($result)){
			$data = $result[0];
		}
		return $data;
	}

	public function format_watermark($data){
		if(empty($data["wm_type"])) $data["wm_type"] = 'text';
		if($data["wm_type"] == 'img'){
			if(!empty($data["img"])){
				$data["img_path"] = $this->watermark_path . $data["img"];
				$data["img_url"] = $this->watermark_url . $data["img"];
			}else{
				$data["wm_type"] == 'text';
			}
		}

		if($data["wm_type"] == 'text'){
			if(empty($data["font_color"])){
				$data["font_color"] = $this->default_watermark['font_color'];
			}

			if(empty($data["shadow_color"])){
				$data["shadow_color"] = $this->default_watermark['shadow_color'];
			}

			if(empty($data["shadow_distance"])){
				$data["shadow_distance"] = $this->default_watermark['shadow_distance'];
			}

			if(empty($data["font_face"])){
				$data["font_face"] = $this->default_watermark['font_face'];
			}

			if(empty($data["font_size"])){
				$data["font_size"] = $this->default_watermark['font_size'];
			}

			if(empty($data["font_text"])){
				$data["font_text"] = $this->default_watermark['font_text'];
			}
		}

		if(empty($data["alpha"])){
			$data["alpha"] = $this->default_watermark['alpha'];
		}

		return $data;
	}

	public function get_watermark_list(){
		$data = array();
		$this->DB->select(implode(", ", $this->watermark_fields_all))->from(UPLOADS_WATERMARK_TABLE)->order_by('gid ASC');
		$results = $this->DB->get()->result_array();

		if(!empty($results) && is_array($results)){
			foreach($results as $r){
				$data[] = $this->format_watermark($r);
			}
		}
		return $data;
	}

	public function get_watermark_count(){
		$this->DB->select(implode(", ", $this->watermark_fields_all))->from(UPLOADS_WATERMARK_TABLE);
		$result = $this->DB->get()->result();
		if(!empty($result)){
			return intval($result[0]->cnt);
		}else{
			return 0;
		}
	}

	public function save_watermark($wm_id, $data){
		if (is_null($wm_id)){
			$data["date_add"] = date("Y-m-d H:i:s");
			$this->DB->insert(UPLOADS_WATERMARK_TABLE, $data);
			$wm_id = $this->DB->insert_id();
		}else{
			$this->DB->where('id', $wm_id);
			$this->DB->update(UPLOADS_WATERMARK_TABLE, $data);
		}
		return $wm_id;
	}

	public function delete_watermark($wm_id){
		$data = $this->get_watermark_by_id($wm_id);
		if(empty($data)) return false;

		$data = $this->format_watermark($data);
		if(!empty($data["img_path"])){
			unlink($data["img_path"]);
		}
		$this->DB->where('id', $wm_id);
		$this->DB->delete(UPLOADS_WATERMARK_TABLE);

		$this->DB->where('watermark_id', $wm_id);
		$this->DB->update(UPLOADS_THUMB_TABLE, array('watermark_id' => 0));
		return;
	}

	public function validate_watermark($wm_id=null, $data=array()){
		$return = array("errors"=> array(), "data" => array());

		if(isset($data["name"])){
			$return["data"]["name"] = strip_tags($data["name"]);
			if(empty($return["data"]["name"]) ){
				$return["errors"][] = l('error_title_invalid', 'uploads');
			}
		}

		if(isset($data["gid"])){
			$return["data"]["gid"] = strip_tags($data["gid"]);
			$return["data"]["gid"] = preg_replace("/[\n\t\s]{1,}/", "-", trim($return["data"]["gid"]));
			if(empty($return["data"]["gid"]) ){
				$return["errors"][] = l('error_gid_invalid', 'uploads');
			}
		}

		if(isset($data["position_ver"])){
			$return["data"]["position_ver"] = $data["position_ver"];
		}

		if(isset($data["position_hor"])){
			$return["data"]["position_hor"] = $data["position_hor"];
		}

		if(isset($data["alpha"])){
			$return["data"]["alpha"] = intval($data["alpha"]);
		}

		if(isset($data["wm_type"])){
			$return["data"]["wm_type"] = $data["wm_type"];
		}

		if(isset($data["img"]) && is_array($data["img"]) && is_uploaded_file($data["img"]["tmp_name"])){
			$this->CI->load->helper('upload');
			$config = array('allowed_types' => 'gif|jpg|png');
			$img_return = upload_file('img', $this->watermark_path, $config);
			if(!empty($img_return["error"])){
				foreach($img_return["error"] as $imgError){
					$return["errors"][] = $imgError;
				}
				$img_name = "";
			}else{
				$old_name = $img_return["data"]["file_name"];
				$new_name = "wm_".$return["data"]["gid"].$img_return["data"]["file_ext"];
				$rename = rename_file($img_return["data"]["file_path"].$old_name, $img_return["data"]["file_path"].$new_name);
				$img_name = ($rename)?$new_name:$old_name;
			}
			$return["data"]["img"] = $img_name;
		}

		if(isset($data["font_size"])){
			$return["data"]["font_size"] = intval($data["font_size"]);
		}

		if(isset($data["shadow_distance"])){
			$return["data"]["shadow_distance"] = intval($data["shadow_distance"]);
		}

		if(isset($data["font_color"])){
			$return["data"]["font_color"] = $data["font_color"];
		}

		if(isset($data["shadow_color"])){
			$return["data"]["shadow_color"] = $data["shadow_color"];
		}

		if(isset($data["font_face"])){
			$return["data"]["font_face"] = $data["font_face"];
		}

		if(isset($data["font_text"])){
			$return["data"]["font_text"] = strip_tags($data["font_text"]);
		}

		return $return;
	}
}
