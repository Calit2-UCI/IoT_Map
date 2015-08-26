<?php
/**
* Geomaps admin side controller
* 
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/
class Admin_Geomap extends Controller {

	/**
	 * Constructor
	 */
	function __construct(){
		parent::Controller();
		$this->load->model('Geomap_model');
		$this->load->model('Menu_model');
		$this->Menu_model->set_menu_active_item('admin_menu', 'interface-items');
	}

	public function index(){

		$drivers = $this->Geomap_model->get_drivers();
		$this->template_lite->assign('drivers', $drivers);

		$page_data["date_format"] = $this->pg_date->get_format('date_time_numeric', 'st');
		$this->template_lite->assign('page_data', $page_data);

		$this->system_messages->set_data('header', l('admin_header_list', 'geomap'));
		$this->template_lite->view('list');

	}

	public function activate($gid){
		if(!empty($gid)){
			$this->Geomap_model->set_default_driver($gid);
		}
		redirect(site_url()."admin/geomap");
	}

	public function edit($gid){
		if(empty($gid)){
			redirect(site_url()."admin/geomap");
		}
		$data = $this->Geomap_model->get_driver_by_gid($gid);
		
		if (!$data['need_regkey']) {
			redirect(site_url() . 'admin/geomap');
		}
		
		if($this->input->post('btn_save')){
			$post_data = array(
				"regkey" => $this->input->post('regkey', true),
			);

			$validate_data = $this->Geomap_model->validate_driver($gid, $post_data);
			if(!empty($validate_data["errors"])){
				$this->system_messages->add_message('error', $validate_data["errors"]);
			}else{
				$this->Geomap_model->set_driver($gid, $validate_data["data"]);

				$this->system_messages->add_message('success', l('success_update_driver', 'geomap'));
				redirect(site_url()."admin/geomap");
			}
			$data = array_merge($data, $validate_data["data"]);
		}

		$this->template_lite->assign('data', $data);
	
		$this->system_messages->set_data('header', l('admin_header_geomap_edit', 'geomap'));
		$this->template_lite->view('edit_form');
	}

	public function settings($gid, $map_gid=''){
		if(empty($gid)){
			redirect(site_url()."admin/geomap");
		}
		$driver_data = $this->Geomap_model->get_driver_by_gid($gid);
		$this->template_lite->assign('driver_data', $driver_data);

		$this->load->model("geomap/models/Geomap_settings_model");
		$data = $this->Geomap_settings_model->get_parsed_settings($gid, 0, 0, $map_gid);

		if($this->input->post('btn_save')){
			$post_data = $this->input->post("data", true);
			$validate_data = $this->Geomap_settings_model->validate_settings($post_data);
			
			$validate_logo = $this->Geomap_settings_model->validate_marker_icon('marker_icon');
			$validate_data['errors'] = array_merge($validate_data['errors'], $validate_logo['errors']);
			
			if($this->input->post("marker_icon_delete")){
				$this->Geomap_settings_model->delete_marker_icon($gid , '');
			}
			
			if(!empty($validate_data["errors"])){
				$this->system_messages->add_message('error', $validate_data["errors"]);
			}else{
				$this->Geomap_settings_model->save_settings($gid, 0, 0, $map_gid, $validate_data["data"]);
				$this->Geomap_settings_model->save_marker_icon($gid, $map_gid, 'marker_icon');
				
				$this->system_messages->add_message('success', l('success_update_driver_settings', 'geomap'));
				redirect(site_url()."admin/geomap/settings/".$gid.($map_gid ? '/'.$map_gid : ''));
			}
			$data = array_merge($data, $validate_data["data"]);
		}
		$this->template_lite->assign('data', $data);

		$markers[] = array(
			"gid" => 'general', 
			"lat" => $data["lat"], 
			"lon" => $data["lon"], 
			"html" => l('set_general_settings_point_text', 'geomap'), 
			"dragging" => true,
		);
		$this->template_lite->assign("markers", $markers);
		
		$current_language = $this->pg_language->get_lang_by_id($this->pg_language->current_lang_id);
		$view_settings = array(
			"zoom_listener" => "get_zoom_data", 
			"type_listener" => "get_type_data",
			"drag_listener" => "get_drag_data",
			"lang" => $current_language["code"],
		);
		$this->template_lite->assign("view_settings", $view_settings);
		
		$this->template_lite->assign('gid', $gid);
		
		$maps = $this->Geomap_settings_model->get_maps_lists($gid);
		$this->template_lite->assign('maps', $maps);
		
		$this->template_lite->assign('map_gid', $map_gid);
		
		$this->template_lite->assign('lang_view_type', ld('geomap_type_'.$gid, 'geomap'));
		$this->system_messages->set_data('header', l('admin_header_geomap_settings_edit', 'geomap'));
		$this->template_lite->view('edit_settings');
	}
	
	public function ajax_get_amenity_form($gid, $max=10) {
		$this->load->model("geomap/models/geomap_settings_model", "Geomap_settings_model");   
	
		$data["max"] = $max;
		
		$data["amenities"] = $this->pg_language->ds->get_reference(
			$this->Geomap_settings_model->module_gid, 
			$gid, 
			$this->pg_language->current_lang_id
		);
		
		$this->template_lite->assign("data", $data);
		$this->template_lite->view('ajax_amenity_form');
	}
	
	public function ajax_get_amenity_data($gid) {
		$this->load->model("geomap/models/geomap_settings_model", "Geomap_settings_model");   
		
		$data = array();		
		$reference = $this->pg_language->ds->get_reference(
			$this->Geomap_settings_model->module_gid, 
			$gid, 
			$this->pg_language->current_lang_id
		);
		
		$data["amenities"] = array();
		if($reference){
			foreach($reference["option"] as $key => $value){
				$data["amenities"][] = array("id"=>$key, "name"=>$value);
			}
		}

		echo json_encode($data);
		return;
	}
}
