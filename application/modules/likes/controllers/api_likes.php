<?php

/**
 * Likes api controller
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * */
class Api_Likes extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Likes_model');
    }

    public function like()
    {
        $id_like = filter_input(INPUT_POST, 'like_id');
        $action = filter_input(INPUT_POST, 'action');
        $id_user = $this->session->userdata('user_id');
        $this->Likes_model->like($id_user, $id_like, $action);
        $count = $this->Likes_model->get_count($id_like);
        $this->set_api_content('data', array('status' => 1, 'count' => (int)$count[$id_like]));
    }

}
