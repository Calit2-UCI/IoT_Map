<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
* UTF8 strings Helper
*
* @package PG_Core
* @subpackage application
* @category	helpers
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Irina Lebedeva <irina@pilotgroup.net>
* @version $Revision: 263 $ $Date: 2010-09-02 15:38:06 +0400 (Чт, 02 сен 2010) $ $Author: irina $
**/
class UTF8_string_helper
{
	public static function utf8_substr($string, $from, $len)
	{
	  return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$from.'}'.
						   '((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$len.'}).*#s',
						   '$1', $string);
	}

	public static function utf8_strlen($string)
	{
		return preg_match_all('/./u', $string, $tmp);
	}
}