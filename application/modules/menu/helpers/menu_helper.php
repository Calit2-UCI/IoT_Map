<?php

if (!function_exists('get_admin_main_menu')) {

    function get_admin_main_menu()
    {
        $CI = &get_instance();
        $CI->load->model('Menu_model');

        // add link to menu
        $menu_data = $CI->Menu_model->get_menu_by_gid('admin_menu');

        $user_type = $CI->session->userdata("user_type");
        if ($user_type == "admin") {
            $menu_data["check_permissions"] = false;
            $permissions = array();
        } else {
            $menu_data["check_permissions"] = true;
            $permissions = $CI->session->userdata("permission_data");
        }

        $menu_items = $CI->Menu_model->get_menu_active_items_list($menu_data["id"], $menu_data["check_permissions"], array(), 0, $permissions);

        $CI->template_lite->assign("menu", $menu_items);
        $html = $CI->template_lite->fetch("main_menu", 'admin', 'menu');
        echo $html;
    }

}

if (!function_exists('get_admin_level1_menu')) {

    function get_admin_level1_menu($gid)
    {
        $CI = & get_instance();
        $CI->load->model('Menu_model');

        // add link to menu
        $menu_data = $CI->Menu_model->get_menu_by_gid($gid);

        $user_type = $CI->session->userdata("user_type");
        if ($user_type == "admin") {
            $menu_data["check_permissions"] = false;
            $permissions = array();
        } else {
            $menu_data["check_permissions"] = true;
            $permissions = $CI->session->userdata("permission_data");
        }

        $menu_items = $CI->Menu_model->get_menu_active_items_list($menu_data["id"], $menu_data["check_permissions"], array(), 0, $permissions);

        $CI->template_lite->assign("menu", $menu_items);
        $html = $CI->template_lite->fetch("level1_menu", 'admin', 'menu');
        echo $html;
    }

}

if (!function_exists('get_admin_level2_menu')) {

    function get_admin_level2_menu($gid)
    {
        $CI = &get_instance();
        $CI->load->model('Menu_model');

        // add link to menu
        $menu_data = $CI->Menu_model->get_menu_by_gid($gid);

        $user_type = $CI->session->userdata("user_type");
        if ($user_type == "admin") {
            $menu_data["check_permissions"] = false;
            $permissions = array();
        } else {
            $menu_data["check_permissions"] = true;
            $permissions = $CI->session->userdata("permission_data");
        }

        $menu_items = $CI->Menu_model->get_menu_active_items_list($menu_data["id"], $menu_data["check_permissions"], array(), 0, $permissions);

        $CI->template_lite->assign("menu", $menu_items);
        $html = $CI->template_lite->fetch("level2_menu", 'admin', 'menu');
        echo $html;
    }

}

if (!function_exists('get_menu')) {

    function get_menu($gid, $template = '')
    {
        $CI = &get_instance();
        $CI->load->model('Menu_model');

        if (!$template) {
            $template = $gid;
        }

        // add link to menu
        $menu_data = $CI->Menu_model->get_menu_by_gid($gid);
        $menu_items = $CI->Menu_model->get_menu_active_items_list($menu_data["id"], $menu_data["check_permissions"]);
        $CI->template_lite->assign("menu", $menu_items);
        $html = $CI->template_lite->fetch($template, 'user', 'menu');
        return $html;
    }

}

if (!function_exists('get_breadcrumbs')) {

    function get_breadcrumbs($template = '')
    {
        $CI = &get_instance();
        $CI->load->model('Menu_model');

        if (!$template) {
            $template = 'helper_breadcrumbs';
        }

        // add link to menu
        $breadcrumbs = $CI->Menu_model->get_breadcrumbs();

        $CI->template_lite->assign("breadcrumbs", $breadcrumbs);
        $html = $CI->template_lite->fetch($template, 'user', 'menu');
        return $html;
    }

}

if (!function_exists('linked_install_set_menu')) {

    function linked_install_set_menu($gid, $type = "create", $name = '')
    {
        $CI = &get_instance();
        $CI->load->model('Menu_model');
        if ($type == 'create') {
            $menu_data = array('gid' => $gid, 'name' => $name);
            return $CI->Menu_model->save_menu(null, $menu_data);
        } elseif ($type == 'none') {
            $menu = $CI->Menu_model->get_menu_by_gid($gid);
            return $menu['id'];
        } elseif ($type == 'delete') {
            $CI->Menu_model->delete_menu_by_gid($gid);
            return '';
        } else {
            return 0;
        }
    }
}

if (!function_exists('linked_install_set_menu_item')) {

    function linked_install_set_menu_item($gid, $menu_id, $type = "create", $parent_id = 0, $link = '/', $icon = '', $status = 1, $sorter = 1, $indicator_gid = '')
    {
        $CI = &get_instance();
        $CI->load->model('Menu_model');
        if ($type == 'create') {
            $item_data = array('gid' => $gid, 'menu_id' => $menu_id, 'parent_id' => $parent_id, 'link' => $link, 'icon' => $icon, 'status' => $status, 'sorter' => $sorter, 'indicator_gid' => (string) $indicator_gid);
            return $CI->Menu_model->save_menu_item(null, $item_data);
        } elseif ($type == 'none') {
            $item = $CI->Menu_model->get_menu_item_by_gid($gid, $menu_id);
            return $item['id'];
        } elseif ($type == 'delete') {
            $item = $CI->Menu_model->get_menu_item_by_gid($gid, $menu_id);
            if (!empty($item))
                $CI->Menu_model->delete_menu_item($item['id']);
            return '';
        }else {
            return 0;
        }
    }

}


