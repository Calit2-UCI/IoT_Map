<?php
/**
* Comments API controller
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Dmitry Popenov
* @version $Revision: 2 $ $Date: 2013-01-30 10:07:07 +0400 $
**/
class Api_Comments extends Controller {

	/**
	 * Controller
	 */
	function __construct()
	{
		parent::Controller();
		$this->load->model('comments/models/Comments_types_model');
		$this->load->model('Comments_model');
	}

	/**
	 * Get comments
	 *
	 * @param string $gid
	 * @param int $id_obj
	 * @param string $page
	 */
	public function get(){
		$gid = $this->input->post('gid', true);
		$id_obj = intval($this->input->post('id_obj', true));
		$page = intval($this->input->post('page', true));
		if($page < 1) $page = 1;

		if(!$gid) {
			log_message('error', 'comments API: Empty comments gid');
			$this->set_api_content('errors', l('api_error_empty_comments_gid', 'comments'));
			return false;
		}
		if(!$id_obj) {
			log_message('error', 'comments API: Empty comments object id');
			$this->set_api_content('errors', l('api_error_empty_comments_id_obj', 'comments'));
			return false;
		}

		$comments = $this->Comments_model->get_comments_page($gid, $id_obj, $page);
		if (empty($comments['comments'])){
			$this->set_api_content('messages', l('api_error_comments_not_found', 'comments'));
			return false;
		}

		$this->set_api_content('data', $comments);
	}

	/**
	 * Get count: comments / pages / comments per page
	 *
	 * @param string $gid
	 * @param int $id_obj
	 */
	public function count(){
		$gid = $this->input->post('gid', true);
		$id_obj = intval($this->input->post('id_obj', true));
		if(!$gid) {
			log_message('error', 'comments API: Empty comments gid');
			$this->set_api_content('errors', l('api_error_empty_comments_gid', 'comments'));
			return false;
		}
		if(!$id_obj) {
			log_message('error', 'comments API: Empty comments object id');
			$this->set_api_content('errors', l('api_error_empty_comments_id_obj', 'comments'));
			return false;
		}
		$comments_count = $this->Comments_model->get_comments_cnt($gid, $id_obj);
		$comments_per_page = intval($this->pg_module->get_module_config('comments', 'items_per_page'));
		$pages_count = ceil($comments_count/$comments_per_page);
		$data = array(
			'gid' => $gid,
			'id_obj' => $id_obj,
			'pages_count' => $pages_count,
			'comments_count' => $comments_count,
			'comments_per_page' => $comments_per_page
		);

		$this->set_api_content('data', $data);
	}

	/**
	 * Get comment by id
	 *
	 * @param int $id
	 */
	public function get_by_id(){
		$id = $this->input->post('id', true);
		if(!$id) {
			log_message('error', 'comments API: Empty comments id');
			$this->set_api_content('errors', l('api_error_empty_comments_id', 'comments'));
			return false;
		}
		$comment = $this->Comments_model->get_comment_by_id($id, '1');
		if (empty($comment['comments'][0])){
			$this->set_api_content('messages', l('api_error_comments_not_found', 'comments'));
			return false;
		}

		$data['comment'] = $comment['comments'][0];

		$this->set_api_content('data', $data);
	}

	/**
	 * Delete comment
	 *
	 * @param int $id
	 */
	public function delete(){
		$id = $this->input->post('id', true);
		if(!$id) {
			log_message('error', 'comments API: Empty comments id');
			$this->set_api_content('errors', l('api_error_empty_comments_id', 'comments'));
			return false;
		}
		$comment = $this->Comments_model->get_comment_by_id($id);
		if(empty($comment['comments'][0])){
			$this->set_api_content('errors', l('api_error_comments_not_found', 'comments'));
			return false;
		}

		$result['status'] = 0;
		$result['is_deleted'] = 0;
		$gid = $comment['comments'][0]['gid'];
		$id_obj = $comment['comments'][0]['id_object'];
		$comments_type = $this->Comments_types_model->get_comments_type_by_gid($gid);
		if($comments_type && $comments_type['status'] && $comment['comments'][0]['can_edit']){
			$result['status'] = 1;
			$result['is_deleted'] = $this->Comments_model->delete_comment_by_id($id);
			$result['count_all'] = $this->Comments_model->get_comments_cnt($gid, $id_obj);
		}else{
			$this->set_api_content('errors', l('api_error_comments_access_denied', 'comments'));
			return false;
		}
		$result['comment'] = $comment['comments'][0];

		$this->set_api_content('data', $result);
	}

