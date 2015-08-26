<?php
/**
* Geomaps settings model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/
if (!defined('BASEPATH')) exit('No direct script access allowed');

define('GEOMAPS_SETTINGS_TABLE', DB_PREFIX.'geomap_settings');

class Geomap_settings_model extends Model
{
	private $CI;
	private $DB;

	private $attrs = array('id', 'map_gid', 'id_user', 'id_object', 'gid', 'lat', 'lon', 'zoom', 'view_type', 'view_settings');

	private $default_settings = array(
		"lat" => 0,
		"lon" => 0,
		"zoom" => 13,
		"view_type" => 1,
		"view_settings" => array(),
	);

	public $module_gid = 'geomap';
	
	/**
	 * Upload image for map marker
	 * @var string
	 */
	public $upload_config_id = "map-marker-icon";
	
	/**
	 * Constructor
	 *
	 * @return
	 */
	function __construct()
	{
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;
	}

	public function get_settings($map_gid, $id_user, $id_object, $gid){
		$settings = array();
		$this->DB->select(implode(", ", $this->attrs));
		$this->DB->from(GEOMAPS_SETTINGS_TABLE);
		$this->DB->where("map_gid", $map_gid);
		$this->DB->where("id_user", $id_user);
		$this->DB->where("id_object", $id_object);
		$this->DB->where("gid", $gid);
		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			return $this->format_settings($results[0]);
		}else{
			return false;
		}
	}

	public function get_parsed_settings($map_gid, $id_user=0, $id_object=0, $gid=""){
		$settings = array('lat'=>0, 'lon'=>0, 'zoom'=>0, 'view_type'=>0, 'view_settings'=>array());
		$this->DB->select(implode(", ", $this->attrs))->from(GEOMAPS_SETTINGS_TABLE)->where("map_gid", $map_gid);
		if(!empty($id_user)){
			$this->DB->where("(id_user='".intval($id_user)."' OR id_user='0')");
		}else{
			$this->DB->where("id_user", 0);
		}
		if(!empty($id_object)){
			$this->DB->where("(id_object='".intval($id_object)."' OR id_object='0')");
		}else{
			$this->DB->where("id_object", 0);
		}
		if(!empty($gid)){
			$this->DB->where("(gid='".$gid."' OR gid='')");
		}else{
			$this->DB->where("gid", "");
		}
		$this->DB->order_by("id_object ASC")->order_by("id_user ASC")->order_by("gid ASC");
		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			foreach($results as $result){
				$result = $this->format_settings($result);
				
				if($result['lat'] != 0 || $result['lon'] != 0){
					$settings['lat'] = $result['lat'];
					$settings['lon'] = $result['lon'];
				}
				if($result['zoom'] != 0) $settings['zoom'] = $result['zoom'];
				if($result['view_type'] != 0) $settings['view_type'] = $result['view_type'];
				
				foreach($this->attrs as $attr){
					if($attr != 'view_settings') unset($result[$attr]);
				}
				
				$settings = array_merge($settings, $result);
			}
		}else{
			$settings = $this->default_settings;
		}
		return $settings;
	}

	public function save_settings($map_gid, $id_user, $id_object, $gid, $data=array()){
		if($this->is_settings_exists($map_gid, $id_user, $id_object, $gid)){
			$this->DB->where('map_gid', $map_gid);
			$this->DB->where('id_user', $id_user);
			$this->DB->where('id_object', $id_object);
			$this->DB->where('gid', $gid);
			$this->DB->update(GEOMAPS_SETTINGS_TABLE, $data);
		}else{
			$data["map_gid"] = $map_gid;
			$data["id_user"] = $id_user;
			$data["id_object"] = $id_object;
			$data["gid"] = $gid;
			$this->DB->insert(GEOMAPS_SETTINGS_TABLE, $data);
		}
		return;
	}
	
	/**
	 * Save map marker
	 * @param string $map_gid map GUID
	 * @param string $gid page guid
	 * @param string $file_name file name
	 * @return void
	 */
	public function save_marker_icon($map_gid, $gid, $file_name){
		if(empty($file_name) || !isset($_FILES[$file_name]) ||
			!is_array($_FILES[$file_name]) || !is_uploaded_file($_FILES[$file_name]["tmp_name"])){
			return;
		}
	
		$this->CI->load->model("Uploads_model");
		$img_return = $this->CI->Uploads_model->upload($this->upload_config_id, $map_gid.($gid ? '_'.$gid : '')."/", $file_name);

		if(!empty($img_return["error"])) return;

		$settings = $this->get_parsed_settings($map_gid, 0, 0, $gid);
		$settings['marker_icon'] = $img_return['file'];
		unset($settings['id']);
		$validate_data = $this->CI->Geomap_settings_model->validate_settings($settings);
		$this->save_settings($map_gid, 0, 0, $gid, $validate_data['data']);
	}	
		
	public function delete_settings($map_gid, $id_user, $id_object, $gid){
		$this->DB->where('map_gid', $map_gid);
		$this->DB->where('id_user', $id_user);
		$this->DB->where('id_object', $id_object);
		$this->DB->where('gid', $gid);
		$this->DB->delete(GEOMAPS_SETTINGS_TABLE);
		return;
	}
	
	/**
	 * Remove map marker icon
	 * @param string $map_gid map GUID 
	 * @param string $gid page GUID
	 */
	public function delete_marker_icon($map_gid, $gid){
		$this->CI->load->model("Uploads_model");		
		$settings = $this->get_parsed_settings($map_gid, 0, 0, $gid);		
		$this->CI->Uploads_model->delete_upload($this->upload_config_id, $map_gid.($gid ? '_'.$gid : '') . "/", $settings["marker_icon"]);
		unset($settings['marker_icon']);		
		unset($settings['id']);
		
		$validate_data = $this->CI->Geomap_settings_model->validate_settings($settings);
		$this->save_settings($map_gid, 0, 0, $gid, $validate_data['data']);
	}

	public function validate_settings($data){
		$return = array("errors"=> array(), "data" => array());

		if(isset($data["lat"])){
			$return["data"]["lat"] = strval(floatval($data["lat"]));
			unset($data["lat"]);
		}

		if(isset($data["lon"])){
			$return["data"]["lon"] = strval(floatval($data["lon"]));
			unset($data["lon"]);
		}

		if(isset($data["zoom"])){
			$return["data"]["zoom"] = intval($data["zoom"]);
			unset($data["zoom"]);
		}

		if(isset($data["view_type"])){
			$return["data"]["view_type"] = intval($data["view_type"]);
			unset($data["view_type"]);
		}
		
		$return["data"]["view_settings"] = serialize($data);
		
		return $return;
	}

	/**
	 * Validate map marker icon
	 * @param string $file_name file name
	 */
	public function validate_marker_icon($file_name){
		$return = array('errors' => array(), 'data' => array());
		if(isset($_FILES[$file_name]) && is_array($_FILES[$file_name]) && is_uploaded_file($_FILES[$file_name]["tmp_name"])){
			$this->load->model("Uploads_model");
			$validate_image = $this->CI->Uploads_model->validate_upload($this->upload_config_id, $file_name);
			if($validate_image["error"]) $return["errors"] = $validate_image["error"];
		}
		return $return;
	}
	
	public function format_settings($data){
		$view_settings = $data["view_settings"] ? (array)unserialize($data["view_settings"]) : array();
		unset($data["view_settings"]);
		foreach($view_settings as $name => $value){
			$data[$name] = $value;
		}
		if(isset($data['marker_icon']) && !empty($data["marker_icon"])){
			$this->CI->load->model('Uploads_model');
			$data["media"]["icon"] = $this->CI->Uploads_model->format_upload($this->upload_config_id, $data['map_gid'].($data['gid'] ? '_'.$data['gid'] : ''), $data["marker_icon"]);
		}
		return $data;
	}
	
	public function is_settings_exists($map_gid, $id_user, $id_object, $gid){
		$this->DB->select("COUNT(*) AS cnt");
		$this->DB->from(GEOMAPS_SETTINGS_TABLE);
		$this->DB->where('map_gid', $map_gid);
		$this->DB->where('id_user', $id_user);
		$this->DB->where('id_object', $id_object);
		$this->DB->where('gid', $gid);
		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results) && intval($results[0]["cnt"])){
			return true;
		}
		return false;
	}
	
	/**
	 * Return available maps
	 */
	public function get_maps_lists($map_gid){
		$this->DB->select("gid");
		$this->DB->from(GEOMAPS_SETTINGS_TABLE);
		$this->DB->where('map_gid', $map_gid);
		$this->DB->where('id_user', 0);
		$this->DB->where('id_object', 0);
		$this->DB->where('gid !=', '');
		$results = $this->DB->get()->result_array();
		return $results;
	}
	
	/**
	 * Update languages
	 * @param array $data
	 * @param array $langs_file
	 * @param array $langs_ids
	 */
	public function update_lang($data, $langs_file, $langs_ids){
		foreach($data as $value){
			$this->CI->pg_language->pages->set_string_langs('geomap', $value, $langs_file[$value], $langs_ids);
		}
	}
	
	/**
	 * Export languages
	 * @param array $data
	 * @param array $langs_ids
	 */
	public function export_lang($data, $langs_ids){
		$langs = array();
		return array_merge($langs, $this->CI->pg_language->export_langs("geomap", (array)$data, $langs_ids));
	}
}
