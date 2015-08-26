<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('show_map')){
	
	function show_map($params){
		$CI = &get_instance();
		$CI->load->model('Geomap_model');
		if(isset($params["width"])) $params["settings"]["width"] = $params["width"];
		if(isset($params["height"])) $params["settings"]["height"] = $params["height"];
		$only_load_scripts = isset($params["only_load_scripts"]) && !empty($params["only_load_scripts"]) ? true : false;
		$only_load_content = isset($params["only_load_content"]) && !empty($params["only_load_content"]) ? true : false;
		return $CI->Geomap_model->create_map($params['map_gid'], $params['id_user'], $params['object_id'], $params['gid'], $params['markers'], $params['settings'], $only_load_scripts, $only_load_content, isset($params['map_id']) ? $params['map_id'] : false);
	}
	
}

if ( ! function_exists('show_default_map')){
	
	function show_default_map($params){
		$CI = &get_instance();
		$CI->load->model('Geomap_model');
		if(isset($params["width"])) $params["settings"]["width"] = $params["width"];
		if(isset($params["height"])) $params["settings"]["height"] = $params["height"];
		$only_load_scripts = isset($params["only_load_scripts"]) && !empty($params["only_load_scripts"]) ? true : false;
		$only_load_content = isset($params["only_load_content"]) && !empty($params["only_load_content"]) ? true : false;
		return $CI->Geomap_model->create_default_map($params['id_user'], $params['object_id'], $params['gid'], $params['markers'], $params['settings'], $only_load_scripts, $only_load_content, isset($params['map_id']) ? $params['map_id'] : false);
	}
	
}

if(!function_exists("show_map_view")){
	function show_map_view($url){
		$CI = &get_instance();
		$CI->template_lite->assign("url", $url);
		return $CI->template_lite->fetch("helper_show_map", "user", "geomap");
	}
}

if(!function_exists("update_default_map")){
	function update_default_map($params){
		if(!isset($params['map_id'])) return '';
		$CI = &get_instance();
		$CI->load->model("Geomap_model");   
		return $CI->Geomap_model->update_default_map($params["map_id"], $params["markers"], $params["settings"]);
	}
}

if(!function_exists("update_map")){
	function update_map($params){
		if(!isset($params['map_id'])) return '';
		$CI = &get_instance();
		$CI->load->model("Geomap_model");   
		return $CI->Geomap_model->update_map($params["map_gid"], $params["map_id"], $params["markers"]);
	}
}

if(!function_exists('geomap_amenity_select')){
	///// amenities = array of ids
	function geomap_amenity_select($gid, $max=5, $amenities=array(), $var='id_amenity', $var_js_name='', $output="max"){
		$CI = & get_instance();
		$CI->load->model("geomap/models/Geomap_settings_model");   

		$max = intval($max);
		if($max < 1) $max = 1;
		
		$c_by_id = $selected_all = $selected = array();
		$selected_data = $max == 1 ? "" : 0;
		$selected_text = "";
		
		if(!empty($amenities)){
			$amenities_data = $CI->pg_language->ds->get_reference(
				$CI->Geomap_settings_model->module_gid, 
				$gid, 
				$CI->pg_language->current_lang_id
			);
			
			$amenities_data = array_intersect_key($amenities_data["option"], array_flip($amenities));
			if(!empty($amenities_data)){
				foreach($amenities_data as $amenity_gid=>$amenity_name){
					$selected_all[$amenity_gid] = 1;
					$c_by_id[$amenity_gid] = array("id"=>$amenity_gid, "name"=>$amenity_name);
					if($max == 1){
						//// get text
						$selected_data .= $amenity_name." ";
					}else{
						/// get count
						$selected_data++;
					}			
				}				
				if($max > 1)
					$selected_text = implode(", ", $amenities_data);
			}
		}

		$data["var"] = $var;
		$data["selected_all"] = $selected_all;
		$data["selected_all_json"] = json_encode($selected_all);
		$data["var_js_name"] = $var_js_name;
		$data["raw_data"] = $c_by_id;
		$data["raw_data_json"] = json_encode($c_by_id);
		$data["max"] = $max;
		$data["gid"] = $gid;
		$data["output"] = ($output)?$output:'max';
		$data["selected_text"] = $selected_text;
		$data["selected_data"] = $selected_data;
		$data["rand"] = rand(100000, 999999);

		$CI->template_lite->assign('amenity_helper_data', $data);
		return $CI->template_lite->fetch('helper_amenity_select', 'admin', 'geomap');
	}
}

if(!function_exists('geomap_panorama_available')){
	function geomap_panorama_available($gid){
		$CI = & get_instance();
		$CI->load->model('Geomap_model');
		$CI->load->model('geomap/models/Geomap_settings_model');
		$map_driver = $CI->Geomap_model->get_default_driver();
		$settings = $CI->Geomap_settings_model->get_parsed_settings($map_driver['gid'], 0, 0, $gid);
		return isset($settings['use_panorama']) && !empty($settings['use_panorama']);
	}
}


if(!function_exists('geomap_load_geocoder')){
	function geomap_load_geocoder(){
		$CI = & get_instance();
		$CI->load->model('Geomap_model');
		return $CI->Geomap_model->create_default_geocoder();
	}
}
