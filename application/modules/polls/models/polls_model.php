<?php
/**
* Poll main model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

if (!defined('POLLS_TABLE'))
	define('POLLS_TABLE', DB_PREFIX . 'polls');

if (!defined('RESPONSES_TABLE'))
	define('RESPONSES_TABLE', DB_PREFIX . 'polls_responses');

if (!defined('USERS_TABLE'))
	define('USERS_TABLE', DB_PREFIX . 'users');

class Polls_model extends Model {

	private $CI;
	private $DB;
	private $moderation_type = 'polls';
	private $poll_attrs = array(
		'id',
		'poll_type',
		'answer_type',
		'question',
		'language',
		'use_comments',
		'sorter',
		'show_results',
		'use_expiration',
		'date_start',
		'date_end',
		'status',
		'answers_data',
	);
	private $results_format_settings = array(
		'use_format' => true,
		'get_user' => true,
	);

	function __construct() {
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;
	}

	// POLL

	public function format_poll($data) {
		if (isset($data['question']) && is_string($data['question'])) {
			$data['question'] = @unserialize($data['question']);
		}
		if (isset($data['answers_languages']) && is_string($data['answers_languages'])) {
			$data['answers_languages'] = @unserialize($data['answers_languages']);
		}
		if (isset($data['answers_colors']) && is_string($data['answers_colors'])) {
			$data['answers_colors'] = @unserialize($data['answers_colors']);
		}
		if (isset($data['results']) && is_string($data['results'])) {
			$data['results'] = @unserialize($data['results']);
		}
		return $data;
	}

	public function validate_poll($data = array()) {
		$return = array('data' => array(), 'errors' => array());

		if(0 == $data['language']) {
			$default_lang = $this->CI->pg_language->get_default_lang_id();
		} else {
			$default_lang = $data['language'];
		}

		if (isset($data['language'])) {
			$return['data']['language'] = floor($data['language']);
		}
		if (isset($data['question']) && is_array($data['question'])) {
			$question = array();
			$question['default'] = strip_tags($data['question'][$default_lang]);
			foreach ($this->CI->pg_language->languages as $lang) {
				if (!isset($lang['id']) || !$lang['name']) {
					continue;
				}

				// If we have an answer for the language
				if (isset($data['question'][$lang['id']]) && strip_tags($data['question'][$lang['id']]) != '') {
					$question[$lang['id']] = strip_tags($data['question'][$lang['id']]);
				} else {
					// If we have an answer for default language
					if (isset($data['question'][$default_lang]) && strip_tags($data['question'][$default_lang]) != '') {
						$question[$lang['id']] = strip_tags($data['question'][$default_lang]);
					} else {
						// Error
						if (isset($return['data']['language']) && $return['data']['language'] == 0) {
							$return['errors'][] = $lang['name'] . ' ' . l('error_question_version_empty', 'polls');
						} else {
							$return['errors']['question'] = l('error_question_empty', 'polls');
						}
					}
				}
			}
			$return['data']['question'] = @serialize($question);
		}
		if (isset($data['poll_type'])) {
			$return['data']['poll_type'] = floor($data['poll_type']);
		}

		if (isset($data['answer_type'])) {
			$return['data']['answer_type'] = floor($data['answer_type']);
		}

		if (isset($data['sorter'])) {
			$return['data']['sorter'] = floor($data['sorter']);
		}

		if (isset($data['show_results'])) {
			$return['data']['show_results'] = $data['show_results'] ? 1 : 0;
		}

		if (isset($data['use_comments'])) {
			$return['data']['use_comments'] = $data['use_comments'] ? 1 : 0;
		}

		if (isset($data['date_start'])) {
			$return['data']['date_start'] = $data['date_start'];
		}

		if (isset($data['date_end'])) {
			$return['data']['date_end'] = $data['date_end'];
		}

		$return['data']['use_expiration'] = $data['use_expiration'] ? 1 : 0;

		return $return;
	}

	public function validate_answers($data = array(), $poll_language = '') {
		$return = array('data' => array(), 'errors' => array());

		$languages = $this->CI->pg_language->languages;

		if (isset($data['answers_languages']) && is_array($data['answers_languages'])) {
			foreach ($data['answers_languages'] as $id => $value) {
				if (strip_tags($value) == '') {
					$id_data = explode('_', $id);
					if (count($id_data) == 2) {
						$answer_id = $id_data[0];
						$answer_language = $id_data[1];
						$lang_name = $languages[$answer_language]['name'];
						if ($poll_language == 0) {
							$return['errors'][] = l('field_answer', 'polls') . ' ' . $answer_id . '. '
									. $lang_name . ' ' . l('error_answer_version_empty', 'polls');
						} else {
							if(isset($data['answers_languages'][$answer_id . '_' . $poll_language])) {
								$answer = $data['answers_languages'][$answer_id . '_' . $poll_language];
							}
							if (!isset($answer)) {
								$return['errors'][] = l('field_answer', 'polls') . ' '
									. $answer_id . ' ' . l('error_answer_empty', 'polls');
							} else {
								$data['answers_languages'][$answer_id . '_' . $answer_language] = $answer;
							}
						}
					}
				}
			}
			// Set default answers
			$default_lang = $this->CI->pg_language->get_default_lang_id();
			foreach ($data['answers_colors'] as $id => $color) {
				$data['answers_languages'][$id . '_default'] =
						$data['answers_languages'][$id . '_' . $default_lang];
			}
			$return['data']['answers_languages'] = @serialize($data['answers_languages']);
		}

		if (isset($data['answers_colors']) && is_array($data['answers_colors'])) {
			$return['data']['answers_colors'] = @serialize($data['answers_colors']);
		}

		return $return;
	}

	public function get_poll_by_id($id = 0) {
		$this->DB->from(POLLS_TABLE)->where("id", $id);
		$results = $this->DB->get()->result_array();
		if (!empty($results) && is_array($results)) {
			return $this->format_poll($results[0]);
		}
		return array();
	}

	public function get_denied_polls($user_id = null) {
		$from_cookie	= array();
		$from_db		= array();
		if(isset($_COOKIE['polls']) && is_array($_COOKIE['polls'])) {
			$from_cookie = array_keys($_COOKIE['polls']);
		}
		if($user_id) {
			$user_type = $this->CI->session->userdata("user_type");
			$results = $this->DB->select('poll_id')
					->from(RESPONSES_TABLE)
					->where('user_id', $user_id)
					->get()->result_array();
			if (!empty($results) && is_array($results)) {
				foreach ($results as $result) {
					$from_db[] = $result['poll_id'];
				}
			}
			$denied_poll_types = array(-1, 0, $user_type);
		} else {
			$denied_poll_types = array(-2, 0);
		}
		$results = $this->DB->select('id')
				->from(POLLS_TABLE)
				->where_not_in('poll_type', $denied_poll_types)
				->get()->result_array();
		if (!empty($results) && is_array($results)) {
			foreach ($results as $result) {
				$from_db[] = $result['id'];
			}
		}

		return array_merge($from_cookie, $from_db);
	}

	/**
	 *
	 * @param int $poll_id
	 * @return boolean
	 */
	public function is_exists($poll_id) {
		if(is_null($poll_id)) {
			return false;
		}
		$is_exists = (bool)$this->DB->where('id', $poll_id)
				->from(POLLS_TABLE)
				->count_all_results();
		return $is_exists;
	}

	/**
	 * Returns random poll id
	 * Will skip polls, passed by $user_id
	 *
	 * @param boolean $has_results Show only polls with results that can be shown
	 * @param int $language
	 * @param int $user_type
	 * @param array $denied_polls Of polls id to be skipped
	 * @return int
	 */
	public function get_random_id($has_results = null, $language = null, $user_type = null, $denied_polls = array()) {

		$this->DB->select(POLLS_TABLE . '.id')
				->from(POLLS_TABLE)
				->where('status', 1)
				->where('date_start < ', date('Y-m-d H:i:s', time()))
				->where("(use_expiration = '0' OR date_end > '" . date('Y-m-d H:i:s', time()) . "')")
				->order_by('RAND()');

		if(!is_null($language)) {
			$this->DB->where_in('language', array(0, $language));
		}

		if(!is_null($user_type)) {
			$this->DB->where_in('poll_type', array(0, 3, $user_type));
		} elseif(is_null($has_results)) {
			$this->DB->where_in('poll_type', array(0, 4));
		}

		if(true == $has_results) {
			$this->DB->where('show_results', 1);
			$this->DB->join(RESPONSES_TABLE, RESPONSES_TABLE . '.poll_id=' . POLLS_TABLE . '.id', 'right');
		}

		if(count($denied_polls)) {
			$this->DB->where_not_in(POLLS_TABLE . '.id', $denied_polls);
		}

		return $this->DB->get()->row('id');
	}

	/**
	 *
	 * @param int $poll_id
	 * @return boolean
	 */
	public function show_results($poll_id) {
		if(is_null($poll_id)) {
			return false;
		}
		$may_show = (bool)$this->DB->select('show_results')
				->from(POLLS_TABLE)
				->where('id', $poll_id)
				->get()->row('show_results');
		return $may_show;
	}

	public function save_poll($data = array()) {
		$data = (array) $data;
		if (isset($data['id'])) {
			$poll_id = $data['id'];
			$this->DB->where('id', $poll_id);
			$this->DB->update(POLLS_TABLE, $data);
		} else {
			$this->DB->insert(POLLS_TABLE, $data);
			$poll_id = $this->DB->insert_id();
		}

		return $poll_id;
	}

	function delete_polls($poll_id = 0) {
		if (is_array($poll_id)) {
			$this->DB->where_in('id', $poll_id);
		} else {
			$this->DB->where('id', $poll_id);
		}
		$this->DB->delete(POLLS_TABLE);
		if (is_array($poll_id)) {
			$this->DB->where_in('poll_id', $poll_id);
		} else {
			$this->DB->where('poll_id', $poll_id);
		}
		$this->DB->delete(RESPONSES_TABLE);
		return;
	}

	function activate_polls($poll_id, $status = 1) {
		$attrs["status"] = intval($status);
		if (is_array($poll_id)) {
			$this->DB->where_in('id', $poll_id);
		} else {
			$this->DB->where('id', $poll_id);
		}
		$this->DB->update(POLLS_TABLE, $attrs);
	}

	function get_polls_count($params = array(), $filter_object_ids = null) {
		$this->DB->select("COUNT(*) AS cnt");
		$this->DB->from(POLLS_TABLE);
		if (isset($params["where"]) && is_array($params["where"]) && count($params["where"])) {
			foreach ($params["where"] as $field => $value) {
				$this->DB->where($field, $value);
			}
		}
		if (isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])) {
			foreach ($params["where_in"] as $field => $value) {
				$this->DB->where_in($field, $value);
			}
		}
		if (isset($params["where_not_in"]) && is_array($params["where_not_in"]) && count($params["where_not_in"])) {
			foreach ($params["where_not_in"] as $field => $value) {
				$this->DB->where_not_in($field, $value);
			}
		}
		if (isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])) {
			foreach ($params["where_sql"] as $value) {
				$this->DB->where($value, null, false);
			}
		}
		if (isset($params["like"]) && is_array($params["like"]) && count($params["like"])) {
			foreach ($params["like"] as $field => $value) {
				$this->DB->or_like($field, $value);
			}
		}
		if (isset($filter_object_ids) && is_array($filter_object_ids) && count($filter_object_ids)) {
			$this->DB->where_in("id", $filter_object_ids);
		}
		$result = $this->DB->get()->result();
		if (!empty($result)) {
			return intval($result[0]->cnt);
		} else {
			return 0;
		}
	}

	function get_polls_list($page = null, $items_on_page = null, $order_by = null, $params = array(), $filter_object_ids = null) {
		$this->DB->from(POLLS_TABLE);
		if (isset($params["where"]) && is_array($params["where"]) && count($params["where"])) {
			foreach ($params["where"] as $field => $value) {
				$this->DB->where($field, $value);
			}
		}
		if (isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])) {
			foreach ($params["where_in"] as $field => $value) {
				$this->DB->where_in($field, $value);
			}
		}
		if (isset($params["where_not_in"]) && is_array($params["where_not_in"]) && count($params["where_not_in"])) {
			foreach ($params["where_not_in"] as $field => $value) {
				$this->DB->where_not_in($field, $value);
			}
		}
		if (isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])) {
			foreach ($params["where_sql"] as $value) {
				$this->DB->where($value, null, false);
			}
		}
		if (isset($filter_object_ids) && is_array($filter_object_ids) && count($filter_object_ids)) {
			$this->DB->where_in("id", $filter_object_ids);
		}
		if (is_array($order_by) && count($order_by) > 0) {
			foreach ($order_by as $field => $dir) {
				if (in_array($field, $this->poll_attrs)) {
					$this->DB->order_by($field . " " . $dir);
				}
			}
		} else if ($order_by) {
			$this->DB->order_by($order_by);
		}
		if (!is_null($page)) {
			$page = intval($page) ? intval($page) : 1;
			$this->DB->limit($items_on_page, $items_on_page * ($page - 1));
		}
		$results = $this->DB->get()->result_array();

		if (!empty($results) && is_array($results)) {
			foreach ($results as $r) {
				$data[$r['id']] = $this->format_poll($r);
			}
			return $data;
		}
		return false;
	}

	// RESPONSES
	
	public function validate_comment($comment){
		$return = array("errors"=> array());
		if(isset($comment)){
			$this->CI->load->model('moderation/models/Moderation_badwords_model');
			$bw_count = $this->CI->Moderation_badwords_model->check_badwords($this->moderation_type, $comment);
			if($bw_count){
				$return['errors'][] = l('error_badwords_comment', 'polls');
			}
		}
		return $return;
	}

	public function save_respond($data = array()) {
		$data = (array) $data;

		if (isset($data['id'])) {
			$r_id = $data['id'];
			$this->DB->where('id', $r_id);
			$this->DB->update(RESPONSES_TABLE, $data);
		} else {
			$this->DB->insert(RESPONSES_TABLE, $data);
			$r_id = $this->DB->insert_id();
		}
		$this->update_poll_stat($data['poll_id']);
		return $r_id;
	}

	/**
	 * Updates polls statistics
	 *
	 * @param int $poll_id
	 */
	private function update_poll_stat($poll_id) {
		$responds = $this->DB->from(RESPONSES_TABLE)
				->where('poll_id', $poll_id)
				->get()->result_array();
		$stat = array();

		$oprions_count = $this->get_poll_options_count($poll_id);
		foreach ($responds as $respond) {
			for($option_num = 1; $option_num <= $oprions_count; $option_num++) {
				if(empty($stat[$option_num])) {
					$stat[$option_num] = 0;
				}
				if(true == $respond["answer_$option_num"]) {
					$stat[$option_num]++;
				}
			}
		}
		if(count($stat)) {
			$data['results'] = serialize($stat);
			$data['responds_count'] = count($responds);
			$this->DB->where('id', $poll_id)
					->update(POLLS_TABLE, $data);
		}
	}

	/**
	 *
	 * @param int $poll_id
	 * @return int
	 */
	private function get_poll_options_count($poll_id) {
		$answers_languages = $this->DB->select('answers_languages')
				->from(POLLS_TABLE)
				->where('id', $poll_id)
				->get()->result_array();

		// ...find a better way
		$answers_languages = unserialize($answers_languages[0]['answers_languages']);
		for($i = 1; $i <= 10; $i++) {
			if(!array_key_exists($i . '_default', $answers_languages)) {
				return $i - 1;
			}
		}
		return $i;
	}

	function get_responses_polls_ids($order_by = null, $params = array()) {
		$this->DB->from(RESPONSES_TABLE);
		if (isset($params["where"]) && is_array($params["where"]) && count($params["where"])) {
			foreach ($params["where"] as $field => $value) {
				$this->DB->where($field, $value);
			}
		}
		if (isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])) {
			foreach ($params["where_in"] as $field => $value) {
				$this->DB->where_in($field, $value);
			}
		}
		if (isset($params["where_not_in"]) && is_array($params["where_not_in"]) && count($params["where_not_in"])) {
			foreach ($params["where_not_in"] as $field => $value) {
				$this->DB->where_not_in($field, $value);
			}
		}
		if (isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])) {
			foreach ($params["where_sql"] as $value) {
				$this->DB->where($value, null, false);
			}
		}
		if (isset($filter_object_ids) && is_array($filter_object_ids) && count($filter_object_ids)) {
			$this->DB->where_in("id", $filter_object_ids);
		}
		if (is_array($order_by) && count($order_by) > 0) {
			foreach ($order_by as $field => $dir) {
				if (in_array($field, $this->poll_attrs)) {
					$this->DB->order_by($field . " " . $dir);
				}
			}
		} else if ($order_by) {
			$this->DB->order_by($order_by);
		}
		$results = $this->DB->get()->result_array();

		if (!empty($results) && is_array($results)) {
			foreach ($results as $r) {
				$data[$r['poll_id']] = $r['poll_id'];
			}
			return $data;
		}
		return false;
	}

	// EXPAND TABLES

	function expand_tables($max = 10) {
		$this->CI->load->dbforge();
		// Answers
		$table_fields = array(
			'id' => array(
				'type' => 'INT',
				'constraint' => '11',
				'null' => FALSE,
				'auto_increment' => TRUE,
			),
			'poll_id' => array(
				'type' => 'INT',
				'constraint' => '11',
				'null' => FALSE,
			),
		);
		for ($i = 1; $i <= $max; $i++) {
			$table_fields["answer_" . $i] = array(
				'type' => 'TEXT',
				'null' => FALSE,
			);
			$table_fields["answer_" . $i . "_color"] = array(
				'type' => 'VARCHAR',
				'constraint' => '6',
				'null' => FALSE,
			);
			$table_fields["answer_" . $i . "_results"] = array(
				'type' => 'INT',
				'constraint' => '11',
				'null' => FALSE,
			);
			foreach ($this->CI->pg_language->languages as $lang) {
				$table_fields["answer_" . $i . "_lang_" . $lang["id"]] = array(
					'type' => 'TEXT',
					'null' => FALSE,
				);
			}
		}

		// Responses
		$table_fields = array(
			'id' => array(
				'type' => 'INT',
				'constraint' => '11',
				'null' => FALSE,
				'auto_increment' => TRUE,
			),
			'poll_id' => array(
				'type' => 'INT',
				'constraint' => '11',
				'null' => FALSE,
			),
			'user_id' => array(
				'type' => 'INT',
				'constraint' => '11',
				'null' => FALSE,
			),
		);
		for ($i = 1; $i <= $max; $i++) {
			$table_fields["answer_" . $i] = array(
				'type' => 'TEXT',
				'null' => FALSE,
			);
		}
		$table_query = $this->db->get(RESPONSES_TABLE);
		$fields = $table_query->list_fields();
		$new_fields = array();
		foreach ($table_fields as $id => $values) {
			if (!in_array($id, $fields)) {
				$new_fields[$id] = $table_fields[$id];
			}
		}
		if (count($new_fields) > 0)
			foreach ($new_fields as $id => $field)
				$this->CI->dbforge->add_column(RESPONSES_TABLE, array($id => $field));
		// Update config
		$this->CI->pg_module->set_module_config('polls', 'max_answers', $max);
	}

	// RESULTS

	function get_results_count($params = array()) {
		$this->DB->select("COUNT(*) AS cnt");
		$this->DB->from(RESPONSES_TABLE);

		if (isset($params["where"]) && is_array($params["where"]) && count($params["where"])) {
			if(isset($params["where"]["user_type"])) {
				$this->db->join(USERS_TABLE, USERS_TABLE . '.id = ' . RESPONSES_TABLE . '.user_id', 'left');
			}
			foreach ($params["where"] as $field => $value) {
				$this->DB->where($field, $value);
			}
		}
		if (isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])) {
			foreach ($params["where_in"] as $field => $value) {
				$this->DB->where_in($field, $value);
			}
		}
		if (isset($params["where_not_in"]) && is_array($params["where_not_in"]) && count($params["where_not_in"])) {
			foreach ($params["where_not_in"] as $field => $value) {
				$this->DB->where_not_in($field, $value);
			}
		}
		if (isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])) {
			foreach ($params["where_sql"] as $value) {
				$this->DB->where($value, null, false);
			}
		}
		if (isset($params["like"]) && is_array($params["like"]) && count($params["like"])) {
			foreach ($params["like"] as $field => $value) {
				$this->DB->or_like($field, $value);
			}
		}

		$result = $this->DB->get()->result();

		if (!empty($result)) {
			return intval($result[0]->cnt);
		} else {
			return 0;
		}
	}

	function get_results_list($page = null, $items_on_page = null, $order_by = null, $params = array(), $by_poll = false) {
		$this->DB->select('*,' . RESPONSES_TABLE . '.date_add as response_date');
		$this->DB->from(RESPONSES_TABLE);

		if (isset($params["where"]) && is_array($params["where"]) && count($params["where"])) {
			if(isset($params['where']['user_type'])) {
				$this->db->join(USERS_TABLE, USERS_TABLE . '.id = ' . RESPONSES_TABLE . '.user_id', 'left');
			}
			foreach ($params["where"] as $field => $value) {
				$this->DB->where($field, $value);
			}
		}
		if (isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])) {
			foreach ($params["where_in"] as $field => $value) {
				$this->DB->where_in($field, $value);
			}
		}
		if (isset($params["where_not_in"]) && is_array($params["where_not_in"]) && count($params["where_not_in"])) {
			foreach ($params["where_not_in"] as $field => $value) {
				$this->DB->where_not_in($field, $value);
			}
		}
		if (isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])) {
			foreach ($params["where_sql"] as $value) {
				$this->DB->where($value, null, false);
			}
		}

		if (is_array($order_by) && count($order_by) > 0) {
			foreach ($order_by as $field => $dir) {
				$this->DB->order_by($field . " " . $dir);
			}
		} else if ($order_by) {
			$this->DB->order_by($order_by);
		}
		if (!is_null($page)) {
			$page = intval($page) ? intval($page) : 1;
			$this->DB->limit($items_on_page, $items_on_page * ($page - 1));
		}

		$results = $this->DB->get()->result_array();

		if (!empty($results) && is_array($results)) {
			foreach ($results as $r) {
				if ($by_poll) {
					$data[$r['poll_id']] = $r;
				} else {
					$data[] = $r;
				}
			}
			$data = $this->format_results($data);
			return $data;
		}
		return false;
	}

	public function format_results($data){
		if(!$this->results_format_settings['use_format']){
			return $data;
		}

		$users_search = array();

		foreach($data as $key=>$result){
			//	'get_user'
			if($this->results_format_settings['get_user']){
				$users_search[] = $result["user_id"];
			}
		}

		if($this->results_format_settings['get_user'] && !empty($users_search)) {
			$this->CI->load->model('Users_model');
			$users_data = $this->CI->Users_model->get_users_list_by_key(null, null, null, array(), $users_search);
			foreach($data as $key=>$result) {
				if(isset($users_data[$result['user_id']])) {
					$data[$key]['user'] = $users_data[$result['user_id']];
				} else {
					$data[$key]['user'] = $this->CI->Users_model->format_default_user($result['user_id']);
				}
				if($data[$key]['user']){
					$data[$key]['fname'] = $data[$key]['user']['fname'];
					$data[$key]['sname'] = $data[$key]['user']['sname'];
					$data[$key]['user_type'] = $data[$key]['user']['user_type'];
				}
			}
		}

		return $data;
	}


	// SEO & SITEMAP

	public function get_seo_settings($method, $lang_id = '') {
		if(!empty($method)){
			return $this->_get_seo_settings($method, $lang_id);
		}else{
			$actions = array('index');
			$return = array();
			foreach($actions as $action){
				$return[$action] = $this->_get_seo_settings($action, $lang_id);
			}
			return $return;
		}
	}
	
	/**
	 * Return data for rewrite seo urls (internal)
	 * 
	 * @param string $method method name
	 * @param integer $lang_id language identifier
	 * @return array
	 */
	public function _get_seo_settings($method, $lang_id = '') {	
		if ($method == "index") {
			return array(
				"templates" => array(),
				"url_vars" => array(),
				'url_postfix' => array(),
				'optional' => array(),
			);
		}
	}

	public function request_seo_rewrite($var_name_from, $var_name_to, $value) {
		return $value;
	}

	public function get_sitemap_xml_urls() {
		$this->CI->load->helper('seo');
		
		$lang_canonical = true;
				
		if($this->CI->pg_module->is_module_installed('seo')){
			$lang_canonical = $this->CI->pg_module->get_module_config('seo', 'lang_canonical');
		}
		
		if($lang_canonical){
			$default_lang_id = $this->CI->pg_language->get_default_lang_id();
			$default_lang_code = $this->CI->pg_language->get_lang_code_by_id($default_lang_id);
			$langs[$default_lang_id] = $default_lang_code;
		}else{
			foreach($this->CI->pg_language->languages as $lang_id=>$lang_data){
				$langs[$lang_id] = $lang_data['code'];
			}
		}
		
		$return = array();
		
		$user_settings = $this->pg_seo->get_settings('user', 'polls', 'index');
		if(!$user_settings['noindex']){	
			foreach($langs as $lang_id=>$lang_code){
				$return[] = array(
					"url" => rewrite_link('polls', 'index', array(), false, $lang_code, $lang_canonical),
					"priority" => 0.5
				);
			}
		}
		
		return $return;
	}

	public function get_sitemap_urls() {
		$this->CI->load->helper('seo');
		$auth = $this->CI->session->userdata("auth_type");
		$block = array();

		$block[] = array(
			"name" => l('header_polls_results', 'polls'),
			"link" => rewrite_link('polls', 'index'),
			"clickable" => true,
			"items" => array()
		);
		return $block;
	}
	
	// banners callback method
	public function _banner_available_pages(){
		$return[] = array("link"=>"polls/index", "name"=> l('header_polls_results', 'polls'));
		return $return;
	}

}
