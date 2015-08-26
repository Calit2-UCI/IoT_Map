<?php

namespace Pg\Modules\Spam\Models;

define("SPAM_ALERTS_TABLE", DB_PREFIX . "spam_alerts");

/**
 * Spam alert Model
 * 
 * @package PG_RealEstate
 * @subpackage Spam
 * @category	models
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * */
class Spam_alert_model extends \Model
{

    /**
     * link to CodeIgniter object
     * @var object
     */
    protected $CI;

    /**
     * link to DataBase object
     * @var object
     */
    protected $DB;

    /**
     * Table fields
     * @var array
     */
    public $fields = array(
        "id",
        "id_type",
        "id_object",
        "id_poster",
        "id_reason",
        "message",
        "mark",
        "date_add",
        "spam_status",
    );

    /**
     * Format settings
     * @var array
     */
    private $format_settings = array(
        "use_format" => true,
        "get_poster" => true,
        "get_type" => true,
        "get_content" => true,
        "get_link" => false,
        "get_object" => false,
        "get_reason" => false,
        "get_deletelink" => false,
        "get_subpost" => false,
    );

    /**
     * Moderation type
     * @var string
     */
    private $moderation_type = "spam";

    /**
     * Cache array for used types
     * @var array
     */
    private $spam_types = array();

    /**
     * Possible alert status
     * @var array
     */
    private $spam_status_arr = array("banned", "unbanned", "removed");

    /**
     * Constructor
     *
     * return Spam alert object
     * required Spam_type_model
     */
    public function __construct()
    {
        parent::__construct();
        $this->CI = &get_instance();
        $this->DB = &$this->CI->db;
        $this->CI->load->model("spam/models/Spam_type_model");
    }

    /**
     * Get spam type by GID
     * @param string $type_gid
     * @return array
     */
    public function get_type_by_gid($type_gid)
    {
        if (!isset($this->types[$type_gid])) {
            $type_data = $this->CI->Spam_type_model->get_type_by_id($type_gid);
            if (!is_array($type_data) || !count($type_data)) {
                return false;
            }
            $this->spam_types[$type_data["id"]] = $type_data;
            $this->spam_types[$type_gid] = $type_data;
        }

        if (is_array($this->spam_types[$type_gid]) && count($this->spam_types[$type_gid])) {
            return $this->spam_types[$type_gid];
        } else {
            return false;
        }
    }

    /**
     * Get spam alert by ID
     * @param integer $id spam alert ID
     * @param 
     */
    public function get_alert_by_id($id, $formatted = false)
    {
        $id = intval($id);

        $this->DB->select(implode(", ", $this->fields));
        $this->DB->from(SPAM_ALERTS_TABLE);
        $this->DB->where("id", $id);

        //_compile_select;
        $result = $this->DB->get()->result();
        if (!empty($result)) {
            $rt = get_object_vars($result[0]);
            if ($formatted) {
                $rt = $this->format_alert(array($rt));
            }
            return $rt[0];
        } else {
            return false;
        }
    }

    /**
     * Save alert
     * @param array $data
     * @return boolean
     */
    public function save_alert($id, $data)
    {
        if (!$id) {
            $data["date_add"] = date("Y-m-d H:i:s");
            $this->DB->insert(SPAM_ALERTS_TABLE, $data);
            $id = $this->DB->insert_id();

            $type = $this->Spam_type_model->get_type_by_id($data['id_type']);
            $save_data['obj_count'] = $type["obj_count"] + 1;
            $save_data['obj_need_approve'] = $type["obj_need_approve"] + 1;
            $this->CI->Spam_type_model->save_type($type['id'], $save_data);
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
            $this->DB->update(SPAM_ALERTS_TABLE, $data);
        }
        return $id;
    }

