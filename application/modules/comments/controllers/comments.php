<?php
/**
* Comments controller
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Dmitry Popenov
* @version $Revision: 2 $ $Date: 2013-01-30 10:07:07 +0400 $
**/
class Comments extends Controller {

	/**
	 * Controller
	 */
	function __construct()
	{
		parent::Controller();
		$this->load->model('comments/models/Comments_types_model');
		$this->load->model('Comments_model');
		$this->template_lite->assign('date_format', $this->pg_date->get_format('date_time_literal', 'st'));
	}

	/*
	 * COMMENTS FUNCTIONS
	 */

	public function ajax_add_comment(){
		$this->load->helper('text');
		$result['status'] = 0;
		$result['errors'] = l('error', 'comments');
		$data['gid'] = $this->input->post('gid', true);
		$data['id_obj'] = $this->input->post('id_obj', true);
		$data['text'] = $this->input->post('text', true);
		$data['user_name'] = $this->input->post('user_name', true);

		$comments_type = $this->Comments_types_model->get_comments_type_by_gid($data['gid']);
		if(empty($comments_type)){
			$result['errors'] = l('error', 'comments');
			exit(json_encode($result));
		}

		$validate_comment = $this->Comments_model->validate_comment($data);
		if($validate_comment['errors']){
			$result['errors'] = $validate_comment['errors'];
			exit(json_encode($result));
		}else{
			$data['text'] = mb_substr($validate_comment['text'], 0, $comments_type['settings']['char_count'], 'UTF-8');
			if(!$data['text']){
				$result['errors'] = l('error_comment_text', 'comments');
				exit(json_encode($result));
			}
		}
		$max_id = intval($this->input->post('max_id', true));
		$min_id = intval($this->input->post('min_id', true));
		if($this->session->userdata('auth_type') != 'user'){
			$data['user_name'] = mb_substr($data['user_name'], 0, 50, 'UTF-8');
			$email = $this->input->post('email'); //botoguard
			if(!$data['user_name']){
				$result['errors'] = l('error_user_name', 'comments');
			}
			if(!$data['user_name'] || $email || !$comments_type['settings']['guest_access']){
				exit(json_encode($result));
			}
		}
		if($comments_type['status'] && $this->session->userdata('auth_type') != 'admin'
			&& ($comments_type['settings']['guest_access'] || (!$comments_type['settings']['guest_access'] && $this->session->userdata('auth_type')))
		){
			$result['status'] = 1;
			$result['id'] = $this->Comments_model->add_comment($data['gid'], $data['id_obj'], $data['text'], $data['user_name']);
			if($result['id']){
				$result['errors'] = '';
				$result['comments'] = $this->Comments_model->get_comments_range_by_gid_obj($data['gid'], $data['id_obj'], $max_id);
				$result['comment'] = $this->Comments_model->get_comment_by_id($result['id']);
				$result['moderation'] = (isset($result['comment']['comments'][0]) && $result['comment']['comments'][0]['status']) ? 0 : 1;
				$this->template_lite->assign('comments', $result['comments']);
				$this->template_lite->assign('comments_type', $comments_type);
				$result['comments_html'] = $this->template_lite->fetch_final('comments_block', 'user', 'comments');
			}
		}
		exit(json_encode($result));
	}

	public function ajax_delete_comment(){
		$id = $this->input->post('id', true);
		$result = $this->Comments_model->get_comment_by_id($id);
		$result['status'] = 0;
		$result['is_deleted'] = 0;
		$result['error'] = l('error', 'comments');
		if($result['comments']){
			$gid = $result['comments'][0]['gid'];
			$id_obj = $result['comments'][0]['id_object'];
			$comments_type = $this->Comments_types_model->get_comments_type_by_gid($gid);
			if($comments_type && $comments_type['status']
				&& $this->session->userdata('auth_type') != 'admin'
				&& $result['comments'][0]['can_edit']
				&& ($comments_type['settings']['guest_access'] || (!$comments_type['settings']['guest_access'] && $this->session->userdata('auth_type')))
			){
				$result['status'] = 1;
				$result['is_deleted'] = $this->Comments_model->delete_comment_by_id($id);
				$result['count_all'] = $this->Comments_model->get_comments_cnt($gid, $id_obj);
			}
		}

		exit(json_encode($result));
	}

	public function ajax_load_comments(){
		$gid = $this->input->post('gid', true);
		$id_obj = $this->input->post('id_obj', true);
		$max_id = intval($this->input->post('max_id', true));
		$min_id = intval($this->input->post('min_id', true));
		$with_form = intval($this->input->post('with_form', true));
		$result['status'] = 0;
		$result['error'] = l('error', 'comments');
		$comments_type = $this->Comments_types_model->get_comments_type_by_gid($gid);
		if(!($comments_type && $comments_type['status'])){
			exit(json_encode($result));
		}

		$result['comments'] = $this->Comments_model->get_comments_range_by_gid_obj($gid, $id_obj, 0, $min_id, false, null);
		$result['comments']['show_form'] = ($this->session->userdata('auth_type') == 'user' || ($comments_type['settings']['guest_access'] && $this->session->userdata('auth_type') != 'admin')) ? 1 : 0;
		if($result['comments']['comments'] || $with_form){
			$result['status'] = 1;
			$this->template_lite->assign('comments', $result['comments']);
			$this->template_lite->assign('comments_type', $comments_type);
			$this->template_lite->assign('ajax', 1);
			if($with_form){
				$result['comments_html'] = $this->template_lite->fetch_final('comments_form', 'user', 'comments');
			}else{
				$result['comments_html'] = $this->template_lite->fetch_final('comments_block', 'user', 'comments');
			}
		}

		exit(json_encode($result));
	}

	public function ajax_like_comment(){
		$id = intval($this->input->post('id', true));
		$result['status'] = 0;
		$result['error'] = l('error', 'comments');
		$comments = $this->Comments_model->get_comment_by_id($id);
		$comment = isset($comments['comments'][0]) ? $comments['comments'][0] : array();

		$comments_type = array();
		$comments_type = $comment ? $this->Comments_types_model->get_comments_type_by_gid($comment['gid']) : array();

		if(!($comment && $comments_type && $comments_type['status'] && $comments_type['settings']['use_likes']) && $comment['can_like']){
			exit(json_encode($result));
		}

		$this->load->helper('cookie');
		$sign = $comment['is_liked'] ? '-' : '+';
		set_cookie(array(
			'name'		=> "comment_like_$id",
			'value'		=> $comment['is_liked'] ? 0 : 1,
			'expire'	=> 3600*24*1000,
			'domain'	=> COOKIE_SITE_SERVER,
			'path'		=> '/' . SITE_SUBFOLDER
		));
		$result['likes'] = $this->Comments_model->like_comment($id, $sign);
		$result['status'] = 1;
		$result['error'] = '';
		$result['a_title'] = $comment['is_liked'] ? l('like', 'comments') : l('unlike', 'comments');

		exit(json_encode($result));
	}
}