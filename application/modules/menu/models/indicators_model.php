<?php

namespace Pg\Modules\Menu\Models;

/**
 * Indicators model
 *
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * */
if (!defined('INDICATORS_TABLE')) {
    define('INDICATORS_TABLE', DB_PREFIX . 'menu_indicators');
}
if (!defined('INDICATORS_TYPES_TABLE')) {
    define('INDICATORS_TYPES_TABLE', DB_PREFIX . 'menu_indicators_types');
}

class Indicators_model extends \Model
{

    protected $CI;
    protected $DB;
    public $fields = array(
        'id',
        'gid',
        'user_id',
        'value',
        'created'
    );
    public $types_fields = array(
        'id',
        'gid',
        'auth_type',
            // name_1
            // name_2
            // ...
    );
    private $auth_types = array('user', 'admin', 'mudule');

    public function __construct()
    {
        parent::__construct();
        $this->CI = &get_instance();
        $this->DB = &$this->CI->db;
        if (INSTALL_DONE) {
            $langs = (array) $this->pg_language->languages;
            foreach ($langs as $lang) {
                $this->types_fields[] = 'name_' . $lang['id'];
            }

            $this->DB->memcache_tables(array(INDICATORS_TYPES_TABLE));
        }
    }

    /**
     * Adds indicator.
     * Depends on "use_indicators" menu setting.
     *
     * @param string $gid
     * @param string $uid
     * @param int $user_id
     * @param string $value
     * @return boolean
     */
    public function add($gid, $uid = '', $user_id = 0, $value = '')
    {
        if (!$this->CI->pg_module->get_module_config('menu', 'use_indicators')) {
            return false;
        }

        // Delete old indicator
        if (!empty($uid)) {
            $this->delete($gid, $uid, true);
        }

        $data = array(
            'gid' => $gid,
            'value' => $value,
            'uid' => $uid,
            'user_id' => $user_id,
        );

        $this->DB->insert(INDICATORS_TABLE, $data);

        return $this;
    }

    /**
     * Gets menu indicators
     *
     * @param string $auth_type (admin | module | user)
     * @param int $user_id
     * @return boolean
     */
    public function get($auth_type = null, $user_id = null)
    {
        if ('user' === $auth_type && empty($user_id)) {
            log_message('ERROR', '(indicators) Empty user id');
            return false;
        }
        $this->DB->select('i.gid')->from(INDICATORS_TABLE . ' AS i');
        if (!empty($auth_type)) {
            $this->DB->join(INDICATORS_TYPES_TABLE . ' AS t', 't.gid = i.gid')
                    ->where('t.auth_type', $auth_type);
            if ('user' === $auth_type) {
                $this->DB->where('i.user_id', $user_id);
            }
        } // else get all indicators
        $result = $this->DB->get()->result_array();
        if (empty($result)) {
            return array();
        }
        $indicators = array();
        foreach ($result as $indicator) {
            if (!empty($indicators[$indicator['gid']])) {
                $indicators[$indicator['gid']] ++;
            } else {
                $indicators[$indicator['gid']] = 1;
            }
        }
        return $indicators;
    }

    /**
     * Removes indicators for the current user
     *
     * @param string $gid
     * @param string $uid
     * @param bool $all If true, session data will be ignored
     * @return boolean
     */
    public function delete($gid, $uid = null, $all = false)
    {
        if (!$this->CI->pg_module->get_module_config('menu', 'use_indicators')) {
            return $this;
        } elseif (empty($gid)) {
            log_message('ERROR', '(indicators) Empty indicator gid');
            return $this;
        }

        if (!$all) {
            $auth_type = $this->CI->session->userdata('auth_type');
            if ('user' === $auth_type) {
                $this->DB->where('user_id', $this->CI->session->userdata('user_id'))
                        ->where('gid', $gid);
                if (!empty($uid)) {
                    $this->DB->where_in('uid', $uid);
                }
                $this->DB->delete(INDICATORS_TABLE);
            } else {
                // CI's active record can't handle such queries
                $query = 'DELETE i FROM ' . INDICATORS_TABLE . ' i
					JOIN ' . INDICATORS_TYPES_TABLE . " t ON t.gid = i.gid
					WHERE t.auth_type = '$auth_type' AND i.gid = '$gid'";
                if (!empty($uid)) {
                    $uid = (array) $uid;
                    foreach ($uid as $key => $value) {
                        $uid[$key] = $this->DB->escape($value);
                    }
                    $query .= ' AND uid IN (' . implode(',', $uid) . ')';
                }
                $this->db->query($query);
            }
        } else {
            if (!empty($uid)) {
                $this->DB->where_in('uid', $uid);
            }
            $this->DB->where('gid', $gid)
                    ->delete(INDICATORS_TABLE);
        }
        return $this;
    }

    /**
     * By cron
     */
    public function delete_old()
    {
        $lifetime = (int) $this->CI->pg_module->get_module_config('menu', 'indicator_lifetime');
        if ($lifetime < 1) {
            $lifetime = 24;
        }
        $query = 'DELETE i FROM ' . INDICATORS_TABLE . ' i
					JOIN ' . INDICATORS_TYPES_TABLE . " t ON t.gid = i.gid
					WHERE t.delete_by_cron = '1'
					AND i.user_id = '0'
					AND i.created < DATE_SUB(NOW(), INTERVAL $lifetime hour)";
        $this->db->query($query);
        return $this;
    }