    /**
     * Remove alert by ID
     * @param integer $id alert ID
     * @param boolean $remove_object
     */
    public function delete_alert($id, $remove_object = false)
    {
        $alert = $this->get_alert_by_id($id, true);
        if (!$alert) {
            return "";
        }

        if ($remove_object && $alert["type"]["module"] && $alert["type"]["model"] && $alert["type"]["callback"]) {
            try {
                $this->CI->load->model($alert["type"]["module"] . "/models/" . $alert["type"]["model"], $alert["type"]["model"], true);
                $error = $this->CI->{$alert["type"]["model"]}->{$alert["type"]["callback"]}("delete", $alert["id_object"]);
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }

            if ($error && $error != "removed") {
                return $error;
            }
        }

        $type = $alert["type"];
        if ($alert["spam_status"] == "none") {
            $save_data["obj_need_approve"] = $type["obj_need_approve"] - 1;
        }
        $save_data["obj_count"] = $type["obj_count"] - 1;
        $this->CI->Spam_type_model->save_type($type['id'], $save_data);

        $this->DB->where("id", $id);
        $this->DB->delete(SPAM_ALERTS_TABLE);
        return "";
    }

    /**
     * Return alerts as array
     * @param integer $page
     * @param string $limits 
     * @param array $order_by
     * @param array $params
     * @return array
     */
    private function _get_alerts_list($page = null, $limits = null, $order_by = null, $params = array())
    {
        $this->DB->select(implode(", ", $this->fields));
        $this->DB->from(SPAM_ALERTS_TABLE);

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

        if (is_array($order_by) && count($order_by) > 0) {
            foreach ($order_by as $field => $dir) {
                if (in_array($field, $this->fields)) {
                    $this->DB->order_by($field . " " . $dir);
                }
            }
        } else if ($order_by) {
            $this->DB->order_by($order_by);
        }

        if (!is_null($page)) {
            $page = intval($page) ? intval($page) : 1;
            $this->DB->limit($limits, $limits * ($page - 1));
        }

        $results = $this->DB->get()->result_array();
        if (!empty($results) && is_array($results)) {
            foreach ($results as $r) {
                $data[] = $r;
            }
            return $this->format_alert($data);
        }
        return array();
    }

    /**
     * Return alerts as array
     * @param array $params
     * @return integer
     */
    private function _get_alerts_count($params = null)
    {
        $this->DB->select("COUNT(*) AS cnt");
        $this->DB->from(SPAM_ALERTS_TABLE);

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

        $results = $this->DB->get()->result_array();
        if (!empty($results) && is_array($results)) {
            return intval($results[0]["cnt"]);
        }
        return 0;
    }

    /**
     * Return alerts as array
     * @param integer $page
     * @param string $limits 
     * @param array $order_by
     * @param string $type_gid
     * @return array
     */
    public function get_alerts_list($page = null, $limits = null, $order_by = null, $type_gid = null)
    {
        $params = array();
        if ($type_gid) {
            $type = $this->get_type_by_gid($type_gid);
            $params["where"]["id_type"] = $type["id"];
        }
        return $this->_get_alerts_list($page, $limits, $order_by, $params);
    }

    /**
     * Return number of alerts
     * @param string $type_gid
     * @return integer
     */
    public function get_alerts_count($type_gid = null)
    {
        $params = array();
        if ($type_gid) {
            $type = $this->get_type_by_gid($type_gid);
            $params["where"]["id_type"] = $type["id"];
        }
        return $this->_get_alerts_count($params);
    }

