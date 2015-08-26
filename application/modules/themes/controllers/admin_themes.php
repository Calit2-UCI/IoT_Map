<?php
/**
* Themes admin side controller
* 
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

Class Admin_Themes extends Controller
{
	/**
	 * link to CodeIgniter object
	 * @var object
	 */

	/**
	 * Constructor
	 */
	function Admin_Themes()
	{
		parent::Controller();
		$this->load->model("Themes_model");
		$this->load->model('Menu_model');
		$this->Menu_model->set_menu_active_item('admin_menu', 'interface-items');
	}
	

	function index($type='user'){
		$this->installed_themes($type);
	}

	function installed_themes($type='user'){
		$current_settings = isset($_SESSION["i_themes_list"])?$_SESSION["i_themes_list"]:array();
		if(!isset($current_settings["filter"])) $current_settings["filter"] = "user";

		if(!$type) $type = $current_settings["filter"];
		$this->template_lite->assign('type', $type);
		$_SESSION["i_themes_list"] = $current_settings;

		$filter = array(
			"user" => $this->Themes_model->get_installed_themes_count(array("where"=>array("theme_type"=>'user'))),
			"admin" => $this->Themes_model->get_installed_themes_count(array("where"=>array("theme_type"=>'admin'))),
		);
		$this->template_lite->assign('filter', $filter);

		$params["where"]["theme_type"] = $type;
		$themes = $this->Themes_model->get_installed_themes_list($params);
		$this->template_lite->assign('themes', $themes);

		$this->system_messages->set_data('header', l('admin_header_installed_themes_list', 'themes'));
		$this->template_lite->view('list_installed');
	}

	function enable_themes($type='user'){
		$current_settings = isset($_SESSION["e_themes_list"])?$_SESSION["e_themes_list"]:array();
		if(!isset($current_settings["filter"])) $current_settings["filter"] = "user";

		if(!$type) $type = $current_settings["filter"];
		$this->template_lite->assign('type', $type);
		$_SESSION["e_themes_list"] = $current_settings;

		$themes = $this->Themes_model->get_uninstalled_themes_list($type);
		$this->template_lite->assign('themes', $themes);

		$this->system_messages->set_data('header', l('admin_header_enable_themes_list', 'themes'));
		$this->template_lite->view('list_enable');
	}

	function view_installed($id, $lang_id=0){
		if(!$lang_id){
			$lang_id = $this->pg_language->current_lang_id;
		}
		
		$theme_data = $this->Themes_model->get_theme($id, '', $lang_id);
		$this->template_lite->assign('theme', $theme_data);

		$permissions = $this->Themes_model->check_theme_permissions($theme_data['theme']);
		if(!$permissions['logo']){
			$error = str_replace('[dir]', $permissions['logo_path'], l('error_logo_dir_writeable_error', 'themes'));
			$this->system_messages->add_message('error', $error);
		}
		
		if($this->input->post('btn_save')){
			$post_data = array(
				"logo_width" => $this->input->post('logo_width', true),
				"logo_height" => $this->input->post('logo_height', true),
				"mini_logo_width" => $this->input->post('mini_logo_width', true),
				"mini_logo_height" => $this->input->post('mini_logo_height', true),
			);
			$validate_data = $this->Themes_model->validate_logo_params($post_data);

			if(!empty($validate_data["errors"])){
				$this->system_messages->add_message('error', $validate_data["errors"]);
			}else{
				$this->Themes_model->save_logo_params($id, $validate_data["data"]);
				$this->system_messages->add_message('success', l('success_logo_params_saved', 'themes'));
			}	
			
			if($this->input->post('logo_delete') == '1' || $this->input->post('mini_logo_delete') == '1'){
				if($this->input->post('logo_delete') == '1'){
					$this->Themes_model->delete_logo($id, $lang_id, 'logo');
				}
				if($this->input->post('mini_logo_delete') == '1'){
					$this->Themes_model->delete_logo($id, $lang_id, 'mini_logo');
				}
				$this->system_messages->add_message('success', l('success_delete_logo', 'themes'));
			}
			
			if(isset($_FILES["logo"]) && is_array($_FILES["logo"]) && is_uploaded_file($_FILES["logo"]["tmp_name"])){
				$ret = $this->Themes_model->upload_logo('logo', $id, $theme_data["logo_width"], $theme_data["logo_height"], $lang_id);
				if(!empty($ret["error"])){
					$this->system_messages->add_message('error', $ret["error"]);
				}else{
					$this->system_messages->add_message('success', l('success_uploaded_logo', 'themes'));
				}
			}
			if(isset($_FILES["mini_logo"]) && is_array($_FILES["mini_logo"]) && is_uploaded_file($_FILES["mini_logo"]["tmp_name"])){
				$ret_mini = $this->Themes_model->upload_logo('mini_logo', $id, $theme_data["mini_logo_width"], $theme_data["mini_logo_height"], $lang_id);
				if(!empty($ret["error"])){
					$this->system_messages->add_message('error', $ret["error"]);
				}else{
					$this->system_messages->add_message('success', l('success_uploaded_logo', 'themes'));
				}
			}
			redirect(site_url()."admin/themes/view_installed/".$id."/".$lang_id);

		}

		$languages = $this->pg_language->languages;
		$this->template_lite->assign('lang_id', $lang_id);
		$this->template_lite->assign('langs', $languages);

		$this->system_messages->set_data('header', l('admin_header_theme_view', 'themes'));
		$this->template_lite->view('view');
	}
	
	function activate($id){
		$this->Themes_model->set_active($id);
		$this->system_messages->add_message('success', l('success_activate_theme', 'themes'));
		$current_settings = $_SESSION["i_themes_list"];
		$url = site_url()."admin/themes/installed_themes/".$current_settings["filter"];
		redirect($url);
	}
	
	function sets($id_theme){
		if(empty($id_theme)) {
			redirect(site_url()."admin/themes");
		}
		$theme_data = $this->Themes_model->get_theme($id_theme);
		$this->template_lite->assign('theme', $theme_data);
		$sets = $this->Themes_model->get_sets_list($id_theme);
		$this->template_lite->assign('sets', $sets);
		$this->template_lite->assign('id_theme', $id_theme);

		$permissions = $this->Themes_model->check_theme_permissions($theme_data['theme']);
		if(!$permissions['sets']){
			$error = str_replace('[dir]', $permissions['sets_path'], l('error_sets_dir_writeable_error', 'themes'));
			$this->system_messages->add_message('error', $error);
		}

		$this->system_messages->set_data('header', $theme_data["name"]." : ".l('admin_header_theme_sets', 'themes'));
		
		if(!empty($_SESSION["i_themes_list"])) {
			$current_settings = $_SESSION["i_themes_list"];
		}
		$this->system_messages->set_data('back_link', site_url() . "admin/themes/installed_themes/".empty($current_settings["filter"])?'':$current_settings["filter"]);
		$this->template_lite->view('list_sets');
	}
	
	public function edit_set($id_theme, $id_set=0){
		$theme_data = $this->Themes_model->get_theme($id_theme);

		$permissions = $this->Themes_model->check_theme_permissions($theme_data['theme']);
		if(!$permissions['sets']){
			$error = str_replace('[dir]', $permissions['sets_path'], l('error_sets_dir_writeable_error', 'themes'));
			$this->system_messages->add_message('error', $error);
		}
		
		//// load defaults
		include($theme_data["path"]."config/colors.config.php");	

		if($id_set){
			$set = $this->Themes_model->get_set_by_id($id_set);
			foreach($set['color_settings'] as $name => $value){
				if($set["scheme_type"] == 'light'){
					$set[$name]['light_default'] = $value;
				}else{
					$set[$name]['dark_default'] = $value;
				}
			}
		}else{
			$set = array(
				'template_id' => 1,
				'scheme_type' => 'light',
			);	
		}
		
		$files = array();
		foreach($scheme as $name => $value){
			if($value['type'] == 'file'){
				$files[$name] = $name;
			}
			if(!$id_set){
				$set['color_settings'][$name] = $value["light_default"];
			}
		}

		if($this->input->post('btn_save')){
			$post_data = array(
				"set_name" => $this->input->post('set_name', true),
				"set_gid" => $this->input->post('set_gid', true),
				"id_theme" => $id_theme,
				"scheme_type" => $this->input->post('scheme_type', true),
			);
			foreach($scheme as $name => $value){
				$post_data["color_settings"][$name] = $this->input->post($name, true);
			}
			foreach($files as $name => &$bg_file){
				$post_data["color_settings"][$name] = $set['color_settings'][$name];
				if($this->input->post($name.'_delete', true)){
					$bg_file = false;
				}
			}
		
			$validate_data = $this->Themes_model->validate_set($id_set, $post_data, $files, $theme_data);
			if(!empty($validate_data["errors"])){
				$this->system_messages->add_message('error', $validate_data["errors"]);
			}else{
				$flag_add = empty($id_set);
				if($flag_add){
					$validate_data["data"]["active"] = 0;
				}
				if (DEMO_MODE && $id_set == 2){
					$this->system_messages->add_message('error', l('error_demo_mode', 'start'));
				} else {
					$this->Themes_model->save_set($id_set, $validate_data["data"]);
					$this->system_messages->add_message('success', (!$flag_add)?l('success_update_set', 'themes'):l('success_add_set', 'themes'));
					redirect(site_url()."admin/themes/sets/".$id_theme);
				}
			}
			$set = array_merge($set, $this->Themes_model->_format_set($validate_data["data"]));
		}
		
		$theme_settings = $this->pg_theme->format_theme_settings('', $theme_data['type'], $theme_data['gid'], $set['set_gid']);
		$bg_img_url = site_url().$theme_settings['img_set_path'].$set['color_settings']['index_bg_image'].'?ver='.$set['color_settings']['index_bg_image_ver'];

		$this->template_lite->assign("bg_img_url", $bg_img_url);
		$this->template_lite->assign("theme_data", $theme_data);
		$this->template_lite->assign("theme_settings", $theme_settings);
		$this->template_lite->assign("scheme", $scheme);
		$this->template_lite->assign("scheme_json", json_encode($scheme));
		$this->template_lite->assign("set", $set);
		$this->template_lite->assign("id_theme", $id_theme);
		$this->template_lite->assign("id_set", $id_set);

		$this->system_messages->set_data('header', l('admin_header_set_edit', 'themes'));
		$this->system_messages->set_data('back_link', site_url() . "admin/themes/sets/".$id_theme);
		$this->template_lite->view('edit_set');
	}

	public function activate_set($id_theme, $id_set){
		$id_set = intval($id_set);
		if($id_set){
			$this->Themes_model->activate_set($id_set);
			$this->system_messages->add_message('success', l('success_activate_set', 'themes'));
		}
		$url = site_url()."admin/themes/sets/".$id_theme;
		redirect($url);
	}

	public function delete_set($id_theme, $id_set){
		$id_set = intval($id_set);
		if($id_set){
			if (DEMO_MODE && $id_set == 2 /*&& $validate_data['data']['set_gid'] == 'default'*/){
				$this->system_messages->add_message('error', l('error_demo_mode', 'start'));
			} else {
				$this->Themes_model->delete_set($id_set);
				$this->system_messages->add_message('success', l('success_delete_set', 'themes'));
			}
		}
		$url = site_url()."admin/themes/sets/".$id_theme;
		redirect($url);
	}
	
	function install($theme){
		if(!empty($theme)){
			$return = $this->Themes_model->install_theme($theme);
			if($return)
				$this->system_messages->add_message('success', l('success_installed_theme', 'themes'));
			else{
				$this->system_messages->add_message('error', l('error_installed_theme', 'themes'));
			}
		}
		$current_settings = $_SESSION["e_themes_list"];
		$url = site_url()."admin/themes/enable_themes/".$current_settings["filter"];
		redirect($url);
	}

	function uninstall($id){
		if(!empty($id)){
			$return = $this->Themes_model->uninstall_theme($id);
			if($return)
				$this->system_messages->add_message('success', l('success_uninstalled_theme', 'themes'));
			else{
				$this->system_messages->add_message('error', l('error_uninstalled_theme', 'themes'));
			}
		}
		$current_settings = $_SESSION["i_themes_list"];
		$url = site_url()."admin/themes/installed_themes/".$current_settings["filter"];
		redirect($url);
	}

	function preview($theme, $scheme=''){
		if(!$scheme){
			$theme_base_data = $this->pg_theme->get_theme_base_data($theme);
			$scheme = !empty($theme_base_data[$theme]['scheme']) ? $theme_base_data[$theme]['scheme'] : '';
		}
		$_SESSION["preview_theme"] = $theme;
		$_SESSION["preview_scheme"] = $scheme;
		$_SESSION['change_color_scheme'] = true;
		//$theme_data = $this->pg_theme->get_theme_data($theme);
		//if($theme_data["type"] == 'user')
		//	redirect(site_url());
		//else{
			redirect(site_url());
		//}
	}
	
	public function regenerate_colorsets($id_theme){
		$return = $this->Themes_model->regenerate_color_sets($id_theme);
		if(!empty($return["errors"])){
			echo "<b>Errors:</b><br>";
			print_r($return["errors"]);
		}else{
			echo "<b>Success</b><br>";
		}
	}
	
	public function rtl_parser($id_theme=0, $css_gid=''){
		$themes = $this->Themes_model->get_installed_themes_list();
		$this->template_lite->assign('themes', $themes);
		
		if(!empty($id_theme)){
			$theme_data = $this->Themes_model->get_theme($id_theme);
			$this->template_lite->assign('theme_data', $theme_data);
		}
		
		if(!empty($css_gid)){
			$css_path = $theme_data['path'].'config/'.str_replace('[rtl]', 'ltr', $theme_data['css'][$css_gid]['file']);
			$css_data = $this->Themes_model->parse_current_rtl($css_path);
			$this->template_lite->assign('css_data', $css_data);
		}
		
		$this->template_lite->assign('id_theme', $id_theme);
		$this->template_lite->assign('css_gid', $css_gid);
		
		$this->system_messages->set_data('header', l('admin_header_rtl_parser', 'themes'));
		$this->template_lite->view('form_rtl');
	}
}
