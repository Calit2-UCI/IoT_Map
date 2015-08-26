<?php
/**
* Mailbox api controller
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Mikhail Chernov <mchernov@pilotgroup.net>
**/


Class Api_Mailbox extends Controller {

	/**
	 * Constructor
	 */
	function __construct() {
		parent::Controller();
		$this->load->model('Mailbox_model');
	}

	private function _view($folder, $page = 1){
		$this->_folder_content($folder, $page);
	}
	
	public function index(){$this->inbox();}
	public function inbox($page=1){$this->_view('inbox', $page);}
	public function outbox($page=1){$this->_view('outbox', $page);}
	public function drafts($page=1){$this->_view('drafts', $page);}
	public function trash($page=1){$this->_view('trash', $page);}
	public function spam($page=1){$this->_view('spam', $page);}
	
	/**
	 * Return folder content
	 * @param string $folder folder name
	 * @param string $keyword search keyword
	 * @param integer $page page of results
	 */
	public function _folder_content($folder, $page){
		$where = array();
		$where['where']['id_user'] = $this->session->userdata('user_id');
		$where['where']['folder'] = $folder;
		
		$this->load->helper('sort_order');
		$items_on_page = $this->pg_module->get_module_config('mailbox', 'items_per_page');
		$mailbox_count = $this->Mailbox_model->get_messages_count($where);
		$page = get_exists_page_number($page, $mailbox_count, $items_on_page);
		
		$order_by = array('date_add' => 'DESC');
		$this->Mailbox_model->set_format_settings(array('get_sender'=>true, 'get_recipient'=>true));
		$messages = $this->Mailbox_model->get_messages($where, $page, $items_on_page, $order_by);
		$this->Mailbox_model->set_format_settings(array('get_sender'=>false, 'get_recipient'=>false));
		
		$this->set_api_content('data', $messages);
	}
	
	/**
	 * Read message action
	 * @param integer $message_id message identifier
	 */
	public function view($message_id){
		$this->load->model('services/models/Services_users_model');
		
		$user_id = $this->session->userdata('user_id');
		
		$this->Mailbox_model->set_format_settings('get_attaches', true);
		$message = $this->Mailbox_model->get_message_by_id($message_id);
		$this->Mailbox_model->set_format_settings('get_attaches', false);
		
		if(!$message || $message['id_user'] != $user_id){
			$this->set_api_content('error', l('error_api_not_access', 'mailbox'));
			return;
		}
		
		if($message['is_new'] && $message['id_from_user'] != $user_id){
			$this->load->model('mailbox/models/Mailbox_service_model');
			$mailbox_service = $this->Mailbox_service_model->get_service_by_user_service($user_id, 'read_message_service');
			if($mailbox_service['service_data']['message_count']){
				$mailbox_service['service_data']['message_count']--;
				$save_data = array('service_data'=>$mailbox_service['service_data']);
				$validate_data = $this->Mailbox_service_model->validate_service($mailbox_service['id'], $save_data);
				$this->Mailbox_service_model->save_service($mailbox_service['id'], $validate_data['data']);
			}else{
				$return = $this->Mailbox_model->service_available_read_message($user_id);
				if(!$return['available']){
					$this->set_api_content('errors', l('error_api_not_read', 'mailbox'));
					return;
				}
			}
		}
		
		if($message['is_new']){
			$save_data = array('is_new'=>0, 'date_read'=>date('Y-m-s H:i:s'));
			$this->Mailbox_model->save_message($message_id, $save_data);
		}
		
		$this->set_api_content('data', $message);
	}
	
	/**
	 * Return thread pages
	 * @param integer $message_id message identifier
	 * @param string $direction direction
	 * @param integer $page page of results
	 */
	public function thread($message_id, $direction, $page=0){
		$user_id = $this->session->userdata('user_id');
		
		$message = $this->Mailbox_model->get_message_by_id($message_id);
		if(!$message || $message['id_user'] != $user_id){
			$this->set_api_content('error', l('error_api_not_access', 'mailbox'));
			return;
		}
		
		$result = $this->Mailbox_model->service_available_read_message($user_id);
		
		if($message['id_thread']){
			$items_on_page = $this->pg_module->get_module_config('mailbox', 'items_per_page');
			$params = array(
				'where'=>array(
					'id_thread' => $message['id_thread'], 
					'id !=' => $message['id'], 
					'id_user' => $message['id_user'],
					'date_add '.($direction == 'next' ? '>' : '<')=>date('Y-m-d H:i:s', strtotime($message['date_add']))
				),
			);
			$thread_count = $this->Mailbox_model->get_messages_count($params);
			if($thread_count){
				$thread = $this->Mailbox_model->get_messages($params, $page+1, $items_on_page, array('date_add'=>'DESC'));
				foreach($thread as $i=>$message){
					if($message['is_new'] && !$result['available']) $thread[$i]['message'] = l('error_api_not_read', 'mailbox');
				}
				$this->set_api_content('data', $thread);
			}else{
				$this->set_api_content('data', array());
			}
			
			$thread_count = max($thread_count-($page+1)*$items_on_page, 0);
			$this->set_api_content('count', $thread_count);
		}
	}
	
	/**
	 * Write message action
	 */
	public function send($message_id=0){
		$user_id = $this->session->userdata('user_id');
		
		$is_user_messages = $this->Mailbox_model->is_user_messages($message_id, $user_id);
		if(!$is_user_messages){
			$this->set_api_content('error', l('error_api_not_access', 'mailbox'));
			return;
		}
		
		$validate_data = $this->Mailbox_model->validate_send($message_id);
		if(!empty($validate_data['errors'])){
			$this->set_api_content("errors", $validate_data['errors']);
		}else{
			$is_send = $this->Mailbox_model->send_message($message_id);
			if($is_send){
				$this->set_api_content("success", l('success_message_send', 'mailbox'));
			}else{
				$this->set_api_content("errors", l('error_service_unavailable', 'mailbox'));
			}
		}
	}	
	
	/**
	 * Reply message action
	 */
	public function reply($message_id){
		$user_id = $this->session->userdata('user_id');
		
		$is_user_messages = $this->Mailbox_model->is_user_messages($message_id, $user_id);
		if(!$is_user_messages){
			$this->set_api_content('error', l('error_api_not_access', 'mailbox'));
		}else{
			$validate_data = $this->Mailbox_model->validate_send($message_id);
			if(!empty($validate_data['errors'])){
				$this->set_api_content("errors", $validate_data['errors']);
			}else{
				$is_send = $this->Mailbox_model->send_message($message_id);
				if($is_send){
					$this->set_api_content("success", l('success_message_reply', 'mailbox'));
				}else{
					$this->set_api_content("errors", l('error_service_unavailable', 'mailbox'));
				}
			}
		}
	}
	
	/**
	 * Upload attachement
	 * @param integer $message_id message identifier
	 */
	public function upload_attach($message_id){
		$user_id = $this->session->userdata('user_id');
		
		$is_user_messages = $this->Mailbox_model->is_user_messages($message_id, $user_id);
		if(!$is_user_messages){
			$this->set_api_content('error', l('error_api_not_access', 'mailbox'));
			return;
		}
		
		$validate_data = $this->Mailbox_model->validate_attach(null, 'attach', $message_id);
		if(!empty($validate_data['errors'])){
			$this->set_api_content("errors", $validate_data['errors']);
		}else{
			$result = $this->Mailbox_model->upload_attach(null, 'attach', $message_id);
			$size = $result['filesize'];
			$i = 1;
			while($size > 1000){
				$size /= 1024;
				$i++;
			}
			$size = ceil($size).' '.l('text_filesize_'.$i, 'mailbox');
			
			$data = array(
				'id' => $result['id'],
				'link' => $result['format']['file_url'],
				'name' => $result['filename'],
				'size' => $size,
			);
			$this->set_api_content('data', $data);
			$this->set_api_content("success", l('success_attach_uploaded', 'mailbox'));
		}
	}
	
	/**
	 * Save draft
	 * @param integer $message_id message identifier
	 */
	public function save_draft($message_id=null){
		$user_id = $this->session->userdata('user_id');
		
		if($message_id){
			$is_user_messages = $this->Mailbox_model->is_user_messages($message_id, $user_id);
			if(!$is_user_messages){
				$this->set_api_content('status', l('text_message_no_saved', 'mailbox'));
				$this->set_api_content('error', l('error_api_not_access', 'mailbox'));
				return;
			}
		}else{
			$message_id = null;
		}
		
		$post_data = array();
		$post_data['id_to_user'] = $this->input->post('id_to_user', true);
		$post_data['subject'] = $this->input->post('subject', true);
		$post_data['message'] = $this->input->post('message', true);
		
		if(!$message_id){
			$post_data['id_user'] = $user_id;
			$post_data['id_from_user'] = $user_id;
			$post_data['is_new'] = 0;
			$post_data['folder'] = 'drafts';
		}
		
		$validate_data = $this->Mailbox_model->validate_draft($message_id, $post_data);
		if(!empty($validate_data['errors'])){
			$this->set_api_content('status', l('text_message_no_saved', 'mailbox'));
			$this->set_api_content('error', implode('<br>', $validate_data['errors']));
		}else{
			$message_id = $this->Mailbox_model->save_message($message_id, $validate_data['data']);
			$this->set_api_content('message_id', $message_id);
			$this->set_api_content('status', l('text_message_saved', 'mailbox'));
		}
	}
	
	/**
	 * Save reply by ajax
	 * @param integer $message_id message identifier
	 * @param integer $reply_id reply identifier
	 */
	public function save_reply($message_id, $reply_id=null){
		$user_id = $this->session->userdata('user_id');
	
		$post_data = array();
		$post_data['message'] = $this->input->post('message', true);
	
		if($reply_id){
			$is_user_messages = $this->Mailbox_model->is_user_messages($reply_id, $user_id);
			if(!$is_user_messages){
				$this->set_api_content('status', l('text_message_no_saved', 'mailbox'));
				$this->set_api_content('error', l('error_api_not_access', 'mailbox'));
				return;
			}
		}else{
			$reply_id = null;
			
			$this->Mailbox_model->set_format_settings('use_format', false);
			$message = $this->Mailbox_model->get_message_by_id($message_id);
			$this->Mailbox_model->set_format_settings('use_format', true);

			if(!$message || $message['id_user'] != $user_id){
				$this->set_api_content('status', l('text_message_no_saved', 'mailbox'));
				$this->set_api_content('error', l('error_api_not_access', 'mailbox'));
				return;
			}

			$post_data['id_user'] = $user_id;
			$post_data['id_reply'] = $message_id;
			$post_data['id_from_user'] = $message['id_to_user'];
			$post_data['id_to_user'] = $message['id_from_user'];
			$post_data['subject'] = $message['subject'];
			$post_data['id_thread'] = $message['id_thread'];
			$post_data['is_new'] = 0;
			$post_data['folder'] = 'drafts';
			
			if(!$message['id_reply']) $post_data['subject'] = l('text_reply_subject', 'mailbox').': '.$post_data['subject'];
		}
		
		$validate_data = $this->Mailbox_model->validate_draft($reply_id, $post_data);
		if(!empty($validate_data['errors'])){
			$this->set_api_content('status', l('text_message_no_saved', 'mailbox'));
			$this->set_api_content('error', implode('<br>', $validate_data['errors']));
		}else{
			$reply_id = $this->Mailbox_model->save_message($reply_id, $validate_data['data']);
			$this->set_api_content('reply_id', $reply_id);
			$this->set_api_content('status', l('text_message_saved', 'mailbox'));
		}
	}
	
	/**
	 * Remove attachement
	 * @param integer $attach_id attachement identifier
	 * @param integer $message_id message identifier
	 */
	public function delete_attach($attach_id, $message_id){
		$user_id = $this->session->userdata('user_id');
		
		$is_user_messages = $this->Mailbox_model->is_user_messages($message_id, $user_id);
		if(!$is_user_messages){
			$this->set_api_content('error', l('error_api_not_access', 'mailbox'));
		}else{
			$this->Mailbox_model->delete_attach($attach_id, $message_id);
			$this->set_api_content('success', l('success_attach_deleted', 'mailbox'));
		}
	}
	
	/**
	 * Mark message as spam
	 * @param integer $message_id message identifier
	 */
	public function mark_spam_message($message_id){
		$user_id = $this->session->userdata('user_id');
		
		$is_user_messages = $this->Mailbox_model->is_user_messages($message_id, $user_id);
		if(!$is_user_messages){
			$this->set_api_content('error', l('error_api_not_access', 'mailbox'));
		}else{
			$this->Mailbox_model->move_messages_in_folder(array($message_id), 'spam');
			$this->set_api_content('success', l('success_message_mark_spam', 'mailbox'));
		}
	}
	
	/**
	 * Mark messages as spam
	 */
	public function mark_spam_messages(){
		$res_ids = $this->input->post('ids', true);
		$res_ids = $this->Mailbox_model->validate_messages_ids($res_ids);
		if(!empty($res_ids)){
			$user_id = $this->session->userdata('user_id');
			
			$is_user_messages = $this->Mailbox_model->is_user_messages($res_ids, $user_id);
			if(!$is_user_messages){
				$this->set_api_content('error', l('error_api_not_access', 'mailbox'));
			}else{
				$this->Mailbox_model->move_messages_in_folder($res_ids, 'spam');
				$this->set_api_content('success', l('messages_in_spam', 'mailbox'));
			}
		}else{
			$this->set_api_content('error', l('error_api_empty_items', 'mailbox'));
		}
	}
	
	/**
	 * Unmark message as spam
	 * @param integer $message_id message identifier
	 */
	public function unmark_spam_message($message_id){
		$user_id = $this->session->userdata('user_id');
		
		$is_user_messages = $this->Mailbox_model->is_user_messages($message_id, $user_id);
		if(!$is_user_messages){
			$this->set_api_content('error', l('error_api_not_access', 'mailbox'));
		}else{
			$this->Mailbox_model->unmark_spam_messages(array($message_id));
			$this->set_api_content('success', l('success_message_unmark_spam', 'mailbox'));
		}
	}
	
	/**
	 * Unmark messages as spam
	 */
	public function unmark_spam_messages(){
		$res_ids = $this->input->post('ids', true);
		$res_ids = $this->Mailbox_model->validate_messages_ids($res_ids);
		if(!empty($res_ids)){
			$user_id = $this->session->userdata('user_id');
			$is_user_messages = $this->Mailbox_model->is_user_messages($res_ids, $user_id);
			if(!$is_user_messages){
				$this->set_api_content('error', l('error_api_not_access', 'mailbox'));
			}else{
				$this->Mailbox_model->unmark_spam_messages($res_ids);
				$this->set_api_content('success', l('success_messages_unmark_spam', 'mailbox'));
			}
		}else{
			$this->set_api_content('error', l('error_api_empty_items', 'mailbox'));
		}
	}
	
	/**
	 * Remove message
	 * @param integer $message_id message identifier
	 */
	public function delete_message($message_id){
		$user_id = $this->session->userdata('user_id');
		
		$is_user_messages = $this->Mailbox_model->is_user_messages($message_id, $user_id);
		if(!$is_user_messages){
			$this->set_api_content('error', l('error_api_not_access', 'mailbox'));
		}else{
			$this->Mailbox_model->move_messages_in_folder(array($message_id), 'trash');
			$this->set_api_content('success', l('success_message_trash', 'mailbox'));
		}
	}
	
	/**
	 * Remove messages
	 */
	public function delete_messages(){
		$res_ids = $this->input->post('ids', true);
		$res_ids = $this->Mailbox_model->validate_messages_ids($res_ids);
		
		if(!empty($res_ids)){
			$user_id = $this->session->userdata('user_id');
		
			$is_user_messages = $this->Mailbox_model->is_user_messages($res_ids, $user_id);
			if(!$is_user_messages){
				$this->set_api_content('error', l('error_api_not_access', 'mailbox'));
			}else{
				$this->Mailbox_model->move_messages_in_folder($res_ids, 'trash');
				$this->set_api_content('success', l('messages_in_trash', 'mailbox'));
			}
		}else{
			$this->set_api_content('error', l('error_api_empty_items', 'mailbox'));
		}
	}
	
	/**
	 * Clear removed messages
	 */
	public function clear_trash(){
		$res_ids = $this->input->post('ids', true);
		$res_ids = $this->Mailbox_model->validate_messages_ids($res_ids);
		
		if(!empty($res_ids)){
			$user_id = $this->session->userdata('user_id');
		
			$is_user_messages = $this->Mailbox_model->is_user_messages($res_ids, $user_id);
			if(!$is_user_messages){
				$this->set_api_content('error', l('error_api_not_access', 'mailbox'));
			}else{
				$this->Mailbox_model->delete_messages($res_ids);
				$this->set_api_content('success', l('delete_forever', 'mailbox'));
			}
		}else{
			$this->set_api_content('error', l('error_api_empty_items', 'mailbox'));
		}
	}
	
	/**
	 * Untrash message
	 * @param integer $message_id message identifier
	 */
	public function untrash_message($message_id){
		$user_id = $this->session->userdata('user_id');
		
		$is_user_messages = $this->Mailbox_model->is_user_messages($message_id, $user_id);
		if(!$is_user_messages){
			$this->set_api_content('error', l('error_api_not_access', 'mailbox'));
		}else{
			$this->Mailbox_model->untrash_messages(array($message_id));
			$this->set_api_content('success', l('success_message_untrash', 'mailbox'));
		}
	}
	
	/**
	 * Untrash messages
	 */
	public function untrash_messages(){
		$res_ids = $this->input->post('ids', true);
		$res_ids = $this->Mailbox_model->validate_messages_ids($res_ids);
		
		if(empty($res_ids)){
			$this->set_api_content('error', l('error_api_empty_items', 'mailbox'));
			return;
		}
		
		$user_id = $this->session->userdata('user_id');
		$is_user_messages = $this->Mailbox_model->is_user_messages($res_ids, $user_id);
		if(!$is_user_messages){
			$this->set_api_content('error', l('error_api_not_access', 'mailbox'));
		}
		
		$this->Mailbox_model->untrash_messages($res_ids);
		$this->set_api_content('success', l('untrash_messages', 'mailbox'));
	}
	
	/**
	 * Remove chain of messages
	 * @param integer $message_id message identifier
	 */
	public function delete_thread($message_id){
		$user_id = $this->session->userdata('user_id');
		
		$this->Mailbox_model->set_format_settings('use_format', false);
		$message = $this->Mailbox_model->get_message_by_id($message_id);
		$this->Mailbox_model->set_format_settings('use_format', true);
		
		if(!$message || $message['id_user'] != $user_id){
			$this->set_api_content('error', l('error_api_not_access', 'mailbox'));
		}else{
			if($message['id_thread']){
				$params = array('where'=>array('id_thread'=>$message['id_thread'], 'id_user'=>$user_id));
				$messages = $this->Mailbox_model->get_messages($params, null, null);
				if(!empty($messages)){
					$message_ids = array();
					foreach($messages as $message){
						$message_ids[] = $message['id'];
					}
					$this->Mailbox_model->move_messages_in_folder($message_ids, 'trash');
				}
			}
		
			$this->set_api_content('success', l('success_message_trash', 'mailbox'));
		}
	}
	
	/**
	 * Check availability of read message service
	 */
	public function available_read_message(){
		$return = array('available' => 0, 'content' => '');
		
		$user_id = $this->session->userdata('user_id');

		$this->Mailbox_model->set_format_settings('use_format', false);
		$message = $this->Mailbox_model->get_message_by_id($message_id);
		$this->Mailbox_model->set_format_settings('use_format', true);

		if(!$message['is_new'] || $message['id_from_user'] == $user_id){
			$return['available'] = 1;
		}else{
			$this->load->model('mailbox/models/Mailbox_service_model');
			$mailbox_service = $this->Mailbox_service_model->get_service_by_user_service($user_id, 'read_message_service');
			if($mailbox_service['service_data']['message_count']){
				$return['available'] = 1;
			}else{
				$return = $this->Mailbox_model->service_available_read_message($user_id);
				if($return["content_buy_block"] == true){
					$this->load->model('services/models/Services_users_model');
					$return['content'] = $this->Services_users_model->available_service_block($user_id, 'read_message_template');
				}
			}
		}
		
		$this->set_api_content('data', $return);
	}
	
	/**
	 * Use read message service
	 * @param integer $id_user user identifier
	 * @param integer $id_user_service
	 */
	public function activate_read_message($id_user, $id_user_service){
		$return = $this->Mailbox_model->service_activate_read_message($id_user, $id_user_service);
		$this->set_api_content('data', $return);
	}
	
	/**
	 * Check availability of send message service
	 */
	public function available_send_message(){
		$return = array('available' => 0, 'content' => '');
		
		$user_id = $this->session->userdata('user_id');

		$this->load->model('mailbox/models/Mailbox_service_model');
		$mailbox_service = $this->Mailbox_service_model->get_service_by_user_service($user_id, 'send_message_service');
		if($mailbox_service['service_data']['message_count']){
			$return['available'] = 1;
		}else{
			$return = $this->Mailbox_model->service_available_send_message($user_id);
			if($return["content_buy_block"] == true){
				$this->load->model('services/models/Services_users_model');
				$return["content"] = $this->Services_users_model->available_service_block($user_id, 'send_message_template');
			}
		}
		
		$this->set_api_content('data', $return);
	}
	
	/**
	 * Activate send message service
	 * @param integer $id_user user identifier
	 * @param integer $id_user_service user service identifier
	 */
	public function activate_send_message($id_user, $id_user_service){
		$return = $this->Mailbox_model->service_activate_send_message($id_user, $id_user_service);
		$this->set_api_content('data', $return);
	}
	
	/**
	 * Check availability of access mailbox service 
	 */
	public function available_access_mailbox(){
		$return = array('available' => 0, 'content' => '');
		
		if($this->session->userdata('auth_type') != 'user'){
			$return['display_login'] = 1;
		}else{
			$user_id = $this->session->userdata('user_id');

			$this->load->model('mailbox/models/Mailbox_service_model');
			$mailbox_service = $this->Mailbox_service_model->get_service_by_user_service($user_id, 'access_mailbox_service');
			if(!empty($mailbox_service)){
				$return['available'] = 1;
			}else{
				$return = $this->Mailbox_model->service_available_access_mailbox($user_id);
				if($return["content_buy_block"] == true){
					$this->load->model('services/models/Services_users_model');
					$return["content"] = $this->Services_users_model->available_service_block($user_id, 'access_mailbox_template');
				}
			}
		}
		
		$this->set_api_content('data', $return);
	}
}
