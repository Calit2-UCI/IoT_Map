<?php
/**
* Wall events model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Dmitry Popenov
* @version $Revision: 2 $ $Date: 2013-01-30 10:07:07 +0400 $
**/
if (!defined('TABLE_WALL_EVENTS')) define('TABLE_WALL_EVENTS', DB_PREFIX.'wall_events');

class Wall_events_model extends Model {

	const	PERM_PRIVATE = 0,
			PERM_FRIENDS = 1,
			PERM_REGISTERED = 2,
			PERM_ALL = 3;

	private $CI;
	private $DB;
	private $fields = array(
		'id',
		'event_type_gid',
		'id_wall',
		'id_poster',
		'id_object',
		'date_add',
		'date_update',
		'data',
		'permissions',
		'status',
		'comments_count'
	);
	private $fields_str;
	private $max_event_data_count = 50;
	
	public $wall_event_gid = 'wall_post';
	
	public $event_type = false;
	
	public $image_upload_gid = 'wall-image';
	public $video_upload_gid = 'wall-video';
	
	private $upload_config;
	
	private $moderation_type = "wall_events";
	

	public function __construct(){
		parent::Model();
		$this->CI = &get_instance();
		$this->DB = &$this->CI->db;
		$this->fields_str = implode(', ', $this->fields);
		$this->CI->load->model('uploads/models/Uploads_config_model');
		$upload_config = $this->CI->Uploads_config_model->get_config_by_gid($this->image_upload_gid);
		$this->upload_config[$this->image_upload_gid] = $this->CI->Uploads_config_model->format_config($upload_config);
		$this->CI->load->model('video_uploads/models/Video_uploads_config_model');
		$this->upload_config[$this->video_upload_gid] = $this->CI->Video_uploads_config_model->get_config_by_gid($this->video_upload_gid);
	}
	
	public function initialize_event_type($gid){
		$this->CI->load->model('wall_events/models/Wall_events_types_model');
		$this->event_type = $this->CI->Wall_events_types_model->get_wall_events_type($gid);
	}

	public function add_event($event_type_gid, $id_wall, $id_poster, $data, $permissions = 3, $id_object = 0, $file_name = ''){
		if(!$this->event_type){
			$this->initialize_event_type($event_type_gid);
			if(!$this->event_type){
				return false;
			}
		}
		$date = date("Y-m-d H:i:s");
		$data['event_date'] = $date;
		$attrs = array(
			'event_type_gid'	=> $event_type_gid,
			'id_wall'			=> $id_wall,
			'data'				=> array($data),
			'permissions'		=> $permissions,
			'id_poster'			=> $id_poster,
			'id_object'			=> $id_object,
			'date_update'		=> $date,
		);
		$joined_event = $this->_get_joined_event($event_type_gid, $id_wall, $id_poster);
		if($joined_event){
			$do_join = true;
			foreach($joined_event['data'] as $join_data){
				if(!empty($join_data['videos'])){
					foreach($join_data['videos'] as $jd_video){
						if($jd_video['status'] != 'end' && empty($jd_video['errors'])){
							$do_join = false;
							break 2;
						}
					}
				}
			}
			if($do_join){
				foreach($joined_event['data'] as $join_data){
					$attrs['data'][] = $join_data;
				}
			}else{
				$joined_event = false;
			}
		}
		if(!$joined_event){
			$attrs['date_add'] = $date;
		}else{
			$attrs['date_add'] = $joined_event['date_add'];
		}
		$id = $this->_save_event($attrs, array());
		
		if($id && $joined_event){
			$where['id'] = $joined_event['id'];
			$this->delete_events($where, false);
		}else{
			$this->_clear_events($id_wall);
		}
		
		if(!empty($file_name) && !empty($id) && isset($_FILES[$file_name]) && is_array($_FILES[$file_name]) && is_uploaded_file($_FILES[$file_name]["tmp_name"])){
			$return = $this->add_event_data($id, array(), $file_name);
		}
		if($joined_event){
			$return['joined_id'] = intval($joined_event['id']);
		}
		$return['id'] = $id;
		
		return $return;
	}
	
