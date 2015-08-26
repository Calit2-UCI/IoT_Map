<?php

/**
 * Spam admin side controller
 * 
 * @package PG_RealEstate
 * @subpackage Spam
 * @category	controllers
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * */
class Admin_Spam extends Controller
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Menu_model");
        $this->Menu_model->set_menu_active_item("admin_menu", "system-items");
        $this->system_messages->set_data("header", l("admin_header_spam", "spam"));
    }

    /**
     * Render alerts list page
     * @param string $filter
     * @param string $order
     * @param string $order_direction
     * @param integer $page
     */
    public function index($filter = "", $order = null, $order_direction = null, $page = 1)
    {
        $this->load->model("spam/models/Spam_alert_model");
        $this->load->model("spam/models/Spam_type_model");

        $this->Menu_model->set_menu_active_item("admin_spam_menu", "spam_alerts_item");

        $spam_types = $this->Spam_type_model->get_types(1);
        $types_count = count($spam_types);
        if (!$types_count) {
            $this->template_lite->view("alerts_list");
            return;
        }

        $current_settings = isset($_SESSION["spam_alerts_list"]) ? $_SESSION["spam_alerts_list"] : array();
        if (!isset($current_settings["filter"])) {
            $type = current($spam_types);
            $current_settings["filter"] = $type["gid"];
        }
        if (!isset($current_settings["order"])) {
            $current_settings["order"] = "date_add";
        }
        if (!isset($current_settings["order_direction"])) {
            $current_settings["order_direction"] = "DESC";
        }
        if (!$filter) {
            $filter = $current_settings["filter"];
        }
        if (!$order) {
            $order = $current_settings["order"];
        }
        if (!$order_direction) {
            $order_direction = $current_settings["order_direction"];
        }

        $current_settings["filter"] = $filter;
        $current_settings["order"] = $order;
        $current_settings["order_direction"] = $order_direction;
        $current_settings["page"] = $page;

        $this->template_lite->assign("order", $order);
        $this->template_lite->assign("order_direction", $order_direction);

        $alerts_count = $this->Spam_alert_model->get_alerts_count($filter);

        if (!$page) {
            $page = $current_settings["page"];
        }
        $items_on_page = $this->pg_module->get_module_config("start", "admin_items_per_page");

        $this->load->helper("sort_order");
        $page = get_exists_page_number($page, $alerts_count, $items_on_page);
        $current_settings["page"] = $page;

        $sort_links = array(
            "poster" => site_url() . "admin/spam/index/" . $filter . "/poster/" . (($order != "poster" xor $order_direction == "DESC") ? "ASC" : "DESC"),
            //"spam_status" => site_url() . "admin/spam/index/".$filter."/spam_status/".(($order != "spam_status" xor $order_direction == "DESC") ? "ASC" : "DESC"),
            "date_add" => site_url() . "admin/spam/index/" . $filter . "/date_add/" . (($order != "date_add" xor $order_direction == "DESC") ? "ASC" : "DESC"),
        );

        $this->template_lite->assign("sort_links", $sort_links);

        $_SESSION["spam_alerts_list"] = $current_settings;

        if ($alerts_count > 0) {
            $this->Spam_alert_model->set_format_settings(array("get_reason" => true));
            if ($order === "poster") {
                $order = "id_poster";
            }
            $alerts = $this->Spam_alert_model->get_alerts_list($page, $items_on_page, array($order => $order_direction), $filter);
            $this->template_lite->assign("alerts", $alerts);
        }

        $this->load->helper("navigation");
        $this->config->load("date_formats", TRUE);
        $url = site_url() . "admin/spam/index/" . $filter . "/" . $order . "/" . $order_direction . "/";
        $page_data = get_admin_pages_data($url, $alerts_count, $items_on_page, $page, "briefPage");
        $page_data["date_format"] = $this->config->item("st_format_date_time_literal", "date_formats");
        $this->template_lite->assign("page_data", $page_data);

        $this->template_lite->assign("spam_types", $spam_types);
        $this->template_lite->assign("spam_types_count", $types_count);

        $this->template_lite->assign("filter", $filter);

        $this->Menu_model->set_menu_active_item("admin_spam_menu", "spam_alerts_item");

        $this->template_lite->view("alerts_list");
    }

    /**
     * Render view alert page
     * @param integer $id
     * @return void
     */
    public function alerts_show($id)
    {
        $this->load->model("spam/models/Spam_alert_model");
        $this->Spam_alert_model->set_format_settings(array("get_link" => true, "get_object" => true, "get_reason" => true, "get_deletelink" => true, "get_subpost" => true));
        $data = $this->Spam_alert_model->get_alert_by_id($id, true);
        $this->template_lite->assign("data", $data);

        $this->Spam_alert_model->mark_alert_as_read($id);

        $this->load->helper("navigation");
        $this->config->load("date_formats", TRUE);
        $date_format = $this->config->item("st_format_date_time_literal", "date_formats");
        $this->template_lite->assign("date_format", $date_format);
        $this->system_messages->set_data("header", l("admin_header_alerts_show", "spam"));
        $this->template_lite->view("alerts_view");
    }

    /**
     * Remove spam alert action
     * @param string $action
     * @param integer $ids
     */
    public function alerts_delete($action = "without_object", $ids = null)
    {
        $errors = false;
        $messages = array();
        if (!$ids) {
            $ids = $this->input->post("ids");
        }

        if (!empty($ids)) {
            $this->load->model("spam/models/Spam_alert_model");
            foreach ((array) $ids as $id) {
                $error = $this->Spam_alert_model->delete_alert($id, ($action == "with_object"));
                if ($error) {
                    $errors = true;
                    $messages[] = $error;
                } else {
                    $messages[] = l("success_deleted_alert", "spam");
                }
            }
            if ($errors) {
                $this->system_messages->add_message("error", implode("<br>", $messages));
            } else {
                $this->system_messages->add_message("success", implode("<br>", $messages));
            }
        }
        //$current_settings = $_SESSION["spam_alerts_list"];
        redirect(site_url() . "admin/spam/index");
    }

    /**
     * Ban spam alert action
     * @param integer $ids
     */
    public function alerts_ban($ids = null)
    {
        $errors = false;
        $messages = array();
        if (!$ids) {
            $ids = $this->input->post("ids");
        }
        if (!empty($ids)) {
            $this->load->model("spam/models/Spam_alert_model");
            foreach ((array) $ids as $id) {
                $data = $this->Spam_alert_model->ban_alert($id);
                if (is_array($data)) {
                    $messages[] = l("success_" . $data["spam_status"] . "_alert", "spam");
                } else {
                    $errors = true;
                    $messages[] = $data;
                }
            }
            if ($errors) {
                $this->system_messages->add_message("error", implode("<br>", $messages));
            } else {
                $this->system_messages->add_message("success", implode("<br>", $messages));
            }
        }
        redirect(site_url() . "admin/spam/index");
    }

    /**
     * Unban spam alert action
     * @param integer $ids
     */
    public function alerts_unban($ids = null)
    {
        $errors = false;
        $messages = array();
        if (!$ids) {
            $ids = $this->input->post("ids");
        }
        if (!empty($ids)) {
            $this->load->model("spam/models/Spam_alert_model");
            foreach ((array) $ids as $id) {
                try {
                    $status = $this->Spam_alert_model->unban_alert($id);
                    $messages[] = l("success_" . $status . "_alert", "spam");
                } catch (Exception $e) {
                    $errors = true;
                    $messages[] = $e->getMessage();
                }
            }
            if ($errors) {
                $this->system_messages->add_message("error", implode("<br>", $messages));
            } else {
                $this->system_messages->add_message("success", implode("<br>", $messages));
            }
        }
        //$current_settings = $_SESSION["spam_alerts_list"];
        redirect(site_url() . "admin/spam/index");
    }

    /**
     * Unban spam alert action
     * @param integer $id
     */
    public function delete_content($id = null)
    {
        $errors = false;
        $messages = array();

        if (!empty($id)) {
            $this->load->model("spam/models/Spam_alert_model");
            try {
                $status = $this->Spam_alert_model->delete_content($id);
                $messages[] = l("success_" . $status . "_content", "spam");
            } catch (Exception $e) {
                $errors = true;
                $messages[] = $e->getMessage();
            }
            if ($errors) {
                $this->system_messages->add_message("error", implode("<br>", $messages));
            } else {
                $this->system_messages->add_message("success", implode("<br>", $messages));
            }
        }
        $this->Spam_alert_model->delete_alert($id);
        //$current_settings = $_SESSION["spam_alerts_list"];
        redirect(site_url() . "admin/spam/index");
    }

    /**
     * Render spam types list page
     */
    public function types()
    {
        $this->load->model("spam/models/Spam_type_model");

        $types = $this->Spam_type_model->get_types();
        $this->template_lite->assign("types", $types);

        $this->Menu_model->set_menu_active_item("admin_spam_menu", "spam_types_item");
        $this->template_lite->view("types_list");
    }

    /**
     * Render spam type edit page
     * @param integer $type_id
     */
    public function types_edit($type_id)
    {
        $this->load->model("spam/models/Spam_type_model");

        $data = $this->Spam_type_model->get_type_by_id($type_id);

        if ($this->input->post("btn_save")) {
            $post_data = $this->input->post("data", true);
            $validate_data = $this->Spam_type_model->validate_type($type_id, $post_data);
            if (!empty($validate_data["errors"])) {
                $return["error"] = implode("<br>", $validate_data["errors"]);
                $this->system_messages->add_message("success", l("success_updated_type", "spam"));
            } else {
                $this->Spam_type_model->save_type($type_id, $validate_data["data"]);
                $this->system_messages->add_message("success", l("success_updated_type", "spam"));
                $url = site_url() . "admin/spam/types";
                redirect($url);
            }

            $data = array_merge($data, $post_data);
        }

        $this->template_lite->assign("data", $data);
        $this->template_lite->assign("form_type_lang", ld("form_type", "spam"));

        $this->config->load("date_formats", TRUE);
        $date_format = $this->config->item("st_format_date_time_literal", "date_formats");
        $this->template_lite->assign("date_format", $date_format);

        $this->system_messages->set_data("header", l("admin_header_types_edit", "spam"));
        $this->Menu_model->set_menu_active_item("admin_spam_menu", "spam_types_item");
        $this->template_lite->view("types_edit");
    }

    /**
     * Activate spam type
     * @param integer $type_id spam type identifier
     * @param integer $status activity status
     */
    public function type_activate($type_id, $status = 0)
    {
        $this->load->model("spam/models/Spam_type_model");
        if (!empty($type_id)) {
            if ($status) {
                $this->Spam_type_model->activate_type($type_id, $status);
                $this->system_messages->add_message("success", l("success_type_activated", "spam"));
            } else {
                $this->Spam_type_model->activate_type($type_id, $status);
                $this->system_messages->add_message("success", l("success_type_deactivated", "spam"));
            }
        }
        $url = site_url() . "admin/spam/types/";
        redirect($url);
    }

    /**
     * Spam type send mail on/off
     * @param integer $type_id spam type identifier
     * @param integer $status on/off status
     */
    public function type_send_mail($type_id, $status = 0)
    {
        $this->load->model("spam/models/Spam_type_model");
        if (!empty($type_id)) {
            if ($status) {
                $this->Spam_type_model->send_mail_type($type_id, $status);
                $this->system_messages->add_message("success", l("success_type_send_form_on", "spam"));
            } else {
                $this->Spam_type_model->send_mail_type($type_id, $status);
                $this->system_messages->add_message("success", l("success_type_send_form_off", "spam"));
            }
        }
        $url = site_url() . "admin/spam/types/";
        redirect($url);
    }

    /**
     * Render reasons modules list page
     * @param integer $lang_id
     */
    public function reasons($lang_id = null)
    {
        $this->load->model("spam/models/Spam_reason_model");

        if (!$lang_id || !array_key_exists($lang_id, $this->pg_language->languages)) {
            $lang_id = $this->pg_language->current_lang_id;
        }

        $reference = $this->pg_language->ds->get_reference($this->Spam_reason_model->module_gid, $this->Spam_reason_model->content[0], $lang_id);

        $this->template_lite->assign("langs", $this->pg_language->languages);
        $this->template_lite->assign("current_lang_id", $lang_id);
        $this->template_lite->assign("reference", $reference);
        $this->template_lite->assign("module_gid", $this->Spam_reason_model->module_gid);

        $this->Menu_model->set_menu_active_item("admin_spam_menu", "spam_reasons_item");
        $this->template_lite->view("reasons_list");
    }

    /**
     * Render reason edit page
     * @param integer $lang_id
     * @param string $option_gid
     */
    public function reasons_edit($lang_id = null, $option_gid = null)
    {
        $this->load->model("spam/models/Spam_reason_model");

        if (!$lang_id || !array_key_exists($lang_id, $this->pg_language->languages)) {
            $lang_id = $this->pg_language->current_lang_id;
        }

        $reference = $this->pg_language->ds->get_reference($this->Spam_reason_model->module_gid, $this->Spam_reason_model->content[0], $lang_id);
        if ($option_gid) {
            $add_flag = false;
            foreach ($this->pg_language->languages as $lid => $lang) {
                $r = $this->pg_language->ds->get_reference($this->Spam_reason_model->module_gid, $this->Spam_reason_model->content[0], $lid);
                $lang_data[$lid] = $r["option"][$option_gid];
            }
        } else {
            $option_gid = "";
            $lang_data = array();
            $add_flag = true;
        }

        if ($this->input->post("btn_save")) {
            $lang_data = $this->input->post("lang_data", true);

            $validate_data = $this->Spam_reason_model->validate_reason($option_gid, $lang_data);
            if (!empty($validate_data["errors"])) {
                $this->system_messages->add_message('error', $validate_data["errors"]);
            } else {
                $this->Spam_reason_model->save_reason($option_gid, $validate_data["langs"]);
                if ($add_flag) {
                    $this->system_messages->add_message("success", l("success_created_reason", "spam"));
                } else {
                    $this->system_messages->add_message("success", l("success_updated_reason", "spam"));
                }
                $url = site_url() . "admin/spam/reasons/" . $lang_id;
                redirect($url);
            }
        }

        $this->template_lite->assign("lang_data", $lang_data);
        $this->template_lite->assign("langs", $this->pg_language->languages);
        $this->template_lite->assign("current_lang_id", $lang_id);
        $this->template_lite->assign("current_module_gid", $this->Spam_reason_model->module_gid);
        $this->template_lite->assign("option_gid", $option_gid);
        $this->template_lite->assign("add_flag", $add_flag);

        $this->system_messages->set_data("header", l("admin_header_reasons_edit", "spam"));
        $this->Menu_model->set_menu_active_item("admin_spam_menu", "spam_reasons_item");
        $this->template_lite->view("reasons_edit");
    }

    /**
     * Save sorting order of reasons options list
     */
    public function ajax_reasons_save_sorter()
    {
        $this->load->model("spam/models/Spam_reason_model");

        $sorter = $this->input->post("sorter");
        foreach ($sorter as $parent_str => $items_data) {
            foreach ($items_data as $item_str => $sort_index) {
                $option_gid = str_replace("item_", "", $item_str);
                $sorter_data[$sort_index] = $option_gid;
            }
        }

        if (empty($sorter_data)) {
            return;
        }
        ksort($sorter_data);
        $this->pg_language->ds->set_reference_sorter($this->Spam_reason_model->module_gid, $this->Spam_reason_model->content[0], $sorter_data);
        return;
    }

    /**
     * Remove reason from options list
     * @param string $option_id
     */
    public function ajax_reasons_delete($option_gid)
    {
        $this->load->model("spam/models/Spam_reason_model");

        if ($option_gid) {
            foreach ($this->pg_language->languages as $lid => $lang) {
                $reference = $this->pg_language->ds->get_reference($this->Spam_reason_model->module_gid, $this->Spam_reason_model->content[0], $lid);
                if (isset($reference["option"][$option_gid])) {
                    unset($reference["option"][$option_gid]);
                    $this->pg_language->ds->set_module_reference($this->Spam_reason_model->module_gid, $this->Spam_reason_model->content[0], $reference, $lid);
                }
            }
        }
        return;
    }

    /**
     * Render spam settings
     */
    public function settings()
    {
        $this->Menu_model->set_menu_active_item("admin_spam_menu", "spam_settings_item");

        if ($this->input->post("btn_save")) {
            $this->load->model("spam/models/Spam_type_model");

            $post_data["send_alert_to_email"] = $this->input->post("send_alert_to_email");

            $validate_data = $this->Spam_type_model->validate_settings($post_data);
            if (!empty($validate_data["errors"])) {
                $this->system_messages->add_message("error", $validate_data["errors"]);
                $data = $validate_data["data"];
            } else {
                foreach ($validate_data["data"] as $setting => $value) {
                    $this->pg_module->set_module_config("spam", $setting, $value);
                }
                $this->system_messages->add_message("success", l("success_settings_saved", "users"));
                $data = $validate_data["data"];
            }
        } else {
            $data["send_alert_to_email"] = $this->pg_module->get_module_config("spam", "send_alert_to_email");
        }

        $this->template_lite->assign("data", $data);
        $this->template_lite->view("settings");
    }

}
