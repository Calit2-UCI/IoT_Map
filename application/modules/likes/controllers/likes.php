<?php

/**
 * Likes user side controller
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @author Alexander Batukhtin <abatukhtin@pilotgroup.net>
 * */
class Likes extends Controller
{

    private $_users_max_count = 5;

    function __construct()
    {
        parent::__construct();
    }

    public function ajax_like()
    {
        $id_like = filter_input(INPUT_POST, 'like_id');
        $like_status = filter_input(INPUT_POST, 'like_action');

        $id_user = $this->session->userdata('user_id');
        $this->load->model('Likes_model');
        $this->Likes_model->like($id_user, $id_like, $like_status);
        $count = $this->Likes_model->get_count($id_like);
        $response = array('status' => 1, 'count' => $count[$id_like]);
        exit(json_encode($response));
    }

    public function ajax_get_users()
    {
        $id_like = filter_input(INPUT_POST, 'like_id');
        $get_all = filter_input(INPUT_POST, 'get_all');
        $this->load->model('Likes_model');
        if ($get_all) {
            $users = $this->Likes_model->get_users_by_like($id_like, 2000);
            if (!count($users)) {
                exit;
            }
        } else {
            $users = $this->Likes_model->get_users_by_like($id_like, $this->_users_max_count + 1);
            $count = count($users);
            if (0 === $count) {
                exit;
            } elseif ($this->_users_max_count < $count) {
                $this->template_lite->assign('has_more', true);
                array_pop($users);
            }
        }

        $id_user = $this->session->userdata('user_id');
        foreach ($users as $key => $user) {
            if ($id_user === $user['id']) {
                $temp = array($key => $users[$key]);
                unset($users[$key]);
                $users = $temp + $users;
                break;
            }
        }

        $this->template_lite->assign('like_users', $users);
        $data = $this->template_lite->fetch('users');
        exit($data);
    }

}
