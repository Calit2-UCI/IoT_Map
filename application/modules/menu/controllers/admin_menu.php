<?php

/**
 * Menu admin side controller
 *
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * */
Class Admin_Menu extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Menu_model");
        $this->Menu_model->set_menu_active_item('admin_menu', 'interface-items');
    }

    public function index($order = "name", $order_direction = "ASC", $page = 1)
    {
        $attrs = array();
        $current_settings = isset($_SESSION["menu_list"]) ? $_SESSION["menu_list"] : array();
        if (!isset($current_settings["order"])) {
            $current_settings["order"] = "nickname";
        }
        if (!isset($current_settings["order_direction"])) {
            $current_settings["order_direction"] = "ASC";
        }
        if (!isset($current_settings["page"])) {
            $current_settings["page"] = 1;
        }

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

        $menu_count = $this->Menu_model->get_menus_count();

        if (!$page) {
            $page = $current_settings["page"];
        }
        $items_on_page = $this->pg_module->get_module_config('start', 'admin_items_per_page');
        $this->load->helper('sort_order');
        $page = get_exists_page_number($page, $menu_count, $items_on_page);
        $current_settings["page"] = $page;

        $_SESSION["menu_list"] = $current_settings;

        $sort_links = array(
            "name" => site_url() . "admin/menu/index/name/" . (($order != 'name' xor $order_direction == 'DESC') ? 'ASC' : 'DESC'),
            "gid" => site_url() . "admin/menu/index/gid/" . (($order != 'gid' xor $order_direction == 'DESC') ? 'ASC' : 'DESC'),
            "date_created" => site_url() . "admin/menu/index/date_created/" . (($order != 'date_created' xor $order_direction == 'DESC') ? 'ASC' : 'DESC'),
        );

        $this->template_lite->assign('sort_links', $sort_links);

        if ($menu_count > 0) {
            $menus = $this->Menu_model->get_menus_list($page, $items_on_page, array($order => $order_direction));
            $this->template_lite->assign('menus', $menus);
        }
        $this->load->helper("navigation");
        $url = site_url() . "admin/menu/index/" . $order . "/" . $order_direction . "/";
        $page_data = get_admin_pages_data($url, $menu_count, $items_on_page, $page, 'briefPage');
        $page_data["date_format"] = $this->pg_date->get_format('date_time_literal', 'st');
        $this->template_lite->assign('page_data', $page_data);

        $this->system_messages->set_data('header', l('admin_header_menu_list', 'menu'));
        $this->template_lite->view('list');
    }

    public function edit($menu_id = null)
    {
        if ($menu_id) {
            $data = $this->Menu_model->get_menu_by_id($menu_id);
        } else {
            $data = array();
        }

        if ($this->input->post('btn_save')) {
            $post_data = array(
                "name" => $this->input->post('name', true),
                "gid" => $this->input->post('gid', true),
                "check_permissions" => intval($this->input->post('check_permissions'))
            );
            $validate_data = $this->Menu_model->validate_menu($menu_id, $post_data);
            if (!empty($validate_data["errors"])) {
                $this->system_messages->add_message('error', $validate_data["errors"]);
                $data = array_merge($data, $validate_data["data"]);
            } else {
                $data = $validate_data["data"];
                $this->Menu_model->save_menu($menu_id, $data);
                $this->system_messages->add_message('success', ($menu_id) ? l('success_update_menu', 'menu') : l('success_add_menu', 'menu'));
                $current_settings = $_SESSION["menu_list"];
                $url = site_url() . "admin/menu/index/" . $current_settings["order"] . "/" . $current_settings["order_direction"] . "/" . $current_settings["page"] . "";
                redirect($url);
            }
        }

        $this->template_lite->assign('data', $data);

        $this->system_messages->set_data('header', l('admin_header_menu_edit', 'menu'));
        $this->template_lite->view('edit_form');
    }

    public function delete($menu_id)
    {
        if (!empty($menu_id)) {
            $this->Menu_model->delete_menu($menu_id);
            $this->system_messages->add_message('success', l('success_delete_menu', 'menu'));
        }
        $current_settings = $_SESSION["menu_list"];
        $url = site_url() . "admin/menu/index/" . $current_settings["order"] . "/" . $current_settings["order_direction"] . "/" . $current_settings["page"] . "";
        redirect($url);
    }

    public function items($menu_id)
    {
        $current_settings = $_SESSION["menu_list"];

        if (!$menu_id) {
            $url = site_url() . "admin/menu/index/" . $current_settings["order"] . "/" . $current_settings["order_direction"] . "/" . $current_settings["page"] . "";
            redirect($url);
        }

        $menu_data = $this->Menu_model->get_menu_by_id($menu_id);
        $this->template_lite->assign("menu_data", $menu_data);

        $menu_items = $this->Menu_model->get_menu_items_list($menu_id);
        $this->template_lite->assign("menu", $menu_items);

        $current_settings = $_SESSION["menu_list"];
        $back_url = site_url() . "admin/menu/index/" . $current_settings["order"] . "/" . $current_settings["order_direction"] . "/" . $current_settings["page"] . "";

        $this->pg_theme->add_js('admin-multilevel-sorter.js');
        $this->system_messages->set_data('header', l('admin_header_menu_items', 'menu') . $menu_data["name"]);
        $this->system_messages->set_data('back_link', $back_url);
        $this->template_lite->view('items_list');
    }

    public function items_edit($menu_id, $parent_id = 0, $item_id = null)
    {
        if ($item_id) {
            $data = $this->Menu_model->get_menu_item_by_id($item_id, true);
        } else {
            $data = array();
        }
        $this->load->model("modules/menu/Indicators_model");
        if ($this->input->post('btn_save')) {
            $link_type = $this->input->post('link_type');
            switch ($link_type) {
                case "out": $link = $this->input->post('link_out');
                    break;
                case "in": $link = str_replace(site_url(), "", $this->input->post('link_in'));
                    break;
                default: $link = $this->input->post('link_out');
            }
            $post_data = array(
                "gid" => $this->input->post('gid', true),
                "link" => $link,
                "menu_id" => $menu_id,
                "parent_id" => $parent_id,
                "indicator_gid" => (string) $this->input->post('indicator_gid'),
            );
            $langs_data = $this->input->post('langs', true);
            $validate_data = $this->Menu_model->validate_menu_item($item_id, $post_data);
            if (!empty($validate_data["errors"])) {
                $this->system_messages->add_message('error', $validate_data["errors"]);
                $data = array_merge($data, $validate_data["data"]);
            } else {
                $data = $validate_data["data"];
                $this->Menu_model->save_menu_item($item_id, $data, $langs_data);
                $this->system_messages->add_message('success', ($menu_id) ? l('success_update_menu_item', 'menu') : l('success_add_menu_item', 'menu'));
                $url = site_url() . "admin/menu/items/" . $menu_id;
                redirect($url);
            }
        }

        $this->template_lite->assign('data', $data);
        $this->template_lite->assign('indicators', $this->Indicators_model->get_types());
        $this->template_lite->assign('languages', $this->pg_language->languages);
        $this->template_lite->assign('menu_id', $menu_id);
        $this->template_lite->assign('parent_id', $parent_id);
        $this->system_messages->set_data('header', l('admin_header_menu_item_edit', 'menu'));
        $this->template_lite->view('edit_item_form');
    }

    public function ajax_save_item_sorter()
    {
        $sorter = $this->input->post("sorter");
        foreach ($sorter as $parent_str => $items_data) {
            $parent_id = intval(str_replace("parent_", "", $parent_str));
            foreach ($items_data as $item_str => $sort_index) {
                $item_id = intval(str_replace("item_", "", $item_str));
                $data = array(
                    "parent_id" => $parent_id,
                    "sorter" => $sort_index
                );
                $this->Menu_model->save_menu_item($item_id, $data);
            }
        }
    }

    public function ajax_item_activate($status, $item_id)
    {
        $data = array(
            "status" => intval($status),
        );
        $this->Menu_model->save_menu_item($item_id, $data);
    }

    public function ajax_item_delete($item_id)
    {
        $this->Menu_model->delete_menu_item($item_id);
    }

}
