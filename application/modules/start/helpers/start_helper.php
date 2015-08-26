<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

if (!function_exists('demo_panel')) {

    function demo_panel($type = "user")
    {
        if (DEMO_MODE) {
            $CI = &get_instance();
            $html = $CI->template_lite->fetch("demo_panel", $type);
            echo $html;
        } elseif (TRIAL_MODE) {
            $CI = &get_instance();
            $html = $CI->template_lite->fetch("demo_panel_trial", $type);
            echo $html;
        }
    }

}

if (!function_exists('product_version')) {

    function product_version()
    {
        if (INSTALL_MODULE_DONE) {
            $CI = &get_instance();

            if ($CI->pg_module->is_module_installed('start')) {
                $current_version_code = $CI->pg_module->get_module_config('start', 'product_version_code');
                $current_version_name = $CI->pg_module->get_module_config('start', 'product_version_name');
                $formated_current_version = str_replace('_', '.', $current_version_name);

                $cache_new_version_code = $CI->pg_module->get_module_config('start', 'product_version_code_update');
                $cache_new_version_name = $CI->pg_module->get_module_config('start', 'product_version_name_update');

                $last_update = $CI->pg_module->get_module_config('start', 'product_version_last_update');
                if ((!$cache_new_version_code || $cache_new_version_code == $current_version_code) &&
                    (!$last_update || (time() - strtotime($last_update) > 24 * 60 * 60))) {
                    try {
                        $new_version = get_new_version($current_version);
                        $cache_new_version_code = intval($new_version['code']);
                        $cache_new_version_name = $new_version['name'];
                        $CI->pg_module->set_module_config('start', 'product_version_last_update', date('Y-m-d H:i:s'));
                    } catch (Exception $e) {
                        
                    }
                }

                if ($cache_new_version_code && $current_version_code < $cache_new_version_code) {
                    $formated_new_version = str_replace('_', '.', $cache_new_version_name);
                } else {
                    $formated_new_version = '';
                }

                $html = l('system_version', 'start') . ": " . $formated_current_version . " ";
                if ($formated_new_version) {
                    $html .= str_replace('[version]', $formated_new_version, l('system_version_available', 'start'));
                }
                echo $html;
            }
        }
    }

}

if (!function_exists('get_new_version')) {

    function get_new_version($current_version)
    {
        $CI = &get_instance();
        $CI->load->library('Snoopy');
        $CI->snoopy->read_timeout = 5;
        $CI->snoopy->rawheaders["Accept"] = "application/json";
        $CI->snoopy->fetch('http://www.pilotgroup.net/feeder/datingpro/version.php');
        if ($CI->snoopy->status == '200') {
            $new_version = (array) json_decode($CI->snoopy->results);
            $CI->pg_module->set_module_config('start', 'product_version_code_update', $new_version['code']);
            $CI->pg_module->set_module_config('start', 'product_version_name_update', $new_version['name']);
            return $new_version;
        } else {
            throw new Exception('error');
        }
    }

}

if (!function_exists('main_search_form')) {

    function main_search_form($object = 'user', $type = 'line', $show_data = false, $params = array())
    {
        $CI = &get_instance();

        // если пользователь незалогинен  то отображаем  вкладки и кнопки
        $user_id = $CI->session->userdata('user_id');
        $auth_type = $CI->session->userdata('auth_type');

        if (('user' === $object || 'perfect_match' === $object) && $CI->pg_module->is_module_installed('users')) {
            $CI->load->helper('users');
            $form_block = users_search_form($object, $type, $show_data);
        }

        if ($form_block) {
            $page_data = array(
                'form_id' => $object . '_' . $type,
                'show_tabs' => false,
                'show_resume_button' => false,
                'show_vacancy_button' => false,
                'object' => $object,
                'type' => $type,
                'hide_popup' => !empty($params['hide_popup']),
                'popup_autoposition' => !empty($params['popup_autoposition'])
            );
            if ($auth_type !== 'user' && $type != 'line') {
                $CI->load->helper('seo');
            }
            $CI->template_lite->assign('form_settings', $page_data);
            $CI->template_lite->assign('form_block', $form_block);
        }
        return $CI->template_lite->fetch("helper_search_form", 'user', 'start');
    }

}


if (!function_exists('selectbox')) {

    function selectbox($params)
    {
        $CI = &get_instance();
        if (empty($params['class'])) {
            $params['class'] = '';
        }
        foreach ($params as $key => $value) {
            $CI->template_lite->assign("sb_" . $key, $value);
        }
        return $CI->template_lite->fetch("helper_selectbox", 'user', 'start');
    }

}

if (!function_exists('hlbox')) {

    function hlbox($params)
    {
        $CI = &get_instance();
        foreach ($params as $key => $value) {
            $CI->template_lite->assign("hlb_" . $key, $value);
        }
        return $CI->template_lite->fetch("helper_hlbox", 'user', 'start');
    }

}

