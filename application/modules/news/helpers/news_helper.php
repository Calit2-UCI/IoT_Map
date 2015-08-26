<?php 

/**
 * News module
 *
 * @package 	PG_Dating
 * @copyright 	Copyright (c) 2000-2014 PilotGroup.NET Powered by PG Dating Pro
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * News management
 * 
 * @package 	PG_Dating
 * @subpackage 	News
 * @category	helpers
 * @copyright 	Copyright (c) 2000-2014 PilotGroup.NET Powered by PG Dating Pro
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */

if ( ! function_exists('news_last_added')) {
	/**
	 * Last added news block
	 * 
	 * @param integer $count max news count
	 * @param integer $width block width
	 * @return string
	 */
	function news_block($count, $width=100){
		$CI = & get_instance();
		$CI->load->model('News_model');
		
		if(func_num_args() == 1 && is_array($count)){
			$params = $count;
			$count = isset($params['count']) ? $params['count'] : 8;
			$width = isset($params['width']) ? $params['width'] : 100;
		}
		
		$count = intval($count);
		if(!$count) $count = 8;
		
		$attrs = array("status"=>1);
		$news = $CI->News_model->get_news_list(1, $count, array("id" => "DESC"), $attrs);
		
		if(empty($news)) return '';
		
		$CI->template_lite->assign("news", $news);
		
		$CI->template_lite->assign("block_width", $width);

		return $CI->template_lite->fetch("helper_news_block", "user", "news");
	}
}