    /**
     * Validate alert
     * @param integer $id
     * @param array $data
     * @return array
     */
    public function validate_alert($id, $data)
    {
        $return = array("errors" => array(), "data" => array());

        if (isset($data["id_type"])) {
            $return['data']['id_type'] = strval($data["id_type"]);
            if (empty($return['data']['id_type'])) {
                $return["errors"][] = l("error_empty_type", "spam");
            } else {
                $type = $this->Spam_type_model->get_type_by_id($return['data']['id_type']);
                if (!$type) {
                    $return["errors"][] = l("error_invalid_type", "spam");
                } else {
                    $return["data"]["id_type"] = $type["id"];
                }
            }
        } elseif (!$id) {
            $return["errors"][] = l("error_empty_type", "spam");
        }

        if (isset($data["id_object"])) {
            $return["data"]["id_object"] = intval($data['id_object']);
            if (empty($data["id_object"])) {
                $return["errors"][] = l("error_empty_object", "spam");
            }
        } elseif (!$id) {
            $return["errors"][] = l("error_empty_object", "spam");
        }

        if (isset($data["id_poster"])) {
            $return["data"]["id_poster"] = intval($data['id_poster']);
            if (empty($data["id_poster"])) {
                $return["errors"][] = l("error_empty_poster", "spam");
            } else {
                if ($type && $type["gid"] && $return["data"]["id_object"]) {
                    $check = $this->is_alert_from_poster($type["gid"], $return["data"]["id_poster"], $return["data"]["id_object"]);
                    if ($check) {
                        $return["errors"][] = l("error_alert_from_poster", "spam");
                    }
                }
            }
        } elseif (!$id) {
            $return["errors"][] = l("error_empty_poster", "spam");
        }

        if (isset($data["id_reason"])) {
            $return["data"]["id_reason"] = intval($data["id_reason"]);
            if ($type && $type["gid"] == "select_text") {
                if (!empty($return["data"]["id_reason"])) {
                    $return["errors"][] = l("error_empty_reason", "spam");
                }
            }
        }

        if (isset($data["message"])) {
            $return["data"]["message"] = trim(strip_tags($data["message"]));
            if (!empty($return["data"]["message"])) {
                $this->CI->load->model("moderation/models/Moderation_badwords_model");
                $bw_count = $this->CI->Moderation_badwords_model->check_badwords($this->moderation_type, $return["data"]["message"]);
                if ($bw_count) {
                    $return["errors"][] = l("error_badwords_message", "spam");
                }
            }
        }

        if (isset($data["spam_status"])) {
            $return["data"]["spam_status"] = intval($data["spam_status"]);
        }

        return $return;
    }

    /**
     * Ban alert
     * @param integer $id
     * @return boolean
     */
    public function ban_alert($id)
    {
        $alert = $this->get_alert_by_id($id, true);
        if (!$alert) {
            return false;
        }
        if (!$alert["type"]["module"] || !$alert["type"]["model"] || !$alert["type"]["callback"]) {
            return false;
        }

        try {
            $this->CI->load->model($alert["type"]["module"] . "/models/" . $alert["type"]["model"], $alert["type"]["model"], true);
            $status = $this->CI->{$alert["type"]["model"]}->{$alert["type"]["callback"]}("ban", $alert["id_object"]);
        } catch (\Exception $e) {
            $status = "removed";
        }

        if (!in_array($status, $this->spam_status_arr)) {
            return $status;
        }
        if ($status == "removed") {
            $this->delete_alert($id);
            return l('success_removed_alert', 'spam');
        } else {
            $type = $alert["type"];
            if ($alert["spam_status"] == "none") {
                $save_data["obj_need_approve"] = $type["obj_need_approve"] - 1;
                $this->CI->Spam_type_model->save_type($type['id'], $save_data);
            }

            //update		
            $data["spam_status"] = $status;
            $this->save_alert($id, $data);
            return $data;
        }
    }

    /**
     * Unban alert
     * @param integer $id
     * @return array/string
     */
    public function unban_alert($id)
    {
        $alert = $this->get_alert_by_id($id, true);
        if (!$alert) {
            return false;
        }
        if (!$alert["type"]["module"] || !$alert["type"]["model"] || !$alert["type"]["callback"]) {
            return false;
        }

        try {
            $this->CI->load->model($alert["type"]["module"] . "/models/" . $alert["type"]["model"], $alert["type"]["model"], true);
            $status = $this->CI->{$alert["type"]["model"]}->{$alert["type"]["callback"]}("unban", $alert["id_object"]);
        } catch (\Exception $e) {
            $status = "removed";
        }

        if (!in_array($status, $this->spam_status_arr)) {
            throw new \Exception($status);
        }

        $this->delete_alert($id);

        return $status;
    }

