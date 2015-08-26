<?php
/**
* Mailbox user side controller
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Mikhail Chernov <mchernov@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: mchernov $
**/


Class Mailbox extends Controller {

	/**
	 * link to CodeIgniter object
	 * @var object
	 */
	
	/**
	 * Constructor
	 */
	function __construct() {
		parent::Controller();
		$this->load->model("Mailbox_model");
	}
	
	private function _view($folder, $page = 1){
		$user_id = $this->session->userdata('user_id');
		$this->template_lite->assign('user_id', $user_id);
	
		if(isset($_SESSION['mailbox']['keywords']))	$this->template_lite->assign('keywords', $_SESSION['mailbox']['keywords']);
		
		/// breadcrumbs
		$this->load->model('Menu_model');
		$this->Menu_model->breadcrumbs_set_parent('mailbox_item');
		$this->Menu_model->breadcrumbs_set_active(l($folder, 'mailbox'));
		$this->template_lite->assign('content', $this->_folder_content($folder, $page));
		$this->template_lite->view('index');
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
		$current_session = isset($_SESSION['mailbox']) ? $_SESSION['mailbox'] : array();
		
		$where = array();
		$where['where']['id_user'] = $this->session->userdata('user_id');
		$where['where']['folder'] = $folder;
		
		if(isset($current_session['keywords'])){
			$keywords = $current_session['keywords'];
		}else{
			$keywords = '';
		}
		
		if(!empty($keywords)){
			$fulltext = $this->Mailbox_model->return_fulltext_criteria($keywords.'*', 'BOOLEAN MODE');
			$where = array_merge($where, $fulltext);
		}
		
		$this->template_lite->assign('keywords', $keywords);
		
		$this->load->helper('sort_order');
		$items_on_page = $this->pg_module->get_module_config('mailbox', 'items_per_page');
		$mailbox_count = $this->Mailbox_model->get_messages_count($where);
		$page = get_exists_page_number($page, $mailbox_count, $items_on_page);
	
		$order_by = array('date_add' => 'DESC');
		$this->Mailbox_model->set_format_settings(array('get_sender'=>true, 'get_recipient'=>true));
		$messages = $this->Mailbox_model->get_messages($where, $page, $items_on_page, $order_by);
		$this->Mailbox_model->set_format_settings(array('get_sender'=>false, 'get_recipient'=>false));
		$this->template_lite->assign('messages', $messages);
		
		$this->load->helper("navigation");
		$url = site_url().'mailbox/'.$folder.'/';
		$page_data = get_user_pages_data($url, $mailbox_count, $items_on_page, $page, 'briefPage');
		$this->config->load('date_formats', TRUE);
		$page_data["date_format"] = $this->pg_date->get_format('date_literal', 'st');
		$page_data["date_time_format"] = $this->pg_date->get_format('date_time_literal', 'st');
		$page_data["time_format"] = $this->pg_date->get_format('time_literal', 'st');

		$this->template_lite->assign('page_data', $page_data);
		$this->template_lite->assign('folder', $folder);
		$this->template_lite->assign('page', $page);
		
		$user_id = $this->session->userdata('user_id');
		
		$inbox_new_message = $this->Mailbox_model->get_new_messages_count($user_id, 'inbox');
		$this->template_lite->assign('inbox_new_message', $inbox_new_message);
		
		$spam_new_message = $this->Mailbox_model->get_new_messages_count($user_id, 'spam');
		$this->template_lite->assign('spam_new_message', $spam_new_message);
		
		$trash_new_message = $this->Mailbox_model->get_new_messages_count($user_id, 'trash');
		$this->template_lite->assign('trash_new_message', $trash_new_message);
		
		return $this->template_lite->fetch('mailbox_content', 'user', 'mailbox');
	}
	
	/**
	 * Write message action
	 * @param integer $message_id message identifier
	 */
	public function edit($message_id){
		$user_id = $this->session->userdata('user_id');
		$this->Mailbox_model->set_format_settings('use_format', false);
		$this->Mailbox_model->set_format_settings('get_attaches', true);
		$message = $this->Mailbox_model->get_message_by_id($message_id);
		$this->Mailbox_model->set_format_settings('get_attaches', false);
		$this->Mailbox_model->set_format_settings('use_format', true);
			
		if(!$message || $message['id_user'] != $user_id) show_404();
		
		$this->template_lite->assign('message', $message);

		if($message['folder'] != 'drafts') show_404();
	
		$page_data = array();

		$this->config->load('date_formats', TRUE);
		$page_data["date_format"] = $this->pg_date->get_format('date_literal', 'st');
		$page_data["date_time_format"] = $this->pg_date->get_format('date_time_literal', 'st');
		$page_data["time_format"] = $this->pg_date->get_format('time_literal', 'st');
		
		$this->template_lite->assign('page_data', $page_data);
		
		$attach_settings = $this->Mailbox_model->get_attach_settings();
		$this->template_lite->assign('attach_settings', $attach_settings);
		
		$inbox_new_message = $this->Mailbox_model->get_new_messages_count($user_id, 'inbox');
		$this->template_lite->assign('inbox_new_message', $inbox_new_message);
		
		$spam_new_message = $this->Mailbox_model->get_new_messages_count($user_id, 'spam');
		$this->template_lite->assign('spam_new_message', $spam_new_message);
		
		$trash_new_message = $this->Mailbox_model->get_new_messages_count($user_id, 'trash');
		$this->template_lite->assign('trash_new_message', $trash_new_message);
		
		$this->template_lite->view('edit');
	}
	
	/**
	 * Read message action
	 * @param integer $message_id message identifier
	 */
	public function view($message_id){
		$this->load->model('services/models/Services_users_model');
		
		$user_id = $this->session->userdata('user_id');
		
		$result = $this->Mailbox_model->service_available_read_message($user_id);
		if(!$result['available']) $this->template_lite->assign('read_disabled', 1);
		
		$this->Mailbox_model->set_format_settings('get_attaches', true);
		$message = $this->Mailbox_model->get_message_by_id($message_id);
		$this->Mailbox_model->set_format_settings('get_attaches', false);
		
		if(!$message || $message['id_user'] != $user_id) show_404();
		
		$this->template_lite->assign('message', $message);
		
		if($message['is_new'] && $message['id_from_user'] != $user_id){
			$this->load->model('mailbox/models/Mailbox_service_model');
			$mailbox_service = $this->Mailbox_service_model->get_service_by_user_service($user_id, 'read_message_service');
			if($mailbox_service['service_data']['message_count']){
				$mailbox_service['service_data']['message_count']--;
				$save_data = array('service_data'=>$mailbox_service['service_data']);
				$validate_data = $this->Mailbox_service_model->validate_service($mailbox_service['id'], $save_data);
				$this->Mailbox_service_model->save_service($mailbox_service['id'], $validate_data['data']);
			}elseif(!$result['available']){
				redirect(site_url().'users/account');
			}
		}
		
		if($message['id_thread']){
			$items_on_page = $this->pg_module->get_module_config('mailbox', 'items_per_page');
			$params = array(
				'where'=>array(
					'id_thread' => $message['id_thread'], 
					'id !=' => $message['id'], 
					'id_user' => $message['id_user'],
					//'folder' => $message['folder'],
					'date_add >' => date('Y-m-d H:i:s', strtotime($message['date_add']))
				),
			);
			$thread_top_count = $this->Mailbox_model->get_messages_count($params);
			if($thread_top_count){
				$thread_top = $this->Mailbox_model->get_messages($params, 1, $items_on_page, array('date_add'=>'DESC'));
				$this->template_lite->assign('thread_top', $thread_top);
			}
			$this->template_lite->assign('thread_top_count', $thread_top_count);
			
			$params = array(
				'where'=>array(
					'id_thread' => $message['id_thread'], 
					'id !=' => $message['id'], 
					'id_user' => $message['id_user'],
					//'folder' => $message['folder'],
					'date_add <' => date('Y-m-d H:i:s', strtotime($message['date_add']))
				),
			);
			$thread_bottom_count = $this->Mailbox_model->get_messages_count($params);
			if($thread_bottom_count){
				$thread_bottom = $this->Mailbox_model->get_messages($params, 1, $items_on_page, array('date_add'=>'DESC'));
				$this->template_lite->assign('thread_bottom', $thread_bottom);
			}
			$this->template_lite->assign('thread_bottom_count', $thread_bottom_count);
		}
		
		if($message['is_new']){
			$save_data = array('is_new'=>0, 'date_read'=>date('Y-m-s H:i:s'));
			$this->Mailbox_model->save_message($message_id, $save_data);
		}
	
		$this->template_lite->assign('reply', array());
	
		$page_data = array();
		
		$this->config->load('date_formats', TRUE);
		$page_data["date_format"] = $this->pg_date->get_format('date_literal', 'st');
		$page_data["date_time_format"] = $this->pg_date->get_format('date_time_literal', 'st');
		$page_data["time_format"] = $this->pg_date->get_format('time_literal', 'st');
		
		$this->template_lite->assign('page_data', $page_data);
		
		$attach_settings = $this->Mailbox_model->get_attach_settings();
		$this->template_lite->assign('attach_settings', $attach_settings);
		
		$inbox_new_message = $this->Mailbox_model->get_new_messages_count($user_id, 'inbox');
		$this->template_lite->assign('inbox_new_message', $inbox_new_message);
		
		$spam_new_message = $this->Mailbox_model->get_new_messages_count($user_id, 'spam');
		$this->template_lite->assign('spam_new_message', $spam_new_message);
		
		$trash_new_message = $this->Mailbox_model->get_new_messages_count($user_id, 'trash');
		$this->template_lite->assign('trash_new_message', $trash_new_message);
	
		$this->template_lite->view('view');
	}
	
	public function ajax_delete_messages($folder = 'inbox', $page = 1){
		$return = array('status' => 0, 'message' => '', 'content' => '');
		
		$res_ids = $this->Mailbox_model->validate_messages_ids($this->input->post('ids', true));
		if(!empty($res_ids)){
			$user_id = $this->session->userdata('user_id');
		
			$is_user_messages = $this->Mailbox_model->is_user_messages($res_ids, $user_id);
			if(!$is_user_messages){
				$return['message'] = l('error_not_access', 'mailbox');
			}else{
				$this->Mailbox_model->move_messages_in_folder($res_ids, 'trash');
				$return['status'] = 1;
				$return['message'] = l('messages_in_trash', 'mailbox');
			}
		}else{
			$return['message'] = l('error_api_empty_items', 'mailbox');
		}
		
		$return['content'] = $this->_folder_content($folder, $page);
		exit(json_encode($return));
	}
	
	public function ajax_delete_message($message_id){
		$return = array('status' => 0, 'message' => '');
		
		$user_id = $this->session->userdata('user_id');
		
		$is_user_messages = $this->Mailbox_model->is_user_messages($message_id, $user_id);
		if(!$is_user_messages){
			$return['message'] = l('error_not_access', 'mailbox');
		}else{
			$this->Mailbox_model->move_messages_in_folder(array($message_id), 'trash');
			$return['status'] = 1;
			$return['message'] = l('success_message_trash', 'mailbox');
		}
		exit(json_encode($return));
	}
	
	public function ajax_delete_forever($folder = 'drafts', $page = 1){
		$return = array('status' => 0, 'message' => '', 'content' => '');
		
		$res_ids = $this->Mailbox_model->validate_messages_ids($this->input->post('ids', true));
		if(!empty($res_ids)){
			$user_id = $this->session->userdata('user_id');
		
			$is_user_messages = $this->Mailbox_model->is_user_messages($res_ids, $user_id);
			if(!$is_user_messages){
				$return['message'] = l('error_not_access', 'mailbox');
			}else{	
				$this->Mailbox_model->delete_messages($res_ids);
				$return['status'] = 1;
				$return['message'] = l('delete_forever', 'mailbox');
			}
		}else{
			$return['message'] = l('error_api_empty_items', 'mailbox');
		}
		
		$return['content'] = $this->_folder_content($folder, $page);
		exit(json_encode($return));
	}
	
	public function ajax_mark_spam_messages($folder = 'inbox', $page = 1){
		$return = array('status' => 0, 'message' => '', 'content' => '');
		$res_ids = $this->Mailbox_model->validate_messages_ids($this->input->post('ids', true));
		
		if(!empty($res_ids)){
			$user_id = $this->session->userdata('user_id');
			
			$is_user_messages = $this->Mailbox_model->is_user_messages($res_ids, $user_id);
			if(!$is_user_messages){
				$return['message'] = l('error_not_access', 'mailbox');
			}else{
				$this->Mailbox_model->move_messages_in_folder($res_ids, 'spam');
				$return['status'] = 1;
				$return['message'] = l('messages_in_spam', 'mailbox');
			}
		}else{
			$return['message'] = l('error_not_access', 'mailbox');
		}
		$return['content'] = $this->_folder_content($folder, $page);
		exit(json_encode($return));
	}
	
	public function ajax_mark_spam_message($message_id){
		$return = array('status' => 0, 'message' => '');
		
		$user_id = $this->session->userdata('user_id');
		
		$is_user_messages = $this->Mailbox_model->is_user_messages($message_id, $user_id);
		if(!$is_user_messages){
			$return['message'] = l('error_not_access', 'mailbox');
		}else{
			$this->Mailbox_model->move_messages_in_folder(array($message_id), 'spam');
			$return['status'] = 1;
			$return['message'] = l('success_message_mark_spam', 'mailbox');
		}
		exit(json_encode($return));
	}
	
	public function ajax_unmark_spam_messages($folder = 'spam', $page = 1){
		$return = array('status' => 0, 'message' => '', 'content' => '');
		$res_ids = $this->Mailbox_model->validate_messages_ids($this->input->post('ids', true));
		
		if(!empty($res_ids)){
			$user_id = $this->session->userdata('user_id');
			$is_user_messages = $this->Mailbox_model->is_user_messages($res_ids, $user_id);
			if(!$is_user_messages){
				$return['message'] = l('error_not_access', 'mailbox');
			}else{
				$this->Mailbox_model->unmark_spam_messages($res_ids);
				$return['status'] = 1;
				$return['message'] = l('success_messages_unmark_spam', 'mailbox');
			}
		}else{
			$this->set_api_content('error', l('error_api_empty_items', 'mailbox'));
		}
		
		$return['content'] = $this->_folder_content($folder, $page);
		exit(json_encode($return));
	}
	
	public function ajax_unmark_spam_message($message_id){
		$return = array('status' => 0, 'message' => '');
		
		$user_id = $this->session->userdata('user_id');
		
		$is_user_messages = $this->Mailbox_model->is_user_messages($message_id, $user_id);
		if(!$is_user_messages){
			$return['message'] = l('error_not_access', 'mailbox');
		}else{
			$this->Mailbox_model->unmark_spam_messages(array($message_id));
			$return['status'] = 1;
			$return['message'] = l('success_message_unmark_spam', 'mailbox');
		}
		exit(json_encode($return));
	}
	
	public function ajax_untrash_messages($folder = 'trash', $page = 1){
		$return = array('status' => 0, 'message' => '', 'content' => '');
		$res_ids = $this->Mailbox_model->validate_messages_ids($this->input->post('ids', true));
		
		if(!empty($res_ids)){
			$user_id = $this->session->userdata('user_id');
			$is_user_messages = $this->Mailbox_model->is_user_messages($res_ids, $user_id);
			if(!$is_user_messages){
				$return['message'] = l('error_not_access', 'mailbox');
			}else{
				$this->Mailbox_model->untrash_messages($res_ids);
				$return['status'] = 1;
				$return['message'] = l('untrash_messages', 'mailbox');
			}
		}else{
			$return['message'] = l('error_not_access', 'mailbox');
		}
		
		$return['content'] = $this->_folder_content($folder, $page);
		exit(json_encode($return));
	}
	
	public function ajax_untrash_message($message_id){
		$return = array('status' => 0, 'message' => '');
		
		$user_id = $this->session->userdata('user_id');
		
		$is_user_messages = $this->Mailbox_model->is_user_messages($message_id, $user_id);
		if(!$is_user_messages){
			$return['message'] = l('error_not_access', 'mailbox');
		}else{
			$this->Mailbox_model->untrash_messages(array($message_id));
			$return['status'] = 1;
			$return['message'] = l('success_message_untrash', 'mailbox');
		}
		
		exit(json_encode($return));
	}
	
	/**
	 * Return thread pages
	 * @param integer $message_id message identifier
	 * @param string $direction direction
	 * @param integer $page page of results
	 */
	public function ajax_thread($message_id, $direction, $page=1){
		$return = array('content'=> '', 'count'=>''); 
		
		$user_id = $this->session->userdata('user_id');
		
		$result = $this->Mailbox_model->service_available_read_message($user_id);
		if(!$result['available']) $this->template_lite->assign('read_disabled', 1);
		
		$message = $this->Mailbox_model->get_message_by_id($message_id);
		if(!$message || $message['id_user'] != $user_id) show_404();
		
		if($message['id_thread']){
			$items_on_page = 1; //$this->pg_module->get_module_config('mailbox', 'items_per_page');
			$params = array(
				'where'=>array(
					'id_thread' => $message['id_thread'], 
					'id !=' => $message['id'], 
					'id_user' => $message['id_user'],
					//'folder' => $message['folder'],
					'date_add '.($direction == 'next' ? '>' : '<')=>date('Y-m-d H:i:s', strtotime($message['date_add']))
				),
			);
			$thread_count = $this->Mailbox_model->get_messages_count($params);
			if($thread_count){
				$thread = $this->Mailbox_model->get_messages($params, $page+1, $items_on_page, array('date_add'=>'DESC'));
				$this->template_lite->assign('thread', $thread);
			}
			
			$page_data = array();
		
			$this->config->load('date_formats', TRUE);
			$page_data["date_format"] = $this->pg_date->get_format('date_literal', 'st');
			$page_data["date_time_format"] = $this->pg_date->get_format('date_time_literal', 'st');
			$page_data["time_format"] = $this->pg_date->get_format('st_format_time_literal', 'st');
		
			$this->template_lite->assign('page_data', $page_data);
			
			$return['content'] = $this->template_lite->fetch('ajax_thread', 'user', 'mailbox');
			$thread_count = max($thread_count-($page+1)*$items_on_page, 0);
			if($thread_count){
				$return['count'] = str_replace('[messages]', $thread_count, l('link_'.$direction.'_messages', 'mailbox'));
			}
		}
		
		echo json_encode($return);
	}
	
	/**
	 * Write message action
	 */
	public function write($message_id=0){
		$user_id = $this->session->userdata('user_id');
		
		$message = array();
		
		if($message_id){
			$this->Mailbox_model->set_format_settings('get_message', false);
			$message = $this->Mailbox_model->get_message_by_id($message_id);
			$this->Mailbox_model->set_format_settings('get_message', true);
			
			$is_user_messages = $this->Mailbox_model->is_user_messages($message_id, $user_id);
			if(!$is_user_messages) show_404();
		}		
		$this->template_lite->assign('message', $message);
		
		$this->template_lite->assign('write_message', 1);
		
		$attach_settings = $this->Mailbox_model->get_attach_settings();
		$this->template_lite->assign('attach_settings', $attach_settings);
		
		$this->template_lite->assign('rand', rand(100000, 999999));
		
		$this->Menu_model->breadcrumbs_set_parent('mailbox_item');
		$this->Menu_model->breadcrumbs_set_active(l('write_message', 'mailbox'));
		
		$inbox_new_message = $this->Mailbox_model->get_new_messages_count($user_id, 'inbox');
		$this->template_lite->assign('inbox_new_message', $inbox_new_message);
		
		$spam_new_message = $this->Mailbox_model->get_new_messages_count($user_id, 'spam');
		$this->template_lite->assign('spam_new_message', $spam_new_message);
		
		$trash_new_message = $this->Mailbox_model->get_new_messages_count($user_id, 'trash');
		$this->template_lite->assign('trash_new_message', $trash_new_message);
		
		$this->template_lite->view('write');
	}
	
	/**
	 * Upload attachement
	 * @param integer $message_id message identifier
	 */
	public function upload_attach($message_id){
		$return = array('errors'=>'', 'success'=>'', 'data'=>'');

		$user_id = $this->session->userdata('user_id');
		
		$is_user_messages = $this->Mailbox_model->is_user_messages($message_id, $user_id);
		if(!$is_user_messages){
			$return['errors'] = l('error_not_access', 'mailbox');
		}else{
			$validate_data = $this->Mailbox_model->validate_attach(null, 'attach', $message_id);
			if(!empty($validate_data['errors'])){
				$return['errors'] = implode('<br>', $validate_data['errors']);
			}else{
				$result = $this->Mailbox_model->upload_attach(null, 'attach', $message_id);
				$return['id'] = $result['id'];
				$return['link'] = $result['format']['file_url'];
				$return['name'] = $result['filename'];
				$return['size'] = $result['filesize'];
			
				$i = 1;
				while($return['size'] > 1000){
					$return['size'] /= 1024;
					$i++;
				}
				$return['size'] = ceil($return['size']).' '.l('text_filesize_'.$i, 'mailbox');;
				$return['success'] = l('success_attach_uploaded', 'mailbox');
			}
		}
		
		echo json_encode($return);
	}
	
	/**
	 * Remove attachement
	 * @param integer $attach_id attachement identifier
	 * @param integer $message_id message identifier
	 */
	public function ajax_delete_attach($attach_id, $message_id){
		$return = array('errors'=>'', 'success'=>'', 'data'=>'');
		
		$user_id = $this->session->userdata('user_id');
		
		$is_user_messages = $this->Mailbox_model->is_user_messages($message_id, $user_id);
		if(!$is_user_messages){
			$return['error'] = l('error_not_access', 'mailbox');
		}else{
			$this->Mailbox_model->delete_attach($attach_id, $message_id);
			$return['success'] = l('success_attach_deleted', 'mailbox');
		}
		
		echo json_encode($return);
	}
	
	/**
	 * Save draft by ajax
	 * @param integer $message_id
	 */
	public function ajax_save_draft($message_id=null){
		$return = array('errors'=>'', 'success'=>'', 'message_id'=>0, 'status'=>'');

		$user_id = $this->session->userdata('user_id');

		if($message_id){
			$is_user_messages = $this->Mailbox_model->is_user_messages($message_id, $user_id);
			if(!$is_user_messages){
				$return['status'] = l('text_message_no_saved', 'mailbox');
				$return['errors'] = l('error_not_access', 'mailbox');
				echo json_encode($return);
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
			$return['status'] = l('text_message_no_saved', 'mailbox');
			$return['errors'] = implode('<br>', $validate_data['errors']);
		}else{			
			$return['message_id'] = $this->Mailbox_model->save_message($message_id, $validate_data['data']);
			$return['status'] = l('text_message_saved', 'mailbox');
		}
		
		echo json_encode($return);
	}
	
	/**
	 * Save reply by ajax
	 * @param integer $message_id message identifier
	 * @param integer $reply_id reply identifier
	 */
	public function ajax_save_reply($message_id, $reply_id=null){
		$return = array('errors'=>'', 'success'=>'', 'message_id'=>0, 'status'=>'');

		$user_id = $this->session->userdata('user_id');
	
		$post_data = array();
		$post_data['message'] = $this->input->post('message', true);
	
		if($reply_id){
			$is_user_messages = $this->Mailbox_model->is_user_messages($reply_id, $user_id);
			if(!$is_user_messages){
				$return['status'] = l('text_message_no_saved', 'mailbox');
				$return['errors'] = l('error_not_access', 'mailbox');
				return;
			}
		}else{
			$reply_id = null;
			
			$this->Mailbox_model->set_format_settings('use_format', false);
			$message = $this->Mailbox_model->get_message_by_id($message_id);
			$this->Mailbox_model->set_format_settings('use_format', true);

			if(!$message || $message['id_user'] != $user_id){
				$return['status'] = l('text_message_no_saved', 'mailbox');
				$return['errors'] = l('error_not_access', 'mailbox');
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
			$return['status'] = l('text_message_no_saved', 'mailbox');
			$return['errors'] = implode('<br>', $validate_data['errors']);
		}else{
			$return['reply_id'] = $this->Mailbox_model->save_message($reply_id, $validate_data['data']);
			$return['status'] = l('text_message_saved', 'mailbox');
		}
		
		echo json_encode($return);
	}

	/**
	 * Send message by ajax
	 */
	public function ajax_send_message($message_id){
		$return = array('error'=>'', 'success'=>'');
		
		$user_id = $this->session->userdata('user_id');
		
		$is_user_messages = $this->Mailbox_model->is_user_messages($message_id, $user_id);
		if(!$is_user_messages){
			$return['error'] = l('error_not_access', 'mailbox');
			echo json_encode($return);
			return;
		}
		
		$validate_data = $this->Mailbox_model->validate_send($message_id);
		if(!empty($validate_data['errors'])){
			$return['error'] = implode('<br>', $validate_data['errors']);
		}else{
			$is_send = $this->Mailbox_model->send_message($message_id);
			if($is_send){
				$return['success'] = l('success_message_send', 'mailbox');
			}else{
				$return['error'] = l('error_service_unavailable', 'mailbox');
			}
		}
		
		echo json_encode($return);
	}
	
	/**
	 * Reply message action by ajax
	 */
	public function ajax_reply_message($message_id){
		$return = array('error'=>'', 'success'=>'');
		
		$user_id = $this->session->userdata('user_id');
		
		$is_user_messages = $this->Mailbox_model->is_user_messages($message_id, $user_id);
		if(!$is_user_messages){
			$return['error'] = l('error_not_access', 'mailbox');
		}else{
			$validate_data = $this->Mailbox_model->validate_send($message_id);
			if(!empty($validate_data['errors'])){
				$return['error'] = implode('<br>', $validate_data['errors']);
			}else{
				$is_send = $this->Mailbox_model->send_message($message_id);
				if($is_send){
					$return['success'] = l('success_message_reply', 'mailbox');
				}else{
					$return['error'] = l('error_service_unavailable', 'mailbox');
				}
			}
		}
		
		echo json_encode($return);
	}
	
	/**
	 * Check availability of read message service
	 * @param integer $message_id message identifier
	 */
	public function ajax_available_read_message($message_id){
		$return = array('available' => 0, 'content' => '', 'display_login' => 0);
		
		$user_id = $this->session->userdata('user_id');

		$this->Mailbox_model->set_format_settings('use_format', false);
		$message = $this->Mailbox_model->get_message_by_id($message_id);
		$this->Mailbox_model->set_format_settings('use_format', true);

		if(!$message['is_new'] || $message['id_from_user'] == $user_id){
			$return['available'] = 1;
		}else{
			$this->load->model('mailbox/models/Mailbox_service_model');
			$mailbox_service = $this->Mailbox_service_model->get_service_by_user_service($user_id, 'read_message_service');
			if(!empty($mailbox_service['service_data']) && $mailbox_service['service_data']['message_count']){
				$return['available'] = 1;
			}else{
				$return = $this->Mailbox_model->service_available_read_message($user_id);
				if($return["content_buy_block"] == true){
					$this->load->model('services/models/Services_users_model');
					$return["content"] = $this->Services_users_model->available_service_block($user_id, 'read_message_template');
				}
			}
		}
		
		exit(json_encode($return));
	}
	
	/**
	 * Use read message service
	 * @param integer $id_user user identifier
	 * @param integer $id_user_service
	 */
	public function ajax_activate_read_message($id_user_service){
		$id_user = $this->session->userdata('user_id');
		$return = $this->Mailbox_model->service_activate_read_message($id_user, $id_user_service);
		exit(json_encode($return));
	}
	
	/**
	 * Check availability of send message service
	 * @param integer $user_id user identifier
	 */
	public function ajax_available_send_message(){
		$return = array('available' => 0, 'content' => '', 'display_login' => 0);
		
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
		
		exit(json_encode($return));
	}
	
	/**
	 * Activate send message service
	 * @param integer $id_user user identifier
	 * @param integer $id_user_service user service identifier
	 */
	public function ajax_activate_send_message($id_user_service){
		$id_user = $this->session->userdata('user_id');
		$return = $this->Mailbox_model->service_activate_send_message($id_user, $id_user_service);
		exit(json_encode($return));
	}
	
	/**
	 * Show write message form
	 * @param integer $user_id contact identifier
	 * @param string $type form type
	 */
	public function ajax_show_form($user_id, $type='full'){
		$this->template_lite->assign('user_id', $user_id);
		$this->template_lite->assign('type', $type);
		echo $this->template_lite->fetch('send_message', 'user', 'mailbox');
	}
	
	/**
	 * Check availability of access mailbox service 
	 * @param integer $user_id user identifier
	 */
	public function ajax_available_access_mailbox(){
		$return = array('available' => 0, 'content' => '', 'display_login' => 0);
		
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
		
		if($return['available']){
			$return['available'] = 0;
			$this->load->model('mailbox/models/Mailbox_service_model');
			$mailbox_service = $this->Mailbox_service_model->get_service_by_user_service($user_id, 'send_message_service');
			if(!empty($mailbox_service['service_data']) && $mailbox_service['service_data']['message_count']){
				$return['available'] = 1;
			}else{
				$return = $this->Mailbox_model->service_available_send_message($user_id);
				if($return["content_buy_block"] == true){
					$this->load->model('services/models/Services_users_model');
					$return["content"] = $this->Services_users_model->available_service_block($user_id, 'send_message_template');
				}
			}
		}
		
		exit(json_encode($return));
	}	
	
	/**
	 * Remove chain of messages
	 * @param integer $message_id message identifier
	 */
	public function ajax_delete_thread($message_id){
		$return = array('error'=>'', 'success'=>'');
		
		$user_id = $this->session->userdata('user_id');
		
		$this->Mailbox_model->set_format_settings('use_format', false);
		$message = $this->Mailbox_model->get_message_by_id($message_id);
		$this->Mailbox_model->set_format_settings('use_format', true);
		
		if(!$message || $message['id_user'] != $user_id){
			$return['success'] = l('error_api_not_message', 'mailbox');
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
			$return['success'] = l('success_message_trash', 'mailbox');
		}
		
		exit(json_encode($return));
	}
	
	/**
	 * Search by keywords
	 * @param string $folder folder name
	 * @param integer $page page of results
	 * @param string $keywords keywords
	 */
	public function ajax_search_messages($folder, $page, $keywords=''){
		$return = array('content'=>'');
		
		$current_session = isset($_SESSION['mailbox']) ? $_SESSION['mailbox'] : array();
		
		if(!empty($keywords)){
			$current_session['keywords'] = $keywords;
		}elseif(isset($current_session['keywords'])){
			unset($current_session['keywords']);
		}
		
		$this->template_lite->assign('keywords', $keywords);
	
		if(!empty($current_session)){
			$_SESSION['mailbox'] = $current_session;
		}elseif(isset($_SESSION['mailbox'])){
			unset($_SESSION['mailbox']);
		}
		
		$return['content'] = $this->_folder_content($folder, $page);
		
		exit(json_encode($return));
	}
}
