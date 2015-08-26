<?php

use Pg\Modules\Memberships\Models\Memberships_model;

/**
 * Memberships module
 *
 * @package 	PG_Dating
 * @copyright 	Copyright (c) 2000-2014 PG Dating Pro - php dating software
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */

/**
 * Memberships admin side controller
 * 
 * @package 	PG_Dating
 * @subpackage 	Memberships
 * @category	controllers
 * @copyright 	Copyright (c) 2000-2014 PG Dating Pro - php dating software
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
Class Admin_Memberships extends Controller
{

    /**
     * Class constructor
     * 
     * @return Admin_Memberships
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('PG_Output');
        $this->load->model('Memberships_model');
        $this->load->model('Menu_model');
        $this->Menu_model->set_menu_active_item('admin_menu', 'payments_menu_item');
    }

    /**
     * Memberships management
     * 
     * @param string $order sorting order 
     * @param string $order_direction order direction
     * @param integer $page page of results
     * @return void
     */
    public function index($order = null, $order_direction = null, $page = null)
    {
        $memberships_settings = isset($_SESSION['memberships']) ? $_SESSION['memberships'] : array();
        if (!isset($memberships_settings['order'])) {
            $memberships_settings['order'] = 'date_created';
        }
        if (!isset($memberships_settings['order_direction'])) {
            $memberships_settings['order_direction'] = 'DESC';
        }
        if (!isset($memberships_settings['page'])) {
            $memberships_settings['page'] = 1;
        }

        $order = strval($order);
        $order_direction = strval($order_direction);
        $page = intval($page);

        $this->load->helper('sort_order');

        $items_on_page = $this->pg_module->get_module_config('start', 'admin_items_per_page');

        $memberships_count = $this->Memberships_model->getMembershipsCount();
        $page = get_exists_page_number($page, $memberships_count, $items_on_page);
        $memberships_settings['page'] = $page;

        if (!$order) {
            $order = $memberships_settings['order'];
        }
        $this->pg_output->setOutputData('order', $order);
        $memberships_settings['order'] = $order;

        if (!$order_direction) {
            $order_direction = $memberships_settings['order_direction'];
        }
        $this->pg_output->setOutputData('order_direction', $order_direction);
        $memberships_settings['order_direction'] = $order_direction;

        $filters = array();

        $_SESSION['memberships'] = $memberships_settings;

        switch ($order) {
            case 'date_created':
                $order_array = array('id' => $order_direction);
                break;
            default:
                $order_array = array($order => $order_direction);
                break;
        }

        if ($memberships_count > 0) {
            $memberships = $this->Memberships_model->getMembershipsList($filters, $page, $items_on_page, $order_array);
            $memberships = $this->Memberships_model->formatMemberships($memberships);
            $this->pg_output->setOutputData('memberships', $memberships);
        }

        $this->load->helper('navigation');
        $page_data = get_admin_pages_data(site_url() . 'admin/memberships/index/' . $order . '/' . $order_direction . '/', $memberships_count, $items_on_page, $page, 'briefPage');
        $this->pg_output->setOutputData('page_data', $page_data);

        $this->load->library('pg_date');
        $date_format = $this->pg_date->get_format('date_time_literal', 'st');
        $this->pg_output->setOutputData('date_format', $date_format);

        $this->pg_output->setOutputHeader(l('admin_header_list', Memberships_model::MODULE_GID));
    }

    private function preProcessPostData($post_data)
    {
        $ut = $this->Users_model->get_user_types();
        $user_types = array_keys($ut['option']);
        if (empty($post_data['user_type_enabled'])) {
            $post_data['user_type_disabled'] = $user_types;
        } else {
            $post_data['user_type_disabled'] = array_diff($user_types, $post_data['user_type_enabled']);
        }
        unset($post_data['user_type_enabled']);
        return $post_data;
    }

    /**
     * Edit membership
     * 
     * @param integer $membership_id membership identifier
     * @return void
     */
    public function edit($membership_id, $price_index = -1)
    {
        $membership_id = intval($membership_id);

        if ($membership_id) {
            $langs_ids = array_keys($this->pg_language->languages);
			$membership_data = $this->Memberships_model->getMembershipById($membership_id, $langs_ids);
            if (empty($membership_data)) {
                redirect(site_url() . 'admin/memberships/create');
            }
            $this->Memberships_model->setFormatSettings('get_services', true);
            $membership_data = $this->Memberships_model->formatMembership($membership_data);
            $this->Memberships_model->setFormatSettings('get_services', false);
            $header = l('admin_header_edit', Memberships_model::MODULE_GID);
        } else {
            $membership_data = array();
            $header = l('admin_header_create', Memberships_model::MODULE_GID);
        }

        if ($this->input->post('save')) {
            $post_data = $this->preProcessPostData($this->input->post('data', true));
            $validate_data = $this->Memberships_model->validateMembership($membership_id, $post_data);
            $message = array();
            if (!empty($validate_data['info'])) {
                foreach($validate_data['info'] as $info) {
                    $message[] = $info;
                }
            }
            if (!empty($validate_data['errors'])) {
                $this->pg_output->setOutputContent('errors', $validate_data['errors']);
            } else {
                $is_new = !(bool)$membership_id;
                $membership_id = $this->Memberships_model->saveMembership($membership_id, $validate_data['data']);

                if (!empty($membership_data)) {
                    $message[] = l('success_item_updated', Memberships_model::MODULE_GID);
                } else {
                    $message[] = l('success_item_created', Memberships_model::MODULE_GID);
                }

                $this->pg_output->setOutputContent('success', $message);
                if(!$is_new) {
                    $redirect = 'admin/memberships';
                } else {
                    $redirect = 'admin/memberships/edit/' . $membership_id;
                }
                $this->pg_output->setOutputContent('redirect', site_url() . $redirect);
                return;
            }

            $membership_data = array_merge($membership_data, $post_data);
        }

        if ($this->input->post('save_params')) {
            $post_data = array(
                'services' => $membership_data['services_array'],
            );

            $params = (array) $this->input->post('params', true);
            foreach ($params as $service_id => $params_data) {
                if (!isset($post_data['services'][$service_id])) {
                    $post_data['services'][$service_id] = array('is_active' => 0, 'params' => array());
                }
                $post_data['services'][$service_id]['params'] = $params_data;
            }

            $validate_data = $this->Memberships_model->validateMembership($membership_id, $post_data);
            if (!empty($validate_data['errors'])) {
                $this->pg_output->setOutputContent('errors', $validate_data['errors']);
            } else {
                $this->Memberships_model->saveMembership($membership_id, $validate_data['data']);
                if ($price_index != -1) {
                    $message = l('success_price_updated', Memberships_model::MODULE_GID);
                } else {
                    $message = l('success_price_created', Memberships_model::MODULE_GID);
                }
                $this->pg_output->setOutputContent('success', $message);
                $this->pg_output->setOutputContent('redirect', site_url() . 'admin/memberships/edit/' . $membership_id);
                return;
            }

            $membership_data = array_merge($membership_data, $post_data);
        }

        if (empty($membership_data['gid'])) {
            $membership_data['gid'] = $this->Memberships_model->generateGUID();
        }

        $this->pg_output->setOutputData('membership', $membership_data);

        $langs = $this->pg_language->languages;
        $this->pg_output->setOutputData('langs', $langs);

        $current_lang_id = $this->pg_language->current_lang_id;
        $this->pg_output->setOutputData('current_lang_id', $current_lang_id);

        $this->pg_output->setOutputData('pay_types', $this->Memberships_model->getAllowedPaymentTypes());
        $this->pg_output->setOutputData('period_types', $this->Memberships_model->getAllowedPeriodTypes());

        $this->load->model('Users_model');
        $user_types = $this->Users_model->get_user_types();
        if (!empty($user_types['option'])) {
            $this->pg_output->setOutputData('user_types', $user_types['option']);
        }

        $params = array(
            'where' => array(
                'type' => 'membership', 
                'id_membership' => $membership_id,
            ),
        );
        
        $this->load->model('Services_model');
        $services = $this->Services_model->get_service_list($params);
        $this->pg_output->setOutputData('services', $services);

        $this->pg_output->setOutputHeader($header);
    }

    /**
     * Create membership
     * 
     * @param integer $membership_id membership idnetifier
     * @return void
     */
    public function create()
    {
        $this->edit(0);
    }

    /**
     * Remove membership
     * 
     * @param integer $membership_id membership identifier
     * @return void
     */
    public function delete($membership_id)
    {
        if (empty($membership_id)) {
            $membership_ids = (array) $this->input->post('ids', true);
        } else {
            $membership_ids = array($membership_id);
        }

        if (!empty($membership_ids)) {
            $this->Memberships_model->deleteMemberships($membership_ids);

            $message = l('success_item_delete', Memberships_model::MODULE_GID);
            $this->pg_output->setOutputContent('success', $message);
        }

        $url = site_url() . 'admin/memberships/index';
        redirect($url);
    }

    /**
     * Activate membership
     * 
     * @param integer $membership_id membership identifier
     * @return void
     */
    public function activate($membership_id)
    {
        $this->Memberships_model->activate($membership_id);
        $this->pg_output->setOutputContent('success', l('success_item_activate', Memberships_model::MODULE_GID));
        $url = site_url() . 'admin/memberships/index';
        redirect($url);
    }

    /**
     * Deactivate membership
     * 
     * @param integer $membership_id membership identifier
     * @return void
     */
    public function deactivate($membership_id)
    {
        $this->Memberships_model->deactivate($membership_id);
        $this->pg_output->setOutputContent('success', l('success_item_deactivate', Memberships_model::MODULE_GID));
        $url = site_url() . 'admin/memberships/index';
        redirect($url);
    }

    /**
     * Activate membership service
     * 
     * @param integer $membership_id membership identifier
     * @param integer $service_id service template identifier
     * @return void
     */
    public function activate_service($membership_id, $service_id)
    {
        $redirect_url = site_url() . 'admin/memberships/edit/' . $membership_id;
        if (!$membership_id || !$service_id) {
            redirect($redirect_url);
            return false;
        }
        $result = $this->Memberships_model->activateService((int) $membership_id, (int) $service_id);
        if (!empty($result['errors'])) {
            $this->pg_output->setOutputContent('error', $result['errors']);
        } else {
            $this->pg_output->setOutputContent('success', l('success_item_service_activate', Memberships_model::MODULE_GID));
        }
        $this->pg_output->setOutputRedirect($redirect_url);
    }

    /**
     * Deactivate membership service
     * 
     * @param integer $membership_id membership identifier
     * @param integer $service_id service template identifier
     * @return void
     */
    public function deactivate_service($membership_id, $service_id)
    {
        $result = $this->Memberships_model->activateService((int) $membership_id, (int) $service_id);
        if (!empty($result['errors'])) {
            $this->pg_output->setOutputContent('error', $result['errors']);
        } else {
            $this->Memberships_model->saveMembership($membership_id, $result['data']);
            $success[] = l('success_item_service_deactivate', Memberships_model::MODULE_GID);
        }
        if (!empty($success)) {
            $this->pg_output->setOutputContent('success', $success);
        }

        $url = site_url() . 'admin/memberships/edit/' . $membership_id;
        $this->pg_output->setOutputRedirect($url);
    }

    /**
     * Remove membership price
     * 
     * @param integer $membership_id membership identifier
     * @param integer $price_index price index
     * @return void
     */
    public function delete_price($membership_id, $price_index)
    {
        $membership_data = $this->Memberships_model->getMembershipById($membership_id);
        $membership_data = $this->Memberships_model->formatMembership($membership_data);
        if (isset($membership_data['prices_array'][$price_index])) {
            $post_data = array(
                'prices' => $membership_data['prices_array'],
            );
            unset($post_data['prices'][$price_index]);
            $post_data['prices'] = array_values($post_data['prices']);
            $validate_data = $this->Memberships_model->validateMembership($membership_id, $post_data);
            if (!empty($validate_data['errors'])) {
                $this->pg_output->setOutputContent('errors', $validate_data['errors']);
            } else {
                $this->Memberships_model->saveMembership($membership_id, $validate_data['data']);
                $this->pg_output->setOutputContent('success', l('success_item_price_delete', Memberships_model::MODULE_GID));
            }
        }
        $url = site_url() . 'admin/memberships/edit/' . $membership_id;
        $this->pg_output->setOutputRedirect($url);
    }

    public function ajax_activate()
    {
        $membership_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if (!$membership_id) {
            $this->pg_output->setOutputContent('errors', l('error_item_activate', Memberships_model::MODULE_GID));
        } else {
            $result = $this->Memberships_model->activate($membership_id);
            if (empty($result['errors'])) {
                $this->pg_output->setOutputContent('success', l('success_item_activate', Memberships_model::MODULE_GID));
            } else {
                $errors = array();
                foreach ($result['errors'] as $error) {
                    $errors[] = l('error_' . $error, Memberships_model::MODULE_GID)? : $error;
                }
                $this->pg_output->setOutputContent('errors', $errors);
            }
        }
    }

    public function ajax_deactivate()
    {
        $membership_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $result = $this->Memberships_model->deactivate($membership_id);
        if (empty($result['errors'])) {
            $this->pg_output->setOutputContent('success', l('success_item_deactivate', Memberships_model::MODULE_GID));
        } else {
            $errors = array();
            foreach ($result['errors'] as $error) {
                $errors[] = l('error_' . $error, Memberships_model::MODULE_GID) ? : $error;
            }
            $this->pg_output->setOutputContent('errors', $errors);
        }
    }

    private function serviceActivity($status)
    {
        if ($status) {
            $action = 'activate';
        } else {
            $action = 'deactivate';
        }
        $membership_id = filter_input(INPUT_POST, 'membership_id', FILTER_VALIDATE_INT);
        $service_id = filter_input(INPUT_POST, 'service_id', FILTER_VALIDATE_INT);
        if (!$membership_id) {
            $this->pg_output->setOutputContent('errors', l('error_service_' . $action, Memberships_model::MODULE_GID));
        } else {
            $result = $this->Memberships_model->{$action . 'Service'}((int) $membership_id, (int) $service_id);
            if (empty($result['errors'])) {
                $this->pg_output->setOutputContent('success', l('success_item_service_' . $action, Memberships_model::MODULE_GID));
            } else {
                $errors = array();
                foreach ($result['errors'] as $error) {
                    $errors[] = l('error_' . $error, Memberships_model::MODULE_GID)? : $error;
                }
                $this->pg_output->setOutputContent('errors', $errors);
            }
        }
    }

    public function ajax_activate_service()
    {
        $this->serviceActivity(true);
    }

    public function ajax_deactivate_service()
    {
        $this->serviceActivity(false);
    }

}
