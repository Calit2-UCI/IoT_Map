<?php

abstract class Chat_abstract extends Model {

	protected $CI;
	protected $DB;

	/**
	 * Id
	 * @int type 
	 */
	protected $_id;

	/**
	 * Gid
	 * @var string 
	 */
	protected $_gid;

	/**
	 * Name
	 * @var string 
	 */
	protected $_name;

	/**
	 * Installed flag
	 * @var bool
	 */
	protected $_installed;

	/**
	 * Active flag
	 * @var bool
	 */
	protected $_active;

	/**
	 * Vendor url
	 * string type 
	 */
	protected $_vendor_url;

	/**
	 * Chat directory
	 * @var string
	 */
	protected $_dir = '';

	/**
	 * Chat activities
	 * @var array
	 */
	protected $_activities;

	/**
	 * Settings
	 * array type 
	 */
	protected $_settings;

	public abstract function user_page();

	public abstract function include_block();

	public abstract function admin_page();

	public abstract function install_page();

	public abstract function validate_settings();

	public function __construct() {
		parent::Model();
		$this->CI = &get_instance();
		$this->DB = &$this->CI->db;
	}

	/**
	 * Check whether chat has required files. Should be redefined in subclasses,
	 * otherwise allways returns true
	 * @return boolean
	 */
	public function has_files() {
		return is_dir(SITE_PHYSICAL_PATH . $this->Chats_model->path . $this->get_gid());
	}

	public function get_dir() {
		return (string) $this->_dir;
	}

	/**
	 * Get chat id
	 * @return int
	 */
	public function get_id() {
		return (int) $this->_id;
	}

	/**
	 * Set chat id
	 * @param int $id
	 * @return \Chat_abstract
	 */
	public function set_id($id) {
		$this->_id = (int) $id;
		return $this;
	}

	/**
	 * Get chat gid
	 * @return string
	 */
	public function get_gid() {
		return (string) $this->_gid;
	}

	/**
	 * Set chat gid
	 * @param string $gid
	 * @return \Chat_abstract
	 */
	public function set_gid($gid) {
		$this->_gid = (string) $gid;
		return $this;
	}

	/**
	 * Get chat name
	 * @return string
	 */
	public function get_name() {
		return (string) $this->_name;
	}

	/**
	 * Get chat installed flag
	 * @return bool
	 */
	public function get_installed() {
		return (bool) $this->_installed;
	}

	/**
	 * Set chat installed flag
	 * @param bool $installed
	 * @return \Chat_abstract
	 */
	public function set_installed($installed = true) {
		$this->_installed = (bool) $installed;
		return $this;
	}

	/**
	 * Get chat activity flag
	 * @return bool
	 */
	public function get_active() {
		return (bool) $this->_active;
	}

	/**
	 * Set chat activity flag
	 * @param type $active
	 * @return \Chat_abstract
	 */
	public function set_active($active = true) {
		$this->_active = (bool) $active;
		return $this;
	}

	/**
	 * Get activities
	 * @return array
	 */
	public function get_activities($for_db = false) {
		if ($for_db) {
			return implode(',', $this->_activities);
		} else {
			return $this->_activities;
		}
	}

	/**
	 * Set activities
	 * @param mixed $activities
	 * @return \Chat_abstract
	 */
	public function set_activities($activities) {
		if (is_string($activities)) {
			$this->set_activities(explode(',', $activities));
		} elseif (is_array($activities)) {
			foreach ($activities as $key => $activity) {
				if (!in_array($activity, $this->CI->Chats_model->activities)) {
					log_message('error', 'Wrong activity (' . gettype($activity) . ')');
					unset($activities[$key]);
				}
			}
			$this->_activities = $activities;
		} else {
			log_message('error', 'Wrong activities type (' . gettype($activities) . ')');
		}
		return $this;
	}

	/**
	 * Get chat settings. 
	 * @param mixed $param 
	 * <b>null</b> — all settings<br>
	 * <b>string</b> — value by key<br>
	 * <b>true</b> — all settings in the form of serialized array
	 * @return array
	 */
	public function get_settings($param = null) {
		if ($param === true) {
			return serialize($this->_settings);
		} elseif ($param) {
			if (isset($this->_settings['param'])) {
				return $this->_settings['param'];
			} else {
				return false;
			}
		} else {
			return $this->_settings;
		}
	}

	/**
	 * Set chat settings.
	 * @param mixed $settings array or serialized array (will be unserialized before saving).
	 * @return \Chat_abstract
	 */
	public function set_settings($settings) {
		if (is_array($settings)) {
			$this->_settings = array_replace_recursive($this->_settings, $settings);
		} elseif ('b:0;' !== $settings && false !== ($unserialized = @unserialize($settings))) {
			return $this->set_settings($unserialized);
		} else {
			log_message('error', 'Wrong settings type (' . gettype($settings) . ')');
		}
		return $this;
	}

	/**
	 * Set object properties from array.
	 * @param array $data
	 * @return \Chat_abstract
	 */
	public function set($data) {
		if (!is_array($data)) {
			log_message('error', 'Wrong parameter');
		}
		foreach ($data as $key => $val) {
			$setter = 'set_' . $key;
			if (method_exists($this, $setter)) {
				$this->$setter($val);
			}
		}
		return $this;
	}

	/**
	 * Get tamplate name
	 * @param string $page
	 * @return string
	 */
	public function get_tpl_name($page = '') {
		$tpl = $this->_gid;
		if ($page) {
			$tpl .= '_' . $page;
		}
		return $tpl;
	}

	/**
	 * Get chat propertyes in the form of array
	 * @param bool $for_db If true, will return only properties that stores in database
	 * @return array
	 */
	public final function as_array($for_db = false) {
		$data = array(
			'id' => $this->get_id(),
			'gid' => $this->get_gid(),
			'active' => $this->get_active(),
			'installed' => $this->get_installed(),
			'activities' => $this->get_activities($for_db),
			'settings' => $this->get_settings($for_db),
		);
		if (!$for_db) {
			$data += array(
				'name' => $this->get_name(),
				'has_files' => $this->has_files(),
				'dir' => $this->get_dir(),
			);
		} elseif (0 === $data['id']) {
			unset($data['id']);
		}
		return $data;
	}

	/**
	 * Save current state to database
	 * @return \Chat_abstract
	 */
	public function save() {
		$data = $this->as_array(true);
		if ($this->get_id()) {
			$this->DB->where('id', $data['id']);
			$this->DB->update(CHATS_TABLE, $data);
		}
		return $this;
	}

}
