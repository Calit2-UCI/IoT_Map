<?php

namespace Pg\Modules\Countries\Controllers;

/**
 * Countries user side controller
 * 
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * */
class Countries extends \Controller
{
    /**
     * link to CodeIgniter object
     * @var object
     */

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Countries_model");
    }

    public function ajax_get_locations($name = '')
    {
        $return = array();
        if ($name) {
            $locations = $this->Countries_model->get_locations(
                str_replace(array(','), ' ', trim($name)), 
                array("priority" => "ASC"), 
                $this->pg_language->current_lang_id, 
                $this->pg_language->languages, 
                false
            );
            $return["all"] = count($locations['countries']) 
                + count($locations['regions']) 
                + count($locations['cities']);
            $return["items"] = $locations ? $locations : array();
        } else {
            $return["all"] = 0;
            $return["items"] = array();
        }
        echo json_encode($return);
        return;
    }

    public function ajax_get_countries()
    {
        $return = array();
        $lang_id = $this->pg_language->current_lang_id;
        $order_by['priority'] = 'ASC';
        $order_by['lang_' . $lang_id] = 'ASC';
        $countries = $this->Countries_model->get_countries($order_by, array(), array(), $lang_id);
        $return["all"] = count($countries);
        if ($return["all"]) {
            foreach ($countries as $country) {
                $return["items"][] = array(
                    "id" => $country["id"],
                    "code" => $country["code"],
                    "name" => $country["name"],
                );
            }
        }
        echo json_encode($return);
        return;
    }

    public function ajax_get_regions($country_code)
    {
        $return = array();
        $lang_id = $this->pg_language->current_lang_id;
        $order_by['priority'] = 'ASC';
        $order_by['lang_' . $lang_id] = 'ASC';
        $return["country"] = $this->Countries_model->get_country($country_code, $lang_id);
        $regions = $this->Countries_model->get_regions($country_code, $order_by, array(), array(), $lang_id);
        $return["all"] = count($regions);
        if ($return["all"]) {
            foreach ($regions as $region) {
                $return["items"][] = array(
                    "id" => $region["id"],
                    "code" => $region["code"],
                    "name" => $region["name"],
                );
            }
        }
        echo json_encode($return);
        return;
    }

    public function ajax_get_cities($id_region, $page = 1)
    {
        $search_string = trim(strip_tags($this->input->post('search', true)));
        $return = array();
        $lang_id = $this->pg_language->current_lang_id;
        $order_by['lang_' . $lang_id] = 'ASC';
        $items_on_page = 400;
        $return["region"] = $this->Countries_model->get_region($id_region, $lang_id);
        $return["country"] = $this->Countries_model->get_country($return["region"]["country_code"], $lang_id);

        $params["where"]["id_region"] = $id_region;
        if (!empty($search_string)) {
            $var = "name";
            if ($lang_id) {
                $var = 'lang_' . $lang_id;
            }
            $params["where"][$var . " LIKE"] = "%" . $search_string . "%";
        }
        $return["all"] = $this->Countries_model->get_cities_count($params);
        $return["pages"] = ceil($return["all"] / $items_on_page);
        $return["current_page"] = $page;
        $return["items"] = array_values($this->Countries_model->get_cities($page, $items_on_page, $order_by, $params, array(), $lang_id));

        echo json_encode($return);
        return;
    }

    public function ajax_get_form($type, $var = 0)
    {
        $data = array();

        switch ($type) {
            case "region":
                $data["country"] = $this->Countries_model->get_country($var, $this->pg_language->current_lang_id);
                break;
            case "city":
                $data["region"] = $this->Countries_model->get_region($var, $this->pg_language->current_lang_id);
                $data["country"] = $this->Countries_model->get_country($data["region"]["country_code"], $this->pg_language->current_lang_id);
                break;
        }

        $this->template_lite->assign("data", $data);
        $this->template_lite->assign("type", $type);
        $this->template_lite->view('ajax_country_form');
    }

    public function ajax_get_data($type, $var)
    {
        $data = array();

        switch ($type) {
            case "country":
                $data["country"] = $this->Countries_model->get_country($var, $this->pg_language->current_lang_id);
                break;
            case "region":
                $data["region"] = $this->Countries_model->get_region($var, $this->pg_language->current_lang_id);
                $data["country"] = $this->Countries_model->get_country($data["region"]["country_code"], $this->pg_language->current_lang_id);
                break;
            case "city":
                $data["city"] = $this->Countries_model->get_city($var, $this->pg_language->current_lang_id);
                $data["region"] = $this->Countries_model->get_region($data["city"]["id_region"], $this->pg_language->current_lang_id);
                $data["country"] = $this->Countries_model->get_country($data["city"]["country_code"], $this->pg_language->current_lang_id);
                break;
        }

        echo json_encode($data);
        return;
    }

}
