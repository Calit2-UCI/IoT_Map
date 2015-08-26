<?php
/**
* Shoutbox API controller
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Dmitry Popenov
* @version $Revision: 2 $ $Date: 2013-01-30 10:07:07 +0400 $
**/
class Api_Shoutbox extends Controller {

	/**
	 * Controller
	 */
	function __construct()
	{
		parent::Controller();
		$this->load->model('Shoutbox_model');
	}

	/**
	 * Get messages
	 *
	 * @param int $max_id
	 * @param int $min_id
	 */
	public function get(){
		$from_id = intval($this->input->get_post('max_id', true));
		if(!$from_id){
			$to_id = intval($this->input->get_post('min_id', true));
			if($to_id){
				$messages = $this->Shoutbox_model->get_old_messages();
			} else {
				$messages = $this->Shoutbox_model->get_messages();
			}
		}else{
			$messages = $this->Shoutbox_model->get_new_messages();
		}

		if (empty($messages)){
			$this->set_api_content('messages', l('api_error_messages_not_found', 'shoutbox'));
			return false;
		}

		$this->set_api_content('data', $messages);
	}

	/**
	 * Add message
	 *
	 * @param string $text
	 * @param int $max_id
	 * @param int $min_id
	 */
	public function add(){
		$result = array();
		$text = trim(strip_tags($this->input->post('text', true)));
		$result = array_merge_recursive($this->Shoutbox_model->add_message($text), $result);
		$result["messages"] = $this->get();
		
		if($result['errors']){
			foreach($result['errors'] as $err){
				log_message('error', 'shoutbox API: '.$err);
				$this->set_api_content('errors', $err);
			}
			return false;
		}

		$this->set_api_content('messages', l('added', 'shoutbox'));
		$this->set_api_content('data', $result);
	}
}