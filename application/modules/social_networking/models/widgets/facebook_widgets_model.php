<?php
/**
* Social networking facebook widgets model
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

class Facebook_widgets_model extends Model {

	public $CI;
	public $widget_types = array(
		'comments',
		'like',
		'share',
	);
	public $url;
	public $head_loaded = false;

	function __construct() {
		parent::__construct();
		$this->CI = & get_instance();
		$this->url = ($_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://';
		$this->url .= ($_SERVER['SERVER_PORT'] != '80') ? $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'] : $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
	}

	public function get_header($service_data = array(), $locale = 'en_US', $types = array()) {
		$header = '';
		$appid = isset($service_data['app_key']) ? $service_data['app_key'] : false;
		$header = $appid ? ('
			<meta property="fb:app_id" content="'.$appid.'"/>
			<div id="fb-root"></div>
			<script>(function(d, s, id) {
				var js, fjs = d.getElementsByTagName(s)[0];
				if (d.getElementById(id)) return;
				js = d.createElement(s); js.id = id;
				js.src = "//connect.facebook.net/' . $locale . '/sdk.js#xfbml=1&appId=' . $appid . '&version=v2.0";
				fjs.parentNode.insertBefore(js, fjs);
			}(document, \'script\', \'facebook-jssdk\'));</script>'
		) : '';
		$this->head_loaded = $header ? true : false;
		return $header;
	}

	public function get_like() {
		if ($this->head_loaded) {
			$url = ($_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://';
			$url .= ($_SERVER['SERVER_PORT'] != '80') ? $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'] : $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
			return '<div class="fb-like" data-href="' . $url . '" data-send="false" data-layout="button_count" data-width="100" data-show-faces="true" data-font="segoe ui"></div>';
		} else
			return '';
	}

	public function get_share() {
		return $this->head_loaded ? '<div class="fb-send" data-href="' . $this->url . '"></div>' : '';
	}

	public function get_comments() {
		return $this->head_loaded ? '<div class="fb-comments" data-href="' . $this->url . '" data-num-posts="2" data-width="470"></div>' : '';
	}

}
