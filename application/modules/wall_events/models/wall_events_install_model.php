<?php
/**
* Wall events install model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Dmitry Popenov
* @version $Revision: 2 $ $Date: 2013-01-30 10:50:07 +0400 $
**/
class Wall_events_install_model extends Model
{
	private $CI;
	private $menu = array(
		'admin_menu' => array(
			'action' => 'none',
			'name' => '',
			'items' => array(
				'settings_items' => array(
					'action' => 'none',
					'name' => '',
					'items' => array(
						'system-items' => array(
							'action'=>'none',
							'name' => '',
							'items' => array(
								"add_ons_items" => array(
									"action" => "none",
									'name' => '',
									"items" => array(
										"wall_events_menu_item" => array("action" => "create", "link" => "admin/wall_events", "status" => 1, "sorter" => 4),
									),
								),
							),
						),
					),
				),
			),
		),
		
		'admin_wall_events_menu' => array(
			'action' => 'create',
			'name' => 'Wall events menu',
			'items' => array(
				'wall_events_list_item' => array('action' => 'create', 'link' => 'admin/wall_events/', 'status' => 1, "sorter" => 1),
				'wall_events_settings' => array('action' => 'create', 'link' => 'admin/wall_events/settings', 'status' => 1, "sorter" => 2),
			),
		),
	);
	
	private $moderation_types = array(
		array(
			"name" => "wall_events",
			"mtype" => "-1",
			"module" => "wall_events",
			"model" => "Wall_events_model",
			"check_badwords" => "1",
			"method_get_list" => "",
			"method_set_status" => "",
			"method_delete_object" => "",
			"allow_to_decline" => "0",
			"template_list_row" => "",
		)
	);
	
	/**
	 * Spam configuration
	 * @var array
	 */
	private $spam = array(
		array("gid"=>"wall_events_object", "form_type"=>"select_text", "send_mail"=>true, "status"=>true, "module"=>"wall_events", "model"=>"Wall_events_model", "callback"=>"spam_callback"),
	);

