<?php

/**
 * Template_Lite  function plugin
 *
 */
function tpl_function_user_input($params, &$tpl) {
    $tpl->CI->load->helper('users');
    return user_input($params['id_user'], $params['var_user'], $params['var_js'], $params['placeholder']);
}
