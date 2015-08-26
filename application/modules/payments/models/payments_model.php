<?php

namespace Pg\Modules\Payments\Models;

/**
 * Payments main model
 *
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @author Katya Kashkova <katya@pilotgroup.net>
 * @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
 * */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

define('PAYMENTS_TABLE', DB_PREFIX . 'payments');
define('PAYMENTS_TYPES_TABLE', DB_PREFIX . 'payments_type');

class Payments_model extends \Model
{

    protected $CI;
    protected $DB;
    private $fields = array(
        'id',
        'payment_type_gid',
        'id_user',
        'amount',
        'currency_gid',
        'status',
        'system_gid',
        'date_add',
        'date_update',
        'payment_data',
    );
    // fields will be formatted on
    private $payment_data_registered_fields = array("name", "comment");
    private $moderation_type = 'payments';

    public function __construct()
    {
        parent::__construct();
        $this->CI = &get_instance();
        $this->DB = &$this->CI->db;

        $this->DB->memcache_tables(array(PAYMENTS_TYPES_TABLE));
    }

    public function get_payment_by_id($id)
    {
        $result = $this->DB->select(implode(", ", $this->fields))->from(PAYMENTS_TABLE)->where("id", $id)->get()->result_array();
        $return = (!empty($result)) ? $result[0] : array();
        if (!empty($return["payment_data"])) {
            $return["payment_data"] = unserialize($return["payment_data"]);
        }
        $return["id_payment"] = $return["id"];
        return $return;
    }

