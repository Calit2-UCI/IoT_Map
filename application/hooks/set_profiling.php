<?php
/**
 * Hook
 * set default css and js files
 */
if ( ! function_exists('set_profiling')){
	$start_exec_time = 0;
	function set_profiling(){
		if(USE_PROFILING){
			global $start_exec_time;
			$start_exec_time = getmicrotime();
		}
	}
}
if ( ! function_exists('getmicrotime')){
	function getmicrotime(){
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}
}