	/**
	 * Add comment
	 *
	 * @param string $gid
	 * @param int $id_obj
	 * @param string $text
	 * @param string $user_name
	 */
	public function add(){
		$data['gid'] = $this->input->post('gid', true);
		$data['id_obj'] = intval($this->input->post('id_obj', true));
		$data['text'] = $this->input->post('text', true);
		$data['user_name'] = $this->input->post('user_name', true);
		if(!$data['gid']) {
			log_message('error', 'comments API: Empty comments gid');
			$this->set_api_content('errors', l('api_error_empty_comments_gid', 'comments'));
			return false;
		}
		if(!$data['id_obj']) {
			log_message('error', 'comments API: Empty comments object id');
			$this->set_api_content('errors', l('api_error_empty_comments_id_obj', 'comments'));
			return false;
		}

		$comments_type = $this->Comments_types_model->get_comments_type_by_gid($data['gid']);
		if(empty($comments_type)){
			$this->set_api_content('errors', l('api_error_comments_access_denied', 'comments'));
			return false;
		}

		$validate_comment = $this->Comments_model->validate_comment($data);
		if($validate_comment['errors']){
			foreach($validate_comment['errors'] as $err){
				log_message('error', 'comments API: '.$err);
				$this->set_api_content('errors', $err);
			}
			return false;
		}else{
			$data['text'] = mb_substr($validate_comment['text'], 0, $comments_type['settings']['char_count'], 'UTF-8');
			if(!$data['text']){
				$err = l('error_comment_text', 'comments');
				$this->set_api_content('errors', $err);
				return false;
			}
		}

		if($this->session->userdata('auth_type') != 'user'){
			if(!$comments_type['settings']['guest_access']){
				$this->set_api_content('errors', l('api_error_comments_access_denied', 'comments'));
				return false;
			}
			$data['user_name'] = mb_substr($data['user_name'], 0, 50, 'UTF-8');
			if(!$data['user_name']){
				$err = l('error_user_name', 'comments');
				$this->set_api_content('errors', $err);
				return false;
			}
		}
		$comment = array();
		if($comments_type['status'] && $this->session->userdata('auth_type') != 'admin'){
			$result['status'] = 1;
			$id = $this->Comments_model->add_comment($data['gid'], $data['id_obj'], $data['text'], $data['user_name']);
			if($id){
				$comments = $this->Comments_model->get_comment_by_id($id);
				$comment = !empty($comments['comments'][0]) ? $comments['comments'][0] : array();
			}
		}
		if(!$comment){
			$this->set_api_content('errors', l('api_error_comments_access_denied', 'comments'));
			return false;
		}

		$result['moderation'] = $comment['status'] ? 0 : 1;
		$result['comment'] = $comment;

		$this->set_api_content('data', $result);
	}

	/**
	 * Get comments types
	 *
	 * @param string $gid
	 */
	public function types(){
		$gid = trim(strip_tags($this->input->post('gid', true)));
		$types = $gid ? $this->Comments_types_model->get_comments_type_by_gid($gid) : $this->Comments_types_model->get_comments_types(null, null);
		if($gid && $types){
			$data['types'][$types['gid']] = $types;
		}elseif($types){
			$data['types'] = $types;
		}else{
			$data['types'] = array();
		}

		$this->set_api_content('data', $data);
	}
}