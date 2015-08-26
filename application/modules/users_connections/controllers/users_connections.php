<?php
/**
* Users connections user side controller
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Alexander Batukhtin <abatukhtin@pilotgroup.net>
* @version $Revision: 1 $ $Date: 2012-09-21 11:36:07 +0300 (Пт, 21 сент 2012) $ $Author: abatukhtin $
**/

Class Users_connections extends Controller
{

	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::Controller();
	}

	public function oauth_login($service_id = false, $user_type = false, $service_user_id = false) {
		// Грузим модели
		$this->load->model('social_networking/models/Social_networking_services_model');
		$this->load->model('social_networking/models/Social_networking_connections_model');
		$this->load->model('Users_connections_model');
		// Данные
		$service = $this->Social_networking_services_model->get_service_by_id($service_id);

		// Проверка подключения
		if ($service['oauth_version'] == 2) {
			$result = $this->Social_networking_connections_model->_check_oauth2_connection($service, site_url('users_connections/oauth_login/' . $service_id . '/' . $user_type));
		} else {
			$result = $this->Social_networking_connections_model->_check_oauth_connection($service, site_url('users_connections/oauth_login/' . $service_id . '/' . $user_type));
		}

		// Авторизуем или посылаем на авторизацию
		if ($result['result']) {
			// Если получен ключ ответа
			if (isset($result['result']['oauth_token']))
				$result['result']['access_token'] = $result['result']['oauth_token'];
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
						$user_data = $this->service->get_user_data($service_user_id, $result['result']['access_token'], $result['result']['access_secret']);
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
					} else {
						$this->load->model('Properties_model');
						$this->template_lite->assign('user_type', $this->Properties_model->get_property('user_type'));
						$this->template_lite->assign('service_id', $service_id);
						$this->template_lite->assign('service_name', $service['name']);
						$this->template_lite->assign('access_token', $result['result']['access_token']);
						$this->template_lite->assign('access_token_secret', $result['result']['access_secret']);
						$this->template_lite->assign('date_end', date("Y-m-d H:i:s", time() + $result['result']['expires_in']));
						$this->template_lite->assign('service_user_id', $service_user_id);
						$this->template_lite->assign('service_user_fname', $service_user_fname);
						$this->template_lite->assign('service_user_sname', $service_user_sname);
						$this->template_lite->assign('service_user_email', $service_user_email);

						$this->template_lite->view('oauth_usertype');
						exit;
					}
					$this->load->model("users/models/Auth_model");
					$auth_data = $this->Auth_model->login($user_id);
					if (!empty($auth_data["errors"])) {
						$this->system_messages->add_message('error', $auth_data["errors"]);
						redirect(site_url());
					} else {
						$connection = array(
							'service_id' => $service_id,
							'user_id' => $user_id,
							'access_token' => $result['result']['access_token'],
							'access_token_secret' => $result['result']['access_secret'],
							'data' => $service_user_id,
							'date_end' => date("Y-m-d H:i:s", time() + $result['result']['expires_in']),
						);
						$this->Users_connections_model->save_connection(null, $connection);
						redirect(site_url("users/account"));
					}
				}
			}
		}
		if ($result['error']) {
			$this->system_messages->add_message('error', $result['error']);
		}
		redirect(site_url());
	}

	public function oauth_register() {
		$service_id = $this->input->post('service_id', false);
		$access_token = $this->input->post('access_token', false);
		$access_token_secret = $this->input->post('access_token_secret', false);
		$date_end = $this->input->post('date_end', false);
		$service_user_id = $this->input->post('service_user_id', false);
		$service_user_fname = ($this->input->post('service_user_fname', false) ? $this->input->post('service_user_fname', false) : 'fname');
		$service_user_sname = ($this->input->post('service_user_sname', false) ? $this->input->post('service_user_sname', false) : 'sname');
		$this->load->library('Translit');
		$service_user_fname  = $this->translit->convert('ru', $service_user_fname);
		$service_user_sname  = $this->translit->convert('ru', $service_user_sname);
		$service_user_email = ($this->input->post('service_user_email', false) ? $this->input->post('service_user_email', false) : $service_user_fname.$service_user_sname.'@mail.com');

		$params = array();
		$params["where"]["email"] = $service_user_email;
		$this->load->model("Users_model");
		$count = $this->Users_model->get_users_count($params);
		if ($count > 0) {
			$service_user_email = rand(100, 300).$service_user_email;
		}

		$lang_id = $this->pg_language->current_lang_id;
		$this->load->model('users/models/Groups_model');
		$group_id = $this->Groups_model->get_default_group_id();

		$user_type = $this->input->post('user_type', true);
		if ($service_id && $access_token && $date_end && $service_user_id) {
			// Грузим модели
			$this->load->model('social_networking/models/Social_networking_services_model');
			$this->load->model('users_connections/models/Users_connections_model');
			// Данные
			$service = $this->Social_networking_services_model->get_service_by_id($service_id);
			if ($service) {
				$user_id = $this->Users_model->save_user(null, array(
					'fname' => $service_user_fname,
					'sname' => $service_user_sname,
					'nickname' => $service_user_fname.$service_user_sname,
					'email' => $service_user_email,
					'confirm' => 1,
					'user_type' => $user_type,
					'lang_id' => $lang_id,
					'group_id' => $group_id,
					'approved' => (intval($this->pg_module->get_module_config('users', 'user_approve')) ? 0 : 1)
				));
				$this->load->model("users/models/Auth_model");
				$auth_data = $this->Auth_model->login($user_id);
				if (!empty($auth_data["errors"])) {
					$this->system_messages->add_message('error', $auth_data["errors"]);
					redirect(site_url());
				} else {
					$connection = array(
						'service_id' => $service_id,
						'user_id' => $user_id,
						'access_token' => $access_token,
						'access_token_secret' => $access_token_secret,
						'data' => $service_user_id,
						'date_end' => $date_end,
					);
					$this->Users_connections_model->save_connection(null, $connection);
					$this->system_messages->add_message('success', l('please_set_email', 'users'));
					redirect(site_url("users/account"));
				}
			}
		}
		redirect(site_url());
	}

	public function oauth_link($service_id = false) {
		$user_id = $this->session->userdata('user_id');
		if ($user_id) {
			// Грузим модели
			$this->load->model('social_networking/models/Social_networking_services_model');
			$this->load->model('social_networking/models/Social_networking_connections_model');
			$this->load->model('users_connections/models/Users_connections_model');
			// Данные
			$service = $this->Social_networking_services_model->get_service_by_id($service_id);
			// Проверка подключения
			if ($service['oauth_version'] == 2) {
				$result = $this->Social_networking_connections_model->_check_oauth2_connection($service, site_url('users_connections/oauth_link/' . $service_id));
			} else {
				$result = $this->Social_networking_connections_model->_check_oauth_connection($service, site_url('users_connections/oauth_link/' . $service_id));
			}
			// Авторизуем или посылаем на авторизацию
			if ($result['result']) {
				// Если получен ключ ответа
				if (isset($result['result']['oauth_token']))
					$result['result']['access_token'] = $result['result']['oauth_token'];
				if (isset($result['result']['access_token'])) {
					$result['result']['access_secret'] = isset($result['result']['oauth_token_secret']) ? $result['result']['oauth_token_secret'] : '';
					$result['result']['expires_in'] = isset($result['result']['expires_in']) ? $result['result']['expires_in'] : 0;
					$service_user_id = isset($result['result']['user_id']) ? $result['result']['user_id'] : false;
					$service_model = $service['gid'] . '_service_model';
					$service_file = APPPATH . 'modules/social_networking/models/services/' . $service_model . '.php';
					if (file_exists($service_file)) {
						include_once($service_file);
						$this->service = new $service_model();
						if (method_exists($this->service, 'get_user_data')) {
							$user_data = $this->service->get_user_data($service_user_id, $result['result']['access_token'], $result['result']['access_secret']);
							if (($user_data) && isset($user_data['id'])) {
								$service_user_id = $user_data['id'];
							}
						}
					}
					if ($service_user_id) {
						$connection = $this->Users_connections_model->get_connection_by_data($service_id, $service_user_id);
						if ($connection && isset($connection['id'])) {
							$this->Users_connections_model->delete_connection($connection['id']);
						}
						$connection = array(
							'service_id' => $service_id,
							'user_id' => $user_id,
							'access_token' => $result['result']['access_token'],
							'access_token_secret' => $result['result']['access_secret'],
							'data' => $service_user_id,
							'date_end' => date("Y-m-d H:i:s", time() + $result['result']['expires_in']),
						);
						$this->Users_connections_model->save_connection(null, $connection);
						if (isset($service['name']))
							$this->system_messages->add_message('success', l('account_link_to', 'users') . ' ' . $service['name']);
						redirect(site_url("users/account"));
					}
				}
			}
			if ($result['error']) {
				$this->system_messages->add_message('error', $result['error']);
			}
		}
		redirect(site_url());
	}

	public function oauth_unlink($service_id = false) {
		// Грузим модели
		$this->load->model('social_networking/models/Social_networking_services_model');
		$this->load->model('users_connections/models/Users_connections_model');
		$user_id = $this->session->userdata('user_id');
		$service = $this->Social_networking_services_model->get_service_by_id($service_id);
		if ($user_id && $service) {
			$connection = $this->Users_connections_model->get_connection_by_user_id($service_id, $user_id);
			if ($connection && isset($connection['id'])) {
				$this->Users_connections_model->delete_connection($connection['id']);
				if (isset($service['name']))
					$this->system_messages->add_message('success', l('account_unlink_from', 'users') . ' ' . $service['name']);
				redirect(site_url("users/account"));
			}
		}
		redirect(site_url());
	}

}