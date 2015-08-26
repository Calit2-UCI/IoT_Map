<?php  
/**
* Geomaps main model
* 
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

if (!defined('BASEPATH')) exit('No direct script access allowed');

define('GEOMAPS_DRIVERS_TABLE', DB_PREFIX.'geomap_drivers');

class Geomap_model extends Model
{
	private $CI;
	private $DB;

	private $attrs = array('id', 'gid', 'regkey', 'need_regkey', 'link', 
		'date_add', 'date_modified', 'status');

	private $driver_cache = array();

	private $default_driver_gid = '';

	private $default_view_settings = array(
		"width" => "500",
		"height" => "300", 
		"class" => "", 
		'zoom_listener' => "", 
		'type_listener' => "",
		'drag_listener' => "",
		'lang' => "en",
	);
	
	static $geomap_map_type_js_loaded = false;
	
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

		$this->get_default_driver();
	}

	public function get_driver_by_gid($gid){
		if(empty($this->driver_cache[$gid])){
			$this->DB->select(implode(", ", $this->attrs))->from(GEOMAPS_DRIVERS_TABLE)->where("gid", $gid);
			$results = $this->DB->get()->result_array();
			if(!empty($results) && is_array($results)){
				$this->driver_cache[$gid] = $this->format_driver($results[0]);
			}
		}
		return $this->driver_cache[$gid];
	}

	public function get_default_driver(){
		if(!empty($this->default_driver_gid)){
			return $this->get_driver_by_gid($this->default_driver_gid);
		}

		$this->DB->select(implode(", ", $this->attrs))->from(GEOMAPS_DRIVERS_TABLE)->where("status", "1")->limit(1);
		$results = $this->DB->get()->result_array();
		
		if(!empty($results) && is_array($results)){
			$this->default_driver_gid = $results[0]["gid"];
			$this->driver_cache[$this->default_driver_gid] = $this->format_driver($results[0]);
			return $this->driver_cache[$this->default_driver_gid];
		}
		return array();
	}
	
	public function get_default_driver_gid(){
		return $this->default_driver_gid;
	}
	
	public function format_driver($data){
		$data["name"] = l('driver_name_'.$data["gid"], 'geomap');
		return $data;
	}

	public function set_default_driver($gid){
		$this->driver_cache = array();
		$this->default_driver_gid = $gid;

		$data["status"] = 0;
		$this->DB->update(GEOMAPS_DRIVERS_TABLE, $data);

		$data["status"] = 1;
		$this->DB->where('gid', $gid);
		$this->DB->update(GEOMAPS_DRIVERS_TABLE, $data);
		return;
	}

	public function get_drivers(){
		$data = array();
		$this->DB->select(implode(", ", $this->attrs))->from(GEOMAPS_DRIVERS_TABLE);
		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			foreach($results as $result){
				$data[] = $this->format_driver($result);
			}
		}
		return $data;
	}

	public function set_driver($gid, $data){
		if (is_null($gid)){
			$data["date_add"] = $data["date_modified"] = date("Y-m-d H:i:s");
			if(!isset($attrs["status"])) $data["status"] = 0;
			$this->DB->insert(GEOMAPS_DRIVERS_TABLE, $data);
		}else{
			$data["date_modified"] = date("Y-m-d H:i:s");
			$this->DB->where('gid', $gid);
			$this->DB->update(GEOMAPS_DRIVERS_TABLE, $data);
			unset($this->driver_cache[$gid]);
		}
		return;
	}

	public function validate_driver($gid, $data){
		$return = array("errors"=> array(), "data" => array());

		if(isset($data["regkey"])){
			$return["data"]["regkey"] = trim(strip_tags($data["regkey"]));
		}

		return $return;
	}

	public function delete_driver($id){
	
	}

	public function create_default_map($id_user=0, $id_object=0, $gid="", $markers=array(), $view_settings=array(), $only_load_scripts=false, $only_load_content=false, $map_id=false){
		return $this->create_map($this->default_driver_gid, $id_user, $id_object, $gid, $markers, $view_settings, $only_load_scripts, $only_load_content, $map_id);
	}

	public function create_map($map_type, $id_user=0, $id_object=0, $gid="", $markers=array(), $view_settings=array(), $only_load_scripts=false, $only_load_content=false, $map_id=false){
		$this->CI->load->model("geomap/models/Geomap_settings_model");
		$driver_settings = $this->get_driver_by_gid($map_type);
		if(empty($driver_settings) /*|| ($driver_settings["need_regkey"] && empty($driver_settings["regkey"]))*/){
			return "";
		}
		
		$this->CI->template_lite->assign('geomap_js_loaded', $this->geomap_map_type_js_loaded);
		$this->geomap_map_type_js_loaded = true;
		
		$this->CI->template_lite->assign('only_load_scripts', $only_load_scripts);
		$this->CI->template_lite->assign('only_load_content', $only_load_content);
		
		$settings = $this->CI->Geomap_settings_model->get_parsed_settings($map_type, $id_user, $id_object, $gid);
		if(empty($view_settings)){
			$view_settings = $this->default_view_settings;
		}
		$view_settings["rand"] = rand(10000, 99999);
	
		$model_name = ucfirst(strtolower($map_type))."_model";
		$this->CI->load->model("geomap/models/".$model_name, $model_name);
	
		return $this->CI->$model_name->create_html($driver_settings["regkey"], $settings, $view_settings, $markers, $map_id);
	}
	
	public function update_default_map($map_id, $markers=array(), $settings=array()){
		return $this->update_map($this->default_driver_gid, $map_id, $markers, $settings);
	}
	
	public function update_map($map_type, $map_id, $markers=array(), $settings=array()){
		$driver_settings = $this->get_driver_by_gid($map_type);
		if(empty($driver_settings) /*|| ($driver_settings["need_regkey"] && empty($driver_settings["regkey"]))*/){
			return "";
		}
		
		$model_name = ucfirst(strtolower($map_type))."_model";
		$this->CI->load->model("geomap/models/".$model_name, $model_name);
	
		return $this->CI->$model_name->update_html($map_id, $markers, $settings);
	}

	function _dynamic_block_map_example($params, $view=''){
		$markers[] = array( "gid" => 'test', "lat" => $params["lat"], "lon" => $params["lon"], "html" => $params["text"], "dragging" => false );
		return $this->create_default_map(0, 'example', $markers);
	}
	
	public function create_default_geocoder(){
		return $this->create_geocoder($this->default_driver_gid);
	}
	
	public function create_geocoder($map_type){
		$driver_settings = $this->get_driver_by_gid($map_type);
		if(empty($driver_settings) /*|| ($driver_settings["need_regkey"] && empty($driver_settings["regkey"]))*/){
			return "";
		}
		
		static $geomap_geocoder_js_loaded = false;
		if($geomap_geocoder_js_loaded) return '';
		$geomap_geocoder_js_loaded = true;
		
		$this->CI->template_lite->assign('geomap_js_loaded', $this->geomap_map_type_js_loaded);
		$this->geomap_map_type_js_loaded = true;
		
		$model_name = ucfirst(strtolower($map_type))."_model";
		$this->CI->load->model("geomap/models/".$model_name, $model_name);
	
		return $this->CI->$model_name->create_geocoder($driver_settings["regkey"]);
	}
}
