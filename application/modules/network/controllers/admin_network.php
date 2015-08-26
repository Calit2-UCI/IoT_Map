<?php

use Pg\Modules\Network\Models\Network_model;

/**
 * Network admin side controller
 *
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * */
Class Admin_Network extends Controller {

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::Controller();
		$this->load->model('Menu_model');
		$this->load->model('Network_model');
		$this->Menu_model->set_menu_active_item('admin_menu', 'system-items');
	}

	private function _save_data() {
		$post_data = filter_input_array(INPUT_POST, array(
			'domain' => array(
				'filter' => FILTER_SANITIZE_STRING,
				'flags' => FILTER_FLAG_EMPTY_STRING_NULL,),
			'key' => array(
				'filter' => FILTER_SANITIZE_STRING,
				'flags' => FILTER_FLAG_EMPTY_STRING_NULL,),
            'is_upload_photos' => array(
                'filter' => FILTER_VALIDATE_INT,
                'flags'  => FILTER_NULL_ON_FAILURE, ),
			'site_type' => array(
				'filter' => FILTER_SANITIZE_STRING, 
				'flags' => FILTER_REQUIRE_ARRAY,),
			'user_type' => array(
				'filter' => FILTER_SANITIZE_STRING, 
				'flags' => FILTER_REQUIRE_ARRAY,),
			'orientation' => array(
				'filter' => FILTER_SANITIZE_STRING, 
				'flags' => FILTER_REQUIRE_ARRAY,),
			'lang' => array(
				'filter' => FILTER_SANITIZE_STRING, 
				'flags' => FILTER_REQUIRE_ARRAY,),
			'land' => array(
				'filter' => FILTER_SANITIZE_STRING, 
				'flags' => FILTER_REQUIRE_ARRAY,),
			'country_code' => array(
				'filter' => FILTER_SANITIZE_STRING, 
				'flags' => FILTER_REQUIRE_ARRAY,),
			'min_age' => array(
				'filter'    => FILTER_VALIDATE_INT,
				'flags'		=> FILTER_NULL_ON_FAILURE,),
			'max_age' => array(
				'filter'    => FILTER_VALIDATE_INT,
				'flags'		=> FILTER_NULL_ON_FAILURE,),
		));
		$this->load->model('network/models/Network_users_model');
		
		$errors = $this->Network_users_model->validate_settings($post_data);
		if (!empty($errors)) {
			$this->system_messages->add_message('error', $errors);
		} else {
            if (isset($post_data["is_upload_photos"])) {
                $post_data["is_upload_photos"] = $post_data["is_upload_photos"] ? 1 : 0;
            }
         
			$data = $this->Network_model->set_config($post_data);
		    if (false === $data) {
				$this->system_messages->add_message('error', l('admin_error_save', Network_model::MODULE_GID));
			} else {
				$this->system_messages->add_message('success', l('admin_success_save', Network_model::MODULE_GID));
			}  
		}
		return $post_data;
	}

	private function _format_selected_options($form_params, $selected) {
		$form_params_selected = array();
		foreach ($form_params as $params) {
			foreach (array_keys($params) as $param) {
				if (isset($selected[$param])) {
					$form_params_selected[$param] = $selected[$param];
				}
			}
		}
		return $form_params_selected;
	}

	/**
	 * Index action
	 */
	public function index() {
        $requirements = $this->Network_model->validateRequirements();
        //if (!$requirements['result']) {
            $this->template_lite->assign('requirements', $requirements['data']);
        //}
        
		$this->load->model('network/models/Network_users_model');
		if ($this->input->post('btn-save')) {
			$data = $this->_save_data();
		} else {
			$data = $this->Network_users_model->get_config();
		}
		$is_started = $this->Network_model->is_started();
		if (!empty($data['domain']) && !empty($data['key'])) {
			$data_is_correct = $this->Network_model->check_auth_data($data['domain'], $data['key']);
		} else {
			$data_is_correct = false;
		}
		$both_clients_started = $is_started[Network_model::CLIENT_FAST] && $is_started[Network_model::CLIENT_SLOW];
		if (!$data_is_correct && $both_clients_started) {
			$this->Network_model->stop();
		}
		$form_fields = $this->Network_users_model->get_form_fields($this->pg_language->current_lang_id);
		$form_limits = $this->Network_users_model->get_form_limits();
		$selected_options = $this->_format_selected_options($form_fields, $data);

		$this->system_messages->set_data('header', l('admin_header_network', Network_model::MODULE_GID));
		$back_url = site_url() . 'admin/start/menu/system-items';
		$this->pg_theme->add_js('admin-network.js', Network_model::MODULE_GID);
		$this->template_lite->assign('age_min', $this->pg_module->get_module_config('users', 'age_min'));
		$this->template_lite->assign('age_max', $this->pg_module->get_module_config('users', 'age_max'));
		$this->template_lite->assign('form_fields', $form_fields);
		$this->template_lite->assign('selected_options', $selected_options);
		$this->template_lite->assign('form_limits', $form_limits);
		$this->template_lite->assign('back_url', $back_url);
		$this->template_lite->assign('data_is_correct', $data_is_correct);
		$this->template_lite->assign('clients_started', $both_clients_started);
		$this->template_lite->assign('data', $data);
		// Logging should be enabled with treshhold "debug" or "info". 
		$this->template_lite->assign('net_show_log', false);
		$this->template_lite->view('index');
	}

	//TODO: ajax?
	public function start() {
		$result = $this->Network_model->start();

        $errors = array();
  
        if (!$result[Network_model::CLIENT_SLOW]) {
            $errors[] = l('error_slow_service_start', 'network');
        }
        
        if (!$result[Network_model::CLIENT_FAST]) {
            $errors[] = l('error_fast_service_start', 'network');
        }
        
        if (!empty($errors)) {
            $this->system_messages->add_message('error', $errors);
        } else {
            $success = l('success_services_start', 'network');
            $this->system_messages->add_message('success', $success); 
        }
	
		redirect(site_url() . 'admin/network');
	}

	//TODO: ajax?
	public function stop() {
		$result = $this->Network_model->stop();
		
        if (!$result[Network_model::CLIENT_SLOW]) {
            $errors[] = l('error_slow_service_stop', 'network');
        }
        
        if (!$result[Network_model::CLIENT_FAST]) {
            $errors[] = l('error_fast_service_stop', 'network');
        }
        
        if (!empty($errors)) {
            $this->system_messages->add_message('error', $errors);
        } else {
            $success = l('success_services_stop', 'network');
            $this->system_messages->add_message('success', $success); 
        }
        
		if (!$result) {
			// TODO: Сделать чё-нибудь
		}
		redirect(site_url() . 'admin/network');
	}

	public function process_temp() {
		$this->load->model('network/models/Network_users_model');
		$this->Network_users_model->process_temp();
	}

	public function ajax_get_status() {
		$status = $this->Network_model->get_status(20);
		echo json_encode($status);
	}

}
