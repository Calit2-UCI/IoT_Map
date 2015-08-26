<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Debug Helper
* 
* @package PG_Core
* @subpackage application
* @category	helpers
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Mikhail Makeev <mmakeev@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2009-12-02 15:07:07 +0300 (Ср, 02 дек 2009) $ $Author: irina $
**/
if ( ! function_exists('dump'))
{	
	/**
	 * simple helper for standart var_dump() function 
	 *
	 * @param mixed $var
	 * @param str $label
	 * @param bool $echo
	 * @return mixed
	 */
	function dump($var, $label = null, $echo = true)
	{	
		// start buffering output
		ob_start();

		if($label) 
		{
			echo "<strong>" . strval($label) . ':</strong><br />';
		}
		
		echo "<pre>";
		var_dump($var);
		echo "</pre>";
		
		$output = ob_get_contents();
		ob_end_clean();
		
		if($echo) 
		{
			echo $output;
		} 
		else 
		{
			return $output;	
		}
	}
}
if ( ! function_exists('generate_backtrace'))
{
	function generate_backtrace($print_arrays_and_objects = false)
	{
		echo '<div style="border: 1px solid gray; padding: 7px;">';
		$backtrace = debug_backtrace();
		foreach ($backtrace as $back)
		{
		 	echo '<i>' . $back['file'] . ':' . $back['line'] . "</i><br/>";
			echo '<b>';
		 	if (isset($back['class']))
		 	{
		 		echo $back['class'] . $back['type'];
		 	}
		 	if (isset($back['function']))
		 	{
		 		echo $back['function'] . '(</b>';

			 	if (isset($back['args']))
			 	{
			 		$coma = false;
			 		foreach ($back['args'] as $arg)
			 		{
			 			if ($coma)
			 			{
			 				echo ', ';
			 			}
			 			$coma = true;

			 			switch (gettype($arg))
			 			{
			 				case 'string' :
			 					echo '\'' . $arg . '\'';
			 				break;
			 				case 'array' :
							case 'object' :
								if ($print_arrays_and_objects)
								{
									print_r($arg);
								}
								else
								{
									echo $arg;
								}
			 				break;
			 				case 'NULL' :
			 					echo 'NULL';
			 				break;
			 				default:
			 					echo $arg;
			 				break;
			 			}
			 		}
			 	}

			 	echo '<b>);</b>';
		 	}
		 	echo '<br/><br/>';
		}
		echo "</div>";
	}
}
/* End of file debug_helper.php */
/* Location: ./application/helpers/debug_helper.php */