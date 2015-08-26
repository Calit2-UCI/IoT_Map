<?php

namespace Pg\Modules\Ausers\Models;

/**
 * Ausers module
 *
 * @package 	PG_Core
 * @copyright 	Copyright (c) 2000-2014 PG Core
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
 
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

define('AUSERS_TABLE', DB_PREFIX . 'ausers');

/**
 * Ausers module
 *
 * @package 	PG_Core
 * @copyright 	Copyright (c) 2000-2014 PG Core
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
class Ausers_model extends \Model
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
     * User type by default
     * 
     * @var string
     */
    public $user_type = 'admin';
    
    /**
	 * Properties of ausers object in data source
	 * 
	 * @var array
	 */
    public $fields_all = array(
        'id',
        'date_created',
        'date_modified',
        'email',
        'nickname',
        'password',
        'name',
        'description',
        'status',
        'lang_id',
        'user_type',
        'permission_data'
    );

    /**
	 * Constructor
	 * 
	 * @return Ausers_model
	 */
    public function __construct()
    {
        parent::__construct();
        $this->CI = &get_instance();
        $this->DB = &$this->CI->db;
    }

    /**
	 * Return administrator data by identifier
	 * 
	 * @param integer $user_id administrator identifier
	 * @return array
	 */
    public function get_user_by_id($user_id)
    {
        $result = $this->DB->select(implode(", ", $this->fields_all))->from(AUSERS_TABLE)->where("id", $user_id)->get()->result_array();
        if (empty($result)) {
            return false;
        } else {
            $data = $this->format_user($result[0]);
            return $data;
        }
    }

    /**
	 * Return administrator data by login and password
	 * 
	 * @param string $login administrator login
	 * @param string $password administrator password
	 * @return array
	 */
    public function get_user_by_login_password($login, $password)
    {
        $result = $this->DB->select(implode(", ", $this->fields_all))->from(AUSERS_TABLE)->where("nickname", $login)->where("password", $password)->get()->result_array();
        if (empty($result)) {
            return false;
        } else {
            $data = $this->format_user($result[0]);
            return $data;
        }
    }

    /**
	 * Return administrators data by criteria as array
	 * 
	 * Sorting data puts as array of order field as key and order direction as value 
	 * for example, array('id'=>'DESC')
	 * 
	 * Available where clauses: where, where_in, where_sql etc.
	 * 
	 * @param integer $page page of results
	 * @param integer $items_on_page items per page
	 * @param array $order_by sorting data
	 * @param array $params where clauses 
	 * @param array $filter_object_ids filter by identificators
	 * @return array
	 */
    public function get_users_list($page = null, $items_on_page = null, $order_by = null, $params = array(), $filter_object_ids = null)
    {
        $this->DB->select(implode(", ", $this->fields_all));
        $this->DB->from(AUSERS_TABLE);

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
                if (in_array($field, $this->fields_all)) {
                    $this->DB->order_by($field . " " . $dir);
                }
            }
        }

        if (!is_null($page)) {
            $page = intval($page) ? intval($page) : 1;
            $this->DB->limit($items_on_page, $items_on_page * ($page - 1));
        }
        $results = $this->DB->get()->result_array();
        if (!empty($results) && is_array($results)) {
            foreach ($results as $r) {
                $data[] = $this->format_user($r);
            }
            return $data;
        }
        return false;
    }

    /**
	 * Return count of administrators by criteria
	 * 
	 * Available where clauses: where, where_in, where_sql etc.
	 * 
	 * @param array $params where clauses 
	 * @param array $filter_object_ids filter by identificators
	 * @return array
	 */
    public function get_users_count($params = array(), $filter_object_ids = null)
    {
        $this->DB->select("COUNT(*) AS cnt");
        $this->DB->from(AUSERS_TABLE);

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

        if (isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])) {
            foreach ($params["where_sql"] as $value) {
                $this->DB->where($value);
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
    
    /**
	 * Format administrator data
	 * 
	 * @param array $data administrator data 
	 * @return array
	 */
    public function format_user($data)
    {
        $data["permission_data"] = unserialize($data["permission_data"]);
        if ($this->CI->pg_module->is_module_installed("moderators") && $data["user_type"] == 'moderator') {
            $data["module"] = 'moderators';
        } else {
            $data["module"] = 'ausers';
        }
        return $data;
    }
    
    /**
	 * Save administrator data to data source
	 * 
	 * Return administrator identifier of inserted or existed record.
	 * 
	 * @param integer $user_id administrator identifier
	 * @param array $data administrator data
     * @param string $password password string
     * @return integer
	 */
    public function save_user($user_id = null, $attrs = array(), $password='')
    {
        if (is_null($user_id)) {
            $attrs["date_created"] = $attrs["date_modified"] = date("Y-m-d H:i:s");
            if (!isset($attrs["status"])) {
                $attrs["status"] = 1;
            }
            $this->DB->insert(AUSERS_TABLE, $attrs);
            $user_id = $this->DB->insert_id();
            
            $is_notification_installed = $this->CI->pg_module->is_module_installed('notifications');
            if($is_notification_installed){
                $user_data = array(
                    'user' => $attrs['name'],
                    'email' => $attrs['email'],
                    'password' => $password,
                    'user_type' => l('field_user_type_admin', 'ausers'),
                );
				$this->CI->load->model("Notifications_model");
                $this->CI->Notifications_model->send_notification($attrs['email'], "auser_account_create_by_admin", $user_data, '', $attrs['lang_id']);
            }
        } else {
            $attrs["date_modified"] = date("Y-m-d H:i:s");
            $this->DB->where('id', $user_id);
            $this->DB->update(AUSERS_TABLE, $attrs);
        }
        return $user_id;
    }

    /**
	 * Activate/de-activate administrator.
	 * 
	 * Available status:
	 * 1 - activate administrator
	 * 0 - de-activate administrator
	 * 
	 * @param integer $user_id administrator identifier
	 * @param integer $status administraot status
	 * @return void
	 */
    public function activate_user($user_id, $status = 1)
    {
        $attrs["status"] = intval($status);
        $attrs["date_modified"] = date("Y-m-d H:i:s");
        $this->DB->where('id', $user_id);
        $this->DB->update(AUSERS_TABLE, $attrs);
    }

    /**
	 * Validate administrator data for saving to data source. 
	 * 
	 * You can save results of this method to data source.
	 * 
	 * @param integer $user_id administrator identifier
	 * @param array $data administrator data
	 * @return array
	 */
    public function validate_user($user_id = null, $data = array())
    {
        $return = array("errors" => array(), "data" => array());

        if (isset($data["name"])) {
            $return["data"]["name"] = strip_tags($data["name"]);
        }

        if (isset($data["description"])) {
            $return["data"]["description"] = trim($data["description"]);
        }

        $return["data"]["user_type"] = $this->user_type;

        if (isset($data["lang_id"])) {
            $return["data"]["lang_id"] = $data["lang_id"];
        }

        if (isset($data["permission_data"])) {
            $return["data"]["permission_data"] = serialize($data["permission_data"]);
        }

        $this->CI->config->load('reg_exps', TRUE);

        if (isset($data["nickname"])) {
            $login_expr = $this->CI->config->item('nickname', 'reg_exps');
            $return["data"]["nickname"] = strip_tags($data["nickname"]);
            if (empty($return["data"]["nickname"]) || !preg_match($login_expr, $return["data"]["nickname"])) {
                $return["errors"][] = l('error_nickname_incorrect', 'ausers');
            }
            $params["where"]["nickname"] = $return["data"]["nickname"];
            if ($user_id) {
                $params["where"]["id <>"] = $user_id;
            }
            $count = $this->get_users_count($params);
            if ($count > 0) {
                $return["errors"][] = l('error_nickname_already_exists', 'ausers');
            }
        }

        if (isset($data["email"])) {
            $email_expr = $this->CI->config->item('email', 'reg_exps');
            $return["data"]["email"] = strip_tags($data["email"]);
            if (empty($return["data"]["email"]) || !preg_match($email_expr, $return["data"]["email"])) {
                $return["errors"][] = l('error_email_incorrect', 'ausers');
            }
        }

        if ((isset($data["update_password"]) && $data["update_password"]) || !$user_id) {
            if (empty($data["password"]) || empty($data["repassword"])) {
                $return["errors"][] = l('error_password_empty', 'ausers');
            } elseif ($data["password"] != $data["repassword"]) {
                $return["errors"][] = l('error_pass_repass_not_equal', 'ausers');
            } else {
                $password_expr = $this->CI->config->item('password', 'reg_exps');
                $data["password"] = trim(strip_tags($data["password"]));
                if (!preg_match($password_expr, $data["password"])) {
                    $return["errors"][] = l('error_password_incorrect', 'ausers');
                } else {
                    $return["data"]["password"] = md5($data["password"]);
                }
            }
        }
        return $return;
    }

    /**
	 * Remove administrator data from data source
	 * 
	 * @param integer $user_id administrator identifier
	 * @return void
	 */
    public function delete_user($user_id)
    {
        $this->DB->where('id', $user_id);
        $this->DB->delete(AUSERS_TABLE);
        return;
    }
    
    /**
	 * Returns gids of methods names
	 * 
	 * Guids extract as they are in database
	 * 
	 * @param string $module module GUID
	 * @param array $methods module methods
	 * @return array
	 */
    private function getLangGids($module, $methods)
    {
        $gid = array();
        $gid['module'] = 'ausers_moderation';
        foreach ($methods as $method) {
            $query['where'] = array(
                'module' => $module,
                'method' => $method
            );
            $method = $this->CI->Ausers_model->get_methods($query);
            if (!empty($method[$module]['main'])) {
                $gid['items'][] = 'method_name_' . $method[$module]['main']['id'];
            } else {
                $gid['items'][] = 'method_name_' . $method[$module]['methods'][0]['id'];
            }
        }
        return $gid;
    }

    /**
	 * Export languages of available methods for moderators
	 * 
	 * @param array $module_methods available methods for moderators of module
	 * @param array $langs_ids languages identifiers
	 * @return array
	 */
    public function export_langs($module_methods, $langs_ids = null)
    {
        $lang_data = array();
        foreach ($module_methods as $module => $methods) {
            $gids_db = $this->getLangGids($module, $methods);
            $langs_db = $this->CI->pg_language->export_langs($gids_db['module'], $gids_db['items'], $langs_ids);
            $lang_codes = array_keys($langs_db);
            foreach ($lang_codes as $lang_code) {
                $lang_data[$lang_code][$module] = array_combine($module_methods[$module], $langs_db[$lang_code]);
            }
        }
        return $lang_data;
    }

}
