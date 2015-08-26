<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CodeIgniter
 *
 * @author      Bekbulaov A
 * @link      http://vkurseweba.ru
 * @since      Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

class PG_Pagination extends CI_Pagination {

	/**
	 * URL suffix, see config.php
	 * 
	 * @var string
	 */
	public $url_suffix = ''; 
	
	/**
	 * Page segment name in query string
	 * 
	 * @var string
	 */
	public $query_string_segment = 'page'; 
	
	/**
	 * Class constructor
	 * 
	 * @return PG_Pagination
	 */
	public function PG_Pagination(){
		parent::CI_Pagination();
		$this->first_link = l('nav_first', 'start');
		$this->last_link = l('nav_last', 'start');
		$this->next_link = '&rsaquo;';
		$this->prev_link = '&lsaquo;';
	}

	// --------------------------------------------------------------------

	/**
	 * Generate the pagination links
	 *
	 * @access   public
	 * @return   string
	 */
	public function create_links($output_format_type = ''){
		// If our item count or per-page total is zero there is no need to
		// continue.
		if($this->total_rows == 0 OR $this->per_page == 0){
			return '';
		}

		// Calculate the total number of pages
		$num_pages = ceil($this->total_rows / $this->per_page);

		// Is there only one page? Hm... nothing more to do here then.
		if($num_pages == 1){
			return '';
		}

		// Determine the current page number.
		$CI =& get_instance();

		$this->url_suffix = $CI->config->item('url_suffix');

		if($CI->config->item('enable_query_strings') === TRUE OR $this->page_query_string === TRUE){
			if(empty($this->cur_page) && $CI->input->get($this->query_string_segment) != 0){
				$this->cur_page = $CI->input->get($this->query_string_segment);

				// Prep the current page - no funny business!
				$this->cur_page = (int) $this->cur_page;
			}
		}else{
			if(empty($this->cur_page) && $CI->uri->segment($this->uri_segment) != 0){
				$this->cur_page = $CI->uri->segment($this->uri_segment);

				// Prep the current page - no funny business!
				$this->cur_page = (int) $this->cur_page;
			}

			$this->base_url = $this->remove_url_suffix($this->base_url, $this->url_suffix);
		}

		$this->num_links = (int)$this->num_links;

		if($this->num_links < 1){
			show_error('Your number of links must be a positive number.');
		}

		if(!is_numeric($this->cur_page)){
			$this->cur_page = 0;
		}

		if($this->cur_page < 1){
			$this->cur_page = 1;
		}
     
		if($this->cur_page > $num_pages){
			$this->cur_page = $num_pages;
		}

		$uri_page_number = $this->cur_page;

		$this->cur_page = floor($this->cur_page);

		// Calculate the start and end numbers. These determine
		// which number to start and end the digit links with
		$start = (($this->cur_page - $this->num_links) > 0) ? $this->cur_page -
			($this->num_links - 1) : 1;
		
		$end = (($this->cur_page + $this->num_links) < $num_pages) ?
			$this->cur_page + $this->num_links : $num_pages;

		// Is pagination being used over GET or POST?  If get, add a per_page
		// query string. If post, add a trailing slash to the base URL if needed
		if($CI->config->item('enable_query_strings') === TRUE OR $this->page_query_string === TRUE){
			$parse_url = parse_url($this->base_url);
			if(isset($parse_url['query'])){
				$this->base_url = preg_replace('/(&|\?)page=([0-9]*)/', '', $this->base_url);
				if(false === strpos($this->base_url, '?')){
					$this->base_url = rtrim($this->base_url).'?'.$this->query_string_segment.'=';
				}else{
					$this->base_url = rtrim($this->base_url).'&amp;'.$this->query_string_segment.'=';
				}
			}else{
				$this->base_url = rtrim($this->base_url).'?'.$this->query_string_segment.'=';
			}
		}else{
			$this->base_url = rtrim($this->base_url, '/') .'/';
		}

		// And here we go...
		$output = '';
		$nav_page_data = array('first'=>'', 'prev'=>'', 'pages'=>array(), 'next'=>'', 'last'=>'');

		if($this->cur_page > $this->num_links){  
			if(strpos($this->base_url, '[page]')){
				$first_page_link = str_replace('[page]', 1, $this->base_url);
			}else{
				$first_page_link = $this->base_url.'1';
			}
			$nav_page_data['first'] = $first_page_link.$this->url_suffix;
			$first_page = '<a href="'.$nav_page_data['first'].'" data-page="1">'.$this->first_link.'</a>';
			
			$output .= $this->first_tag_open.$first_page.$this->first_tag_close;
		}

		if($this->cur_page > 1){
			if(strpos($this->base_url, '[page]')){
				$prev_page_link = str_replace('[page]', $this->cur_page - 1, $this->base_url);
			}else{
				$prev_page_link = $this->base_url.($this->cur_page - 1);
			}
			$nav_page_data['prev'] = $prev_page_link.$this->url_suffix;
			$prev_page = '<a href="'.$nav_page_data['prev'].'" data-page="'.($this->cur_page - 1).'">'.$this->prev_link.'</a>';
			$output .= $this->prev_tag_open.$prev_page.$this->prev_tag_close;
		}

		// Write the digit links
		for($loop = $start - 1; $loop <= $end; $loop++){
			if($loop < 1) continue;
			if($this->cur_page == $loop){
				// Current page
				$output .= $this->cur_tag_open.$loop.$this->cur_tag_close;
				$nav_page_data['pages'][$loop] = $loop;
			}else{
				if(strpos($this->base_url, '[page]')){
					$cur_page_link = str_replace('[page]', $loop, $this->base_url);
				}else{
					$cur_page_link = $this->base_url.$loop;
				}
				$nav_page_data['pages'][$loop] = $cur_page_link.$this->url_suffix;
				$page_link = '<a href="'.$nav_page_data['pages'][$loop].'" data-page="'.$loop.'">'.$loop.'</a>';
				$output .= $this->num_tag_open.$page_link.$this->num_tag_close;
			}
		}

		// Render the "next" link
		if($this->cur_page < $num_pages){         
			if(strpos($this->base_url, '[page]')){
				$next_page_link = str_replace('[page]', ($this->cur_page + 1), $this->base_url);
			}else{
				$next_page_link = $this->base_url.($this->cur_page + 1);
			}
			$nav_page_data['next'] = $next_page_link.$this->url_suffix;
			$next_page = '<a href="'.$nav_page_data['next'].'" data-page="'.($this->cur_page + 1).'">'.$this->next_link.'</a>';
			$output .= $this->next_tag_open.$next_page.$this->next_tag_close;
		}

		// Render the "Last" link
		if(($this->cur_page + $this->num_links) < $num_pages){
			if(strpos($this->base_url, '[page]')){
				$last_page_link = str_replace('[page]', $num_pages, $this->base_url);
			}else{
				$last_page_link = $this->base_url.$num_pages;
			}
			$nav_page_data['last'] = $last_page_link.$this->url_suffix;
			$last_page = '<a href="'.$nav_page_data['last'].'" data-page="'.$num_pages.'">'.$this->last_link.'</a>';
			$output .= $this->last_tag_open.$last_page.$this->last_tag_close;
		}

		if(!$this->format){
			$nav_page_data['current'] = $this->cur_page;
			$nav_page_data['count'] = $num_pages;
			$CI->template_lite->assign('nav_page_data', $nav_page_data);
			$output = $CI->template_lite->fetch('pagination');
		}

		// Kill double slashes.  Note: Sometimes we can end up with a double
		// slash in the penultimate link so we'll kill all double slashes.
		$output = preg_replace("#([^:])//+#", "\1/", $output);

		// Add the wrapper HTML if exists
		$output = $this->full_tag_open.$output.$this->full_tag_close;

		if(!empty($output_format_type)){
			$output = $this->_get_formatted_output($output_format_type, $output);
		}
		
		return $output;
	}

