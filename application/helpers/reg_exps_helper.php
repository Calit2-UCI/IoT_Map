<?php

/**
* Regular expressions helper
* 
* @package PG_Core
* @subpackage application
* @category	helpers
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Irina Lebedeva <irina@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2009-12-02 15:07:07 +0300 (Ср, 02 дек 2009) $ $Author: irina $
**/

if ( ! function_exists('get_numeric_reg_exps'))
{
	function get_numeric_reg_exps()
	{
		$CI = & get_instance();
		
		$CI->config->load('reg_exps');
		$regs = $CI->config->item('reg_exps');
		
		$numeric_field_types = array('float', 'int');
		
		$numeric_regs = array();
		foreach ($regs as $id=>$settings)
		{
			if (in_array($settings['field_type'], $numeric_field_types))
			{
				$numeric_regs[$id] = $settings;
			}
		}		
		return $numeric_regs;
	}
}