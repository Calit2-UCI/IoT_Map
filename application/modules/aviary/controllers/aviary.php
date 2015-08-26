<?php

/**
 * Aviary module
 *
 * @package 	PG_Dating
 * @copyright 	Copyright (c) 2000-2014 PG Dating Pro - php dating software
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */

/**
 * Aviary user side controller
 * 
 * @package 	PG_Dating
 * @subpackage 	Aviary
 * @category	controllers
 * @copyright 	Copyright (c) 2000-2014 PG Dating Pro - php dating software
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
class Aviary extends Controller{
	/**
	 * Constructor
	 * 
	 * @return Aviary
	 */
	public function __construct(){
		parent::Controller();
	}
	
	/**
	 * Save photo after aviary editor
	 * 
	 * @return void
	 */
	public function save(){
		$used = $this->pg_module->get_module_config('aviary', 'used');
		if(!$used) return;
	
		$image_url = $this->input->post('url', true);
		if(!$image_url) show_404();
	
		$data = $this->input->post('postdata', true);
		if(empty($data)) show_404();
		
		$data = @json_decode(stripslashes($data), true);
	
		if(empty($data['code']) || empty($data['module'])) show_404();
		
		if(!isset($data['data'])) $data['data'] = array();
		
		$this->load->model('Aviary_model');
		$check = $this->Aviary_model->save_encode($data['module'], $data['data']);
		if($data['code'] != $check) show_404();
	
		$module_data = $this->Aviary_model->get_module($data['module']);	
		if(empty($module_data)) show_404();
		
		if(strtolower($module_data['module_gid'].'_model') != strtolower($module_data['model_name'])){
			$model_path = $module_data['module_gid'].'/models/'.$module_data['model_name'];
		}else{
			$model_path = $module_data['model_name'];
		}
	
		try{
			$this->load->model($model_path);
			$this->{$module_data['model_name']}->{$module_data['method']}($image_url,  $data['data']);
		}catch(Exception $e){			
		}	
	}
}
