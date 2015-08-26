<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Yandexmapsv2_model extends Model
{
	private $CI;
	/**
	 * Constructor
	 *
	 * @return
	 */
	function __construct()
	{
		parent::Model();
		$this->CI = & get_instance();
	}
	
	public function create_html($key, $settings, $view_settings, $markers=array(), $map_id=false){
		$this->CI->template_lite->assign('map_reg_key', $key);
		$this->CI->template_lite->assign('settings', $settings);
		$this->CI->template_lite->assign('view_settings', $view_settings);
		$this->CI->template_lite->assign('markers', $markers);
		$this->CI->template_lite->assign('map_id', $map_id);
		$this->CI->template_lite->assign('rand', rand(100000, 999999));
		return $this->CI->template_lite->fetch('yandexmapsv2_html', 'user', 'geomap');
	}
	
	public function update_html($map_id, $markers=array()){
		$this->CI->template_lite->assign('map_id', $map_id);
		$this->CI->template_lite->assign('markers', $markers);
		return $this->CI->template_lite->fetch('yandexmapsv2_update', 'user', 'geomap');
	}
	
	public function create_geocoder($key){
		return $this->CI->template_lite->fetch('yandexmapsv2_geocoder', 'user', 'geomap');
	}
}
