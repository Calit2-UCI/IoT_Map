<?php

/**
 * Users user side controller
 *
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * */
Class Api_Users extends Controller
{

    /**
     * link to CodeIgniter object
     * @var object
     */
    public $use_email_confirmation = false;
    public $use_approve = false;
    private $user_id;
    private $subsections = array('default' => 'all', 'photo', 'video', 'albums', 'favorites');

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Users_model");
        $this->use_email_confirmation = (bool) $this->pg_module->get_module_config('users', 'user_confirm');
        $this->use_approve = intval($this->pg_module->get_module_config('users', 'user_approve'));
        $this->user_id = intval($this->session->userdata('user_id'));
    }

    public function logout()
    {
        $this->load->model("users/models/Auth_model");
        $this->Users_model->update_online_status($this->user_id, 0);
        $this->Auth_model->logoff();
        $this->session->sess_create();
        $token = $this->session->sess_create_token();
        $this->set_api_content('data', array('token' => $token));
    }

    public function get()
    {
        $user_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if (empty($user_id)) {
            $user_id = $this->user_id;
        }
        $user = $this->Users_model->get_user_by_id($user_id, filter_input(INPUT_POST, 'formatted', FILTER_VALIDATE_BOOLEAN), true);
        if ($user) {
            $this->set_api_content('data', $user);
        } else {
            $this->set_api_content('errors', l('error_not_found', 'users'));
        }
    }

    private function guest_access()
    {
        if (!empty($this->_user_id)) {
            return true;
        }
        $cookie = array(
            'name' => 'profiles_viewed',
            'expire' => 3600 * 24 * 7,
            'domain' => COOKIE_SITE_SERVER,
            'path' => '/' . SITE_SUBFOLDER
        );
        $this->load->helper('cookie');
        $allowed_views = $this->pg_module->get_module_config('users', 'guest_view_profile_num');
        if (empty($allowed_views)) {
            $cookie['value'] = 0;
            set_cookie($cookie);
            return true;
        }
        $viewed = get_cookie('profiles_viewed') + 1;
        $cookie['value'] = $viewed;
        set_cookie($cookie);
        if ($viewed > $allowed_views) {
            $this->system_messages->add_message('error', l('error_guest_limit', 'users'), 'users');
            $this->set_api_content('code', 403);
        } else {
            return true;
        }
    }

    public function view()
    {
        $this->guest_access();
        $viewer_id = intval($this->user_id);
        $lang_id = filter_input(INPUT_POST, 'lang_id', FILTER_VALIDATE_INT);
        $user_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $section = filter_input(INPUT_POST, 'section', FILTER_SANITIZE_STRING);

        if (!$section) {
            $section = $this->pg_module->is_module_installed('wall_events') ? 'wall' : 'view';
        }

        if ($viewer_id && $viewer_id != $user_id) {
            $this->load->model('users/models/Users_views_model');
            $this->Users_views_model->update_views($user_id, $viewer_id);
        }

        $this->load->model('Field_editor_model');
        $this->Field_editor_model->initialize($this->Users_model->form_editor_type);
        $sections = $this->Field_editor_model->get_section_list(array(), array(), $lang_id);
        $sections_gids = array_keys($sections);
        $fields_for_select = $this->Field_editor_model->get_fields_for_select($sections_gids);

        $this->Users_model->set_additional_fields($fields_for_select);
        $user = $this->Users_model->get_user_by_id($user_id);
        $data = $this->Users_model->format_user($user, false, $lang_id);
        if (!$data) {
            return show_404();
        }
        $api_data = array();

        $params = array();
        foreach (array_keys($sections) as $sgid) {
            $params['where']['section_gid'] = $sgid;
            $sections[$sgid]['fields'] = $this->Field_editor_model->format_item_fields_for_view($params, $data, $lang_id);
        }
        $api_data['sections'] = $sections;
        $this->pg_seo->set_seo_data($data);
        $this->load->helper('seo');
        $api_data['user'] = $data;
        $api_data['profile_section'] = $section;
        $this->set_api_content('data', $api_data);
    }

    public function settings()
    {
        $user_id = $this->user_id;

        $this->load->model('users/models/Auth_model');
        $this->Auth_model->update_user_session_data($user_id);
        $errors = array();
        $messages = array();
        if ($this->input->post('password_save')) {
            $post_data = array(
                "password" => $this->input->post('password', true),
                "repassword" => $this->input->post('repassword', true),
            );
            $validate_data = $this->Users_model->validate($user_id, $post_data);

            if (!empty($validate_data["errors"])) {
                $errors[] = $validate_data["errors"];
            } else {
                $save_data = $validate_data["data"];
                $save_password = $save_data["password"];
                $save_data["password"] = md5($save_data["password"]);
                $user_id = $this->Users_model->save_user($user_id, $save_data);

                // send notification
                $this->load->model('Notifications_model');
                $user_data = $this->Users_model->get_user_by_id($user_id);
                $user_data["password"] = $save_password;
                $data['sending_result'] = $this->Notifications_model->send_notification($user_data["email"], 'users_change_password', $user_data);
                $messages[] = l('success_user_updated', 'users');
            }
        }

        if ($this->input->post('contact_save')) {
            $post_data = array(
                "email" => $this->input->post('email', true),
                "phone" => $this->input->post('phone', true),
                "show_adult" => $this->input->post('show_adult', true),
            );
            $validate_data = $this->Users_model->validate($user_id, $post_data);

            if (!empty($validate_data["errors"])) {
                $errors[] = $validate_data["errors"];
            } else {
                $user_data = $this->Users_model->get_user_by_id($user_id);
                $save_data = $validate_data["data"];
                $user_id = $this->Users_model->save_user($user_id, $save_data);

                // send notification
                if ($user_data["email"] != $save_data["email"]) {
                    $this->load->model('Notifications_model');
                    $user_data["new_email"] = $save_data["email"];
                    $this->Notifications_model->send_notification($user_data["email"], 'users_change_email', $save_data);
                    $this->Notifications_model->send_notification($user_data["new_email"], 'users_change_email', $save_data);
                }

                $this->load->model('users/models/Auth_model');
                $this->Auth_model->update_user_session_data($user_id);
                $messages[] = l('success_user_updated', 'users');
            }
        }

        if ($this->input->post('btn_subscriptions_save') && $this->pg_module->is_module_installed('subscriptions')) {
            // Save user subscribers
            $user_subscriptions_list = $this->input->post('user_subscriptions_list', true);
            $this->load->model('subscriptions/models/Subscriptions_users_model');
            $this->Subscriptions_users_model->save_user_subscriptions($user_id, $user_subscriptions_list);
        }

        $user = $this->Users_model->get_user_by_id($user_id);
        $this->pg_seo->set_seo_data($user);
        $data['user_id'] = $user_id;
        $data['user'] = $user;
        $this->set_api_content('data', $data);
        $this->set_api_content('errors', $errors);
        $this->set_api_content('messages', $messages);
    }

    public function user()
    {
        $this->load->model('users/models/Auth_model');
        $this->Auth_model->update_user_session_data($this->user_id);
        $user = $this->Users_model->format_user($this->Users_model->get_user_by_id($this->user_id));
        $data = array(
            'user_id' => $this->user_id,
            'user' => $user,
        );
        $this->set_api_content('data', $data);
    }

    public function validate()
    {
        $data = array();
        $errors = array();
        $filter_args = array(
            'email' => FILTER_VALIDATE_EMAIL,
            'password' => FILTER_SANITIZE_STRING,
            'repassword' => FILTER_SANITIZE_STRING,
            'nickname' => FILTER_SANITIZE_STRING,
            'birth_date' => FILTER_SANITIZE_STRING,
            'user_type' => FILTER_SANITIZE_STRING,
            'looking_user_type' => FILTER_SANITIZE_STRING,
            'confirmation' => FILTER_VALIDATE_BOOLEAN,
        );

        $post_data = filter_input_array(INPUT_POST, $filter_args);
        $post_data['age_min'] = $this->pg_module->get_module_config('users', 'age_min');
        $post_data['age_max'] = $this->pg_module->get_module_config('users', 'age_max');

        if (false === filter_input(INPUT_POST, 'strict', FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)) {
            $this->Users_model->fields_register = array();
        }
        $validate_data = $this->Users_model->validate(null, $post_data, 'user_icon');
        if (!empty($validate_data['errors'])) {
            $errors[] = $validate_data['errors'];
            $data = $validate_data['data'];
        }

        $this->set_api_content('errors', $errors);
        $this->set_api_content('data', $data);
    }

    public function registration()
    {
        if ($this->session->userdata('auth_type') == 'user') {
            $this->set_api_content('errors', l('error_access_denied', 'users'));
            return false;
        }
        $errors = array();
        $messages = array();
        $post_data = filter_input_array(INPUT_POST, array(
            'email' => FILTER_SANITIZE_STRING,
            'password' => FILTER_SANITIZE_STRING,
            'repassword' => FILTER_SANITIZE_STRING,
            'nickname' => FILTER_SANITIZE_STRING,
            'birth_date' => FILTER_SANITIZE_STRING,
            'user_type' => FILTER_SANITIZE_STRING,
            'looking_user_type' => FILTER_SANITIZE_STRING,
            'confirmation' => FILTER_VALIDATE_BOOLEAN,
            'age_min' => FILTER_VALIDATE_INT,
            'age_max' => FILTER_VALIDATE_INT,
            'id_country' => FILTER_SANITIZE_STRING,
            'id_region' => FILTER_VALIDATE_INT,
            'id_city' => FILTER_VALIDATE_INT,
        ));

        $validate_data = $this->Users_model->validate(null, $post_data, 'user_icon');
        $data = $validate_data["data"];
        if (!empty($validate_data["errors"])) {
            $errors[] = $validate_data["errors"];
        } else {
            $data['activity'] = 0;
            if ($this->use_email_confirmation) {
                $data["confirm"] = 0;
                $data["confirm_code"] = substr(md5(date("Y-m-d H:i:s") . $data["nickname"]), 0, 10);
            } else {
                $data["confirm"] = 1;
                $data["confirm_code"] = "";
            }
            $data["approved"] = $this->use_approve ? 0 : 1;
            $opened_password = $data["password"];
            $data["password"] = md5($data["password"]);
            $data["lang_id"] = $this->session->userdata("lang_id");
            if (!$data["lang_id"]) {
                $data["lang_id"] = $this->pg_language->get_default_lang_id();
            }

            $user_id = $this->Users_model->save_user(null, $data, 'user_icon');

            $this->load->model('Notifications_model');
            $data['password'] = $opened_password;
            $data['user_id'] = $user_id;
            if ($this->use_email_confirmation) {
                //$link = site_url() . "users/confirm/" . $data["confirm_code"];
                $data["confirm_block"] = l('confirmation_code', 'users').': '.$data["confirm_code"];
                $this->load->model("users/models/Auth_model");
                $auth_data = $this->Auth_model->login($user_id);
                if (!empty($auth_data["errors"])) {
                    $messages[] = $auth_data["errors"];
                }
            } else {
                $messages[] = l('info_please_checkout_mailbox', 'users');
            }
            $this->Notifications_model->send_notification($data["email"], 'users_registration', $data);
        }

        $data['token'] = $this->session->sess_create_token();
        $this->set_api_content('errors', $errors);
        $this->set_api_content('messages', $messages);
        $this->set_api_content('data', $data);
    }

    public function restore()
    {
        if ($this->session->userdata('auth_type') === 'user') {
            return true;
        }
        $errors = array();
        $messages = array();
        $email = filter_input(INPUT_POST, 'email');
        $user_data = $this->Users_model->get_user_by_email($email);
        if (empty($user_data) || !$user_data['id']) {
            $errors[] = l('error_user_no_exists', 'users');
        } elseif (!$user_data['confirm']) {
            $errors[] = l('error_unconfirmed_user', 'users');
        } elseif (!$user_data['approved']) {
            $errors[] = l('error_user_is_blocked', 'users');
        } else {
            $user_data['password'] = $new_password = substr(md5(date('Y-m-d H:i:s') . $user_data['email']), 0, 6);
            $data['password'] = md5($new_password);
            $this->Users_model->save_user($user_data['id'], $data);
            $this->load->model('Notifications_model');
            $send_data = $this->Notifications_model->send_notification($user_data['email'], 'users_fogot_password', $user_data);
            $messages[] = l('success_restore_mail_sent', 'users');
        }
        $this->set_api_content('data', $send_data);
        $this->set_api_content('messages', $messages);
        $this->set_api_content('errors', $errors);
    }

    public function confirm($code = '')
    {
        $code = trim(strip_tags($code));
        if (!$code) {
            $code = $this->input->post('code', true);
        }
        if (!$code) {
            return;
        }
        $errors = array();
        $messages = array();
        $user = $this->Users_model->get_user_by_confirm_code($code);
        if (empty($user)) {
            $errors[] = l('error_user_no_exists', 'users');
            $this->set_api_content('errors', $messages);
            return false;
        } elseif ($user["confirm"]) {
            $errors[] = l('error_user_already_confirm', 'users');
            $this->set_api_content('errors', $messages);
            return false;
        } else {
            $data["confirm"] = 1;
            $this->Users_model->save_user($user["id"], $data);

            $messages[] = l('success_confirm', 'users');

            $this->load->model("users/models/Auth_model");
            $auth_data = $this->Auth_model->login($user["id"]);
            if (!empty($auth_data["errors"])) {
                $errors[] = $auth_data["errors"];
            }
            $token = $this->session->sess_create_token();
            $this->set_api_content('data', array('token' => $token));

            $this->set_api_content('errors', $errors);
            $this->set_api_content('messages', $messages);
        }
    }

    public function get_users_data()
    {
        $return = array();
        
        $params = array();

        $search_string = trim(strip_tags($this->input->post('search', true)));
        if (!empty($search_string)) {
            $params["where"]["nickname LIKE"] = "%" . $search_string . "%";
        }

        $selected = $this->input->post('selected', true);
        if (!empty($selected)) {
            $params["where_sql"][] = "id NOT IN (" . implode($selected) . ")";
        }

        $user_type = $this->input->post('user_type', true);

        if ($user_type) {
            $params["where"]["user_type"] = $user_type;
        }

        $page = intval($this->input->post('page', true));
        if (!$page) {
            $page = 1;
        }

        $items_on_page = 20;

        $items = $this->Users_model->get_users_list_by_key($page, $items_on_page, array("id" => "desc", "nickname" => "asc"), $params, array(), true, true); 

        $return["all"] = $this->Users_model->get_users_count($params);
        $return["items"] = array_values($items);
        $return["current_page"] = $page;
        $return["pages"] = ceil($return["all"] / $items_on_page);

        $this->set_api_content('data', $return);
    }

    public function get_selected_users()
    {
        $selected = $this->input->post('selected', true);
        if (!empty($selected)) {
            $params["where_in"]["id"] = $selected;
            $return = $this->Users_model->get_users_list(null, null, array("nickname" => "asc"), $params, null, true, true);
        } else {
            $return = array();
        }
        $this->set_api_content('data', $return);
        return;
    }

    public function search()
    {
        $order = filter_input(INPUT_POST, 'order', FILTER_SANITIZE_STRING);
        if (!$order) {
            $order = 'default';
        }
        $order_direction = filter_input(INPUT_POST, 'order_direction', FILTER_SANITIZE_STRING);
        if (!$order_direction) {
            $order_direction = 'DESC';
        }
        $page = filter_input(INPUT_POST, 'page', FILTER_VALIDATE_INT);
        if (!$page) {
            $page = 1;
        }
        $data = array();
        $post_data = filter_input_array(INPUT_POST);
        if (!empty($post_data)) {
            foreach (array_keys($post_data) as $key) {
                $data[$key] = filter_input(INPUT_POST, $key);
            }
            $data = array_merge($this->Users_model->get_minimum_search_data(), $data);
        } elseif ($this->session->userdata('users_search')) {
            $data = $this->session->userdata('users_search');
        }
        $this->search_list_block($data, $order, $order_direction, $page, 'advanced');
    }

    public function search_counts()
    {
        $type = trim(strip_tags($this->input->post('type', true)));
        if (!$type) {
            $type = 'advanced';
        }

        $result = array('count' => 0, 'error' => '', 'string' => '');
        if (!empty($_POST)) {
            foreach ($_POST as $key => $val) {
                $data[$key] = $this->input->post($key, true);
            }
            switch ($type) {
                case 'perfect_match':
                    $criteria = $this->get_perfect_match_criteria($data);
                    break;
                case 'advanced':
                default:
                    $criteria = $this->get_advanced_search_criteria($data);
                    break;
            }
            $result["count"] = $this->Users_model->get_users_count($criteria);
            $result["string"] = str_replace("[count]", $result["count"], l('user_results_string', 'users'));
        }
        $this->set_api_content('data', $result);
    }

    public function perfect_match()
    {
        $order = trim(strip_tags($this->input->post('order', true)));
        if (!$order) {
            $order = 'default';
        }
        $order_direction = trim(strip_tags($this->input->post('order_direction', true)));
        if (!$order_direction) {
            $order_direction = 'DESC';
        }
        $page = intval($this->input->post('page'));
        if (!$page) {
            $page = 1;
        }

        $this->session->unset_userdata("perfect_match");
        $this->load->model('users/models/Users_perfect_match_model');
        $this->load->model('Field_editor_model');
        $this->load->model('field_editor/models/Field_editor_forms_model');
        $this->Field_editor_model->initialize($this->Users_perfect_match_model->form_editor_type);
        $form = $this->Field_editor_forms_model->get_form_by_gid($this->Users_perfect_match_model->perfect_match_form_gid, $this->Users_perfect_match_model->form_editor_type);
        $fields_for_search = $this->Field_editor_model->get_fields_names_for_search($form);
        $this->Users_perfect_match_model->set_additional_fields($fields_for_search);
        $errors = array();
        if (empty($_POST['params'])) {
            $perfect_match_params = $this->Users_perfect_match_model->get_user_perfect_match_params($this->user_id);
            $data = !empty($perfect_match_params['full_criteria']) ? $perfect_match_params['full_criteria'] : array();
        } else {
            foreach ($_POST['params'] as $key => $val) {
                $post_data[$key] = $this->input->post($key, true);
            }
            foreach ($fields_for_search as $field) {
                if ($this->input->post($field) !== false) {
                    $post_data[$field] = $this->input->post($field, true);
                }
            }
            $validate_data = $this->Users_perfect_match_model->validate($post_data);
            $data = $validate_data['data'];
            if (empty($validate_data['errors'])) {
                $this->Users_perfect_match_model->save_perfect_match($this->user_id, $validate_data['data'], 'all');
            } else {
                $errors[] = $validate_data['errors'];
            }
        }
        $this->set_api_content('errors', $errors);
        $this->search_list_block($data, $order, $order_direction, $page, 'perfect_match');
    }

    private function search_list_block($data = array(), $order = "default", $order_direction = "DESC", $page = 1, $search_type = 'advanced')
    {
        $api_data['user_id'] = $this->user_id;
        switch ($search_type) {
            case 'perfect_match':
                if (!empty($data)) {
                    $current_settings = $data;
                } elseif ($this->session->userdata("perfect_match")) {
                    $current_settings = $this->session->userdata("perfect_match");
                } else {
                    $current_settings = array();
                }
                $this->session->set_userdata("perfect_match", $current_settings);
                $criteria = $this->get_perfect_match_criteria($current_settings);
                $search_url = site_url() . "users/perfect_match";
                $url = site_url() . "users/perfect_match/" . $order . "/" . $order_direction . "/";
                break;
            case 'advanced':
            default:
                if (!empty($data)) {
                    $current_settings = $data;
                } elseif ($this->session->userdata("users_search")) {
                    $current_settings = $this->session->userdata("users_search");
                } else {
                    $current_settings = $this->Users_model->get_default_search_data();
                }

                $this->session->set_userdata("users_search", $current_settings);
                $criteria = $this->get_advanced_search_criteria($current_settings);
                $search_url = site_url() . "users/search";
                $url = site_url() . "users/search/" . $order . "/" . $order_direction . "/";
                break;
        }
        $api_data['search_type'] = $search_type;
        $order = trim(strip_tags($order));
        if (!$order) {
            $order = 'date_created';
        }
        $api_data['order'] = $order;

        $order_direction = strtoupper(trim(strip_tags($order_direction)));
        if ($order_direction !== 'DESC') {
            $order_direction = "ASC";
        }
        $api_data['order_direction'] = $order_direction;

        $items_count = $this->Users_model->get_users_count($criteria);

        if (!$page) {
            $page = 1;
        }
        $items_on_page = filter_input(INPUT_POST, 'items_on_page', FILTER_VALIDATE_INT);
        if (!$items_on_page) {
            $items_on_page = $this->pg_module->get_module_config('users', 'items_per_page');
        }
        $this->load->helper('sort_order');
        $page = get_exists_page_number($page, $items_count, $items_on_page);

        $sort_data = array(
            "url" => $search_url,
            "order" => $order,
            "direction" => $order_direction,
            "links" => array(
                "default" => l('field_default_sorter', 'users'),
                "name" => l('field_name', 'users'),
                "views_count" => l('field_views_count', 'users'),
                "date_created" => l('field_date_created', 'users'),
            )
        );
        $api_data['sort_data'] = $sort_data;

        $use_leader = false;
        if ($items_count > 0) {
            $order_array = array();
            if ($order == 'default') {
                if (!empty($data['id_region']) && intval($data['id_region'])) {
                    $order_array['leader_bid'] = 'DESC';
                }
                if (!empty($criteria['fields']) && intval($criteria['fields'])) {
                    $order_array["fields"] = 'DESC';
                } else {
                    $order_array["up_in_search_end_date"] = 'DESC';
                    $order_array["date_created"] = $order_direction;
                }
                $use_leader = true;
            } elseif ($order == 'name') {
                if ($this->pg_module->get_module_config('users', 'hide_user_names')) {
                    $order_array['nickname'] = $order_direction;
                } else {
                    $order_array['fname'] = $order_direction;
                    $order_array['sname'] = $order_direction;
                }
            } else {
                $order_array[$order] = $order_direction;
            }

            $users = $this->Users_model->get_users_list($page, $items_on_page, $order_array, $criteria, array(), true, true);
            $api_data['users'] = $users;
        }
        $this->load->helper("navigation");
        $page_data = get_user_pages_data($url, $items_count, $items_on_page, $page, 'briefPage');
        $page_data["date_format"] = $this->pg_date->get_format('date_literal', 'st');
        $page_data["date_time_format"] = $this->pg_date->get_format('date_time_literal', 'st');
        $page_data["use_leader"] = $use_leader;
        $api_data['page_data'] = $page_data;
        $use_save_search = ($this->session->userdata("auth_type") == "user") ? true : false;
        $api_data['use_save_search'] = $use_save_search;
        $this->set_api_content('data', $api_data);
    }

    private function get_advanced_search_criteria($data)
    {
        $this->load->model('field_editor/models/Field_editor_forms_model');
        $fe_criteria = $this->Field_editor_forms_model->get_search_criteria($this->Users_model->advanced_search_form_gid, $data, $this->Users_model->form_editor_type, false);
        if (!empty($data["search"])) {
            $data["search"] = trim(strip_tags($data["search"]));
            $this->load->model('Field_editor_model');
            $this->Field_editor_model->initialize($this->Users_model->form_editor_type);
            $temp_criteria = $this->Field_editor_model->return_fulltext_criteria($data["search"]);
            $fe_criteria['fields'][] = $temp_criteria['user']['field'];
            $fe_criteria['where_sql'][] = $temp_criteria['user']['where_sql'];
        }
        $common_criteria = $this->Users_model->get_common_criteria($data);
        $advanced_criteria = $this->Users_model->get_advanced_search_criteria($data);
        $criteria = array_merge_recursive($fe_criteria, $common_criteria, $advanced_criteria);
        return $criteria;
    }

    public function my_guests()
    {
        $period = trim(strip_tags($this->input->post('period', true)));
        if (!$period) {
            $period = 'all';
        }
        $page = intval($this->input->post('page'));
        if (!$page) {
            $page = 1;
        }

        $this->_views($period, 'my_guests', $page);
    }

    public function my_visits()
    {
        $period = trim(strip_tags($this->input->post('period', true)));
        if (!$period) {
            $period = 'all';
        }
        $page = intval($this->input->post('page'));
        if (!$page) {
            $page = 1;
        }
        $this->_views($period, 'my_visits', $page);
    }

    private function _views($period = 'all', $type = 'my_guests', $page = 1)
    {
        if (!in_array($period, array('today', 'week', 'month', 'all'))) {
            $period = 'all';
        }
        $this->load->model('users/models/Users_views_model');

        $data = array();
        $criteria = $this->get_advanced_search_criteria($data);

        $order_by['view_date'] = 'DESC';
        if ($type == 'my_guests') {
            $all_viewers = $this->Users_views_model->get_viewers_daily_unique($this->user_id, null, null, $order_by, array(), $period);
        } else {
            $all_viewers = $this->Users_views_model->get_views_daily_unique($this->user_id, null, null, $order_by, array(), $period);
        }
        $need_ids = $view_dates = array();
        $key = ($type == 'my_guests') ? 'id_viewer' : 'id_user';
        foreach ($all_viewers as $viewer) {
            $need_ids[] = $viewer[$key];
            $view_dates[$viewer[$key]] = $viewer['view_date'];
        }

        $items_count = $need_ids ? $this->Users_model->get_users_count($criteria, $need_ids) : 0;
        $items_on_page = $this->pg_module->get_module_config('users', 'items_per_page');
        $this->load->helper('sort_order');
        $this->load->helper("navigation");
        $page = get_exists_page_number($page, $items_count, $items_on_page);
        $url = site_url() . "users/{$type}/{$period}/";
        $page_data = get_user_pages_data($url, $items_count, $items_on_page, $page, 'briefPage');

        if ($items_count) {
            $users_list = $this->Users_model->get_users_list_by_key($page, $items_on_page, $order_by, $criteria, $need_ids, true, true);
            $users = array();
            foreach ($need_ids as $uid) {
                if (isset($users_list[$uid])) {
                    $users[$uid] = $users_list[$uid];
                }
            }
            $api_data['users'] = $users;
            $api_data['view_dates'] = $view_dates;
        }
        $api_data['views_type'] = $type;
        $api_data['period'] = $period;
        $page_data['date_format'] = $this->pg_date->get_format('date_literal', 'st');
        $page_data["date_time_format"] = $this->pg_date->get_format('date_time_literal', 'st');
        $api_data['page_data'] = $page_data;
        $this->set_api_content('data', $api_data);
    }

    private function get_perfect_match_criteria($data)
    {
        $this->load->model('field_editor/models/Field_editor_forms_model');
        $this->load->model('users/models/Users_perfect_match_model');
        $fe_criteria = $this->Field_editor_forms_model->get_search_criteria($this->Users_perfect_match_model->perfect_match_form_gid, $data, $this->Users_perfect_match_model->form_editor_type, false);
        $common_criteria = $this->Users_model->get_common_criteria($data);
        $criteria = array_merge_recursive($fe_criteria, $common_criteria);
        return $criteria;
    }

    /* USERS SERVICES */

    public function available_user_activate_in_search()
    {
        $return = $this->Users_model->service_available_user_activate_in_search_action($this->user_id);
        $this->set_api_content('data', $return);
    }

    public function activate_user_activate_in_search()
    {
        $id_user_service = intval($this->input->post('id_user_service', true));
        $return = $this->Users_model->service_activate_user_activate_in_search($this->user_id, $id_user_service);
        $this->set_api_content('data', $return);
    }

    /**
     * The method checks the availability of featured user.
     *
     * @param int $id_user
     */
    public function available_users_featured()
    {
        $return = $this->Users_model->service_available_users_featured_action($this->user_id);
        $this->set_api_content('data', $return);
    }

    public function activate_users_featured()
    {
        $id_user_service = intval($this->input->post('id_user_service', true));
        $return = $this->Users_model->service_activate_users_featured($this->user_id, $id_user_service);
        $this->set_api_content('data', $return);
    }

    /**
     * The method checks the availability of approve user.
     *
     * @param int $id_user
     */
    public function available_admin_approve()
    {
        $return = $this->Users_model->service_available_admin_approve_action($this->user_id);
        $this->set_api_content('data', $return);
    }

    public function activate_admin_approve()
    {
        $id_user_service = intval($this->input->post('id_user_service', true));
        $return = $this->Users_model->service_activate_admin_approve($this->user_id, $id_user_service);
        $this->set_api_content('data', $return);
    }

    /**
     * The method checks the availability of hide user on site.
     *
     * @param int $id_user
     */
    public function available_hide_on_site()
    {
        $return = $this->Users_model->service_available_hide_on_site_action($this->user_id);
        $this->set_api_content('data', $return);
    }

    public function activate_hide_on_site()
    {
        $id_user_service = intval($this->input->post('id_user_service', true));
        $return = $this->Users_model->service_activate_hide_on_site($this->user_id, $id_user_service);
        $this->set_api_content('data', $return);
    }

    /**
     * The method checks the availability of highlight user in search.
     *
     * @param int $id_user
     */
    public function available_highlight_in_search()
    {
        $return = $this->Users_model->service_available_highlight_in_search_action($this->user_id);
        $this->set_api_content('data', $return);
    }

    public function activate_highlight_in_search()
    {
        $id_user_service = intval($this->input->post('id_user_service', true));
        $return = $this->Users_model->service_activate_highlight_in_search($this->user_id, $id_user_service);
        $this->set_api_content('data', $return);
    }

    /**
     * The method checks the availability of up user in search.
     *
     * @param int $id_user
     */
    public function available_up_in_search()
    {
        $return = $this->Users_model->service_available_up_in_search_action($this->user_id);
        $this->set_api_content('data', $return);
    }

    public function activate_up_in_search()
    {
        $id_user_service = intval($this->input->post('id_user_service', true));
        $return = $this->Users_model->service_activate_up_in_search($this->user_id, $id_user_service);
        $this->set_api_content('data', $return);
    }

    /**
     * The method checks the availability of up user in search.
     *
     * @param int $id_user
     */
    public function available_ability_delete()
    {
        $return = $this->Users_model->service_available_ability_delete_action($this->user_id);
        $this->set_api_content('data', $return);
    }

    public function activate_ability_delete()
    {
        $id_user_service = intval($this->input->post('id_user_service', true));
        $return = $this->Users_model->service_activate_ability_delete($this->user_id, $id_user_service);
        $this->set_api_content('data', $return);
    }

    public function save_profile($profile_section = 'view')
    {
        if ($profile_section == 'personal') {
            $validate_section = null;
        } else {
            $validate_section = $profile_section;
        }
        $fields = array(
            'looking_user_type',
            'nickname',
            'fname',
            'sname',
            'id_country',
            'id_region',
            'id_city',
            'birth_date',
            'age_min',
            'age_max',
        );
        foreach ($fields as $field) {
            $post_data[$field] = filter_input(INPUT_POST, $field);
        }

        $validate_data = $this->Users_model->validate($this->user_id, $post_data, 'user_icon', $validate_section);
        if ($this->Users_model->save_user($this->user_id, $validate_data['data'], 'user_icon')) {
            $this->system_messages->add_message('success', l('success_update_user', 'users'));
        }
    }

    public function profile()
    {
        $profile_section = filter_input(INPUT_POST, 'section', FILTER_SANITIZE_STRING);
        $subsection = filter_input(INPUT_POST, 'subsection', FILTER_SANITIZE_STRING);
        $lang_id = filter_input(INPUT_POST, 'lang_id', FILTER_VALIDATE_INT);
        if (empty($profile_section)) {
            $profile_section = $this->pg_module->is_module_installed('wall_events') ? 'wall' : 'view';
        } else if ($profile_section == 'gallery') {
            if (!in_array($subsection, $this->subsections)) {
                $subsection = $this->subsections['default'];
            }
        }

        $this->load->model('Field_editor_model');
        $this->Field_editor_model->initialize($this->Users_model->form_editor_type);

        $fields_for_select = array();
        if ($profile_section != 'view' && $profile_section != 'wall' && $profile_section != 'gallery' && $profile_section != 'subscriptions') {
            $section = $this->Field_editor_model->get_section_by_gid($profile_section);
            if (!empty($section)) {
                $fields_for_select = $this->Field_editor_model->get_fields_for_select($section['gid']);
            }
        } elseif ($profile_section == 'view') {
            $sections = $this->Field_editor_model->get_section_list();
            $sections_gids = array_keys($sections);
            $fields_for_select = $this->Field_editor_model->get_fields_for_select($sections_gids);
        }
        $this->Users_model->set_additional_fields($fields_for_select);
        $data = $this->Users_model->format_user(
            $this->Users_model->get_user_by_id($this->user_id), false, $lang_id
        );

        if (!$data['activity']) {
            $data['available_activation'] = $this->Users_model->check_available_user_activation($this->user_id);
        }
        if ($profile_section == 'view') {
            foreach ($sections as $sgid => $sdata) {
                $params["where"]["section_gid"] = $sgid;
                $sections[$sgid]['fields'] = $this->Field_editor_model->format_item_fields_for_view($params, $data, $lang_id);
            }
        } elseif (!empty($section)) {
            $params["where"]["section_gid"] = $section['gid'];
            $fields_data = $this->Field_editor_model->get_form_fields_list($data, $params, null, array(), $lang_id);
            $data['fields_data'] = $fields_data;
        }
        if ($profile_section == 'personal') {
            $this->load->model('Properties_model');
            $user_types = $this->Properties_model->get_property('user_type');
            $data['user_types'] = $user_types;
            $age_range = range($this->pg_module->get_module_config('users', 'age_min'), $this->pg_module->get_module_config('users', 'age_max'));
            $data['age_range'] = $age_range;
        }

        $this->pg_seo->set_seo_data($data);

        $data['not_editable_fields'] = $this->Users_model->fields_not_editable;
        $this->set_api_content('data', $data);
    }

    public function save()
    {
        $profile_section = FILTER_INPUT(INPUT_POST, 'section', FILTER_SANITIZE_STRING);
        $post_data = array();
        $validate_section = null;
        $fields_for_select = array();

        $this->load->model('Field_editor_model');
        $this->Field_editor_model->initialize($this->Users_model->form_editor_type);

        if ($profile_section != 'view' && $profile_section != 'wall' && $profile_section != 'gallery' && $profile_section != 'subscriptions') {
            $section = $this->Field_editor_model->get_section_by_gid($profile_section);
            if (!empty($section)) {
                $fields_for_select = $this->Field_editor_model->get_fields_for_select($section['gid']);
            }
        } elseif ($profile_section == 'view') {
            $sections = $this->Field_editor_model->get_section_list();
            $sections_gids = array_keys($sections);
            $fields_for_select = $this->Field_editor_model->get_fields_for_select($sections_gids);
        }
        $this->Users_model->set_additional_fields($fields_for_select);

        if ($profile_section == 'personal') {
            $args = array(
                'looking_user_type' => FILTER_SANITIZE_STRING,
                'nickname' => FILTER_SANITIZE_STRING,
                'fname' => FILTER_SANITIZE_STRING,
                'sname' => FILTER_SANITIZE_STRING,
                'id_country' => FILTER_SANITIZE_STRING,
                'id_region' => FILTER_VALIDATE_INT,
                'id_city' => FILTER_VALIDATE_INT,
                'birth_date' => FILTER_SANITIZE_STRING,
                'age_min' => FILTER_VALIDATE_INT,
                'age_max' => FILTER_VALIDATE_INT,
                'user_logo' => FILTER_SANITIZE_STRING,
            );
            $post_data = filter_input_array(INPUT_POST, $args);
        } else {
            foreach ($fields_for_select as $field) {
                $post_data[$field] = $this->input->post($field, true);
            }
            $validate_section = $profile_section;
        }
        $validate_data = $this->Users_model->validate($this->user_id, $post_data, 'user_icon', $validate_section, 'save');
        $data = $validate_data['data'];
        if (empty($validate_data['errors'])) {
            if ($this->input->post('user_icon_delete') || (isset($_FILES['user_icon']) && is_array($_FILES['user_icon']) && is_uploaded_file($_FILES['user_icon']['tmp_name']))) {
                $this->load->model('Uploads_model');
                if (!empty($data['user_logo_moderation'])) {
                    $this->Uploads_model->delete_upload($this->Users_model->upload_config_id, $this->user_id . '/', $data['user_logo_moderation']);
                    $validate_data['data']['user_logo_moderation'] = '';
                    $this->load->model('Moderation_model');
                    $this->Moderation_model->delete_moderation_item_by_obj($this->Users_model->moderation_type, $this->user_id);
                } elseif (!empty($data['user_logo'])) {
                    $this->Uploads_model->delete_upload($this->Users_model->upload_config_id, $this->user_id . '/', $data['user_logo']);
                    $validate_data['data']['user_logo'] = '';
                }
            }
            $this->Users_model->save_user($this->user_id, $validate_data['data'], 'user_icon');
            $this->load->model('users/models/Auth_model');
            $this->Auth_model->update_user_session_data($this->user_id);
        }
        $this->set_api_content('validate_data', $validate_data);
    }

    public function get_new()
    {
        $count = filter_input(INPUT_POST, 'count', FILTER_VALIDATE_INT);
        $user_type = filter_input(INPUT_POST, 'user_type', FILTER_SANITIZE_STRING);
        $users = $this->Users_model->get_new_users($count, $user_type);
        $this->set_api_content('data', $users);
    }

}