    public function get_payment_list($page = null, $items_on_page = null, $order_by = null, $params = array(), $filter_object_ids = null)
    {
        $this->DB->select(implode(", ", $this->fields));
        $this->DB->from(PAYMENTS_TABLE);

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
                if (in_array($field, $this->fields)) {
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
                if (!empty($r["payment_data"]))
                    $r["payment_data"] = unserialize($r["payment_data"]);
                $data[] = $r;
            }
            return $this->format_payments($data);
        }
        return array();
    }

    public function format_payments($data)
    {
        if (empty($data)) {
            return $data;
        }

        $user_ids = $currency_gids = array();
        foreach ($data as $key => $payment) {
            if (empty($user_ids) || !in_array($payment["id_user"], $user_ids)) {
                $user_ids[] = $payment["id_user"];
            }
            if (empty($currency_gids) || !in_array($payment["currency_gid"], $currency_gids)) {
                $currency_gids[] = $payment["currency_gid"];
            }

            if (!empty($payment["payment_data"])) {
                foreach ($payment["payment_data"] as $param_id => $param_value) {
                    if (in_array($param_id, $this->payment_data_registered_fields)) {
                        $payment["payment_data_formatted"][$param_id] = array(
                            "name" => l('html_field_' . $param_id, 'payments'),
                            "value" => nl2br($param_value)
                        );
                    }
                }
                if (!empty($payment["payment_data"]["lang"]) && !empty($payment["payment_data"]["module"])) {
                    $payment["payment_data"]['name'] = l($payment["payment_data"]["lang"], $payment["payment_data"]["module"]);
                }
            }
            $data[$key] = $payment;
        }

        if (!empty($user_ids)) {
            $this->CI->load->model('Users_model');
            $users = $this->CI->Users_model->get_users_list_by_key(null, null, null, array(), $user_ids, true, true);
        }

        if (!empty($currency_gids)) {
            $this->CI->load->model("payments/models/Payment_currency_model");
            $params["where_in"]["gid"] = $currency_gids;
            $currencies = $this->CI->Payment_currency_model->get_currency_list_by_key($params);
        }

        foreach ($data as $key => $payment) {
            if (!empty($users[$payment["id_user"]])) {
                $payment["user"] = $users[$payment["id_user"]];
            } else {
                $payment["user"] = $this->CI->Users_model->format_default_user($payment["id_user"]);
            }
            if (!empty($currencies[$payment["currency_gid"]])) {
                $payment["currency"] = $currencies[$payment["currency_gid"]];
            }
            $data[$key] = $payment;
        }
        return $data;
    }

    public function get_payment_count($params = array(), $filter_object_ids = null)
    {
        $this->DB->select("COUNT(*) AS cnt");
        $this->DB->from(PAYMENTS_TABLE);

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

        $results = $this->DB->get()->result_array();
        if (!empty($results) && is_array($results)) {
            return intval($results[0]["cnt"]);
        }
        return 0;
    }

    public function validate_payment($id, $data)
    {
        $return = array("errors" => array(), "data" => array());

        if (isset($data["payment_type_gid"])) {
            $return["data"]["payment_type_gid"] = $data["payment_type_gid"];
        }

        if (isset($data["id_user"])) {
            $return["data"]["id_user"] = intval($data["id_user"]);
        }

        if (isset($data["amount"])) {
            $return["data"]["amount"] = abs(floatval($data["amount"]));
        }

        if (isset($data["currency_gid"])) {
            $return["data"]["currency_gid"] = $data["currency_gid"];
        }

        if (isset($data["status"])) {
            $return["data"]["status"] = intval($data["status"]);
        }

        if (isset($data["system_gid"])) {
            $return["data"]["system_gid"] = $data["system_gid"];
        }

        if (isset($data["payment_data"])) {
            if (!empty($data["payment_data"]["comment"])) {
                $this->CI->load->model('moderation/models/Moderation_badwords_model');
                $bw_count = $this->CI->Moderation_badwords_model->check_badwords(
                    $this->moderation_type, 
                    $data["payment_data"]["comment"]
                );
                if ($bw_count) {
                    $return['errors'][] = l('error_badwords_comment', 'payments');
                }
            }
            $return["data"]["payment_data"] = serialize($data["payment_data"]);
        }

        return $return;
    }

    public function save_payment($id, $data)
    {
        if (is_null($id)) {
            $data["date_add"] = $data["date_update"] = date("Y-m-d H:i:s");
            $this->DB->insert(PAYMENTS_TABLE, $data);
            $id = $this->DB->insert_id();

            $this->CI->load->model('menu/models/Indicators_model');
            $this->CI->Indicators_model->add('new_payment_item', $this->DB->insert_id());
        } else {
            $data["date_update"] = date("Y-m-d H:i:s");
            $this->DB->where('id', $id);
            $this->DB->update(PAYMENTS_TABLE, $data);
        }
        return $id;
    }

    public function delete_payment($id)
    {
        $this->DB->where("id", $id);
        $this->DB->delete(PAYMENTS_TABLE);
        return;
    }

    // Payment types

    public function get_payment_type_by_id($id)
    {
        $result = $this->DB->select("id, gid, callback_module, callback_model, callback_method")->from(PAYMENTS_TYPES_TABLE)->where("id", $id)->get()->result_array();
        $return = (!empty($result)) ? $result[0] : array();
        return $return;
    }

    public function get_payment_type_by_gid($gid)
    {
        $result = $this->DB->select("id, gid, callback_module, callback_model, callback_method")->from(PAYMENTS_TYPES_TABLE)->where("gid", $gid)->get()->result_array();
        $return = (!empty($result)) ? $result[0] : array();
        return $return;
    }

    public function get_payment_type_list($params = array(), $filter_object_ids = null, $order_by = null)
    {
        $this->DB->select('id, gid, callback_module, callback_model, callback_method')->from(PAYMENTS_TYPES_TABLE);

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
                $this->DB->order_by($field . " " . $dir);
            }
        }

        $results = $this->DB->get()->result_array();
        if (!empty($results) && is_array($results)) {
            return $results;
        }
        return array();
    }

    public function get_payment_type_count($params = array(), $filter_object_ids = null)
    {
        $this->DB->select("COUNT(*) AS cnt")->from(PAYMENTS_TYPES_TABLE);

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

        $results = $this->DB->get()->result_array();
        if (!empty($results) && is_array($results)) {
            return intval($results[0]["cnt"]);
        }
        return 0;
    }

    public function validate_payment_type($id, $data)
    {
        $return = array("errors" => array(), "data" => array());

        if (isset($data["gid"])) {
            $data["gid"] = strip_tags($data["gid"]);
            $data["gid"] = preg_replace("/[^a-z0-9]+/i", '', $data["gid"]);

            $return["data"]["gid"] = $data["gid"];

            if (empty($return["data"]["gid"])) {
                $return["errors"][] = l('error_payment_type_code_incorrect', 'payments');
            }
        }

        if (isset($data["callback_module"])) {
            $return["data"]["callback_module"] = $data["callback_module"];
        }

        if (isset($data["callback_model"])) {
            $return["data"]["callback_model"] = $data["callback_model"];
        }

        if (isset($data["callback_method"])) {
            $return["data"]["callback_method"] = $data["callback_method"];
        }


        return $return;
    }

    public function save_payment_type($id, $data, $name = null)
    {
        if (is_null($id)) {
            $this->DB->insert(PAYMENTS_TYPES_TABLE, $data);
            $id = $this->DB->insert_id();
        } else {
            $this->DB->where('id', $id);
            $this->DB->update(PAYMENTS_TYPES_TABLE, $data);
        }

        if (!empty($name)) {
            $languages = $this->CI->pg_language->languages;
            if (!empty($languages)) {
                $lang_ids = array_keys($languages);
                $this->CI->pg_language->pages->set_string_langs('payments', "payment_type_name_" . $id, $name, $lang_ids);
            }
        }
        return $id;
    }

    public function delete_payment_type($id)
    {
        $this->DB->where("id", $id);
        $this->DB->delete(PAYMENTS_TYPES_TABLE);
        $this->CI->pg_language->pages->delete_string("payments", "payment_type_name_" . $id);
        return;
    }

    public function delete_payment_type_by_gid($gid)
    {
        $payment_type_data = $this->get_payment_type_by_gid($gid);
        if (!empty($payment_type_data["id"])) {
            return $this->delete_payment_type($payment_type_data["id"]);
        } else {
            return false;
        }
    }

    public function change_payment_status($id_payment, $status)
    {
        $payment_data = $this->get_payment_by_id($id_payment);
        if (empty($payment_data)) {
            return false;
        }

        if ($payment_data["status"] != $status) {
            $payment_type = $this->get_payment_type_by_gid($payment_data["payment_type_gid"]);
            $model_name = ucfirst($payment_type["callback_model"]);
            $model_path = strtolower($payment_type["callback_module"] . "/models/") . $model_name;
            $this->CI->load->model($model_path);
            $this->CI->$model_name->$payment_type["callback_method"]($payment_data, $status);

            $this->CI->load->model('menu/models/Indicators_model');
            $this->CI->Indicators_model->delete('new_payment_item', $id_payment, true);

            $this->save_payment($id_payment, array("status" => $status));
        }
        return;
    }

    public function validate_payment_form($data)
    {
        $good = true;
        // if (!$data["payment_type_gid"] || !$data["system_gid"]){
        // $this->system_messages->add_message('error', l('error_system_gid_incorrect', 'payments'));
        // $good = false;
        // }
        if (!$data["amount"]) {
            $this->system_messages->add_message('error', l('error_format_required', 'payments'));
            $good = false;
        }
        if (!$data["currency_gid"]) {
            $this->system_messages->add_message('error', l('error_currency_code_incorrect', 'payments'));
            $good = false;
        }
        return $good;
    }

    public function update_langs($data, $langs_file, $langs_ids)
    {
        foreach ($data as $item) {
            $payment_type = $this->get_payment_type_by_gid($item['gid']);
            $this->CI->pg_language->pages->set_string_langs('payments', "payment_type_name_" . $payment_type['id'], $langs_file[$item['gid']], (array) $langs_ids);
        }
    }

    public function export_langs($data, $langs_ids)
    {
        foreach ($data as $item) {
            $payment_type = $this->get_payment_type_by_gid($item['gid']);
            $gids[$item['gid']] = 'payment_type_name_' . $payment_type['id'];
        }
        $langs_data = $this->CI->pg_language->export_langs('payments', $gids, $langs_ids);
        $export = (count(array_keys($gids)) == count($langs_data)) ? array_combine(array_keys($gids), $langs_data) : array();
        return $export;
    }

}
