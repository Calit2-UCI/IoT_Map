<?php

/**
 * News module
 *
 * @package 	PG_Dating
 * @copyright 	Copyright (c) 2000-2014 PilotGroup.NET Powered by PG Dating Pro
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */

/**
 * News admin side controller
 * 
 * @package 	PG_Dating
 * @subpackage 	News
 * @category	controllers
 * @copyright 	Copyright (c) 2000-2014 PilotGroup.NET Powered by PG Dating Pro
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
Class Admin_News extends Controller
{
	/**
	 * Class constructor
	 * 
	 * @return Admin_News
	 */
	public function __construct(){
		parent::Controller();
		$this->load->model("News_model");

		$this->load->model('Menu_model');
		$this->Menu_model->set_menu_active_item('admin_menu', 'content_items');
	}

	/**
	 * Manage news
	 * 
	 * @param string $order sorting order
	 * @param string $order_direction order direction
	 * @param integer $page page of results
	 * @return void
	 */
	public function index($order="date_add", $order_direction="DESC", $page=1){
		$attrs = $search_params = array();

		$current_settings = isset($_SESSION["news_list"])?$_SESSION["news_list"]:array();
		if(!isset($current_settings["order"])) $current_settings["order"] = "date_add";
		if(!isset($current_settings["order_direction"])) $current_settings["order_direction"] = "DESC";
		if(!isset($current_settings["page"])) $current_settings["page"] = 1;
		
		$current_settings["page"] = $page;

		if(!$order) $order = $current_settings["order"];
		$this->template_lite->assign('order', $order);
		$current_settings["order"] = $order;

		if(!$order_direction) $order_direction = $current_settings["order_direction"];
		$this->template_lite->assign('order_direction', $order_direction);
		$current_settings["order_direction"] = $order_direction;

		$news_count = $this->News_model->get_news_count();

		if(!$page) $page = $current_settings["page"];
		$items_on_page = $this->pg_module->get_module_config('start', 'admin_items_per_page');
		$this->load->helper('sort_order');
		$page = get_exists_page_number($page, $news_count, $items_on_page);
		$current_settings["page"] = $page;

		$_SESSION["news_list"] = $current_settings;

		$sort_links = array(
			"date_add" => site_url()."admin/news/index/date_add/".(($order!='date_add' xor $order_direction=='DESC')?'ASC':'DESC'),
			"name" => site_url()."admin/news/index/name/".(($order!='name' xor $order_direction=='DESC')?'ASC':'DESC'),
		);

		$this->template_lite->assign('sort_links', $sort_links);

		if ($news_count > 0){
			$news = $this->News_model->get_news_list($page, $items_on_page, array($order => $order_direction), $attrs);
			$this->template_lite->assign('news', $news);

		}
		$this->load->helper("navigation");
		$url = site_url()."admin/news/index/".$order."/".$order_direction."/";
		$page_data = get_admin_pages_data($url, $news_count, $items_on_page, $page, 'briefPage');
		$page_data["date_format"] = $this->pg_date->get_format('date_time_literal', 'st');
		$this->template_lite->assign('page_data', $page_data);

		$this->template_lite->assign('languages', $languages);


		$this->Menu_model->set_menu_active_item('admin_news_menu', 'news_list_item');
		$this->system_messages->set_data('header', l('admin_header_news_list', 'news'));
		$this->template_lite->view('list_news');
	}

	/**
	 * Edit news data
	 * 
	 * @param integer $news_id news identifier
	 * @param string $section_gid section GUID
	 * @return void
	 */
	public function edit($news_id=null, $section_gid='text'){
		$languages = $this->pg_language->languages;
		
		if($news_id){
			$data = $this->News_model->get_news_by_id($news_id, array_keys($languages));
		}else{
			$data = array();
		}
		
		if($this->input->post('btn_save')){
			switch($section_gid){
				case 'text':
					$post_data = array(
						"gid" => $this->input->post('gid', true),
						'id_lang' => $this->input->post('id_lang', true),
						"news_type" => "news",
					);
			
					foreach($languages as $lid => $lang_data){
						$post_data["name_".$lid] = $this->input->post('name_'.$lid, true);
						$post_data["annotation_".$lid] = $this->input->post('annotation_'.$lid, true);
						$post_data["content_".$lid] = $this->input->post('content_'.$lid);
					}
				
					$validate_data = $this->News_model->validate_news($news_id, $post_data, 'news_icon', 'news_video');
					if(!empty($validate_data["errors"])){
						$this->system_messages->add_message('error', $validate_data["errors"]);
						$data = array_merge($data, $post_data);
					}else{
						if($this->input->post('news_icon_delete') && $news_id && $data["img"]){
							$this->load->model("Uploads_model");
							$format = $this->News_model->format_single_news($data);
							$this->Uploads_model->delete_upload($this->News_model->upload_config_id, $format["prefix"], $format["img"]);
							$validate_data["data"]["img"] = '';
						}

						if($this->input->post('news_video_delete') && $news_id && $data["video"]){
							$this->load->model("Video_uploads_model");
							$format = $this->News_model->format_single_news($data);
							$this->Video_uploads_model->delete_upload($this->News_model->video_config_id, $format["prefix"], $format["video"], $format["video_image"], $format["video_data"]["data"]["upload_type"]);
							$validate_data["data"]["video"] = $validate_data["data"]["video_image"] = $validate_data["data"]["video_data"] = '';
						}

						$flag_add = empty($news_id)?true:false;
						if($flag_add){
							$validate_data["data"]["status"] = 1;
						}
						$news_id = $this->News_model->save_news($news_id, $validate_data["data"], 'news_icon', 'news_video');

						$this->system_messages->add_message('success', (!$flag_add)?l('success_update_news', 'news'):l('success_add_news', 'news'));
						
						$data = array_merge($data, $validate_data["data"]);
						$data = $this->News_model->format_single_news($data);
				
						$url = site_url()."admin/news/edit/".$news_id.'/'.$section_gid;
						redirect($url);
					}
				break;
				case 'seo':
					$this->load->model('Seo_advanced_model');
					$seo_fields = $this->Seo_advanced_model->get_seo_fields();
					foreach($seo_fields as $key=>$section_data){
						if($this->input->post('btn_save_'.$section_data['gid'])){
							$post_data = array();
							$post_data[$section_data['gid']] = $this->input->post($section_data['gid'], true);
							$validate_data = $this->Seo_advanced_model->validate_seo_tags($news_id, $post_data);
							if(empty($validate_data['errors'])){
								$news_data['id_seo_settings'] = $this->Seo_advanced_model->save_seo_tags($data['id_seo_settings'], $validate_data['data']);
								if(!$data['id_seo_settings']){
									$data['id_seo_settings'] = $news_data['id_seo_settings'];
									$this->News_model->save_news($news_id, $news_data);
								}
								$this->system_messages->add_message('success', l('success_settings_updated', 'seo'));
								$url = site_url()."admin/news/edit/".$news_id.'/'.$section_gid;
								redirect($url);
							}
							$data = array_merge($data, $post_data);
							break;
						}
					}
				break;
			}
		}

		$data = $this->News_model->format_single_news($data);

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
			
				$languages = $this->pg_language->languages;
				$this->template_lite->assign('languages', $languages);
				
				$current_lang_id = $this->pg_language->current_lang_id;
				$this->template_lite->assign('lang_id', $current_lang_id);

				if($data['id_seo_settings']){
					$seo_settings = $this->Seo_advanced_model->get_seo_tags($data['id_seo_settings']);
					$this->template_lite->assign('seo_settings', $seo_settings);
				}
			break;
		}
		
		$this->template_lite->assign('data', $data);
		$this->template_lite->assign('section_gid', $section_gid);
		$this->template_lite->assign('current_lang_id', $this->pg_language->current_lang_id);
		$this->template_lite->assign('languages', $languages);


		$this->Menu_model->set_menu_active_item('admin_news_menu', 'news_list_item');
		$this->system_messages->set_data('header', l('admin_header_news_list', 'news'));
		$this->template_lite->view('edit_news');
	}

	/**
	 * Activate/deactivate news entry
	 * 
	 * Available status values: 1 - activate, 0 - deactivate
	 * 
	 * @param integer $id news identifier
	 * @param integer $status activation status
	 * @return void
	 */
	public function activate($id, $status=0){
		$id = intval($id);
		if(!empty($id)){
			$data["status"] = intval($status);
			$this->News_model->save_news($id, $data);
			$this->system_messages->add_message('success', ($status)?l('success_activate_news', 'news'):l('success_deactivate_news', 'news'));
		}
		$cur_set = $_SESSION["news_list"];
		redirect(site_url()."admin/news/index/".$cur_set["order"]."/".$cur_set["order_direction"]."/".$cur_set["page"]);
	}

	/**
	 * Remove news entry
	 * 
	 * @param integer $id news identifier
	 * @return void
	 */
	public function delete($id){
		$id = intval($id);
		if(!empty($id)){
			$this->News_model->delete_news($id);
			$this->system_messages->add_message('success', l('success_delete_news', 'news'));
		}
		$cur_set = $_SESSION["news_list"];
		redirect(site_url()."admin/news/index/".$cur_set["order"]."/".$cur_set["order_direction"]."/".$cur_set["page"]);
	}

	/**
	 * Manage news feeds
	 * 
	 * @param integer $id_lang language identifier
	 * @param string $order sorting order
	 * @param string $order_direction order direction
	 * @param integer $page page of results
	 * @return void
	 */
	public function feeds($id_lang=0, $order="date_add", $order_direction="DESC", $page=1){
		$this->load->model("news/models/Feeds_model");

		$attrs = array();

		$id_lang = intval($id_lang);

		$current_settings = isset($_SESSION["feeds_list"])?$_SESSION["feeds_list"]:array();
		if(!isset($current_settings["id_lang"])) $current_settings["id_lang"] = $id_lang;
		if(!isset($current_settings["order"])) $current_settings["order"] = "date_add";
		if(!isset($current_settings["order_direction"])) $current_settings["order_direction"] = "DESC";
		if(!isset($current_settings["page"])) $current_settings["page"] = 1;

		$languages = $this->pg_language->languages;

		$filter_data[0] = $this->Feeds_model->get_feeds_count();
		foreach($languages as $id_lang_temp => $language){
			$search_params["where"]["id_lang"] = $id_lang_temp;
			$filter_data[$id_lang_temp] = $this->Feeds_model->get_feeds_count($search_params);
		}

		if($id_lang) $attrs["where"]['id_lang'] = $id_lang;

		$this->template_lite->assign('id_lang', $id_lang);
		$this->template_lite->assign('filter_data', $filter_data);

		$current_settings["page"] = $page;

		if(!$order) $order = $current_settings["order"];
		$this->template_lite->assign('order', $order);
		$current_settings["order"] = $order;

		if(!$order_direction) $order_direction = $current_settings["order_direction"];
		$this->template_lite->assign('order_direction', $order_direction);
		$current_settings["order_direction"] = $order_direction;

		$feeds_count = $filter_data[$id_lang];

		if(!$page) $page = $current_settings["page"];
		$items_on_page = $this->pg_module->get_module_config('start', 'admin_items_per_page');
		$this->load->helper('sort_order');
		$page = get_exists_page_number($page, $feeds_count, $items_on_page);
		$current_settings["page"] = $page;

		$_SESSION["feeds_list"] = $current_settings;

		$sort_links = array(
			"date_add" => site_url()."admin/news/feeds/".$id_lang."/date_add/".(($order!='date_add' xor $order_direction=='DESC')?'ASC':'DESC'),
		);

		$this->template_lite->assign('sort_links', $sort_links);

		if ($feeds_count > 0){
			$feeds = $this->Feeds_model->get_feeds_list( $page, $items_on_page, array($order => $order_direction), $attrs);
			$this->template_lite->assign('feeds', $feeds);

		}
		$this->load->helper("navigation");
		$url = site_url()."admin/news/feeds/".$id_lang."/".$order."/".$order_direction."/";
		$page_data = get_admin_pages_data($url, $feeds_count, $items_on_page, $page, 'briefPage');
		$page_data["date_format"] = $this->pg_date->get_format('date_time_literal', 'st');
		$this->template_lite->assign('page_data', $page_data);

		$this->template_lite->assign('languages', $languages);


		$this->Menu_model->set_menu_active_item('admin_news_menu', 'feeds_list_item');
		$this->system_messages->set_data('header', l('admin_header_feeds_list', 'news'));
		$this->template_lite->view('list_feeds');
	}

	/**
	 * Edit feed settings
	 * 
	 * @param integer $id feed identifier
	 * @return void
	 */
	public function feed_edit($id=null){
		$this->load->model("news/models/Feeds_model");
		if($id){
			$data = $this->Feeds_model->get_feed_by_id($id);
		}else{
			$data["id_lang"] = $this->pg_language->current_lang_id;
		}

		if($this->input->post('btn_save')){
			$post_data = array(
				"link" => $this->input->post('link', true),
				"max_news" => $this->input->post('max_news', true),
				"id_lang" => $this->input->post('id_lang', true),
			);

			$validate_data = $this->Feeds_model->validate_feed($id, $post_data);
			if(!empty($validate_data["errors"])){
				$this->system_messages->add_message('error', $validate_data["errors"]);
			}else{

				$flag_add = empty($id)?true:false;
				if($flag_add){
					$validate_data["data"]["status"] = 1;
				}
				$id = $this->Feeds_model->save_feed($id, $validate_data["data"]);

				$this->system_messages->add_message('success', (!$flag_add)?l('success_update_feed', 'news'):l('success_add_feed', 'news'));
				$cur_set = $_SESSION["feeds_list"];
				redirect(site_url()."admin/news/feeds/".$cur_set["id_lang"]."/".$cur_set["order"]."/".$cur_set["order_direction"]."/".$cur_set["page"]);
			}
			$data = array_merge($data, $validate_data["data"]);
		}


		$this->template_lite->assign('data', $data);
		$this->template_lite->assign('languages', $this->pg_language->languages);

		$this->Menu_model->set_menu_active_item('admin_news_menu', 'feeds_list_item');
		$this->system_messages->set_data('header', l('admin_header_feeds_list', 'news'));
		$this->template_lite->view('edit_feeds');
	}

	/**
	 * Activate/deactivate feed
	 * 
	 * Available status values: 1 - activate, 0 - deactivate
	 * 
	 * @param integer $id feed identifier
	 * @param integer $status activation status
	 * @return void
	 */
	public function feed_activate($id, $status=0){
		$this->load->model("news/models/Feeds_model");
		$id = intval($id);
		if(!empty($id)){
			$data["status"] = intval($status);
			$this->Feeds_model->save_feed($id, $data);
			$this->system_messages->add_message('success', ($status)?l('success_activate_feed', 'news'):l('success_deactivate_feed', 'news'));
		}
		$cur_set = $_SESSION["feeds_list"];
		redirect(site_url()."admin/news/feeds/".$cur_set["id_lang"]."/".$cur_set["order"]."/".$cur_set["order_direction"]."/".$cur_set["page"]);
	}

	/**
	 * Remove feed
	 * 
	 * @param integer $id feed identifier
	 * @return void
	 */
	public function feed_delete($id){
		$this->load->model("news/models/Feeds_model");
		$id = intval($id);
		if(!empty($id)){
			$this->Feeds_model->delete_feed($id);
			$this->system_messages->add_message('success', l('success_delete_feed', 'news'));
		}
		$cur_set = $_SESSION["feeds_list"];
		redirect(site_url()."admin/news/feeds/".$cur_set["id_lang"]."/".$cur_set["order"]."/".$cur_set["order_direction"]."/".$cur_set["page"]);
	}

	/**
	 * Load feed content
	 * 
	 * @param integer $id feed identifier
	 * @return void
	 */
	public function feed_parse($id){
		$this->load->model("news/models/Feeds_model");
		$id = intval($id);
		if(!empty($id)){
			$feed_data = $this->Feeds_model->get_feed_by_id($id);
			$content = $this->Feeds_model->get_feed_content($feed_data["link"], $feed_data["max_news"]);
			if(!empty($content["errors"])){
				$this->system_messages->add_message('error', $content["errors"]);
			}else{
				$saved_news = $this->Feeds_model->save_feed_news($id, $content["items"]);
				if($saved_news){
					$success = str_replace("[count]", $saved_news, l('success_parse_feed', 'news'));
				}else{
					$success = l('success_no_feed_news', 'news');
				}
				$this->system_messages->add_message('success', $success);
			}
		}
		$cur_set = $_SESSION["feeds_list"];
		redirect(site_url()."admin/news/feeds/".$cur_set["id_lang"]."/".$cur_set["order"]."/".$cur_set["order_direction"]."/".$cur_set["page"]);
	}

	/**
	 * Manage module settings
	 * 
	 * @return void
	 */
	public function settings(){
		$data = $this->News_model->get_rss_settings();

		if($this->input->post('btn_save')){
			$post_data = array(
				"userside_items_per_page" => $this->input->post('userside_items_per_page', true),
				"userhelper_items_per_page" => $this->input->post('userhelper_items_per_page', true),
				"rss_feed_channel_title" => $this->input->post('rss_feed_channel_title', true),
				"rss_feed_channel_description" => $this->input->post('rss_feed_channel_description', true),
				"rss_feed_image_title" => $this->input->post('rss_feed_image_title', true),
				"rss_news_max_count" => $this->input->post('rss_news_max_count', true),
				"rss_use_feeds_news" => $this->input->post('rss_use_feeds_news', true),
			);

			$validate_data = $this->News_model->validate_rss_settings($post_data, 'rss_logo');
			if(!empty($validate_data["errors"])){
				$this->system_messages->add_message('error', $validate_data["errors"]);
			}else{

				if($this->input->post('rss_logo_delete') && $data["rss_feed_image_url"]){
					$this->load->model("Uploads_model");
					$this->Uploads_model->delete_upload($this->News_model->rss_config_id, "", $format["rss_feed_image_url"]);
					$validate_data["data"]["rss_feed_image_url"] = '';
				}
				$id = $this->News_model->set_rss_settings($validate_data["data"], 'rss_logo');

				$this->system_messages->add_message('success', l('success_settings_save', 'news'));
				redirect(site_url()."admin/news/settings");
			}
		}

		$this->template_lite->assign('data', $data);

		$this->Menu_model->set_menu_active_item('admin_news_menu', 'nsettings_list_item');
		$this->system_messages->set_data('header', l('admin_header_settings_list', 'news'));
		$this->template_lite->view('settings');
	}
}
