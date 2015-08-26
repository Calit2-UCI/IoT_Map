<?php
/**
* Comments main model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Dmitry Popenov
* @version $Revision: 2 $ $Date: 2013-01-30 10:07:07 +0400 $
**/
if (!defined('TABLE_COMMENTS')) define('TABLE_COMMENTS', DB_PREFIX.'comments');

class Comments_model extends Model {

	private $CI;
	private $DB;
	
	private $fields_comments = array(
		'id',
		'gid',
		'id_object',
		'id_user',
		'id_owner',
		'user_name',
		'text',
		'likes',
		'date',
		'status'
	);
	private $fields_comments_str;
	
	private $moderation_type = "comments";

	/**
	 * Controller
	 */
	function __construct()
	{
		parent::Model();
		$this->CI = &get_instance();
		$this->DB = &$this->CI->db;
		$this->fields_comments_str = implode(', ', $this->fields_comments);
	}

	/*
	 * COMMENTS FUNCTIONS
	 */

	private function _get_comments($params, $limit = null){
		$items_on_page = $this->pg_module->get_module_config('comments', 'items_per_page');
		if(!is_array($params)){
			return array();
		}
		
		if (isset($params["where"]) && is_array($params["where"]) && count($params["where"])) {
			foreach ($params["where"] as $field => $value) {
				$this->DB->where($field, $value);
			}
		}

		if (isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])) {
			foreach ($params["where_in"] as $field => $value) {
				$this->DB->where_in($field, $value);
			}
		}

		$this->DB->select($this->fields_comments_str)->from(TABLE_COMMENTS)->order_by('id DESC');

		if(is_null($limit)){
			$this->DB->limit($items_on_page);
		}elseif($limit){
			if(is_array($limit)){
				$this->DB->limit($limit[0], $limit[1]);
			}else{
				$this->DB->limit($limit);
			}
		}
		$result['comments'] = $this->DB->get()->result_array();
		$result['max_id'] = $result['min_id'] = 0;
		$user_id = ($this->session->userdata('auth_type') == 'user') ? $this->session->userdata('user_id') : 0;
		$user_ids = array();
		foreach($result['comments'] as $key => &$comment){
			$user_ids[$comment['id_user']] = $comment['id_user'];
			$comment['can_edit'] = ($user_id && ($user_id == $comment['id_user'] || $user_id == $comment['id_owner'])) ? 1 : 0;
			$comment['is_author'] = ($user_id && $user_id == $comment['id_user']) ? 1 : 0;
			$comment['is_liked'] = $this->input->cookie('comment_like_'.$comment['id']) ? 1 : 0;
			$comment['can_like'] = (!$comment['is_author'] && $user_id) ? 1 : 0;
			if($result['max_id'] == 0 || $result['max_id'] < $comment['id']){
				$result['max_id'] = intval($comment['id']);
			}
			if($result['min_id'] == 0 || $result['min_id'] > $comment['id']){
				$result['min_id'] = intval($comment['id']);
			}
		}

		$result['users'] = $this->_get_comments_users($user_ids);
		foreach($result['comments'] as &$comment){
			$comment['user'] = isset($result['users'][$comment['id_user']]) ? $result['users'][$comment['id_user']] : array();
		}
		$result['count'] = count($result['comments']);
		if(isset($params['where']['gid']) && isset($params['where']['id_object'])){
			$result['gid'] = $params['where']['gid'];
			$result['id_obj'] = $params['where']['id_object'];
		}elseif(isset($params['where']['id'])){
			$result['gid'] = $result['comments'] ? $result['comments'][0]['gid'] : '';
			$result['id_obj'] = $result['comments'] ? $result['comments'][0]['id_object'] : 0;
		}else{
			$result['gid'] = '';
			$result['id_obj'] = 0;
		}
		$result['count_all'] = $this->get_comments_cnt($result['gid'], $result['id_obj']);
		$result['bd_min_id'] = $this->get_comments_min_id($result['gid'], $result['id_obj']);

		return $result;
	}
	
	public function validate_comment($data){
		$return = array('errors'=>array(), 'comment'=>'');
		$return["text"] = trim(strip_tags($data['text']));
		$return["user_name"] = trim(strip_tags($data['user_name']));
		if(empty($return["text"])){
			$return["errors"][] = l('error_comment_text', 'comments');
		}
		$this->CI->load->model('moderation/models/Moderation_badwords_model');
		$bw_count = $this->CI->Moderation_badwords_model->check_badwords($this->moderation_type, $return["text"]);
		$bw_count = $bw_count || $this->CI->Moderation_badwords_model->check_badwords($this->moderation_type, $return["user_name"]);
		if($bw_count){
			$return["errors"][] = l('error_badwords_message', 'comments');
		}

		return $return;
	}
	
	public function get_comments_by_gid_obj($gid, $id_obj, $status = '1'){
		$params['where']['gid'] = $gid;
		$params['where']['id_object'] = $id_obj;
		if(!is_null($status)){
			$params['where']['status'] = $status;
		}

		return $this->_get_comments($params);
	}
	
	public function get_comments_range_by_gid_obj($gid, $id_obj, $from_id = 0, $to_id = 0, $include_min_max = false, $limit = 100, $status = '1'){
		$par = $include_min_max ? '=' : '';
		$params['where']['gid'] = $gid;
		$params['where']['id_object'] = $id_obj;
		if(!is_null($status)){
			$params['where']['status'] = $status;
		}
		if($from_id){
			$params['where']["id >$par"] = $from_id;
		}
		if($to_id){
			$params['where']["id <$par"] = $to_id;
		}
		
		return $this->_get_comments($params, $limit);
	}
	
	public function get_comments_page($gid, $id_obj, $page = 1){
		$items_on_page = $this->pg_module->get_module_config('comments', 'items_per_page');
		$limit[0] = $items_on_page;
		$limit[1] = ($page-1)*$items_on_page;
		$params['where']['gid'] = $gid;
		$params['where']['id_object'] = $id_obj;
		$params['where']['status'] = '1';
		return $this->_get_comments($params, $limit);
	}
	
	public function get_comment_by_id($id, $status = null){
		$params['where']['id'] = $id;
		if(!is_null($status)){
			$params['where']['status'] = $status;
		}
		return $this->_get_comments($params);
	}
	
	public function get_comment_by_user_id($id_user){
		$params['where']['id_user'] = $id_user;
		return $this->_get_comments($params);
	}
	
	public function get_comments_cnt($gid, $id_obj, $status = '1'){
		$where['gid'] = $gid;
		$where['id_object'] = intval($id_obj);
		if(!is_null($status)){
			$where['status'] = $status;
		}
		$count = intval($this->DB->where($where)->from(TABLE_COMMENTS)->count_all_results());
		return $count;
	}
	
	public function get_comments_min_id($gid, $id_obj, $status = '1'){
		$where['gid'] = $gid;
		$where['id_object'] = intval($id_obj);
		if(!is_null($status)){
			$where['status'] = $status;
		}
		$this->DB->where($where)->from(TABLE_COMMENTS)->select_min('id');
		$min_id = intval($this->DB->get()->row()->id);
		return $min_id;
	}
	
	public function add_comment($gid, $id_obj, $text, $user_name = '', $id_owner = 0){
		$data['gid'] = $gid;
		$data['id_object'] = intval($id_obj);
		$data['text'] = $text;
		$data['user_name'] = $user_name;
		$data['id_user'] = ($this->session->userdata('auth_type') == 'user') ? $this->session->userdata('user_id') : 0;
		$data['id_owner'] = intval($id_owner);
		
		$this->CI->load->model('Moderation_model');
		$data['status'] = $this->CI->Moderation_model->get_moderation_type_status($this->moderation_type) ? '1' : '0';
		$mtype = intval($this->CI->Moderation_model->get_moderation_type($this->moderation_type));

		$this->DB->insert(TABLE_COMMENTS, $data);
		$id = $this->DB->insert_id();
		if($mtype > 0) {
			$this->CI->Moderation_model->add_moderation_item($this->moderation_type, $id);
		}
		
		$this->_count_callback($data['gid'], $data['id_object']);
		return $id;
	}
	
	private function _count_callback($gid, $id_obj = 0){
		$this->CI->load->model('comments/models/Comments_types_model');
		$comments_type = $this->CI->Comments_types_model->get_comments_type_by_gid($gid);
		$module = $comments_type['module'];
		$model = $comments_type['model'];
		$method = $comments_type['method_count'];
		if($this->pg_module->is_module_installed($module) && $this->CI->load->model($module.'/models/'.$model, '', false, true, true) && method_exists($this->CI->$model, $method)){
			$count = $id_obj ? $this->get_comments_cnt($gid, $id_obj) : 0;
			$this->CI->$model->$method($count, $id_obj);
		}
	}
	
	private function _object_callback($gid, $id_obj = 0){
		$this->CI->load->model('comments/models/Comments_types_model');
		$comments_type = $this->CI->Comments_types_model->get_comments_type_by_gid($gid);
		$module = $comments_type['module'];
		$model = $comments_type['model'];
		$method = $comments_type['method_object'];
		
		if($this->pg_module->is_module_installed($module) && $this->CI->load->model($module.'/models/'.$model, '', false, true, true) && method_exists($this->CI->$model, $method)){
			$return = $this->CI->$model->$method($id_obj);
			if (empty($return)){
				$return["body"] = "<span class='spam_object_delete'>".l("error_is_deleted_".$gid."_object", "spam")."</span>";
				$return["author"] = l("author_unknown", "spam");
			}
			return $return;
		}
	}
	
	public function save_comment($id, $params = array()){
		$this->DB->where('id', $id)->update(TABLE_COMMENTS, $params);
		$comment = $this->get_comment_by_id($id);
		if(!empty($comment['comments'][0])){
			$this->_count_callback($comment['comments'][0]['gid'], $comment['comments'][0]['id_object']);
		}
		return $this->DB->affected_rows();
	}
	
	public function delete_comment_by_id($id){
		$is_deleted = 0;
		$comment = $this->get_comment_by_id($id);
		if(!empty($comment['comments'][0])){
			$this->DB->where('id', $id)->delete(TABLE_COMMENTS);
			$is_deleted = $this->DB->affected_rows();;
			$this->CI->load->model('Moderation_model');
			$this->CI->Moderation_model->delete_moderation_item_by_obj($this->moderation_type, $id);
			$this->_count_callback($comment['comments'][0]['gid'], $comment['comments'][0]['id_object']);
		}
		return $is_deleted;
	}
	
	public function delete_comments_by_gid($gid){
		$this->DB->where('gid', $gid)->delete(TABLE_COMMENTS);
		$is_deleted = $this->DB->affected_rows();
		$this->_count_callback($gid, 0);
		return $is_deleted;
	}
	
	public function delete_comments_by_gid_obj($gid, $id_obj){
		$this->DB->where('gid', $gid)->where('id_object', $id_obj)->delete(TABLE_COMMENTS);
		$is_deleted = $this->DB->affected_rows();
		$this->_count_callback($gid, $id_obj);
		return $is_deleted;
	}
	
	private function _get_comments_users($user_ids){
		if(!$user_ids || !is_array($user_ids)){
			return array();
		}
		$this->CI->load->model('Users_model');
		$comments_users = $this->CI->Users_model->get_users_list(null,null,null,null,$user_ids);
		if(is_array($comments_users)){
			foreach($comments_users as $comments_user){
				$user_ids[$comments_user['id']] = array(
					'id' => $comments_user['id'],
					'output_name' => $comments_user['output_name'],
					'nickname' => $comments_user['nickname'],
					'user_logo' => $comments_user['media']['user_logo']['thumbs']['small'],
					'is_guest' => 0
				);
			}
		}
		$guest_user = $this->CI->Users_model->format_default_user(1);
		foreach($user_ids as $key=>$user){
			if(!is_array($user)){
				$user_ids[$key] = array(
					'id' => $key,
					'output_name' => $guest_user['output_name'],
					'nickname' => $guest_user['nickname'],
					'user_logo' => $guest_user['media']['user_logo']['thumbs']['small'],
					'is_guest' => 1
				);
			}
		}

		return $user_ids;
	}
	
	public function like_comment($id, $sign = '+', $count = 1){
		$this->DB->where('id', $id)->set('likes', "IF(likes {$sign} {$count} < 0, 0, likes {$sign} {$count})", false)->update(TABLE_COMMENTS);
		$this->DB->where('id', $id)->from(TABLE_COMMENTS);
		$likes = intval($this->DB->get()->row()->likes);
		return $likes;
	}
	
	///// moderation functions
	public function _moder_get_list($object_ids) {
		$params["where_in"]["id"] = $object_ids;

		$comments = $this->_get_comments($params, 0);

		if (!empty($comments['comments'])) {
			foreach ($comments['comments'] as $comment) {
				$return[$comment["id"]] = $comment;
			}
			return $return;
		} else {
			return array();
		}
	}

	public function _moder_set_status($object_id, $status) {
		$this->save_comment($object_id, array('status'=>"$status"));
	}
	
	public function _moder_delete_object($object_id){
		$this->delete_comment_by_id($object_id);
	}
