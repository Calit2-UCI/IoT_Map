<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!defined('SUBSCRIPTIONS_USERS_TABLE')) define('SUBSCRIPTIONS_USERS_TABLE', DB_PREFIX.'subscriptions_users');
if (!defined('MAIL_LIST_FILTERS_TABLE')) define('MAIL_LIST_FILTERS_TABLE', DB_PREFIX.'mail_list_filters');

class Mail_list_model extends Model {
	private $CI;
	private $DB;

	private $fields_user = array(
		'id',
		'date_created',
		'email',
		'nickname',
		'user_type',
		'password',
		'confirm',
		'fname',
		'sname',
		'lang_id',
		'group_id',
		'birth_date',
		'email',
		'id_country',
		'id_region',
		'id_city'
	);

	private $fields_subscriptions_users = array(
		'id_user',
		'id_subscription'
	);

	private $fields_filters = array(
		'id',
		'search_data',
		'date_search'
	);

	function __construct()
	{
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;
		$this->load->model('Users_model');
		$this->load->model('Subscriptions_model');
		// Add table name as prefixes
		array_walk($this->fields_user, create_function('&$item', '$item = USERS_TABLE . "." . $item;'));
	}

	/**
	 *
	 * @param array $params
	 * @param int $page
	 * @param int $items_on_page
	 * @param bool $only_id If true, returns only a list of user id and won't split result into pages
	 * @return boolean
	 */
	function get_users($params, $page = 1, $items_on_page = null, $only_id = false) {
		$this->load->model('Users_model');
		if(false == $only_id) {
			$this->DB->select(implode(', ', $this->fields_user))
					->select(implode(', ', $this->fields_subscriptions_users));
		} else {
			$this->DB->select(USERS_TABLE . '.id');
		}
		$this->DB->from(USERS_TABLE);

		if(isset($params['where']['id_subscription']) && intval($params['where']['id_subscription'])){
			$this->DB->join(SUBSCRIPTIONS_USERS_TABLE,
					USERS_TABLE . '.id = ' . SUBSCRIPTIONS_USERS_TABLE . '.id_user AND ' .
					SUBSCRIPTIONS_USERS_TABLE . ".id_subscription='" . intval($params['where']['id_subscription']) . "'");
			unset($params['where']['id_subscription']);
		}elseif(isset($params['where_not']['id_subscription']) && intval($params['where_not']['id_subscription'])) {
			$this->DB->join(SUBSCRIPTIONS_USERS_TABLE,
					USERS_TABLE . '.id = ' . SUBSCRIPTIONS_USERS_TABLE . '.id_user AND ' .
					SUBSCRIPTIONS_USERS_TABLE . ".id_user=" . USERS_TABLE . '.id', 'left');
			$this->DB->where(USERS_TABLE . '.id NOT IN',
					'(SELECT `id_user` FROM `' . SUBSCRIPTIONS_USERS_TABLE .
					"` WHERE `id_subscription` = '{$params['where_not']['id_subscription']}')", false);
			$this->DB->group_by(USERS_TABLE . '.id');
			unset($params['where_not']['id_subscription']);
		}else{
			$join_subscr = 'AND ' . SUBSCRIPTIONS_USERS_TABLE . '.id_subscription = ' . $params['id_subscription'];
			$this->DB->join(SUBSCRIPTIONS_USERS_TABLE,
					USERS_TABLE . '.id = ' . SUBSCRIPTIONS_USERS_TABLE . ".id_user $join_subscr" , 'left');
		}

		if (isset($params['where']) && is_array($params['where']) && count($params['where'])) {
			foreach ($params['where'] as $field => $value) {
				if($value) {
					$this->DB->where($field, $value);
				}
			}
		}

		if (isset($params['like']) && is_array($params['like']) && count($params['like'])) {
			foreach ($params['like'] as $field => $value) {
				if($value) {
					$this->DB->like($field, $value);
				}
			}
		}

		if (isset($params['where_sql']) && is_array($params['where_sql']) && count($params['where_sql'])) {
			foreach ($params['where_sql'] as $value) {
				$this->DB->where($value);
			}
		}

		if(false == $only_id) {
			if (intval($page) <= 0) {
				$page = 1;
			}
			$this->DB->limit($items_on_page, $items_on_page * ($page - 1));
		}

		$users = $this->DB->get()->result_array();
		if (!empty($users) && is_array($users)) {
			if(false == $only_id) {
				foreach ($users as $user) {
					$data[] = $user;
				}
				$data = $this->Users_model->format_users($data);
			} else {
				foreach ($users as $user) {
					$data[] = $user['id'];
				}
			}
			return $data;
		}
		return false;
	}

