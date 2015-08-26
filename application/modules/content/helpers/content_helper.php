<?php

/**
 * Content module
 *
 * @package 	PG_Dating
 * @copyright 	Copyright (c) 2000-2014 PilotGroup.NET Powered by PG Dating Pro
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */

/**
 * Content management
 * 
 * @package 	PG_Dating
 * @subpackage 	Content
 * @category	helpers
 * @copyright 	Copyright (c) 2000-2014 PilotGroup.NET Powered by PG Dating Pro
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */

if ( ! function_exists('get_content_tree'))
{
	/**
	 * Get content pages tree
	 * 
	 * @param integer $parent_id parent page identifier
	 * @return string
	 */
	function get_content_tree($parent_id=0){
		$CI = & get_instance();
		$CI->load->model('Content_model');
		$lang_id = $CI->pg_language->current_lang_id;

		if($parent_id){
			$params["where"]["parent_id"] = $parent_id;
			$sub_count = $CI->Content_model->get_active_pages_count($params);
			if(empty($sub_count)){
				$CI->Content_model->set_page_active($parent_id);
				$parent_data = $CI->Content_model->get_page_by_id($parent_id);
				$parent_id = isset($parent_data["parent_id"])?$parent_data["parent_id"]:0;
			}
		}

		$pages = $CI->Content_model->get_active_pages_list($lang_id, $parent_id);

		$CI->template_lite->assign("content_tree", $pages);
		$html = $CI->template_lite->fetch("tree", 'user', 'content');
		echo $html;
	}
}

if ( ! function_exists('get_content_page'))
{
	/**
	 * Get content page
	 * 
	 * @param string $gid page guid
	 * @return string
	 */
	function get_content_page($gid){
		$CI = & get_instance();
		$CI->load->model('Content_model');

		$page_data = $CI->Content_model->get_page_by_gid($gid);
		$CI->template_lite->assign("page", $page_data);
		$html = $CI->template_lite->fetch("show_block", 'user', 'content');
		echo $html;
	}
}

if ( ! function_exists('get_content_promo'))
{
	/**
	 * Get promo content
	 * 
	 * @param string $view promo view
	 * @return string
	 */
	function get_content_promo($view='default'){
		$CI = & get_instance();
		$CI->load->model('content/models/Content_promo_model');

		$lang_id = $CI->pg_language->current_lang_id;
		
		$CI->Content_promo_model->set_format_settings('get_output', true);
		$promo_data = $CI->Content_promo_model->get_promo($lang_id);
		$CI->Content_promo_model->set_format_settings('get_output', false);

		$CI->template_lite->assign("promo", $promo_data);
		$CI->template_lite->assign("view", $view);
		$html = $CI->template_lite->fetch("show_promo_block", 'user', 'content');
		return $html;
	}
}

if ( ! function_exists('content_info_pages'))
{
	/**
	 * Info pages widget
	 * 
	 * @param string $keyword page guid
	 * @param string $view widget view
	 * @param integer $width block size
	 * @return string
	 */
	function content_info_pages($keyword='', $view='default', $width=100){
		$CI = & get_instance();
		$CI->load->model('Content_model');
		
		if(func_num_args() == 1 && is_array($keyword)){
			$params = $keyword;
			$keyword = isset($params['keyword']) ? $params['keyword'] : '';
			$view = isset($params['view']) ? $params['view'] : 'default';
			$width = isset($params['width']) ? $params['width'] : 100;
		}
	
		$lang_id = $CI->pg_language->current_lang_id;
		
		$parent_id = 0;
		
		if($keyword){
			$section = $CI->Content_model->get_page_by_gid($keyword);
			if($section){
				$parent_id = $section["id"];
				$CI->template_lite->assign("section", $section);
			}
		}

		$params = array('where_sql'=>array('(parent_id="'.$parent_id.'")'));
		$pages = $CI->Content_model->get_active_pages_list($lang_id, $parent_id, $params);
		if(empty($pages)) return '';
		
		foreach($pages as $i => $page){
			$page = $CI->Content_model->get_page_by_id($page['id']);
			$pages[$i]['content'] = strip_tags($page['content']);
		}
		
		$CI->template_lite->assign("pages", $pages);
	
		$CI->template_lite->assign('block_width', $width);
	
		return $CI->template_lite->fetch("helper_info_pages", "user", "content");
	}
}

if ( ! function_exists('content_info_page'))
{
	/**
	 * Info page widget
	 * 
	 * @param string $keyword page guid
	 * @param string $view widget view
	 * @param integer $width block size
	 * @return string
	 */
	function content_info_page($keyword='', $view='default', $width=100){
		$CI = & get_instance();
		$CI->load->model('Content_model');
		
		if(func_num_args() == 1 && is_array($keyword)){
			$params = $keyword;
			$keyword = isset($params['keyword']) ? $params['keyword'] : '';
			$view = isset($params['view']) ? $params['view'] : 'default';
			$width = isset($params['width']) ? $params['width'] : 100;
		}
		
		if(!$keyword) return '';
		
		$info_page = $CI->Content_model->get_page_by_gid($keyword);
		if(empty($info_page)) return '';
		
		$block_data['data'] = $info_page; 
		$CI->template_lite->assign("info_page_data", $block_data);
	
		$CI->template_lite->assign('block_width', $width);
	
		return $CI->template_lite->fetch("helper_info_page", "user", "content");
	}
}

if ( ! function_exists('get_page_link'))
{
	/**
	 * Return link to page
	 * 
	 * @param string $page_gid page guid
	 * @param integer $lang_id language identifier
	 * @return string
	 */
	function get_page_link($page_gid, $lang_id=null){
		if(func_num_args() == 1 && is_array($page_gid)){
			$params = $page_gid;
			$page_gid = isset($params['page_gid']) ? $params['page_gid'] : '';
			$lang_id = isset($params['lang_id']) ? $params['lang_id'] : null;
		}
		
		$CI = & get_instance();
		$CI->load->model('Content_model');
		
		if(empty($lang_id)) $lang_id = $CI->pg_language->current_lang_id;
	
		$page_data = $CI->Content_model->get_page_by_gid($page_gid, $lang_id);
		if(empty($page_data)) return '';
		
		$CI->load->helper('seo');
		return rewrite_link('content', 'view', $page_data);
	}
}
