<?php
/**
* Video uploads install model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

class Video_uploads_install_model extends Model
{
	var $CI;
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
								'video_menu_item' => array('action' => 'create', 'link' => 'admin/video_uploads', 'status' => 1, 'sorter' => 3)
							)
						)
					)
				)
			)
		),
		'admin_video_menu' => array(
			'action' => 'create',
			'name' => 'Video uploads section menu',
			'items' => array(
				'configs_list_item' => array('action' => 'create', 'link' => 'admin/video_uploads', 'status' => 1),
				'system_list_item' => array('action' => 'create', 'link' => 'admin/video_uploads/system_settings', 'status' => 1),
				'youtube_settings_item' => array('action' => 'create', 'link' => 'admin/video_uploads/youtube_settings', 'status' => 1)
			)
		)
	);

	/**
	 * Constructor
	 *
	 * @return Install object
	 */
	function Video_uploads_install_model()
	{
		parent::Model();
		$this->CI = & get_instance();
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
		$langs_file = $this->CI->Install_model->language_file_read('video_uploads', 'menu', $langs_ids);

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
		return array( "menu" => $return );
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

	public function install_video_uploads() {
		//// reculc system settings
		$this->CI->load->model('video_uploads/models/Video_uploads_settings_model');
		$this->CI->Video_uploads_settings_model->reculc_settings();
		$this->CI->Video_uploads_settings_model->write_settings();

		if ((extension_loaded("fileinfo")) && function_exists('finfo_open')) {
			$this->CI->pg_module->set_module_config('video_uploads', 'use_fileinfo', TRUE);
		}
	}

	public function install_cronjob() {
		///// add cronjob ()
		$this->CI->load->model('Cronjob_model');
		$cron_data = array(
			"name" => "Video processing",
			"module" => "video_uploads",
			"model" => "Video_uploads_process_model",
			"method" => "cron_processing_method",
			"cron_tab" => "*/5 * * * *",
			"status" => "1"
		);
		$this->CI->Cronjob_model->save_cron(null, $cron_data);

		$cron_data = array(
			"name" => "Video image cropping",
			"module" => "video_uploads",
			"model" => "Video_uploads_process_model",
			"method" => "cron_images_method",
			"cron_tab" => "*/5 * * * *",
			"status" => "1"
		);
		$this->CI->Cronjob_model->save_cron(null, $cron_data);

		$cron_data = array(
			"name" => "Video check youtube status",
			"module" => "video_uploads",
			"model" => "Video_uploads_process_model",
			"method" => "cron_waiting_method",
			"cron_tab" => "*/5 * * * *",
			"status" => "1"
		);
		$this->CI->Cronjob_model->save_cron(null, $cron_data);
	}

	function _arbitrary_installing(){

	}

	public function deinstall_cronjob() {
		$this->CI->load->model('Cronjob_model');
		$cron_data = array();
		$cron_data["where"]["module"] = "video_uploads";
		$this->CI->Cronjob_model->delete_cron_by_param($cron_data);
	}

	function _arbitrary_deinstalling(){

	}
}