    /**
     * Unban alert
     * @param integer $id
     * @return array/string
     */
    public function delete_content($id)
    {
        $alert = $this->get_alert_by_id($id, true);
        if (!$alert) {
            return false;
        }
        if (!$alert["type"]["module"] || !$alert["type"]["model"] || !$alert["type"]["callback"]) {
            return false;
        }

        try {
            $this->CI->load->model($alert["type"]["module"] . "/models/" . $alert["type"]["model"], $alert["type"]["model"], true);
            $status = $this->CI->{$alert["type"]["model"]}->{$alert["type"]["callback"]}("delete", $alert["id_object"]);
        } catch (\Exception $e) {
            $status = "removed";
        }

        if (!in_array($status, $this->spam_status_arr)) {
            throw new \Exception($status);
        }

        $this->delete_alert($id);

        return $status;
    }

    /**
     * Check exists alert to object from porter
     * @param string $type_gid
     * @param integer $poster_id
     * @param integer $object_id
     * @return boolean
     */
    public function is_alert_from_poster($type_gid, $poster_id, $object_id)
    {
        $type = $this->get_type_by_gid($type_gid);
        $params = array();
        $params["where"]["id_type"] = $type["id"];
        $params["where"]["id_poster"] = $poster_id;
        $params["where"]["id_object"] = $object_id;
        return $this->_get_alerts_count($params) > 0;
    }

    /**
     * Set format settings
     * @param array $data
     * @return array
     */
    public function set_format_settings($data)
    {
        foreach ($data as $key => $value) {
            if (isset($this->format_settings[$key])) {
                $this->format_settings[$key] = $value;
            }
        }
    }

