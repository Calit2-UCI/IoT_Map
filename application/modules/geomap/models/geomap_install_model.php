<?php
/**
* Geomap install model
* 
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/
class Geomap_install_model extends Model{
	/**
	 * Link to CodeIgniter object
	 */
	var $CI;
	
	/**
	 * Menu configuration
	 * @var array
	 */
	private $menu = array(
		"admin_menu" => array(
			"action" => "none",
			"items" => array(
				"interface-items" => array(
					"action" => "none",
					"items" => array(
						"geomap_menu_item" => array("action" => "create", "link" => "admin/geomap", "status" => 1, "sorter" => 2),
					),
				),
				'system-items' => array(
					"action" => "none",
					"items" => array(
						"geomap_sys_menu_item" => array("action" => "create", "link" => "admin/geomap", "status" => 1, "sorter" => 4),
					),
				),
			),			
		),
	);
	
	/**
	 * Uploads configuration
	 * @var array
	 */
	private $uploads = array(
		array(
			"gid" => "map-marker-icon", 
			"name" => "Map marker icon", 
			"max_height" => 300, 
			"max_width" => 300, 
			"max_size" => 500000, 
			"name_format" => "generate", 
			"file_formats" => array("jpg", "gif", "png"), 
			"default_img" => "",
			"thumbs" => array(
				"small" => array("width"=>20, "height"=>30, "effect"=>"none", "crop_param"=>"crop", "crop_color"=>"ffffff"),
			),
		),
	);
	
	/**
	 * Constructor
	 *
	 * @return Install object
	 */
	public function Geomap_install_model(){
		parent::Model();
		$this->CI = & get_instance();
		//// load langs
		$this->CI->load->model("Install_model");
	}

	/**
	 * Install links to menu module
	 */
	public function install_menu(){
		
		$this->CI->load->helper("menu");

		foreach($this->menu as $gid => $menu_data){
			$this->menu[$gid]["id"] = linked_install_set_menu($gid, $menu_data["action"], $menu_data["name"]);
			linked_install_process_menu_items($this->menu, "create", $gid, 0, $this->menu[$gid]["items"]);
		}
	}
	
	/**
	 * Update languages
	 * @param array $lang_ids
	 */
	public function install_menu_lang_update($langs_ids=null){
		if(empty($langs_ids)) return false;
		$langs_file = $this->CI->Install_model->language_file_read("geomap", "menu", $langs_ids);

		if(!$langs_file){log_message("info", "Empty menu langs data"); return false;}

		$this->CI->load->helper("menu");

		foreach($this->menu as $gid => $menu_data){
			linked_install_process_menu_items($this->menu, "update", $gid, 0, $this->menu[$gid]["items"], $gid, $langs_file);
		}
		return true;
	}

	/**
	 * Export languages
	 * @param array $lang_ids
	 */
	public function install_menu_lang_export($langs_ids){
		if(empty($langs_ids)) return false;
		$this->CI->load->helper("menu");

		$return = array();
		foreach($this->menu as $gid => $menu_data){
			$temp = linked_install_process_menu_items($this->menu, "export", $gid, 0, $this->menu[$gid]["items"], $gid, $langs_ids);
			$return = array_merge($return, $temp);
		}
		return array("menu" => $return);
	}
	
	/**
	 * Uninstall menu
	 */
	public function deinstall_menu(){		
		$this->CI->load->helper('menu');
		foreach($this->menu as $gid => $menu_data){
			if($menu_data['action'] == 'create'){
				linked_install_set_menu($gid, 'delete');
			}else{
				linked_install_delete_menu_items($gid, $this->menu[$gid]['items']);
			}
		}
	}
	
	/**
	 * Install uploades
	 */
	public function install_uploads(){
		///// upload config
		$this->CI->load->model("uploads/models/Uploads_config_model");
		
		$watermark_ids = array();
		
		foreach((array)$this->uploads as $upload_data){
			$config_data = array(
				"gid" 			=> $upload_data["gid"],
				"name" 			=> $upload_data["name"],
				"max_height" 	=> $upload_data["max_height"],
				"max_width" 	=> $upload_data["max_width"],
				"max_size" 		=> $upload_data["max_size"],
				"name_format" 	=> $upload_data["name_format"],
				"file_formats" 	=> serialize((array)$upload_data["file_formats"]),
				"default_img" 	=> $upload_data["default_img"],
				"date_add" => date("Y-m-d H:i:s"),
			);
			$config_id = $this->CI->Uploads_config_model->save_config(null, $config_data);
		
			$wm_data = $this->CI->Uploads_config_model->get_watermark_by_gid("image-wm");
			$wm_id = isset($wm_data["id"])?$wm_data["id"]:0;
			
			foreach((array)$upload_data["thumbs"] as $thumb_gid => $thumb_data){
				if(isset($thumb_data["watermark"])){
					if(!isset($watermark_ids[$thumb_data["watermark"]])){
						$wm_data = $this->CI->Uploads_config_model->get_watermark_by_gid($thumb_data["watermark"]);
						$watermark_ids[$thumb_data["watermark"]] = isset($wm_data["id"])?$wm_data["id"]:0;
					}
					$watermark_id = $watermark_ids[$thumb_data["watermark"]];
				}else{
					$watermark_id = 0;
				}
				
				$thumb_data["config_id"] = $config_id;
				$thumb_data["prefix"] = $thumb_gid;
				$thumb_data["effect"] = "none";
				$thumb_data["watermark_id"] = $watermark_id;

				$validate_data = $this->CI->Uploads_config_model->validate_thumb(null, $thumb_data);
				if(!empty($validate_data["errors"])) continue;
				$this->CI->Uploads_config_model->save_thumb(null, $validate_data["data"]);
			}
		}
	}

	/**
	 * Uninstall uploads links
	 */
	public function deinstall_uploads(){
		$this->CI->load->model("uploads/models/Uploads_config_model");
		
		foreach((array)$this->uploads as $upload_data){
			$config_data = $this->CI->Uploads_config_model->get_config_by_gid($upload_data["gid"]);
			if(!empty($config_data["id"])){
				$this->CI->Uploads_config_model->delete_config($config_data["id"]);
			}
		}
	}

	/**
	 * Install module
	 */
	function _arbitrary_installing(){

	}

	/**
	 * Unintall module
	 */
	function _arbitrary_deinstalling(){
		
	}
}
