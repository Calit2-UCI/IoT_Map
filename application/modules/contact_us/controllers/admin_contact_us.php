<?php
/**
* Contact us admin side controller
* 
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

Class Admin_Contact_us extends Controller
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
		$this->load->model("Contact_us_model");

		$this->load->model('Menu_model');
		$this->Menu_model->set_menu_active_item('admin_menu', 'content_items');
	}
	
	public function index(){
		$attrs = $search_params = array();
		$reasons_count = $this->Contact_us_model->get_reason_count();

		if ($reasons_count > 0){
			$reasons = $this->Contact_us_model->get_reason_list();
			$this->template_lite->assign('reasons', $reasons);

		}
		$this->load->helper("navigation");
		$url = site_url()."admin/contact_us";
		$page_data = get_admin_pages_data($url, $reasons_count, ($reasons_count?$reasons_count:10), 1, 'briefPage');
		$this->template_lite->assign('page_data', $page_data);


		$this->Menu_model->set_menu_active_item('admin_contacts_menu', 'reasons_list_item');
		$this->system_messages->set_data('header', l('admin_header_reasons_list', 'contact_us'));
		$this->template_lite->view('list');
	}

	public function edit($id=null){
		if($id){
			$data = $this->Contact_us_model->get_reason_by_id($id);
		}else{
			$data = array();
		}

		if($this->input->post('btn_save')){
			$post_data = array(
				"mails" => $this->input->post('mails', true),
			);

			$name = $this->input->post('name', true);

			$validate_data = $this->Contact_us_model->validate_reason($id, $post_data, $name);
			if(!empty($validate_data["errors"])){
				$this->system_messages->add_message('error', $validate_data["errors"]);
			}else{

				$flag_add = empty($id)?true:false;
				$data["id"] = $id = $this->Contact_us_model->save_reason($id, $validate_data["data"], $validate_data["langs"]);

				$this->system_messages->add_message('success', (!$flag_add)?l('success_update_reason', 'contact_us'):l('success_add_reason', 'contact_us'));
				redirect(site_url()."admin/contact_us");
			}
			$data = array_merge($data, $validate_data["data"]);
			$this->template_lite->assign('validate_lang', $validate_data["langs"]);
			$temp = $this->Contact_us_model->format_reasons(array($data));
			$data = $temp[0];
		}


		$this->template_lite->assign('data', $data);
		$this->template_lite->assign('languages', $this->pg_language->languages);
		$this->template_lite->assign('languages_count', count($this->pg_language->languages));
		$this->template_lite->assign('cur_lang', $this->pg_language->current_lang_id);

		$this->Menu_model->set_menu_active_item('admin_contacts_menu', 'reasons_list_item');
		$this->system_messages->set_data('header', l('admin_header_reasons_list', 'contact_us'));
		$this->template_lite->view('edit');
	}
	
	public function delete($id){
		if(!empty($id)){
			$this->Contact_us_model->delete_reason($id);
			$this->system_messages->add_message('success', l('success_delete_reason', 'contact_us'));
		}
		redirect(site_url()."admin/contact_us");
	}

	public function settings(){
		$data = $this->Contact_us_model->get_settings();

		if($this->input->post('btn_save')){
			$post_data = array(
				"default_alert_email" => $this->input->post('default_alert_email', true),
			);
			
			$validate_data = $this->Contact_us_model->validate_settings($post_data);
			if(!empty($validate_data["errors"])){
				$this->system_messages->add_message('error', $validate_data["errors"]);
			}else{
				$this->Contact_us_model->set_settings($validate_data["data"]);
				$this->system_messages->add_message('success', l('success_settings_save', 'contact_us'));
				redirect(site_url()."admin/contact_us/settings");
			}
		}
		
		$this->template_lite->assign('data', $data);
		$this->Menu_model->set_menu_active_item('admin_contacts_menu', 'settings_list_item');
		$this->system_messages->set_data('header', l('admin_header_settings', 'contact_us'));
		$this->template_lite->view('settings');
	}
}