	/**
	 * Format pagination content
	 * 
	 * @param string $output_type output type
	 * @param string $output pagination content
	 * @return string
	 */
	public function _get_formatted_output($output_type, $output){
		$tmp = '';
		if($output_type == 'sortBy'){
			$tmp = str_replace(
				array('<a ', '</a>', 'href='), 
				array('<span ', '</span>', 'onclick='),
				$output
			);
			$tmp = preg_replace(
				'/\?page=([\d]+)"/', 
				'\'$1\');"',
				$tmp
			);
		}elseif ($output_type == 'saveDs'){
			$tmp = str_replace(
				array('<a ', '</a>', 'href='), 
				array('<span ', '</span>', 'onclick='),
				$output
			);
			$tmp = preg_replace(
				'/\?page=([\d]+)"/', 
				'\'$1\');"',
				$tmp
			);	      
		}elseif ($output_type == 'loadStatistics'){
			$tmp = str_replace(
				array('<a ', '</a>', 'href='), 
				array('<span class="action" ', '</span>', 'onclick='),
				$output
			);	
			$tmp = preg_replace(
				'/\?page=([\d]+)"/', 
				'\'$1\');"',
				$tmp
			);	      
		}elseif ($output_type == 'briefPage'){
			$tmp = preg_replace(
				'/\?page=([\d]+)/', 
				'$1',
				$output
			);
		}		
		return $tmp;   
	}

	/**
	 * Remove suffix of page url
	 * 
	 * @param string $base_url base url
	 * @param string $url_suffix suffix value
	 * @return string
	 */
	public function remove_url_suffix($base_url = '', $url_suffix = ''){
		if($url_suffix != ""){
			return preg_replace("|".preg_quote($url_suffix)."$|", "", $base_url);
		}
		return $this->base_url;
	}
}
