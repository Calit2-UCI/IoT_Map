<?php
/**
* Polls api controller
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Alexander Batukhtin <abatukhtin@pilotgroup.net>
**/

Class Api_Polls extends Controller {

	function __construct() {
		parent::Controller();
		$this->load->model('Polls_model');
	}

	/**
	 * Returns poll data
	 *
	 * @param int $poll_id Default = null
	 */
	public function poll() {
		$poll_id = $this->input->post('poll_id', true);
		$this->load->helper('polls');

		$poll = $this->Polls_model->get_poll_by_id($poll_id);
		if(!$poll) {
			log_message('error', 'polls API: Poll not found');
			$this->set_api_content('error', l('api_error_poll_not_found', 'polls'));
			return false;
		}
		$this->set_api_content('data', array('poll' => $poll));
	}

	/**
	 * Saves poll answer
	 *
	 * @param int $poll_id
	 * @param int $answer_id
	 * @param string $comment
	 */
	public function save_result() {
		$poll_id = $this->input->post('poll_id', true);
		if(!$poll_id) {
			log_message('error', 'polls API: Empty poll id');
			$this->set_api_content('error', l('api_error_empty_poll_id', 'polls'));
			return false;
		}
		$poll = $this->Polls_model->get_poll_by_id($poll_id);
		if(!$poll) {
			log_message('error', 'polls API: Poll not found');
			$this->set_api_content('error', l('api_error_poll_not_found', 'polls'));
			return false;
		}
		$answer_id	= $this->input->post('answer_id', true);
		if(!$answer_id) {
			log_message('error', 'polls API: Empty answer id');
			$this->set_api_content('error', l('api_error_empty_answer_id', 'polls'));
			return false;
		}
		$comment = $this->input->post('comment', true);
		$user_id = $this->session->userdata('user_id');

		// Save
		$respond['user_id'] = $user_id;
		$respond['poll_id'] = $poll['id'];
		$respond['agent']	= 'api';
		$respond['ip']		= $this->input->ip_address();
		$respond['date_add'] = date('Y-m-d H:i:s');

		if (is_array($answer_id)) {
			foreach ($answer_id as $answer) {
				$respond['answer_' . $answer] =
						isset($respond['answer_' . $answer]) ? $respond['answer_' . $answer] + 1 : 1;
			}
		} else {
			$respond['answer_' . $answer_id] =
					isset($respond['answer_' . $answer_id]) ? $respond['answer_' . $answer_id] + 1 : 1;
		}

		$respond['comment'] = (string) $comment;
		$this->Polls_model->save_respond($respond);

		$max_answers = $this->pg_module->get_module_config('polls', 'max_answers');
		if(!$max_answers) {
			$max_answers =  10;
		}
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

		$data = array('poll' => $poll, 'max_results' => $max_results, 'max_answers' => $max_answers);
		$this->set_api_content('data', $data);
	}

	/**
	 * Polls list
	 *
	 */
	public function polls_list() {
		$language = $this->pg_language->current_lang_id;
		$params = array();
		$params['where']['status'] = 1;
		$params['where_in']['language'] = array('0', $language);
		$params['where']['show_results'] = 1;
		$polls = $this->Polls_model->get_polls_list(null, null, array('date_add' => 'ASC'), $params);
		$this->set_api_content('data', array('polls' => $polls, 'language' => $language));
	}

}
