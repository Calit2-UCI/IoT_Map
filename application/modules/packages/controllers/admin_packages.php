<?php
/**
* Services admin side controller
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Mikhail Chernov <mchernov@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: mchernov $
**/

Class Admin_Packages extends Controller
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
		$this->Menu_model->set_menu_active_item('admin_menu', 'payments_menu_item');
		$this->load->model("Packages_model");
	}

	public function index($page=1){
		$this->load->helper('sort_order');
		$items_on_page = $this->pg_module->get_module_config('start', 'admin_items_per_page');
		$packages_count = $this->Packages_model->get_packages_count();
		$page = get_exists_page_number($page, $packages_count, $items_on_page);

		$order_by["gid"] = "ASC";
		$packages = $this->Packages_model->get_packages_list(array(), null, $order_by, $page, $items_on_page);
		$this->template_lite->assign('packages', $packages);

		$this->load->helper("navigation");
		$page_data = get_admin_pages_data(site_url()."admin/packages/index/", $packages_count, $items_on_page, $page, 'briefPage');
		$page_data["date_format"] = $this->pg_date->get_format('date_time_literal', 'st');

		$this->template_lite->assign('page_data', $page_data);
		$this->Menu_model->set_menu_active_item('admin_payments_menu', 'packages_menu_item');
		$this->system_messages->set_data('header', l('admin_header_packages_list', 'packages'));
		$this->template_lite->view('list_packages');
	}

	public function package_edit($package_id=null){
		$errors = false;

		if(!empty($package_id)){
			$data = $this->Packages_model->get_package_by_id($package_id);
			foreach($this->pg_language->languages as $lang_id=>$lang_data){
				$validate_lang[$lang_id] = l('package_name_'.$data["id"], 'packages', $lang_id);
			}

		}else{
			$data = array();
		}

		if($this->input->post('btn_save')){
			$post_data = array(
				"gid" => $this->input->post("gid", true),
				"status" => $this->input->post("status", true),
				"price" => $this->input->post("price", true),
				"pay_type" => $this->input->post("pay_type", true),
				"available_days" => $this->input->post("available_days", true),
				"langs" => $this->input->post("langs", true),
			);

			$validate_data = $this->Packages_model->validate_package($package_id, $post_data);
			$langs = $validate_data["langs"];
			if(!empty($validate_data["errors"])){
				$errors = $validate_data["errors"];
				$validate_lang = $langs;
				$data = array_merge($data, $validate_data["data"]);
			}else{
				$this->Packages_model->save_package($package_id, $validate_data["data"], $langs);

				$this->system_messages->add_message('success', l('success_update_package_data', 'packages'));
				redirect(site_url()."admin/packages/index");
			}
		}

		///// languages
		$this->template_lite->assign('languages', $this->pg_language->languages);
		$this->template_lite->assign('languages_count', count($this->pg_language->languages));
		$this->template_lite->assign('cur_lang', $this->pg_language->current_lang_id);
		$this->template_lite->assign('pay_type_lang', ld('pay_type', 'services'));
		if(!empty($validate_lang)) $this->template_lite->assign('validate_lang', $validate_lang);

		$this->template_lite->assign('data', $data);

		if(!empty($errors)){
			$this->system_messages->add_message('error', $errors);
		}

		$this->Menu_model->set_menu_active_item('admin_payments_menu', 'packages_menu_item');
		$this->system_messages->set_data('header', l('admin_header_packages_list', 'packages'));
		$this->template_lite->view('edit_package');
	}

	public function activate_package($package_id, $status=1){
		$this->Packages_model->save_package($package_id, array("status"=>$status));
		$this->system_messages->add_message('success', l('success_update_package_data', 'packages'));
		redirect(site_url()."admin/packages/index");
	}

	public function package_delete($package_id){
		$this->Packages_model->delete_package($package_id);
		$this->system_messages->add_message('success', l('success_delete_package', 'packages'));
		redirect(site_url()."admin/packages/index");
	}

	public function package_services($package_id){
		$this->load->model('Services_model');
		$this->system_messages->set_data('header', l('admin_header_packages_list', 'packages'));
		$package = $this->Packages_model->get_package_by_id($package_id);
		$param['where']['price_type'] = 1;
		$templates = $this->Services_model->get_template_list($param);
		$s_params['where_in']['template_gid'] = array_keys($templates);
		if (is_array($package['services_list_array'])){
			$s_params['where_not_in']['id'] = array_keys($package['services_list_array']);
		}
		$s_params['where']['type'] = 'tariff';
		$s_params['where']['status'] = 1;
		$services = $this->Services_model->get_service_list($s_params);
		$this->template_lite->assign('package', $package);
		$this->template_lite->assign('total_price', $this->Packages_model->get_package_services_price($package['services_list'], $package['services_list_array']));
		$this->template_lite->assign('services', $services);
		$this->template_lite->view('package_services');
	}

	public function ajax_save_package_services($package_id){
		$services = $this->input->post("services");
		$data['services_list'] = serialize($services);
		$this->Packages_model->save_package($package_id, $data);
	}
}
