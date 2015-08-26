<?php

/**
 * Content module
 *
 * @package 	PG_Dating
 * @copyright 	Copyright (c) 2000-2014 PilotGroup.NET Powered by PG Dating Pro
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */

/**
 * Content install model
 *
 * @package 	PG_Dating 
 * @subpackage 	Content
 * @category	models
 * @copyright 	Copyright (c) 2000-2014 PilotGroup.NET Powered by PG Dating Pro
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
class Content_install_model extends Model
{
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
				'settings_items' => array(
					'action' => 'none',
					'items' => array(
						'content_items' => array(
							'action'=>'none',
							'items' => array(
								'content_menu_item' => array('action' => 'create', 'link' => 'admin/content', 'status' => 1, 'sorter' => 3),
								'promo_menu_item' => array('action' => 'create', 'link' => 'admin/content/promo', 'status' => 1, 'sorter' => 4)
							)
						)
					)
				)
			)
		),

		'user_footer_menu' => array(
			'action' => 'none',
			'items' => array(
				'footer-menu-policy-item' => array(
					'action' => 'none',
					'items' => array(
						'footer-menu-privacy-item' => array('action' => 'create', 'link' => 'content/view/privacy-and-security', 'status' => 1, 'sortet' => 1),
						'footer-menu-terms-item' => array('action' => 'create', 'link' => 'content/view/legal-terms', 'status' => 1, 'sortet' => 2)
					)
				),
				'footer-menu-about-item' => array(
					'action' => 'none',
					'items' => array(
						'footer-menu-about-us-item' => array('action' => 'create', 'link' => 'content/view/about_us', 'status' => 1, 'sortet' => 1)
					)
				)
			)
		)
	);
	
	/**
	 * Moderators configuration
	 * 
	 * @var array
	 */
	protected $moderators_methods = array(
		array('module' => 'content', 'method' => 'index', 'is_default' => 1),
		array('module' => 'content', 'method' => 'promo', 'is_default' => 0)
	);
	
	/**
	 * Dynamic blocks configuration
	 * 
	 * @var array
	 */
	protected $dynamic_blocks = array(
		'info_pages' =>
		array(
			'gid' => 'info_pages',
			'module' => 'content',
			'model' => 'Content_model',
			'method' => '_dynamic_block_get_info_pages',
			'params' =>
			array(
				'keyword' =>
				array(
					'type' => 'string',
					'default' => '',
					'gid' => 'keyword',
				),
				'transparent' =>
				array(
					'type' => 'checkbox',
					'default' => '1',
					'gid' => 'transparent',
				),
				'show_subsections' =>
				array(
					'type' => 'checkbox',
					'default' => '',
					'gid' => 'show_subsections',
				),
				'trim_subsections_text' =>
				array(
					'type' => 'checkbox',
					'default' => '1',
					'gid' => 'trim_subsections_text',
				),
			),
			'views' =>
			array(
				0 =>
				array(
					'gid' => 'default',
				),
			),
			'area' =>
			array(
				0 =>
				array(
					'gid' => 'mediumturquoise',
					'params' => 'a:4:{s:7:"keyword";s:8:"about_us";s:11:"transparent";s:1:"1";s:16:"show_subsections";s:1:"0";s:21:"trim_subsections_text";s:1:"0";}',
					'view_str' => 'default',
					'width' => '50',
					'sorter' => '14',
					'cache_time' => '0',
				),
				1 =>
				array(
					'gid' => 'lavender',
					'params' => 'a:4:{s:7:"keyword";s:8:"about_us";s:11:"transparent";s:1:"1";s:16:"show_subsections";s:1:"0";s:21:"trim_subsections_text";s:1:"0";}',
					'view_str' => 'default',
					'width' => '50',
					'sorter' => '14',
					'cache_time' => '0',
				),
				2 =>
				array(
					'gid' => 'community',
					'params' => 'a:4:{s:7:"keyword";s:8:"about_us";s:11:"transparent";s:1:"1";s:16:"show_subsections";s:1:"0";s:21:"trim_subsections_text";s:1:"1";}',
					'view_str' => 'default',
					'width' => '70',
					'sorter' => '6',
					'cache_time' => '0',
				),
				3 =>
				array(
					'gid' => 'christian',
					'params' => 'a:4:{s:7:"keyword";s:8:"about_us";s:11:"transparent";s:1:"1";s:16:"show_subsections";s:1:"0";s:21:"trim_subsections_text";s:1:"1";}',
					'view_str' => 'default',
					'width' => '50',
					'sorter' => '11',
					'cache_time' => '0',
				),
				4 =>
				array(
					'gid' => 'social',
					'params' => 'a:4:{s:7:"keyword";s:17:"description_block";s:11:"transparent";s:1:"1";s:16:"show_subsections";s:1:"0";s:21:"trim_subsections_text";s:1:"1";}',
					'view_str' => 'default',
					'width' => '100',
					'sorter' => '4',
					'cache_time' => '0',
				),
				5 =>
				array(
					'gid' => 'social',
					'params' => 'a:4:{s:7:"keyword";s:9:"use_block";s:11:"transparent";s:1:"1";s:16:"show_subsections";s:1:"0";s:21:"trim_subsections_text";s:1:"1";}',
					'view_str' => 'default',
					'width' => '100',
					'sorter' => '5',
					'cache_time' => '0',
				),
				6 =>
				array(
					'gid' => 'social',
					'params' => 'a:4:{s:7:"keyword";s:13:"welcome_block";s:11:"transparent";s:1:"1";s:16:"show_subsections";s:1:"0";s:21:"trim_subsections_text";s:1:"1";}',
					'view_str' => 'default',
					'width' => '100',
					'sorter' => '6',
					'cache_time' => '0',
				),
				7 =>
				array(
					'gid' => 'social',
					'params' => 'a:4:{s:7:"keyword";s:11:"get_m_block";s:11:"transparent";s:1:"1";s:16:"show_subsections";s:1:"0";s:21:"trim_subsections_text";s:1:"1";}',
					'view_str' => 'default',
					'width' => '100',
					'sorter' => '7',
					'cache_time' => '0',
				),
			),
			'presets' =>
			array(
				0 =>
				array(
					'gid_preset' => 'mediumturquoise',
					'gid_area' => 'mediumturquoise',
					'params' => 'a:4:{s:7:"keyword";s:8:"about_us";s:11:"transparent";s:1:"1";s:16:"show_subsections";s:1:"0";s:21:"trim_subsections_text";s:1:"0";}',
					'view_str' => 'default',
					'width' => '50',
					'cache_time' => '0',
					'sorter' => '14',
				),
				1 =>
				array(
					'gid_preset' => 'lavender',
					'gid_area' => 'lavender',
					'params' => 'a:4:{s:7:"keyword";s:8:"about_us";s:11:"transparent";s:1:"1";s:16:"show_subsections";s:1:"0";s:21:"trim_subsections_text";s:1:"0";}',
					'view_str' => 'default',
					'width' => '50',
					'cache_time' => '0',
					'sorter' => '14',
				),
				2 =>
				array(
					'gid_preset' => 'community',
					'gid_area' => 'community',
					'params' => 'a:4:{s:7:"keyword";s:8:"about_us";s:11:"transparent";s:1:"1";s:16:"show_subsections";s:1:"0";s:21:"trim_subsections_text";s:1:"1";}',
					'view_str' => 'default',
					'width' => '70',
					'cache_time' => '0',
					'sorter' => '6',
				),
				3 =>
				array(
					'gid_preset' => 'christian',
					'gid_area' => 'christian',
					'params' => 'a:4:{s:7:"keyword";s:8:"about_us";s:11:"transparent";s:1:"1";s:16:"show_subsections";s:1:"0";s:21:"trim_subsections_text";s:1:"1";}',
					'view_str' => 'default',
					'width' => '50',
					'cache_time' => '0',
					'sorter' => '11',
				),
				4 =>
				array(
					'gid_preset' => 'social',
					'gid_area' => 'social',
					'params' => 'a:4:{s:7:"keyword";s:17:"description_block";s:11:"transparent";s:1:"1";s:16:"show_subsections";s:1:"0";s:21:"trim_subsections_text";s:1:"1";}',
					'view_str' => 'default',
					'width' => '100',
					'cache_time' => '0',
					'sorter' => '4',
				),
				5 =>
				array(
					'gid_preset' => 'social',
					'gid_area' => 'social',
					'params' => 'a:4:{s:7:"keyword";s:9:"use_block";s:11:"transparent";s:1:"1";s:16:"show_subsections";s:1:"0";s:21:"trim_subsections_text";s:1:"1";}',
					'view_str' => 'default',
					'width' => '100',
					'cache_time' => '0',
					'sorter' => '5',
				),
				6 =>
				array(
					'gid_preset' => 'social',
					'gid_area' => 'social',
					'params' => 'a:4:{s:7:"keyword";s:13:"welcome_block";s:11:"transparent";s:1:"1";s:16:"show_subsections";s:1:"0";s:21:"trim_subsections_text";s:1:"1";}',
					'view_str' => 'default',
					'width' => '100',
					'cache_time' => '0',
					'sorter' => '6',
				),
				7 =>
				array(
					'gid_preset' => 'social',
					'gid_area' => 'social',
					'params' => 'a:4:{s:7:"keyword";s:11:"get_m_block";s:11:"transparent";s:1:"1";s:16:"show_subsections";s:1:"0";s:21:"trim_subsections_text";s:1:"1";}',
					'view_str' => 'default',
					'width' => '100',
					'cache_time' => '0',
					'sorter' => '7',
				),
			),
		),
		'content_promo' =>
		array(
			'gid' => 'content_promo',
			'module' => 'content',
			'model' => 'Content_promo_model',
			'method' => '_dynamic_block_get_content_promo',
			'views' =>
			array(
				0 =>
				array(
					'gid' => 'default',
				),
			),
			'area' =>
			array(
				0 =>
				array(
					'gid' => 'girlfriends',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => '100',
					'sorter' => '4',
					'cache_time' => '0',
				),
			),
			'presets' =>
			array(
				0 =>
				array(
					'gid_preset' => 'girlfriends',
					'gid_area' => 'girlfriends',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => '100',
					'cache_time' => '0',
					'sorter' => '4',
				),
			),
		),
	);
	/*	protected $dynamic_blocks = array(
		array(
			'gid'		=> 'info_pages',
			'module'	=> 'content',
			'model'		=> 'Content_model',
			'method'	=> '_dynamic_block_get_info_pages',
			'params'	=> array(
				'keyword' => array('gid'=>'keyword', 'type'=>'string', 'default'=>''),
				'transparent' => array('gid'=>'transparent', 'type'=>'checkbox', 'default'=>'1'),
				'show_subsections' => array('gid'=>'show_subsections', 'type'=>'checkbox', 'default'=>''),
				'trim_subsections_text' => array('gid'=>'trim_subsections_text', 'type'=>'checkbox', 'default'=>'1'),
			),
			'views'		=> array(array('gid'=>'default')),
			'area'		=> array(
				array(
					'gid' => 'mediumturquoise',
					'params' => 'a:4:{s:7:"keyword";s:8:"about_us";s:11:"transparent";s:1:"1";s:16:"show_subsections";s:1:"0";s:21:"trim_subsections_text";s:1:"0";}',
					'view_str' => 'default',
					'width' => 50,
					'sorter' => 14,
					'cache_time' => 0,
				),
				array(
					'gid' => 'lavender',
					'params' => 'a:4:{s:7:"keyword";s:8:"about_us";s:11:"transparent";s:1:"1";s:16:"show_subsections";s:1:"0";s:21:"trim_subsections_text";s:1:"0";}',
					'view_str' => 'default',
					'width' => 50,
					'sorter' => 14,
					'cache_time' => 0,
				),
				array(
					'gid' => 'community',
					'params' => 'a:4:{s:7:"keyword";s:8:"about_us";s:11:"transparent";s:1:"1";s:16:"show_subsections";s:1:"0";s:21:"trim_subsections_text";s:1:"1";}',
					'view_str' => 'default',
					'width' => 70,
					'sorter' => 6,
					'cache_time' => 0,
				),
				array(
					'gid' => 'christian',
					'params' => 'a:4:{s:7:"keyword";s:8:"about_us";s:11:"transparent";s:1:"1";s:16:"show_subsections";s:1:"0";s:21:"trim_subsections_text";s:1:"1";}',
					'view_str' => 'default',
					'width' => 50,
					'sorter' => 11,
					'cache_time' => 0,
				),
			),
		),
		array(
			'gid'		=> 'content_promo',
			'module'	=> 'content',
			'model'		=> 'Content_promo_model',
			'method'	=> '_dynamic_block_get_content_promo',
			'params'	=> array(),
			'views'		=> array(array('gid'=>'default')),
			'area'		=> array(
				array(
					'gid' => 'girlfriends',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => 100,
					'sorter' => 4,
					'cache_time' => 0,
				)
			),
		),
	);*/
	
	/**
	 * Uploads configuration
	 * 
	 * @var array
	 */
	protected $uploads = array(
		array(
			'gid' => 'promo-content-img',
			'name' => 'Promo content image',
			'max_height' => 2000,
			'max_width' => 1200,
			'max_size' => 1000000,
			'name_format' => 'generate',
			'file_formats' => array("jpg", "gif", "png"),
			'default_img' => '',
		),
		array(
			'gid' => 'info-page-logo',
			'name' => 'Info page image',
			'min_height' => 200, 
			'min_width' => 200, 
			'max_height' => 1000,
			'max_width' => 1000,
			'max_size' => 1000000,
			'name_format' => 'generate',
			'file_formats' => array("jpg", "gif", "png"),
			'default_img' => '',
			'thumbs' => array(
				'big' => array('width'=>300, 'height'=>300, 'effect'=>'none', 'watermark'=>'', 'crop_param'=>'crop', 'crop_color'=>"ffffff"),
				'middle' => array('width'=>200, 'height'=>200, 'effect'=>'none', 'watermark'=>'', 'crop_param'=>'crop', 'crop_color'=>"ffffff"),
				'small' => array('width'=>100, 'height'=>100, 'effect'=>'none', 'watermark'=>'', 'crop_param'=>'crop', 'crop_color'=>"ffffff"),
			),
		),
	);
	
	/**
	 * Video uploads configuration
	 * 
	 * @var array
	 */
	protected $video_uploads = array(
		array(
			"gid" => "promo-video", 
			"name" => "Promo video", 
			"max_size" => 1073741824, 
			"file_formats" => array("avi", "flv", "mkv", "asf", "mpeg", "mpg", "mov"), 
			"default_img" => "", 
			"upload_type" => "local", 
			"use_convert" => 1, 
			"use_thumbs" => 1, 
			"module" => "content", 
			"model" => "Content_promo_model", 
			"method_status" => "video_callback", 
			"thumbs_settings" => array(array("gid"=>"small", "width"=>100, "height"=>70, "animated"=>0)), 
			"local_settings" => array("width"=>980, "height"=>400, "audio_freq"=>22050, "audio_brate"=>"64k", "video_brate"=>"300k", "video_rate"=>100),
		),
	);
	
	/**
	 * Seo pages
	 * 
	 * @var array
	 */
	private $_seo_pages = array(
		'index',
		'view',
	);
	
	/**
	 * Fields depended on languages
	 * 
	 * @var array
	 */
	protected $lang_dm_data = array(
		array(
			"module" => "content",
			"model" => "Content_model",
			"method_add" => "lang_dedicate_module_callback_add",
			"method_delete" => "lang_dedicate_module_callback_delete",
		),
		array(
			'module' => 'content',
			'model' => 'Content_promo_model',
			"method_add" => "lang_dedicate_module_callback_add",
			"method_delete" => "lang_dedicate_module_callback_delete",
		),
	);
	
	/**
	 * Class constructor
	 *
	 * @return Content_install_model
	 */
	public function __construct(){
		parent::Model();
		$this->CI = & get_instance();
		$this->CI->load->model('Install_model');
	}

	/**
	 * Install data of menu module
	 * 
	 * @return void
	 */
	public function install_menu() {
		$this->CI->load->helper('menu');
		foreach($this->menu as $gid => $menu_data){
			$this->menu[$gid]['id'] = linked_install_set_menu($gid, $menu_data["action"], $menu_data["name"]);
			linked_install_process_menu_items($this->menu, 'create', $gid, 0, $this->menu[$gid]["items"]);
		}
	}

	/**
	 * Import languages of menu module
	 * 
	 * @param array $langs_ids languages identifiers
	 * @return void
	 */
	public function install_menu_lang_update($langs_ids = null) {
		if(empty($langs_ids)) return false;
		$langs_file = $this->CI->Install_model->language_file_read('content', 'menu', $langs_ids);

		if(!$langs_file) { log_message('info', 'Empty menu langs data'); return false; }

		$this->CI->load->helper('menu');

		foreach($this->menu as $gid => $menu_data){
			linked_install_process_menu_items($this->menu, 'update', $gid, 0, $this->menu[$gid]["items"], $gid, $langs_file);
		}
		return true;
	}

	/**
	 * Export languages of menu module
	 * 
	 * @param array $langs_ids languages identifiers
	 * @return array
	 */
	public function install_menu_lang_export($langs_ids) {
		if(empty($langs_ids)) return false;
		$this->CI->load->helper('menu');

		$return = array();
		foreach($this->menu as $gid => $menu_data){
			$temp = linked_install_process_menu_items($this->menu, 'export', $gid, 0, $this->menu[$gid]["items"], $gid, $langs_ids);
			$return = array_merge($return, $temp);
		}
		return array( "menu" => $return );
	}

	/**
	 * Uninstall data of menu module
	 * 
	 * @return void
	 */
	public function deinstall_menu() {
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
	 * Install data of site map module
	 * 
	 * @return void
	 */
	public function install_site_map() {
		///// site map
		$this->CI->load->model('Site_map_model');
		$site_map_data = array(
			'module_gid' => 'content',
			'model_name' => 'Content_model',
			'get_urls_method' => 'get_sitemap_urls',
		);
		$this->CI->Site_map_model->set_sitemap_module('content', $site_map_data);
	}

	/**
	 * Uninstall data of site map module
	 * 
	 * @return void
	 */
	public function deinstall_site_map() {
		$this->CI->load->model('Site_map_model');
		$this->CI->Site_map_model->delete_sitemap_module('content');
	}

	/**
	 * Install data of banners module
	 * 
	 * @return void
	 */
	public function install_banners() {
		///// add banners module
		$this->CI->load->model('Content_model');
		$this->CI->load->model('banners/models/Banner_group_model');
		$this->CI->load->model('banners/models/Banner_place_model');
		$this->CI->Banner_group_model->set_module("content", "Content_model", "_banner_available_pages");

		$group_id = $this->CI->Banner_group_model->get_group_id_by_gid('content_groups');
		///add pages in group
		$pages = $this->CI->Content_model->_banner_available_pages();
		if ($pages){
			foreach($pages  as $key => $value){
				$page_attrs = array(
					'group_id' => $group_id,
					'name' => $value['name'],
					'link' => $value['link'],
				);
				$this->CI->Banner_group_model->add_page($page_attrs);
			}
		}
	}
	
	/**
	 * Uninstall data of banners module
	 * 
	 * @return void
	 */
	public function deinstall_banners() {
		///// delete banners module
		$this->CI->load->model("banners/models/Banner_group_model");
		$this->CI->Banner_group_model->delete_module("content");
	}

	/**
	 * Install data of moderators module
	 * 
	 * @return void
	 */
	public function install_moderators() {
		// install moderators permissions
		$this->CI->load->model('moderators/models/Moderators_model');

		foreach($this->moderators_methods as $method){
			$this->CI->Moderators_model->save_method(null, $method);
		}
	}

	/**
	 * Import languages of moderators module
	 * 
	 * @param array $langs_ids languages identifiers
	 * @return void
	 */
	public function install_moderators_lang_update($langs_ids = null) {
		$langs_file = $this->CI->Install_model->language_file_read('content', 'moderators', $langs_ids);

		// install moderators permissions
		$this->CI->load->model('moderators/models/Moderators_model');
		$params['where']['module'] = 'content';
		$methods = $this->CI->Moderators_model->get_methods_lang_export($params);

		foreach($methods as $method){
			if(!empty($langs_file[$method['method']])){
				$this->CI->Moderators_model->save_method($method['id'], array(), $langs_file[$method['method']]);
			}
		}
	}

	/**
	 * Export languages of moderators module
	 * 
	 * @param array $langs_ids languages identifiers
	 * @return array
	 */
	public function install_moderators_lang_export($langs_ids) {
		$this->CI->load->model('moderators/models/Moderators_model');
		$params['where']['module'] = 'content';
		$methods =  $this->CI->Moderators_model->get_methods_lang_export($params, $langs_ids);
		foreach($methods as $method){
			$return[$method['method']] = $method['langs'];
		}
		return array('moderators' => $return);
	}

	/**
	 * Uninstall data of moderators module
	 * 
	 * @return void
	 */
	public function deinstall_moderators() {
		// delete moderation methods in moderators
		$this->CI->load->model('moderators/models/Moderators_model');
		$params['where']['module'] = 'contents';
		$this->CI->Moderators_model->delete_methods($params);
	}

	/**
	 * Install data of uploads module
	 * 
	 * @return void
	 */
	public function install_uploads()
    {
        // upload config
        $this->CI->load->model("uploads/models/Uploads_config_model");

        $watermark_ids = array();

        foreach ((array) $this->uploads as $upload_data) {
            $config_data = array(
                "gid" => $upload_data["gid"],
                "name" => $upload_data["name"],
                "min_height" => isset($upload_data["min_height"]) ? $upload_data["min_height"] : 0,
                "min_width" => isset($upload_data["min_width"]) ? $upload_data["min_width"] : 0,
                "max_height" => $upload_data["max_height"],
                "max_width" => $upload_data["max_width"],
                "max_size" => $upload_data["max_size"],
                "name_format" => $upload_data["name_format"],
                "file_formats" => serialize((array) $upload_data["file_formats"]),
                "default_img" => $upload_data["default_img"],
                "date_add" => date("Y-m-d H:i:s"),
            );
            $config_id = $this->CI->Uploads_config_model->save_config(null, $config_data);

            $wm_data = $this->CI->Uploads_config_model->get_watermark_by_gid("image-wm");
            $wm_id = isset($wm_data["id"]) ? $wm_data["id"] : 0;
            if (!empty($upload_data["thumbs"]) && is_array($upload_data['thumbs'])) {
                foreach ($upload_data["thumbs"] as $thumb_gid => $thumb_data) {
                    if (isset($thumb_data["watermark"])) {
                        if (!isset($watermark_ids[$thumb_data["watermark"]])) {
                            $wm_data = $this->CI->Uploads_config_model->get_watermark_by_gid($thumb_data["watermark"]);
                            $watermark_ids[$thumb_data["watermark"]] = isset($wm_data["id"]) ? $wm_data["id"] : 0;
                        }
                        $watermark_id = $watermark_ids[$thumb_data["watermark"]];
                    } else {
                        $watermark_id = 0;
                    }

                    $thumb_data["config_id"] = $config_id;
                    $thumb_data["prefix"] = $thumb_gid;
                    $thumb_data["effect"] = "none";
                    $thumb_data["watermark_id"] = $watermark_id;

                    $validate_data = $this->CI->Uploads_config_model->validate_thumb(null, $thumb_data);
                    if (!empty($validate_data["errors"]))
                        continue;
                    $this->CI->Uploads_config_model->save_thumb(null, $validate_data["data"]);
                }
            }
        }
    }

    /**
	 * Uninstall data of uploads module
	 * 
	 * @return void
	 */
	public function deinstall_uploads() {
		$this->CI->load->model("uploads/models/Uploads_config_model");
		
		foreach((array)$this->uploads as $upload_data){
			$config_data = $this->CI->Uploads_config_model->get_config_by_gid($upload_data["gid"]);
			if(!empty($config_data["id"])){
				$this->CI->Uploads_config_model->delete_config($config_data["id"]);
			}
		}
	}

	/**
	 * Install data of file uploads module
	 * 
	 * @return void
	 */
	public function install_file_uploads() {
		$this->CI->load->model('file_uploads/models/File_uploads_config_model');
		$config_data = array(
			'gid' => 'promo-content-flash',
			'name' => 'Promo content flash',
			'max_size' => 1000000,
			'name_format' => 'generate',
			'file_formats' => 'a:1:{i:0;s:3:"swf";}',
			'date_add' => date('Y-m-d H:i:s'),
		);
		$this->CI->File_uploads_config_model->save_config(null, $config_data);
	}
	
	/**
	 * Uninstall data of file uploads module
	 * 
	 * @return void
	 */
	public function deinstall_file_uploads() {
		$this->CI->load->model('file_uploads/models/File_uploads_config_model');
		$config_data = $this->CI->File_uploads_config_model->get_config_by_gid('promo-content-flash');
		if(!empty($config_data["id"])){
			$this->CI->File_uploads_config_model->delete_config($config_data["id"]);
		}
	}

	/**
	 * Install data of social networking module
	 * 
	 * @return void
	 */
	public function install_social_networking() {
		///// add social netorking page
		$this->CI->load->model('social_networking/models/Social_networking_pages_model');
		$page_data = array(
			'controller' => 'content',
			'method' => 'view',
			'name' => 'View content page',
			'data' => 'a:3:{s:4:"like";a:3:{s:8:"facebook";s:2:"on";s:9:"vkontakte";s:2:"on";s:6:"google";s:2:"on";}s:5:"share";a:4:{s:8:"facebook";s:2:"on";s:9:"vkontakte";s:2:"on";s:8:"linkedin";s:2:"on";s:7:"twitter";s:2:"on";}s:8:"comments";s:1:"1";}',
		);
		$this->CI->Social_networking_pages_model->save_page(null, $page_data);
	}
	
	/**
	 * Uninstall data of social networking module
	 * 
	 * @return void
	 */
	public function deinstall_social_networking() {
		///// delete social netorking page
		$this->CI->load->model('social_networking/models/Social_networking_pages_model');
		$this->CI->Social_networking_pages_model->delete_pages_by_controller('content');
	}
	
	/**
	 * Install data of dynamic blocks module
	 * 
	 * @return void
	 */
	public function install_dynamic_blocks() {
		$this->CI->load->model('Dynamic_blocks_model');
        $this->CI->Dynamic_blocks_model->installBatch($this->dynamic_blocks);
			//echo"<pre>";print_r($validate_data);exit;
	}

	/**
	 * Import language of dynamic blocks module
	 * 
	 * @param array $langs_ids languages identifiers
	 * @return void
	 */
	public function install_dynamic_blocks_lang_update($langs_ids = null) {
		$this->CI->load->model('Dynamic_blocks_model');
        return $this->CI->Dynamic_blocks_model->updateLangsByModuleBlocks($this->dynamic_blocks, $langs_ids);
	}

	/**
	 * Export language of dynamic blocks module
	 * 
	 * @param array $langs_ids languages identifiers
	 * @return array
	 */
	public function install_dynamic_blocks_lang_export($langs_ids = null) {
		$this->CI->load->model('Dynamic_blocks_model');
        return array(
            'dynamic_blocks' => $this->CI->Dynamic_blocks_model->export_langs($this->dynamic_blocks, $langs_ids),
        );
	}

	/**
	 * Uninstall data of dynamic blocks module
	 * 
	 * @return void
	 */
	public function deinstall_dynamic_blocks(){
		$this->CI->load->model('Dynamic_blocks_model');
		foreach($this->dynamic_blocks as $block) {
			$this->CI->Dynamic_blocks_model->delete_block_by_gid($block['gid']);
		}
	}
	
	/**
	 * Install fields of dedicated languages
	 * 
	 * @return void
	 */
	public function _prepare_installing(){
		$this->CI->load->model("Content_model");
		foreach($this->CI->pg_language->languages as $lang_id => $value){
			$this->CI->Content_model->lang_dedicate_module_callback_add($lang_id);
		}
		
		$this->CI->load->model("content/models/Content_promo_model");
		foreach($this->CI->pg_language->languages as $lang_id => $value){
			$this->CI->Content_promo_model->lang_dedicate_module_callback_add($lang_id);
		}
	}
	
	/**
	 * Install module data
	 * 
	 * @return void
	 */
	public function _arbitrary_installing(){
		// add entries for lang data updates
		foreach($this->lang_dm_data as $lang_dm_data){
			$this->CI->pg_language->add_dedicate_modules_entry($lang_dm_data);
		}
		
		///// seo
		$seo_data = array(
			'module_gid' => 'content',
			'model_name' => 'Content_model',
			'get_settings_method' => 'get_seo_settings',
			'get_rewrite_vars_method' => 'request_seo_rewrite',
			'get_sitemap_urls_method' => 'get_sitemap_xml_urls',
		);
		$this->CI->pg_seo->set_seo_module('content', $seo_data);

		$this->CI->load->model("content/models/Content_promo_model");
		foreach ($this->CI->pg_language->languages as $id => $value){
			$this->CI->Content_promo_model->lang_dedicate_module_callback_add($value['id']);
		}
		$this->add_demo_content();
	}
	
	/**
	 * Import module languages
	 * 
	 * @param array $langs_ids languages identifiers
	 * @return void
	 */
	public function _arbitrary_lang_install($langs_ids = null) {
		if(!is_array($langs_ids)) $langs_ids = (array)$langs_ids;
		$langs_file = $this->CI->Install_model->language_file_read('content', 'demo', $langs_ids);

		if(!$langs_file) {
			log_message('info', 'Empty arbitrary langs data');
			return false;
		}

		$this->CI->load->model("content/models/Content_promo_model");
		foreach($langs_ids as $lang_id){
			$promo["promo_text"] = $langs_file["content"][$lang_id];
			$this->CI->Content_promo_model->save_promo($lang_id, $promo);
		}

		$langs_file = $this->CI->Install_model->language_file_read('content', 'arbitrary', $langs_ids);
		if(!$langs_file){
			log_message('info', 'Empty content arbitrary langs data'); 
			return false;
		}
		foreach($this->_seo_pages as $page) {
			$post_data = array(
				'title' => $langs_file["seo_tags_{$page}_title"],
				'keyword' => $langs_file["seo_tags_{$page}_keyword"],
				'description' => $langs_file["seo_tags_{$page}_description"],
				'header' => $langs_file["seo_tags_{$page}_header"],
				'og_title' => $langs_file["seo_tags_{$page}_og_title"],
				'og_type' => $langs_file["seo_tags_{$page}_og_type"],
				'og_description' => $langs_file["seo_tags_{$page}_og_description"],
			);
			$this->CI->pg_seo->set_settings('user', 'content', $page, $post_data);
		}
	}

	/**
	 * Export module languages
	 * 
	 * @param array $langs_ids languages identifiers
	 * @return array
	 */
	public function _arbitrary_lang_export($langs_ids = null) {
		if(!is_array($langs_ids)) $langs_ids = (array)$langs_ids;
		$this->CI->load->model("content/models/Content_promo_model");

		foreach($langs_ids as $lang_id){
			$promo = $this->CI->Content_promo_model->get_promo($lang_id);
			$langs["content"][$lang_id] = $promo["promo_text"];
		}

		$seo_settings = $this->pg_seo->get_all_settings_from_cache('user', 'content');
		$lang_ids = array_keys($this->CI->pg_language->languages);
		foreach($seo_settings as $seo_page) {
			$prefix = 'seo_tags_' . $seo_page['method'];
			foreach($lang_ids as $lang_id) {
				$meta = 'meta_' . $lang_id;
				$og = 'og_' . $lang_id;
				$arbitrary_return[$prefix . '_title'][$lang_id] = $seo_page[$meta]['title'];
				$arbitrary_return[$prefix . '_keyword'][$lang_id] = $seo_page[$meta]['keyword'];
				$arbitrary_return[$prefix . '_description'][$lang_id] = $seo_page[$meta]['description'];
				$arbitrary_return[$prefix . '_header'][$lang_id] = $seo_page[$meta]['header'];
				$arbitrary_return[$prefix . '_og_title'][$lang_id] = $seo_page[$og]['og_title'];
				$arbitrary_return[$prefix . '_og_type'][$lang_id] = $seo_page[$og]['og_type'];
				$arbitrary_return[$prefix . '_og_description'][$lang_id] = $seo_page[$og]['og_description'];
			}
		}

		return array('demo' => $langs, "arbitrary" => $arbitrary_return);
	}
	
	/**
	 * Uninstall module data
	 * 
	 * @return void
	 */
	public function _arbitrary_deinstalling(){
		$this->CI->pg_seo->delete_seo_module('content');
		
		/// delete entries in dedicate modules
		foreach($this->lang_dm_data as $lang_dm_data){
			$this->CI->pg_language->delete_dedicate_modules_entry(array('where'=>$lang_dm_data));
		}
	}
	
	/**
	 * Install demo content
	 * 
	 * @return void
	 */
	public function add_demo_content() {
		$this->CI->load->model('Content_model');
		
		$languages = $this->CI->pg_language->languages;
		
		$demo_content = include MODULEPATH . 'content/install/demo_content.php';

		// Associating languages id with codes
		foreach($languages as $l) {
			$lang[$l['code']] = $l['id'];
			if(!empty($l['is_default'])) {
				$default_lang = $l;
			}
		}

        // Pages
		foreach($demo_content['pages'] as $page) {
			
			foreach($page['title'] as $key_l => $name_l) {
				if (isset($lang[$key_l])) {
					if(!empty($name_l)) {
						$page['title_'.$lang[$key_l]] = $name_l;
					}else{
						$page['title_'.$lang[$key_l]] = $page['title'][$default_lang['code']];
					}
				}
			}
			foreach($page['annotation'] as $key_l => $name_l) {
				if (isset($lang[$key_l])) {
					if(!empty($name_l)) {
						$page['annotation_'.$lang[$key_l]] = $name_l;
					}else{
						$page['annotation_'.$lang[$key_l]] = $page['annotation'][$default_lang['code']];
					}
				}
			}
			foreach($page['content'] as $key_l => $name_l) {
				if (isset($lang[$key_l])) {
					if(!empty($name_l)) {
						$page['content_'.$lang[$key_l]] = $name_l;
					}else{
						$page['content_'.$lang[$key_l]] = $page['content'][$default_lang['code']];
					}
				}
			}
			unset($page['title']);
			unset($page['annotation']);
			unset($page['content']);
			
			if(isset($page['parent'])){
				$parent_data = $this->CI->Content_model->get_page_by_gid($page['parent']);
				if(!empty($parent_data)) $page['parent_id'] = $parent_data['id'];
				unset($page['parent']);
			}
			
			$validate_data = $this->CI->Content_model->validate_page(null, $page);
			if(!empty($validate_data['errors'])) continue;
			$page_id = $this->CI->Content_model->save_page(null, $validate_data['data']);
			if($page['img']){
				$this->CI->Content_model->upload_local_logo($page_id, MODULEPATH.'content/install/img/'.$page['img']);
			}
		}
		
		// Promo
		$this->CI->load->model('content/models/Content_model');
		foreach($this->_promo as $promo) {
			$id_lang = $langs[$promo['lang']];
			unset($promo['lang']);
			$this->CI->Content_model->save_promo($id_lang, $promo);
		}
		return true;
	}
}