    /**
     * Format alert
     * @param array $data
     * @return array
     */
    public function format_alert($data)
    {
        if (!$this->format_settings["use_format"]) {
            return $data;
        }

        $types_is_loaded = false;

        $users_search = array();
        $types_search = array();
        $contents_search = array();
        $links_search = array();
        $objects_search = array();
        $reasons_search = array();
        $subpost_search = array();
        $deletelinks_search = array();

        foreach ($data as $key => $alert) {
            $data[$key] = $alert;
            //get_poster
            if ($this->format_settings["get_poster"]) {
                $users_search[] = $alert["id_poster"];
            }
            //get form
            if ($this->format_settings["get_type"]) {
                $types_search[] = $alert["id_type"];
            }
            //get content
            if ($this->format_settings["get_content"]) {
                $contents_search[$alert["id_type"]][] = $alert["id_object"];
            }
            //get link
            if ($this->format_settings["get_link"]) {
                $links_search[$alert["id_type"]][] = $alert["id_object"];
            }
            //get deletelink
            if ($this->format_settings["get_deletelink"]) {
                $deletelinks_search[$alert["id_type"]][] = $alert["id_object"];
            }
            //get object
            if ($this->format_settings["get_object"]) {
                $objects_search[$alert["id_type"]][] = $alert["id_object"];
            }
            //get object
            if ($this->format_settings["get_subpost"]) {
                $subpost_search[$alert["id_type"]][] = $alert["id_object"];
            }
            //get reason
            if ($this->format_settings["get_reason"] && $alert["id_reason"]) {
                $reasons_search[] = $alert["id_reason"];
            }
            $data[$key]["message"] = nl2br($alert["message"]);
            $data[$key]["spam_status"] = in_array($data[$key]["spam_status"], $this->spam_status_arr) ?
                $data[$key]["spam_status"] : "none";
        }

        if ($this->format_settings["get_poster"] && !empty($users_search)) {
            $this->CI->load->model("Users_model");
            $users_data = $this->CI->Users_model->get_users_list_by_key(null, null, null, array(), $users_search);
            foreach ($data as $key => $alert) {
                $data[$key]["poster"] = (isset($users_data[$alert["id_poster"]])) ?
                    $users_data[$alert["id_poster"]] : $this->CI->Users_model->format_default_user($alert["id_poster"]);
            }
        }

        if ($this->format_settings["get_type"] && !empty($types_search)) {
            $types_data = $this->CI->Spam_type_model->get_types(0, $types_search);
            foreach ($types_data as $type_data) {
                $this->spam_types[$type_data["id"]] = $type_data;
                $this->spam_types[$type_data["gid"]] = $type_data;
            }
            //$this->spam_types[$type_data["id"]] = $type_data;
            //$this->spam_types[$type_gid] = $type_data;
            foreach ($data as $key => $alert) {
                $data[$key]["type"] = (isset($types_data[$alert["id_type"]])) ?
                    $types_data[$alert["id_type"]] : $this->CI->Spam_type_model->format_default_type($alert["id_type"]);
            }
            $types_is_loaded = true;
        }

        if ($this->format_settings["get_content"] && !empty($contents_search)) {
            if (!$types_is_loaded) {
                $types_data = $this->CI->Spam_type_model->get_types(0, $types_search);
                foreach ($types_data as $type_data) {
                    $this->spam_types[$type_data["id"]] = $type_data;
                    $this->spam_types[$type_data["gid"]] = $type_data;
                }
                $types_is_loaded = true;
            }
            $contents_data = array();
            foreach ($this->spam_types as $type_data) {
                if (!isset($contents_search[$type_data["id"]]) || !$type_data["module"] || !$type_data["model"] || !$type_data["callback"]) {
                    continue;
                }

                try {
                    $this->CI->load->model($type_data["module"] . "/models/" . $type_data["model"], $type_data["model"], true);
                    $contents_data[$type_data["id"]] = $this->CI->{$type_data["model"]}->{$type_data["callback"]}(
                        "get_content", $contents_search[$type_data["id"]]);
                } catch (\Exception $e) {
                    
                }
            }
            foreach ($data as $key => $alert) {
                $data[$key]["content"] = isset($contents_data[$alert["id_type"]][$alert["id_object"]]) ?
                    $contents_data[$alert["id_type"]][$alert["id_object"]] : "";
            }
        }

        if ($this->format_settings["get_link"] && !empty($links_search)) {
            if (!$types_is_loaded) {
                $types_data = $this->CI->Spam_type_model->get_types(0, $types_search);
                foreach ($types_data as $type_data) {
                    $this->spam_types[$type_data["id"]] = $type_data;
                    $this->spam_types[$type_data["gid"]] = $type_data;
                }
                $types_is_loaded = true;
            }
            $links_data = array();
            foreach ($this->spam_types as $type_data) {
                if (!isset($links_search[$type_data["id"]]) || !$type_data["module"] || !$type_data["model"] || !$type_data["callback"]) {
                    continue;
                }

                try {
                    $this->CI->load->model($type_data["module"] . "/models/" . $type_data["model"], $type_data["model"], true);
                    $links_data[$type_data["id"]] = $this->CI->{$type_data["model"]}->{$type_data["callback"]}(
                        "get_link", $links_search[$type_data["id"]]);
                } catch (\Exception $e) {
                    
                }
            }
            foreach ($data as $key => $alert) {
                $data[$key]["link"] = isset($links_data[$alert["id_type"]][$alert["id_object"]]) ?
                    $links_data[$alert["id_type"]][$alert["id_object"]] : "";
            }
        }

        if ($this->format_settings["get_deletelink"] && !empty($deletelinks_search)) {
            if (!$types_is_loaded) {
                $types_data = $this->CI->Spam_type_model->get_types(0, $types_search);
                foreach ($types_data as $type_data) {
                    $this->spam_types[$type_data["id"]] = $type_data;
                    $this->spam_types[$type_data["gid"]] = $type_data;
                }
                $types_is_loaded = true;
            }
            $links_data = array();
            foreach ($this->spam_types as $type_data) {
                if (!isset($deletelinks_search[$type_data["id"]]) || !$type_data["module"] || !$type_data["model"] || !$type_data["callback"]) {
                    continue;
                }
                try {
                    $this->CI->load->model($type_data["module"] . "/models/" . $type_data["model"], $type_data["model"], true);
                    $deletelinks_data[$type_data["id"]] = $this->CI->{$type_data["model"]}->{$type_data["callback"]}(
                        "get_deletelink", $deletelinks_search[$type_data["id"]]);
                } catch (\Exception $e) {
                    
                }
            }

            foreach ($data as $key => $alert) {
                $data[$key]["delete_link"] = isset($deletelinks_data[$alert["id_type"]][$alert["id_object"]]) ?
                    $deletelinks_data[$alert["id_type"]][$alert["id_object"]] : "";
            }
        }

        if ($this->format_settings["get_object"] && !empty($objects_search)) {
            if (!$types_is_loaded) {
                $types_data = $this->CI->Spam_type_model->get_types(0, $types_search);
                foreach ($types_data as $type_data) {
                    $this->spam_types[$type_data["id"]] = $type_data;
                    $this->spam_types[$type_data["gid"]] = $type_data;
                }
                $types_is_loaded = true;
            }
            $objects_data = array();
            foreach ($this->spam_types as $type_data) {
                if (!isset($objects_search[$type_data["id"]]) || !$type_data["module"] || !$type_data["model"] || !$type_data["callback"]) {
                    continue;
                }
                try {
                    $this->CI->load->model($type_data["module"] . "/models/" . $type_data["model"], $type_data["model"]);
                    $objects_data[$type_data["id"]] = $this->CI->{$type_data["model"]}->{$type_data["callback"]}(
                        "get_object", $objects_search[$type_data["id"]]);
                } catch (\Exception $e) {
                    
                }
            }
            foreach ($data as $key => $alert) {
                $data[$key]["object"] = isset($objects_data[$alert["id_type"]][$alert["id_object"]]) ?
                    $objects_data[$alert["id_type"]][$alert["id_object"]] : "";
            }
        }

        if ($this->format_settings["get_subpost"] && !empty($subpost_search)) {
            if (!$types_is_loaded) {
                $types_data = $this->CI->Spam_type_model->get_types(0, $types_search);
                foreach ($types_data as $type_data) {
                    $this->spam_types[$type_data["id"]] = $type_data;
                    $this->spam_types[$type_data["gid"]] = $type_data;
                }
                $types_is_loaded = true;
            }
            $subpost_data = array();
            foreach ($this->spam_types as $type_data) {
                if (!isset($subpost_search[$type_data["id"]]) || !$type_data["module"] || !$type_data["model"] || !$type_data["callback"]) {
                    continue;
                }
                try {
                    $this->CI->load->model($type_data["module"] . "/models/" . $type_data["model"], $type_data["model"]);
                    $subpost_data[$type_data["id"]] = $this->CI->{$type_data["model"]}->{$type_data["callback"]}(
                        "get_subpost", $subpost_search[$type_data["id"]]);
                } catch (\Exception $e) {
                    
                }
            }
            foreach ($data as $key => $alert) {
                $data[$key]["subpost"] = isset($subpost_data[$alert["id_type"]][$alert["id_object"]]) ?
                    $subpost_data[$alert["id_type"]][$alert["id_object"]] : "";
            }
        }

        if ($this->format_settings["get_reason"] && !empty($reasons_search)) {
            $this->CI->load->model("spam/models/Spam_reason_model");

            $lang_id = $this->CI->pg_language->current_lang_id;

            $reference = $this->pg_language->ds->get_reference($this->CI->Spam_reason_model->module_gid, $this->CI->Spam_reason_model->content[0], $lang_id);
            foreach ($data as $key => $alert) {
                $data[$key]["reason"] = isset($reference["option"][$alert["id_reason"]]) ?
                    $reference["option"][$alert["id_reason"]] : "";
            }
        }

        return $data;
    }

    /**
     * Mark alert as read
     * @param integer $id
     * @return void
     */
    public function mark_alert_as_read($id)
    {
        $data["mark"] = "1";
        $this->save_alert((int) $id, $data);
        return;
    }

    /**
     * Mark contact as unread
     * @param integer $id
     * @return void
     */
    public function mark_alert_as_unread($id)
    {
        $data["mark"] = "0";
        $this->save_alert((int) $id, $data);
        return;
    }

}