	public function get_users_count($params){
		$this->DB->select('COUNT('.USERS_TABLE.'.id) AS cnt');
		$this->DB->from(USERS_TABLE);

		if(isset($params['where']['id_subscription']) && intval($params['where']['id_subscription'])){
			$this->DB->join(SUBSCRIPTIONS_USERS_TABLE, USERS_TABLE . '.id = ' . SUBSCRIPTIONS_USERS_TABLE . '.id_user AND ' . SUBSCRIPTIONS_USERS_TABLE . ".id_subscription='" . intval($params['where']['id_subscription'])."'");
		}elseif(isset($params['where_not']['id_subscription']) && intval($params['where_not']['id_subscription'])){
			$this->DB->select(implode(', ', $this->fields_subscriptions_users));
			$this->DB->join(SUBSCRIPTIONS_USERS_TABLE, USERS_TABLE . ".id = " . SUBSCRIPTIONS_USERS_TABLE . ".id_user AND " . SUBSCRIPTIONS_USERS_TABLE . ".id_user=".USERS_TABLE . ".id", 'left');// . intval($params['where_not']['id_subscription']) . "'");
			$where = '(' . SUBSCRIPTIONS_USERS_TABLE . '.id_user is null OR ' . SUBSCRIPTIONS_USERS_TABLE . '.id_subscription <>' . $params['where_not']['id_subscription'] . ')';
			$this->db->where($where);
			unset($params['where_not']['id_subscription']);
		}

		if (isset($params['where']) && is_array($params['where']) && count($params['where'])) {
			foreach ($params['where'] as $field => $value) {
				if($value) {
					$this->DB->where($field, $value);
				}
			}
		}

		if (isset($params['like']) && is_array($params['like']) && count($params['like'])) {
			foreach ($params['like'] as $field => $value) {
				if($value) {
					$this->DB->or_like($field, $value);
				}
			}
		}

		if (isset($params['where_sql']) && is_array($params['where_sql']) && count($params['where_sql'])) {
			foreach ($params['where_sql'] as $value) {
				$this->DB->where($value);
			}
		}

		$result = $this->DB->get()->result();
		if(!empty($result)){
			return intval($result[0]->cnt);
		}else{
			return 0;
		}
	}

	/**
	 *
	 * @param int $id_subscription
	 * @param array $id_users
	 * @return boolean
	 */
	public function subscribe_users($id_subscription, $id_users) {
		if (!intval($id_subscription) || !$id_users){
			return false;
		}
		$data['id_subscription'] = (int)$id_subscription;
		foreach ($id_users as $id_user) {
			$data['id_user'] = (int)$id_user;
			$this->DB->ignore()->insert(SUBSCRIPTIONS_USERS_TABLE, $data);
			$this->DB->insert_id();
		}
		return true;
	}

	/**
	 *
	 * @param int $id_subscription
	 * @param array $id_users
	 * @return boolean
	 */
	public function unsubscribe_users($id_subscription, $id_users) {
		if (!intval($id_subscription) || !$id_users) {
			return false;
		}
		$data['id_subscription'] = (int)$id_subscription;
		foreach ($id_users as $id_user) {
			$data['id_user'] = (int)$id_user;
			$this->DB->where('id_user', $id_user)
				->where('id_subscription', $id_subscription)
				->delete(SUBSCRIPTIONS_USERS_TABLE);
		}
		return true;
	}

