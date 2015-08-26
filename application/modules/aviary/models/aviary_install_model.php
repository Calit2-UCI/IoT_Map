<?php

/**
 * Aviary module
 *
 * @package 	PG_Dating
 * @copyright 	Copyright (c) 2000-2014 PG Dating Pro - php dating software
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */

/**
 * Aviary install model
 * 
 * @package 	PG_Dating
 * @subpackage 	Aviary
 * @category	models
 * @copyright 	Copyright (c) 2000-2014 PG Dating Pro - php dating software
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
class Aviary_install_model extends Model{
	/**
	 * Link to CodeIgniter object
	 * 
	 * @var object
	 */
	protected $CI;

	/**
	 * Menu configuration
	 * 
	 * @var array
	 */
	protected $menu = array(
		'admin_menu' => array(
			'action' => 'none',
			'items' => array(
				'other_items' => array(
					'action' =>'none',
					'items' => array(
						//'aviary_menu_item' => array('action' => 'create', 'link' => 'admin/aviary', 'status' => 1, 'sorter' => 7),
						"add_ons_items" => array(
							"action" => "none",
							"items" => array(
								"aviary_menu_item" => array("action" => "create", "link" => "admin/aviary", "status" => 1, "sorter" => 4),
							),
						),
					),
				),
			),
		),
	);
	
	/**
	 * Moderators configuration
	 * 
	 * @var array
	 */
	protected $moderators = array(
		array("module" => "aviary", "method" => "index", "is_default" => 1),
	);
	
	/**
	 * Constructor
	 *
	 * @return Aviary_install_model
	 */
	public function __construct(){
		parent::Model();
		$this->CI = & get_instance();
	}
	
	/**
	 * Install menu data of aviary
	 * 
	 * @return void
	 */
	public function install_menu(){		
		$this->CI->load->helper("menu");
		foreach($this->menu as $gid => $menu_data){
			$this->menu[$gid]["id"] = linked_install_set_menu($gid, $menu_data["action"], isset($menu_data["name"]) ? $menu_data["name"] : '');
			linked_install_process_menu_items($this->menu, "create", $gid, 0, $this->menu[$gid]["items"]);
		}
	}
	
	/**
	 * Import menu languages of aviary
	 * 
	 * @param array $langs_ids languages identifiers
	 * @return void
	 */
	public function install_menu_lang_update($langs_ids=null){
		if(empty($langs_ids)) return false;
		$langs_file = $this->CI->Install_model->language_file_read("aviary", "menu", $langs_ids);

		if(!$langs_file){log_message("info", "Empty menu langs data"); return false;}

		$this->CI->load->helper("menu");

		foreach($this->menu as $gid => $menu_data){
			linked_install_process_menu_items($this->menu, "update", $gid, 0, $this->menu[$gid]["items"], $gid, $langs_file);
		}
		return true;
	}

	/**
	 * Export menu languages of aviary
	 * 
	 * @param array $langs_ids languages identifiers
	 * @return array
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
	 * Uninstall menu data of aviary
	 * 
	 * @return void
	 */
	public function deinstall_menu(){
		
		$this->CI->load->helper("menu");
		foreach($this->menu as $gid => $menu_data){
			if($menu_data["action"] == "create"){
				linked_install_set_menu($gid, "delete");
			}else{
				linked_install_delete_menu_items($gid, $this->menu[$gid]["items"]);
			}
		}
	}
	
	/**
	 * Install moderators data of aviary
	 * 
	 * @return void
	 */
	public function install_moderators(){
		// install moderators permissions
		$this->CI->load->model("Moderators_model");
		
		foreach((array)$this->moderators as $method_data){
			//$validate_data = $this->CI->Moderators_model->validate_method($method_data, true);
			$validate_data = array("errors" => array(), "data" => $method_data);
			if(!empty($validate_data["errors"])) continue;
			$this->CI->Moderators_model->save_method(null, $validate_data["data"]);
		}
	}
	
	/**
	 * Import moderators languages of aviary
	 * 
	 * @param array $langs_ids languages identifiers
	 * @return void
	 */
	public function install_moderators_lang_update($langs_ids=null){
		$langs_file = $this->CI->Install_model->language_file_read("aviary", "moderators", $langs_ids);
		if(!$langs_file){log_message("info", "Empty moderators langs data (aviary)");return false;}

		// install moderators permissions
		$this->CI->load->model("Moderators_model");
		$params["where"]["module"] = "aviary";
		$methods = $this->CI->Moderators_model->get_methods_lang_export($params);

		foreach($methods as $method){
			if(!empty($langs_file[$method["method"]])){
				$this->CI->Moderators_model->save_method($method["id"], array(), $langs_file[$method["method"]]);
			}
		}
	}
	
	/**
	 * Export moderators languages of aviary
	 * 
	 * @param array $langs_ids languages identifiers
	 * @return array
	 */
	public function install_moderators_lang_export($langs_ids){
		$this->CI->load->model("Moderators_model");
		$params["where"]["module"] = "aviary";
		$methods = $this->CI->Moderators_model->get_methods_lang_export($params, $langs_ids);
		foreach($methods as $method){
			$return[$method["method"]] = $method["langs"];
		}
		return array("moderators" => $return);
	}
	
	/**
	 * Uninstall moderators data of aviary
	 * 
	 * @return void
	 */
	public function deinstall_moderators(){
		$this->CI->load->model("Moderators_model");
		$params = array();
		$params["where"]["module"] = "aviary";
		$this->CI->Moderators_model->delete_methods($params);
	}
}
