<?php

/**
 * News module
 *
 * @package 	PG_Dating
 * @copyright 	Copyright (c) 2000-2014 PilotGroup.NET Powered by PG Dating Pro
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */

/**
 * News install model
 *
 * @package 	PG_Dating
 * @subpackage 	News
 * @category	models
 * @copyright 	Copyright (c) 2000-2014 PilotGroup.NET Powered by PG Dating Pro
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
class News_install_model extends Model
{
	/**
	 * Link to CodeIgniter object
	 * 
	 * @var object
	 */
	private $CI;
	
	/**
	 * Menu configuration
	 * 
	 * @var array
	 */
	private $menu = array(
		'admin_menu' => array(
			'action' => 'none',
			'items' => array(
				'settings_items' => array(
					'action' => 'none',
					'items' => array(
						'content_items' => array(
							'action'=>'none',
							'items' => array(
								'news_menu_item' => array('action' => 'create', 'link' => 'admin/news', 'status' => 1, 'sorter' => 5)
							)
						)
					)
				)
			)
		),
		'admin_news_menu' => array(
			'action' => 'create',
			'name' => 'News section menu',
			'items' => array(
				'news_list_item' => array('action' => 'create', 'link' => 'admin/news', 'status' => 1),
				'feeds_list_item' => array('action' => 'create', 'link' => 'admin/news/feeds', 'status' => 1),
				'settings_list_item' => array('action' => 'create', 'link' => 'admin/news/settings', 'status' => 1)
			)
		),
		'guest_main_menu' => array(
			'action' => 'none',
			'items' => array(
				'main-menu-news-item' => array('action' => 'create', 'link' => 'news', 'status' => 1, 'sorter' => 3),
			)
		),
		'user_footer_menu' => array(
			'action' => 'none',
			'items' => array(
				'footer-menu-about-item' => array(
					'action' => 'none',
					'items' => array(
						'footer-menu-news-item' => array('action' => 'create', 'link' => 'news', 'status' => 1, 'sorter' => 2),
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
	private $moderators_methods = array(
		array('module' => 'news', 'method' => 'index', 'is_default' => 1),
		array('module' => 'news', 'method' => 'feeds', 'is_default' => 0),
		array('module' => 'news', 'method' => 'settings', 'is_default' => 0),
	);
	
	/**
	 * Notification configuration
	 * 
	 * @var array
	 */
	private $notifications = array(
		'templates' => array(
			array('gid' => 'last_news', 'name' => 'Last News', 'vars' => array("content"))
		)
	);
	
	/**
	 * Subscription configuration
	 * 
	 * @var array
	 */
	private $subscriptions = array(
		'types' =>  array (
			array('gid' => 'last_news', 'module' => 'news',	'model' => 'news_model', 'method' => 'get_last_news')
		),
		'subscriptions' => array (
			array('gid' => 'last_news', 'template' => 'last_news', 'type' => 'user', 'content_type' => 'last_news', 'scheduler' => 'a:2:{s:4:"type";i:1;s:13:"date_for_cron";i:0;}')
		)
	);

	/**
	 * Dynamic blocks configuration
	 * 
	 * @var array
	 */
	protected $dynamic_blocks = array(
        'news' =>
        array(
            'gid' => 'news',
            'module' => 'news',
            'model' => 'News_model',
            'method' => '_dynamic_block_get_news',
            'params' =>
            array(
                'count' =>
                array(
                    'type' => 'int',
                    'default' => '3',
                    'gid' => 'count',
                ),
                'transparent' =>
                array(
                    'type' => 'checkbox',
                    'default' => '1',
                    'gid' => 'transparent',
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
                    'params' => 'a:2:{s:5:"count";s:1:"2";s:11:"transparent";s:1:"1";}',
                    'view_str' => 'default',
                    'width' => '50',
                    'sorter' => '15',
                    'cache_time' => '0',
                ),
                1 =>
                array(
                    'gid' => 'lavender',
                    'params' => 'a:2:{s:5:"count";s:1:"2";s:11:"transparent";s:1:"1";}',
                    'view_str' => 'default',
                    'width' => '50',
                    'sorter' => '15',
                    'cache_time' => '0',
                ),
                2 =>
                array(
                    'gid' => 'jewish',
                    'params' => 'a:2:{s:5:"count";s:1:"2";s:11:"transparent";s:1:"1";}',
                    'view_str' => 'default',
                    'width' => '70',
                    'sorter' => '8',
                    'cache_time' => '0',
                ),
                3 =>
                array(
                    'gid' => 'lovers',
                    'params' => 'a:2:{s:5:"count";s:1:"2";s:11:"transparent";s:1:"1";}',
                    'view_str' => 'default',
                    'width' => '70',
                    'sorter' => '9',
                    'cache_time' => '0',
                ),
                4 =>
                array(
                    'gid' => 'blackonwhite',
                    'params' => 'a:2:{s:5:"count";s:1:"3";s:11:"transparent";s:1:"1";}',
                    'view_str' => 'default',
                    'width' => '100',
                    'sorter' => '10',
                    'cache_time' => '0',
                ),
                5 =>
                array(
                    'gid' => 'companions',
                    'params' => 'a:2:{s:5:"count";s:1:"3";s:11:"transparent";s:1:"1";}',
                    'view_str' => 'default',
                    'width' => '30',
                    'sorter' => '9',
                    'cache_time' => '0',
                ),
                6 =>
                array(
                    'gid' => 'community',
                    'params' => 'a:2:{s:5:"count";s:1:"2";s:11:"transparent";s:1:"1";}',
                    'view_str' => 'default',
                    'width' => '30',
                    'sorter' => '7',
                    'cache_time' => '0',
                ),
                7 =>
                array(
                    'gid' => 'christian',
                    'params' => 'a:2:{s:5:"count";s:1:"2";s:11:"transparent";s:1:"1";}',
                    'view_str' => 'default',
                    'width' => '50',
                    'sorter' => '12',
                    'cache_time' => '0',
                ),
            ),
            'presets' =>
            array(
                0 =>
                array(
                    'gid_preset' => 'mediumturquoise',
                    'gid_area' => 'mediumturquoise',
                    'params' => 'a:2:{s:5:"count";s:1:"2";s:11:"transparent";s:1:"1";}',
                    'view_str' => 'default',
                    'width' => '50',
                    'cache_time' => '0',
                    'sorter' => '15',
                ),
                1 =>
                array(
                    'gid_preset' => 'lavender',
                    'gid_area' => 'lavender',
                    'params' => 'a:2:{s:5:"count";s:1:"2";s:11:"transparent";s:1:"1";}',
                    'view_str' => 'default',
                    'width' => '50',
                    'cache_time' => '0',
                    'sorter' => '15',
                ),
                2 =>
                array(
                    'gid_preset' => 'jewish',
                    'gid_area' => 'jewish',
                    'params' => 'a:2:{s:5:"count";s:1:"2";s:11:"transparent";s:1:"1";}',
                    'view_str' => 'default',
                    'width' => '70',
                    'cache_time' => '0',
                    'sorter' => '8',
                ),
                3 =>
                array(
                    'gid_preset' => 'lovers',
                    'gid_area' => 'lovers',
                    'params' => 'a:2:{s:5:"count";s:1:"2";s:11:"transparent";s:1:"1";}',
                    'view_str' => 'default',
                    'width' => '70',
                    'cache_time' => '0',
                    'sorter' => '9',
                ),
                4 =>
                array(
                    'gid_preset' => 'blackonwhite',
                    'gid_area' => 'blackonwhite',
                    'params' => 'a:2:{s:5:"count";s:1:"3";s:11:"transparent";s:1:"1";}',
                    'view_str' => 'default',
                    'width' => '100',
                    'cache_time' => '0',
                    'sorter' => '10',
                ),
                5 =>
                array(
                    'gid_preset' => 'companions',
                    'gid_area' => 'companions',
                    'params' => 'a:2:{s:5:"count";s:1:"3";s:11:"transparent";s:1:"1";}',
                    'view_str' => 'default',
                    'width' => '30',
                    'cache_time' => '0',
                    'sorter' => '9',
                ),
                6 =>
                array(
                    'gid_preset' => 'community',
                    'gid_area' => 'community',
                    'params' => 'a:2:{s:5:"count";s:1:"2";s:11:"transparent";s:1:"1";}',
                    'view_str' => 'default',
                    'width' => '30',
                    'cache_time' => '0',
                    'sorter' => '7',
                ),
                7 =>
                array(
                    'gid_preset' => 'christian',
                    'gid_area' => 'christian',
                    'params' => 'a:2:{s:5:"count";s:1:"2";s:11:"transparent";s:1:"1";}',
                    'view_str' => 'default',
                    'width' => '50',
                    'cache_time' => '0',
                    'sorter' => '12',
                ),
            ),
        ),
    );
    /*private $dynamic_blocks = array(
		array(
			'gid'		=> 'news',
			'module'	=> 'news',
			'model'		=> 'News_model',
			'method'	=> '_dynamic_block_get_news',
			'params'	=> array(
				'count' => array('gid'=>'count', 'type'=>'int', 'default'=>'3'),
				'transparent' => array('gid'=>'transparent', 'type'=>'checkbox', 'default'=>'1'),
			),
			'views'		=> array(array('gid'=>'default')),
			'area' => array(
				array(
					'gid' => 'mediumturquoise', 
					'params' => 'a:2:{s:5:"count";s:1:"2";s:11:"transparent";s:1:"1";}', 
					'view_str' => 'default', 
					'width' => 50,
					'sorter' => 15,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'lavender', 
					'params' => 'a:2:{s:5:"count";s:1:"2";s:11:"transparent";s:1:"1";}', 
					'view_str' => 'default', 
					'width' => 50,
					'sorter' => 15,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'jewish', 
					'params' => 'a:2:{s:5:"count";s:1:"2";s:11:"transparent";s:1:"1";}', 
					'view_str' => 'default', 
					'width' => 70,
					'sorter' => 8,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'lovers', 
					'params' => 'a:2:{s:5:"count";s:1:"2";s:11:"transparent";s:1:"1";}', 
					'view_str' => 'default', 
					'width' => 70,
					'sorter' => 9,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'blackonwhite', 
					'params' => 'a:2:{s:5:"count";s:1:"3";s:11:"transparent";s:1:"1";}', 
					'view_str' => 'default', 
					'width' => 100,
					'sorter' => 10,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'companions', 
					'params' => 'a:2:{s:5:"count";s:1:"3";s:11:"transparent";s:1:"1";}', 
					'view_str' => 'default', 
					'width' => 30,
					'sorter' => 9,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'community', 
					'params' => 'a:2:{s:5:"count";s:1:"2";s:11:"transparent";s:1:"1";}', 
					'view_str' => 'default', 
					'width' => 30,
					'sorter' => 7,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'christian', 
					'params' => 'a:2:{s:5:"count";s:1:"2";s:11:"transparent";s:1:"1";}', 
					'view_str' => 'default', 
					'width' => 50,
					'sorter' => 12,
					'cache_time' => 0, 
				),
			),
		),
	);*/

	/**
	 * Seo configuration
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
			"module" => "news",
			"model" => "News_model",
			"method_add" => "lang_dedicate_module_callback_add",
			"method_delete" => "lang_dedicate_module_callback_delete",
		),
	);

	/**
	 * Constructor
	 *
	 * @return Install object
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
	 * @return boolean
	 */
	public function install_menu_lang_update($langs_ids = null) {
		if(empty($langs_ids)) return false;
		$langs_file = $this->CI->Install_model->language_file_read('news', 'menu', $langs_ids);

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
	 * Install data of uploads module
	 * 
	 * @return void
	 */
	public function install_uploads() {
		$this->CI->load->model('uploads/models/Uploads_config_model');
		$config_data = array(
			'gid' => 'news-logo',
			'name' => 'News icon',
			'max_height' => 500,
			'max_width' => 500,
			'max_size' => 100000,
			'name_format' => 'generate',
			'file_formats' => 'a:3:{i:0;s:3:"jpg";i:1;s:3:"gif";i:2;s:3:"png";}',
			'default_img' => 'default_news-logo.jpg',
			'date_add' => date('Y-m-d H:i:s'),
		);
		$config_id = $this->CI->Uploads_config_model->save_config(null, $config_data);

		$thumb_data = array(
			'config_id' => $config_id,
			'prefix' => 'big',
			'width' => 160,
			'height' => 120,
			'effect' => 'none',
			'watermark_id' => 0,
			'crop_param' => 'crop',
			'crop_color' => 'ffffff',
			'date_add' => date('Y-m-d H:i:s'),
		);
		$this->CI->Uploads_config_model->save_thumb(null, $thumb_data);

		$thumb_data = array(
			'config_id' => $config_id,
			'prefix' => 'small',
			'width' => 80,
			'height' => 60,
			'effect' => 'none',
			'watermark_id' => 0,
			'crop_param' => 'crop',
			'crop_color' => 'ffffff',
			'date_add' => date('Y-m-d H:i:s'),
		);
		$this->CI->Uploads_config_model->save_thumb(null, $thumb_data);

		$config_data = array(
			'gid' => 'rss-logo',
			'name' => 'News rss logo',
			'max_height' => 600,
			'max_width' => 800,
			'max_size' => 512000,
			'name_format' => 'generate',
			'file_formats' => 'a:3:{i:0;s:3:"jpg";i:1;s:3:"gif";i:2;s:3:"png";}',
			'default_img' => '',
			'date_add' => date('Y-m-d H:i:s'),
		);
		$config_id = $this->CI->Uploads_config_model->save_config(null, $config_data);

		$thumb_data = array(
			'config_id' => $config_id,
			'prefix' => 'rss',
			'width' => 120,
			'height' => 80,
			'effect' => 'none',
			'watermark_id' => 0,
			'crop_param' => 'resize',
			'crop_color' => 'ffffff',
			'date_add' => date('Y-m-d H:i:s'),
		);
		$this->CI->Uploads_config_model->save_thumb(null, $thumb_data);
	}
	
	/**
	 * Unintsall data of uploads module
	 * 
	 * @return void
	 */
	public function deinstall_uploads() {
		$this->CI->load->model('uploads/models/Uploads_config_model');
		/*$config_data = $this->CI->Uploads_config_model->get_config_by_gid('news-logo');
		if(!empty($config_data["id"])){
			$this->CI->Uploads_config_model->delete_config($config_data["id"]);
		}

		$config_data = $this->CI->Uploads_config_model->get_config_by_gid('rss-logo');
		if(!empty($config_data["id"])){
			$this->CI->Uploads_config_model->delete_config($config_data["id"]);
		}*/
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
			'module_gid' => 'news',
			'model_name' => 'News_model',
			'get_urls_method' => 'get_sitemap_urls',
		);
		$this->CI->Site_map_model->set_sitemap_module('news', $site_map_data);
	}
	
	/**
	 * Uninstall data of site map module
	 * 
	 * @return void
	 */
	public function deinstall_site_map() {
		$this->CI->load->model('Site_map_model');
		$this->CI->Site_map_model->delete_sitemap_module('news');
	}

	/**
	 * Install data of cronjob module
	 * 
	 * @return void
	 */
	public function install_cronjob() {
		///// cronjob
		$this->CI->load->model('Cronjob_model');
		$cron_data = array(
			"name" => "Feed parser",
			"module" => "news",
			"model" => "Feeds_model",
			"method" => "cron_feed_parser",
			"cron_tab" => "0 8 * * *",
			"status" => "1"
		);
		$this->CI->Cronjob_model->save_cron(null, $cron_data);
	}
	
	/**
	 * Uninstall data of cronjob module
	 * 
	 * @return void
	 */
	public function deinstall_cronjob() {
		$this->CI->load->model('Cronjob_model');
		$cron_data = array();
		$cron_data["where"]["module"] = "news";
		$this->CI->Cronjob_model->delete_cron_by_param($cron_data);
	}
	
	/**
	 * Install data of banners module
	 * 
	 * @return void
	 */
	public function install_banners() {
		///// add banners module
		$this->CI->load->model('News_model');
		$this->CI->load->model('banners/models/Banner_group_model');
		$this->CI->load->model('banners/models/Banner_place_model');

		$this->CI->Banner_group_model->set_module("news", "News_model", "_banner_available_pages");

		$group_id = $this->CI->Banner_group_model->get_group_id_by_gid('content_groups');
		///add pages in group
		$pages = $this->CI->News_model->_banner_available_pages();
		if ($pages){
			foreach($pages as $key => $value){
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
		$this->CI->Banner_group_model->delete_module("news");
	}

	/**
	 * Install data of subscriptions module
	 * 
	 * @return void
	 */
	public function install_subscriptions() {
		$this->CI->load->model('Subscriptions_model');
		$this->CI->load->model('subscriptions/models/Subscriptions_types_model');

		// Create template
		$this->CI->load->model('notifications/models/Templates_model');
		foreach($this->notifications['templates'] as $tpl) {
			$template_data = array(
				'gid' => $tpl['gid'],
				'name' => $tpl['name'],
				'vars' => serialize($tpl['vars']),
				'content_type' => 'text',
				'date_add' =>  date('Y-m-d H:i:s'),
				'date_update' => date('Y-m-d H:i:s'),
			);
			$this->CI->Templates_model->save_template(null, $template_data);
		}

		foreach($this->subscriptions['types'] as $type) {
			$subscr_data = array(
				'gid' => $type['gid'],
				'module' => $type['module'],
				'model' => $type['model'],
				'method' => $type['method'],
			);
			$this->CI->Subscriptions_types_model->save_subscriptions_type(null, $subscr_data);
		}
		foreach($this->subscriptions['subscriptions'] as $subscription) {
			$subscr_type = $this->CI->Subscriptions_types_model->get_subscriptions_type_by_gid($subscription['content_type']);
			$subscr_template = $this->CI->Templates_model->get_template_by_gid($subscription['template']);
			$subsc_data = array(
				'gid' => $subscription['gid'],
				'id_template' => $subscr_template['id'],
				'subscribe_type' => $subscription['type'],
				'id_content_type' => $subscr_type['id'],
				'scheduler' => $subscription['scheduler'],
			);
			$this->CI->Subscriptions_model->save_subscription(null, $subsc_data);
		}
	}
	
	/**
	 * Import languages of subscriptions module
	 * 
	 * @param array $langs_ids languages identifiers
	 * @return boolean
	 */
	public function install_subscriptions_lang_update($langs_ids = null) {
		if(empty($langs_ids)) return false;
		if(!is_array($langs_ids)) $langs_ids = (array)$langs_ids;
		$this->CI->load->model('Subscriptions_model');
		$this->CI->load->model('Notifications_model');
		$no_data = false;

		// Update notifications' langs
		$langs_file = $this->CI->Install_model->language_file_read('news', 'notifications', $langs_ids);
		if(!$langs_file) {
			log_message('info', 'Empty notifications langs data');
			$no_data = true;
		} else {
			$this->CI->Notifications_model->update_langs($this->notifications, $langs_file, $langs_ids);
		}

		// Update subscriptions' langs
		$langs_file = $this->CI->Install_model->language_file_read('news', 'subscriptions', $langs_ids);
		if(!$langs_file) {
			log_message('info', 'Empty subscriptions langs data');
			$no_data = true;
		} else {
			$this->CI->Subscriptions_model->update_langs('news', $this->subscriptions['subscriptions'], $langs_file, $langs_ids);
		}
		return !$no_data;
	}

	/**
	 * Export languages of subscriptions module
	 * 
	 * @param array $langs_ids languages identifiers
	 * @return array
	 */
	public function install_subscriptions_lang_export($langs_ids = null) {
		if(!is_array($langs_ids)) $langs_ids = (array)$langs_ids;
		$this->CI->load->model('Notifications_model');
		$this->CI->load->model('Subscriptions_model');

		$langs['notifications'] = $this->CI->Notifications_model->export_langs($this->notifications, $langs_ids);
		$langs['subscriptions'] = $this->CI->Subscriptions_model->export_langs($this->subscriptions['subscriptions'], $langs_ids);
		return $langs;
	}
	
	/**
	 * Uninstall data of subscriptions module
	 * 
	 * @return void
	 */
	public function deinstall_subscriptions() {
		$this->CI->load->model('Subscriptions_model');
		$this->CI->load->model('subscriptions/models/Subscriptions_types_model');

		// Delete template
		$this->CI->load->model('notifications/models/Templates_model');
		foreach($this->notifications['templates'] as $tpl){
			$this->CI->Templates_model->delete_template_by_gid($tpl['gid']);
		}

		foreach($this->subscriptions['types'] as $type) {
			$this->CI->Subscriptions_types_model->delete_subscriptions_type_by_gid($type['gid']);
		}
		foreach($this->subscriptions['subscriptions'] as $subscription) {
			$this->CI->Subscriptions_model->delete_subscription_by_gid($subscription['gid']);
		}
	}

	/**
	 * Install data of video uploads module
	 * 
	 * @return void
	 */
	public function install_video_uploads() {
		$this->CI->load->model('video_uploads/models/Video_uploads_config_model');
		$config_data = array(
			'gid' => 'news-video',
			'name' => 'News video',
			'max_size' => 1073741824,
			'file_formats' => 'a:5:{i:0;s:3:"avi";i:1;s:3:"flv";i:2;s:3:"mkv";i:3;s:3:"asf";i:4;s:4:"mpeg";}',
			'default_img' => 'news-video-default.jpg',
			'date_add' => date('Y-m-d H:i:s'),
			'upload_type' => 'local',
			'use_convert' => '1',
			'use_thumbs' => '1',
			'module' => 'news',
			'model' => 'News_model',
			'method_status' => 'video_callback',
			'thumbs_settings' => 'a:3:{i:0;a:4:{s:3:"gid";s:5:"small";s:5:"width";i:100;s:6:"height";i:70;s:8:"animated";i:0;}i:1;a:4:{s:3:"gid";s:6:"middle";s:5:"width";i:200;s:6:"height";i:140;s:8:"animated";i:0;}i:2;a:4:{s:3:"gid";s:3:"big";s:5:"width";i:480;s:6:"height";i:360;s:8:"animated";i:0;}}',
			'local_settings' => 'a:6:{s:5:"width";i:480;s:6:"height";i:360;s:10:"audio_freq";s:5:"22050";s:11:"audio_brate";s:3:"64k";s:11:"video_brate";s:4:"300k";s:10:"video_rate";s:2:"50";}',
			'youtube_settings' => 'a:2:{s:5:"width";i:480;s:6:"height";i:360;}',
		);
		$this->CI->Video_uploads_config_model->save_config(null, $config_data);
	}

	/**
	 * Uninstall data of video uploads modules
	 * 
	 * @return void
	 */
	public function deinstall_video_uploads() {
		$this->CI->load->model('video_uploads/models/Video_uploads_config_model');
		$config_data = $this->CI->Video_uploads_config_model->get_config_by_gid('news-video');
		if(!empty($config_data["id"])){
			$this->CI->Video_uploads_config_model->delete_config($config_data["id"]);
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
			'controller' => 'news',
			'method' => 'view',
			'name' => 'View news page',
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
		$this->CI->Social_networking_pages_model->delete_pages_by_controller('news');
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
		$langs_file = $this->CI->Install_model->language_file_read('news', 'moderators', $langs_ids);

		// install moderators permissions
		$this->CI->load->model('moderators/models/Moderators_model');
		$params['where']['module'] = 'news';
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
		$params['where']['module'] = 'news';
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
		$params['where']['module'] = 'news';
		$this->CI->Moderators_model->delete_methods($params);
	}

	/**
	 * Install data of comments module
	 * 
	 * @return void
	 */
	public function install_comments(){
		$this->CI->load->model('comments/models/Comments_types_model');
		$comment_type = array(
			'gid' => 'news',
			'module' => 'news',
			'model' => 'News_model',
			'method_count' => 'comments_count_callback',
			'method_object' => 'comments_object_callback'
		);
		$this->CI->Comments_types_model->add_comments_type($comment_type);
	}

	/**
	 * Import languages of comments module
	 * 
	 * @param array $langs_ids languages identifiers
	 * @return boolean
	 */
	public function install_comments_lang_update($langs_ids = null) {
		if(!is_array($langs_ids)) $langs_ids = (array)$langs_ids;
		$langs_file = $this->CI->Install_model->language_file_read('news', 'comments', $langs_ids);

		if(!$langs_file) {
			log_message('info', 'Empty moderation langs data');
			return false;
		}
		$this->CI->load->model('comments/models/Comments_types_model');
		$this->CI->Comments_types_model->update_langs(array('news'), $langs_file);

	}

	/**
	 * Export languages of comments module
	 * 
	 * @param array $langs_ids languages identifiers
	 * @return array
	 */
	public function install_comments_lang_export($langs_ids = null) {
		if(!is_array($langs_ids)) $langs_ids = (array)$langs_ids;
		$this->CI->load->model('comments/models/Comments_types_model');
		return array('comments' => $this->CI->Comments_types_model->export_langs(array('news'), $langs_ids));
	}

	/**
	 * Unistall data of comments module
	 * 
	 * @return void
	 */
	public function deinstall_comments(){
		$this->CI->load->model('comments/models/Comments_types_model');
		$this->CI->Comments_types_model->delete_comments_type('news');
	}
	
	/**
	 * Install data of dynamic blocks module
	 * 
	 * @return void
	 */
	public function install_dynamic_blocks() {
		$this->CI->load->model('Dynamic_blocks_model');
        $this->CI->Dynamic_blocks_model->installBatch($this->dynamic_blocks);
	}

	/**
     * Import languages of dynamic blocks module
     * 
     * @param array $langs_ids languages identifiers
     * @return boolean
     */
    public function install_dynamic_blocks_lang_update($langs_ids = null)
    {
        $this->CI->load->model('Dynamic_blocks_model');
        return $this->CI->Dynamic_blocks_model->updateLangsByModuleBlocks($this->dynamic_blocks, $langs_ids);
    }

    /**
     * Export languages of dynamic blocks module
     * 
     * @param array $langs_ids languages identifiers
     * @return array
     */
    public function install_dynamic_blocks_lang_export($langs_ids = null)
    {
        $this->CI->load->model('Dynamic_blocks_model');
        return array(
            'dynamic_blocks' => $this->CI->Dynamic_blocks_model->export_langs($this->dynamic_blocks, $langs_ids),
        );
    }

    /**
     * Unistall data of dynamic blocks module
     * 
     * @return void
     */
    public function deinstall_dynamic_blocks()
    {
        $this->CI->load->model('Dynamic_blocks_model');
        foreach ($this->dynamic_blocks as $block) {
            $this->CI->Dynamic_blocks_model->delete_block_by_gid($block['gid']);
        }
    }

    /**
	 * Install fields of dedicated languages
	 * 
	 * @return void
	 */
	public function _prepare_installing(){
		$this->CI->load->model("News_model");
		foreach($this->CI->pg_language->languages as $lang_id => $value){
			$this->CI->News_model->lang_dedicate_module_callback_add($lang_id);
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
			'module_gid' => 'news',
			'model_name' => 'News_model',
			'get_settings_method' => 'get_seo_settings',
			'get_rewrite_vars_method' => 'request_seo_rewrite',
			'get_sitemap_urls_method' => 'get_sitemap_xml_urls',
		);
		$this->CI->pg_seo->set_seo_module('news', $seo_data);
		$this->add_demo_content();
	}
	
	/**
	 * Import module languages
	 * 
	 * @param array $langs_ids array languages identifiers
	 * @return void
	 */
	public function _arbitrary_lang_install($langs_ids = null){
		$langs_file = $this->CI->Install_model->language_file_read('news', 'arbitrary', $langs_ids);
		if(!$langs_file){
			log_message('info', 'Empty news arbitrary langs data'); 
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
			$this->CI->pg_seo->set_settings('user', 'news', $page, $post_data);
		}
	}

	/**
	 * Export module languages
	 * 
	 * @param array $langs_ids languages identifiers
	 * @return array
	 */
	public function _arbitrary_lang_export($langs_ids = null){
		if(empty($langs_ids)) {
			return false;
		}
		$seo_settings = $this->pg_seo->get_all_settings_from_cache('user', 'news');
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
		return array('arbitrary' => $arbitrary_return);
	}
	
	/**
	 * Uninstall module data
	 * 
	 * @return void
	 */
	public function _arbitrary_deinstalling(){
		$this->CI->pg_seo->delete_seo_module('news');
		
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
	public function add_demo_content(){
		$this->CI->load->model('News_model');
		
		$languages = $this->CI->pg_language->languages;
		
		$demo_content = include MODULEPATH . 'news/install/demo_content.php';

		// Associating languages id with codes
		/*foreach($this->CI->pg_language->languages as $l) {
			$lang[$l['code']] = $l['id'];
		}*/
		foreach($demo_content as $news) {
			/*if(empty($lang[$news['lang_code']])) {
				continue;
			}*/
			
			if(empty($news['lang_code'])) continue;
			
			$lang_id = $this->CI->pg_language->get_lang_id_by_code($news['lang_code']);
			if(empty($lang_id)) continue;
			
			$news['id_lang'] = $lang_id;
			unset($news['lang_code']);
			
			foreach($languages as $lid=>$lang_data){
				$news['name_'.$lid] = $news['name'];
				$news['annotation_'.$lid] = $news['annotation'];
				$news['content_'.$lid] = $news['content'];
			}
			
			unset($news['name']);
			unset($news['annotation']);
			unset($news['content']);
						
			// Replace language code with ID
			/*$news['id_lang'] = $lang[$news['lang_code']];
			unset($news['lang_code']);
			$this->CI->News_model->save_news(null, $news);*/
	
			$validate_data = $this->CI->News_model->validate_news(null, $news);

			if(!empty($validate_data['errors'])) continue;
			if(!empty($news['img'])) $validate_data['data']['img'] = $news['img'];
			if(!empty($news['date_add'])) $validate_data['data']['date_add'] = $news['date_add'];
			$this->CI->News_model->save_news(null, $validate_data['data']);
		}
	}	
}
