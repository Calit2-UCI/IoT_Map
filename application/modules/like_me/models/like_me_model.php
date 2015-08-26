<?php

namespace Pg\Modules\Like_me\Models;

/**
* Like me main model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Nikita Savanaev <nsavanaev@pilotgroup.net>
**/
if (!defined('BASEPATH')) exit('No direct script access allowed');
if (!defined('TABLE_LIKE_ME')) define('TABLE_LIKE_ME', DB_PREFIX.'like_me');
class Like_me_model extends \Model 
{
	
	const DB_DATE_FORMAT = 'Y-m-d H:i:s';
	const DB_DATE_FORMAT_SEARCH = 'Y-m-d H:i';
	const DB_DEFAULT_DATE = '0000-00-00 00:00:00';
	
	private $CI;
	private $DB;
    
    private $ds_gid = 'chat_message';
    private $modules_category = array(
		'communication',
		'action',
	);
    private $fields = array(
		'id',
		'id_user',
		'id_profile',
		'status_match',
		'date_created',
	);
	
	/**
	 *  Constructor
	 *  @return Like_me_model
	 */
	public function __construct()
    {
		parent::__construct();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;
	}
	
	/**
	 *  Settings
	 *  @return array
	 */
	public function getSettings()
    {
		$data = array(
			'matches_per_page' => 	$this->CI->pg_module->get_module_config('like_me', 'matches_per_page'),
			'play_local_used' => 	$this->CI->pg_module->get_module_config('like_me', 'play_local_used'),
			'play_local_area' => 	$this->CI->pg_module->get_module_config('like_me', 'play_local_area'),
			'play_more' => 			$this->CI->pg_module->get_module_config('like_me', 'play_more'),
			'chat_message' => 		$this->CI->pg_module->get_module_config('like_me', 'chat_message'),
			'chat_more' => 			$this->CI->pg_module->get_module_config('like_me', 'chat_more'),
			/*'bonus_count' => 		$this->CI->pg_module->get_module_config('like_me', 'bonus_count'),
			'bonus_likes' => 		$this->CI->pg_module->get_module_config('like_me', 'bonus_likes'),
			'bonus_type' => 		$this->CI->pg_module->get_module_config('like_me', 'bonus_type'),*/
		);
		return $this->_formatSettings($data);
	}
	
	/**
	 *  Include module
	 *  @param integer $select
	 *  @return array
	 */
	public function getActionModules($select = null)
	{
		$return = array();
		$data = ($this->CI->session->userdata("auth_type") == "user") ? array($select) : $this->_getModules();
		foreach ($data as $value) {
			$this->CI->load->model($value.'/models/'.ucfirst($value.'_model'));
			if (method_exists($this->CI->{ucfirst($value.'_model')}, 'moduleCategoryAction')) {
				$return[$value] = $this->CI->{ucfirst($value.'_model')}->moduleCategoryAction();
				$return[$value]['selected'] = ($select == $value) ? 1 : 0;
			}
		}
		return $return;
	}
	
	/**
     *  Load modules
     *  @return array
     */
    private function _getModules()
	{
		$result = array();
		$modules = $this->CI->pg_module->get_modules();
		foreach ($modules as $module) {
			if (in_array($module['category'], $this->modules_category)) {
				$result[] = $module['module_gid'];
			}
		}
		return $result;
	}
	
    /**
     *  Description action
     *  @param string $setting_gid
     *  @return array
     */
    private function _getMessageFields($setting_gid)
    {
		foreach ($this->CI->pg_language->languages as $lid => $lang) {
			$r = $this->CI->pg_language->ds->get_reference('like_me', $this->ds_gid, $lid);
			$lang_data[$lid] = $r["option"][$setting_gid];
		}
		return $lang_data;
	}
	
	/**
	 *  Liked profile ids
	 *  @param integer $user_id
	 *  @return array
	 */
	public function getLikedProfileIds($user_id = null)
	{
		$this->DB->select('id_profile');
		$this->DB->from(TABLE_LIKE_ME);
		$this->DB->where('id_user', $user_id);
		$results = $this->DB->get()->result_array();
		foreach ($results as $row) {
			$return[] = $row['id_profile'];
		}
		return $return;
	}
	
