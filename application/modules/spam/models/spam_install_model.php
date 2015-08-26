<?php

namespace Pg\Modules\Spam\Models;

/**
 * Spam install model
 * 
 * @package PG_RealEstate
 * @subpackage Spam
 * @category	models
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * */
class Spam_install_model extends \Model
{

    /**
     * Link to CodeIgniter object
     * @var object
     */
    protected $CI;

    /**
     * Menu configuration
     * @var array
     */
    protected $menu = array(
        "admin_menu" => array(
            "action" => "none",
            "items" => array(
                "settings_items" => array(
                    "action" => "none",
                    "items" => array(
                        'system-items' => array(
                            "action" => "none",
                            "items" => array(
                                "spam_sett_menu_item" => array("action" => "create", "link" => "admin/spam/types", "status" => 1, "sorter" => 7),
                            ),
                        )
                    ),
                ),
            ),
        ),
        "admin_spam_menu" => array(
            "action" => "create",
            "name" => "Admin mode - Content - Spam",
            "items" => array(
                "spam_alerts_item" => array("action" => "create", "link" => "admin/spam/index", "status" => 1, "sorter" => 1),
                "spam_types_item" => array("action" => "create", "link" => "admin/spam/types", "status" => 1, "sorter" => 2),
                "spam_reasons_item" => array("action" => "create", "link" => "admin/spam/reasons", "status" => 1, "sorter" => 3),
                "spam_settings_item" => array("action" => "create", "link" => "admin/spam/settings", "status" => 1, "sorter" => 4),
            ),
        ),
    );

    /**
     * Notifications configuration
     * @var array
     */
    protected $notifications = array(
        "templates" => array(
            array("gid" => "spam_object", "name" => "Spam object", "vars" => array("type", "poster", "object_id", "reason", "message"), "content_type" => "text"),
        ),
        "notifications" => array(
            array("gid" => "spam_object", "template" => "spam_object", "send_type" => "simple"),
        ),
    );

    /**
     * Moderators configuration
     * @var array
     */
    protected $moderators = array(
        array("module" => "spam", "method" => "index", "is_default" => 1),
        array("module" => "spam", "method" => "types", "is_default" => 0),
        array("module" => "spam", "method" => "reasons", "is_default" => 0),
    );
    private $moderation_types = array(
        array(
            "name" => "spam",
            "mtype" => "-1",
            "module" => "spam",
            "model" => "Spam_alert_model",
            "check_badwords" => "1",
            "method_get_list" => "",
            "method_set_status" => "",
            "method_delete_object" => "",
            "allow_to_decline" => "0",
            "template_list_row" => "",
        )
    );

    /**
     * Constructor
     *
     * @return Install object
     */
    public function __construct()
    {
        parent::__construct();
        $this->CI = &get_instance();
    }

    /**
     * Install links to menu module
     */
    public function install_menu()
    {
        $this->CI->load->helper("menu");
        foreach ($this->menu as $gid => $menu_data) {
            $this->menu[$gid]["id"] = linked_install_set_menu($gid, $menu_data["action"], $menu_data["name"]);
            linked_install_process_menu_items($this->menu, "create", $gid, 0, $this->menu[$gid]["items"]);
        }
    }

    /**
     * Update languages
     * @param array $langs_ids
     */
    public function install_menu_lang_update($langs_ids = null)
    {
        if (empty($langs_ids)) {
            return false;
        }
        $langs_file = $this->CI->Install_model->language_file_read("spam", "menu", $langs_ids);

        if (!$langs_file) {
            log_message("info", "Empty menu langs data");
            return false;
        }

        $this->CI->load->helper("menu");

        foreach ($this->menu as $gid => $menu_data) {
            linked_install_process_menu_items($this->menu, "update", $gid, 0, $this->menu[$gid]["items"], $gid, $langs_file);
        }
        return true;
    }

    /**
     * Export languages
     * @param array $langs_ids
     */
    public function install_menu_lang_export($langs_ids)
    {
        if (empty($langs_ids)) {
            return false;
        }
        $this->CI->load->helper("menu");

        $return = array();
        foreach ($this->menu as $gid => $menu_data) {
            $temp = linked_install_process_menu_items($this->menu, "export", $gid, 0, $this->menu[$gid]["items"], $gid, $langs_ids);
            $return = array_merge($return, $temp);
        }
        return array("menu" => $return);
    }

    /**
     * Uninstall menu
     */
    public function deinstall_menu()
    {
        $this->CI->load->helper("menu");
        foreach ($this->menu as $gid => $menu_data) {
            if ($menu_data["action"] == "create") {
                linked_install_set_menu($gid, "delete");
            } else {
                linked_install_delete_menu_items($gid, $this->menu[$gid]["items"]);
            }
        }
    }

    /**
     * Install notifications links
     */
    public function install_notifications()
    {
        // add notification
        $this->CI->load->model("Notifications_model");
        $this->CI->load->model("notifications/models/Templates_model");

        $templates_ids = array();

        foreach ((array) $this->notifications["templates"] as $template_data) {
            if (is_array($template_data["vars"])) {
                $template_data["vars"] = implode(",", $template_data["vars"]);
            }

            $validate_data = $this->CI->Templates_model->validate_template(null, $template_data);
            if (!empty($validate_data["errors"])) {
                continue;
            }
            $templates_ids[$template_gid] = $this->CI->Templates_model->save_template(null, $validate_data["data"]);
        }

        foreach ((array) $this->notifications["notifications"] as $notification_data) {
            if (!isset($templates_ids[$notification_data["template"]])) {
                $template = $this->CI->Templates_model->get_template_by_gid($notification_data["template"]);
                $templates_ids[$notification_data["template"]] = $template["id"];
            }

            $notification_data["id_template_default"] = $templates_ids[$notification_data["template"]];

            $validate_data = $this->CI->Notifications_model->validate_notification(null, $notification_data);
            if (!empty($validate_data["errors"])) {
                continue;
            }
            $this->CI->Notifications_model->save_notification(null, $validate_data["data"]);
        }
    }

