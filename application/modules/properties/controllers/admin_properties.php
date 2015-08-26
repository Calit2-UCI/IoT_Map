<?php

Class Admin_properties extends Controller
{
	/**
	 * link to CodeIgniter object
	 * @var object
	 */
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::Controller();
		$this->load->model('Menu_model');
		$this->load->model('Properties_model');
		$this->Menu_model->set_menu_active_item('admin_menu', 'content_items');
	}

	public function index(){
		$lang_id = $this->pg_language->current_lang_id;

		$this->pg_language->ds->return_module($this->Properties_model->module_gid, $lang_id);
		$ds = $this->pg_language->ds->lang[$lang_id][$this->Properties_model->module_gid];

		$property_gids = $this->Properties_model->properties;

		foreach($property_gids as $gid){
			$properties[$gid] = $ds[$gid];
		}

		$this->template_lite->assign('properties', $properties);

		$this->system_messages->set_data('header', l('admin_header_properties', 'properties'));
		$this->template_lite->view('menu');
	}

	public function property($ds_gid, $lang_id = null){
		if (!$ds_gid) {
			redirect(site_url() . 'admin/properties/index');
		}elseif(!$lang_id || !array_key_exists($lang_id, $this->pg_language->languages)) {
			$lang_id = $this->pg_language->current_lang_id;
		}
		$reference = $this->pg_language->ds->get_reference($this->Properties_model->module_gid, $ds_gid, $lang_id);
		$header = l('admin_header_properties', 'properties') . ' : ' . $reference['header'];

		$this->system_messages->set_data('back_link', site_url() . 'admin/start/menu/content_items');
		$this->system_messages->set_data('header', $header);

		$this->template_lite->assign('current_gid', $ds_gid);
		$this->template_lite->assign('langs', $this->pg_language->languages);
		$this->template_lite->assign('current_lang_id', $lang_id);
		$this->template_lite->assign('reference', $reference);
		$this->template_lite->assign('module_gid', $this->Properties_model->module_gid);
		$this->template_lite->assign('ds_gid', $ds_gid);
		$this->template_lite->view('list');
	}

	public function property_items($lang_id, $ds_gid, $option_gid=null){

		if(!$lang_id || !array_key_exists($lang_id, $this->pg_language->languages)) {
			$lang_id = $this->pg_language->current_lang_id;
		}

		$reference = $this->pg_language->ds->get_reference($this->Properties_model->module_gid, $ds_gid, $lang_id);
		if($option_gid){
			$add_flag = false;
			foreach($this->pg_language->languages as $lid => $lang){
				$r = $this->pg_language->ds->get_reference($this->Properties_model->module_gid, $ds_gid, $lid);
				$lang_data[$lid] = $r["option"][$option_gid];
			}
		}else{
			$option_gid = "";
			$lang_data = array();
			$add_flag = true;
		}

		if($this->input->post('btn_save')){
			$lang_data = $this->input->post('lang_data', true);

			if(empty($option_gid)){
				if(!empty($reference["option"])){
					$array_keys = array_keys($reference["option"]);
				}else{
					$array_keys = array(0);
				}
				$option_gid = max($array_keys) + 1;
			}

			foreach($lang_data as $lid => $string){
				if(empty($string)) {
					$this->system_messages->add_message('error', l('error_option_name_required', 'properties') . ': ' . $this->pg_language->languages[$lid]['name']);
					$is_err = true;
					continue;
				}elseif(!array_key_exists($lid, $this->pg_language->languages)) {
					continue;
				}
				$reference = $this->pg_language->ds->get_reference($this->Properties_model->module_gid, $ds_gid, $lid);
				$reference["option"][$option_gid] = $string;
				$this->pg_language->ds->set_module_reference($this->Properties_model->module_gid, $ds_gid, $reference, $lid);
			}
			if(!$is_err) {
				$this->system_messages->add_message('success', ($add_flag?l('success_added_property_item', 'properties'):l('success_updated_property_item', 'properties')));
				$url = site_url()."admin/properties/property/".$ds_gid."/".$lang_id;
				redirect($url);
			}
		}

		$this->template_lite->assign('lang_data', $lang_data);
		$this->template_lite->assign('langs', $this->pg_language->languages);
		$this->template_lite->assign('current_lang_id', $lang_id);
		$this->template_lite->assign('current_module_gid', $this->Properties_model->module_gid);
		$this->template_lite->assign('current_gid', $ds_gid);
		$this->template_lite->assign('option_gid', $option_gid);
		$this->template_lite->assign('add_flag', $add_flag);

		$this->system_messages->set_data('header', l('admin_header_property_item_edit', 'properties'));
		$this->template_lite->view('edit_ds_item');
	}

	public function ajax_ds_item_delete($gid, $option_gid){
		if($gid && $option_gid){
			foreach($this->pg_language->languages as $lid => $lang){
				$reference = $this->pg_language->ds->get_reference($this->Properties_model->module_gid, $gid, $lid);
				if(isset($reference["option"][$option_gid])){
					unset($reference["option"][$option_gid]);
					$this->pg_language->ds->set_module_reference($this->Properties_model->module_gid, $gid, $reference, $lid);
				}
			}
		}
		return;
	}

	public function ajax_ds_item_save_sorter($gid){
		$sorter = $this->input->post("sorter");
		foreach($sorter as $parent_str => $items_data){
			foreach($items_data as $item_str =>$sort_index){
				$option_gid = str_replace("item_", "", $item_str);
				$sorter_data[$sort_index] = $option_gid;
			}
		}

		if(empty($sorter_data)) return;
		ksort($sorter_data);
		$this->pg_language->ds->set_reference_sorter($this->Properties_model->module_gid, $gid, $sorter_data);
		return;
	}

}