<?php
/**
* Payments admin side controller
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

Class Admin_Payments extends Controller
{
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::Controller();

		$this->load->model('Menu_model');
		$this->Menu_model->set_menu_active_item('admin_menu', 'payments_menu_item');
	}

	public function index($filter="all", $payment_type_gid='', $system_gid='', $order="date_add", $order_direction="DESC", $page=1){
		$this->load->model("Payments_model");
		$search_params = $params = array();

		if(!in_array($filter, array("all", "wait", "approve", "decline"))){
			$filter = "all";
		}

		$current_settings = isset($_SESSION["pay_list"])?$_SESSION["pay_list"]:array();
		if (!isset($current_settings["filter"])) {
			$current_settings["filter"] = "all";
		}
		if (!isset($current_settings["payment_type_gid"])) {
			$current_settings["payment_type_gid"] = "all";
		}
		if (!isset($current_settings["system_gid"])) {
			$current_settings["system_gid"] = "all";
		}
		if (!isset($current_settings["order"])) {
			$current_settings["order"] = "date_add";
		}
		if (!isset($current_settings["order_direction"])) {
			$current_settings["order_direction"] = "DESC";
		}
		if (!isset($current_settings["page"])) {
			$current_settings["page"] = 1;
		}
		if (empty($payment_type_gid)) {
			$payment_type_gid = $current_settings["payment_type_gid"];
		}
		$this->template_lite->assign('payment_type_gid', $payment_type_gid);
		$current_settings["payment_type_gid"] = $payment_type_gid;

		if($payment_type_gid != '' && $payment_type_gid != 'all'){
			$params["where"]['payment_type_gid'] = $search_params["where"]["payment_type_gid"] = $payment_type_gid;
		}

		if (empty($system_gid)) {
			$system_gid = $current_settings["system_gid"];
		}
		$this->template_lite->assign('system_gid', $system_gid);
		$current_settings["system_gid"] = $system_gid;

		if($system_gid != '' && $system_gid != 'all'){
			$params["where"]['system_gid'] = $search_params["where"]["system_gid"] = $system_gid;
		}


		$filter_data["all"] = $this->Payments_model->get_payment_count();
		$params["where"]["status"] = 0;
		$filter_data["wait"] = $this->Payments_model->get_payment_count($params);
		$params["where"]["status"] = 1;
		$filter_data["approve"] = $this->Payments_model->get_payment_count($params);
		$params["where"]["status"] = -1;
		$filter_data["decline"] = $this->Payments_model->get_payment_count($params);

		$this->template_lite->assign('filter', $filter);
		$this->template_lite->assign('filter_data', $filter_data);

		if (!$order) {
			$order = $current_settings["order"];
		}
		$this->template_lite->assign('order', $order);
		$current_settings["order"] = $order;

		if (!$order_direction) {
			$order_direction = $current_settings["order_direction"];
		}
		$this->template_lite->assign('order_direction', $order_direction);
		$current_settings["order_direction"] = $order_direction;

		$payments_count = $filter_data[$filter];

		if (!$page) {
			$page = $current_settings["page"];
		}
		$items_on_page = $this->pg_module->get_module_config('start', 'admin_items_per_page');
		$this->load->helper('sort_order');
		$page = get_exists_page_number($page, $payments_count, $items_on_page);
		$current_settings["page"] = $page;

		$_SESSION["pay_list"] = $current_settings;

		$sort_links = array(
			"amount" => site_url()."admin/payments/index/".$filter."/".$payment_type_gid."/".$system_gid."/amount/".(($order!='amount' xor $order_direction=='DESC')?'ASC':'DESC'),
			"date_add" => site_url()."admin/payments/index/".$filter."/".$payment_type_gid."/".$system_gid."/date_add/".(($order!='date_add' xor $order_direction=='DESC')?'ASC':'DESC'),
		);

		$this->template_lite->assign('sort_links', $sort_links);

		if ($payments_count > 0){
			switch($filter){
				case "all": break;
				case "wait": $search_params["where"]["status"] = 0; break;
				case "approve": $search_params["where"]["status"] = 1; break;
				case "decline": $search_params["where"]["status"] = -1; break;
			}

			$payments = $this->Payments_model->get_payment_list( $page, $items_on_page, array($order => $order_direction), $search_params);
			$this->template_lite->assign('payments', $payments);
		}
		$this->load->helper("navigation");
		$url = site_url()."admin/payments/index/".$filter."/".$payment_type_gid."/".$system_gid."/".$order."/".$order_direction."/";
		$page_data = get_admin_pages_data($url, $payments_count, $items_on_page, $page, 'briefPage');
		$page_data["date_format"] = $this->pg_date->get_format('date_time_literal', 'st');
		$this->template_lite->assign('page_data', $page_data);

		$payment_types = $this->Payments_model->get_payment_type_list();
		$this->template_lite->assign('payment_types', $payment_types);

		$this->load->model("payments/models/Payment_systems_model");
		$systems = $this->Payment_systems_model->get_system_list();
		$this->template_lite->assign('systems', $systems);

		$this->Menu_model->set_menu_active_item('admin_payments_menu', 'payments_list_item');
		$this->system_messages->set_data('header', l('admin_header_payments_list', 'payments'));
		$this->template_lite->view('list_payments');
	}

	public function payment_status($status_txt, $id_payment){

		$this->load->model("Payments_model");
		$payment_data = $this->Payments_model->get_payment_by_id($id_payment);

		switch($status_txt){
			case "approve":
				$payment_data["status"] = 1;
				$message = l('success_approve_payment', 'payments');

				if($payment_data['system_gid'] == 'offline'){
					$this->load->model('Users_model');
					$user = $this->Users_model->get_user_by_id($payment_data['id_user']);
					$user = $this->Users_model->format_user($user);
					$mail_data['user'] = $user['output_name'];
					$mail_data['payment'] = $payment_data['payment_data']['name'];
					$mail_data['status'] = l('payment_status_approved', 'payments', $user['lang_id']);

					$this->load->model("Notifications_model");
					$this->Notifications_model->send_notification($user['email'], "payment_status_updated", $mail_data, '', $user['lang_id']);
				}
			break;
			case "decline":
				$payment_data["status"] = -1;
				$message = l('success_decline_payment', 'payments');

				if($payment_data['system_gid'] == 'offline'){
					$this->load->model('Users_model');
					$user = $this->Users_model->get_user_by_id($payment_data['id_user']);
					$user = $this->Users_model->format_user($user);
					$mail_data['user'] = $user['output_name'];
					$mail_data['payment'] = $payment_data['payment_data']['name'];
					$mail_data['status'] = l('payment_status_declined', 'payments');

					$this->load->model("Notifications_model");
					$this->Notifications_model->send_notification($user['email'], "payment_status_updated", $mail_data, '', $user['lang_id']);
				}
			break;
			default:
				$payment_data["status"] = 0;
				$message="";
		}
		$this->load->helper('payments');
		receive_payment('manual', $payment_data);
		if ($message) {
			$this->system_messages->add_message('success', $message);
		}

		$cur_set = $_SESSION["pay_list"];
		$url = site_url()."admin/payments/index/".$cur_set["filter"]."/".$cur_set["payment_type_gid"]."/".$cur_set["system_gid"]."/".$cur_set["order"]."/".$cur_set["order_direction"]."/";
		redirect($url);
	}

	public function systems($filter="all"){
		$this->load->model("payments/models/Payment_systems_model");

		if(!in_array($filter, array("all", "used"))){
			$filter = "all";
		}

		$current_settings = isset($_SESSION["systems_list"])?$_SESSION["systems_list"]:array();
		if (!isset($current_settings["filter"])) {
			$current_settings["filter"] = $filter;
		}
		$_SESSION["systems_list"] = $current_settings;

		$filter_data["all"] = $this->Payment_systems_model->get_system_count();
		$params = array();
		$params["where"]["in_use"] = 1;
		$filter_data["used"] = $this->Payment_systems_model->get_system_count($params);

		$this->template_lite->assign('filter', $filter);
		$this->template_lite->assign('filter_data', $filter_data);

		$systems_count = $filter_data[$filter];

		if ($systems_count > 0){
			switch($filter){
				case "all": $params = array(); break;
				case "used": $params["where"]["in_use"] = 1; break;
			}

			$order_by["name"] = "ASC";
			$systems = $this->Payment_systems_model->get_system_list($params, null, $order_by);
			$this->template_lite->assign('systems', $systems);
		}

		$this->load->helper("navigation");
		$page_data["date_format"] = $this->pg_date->get_format('date_time_literal', 'st');
		$this->template_lite->assign('page_data', $page_data);

		$this->Menu_model->set_menu_active_item('admin_payments_menu', 'systems_list_item');
		$this->system_messages->set_data('header', l('admin_header_systems_list', 'payments'));
		$this->template_lite->view('list_systems');
	}
	
	/**
	 *  Install payments 
	 *   @return void
	 */
	public function install() {
		$this->load->model("payments/models/Payment_systems_model");
		$payments = array();
		
		$path = MODULEPATH . 'payments/models/systems/';
		if (!is_dir($path)) {
			continue;
		}
		$allFiles = scandir($path);
		$files = array_diff($allFiles, array('.', '..'));
		foreach ($files as $file) {
			$result = explode('.', $file);
			if(!empty($result[1])){
				$payment_gid = explode('_', $result[0]);
				$data = $this->Payment_systems_model->get_system_by_gid($payment_gid[0]);
				if(empty($data)){
					$this->Payment_systems_model->load_driver($payment_gid[0]);
					$system_data = $this->Payment_systems_model->get_system_data();
					if(!empty($system_data)){
						$payments[$payment_gid[0]] = $system_data;
					}
				}
			}
		}
		
		$filter_data["all"] = $this->Payment_systems_model->get_system_count();
		$params = array();
		$params["where"]["in_use"] = 1;
		$filter_data["used"] = $this->Payment_systems_model->get_system_count($params);
		$this->template_lite->assign('filter_data', $filter_data);
		
		$this->template_lite->assign('payments', $payments);
		$this->Menu_model->set_menu_active_item('admin_payments_menu', 'systems_list_item');
		$this->system_messages->set_data('header', l('admin_header_systems_list', 'payments'));
		$this->template_lite->view('list_install');
	}
	
	public function install_payment($gid = null){
		if(!empty($gid)){
			$this->load->model("payments/models/Payment_systems_model");
			$this->Payment_systems_model->load_driver($gid);
			$system_data = $this->Payment_systems_model->get_system_data();
			if(!empty($system_data)){
				$payment_id = $this->Payment_systems_model->save_system(null, $system_data);
				if(isset($payment_id)){
					redirect(site_url()."admin/payments/system_edit/".$gid);
				}
			}
		}
	}

	public function system_edit($system_gid){
		$this->load->model("payments/models/Payment_systems_model");

		$data = $this->Payment_systems_model->get_system_by_gid($system_gid);
		$this->Payment_systems_model->load_driver($system_gid);
		$data["map"] = $this->Payment_systems_model->get_system_data_map();

		if(!empty($data["map"])){
			foreach(array_keys($data["map"]) as $param_id){
				if(isset($data["settings_data"][$param_id])){
					$data["map"][$param_id]["value"] = $data["settings_data"][$param_id];
				}else{
					$data["map"][$param_id]["value"] = "";
				}
			}
		}

		if($this->input->post('btn_save')){
			$validate_settings_data = $this->Payment_systems_model->validate_system_settings($this->input->post("map", true));
                        $validate_tarifs_data = $this->Payment_systems_model->validate_tarifs_data($this->input->post("tarifs_data", true));
			$validate_info_data = $this->Payment_systems_model->validate_info_data($this->input->post("info", true));
			if(!empty($validate_settings_data["errors"]) || $validate_info_data['errors']){
				$this->system_messages->add_message('error', array_merge($validate_settings_data["errors"], $validate_info_data["errors"], $validate_tarifs_data['errors']));
			}else{
				$system_post_data["gid"] = $data["gid"];
				$system_post_data["settings_data"] = $validate_settings_data["data"];
                                $system_post_data["tarifs_data"] = $validate_tarifs_data["data"];
				$system_post_data["info_data"] = $validate_info_data["data"];
                                if($data['tarifs_editable']) $system_post_data["tarifs_type"] = $this->input->post("tarifs_type", true);
				$system_data = $this->Payment_systems_model->validate_system($data["id"], $system_post_data);
				$this->upload_logo($system_post_data["gid"]);
				if(!empty($system_data["errors"])){
					$this->system_messages->add_message('error', $system_data["errors"]);
				}else{
					$this->Payment_systems_model->save_system($data["id"], $system_data["data"]);
				}

				$this->system_messages->add_message('success', l('success_update_system_data', 'payments'));
				$cur_set = $_SESSION["systems_list"];
				redirect(site_url()."admin/payments/systems/".$cur_set["filter"]);
			}
		}

		$this->template_lite->assign('data', $data);
                
                $system_tarifs_block = $this->_get_system_tarifs($data);
		$this->template_lite->assign('system_tarifs_block', $system_tarifs_block);

		$this->template_lite->assign('current_lang_id', $this->pg_language->current_lang_id);
		$this->template_lite->assign('langs', $this->pg_language->languages);

		$this->Menu_model->set_menu_active_item('admin_payments_menu', 'systems_list_item');
		$this->system_messages->set_data('header', l('admin_header_systems_list', 'payments'));
		$this->template_lite->view('edit_system');
	}

	private function upload_logo($system_gid) {
		// Logo
		if(isset($_FILES["logo"]) && is_array($_FILES["logo"]) && is_uploaded_file($_FILES["logo"]["tmp_name"])){
			$size = array(
				'width' => 100,
				'height' => 100
			);
			$upload = $this->Payment_systems_model->upload_logo($system_gid, $size);
			if(!empty($upload["error"])){
				$this->system_messages->add_message('error', $upload["error"]);
			}else{
				$this->system_messages->add_message('success', l('success_uploaded_logo', 'themes'));
			}
		}elseif($this->input->post('logo_delete') == '1'){
			$this->Payment_systems_model->delete_logo($system_gid);
			$this->system_messages->add_message('success', l('success_delete_logo', 'payments'));
		}
	}

	public function system_use($system_gid, $status=0){
		$this->load->model("payments/models/Payment_systems_model");
		$this->Payment_systems_model->use_system($system_gid, $status);
		$this->system_messages->add_message('success', $status?l('success_activate_system', 'payments'):l('success_deactivate_system', 'payments'));
		$cur_set = $_SESSION["systems_list"];
		redirect(site_url()."admin/payments/systems/".$cur_set["filter"]);
	}

	public function settings(){
		$this->load->model("payments/models/Payment_currency_model");

		$currency = $this->Payment_currency_model->get_currency_list(null, null, array("gid"=>"ASC"));
		$this->template_lite->assign('currency', $currency);

		$updaters = $this->Payment_currency_model->rates_updaters;
		$this->template_lite->assign("updaters", $updaters);

		$use_rates_update = $this->pg_module->get_module_config("payments", "use_rates_update");
		$this->template_lite->assign("use_rates_update", $use_rates_update);

		$rates_update_driver = $this->pg_module->get_module_config("payments", "rates_update_driver");
		$this->template_lite->assign("rates_update_driver", $rates_update_driver);

		$this->Menu_model->set_menu_active_item('admin_payments_menu', 'settings_list_item');
		$this->system_messages->set_data('header', l('admin_header_currency_list', 'payments'));
		$this->template_lite->view('list_settings');
	}

	public function settings_edit($id = null){
		$this->load->model("payments/models/Payment_currency_model");
		if($id){
			$data = $this->Payment_currency_model->get_currency_by_id($id);
		}else{
			$data = array();
		}
		if($this->input->post('btn_save')){
			$template = $this->input->post("template", true);
			$gr_sep = $this->input->post("gr_sep", true);
			$dec_sep = $this->input->post("dec_sep", true);
			$dec_part = $this->input->post("dec_part", true);
			// Add the number format
			$template = str_replace('value', 'value' .
					'|dec_part:' . $dec_part .
					'|dec_sep:' . $dec_sep .
					'|gr_sep:' . $gr_sep, $template);

			$post_data = array(
				"gid" => $this->input->post("gid", true),
				"abbr" => $this->input->post("abbr", true),
				"template" => $template,
				"name" => $this->input->post("name", true),
				"per_base" => $this->input->post("per_base", true),
			);
			$validate_data = $this->Payment_currency_model->validate_currency($id, $post_data);
			if(!empty($validate_data["errors"])){
				$this->system_messages->add_message('error', $validate_data["errors"]);
			} else {
				$this->Payment_currency_model->save_currency($id, $validate_data["data"]);
				$this->system_messages->add_message('success', ($id) ? l('success_update_currency', 'payments') : l('success_add_currency', 'payments'));
				redirect(site_url() . "admin/payments/settings");
			}
			$data = array_merge($data, $validate_data["data"]);
		}

		$matches = array();
		// Parse the number format
		preg_match('/value\|([^]]*)/', $data['template'], $matches);
		$params = explode('|', $matches[1]);
		$params_str = '';
		foreach($params as $param) {
			$param_arr = explode(':', $param);
			// Used parameters
			$number[$param_arr[0]] = $param_arr[1];
			$params_str .= '|' . $param_arr[0] . ':' . $param_arr[1];
		}
		// Remove the number format from template
		$data['template'] = str_replace($params_str, '', $data['template']);

		$base_currency = $this->pg_module->get_module_config("payments", "base_currency");
		$this->template_lite->assign("base_currency", $base_currency);

		$format = array (
			'template' => array (
				'[abbr][value]',
				'[abbr] [value]',
				'[value][abbr]',
				'[value] [abbr]',
				'[gid][value]',
				'[gid] [value]',
				'[value][gid]',
				'[value] [gid]',
			),
			'dec_sep' => array(
				'period'	=> '.',
				'comma'		=> ','
			),
			'gr_sep' => array (
				'period'	=> '.',
				'comma'		=> ',',
				'space'		=> ' ',
				'empty'		=> ''
			),
			'dec_part'		=> array (
				'one'		=> '1',
				'two'		=> '2',
				'dash'		=> '-',
				'none'		=> ''
			),
			'used' => array (
				'template'	=> $data['template'],
				'dec_sep'	=> $number['dec_sep'],
				'gr_sep'	=> $number['gr_sep'],
				'dec_part'	=> $number['dec_part']
			)
		);
		$this->pg_theme->add_js('admin-payments.js', 'payments');
		$this->Menu_model->set_menu_active_item('admin_payments_menu', 'settings_list_item');
		$this->system_messages->set_data('header', l('admin_header_currency_list', 'payments'));
		$this->template_lite->assign('format', $format);
		$this->template_lite->assign('data', $data);
		$this->template_lite->view('edit_settings');
	}

	public function settings_delete($id){
		$this->load->model("payments/models/Payment_currency_model");
		$data = $this->Payment_currency_model->get_currency_by_id($id);
		if($data["is_default"]){
			$this->system_messages->add_message('error', l('error_delete_is_default_currency', 'payments'));
		} else {
			$this->Payment_currency_model->delete_currency($id);
			$this->system_messages->add_message('success', l('success_delete_currency', 'payments'));
		}
		redirect(site_url()."admin/payments/settings");
	}
	public function settings_use($id){
		$this->load->model("payments/models/Payment_currency_model");
		$this->Payment_currency_model->set_default($id);
		$this->system_messages->add_message('success', l('success_activate_currency', 'payments'));
		redirect(site_url()."admin/payments/settings");
	}

	/**
	 * Change currency rates settings
	 */
	public function update_currency_rates(){
		switch(true){
			case $this->input->post("bt_auto_x") || $this->input->post("bt_auto_y"):
				$status = $this->input->post("use_rates_update");
				$rates_driver = $this->input->post("rates_driver");
				$this->pg_module->set_module_config("payments", "use_rates_update", ($status ? 1 : 0));
				$this->pg_module->set_module_config("payments", "rates_update_driver", $rates_driver);
				if($status){
					$this->system_messages->add_message('success', l('success_rates_update_turn_on', 'payments'));
				}else{
					$this->system_messages->add_message('success', l('success_rates_update_turn_off', 'payments'));
				}
			break;
			case $this->input->post("bt_manual_x") || $this->input->post("bt_manual_y"):
				$this->load->model("payments/models/Payment_currency_model");
				$rates_driver = $this->input->post("rates_driver");
				$result = $this->Payment_currency_model->update_currency_rates($rates_driver);
				if(!empty($result["errors"])){
					$this->system_messages->add_message('errors', implode("<br>", $result["errors"]));
				}else{
					$this->system_messages->add_message('success', l('success_rates_updated', 'payments'));
				}
			break;
		}
		redirect(site_url()."admin/payments/settings");
	}

	/**
	 * Turn on automatic update currency rates by ajax
	 * @param integer $status
	 */
	public function ajax_use_rates_update($status){
		$response = array("error"=>"", "success"=>"");
		$this->pg_module->set_module_config("payments", "use_rates_update", ($status ? 1 : 0));
		if($status){
			$response["success"] = l('success_rates_update_turn_on', 'payments');
		}else{
			$response["success"] = l('success_rates_update_turn_off', 'payments');
		}
		echo json_encode($response);
		exit;
	}

	/**
	 * Save updater of rates
	 * @param integer $updater
	 */
	public function ajax_rates_driver_update($updater){
		$response = array("error"=>"", "success"=>"");
		$this->pg_module->set_module_config("payments", "rates_update_driver", $updater);
		$response["success"] = l('success_rates_driver_updated', 'payments');
		echo json_encode($response);
		exit;
	}

	/**
	 * Update currency rates by ajax
	 * @param string $updater
	 */
	public function ajax_currency_rates_update($updater){
		$response = array("error"=>"", "success"=>"");
		$this->load->model("payments/models/Payment_currency_model");
		$result = $this->Payment_currency_model->update_currency_rates($updater);
		if(!empty($result["errors"])){
			$response["error"] = implode("<br>", $result["errors"]);
		}else{
			$response["success"] = l('success_rates_updated', 'payments');
		}
		echo json_encode($response);
		exit;
	}
        
        /**
	 * Save sorting data of payment system operators by ajax
	 * 
	 * @param string $system_gid system guid
	 * @return void
	 */
	public function ajax_save_system_operators_sorter($system_gid){
		$sorter = $this->input->post("sorter");
		foreach($sorter as $item_str =>$sort_index){
			$sorter_data[$sort_index] = str_replace("operator_", "", $item_str);
		}

		if(empty($sorter_data)) return;
		
		ksort($sorter_data);
		
		$this->load->model('payments/models/Payment_systems_model');
		$this->Payment_systems_model->save_system_operators_sorter($system_gid, $sorter_data);
		
		$data['success'] = l('success_system_operator_sorted', 'payments');
		
		echo json_encode($data);
	}
        
        /**
	 * Remove tarif of payment system by ajax
	 * 
	 * @param string $system_gid system guid
	 * @param string $operator_gid operator guid
	 * @return void
	 */
	public function ajax_delete_system_operator($system_gid, $operator_gid){
		$this->load->model('payments/models/Payment_systems_model');
		$this->Payment_systems_model->delete_system_operator($system_gid, $operator_gid);
	}
               
        /**
	 * Get operator block of payment system by ajax
	 * 
	 * @param string $system_gid system guid
	 * @param strign $operator_gid operator guid
	 * @return void
	 */
	public function ajax_get_system_operator_form($system_gid, $operator_gid=''){
		$this->load->model('payments/models/Payment_systems_model');
		$system_data = $this->Payment_systems_model->get_system_by_gid($system_gid);

		foreach($this->pg_language->languages as $lid => $lang){
			$lang_data[$lid]["name"] = $lang["name"];
			$lang_data[$lid]["value"] = isset($system_data['operators_data'][$operator_gid]) ? $system_data['operators_data'][$operator_gid] : '';
		}

		$this->template_lite->assign('lang_data', $lang_data);
		$this->template_lite->assign('operator_gid', $operator_gid);
		$this->template_lite->view('edit_system_operator_form');
	}
        
        /**
	 * Save operator of payment system by ajax
	 * 
	 * @param string $system_gid system guid
	 * @param string $operator_gid operator guid
	 * @return void
	 */
	public function ajax_save_system_operator_data($system_gid, $operator_gid=''){
		$this->load->model('payments/models/Payment_systems_model');
		$system_data = $this->Payment_systems_model->get_system_by_gid($system_gid);
		if(empty($system_data)) show_404();
		
		$post_data = $this->input->post('data', true);
		$post_data['gid'] = $operator_gid;
	
		$validate_data = $this->Payment_systems_model->validate_system_operator($system_gid, $operator_gid, $post_data);
		if(!empty($validate_data['errors'])){
			$return['errors'] = implode(', ', $return['errors']);
		}else{
			$operator_gid = $validate_data['data']['gid'];
			$operator_data = $validate_data['data']['name'];
			$this->Payment_systems_model->save_system_operator($system_gid, $operator_gid, $operator_data);
			$save_data = array('gid'=>$system_gid, 'tarifs_status'=>$system_data['tarifs_status'], 'tarifs_data' => $system_data['tarifs_data']);
			if(!isset($save_data['tarifs_status'][$operator_gid])) $save_data['tarifs_status'][$operator_gid] = 0;
			if(!isset($save_data['tarifs_data'][$operator_gid])) $save_data['tarifs_data'][$operator_gid] = array();
			$validate_data = $this->Payment_systems_model->validate_system($system_gid, $save_data);
			$this->Payment_systems_model->save_system($system_data['id'], $validate_data['data']);			
			$return['success'] = l('success_system_operator_updated', 'payments');
		}
		
		echo json_encode($return);
	}
        
        /**
	 * Activate/deactivate payment system operator by ajax
	 * 
	 * Available status values: 1 - activate, 0 - deactivate
	 * 
	 * @param integer $system_gid system guid
	 * @param string $operator_gid operator guid
	 * @param integer $status active status
	 * @return void
	 */
	public function ajax_set_system_operator_status($system_gid, $operator_gid, $status=1){
		$this->load->model('payments/models/Payment_systems_model');
		$system_data = $this->Payment_systems_model->get_system_by_gid($system_gid);
		if(empty($system_data)) show_404();
		$save_data = array('gid'=>$system_gid, 'tarifs_status'=>$system_data['tarifs_status']);
		$save_data['tarifs_status'][$operator_gid] = $status;
		$validate_data = $this->Payment_systems_model->validate_system($system_gid, $save_data);
		$this->Payment_systems_model->save_system($system_data['id'], $validate_data['data']);
	}
        
        /**
	 * Get payment system operators block by ajax
	 * 
	 * @param string $system_gid system guid
	 * @return void
	 */
	public function ajax_get_system_operators($system_gid){
		$this->load->model('payments/models/Payment_systems_model');
		$system_data = $this->Payment_systems_model->get_system_by_gid($system_gid);
		echo $this->_get_system_tarifs($system_data);
	}
        
        /**
	 * Get tarifs block of payment system operator 
	 * 
	 * @param array $system_data system data
	 * @return string
	 */
	private function _get_system_tarifs($system_data){
		$this->template_lite->assign('system_data', $system_data);
		return $this->template_lite->fetch('edit_system_operators');
	}
}
