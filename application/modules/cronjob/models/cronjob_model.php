<?php
/**
* Cronjob main model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

if (!defined('BASEPATH')) exit('No direct script access allowed');

define('CRONJOB_TABLE', DB_PREFIX.'cronjob');
define('CRONJOB_LOG_TABLE', DB_PREFIX.'cronjob_log');

class Cronjob_model extends Model
{
	private $CI;
	private $DB;

	private $attrs = array('id', 'date_add', 'date_execute', 'name', 'module', 'model', 'method', 'cron_tab', 'status', 'in_process');
	private $attrs_log = array('id', 'date_add', 'cron_id', 'function_result', 'output', 'errors', 'execution_time', 'memory_usage');

	private $log_expiried_period = 2592000;
	private $log_all_crons = false;
	/**
	 * Constructor
	 *
	 * @return
	 */
	function __construct()
	{
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;
		$this->log_expiried_period = $this->CI->pg_module->get_module_config('cronjob', 'log_expiried_period');
		$this->log_all_crons = $this->CI->pg_module->get_module_config('cronjob', 'log_all_crons');
	}


	public function get_cron_by_id($id_cron){
		$this->DB->select(implode(", ", $this->attrs))->from(CRONJOB_TABLE)->where("id", $id_cron);
		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			return $this->format_cron($results[0]);
		}
		return array();
	}

	public function get_crons($params=array()){
		$data = array();
		$this->DB->select(implode(", ", $this->attrs));
		$this->DB->from(CRONJOB_TABLE);

		if(isset($params["where"]) && is_array($params["where"]) && count($params["where"])){
			foreach($params["where"] as $field=>$value){
				$this->DB->where($field, $value);
			}
		}

		if(isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])){
			foreach($params["where_in"] as $field=>$value){
				$this->DB->where_in($field, $value);
			}
		}

		if(isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])){
			foreach($params["where_sql"] as $value){
				$this->DB->where($value);
			}
		}
		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			$data = $this->format_crons($results);
		}
		return $data;
	}

	public function get_crons_count($params=array()){
		$data = array();
		$this->DB->select("COUNT(*) AS cnt");
		$this->DB->from(CRONJOB_TABLE);

		if(isset($params["where"]) && is_array($params["where"]) && count($params["where"])){
			foreach($params["where"] as $field=>$value){
				$this->DB->where($field, $value);
			}
		}

		if(isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])){
			foreach($params["where_in"] as $field=>$value){
				$this->DB->where_in($field, $value);
			}
		}

		if(isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])){
			foreach($params["where_sql"] as $value){
				$this->DB->where($value);
			}
		}
		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			return intval($results[0]['cnt']);
		}
		return 0;
	}

	public function format_crons($data){
		$this->CI->load->library('Cronparser');

		foreach($data as $key=> $cron){
			$parts = explode(" ", trim($cron["cron_tab"]));
			$cron["ct_min"] = $parts[0];
			$cron["ct_hour"] = $parts[1];
			$cron["ct_day"] = $parts[2];
			$cron["ct_month"] = $parts[3];
			$cron["ct_wday"] = $parts[4];
			$this->CI->cronparser->calcLastRan($cron["cron_tab"]);
			$last_run = $this->CI->cronparser->getLastRan();
			if(!empty($last_run)){
				$cron["date_scheduler"] = $last_run[5] . '-' . $last_run[3] . '-' . $last_run[2] . ' ' . $last_run[1] . ':' . $last_run[0] . ':00';
				$cron_scheduler = strtotime($cron["date_scheduler"]);
				$cron_execute = (!empty($cron['date_execute']))?strtotime($cron['date_execute']):0;
				$cron["expiried"] = ($cron_scheduler > $cron_execute)?true:false;
				$data[$key] = $cron;
			}
		}
		return $data;
	}

	public function format_cron($data){
		$this->CI->load->library('Cronparser');

		$parts = explode(" ", trim($data["cron_tab"]));
		$data["ct_min"] = $parts[0];
		$data["ct_hour"] = $parts[1];
		$data["ct_day"] = $parts[2];
		$data["ct_month"] = $parts[3];
		$data["ct_wday"] = $parts[4];
		$this->CI->cronparser->calcLastRan($data["cron_tab"]);
		$last_run = $this->CI->cronparser->getLastRan();
		if(!empty($last_run)){
			$data["date_scheduler"] = $last_run[5] . '-' . $last_run[3] . '-' . $last_run[2] . ' ' . $last_run[1] . ':' . $last_run[0] . ':00';
			$cron_scheduler = strtotime($data["date_scheduler"]);
			$cron_execute = (!empty($data['date_execute']))?strtotime($data['date_execute']):0;
			$data["expiried"] = ($cron_scheduler > $cron_execute)?true:false;
		}

		return $data;
	}

	public function save_cron($id, $data){
		if (is_null($id)){
			$data["date_add"] = $data["date_execute"] = date("Y-m-d H:i:s");
			if(!isset($attrs["status"])) $data["status"] = 1;
			$this->DB->insert(CRONJOB_TABLE, $data);
			$id = $this->DB->insert_id();
		}else{
			$this->DB->where('id', $id);
			$this->DB->update(CRONJOB_TABLE, $data);
		}
		return $id;
	}

	public function delete_cron($id){
		$this->DB->where('id', $id);
		$this->DB->delete(CRONJOB_TABLE);

		$params["where"]["cron_id"] = $id;
		$this->delete_log($params);
	}

	public function delete_cron_by_param($params=array()){
		if(isset($params["where"]) && is_array($params["where"]) && count($params["where"])){
			foreach($params["where"] as $field=>$value) $this->DB->where($field, $value);
		}

		if(isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])){
			foreach($params["where_in"] as $field=>$value) $this->DB->where_in($field, $value);
		}

		if(isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])){
			foreach($params["where_sql"] as $value) $this->DB->where($value);
		}
		$this->DB->delete(CRONJOB_TABLE);
	}

	public function validate_cron($id, $data){
		$return = array("errors"=> array(), "data" => array());
		//// 'date_execute', 'name', 'module', 'model', 'method', 'name', 'cron_tab', 'status'

		if(isset($data["name"])){
			$return["data"]["name"] = strip_tags($data["name"]);
			if(empty($return["data"]["name"])){
				$return["errors"][] = l('error_name_empty', 'cronjob');
			}
		}

		if(isset($data["date_execute"])){
			$return["data"]["date_execute"] = $data["date_execute"];
		}

		if(isset($data["status"])){
			$return["data"]["status"] = $data["status"];
		}

		if(isset($data["module"])){
			$return["data"]["module"] = $data["module"];
		}
		if(isset($data["model"])){
			$return["data"]["model"] = $data["model"];
		}
		if(isset($data["method"])){
			$return["data"]["method"] = $data["method"];
		}

		if(empty($id) && ( empty($return["data"]["module"]) || empty($return["data"]["model"]) || empty($return["data"]["method"]) ) ){
			$return["errors"][] = l('error_function_empty', 'cronjob');
		}

		if(!(empty($return["data"]["module"]) || empty($return["data"]["model"]) || empty($return["data"]["method"]) )){
			$result = $this->is_method_callable($return["data"]["module"], $return["data"]["model"], $return["data"]["method"]);
			if(!$result){
				$return["errors"][] = l('error_function_invalid', 'cronjob');
			}
		}

		if(isset($data["cron_tab"])){
			$return["data"]["cron_tab"] = $data["cron_tab"];
		}

		if(isset($data["ct_min"]) && isset($data["ct_hour"]) && isset($data["ct_day"]) && isset($data["ct_month"]) && isset($data["ct_wday"])){
			$t[]= trim($data["ct_min"]);
			$t[]= trim($data["ct_hour"]);
			$t[]= trim($data["ct_day"]);
			$t[]= trim($data["ct_month"]);
			$t[]= trim($data["ct_wday"]);
			$return["data"]["cron_tab"] = implode(" ", $t);

			$this->CI->load->library('Cronparser');

			$this->CI->cronparser->calcLastRan($return["data"]["cron_tab"]);
			$last_run = $this->CI->cronparser->getLastRan();
			if(empty($last_run)){
				$return["errors"][] = l('error_crontab_invalid', 'cronjob');
			}
		}

		return $return;
	}

	private function is_method_callable($module, $model, $method){
		$result = false;

		$model_url = $module."/models/".$model;
		$model_path = MODULEPATH.strtolower($model_url).EXT;

		if(file_exists($model_path)){
			$this->CI->load->model($model_url);
			$object = array($this->CI->$model, $method);
			$result = is_callable($object);
		}

		return $result;
	}

	public function run($id, $data=array()){

		$result = false;
		$errors = array();

		$this->benchmark->mark('cronjob_module_run_start');

		if(empty($data)){
			$data = $this->get_cron_by_id($id);
		}

		if(empty($data)){
			$errors[] = l('error_crontab_data_empty', 'cronjob');
		}

		if (!$this->is_method_callable($data["module"], $data["model"], $data["method"])){
			$errors[] = l('error_function_invalid', 'cronjob');
		}else{
			$this->save_cron($id, array("in_process" => 1));

			$model_url = $data["module"]."/models/".$data["model"];
			$model_path = MODULEPATH.strtolower($model_url).EXT;
			$this->CI->load->model($model_url);

			@ob_end_clean();
			ob_start();
			$function_result = call_user_func_array(array(&$this->CI->$data["model"], $data["method"]), array());
			if(!empty($function_result)) $log["function_result"] = $function_result;
			$log["output"] = ob_get_contents();

			$this->benchmark->mark('cronjob_module_run_end');

			$log["execution_time"] = $this->benchmark->elapsed_time('cronjob_module_run_start', 'cronjob_module_run_end');
			$log["memory_usage"] = (!function_exists('memory_get_usage')) ? '0' : round(memory_get_usage()/1024/1024, 2).'MB';
			$log["cron_id"] = $id;
			$log["errors"] = implode(", ", $errors);

			$this->save_log($log);

			$this->save_cron($id, array("date_execute" => date("Y-m-d H:i:s"), "in_process" => 0));
		}

		return $errors;
	}

	public function scheduler(){
		$this->clear_log();
		$this->benchmark->mark('cronjob_scheduler_start');

		$params["where"]["status"] = 1;
		$crons = $this->get_crons($params);
		if(empty($crons)){
			$messages[] = l('error_crontab_tasks_empty', 'cronjob');
		}else{
			foreach($crons as $cron){
				$cron = $this->get_cron_by_id($cron["id"]);
				if(isset($cron["expiried"]) && $cron["expiried"] === true && $cron["in_process"] == 0){
					$this->run($cron["id"], $cron);
					$messages[] = $cron["module"].":".$cron["model"].":".$cron["method"];
				}
			}
		}

		$this->benchmark->mark('cronjob_scheduler_end');

		if($this->log_all_crons){
			$log["execution_time"] = $this->benchmark->elapsed_time('cronjob_scheduler_start', 'cronjob_scheduler_end');
			$log["memory_usage"] = (!function_exists('memory_get_usage')) ? '0' : round(memory_get_usage()/1024/1024, 2).'MB';
			$log["cron_id"] = 0;
			$log["output"] = implode("; ", $messages);

			$this->save_log($log);
		}

		return;
	}

	public function get_log($params=array()){
		$data = array();
		$this->DB->select(implode(", ", $this->attrs_log));
		$this->DB->from(CRONJOB_LOG_TABLE);

		if(isset($params["where"]) && is_array($params["where"]) && count($params["where"])){
			foreach($params["where"] as $field=>$value) $this->DB->where($field, $value);
		}

		if(isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])){
			foreach($params["where_in"] as $field=>$value) $this->DB->where_in($field, $value);
		}

		if(isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])){
			foreach($params["where_sql"] as $value) $this->DB->where($value);
		}

		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			$data = $results;
		}
		return $data;
	}

	public function get_log_count($params=array()){
		$this->DB->select("COUNT(*) AS cnt");
		$this->DB->from(CRONJOB_LOG_TABLE);
		if(isset($params["where"]) && is_array($params["where"]) && count($params["where"])){
			foreach($params["where"] as $field=>$value) $this->DB->where($field, $value);
		}

		if(isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])){
			foreach($params["where_in"] as $field=>$value) $this->DB->where_in($field, $value);
		}

		if(isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])){
			foreach($params["where_sql"] as $value) $this->DB->where($value);
		}

		$result = $this->DB->get()->result();
		if(!empty($result)){
			return intval($result[0]->cnt);
		}else{
			return 0;
		}
	}

	private function save_log($data){
		$data["date_add"] = date("Y-m-d H:i:s");
		$this->DB->insert(CRONJOB_LOG_TABLE, $data);
		return;
	}

	public function delete_log($params=array()){
		if(isset($params["where"]) && is_array($params["where"]) && count($params["where"])){
			foreach($params["where"] as $field=>$value) $this->DB->where($field, $value);
		}

		if(isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])){
			foreach($params["where_in"] as $field=>$value) $this->DB->where_in($field, $value);
		}

		if(isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])){
			foreach($params["where_sql"] as $value) $this->DB->where($value);
		}
		$this->DB->delete(CRONJOB_LOG_TABLE);
		return;
	}

	private function clear_log(){
		$this->DB->where('date_add <', date('Y-m-d H:i:s', $this->log_expiried_period));
		$this->DB->delete(CRONJOB_LOG_TABLE);
		return;
	}
}