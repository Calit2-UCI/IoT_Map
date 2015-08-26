<?php  
/**
* Video uploads youtube model
* 
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Video_uploads_youtube_model extends Model
{
	private $CI;
	
	private $settings;
	
	private $defaults = array(
		"name" => 'default name',
		"description" => 'default description',
		"tags" => 'site, files',
	);

	
	function __construct(){
		parent::Model();
		$this->CI = & get_instance();
		
		$this->read_settings();
	}
	
	public function read_settings(){
		$this->settings = $this->CI->pg_module->return_module_all_config('video_uploads');
	}
	
	public function get_settings($name=''){
		return ($name != '')?$this->settings[$name]:$this->settings;
	}
	
	public function write_settings(){
		$this->CI->pg_module->set_module_all_config('video_uploads', $this->settings);
	}
	
	public function set_settings($name, $value){
		$this->settings[$name] = $value;
	}
	
	public function reculc_permission_settings(){
		if(!$this->settings['youtube_converting_source']){
			$this->settings['youtube_converting_source'] = 'pg core video';
		}
		if(!$this->settings['youtube_converting_login'] || !$this->settings['youtube_converting_password'] || !$this->settings['youtube_converting_developer_key'] ){
			$this->settings['use_youtube_converting'] = false;
		}else{
			$youtube_auth = $this->youtube_auth();
			if(!empty($youtube_auth['error']) || empty($youtube_auth['yt'])){
				$this->settings['use_youtube_converting'] = false;
			}else{
				$this->settings['use_youtube_converting'] = true;
			}
		}
		return $this->settings;
	}
	
	////// main youtube functions
	public function youtube_auth(){
		$return = array("yt"=> '', 'error'=> '');
		if(!$this->settings['youtube_converting_login'] || !$this->settings['youtube_converting_password'] || !$this->settings['youtube_converting_developer_key'] ){
			$return['error'][] = l('error_empty_youtube_settings', 'video_uploads');
			return $return;
		}
		
		ini_set("include_path", "." . PATH_SEPARATOR . APPPATH."/libraries/Zend" . PATH_SEPARATOR . APPPATH."/libraries");
		require_once 'Zend/Loader.php'; 
		Zend_Loader::loadClass('Zend_Gdata_YouTube');
		Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
		
		try {
			$authenticationURL= 'https://www.google.com/youtube/accounts/ClientLogin';
			$httpClient = Zend_Gdata_ClientLogin::getHttpClient(
				$username = $this->settings['youtube_converting_login'],
				$password = $this->settings['youtube_converting_password'],
				$service = 'youtube',
				$client = null,
				$source = $this->settings['youtube_converting_source'], // a short string identifying your application
				$loginToken = null,
				$loginCaptcha = null,
				$authenticationURL);
	
			$myDeveloperKey = $this->settings['youtube_converting_developer_key'];
			$httpClient->setHeaders('X-GData-Key', "key=${myDeveloperKey}");			
			$return['yt'] = new Zend_Gdata_YouTube($httpClient);	
		} catch (Zend_Gdata_App_Exception $e) {
			$return['error'] = $e->getMessage();
		}
		return $return;
	}
	
	private function youtube_upload_video($file_name, $file_path, $file_data){
		$return = array("errors"=>array(), "data"=>array());
		$yt_data = $this->youtube_auth();	
		$yt = $yt_data['yt'];

		Zend_Loader::loadClass('Zend_Gdata_YouTube_VideoEntry');
		$myVideoEntry = new Zend_Gdata_YouTube_VideoEntry();
		
		$filesource = $yt->newMediaFileSource($file_path . $file_name);
		$filesource->setContentType($file_data["type"]);

		$filesource->setSlug($file_name);
		
		$myVideoEntry->setMediaSource($filesource);
		
		$mediaGroup = $yt->newMediaGroup();
		$mediaGroup->title = $yt->newMediaTitle()->setText( $file_data["name"] ? $file_data["name"] : $this->defaults['name'] );
		$mediaGroup->description = $yt->newMediaDescription()->setText( $file_data["description"] ? $file_data["description"] : $this->defaults['description'] );
		
		// the category must be a valid YouTube category
		// optionally set some developer tags (see Searching by Developer Tags for more details)
		$mediaGroup->category = array(  
		  $yt->newMediaCategory()->setText('People')->setScheme('http://gdata.youtube.com/schemas/2007/categories.cat')
		);
		
		// set keywords
		$mediaGroup->keywords = $yt->newMediaKeywords()->setText($file_data["tags"] ? $file_data["tags"] : $this->defaults['tags']);
		$myVideoEntry->mediaGroup = $mediaGroup;
		
		if(strval($file_data["lat"]) || strval($file_data["lon"])){
			$yt->registerPackage('Zend_Gdata_Geo');
			$yt->registerPackage('Zend_Gdata_Geo_Extension');
			$where = $yt->newGeoRssWhere();
			$position = $yt->newGmlPos($file_data["lat"].' '.$file_data["lon"]);
			$where->point = $yt->newGmlPoint($position);
			$myVideoEntry->setWhere($where);
		}

		$uploadUrl = 'http://uploads.gdata.youtube.com/feeds/users/default/uploads';
		
		try {
			$newEntry = $yt->insertEntry($myVideoEntry, $uploadUrl, 'Zend_Gdata_YouTube_VideoEntry');
			$video_id = $newEntry->getVideoId();
			if($video_id){
				$return["data"]["video"] = $video_id;
			}else{
				$return["errors"][] = 'upload error';
			}
		} catch (Zend_Gdata_App_Exception $e) {
			$return["errors"][] = $e->getMessage();
		}
		return $return;
	}
	
	public function youtube_get_info($video_id){
		$return = array("errors"=>array(), "data"=>array());

		$yt_data = $this->youtube_auth();	
		$yt = $yt_data['yt'];
		try {
			$videoEntry = $yt->getVideoEntry($video_id);
			$return["data"] = array(
				"title" => $videoEntry->getVideoTitle(),
				"description" => $videoEntry->getVideoDescription(),
				"category" => $videoEntry->getVideoCategory(),
				"tags" => implode(", ", $videoEntry->getVideoTags()),
				"watch_page" => $videoEntry->getVideoWatchPageUrl(),
				"flash_player_url" => $videoEntry->getFlashPlayerUrl(),
				"duration" => $videoEntry->getVideoDuration(),
				"view_count" => $videoEntry->getVideoViewCount(),
				"rating" => $videoEntry->getVideoRatingInfo(),
				"geo" => $videoEntry->getVideoGeoLocation(),
			);
			
			foreach ($videoEntry->mediaGroup->content as $content) {
				if ($content->type === "video/3gpp") {
					$return["data"]["mobile_rtsp_link"][] = $content->url;
				}
			}
	  
			$videoThumbnails = $videoEntry->getVideoThumbnails();

			foreach($videoThumbnails as $videoThumbnail) {
				$videoThumbnail = (object) $videoThumbnail;
				$return["data"]["thumbs"][] = array(
					"time" => $videoThumbnail->time,
					"url" => $videoThumbnail->url,
					"width" => $videoThumbnail->width,
					"height" => $videoThumbnail->height,
				);
			}		
		} catch (Zend_Gdata_App_Exception $e) {
			$return["errors"][] = $e->getMessage();
		}
		return $return;
	}
	
	///// methods for proccess model
	public function processing_method($file_name, $file_path, $file_data, $config){
		return $this->youtube_upload_video($file_name, $file_path, $file_data);
	}
	
	public function images_method($file_name, $file_path){
		return $this->waiting_method($file_name, $file_path);
	}
	
	public function waiting_method($file_name, $file_path){
		$return = array("errors"=>array(), "data"=>array());
		
		$youtube_data = $this->youtube_get_info($file_name);
		if(!empty($youtube_data["errors"])){
			$return['errors'] = $youtube_data["errors"];
			return $return;
		}
		
		if(!empty($youtube_data["data"]["thumbs"][0]) && $youtube_data["data"]["thumbs"][0]["time"] != '00:00:00'){
			$return["data"]['image'] = $this->save_ext_image($file_path, $youtube_data["data"]["thumbs"][0]["url"]);
		}
		
		return $return;
	}
	
	public function delete_method($video_id){
		$return = array("errors"=>array(), "data"=>array());
		$yt_data = $this->youtube_auth();	
		$yt = $yt_data['yt'];
		try {
			$videoEntry = $yt->getVideoEntry($video_id, null, true);
			$yt->delete($videoEntry);		

		} catch (Zend_Gdata_App_Exception $e) {
			$return["errors"][] = $e->getMessage();
		}
		return $return;
	}
	
	private function save_ext_image($file_path, $url){
		$path_parts = pathinfo($url);
		$file_name = $path_parts["basename"];
		$content = file_get_contents($url);
		$f = fopen($file_path.$file_name, 'w');
		if($f){
			fwrite($f, $content);
			fclose($f);
		}
		return $file_name;
	}
	
	public function get_default_embed($id, $width=480, $height=360){
		return '<iframe width="'.$width.'" height="'.$height.'" src="http://www.youtube.com/embed/'.$id.'" frameborder="0" allowfullscreen></iframe>';
	}
}
