<?php

namespace Pg\Modules\Memberships\Models;

/**
 * Memberships module
 *
 * @package 	PG_Dating
 * @copyright 	Copyright (c) 2000-2014 PG Dating Pro - php dating software
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

define('MEMBERSHIPS_TABLE', DB_PREFIX . 'memberships');

/**
 * Base model
 * 
 * @package 	PG_Dating
 * @subpackage 	Memberships
 * @category	models
 * @copyright 	Copyright (c) 2000-2014 PG Dating Pro - php dating software
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
class Memberships_model extends \Model
{

    /**
     * Module GUID
     * 
     * @var string
     */
    const MODULE_GID = 'memberships';

    /**
     * Payment type as write off from account
     * 
     * @var string
     */
    const PAYMENT_TYPE_ACCOUNT = 'account';

    /**
     * Payment type as write off from account and direct payment
     * 
     * @var string
     */
    const PAYMENT_TYPE_ACCOUNT_AND_DIRECT = 'account_and_direct';

    /**
     * Payment type as direct payment
     * 
     * @var string
     */
    const PAYMENT_TYPE_DIRECT = 'direct';

    /**
     * Period type in years
     * 
     * @var string
     */
    const PERIOD_TYPE_YEARS = 'years';

    /**
     * Period type in months
     * 
     * @var string
     */
    const PERIOD_TYPE_MONTHS = 'months';

    /**
     * Period type in days
     * 
     * @var string
     */
    const PERIOD_TYPE_DAYS = 'days';

    /**
     * Period type in hours
     * 
     * @var string
     */
    const PERIOD_TYPE_HOURS = 'hours';

    /**
     * Prefix of membership GUID
     * 
     * @var string
     */
    const GUID_PREFIX = 'plan';

    /**
     * Link to CodeIgniter object
     * 
     * @var object
     */
    private $CI;

    /**
     * Link to database object
     * 
     * @var object
     */
    private $DB;

    /**
     * Membership object properties
     * 
     * @var array
     */
    private $_fields = array(
        'id',
        'gid',
        'services',
        'user_type_disabled',
        'price',
        'period_count',
        'period_type',
        'prices',
        'pay_type',
        'is_active',
        'date_created',
    );

    /**
     * Return allowed period types as array
     * 
     * @return array
     */
    public function getAllowedPeriodTypes()
    {
        return array(
            self::PERIOD_TYPE_HOURS,
            self::PERIOD_TYPE_DAYS,
            self::PERIOD_TYPE_MONTHS,
            self::PERIOD_TYPE_YEARS,
        );
    }

    /**
     * Return allowed payments types as array
     * 
     * @return array
     */
    public function getAllowedPaymentTypes()
    {
        return array(
            self::PAYMENT_TYPE_ACCOUNT,
            self::PAYMENT_TYPE_DIRECT,
            self::PAYMENT_TYPE_ACCOUNT_AND_DIRECT,
        );
    }

    /**
     * Settings for formatting membership object
     * 
     * @var array
     */
    private $format_settings = array(
        'get_services' => false,
    );

    /**
     * Class constructor
     * 
     * @return Memberships_model
     */
    public function __construct()
    {
        parent::__construct();
        $this->CI = &get_instance();
        $this->DB = &$this->CI->db;
        $this->DB->memcache_tables(array(MEMBERSHIPS_TABLE));
    }

    /**
     * Return membership object by idnetifier
     * 
     * @param string $field_name field name
     * @param mixed $field_value field value
     * @param array $langs_ids languages' idnetifiers 
     * @return array/false
     */
    private function _getMembershipObject($field_name, $field_value, $langs_ids = null)
    {
        if (empty($langs_ids)) {
            $langs_ids = array($this->CI->pg_language->current_lang_id);
        }

        $fields = $this->_fields;

        foreach ($langs_ids as $lang_id) {
            $fields[] = 'name_' . $lang_id;
            $fields[] = 'description_' . $lang_id;
        }

        $results = $this->DB->select(implode(', ', $fields))
            ->from(MEMBERSHIPS_TABLE)
            ->where($field_name, $field_value)
            ->get()
            ->result_array();

        if (!empty($results) && is_array($results)) {
            return $results[0];
        }

        return false;
    }

    /**
     * Return membership object by idnetifier
     * 
     * @param integer $membership_id membership identifier
     * @param array $langs_ids languages' idnetifiers 
     * @return array/false
     */
    public function getMembershipById($membership_id, $langs_ids = null)
    {
        return $this->_getMembershipObject('id', $membership_id, $langs_ids);
    }

    /**
     * Return membership object by guid
     * 
     * @param string $membership_gid membership guid
     * @param array $langs_ids languages' identifiers
     * @return array/false
     */
    public function getMembershipByGid($membership_gid, $langs_ids = null)
    {
        return $this->_getMembershipObject('gid', $membership_gid, $langs_ids);
    }

    /**
     * Return sql criteria for memberships searching as array
     * 
     * @param array $filters filters data
     * @return array
     */
    public function _getMembershipsCriteria($filters)
    {
        $filters = array('data' => $filters, 'table' => MEMBERSHIPS_TABLE, 'type' => '');

        $params = array();

        $params['table'] = !empty($filters['table']) ? $filters['table'] : MEMBERSHIPS_TABLE;

        $fields = array_flip($this->_fields);
        foreach ($filters['data'] as $filter_name => $filter_data) {
            if (!is_array($filter_data)) {
                $filter_data = trim($filter_data);
            }
            switch ($filter_name) {
                case 'ids': {
                        if (empty($filter_data)) {
                            break;
                        }
                        $params = array_merge_recursive($params, array('where_in' => array('id' => $filter_data)));
                        break;
                    }
                case 'user_type': {
                        if (empty($filter_data)) {
                            break;
                        }
                        if (!is_array($filter_data)) {
                            $filter_data = array($filter_data);
                        }
                        $temp = $this->_userTypesToDec($filter_data);
                        $params = array_merge_recursive($params, array("where_sql" => array("user_type_disabled_code&" . $temp . "!=" . $this->CI->db->escape($temp))));
                        break;
                    }
                case 'not_ids': {
                        if (empty($filter_data)) {
                            break;
                        }
                        if (is_array($filter_data)) {
                            $params = array_merge_recursive($params, array('where_not_in' => array('id' => $filter_data)));
                        } else {
                            $params = array_merge_recursive($params, array('where' => array('id !=' => $filter_data)));
                        }
                        break;
                    }
                default: {
                        if (isset($fields[$filter_name])) {
                            if (is_array($filter_data)) {
                                $params = array_merge_recursive($params, array('where_in' => array($filter_name => $filter_data)));
                            } else {
                                $params = array_merge_recursive($params, array('where' => array($filter_name => $filter_data)));
                            }
                        }
                        break;
                    }
            }
        }

        return $params;
    }

    /**
     * Return memberships object from data source as array
     * 
     * @param integer $page page of results
     * @param string $limits results per page
     * @param array $order_by sorting data
     * @param array $params sql criteria
     * @param array $lang_ids languages' identifiers
     * @return array
     */
    protected function _getMembershipsList($page = null, $limits = null, $order_by = null, $params = array(), $lang_ids = null)
    {
        if (empty($lang_ids)) {
            $lang_ids = array($this->CI->pg_language->current_lang_id);
        }

        $table = MEMBERSHIPS_TABLE;

        $fields = $this->_fields;

        foreach ($lang_ids as $lang_id) {
            $fields[] = 'name_' . $lang_id;
            $fields[] = 'description_' . $lang_id;
        }

        $fields = implode(', ', $fields);

        if (isset($params['table']) && $params['table'] != MEMBERSHIPS_TABLE) {
            $table = $params['table'];
            $fields = $table . '.' . implode(', ' . $table . '.', $this->_fields);
        }

        $this->DB->select($fields);
        $this->DB->from($table);

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

        if (isset($params['where_not_in']) && is_array($params['where_not_in']) && count($params['where_not_in'])) {
            foreach ($params['where_not_in'] as $field => $value) {
                $this->DB->where_not_in($field, $value);
            }
        }

        if (isset($params['where_sql'])) {
            if (!is_array($params['where_sql'])) {
                $params['where_sql'] = array($params['where_sql']);
            }
            foreach ($params['where_sql'] as $value) {
                $this->DB->where($value, null, false);
            }
        }

        if (is_array($order_by) && count($order_by) > 0) {
            foreach ($order_by as $field => $dir) {
                if (in_array($field, $this->_fields)) {
                    $this->DB->order_by($field . ' ' . $dir);
                }
            }
        } elseif ($order_by) {
            $this->DB->order_by($order_by);
        }

        if (!is_null($page)) {
            $page = intval($page) ? intval($page) : 1;
            $this->DB->limit($limits, $limits * ($page - 1));
        }

        $results = $this->DB->get()->result_array();
        if (!empty($results) && is_array($results)) {
            return $results;
        }
        return array();
    }

    /**
     * Return number of meberships in data source
     * 
     * @param array $params sql criteria
     * @return integer
     */
    protected function _getMembershipsCount($params = null)
    {
        $table = isset($params['table']) ? $params['table'] : MEMBERSHIPS_TABLE;

        $this->DB->select('COUNT(*) AS cnt');
        $this->DB->from($table);

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

        if (isset($params['where_not_in']) && is_array($params['where_not_in']) && count($params['where_not_in'])) {
            foreach ($params['where_not_in'] as $field => $value) {
                $this->DB->where_not_in($field, $value);
            }
        }

        if (isset($params['where_sql']) && is_array($params['where_sql']) && count($params['where_sql'])) {
            foreach ($params['where_sql'] as $value) {
                $this->DB->where($value, null, false);
            }
        }

        $results = $this->DB->get()->result_array();
        if (!empty($results) && is_array($results)) {
            return intval($results[0]['cnt']);
        }
        return 0;
    }

    /**
     * Return filtered memberships objects from data source as array
     * 
     * @param array $filters filters data
     * @param integer $page page of results
     * @param integer $items_on_page results per page
     * @param string $order_by sorting data
     * @param array $langs_ids languages' identifier
     * @return array
     */
    public function getMembershipsList($filters = array(), $page = null, $items_on_page = null, $order_by = null, $langs_ids = null)
    {
        $params = $this->_getMembershipsCriteria($filters);
        return $this->_getMembershipsList($page, $items_on_page, $order_by, $params, $langs_ids);
    }

    /**
     * Return number of filtered memberships objects in data source
     * 
     * @param array $filters filters data
     * @return array
     */
    public function getMembershipsCount($filters = array())
    {
        $params = $this->_getMembershipsCriteria($filters);
        return $this->_getMembershipsCount($params);
    }

    /**
     * Change settings for listings formatting
     * 
     * @param string $name parameter name
     * @param mixed $value parameter value
     * @return void
     */
    public function setFormatSettings($name, $value = false)
    {
        if (!is_array($name)) {
            $name = array($name => $value);
        }
        foreach ($name as $key => $item) {
            $this->format_settings[$key] = $item;
        }
    }

    /**
     * Format data of membership object
     * 
     * @param array $membership_data membership data
     * @param array $lang_ids languages' identifiers
     * @return array
     */
    public function formatMembership($membership_data, $lang_ids = null)
    {
        return current($this->formatMemberships(array($membership_data), $lang_ids));
    }

    /**
     * Format data of memberships' objects
     * 
     * @param array $memberships_data memberships' data
     * @param array $langs_ids languages' identifiers
     * @return array
     */
    public function formatMemberships($memberships_data, $langs_ids = null)
    {
        $memberships_ids = array();
        
        if (empty($langs_ids)) {
            $langs_ids = array($this->CI->pg_language->current_lang_id);
        }

        $lang_id = current($langs_ids);

        foreach ($memberships_data as &$membership_data) {
            $memberships_ids[] = $membership_data['id'];
            $membership_data['name'] = $membership_data['name_' . $lang_id];
            $membership_data['description'] = $membership_data['description_' . $lang_id];
            $membership_data['pay_type_output'] = l('payment_type_' . $membership_data['pay_type'], self::MODULE_GID);
            $membership_data['pay_type_code'] = $membership_data['pay_type'];
            $membership_data['period_type_output'] = l('period_type_' . $membership_data['period_type'], self::MODULE_GID);
            $membership_data['period_type_code'] = $membership_data['period_type'];
            
            if (!empty($membership_data['user_type_disabled'])) {
                $membership_data['user_type_disabled_array'] = (array) unserialize($membership_data['user_type_disabled']);
            } else {
                $membership_data['user_type_disabled_array'] = array();
            }
            if (!empty($membership_data['prices'])) {
                $membership_data['prices_array'] = (array) unserialize($membership_data['prices']);
            } else {
                $membership_data['prices_array'] = array();
            }
            if (!empty($membership_data['services'])) {
                $membership_data['services_array'] = (array) unserialize($membership_data['services']);
            } else {
                $membership_data['services_array'] = array();
            }
        }

        if ($this->format_settings['get_services'] && !empty($memberships_ids)) {
            $where = array('where_in'=>array('id_membership'=>$memberships_ids));
            $this->CI->load->model('Services_model');
            $services = $this->CI->Services_model->get_service_list($where);
            $services_array = array();
            foreach ($services as $service) {
                if (!isset($services_array[$service['id_membership']])) {
                    $services_array[$service['id_membership']] = array();
                }
                $services_array[$service['id_membership']][$service['id']] = $service;
            }
            foreach ($memberships_data as &$membership_data) {
                $membership_data['services_list'] = $services_array[$membership_data['id']];
            }
        }

        $result = array();

        foreach ($memberships_data as $membership) {
            $result[$membership['id']] = $membership;
        }

        return $result;
    }

    /**
     * Format membership object by default
     * 
     * @return array
     */
    public function formatDefaultMembership()
    {
        $data = array();
        $data['name'] = 'Membership is deleted';
        return $data;
    }

    /**
     * Validate membership object for saving to data source
     * 
     * @param integer $membership_id membership identifier
     * @param array $membership_data membership data
     * @return array
     */
    public function validateMembership($membership_id, $membership_data)
    {
        $return = array('errors' => array(), 'data' => array(), 'services_data' => array());

        $default_lang_id = $this->CI->pg_language->current_lang_id;
        $languages = $this->CI->pg_language->languages;

        if ($membership_id) {
            $membership_object = $this->getMembershipById($membership_id);
        } else {
            $membership_object = array();
        }

        if (isset($membership_data['id'])) {
            $return['data']['id'] = intval($membership_data['id']);
            if (empty($return['data']['id'])) {
                unset($return['data']['id']);
            }
        }

        if (isset($membership_data['gid'])) {
            $return['data']['gid'] = strip_tags($membership_data['gid']);
            $return['data']['gid'] = preg_replace("/[^a-z0-9\-_]+/i", '', $return['data']['gid']);
            if (empty($return['data']['gid'])) {
                $return['errors'][] = l('error_gid_empty', self::MODULE_GID);
            } elseif (strlen($return['data']['gid']) > 50) {
                $return['errors'][] = l('error_gid_length', self::MODULE_GID);
            } else {
                if ($membership_data['gid'] !== $return['data']['gid']) {
                    $return['info']['gid'] = l('info_gid_filtered', self::MODULE_GID);
                }
                $param['where']['gid'] = $return['data']['gid'];
                if ($membership_id) {
                    $param['where']['id <>'] = $membership_id;
                }
                $gid_counts = $this->_getMembershipsCount($param);
                if ($gid_counts > 0) {
                    $return['errors'][] = l('error_gid_exists', self::MODULE_GID);
                }
            }
        }

        if (isset($membership_data['name_' . $default_lang_id])) {
            $return['data']['name_' . $default_lang_id] = trim(strip_tags($membership_data['name_' . $default_lang_id]));
            if (empty($return['data']['name_' . $default_lang_id])) {
                $return['errors'][] = l('error_name_invalid', self::MODULE_GID);
            } else {
                foreach ($languages as $lid => $lang_data) {
                    if ($lid == $default_lang_id) {
                        continue;
                    }
                    if (!isset($membership_data['name_' . $lid]) || empty($membership_data['name_' . $lid])) {
                        $return['data']['name_' . $lid] = $return['data']['name_' . $default_lang_id];
                    } else {
                        $return['data']['name_' . $lid] = trim(strip_tags($membership_data['name_' . $lid]));
                        if (empty($return['data']['name_' . $lid])) {
                            $return['errors'][] = l('error_name_invalid', self::MODULE_GID);
                            break;
                        }
                    }
                }
            }
        } elseif (!$membership_id) {
            $return['errors'][] = l('error_name_invalid', self::MODULE_GID);
        }

        if (isset($membership_data['user_type_disabled'])) {
            if (!is_array($membership_data['user_type_disabled'])) {
                $membership_data['user_type_disabled'] = array();
            }
            if (isset($membership_data['user_type_disabled'])) {
                $this->CI->load->model('Properties_model');
                $user_types = $this->CI->Properties_model->get_property('user_type');
                if (0 === count(array_diff(array_keys($user_types['option']), $membership_data['user_type_disabled']))) {
                    $return["errors"][] = l('error_usertypes_empty', self::MODULE_GID);
                }
                $return['data']['user_type_disabled'] = serialize($membership_data['user_type_disabled']);
                $return['data']['user_type_disabled_code'] = $this->_userTypesToDec($membership_data['user_type_disabled']);
                $return['services_data']['user_type_disabled'] = $membership_data['user_type_disabled'];
            }
        } elseif (!$membership_id) {
            //$return["errors"][] = l('error_usertype_empty', self::MODULE_GID);
        }

        if (isset($membership_data['description_' . $default_lang_id])) {
            $return['data']['description_' . $default_lang_id] = trim($membership_data['description_' . $default_lang_id]);
            if (!empty($return['data']['description_' . $default_lang_id])) {
                foreach ($languages as $lid => $lang_data) {
                    if ($lid == $default_lang_id) {
                        continue;
                    }
                    if (!isset($membership_data['description_' . $lid]) || empty($membership_data['description_' . $lid])) {
                        $return['data']['description_' . $lid] = $return['data']['description_' . $default_lang_id];
                    } else {
                        $return['data']['description_' . $lid] = trim($membership_data['description_' . $lid]);
                    }
                }
            }
        }

        if (isset($membership_data['prices'])) {
            if (!empty($membership_data['prices'])) {
                $value = (array) $membership_data['prices'];
            } else {
                $value = array();
            }

            $return['data']['prices'] = serialize($value);
        }

        if (isset($membership_data['services'])) {
            if (!empty($membership_data['services'])) {
                $value = (array) $membership_data['services'];
            } else {
                $value = array();
            }

            $return['data']['services'] = serialize($value);
        }

        if (isset($membership_data['pay_type'])) {
            if (!in_array($membership_data['pay_type'], $this->getAllowedPaymentTypes())) {
                $return['errors'][] = l('error_payment_type_invalid', self::MODULE_GID);
            } else {
                $return['data']['pay_type'] = $membership_data['pay_type'];
            }
        }

        if (isset($membership_data['price'])) {
            $price = floatval($membership_data['price']);
            if ($price < 0) {
                //$return['info']['price'] = l('error_negative_price', self::MODULE_GID);
                $return['data']['price'] = -$price;
            } else {
                $return['data']['price'] = $price;
            }
        }

        if (isset($membership_data['period_count'])) {
            $period_count = intval($membership_data['period_count']);
            if ($period_count <= 0) {
                //$return['info']['period_count'] = l('error_negative_period', self::MODULE_GID);
                $return['data']['period_count'] = -$period_count;
            } else {
                $return['data']['period_count'] = $period_count;
            }
        }

        if (isset($membership_data['period_type'])) {
            if (!in_array($membership_data['period_type'], $this->getAllowedPeriodTypes())) {
                $return['errors'][] = l('error_period_type_invalid', self::MODULE_GID);
            } else {
                $return['data']['period_type'] = $membership_data['period_type'];
            }
        }

        if (isset($membership_data['is_active'])) {
            $return['data']['is_active'] = $membership_data['is_active'] ? 1 : 0;
        }

        if (isset($membership_data['date_created'])) {
            $value = strtotime($membership_data['date_created']);
            if ($value) {
                $return['data']['date_created'] = date('Y-m-d H:i:s');
            } else {
                $return['data']['date_created'] = '0000-00-00 00:00:00';
            }
        }

        return $return;
    }

    /**
     * Save membership object to data source
     * 
     * @param integer $membership_id membership identifier
     * @param array $membership_data membership data
     * @param array $services_data services data
     * @return integer 
     */
    public function saveMembership($membership_id, $membership_data, $services_data = array())
    {
        if (empty($membership_id)) {
            $membership_data['date_created'] = date('Y-m-d H:i:s');
            $this->DB->insert(MEMBERSHIPS_TABLE, $membership_data);
            $membership_id = $this->DB->insert_id();

            $this->CI->load->model('Services_model');
            $this->CI->Services_model->create_membership_services($membership_id);

            $this->incGidIndex();
        } else {
            $this->DB->where('id', $membership_id);
            $this->DB->update(MEMBERSHIPS_TABLE, $membership_data);
        }

        if (!empty($services_data)) {
            $this->CI->load->model('Services_model');
            $this->CI->Services_model->updateMembershipServicesData($membership_id, $services_data);
        }

        return $membership_id;
    }

    /**
     * Remove mmebership object from data source by identifier
     * 
     * @param integer $membership_id membership identifier
     * @return void
     */
    public function deleteMembership($membership_id)
    {
        if (is_array($membership_id)) {
            foreach ($membership_id as $id) {
                $this->deleteMembership($id);
            }
        } else {
            $this->DB->where('id', $membership_id);
            $this->DB->delete(MEMBERSHIPS_TABLE);
            $this->CI->load->model('Services_model');
            $this->CI->Services_model->deleteMembershipServices($membership_id);
        }
    }

    /**
     * Remove mmeberships' objects from data source
     * 
     * @param integer $memberships_ids memberships' identifiers
     * @return void
     */
    public function deleteMemberships(array $memberships_ids)
    {
        foreach ($memberships_ids as $membership_id) {
            $this->deleteMembership($membership_id);
        }
    }

    /**
     * Change membership status
     * 
     * Available status values: 1 - activate, 0 - deactivate
     * 
     * @param integer $membership_id membership identifier
     * @param integer $status status value
     * @return void
     */
    public function updateMembershipStatus($membership_id, $status)
    {
        $save_data = array('is_active' => $status ? 1 : 0);
        $this->saveMembership($membership_id, $save_data);
    }

    /**
     * Install module fields depended on language 
     * 
     * @param integer $lang_id language identifier
     * @return void
     */
    public function langDedicateModuleCallbackAdd($lang_id = false)
    {
        if (!$lang_id) {
            return;
        }

        $this->CI->load->dbforge();

        $fields = array('name_' . $lang_id => array('type' => 'TEXT', 'null' => TRUE));
        $this->CI->dbforge->add_column(MEMBERSHIPS_TABLE, $fields);

        $default_lang_id = $this->CI->pg_language->get_default_lang_id();
        if ($lang_id != $default_lang_id) {
            $this->CI->db->set('name_' . $lang_id, 'name_' . $default_lang_id, false);
            $this->CI->db->update(MEMBERSHIPS_TABLE);
        }

        $fields = array('description_' . $lang_id => array('type' => 'TEXT', 'null' => TRUE));
        $this->CI->dbforge->add_column(MEMBERSHIPS_TABLE, $fields);

        $default_lang_id = $this->CI->pg_language->get_default_lang_id();
        if ($lang_id != $default_lang_id) {
            $this->CI->db->set('description_' . $lang_id, 'description_' . $default_lang_id, false);
            $this->CI->db->update(MEMBERSHIPS_TABLE);
        }
    }

    /**
     * Uninstall module fields depended on language
     * 
     * @param integer $lang_id language identifier
     * @return void
     */
    public function langDedicateModuleCallbackDelete($lang_id = false)
    {
        if (!$lang_id) {
            return;
        }

        $this->CI->load->dbforge();

        $fields_exists = $this->CI->db->list_fields(MEMBERSHIPS_TABLE);

        //$fields = array('name_' . $lang_id);
        //$fields = array('description_' . $lang_id);
        $fields = array(
            'name_' . $lang_id,
            'description_' . $lang_id
        );
        foreach ($fields as $field_name) {
            if (!in_array($field_name, $fields_exists)) {
                continue;
            }
            $this->CI->dbforge->drop_column(MEMBERSHIPS_TABLE, $field_name);
        }
    }

    /**
     * Convert data from array to numeric
     * 
     * @param array $data membership user types
     * @return integer
     */
    private function _userTypesToDec($data)
    {
        $lang_id = $this->CI->pg_language->current_lang_id;

        $this->CI->load->model('Properties_model');
        $user_types = $this->CI->Properties_model->get_property('user_type', $lang_id);
        if (empty($user_types['option'])) {
            return 0;
        }

        $binary_string = '';
        foreach ($user_types['option'] as $type => $name) {
            $binary_string = (in_array($type, $data) ? "1" : "0") . $binary_string;
        }

        return bindec($binary_string);
    }

    /**
     * Payment for membership by account
     * 
     * @param integer $membership_id membership identifier
     * @param integer $user_id user identifider
     * @param float $price membership price
     * @return integer
     */
    public function accountMembershipPayment($membership_id, $user_id, $price)
    {
        $this->CI->load->model('Users_payments_model');
        $membership = $this->formatMembership($this->getMembershipById($membership_id));

        $message = l('membership_payment', self::MODULE_GID) . ': ' . $membership["name"];

        $payment_result = $this->CI->Users_payments_model->write_off_user_account($user_id, $price, $message);
        if ($payment_result === true) {
            $this->applyMembership($membership, $user_id, $price);

            $this->CI->load->helper('seo');
            $link = rewrite_link('users', 'account', array('action' => 'memberships'));
            $this->CI->session->set_userdata(array('service_redirect' => $link));
        }

        return $payment_result;
    }

    /**
     * Payment for membership by system
     * 
     * @param string $system_gid system GUID
     * @param integer $user_id user identifier
     * @param integer $membership_id membership idnetifier
     * @param float $price membership price
     */
    public function systemMembershipPayment($system_gid, $user_id, $membership_id, $price)
    {
        $membership_data = $this->formatMembership($this->getMembershipById($membership_id));

        $this->CI->load->model("payments/models/Payment_currency_model");
        $currency_gid = $this->CI->Payment_currency_model->default_currency["gid"];

        $message = l('membership_payment', self::MODULE_GID) . ': ' . $membership_data["name"];

        $payment_data = array(
            'name' => $message,
            'id_membership' => $membership_id,
        );

        $this->CI->load->helper('payments');
        send_payment(self::MODULE_GID, $user_id, $price, $currency_gid, $system_gid, $payment_data, true);
    }

    /**
     * Update membership payment status
     * 
     * callback method for payment module
     * 
     * Expected status values: 1, 0, -1
     * 
     * @param array $payment payment data
     * @param integer $payment_status payment status
     * @return void
     */
    public function paymentMembershipStatus($payment, $payment_status)
    {
        if ($payment_status != 1) {
            return;
        }
        $user_id = $payment["id_user"];
        $membership_id = $payment["payment_data"]["id_membership"];
        $price = $payment["amount"];
        $membership = $this->formatMembership($this->getMembershipById($membership_id));
        $this->applyMembership($membership, $user_id, $price);
    }

    /**
     * Update membership status
     * 
     * @param array $payment payment data
     * @return void
     */
    private function applyMembership($membership, $user_id, $price)
    {
        if (in_array($membership['period_type'], $this->getAllowedPeriodTypes())) {
            $tstamp = strtotime('+' . $membership['period_count'] . ' ' . $membership['period_type']);
            $expired = date('Y-m-d H:i:s', $tstamp);
        } else {
            $expired = null;
        }

        $save_data = array(
            'id_user' => $user_id,
            'id_membership' => $membership['id'],
            'membership' => serialize($membership),
            'price' => $price,
            'services' => $membership['services'],
            'services_count' => count($membership['services_array']),
            'is_active' => 1,
            'date_activated' => date('Y-m-d H:i:s'),
            'date_expired' => $expired,
        );

        $this->CI->load->model('memberships/models/Memberships_users_model');
        $user_membership_id = $this->CI->Memberships_users_model->saveUserMembership(null, $save_data);

        $this->CI->load->model('Services_model');
        foreach ($membership['services_array'] as $service_id => $service_data) {
            if (!$service_data['is_active'])
                continue;

            $this->CI->Services_model->add_service_log($user_id, $service_id, array());

            $payment = array(
                'id_user' => $user_id,
                'amount' => $price,
                'payment_data' => array(
                    'id_service' => $service_id,
                    'id_membership' => $membership['id'],
                    'id_users_membership' => $user_membership_id,
                ),
            );
            $this->CI->Services_model->payment_service_status($payment, 1);
        }
    }

    // seo

    /**
     * Return settings for seo
     * 
     * @param string $method method name
     * @param integer $lang_id language identifier
     * @return array
     */
    public function getSeoSettings($method = '', $lang_id = '')
    {
        if (!empty($method)) {
            return $this->_getSeoSettings($method, $lang_id);
        } else {
            $actions = array('index', 'form', 'my');
            $return = array();
            foreach ($actions as $action) {
                $return[$action] = $this->_getSeoSettings($action, $lang_id);
            }
            return $return;
        }
    }

    /**
     * Return settings for seo (internal)
     * 
     * @param string $method method name
     * @param integer $lang_id language identifier
     * @return array
     */
    public function _getSeoSettings($method, $lang_id = '')
    {
        switch ($method) {
            case 'index': {
                    return array(
                        "templates" => array('nickname', 'fname', 'sname'),
                        "url_vars" => array(),
                        'url_postfix' => array(),
                        'optional' => array(),
                    );
                    break;
                }
            case "form": {
                    return array(
                        "templates" => array(),
                        "url_vars" => array(
                            "gid" => array("gid" => 'literal'),
                        ),
                        'url_postfix' => array(),
                        'optional' => array(),
                    );
                    break;
                }
            case 'my': {
                    return array(
                        "templates" => array('nickname', 'fname', 'sname'),
                        "url_vars" => array(
                            "gid" => array(),
                        ),
                        'url_postfix' => array(),
                        'optional' => array(),
                    );
                    break;
                }
        }
    }

    /**
     * Transform values of request data
     * 
     * @param string $var_name_from variable name from request
     * @param string $var_name_to variable name from method
     * @param string $value variable value from request
     * @return mixed
     */
    public function requestSeoRewrite($var_name_from, $var_name_to, $value)
    {
        $user_data = array();

        if ($var_name_from == $var_name_to) {
            return $value;
        }

        show_404();
    }

    /**
     * Return data for generating xml sitemap 
     * 
     * @return array
     */
    public function getSitemapXmlUrls()
    {
        $this->CI->load->helper('seo');
        $return = array();
        return $return;
    }

    /**
     * Return data for generating sitemap page
     * 
     * @return array
     */
    public function getSitemapUrls()
    {
        $this->CI->load->helper('seo');
        //$auth = $this->CI->session->userdata("auth_type");

        $block[] = array(
            "name" => l('header_memberships_index', self::MODULE_GID),
            "link" => rewrite_link(self::MODULE_GID, 'index'),
            "clickable" => true,
        );
        return $block;
    }

    private function validateActivation($membership)
    {
        $result = array();
        if (empty($membership['services_array'])) {
            $result['errors'][] = 'no_services';
        } else {
            if (!self::hasServices($membership)) {
                $result['errors'][] = 'no_active_services';
            }
        }
        return $result;
    }

    private function validateDeactivation($membership)
    {
        return array();
    }

    public static function hasServices($membership)
    {
        $has_services = false;
        foreach ($membership['services_array'] as $service) {
            if ($service['is_active']) {
                $has_services = true;
                break;
            }
        }
        return $has_services;
    }

    /**
     * Activate membership
     * @param int $id
     * @return array
     * @throws Exception
     */
    public function activate($id)
    {
        if ($id <= 0) {
            return array('errors' => array('wrong_id'));
        }
        $membership_data = $this->formatMembership($this->getMembershipById($id));
        $validation = $this->validateActivation($membership_data);
        if (empty($validation['errors'])) {
            $this->updateMembershipStatus($id, 1);
            
            $active_services = array();
            
            foreach($membership_data['services_array'] as $service_id => $service_data) {
                if ($service_data['is_active']) {
                    $active_services[] = $service_id;
                }
            }
            
            $this->CI->load->model('Services_model');
            $this->CI->Services_model->updateMembershipServicesStatus($id, 1, array_keys($active_services));
        }
        return $validation;
    }

    /**
     * Deactivate membership
     * @param int $id
     * @return array
     * @throws Exception
     */
    public function deactivate($id)
    {
        if ($id <= 0) {
            return array('errors' => array('wrong_id'));
        }
        $this->updateMembershipStatus($id, 0);
        $this->CI->load->model('Services_model');
        $this->CI->Services_model->updateMembershipServicesStatus($id, 0);
        return array();
    }

    /**
     * Activate service
     * @param int $membership_id
     * @param int $service_id
     * @return array
     * @throws Exception
     */
    public function activateService($membership_id, $service_id)
    {
        if ($service_id <= 0) {
            return array('errors' => array('wrong_service_id'));
        } elseif ($membership_id <= 0) {
            return array('errors' => array('wrong_membership_id'));
        }
        $membership_data = $this->formatMembership(
            $this->getMembershipById($membership_id));
        if (empty($membership_data['services_array'][$service_id]['is_active'])) {
            if (!isset($membership_data['services_array'][$service_id])) {
                $membership_data['services_array'][$service_id] = array('is_active' => 0, 'params' => array());
            }
            $membership_data['services_array'][$service_id]['is_active'] = 1;
            if ($membership_data['is_active']) {
                $this->CI->load->model('Services_model');
                $this->CI->Services_model->updateServiceStatus($service_id, 1);
            }
        }
        $save_data = array(
            'services' => $membership_data['services_array'],
        );
        $validation = $this->validateMembership($membership_id, $save_data);
        if (empty($validation['errors'])) {
            $this->saveMembership($membership_id, $validation['data']);
        }
        return $validation;
    }

    /**
     * Deactivate service
     * @param int $membership_id
     * @param int $service_id
     * @return array
     * @throws Exception
     */
    public function deactivateService($membership_id, $service_id)
    {
        if ($service_id <= 0) {
            return array('errors' => array('wrong_service_id'));
        } elseif ($membership_id <= 0) {
            return array('errors' => array('wrong_membership_id'));
        }
        $membership_data = $this->Memberships_model->formatMembership(
            $this->Memberships_model->getMembershipById($membership_id));
        if ($membership_data['is_active']) {
            $this->CI->load->model('Services_model');
            $this->CI->Services_model->updateServiceStatus($service_id, 0);
        }
        $membership_data['services_array'][$service_id]['is_active'] = 0;
        $success = array();
        if (!$this->Memberships_model->hasServices($membership_data)) {
            $deactivation_result = $this->Memberships_model->deactivate($membership_id);
            if (empty($deactivation_result['errors'])) {
                $success[] = l('success_item_deactivate', Memberships_model::MODULE_GID);
            }
        }
        $save_data = array(
            'services' => $membership_data['services_array'],
        );
        $validate_data = $this->Memberships_model->validateMembership($membership_id, $save_data);
        if (empty($validate_data['errors'])) {
            $this->Memberships_model->saveMembership($membership_id, $validate_data['data']);
        }
        return $validate_data;
    }

    public static function getServicesByMemberships(array $memberships)
    {
        $all_services = array();
        foreach ($memberships as $membership) {
            if (empty($membership['services_array'])) {
                continue;
            }
            foreach ($membership['services_array'] as $id => $service) {
                if (empty($service['is_active'])) {
                    continue;
                }
                $svs_link = &$membership['services_list'][$id];
                if (empty($all_services[$svs_link['template_gid']])) {
                    $all_services[$svs_link['template_gid']] = array(
                        'template_gid' => $svs_link['template_gid'],
                        'name' => $svs_link['name'],
                    );
                }
                $all_services[$svs_link['template_gid']]['membership_templates'][$membership['id']] = $svs_link['template'];
            }
        }
        return $all_services;
    }

    /**
     * Return membership period in days
     * 
     * @param array $membership_data membership data
     * @return integer
     */
    public function getMembershipDays($membership_data)
    {
        switch ($membership_data['period_type']) {
            case self::PERIOD_TYPE_HOURS: {
                    return $membership_data['period_count'] / 24;
                    break;
                }
            case self::PERIOD_TYPE_DAYS: {
                    return $membership_data['period_count'];
                    break;
                }
            case self::PERIOD_TYPE_MONTHS: {
                    return $membership_data['period_count'] * 30;
                    break;
                }
            case self::PERIOD_TYPE_YEARS: {
                    return $membership_data['period_count'] * 365;
                    break;
                }
            default: {
                    return 0;
                    break;
                }
        }
    }

    /**
     * Return current membership counter
     * 
     * @return integer
     */
    public function generateGUID()
    {
        $membership_counter = $this->CI->pg_module->get_module_config('memberships', 'membership_counter');
        $membership_counter = (int) $membership_counter + 1;
        return self::GUID_PREFIX . $membership_counter;
    }

    /**
     * Increase membership counter
     * 
     * @return void
     */
    public function incGidIndex()
    {
        $membership_counter = $this->CI->pg_module->get_module_config('memberships', 'membership_counter');
        $membership_counter = (int) $membership_counter + 1;
        $this->CI->pg_module->set_module_config('memberships', 'membership_counter', $membership_counter);
    }

    public function installBatch(array $memberships)
    {
        //$default_lang = $this->CI->pg_language->languages[$this->get_default_lang_id()];
        $this->CI->load->model('Services_model');
        foreach ($memberships as $membership) {
            foreach ($this->CI->pg_language->languages as $lang) {
                if (isset($membership['name'][$lang['code']])) {
                    $membership['name_' . $lang['id']] = $membership['name'][$lang['code']];
                }
                if (isset($membership['description'][$lang['code']])) {
                    $membership['description_' . $lang['id']] = $membership['description'][$lang['code']];
                }
            }
            $service_gids = array();
            foreach ($membership['services'] as $service_gid => $service_settings) {
                $service_gids[] = $service_gid;
            }
            unset($membership['services']);
            unset($membership['name']);
            unset($membership['description']);
            $validated = $this->validateMembership(null, $membership);
            $membership_id = $this->saveMembership(null, $validated['data']);
            $params = array(
                'where' => array(
                    'id_membership' => $membership_id
                ),
                'where_in' => array(
                    'template_gid' => $service_gids,
                ),
            );
            foreach ($this->CI->Services_model->get_service_list($params) as $service) {
                $this->activateService((int) $membership_id, (int) $service['id']);
            }
        }
    }

}