	private function _save_event($attrs, $where = array()){
		if(!empty($attrs['data']) && is_array($attrs['data'])){
			$attrs['data'] = serialize($attrs['data']);
		}
		if(!empty($where) && is_array($where)){
			$this->DB->where($where)->update(TABLE_WALL_EVENTS, $attrs);
			return $this->DB->affected_rows();
		}else{
			$this->DB->insert(TABLE_WALL_EVENTS, $attrs);
			$return = $this->DB->insert_id();
			return $return;
		}
	}
	
	public function add_event_data($id, $data, $file_name){
		$result['errors'] = array();
		$event = $this->get_event_by_id($id);
		if($event){
			$data_count = 0;
			foreach($event['data'] as $e_data){
				if(!empty($e_data['images'])){
					$data_count += count($e_data['images']);
				}
				if(!empty($e_data['videos'])){
					$data_count += count($e_data['videos']);
				}
			}
			if($data_count >= $this->max_event_data_count){
				$result['id'] = $id;
				$result['errors'][] = l('error_files_limit', 'wall_events');
				return $result;
				//return $this->add_event($event['event_type_gid'], $event['id_wall'], $event['id_poster'], $data, $event['permissions'], $event['id_object'], $file_name);
			}
			
			if(!empty($file_name) && isset($_FILES[$file_name]) && is_array($_FILES[$file_name]) && is_uploaded_file($_FILES[$file_name]["tmp_name"])){
				$upload_gid = $this->_get_upload_gid($_FILES[$file_name]['name']);
				if($upload_gid == $this->image_upload_gid){
					$this->CI->load->model('Uploads_model');
					$upload_return = $this->CI->Uploads_model->upload($this->image_upload_gid, $id, $file_name);
					if(empty($upload_return['errors'])){
						$data[0]['images'][] = array(
							'file' => $upload_return['file'],
							'id' => $id,
						);
						$result['name'] = $upload_return['file'];
					} else {
						$result['errors'][] = $upload_return["errors"];
					}
					$event['data'][0] = array_merge_recursive((array)$data[0], (array)$event['data'][0]);
					$result['is_saved'] = $this->_save_event($event, array('id'=>$id));
				}elseif($upload_gid == $this->video_upload_gid){
					$this->CI->load->model('Video_uploads_model');
					usleep(2);
					$video_data['key'] = substr(md5(microtime()), 0, 10);
					$event['data'][0]['videos'][$video_data['key']]['status'] = 'start';
					$this->_save_event($event, array('id'=>$id));

					$upload_return = $this->CI->Video_uploads_model->upload($this->video_upload_gid, $id, $file_name, $id, $video_data);
					$event = $this->get_event_by_id($id);
					if(empty($upload_return['errors'])){
						$result['name'] = $upload_return['file'];
						$event['data'][0]['videos'][$video_data['key']]['file'] = $upload_return['file'];
					} else {
						$result['errors'][] = $upload_return['errors'];
						$event['data'][0]['videos'][$video_data['key']]['errors'] = $upload_return['errors'];
					}
					$result['is_saved'] = $this->_save_event($event, array('id'=>$id));
				}else{
					$result['errors'][] = l('error_invalid_file_type', 'wall_events');
				}
			}else{
				$event['data'][0] = array_merge_recursive((array)$data[0], (array)$event['data'][0]);
				$result['is_saved'] = $this->_save_event($event, array('id'=>$id));
			}
		}else{
			$result['errors'][] = l('error_event_not_found', 'wall_events');
		}
		$result['id'] = $id;
		
		return $result;
	}
	
	public function get_event_by_id($id){
		$params['where']['id'] = $id;
		$result = $this->_get_events($params);
		if(!empty($result[0])){
			return $result[0];
		}
		return array();
	}
	
