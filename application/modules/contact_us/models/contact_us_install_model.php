<?php
/**
* Contact us install model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

class Contact_us_install_model extends Model
{
	private $CI;
	private $menu = array(
		'user_footer_menu' => array(
			'action' => 'none',
			'items' => array(
				'footer-menu-help-item' => array(
					'action' => 'none',
					'items' => array(
						'footer-menu-contact-item' => array('action' => 'create', 'link' => 'contact_us', 'status' => 1, 'sorter' => 1)
					)
				)
			)
		),
		'admin_menu' => array(
			'action' => 'none',
			'items' => array(
				'settings_items' => array(
					'action' => 'none',
					'items' => array(
						'content_items' => array(
							'action' => 'none',
							'items' => array(
								'contactus_menu_item' => array('action' => 'create', 'link' => 'admin/contact_us', 'status' => 1, 'sorter' => 7)
							)
						)
					)
				)
			)
		),
		'admin_contacts_menu' => array(
			'action' => 'create',
			'name' => 'Contact section menu',
			'items' => array(
				'reasons_list_item' => array('action' => 'create', 'link' => 'admin/contact_us', 'status' => 1),
				'settings_list_item' => array('action' => 'create', 'link' => 'admin/contact_us/settings', 'status' => 1)
			)
		)
	);
	private $notifications = array(
		'notifications' => array(
			array('gid' => 'contact_us_form', 'send_type' => 'simple')
		),
		'templates' => array(
			array('gid' => 'contact_us_form', 'name' => 'Contact us form mail', 'vars' => array("user_name", "user_email", "subject", "message", "reason", "form_date"), 'content_type' => 'text')
		)
	);
	
	private $_moderation_types = array(
		array(
			"name" => "contact_us",
			"mtype" => "-1",
			"module" => "contact_us",
			"model" => "Contact_us_model",
			"check_badwords" => "1",
			"method_get_list" => "",
			"method_set_status" => "",
			"method_delete_object" => "",
			"allow_to_decline" => "0",
			"template_list_row" => "",
		)
	);

	function __construct()
	{
		parent::Model();
		$this->CI = & get_instance();
		$this->CI->load->model('Install_model');
	}

	public function install_menu() {
		$this->CI->load->helper('menu');
		foreach($this->menu as $gid => $menu_data){
			$this->menu[$gid]['id'] = linked_install_set_menu($gid, $menu_data["action"], $menu_data["name"]);
			linked_install_process_menu_items($this->menu, 'create', $gid, 0, $this->menu[$gid]["items"]);
		}
	}

	public function install_menu_lang_update($langs_ids = null) {
		if(empty($langs_ids)) return false;
		$langs_file = $this->CI->Install_model->language_file_read('contact_us', 'menu', $langs_ids);

		if(!$langs_file) { log_message('info', 'Empty menu langs data'); return false; }
		$this->CI->load->helper('menu');
		foreach($this->menu as $gid => $menu_data){
			linked_install_process_menu_items($this->menu, 'update', $gid, 0, $this->menu[$gid]["items"], $gid, $langs_file);
		}
		return true;
	}

	public function install_menu_lang_export($langs_ids) {
		if(empty($langs_ids)) return false;
		$this->CI->load->helper('menu');

		$return = array();
		foreach($this->menu as $gid => $menu_data){
			$temp = linked_install_process_menu_items($this->menu, 'export', $gid, 0, $this->menu[$gid]["items"], $gid, $langs_ids);
			$return = array_merge($return, $temp);
		}
		return array( 'menu' => $return );
	}

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

	public function install_site_map() {
		$this->CI->load->model('Site_map_model');
		$site_map_data = array(
			'module_gid' => 'contact_us',
			'model_name' => 'Contact_us_model',
			'get_urls_method' => 'get_sitemap_urls',
		);
		$this->CI->Site_map_model->set_sitemap_module('contact_us', $site_map_data);
	}

	public function install_banners() {
		///// add banners module
		$this->CI->load->model('Contact_us_model');
		$this->CI->load->model('banners/models/Banner_group_model');
		$this->CI->load->model('banners/models/Banner_place_model');
		$this->CI->Banner_group_model->set_module("contact_us", "Contact_us_model", "_banner_available_pages");

		$group_id = $this->CI->Banner_group_model->get_group_id_by_gid('contact_groups');
		///add pages in group
		$pages = $this->CI->Contact_us_model->_banner_available_pages();
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

	public function install_notifications() {
		// add notification
		$this->CI->load->model('Notifications_model');
		$this->CI->load->model('notifications/models/Templates_model');

		foreach($this->notifications['templates'] as $tpl){
			$template_data = array(
				'gid' => $tpl['gid'],
				'name' => $tpl['name'],
				'vars' => serialize($tpl['vars']),
				'content_type' => $tpl['content_type'],
				'date_add' =>  date('Y-m-d H:i:s'),
				'date_update' => date('Y-m-d H:i:s'),
			);
			$tpl_ids[$tpl['gid']] = $this->CI->Templates_model->save_template(null, $template_data);
		}

		foreach($this->notifications['notifications'] as $notification) {
			$notification_data = array(
				'gid' => $notification['gid'],
				'send_type' => $notification['send_type'],
				'id_template_default' => $tpl_ids[$notification['gid']],
				'date_add' => date("Y-m-d H:i:s"),
				'date_update' => date("Y-m-d H:i:s"),
			);
			$this->CI->Notifications_model->save_notification(null, $notification_data);
		}

	}

	public function install_notifications_lang_update($langs_ids = null) {
		if(empty($langs_ids)) return false;
		$this->CI->load->model('Notifications_model');

		$langs_file = $this->CI->Install_model->language_file_read('contact_us', 'notifications', $langs_ids);

		if(!$langs_file) { log_message('info', 'Empty notifications langs data'); return false; }

		$this->CI->Notifications_model->update_langs($this->notifications, $langs_file, $langs_ids);
		return true;
	}

	public function install_notifications_lang_export($langs_ids = null) {
		$this->CI->load->model('Notifications_model');
		$langs = $this->CI->Notifications_model->export_langs($this->notifications, $langs_ids);
		return array('notifications' => $langs);
	}

	public function deinstall_notifications() {
		$this->CI->load->model('Notifications_model');
		$this->CI->load->model('notifications/models/Templates_model');
		foreach($this->notifications['templates'] as $tpl){
			$this->CI->Templates_model->delete_template_by_gid($tpl['gid']);
		}
		foreach($this->notifications['notifications'] as $notification){
			$this->CI->Notifications_model->delete_notification_by_gid($notification['gid']);
		}
	}

	public function install_social_networking() {
		///// add social netorking page
		$this->CI->load->model('social_networking/models/Social_networking_pages_model');
		$page_data = array(
			'controller' => 'contact_us',
			'method' => 'index',
			'name' => 'Contact us page',
			'data' => 'a:3:{s:4:"like";a:3:{s:8:"facebook";s:2:"on";s:9:"vkontakte";s:2:"on";s:6:"google";s:2:"on";}s:5:"share";a:4:{s:8:"facebook";s:2:"on";s:9:"vkontakte";s:2:"on";s:8:"linkedin";s:2:"on";s:7:"twitter";s:2:"on";}s:8:"comments";s:1:"1";}',
		);
		$this->CI->Social_networking_pages_model->save_page(null, $page_data);
	}

	function _arbitrary_installing(){
		//// load langs
		$seo_data = array(
			'module_gid' => 'contact_us',
			'model_name' => 'Contact_us_model',
			'get_settings_method' => 'get_seo_settings',
			'get_rewrite_vars_method' => 'request_seo_rewrite',
			'get_sitemap_urls_method' => 'get_sitemap_xml_urls',
		);
		$this->CI->pg_seo->set_seo_module('contact_us', $seo_data);
	}

	public function deinstall_site_map() {
		$this->CI->load->model('Site_map_model');
		$this->CI->Site_map_model->delete_sitemap_module('contact_us');
	}

	public function deinstall_banners() {
		///// delete banners module
		$this->CI->load->model("banners/models/Banner_group_model");
		$this->CI->Banner_group_model->delete_module("contact_us");
	}

	public function deinstall_social_networking() {
		///// delete social netorking page
		$this->CI->load->model('social_networking/models/Social_networking_pages_model');
		$this->CI->Social_networking_pages_model->delete_pages_by_controller('contact_us');
	}

	public function install_moderation () {
		// Moderation
		$this->CI->load->model('moderation/models/Moderation_type_model');
		foreach($this->_moderation_types as $mtype) {
			$mtype['date_add'] = date('Y-m-d H:i:s');
			$this->CI->Moderation_type_model->save_type(null, $mtype);
		}
	}

	public function install_moderation_lang_update($langs_ids = null) {
		if(!is_array($langs_ids)) {
			$langs_ids = (array)$langs_ids;
		}
		$langs_file = $this->CI->Install_model->language_file_read('contact_us', 'moderation', $langs_ids);
		if(!$langs_file) {
			log_message('info', 'Empty moderation langs data');
			return false;
		}
		$this->CI->load->model('moderation/models/Moderation_type_model');
		$this->CI->Moderation_type_model->update_langs($this->_moderation_types, $langs_file);
	}

	public function install_moderation_lang_export($langs_ids = null) {
		if(!is_array($langs_ids)) {
			$langs_ids = (array)$langs_ids;
		}
		$this->CI->load->model('moderation/models/Moderation_type_model');
		return array('moderation' => $this->CI->Moderation_type_model->export_langs($this->_moderation_types, $langs_ids));
	}

	private $_seo_pages = array(
		'index',
	);

	/**
	 * Import module languages
	 * 
	 * @param array $langs_ids array languages identifiers
	 * @return void
	 */
	public function _arbitrary_lang_install($langs_ids = null){
		$langs_file = $this->CI->Install_model->language_file_read('contact_us', 'arbitrary', $langs_ids);
		if(!$langs_file){
			log_message('info', 'Empty contact_us arbitrary langs data'); 
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
			$this->CI->pg_seo->set_settings('user', 'contact_us', $page, $post_data);
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
		$seo_settings = $this->pg_seo->get_all_settings_from_cache('user', 'contact_us');
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

	function _arbitrary_deinstalling(){
		$this->CI->load->model('Menu_model');
		$this->CI->pg_seo->delete_seo_module('contact_us');
	}

}