	/**
	 * Saves search search criteria
	 *
	 * @param array $attrs of search criteria
	 * @return boolean record id when saved, true when exists, false when error
	 */
	public function save_filter($attrs) {
		if(false == is_array($attrs)) {
			return false;
		}
		$data['date_search'] = date('Y-m-d H:i:s');
		$data['search_data'] = serialize($attrs);

		// Check uniqueness
		$this->db->where('search_data', $data['search_data']);
		$this->db->from(MAIL_LIST_FILTERS_TABLE);
		$is_unique = 0 == $this->db->count_all_results();

		if ($is_unique) {
			$this->DB->insert(MAIL_LIST_FILTERS_TABLE, $data);
			return $this->DB->insert_id();
		}
		return true;
	}

	/**
	 * Returns the list of saved filters
	 *
	 * @param int $page
	 * @param int $items_on_page
	 * @return array
	 */
	public function get_filters($page = 1, $items_on_page = null) {
		$this->DB->select(implode(', ', $this->fields_filters));
		$this->DB->from(MAIL_LIST_FILTERS_TABLE);

		if (0 > intval($page)) {
			$page = 1;
		}

		$this->DB->limit($items_on_page, $items_on_page * ($page - 1));

		$searches = $this->DB->get()->result_array();
		if (!empty($searches) && is_array($searches)) {
			foreach ($searches as $key=>$search) {
				$search['search_data'] = unserialize($search['search_data']);
				$data[$key] = $search;
			}
			return $this->format_filters($data);
		}

	}

	/**
	 *
	 * @param int $id_filter
	 * @return array
	 */
	public function get_filter($id_filter) {

		$filter = $this->DB->select('search_data')
				->from(MAIL_LIST_FILTERS_TABLE)
				->where('id', $id_filter)
				->get()->row();
		return unserialize($filter->search_data);
	}

	function format_filters($data){
		$locations = array();
		foreach($data as $key=>$filter){
			$locations[$key] = array(
				$filter['search_data']['id_country'],
				$filter['search_data']['id_region'],
				$filter['search_data']['id_city']);

			$data[$key]['search_data']['subscription'] =
				l("subscription_{$filter['search_data']['id_subscription']}", 'subscriptions');
		}

		// Format locations
		if(!empty($locations)){
			$this->CI->load->helper('countries');
			$user_friendly_location = cities_output_format($locations);

			foreach($data as $key=>$filter){
				$data[$key]['location'] = (isset($user_friendly_location[$key])) ? $user_friendly_location[$key] : '';
			}
		}

		return $data;
	}

	public function get_filters_count() {
		return $this->db->count_all(MAIL_LIST_FILTERS_TABLE);
	}

	/**
	 * Removes filter by id
	 *
	 * @param int $id_filter
	 * @return boolean
	 */
	public function delete_filter($id_filter) {
		$this->DB->where('id', $id_filter)
				->delete(MAIL_LIST_FILTERS_TABLE);
		return true;
	}

	/**
	 *
	 * @param array $settings
	 * @return array
	 */
	public function format_search_attrs($settings) {
		$search_attrs = array();

		if($settings['email']) {
			$search_attrs['like']['email']		= $settings['email'];
		}
		
		if($settings['name']) {
			$search_attrs['where_sql'][] = "(
				fname LIKE '%{$settings['name']}%' OR 
				sname LIKE '%{$settings['name']}%' OR 
				nickname LIKE '%{$settings['name']}%')";
		}

		if($settings['date']) {
			$search_attrs['where']['date_created >'] = $settings['date'];
		}

		if($settings['user_type']) {
			$search_attrs['where']['user_type'] = $settings['user_type'];
		}

		// Location
		if($settings['id_city']) {
			$search_attrs['where']['id_city']		= $settings['id_city'];
		}elseif($settings['id_region']) {
			$search_attrs['where']['id_region']		= $settings['id_region'];
		}elseif($settings['id_country']) {
			$search_attrs['where']['id_country']	= $settings['id_country'];
		}

		// Subscription
		if('subscribed' == $settings['filter']) {
			$search_attrs['where']['id_subscription']		= $settings['id_subscription'];
		} elseif ('not_subscribed' == $settings['filter']) {
			$search_attrs['where_not']['id_subscription']	= $settings['id_subscription'];
		}
		$search_attrs['id_subscription'] = $settings['id_subscription'];

		return $search_attrs;
	}

}