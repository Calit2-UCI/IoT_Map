<?php
 
/**
 * Date management
 * 
 * @package PG_Core
 * @subpackage Helpers
 * @category	helpers
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @author Mikhail Makeev <mmakeev@pilotgroup.net>
 * @version $Revision: 68 $ $Date: 2010-01-11 16:02:23 +0300 (Пн, 11 янв 2010) $ $Author: irina $
 **/
 
if(!function_exists('tpl_date_format')){
	function tpl_date_format($string, $format="%b %e, %Y", $default_date=null, $default=''){
		$timestamp = tpl_make_timestamp($string);
		if($string != '' && $timestamp > 0)
		{
			$CI = &get_instance();
			return $CI->pg_date->strftime($format, $timestamp);
		}
		elseif ($timestamp < -1)
		{
			if(!$string && !empty($default_date))
			{
				$string = $default_date;
			}
			$date = date_create($string);
			if($date)
			{
				return date_format($date, tpl_convert_date_format($format));
			}
		}
		elseif (!empty($default_date))
		{
			return strftime($format, tpl_make_timestamp($default_date));
		}
		else
		{
			return $default;
		}
	}
}

if(!function_exists('tpl_make_timestamp'))
{
	function tpl_make_timestamp($string)
	{
		if(empty($string))
		{
			$string = "now";
		}
		$time = strtotime($string);
		if (is_numeric($time) && $time > -1)
		{
			return $time;
		}

		// is mysql timestamp format of YYYYMMDDHHMMSS?
		if (is_numeric($string) && strlen($string) == 14)
		{
			$time = mktime(substr($string,8,2),substr($string,10,2),substr($string,12,2),substr($string,4,2),substr($string,6,2),substr($string,0,4));
			return $time;
		}

		// couldn't recognize it, try to return a time
		$time = (int) $string;
		return $time;
	}
}

if(!function_exists('tpl_convert_date_format'))
{
	function tpl_convert_date_format($format)
	{
		$convert = array(
			'%e' => 'd',
			'%a' => 'D',
			'%A' => 'l',
			'%b' => 'M',
			'%B' => 'F',
			'%C' => '', // Century
			'%d' => 'd',
			'%D' => 'm/d/y',
			'%e' => 'j',
			'%g' => 'y',
			'%G' => 'Y',
			'%h' => 'M',
			'%H' => 'H',
			'%I' => 'h',
			'%j' => 'z', //
			'%m' => 'm',
			'%M' => 'i',
			'%n' => "\n",
			'%p' => 'a',
			'%r' => 'g:i a',
			'%R' => 'G:i',
			'%S' => 's',
			'%t' => "\t",
			'%T' => 'H:i:s',
			'%u' => 'w', //
			'%U' => 'W',
			'%V' => 'W',
			'%W' => 'W',
			'%w' => 'w',
			'%x' => '',
			'%X' => '',
			'%y' => 'y',
			'%Y' => 'Y',
			'%Z' => 'T',
			'%%' => '%'
		);
		foreach($convert as $key => $value)
		{
			$format = str_replace($key, $value, $format);
		}
		return $format;
	}
}
