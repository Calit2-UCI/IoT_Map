<?php

/**
* Show banner place
*
* @package PG_Core
* @subpackage application
* @category	helpers
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Mikhail Makeev <mmakeev@pilotgroup.net>
* @version $Revision: 68 $ $Date: 2010-01-11 16:02:23 +0300 (Пн, 11 янв 2010) $ $Author: irina $
**/

if(! function_exists('banner_initialize')){
	function banner_initialize(){
		$CI = &get_instance();
		$tpl = &$CI->template_lite;
		$banner_html = $tpl->fetch('show_banner_setup', 'user', 'banners');
		return $banner_html;
	}
}

if ( ! function_exists('show_banner_place'))
{
	function show_banner_place($place_id)
	{
		$CI = &get_instance();
		$tpl = &$CI->template_lite;

		$CI->load->model('banners/models/Banner_place_model');
		if(!is_numeric($place_id)){
			$place = $CI->Banner_place_model->get_by_keyword($place_id);
			$place_id = $place["id"];
		}else{
			$place_id = (is_numeric($place_id) and $place_id > 0) ? intval($place_id) : 0;
			$place = $CI->Banner_place_model->get($place_id);
		}
		if (!is_array($place) or !$place){
			return;
		}
		$place['places_in_rotation'] = intval($place['places_in_rotation']);

		$CI->uri->_fetch_uri_string();
		$uri = $CI->uri->ruri_string();
		$uri = trim(substr($uri, 1));
		if(empty($uri) || count(explode('/', $uri)) < 3){
			$uri = $CI->router->fetch_class(true) . '/' . $CI->router->fetch_method();
		}

		$CI->load->model('banners/models/Banner_group_model');
		$group_id = $CI->Banner_group_model->get_group_id_by_page_link($uri);
		if(!$group_id){
			$group_ids = $CI->Banner_group_model->search_groups_id_by_page_link($uri);
		}else{
			$group_ids[] = $group_id;
		}

		$CI->load->model('Banners_model');
		$banners = $CI->Banners_model->show_rotation_banners($group_ids, $place_id, $place['places_in_rotation']);

		// don't show banner place without banners
		if (empty($banners)) return;

		$tpl->assign('place', $place);
		$tpl->assign('banners', $banners);

		// show template from banners module default user theme
		$banner_html = $tpl->fetch('show_banner_place', 'user', 'banners');
		return $banner_html;
	}
}

if (!function_exists('admin_home_banners_block')) {

	function admin_home_banners_block() {
		$CI = & get_instance();

		$auth_type = $CI->session->userdata("auth_type");
		if($auth_type != "admin") return '';

		$user_type = $CI->session->userdata("user_type");

		$show = true;

		if($user_type == 'moderator'){
			$show = false;
			$CI->load->model('Moderators_model');
			$methods = $CI->Moderators_model->get_module_methods('banners');
			if(is_array($methods) && !in_array('index', $methods)){
				$show = true;
			}else{
				$permission_data = $CI->session->userdata("permission_data");
				if(isset($permission_data['banners']['index']) && $permission_data['banners']['index'] == 1){
					$show = true;
				}
			}
		}

		if(!$show){
			return '';
		}

		$CI->load->model('Banners_model');
		$stat_banners['users'] = $CI->Banners_model->cnt_banners(array("where"=>array('user_id !='=>0, "approve"=>0)));

		$CI->template_lite->assign("stat_banners", $stat_banners);
		return $CI->template_lite->fetch('helper_admin_home_block', 'admin', 'banners');
	}
}

if (!function_exists('my_banners')) {

	function my_banners($params) {
		$CI = & get_instance();

		$auth_type = $CI->session->userdata("auth_type");
		if($auth_type != "user") return '';

		$CI->load->model('Banners_model');
		
		if(isset($params['page'])){
			$page = intval($params['page']);
		}
		
		$page = max($page, 1);

		$params["where"]["user_id"] = $CI->session->userdata("user_id");
		$cnt_banners = $CI->Banners_model->cnt_banners($params);

		$items_on_page = $CI->pg_module->get_module_config('banners', 'items_per_page');
		$CI->load->helper('sort_order');
		$page = get_exists_page_number($page, $cnt_banners, $items_on_page);

		$banners = $CI->Banners_model->get_banners($page, $items_on_page, array("id"=>"DESC"), $params);
		// get place objects for banner
		if ($banners){
			$CI->load->model('banners/models/Banner_place_model');
			foreach ($banners as $key => $banner){
				$banners[$key]['banner_place_obj'] = $banner['banner_place_id'] ? $CI->Banner_place_model->get($banner['banner_place_id']) : null;
			}
		}
		$CI->template_lite->assign('banners', $banners);

		$CI->load->helper("navigation");
		$page_data = get_user_pages_data(site_url()."users/account/banners/", $cnt_banners, $items_on_page, $page, 'briefPage');
		$page_data['date_format'] = $CI->pg_date->get_format('date_literal', 'st');
		$CI->template_lite->assign('page_data', $page_data);

		$CI->Menu_model->breadcrumbs_set_parent('my_banners_item');

		$CI->template_lite->view('my_list_block', 'user', 'banners');
	}
}