	public function get_events($params = array(), $items_on_page = null, $page = null){
		$result = $this->_get_events($params, $items_on_page, $page);
		return $result;
	}
	
	private function _get_events($params = array(), $items_on_page = null, $page = null){
		if(!empty($params['where']) && is_array($params['where'])){
			$this->DB->where($params['where']);
		}
		if(!empty($params['where_in']) && is_array($params['where_in'])){
			foreach($params['where_in'] as $field => $value){
				$this->DB->where_in($field, $value);
			}
		}
		if(!empty($params['where_sql']) && is_array($params['where_sql'])){
			foreach($params['where_sql'] as $value){
				$this->DB->where($value, null, false);
			}
		}
		if(!is_null($items_on_page)){
			$page = intval($page) ? intval($page) : 1;
			$this->DB->limit($items_on_page, $items_on_page * ($page - 1));
		}else{
			$this->DB->limit(1000);
		}
		$result = $this->DB->select($this->fields_str)->from(TABLE_WALL_EVENTS)->order_by('id', 'DESC')->get()->result_array();
		foreach($result as &$event){
			$event['data'] = unserialize($event['data']);
		}
		return $result;
	}
	
	public function format_events($events){
		$formatted_events = array();
		if(!empty($events) && is_array($events)){
			foreach($events as $key => $e){
				$e_by_gid[$e['event_type_gid']][$key] = $e;
			}
			$this->CI->load->model('wall_events/models/Wall_events_types_model');
			$events_types = $this->CI->Wall_events_types_model->get_wall_events_types();
			foreach($e_by_gid as $gid => $es){
				$module = $events_types[$gid]['module'];
				$model = $events_types[$gid]['model'];
				$method = $events_types[$gid]['method_format_event'];
				if($this->pg_module->is_module_installed($module) && $this->CI->load->model($module.'/models/'.$model, '', false, true, true)){
					$formatted_events += $this->CI->$model->$method($es);
				}
			}
		}
		ksort($formatted_events);
		return $formatted_events;
	}
	
	public function _format_wall_events($events, $format_user = false){
		$this->CI->load->model("Uploads_model");
		$this->CI->load->model("Video_uploads_model");
		if ($format_user){
			$this->CI->load->model('Users_model');
		}
		$formatted_events = array();
		foreach($events as $key => $e){
			if ($format_user){
				$users_ids[$e['id_poster']] = $e['id_poster'];
				if($users_ids){
					$users = $this->Users_model->get_users_list_by_key(null, null, null, array(), $users_ids, false);
					$users = $this->Users_model->format_users($users, $safe_format);
					$users[0] = $this->Users_model->format_default_user(1);
					$e['user_info'] = !empty($users[$e['id_poster']]) ? $users[$e['id_poster']] : $users[0];
				}
			}
			foreach($e['data'] as $datakey=>$data){
				if(!empty($data['images'])){
					foreach($data['images'] as $img){
						$image_data = $this->CI->Uploads_model->format_upload($this->image_upload_gid, $img['id'], $img['file']);
						$image_data['photo_title'] = str_replace('[id]', $img['id'], l('text_wall_photo', 'wall_events'));
						$image_data['photo_alt'] = str_replace('[id]', $img['id'], l('text_wall_photo', 'wall_events'));
						$e['media'][$datakey]['img'][] = $image_data;
					}
				}
				if(!empty($data['videos'])){
					foreach($data['videos'] as $video){
						if(!empty($video['video'])){
							if(!isset($video['isHTML5'])) $video['isHTML5'] = 0;
							if(!isset($video['video_image'])) $video['video_image'] = "";
							if(!isset($video['upload_type'])) $video['upload_type'] = "local";
							
							$formatted_video = $this->CI->Video_uploads_model->format_upload($this->video_upload_gid, $e['id'], $video['video'], $video['video_image'], $video['upload_type'], $video['isHTML5']);
							$formatted_video['status'] = $video['status'];
							
							$errors = array();
							foreach($video['errors'] as $error){
								if(!empty($error)){
									$errors[] = $error;
								}
							}
							
							$formatted_video['errors'] = !empty($errors) ? $errors : "";
							
							$e['media'][$datakey]['video'][] = $formatted_video;
						}else{
							
							$errors = array();
							foreach($video['errors'] as $error){
								if(!empty($error)){
									$errors[] = $error;
								}
							}
							
							$e['media'][$datakey]['video'][] = array(
								'video' => '',
								'status' => $video['status'],
								'errors' => !empty($errors) ? $errors : "",
							);
						}
					}
				}
				if(!empty($data['embed_data'])){
					$formatted_video = $this->CI->Video_uploads_model->format_upload($this->video_upload_gid, '', $data['embed_data'], '', 'embed');
					$e['media'][$datakey]['video'][] = $formatted_video;
				}
			}
			$is_event_data = false;
			if(empty($e['media'])){
				foreach($e['data'] as $e_data){
					if(!empty($e_data['text'])){
						$is_event_data = true;
						break;
					}
				}
			}else{
				$is_event_data = true;
			}
			if($is_event_data){
				$this->CI->template_lite->assign('event', $e);
				$e['html'] = $this->CI->template_lite->fetch('wall_events_wall_post', null, 'wall_events');
				$formatted_events[$key] = $e;
			}
		}
		return $formatted_events;
	}
	