if (!function_exists('linked_install_set_menu_item_lang')) {

    // $lang_data is lang_id => value for update AND lang_id array for export
    function linked_install_set_menu_item_lang($item_id, $menu_id, $type = "update", $lang_data = array(), $lang_tooltip_data = array(), $lang_type = "value")
    {
        $CI = & get_instance();
        $CI->load->model('Menu_model');
        if ($type == 'update' && !empty($lang_data)) {
            return $CI->Menu_model->save_menu_item_lang($item_id, $menu_id, $lang_data, $lang_tooltip_data);
        } elseif ($type == 'export') {
            $return = $CI->Menu_model->_get_item_string_data($menu_id, $item_id, $lang_data, $lang_type);
            return $return;
        }
        return 0;
    }

}

if (!function_exists('linked_install_process_menu_items')) {

    // $process_type = 'create', 'update', 'export'
    // $lang_data is lang_id => value for update AND lang_id array for export
    function linked_install_process_menu_items(&$structure, $process_type, $menu_gid, $parent_id, &$items, $lang_prefix = "", $lang_data = array(), $lang_tooltip_data = array())
    {
        $menu_data = $structure[$menu_gid];
        if (empty($menu_data["id"])) {
            $menu_data["id"] = $structure[$menu_gid]["id"] = linked_install_set_menu($menu_gid, "none");
        }
        if (empty($menu_data["id"])) {
            return array();
        }
        if ($process_type == "export") {
            $return = array();
        }

        if (!empty($items)) {
            foreach ($items as $item_gid => $item_data) {
                if ($process_type == "create") {
                    if (!isset($item_data["action"])) {
                        $item_data["action"] = '';
                    }
                    if (!isset($item_data["link"])) {
                        $item_data["link"] = '/';
                    }
                    if (!isset($item_data["icon"])) {
                        $item_data["icon"] = '';
                    }
                    if (!isset($item_data["status"])) {
                        $item_data["status"] = 0;
                    }
                    if (!isset($item_data["sorter"])) {
                        $item_data["sorter"] = 0;
                    }
                    if (!isset($item_data["indicator_gid"])) {
                        $item_data["indicator_gid"] = '';
                    }
                    $items[$item_gid]["id"] = linked_install_set_menu_item($item_gid, $menu_data['id'], $item_data["action"], $parent_id, $item_data["link"], $item_data['icon'], $item_data["status"], $item_data["sorter"], $item_data["indicator_gid"]);
                    if (!empty($items[$item_gid]["items"])) {
                        linked_install_process_menu_items($structure, $process_type, $menu_gid, (int) $items[$item_gid]["id"], $items[$item_gid]["items"]);
                    }
                } elseif ($process_type == "update") {
                    $new_prefix = $lang_prefix . '_' . $item_gid;
                    $new_prefix_tooltip = $lang_prefix . '_' . $item_gid . "_tooltip";
                    if (!empty($lang_data[$new_prefix])) {
                        $item_lang_data = $lang_data[$new_prefix];
                    } else {
                        $item_lang_data = null;
                    }
                    if (!empty($lang_data[$new_prefix_tooltip])) {
                        $item_lang_tooltip_data = $lang_data[$new_prefix_tooltip];
                    } else {
                        $item_lang_tooltip_data = null;
                    }
                    $item_id = $items[$item_gid]["id"];
                    if (!$item_id) {
                        $items[$item_gid]["id"] = $item_id = linked_install_set_menu_item($item_gid, $menu_data["id"], "none");
                    }
                    linked_install_set_menu_item_lang($item_id, $menu_data["id"], "update", $item_lang_data, $item_lang_tooltip_data);

                    if (!empty($items[$item_gid]["items"])) {
                        linked_install_process_menu_items($structure, $process_type, $menu_gid, $item_id, $items[$item_gid]["items"], $new_prefix, $lang_data, $lang_tooltip_data);
                    }
                } elseif ($process_type == "export") {
                    $new_prefix = $lang_prefix . '_' . $item_gid;
                    $new_prefix_tooltip = $lang_prefix . '_' . $item_gid . "_tooltip";
                    $item_id = $items[$item_gid]["id"];
                    if (!$item_id) {
                        $items[$item_gid]["id"] = $item_id = linked_install_set_menu_item($item_gid, $menu_data["id"], "none");
                    }
                    if ('create' == $items[$item_gid]["action"]) {
                        $return[$new_prefix] = linked_install_set_menu_item_lang($item_id, $menu_data["id"], "export", $lang_data);
                        $return[$new_prefix_tooltip] = linked_install_set_menu_item_lang($item_id, $menu_data["id"], "export", $lang_data, array(), 'tooltip');
                    }
                    if (!empty($items[$item_gid]["items"])) {
                        $temp = linked_install_process_menu_items($structure, $process_type, $menu_gid, $item_id, $items[$item_gid]["items"], $new_prefix, $lang_data);
                        $return = array_merge($return, $temp);
                    }
                }
            }
        }
        if ($process_type == "export") {
            return $return;
        } else {
            return;
        }
    }

}

if (!function_exists('linked_install_delete_menu_items')) {

    function linked_install_delete_menu_items($menu_gid, $items)
    {
        $menu_id = linked_install_set_menu($menu_gid, "none");
        if (!$menu_id) {
            return false;
        }
        if (!empty($items)) {
            foreach ($items as $item_gid => $item_data) {
                if ($item_data["action"] == 'create') {
                    linked_install_set_menu_item($item_gid, $menu_id, "delete");
                }
                if (!empty($items[$item_gid]["items"])) {
                    linked_install_delete_menu_items($menu_gid, $items[$item_gid]["items"]);
                }
            }
        }
        return;
    }

}
