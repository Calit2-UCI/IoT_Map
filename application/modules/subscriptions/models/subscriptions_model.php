<?php
/**
* Subscriptions main model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

if (!defined('SUBSCRIPTIONS_TABLE')) define('SUBSCRIPTIONS_TABLE', DB_PREFIX.'subscriptions');
class Subscriptions_model extends Model {
	private $CI;
	private $DB;
	private $attrs = array('id', /*'gid',*/ 'id_template', 'subscribe_type', 'id_content_type', 'scheduler');

	function __construct()
	{
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;
	}

	public function get_subscriptions_count($params=array(), $filter_object_ids=null){
		$this->DB->select('COUNT(*) AS cnt')->from(SUBSCRIPTIONS_TABLE);

		if(isset($params["where"]) && is_array($params["where"]) && count($params["where"])){
			foreach($params["where"] as $field=>$value) $this->DB->where($field, $value);
		}

		if(isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])){
			foreach($params["where_in"] as $field=>$value) $this->DB->where_in($field, $value);
		}

		if(isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])){
			foreach($params["where_sql"] as $value) $this->DB->where($value);
		}

		if(isset($filter_object_ids) && is_array($filter_object_ids) && count($filter_object_ids)){
			$this->DB->where_in("id", $filter_object_ids);
		}

		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			return intval($results[0]["cnt"]);
		}
		return 0;
	}

	public function get_subscriptions_list($page=null, $items_on_page=null, $order_by=null, $params=array(), $filter_object_ids=null){
		$this->DB->select(implode(", ", $this->attrs))->from(SUBSCRIPTIONS_TABLE);

		if(isset($params["where"]) && is_array($params["where"]) && count($params["where"])){
			foreach($params["where"] as $field=>$value) $this->DB->where($field, $value);
		}

		if(isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])){
			foreach($params["where_in"] as $field=>$value) $this->DB->where_in($field, $value);
		}

		if(isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])){
			foreach($params["where_sql"] as $value) $this->DB->where($value);
		}

		if(isset($filter_object_ids) && is_array($filter_object_ids) && count($filter_object_ids)){
			$this->DB->where_in("id", $filter_object_ids);
		}

		if(is_array($order_by) && count($order_by)>0){
			foreach($order_by as $field => $dir){
				if(in_array($field, $this->attrs)){
					$this->DB->order_by($field." ".$dir);
				}
			}
		}

		if(!is_null($page) ){
			$page = intval($page)?intval($page):1;
			$this->DB->limit($items_on_page, $items_on_page*($page-1));
		}

		$data = array();
		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			foreach($results as $r){
				$r['scheduler'] = $this->get_scheduler_format($r['scheduler']);
				$data[] = $this->format_subscription($r, true);
			}
		}
		return $data;
	}

	public function format_subscription($data, $get_langs = false){
		$data["name_i"] = "subscription_".$data["id"];
		$data["name"] = ($get_langs)?(l($data["name_i"], 'subscriptions')):"";
		if ($data['scheduler']['type'] == 1){
			$data["scheduler_format"] = l('manual', 'subscriptions');
		}
		if ($data['scheduler']['type'] == 2){
			$data["scheduler_format"] = l('in_time', 'subscriptions').' '.$data['scheduler']['date'].' '.$data['scheduler']['hours'].':'.$data['scheduler']['minutes'];
		}
		if ($data['scheduler']['type'] == 3){
			$data["scheduler_format"] = l('every_time', 'subscriptions').' '.l($data['scheduler']['period'], 'subscriptions').' '.l('since', 'subscriptions').' '.$data['scheduler']['date'].' '.$data['scheduler']['hours'].':'.$data['scheduler']['minutes'];
		}
		return $data;
	}

	public function get_subscription_by_gid($gid){
		$data = array();
		$result = $this->DB->select(implode(", ", $this->attrs))->from(SUBSCRIPTIONS_TABLE)->where("gid", $gid)->get()->result_array();
		if(!empty($result)){
			$data = $result[0];
		}
		$data['scheduler'] = $this->get_scheduler_format($data['scheduler']);
		return $data;
	}

	public function get_scheduler_format($sheduler_str){
		$return = unserialize($sheduler_str);
		return $return;
	}

	public function get_subscription_by_id($id){
		$data = array();
		$result = $this->DB->select(implode(", ", $this->attrs))->from(SUBSCRIPTIONS_TABLE)->where("id", $id)->get()->result_array();
		if(!empty($result)){
			$data = $result[0];
			$data['scheduler'] = $this->get_scheduler_format($data['scheduler']);
		}
		return $data;
	}

	public function validate_subscription($id, $data, $langs){
		$return = array("errors"=> array(), "data" => array(), 'langs' => array());
		if (isset($data['scheduler_type'])){
			if(empty($data['scheduler_type'])) {
				$return['errors'][] = l('error_scheduler_type_mondatory', 'subscriptions');
			}
			$sheduler_array = array();
			$sheduler_array['type'] = intval($data['scheduler_type']);
			$sheduler_array['date_for_cron'] = 0;
			if(intval($data['scheduler_type']) == 2 || intval($data['scheduler_type']) == 3){
				$sheduler_array['date'] = strval($data['scheduler_date']);
				$sheduler_array['hours'] = intval($data['scheduler_hours']);
				$sheduler_array['minutes'] = intval($data['scheduler_minutes']);
				$sheduler_array['date_for_cron'] = strtotime($sheduler_array['date'].' '.$sheduler_array['hours'].':'.$sheduler_array['minutes']);
			}
			if(intval($data['scheduler_type']) == 3){
				$sheduler_array['period'] = strval($data['scheduler_period']);
			}
			$return["data"]["scheduler"] = serialize($sheduler_array);
		}
		if(isset($data["id_template"])){
			$return["data"]["id_template"] = intval($data["id_template"]);
		}

		if(isset($data["subscribe_type"])){
			$return["data"]["subscribe_type"] = strip_tags($data["subscribe_type"]);
		}

		if(isset($data["id_content_type"])){
			$return["data"]["id_content_type"] = intval($data["id_content_type"]);
		}

		if(!empty($langs)){
			$return["langs"] = $langs;
			foreach($langs as $lang_id => $name) {
				if(empty($name)) {
					$return["errors"][] = l('error_name_mandatory_field', 'subscriptions') . ': ' . $this->pg_language->languages[$lang_id]['name'];
				}
			}
		}

		return $return;
	}

	public function save_subscription($id, $data, $langs=null){
		if (is_null($id)){
			$this->DB->insert(SUBSCRIPTIONS_TABLE, $data);
			$id = $this->DB->insert_id();
		}else{
			$this->DB->where('id', $id);
			$this->DB->update(SUBSCRIPTIONS_TABLE, $data);
		}

		if(!empty($id) && !empty($langs)){
			$languages = $this->CI->pg_language->languages;
			$lang_ids = array_keys($languages);
			$this->CI->pg_language->pages->set_string_langs('subscriptions', "subscription_".$id, $langs, $lang_ids);
		}
		return $id;
	}

	public function delete_subscription($id){
		$this->DB->where("id", $id);
		$this->DB->delete(SUBSCRIPTIONS_TABLE);
		return;
	}

	public function delete_subscription_by_gid($gid){
		$this->DB->where("gid", $gid);
		$this->DB->delete(SUBSCRIPTIONS_TABLE);
		return;
	}

	public function send_subscription($id, $page=null, $limit = 1000){
		$this->CI->load->model('subscriptions/models/Subscriptions_users_model');
		$this->CI->load->model('subscriptions/models/Subscriptions_types_model');
		$this->CI->load->model('notifications/models/Templates_model');
		$this->CI->load->model('notifications/models/Sender_model');
		$count = $this->CI->Subscriptions_users_model->get_subscription_users_count($id);
		$data = array('sended' => 0, 'have_to_send' => 0);
		if ($count){
			$users = $this->CI->Subscriptions_users_model->get_users_by_id_subscription($id, $page, $limit);
			$subscription_object = $this->get_subscription_by_id($id);
			$template = $this->CI->Templates_model->get_template_by_id($subscription_object['id_template']);
			
			//generate template
			$installed_langs	= $this->CI->pg_language->get_langs('is_default DESC');
			$content = array();
			foreach($installed_langs as $lang_id => $lang_info){
				$vars = $this->CI->Subscriptions_types_model->get_subscriptions_type_content($subscription_object['id_content_type'], $lang_id);
				$content[$lang_id] = $this->CI->Templates_model->compile_template($template['gid'], $vars, $lang_id);
			}

			foreach($users as $key => $user){
				//$vars = $this->CI->Subscriptions_types_model->get_subscriptions_type_content($subscription_object['id_content_type'], $user['lang_id']);
				//$content = $this->CI->Templates_model->compile_template($template['gid'], $vars, $user['lang_id']);
				$this->CI->Sender_model->push($user['email'], $content[$user['lang_id']]['subject'], $content[$user['lang_id']]['content']);
			}

			$data['sended'] = count($users);
			if ($count > $page*$limit){
				$data['have_to_send'] = 1;
			}
			else {
				$data['have_to_send'] = 0;
			}
		}

		return $data;
	}

	public function cron_send_subscriptions(){
		$subscriptions_list = $this->get_subscriptions_list();
		foreach ($subscriptions_list as $key => $value) {
			if ($value['scheduler']['type'] != 1 && $value['scheduler']['date_for_cron'] != 0 && $value['scheduler']['date_for_cron'] < time()){
				$this->send_subscription($value['id']);
				if ($value['scheduler']['type'] == 2){
					$value['scheduler']['date_for_cron'] = 0;
				} elseif ($value['scheduler']['type'] == 3){
					$d = $value['scheduler']['date_for_cron'];
					if ($value['scheduler']['period'] == 'day'){
						$value['scheduler']['date_for_cron'] = mktime(date("H", $d), date("i", $d), date("s", $d), date("n", $d), date("j", $d) + 1, date("Y", $d));
					} elseif ($value['scheduler']['period'] == 'week'){
						$value['scheduler']['date_for_cron'] = mktime(date("H", $d), date("i", $d), date("s", $d), date("n", $d), date("j", $d) + 7, date("Y", $d));
					} elseif ($value['scheduler']['period'] == 'month'){
						$value['scheduler']['date_for_cron'] = mktime(date("H", $d), date("i", $d), date("s", $d), date("n", $d) + 1, date("j", $d), date("Y", $d));
					}
					//$value['scheduler']['date_for_cron']
				}
				$data = array('scheduler' => serialize($value['scheduler']));
				$this->save_subscription($value['id'], $data);

			}
		}
	}

	public function update_langs($model, $subscriptions, $langs_file, $langs_ids) {
		foreach($subscriptions as $subscription){
			$subscr = $this->get_subscription_by_gid($subscription['gid']);
			$this->CI->pg_language->pages->set_string_langs('subscriptions',
															'subscription_' . $subscr['id'],
															$langs_file[$subscription['gid']],
															(array)$langs_ids);
			$this->CI->pg_language->pages->set_string_langs($model,
															'subscriptions_type_' . $subscription['gid'],
															$langs_file[$subscription['gid']],
															(array)$langs_ids);
		}
		return true;
	}

	public function export_langs($subscriptions, $langs_ids = null) {
		foreach($subscriptions as $subscription){
			$subscr = $this->get_subscription_by_gid($subscription['gid']);
			$gids[$subscription['gid']] = 'subscription_' . $subscr['id'];
		}
		$langs = $this->CI->pg_language->export_langs('subscriptions', $gids, $langs_ids);
		return array_combine(array_keys($gids), $langs);
	}

}
