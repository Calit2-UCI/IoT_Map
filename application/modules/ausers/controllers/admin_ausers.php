<?php

/**
 * Ausers module
 *
 * @package 	PG_Core
 * @copyright 	Copyright (c) 2000-2014 PG Core
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */

/**
 * Administrators admin side controller
 * 
 * @package 	PG_Core
 * @subpackage 	Ausers
 * @category 	controllers
 * @copyright 	Copyright (c) 2000-2014 PG Core
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
Class Admin_Ausers extends Controller
{
    /**
	 * Constructor
	 * 
	 * @return Admin_Ausers
	 */
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Ausers_model");
        $this->load->model('Menu_model');
        $this->Menu_model->set_menu_active_item('admin_menu', 'ausers_item');
    }

    /**
	 * Get admninistrators list
	 * 
	 * @param string $filter filter data
	 * @param string $order sorting data
	 * @param string $order_direction sorting direction
	 * @param integer $page page of results
	 * @return void
	 */
    public function index($filter = "all", $order = "nickname", $order_direction = "ASC", $page = 1)
    {

        $attrs = array();
        $current_settings = isset($_SESSION["ausers_list"]) ? $_SESSION["ausers_list"] : array();
        if (!isset($current_settings["filter"])) {
            $current_settings["filter"] = "all";
        }
        if (!isset($current_settings["order"])) {
            $current_settings["order"] = "nickname";
        }
        if (!isset($current_settings["order_direction"])) {
            $current_settings["order_direction"] = "ASC";
        }
        if (!isset($current_settings["page"])) {
            $current_settings["page"] = 1;
        }

        $filter_data = array(
            "all" => $this->Ausers_model->get_users_count(),
            "not_active" => $this->Ausers_model->get_users_count(array("where" => array("status" => 0))),
            "active" => $this->Ausers_model->get_users_count(array("where" => array("status" => 1))),
            "admin" => $this->Ausers_model->get_users_count(array("where" => array("user_type" => 'admin'))),
            "moderator" => $this->Ausers_model->get_users_count(array("where" => array("user_type" => 'moderator'))),
        );

        switch ($filter) {
            case 'active' : $attrs["where"]['status'] = 1;
                break;
            case 'not_active' : $attrs["where"]['status'] = 0;
                break;
            case 'admin' : $attrs["where"]['user_type'] = 'admin';
                break;
            case 'moderator' : $attrs["where"]['user_type'] = 'moderator';
                break;
            default: $filter = $current_settings["filter"];
        }

        $this->template_lite->assign('filter', $filter);
        $this->template_lite->assign('filter_data', $filter_data);
        $current_settings["page"] = $page;

        if (!$order) {
            $order = $current_settings["order"];
        }
        $this->template_lite->assign('order', $order);
        $current_settings["order"] = $order;

        if (!$order_direction) {
            $order_direction = $current_settings["order_direction"];
        }
        $this->template_lite->assign('order_direction', $order_direction);
        $current_settings["order_direction"] = $order_direction;

        $users_count = $filter_data[$filter];

        if (!$page) {
            $page = $current_settings["page"];
        }
        $items_on_page = $this->pg_module->get_module_config('start', 'admin_items_per_page');
        $this->load->helper('sort_order');
        $page = get_exists_page_number($page, $users_count, $items_on_page);
        $current_settings["page"] = $page;

        $_SESSION["ausers_list"] = $current_settings;

        $sort_links = array(
            "name" => site_url() . "admin/ausers/index/" . $filter . "/name/" . (($order != 'name' xor $order_direction == 'DESC') ? 'ASC' : 'DESC'),
            "nickname" => site_url() . "admin/ausers/index/" . $filter . "/nickname/" . (($order != 'nickname' xor $order_direction == 'DESC') ? 'ASC' : 'DESC'),
            "email" => site_url() . "admin/ausers/index/" . $filter . "/email/" . (($order != 'email' xor $order_direction == 'DESC') ? 'ASC' : 'DESC'),
            "date_created" => site_url() . "admin/ausers/index/" . $filter . "/date_created/" . (($order != 'date_created' xor $order_direction == 'DESC') ? 'ASC' : 'DESC'),
        );

        $this->template_lite->assign('sort_links', $sort_links);

        if ($users_count > 0) {
            $users = $this->Ausers_model->get_users_list($page, $items_on_page, array($order => $order_direction), $attrs);
            $this->template_lite->assign('users', $users);
        }
        $this->load->helper("navigation");
        $url = site_url() . "admin/ausers/index/" . $filter . "/" . $order . "/" . $order_direction . "/";
        $page_data = get_admin_pages_data($url, $users_count, $items_on_page, $page, 'briefPage');
        $page_data["date_format"] = $this->pg_date->get_format('date_time_literal', 'st');
        $this->template_lite->assign('page_data', $page_data);

        $this->system_messages->set_data('header', l('admin_header_ausers_list', 'ausers'));
        $this->template_lite->view('list');
    }

    /**
	 * Edit administrator data
	 * 
	 * @param integer $user_id administrator identifier
	 * @return void
	 */
    public function edit($user_id = null)
    {
        if ($user_id) {
            $data = $this->Ausers_model->get_user_by_id($user_id);
        } else {
            $data["lang_id"] = $this->pg_language->current_lang_id;
        }
        if ($this->input->post('btn_save')) {

            $post_data = array(
                "name" => $this->input->post('name', true),
                "nickname" => $this->input->post('nickname', true),
                "password" => $this->input->post('password', true),
                "repassword" => $this->input->post('repassword', true),
                "update_password" => intval($this->input->post('update_password')),
                "email" => $this->input->post('email', true),
                "description" => $this->input->post('description', true),
                "user_type" => $this->input->post('user_type', true),
                "permission_data" => $this->input->post('permission_data', true),
                "lang_id" => intval($this->input->post('lang_id'))
            );
            $validate_data = $this->Ausers_model->validate_user($user_id, $post_data);
            if (!empty($validate_data["errors"])) {
                $this->system_messages->add_message('error', $validate_data["errors"]);
                $validate_data["data"]['permission_data'] = unserialize($validate_data["data"]['permission_data']);
                $data = array_merge($data, $validate_data["data"]);
            } else {
                $data = $validate_data["data"];
                $this->Ausers_model->save_user($user_id, $data, $post_data['password']);
                $this->system_messages->add_message('success', ($user_id) ? l('success_update_user', 'ausers') : l('success_add_user', 'ausers'));
                $current_settings = $_SESSION["ausers_list"];
                $url = site_url() . "admin/ausers/index/" . $current_settings["filter"] . "/" . $current_settings["order"] . "/" . $current_settings["order_direction"] . "/" . $current_settings["page"] . "";
                redirect($url);
            }
        }

        $this->template_lite->assign('langs', $this->pg_language->languages);
        $this->template_lite->assign('data', $data);

        $this->system_messages->set_data('header', l('admin_header_ausers_edit', 'ausers'));
        $this->template_lite->view('edit_form');
    }

    /**
	 * Remove administrator
	 * 
	 * @param integer $user_id administrator identifier
	 * @return void
	 */
    public function delete($user_id)
    {
        if (!empty($user_id)) {
            $this->Ausers_model->delete_user($user_id);
            $this->system_messages->add_message('success', l('success_delete_user', 'ausers'));
        }
        $current_settings = $_SESSION["ausers_list"];
        $url = site_url() . "admin/ausers/index/" . $current_settings["filter"] . "/" . $current_settings["order"] . "/" . $current_settings["order_direction"] . "/" . $current_settings["page"] . "";
        redirect($url);
    }

    /**
	 * Activate/de-activate administrator
	 * 
	 * @param integer $user_id administrator identifier
	 * @param integer $status administrator status
	 * @return void
	 */
    public function activate($user_id, $status = 0)
    {
        if (!empty($user_id)) {
            $this->Ausers_model->activate_user($user_id, $status);
            if ($status) {
                $this->system_messages->add_message('success', l('success_activate_user', 'ausers'));
            } else {
                $this->system_messages->add_message('success', l('success_deactivate_user', 'ausers'));
            }
        }
        $current_settings = $_SESSION["ausers_list"];
        $url = site_url() . "admin/ausers/index/" . $current_settings["filter"] . "/" . $current_settings["order"] . "/" . $current_settings["order_direction"] . "/" . $current_settings["page"] . "";
        redirect($url);
    }

    /**
	 * Log in to account
	 * 
	 * @return void
	 */
    public function login()
    {
        if ($this->session->userdata("auth_type") == 'admin') {
            redirect(site_url() . "admin/ausers");
        }

        $data["action"] = site_url() . "admin/ausers/login";

        if ($this->input->post('btn_login')) {
            $data["nickname"] = $nickname = trim(strip_tags($this->input->post('nickname', true)));
            $password = trim(strip_tags($this->input->post('password', true)));
            $user_data = $this->Ausers_model->get_user_by_login_password($nickname, md5($password));
            if (empty($user_data) || !$user_data["status"]) {
                $this->system_messages->add_message('error', l('error_login_password_incorrect', 'ausers'));
            } else {
                $user_data["permission_data"]["start"] = array(
                    'index' => 1,
                    'menu' => 1,
                    'error' => 1
                );
                $session = array(
                    "auth_type" => 'admin',
                    "user_id" => $user_data["id"],
                    "name" => $user_data["name"],
                    "nickname" => $user_data["nickname"],
                    "email" => $user_data["email"],
                    "user_type" => $user_data["user_type"],
                    "permission_data" => $user_data["permission_data"],
                );
                $this->session->set_userdata($session);
                redirect(site_url() . "admin/start");
            }
        }

        $this->template_lite->assign("data", $data);
        $this->system_messages->set_data('header', l('admin_header_login', 'ausers'));
        $this->template_lite->view('login_form');
    }

    /**
	 * Log off from account
	 * 
	 * @return void
	 */
    public function logoff()
    {
        $this->session->sess_destroy();
        redirect();
    }

}
