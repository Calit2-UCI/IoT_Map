<?php

namespace Pg\Modules\Network\Models;

/**
 * Network users model
 *
 * @package PG_Dating
 * @subpackage application
 * @category modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * */
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

define('NET_TEMP', DB_PREFIX . 'net_temp');

/**
 * Network model
 */
class Network_users_model extends Network_model {

	const ACTION_ADD = 'add';
	const ACTION_UPDATE = 'update';
	const ACTION_REMOVE = 'remove';
	const PROFILE_DATA_FIELD = 'about_me';
	const PROFILE_DATA_DELIMITER = ': ';
	const STATUS_NOT_AGREE = 'not_agree';
	const STATUS_READY = 'ready';
	const STATUS_PROCESSED = 'processed';
	const STATUS_ERROR = 'error';
    const STATUS_OK = 'ok';
    const STATUS_NEED_ASK = 'need_ask';
	const TYPE_IN = 'in';
	const TYPE_OUT = 'out';
	const VALUE_ALL = 'all';

	private $_process_temp_time_limit = 60;
	private $_process_temp_limit = array(
		self::ACTION_ADD => 5,
		self::ACTION_UPDATE => 5,
		self::ACTION_REMOVE => 15,
	);
	// local field	=> net field
	private $_profile_data_assoc = array(
		'body' => 'body_type',
		'hair' => 'hair_colour',
		'height' => 'height',
		'weight' => 'weight',
	);
	private $_cache = array();
	private $_check_net_id_uniqueness = true;
	private $_fe_fields = array();
	private $_skip_action = array();
	private $_cfg_filter = array(
		'site_type',
		'user_type',
		'orientation',
		'lang',
		'land',
		'country_code',
		'min_age',
		'max_age',
	);
	private $_cfg_settings_limits = 'settings_limits';
	private $_cfg_profile_data_limits = 'profile_data_limits';
	private $_cfg_temp_processing_limits = 'temp_processing_limits';
	protected $CI;
	protected $DB;
	protected $_profile_data_field;
	protected $_common_user_data = array();
	public $db_fields = array(
		'id',
		'net_id',
		'local_id',
		'action',
		'type',
		'data',
		'created',
	);
	public $net_fields_validation = array(
		'id' => array(
			'type' => 'int',
			'required' => true,
		),
		'email' => array(
			'type' => 'string',
			'required' => true,
		),
		'user_type' => array(
			'type' => 'string',
			'values' => array(
				'male',
				'female',
				'transgender',
			),
			'required' => true,
		),
		'looking_user_type' => array(
			'type' => array(
				'string', 'array',
			),
			'values' => array(
				'male',
				'female',
				'transgender',
			),
			'required' => true,
		),
		'nickname' => array(
			'type' => 'string',
			'required' => true,
		),
		'fname' => array(
			'type' => 'string',
			'required' => false,
		),
		'sname' => array(
			'type' => 'string',
			'required' => false,
		),
		'lang' => array(
			'type' => 'string',
			'required' => true,
		),
		'icon' => array(
			'type' => 'string',
			'required' => true,
		),
		'country_code' => array(
			'type' => 'string',
			'required' => true,
		),
		'region' => array(
			'type' => 'string',
			'required' => true,
		),
		'city' => array(
			'type' => 'string',
			'required' => true,
		),
		'postal_code' => array(
			'string' => 'string',
			'required' => false,
		),
		'latitude' => array(
			'type' => 'decimal',
			'required' => false,
		),
		'longitude' => array(
			'type' => 'decimal',
			'required' => false,
		),
		'birth_date' => array(
			'type' => 'date',
			'required' => true,
		),
		'age_min' => array(
			'type' => 'int',
			'required' => false,
		),
		'age_max' => array(
			'type' => 'int',
			'required' => false,
		),
		'photos' => array(
			'type' => 'array',
			'required' => false,
		),
		'profile_data' => array(
			'type' => 'array',
			'required' => false,
			'values' => array(
				'headline' => array(
					'type' => 'string',
					'maxlength' => 255,
				),
				'description' => array(
					'type' => 'string',
					'maxlength' => 1000,
				),
				'marital_status' => array(
					'type' => 'string',
					'maxlength' => 100,
				),
				'occupation' => array(
					'type' => 'string',
					'maxlength' => 100,
				),
				'purpose' => array(
					'type' => 'string',
					'maxlength' => 100,
				),
				'race' => array(
					'type' => 'string',
					'maxlength' => 100,
				),
				'religion' => array(
					'type' => 'string',
					'maxlength' => 100,
				),
				'height' => array(
					'type' => 'string',
					'maxlength' => 100,
				),
				'weight' => array(
					'type' => 'string',
					'maxlength' => 100,
				),
				'body_type' => array(
					'type' => 'string',
					'maxlength' => 100,
				),
				'eye_colour' => array(
					'type' => 'string',
					'maxlength' => 100,
				),
				'hair_colour' => array(
					'type' => 'string',
					'maxlength' => 100,
				),
				'want_children' => array(
					'type' => 'string',
					'maxlength' => 100,
				),
				'have_children' => array(
					'type' => 'string',
					'maxlength' => 100,
				),
				'education' => array(
					'type' => 'string',
					'maxlength' => 100,
				),
				'smoking_habits' => array(
					'type' => 'string',
					'maxlength' => 100,
				),
				'drinking_habits' => array(
					'type' => 'string',
					'maxlength' => 100,
				),
				'hobby' => array(
					'type' => 'string',
					'maxlength' => 1000,
				),
			),
		),
	);

	/**
	 * Constructor
	 *
	 * @return Install object
	 */
	public function __construct() {
		parent::__construct();
		$this->CI = &get_instance();
		$this->DB = &$this->CI->db;
		$this->CI->load->model('Users_model');

		$this->_common_user_data = array(
			'confirm' => true,
			'approved' => true,
			'activity' => true,
			'net_status' => self::STATUS_OK,
			'confirm_code' => '',
		);
	}

