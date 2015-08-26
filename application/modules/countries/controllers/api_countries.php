<?php

namespace Pg\Modules\Countries\Controllers;

/**
 * Countries api controller
 *
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @author Alexander Batukhtin <abatukhtin@pilotgroup.net>
 * */
class Api_Countries extends \Controller
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Countries_model');
    }

    /**
     * Returns locations
     *
     * @param string name
     */
    public function get_locations()
    {
        $name = $this->input->post('name', true);
        $data = array();
        if ($name) {
            $locations = $this->Countries_model->get_locations($name, array('priority' => 'ASC'), $this->pg_language->current_lang_id, $this->pg_language->languages);
            $data['count'] = count($locations['countries']) + count($locations['regions']) + count($locations['cities']);
            $data['items'] = $locations ? $locations : array();
            $this->set_api_content('data', array('name' => $name, 'data' => $data));
        } else {
            $this->set_api_content('error', l('error_name_empty', 'countries'));
        }
    }

    /**
     * Returns countries list
     *
     */
    public function get_countries()
    {
        $data = array();
        $countries = $this->Countries_model->get_countries(array('priority' => 'ASC'), array(), array(), $this->pg_language->current_lang_id);
        $count = count($countries);
        if ($count) {
            foreach ($countries as $country) {
                $data['items'][] = array(
                    'id' => $country['id'],
                    'code' => $country['code'],
                    'name' => $country['name'],
                );
            }
            $data['count'] = $count;
            $this->set_api_content('data', array('countries' => $data));
        } else {
            $this->set_api_content('messages', l('no_countries', 'countries'));
        }
    }

    /**
     * Returns regions by country
     *
     * @param string $country_code
     */
    public function get_regions()
    {

        $country_code = $this->input->post('country_code', true);
        if (!$country_code) {
            log_message('error', 'API: Empty country code');
            $this->set_api_content('error', l('api_error_empty_country_code', 'countries'));
            return false;
        }
        $regions['country'] = $this->Countries_model->get_country($country_code, $this->pg_language->current_lang_id);
        if (!$regions['country']) {
            log_message('error', 'API: Wrong country code ("' . $country_code . '")');
            $this->set_api_content('error', l('api_error_wrong_country_code', 'countries') . '("' . $country_code . '")');
            return false;
        }

        $regions = array();

        $regions = $this->Countries_model->get_regions($country_code, array('priority' => 'ASC'), array(), array(), $this->pg_language->current_lang_id);
        $count = count($regions);
        if ($count) {
            foreach ($regions as $region) {
                $regions['items'][] = array(
                    'id' => $region['id'],
                    'code' => $region['code'],
                    'name' => $region['name'],
                );
            }
        }
        $regions['count'] = $count;
        $this->set_api_content('data', array('country_code' => $country_code, 'regions' => $regions));
    }

    /**
     * Returns cities by region
     *
     * @param int $region_id
     * @param string $search
     * @param int $page
     */
    public function get_cities()
    {
        $cities = array();
        $params = array();
        $items_on_page = 100;

        $region_id = $this->input->post('region_id', true);
        if (!$region_id) {
            log_message('error', 'API: Empty region id');
            $this->set_api_content('error', l('api_error_empty_region_id', 'countries'));
            return false;
        }

        $cities['region'] = $this->Countries_model->get_region($region_id, $this->pg_language->current_lang_id);
        if (!$cities['region']) {
            log_message('error', 'API: Wrong region id ("' . $region_id . '")');
            $this->set_api_content('error', l('api_error_wrong_region_id', 'countries') . '("' . $region_id . '")');
            return false;
        }

        $cities['country'] = $this->Countries_model->get_country($cities['region']['country_code'], $this->pg_language->current_lang_id);
        if (!$cities['country']) {
            log_message('error', 'API: Wrong country code ("' . $cities['region']['country_code'] . '")');
            $this->set_api_content('error', l('api_error_wrong_country_code', 'countries') . '"(' . $cities['region']['country_code'] . ')"');
        }

        $search_string = trim(strip_tags($this->input->post('search', true)));

        $params['where']['id_region'] = $region_id;
        if (!empty($search_string)) {
            if ($this->pg_language->current_lang_id) {
                $var = 'lang_' . $this->pg_language->current_lang_id;
            } else {
                $var = 'name';
            }
            $params['where'][$var . ' LIKE'] = '%' . $search_string . '%';
        }
        $cities['count'] = $this->Countries_model->get_cities_count($params);
        $page = $this->input->post('page', true);
        $this->load->helper('sort_order');
        $page = get_exists_page_number($page, $cities['count'], $items_on_page);

        $cities['pages'] = ceil($cities['count'] / $items_on_page);
        $cities['current_page'] = $page;
        $cities['cities']['items'] = $this->Countries_model->get_cities($page, $items_on_page, array(), $params, array(), $this->pg_language->current_lang_id);
        $this->set_api_content('data', $cities);
    }

    /**
     * Returns location data
     *
     * @param string $type country/region/city
     * @param int|string $var Country code, region id or city id
     */
    public function get_data()
    {
        $type = $this->input->post('type', true);
        if (!$type) {
            log_message('error', 'API: Empty location type');
            $this->set_api_content('error', l('api_error_empty_location_type', 'countries'));
            return false;
        }
        $var = $this->input->post('var', true);
        if (!$var) {
            log_message('error', 'API: Empty location');
            $this->set_api_content('error', l('api_error_empty_location', 'countries'));
            return false;
        }
        $data = array();
        switch ($type) {
            case 'country':
                $data['country'] = $this->Countries_model->get_country($var, $this->pg_language->current_lang_id);
                break;
            case 'region':
                $data['region'] = $this->Countries_model->get_region($var, $this->pg_language->current_lang_id);
                $data['country'] = $this->Countries_model->get_country($data['region']['country_code'], $this->pg_language->current_lang_id);
                break;
            case 'city':
                $data['city'] = $this->Countries_model->get_city($var, $this->pg_language->current_lang_id);
                $data['region'] = $this->Countries_model->get_region($data['city']['id_region'], $this->pg_language->current_lang_id);
                $data['country'] = $this->Countries_model->get_country($data['city']['country_code'], $this->pg_language->current_lang_id);
                break;
        }
        $this->set_api_content('data', $data);
    }

}
