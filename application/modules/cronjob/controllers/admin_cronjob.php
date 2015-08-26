<?php
/**
* Cronjob admin side controller
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/
class Admin_Cronjob extends Controller {

	/**
	 * Constructor
	 */
	function __construct(){
		parent::Controller();
		$this->load->model('Cronjob_model');
		$this->load->model('Menu_model');
		$this->Menu_model->set_menu_active_item('admin_menu', 'system-items');
	}

	public function index($filter="all"){
		$filter_data = array(
			"all" =>  $this->Cronjob_model->get_crons_count(),
			"not_active" =>  $this->Cronjob_model->get_crons_count(array("where"=>array("status"=>0))),
			"active" =>  $this->Cronjob_model->get_crons_count(array("where"=>array("status"=>1))),
		);

		$attrs = array();
		switch($filter){
			case 'active' : $attrs["where"]['status'] = 1; break;
			case 'not_active' : $attrs["where"]['status'] = 0; break;
			default: $filter = "all";
		}
		$_SESSION["cronjob_list"]['filter'] = $filter;
		$this->template_lite->assign('filter', $filter);
		$this->template_lite->assign('filter_data', $filter_data);

		$crons = $this->Cronjob_model->get_crons($attrs);
		$this->template_lite->assign('crontab', $crons);

		$page_data["date_format"] = $this->pg_date->get_format('date_time_numeric', 'st');
		$this->template_lite->assign('page_data', $page_data);

		$this->system_messages->set_data('header', l('admin_header_list', 'cronjob'));
		$this->template_lite->view('list');
	}

	public function edit($id=null){
		if($id){
			$data = $this->Cronjob_model->get_cron_by_id($id);
		}else{
			$data["cron_tab"] = "0 0 * * *";
		}

		if($this->input->post('btn_save')){
			$post_data = array(
				"name" => $this->input->post('name', true),
				"module" => $this->input->post('module', true),
				"model" => $this->input->post('model', true),
				"method" => $this->input->post('method', true),
				"ct_min" => $this->input->post('ct_min'),
				"ct_hour" => $this->input->post('ct_hour'),
				"ct_day" => $this->input->post('ct_day'),
				"ct_month" => $this->input->post('ct_month'),
				"ct_wday" => $this->input->post('ct_wday'),
			);

			$validate_data = $this->Cronjob_model->validate_cron($id, $post_data);
			if(!empty($validate_data["errors"])){
				$this->system_messages->add_message('error', $validate_data["errors"]);
			}else{
				$save_data = $validate_data["data"];
				if(!$id){
					$save_data['status'] = 1;
				}
				$id = $this->Cronjob_model->save_cron($id, $save_data);

				$this->system_messages->add_message('success', ($id)?l('success_update_cronjob', 'cronjob'):l('success_add_cronjob', 'cronjob'));
				$url = site_url()."admin/cronjob/index/".$_SESSION["cronjob_list"]["filter"];
				redirect($url);
			}
			$data = array_merge($data, $validate_data["data"]);
		}

		$data = $this->Cronjob_model->format_cron($data);
		$this->template_lite->assign('data', $data);

		$this->system_messages->set_data('header', l('admin_header_edit', 'cronjob'));
		$this->template_lite->view('edit_form');
	}

	public function delete($id){
		if(!empty($id)){
			$this->Cronjob_model->delete_cron($id);
			$this->system_messages->add_message('success', l('success_delete_cronjob', 'cronjob'));
		}
		$url = site_url()."admin/cronjob/index/".$_SESSION["cronjob_list"]["filter"];
		redirect($url);
	}

	public function activate($id, $status=0){
		if(!empty($id)){
			$data["status"] = intval($status);
			$this->Cronjob_model->save_cron($id, $data);
			$this->system_messages->add_message('success', ($status)?l('success_activate_cronjob', 'cronjob'):l('success_deactivate_cronjob', 'cronjob'));
		}
		$url = site_url()."admin/cronjob/index/".$_SESSION["cronjob_list"]["filter"];
		redirect($url);
	}

	public function log($id_cron){
		$params["where"]["cron_id"] = $id_cron;
		$logs = $this->Cronjob_model->get_log($params);
		$this->template_lite->assign('logs', $logs);

		$page_data["date_format"] = $this->pg_date->get_format('date_time_numeric', 'st');
		$this->template_lite->assign('page_data', $page_data);

		$cron_data = $this->Cronjob_model->get_cron_by_id($id_cron);
		$this->template_lite->assign('cron_data', $cron_data);

		$this->system_messages->set_data('back_link', site_url()."admin/cronjob/index/".$_SESSION["cronjob_list"]["filter"]);
		$this->system_messages->set_data('header', l('admin_header_log_list', 'cronjob'));
		$this->template_lite->view('list_log');
	}

	public function delete_log($id_cron){
		if(!empty($id_cron)){
			$params["where"]["cron_id"] = $id_cron;
			$this->Cronjob_model->delete_log($params);
			$this->system_messages->add_message('success', l('success_clear_log_cronjob', 'cronjob'));
		}
		$url = site_url()."admin/cronjob/log/".$id_cron;
		redirect($url);
	}

	public function run($id_cron){
		if(!empty($id_cron)){
			$errors = $this->Cronjob_model->run($id_cron);

			if(!empty($errors)){
				$this->system_messages->add_message('error', implode("<br>", $errors));
			}else{
				$this->system_messages->add_message('success', l('success_run_cronjob', 'cronjob'));
			}
		}
		$url = site_url()."admin/cronjob/index/".$_SESSION["cronjob_list"]["filter"];
		redirect($url);
	}
}