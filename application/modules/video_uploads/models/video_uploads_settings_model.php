<?php  
/**
* Video uploads settings model
* 
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Video_uploads_settings_model extends Model
{
	private $CI;
	
	private $settings;

	private $required_codecs = array(
		"mencoder" => array(
			'video' => array('lavc'),
			'audio' => array('mp3lame'),
		),
		"ffmpeg" => array(
			'video' => array('libxvid'),
			'audio' => array('libmp3lame'),
		),
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
	
	public function reculc_settings(){
		$this->get_ffmpeg_path();
		$this->get_mencoder_path();
		$this->get_flvtool2_path();
		$this->get_mplayer_path();
		
		$this->reculc_permission_settings();
		
		return $this->settings;
	}
	
	public function reculc_permission_settings(){

		if(!$this->is_shell_exec_exist()){
			$this->settings["use_local_converting_video"] = false;
			$this->settings["use_local_converting_meta_data"] = false;
			$this->settings["use_local_converting_thumbs"] = false;
		}else{
			$is_ffmpeg = false;
			if($this->settings["ffmpeg_path"] && $this->get_ffmpeg_version() != ''){
				$is_ffmpeg = true;
			}
			
			$is_mencoder = false;
			if($this->settings["mencoder_path"] && $this->get_mencoder_version() != ''){
				$is_mencoder = true;
			}
			
			$is_flvtool = false;
			if($this->settings["flvtool2_path"] && $this->get_flvtool2_version() != ''){
				$is_flvtool = true;
			}
			
			$is_mplayer = false;
			if($this->settings["mplayer_path"] && $this->get_mplayer_version() != ''){
				$is_mplayer = true;
			}
			
			if($is_ffmpeg || $is_mencoder){
				$this->settings["use_local_converting_video"] = true;
				$this->settings["local_converting_video_type"] = $is_mencoder?'mencoder':'ffmpeg';
			}else{
				$this->settings["use_local_converting_video"] = false;
				$this->settings["local_converting_video_type"] = 'ffmpeg';
			}
			
			if($is_flvtool){
				$this->settings["use_local_converting_meta_data"] = true;
			}else{
				$this->settings["use_local_converting_meta_data"] = false;
			}
			
			if( $is_ffmpeg || ($is_mencoder && $is_mplayer) ){
				$this->settings["use_local_converting_thumbs"] = true;
			}else{
				$this->settings["use_local_converting_thumbs"] = false;
			}
			
		}
		return $this->settings;
	}
	
	
	public function is_shell_exec_exist(){
		if(function_exists('shell_exec') && is_callable('shell_exec')){
			$this->settings["use_shell_exec"] = true;
		}else{
			$this->settings["use_shell_exec"] = false;
		}
		return $this->settings["use_shell_exec"];
	}
	
	public function get_used_system(){
		$this->settings["used_system"] = PHP_OS;
		if(strpos(PHP_OS, 'win') !== false){
			$this->settings["used_system_type"] = 'no-unix';
		}else{
			$this->settings["used_system_type"] = 'unix';
		}
		return $this->settings["used_system_type"];
	}
	
	public function get_ffmpeg_path(){
		if(!isset($this->settings["ffmpeg_path"])) $this->settings["ffmpeg_path"] = '';
		
		if( $this->get_used_system() == 'unix' && $this->is_shell_exec_exist() ){
			$this->settings["ffmpeg_path"] = $this->_get_binary_path('ffmpeg');
		}
		
		return $this->settings["ffmpeg_path"];
	}

	public function get_mencoder_path(){
		if(!isset($this->settings["mencoder_path"])) $this->settings["mencoder_path"] = '';
		
		if( $this->get_used_system() == 'unix' && $this->is_shell_exec_exist() ){
			$this->settings["mencoder_path"] = $this->_get_binary_path('mencoder');
		}
		
		return $this->settings["mencoder_path"];
	}

	public function get_flvtool2_path(){
		if(!isset($this->settings["flvtool2_path"])) $this->settings["flvtool2_path"] = '';
		
		if( $this->get_used_system() == 'unix' && $this->is_shell_exec_exist() ){
			$this->settings["flvtool2_path"] = $this->_get_binary_path('flvtool2');
		}
		
		return $this->settings["flvtool2_path"];
	}

	public function get_mplayer_path(){
		if(!isset($this->settings["mplayer_path"])) $this->settings["mplayer_path"] = '';
		
		if( $this->get_used_system() == 'unix' && $this->is_shell_exec_exist() ){
			$this->settings["mplayer_path"] = $this->_get_binary_path('mplayer');
		}
		
		return $this->settings["mplayer_path"];
	}
	
	private function _get_binary_path($prog_name){
		$cmd = $this->_get_path_cmd();
		$path = shell_exec($cmd."which ".$prog_name);
		$path = preg_replace("/[\n\t\s]+/", "", $path);
		return $path;
	}

	private function _get_path_cmd(){
		$path = shell_exec('echo $PATH');
		$path_arr = explode(":", $path);
		$sub_path = "";
		if(is_array($path_arr) && !in_array("/usr/bin", $path_arr)){
			$sub_path .= ":/usr/bin";
		}
		if(is_array($path_arr) && !in_array("/usr/local/bin", $path_arr)){
			$sub_path .= ":/usr/local/bin";
		}
		if(is_array($path_arr) && !in_array("/usr/local/lib", $path_arr)){
			$sub_path .= ":/usr/local/lib";
		}
		if(strlen($sub_path)){
			$cmd = 'PATH=$PATH'.$sub_path.'; ';
		}else{
			$cmd = "";
		}
		return $cmd;
	}	
	
	//// get info methods
	public function get_ffmpeg_version(){
		$ffmpeg_path = $this->settings["ffmpeg_path"];
		$return = shell_exec($ffmpeg_path." -version");
		if(strlen($return)){
			$return_arr = preg_split("/\n/", $return);
			return $return_arr[0];
		}else{
			return "";
		}
	}

	public function get_mencoder_version(){
		$mencoder_path = $this->settings["mencoder_path"];
		$return = shell_exec($mencoder_path);
		if(strlen($return)){
			$return_arr = preg_split("/\n/", $return);
			return $return_arr[0];
		}else{
			return "";
		}
	}

	public function get_flvtool2_version(){
		$flvtool2_path = $this->settings["flvtool2_path"];
		$return = shell_exec($flvtool2_path);
		if(strlen($return)){
			$return_arr = preg_split("/\n/", $return);
			return $return_arr[0];
		}else{
			return "";
		}
	}

	public function get_mplayer_version(){
		$mplayer_path = $this->settings["mplayer_path"];
		$return = shell_exec($mplayer_path);
		if(strlen($return)){
			$return_arr = preg_split("/\n/", $return);
			return $return_arr[0];
		}else{
			return "";
		}
	}

	public function get_ffmpeg_codecs(){
		$ffmpeg_path = $this->settings["ffmpeg_path"];
		$video = $audio = $return = array();

		$video_data = shell_exec($ffmpeg_path.' -codecs | grep .EV &>&1');
		//// parse video_data
		$video_strings = preg_split("/\n/", $video_data);
		foreach($video_strings as $str){
			$format = trim(substr($str, 0, 8));
			$codec_string = trim(substr($str, 8));
			if(!empty($codec_string)){
				if(preg_match("/^([a-z0-9_]+\s+)(.*)/i", $codec_string, $codec_array)){
					$codec_name = trim($codec_array[1]);
					$codec_description = trim($codec_array[2]);
					$video[$codec_name] = array(
						"format" => $format,
						"codec_name" => $codec_name,
						"codec_description" => $codec_description,
					);
				}
			}
		}
		$return["video"] = $video;
		$return["video"] = $video;
		foreach($this->required_codecs['ffmpeg']["video"] as $video_required){
			if(isset($return["video"][$video_required])){
				$return["video"][$video_required]["installed"] = true;
				$return["video_required"][$video_required] = $return["video"][$video_required];
			}else{
				$return["video_required"][$video_required]["installed"] = false;
			}
		}


		$audio_data = shell_exec($ffmpeg_path.' -codecs | grep .EA');
		//// parse audio_data
		$audio_strings = preg_split("/\n/", $audio_data);
		foreach($audio_strings as $str){
			$format = trim(substr($str, 0, 8));
			$codec_string = trim(substr($str, 8));
			if(!empty($codec_string)){
				if(preg_match("/^([a-z0-9_]+\s+)(.*)/i", $codec_string, $codec_array)){
					$codec_name = trim($codec_array[1]);
					$codec_description = trim($codec_array[2]);
					$audio[$codec_name] = array(
						"format" => $format,
						"codec_name" => $codec_name,
						"codec_description" => $codec_description,
					);
				}
			}
		}
		$return["audio"] = $audio;
		foreach($this->required_codecs['ffmpeg']["audio"] as $audio_required){
			if(isset($return["audio"][$audio_required])){
				$return["audio"][$audio_required]["installed"] = true;
				$return["audio_required"][$audio_required] = $return["audio"][$audio_required];
			}else{
				$return["audio_required"][$audio_required]["installed"] = false;
			}
		}

		return $return;
	}
	
	public function get_mencoder_codecs(){
		$mencoder_path = $this->settings["mencoder_path"];
		$video = $audio = $return = array();

		$video_data = shell_exec($mencoder_path.' -ovc help');
		//// parse video_data
		$video_strings = preg_split("/\n/", $video_data);
		foreach($video_strings as $str){
			if(preg_match('/^\s*([a-z0-9]+)\s*-(.*)/i', $str, $matches)){
				$codec_name = trim($matches[1]);
				$video[$codec_name] = array(
					"codec_name" => $codec_name,
					"codec_description" => trim($matches[2]),
				);
			}
		}
		$return["video"] = $video;
		foreach($this->required_codecs['mencoder']["video"] as $video_required){
			if(isset($return["video"][$video_required])){
				$return["video"][$video_required]["installed"] = true;
				$return["video_required"][$video_required] = $return["video"][$video_required];
			}else{
				$return["video_required"][$video_required]["installed"] = false;
			}
		}

		$audio_data = shell_exec($mencoder_path.' -oac help');
		//// parse video_data
		$audio_strings = preg_split("/\n/", $audio_data);
		foreach($audio_strings as $str){
			if(preg_match('/^\s*([a-z0-9]+)\s*-(.*)$/i', $str, $matches)){
				$codec_name = trim($matches[1]);
				$audio[$codec_name] = array(
					"codec_name" => $codec_name,
					"codec_description" => trim($matches[2]),
				);
			}
		}
		$return["audio"] = $audio;

		foreach($this->required_codecs['mencoder']["audio"] as $audio_required){
			if(isset($return["audio"][$audio_required])){
				$return["audio"][$audio_required]["installed"] = true;
				$return["audio_required"][$audio_required] = $return["audio"][$audio_required];
			}else{
				$return["audio_required"][$audio_required]["installed"] = false;
			}
		}


		return $return;
	}
	
}