if (!function_exists('checkbox')) {

    function checkbox($params)
    {
        $CI = &get_instance();

        $cb_count = (!empty($params['value'])) ? count($params['value']) : 0;
        $CI->template_lite->assign("cb_count", $cb_count);

        $params['display_group_methods'] = false;
        if (isset($params['value'])) {
            $values = array();
            $selected = (!empty($params['selected'])) ? $params['selected'] : array();
            if (!is_array($selected)) {
                $selected = array($selected);
            }

            if (is_array($params['value'])) {
                foreach ($params['value'] as $key => $value) {
                    $values[$key] = array('name' => $value, 'checked' => (in_array($key, $selected)) ? 1 : 0);
                }
            } else {
                $values[1] = array('name' => '', 'checked' => $params['value']);
            }

            if (count($values) > 1 && !empty($params['group_methods'])) {
                $params['display_group_methods'] = true;
            }

            $params['value'] = $values;
        }

        unset($params['selected']);

        foreach ($params as $key => $value) {
            $CI->template_lite->assign("cb_" . $key, $value);
        }
        return $CI->template_lite->fetch("helper_checkbox", 'user', 'start');
    }

}

if (!function_exists('slider')) {

    function slider($params)
    {
        $CI = &get_instance();

        $slider['id'] = isset($params['id']) ? $params['id'] : 'slider' . substr(md5(microtime()), 0, 5);
        usleep(2);
        $slider['single'] = isset($params['single']) ? intval($params['single']) : 0;
        $slider['active_always'] = isset($params['active_always']) ? intval($params['active_always']) : 0;
        $slider['min'] = !is_null($params['min']) ? floatval($params['min']) : 0;
        $slider['max'] = !is_null($params['max']) ? floatval($params['max']) : 1000;
        $slider['value'] = isset($params['value']) ? floatval($params['value']) : floor(($slider['max'] - $slider['min']) / 2);
        $slider['value_min'] = isset($params['value_min']) ? floatval($params['value_min']) : $slider['min'];
        $slider['value_max'] = isset($params['value_max']) ? floatval($params['value_max']) : $slider['max'];
        $slider['use'] = (!empty($params['value_min']) || !empty($params['value_max']));
        if ($slider['value'] < $slider['min']) {
            $slider['value'] = $slider['min'];
        }
        if ($slider['value'] > $slider['max']) {
            $slider['value'] = $slider['max'];
        }
        if ($slider['value_min'] < $slider['min']) {
            $slider['value_min'] = $slider['min'];
        }
        if ($slider['value_max'] > $slider['max']) {
            $slider['value_max'] = $slider['max'];
        }
        $slider['field_name'] = isset($params['field_name']) ? $params['field_name'] : 'slider_field';
        $slider['field_name_min'] = isset($params['field_name_min']) ? $params['field_name_min'] : 'slider_field_min';
        $slider['field_name_max'] = isset($params['field_name_max']) ? $params['field_name_max'] : 'slider_field_max';

        $CI->template_lite->assign('slider_data', $slider);
        return $CI->template_lite->fetch('helper_slider', 'user', 'start');
    }

}

if (!function_exists('pagination')) {

    function pagination($params)
    {
        $CI = &get_instance();
        foreach ($params as $key => $value) {
            $CI->template_lite->assign("page_" . $key, $value);
        }
        return $CI->template_lite->fetch("helper_pagination", 'user', 'start');
    }

}

if (!function_exists('sorter')) {

    function sorter($params)
    {
        $CI = &get_instance();
        if (!$params["module"]) {
            $params["module"] = "start";
        }

        $params["rand"] = rand(0, 9999);
        foreach ($params as $key => $value) {
            $CI->template_lite->assign("sort_" . $key, $value);
        }
        return $CI->template_lite->fetch("helper_sorter", 'user', 'start');
    }

}

if (!function_exists('available_browsers')) {

    function available_browsers()
    {
        $CI = &get_instance();
        $html = $CI->template_lite->fetch("available_browsers", 'user');
        echo $html;
    }

}

if (!function_exists('currency_format_output')) {

    /**
     * Returns formatted currency string
     * Or unformatted if payments is not installed
     *
     * @param int $params['cur_id'] currency id
     * @param string $params['cur_gid'] currency gid
     * @param int $params['value'] amount
     * @param string $params['template']&nbsp;[abbr][value|dec_part:2|dec_sep:.|gr_sep:&nbsp;]
     * @param bool $params['no_tags']
     * @return string
     */
    function currency_format_output($params = array())
    {
        // {block name=currency_format_output module=payments value=30 cur_gid=RUR}
        $CI = & get_instance();
        if ($CI->pg_module->is_module_installed('payments')) {
            $CI->load->helper('payments');
            return currency_format($params);
        } elseif (empty($params['no_tags'])) {
            return '<span dir="ltr">' . (float) $params['value'] . '&nbsp;USD</span>';
        } else {
            return (float) $params['value'] . '&nbsp;USD';
        }
    }

}

