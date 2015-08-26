<?php

/**
 * Widgets admin side controller
 *
 * @package PG_DatingPro
 * @subpackage Widgets
 * @category	controllers
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * */
class Admin_Widgets extends Controller {

	/**
	 * Constructor
	 */
	public function Admin_Widgets() {
		parent::Controller();
		$this->load->model('Menu_model');
		$this->Menu_model->set_menu_active_item('admin_menu', 'add_ons_items');
	}

	/**
	 * Installed widgets
	 */
	public function index() {
		$this->load->model('Widgets_model');
		$widgets = $this->Widgets_model->get_widgets_list();
		$this->template_lite->assign('widgets', $widgets);
		$this->system_messages->set_data('header', l('admin_header_widgets_list', 'widgets'));
		$this->template_lite->view('list_widgets');
	}

	/**
	 * Enabled widgets
	 */
	public function install() {
		$this->load->model('Install_model');
		$widgets = array();
		$current_lang_id = $this->pg_language->current_lang_id;
		$current_lang_code = $this->pg_language->get_lang_code_by_id($current_lang_id);
		$installed_modules = $this->Install_model->get_installed_modules();
		foreach ($installed_modules as $installed_module) {
			$path = MODULEPATH . $installed_module['module_gid'] . '/widgets/';
			if (!is_dir($path)) {
				continue;
			}
			foreach (scandir($path) as $file) {
				$r = array();
				$result = preg_match('/^' . preg_quote($installed_module['module_gid'], '/') . '_([a-z]+)_widget/i', $file, $r);
				if (!$result) {
					continue;
				}
				$model_name = ucfirst($installed_module['module_gid']) . '_' . $r[1] . '_widget';
				$this->load->model($installed_module['module_gid'] . '/widgets/' . $model_name, $model_name);
				$widget = $this->{$model_name}->get_widget_info($current_lang_code);
				$widget['langs'] = $this->{$model_name}->get_widget_langs($current_lang_code);
				$widgets[$widget['gid']] = $widget;
			}
		}

		$installed_gids = array();

		$this->load->model('Widgets_model');
		$installed_modules = $this->Widgets_model->get_widgets_list();
		foreach ($installed_modules as $installed_module) {
			$installed_gids[$installed_module['gid']] = 1;
		}

		$widgets = array_diff_key($widgets, $installed_gids);
		$this->template_lite->assign('widgets', $widgets);

		$this->system_messages->set_data('header', l('admin_header_widgets_install', 'widgets'));
		$this->template_lite->view('list_install');
	}

	/**
	 * Install widget
	 * @param string $module widget module
	 * @param string $gid widget guid
	 */
	public function install_widget($module, $gid) {
		if (!$this->pg_module->is_module_installed($module)) {
			$this->system_messages->add_message('error', l('error_module_install', 'widgets'));
			redirect(site_url() . 'admin/widgets/install');
		}

		$path = MODULEPATH . $module . '/widgets/' . $gid . '.php';
		if (!file_exists($path)) {
			$this->system_messages->add_message('error', l('error_widget_invalid', 'widgets'));
			redirect(site_url() . 'admin/widgets/install');
		}

		$model_name = ucfirst($gid);
		$this->load->model($module . '/widgets/' . $model_name, $model_name);
		$save_data = $this->{$model_name}->get_widget_info();

		$this->load->model('Widgets_model');
		$validate_data = $this->Widgets_model->validate_widget(null, $save_data);
		if (!empty($validate_data['errors'])) {
			$this->system_messages->add_message('error', $validate_data['errors']);
		} else {
			$this->Widgets_model->save_widget(null, $validate_data['data']);
			$this->system_messages->add_message('success', l('success_widget_install', 'widgets'));

			$langs = $this->{$model_name}->get_widget_langs();
			foreach ($this->pg_language->languages as $lang) {
				if (!isset($langs[$lang['code']])) {
					$langs[$lang['code']] = current($langs);
				}
				$this->pg_language->pages->set_strings('widgets', $langs[$lang['code']], $lang['id']);
			}

			redirect(site_url() . 'admin/widgets/index');
		}

		redirect(site_url() . 'admin/widgets/install');
	}

	/**
	 * Edit widget
	 * @param integer $widget_gid widget guid
	 */
	public function edit($widget_gid) {
		$this->load->model('Widgets_model');
		$widget = $this->Widgets_model->get_widget_by_gid($widget_gid);
		$this->template_lite->assign('widget', $widget);

		if ($this->pg_module->is_module_installed($widget['module'])) {
			$model_name = ucfirst($widget['gid']);
			$this->load->model($widget['module'] . '/widgets/' . $model_name, $model_name);
		}

		$widget_code = $this->template_lite->fetch('widget_code', 'admin', 'widgets');
		$this->template_lite->assign('widget_code', $widget_code);

		if ($this->input->post('btn_save')) {
			$errors = array();
			$post_data = $this->input->post('data', true);

			if (isset($model_name) && !empty($post_data['settings'])) {
				$validate_settings = $this->{$model_name}->validate_settings($post_data['settings']);
				$post_data['settings'] = $validate_settings['data'];
				$errors = array_merge($errors, $validate_settings['errors']);
			}

			$validate_data = $this->Widgets_model->validate_widget($widget['id'], $post_data);
			$validate_data['errors'] = array_merge($validate_data['errors'], $errors);
			if (!empty($validate_data['errors'])) {
				$this->system_messages->add_message('error', $validate_data['errors']);
			} else {
				$this->Widgets_model->save_widget($widget['id'], $validate_data['data']);
				$this->system_messages->add_message('success', l('success_widget_update', 'widgets'));
				$url = site_url() . 'admin/widgets/edit/' . $widget_gid;
				redirect($url);
			}
		}

		if (isset($model_name)) {
			$settings_form = $this->{$model_name}->get_settings_form($widget['settings']);
			$this->template_lite->assign('settings_form', $settings_form);
		}

		$this->template_lite->assign('current_lang_id', $this->pg_language->current_lang_id);
		$this->template_lite->assign('current_lang_code', $this->pg_language->get_lang_code_by_id($this->pg_language->current_lang_id));
		$this->template_lite->assign('langs', $this->pg_language->languages);

		$this->system_messages->set_data('back_link', site_url() . 'admin/widgets/index');
		$this->system_messages->set_data('header', l('admin_header_widget_edit', 'widgets') . ' : ' . l($widget['gid'] . '_name', 'widgets'));
		$this->template_lite->view('edit_widget');
	}

	/**
	 * Remove widget
	 * @param integer $widget_gid widget guid
	 */
	public function delete($widget_gid) {
		$this->load->model('Widgets_model');
		$this->Widgets_model->delete_widget($widget_gid);
		$this->system_messages->add_message('success', l('success_widget_delete', 'widgets'));
		$url = site_url() . 'admin/widgets/index';
		redirect($url);
	}

}
