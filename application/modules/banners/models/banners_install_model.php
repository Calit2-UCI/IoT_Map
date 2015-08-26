<?php
/**
* Banners install model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/
class Banners_install_model extends Model
{
	private $CI;
	private $menu = array(
		'admin_menu' => array(
			'action' => 'none',
			'items' => array(
				'system_items' => array(
					'action' =>'none',
					'items' => array(
						'banners_menu_item' => array('action' => 'create', 'link' => 'admin/banners', 'icon'=>'th-large', 'status' => 1, 'sorter' => 5),
					),
				),
			),
		),
		'admin_banners_menu' => array(
			'action' => 'create',
			'name' => 'Banners section menu',
			'items' => array(
				'banners_list_item' => array('action' => 'create', 'link' => 'admin/banners', 'status' => 1),
				'groups_list_item' => array('action' => 'create', 'link' => 'admin/banners/groups_list', 'status' => 1),
				'places_list_item' => array('action' => 'create', 'link' => 'admin/banners/places_list', 'status' => 1),
				'banners_settings_item' => array('action' => 'create', 'link' => 'admin/banners/settings', 'status' => 1),
			),
		),
	);
	
	/**
	 * Notifications configuration
	 * 
	 * @var array
	 */
	protected $notifications = array(
		"templates" => array(
			array("gid"=>"banner_need_moderate", "name"=>"New banner awaiting moderation", "vars"=>array(), "content_type"=>"text"),
			array("gid"=>"banner_status_approved", "name"=>"Banner approved", "vars"=>array("user", "banner"), "content_type"=>"text"),
			array("gid"=>"banner_status_declined", "name"=>"Banner declined", "vars"=>array("user", "banner"), "content_type"=>"text"),
			array("gid"=>"banner_status_expired", "name"=>"Banner status expired", "vars"=>array("user", "banner"), "content_type"=>"text"),
			//array("gid"=>"banner_status_updated", "name"=>"Banner status updated", "vars"=>array("user", "banner", "status"), "content_type"=>"text"),
		),
		"notifications" => array(
			array("gid"=>"banner_need_moderate", "template"=>"banner_need_moderate", "send_type"=>"simple"),
			array("gid"=>"banner_status_approved", "template"=>"banner_status_approved", "send_type"=>"simple"),
			array("gid"=>"banner_status_declined", "template"=>"banner_status_declined", "send_type"=>"simple"),
			array("gid"=>"banner_status_expired", "template"=>"banner_status_expired", "send_type"=>"simple"),
			//array("gid"=>"banner_status_updated", "template"=>"banner_status_updated", "send_type"=>"simple"),
		),
	);

	private $lang_services = array(
		'service' => array('banner_service'),
		'template' => array('banner_template'),
	);
	
	/**
	 * Moderators configuration
	 * 
	 * @var array
	 */
	protected $moderators_methods = array(
		array('module' => 'banners', 'method' => 'index', 'is_default' => 1),
		array('module' => 'banners', 'method' => 'groups_list', 'is_default' => 0),
		array('module' => 'banners', 'method' => 'places_list', 'is_default' => 0),
	);
	
	private $moderation_types = array(
		array(
			"name" => "banners",
			"mtype" => "-1",
			"module" => "banners",
			"model" => "Banners_model",
			"check_badwords" => "1",
			"method_get_list" => "",
			"method_set_status" => "",
			"method_delete_object" => "",
			"allow_to_decline" => "0",
			"template_list_row" => "",
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

	public function install_menu() {
		$this->CI->load->helper('menu');

		foreach($this->menu as $gid => $menu_data){
			$this->menu[$gid]['id'] = linked_install_set_menu($gid, $menu_data['action'], $menu_data['name']);
			linked_install_process_menu_items($this->menu, 'create', $gid, 0, $this->menu[$gid]['items']);
		}
	}

	public function install_menu_lang_update($langs_ids = null) {
		if(empty($langs_ids)) return false;
		$langs_file = $this->CI->Install_model->language_file_read('banners', 'menu', $langs_ids);

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
	
	/**
	 * Install notifications of banners
	 * 
	 * @return void
	 */
	public function install_notifications(){
		// add notification
		$this->CI->load->model("Notifications_model");
		$this->CI->load->model("notifications/models/Templates_model");

		$templates_ids = array();

		foreach((array)$this->notifications["templates"] as $template_data){
			if(is_array($template_data["vars"])) $template_data["vars"] = implode(", ", $template_data["vars"]);			
			$validate_data = $this->CI->Templates_model->validate_template(null, $template_data);
			if(!empty($validate_data["errors"])) continue;
			$templates_ids[$template_data['gid']] = $this->CI->Templates_model->save_template(null, $validate_data["data"]);			
		}
		
		foreach((array)$this->notifications["notifications"] as $notification_data){
			if(!isset($templates_ids[$notification_data["template"]])){
				 $template = $this->CI->Templates_model->get_template_by_gid($notification_data["template"]);
				 $templates_ids[$notification_data["template"]] = $template["id"];
			}
			$notification_data["id_template_default"] = $templates_ids[$notification_data["template"]];
			$validate_data = $this->CI->Notifications_model->validate_notification(null, $notification_data);
			if(!empty($validate_data["errors"])) continue;
			$this->CI->Notifications_model->save_notification(null, $validate_data["data"], $lang_data);
		}
	}
	
	/**
	 * Import notifiactions languages of banners
	 * 
	 * @param array $langs_ids languages identifiers
	 * @return void
	 */
	public function install_notifications_lang_update($langs_ids=null){
		if(empty($langs_ids)) return false;
		$this->CI->load->model("Notifications_model");
		
		$langs_file = $this->CI->Install_model->language_file_read("banners", "notifications", $langs_ids);
		if(!$langs_file){log_message("info", "Empty notifications langs data");return false;}
		
		$this->CI->Notifications_model->update_langs($this->notifications, $langs_file, $langs_ids);
		return true;
	}
	
	/**
	 * Export notifications languages of banners
	 * 
	 * @param array $langs_ids languages identifiers
	 * @return array
	 */
	public function install_notifications_lang_export($langs_ids=null){
		$this->CI->load->model("Notifications_model");
		$langs = $this->CI->Notifications_model->export_langs($this->notifications, $langs_ids);
		return array("notifications" => $langs);
	}
	
	/**
	 * Unistall notifacations data of banners
	 * 
	 * @return array
	 */
	public function deinstall_notifications(){
		$this->CI->load->model("Notifications_model");
		$this->CI->load->model("notifications/models/Templates_model");
		
		foreach((array)$this->notifications["notifications"] as $notification_data){
			$this->CI->Notifications_model->delete_notification_by_gid($notification_data["gid"]);
		}
		
		foreach((array)$this->notifications["templates"] as $template_data){
			$this->CI->Templates_model->delete_template_by_gid($template_data["gid"]);
		}
	}

	public function install_uploads () {
		$this->CI->load->model('uploads/models/Uploads_config_model');
		$config_data = array(
			'gid' => 'banner',
			'name' => 'Banner image file',
			'max_height' => 1000,
			'max_width' => 1000,
			'max_size' => 100000,
			'name_format' => 'generate',
			'file_formats' => 'a:3:{i:0;s:3:"jpg";i:1;s:3:"gif";i:2;s:3:"png";}',
			//'default_img' => 'default_banner.gif',
			'date_add' => date('Y-m-d H:i:s'),
		);
		$this->CI->Uploads_config_model->save_config(null, $config_data);
	}

	public function install_services () {
		// add service type and service
		// create service template and service
		$this->CI->load->model("Services_model");
		$template_data = array(
			'gid' => "banner_template",
			'callback_module' => "banners",
			'callback_model' => "Banners_model",
			'callback_buy_method' => "service_buy_banner",
			'callback_activate_method' => "service_activate_banner",
			'callback_validate_method' => "service_validate_banner",
			'price_type' => 3,
			'data_admin' => "",
			'data_user'=> serialize(array("id_banner_payment" => "hidden")),
			'date_add' => date("Y-m-d H:i:s"),
			'moveable' => 0,
			'alert_activate' => 0,
		);
		$this->CI->Services_model->save_template(null, $template_data);

		$service_data = array(
			"gid" => "banner_service",
			"template_gid" => "banner_template",
			"pay_type" => 2,
			"status" => 1,
			"price" => 0,
			"type" => "internal",
			"data_admin" => "",
			"date_add" => date("Y-m-d H:i:s")
		);
		$this->CI->Services_model->save_service(null, $service_data);
	}

	public function install_services_lang_update($langs_ids = null) {
		if(!is_array($langs_ids)) $langs_ids = (array)$langs_ids;
		$this->CI->load->model('Services_model');
		$langs_file = $this->CI->Install_model->language_file_read('banners', 'services', $langs_ids);
		$this->CI->Services_model->update_langs($this->lang_services, $langs_file);
		return true;
	}

	public function install_services_lang_export($langs_ids = null) {
		if(!is_array($langs_ids)) $langs_ids = (array)$langs_ids;
		$this->CI->load->model('Services_model');
		return array( 'services' => $this->CI->Services_model->export_langs($this->lang_services, $langs_ids));
	}

	public function install_cronjob () {
		///// add cronjob ()
		$this->CI->load->model('Cronjob_model');
		$cron_data = array(
			"name" => "Update banner statistic",
			"module" => "banners",
			"model" => "Banners_model",
			"method" => "update_statistic",
			"cron_tab" => "*/10 * * * *",
			"status" => "1"
		);
		$this->CI->Cronjob_model->save_cron(null, $cron_data);
	}

	/**
	 * Moderators module methods
	 */
	public function install_moderators() {
		// install moderators permissions
		$this->CI->load->model('moderators/models/Moderators_model');

		foreach($this->moderators_methods as $method){
			$this->CI->Moderators_model->save_method(null, $method);
		}
	}

	public function install_moderators_lang_update($langs_ids = null) {
		$langs_file = $this->CI->Install_model->language_file_read('banners', 'moderators', $langs_ids);

		// install moderators permissions
		$this->CI->load->model('moderators/models/Moderators_model');
		$params['where']['module'] = 'banners';
		$methods = $this->CI->Moderators_model->get_methods_lang_export($params);

		foreach($methods as $method){
			if(!empty($langs_file[$method['method']])){
				$this->CI->Moderators_model->save_method($method['id'], array(), $langs_file[$method['method']]);
			}
		}
	}

	public function install_moderators_lang_export($langs_ids) {
		$this->CI->load->model('moderators/models/Moderators_model');
		$params['where']['module'] = 'banners';
		$methods =  $this->CI->Moderators_model->get_methods_lang_export($params, $langs_ids);
		foreach($methods as $method){
			$return[$method['method']] = $method['langs'];
		}
		return array( 'moderators' => $return );
	}

	public function deinstall_moderators() {
		// delete moderation methods in moderators
		$this->CI->load->model('moderators/models/Moderators_model');
		$params['where']['module'] = 'banners';
		$this->CI->Moderators_model->delete_methods($params);
	}

	function _arbitrary_installing(){
        if (DEMO_MODE || TRIAL_MODE) {
            ///// Add dynamic block
            $this->add_banners_to_site();
        }
	}

	public function deinstall_uploads () {
		$this->CI->load->model('uploads/models/Uploads_config_model');
		$config_data = $this->CI->Uploads_config_model->get_config_by_gid('banner');
		if(!empty($config_data["id"])){
			$this->CI->Uploads_config_model->delete_config($config_data["id"]);
		}
	}

	public function deinstall_services () {
		$this->CI->load->model("Services_model");
		$this->CI->Services_model->delete_template_by_gid('banner_template');
		$this->CI->Services_model->delete_service_by_gid('banner_service');
	}

	public function deinstall_cronjob () {
		$this->CI->load->model('Cronjob_model');
		$cron_data = array();
		$cron_data["where"]["module"] = "banners";
		$this->CI->Cronjob_model->delete_cron_by_param($cron_data);
	}

	function _arbitrary_deinstalling(){
	}

	public function add_banners_to_site(){
		$this->CI->load->model('banners/models/Banner_group_model');
		$this->CI->load->model('Banners_model');
		$all_groups_ids = array_keys($this->CI->Banner_group_model->get_all_groups_key_id());
		$banners = array(
			array(
				'alt_text' => 'PG Dating Pro',
				'approve' => 1,
				'banner_image' => '55286cb3e5.jpg',
				'banner_place_id' => 3,
				'banner_type' => 2,
				'decline_reason' => '',
				'expiration_date' => '0000-00-00 00:00:00',
				'html' => '
					<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
					<!-- Demo DP 185*75 -->
					<ins class="adsbygoogle"
						 style="display:inline-block;width:185px;height:75px"
						 data-ad-client="ca-pub-7776361119486928"
						 data-ad-slot="7694070741"></ins>
					<script>
					try{(adsbygoogle = window.adsbygoogle || []).push({});}catch(e){}
					</script>				
				',
				'link' => 'http://demo.datingpro.com/dating/',
				'name' => 'Left banner',
				'new_window' => 1,
				'is_admin' => 1,
				'number_of_clicks' => 0,
				'number_of_views' => 0,
				'status' => 1,
				'user_id' => 0,
				'stat_clicks' => 0,
				'stat_views' => 0,
				'user_activate_info' => '',
				'banner_groups' => $all_groups_ids,
			),
			array(
				'alt_text' => 'PG Dating Pro',
				'approve' => 1,
				'banner_image' => 'e9116ceea8.jpg',
				'banner_place_id' => 1,
				'banner_type' => 2,
				'decline_reason' => '',
				'expiration_date' => '0000-00-00 00:00:00',
				'html' => '
					<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
					<!-- Demo DP 728*90 -->
					<ins class="adsbygoogle"
						 style="display:inline-block;width:728px;height:90px"
						 data-ad-client="ca-pub-7776361119486928"
						 data-ad-slot="9450005541"></ins>
					<script>
					try{(adsbygoogle = window.adsbygoogle || []).push({});}catch(e){}
					</script>
				',
				'link' => 'http://demo.datingpro.com/dating/',
				'name' => 'Bottom banner',
				'new_window' => 1,
				'is_admin' => 1,
				'number_of_clicks' => 0,
				'number_of_views' => 0,
				'status' => 1,
				'user_id' => 0,
				'stat_clicks' => 1,
				'stat_views' => 846,
				'user_activate_info' => '',
				'banner_groups' => $all_groups_ids,
			),
			array(
				'alt_text' => 'PG Dating Pro',
				'approve' => 1,
				'banner_image' => 'b4ab5556ad.jpg',
				'banner_place_id' => 2,
				'banner_type' => 2,
				'decline_reason' => '',
				'expiration_date' => '0000-00-00 00:00:00',
				'html' => '
					<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
					<!-- Demo DP 185*155 -->
					<ins class="adsbygoogle"
						 style="display:inline-block;width:185px;height:155px"
						 data-ad-client="ca-pub-7776361119486928"
						 data-ad-slot="1787137940"></ins>
					<script>
					try{(adsbygoogle = window.adsbygoogle || []).push({});}catch(e){}
					</script>
				',
				'link' => 'http://demo.datingpro.com/dating/',
				'name' => 'Big left banner',
				'new_window' => 1,
				'is_admin' => 1,
				'number_of_clicks' => 0,
				'number_of_views' => 0,
				'status' => 1,
				'user_id' => 0,
				'stat_clicks' => 0,
				'stat_views' => 0,
				'user_activate_info' => '',
				'banner_groups' => $all_groups_ids,
			),
			array(
				'alt_text' => 'PG Dating Pro',
				'approve' => 1,
				'banner_image' => '55286cb3e6.jpg',
				'banner_place_id' => 6,
				'banner_type' => 2,
				'decline_reason' => '',
				'expiration_date' => '0000-00-00 00:00:00',
				'html' => '
					<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
					<!-- Demo DP 320*250 -->
					<ins class="adsbygoogle"
						 style="display:inline-block;width:320px;height:75px"
						 data-ad-client="ca-pub-7776361119486928"
						 data-ad-slot="1647537143"></ins>
					<script>
					try{(adsbygoogle = window.adsbygoogle || []).push({});}catch(e){}
					</script>
				',
				'link' => 'http://demo.datingpro.com/dating/',
				'name' => 'Right banner',
				'new_window' => 1,
				'is_admin' => 1,
				'number_of_clicks' => 0,
				'number_of_views' => 0,
				'status' => 1,
				'user_id' => 0,
				'stat_clicks' => 0,
				'stat_views' => 0,
				'user_activate_info' => '',
				'banner_groups' => $all_groups_ids,
			),
			array(
				'alt_text' => 'PG Dating Pro',
				'approve' => 1,
				'banner_image' => 'b4ab5556ae.jpg',
				'banner_place_id' => 5,
				'banner_type' => 2,
				'decline_reason' => '',
				'expiration_date' => '0000-00-00 00:00:00',
				'html' => '
					<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
					<!-- Demo DP 320*250 -->
					<ins class="adsbygoogle"
						 style="display:inline-block;width:320px;height:75px"
						 data-ad-client="ca-pub-7776361119486928"
						 data-ad-slot="1647537143"></ins>
					<script>
					try{(adsbygoogle = window.adsbygoogle || []).push({});}catch(e){}
					</script>
				',
				'link' => 'http://demo.datingpro.com/dating/',
				'name' => 'Big right banner',
				'new_window' => 1,
				'is_admin' => 1,
				'number_of_clicks' => 0,
				'number_of_views' => 0,
				'status' => 1,
				'user_id' => 0,
				'stat_clicks' => 0,
				'stat_views' => 0,
				'user_activate_info' => '',
				'banner_groups' => $all_groups_ids,
			)
		);
		foreach($banners as $banner){
			$this->CI->Banners_model->save(null, $banner);
		}
		return true;
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
		$langs_file = $this->CI->Install_model->language_file_read('banners', 'moderation', $langs_ids);

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
}
