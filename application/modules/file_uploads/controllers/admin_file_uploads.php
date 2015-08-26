<?php
/**
* File uploads admin side controller
* 
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/
Class Admin_File_uploads extends Controller {

	private $allow_config_add = false;

	function Admin_File_uploads() {
		parent::Controller();
		$this->load->model('Menu_model');
		$this->Menu_model->set_menu_active_item('admin_menu', 'system-items');

		$this->load->model('file_uploads/models/File_uploads_config_model');
	}

	function index() {
		$this->configs();
	}

	function configs() {
		$this->template_lite->assign('configs', $this->File_uploads_config_model->get_config_list());
		$this->template_lite->assign('allow_config_add', $this->allow_config_add);

		$this->Menu_model->set_menu_active_item('admin_file_uploads_menu', 'configs_list_item');
		$this->system_messages->set_data('header', l('admin_header_configs_list', 'file_uploads'));
		$this->template_lite->view('list_settings');
	}

	function config_edit($config_id = null) {
		if ($config_id) {
			$data = $this->File_uploads_config_model->get_config_by_id($config_id);
		} else {
			$data = array();
		}

		if ($this->input->post('btn_save')) {
			$post_data = array(
				"name" => $this->input->post('name', true),
				"gid" => $this->input->post('gid', true),
				"max_size" => $this->input->post('max_size', true),
				"name_format" => $this->input->post('name_format', true),
				"file_formats" => $this->input->post('file_formats', true),
			);
			$validate_data = $this->File_uploads_config_model->validate_config($config_id, $post_data);
			if (!empty($validate_data["errors"])) {
				$this->system_messages->add_message('error', $validate_data["errors"]);
				$data = array_merge($data, $validate_data["data"]);
			} else {
				$data = $validate_data["data"];
				$this->File_uploads_config_model->save_config($config_id, $data);
				$this->system_messages->add_message('success', ($config_id) ? l('success_updated_config', 'file_uploads') : l('success_added_config', 'file_uploads'));
				$url = site_url('admin/file_uploads/configs');
				redirect($url);
			}
		}

		$this->template_lite->assign('data', $this->File_uploads_config_model->format_config($data));
		$this->template_lite->assign('formats', $this->File_uploads_config_model->file_categories);
		$this->template_lite->assign('lang_name_format', ld('upload_name_format', 'file_uploads'));
		$this->system_messages->set_data('header', l('admin_header_configs_list', 'file_uploads'));
		$this->template_lite->view('edit_settings');
	}

	function config_delete($config_id) {
		$this->File_uploads_config_model->delete_config($config_id);
		$url = site_url('admin/file_uploads/configs');
		$this->system_messages->add_message('success', l('success_deleted_config', 'file_uploads'));
		redirect($url);
	}
}
