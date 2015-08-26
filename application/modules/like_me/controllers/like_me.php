<?php

use Pg\Modules\Like_me\Models\Like_me_model;

/**
* Like me controller
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Nikita Savanaev <nsavanaev@pilotgroup.net>
**/

class Like_me extends Controller 
{
	
   /**
	 * Constructor
	 * @return Like_me
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('like_me/models/Like_me_model');	
		$this->load->model('Menu_model');
	}
	
    /**
     * Index
     * @param string $action  
     * @return void
     */
	public function index($action = 'play_global')
	{
		$data = $this->Like_me_model->getSettings();
		$lang_id = $this->pg_language->current_lang_id;		
		$this->Menu_model->breadcrumbs_set_active(l('header_main', 'like_me'));
		$this->load->library('PG_Output');
		$this->pg_output->setOutputData('action', $action);
		$this->pg_output->setOutputData('data', $data);
		$this->pg_output->setOutputData('lang_id', $lang_id);
		$this->pg_output->setOutputHeader(l('header_main', 'like_me'));
		$this->pg_output->setOutputContent('template', 'like_me');
	}
	
	/**
	 *  Remove matches
	 *  @param integer $profile_id
	 *  @return void
	 */
	public function remove($profile_id = null)
	{	
		if (!is_null($profile_id)) {
			$user_id = intval($this->session->userdata('user_id'));
			$this->Like_me_model->removeMatches($user_id, $profile_id);
		}
		$this->load->library('PG_Output');
		$this->pg_output->setOutputContent('redirect', site_url().'like_me/index/matches');
	}
	
	/**
	 *  Selected actions
	 *  @param array $action
	 *  @return array
	 */
	private function _selAction($action = array(), $page = 1)
	{
		switch($action['type']){
			case 'play_local':
				$data = $this->_userListLocal($action);
				break;
			case 'matches':
				$data = $this->_userListMatches($page);
				break;
			default:
				$data = $this->_userListGlobal($action);
				break;
		}
		return $data;
	}
    
    /**
     * Play global list
     * @param array $data
     * @return array
     */
	private function _userListGlobal($data = array())
	{
		$params = $this->_getParams($data);
		$order = array('id' => 'ASC');
		$user_list = $this->Users_model->get_users_list(1, 1, $order, $params);
		return $user_list[0];
	}
   
   /**
     * Play matches list
     * @param array $data
     * @return array
     */
   private function _userListMatches($page = 1)
   {
		$user_id = intval($this->session->userdata("user_id"));
		$count_data = $this->Like_me_model->getCountMatchesList($user_id);
		$items_on_page = $this->pg_module->get_module_config('like_me', 'matches_per_page');
		$this->load->helper('sort_order');
		$exists_page = get_exists_page_number($page, $count_data, $items_on_page);
		$next_page = get_exists_page_number($exists_page + 1, $count_data, $items_on_page);
		if ($next_page > $exists_page) {
			$user_list = array('have_more' => 1);
		}
		$user_list['content'] = $this->Like_me_model->getMatchesList($page, $items_on_page, $user_id);
		return $user_list;
   }
   
   /**
     * Play local list
     * @param array $data
     * @return array
     */
   private function _userListLocal($data = array())
   {	
		$params = $this->_getParams($data);
		$area = $this->pg_module->get_module_config('like_me', 'play_local_area');
		$user_id = intval($this->session->userdata('user_id'));
		$this->load->model('Users_model');
		$user_data = $this->Users_model->get_user_by_id($user_id);
		$params['where'][$area] = $user_data[$area];
		$order = array('id' => 'ASC');
		$user_list = $this->Users_model->get_users_list(1, 1, $order, $params);
		return $user_list[0];
   }
   
   /**
     * Querying
     * @param array $data  
     * @return array
     */
   private function _getParams($data = array())
   {	
		$profile_ids = array();
        
		if (isset($data['profile_id']) && !empty($data['profile_id']))
			$this->_setCookieLikeMe($data['profile_id']);
        
		if (empty($data['reload']))
			$profile_ids = $this->_getCookieProfile();
        
		$user_id = intval($this->session->userdata('user_id'));
		
        $this->load->model('users/models/Users_model');
        $this->load->model('users/models/Users_perfect_match_model');
        $search_data = $this->Users_perfect_match_model->get_user_perfect_match_params($user_id);
        $params = $this->Users_model->get_common_criteria($search_data['full_criteria']);
        
		if (!empty($data['profile_id'])) {
			$liked = $this->session->userdata('like_me_selected');
			$liked = array_unique(array_merge($liked, $profile_ids));				
			if (!empty($liked)) {
				$params['where_sql'][] = "id NOT IN (" . implode(', ', $liked) . ")";
			}
			$params['where']['id >'] = intval($data['profile_id']);
			$params['where']['id !='] = intval($user_id);
		} else {
			$selected = array($user_id);
			$liked = $this->Like_me_model->getLikedProfileIds($user_id);
			$liked = array_merge($liked, $profile_ids);
			if (!empty($liked)) {
				$selected = array_unique(array_merge($liked, $selected));
			}
			$this->session->set_userdata(array('like_me_selected' => $selected));
			$params['where_sql'][] = "id NOT IN (" . implode(', ', $selected) . ")";
		}
		return $params;
   }
   
