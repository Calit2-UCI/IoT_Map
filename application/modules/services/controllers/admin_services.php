<?php

/**
 * Services admin side controller
 *
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @author Katya Kashkova <katya@pilotgroup.net>
 * @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
 * */
Class Admin_Services extends Controller
{
    /**
     * link to CodeIgniter object
     * @var object
     */

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        $this->load->model('Menu_model');
        $this->Menu_model->set_menu_active_item('admin_menu', 'payments_menu_item');
        $this->load->model("Services_model");
    }

    public function index()
    {
        $order_by["gid"] = "ASC";
        $services = $this->Services_model->get_service_list(array(), null, $order_by);
        foreach ($services as $key => $service) {
            if ($service['type'] != 'tariff') {
                unset($services[$key]);
            }
        }

        $this->template_lite->assign('services', $services);
        $services_count = count($services);

        $this->load->helper("navigation");
        $page_data = get_admin_pages_data(site_url() . "admin/services/index/", $services_count, $services_count, 1, 'briefPage');
        $page_data["date_format"] = $this->pg_date->get_format('date_time_literal', 'st');

        $addable_templates = $this->Services_model->get_template_count(array("where" => array("moveable" => "1")));
        if ($addable_templates > 0) {
            $page_data["add_service_link"] = 1;
        }

        $this->template_lite->assign('page_data', $page_data);
        $this->Menu_model->set_menu_active_item('admin_payments_menu', 'services_menu_item');
        $this->system_messages->set_data('header', l('admin_header_services_list', 'services'));
        $this->template_lite->view('list');
    }

    public function edit($service_id = null)
    {
        $errors = false;

        if (!empty($service_id)) {
            $data = $this->Services_model->get_service_by_id($service_id);
            foreach ($this->pg_language->languages as $lang_id => $lang_data) {
                $validate_lang['name'][$lang_id] = l('service_name_' . $data["id"], 'services', $lang_id);
                $validate_lang['description'][$lang_id] = l('service_name_' . $data["id"] . '_description', 'services', $lang_id);
            }
            $template = $this->Services_model->get_template_by_gid($data["template_gid"]);
            $this->template_lite->assign('template', $template);
        } else {
            $data = array();
            $params["where"]["moveable"] = "1";
            $templates = $this->Services_model->get_template_list($params);
            $template = $this->Services_model->format_template(current($templates));
            $this->template_lite->assign('templates', $templates);
        }

        if ($this->input->post('btn_save')) {
            $post_data = array(
                "gid" => $this->input->post("gid", true),
                "pay_type" => $this->input->post("pay_type", true),
                "template_gid" => $this->input->post("template_gid", true),
                "status" => $this->input->post("status", true),
                "price" => $this->input->post("price", true),
                "data_admin" => $this->input->post("data_admin", true),
                "lds" => $this->input->post("lds", true),
            );
            $langs = $this->input->post("langs", true);

            $validate_data = $this->Services_model->validate_service($service_id, $post_data);
            if (!empty($validate_data["errors"])) {
                $errors = $validate_data["errors"];
                $validate_lang['name'] = $langs['name'];
                $validate_lang['description'] = $langs['description'];
                $data = array_merge($data, $validate_data["data"]);
            } else {
                $this->Services_model->save_service($service_id, $validate_data["data"], $langs['name'], $langs['description']);

                $this->system_messages->add_message('success', l('success_update_service_data', 'services'));
                redirect(site_url() . "admin/services");
            }
        }
        if (!empty($data)) {
            $t = $this->Services_model->format_services(array(0 => $data));
            $data = $t[0];
        }

        $this->load->model('Users_model');
        $user_types = $this->Users_model->get_user_types();
        if (!empty($user_types['option'])) {
            $this->template_lite->assign('user_types', $user_types['option']);
        }

        // languages
        $this->template_lite->assign('languages', $this->pg_language->languages);
        $this->template_lite->assign('languages_count', count($this->pg_language->languages));
        $this->template_lite->assign('cur_lang', $this->pg_language->current_lang_id);
        $this->template_lite->assign('pay_type_lang', ld('pay_type', 'services'));

        $this->template_lite->assign('template_block', $this->_get_template_admin_param_block($template, isset($data["price"]) ? $data["price"] : 0, isset($data["data_admin"]) ? $data["data_admin"] : array()));
        $this->template_lite->assign('lds_block', $this->_get_template_lds_block($template, isset($data["lds_array"]) ? $data["lds_array"] : array()));

        if (!empty($validate_lang)) {
            $this->template_lite->assign('validate_lang', $validate_lang);
        }

        $this->template_lite->assign('data', $data);

        if (!empty($errors)) {
            $this->system_messages->add_message('error', $errors);
        }

        $this->Menu_model->set_menu_active_item('admin_payments_menu', 'services_menu_item');
        $this->system_messages->set_data('header', l('admin_header_services_list', 'services'));
        $this->template_lite->view('edit');
    }

    public function ajax_get_template_admin_param_block($template_gid)
    {
        $template = $this->Services_model->get_template_by_gid($template_gid);
        echo $this->_get_template_admin_param_block($template);
    }

    private function _get_template_admin_param_block($template, $price = 0, $values = "")
    {
        if (!empty($values)) {
            foreach ($template["data_admin_array"] as $gid => $data) {
                if (!empty($values[$gid])) {
                    $template["data_admin_array"][$gid]["value"] = $values[$gid];
                }
            }
        }
        $this->template_lite->assign('price', $price);
        $this->template_lite->assign('template_data', $template);

        return $this->template_lite->fetch('block_service_param');
    }

    private function _get_template_lds_block($template, $values = "")
    {
        $template['lds_array'] = array();
        if (!empty($template["lds_array"])) {
            foreach ($template["lds_array"] as $gid => $data) {
                $template["lds_array"][$gid]["reference"] = ld($data['ds'], $data['module']);
                if (isset($values[$data['ds']])) {
                    $template["lds_array"][$gid]['value'] = $values[$data['ds']];
                }
            }
        }
        $this->template_lite->assign('lds_data', $template['lds_array']);

        return $this->template_lite->fetch('block_service_lds');
    }

    public function delete($service_id)
    {
        $this->Services_model->delete_service($service_id);
        $this->system_messages->add_message('success', l('success_delete_service', 'services'));
        redirect(site_url() . "admin/services");
    }

    public function activate($service_id, $status = 1)
    {
        $this->Services_model->save_service($service_id, array("status" => $status));
        $this->system_messages->add_message('success', l('success_update_service_data', 'services'));
        redirect(site_url() . "admin/services");
    }

    public function templates()
    {
        $order_by["gid"] = "ASC";
        $templates = $this->Services_model->get_template_list(array(), null, $order_by);
        $this->template_lite->assign('templates', $templates);
        $templates_count = count($templates);

        $this->load->helper("navigation");
        $page_data = get_admin_pages_data(site_url() . "admin/services/templates/", $templates_count, $templates_count, 1, 'briefPage');
        $page_data["date_format"] = $this->pg_date->get_format('date_time_literal', 'st');
        $this->template_lite->assign('page_data', $page_data);

        $this->Menu_model->set_menu_active_item('admin_payments_menu', 'services_menu_item');
        $this->system_messages->set_data('header', l('admin_header_templates_list', 'services'));
        $this->template_lite->view('list_templates');
    }

    public function template_edit($template_id = null)
    {
        if (!empty($template_id)) {
            $data = $this->Services_model->get_template_by_id($template_id);
            foreach ($this->pg_language->languages as $lang_id => $lang_data) {
                $validate_lang[$lang_id] = l('template_name_' . $data["id"], 'services', $lang_id);
            }
        } else {
            $data = array();
        }

        if ($this->input->post('btn_save')) {
            $post_data = array(
                "gid" => $this->input->post("gid", true),
                "callback_module" => $this->input->post("callback_module", true),
                "callback_model" => $this->input->post("callback_model", true),
                "callback_buy_method" => $this->input->post("callback_buy_method", true),
                "callback_activate_method" => $this->input->post("callback_activate_method", true),
                "callback_validate_method" => $this->input->post("callback_validate_method", true),
                "price_type" => $this->input->post("price_type", true),
                "moveable" => $this->input->post("moveable", true),
                "data_admin" => unserialize($this->input->post("data_admin", true)),
                "data_user" => unserialize($this->input->post("data_user", true)),
                "lds" => unserialize($this->input->post("lds", true)),
            );
            $langs = $this->input->post("langs", true);

            $validate_data = $this->Services_model->validate_template($template_id, $post_data);
            if (!empty($validate_data["errors"])) {
                $this->system_messages->add_message('error', $validate_data["errors"]);
                $validate_lang[] = $langs;
            } else {
                $this->Services_model->save_template($template_id, $validate_data["data"], $langs);

                $this->system_messages->add_message('success', l('success_update_template_data', 'services'));
                redirect(site_url() . "admin/services/templates");
            }
        }

        // languages
        $this->template_lite->assign('languages', $this->pg_language->languages);
        $this->template_lite->assign('languages_count', count($this->pg_language->languages));
        $this->template_lite->assign('cur_lang', $this->pg_language->current_lang_id);
        $this->template_lite->assign('price_type_lang', ld('price_type', 'services'));

        if (!empty($validate_lang)) {
            $this->template_lite->assign('validate_lang', $validate_lang);
        }

        $this->template_lite->assign('data', $data);
        $this->Menu_model->set_menu_active_item('admin_payments_menu', 'services_menu_item');
        $this->system_messages->set_data('header', l('admin_header_templates_list', 'services'));
        $this->template_lite->view('edit_templates');
    }

    public function template_delete($template_id)
    {
        $this->Services_model->delete_template($template_id);
        $this->system_messages->add_message('success', l('success_delete_template', 'services'));
        redirect(site_url() . "admin/services/templates");
    }

}
