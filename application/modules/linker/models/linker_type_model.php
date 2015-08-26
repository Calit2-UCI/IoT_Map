<?php

/**
 * Linker type Model
 *
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @author Katya Kashkova <katya@pilotgroup.net>
 * @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
 * */
define('LINKER_TABLE', DB_PREFIX . 'linker');
define('LINKER_TYPES_TABLE', DB_PREFIX . 'linker_types');
define('LINKER_SEPARATED_PREFIX', DB_PREFIX . 'linker_sep_');

class Linker_type_model extends Model {

	/**
	 * link to CodeIgniter object
	 * @var object
	 */
	private $CI;

	/**
	 * link to DataBase object
	 * @var object
	 */
	private $DB;

	/**
	 * Constructor
	 *
	 * @return Linker_type Object
	 */
	function __construct() {
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;
		$this->DB->memcache_tables(array(LINKER_TYPES_TABLE));
	}

	/**
	 * Get link type by ID
	 * @param integer $type_id
	 * @return mixed
	 */
	public function get_type_by_id($type_id) {
		$type_id = intval($type_id);
		if (!$type_id)
			return false;

		$this->DB->select('id, gid, separated, lifetime, unique_type');
		$this->DB->from(LINKER_TYPES_TABLE);
		$this->DB->where('id', $type_id);

		//_compile_select;
		$result = $this->DB->get()->result();
		if (!empty($result)) {
			$rt = get_object_vars($result[0]);
			$rt["table_name"] = ($rt["separated"]) ? LINKER_SEPARATED_PREFIX . $rt["gid"] : LINKER_TABLE;
			return $rt;
		} else
			return false;
	}

	/**
	 * Get link type by GID
	 * @param string $type_gid
	 * @return mixed
	 */
	public function get_type_by_gid($type_gid) {
		$type_gid = preg_replace("/[^a-z_]/", "", strtolower($type_gid));
		if (!$type_gid)
			return false;

		$this->DB->select('id, gid, separated, lifetime, unique_type');
		$this->DB->from(LINKER_TYPES_TABLE);
		$this->DB->where('gid', $type_gid);

		//_compile_select;
		$result = $this->DB->get()->result();
		if (!empty($result)) {
			$rt = get_object_vars($result[0]);
			$rt["table_name"] = ($rt["separated"]) ? LINKER_SEPARATED_PREFIX . $rt["gid"] : LINKER_TABLE;
			return $rt;
		} else
			return false;
	}

	/**
	 * Get link type ID by GID
	 * @param string $type_gid
	 * @return mixed
	 */
	public function get_type_id_by_gid($type_gid) {
		$type = $this->get_type_by_gid($type_gid);
		if ($type !== false && !empty($type["id"])) {
			return $type["id"];
		}
		return false;
	}

	/**
	 * Create link type
	 * @param string $gid linker type GID [a-z_]
	 * @param integer $separated It shows, to create separate table for the link 
	 * @param integer $lifetime time in sec., 0-unlimited
	 * @param string $unique_type 'no'/'update'/'noupdate' link replace type
	 * @return integer link ID
	 */
	public function create_type($gid, $separated = 0, $lifetime = 0, $unique_type = 'no') {
		$gid = preg_replace("/[^a-z_]/", "", strtolower($gid));

		$id = $this->get_type_id_by_gid($gid);
		if (!$id) {
			$data = array(
				'gid' => $gid,
				'separated' => intval($separated),
			);

			$this->DB->insert(LINKER_TYPES_TABLE, $data);
			$id = $this->get_type_id_by_gid($gid);
		}
		if ($separated) {
			$this->create_table($gid);
		}

		return $id;
	}

	/**
	 * Delete link type by ID or GID
	 * @param mixed $id integer ID / string GID
	 */
	public function delete_type($id) {
		if (!is_int($id)) {
			$id = $this->get_type_id_by_gid($id);
		}
		$this->DB->where('id', $id);
		$this->DB->delete(LINKER_TYPES_TABLE);
		return;
	}

	/**
	 * Create a separated table for Link type
	 * @param string $gid
	 */
	public function create_table($gid) {
		$table_name = LINKER_SEPARATED_PREFIX . $gid;
		if (!$this->DB->table_exists($table_name)) {
			$this->CI->load->dbforge();

			$fields = array(
				'id' => array(
					'type' => 'INT',
					'constraint' => 3,
					'null' => FALSE,
					'auto_increment' => TRUE
				),
				'id_link_1' => array(
					'type' => 'INT',
					'constraint' => 3,
					'null' => FALSE,
				),
				'id_link_2' => array(
					'type' => 'INT',
					'constraint' => 3,
					'null' => FALSE,
				),
				'date_add' => array(
					'type' => 'DATETIME',
				),
				'sorter' => array(
					'type' => 'INT',
					'constraint' => 3,
					'null' => FALSE,
				),
			);
			$this->CI->dbforge->add_field($fields);
			$this->CI->dbforge->add_key('id', TRUE);
			$this->CI->dbforge->add_key(array('id_link_1', 'id_link_2'));
			$this->CI->dbforge->add_key('sorter');
			$this->CI->dbforge->create_table($table_name);
		}
		return $table_name;
	}

}
