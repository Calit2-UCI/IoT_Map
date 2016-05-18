<?php


/**
 * Users user side controller
 *
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * */
Class Users extends Controller
{

    /**
     * link to CodeIgniter object
     * @var object
     */
    public $use_email_confirmation = false;
    public $use_approve = false;
    /* private $not_approved_methods = array(
      'change_language',
      'confirm',
      'login',
      'login_form',
      'logout',
      'registration',
      'restore',
      'account',
      ); */
    private $user_id = 0;
    private $subsections = array('default' => 'all', 'photo', 'video', 'albums', 'favorites');

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Users_model");
        $this->load->model('Menu_model');
        $this->use_email_confirmation = (bool) $this->pg_module->get_module_config('users', 'user_confirm');
        $this->use_approve = intval($this->pg_module->get_module_config('users', 'user_approve'));
        if ('user' === $this->session->userdata('auth_type')) {
            $this->user_id = intval($this->session->userdata('user_id'));
        }
    }

    public function ajax_login_form()
    {
        $this->template_lite->view('ajax_login_form');
    }

    public function login_form()
    {
        if ($this->session->userdata("auth_type") == "user") {
            redirect();
        }
        $this->load->model('Menu_model');
        $this->Menu_model->breadcrumbs_set_active(l('header_login', 'users'));
        $this->template_lite->view('login_form');
    }

    public function login()
    {
        $errors = array();

        $this->load->model("users/models/Auth_model");

        $data = array(
            "email" => trim(strip_tags($this->input->post('email', true))),
            "password" => trim(strip_tags($this->input->post('password', true))),
            //"user_open_id" => trim(strip_tags($this->input->post('user_open_id', true))), // Don't delete (openid)
        );

        $validate = $this->Auth_model->validate_login_data($data);
        if (!empty($validate["errors"])) {
            $errors = array_merge($errors, $validate["errors"]);
        }
        // Don't delete (openid)
        /* elseif (isset($validate["data"]["user_open_id"])) {
          $open_id_return = $this->Auth_model->open_id_request($validate["data"]["user_open_id"]);
          if ($open_id_return !== true) {
          $errors = $open_id_return["errors"];
          } else {
          return;
          }
          } */ else {
            $login_return = $this->Auth_model->login_by_email_password($validate["data"]["email"], md5($validate["data"]["password"]));
            if (!empty($login_return["errors"])) {
                $errors = array_merge($errors, $login_return["errors"]);
            }
        }

        if (!empty($errors)) {
            foreach ($errors as $error) {
                $this->system_messages->add_message('error', $error);
            }
            if (isset($login_return['user_data']['confirm']) && !$login_return['user_data']['confirm']) {
                redirect(site_url() . 'users/confirm');
            } else {
                redirect(site_url());
            }
        }

        $this->session->set_flashdata('js_events', 'users:login');
        if (strpos($_SERVER["HTTP_REFERER"], site_url()) !== false) {
            redirect($_SERVER["HTTP_REFERER"], 'hard');
        } else {
            redirect('', 'hard');
        }
    }

    public function logout()
    {
        $this->load->model("users/models/Auth_model");
        $this->Users_model->update_online_status($this->user_id, 0);
        $lang_id = $this->session->userdata('lang_id');
        $this->Auth_model->logoff();
        $this->session->sess_create();
        if ($this->session->userdata('lang_id') != $lang_id) {
            $this->session->set_userdata("lang_id", $lang_id);
        }
        $this->session->set_flashdata('js_events', 'users:logout');
        redirect('', 'hard');
    }

    public function change_language($lang_id)
    {
        $lang_id = intval($lang_id);
        $this->session->set_userdata("lang_id", $lang_id);
        $old_code = $this->pg_language->languages[$this->pg_language->current_lang_id]["code"];
        $this->pg_language->current_lang_id = $lang_id;
        $code = $this->pg_language->languages[$lang_id]["code"];
        $_SERVER["HTTP_REFERER"] = str_replace("/" . $old_code . "/", "/" . $code . "/", $_SERVER["HTTP_REFERER"]);
        $site_url = str_replace("/" . $code . "/", "", site_url());

        if ($this->session->userdata('auth_type') == 'user') {
            $save_data["lang_id"] = $lang_id;
            $this->Users_model->save_user($this->user_id, $save_data);
        }

        if (strpos($_SERVER["HTTP_REFERER"], $site_url) !== false) {
            redirect($_SERVER["HTTP_REFERER"]);
        } else {
            redirect();
        }
    }

    public function settings()
    {
        $this->load->model('users/models/Auth_model');
        $this->Auth_model->update_user_session_data($this->user_id);
        $use_repassword = $this->pg_module->get_module_config('users', 'use_repassword');
        if ($this->input->post('btn_password_save')) {
            $post_data = array(
                'password' => $this->input->post('password', true),
            );
            if ($use_repassword) {
                $post_data['repassword'] = $this->input->post('repassword', true);
            }
            $validate_data = $this->Users_model->validate($this->user_id, $post_data);

            if (!empty($validate_data["errors"])) {
                $this->system_messages->add_message('error', $validate_data["errors"]);
            } else {
                $save_data = $validate_data["data"];
                $save_password = $save_data["password"];
                $save_data["password"] = md5($save_data["password"]);
                $this->Users_model->save_user($this->user_id, $save_data);

                // send notification
                $this->load->model('Notifications_model');
                $user_data = $this->Users_model->get_user_by_id($this->user_id);
                $user_data["password"] = $save_password;
                $this->Notifications_model->send_notification($user_data["email"], 'users_change_password', $user_data, '', $user_data['lang_id']);

                $this->system_messages->add_message('success', l('success_user_updated', 'users'));
            }
        }

        if ($this->input->post('btn_contact_save')) {
            $post_data = array(
                "email" => $this->input->post('email', true),
                "phone" => $this->input->post('phone', true),
                "show_adult" => $this->input->post('show_adult', true),
            );
            $validate_data = $this->Users_model->validate($this->user_id, $post_data);

            if (!empty($validate_data["errors"])) {
                $this->system_messages->add_message('error', $validate_data["errors"]);
            } else {
                $user_data = $this->Users_model->get_user_by_id($this->user_id);
                $save_data = $validate_data["data"];
                $this->Users_model->save_user($this->user_id, $save_data);

                // send notification
                if ($user_data["email"] !== $save_data["email"]) {
                    $this->load->model('Notifications_model');
                    $user_data["new_email"] = $save_data["email"];
                    $user_data = array_merge($user_data, $save_data);
                    $this->Notifications_model->send_notification($user_data["email"], 'users_change_email', $user_data, '', $user_data['lang_id']);
                    $this->Notifications_model->send_notification($user_data["new_email"], 'users_change_email', $user_data, '', $user_data['lang_id']);
                }

                $this->load->model('users/models/Auth_model');
                $this->Auth_model->update_user_session_data($this->user_id);

                $this->system_messages->add_message('success', l('success_user_updated', 'users'));
            }
        }

        if ($this->input->post('btn_subscriptions_save') && $this->pg_module->is_module_installed('subscriptions')) {
            // Save user subscribers
            $user_subscriptions_list = $this->input->post('user_subscriptions_list', true);
            $this->load->model('subscriptions/models/Subscriptions_users_model');
            $this->Subscriptions_users_model->save_user_subscriptions($this->user_id, $user_subscriptions_list);
        }

        $user = $this->Users_model->get_user_by_id($this->user_id);
        $this->pg_seo->set_seo_data($user);

        $this->load->helper('seo');
        $this->session->set_userdata(array('service_redirect' => rewrite_link('users', 'settings')));

        // breadcrumbs
        $this->load->model('Menu_model');
        $this->Menu_model->breadcrumbs_set_parent('settings-item');

        $this->template_lite->assign('use_repassword', $use_repassword);
        $this->template_lite->assign('user_id', $this->user_id);
        $this->template_lite->assign('user', $user);
        $this->template_lite->view('account_settings');
    }

    public function account($action = 'services', $page = 1)
    {
        $page = intval($page);

        $this->load->model('users/models/Auth_model');
        $this->Auth_model->update_user_session_data($this->user_id);

        $user = $this->Users_model->get_user_by_id($this->user_id);
        $this->pg_seo->set_seo_data($user);

        // breadcrumbs
        $this->load->model('Menu_model');
        $this->Menu_model->breadcrumbs_set_parent('account-item');

        $this->load->helper('seo');
        $base_url = rewrite_link('users', 'account', array('action' => $action));
        $this->setAccountMenu($action);

        $this->template_lite->assign('base_url', $base_url);
        $this->template_lite->assign('page', $page);
        $this->template_lite->assign('action', $action);
        $this->template_lite->assign('user_id', $this->user_id);
        $this->template_lite->assign('user', $user);
        $this->template_lite->view('account');
    }

    private function setAccountMenu($action)
    {
        switch ($action) {
            case 'services':
                $this->session->set_userdata(array('service_redirect' => rewrite_link('users', 'account', array('action' => 'services'))));
                $this->session->set_userdata(array('service_activate_redirect' => rewrite_link('users', 'account', array('asction' => 'services'))));
                $this->Menu_model->breadcrumbs_set_active(l('header_services', 'users'));
                break;
            /* case 'my_services':
              $this->session->set_userdata(array('service_activate_redirect' => rewrite_link('users', 'account', array('action'=>'my_services'))));
              $this->Menu_model->breadcrumbs_set_active(l('header_my_services', 'users'));
              break; */
            case 'update':
                $this->Menu_model->breadcrumbs_set_active(l('header_account_update', 'users'));
                break;
            case 'payments_history':
                $this->Menu_model->breadcrumbs_set_active(l('header_my_payments_statistic', 'payments'));
                break;
            case 'banners':
                $this->Menu_model->breadcrumbs_set_active(l('header_my_banners', 'banners'));
                break;
            case 'memberships':
                $this->Menu_model->breadcrumbs_set_active(l('header_memberships', 'users'));
                break;
            case 'send_money':
                $this->Menu_model->breadcrumbs_set_active(l('send_money', 'send_money'));
                break;
        }
    }

    /**
     * Don't delete (openid)
     *
     */
    /* function openid_response() {
      $this->load->model("users/models/Auth_model");
      $open_id_return = $this->Auth_model->open_id_response();
      if (!empty($open_id_return["errors"])) {
      foreach ($open_id_return["errors"] as $error)
      $this->system_messages->add_message('error', $error);
      }
      redirect();
      } */

    public function ajax_view_map_user_location()
    {
        $return = array("errors" => "", "html" => "");

        if (!$this->pg_module->is_module_installed('geomap')) {
            echo json_encode($return);
        }

        $id_user = $this->input->post('id', true);
        $load_map_scripts = $this->input->post('load_map_scripts', true);

        if ($id_user) {
            $user = $this->Users_model->get_user_by_id($id_user, true);

            $markers[] = array(
                'gid' => $user['id'],
                'country' => $user['country'],
                'region' => $user['region'],
                'city' => $user['city'],
                'address' => $user['address'],
                'lat' => (float) $user['lat'],
                'lon' => (float) $user['lon'],
                //'info' => $user['output_name'] . ", " . $user['age'],
				'info' => $user['output_name'] . "<br/>" . $user['address']." ".$user[postal_code],
            );
            $this->template_lite->assign('markers', $markers);
            $this->template_lite->assign('header', $user["output_name"]." - Google Maps");
            $this->template_lite->assign('load_map_scripts', $load_map_scripts);

            $return['html'] = $this->template_lite->fetch('ajax_view_map_user_location', 'user', 'users');
        }

        echo json_encode($return);
    }

    public function profile($profile_section = '', $subsection = 'all')
    {
        $subsection = trim(strip_tags($subsection));
        if (!$profile_section) {
            $profile_section = $this->pg_module->is_module_installed('wall_events') ? 'wall' : 'view';
        }
        if ($profile_section == 'gallery' && !in_array($subsection, $this->subsections)) {
            $subsection = $this->subsections['default'];
        }

        $this->load->model('Field_editor_model');
        $this->Field_editor_model->initialize($this->Users_model->form_editor_type);
        $fields_for_select = array();
        $sections = array();
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

        $data = $this->Users_model->get_user_by_id($this->user_id);

        if ($this->input->post('btn_register')) {
            $post_data = array();
            $validate_section = null;

            if ($profile_section == 'personal') {
                $post_data = array(
                    'looking_user_type' => $this->input->post('looking_user_type', true),
                    'nickname' => $this->input->post('nickname', true),
                    'fname' => $this->input->post('fname', true),
                    'sname' => $this->input->post('sname', true),
                    'id_country' => $this->input->post('id_country', true),
                    'id_region' => $this->input->post('id_region', true),
                    'id_city' => $this->input->post('id_city', true),
                    'birth_date' => $this->input->post('birth_date', true),
                    'age_min' => $this->input->post('age_min', true),
                    'age_max' => $this->input->post('age_max', true),
                    'lat' => $this->input->post('lat', true),
                    'lon' => $this->input->post('lon', true),
					'address' => $this->input->post('address', true),
					'website' => $this->input->post('website', true),
					'instagram' => $this->input->post('instagram', true),
					'twitter' => $this->input->post('twitter', true),
					'facebook' => $this->input->post('facebook', true),
					'linkedin' => $this->input->post('linkedin', true),
					
                );
            } else {
                foreach ($fields_for_select as $field) {
                    $post_data[$field] = $this->input->post($field, true);
                }
                $validate_section = $profile_section;
            }

            /*
              if($this->pg_module->is_module_installed('geomap')) {
              $this->load->model('geomap/models/Geomap_model');
              $this->load->model('geomap/models/Geomap_settings_model');
              $map_gid = $this->Geomap_model->get_default_driver_gid();
              $post_settings['lat'] = $post_data['lat'];
              $post_settings['lon'] = $post_data['lon'];

              $validate_map = $this->Geomap_settings_model->validate_settings($post_settings);
              if(empty($validate_map['errors'])) {
              $this->Geomap_settings_model->save_settings($map_gid, 0, $this->_user_id, 'profile_view', $validate_map['data']);
              }
              } */

            $validate_data = $this->Users_model->validate($this->user_id, $post_data, 'user_icon', $validate_section);

            if (!empty($validate_data['errors'])) {
                $this->system_messages->add_message('error', $validate_data['errors']);
                //$data = $validate_data['data'];
            } else {
                if ($this->input->post('user_icon_delete') || (isset($_FILES['user_icon']) && is_array($_FILES['user_icon']) && is_uploaded_file($_FILES['user_icon']['tmp_name']))) {
                    $this->load->model('Uploads_model');
                    if ($data['user_logo_moderation']) {
                        $this->Uploads_model->delete_upload($this->Users_model->upload_config_id, $this->user_id, $data['user_logo_moderation']);
                        $validate_data['data']['user_logo_moderation'] = '';
                        $this->load->model('Moderation_model');
                        $this->Moderation_model->delete_moderation_item_by_obj($this->Users_model->moderation_type, $this->user_id);
                    } elseif ($data['user_logo']) {
                        $this->Uploads_model->delete_upload($this->Users_model->upload_config_id, $this->user_id, $data['user_logo']);
                        $validate_data['data']['user_logo'] = '';
                    }
                }
                if ($this->Users_model->save_user($this->user_id, $validate_data['data'], 'user_icon')) {
                    $this->system_messages->add_message('success', l('success_update_user', 'users'));
                }

                $this->load->model('users/models/Auth_model');
                $this->Auth_model->update_user_session_data($this->user_id);

                $seo_data = $data;
                $seo_data['section'] = $profile_section;
                $seo_data['section-code'] = $profile_section;
                $seo_data['section-name'] = l('filter_section_' . $profile_section, 'users');

                if ($subsection != 'all') {
                    $seo_data['subsection'] = $subsection;
                    $seo_data['subsection-code'] = $subsection;
                    $seo_data['subsection-name'] = l($subsection, 'media');
                }

                $this->load->helper('seo');
                $url = rewrite_link('users', 'profile', $seo_data);
                redirect($url);
            }
        }

        $this->template_lite->assign('action', $profile_section);

        $lang_id = $this->pg_language->current_lang_id;
        $data = $this->Users_model->format_user($data, false, $lang_id);

        if (empty($data['activity'])) {
            $data['available_activation'] = $this->Users_model->check_available_user_activation($this->user_id);
            $service_status = $this->Users_model->service_available_user_activate_in_search_action($this->user_id);
            if ($service_status["content_buy_block"] == false && ($data['user_logo'] || $data['user_logo_moderation']) && $data['id_region']) {
                $data['activity'] = 1;
            }
        }
        if ($profile_section == 'view') {
            foreach ($sections as $sgid => $sdata) {
                $params["where"]["section_gid"] = $sgid;
                $sections[$sgid]['fields'] = $this->Field_editor_model->format_item_fields_for_view($params, $data);
            }
        } elseif (!empty($section)) {
            $params["where"]["section_gid"] = $section['gid'];
            $fields_data = $this->Field_editor_model->get_form_fields_list($data, $params);
            $this->template_lite->assign('fields_data', $fields_data);
        }
        if ($profile_section == 'personal') {
            $this->load->model('Properties_model');
            $user_types = $this->Properties_model->get_property('user_type');
            $this->template_lite->assign('user_types', $user_types);
            $age_range = range($this->pg_module->get_module_config('users', 'age_min'), $this->pg_module->get_module_config('users', 'age_max'));
            $this->template_lite->assign('age_range', $age_range);
        }

        $this->load->helper('seo');

        if ($profile_section == 'gallery') {
            $gallery_filters = array();

            foreach ($this->subsections as $subsection_code) {
                $subsection_name = l($subsection_code, 'media');

                $seo_data = $data;
                $seo_data['section'] = $profile_section;
                $seo_data['section-code'] = $profile_section;
                $seo_data['section-name'] = l('filter_section_' . $profile_section, 'users');

                $seo_data['subsection'] = $subsection_code;
                $seo_data['subsection-code'] = $subsection_code;
                $seo_data['subsection-name'] = $subsection_name;
                $subsection_link = rewrite_link('users', 'view', $seo_data);
                $gallery_filters[$subsection_code] = array('link' => $subsection_link, 'name' => $subsection_name);
            }

            $this->template_lite->assign('gallery_filters', $gallery_filters);

            $location_base_url = function($subsection_code, $subsection_name) use($data, $profile_section) {
                $seo_data = $data;
                $seo_data['section'] = $profile_section;
                $seo_data['section-code'] = $profile_section;
                $seo_data['section-name'] = l('filter_section_' . $profile_section, 'users');

                $seo_data['subsection'] = $subsection_code;
                $seo_data['subsection-code'] = $subsection_code;
                $seo_data['subsection-name'] = $subsection_name;

                $subsection_link = rewrite_link('users', 'profile', $seo_data);
                return $subsection_link;
            };
            $this->template_lite->assign('location_base_url', $location_base_url);
        }

        $this->pg_seo->set_seo_data($data);

        if (!empty($section)) {
            $section_name = $section['name'];
        } else {
            $section_name = l("filter_section_" . $profile_section, "users");
        }

        // breadcrumbs
        $this->load->model('Menu_model');
        $this->Menu_model->breadcrumbs_set_parent('my-profile-item');
        $this->Menu_model->breadcrumbs_set_active($section_name);

        $page_data['date_format'] = $this->pg_date->get_format('date_literal', 'st');
        $page_data["date_time_format"] = $this->pg_date->get_format('date_time_literal', 'st');

        $not_editable_fields = $this->Users_model->fields_not_editable;
        foreach ($not_editable_fields as $field) {
            $not_editable_fields[$field] = 1;
        }

        $data['services_status'] = $this->Users_model->services_status($data);

        if ($profile_section == 'gallery') {
            $gallery_filters = array();

            foreach ($this->subsections as $subsection_code) {
                $subsection_name = l($subsection_code, 'media');

                $seo_data = $data;
                $seo_data['section'] = $profile_section;
                $seo_data['section-code'] = $profile_section;
                $seo_data['section-name'] = l('filter_section_' . $profile_section, 'users');

                $seo_data['subsection'] = $subsection_code;
                $seo_data['subsection-code'] = $subsection_code;
                $seo_data['subsection-name'] = $subsection_name;
                $subsection_link = rewrite_link('users', 'profile', $seo_data);
                $gallery_filters[$subsection_code] = array('link' => $subsection_link, 'name' => $subsection_name);
            }

            $this->template_lite->assign('gallery_filters', $gallery_filters);
        }

        $seo_data = $data;
        $seo_data['section'] = $profile_section;
        $seo_data['section-code'] = $profile_section;
        $seo_data['section-name'] = l('filter_section_' . $profile_section, 'users');

        if ($subsection != 'all') {
            $seo_data['subsection'] = $subsection;
            $seo_data['subsection-code'] = $subsection;
            $seo_data['subsection-name'] = l($subsection, 'media');
        }

        $url = rewrite_link('users', 'profile', $seo_data);
        $location_full_url = rewrite_link('users', 'profile', $seo_data);
        $this->session->set_userdata(array('service_redirect' => $location_full_url));

        $age_min = $this->pg_module->get_module_config('users', 'age_min');
        $age_max = $this->pg_module->get_module_config('users', 'age_max');
        $this->template_lite->assign('age_min', $age_min);
        $this->template_lite->assign('age_max', $age_max);

        $this->template_lite->assign('not_editable_fields', $not_editable_fields);
        $this->template_lite->assign('page_data', $page_data);
        $this->template_lite->assign('data', $data);
        $this->template_lite->assign('sections', $sections);
        $this->template_lite->assign('subsection', $subsection);
        $this->template_lite->assign('user_id', $this->user_id);
        $this->template_lite->assign('edit_mode', 1);
        $this->template_lite->view('profile');
    }

    public function registration()
    {
        if ($this->session->userdata('auth_type') == 'user') {
            //show_404();
            //return;
            redirect(site_url() . 'start/homepage');
        }
        $age_min = $this->pg_module->get_module_config('users', 'age_min');
        $age_max = $this->pg_module->get_module_config('users', 'age_max');
        $use_repassword = $this->pg_module->get_module_config('users', 'use_repassword');
        $this->load->model('Properties_model');
        $user_types = $this->Properties_model->get_property('user_type');
        if ($this->input->post('btn_register')) {
            $post_data = array(
                'email' => $this->input->post('email', true),
                'password' => $this->input->post('password', true),
                'nickname' => $this->input->post('nickname', true),
                'birth_date' => $this->input->post('birth_date', true),
                'id_country' => $this->input->post('id_country', true),
                'id_region' => $this->input->post('id_region', true),
                'id_city' => $this->input->post('id_city', true),
                'user_type' => $this->input->post('user_type', true),
                'looking_user_type' => $this->input->post('looking_user_type', true),
                'confirmation' => $this->input->post('confirmation', true),
                'age_min' => $age_min,
                'age_max' => $age_max,
				'lat' => $this->input->post('lat', true),
                'lon' => $this->input->post('lon', true),
            );

            if ($use_repassword) {
                $post_data['repassword'] = $this->input->post('repassword', true);
            }

            $is_captcha_valid = ($this->input->post('captcha_confirmation', true) == $this->session->userdata('captcha_word')) ? 1 : 0;
            if (!$is_captcha_valid || !$post_data['confirmation']) {
                $data = $post_data;
                if (!$is_captcha_valid) {
                    $this->system_messages->add_message('error', array('captcha_confirmation' => l('error_invalid_captcha', 'users')));
                }
                if (!$post_data['confirmation']) {
                    $this->system_messages->add_message('error', array('confirmation' => l('error_no_confirmation', 'users')));
                }
            } else {
                $validate_data = $this->Users_model->validate(null, $post_data, 'user_icon');
                if (!empty($validate_data["success"])) {
                    $this->system_messages->add_message('success', $validate_data["success"]);
                }
                if (!empty($validate_data["errors"])) {
                    $this->system_messages->add_message('error', $validate_data["errors"]);
                    $data = $validate_data["data"];
                } else {
                    $data = $validate_data["data"];
                    $data['activity'] = 0;
                    if ($this->use_email_confirmation) {
                        $data["confirm"] = 0;
                        $data["confirm_code"] = substr(md5(date("Y-m-d H:i:s") . $data["nickname"]), 0, 10);
                    } else {
                        $data["confirm"] = 1;
                        $data["confirm_code"] = "";
                    }
                    $data["approved"] = $this->use_approve ? 0 : 1;
                    $saved_password = $data["password"];
                    $data["password"] = md5($data["password"]);
                    $data["lang_id"] = $this->session->userdata("lang_id");
                    if (!$data["lang_id"]) {
                        $data["lang_id"] = $this->pg_language->get_default_lang_id();
                    }

                    $user_id = $this->Users_model->save_user(null, $data, 'user_icon');

                    if ($this->pg_module->is_module_installed('incomplete_signup')) {
                        $this->load->model("incomplete_signup/models/Incomplete_signup_model");
                        $this->Incomplete_signup_model->delete_unregistered_user_by_email($data['email']);
                    }
                    if ($this->pg_module->is_module_installed('subscriptions')) {
                        // Save user subscribers
                        $this->load->model("subscriptions/models/Subscriptions_users_model");
                        $this->Subscriptions_users_model->save_user_subscriptions($user_id, $this->input->post('user_subscriptions_list'));
                    }
                    $this->load->model('Notifications_model');
                    $data["password"] = $saved_password;
                    if ($this->use_email_confirmation) {
                        $link = site_url() . "users/confirm/" . $data["confirm_code"];
                        //$data["confirm_code"] = str_replace("[code]", $data["confirm_code"], l('confirm_text', 'users'));
                        $data["confirm_block"] = l('confirmation_code', 'users') . ': ' . $data["confirm_code"] . "\n\n" . str_replace("[link]", $link, l('confirm_block_text', 'users'));
                    }
                    $this->Notifications_model->send_notification($data["email"], 'users_registration', $data, '', $data['lang_id']);

                    if (!$this->use_email_confirmation) {
                        $this->load->model("users/models/Auth_model");
                        $auth_data = $this->Auth_model->login($user_id);
                        if (!empty($auth_data["errors"])) {
                            $this->system_messages->add_message('error', $auth_data["errors"]);
                            redirect(site_url() . 'start/index');
                        }
                        redirect(site_url() . 'users/profile', 'hard');
                    } else {
                        $this->system_messages->add_message('info', l('info_please_checkout_mailbox', 'users'));
                        redirect(site_url() . 'users/confirm');
                    }
                }
            }
        } else {
            $post_data = array('email' => $this->input->post('email', true));
            $validate_data = $this->Users_model->validate(null, $post_data);
            $data = $validate_data['data'];
            $data['password'] = $this->input->post('password', true);
            // Differ looking_user_type from user_type
            if (!isset($data['user_type']) && !isset($data['looking_user_type']) && count($user_types['option'])) {
                $data['looking_user_type'] = key(array_slice($user_types['option'], 1, 1));
            }
        }

        $this->load->plugin('captcha');
        $this->config->load('captcha_settings');
        $captcha_settings = $this->config->item('captcha_settings');
        $captcha = create_captcha($captcha_settings);
        $this->session->set_userdata('captcha_word', $captcha['word']);
        $data['captcha_image'] = $captcha['image'];
        $data['captcha_word_length'] = strlen($captcha['word']);

        $this->template_lite->assign('user_types', $user_types);

        // breadcrumbs
        $this->load->model('Menu_model');
        $this->Menu_model->breadcrumbs_set_active(l('header_register', 'users'));
        $this->template_lite->assign('use_repassword', $use_repassword);
        $this->template_lite->assign('age_min', $age_min);
        $this->template_lite->assign('age_max', $age_max);
        $this->template_lite->assign('data', $data);
        $this->template_lite->view('register');
    }

    public function restore()
    {
        if ($this->session->userdata("auth_type") == "user") {
            redirect();
        }

        if ($this->input->post('btn_save')) {
            $email = strip_tags($this->input->post("email", true));
            $user_data = $this->Users_model->get_user_by_email($email);
            if (empty($user_data) || !$user_data["id"]) {
                $this->system_messages->add_message('error', l('error_user_no_exists', 'users'));
            } elseif (!$user_data["confirm"]) {
                $this->system_messages->add_message('error', l('error_unconfirmed_user', 'users'));
            } elseif (!$user_data["approved"]) {
                $this->system_messages->add_message('error', l('error_user_is_blocked', 'users'));
            } else {
                $user_data["password"] = $new_password = substr(md5(date("Y-m-d H:i:s") . $user_data["email"]), 0, 6);
                $data["password"] = md5($new_password);
                $this->Users_model->save_user($user_data["id"], $data);

                $this->load->model('Notifications_model');
                $this->Notifications_model->send_notification($user_data["email"], 'users_fogot_password', $user_data, '', $user_data['lang_id']);
                $this->system_messages->add_message('success', l('success_restore_mail_sent', 'users'));
                redirect(site_url() . "users/restore");
            }
        }
        // breadcrumbs
        $this->load->model('Menu_model');
        $this->Menu_model->breadcrumbs_set_active(l('header_restore_password', 'users'));

        $this->template_lite->view('forgot_form');
    }

    public function confirm($code = '')
    {
        $code = trim(strip_tags($code));
        if (!$code) {
            $code = $this->input->post('code', true);
        }
        if (!$code) {
            $this->load->model('Menu_model');
            $this->Menu_model->breadcrumbs_set_active(l('header_confirm_email', 'users'));
            $this->template_lite->view('confirm_form');
            return;
        }
        $user = $this->Users_model->get_user_by_confirm_code($code);
        if (empty($user)) {
            $this->system_messages->add_message('error', l('error_user_no_exists', 'users'));
            redirect();
        } elseif ($user["confirm"]) {
            $this->system_messages->add_message('error', l('error_user_already_confirm', 'users'));
            redirect(site_url() . "users/profile", 'hard');
        } else {
            $data["confirm"] = 1;
            $this->Users_model->save_user($user["id"], $data);

            $this->system_messages->add_message('success', l('success_confirm', 'users'));

            $this->load->model("users/models/Auth_model");
            $auth_data = $this->Auth_model->login($user["id"]);
            if (!empty($auth_data["errors"])) {
                $this->system_messages->add_message('error', $auth_data["errors"]);
            }
            redirect(site_url() . "users/profile", 'hard');
        }
    }

	
    public function ajax_get_users_data($page = 1)
    {
        $return = array();
        $params = array();
        if (!$page) {
            $page = intval($this->input->post('page', true));
            if (!$page) {
                $page = 1;
            }
        }

        $search_string = trim(strip_tags($this->input->post('search', true)));
        if (!empty($search_string)) {
            $hide_user_names = $this->pg_module->get_module_config('users', 'hide_user_names');
            if ($hide_user_names) {
                $search_string_escape = $this->db->escape("%" . $search_string . "%");
                $params["where_sql"][] = "(nickname LIKE " . $search_string_escape
                    . " OR fname LIKE " . $search_string_escape
                    . " OR sname LIKE " . $search_string_escape . ")";            } else {
                $search_string_escape = $this->db->escape("%" . $search_string . "%");
                $params["where_sql"][] = "(nickname LIKE " . $search_string_escape
                    . " OR fname LIKE " . $search_string_escape
                    . " OR sname LIKE " . $search_string_escape . ")";
            }
        }

        $selected = $this->input->post('selected', true);
        if (!empty($selected)) {
            $params["where_sql"][] = "id NOT IN (" . implode($selected) . ")";
        }

        $user_type = $this->input->post('user_type', true);
        if ($user_type) {
            $params["where"]["user_type"] = $user_type;
        }

        $items_on_page = $this->pg_module->get_module_config('start', 'admin_items_per_page');
        $items = $this->Users_model->get_users_list_by_key($page, $items_on_page, array("nickname" => "asc"), $params, array(), true, true);

        $return["all"] = $this->Users_model->get_users_count($params);
        $return["items"] = $items;
        $return["current_page"] = $page;
        $return["pages"] = ceil($return["all"] / $items_on_page);

        echo json_encode($return);
        return;
    }

    public function ajax_get_selected_users()
    {
        $selected = $this->input->post('selected', true);
        $selected = array_slice(array_unique(array_map('intval', (array) $selected)), 0, 1000);
        if (!empty($selected)) {
            $return = $this->Users_model->get_users_list(null, null, array("nickname" => "asc"), array(), $selected, true, true);
        } else {
            $return = array();
        }
        echo json_encode($return);
        return;
    }

    public function ajax_get_users_form($max_select = 1)
    {
        $selected = $this->input->post('selected', true);

        if (!empty($selected)) {
            $data["selected"] = $this->Users_model->get_users_list(null, null, array("nickname" => "asc"), array(), $selected, false);
        } else {
            $data["selected"] = array();
        }
        $data["max_select"] = $max_select ? $max_select : 0;

        $this->template_lite->assign('select_data', $data);
        $this->template_lite->view('ajax_user_select_form');
    }

	
    public function search($order = "default", $order_direction = "DESC", $page = 1)
    {
        if (empty($_POST)) {
            $current_settings = ($this->session->userdata("users_search")) ? $this->session->userdata("users_search") : array();
            $data = (!empty($current_settings)) ? $current_settings : array();
        } else {
            foreach ($_POST as $key => $val) {
                $data[$key] = $this->input->post($key, true);
            }
            $data = array_merge($this->Users_model->get_minimum_search_data(), $data);
        }
        $this->template_lite->assign('block', $this->searchListBlock($data, $order, $order_direction, $page, 'advanced'));

        if ((isset($data['search'])) and ( !empty($data['search']))) {
            $this->template_lite->assign('search_text', $data['search']);
        }

        $this->Menu_model->breadcrumbs_set_parent('search_item');
        $this->template_lite->view('users_list');
    }

    public function ajax_search($order = "default", $order_direction = "DESC", $page = 1)
    {
        if (empty($_POST)) {
            $current_settings = ($this->session->userdata("users_search")) ? $this->session->userdata("users_search") : array();
            $data = (!empty($current_settings)) ? $current_settings : array();
        } else {
            foreach ($_POST as $key => $val) {
                $data[$key] = $this->input->post($key, true);
            }
            $data = array_merge($this->Users_model->get_minimum_search_data(), $data);
        }
        echo $this->searchListBlock($data, $order, $order_direction, $page, 'advanced');
    }

    public function ajax_search_counts($type = 'advanced')
    {
        $result = array('count' => 0, 'error' => '', 'string' => '');
        if (!empty($_POST)) {
            foreach ($_POST as $key => $val) {
                $data[$key] = $this->input->post($key, true);
            }
            switch ($type) {
                case 'perfect_match':
                    $criteria = $this->getPerfectMatchCriteria($data);
                    break;
                case 'advanced':
                default:
                    $criteria = $this->getAdvancedSearchCriteria($data);
                    break;
            }
            $result["count"] = $this->Users_model->get_users_count($criteria);
            $result["string"] = str_replace("[count]", $result["count"], l('user_results_string', 'users'));
        }
        echo json_encode($result);
    }

    public function ajax_perfect_match($order = "default", $order_direction = "DESC", $page = 1)
    {
        if (empty($_POST)) {
            $current_settings = $this->session->userdata("perfect_match") ? $this->session->userdata("perfect_match") : array();
            $data = (!empty($current_settings)) ? $current_settings : array();
        } else {
            $this->load->model('users/models/Users_perfect_match_model');
            $this->load->model('Field_editor_model');
            $this->Field_editor_model->initialize($this->Users_perfect_match_model->form_editor_type);

            $this->load->model('field_editor/models/Field_editor_forms_model');
            $form = $this->Field_editor_forms_model->get_form_by_gid($this->Users_perfect_match_model->perfect_match_form_gid, $this->Users_perfect_match_model->form_editor_type);
            $fields_for_search = $this->Field_editor_model->get_fields_names_for_search($form);
            foreach ($_POST as $key => $val) {
                $data[$key] = $this->input->post($key, true);
            }
            foreach ($fields_for_search as $field) {
                if ($this->input->post($field) !== false) {
                    $data[$field] = $this->input->post($field, true);
                }
            }
            $this->Users_perfect_match_model->set_additional_fields($fields_for_search);
            $validate_data = $this->Users_perfect_match_model->validate($data);
            if (empty($validate_data['errors'])) {
                $this->Users_perfect_match_model->save_perfect_match($this->user_id, $validate_data['data'], 'all');
            }
        }
        echo $this->searchListBlock($data, $order, $order_direction, $page, 'perfect_match');
    }

    private function searchListBlock($data = array(), $order = "default", $order_direction = "DESC", $page = 1, $search_type = 'advanced')
    {
        $this->template_lite->assign('user_id', $this->user_id);
        switch ($search_type) {
            case 'perfect_match':
                $current_settings = $this->session->userdata("perfect_match") ? $this->session->userdata("perfect_match") : array();
                if (!empty($data)) {
                    $current_settings = $data;
                }
                $this->session->set_userdata("perfect_match", $current_settings);
                $criteria = $this->getPerfectMatchCriteria($current_settings);
                $search_url = site_url() . "users/perfect_match";
                $url = site_url() . "users/perfect_match/" . $order . "/" . $order_direction . "/";
                break;
            case 'advanced':
            default:
                $current_settings = $this->session->userdata("users_search") ? $this->session->userdata("users_search") : $this->Users_model->get_default_search_data();
                if (!empty($data)) {
                    $current_settings = $data;
                }
                $this->session->set_userdata("users_search", $current_settings);
                $criteria = $this->getAdvancedSearchCriteria($current_settings);
                $search_url = site_url() . "users/search";
                $url = site_url() . "users/search/" . $order . "/" . $order_direction . "/";

                /* highligth in search */
                $hl_data['service_highlight'] = $this->Users_model->service_status_highlight_in_search($this->Users_model->format_user($this->Users_model->get_user_by_id($this->user_id)));
                if ($hl_data['service_highlight']['status']) {
                    $this->load->helper('seo');
                    $this->session->set_userdata(array('service_redirect' => rewrite_link('users', 'search')));
                }
                $this->template_lite->assign('hl_data', $hl_data);

                break;
        }
        $this->template_lite->assign('search_type', $search_type);
        $order = trim(strip_tags($order));
        if (!$order) {
            $order = "date_created";
        }
        $this->template_lite->assign('order', $order);

        $order_direction = strtoupper(trim(strip_tags($order_direction)));
        if ($order_direction != 'DESC') {
            $order_direction = "ASC";
        }
        $this->template_lite->assign('order_direction', $order_direction);

        $items_count = $this->Users_model->get_users_count($criteria);

        if (!$page) {
            $page = 1;
        }
        $items_on_page = $this->pg_module->get_module_config('users', 'items_per_page');
        $this->load->helper('sort_order');
        $page = get_exists_page_number($page, $items_count, $items_on_page);

        $sort_data = array(
            "url" => $search_url,
            "order" => $order,
            "direction" => $order_direction,
            "links" => array(
                "default" => l('field_default_sorter', 'users'),  
                "name" => l('field_name', 'users'),
                //"views_count" => l('field_views_count', 'users'),
                "date_created" => l('field_date_created', 'users'),
            )
        );
        $this->template_lite->assign('sort_data', $sort_data);

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
            } else {
                if ($order == 'name') {
                    if ($this->pg_module->get_module_config('users', 'hide_user_names')) {
                        $order_array['nickname'] = $order_direction;
                    } else {
                        $order_array['fname'] = $order_direction;
                        $order_array['sname'] = $order_direction;
                    }
                } else {
                    $order_array[$order] = $order_direction;
                }
            }
            $lang_id = $this->pg_language->current_lang_id;
            $users = $this->Users_model->get_users_list($page, $items_on_page, $order_array, $criteria, array(), true, false, $lang_id);
            $this->template_lite->assign('users', $users);
        }
        $this->load->helper("navigation");
        $page_data = get_user_pages_data($url, $items_count, $items_on_page, $page, 'briefPage');
        $page_data["date_format"] = $this->pg_date->get_format('date_literal', 'st');
        $page_data["date_time_format"] = $this->pg_date->get_format('date_time_literal', 'st');
        $page_data["use_leader"] = $use_leader;
        $page_data["view_type"] = 'gallery';

        $this->template_lite->assign('page_data', $page_data);

        $use_save_search = ($this->session->userdata("auth_type") == "user") ? true : false;
        $this->template_lite->assign('use_save_search', $use_save_search);

        return $this->template_lite->fetch('users_list_block');
    }

	
    private function getAdvancedSearchCriteria($data)
    {
        $this->load->model('field_editor/models/Field_editor_forms_model');
        $fe_criteria = $this->Field_editor_forms_model->get_search_criteria($this->Users_model->advanced_search_form_gid, $data, $this->Users_model->form_editor_type, false);
        if (!empty($data["search"])) {
            $data["search"] = trim(strip_tags($data["search"]));
            $this->load->model('Field_editor_model');
            $this->Field_editor_model->initialize($this->Users_model->form_editor_type);
            if (strlen($data["search"]) > 3) {
                $temp_criteria = $this->Field_editor_model->return_fulltext_criteria($data["search"], 'BOOLEAN MODE');
                $fe_criteria['fields'][] = $temp_criteria['user']['field'];
                $fe_criteria['where_sql'][] = $temp_criteria['user']['where_sql'];
            } else {
                $search_text_escape = $this->db->escape("%" . $data["search"] . "%");  ////////JL edited on 3/5/2016
                $fe_criteria['where_sql'][] = "(fname LIKE " . $search_text_escape
					. " OR address LIKE " . $search_text_escape
					. ")";
            }
        }
		//////////////JL added, for location searching 
		if (!empty($data["search_location"])) {
            $data["search_location"] = trim(strip_tags($data["search_location"]));
            $this->load->model('Field_editor_model');
            $this->Field_editor_model->initialize($this->Users_model->form_editor_type);
			
			// make it for address searching only  ////////JL edited on 3/5/2016
			$search_text_escape = $this->db->escape("%" . $data["search_location"] . "%");
			$fe_criteria['where_sql'][] = "(address LIKE " . $search_text_escape   
				. ")";

            
        }
		////////////////
        $common_criteria = $this->Users_model->get_common_criteria($data);
        $advanced_criteria = $this->Users_model->get_advanced_search_criteria($data);
        $criteria = array_merge_recursive($fe_criteria, $common_criteria, $advanced_criteria);
        return $criteria;
    }

    private function guestAccess($user_id)
    {
        if (!empty($this->user_id)) {
            // Not guest
            return true;
        } elseif (!$this->pg_module->get_module_config('users', 'guest_view_profile_allow')) {
            // Guest; disallowed
            $this->system_messages->add_message('error', l('error_guest_limit', 'users'), 'users');
            redirect(site_url() . 'users/login_form');
        } elseif (!$this->pg_module->get_module_config('users', 'guest_view_profile_limit')) {
            // Guest; unlimited
            return true;
        }
        // Guest; limited
        $cookie = array(
            'name' => 'profiles_viewed',
            'expire' => 604800, // 1 week
            'domain' => COOKIE_SITE_SERVER,
            'path' => '/' . SITE_SUBFOLDER
        );
        $this->load->helper('cookie');
        $allowed_views = $this->pg_module->get_module_config('users', 'guest_view_profile_num');
        if (empty($allowed_views)) {
            // limit is zero
            $cookie['value'] = array();
            set_cookie($cookie);
            $this->system_messages->add_message('error', l('error_guest_limit', 'users'), 'users');
            redirect(site_url() . 'users/login_form');
        }
        $curr_cookie = get_cookie('profiles_viewed');
        if (empty($curr_cookie)) {
            $viewed_arr = array();
        } else {
            $viewed_arr = unserialize($curr_cookie);
        }
        if ($this->pg_module->get_module_config('users', 'guest_view_profile_count_only_diff')) {
            $viewed_count = count(array_keys($viewed_arr));
        } else {
            $viewed_count = array_sum($viewed_arr);
        }
        if ($viewed_count >= $allowed_views) {
            $this->system_messages->add_message('error', l('error_guest_limit', 'users'), 'users');
            redirect(site_url() . 'users/login_form');
        } else {
            if (!isset($viewed_arr[$user_id])) {
                $viewed_arr[$user_id] = 1;
            } else {
                $viewed_arr[$user_id] ++;
            }
            $cookie['value'] = serialize($viewed_arr);
            set_cookie($cookie);
            return true;
        }
    }

    public function view($user_id, $profile_section = '', $subsection = 'all')
    {
        $this->guestAccess($user_id);
        $user_id = intval($user_id);
        $viewer_id = intval($this->user_id);
        $subsection = trim(strip_tags($subsection));

        if ($viewer_id) {
            $viewer = $this->Users_model->format_user($this->Users_model->get_user_by_id($viewer_id));
        }

        if ($viewer_id && !$viewer['activity']) {
            if ($this->pg_module->is_module_installed('services')) {
                if ($service_access['use_status']) {
                    $this->system_messages->add_message('error', l('text_inactive_in_search', 'users'));
                    redirect(site_url() . 'services/form/user_activate_in_search', 'hard');
                    return;
                } else {
                    $viewer['available_activation'] = $this->Users_model->check_available_user_activation($viewer_id);
                    if ($viewer['available_activation']['status'] == 0) {
                        $this->system_messages->add_message('error', l('text_register', 'users'));
                    } else {
                        $this->system_messages->add_message('error', l('text_register_activate', 'users'));
                    }
                    redirect(site_url() . 'users/profile/view');
                }
            } else {
                $viewer['available_activation'] = $this->Users_model->check_available_user_activation($viewer_id);
                if ($viewer['available_activation']['status'] == 0) {
                    $this->system_messages->add_message('error', l('text_register', 'users'));
                } else {
                    $this->system_messages->add_message('error', l('text_register_activate', 'users'));
                }
                redirect(site_url() . 'users/profile/view');
            }
        }
        if (!$profile_section || 'wall' === $profile_section) {
            $profile_section = $this->pg_module->is_module_installed('wall_events') ? 'wall' : 'profile';
        }
        if ($profile_section == 'gallery') {
            if (!in_array($subsection, $this->subsections)) {
                $subsection = $this->subsections['default'];
            }
        }

        if ($viewer_id == $user_id) {
            redirect(site_url() . 'users/profile/');
        }

        if (!$viewer_id || !$viewer['is_hide_on_site']) {
            $this->load->model('users/models/Users_views_model');
            $this->Users_views_model->update_views($user_id, $viewer_id);
        }

        if ($profile_section == 'profile') {
            $this->load->model('Field_editor_model');
            $this->Field_editor_model->initialize($this->Users_model->form_editor_type);
            $sections = $this->Field_editor_model->get_section_list();
            $sections_gids = array_keys($sections);
            $fields_for_select = $this->Field_editor_model->get_fields_for_select($sections_gids);
            $this->Users_model->set_additional_fields($fields_for_select);
        }

        $lang_id = $this->pg_language->current_lang_id;
        $data = $this->Users_model->get_user_by_id($user_id);
        if (empty($data)) {
            redirect(site_url() . 'users/untitled/');
        }
        $data = $this->Users_model->format_user($data, false, $lang_id);

        if ($profile_section == 'profile') {
            foreach ($sections as $sgid => $sdata) {
                $params["where"]["section_gid"] = $sgid;
                $sections[$sgid]['fields'] = $this->Field_editor_model->format_item_fields_for_view($params, $data);
            }
            $this->template_lite->assign('sections', $sections);
        }

        // breadcrumbs
        $this->load->model('Menu_model');
        $this->Menu_model->breadcrumbs_set_active($data['output_name'] . " > " . l("filter_section_" . $profile_section, "users"));

        $page_data['date_format'] = $this->pg_date->get_format('date_literal', 'st');

        $this->load->helper('seo');

        if ($profile_section == 'gallery') {
            $gallery_filters = array();

            foreach ($this->subsections as $subsection_code) {
                $subsection_name = l($subsection_code, 'media');

                $seo_data = $data;
                $seo_data['section'] = $profile_section;
                $seo_data['section-code'] = $profile_section;
                $seo_data['section-name'] = l('filter_section_' . $profile_section, 'users');

                $seo_data['subsection'] = $subsection_code;
                $seo_data['subsection-code'] = $subsection_code;
                $seo_data['subsection-name'] = $subsection_name;
                $subsection_link = rewrite_link('users', 'view', $seo_data);
                $gallery_filters[$subsection_code] = array('link' => $subsection_link, 'name' => $subsection_name);
            }

            $this->template_lite->assign('gallery_filters', $gallery_filters);

            $location_base_url = function($subsection_code, $subsection_name) use($data, $profile_section) {
                $seo_data = $data;
                $seo_data['section'] = $profile_section;
                $seo_data['section-code'] = $profile_section;
                $seo_data['section-name'] = l('filter_section_' . $profile_section, 'users');

                $seo_data['subsection'] = $subsection_code;
                $seo_data['subsection-code'] = $subsection_code;
                $seo_data['subsection-name'] = $subsection_name;
                $subsection_link = rewrite_link('users', 'view', $seo_data);
                return $subsection_link;
            };
            $this->template_lite->assign('location_base_url', $location_base_url);
        }

        $lang_canonical = true;

        if ($this->pg_module->is_module_installed('seo')) {
            $lang_canonical = $this->pg_module->get_module_config('seo', 'lang_canonical');
        }

        if ($data['id_seo_settings']) {
            $this->load->model('Seo_advanced_model');
            $seo_settings = $this->Seo_advanced_model->parse_seo_tags($data['id_seo_settings']);
            $seo_settings['canonical'] = rewrite_link('users', 'view', $data, false, null, $lang_canonical);
            $seo_settings['image'] = $data['media']['user_logo']['thumbs']['big'];
            $this->pg_seo->set_seo_tags($seo_settings);
        } else {
            $seo_settings = $data;
            $seo_settings['canonical'] = rewrite_link('users', 'view', $data, false, null, $lang_canonical);
            $seo_settings['image'] = $data['media']['user_logo']['thumbs']['big'];
            $seo_settings['section_code'] = $profile_section;
            $seo_settings['section_name'] = l('filter_section_' . $profile_section, 'users', $this->pg_language->current_lang_id);
            $this->pg_seo->set_seo_data($seo_settings);
        }

        $this->template_lite->assign('page_data', $page_data);
        $this->template_lite->assign('data', $data);
        $this->template_lite->assign('seodata', $data);
        $this->template_lite->assign('profile_section', $profile_section);
        $this->template_lite->assign('subsection', $subsection);
        $this->template_lite->assign('user_id', $user_id);
        $this->template_lite->view('view');
    }

    public function my_guests($period = 'all', $page = 1)
    {
        $this->load->model('Menu_model');
        $this->Menu_model->breadcrumbs_set_parent('user-menu-people');
        $this->Menu_model->breadcrumbs_set_active(l('header_my_guests', 'users'));
        $this->views($period, 'my_guests', $page);
        return;
    }

    public function my_visits($period = 'all', $page = 1)
    {
        $this->load->model('Menu_model');
        $this->Menu_model->breadcrumbs_set_parent('user-menu-people');
        $this->Menu_model->breadcrumbs_set_active(l('header_my_visits', 'users'));
        $this->views($period, 'my_visits', $page);
        return;
    }

    private function views($period = 'all', $type = 'my_guests', $page = 1)
    {
        if (!in_array($period, array('today', 'week', 'month', 'all'))) {
            $period = 'all';
        }
        $this->load->model('users/models/Users_views_model');

        $criteria = array();

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

        $items_count = $need_ids ? count($need_ids) : 0;
        $page = intval($page);
        if (!$page) {
            $page = 1;
        }
        $items_on_page = $this->pg_module->get_module_config('users', 'items_per_page');
        $this->load->helper('sort_order');
        $this->load->helper("navigation");
        $page = get_exists_page_number($page, $items_count, $items_on_page);
        $url = site_url() . "users/{$type}/{$period}/";
        $page_data = get_user_pages_data($url, $items_count, $items_on_page, $page, 'briefPage');

        if ($items_count) {
            $users_list = $this->Users_model->get_users_list_by_key($page, $items_on_page, $order_by, $criteria, $need_ids);
            $users = array();
            foreach ($need_ids as $uid) {
                if (isset($users_list[$uid]['id'])) {
                    $users[$uid] = $users_list[$uid];
                } else {
                    $users[$uid] = $this->Users_model->format_default_user($uid);
                }
            }
            $this->template_lite->assign('users', $users);
            $this->template_lite->assign('view_dates', $view_dates);
        }
        $this->template_lite->assign('views_type', $type);
        $this->template_lite->assign('period', $period);
        $page_data['date_format'] = $this->pg_date->get_format('date_literal', 'st');
        $page_data["date_time_format"] = $this->pg_date->get_format('date_time_literal', 'st');
        $this->template_lite->assign('page_data', $page_data);
        $this->template_lite->assign('page', $page);
        $this->template_lite->view('visits');
    }

    private function getPerfectMatchCriteria($data)
    {
        $this->load->model('field_editor/models/Field_editor_forms_model');
        $this->load->model('users/models/Users_perfect_match_model');
        $fe_criteria = $this->Field_editor_forms_model->get_search_criteria($this->Users_perfect_match_model->perfect_match_form_gid, $data, $this->Users_perfect_match_model->form_editor_type, false);
        $common_criteria = $this->Users_model->get_common_criteria($data);
        $criteria = array_merge_recursive($fe_criteria, $common_criteria);
        return $criteria;
    }

    public function perfect_match($order = "default", $order_direction = "DESC", $page = 1)
    {
        $this->session->unset_userdata("perfect_match");
        $this->load->model('users/models/Users_perfect_match_model');
        $this->load->model('Field_editor_model');
        $this->load->model('field_editor/models/Field_editor_forms_model');
        $this->Field_editor_model->initialize($this->Users_perfect_match_model->form_editor_type);
        $form = $this->Field_editor_forms_model->get_form_by_gid($this->Users_perfect_match_model->perfect_match_form_gid, $this->Users_perfect_match_model->form_editor_type);
        $fields_for_search = $this->Field_editor_model->get_fields_names_for_search($form);
        $this->Users_perfect_match_model->set_additional_fields($fields_for_search);
        if (empty($_POST)) {
            $perfect_match_params = $this->Users_perfect_match_model->get_user_perfect_match_params($this->user_id);
            $data = !empty($perfect_match_params['full_criteria']) ? $perfect_match_params['full_criteria'] : array();
        } else {
            foreach ($_POST as $key => $val) {
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
                $this->system_messages->add_message('error', $validate_data['errors']);
            }
        }
        $this->template_lite->assign('block', $this->searchListBlock($data, $order, $order_direction, $page, 'perfect_match'));

        $this->Menu_model->breadcrumbs_set_parent('perfect_match_item');
        $this->template_lite->view('perfect_match');
    }

    /* USERS SERVICES */

    public function ajax_available_user_activate_in_search()
    {
        $return = array('available' => 0, 'content' => '', 'display_login' => 0);
        if ($this->session->userdata('auth_type') != 'user') {
            $return['display_login'] = 1;
        } else {
            $return = $this->Users_model->service_available_user_activate_in_search_action($this->user_id);
            if ($return["content_buy_block"] == true) {
                $this->load->model('services/models/Services_users_model');
                $return["content"] = $this->Services_users_model->available_service_block($this->user_id, 'user_activate_in_search_template');
                $this->load->helper('seo');
                $this->session->set_userdata(array('service_redirect' => rewrite_link('users', 'profile')));
            }
        }
        exit(json_encode($return));
    }

    public function ajax_activate_user_activate_in_search($id_user_service)
    {
        $return = $this->Users_model->service_activate_user_activate_in_search($this->user_id, $id_user_service);
        exit(json_encode($return));
    }

    /**
     * The method checks the availability of featured user.
     *
     * @param int $id_user
     */
    public function ajax_available_users_featured()
    {
        $return = array('available' => 0, 'content' => '', 'display_login' => 0);
        if ($this->session->userdata('auth_type') != 'user') {
            $return['display_login'] = 1;
        } else {
            $return = $this->Users_model->service_available_users_featured_action($this->user_id);
            if ($return["content_buy_block"] == true) {
                $this->load->model('services/models/Services_users_model');
                $return["content"] = $this->Services_users_model->available_service_block($this->user_id, 'users_featured_template');
            }
        }
        exit(json_encode($return));
    }

    public function ajax_activate_users_featured($id_user_service)
    {
        $return = $this->Users_model->service_activate_users_featured($this->user_id, $id_user_service);
        exit(json_encode($return));
    }

    /**
     * The method checks the availability of approve user.
     *
     * @param int $id_user
     */
    public function ajax_available_admin_approve()
    {
        $return = array('available' => 0, 'content' => '', 'display_login' => 0);
        if ($this->session->userdata('auth_type') != 'user') {
            $return['display_login'] = 1;
        } else {
            $return = $this->Users_model->service_available_admin_approve_action($this->user_id);
            if ($return["content_buy_block"] == true) {
                $this->load->model('services/models/Services_users_model');
                $return["content"] = $this->Services_users_model->available_service_block($this->user_id, 'admin_approve_template');
            }
        }
        exit(json_encode($return));
    }

    public function ajax_activate_admin_approve($id_user_service)
    {
        $return = $this->Users_model->service_activate_admin_approve($this->user_id, $id_user_service);
        exit(json_encode($return));
    }

    /**
     * The method checks the availability of hide user on site.
     *
     * @param int $id_user
     */
    public function ajax_available_hide_on_site()
    {
        $return = array('available' => 0, 'content' => '', 'display_login' => 0);
        if ($this->session->userdata('auth_type') != 'user') {
            $return['display_login'] = 1;
        } else {
            $return = $this->Users_model->service_available_hide_on_site_action($this->user_id);
            if ($return["content_buy_block"] == true) {
                $this->load->model('services/models/Services_users_model');
                $return["content"] = $this->Services_users_model->available_service_block($this->user_id, 'hide_on_site_template');
            }
        }
        exit(json_encode($return));
    }

    public function ajax_activate_hide_on_site($id_user_service)
    {
        $return = $this->Users_model->service_activate_hide_on_site($this->user_id, $id_user_service);
        exit(json_encode($return));
    }

    /**
     * The method checks the availability of highlight user in search.
     *
     * @param int $id_user
     */
    public function ajax_available_highlight_in_search()
    {
        $return = array('available' => 0, 'content' => '', 'display_login' => 0);
        if ($this->session->userdata('auth_type') != 'user') {
            $return['display_login'] = 1;
        } else {
            $return = $this->Users_model->service_available_highlight_in_search_action($this->user_id);
            if ($return["content_buy_block"] == true) {
                $this->load->model('services/models/Services_users_model');
                $return["content"] = $this->Services_users_model->available_service_block($this->user_id, 'highlight_in_search_template');
            }
        }
        exit(json_encode($return));
    }

    public function ajax_activate_highlight_in_search($id_user_service)
    {
        $return = $this->Users_model->service_activate_highlight_in_search($this->user_id, $id_user_service);
        exit(json_encode($return));
    }

    /**
     * The method checks the availability of up user in search.
     *
     * @param int $id_user
     */
    public function ajax_available_up_in_search()
    {
        $return = array('available' => 0, 'content' => '', 'display_login' => 0);
        if ($this->session->userdata('auth_type') != 'user') {
            $return['display_login'] = 1;
        } else {
            $return = $this->Users_model->service_available_up_in_search_action($this->user_id);
            if ($return["content_buy_block"] == true) {
                $this->load->model('services/models/Services_users_model');
                $return["content"] = $this->Services_users_model->available_service_block($this->user_id, 'up_in_search_template');
            }
        }
        exit(json_encode($return));
    }

    public function ajax_activate_up_in_search($id_user_service)
    {
        $return = $this->Users_model->service_activate_up_in_search($this->user_id, $id_user_service);
        exit(json_encode($return));
    }

    /**
     * The method checks the availability of up user in search.
     *
     * @param int $id_user
     */
    public function ajax_available_ability_delete()
    {
        $return = array('available' => 0, 'content' => '', 'content_buy_block' => false, 'display_login' => 0);
        if ($this->session->userdata('auth_type') != 'user') {
            $return['display_login'] = 1;
        } else {
            $return = $this->Users_model->service_available_ability_delete_action($this->user_id);
        }
        exit(json_encode($return));
    }

    public function ajax_activate_ability_delete($id_user_service)
    {
        $return = $this->Users_model->service_activate_ability_delete($this->user_id, $id_user_service, 1);
        exit(json_encode($return));
    }

    public function account_delete()
    {
        if ($this->pg_module->is_module_installed('services')) {
            $this->load->model('services/models/Services_users_model');
            $service_access = $this->Services_users_model->is_service_access($this->user_id, 'ability_delete_template');
            if ($service_access['service_status'] && !$service_access['activate_status']) {
                show_404();
            }
        }
        if ($this->input->post('btn_delete')) {
            $this->Users_model->delete_user($this->user_id);
            $this->load->model("users/models/Auth_model");
            $this->Auth_model->logoff();
            redirect();
        } else {
            // breadcrumbs
            $this->load->model('Menu_model');
            $this->Menu_model->breadcrumbs_set_parent('settings-item');
            $this->Menu_model->breadcrumbs_set_active(l('seo_tags_account_delete_header', 'users'));
            $this->template_lite->view('account_delete_block');
        }
    }

    public function ajax_load_avatar()
    {
        $result = array('status' => 1, 'errors' => array(), 'msg' => array(), 'data' => array());
        $id_user = $this->input->post('id_user') ? intval($this->input->post('id_user', true)) : $this->user_id;
        $data['user'] = $this->Users_model->format_user($this->Users_model->get_user_by_id($id_user));
        $data['is_owner'] = ($id_user == $this->user_id);
        if (!$id_user || !$data['user'] || (!$data['is_owner'] && !($data['user']['user_logo'] || $data['user']['user_logo_moderation']))) {
            $result['status'] = 0;
            $result['errors'][] = l('error_access_denied', 'users');
            echo json_encode($result);
            return;
        }
        $data['have_avatar'] = ($data['user']['user_logo'] || $data['user']['user_logo_moderation']);
        if ($data['is_owner']) {
            $this->load->model('uploads/models/Uploads_config_model');
            $data['upload_config'] = $this->Uploads_model->get_config($this->Users_model->upload_config_id);
            $data['selections'] = array();
            foreach ($data['upload_config']['thumbs'] as $thumb_config) {
                $data['selections'][$thumb_config['prefix']] = array(
                    'width' => $thumb_config['width'],
                    'height' => $thumb_config['height'],
                );
            }
        }

        $this->template_lite->assign('avatar_data', $data);
        $result['data']['html'] = $this->template_lite->fetch_final('ajax_user_avatar');
        $result['data']['selections'] = $data['selections'];

        echo json_encode($result);
        return;
    }

    public function ajax_recrop_avatar()
    {
        $result = array('status' => 1, 'errors' => array(), 'msg' => array(), 'data' => array());
        $user = $this->Users_model->format_user($this->Users_model->get_user_by_id($this->user_id));
        if (!$user || !($user['user_logo'] || $user['user_logo_moderation'])) {
            $result['status'] = 0;
            $result['errors'][] = l('error_access_denied', 'users');
            echo json_encode($result);
            return;
        }

        $logo_name = $user['user_logo_moderation'] ? 'user_logo_moderation' : 'user_logo';
        $recrop_data['x1'] = $this->input->post('x1', true);
        $recrop_data['y1'] = $this->input->post('y1', true);
        $recrop_data['width'] = $this->input->post('width', true);
        $recrop_data['height'] = $this->input->post('height', true);
        $thumb_prefix = trim(strip_tags($this->input->post('prefix', true)));
        $this->load->model('Uploads_model');
        $this->Uploads_model->recrop_upload($this->Users_model->upload_config_id, $this->user_id, $user[$logo_name], $recrop_data, $thumb_prefix);
        $result['data']['img_url'] = $user['media'][$logo_name]['thumbs'][$thumb_prefix];
        $result['data']['rand'] = rand(0, 999999);
        $result['msg'][] = l('photo_successfully_saved', 'users');

        exit(json_encode($result));
        return;
    }

    public function upload_avatar()
    {
        $return = array('errors' => array(), 'warnings' => array(), 'name' => '', 'logo' => array(), 'old_logo' => array());
        $validate_data = $this->Users_model->validate($this->user_id, array(), 'avatar');
        if (!empty($validate_data['errors'])) {
            $return['errors'] = $validate_data['errors'];
        } else {
            $data = $this->Users_model->format_user($this->Users_model->get_user_by_id($this->user_id));
            $return['old_logo'] = $data['user_logo'] ? $data['media']['user_logo'] : array();
            $return['old_logo_moderation'] = $data['user_logo_moderation'] ? $data['media']['user_logo_moderation'] : array();
            if ($this->input->post('user_icon_delete') || (isset($_FILES['avatar']) && is_array($_FILES['avatar']) && is_uploaded_file($_FILES['avatar']['tmp_name']))) {
                $this->load->model('Uploads_model');
                if ($data['user_logo_moderation']) {
                    $this->Uploads_model->delete_upload($this->Users_model->upload_config_id, $this->user_id . '/', $data['user_logo_moderation']);
                    $validate_data['data']['user_logo_moderation'] = '';
                    $this->load->model('Moderation_model');
                    $this->Moderation_model->delete_moderation_item_by_obj($this->Users_model->moderation_type, $this->user_id);
                } elseif ($data['user_logo']) {
                    $this->Uploads_model->delete_upload($this->Users_model->upload_config_id, $this->user_id . '/', $data['user_logo']);
                    $validate_data['data']['user_logo'] = '';
                }
            }
            $this->Users_model->save_user($this->user_id, $validate_data['data'], 'avatar');
            $this->Users_model->service_available_user_activate_in_search_action($this->user_id);
            $data = $this->Users_model->format_user($this->Users_model->get_user_by_id($this->user_id));
            $return['logo'] = $data['user_logo_moderation'] ? $data['media']['user_logo_moderation'] : $data['media']['user_logo'];
        }

        echo json_encode($return);
        return;
    }

    public function untitled()
    {
        $data['img'] = site_url() . "uploads/default/midle-default-user-logo-deleted.png";
        $this->template_lite->assign('data', $data);

        $this->load->model('Menu_model');
        $this->Menu_model->breadcrumbs_set_active(l('user_deleted', 'users'));
        $this->template_lite->view('profile_deleted');
    }

    public function ajax_refresh_active_users()
    {
        $attrs["where_sql"][] = " id!='" . $this->session->userdata("user_id") . "'";
        $params['count'] = intval($this->input->post('count', true));
        $data['users'] = $this->Users_model->get_active_users($params['count'], 0, $attrs);
        if (!empty($data['users'])) {
            $users_count = 16 - count($data['users']);
            switch ($users_count) {
                case 13: $recent_thumb['name'] = 'middle';
                    $recent_thumb['width'] = '82px';
                    break;
                case 14: $recent_thumb['name'] = 'big';
                    $recent_thumb['width'] = '125px';
                    break;
                case 15: $recent_thumb['name'] = 'great';
                    $recent_thumb['width'] = '255px';
                    break;
                default: $recent_thumb['name'] = 'small';
                    $recent_thumb['width'] = '60px';
            }
            $this->template_lite->assign('recent_thumb', $recent_thumb);
            $this->template_lite->assign('active_users_block_data', $data);
            exit($this->template_lite->fetch('helper_active_users_block', 'user', 'users'));
        }
        return false;
    }

    /**
     * If user not approve
     * @return void
     * */
    public function blocked()
    {
        if ($this->session->userdata('auth_type') == 'user') {
            redirect(site_url() . 'start/homepage');
        }

        $module = 'contact_us';
        if ($this->pg_module->is_module_installed('contact_us')) {
            $module = 'contact_us';
        } elseif ($this->pg_module->is_module_installed('tickets')) {
            $module = 'tickets';
        }

        $this->template_lite->assign('module', $module);
        $this->template_lite->view('user_blocked');
    }

}
