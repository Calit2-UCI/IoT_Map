<?php

namespace Pg\Modules\Mobile\Controllers;

/**
 * Mobile version controller
 *
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * */
Class Mobile extends \Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        
    }

    public function init($lang_id = '')
    {
        $data['l'] = $this->pg_language->pages->return_module('mobile', $lang_id);
        $theme_data = $this->pg_theme->format_theme_settings($this->router->class);
        $data['data']['site_url'] = site_url();
        $data['data']['logo'] = $theme_data['mini_logo']['path'];
        exit(json_encode($data));
    }

    /**
     * Authorize user by oauth
     * 
     * @param string $service_id service identifier
     * @param string $redirect redirect url
     * @return void
     */
    public function oauthLogin($service_id, $redirect = null)
    {
        $user_type = 0;

        if ($redirect) {
            $redirect = base64_decode($redirect);
        } else {
            $redirect = $this->input->get('redirect');
        }

        // Грузим модели
        $this->load->model('social_networking/models/Social_networking_services_model');
        $this->load->model('social_networking/models/Social_networking_connections_model');
        $this->load->model('Users_connections_model');
        // Данные
        $service = $this->Social_networking_services_model->get_service_by_id($service_id);

        // Проверка подключения
        if ($service['oauth_version'] == 2) {
            $method = '_check_oauth2_connection';
        } else {
            $method = '_check_oauth_connection';
        }
        $result = $this->Social_networking_connections_model->$method(
            $service, 
            site_url('mobile/oauth_login/' . $service_id . '/' . base64_encode($redirect))
        );

        // Авторизуем или посылаем на авторизацию
        if ($result['result']) {
            // Если получен ключ ответа
            if (isset($result['result']['oauth_token'])) {
                $result['result']['access_token'] = $result['result']['oauth_token'];
            }
            if (isset($result['result']['access_token'])) {
                $result['result']['access_secret'] = isset($result['result']['oauth_token_secret']) ? $result['result']['oauth_token_secret'] : '';
                $result['result']['expires_in'] = isset($result['result']['expires_in']) ? $result['result']['expires_in'] : 0;
                $user_id = $this->session->userdata('user_id');
                $service_user_id = isset($result['result']['user_id']) ? $result['result']['user_id'] : false;
                $service_user_fname = '';
                $service_user_sname = '';
                $service_user_email = '';
                $service_model = $service['gid'] . '_service_model';
                $service_file = APPPATH . 'modules/social_networking/models/services/' . $service_model . '.php';
                if (file_exists($service_file)) {
                    include_once($service_file);
                    $this->service = new $service_model();
                    if (method_exists($this->service, 'get_user_data')) {
                        $user_data = $this->service->get_user_data(
                            $service_user_id, 
                            $result['result']['access_token'], 
                            $result['result']['access_secret']
                        );
                        if (($user_data) && isset($user_data['id'])) {
                            $service_user_id = $user_data['id'];
                            $service_user_fname = $user_data['fname'];
                            $service_user_sname = $user_data['sname'];
                            $service_user_email = $user_data['email'];
                        }
                    }
                }

                if ($service_user_id) {
                    $connection = $this->Users_connections_model->get_connection_by_data($service_id, $service_user_id);

                    if ($connection && isset($connection['id'])) {
                        $this->Users_connections_model->delete_connection($connection['id']);
                        $user_id = $connection['user_id'];

                        $this->load->model("users/models/Auth_model");
                        $auth_data = $this->Auth_model->login($user_id);
                        if (empty($auth_data["errors"])) {
                            $connection = array(
                                'service_id' => $service_id,
                                'user_id' => $user_id,
                                'access_token' => $result['result']['access_token'],
                                'access_token_secret' => $result['result']['access_secret'],
                                'data' => $service_user_id,
                                'date_end' => date('Y-m-d H:i:s', time() + $result['result']['expires_in']),
                            );
                            $this->Users_connections_model->save_connection(null, $connection);
                            $user_type = $auth_data['user_data']['user_type'];
                        }
                    }
                }
            }
        }

        redirect($redirect . '#!/account/oauth/' . $service_id . '/' . intval($service_user_id) . '/' . ($user_type ? : 0));
    }

}
