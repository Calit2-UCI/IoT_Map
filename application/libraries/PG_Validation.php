<?php
/**
* Extension for the CodeIgniter Validation library
* 
* @package PG_Core
* @subpackage application
* @category	libraries
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Irina Lebedeva <irina@pilotgroup.net>
* @version $Revision: 224 $ $Date: 2010-04-12 15:56:34 +0400 (Пн, 12 апр 2010) $ $Author: irina $
**/

class PG_Validation extends CI_Validation {
	
	/**
	 * Alpha(lower)-numeric with underscores
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */	
	function alpha_underscore($str)
	{
		return ( ! preg_match("/^([a-z0-9_])+$/", $str)) ? FALSE : TRUE;
	}
	/**
	 * Alpha(lower)-numeric with underscores and slashes
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
	function alpha_underscore_slash($str)
	{
		return ( ! preg_match("/^([a-z0-9_\/])+$/", $str)) ? FALSE : TRUE;
	}

	/**
	 * Alpha(lower)-numeric with underscores with at least one alpha symbol
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */	
	function alpha_underscore_not_only_int($str)
	{
		return ( ! preg_match("/^([0-9_]*[a-z]+[a-z_0-9]*)$/", $str) ) ? FALSE : TRUE;
	}
	
	/**
	 * Check field for compatability with PREG pattern
	 * 
	 * @param string $str
	 * @param string $pattern string pattern for comparison
	 * @return boolean
	 */
	function match_pattern($str, $pattern)
	{
		if (preg_match($pattern, $str))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * Is positive numeric
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */	
	function positive_numeric($str)
	{
		return (bool)preg_match( '/^[0-9]*\.?[0-9]+$/', $str);

	}

	/**
	 * Is positive numeric no zero
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */	
	function positive_numeric_no_zero($str)
	{
		if ( ! preg_match('/^[0-9]*\.?[0-9]+$/', $str))
		{
			return FALSE;
		}
	
		if ($str == 0)
		{
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Is valid URL
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
	function url($str)
	{
		return (bool) preg_match( '/^(http:\/\/|https:\/\/|)([^\.\/]+\.)*([a-zA-Z0-9])([a-zA-Z0-9-]*)\.([a-zA-Z]{2,4})(\/.*)?$/i', $str);

	}
}
