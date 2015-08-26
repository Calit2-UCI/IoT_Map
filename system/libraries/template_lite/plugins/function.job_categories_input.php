<?php

/**
 * Template_Lite  function plugin
 *
 */
function tpl_function_job_categories_input($params, &$tpl) {
    $tpl->CI->load->helper('properties');
    return job_categories_input($params['name'], $params['selected_categories'], $params['available_to_select'], $params['var_js']);
}
