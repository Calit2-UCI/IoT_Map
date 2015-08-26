<?php
/**
* Comments install model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Dmitry Popenov
* @version $Revision: 2 $ $Date: 2013-01-30 10:50:07 +0400 $
**/
class Comments_install_model extends Model
{
	private $CI;
	private $menu = array(
		'admin_menu' => array(
			'action' => 'none',
			'items' => array(
				'settings_items' => array(
					'action' => 'none',
					'items' => array(
						'system-items' => array(
							'action'=>'none',
							'items' => array(
								'comments_menu_item' => array('action' => 'create', 'link' => 'admin/comments', 'status' => 1, 'sorter' => 2)
							)
						)
					)
				)
			)
		)
	);
	private $moderation_types = array(
		array(
			"name"					=> "comments",
			"mtype"					=> "0",
			"module"				=> "comments",
			"model"					=> "Comments_model",
			"check_badwords"		=> "1",
			"method_get_list"		=> "_moder_get_list",
			"method_set_status"		=> "_moder_set_status",
			"method_delete_object"	=> "_moder_delete_object",
			"allow_to_decline"		=> "1",
			"template_list_row"		=> "moder_block",
		)
	);
	private $spam = array(
		array(
			"gid"		=> "comments_object",
			"form_type"	=> "select_text",
			"send_mail"	=> true,
			"status"	=> true,
			"module"	=> "comments",
			"model"		=> "Comments_model",
			"callback"	=> "spam_callback"
		)
	);

	/**
	 * Constructor
	 *
	 * @return Install object
	 */
	function __construct()
	{
		parent::Model();
		$this->CI = & get_instance();
	}
	
	/**
	 * Check system requirements of module
	 */
	function _validate_requirements(){
		$result = array("data"=>array(), "result" => true);

		//check for Mbstring
		$good			= function_exists("mb_substr");
		$result["data"][] = array(
			"name" => "Mbstring extension (required for feeds parsing) is installed",
			"value" => $good?"Yes":"No",
			"result" => $good,
		);
		$result["result"] = $result["result"] && $good;

		return $result;
	}

	public function install_menu() {
		$this->CI->load->helper('menu');

		foreach($this->menu as $gid => $menu_data){
			$this->menu[$gid]['id'] = linked_install_set_menu($gid, $menu_data['action'], $menu_data['name']);
			linked_install_process_menu_items($this->menu, 'create', $gid, 0, $this->menu[$gid]['items']);
		}
	}

	public function install_menu_lang_update($langs_ids = null) {
		if(empty($langs_ids)) return false;
		$langs_file = $this->CI->Install_model->language_file_read('comments', 'menu', $langs_ids);

		if(!$langs_file) { log_message('info', 'Empty menu langs data'); return false; }

		$this->CI->load->helper('menu');

		foreach($this->menu as $gid => $menu_data){
			linked_install_process_menu_items($this->menu, 'update', $gid, 0, $this->menu[$gid]['items'], $gid, $langs_file);
		}
		return true;
	}

	public function install_menu_lang_export($langs_ids) {
		if(empty($langs_ids)) return false;
		$this->CI->load->helper('menu');

		$return = array();
		foreach($this->menu as $gid => $menu_data){
			$temp = linked_install_process_menu_items($this->menu, 'export', $gid, 0, $this->menu[$gid]['items'], $gid, $langs_ids);
			$return = array_merge($return, $temp);
		}
		return array( 'menu' => $return );
	}
	
	public function install_moderation () {
		// Moderation
		$this->CI->load->model('moderation/models/Moderation_type_model');
		foreach($this->moderation_types as $mtype) {
			$mtype['date_add'] = date("Y-m-d H:i:s");
			$this->CI->Moderation_type_model->save_type(null, $mtype);
		}
	}
	
	public function install_moderation_lang_update($langs_ids = null) {
		if(!is_array($langs_ids)) $langs_ids = (array)$langs_ids;
		$langs_file = $this->CI->Install_model->language_file_read('comments', 'moderation', $langs_ids);

		if(!$langs_file) {
			log_message('info', 'Empty moderation langs data');
			return false;
		}
		$this->CI->load->model('moderation/models/Moderation_type_model');
		$this->CI->Moderation_type_model->update_langs($this->moderation_types, $langs_file);
														
	}

	public function install_moderation_lang_export($langs_ids = null) {
		if(!is_array($langs_ids)) $langs_ids = (array)$langs_ids;
		$this->CI->load->model('moderation/models/Moderation_type_model');
		return array('moderation' => $this->CI->Moderation_type_model->export_langs($this->moderation_types, $langs_ids));
	}

	/**
	 * Install spam links
	 */
	public function install_spam(){
		// add spam type
		$this->CI->load->model("spam/models/Spam_type_model");

		foreach((array)$this->spam as $spam_data){
			$validate_data = $this->CI->Spam_type_model->validate_type(null, $spam_data);
			if(!empty($validate_data["errors"])) continue;
			$this->CI->Spam_type_model->save_type(null, $validate_data["data"]);
		}
	}
	
	/**
	 * Import spam languages
	 * @param array $langs_ids
	 */
	public function install_spam_lang_update($langs_ids=null){
		if(!is_array($langs_ids)) $langs_ids = (array)$langs_ids;

		$this->CI->load->model("spam/models/Spam_type_model");
		$langs_file = $this->CI->Install_model->language_file_read("comments", "spam", $langs_ids);
		if(!$langs_file){log_message("info", "Empty spam langs data");return false;}

		$this->CI->Spam_type_model->update_langs($this->spam, $langs_file, $langs_ids);
		return true;
	}
	
	/**
	 * Export spam languages
	 * @param array $langs_ids
	 */
	 
	public function install_spam_lang_export($langs_ids=null){
		$this->CI->load->model("spam/models/Spam_type_model");
		$langs = $this->CI->Spam_type_model->export_langs($this->spam, $langs_ids);
		return array("spam" => $langs);
	}
	
	/**
	 * Uninstall spam links
	 */
	public function deinstall_spam(){
		//add spam type
		$this->CI->load->model("spam/models/Spam_type_model");
		foreach($this->spam as $spam_data){
			$this->CI->Spam_type_model->delete_type($spam_data["gid"]);
		}
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
	
	public function deinstall_moderation() {
		$this->CI->load->model('moderation/models/Moderation_type_model');
		foreach($this->moderation_types as $mtype) {
			$type = $this->CI->Moderation_type_model->get_type_by_name($mtype["name"]);
			$this->CI->Moderation_type_model->delete_type($type['id']);
		}
	}
	
	public function install_users(){
		$this->CI->load->model('users/models/Users_delete_callbacks_model');
		$this->CI->Users_delete_callbacks_model->add_callback('comments', 'Comments_model', 'callback_user_delete', '', 'comments');
	}

	public function deinstall_users(){
		$this->CI->load->model('users/models/Users_delete_callbacks_model');
		$this->CI->Users_delete_callbacks_model->delete_callbacks_by_module('comments');
	}

	function _arbitrary_installing(){

	}

	function _arbitrary_deinstalling(){
		
	}
}
