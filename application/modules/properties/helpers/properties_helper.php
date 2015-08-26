<?php

if(!function_exists('property_select')){
	function property_select($property_gid, $var_name='', $selected=array(), $multi=0, $empty=1, $lang_id=''){
		$CI = & get_instance();
		$CI->load->model("Properties_model"); 
		
		if(empty($property_gid)){
			return false;
		}
		
		$data = $CI->Properties_model->get_property($property_gid, $lang_id);
		
		if(empty($data)){
			return false;
		}
		
		if(!$var_name){
			$var_name = $property_gid;
		}
		
		if(!is_array($selected) && !empty($selected)){
			$selected = array(0 => $selected);
		}
		if(!empty($selected) ){
			foreach($selected as $item) $selected_reverse[$item] = 1;
		}
		
		$select = array(
			'options' => $data['option'],
			'name' => $var_name,
			'selected' => $selected_reverse,
			'multi' => $multi,
			'empty_option' => $empty,
		);
		$CI->template_lite->assign('properties_helper_data', $select);
		return $CI->template_lite->fetch('helper_properties_select', 'admin', 'properties');
	}
}