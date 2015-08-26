<?php

use Pg\Modules\Countries\Models\Countries_model;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

if (!function_exists('location_select')) {

    function location_select($params)
    {
        $default_values = array(
            'select_type' => 'city',
            'var_country_name' => 'id_country',
            'var_region_name' => 'id_region',
            'var_city_name' => 'id_city',
        );
        $filtered_params = filter_var_array($params, array(
            'id_country' => FILTER_SANITIZE_STRING,
            'id_region' => FILTER_VALIDATE_INT,
            'id_city' => FILTER_VALIDATE_INT,
            'select_type' => FILTER_SANITIZE_STRING,
            'var_country_name' => FILTER_SANITIZE_STRING,
            'var_region_name' => FILTER_SANITIZE_STRING,
            'var_city_name' => FILTER_SANITIZE_STRING,
            'var_js_name' => FILTER_SANITIZE_STRING,
            'placeholder' => FILTER_SANITIZE_STRING,
        ));
        $tpl_vars = array_merge($filtered_params, $default_values);
        $tpl_vars['rand'] = rand(100000, 999999);
        $CI = &get_instance();
        $CI->load->model('Countries_model');
        if (!empty($tpl_vars['id_country'])) {
            $tpl_vars['country'] = $CI->Countries_model->get_country($tpl_vars['id_country']);
        }
        if (!empty($tpl_vars['id_region'])) {
            $tpl_vars['region'] = $CI->Countries_model->get_region($tpl_vars['id_region']);
        }
        if (!empty($tpl_vars['id_city'])) {
            $tpl_vars['city'] = $CI->Countries_model->get_city($tpl_vars['id_city']);
        }
        $tpl_vars['location_text'] = Countries_model::getLocationText(
                $tpl_vars['country'], $tpl_vars['region'], $tpl_vars['city']
        );

        $CI->load->model('countries/models/Countries_location_select_model');
        $CI->template_lite->assign('country_helper_data', $tpl_vars);
        return $CI->template_lite->fetch(
                $CI->Countries_location_select_model->getCurrentTplFile(), 'user', 'countries'
        );
    }

}

if (!function_exists('country')) {

    function country($id_country = '', $id_region = '', $id_city = '')
    {
        $CI = & get_instance();
        $CI->load->model("Countries_model");
        if (!empty($id_country)) {
            $data["country"] = $CI->Countries_model->get_country($id_country);
            $return_array[] = $data["country"]["name"];
        }
        if (!empty($id_region)) {
            $data["region"] = $CI->Countries_model->get_region($id_region);
            $return_array[] = $data["region"]["name"];
        }
        if (!empty($id_city)) {
            $data["city"] = $CI->Countries_model->get_city($id_city);
            $return_array[] = $data["city"]["name"];
        }
        $return = (is_array($return_array)) ? implode(', ', $return_array) : '';
        return $return;
    }

}

/*
 * Data is array( id => array(id_country, id_region, id_city), id => array(id_country, id_region, id_city), .....)
 * return array(id => str, id => str, id => str...);
 */
if (!function_exists('countries_output_format')) {

    function countries_output_format($data)
    {
        if (empty($data)) {
            return array();
        }
        $CI = &get_instance();
        $location_data = get_location_data($data, 'country');
        $country_template = $CI->pg_module->get_module_config('countries', 'output_country_format');

        $return = array();
        foreach ($data as $id => $location) {
            if (isset($location["country"]) && isset($location_data["country"][$location["country"]])) {
                $str = str_replace("[country]", $location_data["country"][$location["country"]]["name"], $country_template);
                $str = str_replace('[country_code]', $location["country"], $str);
            } else {
                $str = "";
            }
            $return[$id] = $str;
        }
        return $return;
    }

}