	/**
	 * Constructor
	 *
	 * @return Install object
	 */
	public function __construct(){
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
		$langs_file = $this->CI->Install_model->language_file_read('wall_events', 'menu', $langs_ids);

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
	
	function _arbitrary_installing(){
		$this->CI->load->model('Wall_events_model');
		$this->CI->load->model('wall_events/models/Wall_events_types_model');
		$attrs = array(
			'gid' => $this->CI->Wall_events_model->wall_event_gid,
			'status' => '1',
			'module' => 'wall_events',
			'model' => 'wall_events_model',
			'method_format_event' => '_format_wall_events',
			'date_add' => date("Y-m-d H:i:s"),
			'date_update' => date("Y-m-d H:i:s"),
			'settings' => array(
				'join_period' => 0, // minutes, 0 = do not use
				'permissions' => array(
					'permissions' => 3, // permissions 0 - only for me, 1 - for me and friends, 2 - for registered, 3 - for all
					'feed' => 1, // show friends events in user feed
					'post_allow' => 1, // allow post on the wall for other users
				),
			),
		);
		$this->CI->Wall_events_types_model->add_wall_events_type($attrs);
		return;
	}

	function _arbitrary_deinstalling(){
		
	}
	
	public function install_comments(){
		$this->CI->load->model('comments/models/Comments_types_model');
		$comment_type = array(
			'gid' => 'wall_events',
			'module' => 'wall_events',
			'model' => 'Wall_events_model',
			'method_count' => 'comments_count_callback',
			'method_object' => 'comments_object_callback'
		);
		$this->CI->Comments_types_model->add_comments_type($comment_type);
	}

	public function install_comments_lang_update($langs_ids = null) {
		if(!is_array($langs_ids)) $langs_ids = (array)$langs_ids;
		$langs_file = $this->CI->Install_model->language_file_read('wall_events', 'comments', $langs_ids);

		if(!$langs_file) {
			log_message('info', 'Empty moderation langs data');
			return false;
		}
		$this->CI->load->model('comments/models/Comments_types_model');
		$this->CI->Comments_types_model->update_langs(array('wall_events'), $langs_file);
														
	}

	public function install_comments_lang_export($langs_ids = null) {
		if(!is_array($langs_ids)) $langs_ids = (array)$langs_ids;
		$this->CI->load->model('comments/models/Comments_types_model');
		return array('comments' => $this->CI->Comments_types_model->export_langs(array('wall_events'), $langs_ids));
	}

	public function deinstall_comments(){
		$this->CI->load->model('comments/models/Comments_types_model');
		$this->CI->Comments_types_model->delete_comments_type('wall_events');
	}
	
	public function install_uploads(){
		///// upload config
		$this->CI->load->model('uploads/models/Uploads_config_model');
		$config_data = array(
			'gid' => 'wall-image',
			'name' => 'Wall image',
			'max_height' => 2000,
			'max_width' => 2000,
			'max_size' => 10000000,
			'name_format' => 'generate',
			'file_formats' => array('jpg', 'jpeg', 'gif', 'png'),
			'default_img' => '',
			'date_add' => date('Y-m-d H:i:s'),
		);
		$config_data['file_formats'] = serialize($config_data['file_formats']);
		$config_id = $this->CI->Uploads_config_model->save_config(null, $config_data);
		$wm_data = $this->CI->Uploads_config_model->get_watermark_by_gid('image-wm');
		$wm_id = isset($wm_data["id"])?$wm_data["id"]:0;
		
		$thumb_data = array(
			'config_id' => $config_id,
			'prefix' => 'grand',
			'width' => 960,
			'height' => 720,
			'effect' => 'none',
			'watermark_id' => $wm_id,
			'crop_param' => 'resize',
			'crop_color' => 'ffffff',
			'date_add' => date('Y-m-d H:i:s'),
		);
		$this->CI->Uploads_config_model->save_thumb(null, $thumb_data);

		$thumb_data = array(
			'config_id' => $config_id,
			'prefix' => 'big',
			'width' => 200,
			'height' => 200,
			'effect' => 'none',
			'watermark_id' => $wm_id,
			'crop_param' => 'crop',
			'crop_color' => 'ffffff',
			'date_add' => date('Y-m-d H:i:s'),
		);
		$this->CI->Uploads_config_model->save_thumb(null, $thumb_data);

		$thumb_data = array(
			'config_id' => $config_id,
			'prefix' => 'middle',
			'width' => 100,
			'height' => 100,
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
			'width' => 60,
			'height' => 60,
			'effect' => 'none',
			'watermark_id' => 0,
			'crop_param' => 'crop',
			'crop_color' => 'ffffff',
			'date_add' => date('Y-m-d H:i:s'),
		);
		$this->CI->Uploads_config_model->save_thumb(null, $thumb_data);
	}
	
	public function deinstall_uploads(){
		$this->CI->load->model('uploads/models/Uploads_config_model');
		$config_data = $this->CI->Uploads_config_model->get_config_by_gid('wall-image');
		if(!empty($config_data['id'])){
			$this->CI->Uploads_config_model->delete_config($config_data['id']);
		}
	}

	public function install_video_uploads(){
		$this->CI->load->model('video_uploads/models/Video_uploads_config_model');
		$config_data = array(
			'gid' => 'wall-video',
			'name' => 'Wall video',
			'max_size' => 100000000,
			'file_formats' => 'a:5:{i:0;s:3:"avi";i:1;s:3:"flv";i:2;s:3:"mkv";i:3;s:3:"asf";i:4;s:4:"mpeg";}',
			'default_img' => '',
			'date_add' => date('Y-m-d H:i:s'),
			'upload_type' => 'local',
			'use_convert' => '1',
			'use_thumbs' => '1',
			'module' => 'wall_events',
			'model' => 'Wall_events_model',
			'method_status' => 'video_callback',
			/*'thumbs_settings' => 'a:3:{i:0;a:4:{s:3:"gid";s:5:"small";s:5:"width";i:100;s:6:"height";i:70;s:8:"animated";i:0;}i:1;a:4:{s:3:"gid";s:6:"middle";s:5:"width";i:200;s:6:"height";i:140;s:8:"animated";i:0;}i:2;a:4:{s:3:"gid";s:3:"big";s:5:"width";i:480;s:6:"height";i:360;s:8:"animated";i:0;}}',*/
			'thumbs_settings' => 			'a:4:{i:0;a:4:{s:3:"gid";s:5:"small";s:5:"width";i:100;s:6:"height";i:70;s:8:"animated";i:0;}i:1;a:4:{s:3:"gid";s:6:"middle";s:5:"width";i:200;s:6:"height";i:140;s:8:"animated";i:0;}i:2;a:4:{s:3:"gid";s:3:"big";s:5:"width";i:480;s:6:"height";i:360;s:8:"animated";i:0;}i:3;a:4:{s:3:"gid";s:5:"grand";s:5:"width";i:960;s:6:"height";i:720;s:8:"animated";i:0;}}',
			'local_settings' => 'a:6:{s:5:"width";i:480;s:6:"height";i:360;s:10:"audio_freq";s:5:"22050";s:11:"audio_brate";s:3:"64k";s:11:"video_brate";s:4:"300k";s:10:"video_rate";s:2:"50";}',
			'youtube_settings' => 'a:2:{s:5:"width";i:480;s:6:"height";i:360;}',
		);
		$this->CI->Video_uploads_config_model->save_config(null, $config_data);
	}
	
	public function deinstall_video_uploads(){
		$this->CI->load->model('video_uploads/models/Video_uploads_config_model');
		$config_data = $this->CI->Video_uploads_config_model->get_config_by_gid('wall-video');
		if(!empty($config_data["id"])){
			$this->CI->Video_uploads_config_model->delete_config($config_data["id"]);
		}
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
		if(empty($langs_ids)) return false;
		
		$this->CI->load->model("spam/models/Spam_type_model");
		
		$langs_file = $this->CI->Install_model->language_file_read("wall_events", "spam", $langs_ids);
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
		$langs = $this->CI->Spam_type_model->export_langs((array)$this->spam, $langs_ids);
		return array("spam" => $langs);
	}
	
	/**
	 * Uninstall spam links
	 */
	public function deinstall_spam(){
		//add spam type
		$this->CI->load->model("spam/models/Spam_type_model");

		foreach((array)$this->spam as $spam_data){
			$this->CI->Spam_type_model->delete_type($spam_data["gid"]);
		}
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
		$langs_file = $this->CI->Install_model->language_file_read('wall_events', 'moderation', $langs_ids);

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
	
	public function deinstall_moderation() {
		$this->CI->load->model('moderation/models/Moderation_type_model');
		foreach($this->moderation_types as $mtype) {
			$type = $this->CI->Moderation_type_model->get_type_by_name($mtype["name"]);
			$this->CI->Moderation_type_model->delete_type($type['id']);
		}
	}
	
	public function install_users(){
		$this->CI->load->model('users/models/Users_delete_callbacks_model');
		$this->CI->Users_delete_callbacks_model->add_callback('wall_events', 'Wall_events_model', 'callback_user_delete', '', 'wall_events');
	}

	public function deinstall_users(){
		$this->CI->load->model('users/models/Users_delete_callbacks_model');
		$this->CI->Users_delete_callbacks_model->delete_callbacks_by_module('wall_events');
	}
}
