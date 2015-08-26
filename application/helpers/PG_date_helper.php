<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter Date Helpers
 *
 * @package   PG_Core
 * @subpackage  
 * @category  
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @author    Mikhail Makeev
 * @version   $Revision: 2 $ $Date: 2009-12-02 15:07:07 +0300 (Ср, 02 дек 2009) $ $Author: irina $
 */

// ------------------------------------------------------------------------
// adodb lib need for date early that 1970 year in win and 1901 in *nix system
include_once SITE_PHYSICAL_PATH . 'system/plugins/adodb-time.inc.php';
/**
 * format iso date to the site format
 */
if ( ! function_exists('format_date'))
{
  function format_date($dateISO, $use_time = false)
  {
    // load lang settings for date
    $CI = & get_instance();
    $CI->load->model('lang_config');
    $lang_config = $CI->lang_config->get_config();
    $date = dateParseFromFormat('Y-m-d H:i:s', $dateISO);
    $time = adodb_mktime($date['hour'], $date['minute'], $date['second'], $date['month'], $date['day'], $date['year']);
    $format = $lang_config['date_format_array'];
    if (!$time or 
      -1 === $time or
	  !is_array($format) or
	  !$format)
    {
      return false; 
    }
	
    return generate_date_str($format, $time, $use_time);
  }
}

/**
 * Generate date string from format
 * @param str $format (ex. dd/mm/yy)
 * @param int $timestamp (from mktime)
 */
function generate_date_str($format, $timestamp, $use_time = false)
{
	// load lang settings for date
    $CI = & get_instance();
    $CI->load->model('lang_config');
    $lang_config = $CI->lang_config->get_config();
	$separator = isset($lang_config['date_separator_str']) ? $lang_config['date_separator_str'] : ' ';
    $result = '';
    $i = 0;
    foreach ($format as $f)
    {
      $i++;
      switch ($f)
      {
        case 'dd':
          $result .= adodb_date('d', $timestamp);
        break;
        case 'd':
          $result .= adodb_date('j', $timestamp);
        break;
        case 'mm':
          $result .= adodb_date('m', $timestamp);
        break;
        case 'MM':
          $result .= ld('months', adodb_date('n', $timestamp));
        break;
        case 'yy':
          $result .= adodb_date('Y', $timestamp);
        break;
        default:
        break;
      }
      // add separator
      if ($i != count($format))
      {
        $result .= $separator;
      }
    }

	if ($use_time)
    {
      $result .= ' ';
      if (1 == $lang_config['show_24_hour'])
      {
        $result .= adodb_date('H', $timestamp);
      }
      else
      {
        $result .= adodb_date('h', $timestamp);
      }
      $result .= $lang_config['time_separator_str'];
      $result .= adodb_date('i', $timestamp);
      if (1 == $lang_config['show_seconds'])
      {
        $result .= $lang_config['time_separator_str'];
        $result .= adodb_date('s', $timestamp);
      }

      if (1 != $lang_config['show_24_hour'])
      {
        $result .= isset($lang_config['ampm_prefix_str']) ? $lang_config['ampm_prefix_str'] : ' ';
        $result .= adodb_date('A', $timestamp);
      }
    }

	return $result;
}

/**
 * Simple function to take in a date format and return array of associated
 * formats for each date element
 *
 * @return array
 * @param string $strFormat
 *
 * Example: Y/m/d g:i:s becomes
 * Array
 * (
 *     [year] => Y
 *     [month] => m
 *     [day] => d
 *     [hour] => g
 *     [minute] => i
 *     [second] => s
 * )
 *
 *  This function is needed for  PHP < 5.3.0
 */
if ( ! function_exists('dateParseFromFormat')) 
{
  function dateParseFromFormat($stFormat, $stData)
  {
      $aDataRet = array();
      $aPieces = preg_split('/[:.\-\s\/\\\]/', $stFormat);
      $aDatePart = preg_split('/[:.\-\s\/\\\]/', $stData);
      foreach($aPieces as $key=>$chPiece)   
      {
          switch ($chPiece)
          {
              case 'd':
              case 'j':
                  $aDataRet['day'] = $aDatePart[$key];
                  break;
                 
              case 'F':
              case 'M':
              case 'm':
              case 'n':
                  $aDataRet['month'] = $aDatePart[$key];
                  break;
                 
              case 'o':
              case 'Y':
              case 'y':
                  $aDataRet['year'] = $aDatePart[$key];
                  break;
             
              case 'g':
              case 'G':
              case 'h':
              case 'H':
                  $aDataRet['hour'] = $aDatePart[$key];
                  break;   
                 
              case 'i':
                  $aDataRet['minute'] = $aDatePart[$key];
                  break;
                 
              case 's':
                  $aDataRet['second'] = $aDatePart[$key];
                  break;           
          }
         
      }
      return $aDataRet;
  }
}

/**
 * Validate date
 * check date that less that 1937 year or greater that 2038
 *
 * @param str $value - date in ISO format Y-m-d H:i:s
 * @param array $setup
 * @return bool
 */
if ( ! function_exists('is_valid_date')) 
{
  function is_valid_date($value, $setup = null)
  {
    // return true if date equals 0000-00-00 00:00:00 
    if ('0000-00-00 00:00:00' == $value)
    {
      // field required must be checked in other place
      return true;
    }
    
    // get date array from date ISO format
    $date_array = dateParseFromFormat('Y-m-d H:i:s', $value);
    $is_error = (!$date_array or !is_array($date_array));
    if ($is_error) return false;
    
    // check date
    $year = (isset($date_array['year']) and checkdate(1,1,$date_array['year'])) 
          ? intval($date_array['year']) 
          : false;
    $month  = (isset($date_array['month']) and checkdate($date_array['month'],1,1)) 
          ? intval($date_array['month']) 
          : false;
    $day  = (isset($date_array['day']) and checkdate(1,$date_array['day'],1)) 
          ? intval($date_array['day']) 
          : false;
    $is_error = (!$day or !$month or !$year);
    if ($is_error) return false;
    
    // check time
    $unix_time = adodb_mktime($date_array['hour'], $date_array['minute'], $date_array['second'], $month, $day, $year);
    if (!$unix_time or 
      -1 === $unix_time)
    {
      $is_error = true;
    }
    if ($is_error) return false;
    
    // check min year if exist
    $is_error = (isset($setup['min_year']))
            ? intval($setup['min_year']) > $year
            : false;
    // check max year if exist
    $is_error = (isset($setup['max_year']))
            ? intval($setup['max_year']) < $year
            : false;
    
    return ($is_error === true) ? false : true;
  }
} 
/* End of file PG_date_helper.php */
/* Location: ./application/helpers/PG_date_helper.php */