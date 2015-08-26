<?php
/**
* Admin Wall events controller
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Dmitry Popenov
* @version $Revision: 2 $ $Date: 2013-01-30 10:07:07 +0400 $
**/
class Admin_Wall_events extends Controller {
	
	private $_other_settings = array(
		'wall_events' => array(
			array('var' => 'live_period', 'type' => 'text'),
			array('var' => 'events_max_count', 'type' => 'text'),
		),
	);
	
	public function __construct(){
		parent::Controller();
		$this->load->model('wall_events/models/Wall_events_types_model');
		$this->load->model('Menu_model');
		$this->Menu_model->set_menu_active_item('admin_menu', 'add_ons_items');
	}

	public function index($page = 1){
		$items_on_page = $this->pg_module->get_module_config('start', 'admin_items_per_page');
		$this->load->helper('sort_order');
		$wall_events_types_cnt = $this->Wall_events_types_model->get_wall_events_types_cnt();
		$page = get_exists_page_number($page, $wall_events_types_cnt, $items_on_page);

		$this->load->helper("navigation");
		$url = site_url()."admin/wall_events/index/";
		$page_data = get_admin_pages_data($url, $wall_events_types_cnt, $items_on_page, $page, 'briefPage');
		$this->template_lite->assign('page_data', $page_data);

		$wall_events_types = $this->Wall_events_types_model->get_wall_events_types(array(), $page, $items_on_page);
		$this->template_lite->assign('wall_events_types', $wall_events_types);
		
		$_SESSION["wall_events_types"]["page"] = $page;

		$this->Menu_model->set_menu_active_item('admin_wall_events_menu', 'wall_events_list_item');
		$this->system_messages->set_data('header', l('admin_header_list', 'wall_events'));
		$this->template_lite->view('wall_events_types');
	}
	
	public function ajax_activate_type(){
		$gid = $this->input->post('gid', true);
		$status = $this->input->post('status', true) ? '1' : '0';
		$this->Wall_events_types_model->save_wall_events_type($gid, array('status'=>$status));
		$return['status'] = $status;
		exit(json_encode($return));
	}
	
	public function edit_type($gid){
		$wall_events_type = $this->Wall_events_types_model->get_wall_events_type($gid);
		$this->template_lite->assign('wall_events_type', $wall_events_type);
		
		$data['page'] = isset($_SESSION['wall_events_types']['page']) && $_SESSION['wall_events_types']['page'] ? $_SESSION['wall_events_types']['page'] : 1;
		$data['action'] = site_url()."admin/wall_events/save_type/{$gid}";
		$this->template_lite->assign('data', $data);
		$this->template_lite->view('form_wall_events_type');
	}
	
	public function save_type($gid){
		$page = isset($_SESSION['wall_events_types']['page']) && $_SESSION['wall_events_types']['page'] ? $_SESSION['wall_events_types']['page'] : 1;
		if(!$gid){
			redirect(site_url()."admin/wall_events/index/{$page}");
		}
		$attrs['status'] = $this->input->post('status', true);
		$attrs['settings']['join_period'] = intval($this->input->post('join_period', true));
		$result = $this->Wall_events_types_model->save_wall_events_type($gid, $attrs);
		$this->system_messages->add_message('success', l('success_update_wall_events_data', 'wall_events'));
		redirect(site_url()."admin/wall_events/index/{$page}");
	}
	
	/**
	 * Method manage settings wall events
	**/
	function settings(){
		$this->load->model('Menu_model');
		$this->Menu_model->set_menu_active_item('admin_menu', 'add_ons_items');
		
		$data = array(
			'live_period' => $this->pg_module->get_module_config('wall_events', 'live_period'),
			//'items_per_page' => $this->pg_module->get_module_config('wall_events', 'items_per_page'),
			'events_max_count' => $this->pg_module->get_module_config('wall_events', 'events_max_count'),
		);
		
		if ($this->input->post('btn_save')) {
			
			$data['live_period'] = $this->input->post('live_period', true);
			//$data['items_per_page'] = $this->input->post('items_per_page', true);
			$data['events_max_count'] = $this->input->post('events_max_count', true);
			
			foreach ($data as $setting => $value) {
					$this->pg_module->set_module_config('wall_events', $setting, (int)$value);
			}
			
			$this->system_messages->add_message('success', l('success_update_wall_events_data', 'wall_events'));
		}
		
		$this->system_messages->set_data('header', l('admin_header_settings', 'wall_events'));
		
		$this->template_lite->assign('settings_data', $data);
		$this->template_lite->view('settings');
		
	}
}
