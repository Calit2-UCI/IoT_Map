<?php
/**
* Video Uploads admin side controller
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

Class Admin_Video_Uploads extends Controller
{
	/**
	 * link to CodeIgniter object
	 * @var object
	 */

	/**
	 * Constructor
	 */
	function Admin_Video_Uploads()
	{
		parent::Controller();
		$this->load->model('Menu_model');
		$this->Menu_model->set_menu_active_item('admin_menu', 'system-items');
	}

	function index(){
		$this->configs();
	}

	function configs(){
		$this->load->model('video_uploads/models/Video_uploads_config_model');
		$this->template_lite->assign('configs', $this->Video_uploads_config_model->get_config_list());

		$this->Menu_model->set_menu_active_item('admin_uploads_menu', 'configs_list_item');
		$this->system_messages->set_data('header', l('admin_header_configs_list', 'video_uploads'));
		$this->template_lite->view('list_settings');
	}

	function config_edit($config_id){
		$this->load->model('video_uploads/models/Video_uploads_config_model');
		$data = $this->Video_uploads_config_model->get_config_by_id($config_id);

		$this->load->model('video_uploads/models/Video_uploads_settings_model');
		$settings = $this->Video_uploads_settings_model->get_settings();

		if($this->input->post('btn_save')){
			$post_data = array(
				"name" => $this->input->post('name', true),
				"max_size" => $this->input->post('max_size', true),
				"upload_type" => $this->input->post('upload_type', true),
				"file_formats" => $this->input->post('file_formats', true),
				"use_convert" => $this->input->post('use_convert', true),
				"local_settings" => $this->input->post('local_settings', true),
				"youtube_settings" => $this->input->post('youtube_settings', true),
				"use_thumbs" => $this->input->post('use_thumbs', true),
				"thumbs_settings" => $this->input->post('thumbs_settings', true),
				"default_img" => $_FILES['default_img'],
			);

			$validate_data = $this->Video_uploads_config_model->validate_config($config_id, $post_data);

			if(!empty($validate_data["errors"])){
				$this->system_messages->add_message('error', $validate_data["errors"]);
				$validate_data["data"] = $this->Video_uploads_config_model->format_config($validate_data["data"]);
				$data = array_merge($data, $validate_data["data"]);
			}else{

				if($this->input->post('default_img_delete', true)){
					$this->load->model('Video_uploads_model');
					$this->Video_uploads_model->delete_thumbs($data["default_img"], $this->Video_uploads_config_model->default_path, $data['thumbs_settings']);
					$validate_data["data"]["default_img"] = "";
				}

				$this->Video_uploads_config_model->save_config($config_id, $validate_data["data"], 'default_img');
				$this->system_messages->add_message('success', l('success_updated_config', 'video_uploads'));
				$url = site_url()."admin/video_uploads/index";
				redirect($url);
			}
		}

		$this->template_lite->assign('data', $data);
		$this->template_lite->assign('settings', $settings);

		$this->template_lite->assign('formats', $this->Video_uploads_config_model->file_formats);
		$this->system_messages->set_data('header', l('admin_header_config_form', 'video_uploads'));
		$this->template_lite->view('edit_settings');
	}


	///// local converting settings

	public function system_settings(){
		$this->load->model('video_uploads/models/Video_uploads_settings_model');

		if($this->input->post('btn_save')){
			$this->Video_uploads_settings_model->set_settings('ffmpeg_path', trim(strip_tags($this->input->post('ffmpeg_path', true))));
			$this->Video_uploads_settings_model->set_settings('mencoder_path', trim(strip_tags($this->input->post('mencoder_path', true))));
			$this->Video_uploads_settings_model->set_settings('flvtool2_path', trim(strip_tags($this->input->post('flvtool2_path', true))));
			$this->Video_uploads_settings_model->set_settings('mplayer_path', trim(strip_tags($this->input->post('mplayer_path', true))));
			$this->Video_uploads_settings_model->reculc_permission_settings();
			$this->Video_uploads_settings_model->write_settings();
			$this->system_messages->add_message('success', l('success_update_system_settings', 'video_uploads'));
			redirect(site_url()."admin/video_uploads/system_settings");
		}

		//// check  shell_exec || ffmpeg-php changing
		if(!$this->Video_uploads_settings_model->is_shell_exec_exist()){
			$this->Video_uploads_settings_model->reculc_permission_settings();
			$this->Video_uploads_settings_model->write_settings();
		}

		$settings = $this->Video_uploads_settings_model->get_settings();
		$this->template_lite->assign('settings', $settings);

		if($settings["local_converting_video_type"]){
			$coder_path = 	$settings[$settings["local_converting_video_type"]."_path"];
			$get_codecs_method = "get_".$settings["local_converting_video_type"]."_codecs";
			$get_version_method = "get_".$settings["local_converting_video_type"]."_codecs";
			if($coder_path){
				$codecs = $this->Video_uploads_settings_model->$get_codecs_method();
				$this->template_lite->assign('codecs', $codecs);
			}
		}
		$versions = array();
		if($settings["ffmpeg_path"]){
			$versions["ffmpeg"] = $this->Video_uploads_settings_model->get_ffmpeg_version();
		}

		if($settings["mencoder_path"]){
			$versions["mencoder"] = $this->Video_uploads_settings_model->get_mencoder_version();
		}

		if($settings["flvtool2_path"]){
			$versions["flvtool2"] = $this->Video_uploads_settings_model->get_flvtool2_version();
		}

		if($settings["mplayer_path"]){
			$versions["mplayer"] = $this->Video_uploads_settings_model->get_mplayer_version();
		}

		$this->template_lite->assign('versions', $versions);

		//// php ini settings
		$php_ini['post_max_size'] = ini_get('post_max_size');
		$php_ini['upload_max_filesize'] = ini_get('upload_max_filesize');
		$php_ini['max_size_notice'] = str_replace('[max_size]', $php_ini['upload_max_filesize'], l('upload_max_size_notice', 'video_uploads'));

		$this->template_lite->assign('php_ini', $php_ini);

		$this->Menu_model->set_menu_active_item('admin_uploads_menu', 'system_list_itemm');
		$this->system_messages->set_data('header', l('admin_header_local_settings_list', 'video_uploads'));
		$this->template_lite->view('system_settings');
	}

	public function system_settings_reset(){
		$this->load->model('video_uploads/models/Video_uploads_settings_model');
		$this->Video_uploads_settings_model->reculc_settings();
		$this->Video_uploads_settings_model->write_settings();

		$this->system_messages->add_message('success', l('success_reset_system_settings', 'video_uploads'));
		redirect(site_url()."admin/video_uploads/system_settings");
	}

	public function youtube_settings(){
		$this->load->model('video_uploads/models/Video_uploads_youtube_model');

		if($this->input->post('btn_save')){
			$this->Video_uploads_youtube_model->set_settings('youtube_converting_login', trim(strip_tags($this->input->post('youtube_converting_login', true))));
			$this->Video_uploads_youtube_model->set_settings('youtube_converting_password', trim(strip_tags($this->input->post('youtube_converting_password', true))));
			$this->Video_uploads_youtube_model->set_settings('youtube_converting_developer_key', trim(strip_tags($this->input->post('youtube_converting_developer_key', true))));
			$this->Video_uploads_youtube_model->set_settings('youtube_converting_source', trim(strip_tags($this->input->post('youtube_converting_source', true))));
			$this->Video_uploads_youtube_model->reculc_permission_settings();
			$this->Video_uploads_youtube_model->write_settings();
			$this->system_messages->add_message('success', l('success_update_youtube_settings', 'video_uploads'));
			redirect(site_url()."admin/video_uploads/youtube_settings");
		}

		$settings = $this->Video_uploads_youtube_model->get_settings();
		$this->template_lite->assign('settings', $settings);

		$this->Menu_model->set_menu_active_item('admin_uploads_menu', 'youtube_settings_item');
		$this->system_messages->set_data('header', l('admin_header_youtube_settings_list', 'video_uploads'));
		$this->template_lite->view('youtube_settings');
	}

	public function youtube_settings_check(){
		$this->load->model('video_uploads/models/Video_uploads_youtube_model');
		$ret = $this->Video_uploads_youtube_model->youtube_auth();

		if($ret["error"]){
			$this->system_messages->add_message('error', $ret["error"]);
		}else{
			$this->system_messages->add_message('success', l('success_youtube_auth', 'video_uploads'));
		}
		redirect(site_url()."admin/video_uploads/youtube_settings");
	}





}
