<?php

/**
 * Seo module
 *
 * @package 	PG_Core
 * @copyright 	Copyright (c) 2000-2014 PG Core
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
  
/**
 * Seo admin side controller
 *
 * @package 	PG_Core
 * @subpackage 	Seo
 * @category 	controllers
 * @copyright 	Copyright (c) 2000-2014 PG Core
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
class Admin_Seo extends Controller
{
	/**
	 * Class constructor
	 * 
	 * @return Admin_Seo
	 */
	public function __construct(){
		parent::Controller();
		
		$this->load->model('Menu_model');
		$this->load->model('Seo_model');
		
		$this->Menu_model->set_menu_active_item('admin_menu', 'system-items');
		$this->system_messages->set_data('header', l('admin_header_list', 'seo'));
	}

	/**
	 * Render index action
	 * 
	 * @return void
	 */
	public function index(){
		$this->default_listing();
	}

	/**
	 * Render global settings management action
	 * 
	 * @return void
	 */
	public function default_listing(){

		$this->Menu_model->set_menu_active_item('admin_seo_menu', 'seo_default_list_item');
		$this->template_lite->view('default_list');
	}

	/**
	 * Render edit global settings action
	 * 
	 * @param string $controller user mode controller
	 * @return void
	 */
	public function default_edit($controller){
		$languages = $this->pg_language->languages;

		$user_settings = $this->pg_seo->get_settings($controller, '', '');
		$user_settings['lang_canonical'] = $this->pg_module->get_module_config('seo', 'lang_canonical');
		
		if($this->input->post('btn_save')){
			$post_data = array(
				"title" => $this->input->post('title', true),
				"keyword" => $this->input->post('keyword', true),
				"description" => $this->input->post('description', true),
				"header" => $this->input->post('header', true),
				"og_title" => $this->input->post('og_title', true),
				"og_type" => $this->input->post('og_type', true),
				"og_description" => $this->input->post('og_description', true),
				"lang_in_url" => $this->input->post('lang_in_url', true),
				'lang_canonical' => $this->input->post('lang_canonical', true),
			);
			
			$validate_data = $this->Seo_model->validate_seo_settings($controller, '', '', $post_data);
			if(!empty($validate_data['errors'])){
				$this->system_messages->add_message('error', $validate_data['errors']);
				
				foreach($languages as $lang_id => $lang_data){
					$user_settings['meta_'.$lang_id]['title'] = $post_data['title'][$lang_id];
					$user_settings['meta_'.$lang_id]['keyword'] = $post_data['keyword'][$lang_id];
					$user_settings['meta_'.$lang_id]['description'] = $post_data['description'][$lang_id];
					$user_settings['meta_'.$lang_id]['header'] = $post_data['header'][$lang_id];
					$user_settings['og_'.$lang_id]['og_title'] = $post_data['og_title'][$lang_id];
					$user_settings['og_'.$lang_id]['og_type'] = $post_data['og_type'][$lang_id];
					$user_settings['og_'.$lang_id]['og_description'] = $post_data['og_description'][$lang_id];
					$user_settings['lang_in_url'] = $post_data['lang_in_url'];
					$user_settings['lang_canonical'] = $post_data['lang_canonical'] ? 1 : 0;
				}
			}else{
				$this->pg_module->set_module_config('seo', 'lang_canonical', $post_data['lang_canonical'] ? 1 : 0);
				$this->pg_seo->set_settings($controller, '', '', $validate_data['data']);
				$this->system_messages->add_message('success', l('success_settings_saved', 'seo'));
				
				foreach($languages as $lang_id => $lang_data){
					$user_settings['meta_'.$lang_id]['title'] = $validate_data['data']['title'][$lang_id];
					$user_settings['meta_'.$lang_id]['keyword'] = $validate_data['data']['keyword'][$lang_id];
					$user_settings['meta_'.$lang_id]['description'] = $validate_data['data']['description'][$lang_id];
					$user_settings['meta_'.$lang_id]['header'] = $validate_data['data']['header'][$lang_id];
					$user_settings['og_'.$lang_id]['og_title'] = $validate_data['data']['og_title'][$lang_id];
					$user_settings['og_'.$lang_id]['og_type'] = $validate_data['data']['og_type'][$lang_id];
					$user_settings['og_'.$lang_id]['og_description'] = $validate_data['data']['og_description'][$lang_id];
					$user_settings['lang_in_url'] = $validate_data['data']['lang_in_url'];
				}
				
				$url = site_url()."admin/seo/default_edit/".$controller;
				redirect($url);
			}
		}

		$this->template_lite->assign("controller", $controller);
		$this->template_lite->assign("languages", $languages);
		$this->template_lite->assign("user_settings", $user_settings);
		
		$this->Menu_model->set_menu_active_item('admin_seo_menu', 'seo_default_list_item');
		$this->system_messages->set_data('header', l('admin_header_default_edit', 'seo'));
		$this->template_lite->view('default_edit_form');
	}
}
