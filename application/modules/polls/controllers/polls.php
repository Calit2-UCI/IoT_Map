<?php
/**
* Polls user side controller
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

Class Polls extends Controller {

	function __construct() {
		parent::Controller();
		$this->load->model('Polls_model');
	}

	public function ajax_poll($poll_id = 0, $one_poll_place = null) {
		$this->load->helper('polls');
		echo show_poll_place_block(array('id_poll' => $poll_id, 'one_poll_place' => $one_poll_place));
	}

	public function ajax_save_result() {
		$poll = $this->Polls_model->get_poll_by_id($this->input->post('poll_id', true));
		if (!$poll) {
			return false;
		}
		$this->load->helper('polls');
		$this->load->helper('cookie');
		$this->load->library('user_agent');

		// Array
		$poll_answer_id = $this->input->post('answer', true);
		$poll_comment	= $this->input->post('poll_comment', true);
		$request = $this->Polls_model->validate_comment($poll_comment);
		if(!empty($request['errors'])){
			$this->system_messages->add_message('error', $request["errors"]);
			exit('error');
		}
		$user_id		= $this->session->userdata('user_id');

		$cookie = array(
				'name'		=> 'polls[' . $poll['id'] . ']',
				'value'		=> '0',
				'expire'	=> time() + '86500',
				'domain'	=> COOKIE_SITE_SERVER,
				'path'		=> '/' . SITE_SUBFOLDER,
			);
		set_cookie($cookie);

		// Save
		$respond['user_id'] = floor($user_id);
		$respond['poll_id'] = $poll['id'];
		$respond['agent']	= $this->agent->agent_string();
		$respond['ip']		= $this->input->ip_address();
		$respond['date_add'] = date("Y-m-d H:i:s");

		if (is_array($poll_answer_id)) {
			foreach ($poll_answer_id as $answer) {
				$respond['answer_' . $answer] = isset($respond['answer_' . $answer]) ? $respond['answer_' . $answer] + 1 : 1;
			}
		} else {
			$respond['answer_' . $poll_answer_id] = isset($respond['answer_' . $poll_answer_id]) ? $respond['answer_' . $poll_answer_id] + 1 : 1;
		}

		$respond['comment'] = (string) $poll_comment;
		$this->Polls_model->save_respond($respond);

		$this->template_lite->assign('cur_lang', $language);
		$this->template_lite->assign('language', $poll['language']);

		$max_answers = $this->pg_module->get_module_config('polls', 'max_answers');
		$max_answers = $max_answers ? $max_answers : 10;
		$max_results = 0;

		for ($i = 1; $i <= $max_answers; $i++) {
			if (isset($poll['results'][$i])) {
				$max_results = $max_results + floor($poll['results'][$i]);
			}
		}

		switch ($poll['sorter']) {
			case 2:
				arsort($poll['results']);
				break;
			case 1:
				asort($poll['results']);
				break;
		}

		$this->template_lite->assign('poll_data', $poll);
		$this->template_lite->assign('user_id', $user_id);
		$this->template_lite->assign('max_results', $max_results);
		$this->template_lite->assign('max_answers', $max_answers);

		echo show_poll_place_block(array('id_poll' => $poll['id'], 'one_poll_place' => true));
	}

	public function index() {
		$user_id = $this->session->userdata('user_id');
		$user_type = $this->session->userdata('user_type');
		$auth_type = $this->session->userdata('auth_type');
		$this->load->model('Users_model');

		$language = $this->pg_language->current_lang_id;

		$params = array();
		$params['where']['status'] = 1;
		$params['where_in']['language'] = array('0', $language);
		$params["where_sql"][] = ' ((use_expiration = 0 OR (use_expiration = 1 AND date_end >= \'' . date('Y-m-d H:i:s') . '\')) AND date_start < \'' . date('Y-m-d H:i:s') . '\') ';
		if($auth_type=='user'){
			$params['where_in']['poll_type'] = array('-1','0',$user_type);
		}else{
			$params['where_in']['poll_type'] = array('-2','0');
		}

		$polls = $this->Polls_model->get_polls_list(null, null, array('date_add' => 'ASC'), $params);
		$this->template_lite->assign('language', $language);

		$this->load->model('Menu_model');
		$this->Menu_model->breadcrumbs_set_active(l('header_polls_results' ,'polls'));
		$this->template_lite->assign('polls', $polls);
		$this->template_lite->view('list_polls');
	}

}