if (!function_exists('regions_output_format')) {

    /**
     * Return locations output names in region format
     * 
     * @param array $data locations data
     * @return string
     */
    function regions_output_format($data)
    {
        if (empty($data)) {
            return array();
        }
        $CI = &get_instance();
        $location_data = get_location_data($data, 'region');
        $country_template = $CI->pg_module->get_module_config('countries', 'output_country_format');
        $region_template = $CI->pg_module->get_module_config('countries', 'output_region_format');

        $return = array();
        foreach ($data as $id => $location) {
            $template = $country = $country_code = $region = '';
            if (isset($location["country"]) && !empty($location_data["country"][$location["country"]])) {
                $country = $location_data["country"][$location["country"]]["name"];
                $country_code = $location["country"];
                $template = $country_template;
            }

            if (isset($location["region"]) && !empty($location_data["region"][$location["region"]])) {
                $region = $location_data["region"][$location["region"]]["name"];
                $template = $region_template;
            }

            if ($template) {
                $template = str_replace("[country]", $country, $template);
                $template = str_replace("[country_code]", $country_code, $template);
                $template = str_replace("[region]", $region, $template);
            }
            $return[$id] = $template;
        }
        return $return;
    }

}

if (!function_exists('cities_output_format')) {

    function cities_output_format($data)
    {
        if (empty($data)) {
            return array();
        }
        $CI = & get_instance();
        $location_data = get_location_data($data, 'city');
        $country_template = $CI->pg_module->get_module_config('countries', 'output_country_format');
        $region_template = $CI->pg_module->get_module_config('countries', 'output_region_format');
        $city_template = $CI->pg_module->get_module_config('countries', 'output_city_format');

        $return = array();
        foreach ($data as $id => $location) {
            $template = $country = $country_code = $region = $city = '';
            if (isset($location["country"]) && !empty($location_data["country"][$location["country"]])) {
                $country = $location_data["country"][$location["country"]]["name"];
                $country_code = $location["country"];
                $template = $country_template;
            }

            if (isset($location["region"]) && !empty($location_data["region"][$location["region"]])) {
                $region = $location_data["region"][$location["region"]]["name"];
                $template = $region_template;
            }

            if (isset($location["city"]) && !empty($location_data["city"][$location["city"]])) {
                $city = $location_data["city"][$location["city"]]["name"];
                $template = $city_template;
            }

            if ($template) {
                $search = array('[country]', '[country_code]', '[region]', '[city]',);
                $replace = array($country, $country_code, $region, $city,);
                $template = str_replace($search, $replace, $template);
            }
            $return[$id] = $template;
        }
        return $return;
    }

}

if (!function_exists('get_location_data')) {

    /**
     * Return location data from identifiers
     * 
     * @param array $data location identifiers
     * @param string $type max level data type
     * @return array
     */
    function get_location_data($data, $type = 'city')
    {
        $CI = &get_instance();
        $CI->load->model("Countries_model");

        if (empty($data)) {
            return array();
        }

        $use_district = $CI->pg_module->get_module_config('countries', 'use_district');

        $return = $country_ids = $region_ids = $city_ids = $district_ids = array();
        foreach ($data as $set) {
            // country
            if (isset($set['country']) && !empty($set['country']) && !in_array($set['country'], $country_ids)) {
                $country_ids[] = $set['country'];
            }

            if ($type != 'country') {
                // region
                if (isset($set['region']) && !empty($set['region']) && !in_array($set['region'], $region_ids)) {
                    $region_ids[] = $set['region'];
                }

                if ($type != 'region') {
                    // city
                    if (isset($set['city']) && !empty($set['city']) && !in_array($set['city'], $city_ids)) {
                        $city_ids[] = $set['city'];
                    }

                    if ($type != 'city') {
                        // district
                        if (isset($set['district']) && !empty($set['district']) && !in_array($set['district'], $district_ids)) {
                            $district_ids[] = $set['district'];
                        }
                    }
                }
            }
        }

        if (!empty($country_ids)) {
            $return["country"] = $CI->Countries_model->get_countries_by_code($country_ids);
        }

        if (!empty($region_ids)) {
            $return["region"] = $CI->Countries_model->get_regions_by_id($region_ids);
        }

        if (!empty($city_ids)) {
            $return["city"] = $CI->Countries_model->get_cities_by_id($city_ids);
        }

        if (!empty($district_ids)) {
            $return["district"] = $CI->Countries_model->get_districts_by_id($district_ids);
        }

        return $return;
    }

}