    /**
     * Saves indicator type
     *
     * @param string $gid
     * @param bool $delete_by_cron
     * @param string $auth_type
     * @param array $data['name']
     * @return boolean
     */
    public function save_type($gid = null, $data = array())
    {
        if (empty($data)) {
            log_message('ERROR', '(indicators) Empty indicator data');
            return $this;
        } elseif (!empty($data['auth_type']) && !in_array($data['auth_type'], $this->auth_types)) {
            log_message('ERROR', '(indicators) Wrong indicator auth type');
            return $this;
        }
        $data['delete_by_cron'] = (int) $data['delete_by_cron'];
        if (empty($gid)) {
            $this->DB->ignore()->insert(INDICATORS_TYPES_TABLE, $data);
        } else {
            $this->DB->where('gid', $gid);
            $this->DB->update(INDICATORS_TYPES_TABLE, $data);
        }
        return $this;
    }

    /**
     * Returns indicators' types
     * Depends on "use_indicators" menu setting.
     *
     * @param $params
     * @return array
     */
    public function get_types($params = null)
    {
        if (!$this->CI->pg_module->get_module_config('menu', 'use_indicators')) {
            return false;
        }
        $this->DB->select(implode(', ', $this->types_fields))
                ->from(INDICATORS_TYPES_TABLE);

        if (!empty($params)) {
            if (isset($params['where']) && is_array($params['where']) && count($params['where'])) {
                foreach ($params['where'] as $field => $value) {
                    $this->DB->where($field, $value);
                }
            }

            if (isset($params['where_in']) && is_array($params['where_in']) && count($params['where_in'])) {
                foreach ($params['where_in'] as $field => $value) {
                    $this->DB->where_in($field, $value);
                }
            }

            if (isset($params['where_sql']) && is_array($params['where_sql']) && count($params['where_sql'])) {
                foreach ($params['where_sql'] as $value) {
                    $this->DB->where($value, null, false);
                }
            }
        }

        $result = $this->DB->get()->result_array();
        $types = array();
        $cur_lang = $this->CI->pg_language->current_lang_id;
        foreach ($result as $set) {
            $types[$set['gid']] = $set;
            $types[$set['gid']]['name'] = $types[$set['gid']]['name_' . $cur_lang];
        }
        return $types;
    }

    public function delete_type($gid)
    {
        if (empty($gid)) {
            log_message('ERROR', '(indicators) Empty type gid');
            return false;
        }
        $this->DB->where('gid', $gid)
                ->delete(INDICATORS_TYPES_TABLE);
        $this->delete($gid, null, true);
        return true;
    }

    /**
     * Adds langs fields
     *
     * @param bool $lang_id
     * @return boolean
     */
    public function lang_dedicate_module_callback_add($lang_id = false)
    {
        if (!$lang_id) {
            return false;
        }
        $fields['name_' . $lang_id] = array(
            'type' => 'VARCHAR',
            'constraint' => '255',
            'null' => false,
        );
        // Add column
        $this->CI->load->dbforge();
        $this->CI->dbforge->add_column(INDICATORS_TYPES_TABLE, $fields);
        return true;
    }

    /**
     * Deletes langs fields
     *
     * @param int $lang_id
     * @return boolean
     */
    public function lang_dedicate_module_callback_delete($lang_id = false)
    {
        if (!$lang_id) {
            return false;
        }
        $field_name = 'name_' . $lang_id;

        // Delete column
        $table = $this->CI->db->get(INDICATORS_TYPES_TABLE);
        if (in_array('name_' . $lang_id, $table->list_fields())) {
            $this->CI->load->dbforge();
            $this->CI->dbforge->drop_column(INDICATORS_TYPES_TABLE, $field_name);
        }
    }

    /**
     * Updates langs
     *
     * @param array $data
     * @param array $langs_file
     * @param array $langs_ids
     * @return boolean
     */
    public function update_langs($data, $langs_file, $langs_ids = null)
    {
        if (empty($data)) {
            log_message('ERROR', '(indicators) Empty data for update');
            return false;
        } elseif (empty($langs_file)) {
            log_message('ERROR', '(indicators) Empty langs for update');
            return false;
        }

        // Get all langs if $langs_ids is empty
        if (empty($langs_ids)) {
            $langs = (array) $this->pg_language->languages;
            foreach ($langs as $lang) {
                $langs_ids[] = $lang['id'];
            }
        }

        $default_lang = $this->CI->pg_language->get_default_lang_id();
        foreach ($data as $indicator) {
            $langs_data = array();
            foreach ($langs_ids as $lang_id) {
                if (!empty($langs_file[$indicator['gid']][$lang_id])) {
                    $name = $langs_file[$indicator['gid']][$lang_id];
                } elseif (!empty($langs_file[$indicator['gid']][$default_lang])) {
                    // Use default lang
                    $name = $langs_file[$indicator['gid']][$default_lang];
                } else {
                    continue;
                }
                $langs_data['name_' . $lang_id] = $name;
            }
            if (!empty($langs_data)) {
                $this->save_type($indicator['gid'], $langs_data);
            }
        }
    }

    /**
     * Exports langs
     *
     * @param array $data
     * @param array $langs_ids
     * @return array
     */
    public function export_langs($data, $langs_ids = null)
    {
        if (empty($data)) {
            log_message('ERROR', '(indicators) Empty data for export');
        }
        $gids = array();
        foreach ($data as $indicator) {
            $gids[] = $indicator['gid'];
        }

        $indicators = $this->get_types(array('where_in' => array('gid' => $gids)));

        // Get all langs if $langs_ids is empty
        if (empty($langs_ids)) {
            $langs = (array) $this->pg_language->languages;
            foreach ($langs as $lang) {
                $langs_ids[] = $lang['id'];
            }
        }

        $langs_data = array();
        foreach ($indicators as $indicator) {
            foreach ($langs_ids as $lang_id) {
                if (!empty($indicator['name_' . $lang_id])) {
                    $langs_data[$indicator['gid']][$lang_id] = $indicator['name_' . $lang_id];
                }
            }
        }

        return $langs_data;
    }

}
