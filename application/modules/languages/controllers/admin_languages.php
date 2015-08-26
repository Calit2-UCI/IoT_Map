<?php
/**
* Languages admin side controller
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

Class Admin_Languages extends Controller
{
	/**
	 * link to CodeIgniter object
	 * @var object
	 */

	/**
	 * Constructor
	 */
	function Admin_Languages()
	{
		parent::Controller();
		$this->load->model('Menu_model');
		$this->Menu_model->set_menu_active_item('admin_menu', 'content_items');
	}

	function index(){
		$this->langs();
	}

	function langs(){
		$this->template_lite->assign('languages', $this->pg_language->languages);
        $this->template_lite->assign('default_lang_id', $this->pg_language->get_default_lang_id());
		$this->Menu_model->set_menu_active_item('admin_languages_menu', 'languages_list_item');
		$this->system_messages->set_data('header', l('admin_header_languages_list', 'languages'));
		$this->template_lite->view('list_langs');
	}

	function lang_default($lang_id){
		if(!empty($lang_id)){
			if($this->pg_language->is_active($lang_id)){
				$this->pg_language->set_default_lang($lang_id);
				$this->system_messages->add_message('success', l('success_defaulted_lang', 'languages', $lang_id));
			} else {
				$this->system_messages->add_message('error', l('error_default_inactive_lang', 'languages'));
			}
		}
		$url = site_url()."admin/languages/langs";
		redirect($url);
	}

	function lang_active($lang_id, $status='1'){
		if(!empty($lang_id)){
			$data["status"] = intval($status);
			$this->pg_language->set_lang($lang_id, $data);
			$this->system_messages->add_message('success', l('success_updated_lang', 'languages'));
		}
		$url = site_url()."admin/languages/langs";
		redirect($url);
	}

	function lang_edit($lang_id=null){
		if($lang_id){
			$data = $this->pg_language->languages[$lang_id];
		}else{
			$data = array();
		}

		if($this->input->post('btn_save')){
			$post_data = array(
				"name" => $this->input->post('name', true),
				"code" => $this->input->post('code', true),
				"rtl" => $this->input->post('rtl', true),
			);
			$validate_data = $this->pg_language->validate_lang($lang_id, $post_data);
			if(!empty($validate_data["errors"])){
				$this->system_messages->add_message('error', $validate_data["errors"]);
				$data = array_merge($data, $validate_data["data"]);
			}else{
				$data = $validate_data["data"];
				$this->pg_language->set_lang($lang_id, $data);
				$this->system_messages->add_message('success', ($lang_id)?l('success_updated_lang', 'languages'):l('success_added_lang', 'languages'));
				$url = site_url()."admin/languages/langs";
				redirect($url);
			}
		}

		$this->template_lite->assign('data', $data);

		$this->system_messages->set_data('header', l('admin_header_language_edit', 'languages'));
		$this->template_lite->view('edit_lang');
	}

	function lang_delete($lang_id){
		if(!empty($lang_id)){
			if($this->pg_language->get_default_lang_id() == $lang_id) {
				$this->system_messages->add_message('error', l('error_delete_default_lang', 'languages'));
            } elseif($this->pg_language->current_lang_id == $lang_id) {
                $this->system_messages->add_message('error', l('error_delete_current_lang', 'languages'));
			} elseif($this->pg_language->languages[$lang_id]) {
                $this->pg_language->delete_lang($lang_id);
				$this->system_messages->add_message('success', l('success_deleted_lang', 'languages'));
			}
		}
		$url = site_url()."admin/languages/langs";
		redirect($url);
	}


	function pages($lang_id=null, $module_id=null){
		$this->template_lite->assign('langs', $this->pg_language->languages);
		if(!$lang_id) $lang_id = $this->pg_language->get_default_lang_id();
		$this->template_lite->assign('current_lang_id', $lang_id);

		$modules = $this->pg_module->return_modules();
		$this->template_lite->assign('modules', $modules);
		if(!$module_id){
			$first_module = current($modules);
			$module_id = $first_module["id"];
		}
		$this->template_lite->assign('current_module_id', $module_id);

		$current_module = $modules[$module_id];

		$this->pg_language->pages->return_module($current_module["module_gid"], $lang_id);
		$pages = $this->pg_language->pages->lang[$lang_id][$current_module["module_gid"]];
		$this->template_lite->assign('pages', $pages);

		$this->Menu_model->set_menu_active_item('admin_languages_menu', 'languages_pages_item');
		$this->system_messages->set_data('header', l('admin_header_pages_list', 'languages'));
		$this->template_lite->view('list_pages');
	}

	function pages_delete($lang_id, $module_id, $gid){
		$modules = $this->pg_module->return_modules();
		$module_gid = $modules[$module_id]["module_gid"];
		if($gid){
			$this->pg_language->pages->delete_string($module_gid, $gid);
		}
		$url = site_url()."admin/languages/pages/".$lang_id."/".$module_id;
		redirect($url);
	}

	function pages_edit($lang_id, $module_id, $gid=null){
		$modules = $this->pg_module->return_modules();
		$module_gid = $modules[$module_id]["module_gid"];

		if($gid){
			$this->template_lite->assign('gid', $gid);
			foreach($this->pg_language->languages as $lid => $lang){
				$lang_data[$lid] = $this->pg_language->get_string($module_gid, $gid, $lid);
			}
		}else{
			$lang_data = array();
		}

		if($this->input->post('btn_save')){
			$errors = array();
			if(empty($gid)){
				$post_gid = trim(strip_tags($this->input->post('gid', true)));
				$post_gid = preg_replace('/[^a-z0-9_\-\s]+/i', '', $post_gid);
				$post_gid = preg_replace('/\s+/i', '_', trim($post_gid));
				if(empty($post_gid)){
					$errors[] = l('error_gid_incorrect', 'languages');
				}
				if($this->pg_language->pages->is_string_exists($module_gid, $post_gid)){
					$errors[] = l('error_gid_exists', 'languages');
				}else{
					$gid = $post_gid;
				}
			}
			$lang_data = $this->input->post('lang_data', true);

			if(!empty($errors)){
				$this->system_messages->add_message('error', $errors);
			}else{
				foreach($lang_data as $lid => $string){
					$this->pg_language->pages->set_string($module_gid, $gid, $string, $lid);
				}
				$this->system_messages->add_message('success', isset($post_gid)?l('success_added_page', 'languages'):l('success_updated_page', 'languages'));
				$url = site_url()."admin/languages/pages/".$lang_id."/".$module_id;
				redirect($url);
			}
		}

		$this->template_lite->assign('lang_data', $lang_data);
		$this->template_lite->assign('langs', $this->pg_language->languages);
		$this->template_lite->assign('current_lang_id', $lang_id);
		$this->template_lite->assign('current_module_id', $module_id);

		$this->system_messages->set_data('header', l('admin_header_page_change', 'languages'));
		$this->template_lite->view('edit_page');
	}

	function ajax_pages_delete($lang_id, $module_id){

		$strings = $this->input->post("gids", true);
		if(empty($strings)) return;
		$modules = $this->pg_module->return_modules();
		$module_gid = $modules[$module_id]["module_gid"];
		$this->pg_language->pages->delete_strings($module_gid, $strings);
		return;
	}

	function ajax_pages_save($lang_id, $module_id){
		$gid = $this->input->post("id", true);
		$value = $this->input->post("text", true);
		$modules = $this->pg_module->return_modules();
		$module_gid = $modules[$module_id]["module_gid"];

		if(!empty($gid)){
			$this->pg_language->pages->set_string($module_gid, $gid, $value, $lang_id);
		}
		echo $value;
	}


	function ds($lang_id=null, $module_id=null){
		$this->template_lite->assign('langs', $this->pg_language->languages);
		if(!$lang_id) $lang_id = $this->pg_language->get_default_lang_id();
		$this->template_lite->assign('current_lang_id', $lang_id);

		$modules = $this->pg_module->return_modules();
		$this->template_lite->assign('modules', $modules);
		if(!$module_id){
			$first_module = current($modules);
			$module_id = $first_module["id"];
		}
		$this->template_lite->assign('current_module_id', $module_id);

		$current_module = $modules[$module_id];

		$this->pg_language->ds->return_module($current_module["module_gid"], $lang_id);
		$ds = $this->pg_language->ds->lang[$lang_id][$current_module["module_gid"]];
		$this->template_lite->assign('ds', $ds);

		$this->Menu_model->set_menu_active_item('admin_languages_menu', 'languages_ds_item');
		$this->system_messages->set_data('header', l('admin_header_ds_list', 'languages'));
		$this->template_lite->view('list_ds');
	}

	function ds_edit($lang_id, $module_id, $gid=null){
		$modules = $this->pg_module->return_modules();
		$module_gid = $modules[$module_id]["module_gid"];

		if($gid){
			$this->template_lite->assign('gid', $gid);
			foreach($this->pg_language->languages as $lid => $lang){
				$reference = $this->pg_language->ds->get_reference($module_gid, $gid, $lid);
				$lang_data[$lid] = $reference["header"];
			}
		}else{
			$lang_data = array();
		}

		if($this->input->post('btn_save')){
			$errors = array();
			if(empty($gid)){
				$post_gid = trim(strip_tags($this->input->post('gid', true)));
				$post_gid = preg_replace('/[^a-z0-9_\-\s]+/i', '', $post_gid);
				$post_gid = preg_replace('/\s+/i', '-', trim($post_gid));
				if(empty($post_gid)){
					$errors[] = l('error_gid_incorrect', 'languages');
				}
				if($this->pg_language->ds->is_ds_exists($module_gid, $post_gid)){
					$errors[] = l('error_gid_exists', 'languages');
				}else{
					$gid = $post_gid;
				}
			}
			$lang_data = $this->input->post('lang_data', true);

			if(!empty($errors)){
				$this->system_messages->add_message('error', $errors);
			}else{
				foreach($lang_data as $lid => $string){
					$reference = $this->pg_language->ds->get_reference($module_gid, $gid, $lid);
					$reference["header"] = $string;
					$this->pg_language->ds->set_module_reference($module_gid, $gid, $reference, $lid);
				}
				$this->system_messages->add_message('success', isset($post_gid)?l('success_added_ds', 'languages'):l('success_updated_ds', 'languages'));
				$url = site_url()."admin/languages/ds/".$lang_id."/".$module_id;
				redirect($url);
			}
		}

		$this->template_lite->assign('lang_data', $lang_data);
		$this->template_lite->assign('langs', $this->pg_language->languages);
		$this->template_lite->assign('current_lang_id', $lang_id);
		$this->template_lite->assign('current_module_id', $module_id);

		$this->system_messages->set_data('header', l('admin_header_ds_edit', 'languages'));
		$this->template_lite->view('edit_ds');
	}

	function ds_delete($lang_id, $module_id, $gid){
		$modules = $this->pg_module->return_modules();
		$module_gid = $modules[$module_id]["module_gid"];
		if($gid){
			$this->pg_language->ds->delete_reference($module_gid, $gid);
		}
		$url = site_url()."admin/languages/ds/".$lang_id."/".$module_id;
		redirect($url);
	}

	function ds_items($lang_id, $module_id, $gid){
		$this->template_lite->assign('current_gid', $gid);

		$this->template_lite->assign('langs', $this->pg_language->languages);
		if(!$lang_id) $lang_id = $this->pg_language->get_default_lang_id();
		$this->template_lite->assign('current_lang_id', $lang_id);

		$modules = $this->pg_module->return_modules();
		$current_module = $modules[$module_id];
		$module_gid = $modules[$module_id]["module_gid"];
		$this->template_lite->assign('modules', $modules);
		$this->template_lite->assign('current_module_id', $module_id);
		$this->template_lite->assign('module', $current_module);

		$reference = $this->pg_language->ds->get_reference($module_gid, $gid, $lang_id);
		$this->template_lite->assign('reference', $reference);

		$this->pg_theme->add_js('admin-multilevel-sorter.js');
		$this->Menu_model->set_menu_active_item('admin_languages_menu', 'languages_ds_item');
		$header = l('admin_header_ds_items', 'languages').' : '.$current_module["module_name"].' : '.$reference["header"];
		$this->system_messages->set_data('back_link', site_url()."admin/languages/ds/".$lang_id."/".$module_id);
		$this->system_messages->set_data('header', $header);
		$this->template_lite->view('items_ds');
	}

	function ds_items_edit($lang_id, $module_id, $gid, $option_gid=null){
		$modules = $this->pg_module->return_modules();
		$module_gid = $modules[$module_id]["module_gid"];

		$reference = $this->pg_language->ds->get_reference($module_gid, $gid, $lang_id);
		if($option_gid){
			foreach($this->pg_language->languages as $lid => $lang){
				$r = $this->pg_language->ds->get_reference($module_gid, $gid, $lid);
				$lang_data[$lid] = $r["option"][$option_gid];
			}
		}else{
			$option_gid = "";
			$lang_data = array();
		}

		if($this->input->post('btn_save')){
			$lang_data = $this->input->post('lang_data', true);
			$add_flag = false;

			if(empty($option_gid)){
				$post_option_gid = trim(strip_tags($this->input->post('option_gid', true)));
				$post_option_gid = preg_replace('/[^a-z0-9_\-\s]+/i', '', $post_option_gid);
				$post_option_gid = preg_replace('/[\s]+/i', '-', trim($post_option_gid));
				if(empty($post_option_gid)){
					$errors[] = l('error_gid_incorrect', 'languages');
				}else{
					$option_gid = $post_option_gid;
				}
			}

			if(empty($option_gid)){
				$add_flag = true;
				if(!empty($reference["option"])){
					$array_keys = array_keys($reference["option"]);
				}else{
					$array_keys = array(0);
				}
				$index = max($array_keys) + 1;
			}

			if(!empty($errors)){
				$this->system_messages->add_message('error', $errors);
			}else{
				foreach($lang_data as $lid => $string){
					$reference = $this->pg_language->ds->get_reference($module_gid, $gid, $lid);
					$reference["option"][$option_gid] = $string;
					$this->pg_language->ds->set_module_reference($module_gid, $gid, $reference, $lid);
				}
				$this->system_messages->add_message('success', isset($add_flag)?l('success_added_ds_item', 'languages'):l('success_updated_ds_item', 'languages'));
				$url = site_url()."admin/languages/ds_items/".$lang_id."/".$module_id."/".$gid;
				redirect($url);
			}
		}

		$this->template_lite->assign('lang_data', $lang_data);
		$this->template_lite->assign('langs', $this->pg_language->languages);
		$this->template_lite->assign('current_lang_id', $lang_id);
		$this->template_lite->assign('current_module_id', $module_id);
		$this->template_lite->assign('current_module_gid', $module_gid);
		$this->template_lite->assign('current_gid', $gid);
		$this->template_lite->assign('option_gid', $option_gid);

		$this->system_messages->set_data('header', l('admin_header_ds_item_edit', 'languages'));
		$this->template_lite->view('edit_ds_item');
	}

	function ajax_ds_delete($lang_id, $module_id){

		$references = $this->input->post("gids", true);
		if(empty($references)) return;
		$modules = $this->pg_module->return_modules();
		$module_gid = $modules[$module_id]["module_gid"];
		$this->pg_language->ds->delete_references($module_gid, $references);
		return;
	}

	function ajax_ds_save($lang_id, $module_id){
		$gid = $this->input->post("id", true);
		$value = $this->input->post("text", true);
		$modules = $this->pg_module->return_modules();
		$module_gid = $modules[$module_id]["module_gid"];

		if(!empty($gid)){
			$reference = $this->pg_language->ds->get_reference($module_gid, $gid, $lang_id);
			$reference["header"] = $value;
			$this->pg_language->ds->set_module_reference($module_gid, $gid, $reference, $lang_id);
		}
		echo $value;
	}

	function ajax_ds_item_save($lang_id, $module_id, $gid){
		$option_gid = $this->input->post("id", true);
		$value = $this->input->post("text", true);
		$modules = $this->pg_module->return_modules();
		$module_gid = $modules[$module_id]["module_gid"];

		if(!empty($gid)){
			$reference = $this->pg_language->ds->get_reference($module_gid, $gid, $lang_id);
			$reference["option"][$option_gid] = $value;
			$this->pg_language->ds->set_module_reference($module_gid, $gid, $reference, $lang_id);
		}
		echo $value;
	}

	function ajax_ds_item_delete($module_id, $gid, $option_gid){
		$modules = $this->pg_module->return_modules();
		$module_gid = $modules[$module_id]["module_gid"];
		if($gid && $option_gid){
			foreach($this->pg_language->languages as $lid => $lang){
				$reference = $this->pg_language->ds->get_reference($module_gid, $gid, $lid);
				if(isset($reference["option"][$option_gid])){
					unset($reference["option"][$option_gid]);
					$this->pg_language->ds->set_module_reference($module_gid, $gid, $reference, $lid);
				}
			}
		}
		return;
	}

	function ajax_ds_item_save_sorter($module_id, $gid){
		$modules = $this->pg_module->return_modules();
		$module_gid = $modules[$module_id]["module_gid"];

		$sorter = $this->input->post("sorter");
		foreach($sorter as $parent_str => $items_data){
			foreach($items_data as $item_str =>$sort_index){
				$option_gid = str_replace("item_", "", $item_str);
				$sorter_data[$sort_index] = $option_gid;
			}
		}

		if(empty($sorter_data)) return;
		ksort($sorter_data);
		$this->pg_language->ds->set_reference_sorter($module_gid, $gid, $sorter_data);
		return;
	}

	function generate_install_module_lang($module_gid){
		$code = $this->pg_language->generate_install_module_lang($module_gid);
		header('Content-type: text/plain; charset=utf-8');
		echo $code;
	}
}
