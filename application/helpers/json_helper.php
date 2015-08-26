<?php

/**
* JSON Helper
* 
* @package PG_Core
* @subpackage application
* @category	helpers
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Mikhail Makeev <mmakeev@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2009-12-02 15:07:07 +0300 (Ср, 02 дек 2009) $ $Author: irina $
**/
if ( ! function_exists('json_encode'))
{
	function json_encode($arr)
	{
		$parts = array();
		if (!is_array($arr)) return;
		if (count($arr) === 0) return '{}';
	
		$keys = array_keys($arr);
		foreach($keys as $key)
		{
			if (is_array($arr[$key])) { //Custom handling for arrays
				$parts[] = '"' . $key . '":' . json_encode($arr[$key]); /* :RECURSION: */
			}
			else
			{
			$str = '"'.$key.'":';
			//Custom handling for multiple data types
			if (is_numeric($arr[$key])) $str .= $arr[$key]; //Numbers
			elseif ($arr[$key] === false) $str .= 'false'; //The booleans
			elseif ($arr[$key] === true) $str .= 'true';
			else $str .= '"'.strtr($arr[$key],
			array('\\'=>'\\\\', '/'=>'\/', '"'=>'\"', "\b"=>'\b', "\t"=>'\t', "\n"=>'\n', "\f"=>'\f', "\r"=>'\r')
			).'"'; //All other things
			
			$parts[] = $str;
			}
		}
		
		return '{'.implode(',', $parts).'}'; //Return associative JSON
	} 
}