   /**
     * Play list
     * @param array $data
     * @return array
     */
   private function _getUserData($data = array(), $page = 1)
   {	
		$data['user'] = $this->_selAction($data, $page);
		$this->load->library('PG_Output');
		$this->pg_output->setOutputData('data', $data);
		$this->pg_output->setOutputContent('template', 'ajax_like_me');
   }
   
   /**
     *  Save play action
     *  @param array $data
     *  @return array
     */
   private function _setPlayAction($data = array())
   {
		$validate_data = $this->Like_me_model->validatePlayAction($data);
		if (!empty($validate_data['errors'])) {
			return $validate_data['errors'];
		} else {
			$like_id = $this->Like_me_model->savePlayAction($validate_data['data']);
			if (isset($like_id)) {
				if ($validate_data['data']['status_match'] == 0) {
					return $this->_getUserData($data);
				} else{
					$this->Like_me_model->changeStatus($validate_data['data']);
					return $this->_sendingMessage($validate_data['data']);
				}
			}
			return false;
		}
   }
   
   /**
     * Send message 
     * @param array $data  
     * @return boolean
     */
   private function _sendingMessage($data = array())
   {
		$this->load->model('Users_model');
		$this->load->model('Notifications_model');
		$user = $this->Users_model->get_user_by_id($data['id_profile']);
		$alert = array(
			"user_nickname" => $user['fname']." ".$user['sname'],
			"profile_nickname" => $this->session->userdata('output_name'),
		);
		$this->Notifications_model->send_notification($user['email'], 'like_me_overlap', $alert, '', $user['lang_id']);
		return false;
   }
   
   /**
    *  Get cookie profile id
    *  @return array
    */
   private function _getCookieProfile()
   {
	   $result = !empty($_COOKIE['last_like_me_id']) ? unserialize($_COOKIE['last_like_me_id']) : array();
	   return array_unique($result);
   }
   
   /**
     *  Save cookie
     *  @param integer $profile_id
     *  @return void
     */
   private function _setCookieLikeMe($id = null)
   {	
		$data = $this->_getCookieProfile();
		$ids = serialize(array_merge($data, array($id)));
		$this->load->helper('cookie');
		$cookie = array(
			'name'		=> 'last_like_me_id',
			'value'		=> $ids,
			'expire'	=> time() + '604800',
			'domain'	=> COOKIE_SITE_SERVER,
			'path'		=> '/' . SITE_SUBFOLDER,
		);
		set_cookie($cookie);
   }
   
   /**
    *  Cookie 'last_like_me_id' delete
    *  @return void
    */
   private function _deleteCookieLikeMe()
   {
	   $this->load->helper('cookie');
	   $cookie = array(
			'name'		=> 'last_like_me_id',
			'domain'	=> COOKIE_SITE_SERVER,
			'path'		=> '/' . SITE_SUBFOLDER,
		);
	   delete_cookie($cookie);
   }
   
   /**
     * Load congratulations
     * @param array $data  
     * @return void
     */
   private function _loadCongratulations($data = array())
   {
		$lang_id = $this->pg_language->current_lang_id;
		$data['settings'] = $this->Like_me_model->getSettings();
		$this->load->model('Users_model');
		$user_id = intval($this->session->userdata('user_id'));
		$ids = array($user_id, $data['profile_id']);
		$data['users'] = $this->Users_model->get_users_list(null, null, null, array(), $ids);
		$this->load->library('PG_Output');
		$this->pg_output->setOutputData('user_data', $data);
		$this->pg_output->setOutputData('lang_id', $lang_id);
		$this->pg_output->setOutputContent('template', 'ajax_notify');
   }
	
    /**
     * Ajax user list
     * @return array
     */
	public function ajaxGetUsers()
	{
		$post_data = array(
			'type' => (trim(strip_tags($this->input->post('type', true))) ?: 'play_global'),
			'reload' => intval($this->input->post('reload', true)),
		);
		if(!empty($post_data['reload'])){
			$this->_deleteCookieLikeMe();
		}
		$this->_getUserData($post_data);
	}
	
	/**
	 *  Ajax play action
	 *  @return array
	 */
	public function ajaxPlayAction ()
	{
		$post_data = array(
			'type' => trim(strip_tags($this->input->post('type', true))),
			'action' => trim(strip_tags($this->input->post('action', true))),
			'profile_id' => intval($this->input->post('profile_id', true)),
		);
		$this->_setPlayAction($post_data);
	}
	
	/**
	 *  Ajax load congratulations
	 *  @return void
	 */
	public function ajaxCongratulations()
	{
		$post_data = array(
			'profile_id' => intval($this->input->post('profile_id', true)),
		);
		$this->_loadCongratulations($post_data);
	}
	
	/**
	 *  Ajax load match list
	 *  @param integer $page
	 *  @return void
	 */
	public function ajaxLoadMatches($page = null)
	{
		if (!is_null($page)) {
			$page = intval($page);
			$data = array('type' => 'matches');
			$this->_getUserData($data, $page);
		}
	}

}