    /**
     * Import notifications languages
     * @param array $langs_ids
     */
    public function install_notifications_lang_update($langs_ids = null)
    {
        if (empty($langs_ids)) {
            return false;
        }

        $this->CI->load->model("Notifications_model");

        $langs_file = $this->CI->Install_model->language_file_read("spam", "notifications", $langs_ids);
        if (!$langs_file) {
            log_message("info", "Empty notifications langs data");
            return false;
        }

        $this->CI->Notifications_model->update_langs($this->notifications, $langs_file, $langs_ids);
        return true;
    }

    /**
     * Export notifications languages
     * @param array $langs_ids
     */
    public function install_notifications_lang_export($langs_ids = null)
    {
        $this->CI->load->model("Notifications_model");
        $langs = $this->CI->Notifications_model->export_langs($this->notifications, $langs_ids);
        return array("notifications" => $langs);
    }

    /**
     * Uninstall notifications links
     */
    public function deinstall_notifications()
    {
        //add notification
        $this->CI->load->model("Notifications_model");
        $this->CI->load->model("notifications/models/Templates_model");

        foreach ((array) $this->notifications["notifications"] as $notification_data) {
            $this->CI->Notifications_model->delete_notification_by_gid($notification_data["gid"]);
        }

        foreach ((array) $this->notifications["templates"] as $template_data) {
            $this->CI->Templates_model->delete_template_by_gid($template_data["gid"]);
        }
    }

    /**
     * Install moderators links
     */
    public function install_moderators()
    {
        //install moderators permissions
        $this->CI->load->model("Moderators_model");

        foreach ((array) $this->moderators as $method_data) {
            //$validate_data = $this->CI->Moderators_model->validate_method($method_data, true);
            $validate_data = array("errors" => array(), "data" => $method_data);
            if (!empty($validate_data["errors"])) {
                continue;
            }
            $this->CI->Moderators_model->save_method(null, $validate_data["data"]);
        }
    }

    /**
     * Import moderators languages
     * @param array $langs_ids
     */
    public function install_moderators_lang_update($langs_ids = null)
    {
        $langs_file = $this->CI->Install_model->language_file_read("spam", "moderators", $langs_ids);
        if (!$langs_file) {
            log_message("info", "Empty moderators langs data");
            return false;
        }

        // install moderators permissions
        $this->CI->load->model("Moderators_model");
        $params["where"]["module"] = "spam";
        $methods = $this->CI->Moderators_model->get_methods_lang_export($params);

        foreach ($methods as $method) {
            if (!empty($langs_file[$method["method"]])) {
                $this->CI->Moderators_model->save_method($method["id"], array(), $langs_file[$method["method"]]);
            }
        }
    }

    /**
     * Export moderators languages
     * @param array $langs_ids
     */
    public function install_moderators_lang_export($langs_ids)
    {
        $this->CI->load->model("Moderators_model");
        $params["where"]["module"] = "spam";
        $methods = $this->CI->Moderators_model->get_methods_lang_export($params, $langs_ids);
        foreach ($methods as $method) {
            $return[$method["method"]] = $method["langs"];
        }
        return array('moderators' => $return);
    }

    /**
     * Uninstall moderators links
     */
    public function deinstall_moderators()
    {
        $this->CI->load->model("Moderators_model");
        $params = array();
        $params["where"]["module"] = "spam";
        $this->CI->Moderators_model->delete_methods($params);
    }

    public function install_moderation()
    {
        // Moderation
        $this->CI->load->model('moderation/models/Moderation_type_model');
        foreach ($this->moderation_types as $mtype) {
            $mtype['date_add'] = date("Y-m-d H:i:s");
            $this->CI->Moderation_type_model->save_type(null, $mtype);
        }
    }

    public function install_moderation_lang_update($langs_ids = null)
    {
        if (!is_array($langs_ids)) {
            $langs_ids = (array) $langs_ids;
        }
        $langs_file = $this->CI->Install_model->language_file_read('spam', 'moderation', $langs_ids);

        if (!$langs_file) {
            log_message('info', 'Empty moderation langs data');
            return false;
        }
        $this->CI->load->model('moderation/models/Moderation_type_model');
        $this->CI->Moderation_type_model->update_langs($this->moderation_types, $langs_file);
    }

    public function install_moderation_lang_export($langs_ids = null)
    {
        if (!is_array($langs_ids)) {
            $langs_ids = (array) $langs_ids;
        }
        $this->CI->load->model('moderation/models/Moderation_type_model');
        return array('moderation' => $this->CI->Moderation_type_model->export_langs($this->moderation_types, $langs_ids));
    }

    public function deinstall_moderation()
    {
        $this->CI->load->model('moderation/models/Moderation_type_model');
        foreach ($this->moderation_types as $mtype) {
            $type = $this->CI->Moderation_type_model->get_type_by_name($mtype["name"]);
            $this->CI->Moderation_type_model->delete_type($type['id']);
        }
    }

    /**
     * Install model
     */
    public function _arbitrary_installing()
    {
        //get administrator email
        if ($this->pg_module->is_module_installed('moderators')) {
            $this->CI->load->model("Moderators_model");
            $users = $this->CI->Moderators_model->get_users_list(null, 1, null, array('where' => array('user_type' => 'admin')));
            if (!empty($users)) {
                $this->CI->pg_module->set_module_config("spam", 'send_alert_to_email', $users[0]["email"]);
            }
        }
    }

}
