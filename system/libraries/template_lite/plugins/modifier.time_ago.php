<?php

/*
 * Template Lite plugin converted from Smarty
 * -------------------------------------------------------------
 * Type:     modifier
 * Name:     date_format
 * Purpose:  format datestamps via strftime
 * Input:    string: input date string
 *           format: strftime format for output
 *           default_date: default date if $string is empty
 * -------------------------------------------------------------
 */

function tpl_modifier_time_ago($string)
{
	$uts = tpl_make_timestamp($string);
	$mins = round((time() - $uts)/60);

	if($mins < 60){
		return $mins." ".l('mins_ago');
	}else{
		$hours = round($mins/60);
		if($hours < 24){
			return $hours." ".l('hours_ago');
		}else{
			$days = round($hours/24);
			if($days < 7){
				return $days." ".l('days_ago');
			}else{
				$weeks = round($days/7);
				if($weeks < 3){
					return $weeks." ".l('weeks_ago');
				}else{
					$months = round($days/30);
					if($months < 12){
						return $months." ".l('months_ago');
					}else{
						$years = round($months/12);
						return $years." ".l('years_ago');
					}
				}
			
			}
		}
	}

	return;
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
		if (is_numeric($time) && $time != -1)
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
		if ($time > 0)
		{
			return $time;
		}
		else
		{
			return time();
		}
	}
}

