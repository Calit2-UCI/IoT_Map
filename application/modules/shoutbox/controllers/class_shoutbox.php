<?php
/**
* Shoutbox controller
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Dmitry Popenov
* @version $Revision: 2 $ $Date: 2013-01-30 10:07:07 +0400 $
**/
class Class_shoutbox extends Controller {

	protected $user_id;

	public function __construct(){
		parent::Controller();
		$this->user_id = intval($this->session->userdata('user_id'));
		$this->load->model('shoutbox/models/Shoutbox_model');
	}

	protected function _check_new_messages(){
		$result = $this->Shoutbox_model->check_new_messages(0);
		return $result;
	}

	private function get_messages($type = ''){
		$from_id = intval($this->input->get_post('max_id', true));
		if(!$from_id){
			$to_id = intval($this->input->get_post('min_id', true));
			if($to_id){
				$result['msg'] = $this->Shoutbox_model->get_old_messages();
			} else {
				$result['msg'] = $this->Shoutbox_model->get_messages();
			}
		}else{
			$result['msg'] = $this->Shoutbox_model->get_new_messages();
		}
		
		$result['min_id'] = $result['max_id'] = 0;
		foreach($result['msg'] as $msg){
			if(intval($msg['id']) > $result['max_id']){
				$result['max_id'] = intval($msg['id']);
			}
			if(intval($msg['id']) < $result['min_id'] || !$result['min_id']){
				$result['min_id'] = intval($msg['id']);
			}
		}
		return $result;
	}
	
	protected function _get_messages(){
		$result = $this->get_messages();
	//	print_r($result);
		return $result;
	}
	
	protected function _post_message(){
		$result = array();
		$text = trim(strip_tags($this->input->post('text', true)));
		$result = array_merge_recursive($this->Shoutbox_model->add_message($text), $result);
		$result["messages"] = $this->get_messages();
		return $result;
	}
}