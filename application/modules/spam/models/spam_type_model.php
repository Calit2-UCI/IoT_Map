<?php

namespace Pg\Modules\Spam\Models;

define("SPAM_TYPES_TABLE", DB_PREFIX . "spam_types");

/**
 * Spam type Model
 *
 * @package PG_RealEstate
 * @subpackage Spam
 * @category	models
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * */
class Spam_type_model extends \Model
{

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
     * Table fields
     * @var array
     */
    public $fields = array(
        "id",
        "gid",
        "form_type",
        "send_mail",
        "status",
        "module",
        "model",
        "callback",
        "obj_count",
        "obj_need_approve",
    );

    /**
     * Format settings
     * @var array
     */
    private $format_settings = array(
        "use_format" => true,
        "get_form_type" => true,
    );

    /**
     * Types cache
     * @var array
     */
    private $type_cache = array();

    /**
     * Deactivated settings
     * @var array
     */
    private $settings = array("send_alert_to_email");

    /**
     * Constructor
     *
     * @return Linker_type Object
     */
    public function __construct()
    {
        parent::__construct();
        $this->CI = &get_instance();
        $this->DB = &$this->CI->db;
        $this->DB->memcache_tables(array(SPAM_TYPES_TABLE));
    }

    /**
     * Get type by ID/GID
     * @param integer $type_id
     * @return mixed
     */
    public function get_type_by_id($type_id)
    {
        $field = "id";
        if (!intval($type_id)) {
            $field = "gid";
            $type_id = preg_replace("/[^a-z_]/", "", strtolower($type_id));
        }
        if (!$type_id) {
            return false;
        }

        if (isset($this->type_cache[$type_id])) {
            return $this->type_cache[$type_id];
        }

        $this->DB->select(implode(", ", $this->fields));
        $this->DB->from(SPAM_TYPES_TABLE);
        $this->DB->where($field, $type_id);

        //_compile_select;
        $result = $this->DB->get()->result();
        if (!empty($result)) {
            $rt = get_object_vars($result[0]);
            $this->type_cache[$rt['id']] = $this->type_cache[$rt['gid']] = $rt;
            return $rt;
        } else {
            return false;
        }
    }

    /**
     * Save type
     * @param array $data
     * @return boolean
     */
    public function save_type($id, $data)
    {
        if (!$id) {
            if (!isset($data["gid"]) || !$data["gid"]) {
                return false;
            }

            $data["gid"] = preg_replace("/[^a-z_]/", "", strtolower($data["gid"]));

            $type = $this->get_type_by_id($data["gid"]);
            if ($type) {
                return $type["id"];
            }

            $this->DB->insert(SPAM_TYPES_TABLE, $data);
            $id = $this->DB->insert_id();
        } else {
            $fields = array_flip($this->fields);
            foreach ($data as $key => $value) {
                if (!isset($fields[$key])) {
                    unset($data[$key]);
                }
            }

            if (empty($data)) {
                return false;
            }

            $this->DB->where("id", $id);
            $this->DB->update(SPAM_TYPES_TABLE, $data);
        }

        return $id;
    }

    /**
     * Remove type by ID or GID
     * @param mixed $type_id integer ID / string GID
     */
    public function delete_type($type_id)
    {
        $type = $this->get_type_by_id($type_id);
        $this->DB->where("id", $type["type_id"]);
        $this->DB->delete(SPAM_TYPES_TABLE);
        return;
    }

    /**
     * Return all types as array
     * @param boolean $status
     * @param array $filter_object_ids
     * @param boolean $formatted
     * @return array
     */
    public function get_types($status = false, $filter_object_ids = null, $formatted = true)
    {
        $this->DB->select(implode(", ", $this->fields));
        $this->DB->from(SPAM_TYPES_TABLE);

        if ($status) {
            $this->DB->where("status", "1");
        }

        if (is_array($filter_object_ids)) {
            foreach ($filter_object_ids as $value) {
                $this->DB->where_in("id", $value);
            }
        }

        $results = $this->DB->get()->result_array();
        if (!empty($results) && is_array($results)) {
            foreach ($results as $r) {
                $this->type_cache[$r['id']] = $this->type_cache[$r['gid']] = $data[$r['id']] = $r;
            }
            if ($formatted) {
                return $this->format_type($data);
            } else {
                return $data;
            }
        }
        return array();
    }

    /**
     * Validate deactivate settings
     * @param array $data
     * @return array
     */
    public function validate_settings($data)
    {
        $return = array("errors" => array(), "data" => array());

        foreach ($this->settings as $field) {
            if (isset($data[$field])) {
                $return["data"][$field] = $data[$field];
            }
        }

        if (isset($return["data"]["send_alert_to_email"]) && !empty($return["data"]["send_alert_to_email"])) {
            $this->CI->config->load("reg_exps", TRUE);
            $email_expr = $this->CI->config->item("email", "reg_exps");
            $return["data"]["send_alert_to_email"] = strip_tags($return["data"]["send_alert_to_email"]);
            if (empty($return["data"]["send_alert_to_email"]) || !preg_match($email_expr, $return["data"]["send_alert_to_email"])) {
                $return["errors"][] = l("error_email_incorrect", "spam");
            }
        }

        return $return;
    }

