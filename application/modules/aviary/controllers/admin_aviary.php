<?php

/**
 * Aviary module
 *
 * @package 	PG_Dating
 * @copyright 	Copyright (c) 2000-2014 PG Dating Pro - php dating software
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */

/**
 * Aviary admin side controller
 * 
 * @package 	PG_Dating
 * @subpackage 	Aviary
 * @category	controllers
 * @copyright 	Copyright (c) 2000-2014 PG Dating Pro - php dating software
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
class Admin_Aviary extends Controller{
	
	/**
	 * Constructor
	 *
	 * @return Admin_Aviary
	 */
	public function __construct(){
		parent::Controller();
		$this->load->model("Menu_model");
		$this->Menu_model->set_menu_active_item("admin_menu", "add_ons_items");
	}
	
	/**
	 * Module settings
	 * 
	 * @return void
	 */
	public function index(){
		return $this->settings();
	}
	
	/**
	 * Render module settings
	 * 
	 * return void
	 */
	public function settings(){
		if($this->input->post("btn_save")){
			$post_data = $this->input->post('data', true);
			$this->load->model("Aviary_model");
			$validate_data = $this->Aviary_model->validate_settings($post_data);
			if(!empty($validate_data["errors"])){
				$this->system_messages->add_message("error", $validate_data["errors"]);
				$data = $post_data;
			}else{
				foreach($validate_data["data"] as $setting=>$value){
					$this->pg_module->set_module_config("aviary", $setting, $value);
				}
				$this->system_messages->add_message("success", l("success_settings_saved", "aviary"));
				$data = $validate_data["data"];
			}
		}else{
			$data["used"] = $this->pg_module->get_module_config("aviary", "used");
			$data["api_key"] = $this->pg_module->get_module_config("aviary", "api_key");
		}
		$this->template_lite->assign("data", $data);		
		$this->system_messages->set_data("header", l("admin_header_settings", "aviary"));				
		$this->template_lite->view("settings");
	}
}