/*
	public function _spam_callback($action, $id){
		switch($action){
			case "ban":{
				$this->save_comment($id, array('status'=>'0'));
				return "banned";
				break;
			}
			case "unban":{
				$this->save_comment($id, array('status'=>'1'));
				return "unbanned";
				break;
			}
			case "delete":{
				$this->delete_comment_by_id($id);
				return "removed";
				break;
			}
		}
	}*/

	/**
	 * Get objects list
	 * banners - default return all object
	 * @return array
	 */
	public function get_comments($page = 1, $items_on_page = 20, $order_by = null, $params = array(), $filter_object_ids = null, $format_items = true, $format_owner = false, $format_user = false, $safe_format = false){
		$this->DB->select(implode(", ", $this->fields_comments));
		$this->DB->from(TABLE_COMMENTS);

		if(isset($params["where"]) && is_array($params["where"]) && count($params["where"])){
			foreach($params["where"] as $field=>$value){
				$this->DB->where($field, $value);
			}
		}

		if(isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])){
			foreach($params["where_in"] as $field=>$value){
				$this->DB->where_in($field, $value);
			}
		}

		if(isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])){
			foreach($params["where_sql"] as $value){
				$this->DB->where($value);
			}
		}

		if(isset($params['limit']['count'])){
			if(isset($params['limit']['from'])){
				$this->DB->limit($params['limit']['count'], $params['limit']['from']);
			}else{
				$this->DB->limit($params['limit']['count']);
			}
		}

		if(isset($filter_object_ids) && is_array($filter_object_ids) && count($filter_object_ids)){
			$filter_object_ids = array_slice($filter_object_ids, 0, 5000);
			$this->DB->where_in("id", $filter_object_ids);
		}

		if(is_array($order_by) && count($order_by)>0){
			foreach($order_by as $field => $dir){
				if(in_array($field, $this->_fields)){
					$this->DB->order_by($field." ".$dir);
				}
			}
		}

		if(!is_null($page) ){
			$page = intval($page)?intval($page):1;
			$this->DB->limit($items_on_page, $items_on_page*($page-1));
		}
		$result = $this->DB->get()->result_array();
		
		foreach($result as &$event){
			$event['data'] = unserialize($event['data']);
		}
		
		$result = $this->format_comments($result);

		return $result;
	}
	
	public function format_comments($comments){
		$this->CI->load->model('Users_model');
		$formatted_comments = array();
		foreach($comments as $key => $e){
				$users_ids[$e['id_user']] = $e['id_user'];
				if($users_ids){
					$users = $this->Users_model->get_users_list_by_key(null, null, null, array(), $users_ids, false);
					$users = $this->Users_model->format_users($users, $safe_format);
					$users[0] = $this->Users_model->format_default_user(1);
					$e['user_info'] = !empty($users[$e['id_user']]) ? $users[$e['id_user']] : $users[0];
				}
			$formatted_events[$key] = $e;
		}
		return $formatted_events;
	}
	
	public function get_comments_by_key($page = 1, $items_on_page = 20, $order_by = null, $params = array(), $filter_object_ids = null, $format_items = true, $format_owner = false, $format_user = false, $safe_format = false){
		$media = $this->get_comments($page, $items_on_page, $order_by, $params, $filter_object_ids, $format_items, $format_owner, $format_user, $safe_format);
		$media_by_key = array();
		foreach($media as $m){
			$media_by_key[$m['id']] = $m;
		}
		return $media_by_key;
	}
	/*
	 * Work like get_media method, but return number of objects
	 * necessary for pagination
	 * banners - default return all object
	 */
	public function get_comments_count($params = array(), $filter_object_ids = null)
	{
		if(isset($params["where"]) && is_array($params["where"]) && count($params["where"])){
			foreach($params["where"] as $field=>$value){
				$this->DB->where($field, $value);
			}
		}

		if(isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])){
			foreach($params["where_in"] as $field=>$value){
				$this->DB->where_in($field, $value);
			}
		}

		if(isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])){
			foreach($params["where_sql"] as $value){
				$this->DB->where($value);
			}
		}

		if(isset($filter_object_ids) && is_array($filter_object_ids) && count($filter_object_ids)){
			$this->DB->where_in("id", $filter_object_ids);
		}

		if(isset($params['limit']['count'])){
			if(isset($params['limit']['from'])){
				$this->DB->limit($params['limit']['count'], $params['limit']['from']);
			}else{
				$this->DB->limit($params['limit']['count']);
			}
			return count($this->DB->select('*')->from(TABLE_COMMENTS)->get()->result_array());
		}else{
			return $this->DB->count_all_results(TABLE_COMMENTS);
		}
	}
	
	/**
	 * Callback for spam module
	 * @param string $action action name
	 * @param integer $user_ids user identifiers
	 * @return string
	 */
	public function spam_callback($action, $data){
		switch($action){
			case "delete":
				$this->delete_comment_by_id((int)$data);
				return "removed";
			break;
			case 'get_content':
				if(empty($data)) return array();
				$new_data = array();
				$return = array();
				foreach ($data as $id){
					if (($this->get_comments_count(array('where_in'=>array('id'=>$id))))==0){
						$return[$id]["content"]["view"] = $return[$id]["content"]["list"] = "<span class='spam_object_delete'>".l("error_is_deleted_comments_object", "spam")."</span>";
						$return[$id]["user_content"] = l("author_unknown", "spam");
					} else {
						$new_data[] = $id;
					}
				}	
				$events = $this->get_comments(null, null, null, null, (array)$new_data, true, false, true);
				foreach($events as $event){//echo "<pre>"; print_r($event); echo "</pre>"; 
					$return[$event['id']]["content"]["view"] = $return[$event['id']]["content"]["list"] = $event['text'];
					if ($event['user_name']=='') {
						$return[$event['id']]["user_content"] = $event['user_info']['output_name'];
					} else {
						$return[$event['id']]["user_content"] = $event['user_name'].' ('.l("guest", "comments").')';
					}
				}
				return $return;
			break;
			case 'get_subpost':
				if(($this->get_comments_count(array('where_in'=>array('id'=>$data))))==0) return array();
				$events = $this->get_comments(null, null, null, null, (array)$data);
				$return = array();
				foreach($events as $event){
					$return[$event['id']] = $this->_object_callback($event['gid'], $event['id_object']);
				}
				return $return;
			break;
			case 'get_link':
				return array();
			break;
			case 'get_deletelink':
				if(($this->get_comments_count(array('where_in'=>array('id'=>$data))))==0) return array();	
				$events = $this->get_comments(null, null, null, null, (array)$data);
				$return = array();
				foreach($events as $event){
					$return[$event['id']] = site_url().'admin/spam/delete_content/';
				}
				return $return;
			break;
			case 'get_object':
				if(($this->get_comments_count(array('where_in'=>array('id'=>$data))))==0) return array();	
				$medias = $this->get_comments_by_key(null, null, null, null, (array)$data);
				return $medias;
			break;
		}
	}
	
	public function callback_user_delete($id_user){
		$this->delete_messages_by_user_id($id_user);
	}
	
	private function delete_messages_by_user_id($id_user){
		$is_deleted = 0;
		$comment = $this->get_comment_by_user_id($id_user);
		if(!empty($comment['comments'][0])){
			$this->DB->where('id_user', $id_user)->delete(TABLE_COMMENTS);
			$is_deleted = $this->DB->affected_rows();
			foreach($comment['comments'] as $k => $val){
				$this->_count_callback($comment['comments'][$k]['gid'], $comment['comments'][$k]['id_object']);
			}
		}
		return $is_deleted;
	}
}