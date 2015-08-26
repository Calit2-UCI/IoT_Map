<?php

/**
 * Spam user side controller
 * 
 * @package PG_RealEstate
 * @subpackage Spam
 * @category	controllers
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * */
class Spam extends Controller
{

    /**
     * Constructor
     *
     * @return Contact
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Mark object as spam 
     */
    public function ajax_mark_as_spam()
    {
        $this->load->model("spam/models/Spam_alert_model");

        if ($this->session->userdata("auth_type") != "user") {
            return;
        }

        $post_data = $this->input->post("data");
        $post_data["id_object"] = intval($this->input->post("object_id"));
        $post_data["id_type"] = $this->input->post("type_gid");
        $post_data["id_poster"] = intval($this->session->userdata("user_id"));
        $lang_id = $this->pg_language->get_default_lang_id();
        $is_owner = $this->input->post("is_owner", true);
        if ($is_owner) {
            $return["error"] = l('error_is_owner', 'spam');
            echo json_encode($return);
            return;
        }

        $validate_data = $this->Spam_alert_model->validate_alert(null, $post_data);
        if (!empty($validate_data["errors"])) {
            $return["error"] = implode("<br>", $validate_data["errors"]);
            echo json_encode($return);
            return;
        } else {
            $id = $this->Spam_alert_model->save_alert(null, $validate_data["data"]);
            $this->Spam_alert_model->set_format_settings(array("get_object" => true, 'get_reason' => true));
            $alert = $this->Spam_alert_model->get_alert_by_id($id, true);
            if ($alert["type"]["send_mail"]) {
                $email = $this->pg_module->get_module_config("spam", "send_alert_to_email");
                if ($email) {
                    $data = array(
                        "poster" => $alert["poster"]["output_name"],
                        "type" => l('stat_header_spam_' . $alert["type"]["output_name"], 'spam', $lang_id),
                        "object_id" => $alert["object"]['id'],
                        "reason" => $alert["reason"],
                        "message" => strip_tags($alert["message"]),
                    );
                    //send mail
                    $this->load->model("Notifications_model");
                    $this->Notifications_model->send_notification($email, "spam_object", $data, '', $lang_id);
                }
            }
            $return["success"] = l("success_created_alert", "spam");
            echo json_encode($return);
            return;
        }
    }

    /**
     * Get form to mark object as spam
     */
    public function ajax_get_form()
    {
        if ($this->session->userdata("auth_type") != "user") {
            return;
        }
        $this->load->model("spam/models/Spam_type_model");
        $this->load->model("spam/models/Spam_alert_model");

        $object_id = intval($this->input->post("object_id", true));
        $type_gid = $this->input->post("type_gid", true);

        $type = $this->Spam_type_model->get_type_by_id($type_gid, true);
        $this->template_lite->assign("data", $type);

        $is_owner = $this->input->post("is_owner", true);
        $this->template_lite->assign("is_spam_owner", $is_owner);
        $user_id = intval($this->session->userdata("user_id"));

        if (($this->Spam_alert_model->is_alert_from_poster($type_gid, $user_id, $object_id)) == 1) {
            exit("is_send");
        }

        if ($type["form_type"] == "select_text") {
            $this->load->model("spam/models/Spam_reason_model");
            $lang_id = $this->pg_language->current_lang_id;
            $reference = $this->pg_language->ds->get_reference($this->Spam_reason_model->module_gid, $this->Spam_reason_model->content[0], $lang_id);
            $this->template_lite->assign("reasons", $reference);
        }

        $this->template_lite->assign("object_id", $object_id);

        $this->template_lite->view("mark_as_spam_form", "user", "spam");
        return;
    }

}
