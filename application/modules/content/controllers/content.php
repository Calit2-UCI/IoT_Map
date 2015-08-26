<?php

/**
 * Content module
 *
 * @package 	PG_Dating
 * @copyright 	Copyright (c) 2000-2014 PilotGroup.NET Powered by PG Dating Pro
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */

/**
 * Content user side controller
 * 
 * @package 	PG_Dating
 * @subpackage 	Content
 * @category	controllers
 * @copyright 	Copyright (c) 2000-2014 PilotGroup.NET Powered by PG Dating Pro
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
Class Content extends Controller
{
	/**
	 * Class constructor
	 * 
	 * @return Content
	 */
	public function __construct(){
		parent::Controller();
		$this->load->model("Content_model");
	}

	/**
	 * Content pages list
	 * 
	 * @param integer $lang_id language identifier
	 * @return void
	 */
	public function index($lang_id=null){
		$seo_settings = $this->pg_seo->get_settings('user', 'content', 'index');
		$headline = $seo_settings['meta_'.$this->pg_language->current_lang_id]['header'];
		
		$this->load->model('Menu_model');
		$this->Menu_model->breadcrumbs_set_active($headline, site_url().'content/');

		$params = array();
		$params['where']['parent_id'] = '0';
		$params['where']['status'] = '1';
		$lang_id = $this->pg_language->current_lang_id;
		$pages_list = $this->Content_model->get_pages_list($lang_id, 0, $params);

		$this->template_lite->assign('pages', $pages_list);
		$this->template_lite->assign('date_format', $this->pg_date->get_format('date_time_literal', 'st'));
		$this->template_lite->view('list');
	}

	/**
	 * Show content page
	 * 
	 * @param string $gid page guid
	 * @return void
	 */
	public function view($gid){
		$gid = trim(strip_tags($gid));
	 	if(!$gid) show_404();
	 
		$page_data = $this->Content_model->get_page_by_gid($gid);
		if(empty($page_data)) {
			show_404();
		};
		$this->template_lite->assign("page", $page_data);

		$this->load->model('Menu_model');
		$seo_settings = $this->pg_seo->get_settings('user', 'content', 'index');
		$headline = $seo_settings['meta_'.$this->pg_language->current_lang_id]['header'];
		$this->Menu_model->breadcrumbs_set_active($headline, site_url().'content/');
		$this->Menu_model->breadcrumbs_set_active($page_data["title"]);

		$this->load->helper('seo');
		
		$seo_data = array(
			'id' => $page_data['id'],
			'gid' => $page_data['gid'],
			'title' => $page_data['title'],
		);
			
		$lang_canonical = true;
				
		if($this->pg_module->is_module_installed('seo')){
			$lang_canonical = $this->pg_module->get_module_config('seo', 'lang_canonical');
		}
		
		if($lang_canonical){
			$lang_id = $this->pg_language->get_default_lang_id();
			if($lang_id != $this->pg_language->current_lang_id){
				$page_canonical = $this->Content_model->get_page_by_gid($gid, $lang_id);
				$seo_data['title'] = $page_canonical['title'];
			}
		}
		
		if($page_data['id_seo_settings']){
			$this->load->model('Seo_advanced_model');
			$seo_settings = $this->Seo_advanced_model->parse_seo_tags($page_data['id_seo_settings']);
			$seo_settings['canonical'] = rewrite_link('content', 'view', $seo_data, false, null, $lang_canonical);
			$this->pg_seo->set_seo_tags($seo_settings);
		}else{
			$seo_settings = $page_data;
			$seo_settings['canonical'] = rewrite_link('content', 'view', $seo_data, false, null, $lang_canonical);
			$this->pg_seo->set_seo_data($seo_settings);
		}
		
		$this->template_lite->view('view');
	}
}
