<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Upload file
* 
* @package PG_Core
* @subpackage application
* @category	helpers
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Irina Lebedeva <irina@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2009-12-02 15:07:07 +0300 (Ср, 02 дек 2009) $ $Author: irina $
**/

/*
	'max_size'
	'max_width'
	'max_height'
	'allowed_types'
	'file_name'
	'upload_path'
	'overwrite'

*/
function upload_file($file_elem_name, $upload_path, $config=array())
{
	$CI = & get_instance();
	$error = $msg = '';
	
	// do upload
	$config["upload_path"] = $upload_path;
	$CI->load->library('upload');
	$CI->upload->initialize($config);
	if (!$CI->upload->do_upload($file_elem_name)){
		$error = $CI->upload->error_msg;
	}else{
		$msg = $CI->upload->data();
	}

	//for security reason, we force to remove all uploaded file
	@unlink($_FILES[$file_elem_name]);
	
	return array('error' => $error, 'data' => $msg);
}

function validate_file($file_elem_name, $config=array()){
	$CI = & get_instance();
	$error = $msg = '';
	
	// do validate
	$CI->load->library('upload');
	$CI->upload->initialize($config);
	if (!$CI->upload->do_validate($file_elem_name)){
		$error = $CI->upload->error_msg;
	}else{
		$msg = $CI->upload->data();
	}

	return array('error' => $error, 'data' => $msg);
}

function get_extension($file_name){
	$path_parts = pathinfo($file_name);
	return $path_parts["extension"];
}

function rename_file($file_old, $file_new){
	if(copy($file_old, $file_new)){
		@unlink($file_old);
		return true;
	}
	return false;
}

function get_mimes_types_by_files_types($files_types){
	$CI = & get_instance();
	$CI->load->library('upload');
	$mimes_cache = $CI->upload->mimes;
	$CI->upload->mimes_types('');
	$result = array();
	foreach($files_types as $file_type){
		if(!empty($CI->upload->mimes[$file_type])){
			if(is_array($CI->upload->mimes[$file_type])){
				foreach($CI->upload->mimes[$file_type] as $mime_format){
					$result[] = $mime_format;
				}
			}else{
				$result[] = $CI->upload->mimes[$file_type];
			}
		}
	}
	$CI->upload->mimes = $mimes_cache;
	return array_values(array_unique($result));
}
