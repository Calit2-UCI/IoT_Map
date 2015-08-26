<?php

use Pg\Modules\Network\Models\Network_users_model;

/**
 * Network user side controller
 *
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * */
Class Network extends Controller {
    /**
	 * Class constructor
     * 
     * @return Network
	 */
	public function __construct() {
		parent::__construct();
	}
    
    /**
     * Register user to network
     * 
     * @return void
     */
    public function register() {        
        if ($this->input->post('btn_agree')) {
            $user_id = $this->session->userdata('user_id');
  
            $this->load->model('Network_users_model');
            $this->Network_users_model->saveReady($user_id);
            
            $redirect = $this->input->post('redirect');
            redirect($redirect);
            exit;
        }
        
        if ($this->input->post('btn_not_agree')) {
            $user_id = $this->session->userdata('user_id');
  
            $this->load->model('Network_users_model');
            $this->Network_users_model->saveNotAgree($user_id);
            
            $redirect = $this->input->post('redirect');
            redirect($redirect);
            exit;
        }
        
        if ($this->input->post('btn_skip')) {
            $redirect = $this->input->post('redirect');
            redirect($redirect);
            exit;
        }
        
        if (!empty($_SERVER['HTTP_REFERER'])) {
            $redirect = $_SERVER['HTTP_REFERER'];
        } else {
            $redirect = site_url();
        }
        
        $this->template_lite->assign('redirect', $redirect);
        
        $this->template_lite->assign('header_type', 'network');
        
        $this->template_lite->view('register');
    }
}
