<?php

namespace Pg\Modules\Memberships\Models;

use Pg\Modules\Memberships\Models\Memberships_model;

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

define('MEMBERSHIPS_USERS_TABLE', DB_PREFIX . 'memberships_users');

/**
 * User membership model
 * 
 * @package 	PG_Dating
 * @subpackage 	Memberships
 * @category	models
 * @copyright 	Copyright (c) 2000-2014 PG Dating Pro - php dating software
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
class Memberships_users_model extends Memberships_model
{

    /**
     * Link to CodeIgniter object
     * 
     * @var object
     */
    protected $CI;

    /**
     * Link to database object
     * 
     * @var object
     */
    protected $DB;

    /**
     * User membership object properties
     * 
     * @var array
     */
    private $fields = array(
        'id',
        'id_user',
        'id_membership',
        'membership',
        'services',
        'services_count',
        'is_active',
        'date_activated',
        'date_expired',
    );

    /**
     * Settings for formatting membership object
     * 
     * @var array
     */
    private $format_settings = array(
        'get_user' => false,
    );

    /**
     * Class constructor
     * 
     * @return Memberships_users_model
     */
    public function __construct()
    {
        parent::__construct();
        $this->CI = &get_instance();
        $this->DB = &$this->CI->db;
    }

    /**
     * Save user membership
     * 
     * @param integer $membership_id membership identifier
     * @param array $membership membership data
     * @return integer
     */
    public function saveUserMembership($membership_id = null, $membership = array())
    {
        if (empty($membership_id)) {
            $update_arr = array();
            foreach (array_diff(array_keys($membership), array('id', 'id_user')) as $field) {
                $update_arr[] = $field . "`='" . $membership[$field];
            }
            $sql = $this->DB->insert_string(MEMBERSHIPS_USERS_TABLE, $membership)
                    . ' ON DUPLICATE KEY UPDATE `' . implode("',`", $update_arr) . "'";
            $this->DB->query($sql);
            $membership_id = $this->DB->insert_id();
        } else {
            $this->DB->where('id', $membership_id);
            $this->DB->update(MEMBERSHIPS_USERS_TABLE, $membership);
        }
        return $membership_id;
    }

    /**
     * Return user's memberships as array
     * 
     * @param array $order_by sorting data
     * @param array $params filter parameters
     * @param array $filter_object_ids membership identifiers for filtering
     * @return array
     */
    public function getUserMembershipsList($order_by = null, $params = array(), $filter_object_ids = null)
    {
        $this->DB->select(implode(", ", $this->fields))->from(MEMBERSHIPS_USERS_TABLE);

        if (isset($params["where"]) && is_array($params["where"]) && count($params["where"])) {
            $this->DB->where($params["where"]);
        }
        if (isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])) {
            foreach ($params["where_in"] as $field => $value) {
                $this->DB->where_in($field, $value);
            }
        }
        if (isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])) {
            foreach ($params["where_sql"] as $value) {
                $this->DB->where($value);
            }
        }
        if (isset($filter_object_ids) && is_array($filter_object_ids) && count($filter_object_ids)) {
            $this->DB->where_in("id", $filter_object_ids);
        }
        if (is_array($order_by) && count($order_by) > 0) {
            foreach ($order_by as $field => $dir) {
                if (in_array($field, $this->fields)) {
                    $this->DB->order_by($field . " " . $dir);
                }
            }
        }
        return $this->formatMemberships($this->DB->get()->result_array());
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
     * Format membership data
     * 
     * @param array $membership membership data
     * @return array
     */
    public function formatMembership($membership, $langs_ids = null)
    {
        return current($this->formatMemberships(array($membership), $langs_ids));
    }

    /**
     * Format memberships' data
     * 
     * @param array $memberships_data memberships' data
     * @return array
     */
    public function formatMemberships($memberships_data, $langs_ids = null)
    {
        $user_memberships_ids = array();
        foreach ($memberships_data as $membership_data) {
            $user_memberships_ids[$membership_data['id']] = $membership_data['id_membership'];
        }

        if (!empty($user_memberships_ids)) {
            $memberships = parent::formatMemberships(
                parent::getMembershipsList(array('ids' => $user_memberships_ids)), 
                $langs_ids
            );

            $temp = array();
            foreach ($memberships as $membership) {
                $temp[$membership['id']] = $membership;
            }
            foreach ($memberships_data as $key => $membership_data) {
                if (!empty($temp[$membership_data['id_membership']])) {
                    $memberships_data[$key]['membership_info'] = $temp[$membership_data['id_membership']];
                    $memberships_data[$key]['left_str'] = $this->left($memberships_data[$key]);
                } elseif (!empty($membership_data['membership'])){
                    $memberships_data[$key]['membership_info'] = (array)unserialize($membership_data['membership']);
                    $memberships_data[$key]['left_str'] = $this->left($memberships_data[$key]);
                } else {
                    $memberships_data[$key]['membership_info'] = array();
                }
            }
        
            $user_memberships_services = array();
            $param['where_in']['id_users_membership'] = array_keys($user_memberships_ids);
            $this->CI->load->model('services/models/Services_users_model');
            $services = $this->CI->Services_users_model->get_services_list($param);
            
            foreach ($services as $ums) {
                if (!isset($user_memberships_services[$ums['id']])) {
                    $user_memberships_services[$ums['id']] = array(
                        'services_array' => array(),
                        'services_list' => array(),
                    );
                }
                $ums['name'] = $ums['service']['name'];
                $user_memberships_services[$ums['id_users_membership']]['services_array'][$ums['id']]['is_active'] = 1;
                $user_memberships_services[$ums['id_users_membership']]['services_list'][$ums['id']] = $ums;
            }
            
            foreach ($memberships_data as $key => $membership_data) {
                if (isset($user_memberships_services[$membership_data['id']])) {
                    $memberships_data[$key]['membership_info'] = array_merge(
                        $memberships_data[$key]['membership_info'], 
                        $user_memberships_services[$membership_data['id']]);
                }
            }
        }

        return $memberships_data;
    }

    private function left($user_membership)
    {
        // TODO: Сделать красиво 
        $left = $this->pg_date->diff('now', $user_membership['date_expired']);
        if ($left->days > 0) {
            return $left->days + round(($left->h + round($left->i / 60)) / 24)
                . ' ' . l('period_type_' . parent::PERIOD_TYPE_DAYS, self::MODULE_GID);
        } else {
            return l('expires_today', self::MODULE_GID);
        }
    }

    /**
     * Return number of users' memberships
     * 
     * @param array $params filter parameters
     * @param array $filter_object_ids identifiers for filtering
     * @return integer
     */
    public function getUserMembershipsCount($params = array(), $filter_object_ids = null)
    {
        if (isset($params["where"]) && is_array($params["where"]) && count($params["where"])) {
            $this->DB->where($params["where"]);
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
                $this->DB->where($value);
            }
        }
        if (isset($filter_object_ids) && is_array($filter_object_ids) && count($filter_object_ids)) {
            $this->DB->where_in("id", $filter_object_ids);
        }

        $results = $this->DB->get()->result_array();
        if (!empty($results) && is_array($results)) {
            return intval($results[0]['cnt']);
        }
        return 0;
    }

    /**
     * Update memberships by cron
     * 
     * @return void
     */
    public function cronUpdateMemberships()
    {
        $this->DB->select('id')->from(MEMBERSHIPS_USERS_TABLE)->where('date_expire <', time());
        $results = $this->DB->get()->result_array();
        if (!empty($results) && is_array($results)) {
            $ids = array();
            foreach ($results as $r) {
                $ids[] = $r['id'];
            }
            if ($ids) {
                $params['where_in']['id_users_membership'] = $ids;
                $this->CI->load->model('services/models/Services_users_model');
                $this->CI->Services_users_model->update_service($params, array('status' => 0));
            }
        }
    }

    /**
     * Return user's memberships as array
     * 
     * @param integer $user_id user identifier
     * @return array
     */
    public function getUserMemberships($user_id)
    {
        if (empty($user_id)) {
            throw new \Exception('Empty user id');
        }
        parent::setFormatSettings('get_services', true);
        $memberships = $this->formatMemberships(
            $this->getUserMembershipsList(null, array('where' => array('id_user' => $user_id)))
        );
        parent::setFormatSettings('get_services', true);            
        return $memberships;
    }

    public function userHasMembership($user_id)
    {
        return (bool) $this->DB->select('id')
            ->from(MEMBERSHIPS_USERS_TABLE)
            ->where('id_user', $user_id)
            ->get()->num_rows();
    }

}
