<?php
/**
* Chats install model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Alexander Batukhtin <abatukhtin@pilotgroup.net>
**/
class Chats_install_model extends Model
{
	private $CI;
	private $_menu = array(
		'admin_menu' => array(
			'action' => 'none',
			'name'	=> '',
			'items' => array(
				'other_items' => array(
					'action' =>'none',
					'name'	=> '',
					'items' => array(
						"add_ons_items" => array(
							"action" => "none",
							'name'	=> '',
							"items" => array(
								"chats_menu_item" => array("action" => "create", "link" => "admin/chats", 'icon'=>'video-camera', "status" => 1, "sorter" => 3),
							),
						),
					),
				),
			),
		),
		'user_top_menu' => array(
			'action' => 'none',
			'name'	=> '',
			'items' => array(
				'user-menu-communication' => array(
					'action' => 'none',
					'name'	=> '',
					'items' => array(
						'chat_item' => array(
							'action' => 'create', 
							'link' => 'chats/', 
							'status' => 0, 
							'sorter' => 11,
						),
					),
				),
			),
		),
	);

	private $_chats = array(
		'cometchat',
		'flashchat'
	);

	private $_chat_models = array();

	/**
	 * Constructor
	 *
	 * @return Install object
	 */
	public function __construct()
	{
		parent::Model();
		$this->CI = & get_instance();
		foreach($this->_chats as $chat) {
			if(file_exists(__DIR__ . "/chats/$chat" . EXT)) {
				$this->CI->load->model("chats/models/chats/$chat");
				$this->_chat_models[] = $this->CI->$chat;
			}
		}
	}

	public function install_menu() {
		$this->CI->load->helper('menu');
		foreach($this->_menu as $gid => $menu_data){
			$this->_menu[$gid]['id'] = linked_install_set_menu($gid, $menu_data['action'], $menu_data['name']);
			linked_install_process_menu_items($this->_menu, 'create', $gid, 0, $this->_menu[$gid]['items']);
		}
	}

	public function install_menu_lang_update($langs_ids = null) {
		if(empty($langs_ids)) {
			return false;
		}
		$langs_file = $this->CI->Install_model->language_file_read('chats', 'menu', $langs_ids);
		if(!$langs_file) {
			log_message('info', 'Empty menu langs data'); return false;
		}
		$this->CI->load->helper('menu');
		foreach(array_keys($this->_menu) as $gid){
			linked_install_process_menu_items($this->_menu, 'update', $gid, 0, $this->_menu[$gid]['items'], $gid, $langs_file);
		}
		return true;
	}

	public function install_menu_lang_export($langs_ids) {
		if(empty($langs_ids)) {
			return false;
		}
		$this->CI->load->helper('menu');
		$return = array();
		foreach(array_keys($this->_menu) as $gid){
			$temp = linked_install_process_menu_items($this->_menu, 'export', $gid, 0, $this->_menu[$gid]['items'], $gid, $langs_ids);
			$return = array_merge($return, $temp);
		}
		return array( 'menu' => $return );
	}

	public function deinstall_menu() {
		$this->CI->load->helper('menu');
		foreach($this->_menu as $gid => $menu_data){
			if($menu_data['action'] == 'create'){
				linked_install_set_menu($gid, 'delete');
			}else{
				linked_install_delete_menu_items($gid, $this->_menu[$gid]['items']);
			}
		}
	}

	private function _add_chats() {
		$this->CI->load->model('Chats_model');
		foreach($this->_chat_models as $chat) {
			$this->CI->Chats_model->save($chat->as_array(true));
		}
	}

	public function _arbitrary_installing(){
		$this->_add_chats();
		// seo
		$seo_data = array(
			'module_gid' => 'chats',
			'model_name' => 'Chats_model',
			'get_settings_method' => 'get_seo_settings',
			'get_rewrite_vars_method' => 'request_seo_rewrite',
			'get_sitemap_urls_method' => 'get_sitemap_xml_urls',
		);
		$this->CI->pg_seo->set_seo_module('chats', $seo_data);
	}

	/**
	 * Import module languages
	 * 
	 * @param array $langs_ids array languages identifiers
	 * @return void
	 */
	public function _arbitrary_lang_install($langs_ids=null){
		$langs_file = $this->CI->Install_model->language_file_read('chats', 'arbitrary', $langs_ids);
		if(!$langs_file){
			log_message('info', 'Empty chats arbitrary langs data'); 
			return false;
		}

		$post_data = array(
			'title' => $langs_file['seo_tags_index_title'],
			'keyword' => $langs_file['seo_tags_index_keyword'],
			'description' => $langs_file['seo_tags_index_description'],
			'header' => $langs_file['seo_tags_index_header'],
			'og_title' => $langs_file['seo_tags_index_og_title'],
			'og_type' => $langs_file['seo_tags_index_og_type'],
			'og_description' => $langs_file['seo_tags_index_og_description'],
		);
		$this->CI->pg_seo->set_settings('user', 'chats', 'index', $post_data);
	}

	/**
	 * Export module languages
	 * 
	 * @param array $langs_ids languages identifiers
	 * @return array
	 */
	public function _arbitrary_lang_export($langs_ids=null){
		if(empty($langs_ids)) {
			return false;
		}

		// arbitrary
		$settings = $this->CI->pg_seo->get_settings('user', 'chats', 'chats', $langs_ids);
		$arbitrary_return['seo_tags_chats_title'] = $settings['title'];
		$arbitrary_return['seo_tags_chats_keyword'] = $settings['keyword'];
		$arbitrary_return['seo_tags_chats_description'] = $settings['description'];
		$arbitrary_return['seo_tags_chats_header'] = $settings['header'];
		$arbitrary_return['seo_tags_chats_og_title'] = $settings['og_title'];
		$arbitrary_return['seo_tags_chats_og_type'] = $settings['og_type'];
		$arbitrary_return['seo_tags_chats_og_description'] = $settings['og_description'];
		return array('arbitrary' => $arbitrary_return);
	}

	public function _arbitrary_deinstalling(){}

}
