<?php
/**
* Social networking google widgets model
* 
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Google_widgets_model extends Model {

	public $CI;
	public $widget_types = array(
		'like',
	);
	public $head_loaded = false;

	function __construct() {
		parent::__construct();
		$this->CI = & get_instance();
	}

	public function get_header($service_data = array(), $locale = '', $types = array()) {
		$header = '';
		$lang = $this->CI->pg_language->get_lang_by_id($this->CI->pg_language->current_lang_id);
		$lang_code = isset($lang['code']) ? $lang['code'] : false;
		if (in_array('like', $types))
			$header = $lang_code ? '<script type="text/javascript">window.___gcfg = {lang: \'' . $lang_code . '\'};(function() { var po = document.createElement(\'script\'); po.type = \'text/javascript\'; po.async = true; po.src = \'https://apis.google.com/js/plusone.js\'; var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(po, s); })();</script>' : '';
		$this->head_loaded = $header ? true : false;
		return $header;
	}

	public function get_like() {
		return $this->head_loaded ? '<g:plusone></g:plusone>' : '';
	}

}
