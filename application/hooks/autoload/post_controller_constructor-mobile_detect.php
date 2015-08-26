<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

if (!function_exists('mobile_detect')) {

    function mobile_detect()
    {
        if (!INSTALL_MODULE_DONE) {
            return false;
        }
        $CI = &get_instance();
        $CI->load->helper('cookie');

        $mobile_detect = filter_input(INPUT_GET, 'mobile_detect');
        if (empty($mobile_detect)) {
            $mobile_detect = filter_input(INPUT_COOKIE, 'mobile_detect');
        } elseif ('denied' === $mobile_detect) {
            // Back from the mobapp
            set_cookie(array(
                'name' => 'mobile_detect',
                'value' => 'denied',
                'expire' => time() + '86500',
                'domain' => COOKIE_SITE_SERVER,
                'path' => '/' . SITE_SUBFOLDER,
            ));
            return false;
        }

        if ('denied' === $mobile_detect || !$CI->pg_module->is_module_installed('mobile') || $CI->router->is_api_class || !$CI->pg_module->get_module_config('mobile', 'use_mobile_detect')) {
            return false;
        } else {
            $CI->load->library('mobile_detect');
            if ($CI->mobile_detect->isMobile()) {
                set_cookie(array(
                    'name' => 'mobile_detect',
                    'value' => 'ask',
                    'expire' => time() + '86500',
                    'domain' => COOKIE_SITE_SERVER,
                    'path' => '/' . SITE_SUBFOLDER,
                ));
                redirect($CI->pg_module->get_module_config('mobile', 'app_url') . '/#!/redirect');
            }
            return true;
        }
    }

}
