<?php

namespace Pg\Modules\Start\Controllers;

/**
 * Start admin side controller
 *
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * */
Class Admin_start extends \Controller
{

    /**
     * link to CodeIgniter object
     * @var object
     */
    private $numerics_settings = array(
        "start" => array(
            'admin_items_per_page',
            'index_items_per_page',
        ),
        "mailbox" => array(
            'items_per_page',
            'attach_limit',
        ),
        "news" => array(
            'userside_items_per_page',
        ),
        "payments" => array(
            'items_per_page',
        ),
        'users' => array(
            'items_per_page',
        ),
        'wall_events' => array(
            'items_per_page',
        ),
        'media' => array(
            'items_per_page',
        ),
        'banners' => array(
            'items_per_page',
        ),
    );
    private $other_settings = array(
        "countries" => array(
            array('var' => 'output_country_format', 'type' => 'text'),
            array('var' => 'output_region_format', 'type' => 'text'),
            array('var' => 'output_city_format', 'type' => 'text')
        ),
        /* 'users' => array(
          array('var' => 'user_approve', 'type' => 'select', 'values' => array(0 => 'no', 1 => 'admin', 2 => 'service')),
          array('var' => 'user_confirm', 'type' => 'checkbox'),
          array('var' => 'hide_user_names', 'type' => 'checkbox'),
          array('var' => 'age_min', 'type' => 'text'),
          array('var' => 'age_max', 'type' => 'text')
          ), */
    );
    private $date_formats = array(
        'date_literal',
        'date_time_numeric',
        'date_time_literal',
    );
    private $date_formats_pages = array(
        'date_literal' => array(
            'users/profile/',
            'users/my_visits/',
            'users/my_guests/',
            'users/account/services/',
            'users/view/',
            'users/my_guests/',
            'users/my_visits/',
        ),
        'date_time_numeric' => array(
            'admin/moderation',
            'admin/cronjob/index/',
            'admin/cronjob/log/',
            'admin/mail_list/filters/',
            'admin/polls/index/',
            'admin/polls/results/',
            'admin/banners/index/',
        ),
        'date_time_literal' => array(
            'admin/reviews/index/',
            'admin/reviews/types/',
            'admin/ausers/index/',
            'admin/users/index/',
            'admin/payments/index/',
            'admin/menu/index/',
            'admin/notifications/index/',
            'admin/notifications/templates/',
            'admin/news/index/',
            'admin/news/feeds/',
            'admin/mail_list/users/',
            'admin/seo/robots/',
            'mailbox/index/ (mailbox)',
            'start/homepage/ (wall events)',
            'users/profile/wall/ (wall events)',
            'users/view/ (wall events)',
            //'users_lists/',
            'friendlist/',
            'users/my_guests/',
            'users/my_visits/',
            'users/account/services/',
            'payments/statistic',
            'news/index/',
            'news/view/',
            'im',
            'comments',
            'media',
        ),
    );

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $settings_for_modules = array_unique(array_merge(array_keys($this->numerics_settings), array_keys($this->other_settings)));
        foreach ($settings_for_modules as $module) {
            if (!$this->pg_module->is_module_installed($module)) {
                unset($this->numerics_settings[$module], $this->other_settings[$module]);
            }
        }
    }

    public function index()
    {
        if ($this->session->userdata("auth_type") != 'admin') {
            $this->session->set_userdata('demo_user_type', 'admin');
            redirect(site_url() . "admin/ausers/login");
        }

        $this->system_messages->set_data('header', l("header_admin_homepage", 'start'));
        if ($this->session->userdata("user_type") == "moderator") {
            $this->template_lite->view('index_moderator');
        } else {
            $this->template_lite->view('index');
        }
    }

    public function error($error_type = '', $request_method = '')
    {
        if ($request_method == 'ajax') {
            $this->template_lite->assign('ajax', '1');
        }
        $this->template_lite->view('error');
    }

    public function mod_login()
    {
        $data["action"] = site_url() . "admin/install/login";
        $this->template_lite->assign("data", $data);

        $this->system_messages->set_data('header', l("header_modinstaller_login", 'start'));
        $this->template_lite->view('modules_login');
        return;
    }

    public function menu($menu_item_gid)
    {
        $this->load->model('Menu_model');
        $this->Menu_model->set_menu_active_item('admin_menu', $menu_item_gid);

        // add link to menu
        $menu_data = $this->Menu_model->get_menu_by_gid('admin_menu');
        $menu_item = $this->Menu_model->get_menu_item_by_gid($menu_item_gid, $menu_data["id"]);

        $user_type = $this->session->userdata("user_type");
        if ($user_type == "admin") {
            $menu_data["check_permissions"] = false;
            $permissions = array();
        } else {
            $menu_data["check_permissions"] = true;
            $permissions = $this->session->userdata("permission_data");
        }
        $menu_items = $this->Menu_model->get_menu_active_items_list($menu_data["id"], $menu_data["check_permissions"], array(), $menu_item["id"], $permissions);

        $this->template_lite->assign("menu", $menu_item);
        $this->template_lite->assign("options", $menu_items);
        $this->system_messages->set_data('header', l("header_settings_list", 'start') . "" . $menu_item["value"]);
        $this->template_lite->view('menu_list');
        return;
    }

    public function settings($section = "overview")
    {
        $this->load->model('Menu_model');
        $this->Menu_model->set_menu_active_item('admin_menu', 'system-items');


        if ($this->input->post('btn_save')) {
            $errors = array();
            $post_data = $this->input->post('settings', true);

            if ($section == 'numerics') {
                foreach ($this->numerics_settings as $module => $settings) {
                    foreach ($settings as $var) {
                        $value = !empty($post_data[$module][$var]) ? intval($post_data[$module][$var]) : 0;
                        if (empty($value)) {
                            $errors[] = l('error_numerics_empty', 'start', '', 'text', array('field' => l($module . "_" . $var . "_field", "start")));
                        } else {
                            $this->pg_module->set_module_config($module, $var, $value);
                        }
                    }
                }
            } else {
                $settings = $this->other_settings[$section];
                foreach ($settings as $var) {
                    $field_type = 'text';
                    if (is_array($var)) {
                        $field_type = $var['type'];
                        $field_values = !empty($var['values']) ? $var['values'] : array();
                        $var = $var['var'];
                    }
                    $value = !empty($post_data[$var]) ? $post_data[$var] : '';
                    $error = '';

                    if (!empty($error)) {
                        $errors[] = $error;
                    } else {
                        $this->pg_module->set_module_config($section, $var, $value);
                    }
                }
            }

            if (empty($errors)) {
                $this->system_messages->add_message('success', l('success_update_numerics_data', 'start'));
            } else {
                $this->system_messages->add_message('error', $errors);
            }
        }

        if ($section == 'overview') {
            foreach ($this->numerics_settings as $module => $settings) {
                $settings_data["numerics"][$module]["name"] = l($module . "_settings_module", "start");
                foreach ($settings as $var) {
                    $settings_data["numerics"][$module]['vars'][] = array(
                        "field" => $var,
                        "field_name" => l($module . "_" . $var . "_field", "start"),
                        "value" => $this->pg_module->get_module_config($module, $var)
                    );
                }
            }
            foreach ($this->other_settings as $module => $settings) {
                $settings_data["other"][$module]["name"] = l($module . "_settings_module", "start");
                foreach ($settings as $var) {
                    $field_type = 'text';
                    $field_values = array();
                    if (is_array($var)) {
                        $field_type = $var['type'];
                        $field_values = !empty($var['values']) ? $var['values'] : array();
                        $var = $var['var'];
                    }
                    $vars_value = $this->pg_module->get_module_config($module, $var);
                    $vars_value_int = intval($vars_value);
                    if (!empty($field_values[$vars_value_int])) {
                        $vars_value_name = l($module . "_" . $var . "_" . $field_values[$vars_value_int] . "_value", "start");
                    } else {
                        $vars_value_name = '';
                    }
                    $settings_data["other"][$module]['vars'][] = array(
                        "field" => $var,
                        "field_name" => l($module . "_" . $var . "_field", "start"),
                        "value" => $vars_value,
                        "value_name" => $vars_value_name,
                        'field_type' => $field_type,
                        'field_values' => $field_values
                    );
                }
            }
        } elseif ($section == 'numerics') {
            foreach ($this->numerics_settings as $module => $settings) {
                $settings_data[$module]["name"] = l($module . "_settings_module", "start");
                foreach ($settings as $var) {
                    $settings_data[$module]['vars'][] = array(
                        "field" => $var,
                        "field_name" => l($module . "_" . $var . "_field", "start"),
                        "value" => $this->pg_module->get_module_config($module, $var)
                    );
                }
            }
        } elseif ($section == 'date_formats') {
            $this->template_lite->assign('date_formats_pages', $this->date_formats_pages);
            $settings_data["name"] = l($section . "_settings_module", "start");
            foreach ($this->date_formats as $var) {
                $settings_data['vars'][] = array(
                    "field" => $var,
                    "field_name" => l($section . "_" . $var . "_field", "start"),
                    "value" => $this->pg_date->strftime($this->pg_date->get_format($var, 'st'), time())
                );
            }
        } else {
            $settings_data["name"] = l($section . "_settings_module", "start");
            foreach ($this->other_settings[$section] as $var) {
                $field_type = 'text';
                if (is_array($var)) {
                    $field_type = $var['type'];
                    $field_values = !empty($var['values']) ? $var['values'] : array();
                    $var = $var['var'];
                }
                $vars_value = $this->pg_module->get_module_config($section, $var);
                $vars_value_int = intval($vars_value);
                if (!empty($field_values[$vars_value_int])) {
                    $vars_value_name = l($section . "_" . $var . "_" . $field_values[intval($vars_value)] . "_value", "start");
                } else {
                    $vars_value_name = '';
                }
                $settings_data['vars'][] = array(
                    "field" => $var,
                    "field_name" => l($section . "_" . $var . "_field", "start"),
                    "value" => $vars_value,
                    "value_name" => $vars_value_name,
                    'field_type' => $field_type,
                    'field_values' => $field_values
                );
            }
        }

        $this->template_lite->assign("section", $section);
        $this->template_lite->assign("settings_data", $settings_data);
        $this->template_lite->assign("numerics_settings", $this->numerics_settings);
        $this->template_lite->assign("other_settings", $this->other_settings);
        $this->system_messages->set_data('header', l("header_settings_numerics_list", 'start'));
        $this->template_lite->view('numerics_list');
        return;
    }

    public function date_formats($format_id)
    {
        if (!in_array($format_id, array_keys($this->date_formats))) {
            $this->settings('date_formats');
        }

        $this->load->model('Menu_model');
        $this->Menu_model->set_menu_active_item('admin_menu', 'system-items');

        if ($this->input->post('btn_save')) {
            $tpl = $this->input->post('tpl', true);
            // Fill $data with used patterns
            foreach ($this->pg_date->available_formats[$format_id] as $f_id => $available) {
                $value = $this->input->post($f_id, true);
                if (in_array($value, $available)) {
                    $data[$f_id] = $value;
                }
            }
            $generic_tpl = $this->pg_date->create_generic_tpl($tpl, $data);
            $this->pg_date->save_format($generic_tpl, $format_id);
        } else {
            $generic_tpl = $this->pg_module->get_module_config('start', 'date_format_' . $format_id);
        }

        $format = array(
            'current' => $this->pg_date->parse_generic_template($generic_tpl, 'js'),
            'available' => $this->pg_date->available_formats[$format_id],
            'name' => l('date_formats_' . $format_id . '_field', 'start'),
            'gid' => $format_id
        );
        $this->system_messages->set_data('header', l('header_settings_numerics_list', 'start'));
        $this->template_lite->assign('section', 'date_formats');
        $this->template_lite->assign('format', $format);
        $this->template_lite->assign('settings_name', l('date_formats_settings_module', 'start'));
        $this->template_lite->assign('other_settings', $this->other_settings);
        $this->template_lite->view('date_formats');
        return;
    }

    public function ajax_get_example()
    {

        $uf_tpl = $this->input->get('tpl', true);
        $format_id = $this->input->get('format_id', true);
        if (empty($uf_tpl) || empty($format_id)) {
            return false;
        }

        // Fill $data with used patterns
        foreach ($this->pg_date->available_formats[$format_id] as $f_id => $available) {
            $value = $this->input->get($f_id, true);
            if (in_array($value, $available)) {
                $data[$f_id] = $value;
            }
        }
        $generic_tpl = $this->pg_date->create_generic_tpl($uf_tpl, $data);
        echo $this->pg_date->strftime($generic_tpl, null, 'generic');
    }

    public function lang_inline_editor($is_textarea = 0)
    {
        $this->template_lite->assign("langs", $this->pg_language->languages);
        $this->template_lite->assign('is_textarea', $is_textarea);
        $this->template_lite->view('helper_lang_inline_editor');
        return;
    }

    public function wysiwyg_uploader($module = '', $id = 0, $upload_config_gid = '', $field = 'upload')
    {
        $module = trim(strip_tags($module));
        $id = intval($id);
        $upload_config_gid = trim(strip_tags($upload_config_gid));
        $field = trim(strip_tags($field));

        $this->load->model('Start_model');
        $upload = $this->Start_model->do_wysiwyg_upload($module, $id, $upload_config_gid, $field);
        $message = '';
        $url = '';
        if ($upload['error']) {
            $message = $upload['error'];
        } elseif ($upload['is_uploaded']) {
            $url = $upload['upload_url'];
        }
        $funcNum = intval($this->input->get('CKEditorFuncNum', true));
        $ckeditor = trim(strip_tags($this->input->get('CKEditor', true)));
        $langcode = trim(strip_tags($this->input->get('langCode', true)));
        echo("<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '$message');</script>");
        return;
    }

    public function geolocation()
    {
        $this->load->model('Menu_model');
        $this->Menu_model->set_menu_active_item('admin_menu', 'system-items');

        if ($this->input->post('btn_save')) {
            $geolocation_onoff = intval($this->input->post('geolocation_onoff', true));
            $this->pg_module->set_module_config('start', 'geolocation_onoff', $geolocation_onoff);
        } else {
            $geolocation_onoff = $this->pg_module->get_module_config('start', 'geolocation_onoff');
        }
        $this->template_lite->assign('geolocation_onoff', $geolocation_onoff);

        $this->system_messages->set_data('header', l("header_settings_geolocation", 'start'));
        $this->template_lite->view('geolocation_settings');
        return;
    }

}
