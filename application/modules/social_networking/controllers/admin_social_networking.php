<?php
/**
* Social networking admin side controller
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

Class Admin_social_networking extends Controller {

	private $allow_service_add = false;
	private $allow_service_edit = false;
	private $allow_service_delete = false;

	function Admin_social_networking() {
		parent::Controller();
		$this->load->model('Menu_model');
		$this->Menu_model->set_menu_active_item('admin_menu', 'system-items');
	}

	function services() {
		$this->load->model('social_networking/models/Social_networking_services_model');
		$this->template_lite->assign('services', $this->Social_networking_services_model->get_services_list());

		$this->template_lite->assign('allow_add', $this->allow_service_add);
		$this->template_lite->assign('allow_edit', $this->allow_service_edit);
		$this->template_lite->assign('allow_delete', $this->allow_service_delete);

		$this->Menu_model->set_menu_active_item('admin_social_networking_menu', 'services_list_item');
		$this->system_messages->set_data('header', l('admin_header_services_list', 'social_networking'));
		$this->template_lite->view('list_services');
	}

	function service_edit($service_id = null) {
		$this->load->model('social_networking/models/Social_networking_services_model');
		if ($service_id) {
			$data = $this->Social_networking_services_model->get_service_by_id($service_id);
		} else {
			$data = array();
		}

		if ($this->input->post('btn_save')) {
			$post_data = array(
				"name" => $this->input->post('name', true),
				"gid" => $this->input->post('gid', true),
				"authorize_url" => $this->input->post('authorize_url', true),
				"access_key_url" => $this->input->post('access_key_url', true),
				"app_enabled" => $this->input->post('app_enabled', true),
				"oauth_enabled" => $this->input->post('oauth_enabled', true),
			);
			$validate_data = $this->Social_networking_services_model->validate_service($post_data);
			if (!empty($validate_data["errors"])) {
				$this->system_messages->add_message('error', $validate_data["errors"]);
				$data = array_merge($data, $validate_data["data"]);
			} else {
				$data = $validate_data["data"];

				$result = $this->Social_networking_services_model->save_service($service_id, $data, $langs);
				if (isset($result['errors']) && is_array($result['errors']) && count($result['errors']) > 0) {
					$this->system_messages->add_message('error', $result['errors']);
					redirect(site_url('admin/social_networking/service_edit/' . $service_id));
				} else {
					$this->system_messages->add_message('success', ($service_id) ? l('success_updated_service', 'oauth') : l('success_added_service', 'oauth'));
					redirect(site_url('admin/social_networking/services'));
				}
			}
		}

		$this->system_messages->set_data('header', $data['name']);

		$this->template_lite->assign('data', $data);
		$this->template_lite->view('edit_service');
	}

	function service_delete($service_id = null) {
		$this->load->model('social_networking/models/Social_networking_services_model');
		$this->Social_networking_services_model->delete_service($service_id);
		$url = site_url('admin/social_networking/services');
		$this->system_messages->add_message('success', l('success_deleted_service', 'social_networking'));
		redirect($url);
	}

	function service_active($service_id = null, $status = '1') {
		$this->load->model('social_networking/models/Social_networking_services_model');
		$this->load->model('social_networking/models/Social_networking_connections_model');
		$url = site_url('admin/social_networking/services');
		if ($service_id) {
			$data = $this->Social_networking_services_model->get_service_by_id($service_id);
		}
		if (!empty($data)) {
			$data['status'] = floor($status);
			$this->Social_networking_services_model->save_service($service_id, $data);
			$this->system_messages->add_message('success', l('success_updated_service', 'social_networking'));
		}
		redirect($url);
	}

	function oauth_active($service_id = null, $status = '1') {
		$this->load->model('social_networking/models/Social_networking_services_model');
		$this->load->model('social_networking/models/Social_networking_connections_model');
		$url = site_url('admin/social_networking/services');
		if ($service_id) {
			$data = $this->Social_networking_services_model->get_service_by_id($service_id);
		}
		if (!empty($data)) {
			if ($status == 1) {
				if (!$this->Social_networking_services_model->enabled) {
					$this->system_messages->add_message('error', l('no_curl', 'social_networking'));
					redirect(site_url('admin/social_networking/services/'));
				}
				if ($data['oauth_version'] == 2) {
					// Проверка подключения
					$result = $this->Social_networking_connections_model->_check_oauth2_connection($data, site_url('admin/social_networking/oauth_active/' . $service_id . '/1/'));
				} else {
					$result = $this->Social_networking_connections_model->_check_oauth_connection($data, site_url('admin/social_networking/oauth_active/' . $service_id . '/1/'));
				}
				// Вывод ошибок
				if (isset($result['error']) && $result['error']) {
					$this->system_messages->add_message('error', $result['error']);
					redirect($url);
				}
			}
			$data['oauth_status'] = floor($status);
			$this->Social_networking_services_model->save_service($service_id, $data);
			$this->system_messages->add_message('success', l('success_updated_service', 'social_networking'));
		}
		redirect($url);
	}

	function application($service_id = null) {
		$this->load->model('social_networking/models/Social_networking_services_model');
		$data = $this->Social_networking_services_model->get_service_by_id($service_id);
		if ($data) {
			if ($this->input->post('btn_save')) {
				$post_data = (array) $data;
				$post_data["app_key"] = $this->input->post('app_key', true);
				$post_data["app_secret"] = $this->input->post('app_secret', true);
				$data = $post_data + $data;
				$validate_data = $this->Social_networking_services_model->validate_service($data);
				if (!empty($validate_data["errors"])) {
					$this->system_messages->add_message('error', $validate_data["errors"]);
					$data = array_merge($data, $validate_data["data"]);
				} else {
					$data = $validate_data["data"];
					$data['oauth_status'] = 0;
					$this->Social_networking_services_model->save_service($service_id, $data);

					$this->system_messages->add_message('success', l('success_updated_application', 'social_networking'));
					$url = site_url('admin/social_networking/services/');
					redirect($url);
				}
			}
			$this->system_messages->set_data('header', $data['name']);

			$this->template_lite->assign('data', $data);
			$this->template_lite->view('edit_application');
		} else
			redirect(site_url('admin/social_networking/services'));
	}

	public function pages() {
		$this->load->model('social_networking/models/Social_networking_pages_model');
		$this->template_lite->assign('pages', $this->Social_networking_pages_model->get_pages_list());

		$this->template_lite->assign('allow_add', $this->allow_page_add);
		$this->template_lite->assign('allow_edit', $this->allow_page_edit);
		$this->template_lite->assign('allow_delete', $this->allow_page_delete);

		$this->Menu_model->set_menu_active_item('admin_social_networking_menu', 'services_list_item');
		$this->system_messages->set_data('header', l('admin_header_pages_list', 'social_networking'));
		$this->template_lite->view('list_pages');
	}

	function widgets($page_id = null) {
		$this->load->model('social_networking/models/Social_networking_pages_model');
		$this->load->model('social_networking/models/Social_networking_services_model');
		$this->load->model('social_networking/models/Social_networking_widgets_model');
		$data = $this->Social_networking_pages_model->get_page_by_id($page_id);
		if ($data) {
			if ($this->input->post('btn_save')) {
				$post_data = array(
					"like" => $this->input->post('like', true),
					"share" => $this->input->post('share', true),
					"comments" => $this->input->post('comments', true),
				);
				$data['data'] = $post_data;
				$validate_data = $this->Social_networking_pages_model->validate_page($page_id, $data);
				if (!empty($validate_data["errors"])) {
					$this->system_messages->add_message('error', $validate_data["errors"]);
					$data = array_merge($data, $validate_data["data"]);
				} else {
					$data = $validate_data["data"];
					$this->Social_networking_pages_model->save_page($page_id, $data);
					$this->system_messages->add_message('success', l('success_updated_widgets', 'social_networking'));
					$url = site_url('admin/social_networking/pages/');
					redirect($url);
				}
			}
			$services = $this->Social_networking_services_model->get_services_list(array('id' => 'ASC'));
			$this->template_lite->assign('services', $services);
			$widgets_actions = $this->Social_networking_widgets_model->get_widgets_actions($services);
			$this->template_lite->assign('widgets_actions', $widgets_actions);
			$this->system_messages->set_data('header', $data['name']);
			$this->template_lite->assign('data', $data);
			$this->template_lite->view('edit_widgets');
		} else
			redirect(site_url('admin/social_networking/pages'));
	}

}