	private function _get_joined_event($event_type_gid, $id_wall, $id_poster){
		$params['where']['event_type_gid'] = $event_type_gid;
		$params['where']['id_wall'] = $id_wall;
		$params['where']['id_poster'] = $id_poster;
		$params['where']['date_update >='] = date("Y-m-d H:i:s", time() - $this->event_type['settings']['join_period']*60);
		$params['where']['comments_count'] = '0';
		$events = $this->get_events($params);
		//check max_event_data_count for prevent mega events with lots of data requiring big sql requests
		if(!empty($events[0]['data'])){
			$data_count = count($events[0]['data']);
			foreach($events[0]['data'] as $e_data){
				if(!empty($e_data['images'])){
					$data_count += count($e_data['images']) - 1;
				}
				if(!empty($e_data['videos'])){
					$data_count += count($e_data['videos']) - 1;
				}
			}
			if($data_count >= $this->max_event_data_count){
				return false;
			}
			return $events[0];
		}
		return false;
	}
	
	public function delete_events($where, $delete_uploads = true){
		$events = $this->get_events(array('where' => $where));
		if($delete_uploads){
			$this->CI->load->model('Uploads_model');
			$this->CI->load->model('Video_uploads_model');
			foreach($events as $e){
				$id = $e['id'];
				foreach($e['data'] as $edata){
					if(!empty($edata['images'])){
						foreach($edata['images'] as $img){
							$this->CI->Uploads_model->delete_upload($this->image_upload_gid, $img['id'], $img['file']);
						}
					}
					if(!empty($edata['videos'])){
						foreach($edata['videos'] as $video){
							$this->CI->Video_uploads_model->delete_upload($this->video_upload_gid, $e['id'], $video['file'], $video['video_image']);
						}
					}
				}
			}
			$this->CI->Uploads_model->delete_path($this->image_upload_gid, $id);
		}
		$this->DB->where($where);
		$this->DB->delete(TABLE_WALL_EVENTS);
		return $this->DB->affected_rows();
	}
	
	public function get_events_count($params = array()){
		if(!empty($params['where']) && is_array($params['where'])){
			$this->DB->where($params['where']);
		}
		if(!empty($params['where_in']) && is_array($params['where_in'])){
			foreach($params['where_in'] as $field => $value){
				$this->DB->where_in($field, $value);
			}
		}
		if(!empty($params['where_sql']) && is_array($params['where_sql'])){
			foreach($params['where_sql'] as $value){
				$this->DB->where($value, null, false);
			}
		}
		$result = $this->DB->count_all_results(TABLE_WALL_EVENTS);
		return $result;
	}
	
