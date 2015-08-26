<?php

/**
 * Content module
 *
 * @package 	PG_Dating
 * @copyright 	Copyright (c) 2000-2014 PilotGroup.NET Powered by PG Dating Pro
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */

/**
 * Content admin side controller
 *
 * @package 	PG_Dating
 * @subpackage 	Content
 * @category	controllers
 * @copyright 	Copyright (c) 2000-2014 PilotGroup.NET Powered by PG Dating Pro
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
Class Admin_Content extends Controller
{
	/**
	 * Class constructor
	 * 
	 * @return Admin_Content
	 */
	public function __construct(){
		parent::Controller();
		$this->load->model("Content_model");
		$this->load->model('Menu_model');
		$this->Menu_model->set_menu_active_item('admin_menu', 'content_items');
	}

	/**
	 * Manage pages tree
	 * 
	 * @param integer $lang_id language identifier
	 * @return void
	 */
	public function index($lang_id=null){
		if(!$lang_id) $lang_id = $this->pg_language->get_default_lang_id();

		$languages = $this->pg_language->languages;
		$this->template_lite->assign("languages", $languages);
		$this->template_lite->assign("current_lang", $lang_id);

		$pages = $this->Content_model->get_pages_list($lang_id);
		$this->template_lite->assign("pages", $pages);

		$this->pg_theme->add_js('admin-multilevel-sorter.js');
		$this->system_messages->set_data('header', l('admin_header_page_list', 'content'));
		$this->template_lite->view('list');
	}

	/**
	 * Edit info page content
	 * 
	 * @param integer $lang_id language identifier
	 * @param integer $parent_id parent page identifier
	 * @param integer $page_id page identifier
	 * @param string $section_gid section guid
	 * @return void
	 */
	public function edit($lang_id, $parent_id=0, $page_id=null, $section_gid='text'){
		$languages = $this->pg_language->languages;
		
		if($page_id){
			$data = $this->Content_model->get_page_by_id($page_id, array_keys($languages));
		}else{
			$data = array();
		}

		if($this->input->post('btn_save')){
			switch($section_gid){
				case 'text':
					$post_data = array(
						//"title" => $this->input->post('title', true),
						//"content" => $this->input->post('content', true),
						"gid" => $this->input->post('gid', true),
						"lang_id" => $this->input->post('lang_id', true),
						"parent_id" => $parent_id
					);
					
					foreach($languages as $lid => $lang_data){
						$post_data["title_".$lid] = $this->input->post('title_'.$lid, true);
						$post_data["annotation_".$lid] = $this->input->post('annotation_'.$lid, true);
						$post_data["content_".$lid] = $this->input->post('content_'.$lid);
					}
					
					$validate_data = $this->Content_model->validate_page($page_id, $post_data);
					$validate_logo = $this->Content_model->validate_logo('page_icon');
					if(!empty($validate_data["errors"]) || !empty($validate_logo["errors"])){
						$this->system_messages->add_message('error', array_merge($validate_data["errors"], $validate_logo["errors"]));
					}else{
						if($this->input->post('page_icon_delete') && $page_id && $data["img"]){
							$this->Content_model->delete_logo($page_id);
						}
						
						$page_id = $this->Content_model->save_page($page_id, $validate_data["data"]);
						
						$this->Content_model->upload_logo($page_id, 'page_icon');
						
						if($page_id){
							$success = l('success_update_page', 'content');
						}else{
							$success = l('success_add_page', 'content');
						}
						
						$this->system_messages->add_message('success', $success);
						
						//$url = site_url()."admin/content/index/".$lang_id;
						$url = site_url()."admin/content/edit/".$lang_id.'/'.$parent_id.'/'.$page_id.'/'.$section_gid;
						redirect($url);
					}
					$data = array_merge($data, $post_data);
				break;
				case 'seo':
					$this->load->model('Seo_advanced_model');
					$seo_fields = $this->Seo_advanced_model->get_seo_fields();
					foreach($seo_fields as $key=>$section_data){
						if($this->input->post('btn_save_'.$section_data['gid'])){
							$post_data = array();
							$post_data[$section_data['gid']] = $this->input->post($section_data['gid'], true);
							$validate_data = $this->Seo_advanced_model->validate_seo_tags($page_id, $post_data);
							if(!empty($validate_data['errors'])){
								$this->system_messages->add_message('error', $validate_data["errors"]);
							}else{
								$page_data['id_seo_settings'] = $this->Seo_advanced_model->save_seo_tags($data['id_seo_settings'], $validate_data['data']);
								if(!$data['id_seo_settings']){
									$data['id_seo_settings'] = $page_data['id_seo_settings'];
									$this->Content_model->save_page($page_id, $page_data);
								}
								$this->system_messages->add_message('success', l('success_settings_updated', 'seo'));
								$url = site_url()."admin/content/edit/".$lang_id.'/'.$parent_id.'/'.$page_id.'/'.$section_gid;
								redirect($url);
							}
							$data = array_merge($data, $post_data);
							break;
						}
					}
				break;
			}
		}
		
		switch($section_gid){
			case 'text':
				$this->load->plugin('fckeditor');
				foreach($languages as $lid => $lang_data){
					$data["content_fck"][$lid] = create_editor("content_".$lid, isset($data["content_".$lid]) ? $data["content_".$lid] : "", 700, 400, 'Middle');
				}
			break;
			case 'seo':
				$this->load->model('Seo_advanced_model');
				$seo_fields = $this->Seo_advanced_model->get_seo_fields();
				$this->template_lite->assign('seo_fields', $seo_fields);
			
				$this->template_lite->assign('languages', $languages);
				
				$current_lang_id = $this->pg_language->current_lang_id;
				$this->template_lite->assign('current_lang_id', $current_lang_id);

				if($data['id_seo_settings']){
					$seo_settings = $this->Seo_advanced_model->get_seo_tags($data['id_seo_settings']);
					$this->template_lite->assign('seo_settings', $seo_settings);
				}
			break;
		}
		
		$this->template_lite->assign('data', $data);
		$this->template_lite->assign('section_gid', $section_gid);

		$this->template_lite->assign('languages', $languages);
		$this->template_lite->assign("current_lang", $lang_id);
		$this->template_lite->assign('parent_id', $parent_id);
		$this->system_messages->set_data('header', l('admin_header_page_edit', 'content'));
		$this->template_lite->view('edit_form');
	}

	/**
	 * Save page sorter by ajax
	 * 
	 * @return void
	 */
	public function ajax_save_sorter(){
		$sorter = $this->input->post("sorter");
		foreach($sorter as $parent_str => $items_data){
			$parent_id = intval(str_replace("parent_", "", $parent_str));
			foreach($items_data as $item_str =>$sort_index){
				$page_id = intval(str_replace("item_", "", $item_str));
				$data = array(
					"parent_id" => $parent_id,
					"sorter" => $sort_index
				);
				$this->Content_model->save_page($page_id, $data);
			}
		}
	}

	/**
	 * Activate/deactivate page by ajax
	 * 
	 * Available statuses: 1 - activate, 0 - deactivate
	 * 
	 * @param ionteger $status activity status
	 * @param integer $page_id page identifier
	 * @return void
	 */
	public function ajax_activate($status, $page_id){
		$this->Content_model->activate_page($page_id, $status);
	}

	/**
	 * Remove page by ajax
	 * 
	 * @param integer $page_id page identifier
	 * @return void
	 */
	public function ajax_delete($page_id){
		$this->Content_model->delete_page($page_id);
	}

	/**
	 * Manage promo block
	 * 
	 * @param integer $lang_id language identifier
	 * @param string $content_type content type
	 * @return void
	 */
	public function promo($lang_id=0, $content_type=''){
		if(!$lang_id) $lang_id = $this->pg_language->get_default_lang_id();
		$this->load->model("content/models/Content_promo_model");

		$promo_data = $this->Content_promo_model->get_promo($lang_id);

		if($this->input->post('btn_save_settings')){
			$post_data = array("content_type" => $this->input->post('content_type', true));
			switch($post_data['content_type']){
				case 't':
					$post_data = array_merge($post_data, $this->input->post('t', true));
				break;
				case 'f':
					$post_data = array_merge($post_data, $this->input->post('f', true));
				break;
				case 'v':
					$post_data = array_merge($post_data, $this->input->post('v', true));
				break;
			}
			$validate_data = $this->Content_promo_model->validate_promo($post_data);

			if(!empty($validate_data["errors"])){
				$this->system_messages->add_message('error', $validate_data["errors"]);
				$promo_data = array_merge($promo_data, $validate_data["data"]);
			}else{
				$this->Content_promo_model->save_promo($lang_id, $validate_data["data"]);
				$this->system_messages->add_message('success', l('success_update_promo_block', 'content'));
				$url = site_url()."admin/content/promo/".$lang_id;
				redirect($url);
			}
		}

		if($this->input->post('btn_save_content')){
			switch($content_type){
				case 't':
					$post_data = array(
						"promo_text" => $this->input->post('promo_text', true),
						"block_align_hor" => $this->input->post('block_align_hor', true),
						"block_align_ver" => $this->input->post('block_align_ver', true),
						"block_image_repeat" => $this->input->post('block_image_repeat', true),
					);
					$validate_data = $this->Content_promo_model->validate_promo($post_data, 'promo_image');
					if($this->input->post('promo_image_delete')){
						$this->load->model("Uploads_model");
						$this->Uploads_model->delete_upload($this->Content_promo_model->upload_gid, $lang_id."/", $promo_data['promo_image']);
						$validate_data["data"]["promo_image"] = '';
					}
					if(!empty($validate_data["errors"])){
						$this->system_messages->add_message('error', $validate_data["errors"]);
						$promo_data = array_merge($promo_data, $validate_data["data"]);
					}else{
						$this->Content_promo_model->save_promo($lang_id, $validate_data["data"], 'promo_image', 'promo_flash');
						$this->system_messages->add_message('success', l('success_update_promo_block', 'content'));
						$url = site_url()."admin/content/promo/".$lang_id."/".$content_type;
						redirect($url);
					}
				break;
				case 'f':
					$validate_data = $this->Content_promo_model->validate_promo(array(), '', 'promo_flash');
					
					if($this->input->post('promo_flash_delete')){
						$this->load->model("File_uploads_model");
						$this->File_uploads_model->delete_upload($this->Content_promo_model->file_upload_gid, $lang_id."/", $promo_data['promo_flash']);
						$validate_data["data"]["promo_flash"] = '';
					}
					
					if(!empty($validate_data["errors"])){
						$this->system_messages->add_message('error', $validate_data["errors"]);
						$promo_data = array_merge($promo_data, $validate_data["data"]);
					}else{
						$this->Content_promo_model->save_promo($lang_id, $validate_data["data"], 'promo_image', 'promo_flash');
						$this->system_messages->add_message('success', l('success_update_promo_block', 'content'));
						$url = site_url()."admin/content/promo/".$lang_id."/".$content_type;
						redirect($url);
					}
				break;
				case 'v':
					///// delete video
					if($this->input->post('promo_video_delete')){
						$this->Content_promo_model->delete_video($lang_id);
						$this->system_messages->add_message('success', l('success_video_deleted', 'content'));
					}else{
						$promo_video_embed = $this->input->post('promo_video_embed');
						$validate_data = $this->Content_promo_model->save_video($lang_id, 'promo_video', $promo_video_embed);
						if($validate_data['success']){
							$this->system_messages->add_message('success', l('success_video_uploaded', 'content'));
						}
					}
					if(!empty($validate_data['errors'])){
						$this->system_messages->add_message('error', $validate_data['errors']);
					}else{
						$url = site_url().'admin/content/promo/'.$lang_id.'/v';
						redirect($url);
					}
				break;
			}
		}

		if(!$content_type) $content_type = $promo_data["content_type"];

		$this->load->plugin('fckeditor');
		$promo_data["promo_text_fck"] = create_editor("promo_text", isset($promo_data["promo_text"]) ? $promo_data["promo_text"] : "", 570, 300, 'Middle');

		$this->template_lite->assign('languages', $this->pg_language->languages);
		$this->template_lite->assign("current_lang", $lang_id);
		$this->template_lite->assign("content_type", $content_type);
		$this->template_lite->assign("promo_data", $promo_data);
		$this->system_messages->set_data('header', l('admin_header_promo_edit', 'content'));
		$this->template_lite->view('promo_form');
	}
}
