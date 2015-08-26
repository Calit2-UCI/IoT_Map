<?php

namespace Pg\Modules\Ausers\Models;

/**
 * Ausers module
 *
 * @package 	PG_Core
 * @copyright 	Copyright (c) 2000-2014 PG Core
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */

/**
 * Administrators install model
 *
 * @package 	PG_RealEstate
 * @subpackage 	Ausers
 * @category	models
 * @copyright 	Copyright (c) 2000-2014 PG Core
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
class Ausers_install_model extends \Model
{
    /**
	 * Link to CodeIgniter object
	 * 
	 * @var object
	 */
    protected $CI;
    
    /**
	 * Menu configuration
	 * 
	 * @var array
	 */
    private $menu = array(
        // admin menu
        'admin_menu' => array(
            'name' => 'Admin area menu',
            "action" => "none",
            "items" => array(
                'main_items' => array(
                    "action" => "none",
                    "items" => array(
                        'ausers_item' => array("action" => "create", 'link' => 'admin/ausers', 'icon' => 'eye', 'status' => 1, 'sorter' => 2),
                    )
                )
            )
        )
    );

    /**
	 * Notifications configuration
	 * 
	 * @var array
	 */
	protected $notifications = array(
		"templates" => array(
			array("gid"=>"auser_account_create_by_admin", "name"=>"Administrator created by admin mail", "vars"=>array("user", "email", "password", "user_type"), "content_type"=>"text"),
		),
		"notifications" => array(
			array("gid"=>"auser_account_create_by_admin", "template"=>"auser_account_create_by_admin", "send_type"=>"simple"),
		),
	);

    /**
	 * Constructor
	 *
	 * @return Ausers_install_model
	 */
    public function __construct()
    {
        parent::__construct();
        $this->CI = &get_instance();
        // load langs
        $this->CI->load->model('Install_model');
    }

    /**
	 * Form of module settings
	 * 
	 * @return array
	 */
    public function _validate_settings_form()
    {
        $errors = array();
        $data["name"] = $this->CI->input->post('name', true);
        $data["password"] = $this->CI->input->post('password', true);
        $data["email"] = $this->CI->input->post('email', true);
        $data["nickname"] = $this->CI->input->post('nickname', true);

        if (empty($data["name"])) {
            $errors[] = $this->CI->pg_language->get_string('ausers', 'error_name_incorrect');
        }

        $this->CI->config->load('reg_exps', TRUE);
        $login_expr = $this->CI->config->item('nickname', 'reg_exps');
        $password_expr = $this->CI->config->item('password', 'reg_exps');
        $email_expr = $this->CI->config->item('email', 'reg_exps');

        if (empty($data["nickname"]) || !preg_match($login_expr, $data["nickname"])) {
            $errors[] = $this->CI->pg_language->get_string('ausers', 'error_nickname_incorrect');
        }

        if (empty($data["password"]) || !preg_match($password_expr, $data["password"])) {
            $errors[] = $this->CI->pg_language->get_string('ausers', 'error_password_incorrect');
        }

        if (empty($data["email"]) || !preg_match($email_expr, $data["email"])) {
            $errors[] = $this->CI->pg_language->get_string('ausers', 'error_email_incorrect');
        }

        $return = array(
            "data" => $data,
            "errors" => $errors,
        );
        return $return;
    }

    /**
	 * Save module settings
	 * 
	 * @param array $data settings data
	 * @return void
	 */
    public function _save_settings_form($data)
    {
        $data["password"] = md5($data["password"]);
        $data["status"] = 1;
        $data["lang_id"] = 1;
        $data["user_type"] = "admin";

        $this->CI->load->model('Ausers_model');
        $this->CI->Ausers_model->save_user(null, $data);
        return;
    }

    /**
	 * Return settings form
	 * 
	 * @param boolean $submit turn on if it is submit
	 * @return string/false
	 */
    public function _get_settings_form($submit = false)
    {
        $data = array(
            "nickname" => "admin",
            "name" => "Administrator",
        );
        if ($submit) {
            $validate = $this->_validate_settings_form();
            if (!empty($validate["errors"])) {
                $this->CI->template_lite->assign('settings_errors', $validate["errors"]);
                $data = $validate["data"];
            } else {
                $this->_save_settings_form($validate["data"]);
                return false;
            }
        }

        $this->CI->template_lite->assign('settings_data', $data);
        $html = $this->CI->template_lite->fetch('install_settings_form', 'admin', 'ausers');
        return $html;
    }

    /**
	 * Install data of menu module
	 * 
	 * @return void
	 */
    public function install_menu()
    {
        $this->CI->load->model('Menu_model');
        $this->CI->load->helper('menu');

        foreach ($this->menu as $gid => $menu_data) {
            $this->menu[$gid]['id'] = linked_install_set_menu($gid, $menu_data["action"], $menu_data["name"]);
            linked_install_process_menu_items($this->menu, 'create', $gid, 0, $this->menu[$gid]["items"]);
        }
    }

    /**
	 * Import languages of menu module
	 * 
	 * @param array $langs_ids languages identifiers
	 * @return void
	 */
    public function install_menu_lang_update($langs_ids = null)
    {
        if (empty($langs_ids)) {
            return false;
        }
        $langs_file = $this->CI->Install_model->language_file_read('ausers', 'menu', $langs_ids);

        if (!$langs_file) {
            log_message('info', 'Empty menu langs data');
            return false;
        }

        $this->CI->load->model('Menu_model');
        $this->CI->load->helper('menu');

        foreach ($this->menu as $gid => $menu_data) {
            linked_install_process_menu_items($this->menu, 'update', $gid, 0, $this->menu[$gid]["items"], $gid, $langs_file);
        }
        return true;
    }

    /**
	 * Export languages of menu module
	 * 
	 * @param array $langs_ids languages identifiers
	 * @return array
	 */
    public function install_menu_lang_export($langs_ids = null)
    {
        if (empty($langs_ids)) {
            return false;
        }
        $this->CI->load->model('Menu_model');
        $this->CI->load->helper('menu');

        $return = array();
        foreach ($this->menu as $gid => $menu_data) {
            $temp = linked_install_process_menu_items($this->menu, 'export', $gid, 0, $this->menu[$gid]["items"], $gid, $langs_ids);
            $return = array_merge($return, $temp);
        }
        return array("menu" => $return);
    }

    /**
	 * Uninstall data of menu module
	 * 
	 * @return void
	 */
    public function deinstall_menu()
    {
        $this->CI->load->model('Menu_model');
        $this->CI->load->helper('menu');
        linked_install_delete_menu_items($menu_gid, $items);
        foreach ($this->menu as $gid => $menu_data) {
            if ($menu_data["action"] == "create") {
                linked_install_set_menu($gid, "delete");
            } else {
                linked_install_delete_menu_items($gid, $this->menu[$gid]["items"]);
            }
        }
    }
    
    /**
	 * Install data of notifications module
	 * 
	 * @return void
	 */
	public function install_notifications(){
		// add notification
		$this->CI->load->model("Notifications_model");
		$this->CI->load->model("notifications/models/Templates_model");

		$templates_ids = array();

		foreach((array)$this->notifications["templates"] as $template_data){
			if(is_array($template_data["vars"])) $template_data["vars"] = implode(", ", $template_data["vars"]);			
			$validate_data = $this->CI->Templates_model->validate_template(null, $template_data);
			if(!empty($validate_data["errors"])) continue;
			$templates_ids[$template_data['gid']] = $this->CI->Templates_model->save_template(null, $validate_data["data"]);			
		}
		
		foreach((array)$this->notifications["notifications"] as $notification_data){
			if(!isset($templates_ids[$notification_data["template"]])){
				 $template = $this->CI->Templates_model->get_template_by_gid($notification_data["template"]);
				 $templates_ids[$notification_data["template"]] = $template["id"];
			}
			$notification_data["id_template_default"] = $templates_ids[$notification_data["template"]];
			$validate_data = $this->CI->Notifications_model->validate_notification(null, $notification_data);
			if(!empty($validate_data["errors"])) continue;
			$this->CI->Notifications_model->save_notification(null, $validate_data["data"], $lang_data);
		}
	}
	
	/**
	 * Import languages of notifiactions module
	 * 
	 * @param array $langs_ids languages identifiers
	 * @return void
	 */
	public function install_notifications_lang_update($langs_ids=null){
		if(empty($langs_ids)) return false;
		$this->CI->load->model("Notifications_model");
		
		$langs_file = $this->CI->Install_model->language_file_read("ausers", "notifications", $langs_ids);
		if(!$langs_file){log_message("info", "Empty notifications langs data");return false;}
		
		$this->CI->Notifications_model->update_langs($this->notifications, $langs_file, $langs_ids);
		return true;
	}
	
	/**
	 * Export languages of notifications module
	 * 
	 * @param array $langs_ids languages identifiers
	 * @return array
	 */
	public function install_notifications_lang_export($langs_ids=null){
		$this->CI->load->model("Notifications_model");
		$langs = $this->CI->Notifications_model->export_langs($this->notifications, $langs_ids);
		return array("notifications" => $langs);
	}
	
	/**
	 * Unistall data of notifacations module
	 * 
	 * @return void
	 */
	public function deinstall_notifications(){
		$this->CI->load->model("Notifications_model");
		$this->CI->load->model("notifications/models/Templates_model");
		
		foreach((array)$this->notifications["notifications"] as $notification_data){
			$this->CI->Notifications_model->delete_notification_by_gid($notification_data["gid"]);
		}
		
		foreach((array)$this->notifications["templates"] as $template_data){
			$this->CI->Templates_model->delete_template_by_gid($template_data["gid"]);
		}
	}
}
