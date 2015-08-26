<?php

/**
 * News module
 *
 * @package 	PG_Dating
 * @copyright 	Copyright (c) 2000-2014 PilotGroup.NET Powered by PG Dating Pro
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */


if (!defined('BASEPATH')) exit('No direct script access allowed');

define('NEWS_TABLE', DB_PREFIX.'news');

/**
 * News main model
 *
 * @package 	PG_Dating
 * @subpackage 	News
 * @category	models
 * @copyright 	Copyright (c) 2000-2014 PilotGroup.NET Powered by PG Dating Pro
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
class News_model extends Model
{
	/**
	 * Link to CodeIgniter object
	 * 
	 * @var object
	 */
	private $CI;
	
	/**
	 * Link to database object
	 * 
	 * @var object
	 */
	private $DB;

	/**
	 * News object properties in data source
	 * 
	 * @var array
	 */
	private $fields_news = array(
		'id',
		'gid',
		//'name',
		//'annotation',
		//'content',
		'img',
		'status',
		'id_lang',
		'news_type',
		'date_add',
		'feed_link',
		'feed_id',
		'feed_unique_id',
		'video',
		'video_image',
		'video_data',
		'comments_count',
		'id_seo_settings',
	);

	/**
	 * Short properties of news
	 * 
	 * @var array
	 */
	private $fields_news_cute = array(
		'id',
		'gid',
		//'name',
		//'annotation',
		'img',
		'status',
		'id_lang',
		'news_type',
		'date_add',
		'feed_link',
		'feed_id',
		'feed_unique_id',
		'comments_count',
	);

	/**
	 * News logo upload (GUID)
	 * 
	 * @var string
	 */
	public $upload_config_id = 'news-logo';
	
	/**
	 * Video news upload (GUID)
	 * 
	 * @var string
	 */
	public $video_config_id = 'news-video';
	
	/**
	 * News RSS logo upload (GUID)
	 * 
	 * @var string
	 */
	public $rss_config_id = 'rss-logo';
	
	/**
	 * Class constructor
	 *
	 * @return News_model
	 */
	public function __construct(){
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;
	}

	/**
	 * Return news object by identifier
	 * 
	 * @param integer $id news identifier
	 * @param integer $lang_ids languages identifiers
	 * @return array/false
	 */
	public function get_news_by_id($id, $lang_ids=null){
		if(empty($lang_ids)){
			$lang_ids = array($this->CI->pg_language->current_lang_id);
		}
		
		$fields_news = $this->fields_news;
		
		foreach($lang_ids as $lang_id){
			$fields_news[] = 'name_'.$lang_id;
			$fields_news[] = 'annotation_'.$lang_id;
			$fields_news[] = 'content_'.$lang_id;
		}
		
		$result = $this->DB->select(implode(", ", $fields_news))
						   ->from(NEWS_TABLE)->where("id", $id)
						   ->get()
						   ->result_array();
		if(empty($result)){
			return false;
		}else{
			reset($lang_ids);
			$data = $result[0];
			$lang_id = in_array($data['id_lang'], $lang_ids) ? $data['id_lang'] : current($lang_ids);
			$data['name'] = $data['name_'.$lang_id];
			$data['annotation'] = $data['annotation_'.$lang_id];
			$data['content'] = $data['content_'.$lang_id];
			return $data;
		}
	}

	/**
	 * Return news object by GUID
	 * 
	 * @param string $gid news GUID
	 * @return array/false
	 */
	public function get_news_by_gid($gid, $lang_id=null){
		if(!$lang_id) $lang_id = $this->CI->pg_language->current_lang_id;
		
		$fields_news = $this->fields_news;
		$fields_news[] = 'name_'.$lang_id;
		$fields_news[] = 'annotation_'.$lang_id;
		$fields_news[] = 'content_'.$lang_id;
		
		$result = $this->DB->select(implode(", ", $fields_news))
						   ->from(NEWS_TABLE)
						   ->where("gid", $gid)
						   ->get()
						   ->result_array();
		if(empty($result)){
			return false;
		}else{
			$data = $result[0];
			$data['name'] = $data['name_'.$lang_id];
			$data['annotation'] = $data['annotation_'.$lang_id];
			$data['content'] = $data['content_'.$lang_id];
			return $data;
		}
	}

	/**
	 * Format news object
	 * 
	 * @param array $news news object
	 * @return array
	 */
	public function format_news($news){
		$feeds = array();
		
		$is_uploads_install = $this->CI->pg_module->is_module_installed('uploads');
		if($is_uploads_install){
			$this->CI->load->model('Uploads_model');
		}
		
		$is_video_uploads_install = $this->CI->pg_module->is_module_installed('video_uploads');
		if($is_video_uploads_install){
			$this->CI->load->model('Video_uploads_model');
		}
		
		$this->CI->load->helper('date_format');
		$date_formats = $this->pg_date->get_format('date_literal', 'st');

		foreach($news as $key => $data){
			if(!empty($data["id"])){
				$data["prefix"] = date("Y/m/d/", strtotime($data["date_add"])).$data["id"]."";
			}
			
			if($is_uploads_install){
				if(!empty($data["img"])){
					$data["media"]["img"] = $this->CI->Uploads_model->format_upload($this->upload_config_id, $data["prefix"], $data["img"]);
				}else{
					$data["media"]["img"] = $this->CI->Uploads_model->format_default_upload($this->upload_config_id);
				}
			}
			
			if($is_video_uploads_install){
				if(!empty($data["video_data"])){
					$data["video_data"] = unserialize($data["video_data"]);
				}
			

				if(!empty($data["video"]) && $data["video_data"]["status"] == "end"){
					$data["video_content"] = $this->CI->Video_uploads_model->format_upload($this->video_config_id, $data["prefix"], $data["video"], $data["video_image"], $data["video_data"]["data"]["upload_type"]);
				}
			}

			// seo data
			$data['created-date'] = tpl_date_format($data['date_add'], $date_formats);

			$news[$key] = $data;

			if(!empty($data["feed_id"]) && !in_array($data["feed_id"], $feeds)){
				$feeds[] = $data["feed_id"];
			}
		}

		if(!empty($feeds)){
			$this->CI->load->model('news/models/Feeds_model');
			$temp = $this->CI->Feeds_model->get_feeds_list(null, null, null, array(), $feeds);
			if(!empty($temp)){
				foreach($temp as $feed) $feeds_list[$feed["id"]] = $feed;
				foreach($news as $key => $data){
					if(!empty($data["feed_id"]) && !empty($feeds_list[$data["feed_id"]])){
						$news[$key]["feed"] = $feeds_list[$data["feed_id"]];
					}
				}
			}
		}
		
		return $news;
	}

	/**
	 * Format news object
	 * 
	 * @param array $news news object
	 * @return array
	 */
	public function format_single_news($news){
		$data = $this->format_news(array($news));
		return $data[0];
	}

	/**
	 * Return news objects as array
	 * 
	 * @param integer $page page of results
	 * @param integer $items_on_page items per page
	 * @param array $order_by sorting data
	 * @param array $params sql criteria of query to data source
	 * @param array $filter_object_ids filters identifiers
	 * @param boolean $formated format results
	 * @return array
	 */
	public function get_news_list($page=null, $items_on_page=null, $order_by=null, $params=array(), $filter_object_ids=null, $formated=true){
		$lang_id = $this->CI->pg_language->current_lang_id;
		
		$fields_news_cute = $this->fields_news_cute;
		$fields_news_cute[] = 'name_'.$lang_id;
		$fields_news_cute[] = 'annotation_'.$lang_id;
		
		$this->DB->select(implode(", ", $fields_news_cute));
		$this->DB->from(NEWS_TABLE);

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

		if(is_array($order_by) && count($order_by)>0){
			$fields_news = $this->fields_news;
			$fields_news[] = 'name_'.$lang_id;
			$fields_news[] = 'annotation_'.$lang_id;
			$fields_news[] = 'content_'.$lang_id;
			
			foreach($order_by as $field => $dir){
				if(in_array($field, $fields_news)){
					$this->DB->order_by($field." ".$dir);
				}
			}
		}

		if(!is_null($page) ){
			$page = intval($page)?intval($page):1;
			$this->DB->limit($items_on_page, $items_on_page*($page-1));
		}
		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			foreach($results as $r){
				if(!empty($r['name_'.$lang_id])) $r['name'] = $r['name_'.$lang_id];
				if(!empty($r['annotation_'.$lang_id])) $r['annotation'] = $r['annotation_'.$lang_id];
				$data[] = $r;
			}
			return ($formated)?$this->format_news($data):$data;
		}
		return array();
	}

	/**
	 * Return number of news objects in data source
	 * 
	 * @param array $params sql criteria of query to data source
	 * @param array $filter_object_ids filters identifiers
	 * @return integer
	 */
	public function get_news_count($params=array(), $filter_object_ids=null){
		$this->DB->select("COUNT(*) AS cnt");
		$this->DB->from(NEWS_TABLE);

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

		$result = $this->DB->get()->result();
		if(!empty($result)){
			return intval($result[0]->cnt);
		}else{
			return 0;
		}
	}

	/**
	 * Save news object to data source
	 * 
	 * @param integer $id news identifier
	 * @param array $data news data
	 * @param string $file_name file name of news image upload 
	 * @param string $video_name file name of news video upload
	 * @return integer
	 */
	public function save_news($id, $data, $file_name="", $video_name=""){
		if (empty($id)){
			if(empty($data["date_add"])) {
				$data["date_add"] = date("Y-m-d H:i:s");
			}
			$this->DB->insert(NEWS_TABLE, $data);
			$id = $this->DB->insert_id();
		}else{
			$this->DB->where('id', $id);
			$this->DB->update(NEWS_TABLE, $data);
		}

		if(!empty($file_name) && !empty($id) && isset($_FILES[$file_name]) && is_array($_FILES[$file_name]) && is_uploaded_file($_FILES[$file_name]["tmp_name"])){
			$news_data = $this->get_news_by_id($id);
			$news_data = $this->format_single_news($news_data);

			$this->CI->load->model("Uploads_model");
			$img_return = $this->CI->Uploads_model->upload($this->upload_config_id, $news_data["prefix"], $file_name);

			if(empty($img_return["errors"])){
				$img_data["img"] = $img_return["file"];
				$this->save_news($id, $img_data);
			}
		}

		if(!empty($video_name) && !empty($id) && isset($_FILES[$video_name]) && is_array($_FILES[$video_name]) && is_uploaded_file($_FILES[$video_name]["tmp_name"])){
			if(!isset($news_data)){
				$news_data = $this->get_news_by_id($id);
				$news_data = $this->format_single_news($news_data);
			}
			$this->CI->load->model("Video_uploads_model");
			$video_data = array(
				"name" => $news_data["name"],
				"description" => $news_data["annotation"],
			);
			$video_return = $this->CI->Video_uploads_model->upload($this->video_config_id, $news_data["prefix"], $video_name, $id, $video_data);
		}
		return $id;
	}
	
	/**
	 * Upload logo from url
	 * 
	 * @param integer $news_id news identifier
	 * @param string $image_link image url
	 * @return void
	 */
	public function upload_logo_url($news_id, $image_link){
		$news_data = $this->get_news_by_id($news_id);
		$news_data = $this->format_single_news($news_data);
		$data = $this->CI->Uploads_model->upload_url($this->upload_config_id, $news_data["prefix"],  $image_link, 'news_icon');
		$this->save_news($news_id, array('img'=>$data["file"]), null, null);
	}

	/**
	 * Validate news object for saving to data source
	 * 
	 * @param integer $id news identifier
	 * @param array $data news data
	 * @param string $file_name file name of news image upload
	 * @param string $video_name file name of news video upload
	 * @return array
	 */
	public function validate_news($id, $data, $file_name="", $video_name=""){
		$return = array("errors"=> array(), "data" => array());

		if($id){
			$lang_ids = array_keys($this->CI->pg_language->languages);
			$news_data = $this->get_news_by_id($id, $lang_ids);
		}else{
			$news_data = array();
		}

		if(isset($data["id_lang"])){
			$return["data"]["id_lang"] = $news_data['id_lang'] = intval($data["id_lang"]);
		}

		/*if(isset($data["name"])){
			$return["data"]["name"] = strip_tags($data["name"]);

			if(empty($return["data"]["name"]) ){
				$return["errors"][] = l('error_name_incorrect', 'news');
			}
		}*/

		$default_lang_id = $news_data['id_lang'];
		if(isset($data['name_'.$default_lang_id])){
			$return['data']['name_'.$default_lang_id] = trim(strip_tags($data['name_'.$default_lang_id]));
			if(empty($return['data']['name_'.$default_lang_id])){
				$return['errors'][] = l('error_name_incorrect', 'news');
			}else{				
				foreach($this->CI->pg_language->languages as $lid=>$lang_data){
					if($lid == $default_lang_id) continue;
					if(!isset($data['name_'.$lid]) || empty($data['name_'.$lid])){
						$return['data']['name_'.$lid] = $return['data']['name_'.$default_lang_id];
					}else{
						$return['data']['name_'.$lid] = trim(strip_tags($data['name_'.$lid]));
						if(empty($return['data']['name_'.$lid])){
							$return['errors'][] = l('error_name_incorrect', 'news');
							break;
						}
					}
				}
			}
		}elseif(!$id){
			$return["errors"][] = l('error_name_incorrect', 'news');
		}


		/*if(isset($data["annotation"])){
			$return["data"]["annotation"] = strip_tags($data["annotation"]);
		}*/
		
		$default_lang_id = $news_data['id_lang'];
		if(isset($data['annotation_'.$default_lang_id])){
			$return['data']['annotation_'.$default_lang_id] = trim(strip_tags($data['annotation_'.$default_lang_id]));
			foreach($this->CI->pg_language->languages as $lid=>$lang_data){
				if($lid == $default_lang_id) continue;
				$return['data']['annotation_'.$lid] = trim(strip_tags($data['annotation_'.$lid]));
			}
		}

		/*if(isset($data["content"])){
			$return["data"]["content"] = $data["content"];
		}*/
		
		$default_lang_id = $news_data['id_lang'];
		if(isset($data['content_'.$default_lang_id])){
			$return['data']['content_'.$default_lang_id] = trim($data['content_'.$default_lang_id]);
			if(empty($return['data']['content_'.$default_lang_id])){
				$return['errors'][] = l('error_content_incorrect', 'news');
			}else{				
				foreach($this->CI->pg_language->languages as $lid=>$lang_data){
					if($lid == $default_lang_id) continue;
					if(!isset($data['content_'.$lid]) || empty($data['content_'.$lid])){
						$return['data']['content_'.$lid] = $return['data']['content_'.$default_lang_id];
					}else{
						$return['data']['content_'.$lid] = trim($data['content_'.$lid]);
						if(empty($return['data']['content_'.$lid])){
							$return['errors'][] = l('error_content_incorrect', 'news');
							break;
						}
					}
				}
			}
		}elseif(!$id){
			$return["errors"][] = l('error_content_incorrect', 'news');
		}

		if(isset($data["feed_id"])){
			$return["data"]["feed_id"] = intval($data["feed_id"]);
		}

		if(isset($data["feed_link"])){
			$return["data"]["feed_link"] = trim(strip_tags($data["feed_link"]));
		}

		if(isset($data["feed_unique_id"])){
			$return["data"]["feed_unique_id"] = trim(strip_tags($data["feed_unique_id"]));
			if(!empty($return["data"]["feed_unique_id"]) && empty($id)){
				$feed_params["where"]["feed_unique_id"] = $return["data"]["feed_unique_id"];
				$count = $this->get_news_count($feed_params);
				if($count > 0){
					$return["errors"][] = l('error_feed_news_exists', 'news');
				}
			}
		}

		if(isset($data["news_type"])){
			$return["data"]["news_type"] = trim($data["news_type"]);
		}

		if(isset($data["status"])){
			$return["data"]["status"] = intval($data["status"]);
		}

		if(isset($data["video"])){
			$return["data"]["video"] = strval($data["video"]);
		}

		if(isset($data["video_data"])){
			$return["data"]["video_data"] = $data["video_data"];
		}

		if(isset($data["gid"])){
			$temp_gid = $return["data"]["gid"] = strtolower(trim(strip_tags($data["gid"])));
			$return["data"]["gid"] = preg_replace("/[^a-z0-9_\-]+/i", '-', $return["data"]["gid"]);
			$return["data"]["gid"] = preg_replace("/[\-]{2,}/i", '-', $return["data"]["gid"]);

			if($return["data"]["gid"] == '-'){
				$return["data"]["gid"] = md5($temp_gid);
			}

			if(empty($return["data"]["gid"]) ){
				$return["errors"][] = l('error_gid_incorrect', 'news');
			}

			$params["where"]["id_lang"] = $return["data"]["id_lang"];
			$params["where"]["gid"] = $return["data"]["gid"];
			if($id) $params["where"]["id <>"] = $id;
			$count = $this->get_news_count($params);
			if($count > 0){
				$return["errors"][] = l('error_gid_already_exists', 'news');
			}
		}

		if(!empty($file_name) && isset($_FILES[$file_name]) && is_array($_FILES[$file_name]) && is_uploaded_file($_FILES[$file_name]["tmp_name"])){
			$this->CI->load->model("Uploads_model");
			$img_return = $this->CI->Uploads_model->validate_upload($this->upload_config_id, $file_name);
			if(!empty($img_return["error"])){
				$return["errors"][] = implode("<br>", $img_return["error"]);
			}
		}

		if(!empty($video_name) && isset($_FILES[$video_name]) && is_array($_FILES[$video_name]) && is_uploaded_file($_FILES[$video_name]["tmp_name"])){
			$this->CI->load->model("Video_uploads_model");
			$video_return = $this->CI->Video_uploads_model->validate_upload($this->video_config_id, $video_name);
			if(!empty($video_return["error"])){
				$return["errors"][] = implode("<br>", $video_return["error"]);
			}
		}
		return $return;
	}

	/**
	 * Remove news object by identifier
	 * 
	 * @param integer $id news identifier
	 * @return void
	 */
	public function delete_news($id){
		if(!empty($id)){
			$news_data = $this->get_news_by_id($id);

			$this->DB->where('id', $id);
			$this->DB->delete(NEWS_TABLE);

			if(!empty($news_data["img"])){
				$news_data = $this->format_single_news($news_data);
				$this->CI->load->model("Uploads_model");
				$this->CI->Uploads_model->delete_upload($this->upload_config_id, $news_data["prefix"], $news_data["img"]);
			}

			if(!empty($news_data["video"])){
				$news_data = $this->format_single_news($news_data);
				$this->CI->load->model("Video_uploads_model");
				$this->CI->Video_uploads_model->delete_upload($this->video_config_id, $news_data["prefix"], $news_data["video"], $news_data["video_data"]["data"]["upload_type"]);
			}
		}
		return;
	}

	/**
	 * Validate settings of news rss
	 * 
	 * @param array $data settings data
	 * @param array $file_name file name of rss logo upload
	 * @return array
	 */
	public function validate_rss_settings($data, $file_name){
		$return = array("errors"=> array(), "data" => array());

		if(isset($data["rss_use_feeds_news"])){
			$return["data"]["rss_use_feeds_news"] = intval($data["rss_use_feeds_news"]);
		}

		if(isset($data["rss_news_max_count"])){
			$return["data"]["rss_news_max_count"] = intval($data["rss_news_max_count"]);

			if(empty($return["data"]["rss_news_max_count"]) ){
				$return["errors"][] = l('error_sett_rss_news_count_incorrect', 'news');
			}
		}

		if(isset($data["userside_items_per_page"])){
			$return["data"]["userside_items_per_page"] = intval($data["userside_items_per_page"]);

			if(empty($return["data"]["userside_items_per_page"]) ){
				$return["errors"][] = l('error_sett_userside_page_incorrect', 'news');
			}
		}

		if(isset($data["userhelper_items_per_page"])){
			$return["data"]["userhelper_items_per_page"] = intval($data["userhelper_items_per_page"]);

			if(empty($return["data"]["userhelper_items_per_page"]) ){
				$return["errors"][] = l('error_sett_userhelper_page_incorrect', 'news');
			}
		}

		if(isset($data["rss_feed_channel_title"])){
			$return["data"]["rss_feed_channel_title"] = trim(strip_tags($data["rss_feed_channel_title"]));

			if(empty($return["data"]["rss_feed_channel_title"]) ){
				$return["errors"][] = l('error_sett_feed_channel_title_incorrect', 'news');
			}
		}

		if(isset($data["rss_feed_channel_description"])){
			$return["data"]["rss_feed_channel_description"] = trim(strip_tags($data["rss_feed_channel_description"]));

			if(empty($return["data"]["rss_feed_channel_description"]) ){
				$return["errors"][] = l('error_sett_feed_channel_description_incorrect', 'news');
			}
		}

		if(isset($data["rss_feed_image_title"])){
			$return["data"]["rss_feed_image_title"] = trim(strip_tags($data["rss_feed_image_title"]));

			if(empty($return["data"]["rss_feed_image_title"]) ){
				$return["errors"][] = l('error_sett_feed_image_title_incorrect', 'news');
			}
		}

		if(!empty($file_name) && isset($_FILES[$file_name]) && is_array($_FILES[$file_name]) && is_uploaded_file($_FILES[$file_name]["tmp_name"])){
			$this->CI->load->model("Uploads_model");
			$img_return = $this->CI->Uploads_model->validate_upload($this->rss_config_id, $file_name);
			if(!empty($img_return["error"])){
				$return["errors"][] = implode("<br>", $img_return["error"]);
			}
		}

		return $return;
	}

	/**
	 * Return settings of news rss as array
	 * 
	 * @return array
	 */
	public function get_rss_settings(){
		$data = array(
			"userside_items_per_page" => $this->CI->pg_module->get_module_config('news', 'userside_items_per_page'),
			"userhelper_items_per_page" => $this->CI->pg_module->get_module_config('news', 'userhelper_items_per_page'),
			"rss_feed_channel_title" => $this->CI->pg_module->get_module_config('news', 'rss_feed_channel_title'),
			"rss_feed_channel_description" => $this->CI->pg_module->get_module_config('news', 'rss_feed_channel_description'),
			"rss_feed_image_url" => $this->CI->pg_module->get_module_config('news', 'rss_feed_image_url'),
			"rss_feed_image_title" => $this->CI->pg_module->get_module_config('news', 'rss_feed_image_title'),
			"rss_use_feeds_news" => $this->CI->pg_module->get_module_config('news', 'rss_use_feeds_news'),
			"rss_news_max_count" => $this->CI->pg_module->get_module_config('news', 'rss_news_max_count'),
		);

		if($data["rss_feed_image_url"]){
			$this->CI->load->model('Uploads_model');
			$data["rss_feed_image_media"] = $this->CI->Uploads_model->format_upload($this->rss_config_id, "", $data["rss_feed_image_url"]);
		}

		return $data;
	}

	/**
	 * Save settings of news rss to data source
	 * 
	 * @param array $data settings data
	 * @param strign $file_name file name of rss logo upload
	 * @return void
	 */
	public function set_rss_settings($data, $file_name=''){
		foreach($data as $setting => $value){
			$this->CI->pg_module->set_module_config('news', $setting, $value);
		}

		if(!empty($file_name) && isset($_FILES[$file_name]) && is_array($_FILES[$file_name]) && is_uploaded_file($_FILES[$file_name]["tmp_name"])){
			$this->CI->load->model("Uploads_model");
			$img_return = $this->CI->Uploads_model->upload($this->rss_config_id, "", $file_name);

			if(empty($img_return["errors"])){
				$this->CI->pg_module->set_module_config('news', "rss_feed_image_url", $img_return["file"]);
			}
		}
		return;
	}


	////// seo
	
	/**
	 * Return module settings to rewrite seo urls
	 * 
	 * @param string $method method name
	 * @param integer $lang_id language identifier
	 * @return array
	 */
	public function get_seo_settings($method='', $lang_id=''){
		if(!empty($method)){
			return $this->_get_seo_settings($method, $lang_id);
		}else{
			$actions = array('index', 'view');
			$return = array();
			foreach($actions as $action){
				$return[$action] = $this->_get_seo_settings($action, $lang_id);
			}
			return $return;
		}
	}

	/**
	 * Return module settings to rewrite seo urls (internal)
	 * 
	 * @param string $method method name
	 * @param integer $lang_id language identifier
	 * @return array
	 */
	public function _get_seo_settings($method, $lang_id=''){
		if($method == "index"){
			return array(
				"templates" => array(),
				"url_vars" => array(),
				'url_postfix' => array(
					'page'=> array('page' => 'numeric'),
				),
				'optional' => array(),
			);
		}elseif($method == "view"){
			return array(
				"templates" => array('id', 'gid', 'name', 'annotation', 'created-date', 'feed_unique_id'),
				"url_vars" => array(
					"id" => array('id'=>'literal', "gid"=>'literal')
				),
				'url_postfix' => array(),
				'optional' => array(
					array('name' => 'literal', 'created-date' => 'literal'),
				),
			);
		}
	}

	/**
	 * Transform seo value form query string to method parameter
	 * 
	 * @param string $var_name_from variable name from url
	 * @param string $var_name_to variable name to method parameter
	 * @param string $value variable value
	 * @return mixed
	 */
	public function request_seo_rewrite($var_name_from, $var_name_to, $value){
		$user_data = array();

		if($var_name_from == $var_name_to){
			return $value;
		}

		if($var_name_from == "gid" && $var_name_to == "id"){
			$news_data = $this->get_news_by_gid($value);
			return $news_data["id"];
		}
		
		show_404();
	}

	/**
	 * Return data for generating xml sitemap
	 * 
	 * @return array
	 */
	public function get_sitemap_xml_urls(){
		$this->CI->load->helper('seo');
		
		$lang_canonical = true;
				
		if($this->CI->pg_module->is_module_installed('seo')){
			$lang_canonical = $this->CI->pg_module->get_module_config('seo', 'lang_canonical');
		}
		
		if($lang_canonical){
			$default_lang_id = $this->CI->pg_language->get_default_lang_id();
			$default_lang_code = $this->CI->pg_language->get_lang_code_by_id($default_lang_id);
			$langs[$default_lang_id] = $default_lang_code;
		}else{
			foreach($this->CI->pg_language->languages as $lang_id=>$lang_data){
				$langs[$lang_id] = $lang_data['code'];
			}
		}
		
		$return = array();
		
		$user_settings = $this->pg_seo->get_settings('user', 'news', 'index');
		if(!$user_settings['noindex']){	
			foreach($langs as $lang_id=>$lang_code){
				$return[] = array(
					"url" => rewrite_link('news', 'index', array(), false, $lang_code, $lang_canonical),
					"priority" => 0.5
				);
			}
		}
		
		$user_settings = $this->pg_seo->get_settings('user', 'news', 'view');
		if(!$user_settings['noindex']){	
			$criteria = array(
				'where'=> array(
					'status'=>1, 
					'date_add >=' => date("Y-m-d" ,time()-60*60*24*31),
				),
			);
		
			$order_array = array('date_add'=>'DESC');
		
			$result = $this->get_news_list(1, null, $order_array, $criteria);
			foreach($result as $news){
				foreach($langs as $lang_id=>$lang_code){
					$return[] = array(
						"url" => rewrite_link('news', 'view', $news, false, $lang_code, $lang_canonical),
						"priority" => 0.7,
					);
				}
			}
		}
		
		return $return;
	}

	/**
	 * Return data for generating site map
	 * 
	 * @return array
	 */
	public function get_sitemap_urls(){
		$this->CI->load->helper('seo');
		$auth = $this->CI->session->userdata("auth_type");

		$block[] = array(
			"name" => l('header_news', 'news'),
			"link" => rewrite_link('news', 'index'),
			"clickable" => true,
		);
		return $block;
	}


	/**
	 * Return available pages of banners locations as array
	 * 
	 * Callback method of banners module
	 * 
	 * @return array
	 */
	public function _banner_available_pages(){
		$return[] = array("link"=>"news/index", "name"=> l('seo_tags_index_header', 'news'));
		$return[] = array("link"=>"news/view", "name"=> l('seo_tags_view_header', 'news'));
		return $return;
	}

	/**
	 * Return last news to form subscription
	 * 
	 * Callback method of subscriptions module
	 * 
	 * @param integer $lang_id language identifier
	 * @return array
	 */
	public function get_last_news($lang_id){
		//$result = array('content' => 'test content test content test content test content with lang_id:'.$lang_id);
		$d = mktime();
		$last_week = mktime(date("H", $d), date("i", $d), date("s", $d), date("n", $d), date("j", $d) - 7, date("Y", $d));
		$attrs["where"]["id_lang"] = $lang_id;
		$attrs["where"]["status"] = "1";
		$attrs["where"]["date_add >"]  = date("Y-m-d H:i:s", $last_week);
		$attrs["where"]["set_to_subscribe"] = "0";
		$content = '';
		$last_news = $this->get_news_list(null, null, array('date_add' => "DESC"), $attrs);
		if ($last_news){
			foreach($last_news as $k => $item){
				$content .= $item['date_add']."\r\n".$item['annotation']."\r\n".l('link_view_more', 'news', $lang_id).' '.site_url().'news/view/'.$item['id']."\r\n"."\r\n";
			}
			//$this->update_news_subscription_status($attrs);
		}
		$result = array('content' => $content);
		return $result;
	}

	/**
	 * Update status of news subscription
	 * 
	 * @param array $params subscription sql criteria
	 * @return void
	 */
	public function update_news_subscription_status($params){
		$data = array('set_to_subscribe' => '1');

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
		$this->DB->update(NEWS_TABLE, $data);
	}

	/**
	 * Process events from video upload module
	 * 
	 * Callback method of video upload module
	 * 
	 * @param integer $id news object identifier
	 * @param string $status status of news video
	 * @param array $data video data
	 * @param array $errors video errors
	 * @return void
	 */
	public function video_callback($id, $status, $data, $errors){

		$news_data = $this->get_news_by_id($id);
		$news_data = $this->format_single_news($news_data);

		if(isset($data["video"])){
			$update["video"] = $data["video"];
		}
		if(isset($data["image"])){
			$update["video_image"] = $data["image"];
		}

		$update["video_data"] = $news_data["video_data"];

		if($status == 'start'){
			$update["video_data"] = array();
		}

		if(!isset($update["video_data"]["data"])){
			$update["video_data"]["data"] = array();
		}

		if(!empty($data)){
			$update["video_data"]["data"] = array_merge($update["video_data"]["data"], $data);
		}

		$update["video_data"]["status"] = $status;
		$update["video_data"]["errors"] = $errors;
		$update["video_data"] = serialize($update["video_data"]);
		$this->save_news($id, $update);
	}

	/**
	 * Update comments count
	 * 
	 * Callback method of comments module
	 * 
	 * @param integer $count comments count
	 * @param integer $id object identifier
	 * @return void
	 */
	public function comments_count_callback($count, $id = 0){
		if($id){
			$this->DB->where('id', $id);
		}
		$data['comments_count'] = $count;
		$this->DB->update(NEWS_TABLE, $data);
	}
	
	/**
	 * Generate comment text
	 * 
	 * Callback method of comments module
	 * 
	 * @param integer $id object identifier
	 * @return void
	 */
	public function comments_object_callback($id = 0){
		$return = array();
		$return["body"] = "<a href='".site_url()."admin/news/edit/".$id."'>".site_url()."admin/news/edit/".$id."</a>";
		$return["author"] =  "admin";
		return $return;
	}

	/**
	 * Return latest added news block
	 * 
	 * Callback method of dynamic blocks modules
	 * 
	 * @param array $params block parameters
	 * @param string $view view mode
	 * @param integer $width block size
	 * @return string
	 */
	public function _dynamic_block_get_news($params, $view, $width){
		$data['params'] = $params;
		$data['view'] = $view;
		$data['width'] = $width;
		$count = $params['count'] ? $params['count'] : 1;
		$attrs['where']['id_lang'] = $this->pg_language->current_lang_id;
		$attrs['where']['status'] = '1';
		$attrs['where']['set_to_subscribe'] = '0';
		$data['news'] = $this->get_news_list(1, $count, array('date_add' => 'DESC'), $attrs);
		$data['news_count'] = count($data['news']);
		$this->CI->template_lite->assign('dynamic_block_news_data', $data);
		return $this->CI->template_lite->fetch('dynamic_block_news', 'user', 'news');
	}
	
	/**
	 * Install content properties depended on language 
	 * 
	 * @param integer $lang_id language identifier
	 * @return void
	 */
	public function lang_dedicate_module_callback_add($lang_id=false){
		if(!$lang_id) return;
	
		$this->CI->load->dbforge();
		
		$fields = array();
		$fields['name_'.$lang_id] = array('type'=>'varchar(255)', 'null'=>FALSE);
		$this->CI->dbforge->add_column(NEWS_TABLE, $fields);
		
		$fields = array();
		$fields['annotation_'.$lang_id] = array('type'=>'text', 'null'=>TRUE);
		$this->CI->dbforge->add_column(NEWS_TABLE, $fields);
		
		$fields = array();
		$fields['content_'.$lang_id] = array('type'=>'text', 'null'=>TRUE);
		$this->CI->dbforge->add_column(NEWS_TABLE, $fields);
	}
	
	/**
	 * Uninstall content properties depended on language
	 * 
	 * @param integer $lang_id language identifier
	 * @return void
	 */
	public function lang_dedicate_module_callback_delete($lang_id=false){
		if(!$lang_id) return;
		
		$this->CI->load->dbforge();
		
		$fields_exists = $this->CI->db->list_fields(NEWS_TABLE);
		
		$fields = array('name_'.$lang_id, 'annotation_'.$lang_id,  'content_'.$lang_id);
		foreach($fields as $field_name){
			if(!in_array($field_name, $fields_exists)) continue;
			$this->CI->dbforge->drop_column(NEWS_TABLE, $field_name);
		}
	}
}
