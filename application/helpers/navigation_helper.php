<?php

if ( ! function_exists('get_admin_pages_data'))
{
	function get_admin_pages_data($link, $count, $count_per_page, $current_page, $link_type){
		$CI = & get_instance();
	
		$page_array = array(
			'base_url' => $link, 
			'total_rows' => $count, 
			'per_page' => $count_per_page,
			'cur_page' => $current_page,
			'first_link' => l('nav_first', 'start'),
			'last_link' => l('nav_last', 'start'),
			'next_link' => l('nav_next', 'start'),
			'prev_link' => l('nav_prev', 'start'),

		);
		if($count > $count_per_page){
			$CI->load->library("pagination");
			$CI->pagination->initialize($page_array);
			$page_array["nav"] = $CI->pagination->create_links($link_type);
		}

		if($page_array["total_rows"]){
			$page_array["start_num"] = ($current_page - 1)*$count_per_page+1;
			$page_array["end_num"] = $current_page*$count_per_page;
			$page_array["end_num"] = ($page_array["end_num"]  > $page_array["total_rows"])?$page_array["total_rows"]:$page_array["end_num"];
		}
		return $page_array;
	}
}

if ( ! function_exists('get_user_pages_data'))
{
	function get_user_pages_data($link, $count, $count_per_page, $current_page, $link_type){
		$CI = & get_instance();
	
		$page_array = array(
			'base_url' => $link, 
			'total_rows' => $count, 
			'per_page' => $count_per_page,
			'cur_page' => $current_page,
			'prev_page' => ($current_page == 1)?1:($current_page-1),
			'next_page' => ($current_page == ceil($count/$count_per_page))?ceil($count/$count_per_page):($current_page+1),
			'total_pages' => ceil($count/$count_per_page), 
			
			'first_tag_open' => '<ins class="first">',
			'first_link' =>  '&nbsp;',
			'first_tag_close' =>  '</ins>',
			'prev_tag_open' =>  '<ins class="prev">',
			'prev_link' =>  '&nbsp;',
			'prev_tag_close' =>  '</ins>',
			'cur_tag_open' =>  '<ins>|</ins><ins class="current">',
			'cur_tag_close' =>  '</ins>',
			'num_tag_open' =>  '<ins>|</ins><ins>',
			'num_tag_close' =>  '</ins>',
			'next_tag_open' =>  '<ins>|</ins><ins class="next">',
			'next_link' =>  '&nbsp;',
			'next_tag_close' =>  '</ins>',
			'last_tag_open' =>  '<ins class="last">',
			'last_link' =>  '&nbsp;',
			'last_tag_close' =>  '</ins>',
		);
				
		if($count > $count_per_page){
			$CI->load->library("pagination");
			$CI->pagination->initialize($page_array);
			$page_array["nav"] = $CI->pagination->create_links($link_type);
		}

		if($page_array["total_rows"]){
			$page_array["start_num"] = ($current_page - 1)*$count_per_page+1;
			$page_array["end_num"] = $current_page*$count_per_page;
			$page_array["end_num"] = ($page_array["end_num"]  > $page_array["total_rows"])?$page_array["total_rows"]:$page_array["end_num"];
		}
		return $page_array;
	}
}