<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Cookie Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/cookie_helper.html
 */

// ------------------------------------------------------------------------

/**
 * Set cookie
 *
 * Accepts six parameter, or you can submit an associative
 * array in the first parameter containing all the values.
 *
 * @access	public
 * @param	mixed
 * @param	string	the value of the cookie
 * @param	string	the number of seconds until expiration
 * @param	string	the cookie domain.  Usually:  .yourdomain.com
 * @param	string	the cookie path
 * @param	string	the cookie prefix
 * @return	void
 */
if ( ! function_exists('set_cookie'))
{
	function set_cookie($name = '', $value = '', $expire = '', $domain = '', $path = '/', $prefix = '')
	{
		if (is_array($name))
		{		
			foreach (array('value', 'expire', 'domain', 'path', 'prefix', 'name') as $item)
			{
				if (isset($name[$item]))
				{
					$$item = $name[$item];
				}
			}
		}
	
		// Set the config file options
		$CI =& get_instance();
	
		if ($prefix == '' AND $CI->config->item('cookie_prefix') != '')
		{
			$prefix = $CI->config->item('cookie_prefix');
		}
		if ($domain == '' AND $CI->config->item('cookie_domain') != '')
		{
			$domain = $CI->config->item('cookie_domain');
		}
		if ($path == '/' AND $CI->config->item('cookie_path') != '/')
		{
			$path = $CI->config->item('cookie_path');
		}
		
		if ( ! is_numeric($expire))
		{
			$expire = time() - 86500;
		}
		else
		{
			if ($expire > 0)
			{
				$expire = time() + $expire;
			}
			else
			{
				$expire = 0;
			}
		}
	
		// set $_COOKIE directly for ajax requests
		if($expire == 0 || $expire > time()){
			preg_match_all("/\[?([^\[\]]+)\]?/i", $prefix.$name, $matches);
			array_shift($matches);
			if(!empty($matches[0])){
				make_cookie_array($matches[0], $value, $_COOKIE);
			}
		}else{
			make_cookie_array($matches[0], $value, $_COOKIE, true);
		}

		setcookie($prefix.$name, $value, $expire, $path, $domain, 0);
	}
}

if ( ! function_exists('make_cookie_array'))
{
	function make_cookie_array(&$keys, $value, &$array = array(), $unset = false){
		if(!(is_array($keys) && $keys)){
			return $array;
		}
		$key = array_shift($keys);
		if(!isset($array[$key])){
			$array[$key] = array();
		}
		if($keys){
			make_cookie_array($keys, $value, $array[$key], $unset);
		}else{
			if($unset){
				unset($array[$key]);
			}else{
				$array[$key] = $value;
			}
		}
		return $array;
	}
}
	
// --------------------------------------------------------------------

/**
 * Fetch an item from the COOKIE array
 *
 * @access	public
 * @param	string
 * @param	bool
 * @return	mixed
 */
if ( ! function_exists('get_cookie'))
{
	function get_cookie($index = '', $xss_clean = FALSE)
	{
		$CI =& get_instance();
		return $CI->input->cookie($index, $xss_clean);
	}
}

// --------------------------------------------------------------------

/**
 * Delete a COOKIE
 *
 * @param	mixed
 * @param	string	the cookie domain.  Usually:  .yourdomain.com
 * @param	string	the cookie path
 * @param	string	the cookie prefix
 * @return	void
 */
if ( ! function_exists('delete_cookie'))
{
	function delete_cookie($name = '', $domain = '', $path = '/', $prefix = '')
	{
		set_cookie($name, '', '', $domain, $path, $prefix);
	}
}


/* End of file cookie_helper.php */
/* Location: ./system/helpers/cookie_helper.php */