    /**
     * Validate type
     * @param int $id
     * @param array $data
     * @return array
     */
    public function validate_type($id, $data)
    {
        $return = array("errors" => array(), "data" => array());

        foreach ($this->fields as $field) {
            if (isset($data[$field])) {
                $return["data"][$field] = $data[$field];
            }
        }

        if (isset($data["id"])) {
            $return['data']['id'] = intval($data['id']);
        }

        if (isset($data["gid"])) {
            $return["data"]["gid"] = trim(strip_tags($data['gid']));
            if (empty($return["data"]["gid"])) {
                $return["errors"][] = l("error_empty_type_gid", "spam");
            }
        } elseif (!$id) {
            $return["errors"][] = l("error_empty_type_gid", "spam");
        }

        if (isset($data["form_type"])) {
            $return["data"]["form_type"] = trim(strip_tags($data['form_type']));
            if (empty($return["data"]["form_type"])) {
                $return["errors"][] = l("error_empty_type_form_type", "spam");
            }
        } elseif (!$id) {
            $return["errors"][] = l("error_empty_type_form_type", "spam");
        }

        if (isset($data["status"])) {
            $return["data"]["status"] = $data["status"] ? 1 : 0;
        }

        if (isset($data["send_mail"])) {
            $return["data"]["send_mail"] = $data["send_mail"] ? 1 : 0;
        }

        if (isset($data["module"])) {
            $return["data"]["send_mail"] = trim(strip_tags($data["module"]));
        }

        if (isset($data["model"])) {
            $return["data"]["model"] = trim(strip_tags($data["model"]));
        }

        if (isset($data["callback"])) {
            $return["data"]["callback"] = trim(strip_tags($data["callback"]));
        }

        if (isset($data["obj_count"])) {
            $return["data"]["obj_count"] = intval($data["obj_count"]);
        }

        if (isset($data["obj_need_approve"])) {
            $return["data"]["obj_need_approve"] = intval($data["obj_need_approve"]);
        }

        return $return;
    }

    /**
     * Format type
     * @param array $data
     * @return array
     */
    public function format_type($data)
    {
        if (!$this->format_settings["use_format"]) {
            return $data;
        }

        if ($this->format_settings["get_form_type"]) {
            $form_types = ld("form_type", "spam");
            foreach ($data as $key => $type) {
                $data[$key] = $type;
                $data[$key]["form"] = isset($form_types["option"][$type["form_type"]]) ? $form_types["option"][$type["form_type"]] : "-";
            }
        }

        foreach ($data as $key => $type) {
            $data[$key]["output_name"] = $data[$key]["gid"];
        }

        return $data;
    }

    /**
     * Format type
     * @param int $type_id
     * @return array
     */
    public function format_default_type($type_id)
    {
        $return = array();
        foreach ($this->fields as $field) {
            $return[$field] = "-";
        }
        $return["output_name"] = "-";
        return $return;
    }

    /**
     * Import spam type languages
     * @param array $data
     * @param array $langs_file
     * @param array $langs_ids
     */
    public function update_langs($data, $langs_file, $langs_ids)
    {
        foreach ((array) $data as $type_data) {
            $this->CI->pg_language->pages->set_string_langs(
                "spam", "stat_header_spam_" . $type_data["gid"], $langs_file["stat_header_spam_" . $type_data["gid"]], $langs_ids);
            $this->CI->pg_language->pages->set_string_langs(
                "spam", "error_is_send_" . $type_data["gid"], $langs_file["error_is_send_" . $type_data["gid"]], $langs_ids);
            $this->CI->pg_language->pages->set_string_langs(
                "spam", "error_is_deleted_" . $type_data["gid"], $langs_file["error_is_deleted_" . $type_data["gid"]], $langs_ids);
        }
    }

    /**
     * Export spam type languages
     * @param array $data
     * @param array $langs_ids
     */
    public function export_langs($data, $langs_ids = null)
    {
        $gids = array();
        $langs = array();
        foreach ($data as $type_data) {
            $gids[] = "stat_header_spam_" . $type_data["gid"];
        }
        return array_merge($langs, $this->CI->pg_language->export_langs("spam", $gids, $langs_ids));
    }

    /**
     * Activate type
     * @param integer $type_id identifier
     * @param integer $status lisitng status
     */
    public function activate_type($type_id, $status = 1)
    {
        $data["status"] = intval($status);
        $this->save_type($type_id, $data);
    }

    /**
     * Send mail type on/off
     * @param integer $type_id identifier
     * @param integer $status lisitng status
     */
    public function send_mail_type($type_id, $status = 1)
    {
        $data["send_mail"] = intval($status);
        $this->save_type($type_id, $data);
    }

}
