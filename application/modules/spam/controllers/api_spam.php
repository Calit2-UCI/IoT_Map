<?php

/**
 * Spam api controller
 * 
 * @package PG_RealEstate
 * @subpackage Spam
 * @category controllers
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * */
class Api_Spam extends Controller
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

    public function get_reasons()
    {
        $this->load->model('spam/models/Spam_reason_model');
        $this->set_api_content(
            'reasons', 
            $this->Spam_reason_model->get_reason($this->pg_language->current_lang_id)
        );
    }

    /**
     * Mark object as spam 
     */
    public function mark_as_spam()
    {
        $this->load->model("spam/models/Spam_alert_model");

        if ($this->session->userdata("auth_type") != "user") {
            return;
        }

        $post_data = $this->input->post("data");
        $post_data["id_object"] = intval($this->input->post("object_id"));
        $post_data["id_type"] = $this->input->post("type_gid");
        $post_data["id_poster"] = intval($this->session->userdata("user_id"));

        $validate_data = $this->Spam_alert_model->validate_alert(null, $post_data);
        if (!empty($validate_data["errors"])) {
            $this->set_api_content("errors", $validate_data["errors"]);
            return;
        } else {
            $id = $this->Spam_alert_model->save_alert(null, $validate_data["data"]);
            $this->Spam_alert_model->set_format_settings(array("get_object" => true));
            $alert = $this->Spam_alert_model->get_alert_by_id($id, true);
            if ($alert["type"]["send_mail"]) {
                $email = $this->pg_module->get_module_config("spam", "send_alert_to_email");
                if ($email) {
                    $data = array(
                        "poster" => $alert["poster"]["output_name"],
                        "type" => $alert["type"]["output_name"],
                        "object" => $alert["object"],
                        "message" => $alert["message"],
                    );
                    //send mail
                    $this->load->model("Notifications_model");
                    $this->Notifications_model->send_notification($email, "spam_object", $data);
                }
            }
            $this->set_api_content("messages", l("success_created_alert", "spam"));
            return;
        }
    }

    /**
     * Get status of alert
     * @param integer $id
     */
    public function get_alert_status($id)
    {
        $this->load->model("spam/models/Spam_alert_model");
        $alert = $this->Spam_alert_model->get_alert_by_id($id, true);
        $this->set_api_content("messages", l("alert_" . $alert["spam_status"] . "_status", "spam"));
        return;
    }

}
