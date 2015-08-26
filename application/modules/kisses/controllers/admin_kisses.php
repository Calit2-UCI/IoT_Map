<?php

/**
 * Kisses admin side controller
 *
 * @package PG_DatingPro
 * @subpackage Kisses
 * @category	controllers
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * */
class Admin_Kisses extends Controller {

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::Controller();
		$this->load->model('Menu_model');
		$this->Menu_model->set_menu_active_item('admin_menu', 'add_ons_items');
		$this->load->model('Kisses_model');
		$this->load->model('Uploads_model');
	}
	
	/**
	 * Main page
	 */
	public function index($order="sorter", $order_direction="ASC", $page = 1) {
		
		$current_settings = isset($_SESSION["kisses_list"])?$_SESSION["kisses_list"]:array();
		if(!isset($current_settings["order"])) $current_settings["order"] = $order;
		if(!isset($current_settings["order_direction"])) $current_settings["order_direction"] = $order_direction;
		if(!isset($current_settings["page"])) $current_settings["page"] = $page;
		
		$kisses_count = $this->Kisses_model->get_count();
		
		if( !$this->pg_module->get_module_config('kisses', 'system_settings_page') ){
			$items_on_page = $this->pg_module->get_module_config('kisses', 'admin_items_per_page');
		} else {
			$items_on_page = $this->pg_module->get_module_config('start', 'admin_items_per_page');
		}
		
		if(!$order) $order = $current_settings["order"];
		$this->template_lite->assign('order', $order);
		$current_settings["order"] = $order;
		
		if(!$order_direction) $order_direction = $current_settings["order_direction"];
		$this->template_lite->assign('order_direction', $order_direction);
		$current_settings["order_direction"] = $order_direction;
		
		if(!$page) $page = $current_settings["page"];
		$this->load->helper('sort_order');
		$page = get_exists_page_number($page, $kisses_count, $items_on_page);
		$current_settings["page"] = $page;
		
		$_SESSION["kisses_list"] = $current_settings;
		
		$sort_links = array(
			"sorter" => site_url()."admin/kisses/index/sorter/".(($order!='sorter' xor $order_direction=='DESC')?'ASC':'DESC'),
			"date_created" => site_url()."admin/kisses/index/date_created/".(($order!='date_created' xor $order_direction=='DESC')?'ASC':'DESC'),
		);
		
		$this->template_lite->assign('sort_links', $sort_links);
		
		$kisses = $this->Kisses_model->get_list($page, $items_on_page, array(), array($order => $order_direction), array());
		
		$mediafile = array();
		$file_url = '';
		
		foreach($kisses as $media){
			$mediafile[0] = $this->Uploads_model->format_upload($this->Kisses_model->image_upload_gid, '', $media['image']);
			/*echo '<pre>';
			print_r($mediafile);
			echo '</pre>';*/
			$file_url = $mediafile[0]['url'] . "kisses-";
			break;
		}
		
		$this->load->helper("navigation");
		$url = site_url()."admin/kisses/index/{$order}/{$order_direction}/";
		$page_data = get_admin_pages_data($url, $kisses_count, $items_on_page, $page, 'briefPage');
		$page_data["date_format"] = $this->pg_date->get_format('date_time_literal', 'st');
		$this->template_lite->assign('page_data', $page_data);
		
		$this->template_lite->assign('kisses', $kisses);
		$this->template_lite->assign('file_url', $file_url);
		
		$this->system_messages->set_data('header', l('admin_header_kisses_list', 'kisses'));
		$this->template_lite->view('index');
	}
	
	/**
	 * Method get files
	 * @var $_POST
	 * return json error or success
	 */
	public function post_upload() {
		
		$result = array();
		$validate_data = $this->Kisses_model->validate('multiupload');
		if(!$validate_data['errors']){
			$result = $this->Kisses_model->_post_upload('multiupload');
		}else{
			$result['errors'] = $validate_data['errors'];
			//$this->system_messages->add_message('error', $result['errors']);
		}
		
		echo json_encode($result);
	}
	
	/**
	 * Method get confirm window
	 * return string
	 */
	public function ajax_confirm_delete_select(){
		echo $this->template_lite->view('ajax_delete_select_block');
	}
	
	/**
	 * Method deleted kisses after confirm
	 * @var $kisses_id integer kisses id
	 * return error or success
	 */
	public function ajax_delete_select(){
		$kisses_id = $this->input->post("file_ids", true);
		$this->delete_kisses($kisses_id, true);
	}
	
	/**
	 * Method deleted kisses files
	 * @var $kisses_id integer kisses id
	 * @var $message bool echo message
	 * return error or success
	 */
	public function delete_kisses($kisses_id = null, $message = true){
		
		if( !empty($kisses_id) ){
			
			foreach((array)$kisses_id as $object_id){
				$result = $this->Kisses_model->delete_kisses($object_id);
			}
			
			if(!empty($result['errors']) && $message){
				$this->system_messages->add_message('error', $result['errors']);
			}elseif($message){
				$this->system_messages->add_message('success', l('success_delete_kisses', 'kisses'));
			}
			
		}
		
		if($message){
			redirect($_SERVER["HTTP_REFERER"]);
		} else { return; }
	}
	
	/**
	 * Method add kisses files
	 * return error or success
	 */
	public function add(){
		
		$image_upload_config = $this->Uploads_model->get_config($this->Kisses_model->image_upload_gid);
		
		$max_upload_size = ($image_upload_config['max_size'] == 0) ? 0 : $image_upload_config['max_size'];
		
		$kisses_params = array(
			'id' => 'kisses',
			'url_upload' => "admin/kisses/post_upload/",
			'view_button_title' => l('btn_view_more', 'start'),
			'image_lng' => l('image', 'kisses'),
			'image_upload_config' => $image_upload_config,
			'max_upload_size' => $max_upload_size,
			'allowed_mimes' => array_merge($image_upload_config['allowed_mimes']),
		);
		
		$this->system_messages->set_data('header', l('kisses', 'kisses'));
		$this->Menu_model->set_menu_active_item('admin_kisses_menu', 'kisses_list_item');
		$this->template_lite->assign('kisses_params', $kisses_params);
		$this->template_lite->view('form_add');
		
	}
	
	/**
	 * Method edit kisses files
	 * @var $kisses_id integer kisses id
	 * return error or success
	 */
	public function edit($kisses_id = 0){
		
		if( !empty($kisses_id) ){
			
			$kisses = $this->Kisses_model->get_kisses_by_id($kisses_id);
			if(!$kisses){
				$result['error'] = $this->system_messages->add_message('error', l('error_empty_kiss_id', 'kisses'));
			}
			
			if($this->input->post('btn_save')){
				$post_data = $this->input->post('data', true);
				
				$validate_data = $this->Kisses_model->validate_kisses($kisses_id, $post_data);
				if(!empty($validate_data['errors'])){
					$this->system_messages->add_message('error', $validate_data['errors']);
				}else{
					if(!empty($validate_data['data'])){
						$this->Kisses_model->save_kisses($kisses_id, $validate_data['data']);
						$this->system_messages->add_message('success', l('success_update_kisses', 'kisses'));
						
						$url = site_url().'admin/kisses/';
						redirect($url);
					}
				}
			}
			
			$this->template_lite->assign('current_lang_id', $this->pg_language->current_lang_id);
			$this->template_lite->assign('langs', $this->pg_language->languages);
			
			$file_url = $this->Uploads_model->format_upload($this->Kisses_model->image_upload_gid, '', $kisses['image']);
			
			$this->template_lite->assign('data', $kisses);
			$this->template_lite->assign('id', $kisses['id']);
			$this->template_lite->assign('file_url', $file_url['file_url']);
			$this->template_lite->view('form_edit');
			
		} else {
			return $this->system_messages->add_message('error', l('error_empty_kiss_id', 'kisses'));
		}
		
	}
	
	/**
	 * Method save position on page
	 * return error or success
	 */
	function ajax_save_sorter(){
		
		$sorter = $this->input->post("sorter");
		$return = array();
		
		foreach($sorter as $parent_str => $items_data){
			
			foreach($items_data as $item_str =>$sort_index){
				$page_id = intval(str_replace("item_", "", $item_str));
				$data = array(
					"sorter" => $sort_index
				);
				$return = $this->Kisses_model->save_page($page_id, $data);
			}
			echo $this->system_messages->add_message('success', l('success_save_sorter', 'kisses'));
		}
		
	}
	
	/**
	 * Setting module
	 * 
	 * @return template
	 */
	public function settings(){
		
		$this->system_messages->set_data('header', l('admin_header_kisses_settings', 'kisses'));
		
		$data = array(
			'admin_items_per_page' => $this->pg_module->get_module_config('kisses', 'admin_items_per_page'),
			'items_per_page' => $this->pg_module->get_module_config('kisses', 'items_per_page'),
			'system_settings_page' => $this->pg_module->get_module_config('kisses', 'system_settings_page'),
			'number_max_symbols' => $this->pg_module->get_module_config('kisses', 'number_max_symbols'),
		);
		
		if ($this->input->post('btn_save')) {
			
			$data['admin_items_per_page'] = $this->input->post('admin_items_per_page', true);
			$data['items_per_page'] = $this->input->post('items_per_page', true);
			$data['system_settings_page'] = $this->input->post('system_settings_page', true);
			$data['number_max_symbols'] = $this->input->post('number_max_symbols', true);
			
			foreach ($data as $setting => $value) {
					$this->pg_module->set_module_config('kisses', $setting, (int)$value);
			}
			
			$this->system_messages->add_message('success', l('success_settings_saved', 'kisses'));
		}
		
		$this->template_lite->assign('settings_data', $data);
		
		$this->template_lite->view('settings');
	}
}