	private function _clear_events($id_wall){
		$config = $this->pg_module->get_module_all_config('wall_events');
		$where = array();
		if($config['events_max_count']){
			if($config['live_period']){
				$where = array();
				$where['id_wall'] = $id_wall;
				$where['date_update >='] = date("Y-m-d H:i:s", time() - $config['live_period']*60*60*24);
				$events_in_date = $this->get_events_count($where);
				if($events_in_date >= $config['events_max_count']){
					$where = array();
					$where['id_wall'] = $id_wall;
					$where['date_update <'] = date("Y-m-d H:i:s", time() - $config['live_period']*60*60*24);
					return $this->delete_events($where);
				}
			}
			$this->DB->select('id')->from(TABLE_WALL_EVENTS)->where('id_wall', $id_wall)->order_by('id', 'DESC')->limit($config['events_max_count']);
			$events_in_count = $this->DB->get()->result_array();
			if(count($events_in_count) == $config['events_max_count']){
				$last_event_in_count = array_pop($events_in_count);
				$where = array();
				$where['id_wall'] = $id_wall;
				$where['id <'] = $last_event_in_count['id'];
				return $this->delete_events($where);
			}
		}elseif($config['live_period']){
			$where = array();
			$where['id_wall'] = $id_wall;
			$where['date_update <'] = date("Y-m-d H:i:s", time() - $config['live_period']*60*60*24);
			return $this->delete_events($where);
		}
		return false;
	}
	
	public function update_permissions($id_wall, $event_type_gid, $permissions){
		$where['id_wall'] = intval($id_wall);
		$where['event_type_gid'] = trim(strip_tags($event_type_gid));
		$permissions = intval($permissions);
		$this->DB->where($where)->set('permissions', $permissions)->update(TABLE_WALL_EVENTS);
		return $this->DB->affected_rows();
	}
	
	////// video callback
	public function video_callback($id, $status, $data, $errors){
		$result['errors'] = array();
		$result['is_saved'] = 0;
		$event = $this->get_event_by_id($id);
		if($event){
			$data_key = false;
			foreach($event['data'] as $key => $edata){
				if(!empty($edata['videos'][$data['file_data']['key']])){
					$video_data = $edata['videos'][$data['file_data']['key']];
					$data_key = $key;
					break;
				}
			}
			if($data_key !== false){
				$video_data['isHTML5'] = (!empty($data['isHTML5'])) ? $data['isHTML5'] : 0;
				$video_data['errors'] = (!empty($video_data['errors'])) ? $video_data['errors'] : "";
				if(isset($data['video'])){
					$video_data['video'] = $data['video'];
				}
				if(isset($data['image'])){
					$video_data['video_image'] = $data['image'];
				}
				$video_data['status'] = $status;
				$video_data['errors'] = array_merge((array)$video_data['errors'], (array)$errors);

				$event['data'][$data_key]['videos'][$data['file_data']['key']] = $video_data;

				$result['is_saved'] = $this->_save_event($event, array('id'=>$event['id']));
			}
		}
		return $result;
	}
	
