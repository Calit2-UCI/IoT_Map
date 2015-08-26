<?php

/**
 * Seo advanced module
 *
 * @package 	PG_Core
 * @copyright 	Copyright (c) 2000-2014 PG Core
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */

/**
 * Seo advanced management
 * 
 * @package 	PG_Core
 * @subpackage 	Seo_advanced
 * @category	helpers
 * @copyright 	Copyright (c) 2000-2014 PG Core
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
if ( ! function_exists('seo_traker'))
{
    /**
	 * Display tracker code
	 * 
	 * @param string $placement tracker placement
	 * @return void
	 */
	function seo_traker($placement='top')
	{
		$CI = &get_instance();
		$CI->load->model('Seo_advanced_model');
		$return = $CI->Seo_advanced_model->get_tracker_html($placement);
		echo $return;
	}

}