	/**
	 * Prohibit the processing of the next action.
	 * @param string $action
	 */
	private function _forbid_action($action) {
		$this->_skip_action[$action] = true;
		return $this;
	}

	/**
	 * Check whether the actions processing is allowed or not.
	 * If not, allow it.
	 * @param string $action
	 * @return boolean
	 */
	private function _is_action_forbidden($action) {
		if (!empty($this->_skip_action[$action])) {
			$this->_skip_action[$action] = false;
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Initialize field editor module
	 */
	private function _init_field_editor() {
		$this->CI->load->model('Field_editor_model');
		$this->CI->Field_editor_model->initialize($this->CI->Users_model->form_editor_type);
		$this->_fe_fields = $this->CI->Field_editor_model->get_fields_list();
		foreach ($this->_fe_fields as $fe_field) {
			$fields_for_select[] = $fe_field['field_name'];
		}
		$this->CI->Users_model->set_additional_fields($fields_for_select);
	}

	/**
	 * Process user data before creation
	 * @param array $user
	 * @return array
	 */
	public function pre_create(array $user) {
		if (!isset($user['net_status'])) {
			$user['net_status'] = self::STATUS_READY;
		}
		return $user;
	}

	/**
	 * Get local id by net id
	 * @param int $net_id
	 * @return int
	 */
	public function get_local_id_by_net($net_id) {

		if (empty($this->_cache['local_id'][$net_id])) {
			$results = $this->DB->select('id')
							->from(USERS_TABLE)
							->where('net_id', $net_id)
							->get()->result_array();
			if (empty($results)) {
				$this->_cache['local_id'][$net_id] = 0;
			} else {
				$result = array_shift($results);
				$this->_cache['local_id'][$net_id] = (int) $result['id'];
			}
		}
		return $this->_cache['local_id'][$net_id];
	}

	/**
	 * Get net id by local id
	 * @param int $local_id
	 * @return int
	 */
	public function get_net_id_by_local($local_id) {
		if (empty($this->_cache['net_id'][$local_id])) {
			$results = $this->DB->select('net_id')
							->from(USERS_TABLE)
							->where('id', $local_id)
							->get()->result_array();
			if (empty($results)) {
				$this->_cache['net_id'][$local_id] = 0;
			} else {
				$result = array_shift($results);
				$this->_cache['net_id'][$local_id] = (int) $result['net_id'];
			}
		}
		return $this->_cache['net_id'][$local_id];
	}

	/**
	 * Get users from database
	 * @param int $count
	 * @return array
	 */
	public function get_profiles($count = 1) {
		// TODO: Переделать через NET_TEMP (или не переделать)
		$this->_init_field_editor();
		$params = array(
			'where' => array(
				'net_id' => '0',
				'net_status' => self::STATUS_READY,
				'user_logo !=' => '',
			),
		);
		$users = $this->CI->Users_model->get_users_list(1, $count, null, $params, array(), true, false, '');
		$formatted_users = array();
		foreach ($users as $user) {
			$formatted_users[] = $this->format_user_out($user);
		}
		return $formatted_users;
	}

	/**
	 * Get temp records
	 * @param int|array $net_id
	 * @param int|array $local_id
	 * @param string|array $action
	 * @param string|array $type
	 * @param array $args
	 * @return array
	 */
	public function get_temp_records($net_id = null, $local_id = null, $action = null, $type = null, $args = null) {
		$this->DB->select($args ? : $this->db_fields)->from(NET_TEMP);
		foreach (array('net_id', 'local_id', 'action', 'type') as $field) {
			if (empty($$field)) {
				continue;
			}
			if (is_array($$field)) {
				$this->DB->where_in($field, array_unique($$field));
			} else {
				$this->DB->where($field, $$field);
			}
		}
		return $this->DB->order_by('created')->get()->result_array();
	}

	/**
	 * Delete temp record
	 * @param int|array $net_id
	 * @param int|array $local_id
	 * @param string|array $action
	 * @param string|array $type
	 * @return boolean
	 */
	public function delete_temp_records($net_id = null, $local_id = null, $action = null, $type = null) {
		foreach (array('net_id', 'local_id', 'action', 'type') as $field) {
			if (empty($$field)) {
				continue;
			}
			if (is_array($$field)) {
				$this->DB->where_in($field, array_unique($$field));
			} else {
				$this->DB->where($field, $$field);
			}
		}
		$this->DB->delete(NET_TEMP);
		return true;
	}

	/**
	 * Format temp profile
	 * @param array $user
	 * @param string $type
	 * @return array
	 */
	private function _format_temp_profile(array $user, $type) {
		if (!empty($user['id'])) {
			if (self::TYPE_OUT === $type) {
				$local_id = (int) $user['id'];
			} elseif (self::TYPE_IN === $type) {
				$net_id = (int) $user['id'];
			}
		} 
		if (empty($net_id)) {
			if (isset($user['net_id'])) {
				$net_id = (int) $user['net_id'];
			} else {
				$net_id = 0;
			}
		}
		if (empty($user['net_id'])) {
			$user['net_id'] = $net_id;
		}
		if (empty($local_id)) {
			if (isset($user['local_id'])) {
				$local_id = (int) $user['local_id'];
			} else {
				$local_id = 0;
			}
		} 
		if (empty($user['local_id'])) {
			$user['local_id'] = $local_id;
		}
		if (!empty($net_id) && self::TYPE_OUT === $type) {
			$user['id'] = $net_id;
		} elseif (!empty($local_id) && self::TYPE_IN === $type) {
			$user['id'] = $local_id;
		}
		return $user;
	}

	/**
	 * Check temp record before insertion
	 * @param array $temp_record
	 */
	private function _check_temp_record(array $temp_record) {
		if (self::TYPE_IN === $temp_record['type']) {
			if (self::ACTION_REMOVE === $temp_record['action'] || self::ACTION_ADD === $temp_record['action']) {
				// Check uniqueness
				return !(bool) $this->get_temp_records($temp_record['net_id'], null, $temp_record['action'], self::TYPE_IN, array('id'));
			}
		}
		return true;
	}

	/**
	 * Add temp record
	 * @param array $users
	 * @param string $action
	 * @param string $type
	 * @param int $batch_max_size
	 * @return array
	 */
	public function set_temp_profiles(array $users, $action, $type, $batch_max_size = 50) {
		$temp_records = array();
		$batch = array();
		$profiles_count = count($users);
		for ($i = 0, $batch_size = 0; $i < $profiles_count; $i++, $batch_size++) {
			$profile = $this->_format_temp_profile($users[$i], $type);
			$temp_record = array(
				'net_id' => $profile['net_id'],
				'local_id' => $profile['local_id'],
				'data' => serialize($profile),
				'action' => $action,
				'type' => $type,
			);
			if (!$this->_check_temp_record($temp_record)) {
				continue;
			}
			$batch[] = $temp_record;
			if ($batch_size > $batch_max_size || $i === $profiles_count - 1) {
				$this->DB->insert_batch(NET_TEMP, $batch);
				$temp_records = array_merge($temp_records, $batch);
				$batch = array();
				$batch_size = 0;
			}
		}
		return $temp_records;
	}

	/**
	 * User deleted callback
	 * @param array $user
	 * @return array
	 */
	public function user_deleted($user) {
		if ($this->_is_action_forbidden(self::ACTION_REMOVE) || (empty($user['id']) && empty($user['net_id']))) {
			return $user;
		} else {
			$net_data = array(
				'id' => (int) $user['id'],
				'net_id' => (int) $user['net_id'],
			);
			return $this->set_temp_profiles(array($net_data), self::ACTION_REMOVE, self::TYPE_OUT);
		}
	}

	/**
	 * User updated callback
	 * @param int $local_id
	 * @param array $new_user_data
	 * @return array
	 */
	public function user_updated($local_id, array $new_user_data/* , $merge = true */) {
		if ($this->_is_action_forbidden(self::ACTION_UPDATE)) {
			return $new_user_data;
		}
		// TODO: Проверять, изменились ли поля
		if (!empty($local_id) && empty($new_user_data['net_is_incomer'])) {
			$this->_init_field_editor();
			$result = $this->DB->select(array('net_id', 'net_is_incomer'))
							->from(USERS_TABLE)
							->where('id', $local_id)
							->get()->result_array();
			if (empty($result)) {
				return $new_user_data;
			}
			$net_data = $result[0];
			$net_data['id'] = (int) $local_id;
			if (empty($net_data['net_is_incomer']) && !empty($net_data['net_id'])) {
				/*$temp_records = $this->get_temp_records(null, $local_id, self::ACTION_UPDATE, self::TYPE_OUT, 'data');
				$final_data = array();
				foreach ($temp_records as $temp_record) {
					$data = unserialize($temp_record['data']);
					$final_data = array_merge($final_data, $data);
				}*/
				$net_user = $this->format_user_out(array_merge($new_user_data, $net_data));
				$this->set_temp_profiles(array($net_user), self::ACTION_UPDATE, self::TYPE_OUT);
				return $net_user;
			}
		}
		return $new_user_data;
	}

	/**
	 * Parse users to form an array of statuses
	 * @param array $users
	 * @return array
	 */
	private function _prepare_statuses(array $users) {
		if (empty($users)) {
			return array();
		}
		$errors_ds = $this->CI->pg_language->ds->get_reference(
				self::MODULE_GID, 'error', $this->CI->pg_language->get_default_lang_id());
		$errors_keys = array_keys($errors_ds['option']);
		$statuses = array();
		$info = array();
		$success = array();
		foreach ($users as $local_id => $status) {
			if ($status > 0) {
				$success[$status] = array((int) $local_id);
				$info_txt = '';
				$status = self::STATUS_OK;
			} elseif (in_array($status, $errors_keys)) {
				$info_txt = $status;
				$status = self::STATUS_ERROR;
			} else {
				$info_txt = '';
			}
			$info[$info_txt][] = $local_id;
			$statuses[$status][] = $local_id;
		}
		return array('id' => $success, 'status' => $statuses, 'info' => $info);
	}

	/**
	 * Build sql query to update net statuses at the users table
	 * @param array $users
	 * @return string
	 */
	private function _build_statuses_query(array $users) {
		$statuses = $this->_prepare_statuses($users);
		if (empty($statuses)) {
			return '';
		}
		$users_ids = array();
		$start_sql = 'UPDATE `' . USERS_TABLE . '` SET';
		foreach ($statuses as $status => $data) {
			if (empty($data)) {
				continue;
			}
			$mid_sql[$status] = PHP_EOL . '`net_' . $status . '` = CASE' . PHP_EOL;
			foreach ($data as $key => $users) {
				$mid_sql[$status] .= " WHEN `id` IN('" . implode("','", $users) . "') THEN '$key'" . PHP_EOL;
				$users_ids = array_merge($users_ids, $users);
			}
			$mid_sql[$status] .= 'END';
		}
		if (empty($users_ids)) {
			return '';
		} else {
			$end_sql = PHP_EOL . " WHERE `id` IN ('" . implode("','", array_unique($users_ids)) . "');" . PHP_EOL;
			return $start_sql . implode(',', $mid_sql) . $end_sql;
		}
	}

	/**
	 * Update net statuses at users table
	 * @param array $users
	 * @return boolean
	 */
	public function set_profiles_status(array $users) {
		$query = $this->_build_statuses_query($users);
		if (!empty($query)) {
			$result = (bool) $this->DB->query($query);
			$this->update_last_id();
			return $result;
		} else {
			return false;
		}
	}

	/**
	 * Get last net id
	 * @return int
	 */
	public function get_last_id() {
		return $this->_get_last_id_cfg() ? :
				$this->_get_last_id_new_profiles() ? :
						$this->_get_last_id_users();
	}

	/**
	 * Update last net id from parameter or on the basis of available data.
	 * @param int $last_id
	 * @return int last net id
	 */
	public function update_last_id($last_id = null) {
		if (empty($last_id) && 0 !== $last_id) {
			$last_id = $this->_get_last_id_new_profiles();
			if (empty($last_id)) {
				$last_id = $this->_get_last_id_users();
			}
		}
		if (empty($last_id)) {
			$last_id = 0;
		}
		$this->CI->pg_module->set_module_config(self::MODULE_GID, 'last_id', $last_id);
		return $last_id;
	}

	/**
	 * Get the last net id from net_temp table
	 * @return int
	 */
	private function _get_last_id_new_profiles() {
		$results = $this->DB->select('MAX(net_id) as last_id')
						->from(NET_TEMP)
						->get()->result_array();
		if (empty($results)) {
			return 0;
		} else {
			$result = array_shift($results);
			return (int) $result['last_id'];
		}
	}

	/**
	 * Get the last net id from users table
	 * @return int
	 */
	private function _get_last_id_users() {
		$results = $this->DB->select('MAX(net_id) as last_id')
						->from(USERS_TABLE)
						->get()->result_array();
		if (empty($results)) {
			return 0;
		} else {
			$result = array_shift($results);
			return (int) $result['last_id'];
		}
	}

	/**
	 * Get last net id from module config
	 * @return int
	 */
	private function _get_last_id_cfg() {
		return (int) $this->CI->pg_module->get_module_config(self::MODULE_GID, 'last_id');
	}

	/**
	 * Check file existence over http
	 * @param string $url
	 * @return boolean
	 */
	private function _net_file_exists($url) {
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_exec($ch);
		$retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		return $retcode < 400;
	}

	/**
	 * Upload file to the temporary folder
	 * @param string $file_url
	 * @param string $subfolder
	 * @return string File path
	 */
	private function _upload_tmp_file($file_url, $subfolder = '') {
		if (!$this->_net_file_exists($file_url)) {
			return '';
		}
		$file = pathinfo($file_url);
		if (!$file) {
			return '';
		}
		$temp_path = TEMPPATH . self::MODULE_GID . '/' . $subfolder;
		$n = '';
		if ($subfolder && !file_exists($temp_path)) {
			mkdir($temp_path, 0777, true);
		}
		do {
			$dest_full_name = $temp_path . $file['filename'] . $n++ . '.' . $file['extension'];
			$n = (int) $n + 1;
		} while (file_exists($dest_full_name));
		$this->CI->load->helper('file');
		$is_downloaded = download_file($file_url, $dest_full_name, false, 0777);
		if ($is_downloaded) {
			return $dest_full_name;
		} else {
			return '';
		}
	}

	/**
	 * Delete file from the temporary folder
	 * @param string $file_name
	 * @param string $subfolder
	 * @return boolean
	 */
	private function _delete_tmp_file($file_name, $subfolder = '') {
		$file = pathinfo($file_name);
		if (!$file) {
			return false;
		}
		$file_path = TEMPPATH . self::MODULE_GID . '/' . $subfolder . $file['basename'];
		if (file_exists($file_path)) {
			return unlink($file_path);
		} else {
			return false;
		}
	}

	/**
	 * Load photos and save it by user id
	 * @param array $photos
	 * @param int $user_id
	 * @return boolean
	 */
	private function _load_user_photos(array $photos, $user_id) {
		$user_id = (int) $user_id;
		if (empty($photos) || empty($user_id)) {
			log_message('error', '(' . self::MODULE_GID . ') Wrong _load_user_photos() params (' . gettype($photos) . ', ' . gettype($user_id) . ')');
			return false;
		}
		$local_photos = array();
		foreach ($photos as $photo_url) {
			$tmp_file = $this->_upload_tmp_file($photo_url, $user_id . '/');
			if ($tmp_file) {
				$local_photos[] = $tmp_file;
			}
		}
		if (!$local_photos) {
			return false;
		}
		$this->CI->load->model('Media_model');
		$data = array(
			'id_user' => $user_id,
			'id_owner' => $user_id,
			'type_id' => $this->CI->Media_model->album_type_id,
			'upload_gid' => $this->CI->Media_model->file_config_gid,
			'status' => 1,
		);
		$result = array();
		foreach ($local_photos as $local_photo) {
			$result[] = $this->CI->Media_model->save_image(null, $data, $local_photo, false);
		}
		return $result;
	}

	/**
	 * Check users uniqueness.
	 * By default removes non-unique users from temp table.
	 * @param array $temp_data users
	 * @param boolean $tmp_remove
	 * @return array Not unique ids
	 */
	private function _check_users_uniqueness(array $temp_data, $tmp_remove = true) {
		$temp_net_ids = array();
		foreach ($temp_data as $temp_user) {
			if (!empty($temp_user['net_id'])) {
				$temp_net_ids[] = $temp_user['net_id'];
			}
		}
		$result = $this->DB->select('net_id')
						->from(USERS_TABLE)
						->where_in('net_id', $temp_net_ids)
						->get()->result_array();
		$not_unique_net_ids = array();
		foreach ($result as $record) {
			if (!in_array($record['net_id'], $not_unique_net_ids)) {
				$not_unique_net_ids[] = $record['net_id'];
			} else {
				log_message('error', '(' . self::MODULE_GID . ') Duplicate users: net_id ' . $record['net_id']);
			}
		}
		if ($tmp_remove && !empty($not_unique_net_ids)) {
			$this->delete_temp_records($not_unique_net_ids);
		}
		return $not_unique_net_ids;
	}

	/**
	 * Get user by net_id
	 * @param int $net_id
	 * @param array $fields List of required fields
	 * @return array
	 */
	public function get_user_by_net_id($net_id, array $fields = null, $expect_lots = false) {
		if (is_null($fields)) {
			$fields = $this->CI->Users_model->fields_all;
		}
		$this->DB->select($fields)
				->from(USERS_TABLE)
				->where('net_id', $net_id);
		if ($expect_lots) {
			$this->DB->limit(1);
		}
		$temp_data = $this->DB->get()->result_array();
		if (!empty($temp_data)) {
			return $temp_data[0];
		} else {
			return array();
		}
	}

	/**
	 * Processes records from the temporary table
	 * @return int Number of processed records
	 */
	public function process_temp() {
		set_time_limit($this->_process_temp_time_limit);
		// TODO: Лимит не корректный. Не известно, кого сколько нужно.
		$limit = array_sum($this->_process_temp_limit);
		$temp_data = $this->DB->select($this->db_fields)
						->from(NET_TEMP)
						->where('type', self::TYPE_IN)
						->order_by('created ASC')
						->limit($limit)
						->get()->result_array();

		$actions_data = array();
		foreach ($temp_data as $record) {
			$actions_data[$record['action']][] = $record;
		}
        if (!empty($actions_data[self::ACTION_ADD])) {
			$actions_data[self::ACTION_ADD] = array_slice($actions_data[self::ACTION_ADD], 0, $this->_process_temp_limit[self::ACTION_ADD]);
			$this->_add_from_temp($actions_data[self::ACTION_ADD], $actions_data[self::ACTION_ADD]);
		}
		if (!empty($actions_data[self::ACTION_REMOVE])) {
			$actions_data[self::ACTION_REMOVE] = array_slice($actions_data[self::ACTION_REMOVE], 0, $this->_process_temp_limit[self::ACTION_REMOVE]);
			$this->_remove_from_temp($actions_data[self::ACTION_REMOVE]);
		}
		if (!empty($actions_data[self::ACTION_UPDATE])) {
			$actions_data[self::ACTION_UPDATE] = array_slice($actions_data[self::ACTION_UPDATE], 0, $this->_process_temp_limit[self::ACTION_UPDATE]);
			$this->_update_from_temp($actions_data[self::ACTION_UPDATE]);
		}
	}

	/**
	 * Add net users from temporary table
	 * @param array $temp_data Data from temp table
	 * @return array
	 */
	private function _add_from_temp(array $temp_data) {
		if (empty($temp_data)) {
			return array();
		}
		if ($this->_check_net_id_uniqueness) {
			$skip_net_ids = $this->_check_users_uniqueness($temp_data);
		} else {
			$skip_net_ids = array();
		}
		$this->_init_field_editor();
		$new_temp_data = array();
		foreach ($temp_data as $temp_record) {
			if (in_array($temp_record['net_id'], $skip_net_ids)) {
				continue;
			}
			$user_data = unserialize($temp_record['data']);
			$new_temp_data['local_id'][$temp_record['id']] = $this->_add_user($user_data);
			$new_temp_data['type'][$temp_record['id']] = self::TYPE_OUT;
		}
		$this->_update_temp($new_temp_data);
		$this->update_last_id();
		return $new_temp_data;
	}

	/**
	 * Remove from site users, which are marked as removed in temp table
	 * @param array $temp_data
	 * @return array
	 */
	private function _remove_from_temp(array $temp_data) {
		if (empty($temp_data)) {
			return array();
		}
		$new_temp_data = array();
		foreach ($temp_data as $temp_record) {
			if ($this->_delete_user($temp_record['net_id'])) {
				$new_temp_data['type'][$temp_record['id']] = self::TYPE_OUT;
			}
		}
		$this->_update_temp($new_temp_data);
		return $new_temp_data;
	}

	/**
	 * Update site users, which are marked for update in temp table
	 * @param array $temp_data
	 * @return array
	 */
	private function _update_from_temp(array $temp_data) {
		if (empty($temp_data)) {
			return array();
		}
		$updated_net_ids = array();
		$this->_init_field_editor();
		foreach ($temp_data as $temp_record) {
			$user_data = unserialize($temp_record['data']);
			$user_data['id'] = $this->get_local_id_by_net($temp_record['net_id']);
			$this->_forbid_action(self::ACTION_UPDATE);
			$this->_update_user($user_data);
			$updated_net_ids[] = $temp_record['net_id'];
		}
		$this->delete_temp_records($updated_net_ids, null, 'update');
		return $updated_net_ids;
	}

	/**
	 * Add net user from temporary table
	 * @param array $net_user Net user's data
	 * @return int Local user id
	 */
	private function _add_user(array $net_user) {
		$formatted_data = $this->format_user_in($net_user);

		if (false === $formatted_data) {
			return false;
		}
		$local_user = array_merge($this->_common_user_data, $formatted_data);
		$local_user['net_is_incomer'] = true;
        
        $is_upload_photos = $this->CI->pg_module->get_module_config(self::MODULE_GID, "is_upload_photos");
        if ($is_upload_photos) {
            $user_logo = $this->_upload_tmp_file($net_user['icon']);
        } else {
            $local_user["user_logo"] = $net_user['icon'];
            $user_logo = null;
        }
        
        $user_id = $this->CI->Users_model->save_user(null, $local_user, $user_logo, false);
        
        if ($is_upload_photos) {
            $this->_delete_tmp_file($net_user['icon']);
        }
        
        $photos = @unserialize($net_user['photos']);
        if ($photos) {
            if ($is_upload_photos) {
                $this->_load_user_photos($photos, $user_id);
            } else {
                $this->CI->load->model('Media_model');
                foreach ($photos as $photo) {
                    $image_size = getimagesize($photo);
                    $img_data = array(
                        "id_user" => $user_id,
                        "id_owner" => $user_id,
                        "type_id" => $this->CI->Media_model->album_type_id,
                        "upload_gid" => $this->CI->Media_model->file_config_gid,
                        "mediafile" => $photo, 
                        "fname" => basename($photo),
                        "settings" => serialize(array("width" => $image_size[0], "height" => $image_size[1])),
                        "status" => 1,
                    );
                    $this->CI->Media_model->save_image(null, $img_data);
                }
            }
        }
        
        $this->_forbid_action(self::ACTION_UPDATE);
        
		return $user_id;
	}

	/**
	 * Delete user.
	 * Executes all callbacks.
	 * @param int $net_id
	 * @return boolean
	 */
	private function _delete_user($net_id) {
		$user = $this->get_user_by_net_id($net_id);
		if (empty($user)) {
			return true;
		}
		$this->CI->load->model('users/models/Users_delete_callbacks_model');
		$callbacks = $this->CI->Users_delete_callbacks_model->get_all_callbacks_gid();
		$this->_forbid_action(self::ACTION_REMOVE);
		$this->CI->Users_model->callback_user_delete($user['id'], null, $callbacks);
		return true;
	}

	/**
	 * Update user
	 * @param array $net_user
	 */
	private function _update_user(array $net_user) {
        $this->_forbid_action(self::ACTION_UPDATE);
        
		$local_user = $this->format_user_in($net_user);
		if (!empty($net_user['icon'])) {
            $is_upload_photos = $this->CI->pg_module->get_module_config(self::MODULE_GID, "is_upload_photos");
            if ($is_upload_photos) {
                $user_logo = $this->_upload_tmp_file($net_user['icon']);
            } else {
                $local_user['user_logo'] = $net_user['icon'];
                $user_logo = null;
            }
		} else {
			$user_logo = null;
		}
        
        if (!empty($net_user['id'])) {
			$this->CI->Users_model->save_user($net_user['id'], $local_user, $user_logo, false);
		} else {
			log_message('error', '(' . self::MODULE_GID . ') Empty local_id');
		}
        
        
	}

	/**
	 * Build sql query to update net table
	 * @param array $new_temp_data
	 * @return string Sql query
	 */
	private function _build_temp_update_query(array $new_temp_data) {
		if (empty($new_temp_data)) {
			return '';
		}
		$ids = array();
		$sql_mid = array();
		foreach ($new_temp_data as $field => $values) {
			$case = "`$field` = CASE" . PHP_EOL;
			foreach ($values as $id => $value) {
				$case .= "WHEN `id` = '$id' THEN '$value'" . PHP_EOL;
				$ids[] = $id;
			}
			$case .= 'END' . PHP_EOL;
			$sql_mid[] = $case;
		}
		$sql = 'UPDATE `' . NET_TEMP . '` SET' . PHP_EOL . implode(',', $sql_mid)
				. "WHERE `id` IN ('" . implode("','", array_unique($ids)) . "');" . PHP_EOL;
		return $sql;
	}

	/**
	 * Update temp table records
	 * @param array $new_temp_data
	 * @return boolean
	 */
	private function _update_temp(array $new_temp_data) {
		if (empty($new_temp_data)) {
			return false;
		}
		$query = $this->_build_temp_update_query($new_temp_data);
		if (empty($query)) {
			return false;
		} else {
			return (bool) $this->DB->query($query);
		}
	}

	/**
	 * Format language
	 * @param string $lang_code
	 * @return int
	 */
	private function _format_lang_in($lang_code) {
		if (empty($lang_code)) {
			return '';
		}
		if (empty($this->_cache['lang'][$lang_code])) {
			$this->_cache['lang'][$lang_code] = (int) $this->CI->pg_language->get_lang_id_by_code($lang_code) ? :
					$this->CI->pg_language->get_default_lang_id();
		}
		return $this->_cache['lang'][$lang_code];
	}

	/**
	 * Format language
	 * @param int $lang_id
	 * @return string
	 */
	private function _format_lang_out($lang_id) {
		if (empty($lang_id)) {
			return '';
		}
		if (empty($this->_cache['lang'][$lang_id])) {
			$this->_cache['lang'][$lang_id] = strtoupper($this->CI->pg_language->get_lang_code_by_id($lang_id) ? :
							$this->CI->pg_language->get_lang_code_by_id($this->CI->pg_language->get_default_lang_id()));
		}
		return $this->_cache['lang'][$lang_id];
	}

	/**
	 * Format region
	 * @param string $region
	 * @param string $country_code
	 * @return int
	 */
	private function _format_region_in($region, $country_code) {
		if (empty($this->_cache['region'][$region])) {
			$this->CI->load->model('Countries_model');
			$region_data = $this->CI->Countries_model->get_region_by_name($region, $country_code);
			if (!empty($region_data['id'])) {
				$this->_cache['region'][$region] = (int) $region_data['id'];
			} else {
				// TODO: Добавлять регионы динамически (?)
				$this->_cache['region'][$region] = 0;
			}
		}
		return $this->_cache['region'][$region];
	}

	/**
	 * Format region
	 * @param int $region_id
	 * @return string
	 */
	private function _format_region_out($region_id) {
		if (empty($this->_cache['region'][$region_id])) {
			$this->CI->load->model('Countries_model');
			$region_data = $this->CI->Countries_model->get_region($region_id);
			if (!empty($region_data['name'])) {
				$this->_cache['region'][$region_id] = $region_data['name'];
			} else {
				$this->_cache['region'][$region_id] = '';
			}
		}
		return $this->_cache['region'][$region_id];
	}

	/**
	 * Format city
	 * @param string $city_name
	 * @param int $region_id
	 * @param string $country_code
	 * @return int
	 */
	private function _format_city_in($city_name, $region_id = null, $country_code = null) {
		if (empty($city_name)) {
			return '';
		}
		if (empty($this->_cache['city'][$city_name])) {
			$this->CI->load->model('Countries_model');
			$city_data = $this->CI->Countries_model->get_city_by_name($city_name, $region_id, $country_code);
			if (!empty($city_data['id'])) {
				$this->_cache['city'][$city_name] = $city_data['id'];
			} else {
				// TODO: Добавлять города динамически (?)
				$this->_cache['city'][$city_name] = '';
			}
		}
		return $this->_cache['city'][$city_name];
	}

	/**
	 * Format city
	 * @param int $city_id
	 * @return string
	 */
	private function _format_city_out($city_id) {
		if (empty($city_id)) {
			return '';
		}
		if (empty($this->_cache['city'][$city_id])) {
			$this->CI->load->model('Countries_model');
			$city_data = $this->CI->Countries_model->get_city($city_id);
			if (!empty($city_data['name'])) {
				$this->_cache['city'][$city_id] = $city_data['name'];
			} else {
				$this->_cache['city'][$city_id] = '';
			}
		}
		return $this->_cache['city'][$city_id];
	}

	/**
	 * Format photos
	 * @param int $user_id
	 * @param int $max_photo_num
	 * @return array
	 */
	private function _format_photos_out($user_id, $max_photo_num = 5) {
		$this->CI->load->model('Media_model');
		$params = array(
			'where' => array(
				'id_user' => $user_id,
				'status' => 1,
				'upload_gid' => $this->CI->Media_model->file_config_gid,
			),
		);
		$list = $this->CI->Media_model->get_media(1, $max_photo_num, null, $params, null, true);
		if (empty($list)) {
			return array();
		}
		$files = array();
		foreach ($list as $file) {
			$files[] = $file['media']['mediafile']['file_url'];
		}
		return $files;
	}

	/**
	 * Format data
	 * @param array $user
	 * @return string
	 */
	private function _format_profile_data_in(array $user) {
		if (empty($user['profile_data'])) {
			return array();
		}
		$str_delim = PHP_EOL;
		if (empty($this->_fe_fields[self::PROFILE_DATA_FIELD]['field_name'])) {
			log_message('error', '(' . self::MODULE_GID . ') Wrong profile data field');
			return array();
		} else {
			$profile_data_field = $this->_fe_fields[self::PROFILE_DATA_FIELD]['field_name'];
		}
		if(is_string($user['profile_data'])) {
			$profile_data = @unserialize($user['profile_data']);
		} else {
			$profile_data = &$user['profile_data'];
		}
		$result = array();
		$result_arr = array();
		foreach ($profile_data as $field => $value) {
			if (!empty($value)) {
				$result_arr[] = $field . self::PROFILE_DATA_DELIMITER . $value;
			}
		}
		$result[$profile_data_field] = implode($str_delim, $result_arr);
		return $result;
	}

	/**
	 * Format profile data
	 * @param array $user
	 * @return array
	 */
	private function _format_profile_data_out(array $user) {
		$result = array();
		foreach ($this->_profile_data_assoc as $local_field => $net_field) {
			if (!empty($user[$this->_fe_fields[$local_field]['field_name']])) {
				$option_key = $user[$this->_fe_fields[$local_field]['field_name']];
				$result[$net_field] = $this->_fe_fields[$local_field]['options']['option'][$option_key];
			}
		}
		return $result;
	}

	/**
	 * Format common user fields
	 * @param array $user
	 * @return array
	 */
	private function _format_user_common(array $user) {
		return filter_var_array($user, array(
			'id' => FILTER_VALIDATE_INT,
			'net_id' => FILTER_VALIDATE_INT,
			'age_max' => FILTER_VALIDATE_INT,
			'age_min' => FILTER_VALIDATE_INT,
			'fname' => FILTER_SANITIZE_STRING,
			'looking_user_type' => FILTER_SANITIZE_STRING,
			'nickname' => FILTER_SANITIZE_STRING,
			'postal_code' => FILTER_SANITIZE_STRING,
			'sname' => FILTER_SANITIZE_STRING,
			'user_type' => FILTER_SANITIZE_STRING,
			'birth_date' => array(
				'filter' => FILTER_CALLBACK,
				'options' => function($birth_date) {
					$date = new \DateTime($birth_date);
					return $date->format('Y-m-d');
				},
			),
		), false);
	}

	/**
	 * Format user inside
	 * @param array $user
	 * @param int $lang_id
	 * @return array
	 */
	public function format(&$user, $lang_id) {
		$field = &$this->_fe_fields[self::PROFILE_DATA_FIELD]['field_name'];
		if (!empty($user[$field])) {
			$lang = $this->CI->pg_language->ds->get_reference(self::MODULE_GID, 'profile_data', $lang_id);
			foreach ($this->_profile_data_assoc as $net_field) {
				$user[$field] = str_ireplace(
						$net_field . self::PROFILE_DATA_DELIMITER, 
						$lang['option'][$net_field] . self::PROFILE_DATA_DELIMITER, 
						$user[$field]);
			}
		}
		return $user;
	}

	/**
	 * Format user
	 * @param array $user
	 * @return array
	 */
	public function format_user_in(array $user) {
		$formated_user = $this->_format_user_common($user);

		if (isset($user['email'])) {
			$formated_user['email'] = $user['email'];
		}
		if (isset($user['country_code'])) {
			$formated_user['id_country'] = $user['country_code'];
		}
		if (isset($user['region'])) {
			$id_region = $this->_format_region_in($user['region'], isset($user['country_code']) ? $user['country_code'] : null);
			if (0 === $id_region) {
				return false;
			}
			$formated_user['id_region'] = $id_region;
		}
		if (isset($user['city'])) {
			$id_city = $this->_format_city_in($user['city'], isset($formated_user['id_region']) ? $formated_user['id_region'] : null, isset($user['country_code']) ? $user['country_code'] : null);
			if (0 === $id_city) {
				return false;
			}
			$formated_user['id_city'] = $id_city;
		}
		if (isset($user['lang'])) {
			$formated_user['lang_id'] = $this->_format_lang_in($user['lang']);
		}

		foreach ($this->_format_profile_data_in($user) as $fe_field => $value) {
			$formated_user[$fe_field] = $value;
		}
		return $formated_user;
	}

	/**
	 * Format user
	 * @param array $user
	 * @return array
	 */
	public function format_user_out(array $user) {
		$formated_user = $this->_format_user_common($user);
		if (isset($user['net_id'])) {
			$formated_user['net_id'] = $user['net_id'];
		}
		if (isset($user['email']) && isset($user['nickname'])) {
			$formated_user['email'] = md5($user['nickname'] . $user['email']);
		}
		if (isset($user['id_country'])) {
			$formated_user['country_code'] = $user['id_country'];
		}
		if (isset($user['id_region'])) {
			$formated_user['region'] = $this->_format_region_out($user['id_region']);
		}
		if (isset($user['id_city'])) {
			$formated_user['city'] = $this->_format_city_out($user['id_city']);
		}
		if (isset($user['media']['user_logo']['file_url'])) {
			$formated_user['icon'] = $user['media']['user_logo']['file_url'];
		}
		if (isset($user['lang_id'])) {
			$formated_user['lang'] = $this->_format_lang_out($user['lang_id']);
		}
		if (isset($user['id'])) {
			$formated_user['id'] = $user['id'];
			$formated_user['photos'] = $this->_format_photos_out($user['id']);
		}
		$formated_user['profile_data'] = $this->_format_profile_data_out($user);
		return $formated_user;
	}

	/**
	 * Get settings
	 * @param mixed $cfg_gids
	 * @return array
	 */
	public function get_config($cfg_gids = null) {
		if (empty($cfg_gids)) {
			$cfg_gids = array_merge((array) $this->_cfg_data, (array) $this->_cfg_filter, (array) $this->_cfg_profile_data_limits, (array) $this->_cfg_settings_limits, (array) $this->_cfg_temp_processing_limits);
		} elseif (!is_array($cfg_gids)) {
			$cfg_gids = array($cfg_gids);
		}
		return parent::get_config($cfg_gids);
	}

	/**
	 * Get form fields
	 * @param int $lang_id
	 * @return array
	 */
	public function get_form_fields($lang_id = null) {
		$country_code = $this->CI->pg_language->ds->get_reference(self::MODULE_GID, 'country_code', $lang_id);
		asort($country_code['option']);
		$lang = $this->CI->pg_language->ds->get_reference(self::MODULE_GID, 'lang', $lang_id);
		asort($lang['option']);
		return array(
			array('site_type' => $this->CI->pg_language->ds->get_reference(self::MODULE_GID, 'site_type', $lang_id),),
			array('user_type' => $this->CI->pg_language->ds->get_reference(self::MODULE_GID, 'user_type', $lang_id),),
			array('orientation' => $this->CI->pg_language->ds->get_reference(self::MODULE_GID, 'orientation', $lang_id),),
			array('lang' => $lang,),
			array(
				'land' => $this->CI->pg_language->ds->get_reference(self::MODULE_GID, 'land', $lang_id),
				'country_code' => $country_code,
			),
		);
	}

	/**
	 * Get form limits
	 * @return array
	 */
	public function get_form_limits() {
		$config = $this->get_config($this->_cfg_settings_limits);
		return $config[$this->_cfg_settings_limits];
	}

	/**
	 * Get user profile data limits
	 * @return array
	 */
	public function get_profile_data_limits() {
		$config = $this->get_config($this->_cfg_profile_data_limits);
		return $config[$this->_cfg_profile_data_limits];
	}

	/**
	 * Get temp processing limits
	 * @return array
	 */
	public function get_temp_processing_limits() {
		$config = $this->get_config($this->_cfg_temp_processing_limits);
		return $config[$this->_cfg_temp_processing_limits];
	}

	/**
	 * Validate settings.
	 * @param array $settings
	 * @return array
	 */
	public function validate_settings(array $settings) {
		$errors = parent::validate_settings($settings);
	    $form_limits = $this->get_form_limits();
		$alike_fields = array('site_type', 'user_type', 'orientation', 'lang', 'land', 'country_code',);
		foreach ($alike_fields as $field) {
			if (!isset($settings[$field])) {
				continue;
			} elseif (empty($settings[$field])) {
				$errors[$field] = l("admin_error_{$field}_empty", self::MODULE_GID);
			} elseif (!empty($form_limits[$field]) && count($settings[$field]) > $form_limits[$field]) {
				$errors[$field] = l("admin_error_{$field}_too_many", self::MODULE_GID);
			}
		}
		if (!empty($settings['min_age']) || !empty($settings['max_age'])) {
			$age_min = $this->CI->pg_module->get_module_config('users', 'age_min');
			$age_max = $this->CI->pg_module->get_module_config('users', 'age_max');
			if ((int) $settings['min_age'] > (int) $settings['max_age']) {
				$errors['max_age'] = l('admin_error_min_age_bigger_than_max_age', self::MODULE_GID);
			}
		}
		if (isset($settings['min_age'])) {
			$settings['min_age'] = (int) $settings['min_age'];
			if (empty($settings['min_age'])) {
				$errors['min_age'] = l('admin_error_min_age_empty', self::MODULE_GID);
			} elseif ($settings['min_age'] < $age_min) {
				$errors['min_age'] = l('admin_error_min_age_too_small', self::MODULE_GID);
			} elseif ($settings['min_age'] > $age_max) {
				$errors['min_age'] = l('admin_error_min_age_too_big', self::MODULE_GID);
			}
		}
		if (isset($settings['max_age'])) {
			$settings['max_age'] = (int) $settings['max_age'];
			if (empty($settings['max_age'])) {
				$errors['max_age'] = l('admin_error_max_age_empty', self::MODULE_GID);
			} elseif ($settings['max_age'] > $age_max) {
				$errors['max_age'] = l('admin_error_max_age_too_big', self::MODULE_GID);
			} elseif ($settings['max_age'] < $age_min) {
				$errors['max_age'] = l('admin_error_max_age_too_small', self::MODULE_GID);
			}
		}
		return $errors;
	}

    /**
     * Save user as ready joining to network
     * 
     * @param integer $user_id
     * @return void
     */
    public function saveReady($user_id) {
        $this->CI->load->model('Users_model');
        $save_data = array('net_status'=> self::STATUS_READY);
        $this->Users_model->save_user($user_id, $save_data);
    }
    
    /**
     * Save user as not agree joining to network
     * 
     * @param integer $user_id
     * @return void
     */
    public function saveNotAgree($user_id) {
        $this->CI->load->model('Users_model');
        $save_data = array('net_status'=> self::STATUS_NOT_AGREE);
        $this->Users_model->save_user($user_id, $save_data);
    }
}