	public function validate($event_data, $file_name = '', $required_fields = array()){
		$validate_data['data'] = $validate_data['errors'] = array();
		if(isset($event_data['id_wall'])){
			$validate_data['data']['id_wall'] = intval($event_data['id_wall']);
			$this->CI->load->model('Users_model');
			$owner = $this->CI->Users_model->get_user_by_id($validate_data['data']['id_wall']);
			if(!$owner){
				$validate_data['errors'][] = l('error_user_not_found', 'wall_events');
			}
		}
		$validate_data['data']['data'] = $event_data['data'];
		
		if(!empty($event_data['data']['text'])){
			$this->CI->load->library('VideoEmbed');
			$text = trim(strip_tags($event_data['data']['text']));
			//parse video urls to embed codes
			$text = $this->CI->videoembed->replace_urls_to_embed_in_text($text);
			
			$this->CI->load->model('moderation/models/Moderation_badwords_model');
			$bw_count = $this->CI->Moderation_badwords_model->check_badwords($this->moderation_type, $text);
			if(!empty($validate_data['data']['user_name'])) {
				$user_name = $validate_data['data']['user_name'];
			} else {
				$user_name = '';
			}
			$bw_count = $bw_count || $this->CI->Moderation_badwords_model->check_badwords($this->moderation_type, $user_name);
			if($bw_count){
				$validate_data["errors"][] = l('error_badwords_message', 'wall_events');
			}
			
			$validate_data['data']['data']['text'] = $text;
		}
		
		if(!empty($event_data['id_poster'])){
			$validate_data['data']['id_poster'] = intval($event_data['id_poster']);
		}
		
		$embed_data = array();
		if(!empty($event_data['data']['embed_code'])){
			$this->CI->load->library('VideoEmbed');
			$embed_data = $this->CI->videoembed->get_video_data($event_data['data']["embed_code"]);
			if($embed_data !== false){
				$embed_data["string_to_save"] = $this->videoembed->get_string_from_video_data($embed_data);
				$validate_data['data']['data']['embed_data'] = $embed_data;
			}else{
				$validate_data["errors"][] = l('error_embed_wrong', 'wall_events');
			}
			unset($validate_data['data']['data']['embed_code']);
		}
		if(!empty($file_name)){
			if(isset($_FILES[$file_name]) && is_array($_FILES[$file_name]) && !($_FILES[$file_name]["error"])){
				$upload_gid = $this->_get_upload_gid($_FILES[$file_name]['name']);
				if($upload_gid == $this->image_upload_gid){
					$this->CI->load->model("Uploads_model");
					$file_return = $this->CI->Uploads_model->validate_upload($this->image_upload_gid, $file_name);
					if(!empty($file_return["error"])){
						$validate_data["errors"][] = (is_array($file_return["error"])) ? implode("<br>", $file_return["error"]) : $file_return["error"];
					}
				}elseif($upload_gid == $this->video_upload_gid){
					$this->CI->load->model("Video_uploads_model");
					$file_return = $this->CI->Video_uploads_model->validate_upload($this->video_upload_gid, $file_name);
					if(!empty($file_return["error"])){
						$validate_data["errors"][] = (is_array($file_return["error"])) ? implode("<br>", $file_return["error"]) : $file_return["error"];
					}
				}else{
					$validate_data["errors"][] = l('error_invalid_file_type', 'wall_events');
				}
				$validate_data["data"]['mime'] = $_FILES[$file_name]["type"];
			}elseif(!empty($_FILES[$file_name]["error"])){
				$validate_data["errors"][] = $_FILES[$file_name]["error"];
			}
		}

		foreach($required_fields as $required_field){
			if(empty($validate_data['data']['data'][$required_field])){
				$validate_data['errors'][] = l('error_empty_data', 'wall_events');
			}
		}
		
		return $validate_data;
	}
	
	private function _get_upload_gid($file_name) {
		$extension = strtolower(end(explode('.', $file_name)));
		$result = '';
		foreach($this->upload_config as $upload_gid => $upload_config){
			foreach($upload_config['file_formats'] as $file_format){
				if($file_format == $extension){
					$result = $upload_gid;
					break 2;
				}
			}
		}
		return $result;
	}

	public function comments_count_callback($count, $id = 0){
		$where = $id ? array('id'=>$id) : array();
		$attrs['comments_count'] = $count;
		$this->_save_event($attrs, $where);
	}

	public function comments_object_callback($id = 0){
		$return = array();
		$object = $this->get_wall_events(null, null, null, null, (array)$id);
		if (!empty($object)) {
			$return["body"] = $object[0]["html_delete"]?$object[0]["html_delete"]:$object[0]["html"];
			$return["author"] = $object[0]["html_delete"]?l("author_unknown", "spam"):$object[0]["user_info"]["output_name"];
		}
		return $return;
	}

