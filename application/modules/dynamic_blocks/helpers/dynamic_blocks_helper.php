<?php

/**
 * Dynamic blocks module
 *
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Dynamic blocks management
 * 
 * @subpackage 	Dynamic blocks
 * @category	helpers
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
if (!function_exists('dynamic_blocks_area')) {

    /**
     * Return content of dynamic blocks area
     * 
     * @param string $area_gid area guid
     * @return string
     */
    function dynamic_blocks_area($area_gid)
    {
        $CI = & get_instance();
        $CI->load->model("Dynamic_blocks_model");
        $area = $CI->Dynamic_blocks_model->get_area_by_gid($area_gid);
        if (empty($area)) {
            $area_gid = "index-page";
        }
        return $CI->Dynamic_blocks_model->html_area_blocks_by_gid($area_gid);
    }

}
