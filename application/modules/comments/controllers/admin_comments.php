<?php
/**
* Admin comments controller
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Dmitry Popenov
* @version $Revision: 2 $ $Date: 2013-01-30 10:07:07 +0400 $
**/
class Admin_Comments extends Controller {
	/**
	 * Controller
	 */
	function __construct()
	{
		parent::Controller();
		$this->load->model('comments/models/Comments_types_model');
		$this->load->model('Menu_model');
		$this->Menu_model->set_menu_active_item('admin_menu', 'system-items');
	}

	/*
	 * COMMENTS FUNCTIONS
	 */

	public function index($page = 1){
		$items_on_page = $this->pg_module->get_module_config('start', 'admin_items_per_page');
		$this->load->helper('sort_order');
		$comments_types_cnt = $this->Comments_types_model->get_comments_types_cnt();
		$page = get_exists_page_number($page, $comments_types_cnt, $items_on_page);

		$this->load->helper("navigation");
		$url = site_url()."admin/comments/index/";
		$pages_data = get_admin_pages_data($url, $comments_types_cnt, $items_on_page, $page, 'briefPage');
		$this->template_lite->assign('page_data', $pages_data);

		$comments_types = $this->Comments_types_model->get_comments_types($page, $items_on_page);
		$this->template_lite->assign('comments_types', $comments_types);
		
		$_SESSION["comments_types"]["page"] = $page;

		$this->Menu_model->set_menu_active_item('admin_comments_menu', 'comments_list_item');
		$this->system_messages->set_data('header', l('admin_header_list', 'comments'));
		$this->template_lite->view('comments_types');
	}
	
	public function ajax_activate_type(){
		$id = $this->input->post('id', true);
		$status = $this->input->post('status', true) ? '1' : '0';
		$return['status'] = $this->Comments_types_model->save_comments_type($id, array('status'=>$status));
		exit(json_encode($return));
	}
	
	public function edit_type($id){
		$comments_type = $this->Comments_types_model->get_comments_type_by_id($id);
		$this->template_lite->assign('comments_type', $comments_type);
		
		$data['page'] = isset($_SESSION['comments_types']['page']) && $_SESSION['comments_types']['page'] ? $_SESSION['comments_types']['page'] : 1;
		$data['action'] = site_url()."admin/comments/save_type/{$id}";
		$this->template_lite->assign('data', $data);
		$this->template_lite->view('form_comments_type');
	}
	
	public function save_type($id){
		$id = intval($id);
		$page = isset($_SESSION['comments_types']['page']) && $_SESSION['comments_types']['page'] ? $_SESSION['comments_types']['page'] : 1;
		if(!$id){
			redirect(site_url()."admin/comments/index/{$page}");
		}
		$params['status'] = $this->input->post('status', true);
		$params['settings']['use_likes'] = $this->input->post('use_likes', true);
		$params['settings']['use_spam'] = $this->input->post('use_spam', true);
		$params['settings']['use_moderation'] = $this->input->post('use_moderation', true);
		$params['settings']['guest_access'] = $this->input->post('guest_access', true);
		$params['settings']['char_count'] = intval($this->input->post('char_count', true));
		$result = $this->Comments_types_model->save_comments_type($id, $params);
		$this->system_messages->add_message('success', l('success_update_comment_data', 'comments'));
		redirect(site_url()."admin/comments/index/{$page}");
	}
}