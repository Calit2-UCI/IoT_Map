<?php
/**
* Subscriptions users model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

if (!defined('SUBSCRIPTIONS_USERS_TABLE')) define('SUBSCRIPTIONS_USERS_TABLE', DB_PREFIX.'subscriptions_users');
class Subscriptions_users_model extends Model {
	private $CI;
	private $DB;
	private $attrs = array('id', 'id_user', 'id_subscription');

	function __construct()
	{
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;
	}

	public function get_users_by_id_subscription($id_subscription, $page = null, $items_on_page = 1000){
		$this->CI->load->model('Users_model');

		$this->DB->select('id_user, email, lang_id')->from(SUBSCRIPTIONS_USERS_TABLE);
		$this->DB->join(USERS_TABLE, USERS_TABLE.'.id = '.SUBSCRIPTIONS_USERS_TABLE.'. 	id_user');
		$this->DB->where('id_subscription', $id_subscription);
		if(!is_null($page) ){
			$page = intval($page)?intval($page):1;
			$this->DB->limit($items_on_page, $items_on_page*($page-1));
		}

		$results = $this->DB->get()->result_array();
		return $results;
	}

	public function save_user_subscriptions($id_user, $subscriptions){
		$this->DB->where("id_user", $id_user);
		$this->DB->delete(SUBSCRIPTIONS_USERS_TABLE);
		if ($subscriptions){
			foreach ($subscriptions as $key => $value) {
				$data['id_user'] = $id_user;
				$data['id_subscription'] = intval($value);
				$this->DB->insert(SUBSCRIPTIONS_USERS_TABLE, $data);
				$id = $this->DB->insert_id();
			}
		}
		$this->save_auto_subscriptions($id_user);
	}

	public function save_auto_subscriptions($id_user){
		$this->CI->load->model('Subscriptions_model');
		$auto_subscriptions = $this->CI->Subscriptions_model->get_subscriptions_list(null, null, null, array('where' => array( 'subscribe_type' => 'auto')));
		if ($auto_subscriptions){
			foreach ($auto_subscriptions as $key => $value) {
				$data['id_user'] = $id_user;
				$data['id_subscription'] = intval($value['id']);
				$this->DB->insert(SUBSCRIPTIONS_USERS_TABLE, $data);
			}
		}
	}

	public function get_subscriptions_by_id_user($id_user){
		$return = array();
		$this->DB->select(implode(", ", $this->attrs))->from(SUBSCRIPTIONS_USERS_TABLE);
		$this->DB->where('id_user', $id_user);
		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			foreach($results as $r){
				$return[$r['id_subscription']] = 1;
			}
		}
		return $return;
	}

	public function get_subscription_users_count($id_subscription){
		 $this->CI->load->model('Users_model');

		 $this->DB->select('COUNT(*) AS cnt')->from(SUBSCRIPTIONS_USERS_TABLE);
		 $this->DB->join(USERS_TABLE, USERS_TABLE.'.id = '.SUBSCRIPTIONS_USERS_TABLE.'.id_user');
		 $this->DB->where('id_subscription', $id_subscription);

		 $results = $this->DB->get()->result_array();
		 if(!empty($results) && is_array($results)){
			return intval($results[0]["cnt"]);
		 }
		 return 0;
	}
}
