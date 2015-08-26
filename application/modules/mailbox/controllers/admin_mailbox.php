<?php
/**
* Mailbox admin side controller
* 
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/
class Admin_Mailbox extends Controller{
	/**
	 * Constructor
	 *
	 * @return Admin_Mailbox
	 */
	public function __construct(){
		parent::Controller();
		$this->load->model('Menu_model');
		$this->Menu_model->set_menu_active_item('admin_menu', 'mailbox-items');
	}
	
	/**
	 * Render contacts list
	 * @param string $filter
	 * @param string $order
	 * @param string $order_direction
	 * @param integer $page
	 */
	public function index($folder='inbox', $order=null, $order_direction=null, $page=null){
		$this->load->model('Mailbox_model');
		
		$current_settings = isset($_SESSION['mailbox_list']) ? $_SESSION['mailbox_list'] : array();
		if(!isset($current_settings['order'])) $current_settings['order'] = 'date_add';
		if(!isset($current_settings['order_direction'])) $current_settings['order_direction'] = 'DESC';
		if(!isset($current_settings['page'])) $current_settings['page'] = 1;

		if(!empty($order)) $current_settings['order'] = $order;
		$order = $current_settings['order'];
		$this->template_lite->assign('order', $order);
		
		if(in_array($order_direction, array('ASC', 'DESC'))) $current_settings["order_direction"] = $order_direction;
		$order_direction = $current_settings['order_direction'];
		$this->template_lite->assign('order_direction', $order_direction);
		
		$page = intval($page);
		if(!empty($page)) $current_settings['page'] = $page;	
		$page = $current_settings['page'];
		
		if($this->input->post("filter-submit")){
			$_SESSION['mailbox_filters'] = array();
			
			$post_data['id_user'] = intval($this->input->post('id_user', true));
			if($post_data['id_user']) $_SESSION['mailbox_filters']['id_user'] = $post_data['id_user'];
		}

		if($this->input->post("filter-reset")){
			$_SESSION['mailbox_filters'] = array();
		}
		
		$id_user = isset($_SESSION['mailbox_filters']['id_user']) ? $_SESSION['mailbox_filters']['id_user'] : 0;
		$this->template_lite->assign('id_user', $id_user);
		
		$this->template_lite->assign('folder', $folder);
		
		$items_on_page = $this->pg_module->get_module_config('start', 'admin_items_per_page');
	
		$where = array();
		$where['where']['id_user'] = $id_user;
		$where['where']['folder'] = $folder;
		$messages_count = $this->Mailbox_model->get_messages_count($where);
			
		$this->load->helper('sort_order');
		$page = get_exists_page_number($page, $messages_count, $items_on_page);
		$current_settings['page'] = $page;

		$_SESSION['mailbox_list'] = $current_settings;

		$sort_links = array(
			'date_add' => site_url().'admin/mailbox/index/'.$folder.'/date_add/'.(($order != 'date_add' xor $order_direction == 'DESC') ? 'ASC' : 'DESC'),
			'sender' => site_url().'admin/mailbox/index/'.$folder.'/sender/'.(($order != 'sender' xor $order_direction == 'DESC') ? 'ASC' : 'DESC'),
			'recipient' => site_url().'admin/mailbox/index/'.$folder.'/status/'.(($order != 'status' xor $order_direction == 'DESC') ? 'ASC' : 'DESC'),
		);		
		$this->template_lite->assign('sort_links', $sort_links);

		if($messages_count > 0){
			$messages = $this->Mailbox_model->get_messages($where, $page, $items_on_page, array($order => $order_direction));
			$this->template_lite->assign('messages', $messages);
		}
		$this->load->helper('navigation');
		$this->config->load('date_formats', TRUE);
		$url = site_url().'admin/mailbox/index/'.$folder.'/'.$order.'/'.$order_direction.'/';
		$page_data = get_admin_pages_data($url, $messages_count, $items_on_page, $page, 'briefPage');
		$page_data['date_format'] = $this->config->item('st_format_date_time_literal', 'date_formats');
		$this->template_lite->assign('page_data', $page_data);
		
		$this->system_messages->set_data('header', l('admin_header_messages', 'mailbox'));		
		$this->Menu_model->set_menu_active_item('admin_mailbox_menu', 'mailbox_'.$folder.'_item');
		$this->template_lite->view('list');
	}
	
	/**
	 * Render view message action
	 * @param integer $message_id
	 */
	public function view($message_id){
		$this->load->model('Mailbox_model');
		
		$this->Mailbox_model->set_format_settings('get_attaches', true);
		$message = $this->Mailbox_model->get_message_by_id($message_id);
		$this->Mailbox_model->set_format_settings('get_attaches', false);
		$this->template_lite->assign('message', $message);
		
		$this->config->load('date_formats', TRUE);
		$date_format = $this->config->item('st_format_date_time_literal', 'date_formats');
		$this->template_lite->assign('date_format', $date_format);
		
		$this->system_messages->set_data('header', l('admin_header_messages', 'mailbox'));		
		$this->template_lite->view('view');
	}
	
	/**
	 * Remove message action
	 * @param integer $message_id
	 */
	public function delete($message_ids, $folder){
		if(!$message_ids) $ids = $this->input->post('ids', true);
		if(!empty($message_ids)){
			$this->load->model('Mailbox_model');
			foreach((array)$message_ids as $message_id) $this->Mailbox_model->delete_message($message_id);
			$this->system_messages->add_message('success', l('success_message_deleted', 'mailbox'));
		}
		$url = site_url().'admin/mailbox/index/'.$folder;
		redirect($url);
	}
}
