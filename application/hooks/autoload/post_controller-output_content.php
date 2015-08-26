<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('output_content')){

	function output_content(){
		$CI = & get_instance();	
			
		$output_format = '';	
			
		if($CI->router->is_api_class){
			$output_format = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
			if($output_format === 'xml'){
				if(!$CI->pg_module->get_module_config('get_token', 'use_xml')){
					$output_format = 'json';
				}
			}else{
				$output_format = 'json';
			}
			$output_content = $CI->api_content;
		}else{
			$accept = filter_input(INPUT_SERVER, 'HTTP_ACCEPT', FILTER_SANITIZE_STRING);
			if(strpos($accept, 'text/xml') !== false){
				$output_format = 'xml';
			}
			if(strpos($accept, 'application/json') !== false){
				$output_format = 'json';
			}
			
			$CI->load->library('PG_Output');
			$output_content = $CI->pg_output->getOutputContent();
		}
		
		if(empty($output_content)) return;
	
		switch($output_format){
			case 'xml':
				$CI->load->library('array2xml');
				echo $CI->array2xml->convert($output_content);
			break;
			case 'json':
				$force_json = filter_input(INPUT_POST, 'force_object', FILTER_VALIDATE_BOOLEAN);
				echo json_encode($output_content, $force_json ? JSON_FORCE_OBJECT : null);
			break;
			default:
				if(!empty($output_content['errors'])){
					$CI->system_messages->add_message('error', $output_content['errors']);
				}
				if(!empty($output_content['messages'])){
					$CI->system_messages->add_message('info', $output_content['messages']);
				}
				if(!empty($output_content['success'])){
					$CI->system_messages->add_message('success', $output_content['success']);
				}
				if(!empty($output_content['header'])){
					if(is_array($output_content['header'])){
						if(count($output_content['header']) > 1){
							$CI->system_messages->set_data('header', $output_content['header'][0]);
							$CI->system_messages->set_data('subheader', $output_content['header'][1]);
						}else{
							$CI->system_messages->set_data('header', $output_content['header'][0]);
						}
					}else{
						$CI->system_messages->set_data('header', $output_content['header']);
					}
				}
				if(!empty($output_content['help'])){
					$CI->system_messages->set_data('help', $output_content['help']);
				}
				if(!empty($output_content['back_link'])){
					$CI->system_messages->set_data('back_link', $output_content['back_link']);
				}
				if(!empty($output_content['data'])){
					foreach($output_content['data'] as $key=>$data){
						$CI->template_lite->assign($key, $data);
					}
				}
			
				if(!empty($output_content['redirect'])){
					redirect($output_content['redirect']);
				}
			
				if(!empty($output_content['template'])){
					$CI->template_lite->view($output_content['template']);
				}else{
					$CI->template_lite->view($CI->uri->rsegments[2]);
				}
			break;
		}
	}
}
