<?php

/**
 * Template_Lite  function plugin
 *
 */
function tpl_function_friend_input($params, &$tpl) {
    /*$tpl->CI->load->helper('users_lists');
    return friend_input($params['id_user'], $params['var_user'], $params['var_js'], $params['placeholder']);*/
	$tpl->CI->load->helper('friendlist');
    return friend_input($params['id_user'], $params['var_user'], $params['var_js'], $params['placeholder']);
}
