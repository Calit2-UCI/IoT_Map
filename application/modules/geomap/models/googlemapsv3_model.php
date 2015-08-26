<?php 
/**
* Google maps driver model
* 
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Googlemapsv3_model extends Model
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
		$amenities = $this->pg_language->ds->get_reference(
			'geomap', 
			'amenities_googlemapsv3', 
			$this->pg_language->current_lang_id
		);
		$settings['amenities_names'] = $amenities['option'];
		$this->CI->template_lite->assign('map_reg_key', $key);
		$this->CI->template_lite->assign('settings', $settings);
		$this->CI->template_lite->assign('view_settings', $view_settings);
		$this->CI->template_lite->assign('markers', $markers);
		$this->CI->template_lite->assign('map_id', $map_id);
		$this->CI->template_lite->assign('rand', rand(100000, 999999));
		return $this->CI->template_lite->fetch('googlemapsv3_html', 'user', 'geomap');
	}
	
	public function update_html($map_id, $markers=array(), $settings){
		$this->CI->template_lite->assign('map_id', $map_id);
		$this->CI->template_lite->assign('markers', $markers);
		$this->CI->template_lite->assign('view_settings', $settings);
		return $this->CI->template_lite->fetch('googlemapsv3_update', 'user', 'geomap');
	}
	
	public function create_geocoder($key){
		return $this->CI->template_lite->fetch('googlemapsv3_geocoder', 'user', 'geomap');
	}
}
