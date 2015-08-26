<?php

/**
 * Template_Lite  function plugin
 *
 */
function tpl_function_country_input($params, &$tpl) {
    $tpl->CI->load->helper('countries');
    
    if (!isset($params['select_type'])) $params['select_type'] = 'city';
    
    if (!isset($params['id_country'])) $params['id_country'] = '';
    if (!isset($params['id_region'])) $params['id_region'] = '';
    if (!isset($params['id_city'])) $params['id_city'] = '';
    if (!isset($params['id_district'])) $params['id_district'] = '';
    
    if (!isset($params['var_country'])) $params['var_country'] = 'id_country';
    if (!isset($params['var_region'])) $params['var_region'] = 'id_region';
    if (!isset($params['var_city'])) $params['var_city'] = 'id_city';
    if (!isset($params['var_district'])) $params['var_district'] = 'id_district';
    
    if (!isset($params['var_js'])) $params['var_js'] = '';
    if (!isset($params['placeholder'])) $params['placeholder'] = '';
    
    return country_input(
        $params['select_type'], $params['id_country'], $params['id_region'], 
        $params['id_city'], $params['var_country'], $params['var_region'], 
        $params['var_city'], $params['var_js'], $params['placeholder'], 
        $params['id_district'], $params['var_district']
    );
}