if (!function_exists('lang_inline_editor')) {

    function lang_inline_editor($params)
    {
        $CI = &get_instance();
        $CI->template_lite->assign('multiple', (!empty($params['multiple']) ? 1 : 0));
        if (isset($params['textarea']) && $params['textarea']) {
            $CI->template_lite->assign('textarea', true);
        }
        $CI->template_lite->assign('rand', rand(100000, 999999));
        return $CI->template_lite->fetch("helper_lang_inline_editor_js", null, 'start');
    }

}

if (!function_exists('currency_output')) {

    /**
     * Returns unformatted currency string
     * Or unformatted if payments is not installed
     *
     * @param int $params['cur_id'] currency id
     * @param string $params['cur_gid'] currency gid
     * @param int $params['value'] amount
     * @param string $params['template']&nbsp;[abbr][value|dec_part:2|dec_sep:.|gr_sep:&nbsp;]
     * @return string
     */
    function currency_output($params = array())
    {
        $CI = & get_instance();
        if ($CI->pg_module->is_module_installed('payments')) {
            $CI->load->helper('payments');
            return currency($params);
        } else {
            return '<span dir="ltr">' . $params['value'] . '&nbsp;USD</span>';
        }
    }

}

if (!function_exists('currency_format_regexp_output')) {

    /**
     * Returns formatted currency string
     * Or unformatted if payments is not installed
     *
     * @param int $params['cur_id'] currency id
     * @param string $params['cur_gid'] currency gid
     * @param int $params['value'] amount
     * @param string $params['template']&nbsp;[abbr][value|dec_part:2|dec_sep:.|gr_sep:&nbsp;]
     * @return string
     */
    function currency_format_regexp_output($params = array())
    {
        $CI = & get_instance();
        if ($CI->pg_module->is_module_installed('payments')) {
            $CI->load->helper('payments');
            return currency_format_regexp($params);
        } else {
            return 'function(value){return value+\' USD\'}';
        }
    }

}

if (!function_exists('multiselect')) {

    function multiselect($params)
    {
        $CI = &get_instance();
        $rand = rand(100000, 999999);

        $filtered_params = filter_var_array($params, array(
            'fields' => array('flags' => FILTER_REQUIRE_ARRAY,),
            'selected' => array('flags' => FILTER_REQUIRE_ARRAY,),
            'limits' => array('flags' => FILTER_REQUIRE_ARRAY,),
            'all_text' => FILTER_SANITIZE_STRING,
            'all_value' => FILTER_SANITIZE_STRING,
            'min' => FILTER_VALIDATE_INT,
            'max' => FILTER_VALIDATE_INT,
        ));
        if (!isset($filtered_params['all_text'])) {
            $filtered_params['all_text'] = l('multiselect_all_text', 'start');
        }
        if (!isset($filtered_params['all_value'])) {
            $filtered_params['all_value'] = 'all';
        }
        // $filtered_params['selected'] are just keys. Fill them with values.
        $selected = array();
        $fields = array_keys($filtered_params['fields']);
        $all_selected = array();
        $selected_values = array();
        foreach (array_filter($filtered_params['selected']) as $field => $values) {
            if (!in_array($field, $fields)) {
                continue;
            }
            foreach ((array) $values as $value) {
                $selected_values[$field][$value] = $value;
                if ($filtered_params['all_value'] === $value) {
                    $selected[$field][$value] = $filtered_params['all_text'];
                    $all_selected[$field] = true;
                    break;
                } elseif (!empty($filtered_params['fields'][$field]['option'][$value])) {
                    $selected[$field][$value] = $filtered_params['fields'][$field]['option'][$value];
                }
            }
        }
        $has_selected = array_keys($selected ? : $filtered_params['fields']);
        $helper_data = array_merge($filtered_params, array(
            'rand' => $rand,
            'selected' => $selected,
            'selected_keys' => $selected_values,
            'active_field' => array_shift($has_selected),
            'all_selected' => $all_selected,
        ));
        $CI->template_lite->assign('multiselect_helper_data', $helper_data);
        return $CI->template_lite->fetch('helper_multiselect', 'user', 'start');
    }

}

if (!function_exists('ad')) {

    function ad()
    {
        if (FREE_VERSION) {
            $CI = &get_instance();
            $langs = $CI->pg_language->languages;
            $lang_id = $CI->pg_language->current_lang_id;
            echo $langs[$lang_id]['code'];
            $CI->template_lite->assign('lang_code', $langs[$lang_id]['code']);
            return $CI->template_lite->fetch('helper_admin_banner', null, 'start');
        }
    }

}
