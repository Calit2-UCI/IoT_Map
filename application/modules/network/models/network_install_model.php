<?php

namespace Pg\Modules\Network\Models;

use Pg\Modules\Network\Models\Network_model;

/**
 * Network install model
 *
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * */
class Network_install_model extends Network_model {

	protected $CI;
	public $_menu = array(
		'admin_menu' => array(
			'action' => 'none',
			'items' => array(
				'settings_items' => array(
					'action' => 'none',
					'items' => array(
						'system-items' => array(
							'action' => 'none',
							'items' => array(
								'network_menu_item' => array(
									'action' => 'create',
									'link' => 'admin/network/',
									'status' => 1,
									'sorter' => 12
								),
							),
						),
					),
				),
			),
		),
	);

	/**
	 * Constructor
	 *
	 * @return Install object
	 */
	public function __construct() {
		parent::__construct();
		$this->CI = & get_instance();
		$this->CI->load->model('Install_model');
	}

	public function install_menu() {
		$this->CI->load->helper('menu'); 
		foreach ($this->_menu as $gid => $menu_data) {
			$this->_menu[$gid]['id'] = linked_install_set_menu($gid, $menu_data['action'], $menu_data['name']);
			linked_install_process_menu_items($this->_menu, 'create', $gid, 0, $this->_menu[$gid]['items']);
		}
	}

	public function install_menu_lang_update($langs_ids = null) {
		if (empty($langs_ids)) {
			return false;
		}
		$langs_file = $this->CI->Install_model->language_file_read(self::MODULE_GID, 'menu', $langs_ids);
		if (!$langs_file) {
			log_message('info', 'Empty menu langs data');
			return false;
		}
		$this->CI->load->helper('menu');
		foreach (array_keys($this->_menu) as $gid) {
			linked_install_process_menu_items($this->_menu, 'update', $gid, 0, $this->_menu[$gid]['items'], $gid, $langs_file);
		}
		return true;
	}

	public function install_menu_lang_export($langs_ids) {
		if (empty($langs_ids)) {
			return false;
		}
		$this->CI->load->helper('menu');

		$return = array();
		foreach (array_keys($this->_menu) as $gid) {
			$temp = linked_install_process_menu_items($this->_menu, 'export', $gid, 0, $this->_menu[$gid]['items'], $gid, $langs_ids);
			$return = array_merge($return, $temp);
		}
		return array('menu' => $return);
	}

	public function deinstall_menu() {
		$this->CI->load->helper('menu');
		foreach ($this->_menu as $gid => $menu_data) {
			if ($menu_data['action'] == 'create') {
				linked_install_set_menu($gid, 'delete');
			} else {
				linked_install_delete_menu_items($gid, $this->_menu[$gid]['items']);
			}
		}
	}

	public function install_field_editor(){
		$this->CI->load->model('Users_model');
		$this->CI->load->model('Field_editor_model');
		$this->CI->Field_editor_model->initialize('users');
		include MODULEPATH . 'network/install/user_fields_data.php';
		$this->CI->Field_editor_model->import_type_structure($this->CI->Users_model->form_editor_type, $fe_sections, $fe_fields, $fe_forms);
	}

	public function install_field_editor_lang_update() {
		$langs_file = $this->CI->Install_model->language_file_read(Network_model::MODULE_GID, 'field_editor');
		if(!$langs_file) {
			log_message('info', 'Empty field_editor langs data');
			return false;
		}
		$this->CI->load->model('Users_model');
		$this->CI->load->model('Field_editor_model');
		$this->CI->Field_editor_model->initialize($this->CI->Users_model->form_editor_type);
		include MODULEPATH . 'network/install/user_fields_data.php';
		$this->CI->Field_editor_model->update_sections_langs($fe_sections, $langs_file);
		$this->CI->Field_editor_model->update_fields_langs($this->CI->Users_model->form_editor_type, $fe_fields, $langs_file);
		return true;
	}

	public function install_field_editor_lang_export($langs_ids = null) {
		$this->CI->load->model('Users_model');
		$this->CI->load->model('Field_editor_model');
		$this->CI->Field_editor_model->initialize($this->CI->Users_model->form_editor_type);
		list($fe_sections, $fe_fields, $fe_forms) = $this->CI->Field_editor_model->export_type_structure($this->CI->Users_model->form_editor_type, 'application/modules/network/install/user_fields_data.php');
		$sections = $this->CI->Field_editor_model->export_sections_langs($fe_sections, $langs_ids);
		$fields = $this->CI->Field_editor_model->export_fields_langs($this->CI->Users_model->form_editor_type, $fe_fields, $langs_ids);

		return array('field_editor' => array_merge($sections, $fields));
	}

	public function deinstall_field_editor() {
		$this->CI->load->model('Users_model');
		$this->CI->load->model('Field_editor_model');
		$this->CI->load->model('field_editor/models/Field_editor_forms_model');
		include MODULEPATH . 'network/install/user_fields_data.php';
		if(!empty($fe_fields)) {
			foreach($fe_fields as $field){
				$this->CI->Field_editor_model->delete_field_by_gid($field['data']['gid']);
			}
		}
		$this->CI->Field_editor_model->initialize($this->CI->Users_model->form_editor_type);
		if(!empty($fe_sections)) {
			foreach($fe_sections as $section){
				$this->CI->Field_editor_model->delete_section_by_gid($section['data']['gid']);
			}
		}
		if(!empty($fe_forms)) {
			foreach($fe_forms as $form){
				$this->CI->Field_editor_forms_model->delete_form_by_gid($form['data']['gid']);
			}
		}
		return true;
	}

	/*public function install_users(){
		$this->CI->load->model('users/models/Users_delete_callbacks_model');
		$this->CI->Users_delete_callbacks_model->add_callback(self::MODULE_GID, 'Network_users_model', 'callback_user_delete', '', Network_model::MODULE_GID);
	}

	public function deinstall_users(){
		$this->CI->load->model('users/models/Users_delete_callbacks_model');
		$this->CI->Users_delete_callbacks_model->delete_callbacks_by_module(self::MODULE_GID);
	}*/

	public function install_cronjob() {
		$this->CI->load->model('Cronjob_model');
		$cron_data = array(
			'name' => 'Process network temp',
			'module' => self::MODULE_GID,
			'model' => 'Network_users_model',
			'method' => 'process_temp',
			'cron_tab' => '*/5 * * * *',
			'status' => true,
		);
		$this->CI->Cronjob_model->save_cron(null, $cron_data);
	}

	public function deinstall_cronjob() {
		$this->CI->load->model('Cronjob_model');
		$cron_data = array('where' => array('module' => self::MODULE_GID));
		$this->CI->Cronjob_model->delete_cron_by_param($cron_data);
	}

}