	/**
	 * Get objects list
	 * banners - default return all object
	 * @return array
	 */
	public function get_wall_events($page = 1, $items_on_page = 20, $order_by = null, $params = array(), $filter_object_ids = null, $format_items = true, $format_owner = false, $format_user = false, $safe_format = false){
		$this->DB->select(implode(", ", $this->fields));
		$this->DB->from(TABLE_WALL_EVENTS);

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
		
		$result = $this->format_events($result, true);

		$this->CI->load->model('Users_model');
		foreach($result as $key => $e){
			$users_ids[$e['id_poster']] = $e['id_poster'];
			if($users_ids){
				$users = $this->Users_model->get_users_list_by_key(null, null, null, array(), $users_ids, false);
				$users = $this->Users_model->format_users($users, $safe_format);
				$users[0] = $this->Users_model->format_default_user(1);
				$result[$key]['user_info'] = !empty($users[$e['id_poster']]) ? $users[$e['id_poster']] : $users[0];
			}
		}
		return $result;
	}
	
	public function get_wall_events_by_key($page = 1, $items_on_page = 20, $order_by = null, $params = array(), $filter_object_ids = null, $format_items = true, $format_owner = false, $format_user = false, $safe_format = false){
		$media = $this->get_wall_events($page, $items_on_page, $order_by, $params, $filter_object_ids, $format_items, $format_owner, $format_user, $safe_format);
		$media_by_key = array();
		foreach($media as $m){
			$media_by_key[$m['id']] = $m;
		}
		return $media_by_key;
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
				$where['id'] = (int)$data;
				$this->delete_events($where);
				return "removed";
			break;
			case 'get_content':
				if(empty($data)) return array();	
				$new_data = array();
				$return = array();
				foreach ($data as $id){
					if (($this->get_events_count(array('where_in'=>array('id'=>$id))))==0){
						$return[$id]["content"]["view"] = $return[$id]["content"]["list"] = "<span class='spam_object_delete'>".l("error_is_deleted_wall_events_object", "spam")."</span>";
						$return[$id]["user_content"] = l("author_unknown", "spam");
					} else {
						$new_data[] = $id;
					}
				}
				$events = $this->get_wall_events(null, null, null, null, (array)$new_data, true, false, true);

				foreach($events as $event){
					if (!$event['html_delete'])
						if (preg_match_all("/id=\"default_player_([0-9]+)\">/is", $event['html'], $match)){
							$return[$event['id']]["rand"] = $match[1];
							foreach ($match[1] as $key=>$rand){
								$event['html'] = str_replace($rand, $rand."_".$key, $event['html']);
							}
						}
					$return[$event['id']]["content"]["view"] = $return[$event['id']]["content"]["list"] = $event['html_delete']?$event['html_delete']:$event['html'];
					$return[$event['id']]["user_content"] = $event['html_delete']?l("author_unknown", "spam"):$event['user_info']['output_name'];
				}
				return $return;
			break;
			case 'get_subpost':
				return array();
			break;
			case 'get_link':
				return array();
			break;
			case 'get_deletelink':
				if(empty($data)) return array();	
				$events = $this->get_wall_events(null, null, null, null, (array)$data);
				$return = array();
				foreach($events as $event){
					$return[$event['id']] = site_url().'admin/spam/delete_content/';
				}
				return $return;
			break;
			case 'get_object':
				if(empty($data)) return array();	
				$medias = $this->get_wall_events_by_key(null, null, null, null, (array)$data);
				return $medias;
			break;
		}
	}
	
	public function callback_user_delete($id_user){
		$this->delete_events_by_user_id($id_user);
	}
	
	private function delete_events_by_user_id($id_user){
		$where['id_poster'] = $id_user;
		return $this->delete_events($where);
	}
}