	/**
	 *  Get liked
	 *  @return integer
	 */
	private function _getLikedCheck($data = array())
	{
		$this->DB->select('id');
		$this->DB->from(TABLE_LIKE_ME);
		$this->DB->where('id_profile', $data['id_user']);
		$this->DB->where('id_user', $data['id_profile']);		
		$results = $this->DB->get()->result_array();
		if (!empty($results) && is_array($results)) {
			return 1;
		}
		return 0;
	}
	
	/**
	 *  Count matches list
	 *  @param integer $user_id
	 *  @return integer
	 */
	public function getCountMatchesList($user_id = null)
	{
		$this->DB->select('COUNT(id_profile) AS cnt');
		$this->DB->from(TABLE_LIKE_ME);
		$this->DB->where('id_user', $user_id);
		$this->DB->where('status_match', 1);
		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			return intval($results[0]["cnt"]);
		}
		return 0;
	}
	
	/**
	 *  Profile ids
	 *  @param integer $user_id
	 *  @return array
	 */
	private function _getProfileIds($user_id = null)
	{
		$this->DB->select('id_profile');
		$this->DB->from(TABLE_LIKE_ME);
		$this->DB->where('id_user', $user_id);
		$this->DB->where('status_match', 1);
		$results = $this->DB->get()->result_array();
		$return = array();
		foreach ($results as $row) {
			$return[] = $row['id_profile'];
		}
		return $return;
	}
	
	/**
	 *  Matches list
	 *  @param integer $page
	 *  @param integer $items_on_page
	 *  @param integer $user_id
	 *  @return array
	 */
	public function getMatchesList($page = 1, $items_on_page = null, $user_id = null)
	{
		$return = array();
		$data = $this->_getProfileIds($user_id);
		if (!empty($data)) {
			$return = $this->_getUsers($page, $items_on_page, $data);
		}
		return $return;
	}
	
	/**
	 *  Users list
	 *  @param integer $page
	 *  @param integer $items_on_page
	 *  @param array $data
	 *  @return array
	 */
	private function _getUsers($page = 1, $items_on_page = null, $data = array())
	{
		$this->CI->load->model('Users_model');
		$users = $this->Users_model->get_users_list($page, $items_on_page, array('date_created' => 'DESC'), array(), $data);
		return $users;
	}
    
    /**
     *  Format settings
     *  @param array $data
     *  @return array
     */
    private function _formatSettings($data = array())
    {
        $data['play_more'] = unserialize($data['play_more']);
        $data['chat_message'] = $this->_getMessageFields($data['chat_message']);
        $data['chat_more'] = $this->getActionModules($data['chat_more']);
        return $data;
    }
    
    /**
     *  Validate settings
     *  @param array $data
     *  @return array
     */
    public function validateSettings($data)
    {
		$return = array("errors"=> array(), "data" => array());
		if (isset($data['matches_per_page'])) {
			$return["data"]["matches_per_page"] = intval($data["matches_per_page"]);
			if (empty($return["data"]["matches_per_page"])) {
				$return["errors"][] = l("error_matches_per_page_incorrect", "like_me");
			}
		}
        if (isset($data['play_local_used'])) {
			$return["data"]["play_local_used"] = intval($data["play_local_used"]);
            if (!empty($data['play_local_area'])) {
                $return["data"]["play_local_area"] = trim(strip_tags($data["play_local_area"]));
                if (empty($return["data"]["play_local_area"])) {
                    $return["errors"][] = l("error_play_local_area_incorrect", "like_me");
                }
            } else {
				$return["data"]["play_local_used"] = '';
				$return["data"]["play_local_area"] = '';
			}
		}
        if (isset($data['play_more'])) {
			$return["data"]["play_more"] = serialize($data["play_more"]);
		}
       
        if (isset($data['chat_message'])) {
			foreach ($this->CI->pg_language->languages as $key => $value) {
                if (!empty($data['chat_message'][$value['id']])) {			
                    $return['ds'][$value['id']] = strip_tags($data['chat_message'][$value['id']]);
                }
            }
		}
		
		if (isset($data['chat_more'])) {
			$return["data"]["chat_more"] = trim(strip_tags($data["chat_more"]));
		}
       /* if (isset($data['bonus_count'])) {
			$return["data"]["bonus_count"] = intval($data["bonus_count"]);
			if (empty($return["data"]["bonus_count"])) {
				$return["errors"][] = l("error_bonus_count_incorrect", "like_me");
			}
		}
        if (isset($data['bonus_likes'])) {
			$return["data"]["bonus_likes"] = intval($data["bonus_likes"]);
			if (empty($return["data"]["bonus_likes"])) {
				$return["errors"][] = l("error_bonus_likes_incorrect", "like_me");
			}
		}
        if (isset($data['bonus_type'])) {
			$return["data"]["bonus_type"] = trim(strip_tags($data["bonus_type"]));
			if (empty($return["data"]["bonus_type"])) {
				$return["errors"][] = l("error_bonus_type_incorrect", "like_me");
			}
		}*/

		return $return;
	}
	
	/**
	 *  Validate Action
	 *  @param array $data
	 *  @return array
	 */
	public function validatePlayAction($data = array())
	{
		if (isset($data['action']) && $data['action'] == 'like') {
			if (isset($data['profile_id'])) {
				$return["data"]["id_user"] = intval($this->CI->session->userdata('user_id'));
				$return["data"]["id_profile"] = intval($data["profile_id"]);
				$return["data"]["status_match"] = $this->_getLikedCheck($return["data"]);
				if (empty($return["data"]["id_profile"])) {
					$return["errors"][] = l("error_profile_id_incorrect", "like_me");
				}
			}
		}
		return $return;
	}
	
	/**
	 *  Save Action
	 *  @param array $attrs
	 *  @return integer
	 */
	public function savePlayAction($attrs = array())
	{
		if (!empty($attrs)) {
			$attrs["date_created"] = date(self::DB_DATE_FORMAT);
			$this->DB->insert(TABLE_LIKE_ME, $attrs);
			$id = $this->DB->insert_id();
			return $id;
		}
		return false;
	}
	
	/**
	 *  Change status
	 *  @param array $params
	 *  @return void
	 */
	public function changeStatus($params = array())
	{
		$this->DB->where('id_user', $params['id_profile']);
		$this->DB->where('id_profile', $params['id_user']);
		$this->DB->update(TABLE_LIKE_ME, array('status_match' => 1));
	}
	
	/**
	 *  Remove matches
	 *  @param integer $user_id
	 *  @param integer $profile_id
	 *  @return void
	 */
	public function removeMatches($user_id, $profile_id)
	{
		$this->_removeILikes($user_id, $profile_id);
		$this->_removeHeLikes($profile_id, $user_id);
	}
	
	/**
	 *  Remove likes
	 *  @param integer $user_id
	 *  @param integer $profile_id
	 *  @return void
	 */
	private function _removeILikes($user_id, $profile_id)
	{
		$this->DB->where('id_user', $user_id);
		$this->DB->where('id_profile', $profile_id);
		$this->DB->delete(TABLE_LIKE_ME);
	}
	
	/**
	 *  Remove likes
	 *  @param integer $profile_id
	 *  @param integer $user_id
	 *  @return void
	 */
	private function _removeHeLikes($profile_id, $user_id)
	{
		
		$this->DB->where('id_user', $profile_id);
		$this->DB->where('id_profile', $user_id);
		$this->DB->delete(TABLE_LIKE_ME);
	}
    
    /**
     *  __Description__
     *  @param array $lang_data
     *  @return array
     */
    public function setMessageFields($lang_data)
    {
        foreach ($lang_data as $lid => $string) {
            if (empty($string)) {
                $is_err = true;
                continue;
            } elseif (!array_key_exists($lid, $this->CI->pg_language->languages)) {
                continue;
            }
        }
        if (!$is_err) {
            foreach ($lang_data as $lid => $string) {
                $option = $this->CI->pg_language->ds->get_reference('like_me', $this->ds_gid, $lid);
                $option["option"]["text"] = $string;
                $this->CI->pg_language->ds->set_module_reference('like_me', $this->ds_gid, $option, $lid);
            }
        }
		return $lang_data;
	}
    
	/**
	 *  Save settings
	 *  @param array $data
	 *  @return void
	 */
	public function setSettings($data)
    {
		foreach ($data as $setting => $value) {
			$this->CI->pg_module->set_module_config('like_me', $setting, $value);
		}
		return;
	}
	
	/**
	 *  Seo settings
	 *  @param string $method
	 *  @param integer $lang_id
	 *  @return void
	 */
	public function getSeoSettings($method = '', $lang_id = '') 
    {
		if (!empty($method)) {
			return $this->_getSeoSettings($method, $lang_id);
		} else {
			$actions = array('index');
			$return = array();
			foreach ($actions as $action) {
				$return[$action] = $this->_getSeoSettings($action, $lang_id);
			}
			return $return;
		}
	}
		
	private function _getSeoSettings($method, $lang_id = '') 
    {
		switch ($method) {
			case 'index':
				return array(
					"templates" => array(),
					"url_vars" => array(),
				); break;
		}
	}
	
	public function requestSeoRewrite($var_name_from, $var_name_to, $value)
    {
		if ($var_name_from == $var_name_to) {
			return $value;
		}
		return $value;
	}

	public function getSitemapXmlUrls()
    {
		$this->CI->load->helper('seo');
		$return = array();
		return $return;
	}

	public function getSitemapUrls() 
    {
		$this->CI->load->helper('seo');
		$auth = $this->CI->session->userdata("auth_type");
		$block = array();

		$block[] = array(
			"name" => l('header_main_sections', 'like_me'),
			"link" => rewrite_link('like_me', 'index'),
			"clickable" => ($auth=="user"),
			"items" => array(
				array(
					"name" => l('cart', 'like_me'),
					"link" => rewrite_link('like_me', 'cart'),
					"clickable" => ($auth == "user"),
				),
			)
		);
		return $block;
	}

	public function bannerAvailablePages() 
    {
		$return[] = array("link" => "like_me/product", "name" => l('header_main', 'like_me'));
		return $return;
	}
    
    public function langDedicateModuleCallbackAdd($lang_id = false) 
    {
		if (!$lang_id) return;

		$this->CI->load->dbforge();

		$fields_n['name_'.$lang_id] = array('type'=>'VARCHAR', 'constraint' => '255', 'null' => FALSE);
		$this->CI->dbforge->add_column(TABLE_LIKE_ME, $fields_n);

		$default_lang_id = $this->CI->pg_language->get_default_lang_id();
		if ($lang_id != $default_lang_id) {
			$this->CI->db->set('name_'.$lang_id, 'name_'.$default_lang_id, false);
			$this->CI->db->update(TABLE_LIKE_ME);
		}
		$fields_d['description_'.$lang_id] = array('type'=>'TEXT', 'null'=>FALSE);
		$this->CI->dbforge->add_column(TABLE_LIKE_ME, $fields_d);
		if ($lang_id != $default_lang_id) {
			$this->CI->db->set('description_'.$lang_id, 'description_'.$default_lang_id, false);
			$this->CI->db->update(TABLE_LIKE_ME);
		}
	}

	public function langDedicateModuleCallbackDelete($lang_id = false)
    {
		if (!$lang_id) return;

		$this->CI->load->dbforge();

		$table_query = $this->CI->db->get(TABLE_LIKE_ME);
		$fields_exists = $table_query->list_fields();

		$fields = array('name_'.$lang_id, 'description_'.$lang_id);
		foreach ($fields as $field_name) {
			if(!in_array($field_name, $fields_exists)) continue;
			$this->CI->dbforge->drop_column(TABLE_LIKE_ME, $field_name);
		}
	}

}
