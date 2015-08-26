<?php

namespace Pg\Modules\Countries\Models;

/**
 * Countries main model
 *
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @author Katya Kashkova <katya@pilotgroup.net>
 * @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
 * */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

define('COUNTRIES_TABLE', DB_PREFIX . 'cnt_countries');
define('REGIONS_TABLE', DB_PREFIX . 'cnt_regions');
define('CITIES_TABLE', DB_PREFIX . 'cnt_cities');
define('CACHE_COUNTRIES_TABLE', DB_PREFIX . 'cnt_cache_countries');
define('CACHE_REGIONS_TABLE', DB_PREFIX . 'cnt_cache_regions');
define('GEOBASE_URL', 'http://download.pilotgroup.net/geobase/wget/');

class Countries_model extends \Model
{

    private $CI;
    private $DB;
    private $db_insert_step = 100;
    private $cache_attrs_country = array('id', 'code', 'name', 'areainsqkm', 'continent', 'currency', 'region_update_date');
    private $cache_attrs_region = array('id', 'country_code', 'code', 'id_region', 'name');
    private $attrs_country = array('id', 'code', 'name', 'areainsqkm', 'continent', 'currency', 'priority', 'sorted');
    private $attrs_region = array('id', 'country_code', 'code', 'name', 'priority', 'sorted');
    private $attrs_city = array('id', 'id_region', 'name', 'latitude', 'longitude', 'country_code', 'region_code', 'priority', 'sorted');
    private $use_infile_city_install = true;
    private $city_install_step = 100;
    private $temp_server_city_id = 0;

    /**
     * Constructor
     *
     * @return
     */
    public function __construct()
    {
        parent::__construct();
        $this->CI = &get_instance();
        $this->DB = &$this->CI->db;

        $this->use_infile_city_install = $this->CI->pg_module->get_module_config('countries', 'use_infile_city_install');
    }

    /**
     *
     *  get country list from server
     *  put it in base cache
     *  return countries list
     *
     * @return array
     */
    private function wgetCountries()
    {
        $languages = array_values($this->CI->pg_language->languages);

        $langs_code = array();
        foreach ($languages as $language) {
            $langs_code[] = $language['code'];
        }

        $this->CI->load->library('Snoopy');
        $res = $this->CI->snoopy->fetch(GEOBASE_URL . 'get_countries/' . implode('-', $langs_code) . '/');

        if (!$res || !preg_match('/200 OK/i', $this->CI->snoopy->headers[0])) {
            return false;
        }
        $temp_geo_data = $this->CI->snoopy->results;

        $data = array();
        $temp_geo_array = preg_split('/\n/', $temp_geo_data);
        foreach ($temp_geo_array as $geo) {
            if (!strlen(trim($geo)))
                continue;
            $geo_array = preg_split("/\t/", $geo);

            $langs_names = array();
            foreach ($languages as $i => $language) {
                $langs_names['lang_' . $language['id']] = isset($geo_array[$i + 19]) ? $geo_array[$i + 19] : $geo_array[4];
            }

            $data[] = array_merge(array(
                'code' => $geo_array[0],
                'name' => $geo_array[4],
                'areainsqkm' => $geo_array[6],
                'continent' => $geo_array[8],
                'currency' => $geo_array[10],
                'region_update_date' => '0000-00-00 00:00:00',
                ), $langs_names);
        }
        return $data;
    }

    /**
     * Upload region list from server
     * 
     * Put it in base cache. Return region list.
     *
     * @param string $country_code country code
     * @return array
     */
    private function wgetRegions($country_code)
    {
        $languages = array_values($this->CI->pg_language->languages);

        $langs_code = array();
        foreach ($languages as $language) {
            $langs_code[] = $language['code'];
        }

        $this->CI->load->library('Snoopy');
        $res = $this->CI->snoopy->fetch(GEOBASE_URL . 'get_regions/' . $country_code . '/' . implode('-', $langs_code) . '/');

        if (!$res || !preg_match('/200 OK/i', $this->CI->snoopy->headers[0])) {
            return false;
        }
        $temp_geo_data = $this->CI->snoopy->results;

        $data = array();
        $temp_geo_array = preg_split('/\n/', $temp_geo_data);
        foreach ($temp_geo_array as $geo) {
            if (!strlen(trim($geo))) {
                continue;
            }

            $geo_array = preg_split("/\t/", $geo);

            $langs_names = array();
            foreach ($languages as $i => $language) {
                $langs_names['lang_' . $language['id']] = isset($geo_array[$i + 4]) ? $geo_array[$i + 4] : $geo_array[3];
            }

            $data[] = array_merge(
                array(
                'country_code' => $geo_array[1],
                'code' => $geo_array[2],
                'id_region' => $geo_array[0],
                'name' => $geo_array[3],
                ), $langs_names
            );
        }
        return $data;
    }

    /**
     * Upload city list from server
     * 
     * Put it in base cache. Return city list.
     *
     * @param string $country_code country code
     * @param integer $region_server_id region identifier
     * @param array $region_data region data
     * @return array
     */
    private function wgetCities($country_code, $region_server_id, $region_data)
    {
        //// get cities from server and return cities list
        //// if returned data && infile == true - save file and return filepath
        //// if returned data && infile == false - return data array
        //// if returned install clear and return

        $languages = $this->CI->pg_language->languages;
        ksort($languages);
        $languages = array_values($languages);

        $langs_code = array();
        foreach ($languages as $language) {
            $langs_code[] = $language['code'];
        }

        $this->CI->load->library('Snoopy');
        $res = $this->CI->snoopy->fetch(GEOBASE_URL . 'get_regions_cities/' . $country_code . "/" . $region_server_id . "/" . $this->temp_server_city_id . '/' . implode('-', $langs_code) . '/');

        if (!$res || !preg_match('/200 OK/i', $this->CI->snoopy->headers[0])) {
            return false;
        }

        $temp_geo_data = $this->CI->snoopy->results;

        if ('installed' === trim($temp_geo_data)) {
            $this->temp_server_city_id = 0;
            return true;
        }

        $data = array();
        $temp_geo_array = preg_split('/\n/', $temp_geo_data);
        foreach ($temp_geo_array as $geo) {
            if (!strlen(trim($geo))) {
                continue;
            }
            $geo_array = preg_split("/\t/", $geo);

            $langs_names = array();
            foreach ($languages as $i => $language) {
                $langs_names['lang_' . $language['id']] = isset($geo_array[$i + 20]) ? $geo_array[$i + 20] : $geo_array[2];
            }
            $data[] = array_merge(array(
                'id' => NULL,
                'id_region' => $region_data["id"],
                'name' => $geo_array[2],
                'latitude' => $geo_array[5],
                'longitude' => $geo_array[6],
                'country_code' => $country_code,
                'region_code' => $region_data["code"],
                'priority' => 0,
                'sorted' => 0,
                ), $langs_names);

            $this->temp_server_city_id = $geo_array[0];
        }

        $return = array("data" => $data, "file" => '');
        if ($this->use_infile_city_install) {
            $infile = "";
            foreach ($data as $city) {
                $infile .= implode("\t", $city) . "\n";
            }
            $path_to_file = TEMPPATH . 'countries/regions_' . $country_code . '.txt';
            $this->CI->load->helper('file');
            if (!write_file($path_to_file, $infile)) {
                $this->use_infile_city_install = false;
                return $return;
            } else {
                @chmod($path_to_file, 0777);
                $return["file"] = $path_to_file;
                return $return;
            }
        } else {
            return $return;
        }
    }

    /**
     * Return country list from cache
     * 
     * If cache is empty or expiries - wget_countries
     * Save update date in module settings
     *
     * @return array
     */
    public function get_cache_countries()
    {
        $expiried_period = $this->CI->pg_module->get_module_config('countries', 'countries_update_period');

        $lang_id = $this->CI->pg_language->current_lang_id;

        $last_update = $this->CI->pg_module->get_module_config('countries', 'countries_last_update');

        $langs_names = array();
        foreach ($this->pg_language->languages as $i => $language) {
            $langs_names[] = 'lang_' . $language['id'];
        }

        $this->DB->select(implode(", ", array_merge($this->cache_attrs_country, $langs_names)))->from(CACHE_COUNTRIES_TABLE)->order_by('lang_' . $lang_id . ' ASC')->order_by('name ASC');
        $results = $this->DB->get()->result_array();

        if (empty($results) || (!empty($last_update) && $last_update + $expiried_period < time()) || empty($last_update)) {
            $results = $this->wgetCountries();
            if (empty($results)) {
                return array();
            }

            $counter = 0;
            $data_count = count($results);
            $this->DB->query('TRUNCATE TABLE ' . CACHE_COUNTRIES_TABLE . '');

            $start_sql = "INSERT INTO " . CACHE_COUNTRIES_TABLE . " (" . implode(',', array_merge(array('code', 'name', 'areainsqkm', 'continent', 'currency', 'region_update_date'), $langs_names)) . ") VALUES  ";

            while ($counter < $data_count) {
                unset($strings);
                $temp_geo = array_slice($results, $counter, $this->db_insert_step);
                foreach ($temp_geo as $data) {
                    $lang_string = '';
                    foreach ($langs_names as $lang_name) {
                        $lang_string .= ", '" . addslashes($data[$lang_name]) . "'";
                    }
                    $strings[] = "( '" . $data["code"] . "', '" . addslashes($data["name"]) . "', '" . $data["areainsqkm"] . "', '" . $data["continent"] . "', '" . $data["currency"] . "', '" . $data["region_update_date"] . "'" . $lang_string . ")";
                }

                $query = $start_sql . implode(", ", $strings);
                $this->DB->query($query);
                $counter = $counter + $this->db_insert_step;
            }

            $this->CI->pg_module->set_module_config('countries', 'countries_last_update', time());
        }

        foreach ($results as $key => $result) {
            if (empty($result['lang_' . $lang_id])) {
                continue;
            }
            $results[$key]['name'] = $result['lang_' . $lang_id];
        }

        return $results;
    }

    /**
     * Return country object from cache by code
     *
     * @param string $country_code country code
     * @return array
     */
    public function get_cache_country_by_code($country_code)
    {
        $langs_names = array();
        foreach ($this->pg_language->languages as $i => $language) {
            $langs_names[] = 'lang_' . $language['id'];
        }
        $this->DB->select(implode(", ", array_merge($this->cache_attrs_country, $langs_names)))->from(CACHE_COUNTRIES_TABLE)->where("code", $country_code);
        $data = $this->DB->get()->result_array();
        if (!empty($data)) {
            $lang_id = $this->pg_language->current_lang_id;
            if (!empty($data[0]['lang_' . $lang_id])) {
                $data[0]['name'] = $data[0]['lang_' . $lang_id];
            }
            return $data[0];
        }
        return array();
    }

    /**
     * Return region list from cache
     * 
     * If cache is empty or expiries - wget_regions
     * Save update date in cache region table
     *
     * @param string $country_code country code
     * @param array $country_cache_data country data from cache
     * @return array
     */
    public function get_cache_regions($country_code, $country_cache_data = array())
    {
        $expiried_period = $this->CI->pg_module->get_module_config('countries', 'countries_update_period');

        if (empty($country_cache_data)) {
            $country_cache_data = $this->get_cache_country_by_code($country_code);
        }

        $lang_id = $this->CI->pg_language->current_lang_id;

        $last_update = (!empty($country_cache_data["region_update_date"])) ? $country_cache_data["region_update_date"] : "0000-00-00 00:00:00";
        $last_update = intval(strtotime($last_update));

        $langs_names = array();
        foreach ($this->pg_language->languages as $i => $language) {
            $langs_names[] = 'lang_' . $language['id'];
        }

        $this->DB->select(implode(", ", array_merge($this->cache_attrs_region, $langs_names)))->from(CACHE_REGIONS_TABLE)->where('country_code', $country_code)->order_by('lang_' . $lang_id . ' ASC')->order_by('code ASC');
        $results = $this->DB->get()->result_array();

        if (empty($results) || (!empty($last_update) && $last_update + $expiried_period < time()) || empty($last_update)) {
            $results = $this->wgetRegions($country_code);
            if (empty($results)) {
                return array();
            }

            $counter = 0;
            $data_count = count($results);
            $this->DB->where('country_code', $country_code);
            $this->DB->delete(CACHE_REGIONS_TABLE);

            $start_sql = "INSERT INTO " . CACHE_REGIONS_TABLE . " (" . implode(',', array_merge(array('country_code', 'code', 'id_region', 'name'), $langs_names)) . ") VALUES  ";

            while ($counter < $data_count) {
                unset($strings);
                $temp_geo = array_slice($results, $counter, $this->db_insert_step);
                foreach ($temp_geo as $data) {
                    $lang_string = '';
                    foreach ($langs_names as $lang_name) {
                        if (!empty($data[$lang_name])) {
                            $lang_string .= ", '" . addslashes($data[$lang_name]) . "'";
                        }
                    }
                    $strings[] = "('" . $data["country_code"] . "', '" . $data["code"] . "', '" . $data["id_region"] . "', '" . addslashes($data["name"]) . "'" . $lang_string . ")";
                }

                $query = $start_sql . implode(", ", $strings);
                $this->DB->query($query);
                $counter = $counter + $this->db_insert_step;
            }

            $cdata["region_update_date"] = date('Y-m-d H:i:s');
            $this->DB->where("code", $country_code);
            $this->DB->update(CACHE_COUNTRIES_TABLE, $cdata);
        }

        foreach ($results as $key => $result) {
            if (empty($result['lang_' . $lang_id])) {
                continue;
            }
            $results[$key]['name'] = $result['lang_' . $lang_id];
        }

        return $results;
    }

    /**
     * Return region object from cache by code
     *
     * @param string $country_code country code
     * @param string $region_code region code
     * @return array
     */
    public function get_cache_region_by_code($country_code, $region_code)
    {
        $langs_names = array();
        foreach ($this->pg_language->languages as $i => $language) {
            $langs_names[] = 'lang_' . $language['id'];
        }
        $this->DB->select(implode(", ", array_merge($this->cache_attrs_region, $langs_names)))->from(CACHE_REGIONS_TABLE)->where("country_code", $country_code)->where("code", $region_code);
        $data = $this->DB->get()->result_array();
        if (!empty($data)) {
            $lang_id = $this->pg_language->current_lang_id;
            if (!empty($data[0]['lang_' . $lang_id])) {
                $data[0]['name'] = $data[0]['lang_' . $lang_id];
            }
            return $data[0];
        }
        return array();
    }

    /**
     * Return countries objects as array
     * 
     * @param array $order_by sorting data
     * @param array $params filters data
     * @param array $filter_object_ids filters identifiers
     * @param integer $lang_id language identifier
     * @return array
     */
    public function get_countries($order_by = array(), $params = array(), $filter_object_ids = array(), $lang_id = false)
    {
        /// return installed countries

        if ($lang_id === false) {
            $lang_id = $this->CI->pg_language->current_lang_id;
        }

        $select_attrs = $this->attrs_country;
        if ($lang_id) {
            $select_attrs = array_diff($select_attrs, array('name'));
            $select_attrs[] = 'lang_' . $lang_id . ' as name';
        }

        $this->DB->select(implode(", ", $select_attrs))->from(COUNTRIES_TABLE);

        if (isset($params["where"]) && is_array($params["where"]) && count($params["where"])) {
            foreach ($params["where"] as $field => $value) {
                $this->DB->where($field, $value);
            }
        }

        if (isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])) {
            foreach ($params["where_in"] as $field => $value) {
                $this->DB->where_in($field, $value);
            }
        }

        if (isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])) {
            foreach ($params["where_sql"] as $value) {
                $this->DB->where($value);
            }
        }

        if (isset($filter_object_ids) && is_array($filter_object_ids) && count($filter_object_ids)) {
            $this->DB->where_in("id", $filter_object_ids);
        }

        if (is_array($order_by) && count($order_by) > 0) {
            $all_fields = $this->attrs_country;
            foreach ($this->CI->pg_language->languages as $lang_id => $lang_data) {
                $all_fields[] = 'lang_' . $lang_id;
            }
            foreach ($order_by as $field => $dir) {
                if (in_array($field, $all_fields)) {
                    $this->DB->order_by($field . " " . $dir);
                }
            }
        }

        $results = $this->DB->get()->result_array();
        if (!empty($results) && is_array($results)) {
            $data = array();
            foreach ($results as $r) {
                $this->country_cache[$r['code']] = $data[$r['code']] = $r;
            }
            return $data;
        }
        return array();
    }

    public function get_countries_by_code($filter_object_ids = array(), $lang_id = false)
    {
        /// return installed regions

        $select_attrs = $this->attrs_country;
        if ($lang_id) {
            $select_attrs[] = 'lang_' . $lang_id . ' as name';
        }

        $this->DB->select(implode(", ", $select_attrs))->from(COUNTRIES_TABLE);

        if (isset($filter_object_ids) && is_array($filter_object_ids) && count($filter_object_ids)) {
            $this->DB->where_in("code", $filter_object_ids);
        }

        $data = array();
        $results = $this->DB->get()->result_array();
        if (!empty($results) && is_array($results)) {
            foreach ($results as $r) {
                $data[$r["code"]] = $r;
            }
            return $data;
        }
        return array();
    }

    /**
     * Return number of countries
     * 
     * @param array $params filters parameters
     * @return integer
     */
    public function get_countries_count($params = array())
    {
        $this->DB->select('COUNT(*) AS cnt')->from(COUNTRIES_TABLE);

        if (isset($params["where"]) && is_array($params["where"]) && count($params["where"])) {
            foreach ($params["where"] as $field => $value) {
                $this->DB->where($field, $value);
            }
        }

        if (isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])) {
            foreach ($params["where_in"] as $field => $value) {
                $this->DB->where_in($field, $value);
            }
        }

        if (isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])) {
            foreach ($params["where_sql"] as $value) {
                $this->DB->where($value, null, false);
            }
        }

        $results = $this->DB->get()->result_array();
        if (!empty($results) && is_array($results)) {
            return intval($results[0]["cnt"]);
        }
        return 0;
    }

    /**
     * Return regions objects as array
     * 
     * @param string $country_code country code
     * @param array $order_by sorting data
     * @param array $params filters parameters
     * @param array $filter_object_ids filters identifiers
     * @param integer $lang_id language identifier
     * @return array
     */
    public function get_regions($country_code, $order_by = array(), $params = array(), $filter_object_ids = array(), $lang_id = false)
    {
        /// return installed regions

        if ($lang_id === false) {
            $lang_id = $this->CI->pg_language->current_lang_id;
        }

        $select_attrs = $this->attrs_region;
        if ($lang_id) {
            $select_attrs = array_diff($select_attrs, array('name'));
            $select_attrs[] = 'lang_' . $lang_id . ' as name';
        }

        $this->DB->select(implode(", ", $select_attrs))->from(REGIONS_TABLE)->where("country_code", $country_code);

        if (isset($params["where"]) && is_array($params["where"]) && count($params["where"])) {
            foreach ($params["where"] as $field => $value) {
                $this->DB->where($field, $value);
            }
        }

        if (isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])) {
            foreach ($params["where_in"] as $field => $value) {
                $this->DB->where_in($field, $value);
            }
        }

        if (isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])) {
            foreach ($params["where_sql"] as $value) {
                $this->DB->where($value);
            }
        }

        if (isset($filter_object_ids) && is_array($filter_object_ids) && count($filter_object_ids)) {
            $this->DB->where_in("id", $filter_object_ids);
        }

        if (is_array($order_by) && count($order_by) > 0) {
            $all_fields = $this->attrs_region;
            foreach ($this->CI->pg_language->languages as $lang_id => $lang_data) {
                $all_fields[] = 'lang_' . $lang_id;
            }
            foreach ($order_by as $field => $dir) {
                if (in_array($field, $all_fields)) {
                    $this->DB->order_by($field . " " . $dir);
                }
            }
        }
        $results = $this->DB->get()->result_array();
        if (!empty($results) && is_array($results)) {
            $data = array();
            foreach ($results as $r) {
                $this->region_cache[$r['id']] = $data[$r['id']] = $r;
            }
            return $data;
        }
        return array();
    }

    /**
     * Return region objects by identifiers
     * 
     * @param array $filter_object_ids region identifiers
     * @param integer $lang_id language identifier
     * @return array
     */
    public function get_regions_by_id($filter_object_ids = array(), $lang_id = false)
    {
        /// return installed regions

        if ($lang_id === false) {
            $lang_id = $this->CI->pg_language->current_lang_id;
        }

        $select_attrs = $this->attrs_region;
        if ($lang_id) {
            $select_attrs[] = 'lang_' . $lang_id . ' as name';
        }

        if (!isset($filter_object_ids) || !is_array($filter_object_ids)) {
            return array();
        }

        $data = array();

        foreach ($filter_object_ids as $index => $id) {
            if (isset($this->region_cache[$id])) {
                $data[$id] = $this->region_cache[$id];
                unset($filter_object_ids[$index]);
            }
        }

        if (empty($filter_object_ids)) {
            return $data;
        }

        $this->DB->select(implode(", ", $select_attrs))->from(REGIONS_TABLE);

        if (isset($filter_object_ids) && is_array($filter_object_ids) && count($filter_object_ids)) {
            $this->DB->where_in("id", $filter_object_ids);
        }

        $results = $this->DB->get()->result_array();
        if (!empty($results) && is_array($results)) {
            foreach ($results as $r) {
                $this->region_cache[$r["id"]] = $data[$r["id"]] = $r;
            }
            return $data;
        }
        return array();
    }

    /**
     * Return region objects by code
     * 
     * @param string $country_code country code
     * @param array $order_by sorting data
     * @param array $params filters parameters
     * @param array $filter_object_ids filters identifiers
     * @return array
     */
    public function get_regions_by_code($country_code, $order_by = array(), $params = array(), $filter_object_ids = array())
    {
        $data = array();
        $regions = $this->get_regions($country_code, $order_by, $params, $filter_object_ids);
        if (!empty($regions) && is_array($regions)) {
            foreach ($regions as $r) {
                if (!empty($r["code"]))
                    $data[$r["code"]] = $r;
            }
        }
        return $data;
    }

    /**
     * Return cities objects as array
     * 
     * @param integer $page page of results
     * @param integer $items_on_page results per page
     * @param array $order_by sorting data
     * @param array $params filters parameters
     * @param array $filter_object_ids filters identifiers
     * @param integer $lang_id language identifier
     * @return array
     */
    public function get_cities($page = null, $items_on_page = null, $order_by = array(), $params = array(), $filter_object_ids = array(), $lang_id = false)
    {
        /// return installed cities

        if ($lang_id === false) {
            $lang_id = $this->CI->pg_language->current_lang_id;
        }

        $select_attrs = $this->attrs_city;
        if ($lang_id) {
            $select_attrs = array_diff($select_attrs, array('name'));
            $select_attrs[] = 'lang_' . $lang_id . ' as name';
        }

        $this->DB->select(implode(", ", $select_attrs))->from(CITIES_TABLE);

        if (isset($params["where"]) && is_array($params["where"]) && count($params["where"])) {
            foreach ($params["where"] as $field => $value) {
                $this->DB->where($field, $value);
            }
        }

        if (isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])) {
            foreach ($params["where_in"] as $field => $value) {
                $this->DB->where_in($field, $value);
            }
        }

        if (isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])) {
            foreach ($params["where_sql"] as $value) {
                $this->DB->where($value);
            }
        }

        if (isset($filter_object_ids) && is_array($filter_object_ids) && count($filter_object_ids)) {
            $this->DB->where_in("id", $filter_object_ids);
        }

        if (is_array($order_by) && count($order_by) > 0) {
            $all_fields = $this->attrs_city;
            foreach ($this->CI->pg_language->languages as $lang_id => $lang_data) {
                $all_fields[] = 'lang_' . $lang_id;
            }
            foreach ($order_by as $field => $dir) {
                if (in_array($field, $all_fields)) {
                    $this->DB->order_by($field . " " . $dir);
                }
            }
        }
        if (!is_null($page)) {
            $page = intval($page) ? intval($page) : 1;
            $this->DB->limit($items_on_page, $items_on_page * ($page - 1));
        }
        $results = $this->DB->get()->result_array();

        if (!empty($results) && is_array($results)) {
            $data = array();
            foreach ($results as $r) {
                $this->city_cache[$r['id']] = $data[$r['id']] = $r;
            }
            return $data;
        }
        return array();
    }

    /**
     * Return number of cities
     * 
     * @param array $params filters parameters
     * @param array $filter_object_ids filters identifiers
     * @return integer
     */
    public function get_cities_count($params = array(), $filter_object_ids = array())
    {
        /// return installed cities
        $this->DB->select("COUNT(*) AS cnt")->from(CITIES_TABLE);

        if (isset($params["where"]) && is_array($params["where"]) && count($params["where"])) {
            foreach ($params["where"] as $field => $value) {
                $this->DB->where($field, $value);
            }
        }

        if (isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])) {
            foreach ($params["where_in"] as $field => $value) {
                $this->DB->where_in($field, $value);
            }
        }

        if (isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])) {
            foreach ($params["where_sql"] as $value) {
                $this->DB->where($value);
            }
        }

        if (isset($filter_object_ids) && is_array($filter_object_ids) && count($filter_object_ids)) {
            $this->DB->where_in("id", $filter_object_ids);
        }

        $results = $this->DB->get()->result_array();
        if (!empty($results) && is_array($results)) {
            return intval($results[0]["cnt"]);
        }
        return 0;
    }

    /**
     * Return cities objects as array
     * 
     * @param array $filter_object_ids filters identifiers
     * @param integer $lang_id language identifier
     * @return array
     */
    public function get_cities_by_id($filter_object_ids = array(), $lang_id = false)
    {
        /// return installed cities

        if ($lang_id === false) {
            $lang_id = $this->CI->pg_language->current_lang_id;
        }

        $select_attrs = $this->attrs_city;
        if ($lang_id) {
            $select_attrs = array_diff($select_attrs, array('name'));
            $select_attrs[] = 'lang_' . $lang_id . ' as name';
        }

        if (!isset($filter_object_ids) || !is_array($filter_object_ids)) {
            return array();
        }

        $data = array();

        foreach ($filter_object_ids as $index => $id) {
            if (isset($this->city_cache[$id])) {
                $data[$id] = $this->city_cache[$id];
                unset($filter_object_ids[$index]);
            }
        }

        if (empty($filter_object_ids)) {
            return $data;
        }

        $this->DB->select(implode(", ", $select_attrs))->from(CITIES_TABLE);

        if (isset($filter_object_ids) && is_array($filter_object_ids) && count($filter_object_ids)) {
            $this->DB->where_in("id", $filter_object_ids);
        }

        $results = $this->DB->get()->result_array();
        if (!empty($results) && is_array($results)) {
            foreach ($results as $r) {
                $this->city_cache[$r['id']] = $data[$r['id']] = $r;
            }
            return $data;
        }
        return array();
    }

    /**
     * Return cities objects by radius
     * 
     * @param float $lat point latitude
     * @param float $lon point longitude
     * @param integer $radius search radius
     * @param string $radius_type radius measurement
     * @param integer $page page of results
     * @param integer $items_on_page results per page
     * @param array $params filters parameters
     * @param array $filter_object_ids filters identifiers
     * @return array
     */
    public function get_cities_by_radius($lat, $lon, $radius = 10, $radius_type = "km", $page = null, $items_on_page = null, $params = array(), $filter_object_ids = array())
    {
        $lang_id = $this->CI->pg_language->current_lang_id;

        $select_attrs = $this->attrs_city;
        $select_attrs = array_diff($select_attrs, array('name'));
        $select_attrs[] = 'lang_' . $lang_id . ' as name';

        /// return installed cities
        $this->DB->select(implode(", ", $select_attrs))->from(CITIES_TABLE);

        $radius = ($radius_type == "mile") ? $radius : $radius * 0.6213712;
        $this->DB->where('(POW((69.1*(lon-"' . $lon . '")*cos(' . $lat . '/57.3)),"2")+POW((69.1*(lat-"' . $lat . '")),"2"))<(' . ($radius * $radius) . ')');


        if (isset($params["where"]) && is_array($params["where"]) && count($params["where"])) {
            foreach ($params["where"] as $field => $value) {
                $this->DB->where($field, $value);
            }
        }

        if (isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])) {
            foreach ($params["where_in"] as $field => $value) {
                $this->DB->where_in($field, $value);
            }
        }

        if (isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])) {
            foreach ($params["where_sql"] as $value) {
                $this->DB->where($value);
            }
        }

        if (isset($filter_object_ids) && is_array($filter_object_ids) && count($filter_object_ids)) {
            $this->DB->where_in("id", $filter_object_ids);
        }

        if (!is_null($page)) {
            $page = intval($page) ? intval($page) : 1;
            $this->DB->limit($items_on_page, $items_on_page * ($page - 1));
        }

        $results = $this->DB->get()->result_array();
        if (!empty($results) && is_array($results)) {
            $data = array();
            foreach ($results as $r) {
                $this->city_cache[$r['id']] = $data[$r['id']] = $r;
            }
            return $data;
        }
        return array();
    }

    /**
     * Return country object by code
     * 
     * @param string $country_code country code
     * @param integer $lang_id language identifier
     * @param array $languages languages data
     * @return array
     */
    public function get_country($country_code, $lang_id = false, $languages = array())
    {
        /// return installed country

        $cache_enabled = false;

        if ($lang_id === false) {
            $lang_id = $this->CI->pg_language->current_lang_id;
        }

        $select_attrs = $this->attrs_country;
        if ($lang_id) {
            $select_attrs = array_diff($select_attrs, array('name'));
            $select_attrs[] = 'lang_' . $lang_id . ' as name';
            $cache_enabled = true;
        }
        if (is_array($languages) && count($languages) > 0) {
            foreach ($languages as $id => $value)
                $select_attrs[] = 'lang_' . $id . ' as lang_' . $id;
            $cache_enabled = false;
        }

        if ($cache_enabled && isset($this->country_cache[$country_code])) {
            return $this->country_cache[$country_code];
        }

        $this->DB->select(implode(", ", $select_attrs))->from(COUNTRIES_TABLE)->where("code", $country_code);
        $results = $this->DB->get()->result_array();
        if (!empty($results) && is_array($results)) {
            if ($cache_enabled)
                $this->country_cache[$country_code] = $results[0];
            return $results[0];
        }
        return array();
    }

    /**
     * Return country object by identifier
     * 
     * @param integer $country_id country identifier
     * @return array
     */
    public function get_country_by_id($country_id)
    {
        /// return installed country
        $this->DB->select(implode(", ", $this->attrs_country))->from(COUNTRIES_TABLE)->where("id", $country_id);
        $results = $this->DB->get()->result_array();
        if (!empty($results) && is_array($results)) {
            return $results[0];
        }
        return array();
    }

    /**
     * Return region object by identifier
     * 
     * @param integer $region_id region identifier
     * @param integer $lang_id language identifier
     * @param array $languages languages data
     * @return array
     */
    public function get_region($region_id, $lang_id = false, $languages = array())
    {
        /// return installed region

        $cache_enabled = false;

        if ($lang_id === false) {
            $lang_id = $this->CI->pg_language->current_lang_id;
        }

        $select_attrs = $this->attrs_region;
        if ($lang_id) {
            $select_attrs = array_diff($select_attrs, array('name'));
            $select_attrs[] = 'lang_' . $lang_id . ' as name';
            $cache_enabled = true;
        }
        if (is_array($languages) && count($languages) > 0) {
            foreach ($languages as $id => $value)
                $select_attrs[] = 'lang_' . $id . ' as lang_' . $id;
            $cache_enabled = false;
        }

        if ($cache_enabled && isset($this->region_cache[$region_id])) {
            return $this->region_cache[$region_id];
        }

        $this->DB->select(implode(", ", $select_attrs))->from(REGIONS_TABLE)->where("id", $region_id);
        $results = $this->DB->get()->result_array();
        if (!empty($results) && is_array($results)) {
            if ($cache_enabled) {
                $this->region_cache[$region_id] = $results[0];
            }
            return $results[0];
        }
        return array();
    }

    /**
     * Return region object by code
     * 
     * @param string $region_code region code
     * @param string $country_code country code
     * @return array
     */
    public function get_region_by_code($region_code, $country_code = null)
    {
        /// return installed region
        $this->DB->select(implode(", ", $this->attrs_region));
        $this->DB->from(REGIONS_TABLE);
        if ($country_code) {
            $this->DB->where("country_code", $country_code);
        }
        $this->DB->where("code", $region_code);
        $results = $this->DB->get()->result_array();
        if (!empty($results) && is_array($results)) {
            return $results[0];
        }
        return array();
    }

    /**
     * Return city object by identifier
     * 
     * @param integer $city_id city identifier
     * @param integer $lang_id language identifier
     * @param array $languages languages data
     * @return array
     */
    public function get_city($city_id, $lang_id = false, $languages = array())
    {
        /// return installed city

        $cache_enabled = false;

        if ($lang_id === false) {
            $lang_id = $this->CI->pg_language->current_lang_id;
        }

        $select_attrs = $this->attrs_city;
        if ($lang_id) {
            $select_attrs = array_diff($select_attrs, array('name'));
            $select_attrs[] = 'lang_' . $lang_id . ' as name';
            $cache_enabled = true;
        }

        if (is_array($languages) && count($languages) > 0) {
            foreach ($languages as $id => $value) {
                $select_attrs[] = 'lang_' . $id . ' as lang_' . $id;
            }
            $cache_enabled = false;
        }

        if ($cache_enabled && isset($this->city_cache[$city_id])) {
            return $this->city_cache[$city_id];
        }

        $this->DB->select(implode(", ", $select_attrs))->from(CITIES_TABLE)->where("id", $city_id);
        $results = $this->DB->get()->result_array();
        if (!empty($results) && is_array($results)) {
            if ($cache_enabled) {
                $this->city_cache[$city_id] = $results[0];
            }
            return $results[0];
        }

        return array();
    }

    private function getQueryStringForLangs($value, array $languages = array())
    {
        $where = array();
        foreach ($languages as $lid => $lang_value) {
            $where[] = "lang_" . $lid . " like " . $this->DB->escape('%' . $value . '%');
        }
        return implode(' OR ', $where);
    }

    /**
     * Return location objects as array
     * 
     * @param string $loc_name location name
     * @param array $order_by sorting data
     * @param integer $lang_id language identifier
     * @param array $languages languages data
     * @param integer $country country identifier
     * @param integer $region region identifier
     * @param integer $city city identifier
     * @return array
     */
    public function get_locations($loc_name, $order_by = array(), $lang_id = false, $languages = array(), $country = null, $region = null, $city = null)
    {
        $return = array();
        if ($loc_name) {
            $where_str = '';
            $loc_names = array();
            if (!is_array($loc_name)) {
                $loc_name = trim($loc_name);
                $filter = array(',', '.', '?', '!', '+', '-', '–', '—', '/', '\\', '-');
                $loc_names[] = $loc_name;
                $loc_names[] = preg_replace('/\s+/', ' ', str_ireplace($filter, ' ', $loc_name));
            }
            //$loc_names = array_unique(explode(' ', $loc_name));
            foreach ($loc_names as $loc_subname) {
                if (empty($loc_subname)) {
                    continue;
                }
                if(strlen($where_str) > 0) {
                    $where_str .=  " OR";
                }
                $where_str .= " name like " . $this->DB->escape($loc_subname . '%') . " ";
                $query_string_for_langs = $this->getQueryStringForLangs($loc_subname, $languages);
                if ($query_string_for_langs) {
                    $where_str .=  'OR ' . $query_string_for_langs;
                }
            }
        }

        // Search in countries
        if (!$country) {
            $select_attrs = $this->attrs_country;
            if ($lang_id) {
                $select_attrs = array_diff($select_attrs, array('name'));
                $select_attrs[] = 'lang_' . $lang_id . ' as name';
            }

            $this->DB->select(implode(", ", $select_attrs))->from(COUNTRIES_TABLE);

            if ($where_str) {
                $this->DB->where($where_str, null, false);
            }

            $this->DB->limit(50);
            if (is_array($order_by) && count($order_by) > 0) {
                foreach ($order_by as $field => $dir) {
                    if (in_array($field, $this->attrs_country)) {
                        $this->DB->order_by($field . " " . $dir);
                    }
                }
            }

            $results = $this->DB->get()->result_array();
            $return['countries'] = $results ? $results : array();

            if (!empty($return['countries'])) {
                $this->CI->load->helper('countries');
                $locations_ids = array();

                foreach ($return['countries'] as $key => $row_countries) {
                    $locations_ids[$key] = array(
                        'country' => $row_countries['code'],
                    );

                    $list_locations = countries_output_format($locations_ids);
                    $return['countries'][$key]['name'] = $list_locations[$key];
                }
            }
        } else {
            $return['countries'] = array();
        }

        // Search in regions
        if (!$region) {
            $select_attrs = $this->attrs_region;
            if ($lang_id) {
                $select_attrs = array_diff($select_attrs, array('name'));
                $select_attrs[] = 'lang_' . $lang_id . ' as name';
            }

            $this->DB->select(implode(", ", $select_attrs))->from(REGIONS_TABLE);
            if ($country) {
                $this->DB->where('country_code', $country);
            }
            if ($where_str) {
                $this->DB->where('(' . $where_str . ')', null, false);
            }
            $this->DB->limit(50);

            if (is_array($order_by) && count($order_by) > 0) {
                foreach ($order_by as $field => $dir) {
                    if (in_array($field, $this->attrs_region)) {
                        $this->DB->order_by($field . " " . $dir);
                    }
                }
            }
            $results = $this->DB->get()->result_array();
            $return['regions'] = $results ? $results : array();

            if (!empty($return['regions'])) {

                $this->CI->load->helper('countries');
                $locations_ids = array();

                foreach ($return['regions'] as $key => $row_region) {
                    $locations_ids[$key] = array(
                        'country' => $row_region['country_code'],
                        'region' => $row_region['id'],
                    );

                    $list_locations = regions_output_format($locations_ids);
                    $return['regions'][$key]['name'] = $list_locations[$key];
                }
            }
        } else {
            $return['regions'] = array();
        }

        // Search in cities
        if (!$city) {
            $select_attrs = $this->attrs_city;
            if ($lang_id) {
                $select_attrs = array_diff($select_attrs, array('name'));
                $select_attrs[] = 'lang_' . $lang_id . ' as name';
            }
            $this->DB->select(implode(", ", $select_attrs))->from(CITIES_TABLE);
            if ($country) {
                $this->DB->where('country_code', $country);
            }
            if ($region) {
                $this->DB->where('id_region', $region);
            }
            if ($where_str) {
                $this->DB->where('(' . $where_str . ')', null, false);
            }
            $this->DB->limit(50);
            if (is_array($order_by) && count($order_by) > 0) {
                foreach ($order_by as $field => $dir) {
                    if (in_array($field, $this->attrs_city)) {
                        $this->DB->order_by($field . " " . $dir);
                    }
                }
            }
            $results = $this->DB->get()->result_array();
            $return['cities'] = $results ? $results : array();

            if (!empty($return['cities'])) {

                $this->CI->load->helper('countries');
                $locations_ids = array();

                foreach ($return['cities'] as $key => $row_cities) {
                    $locations_ids[$key] = array(
                        'country' => $row_cities['country_code'],
                        'region' => $row_cities['id_region'],
                        'city' => $row_cities['id'],
                    );

                    $list_locations = cities_output_format($locations_ids);
                    $return['cities'][$key]['name'] = $list_locations[$key];
                }
            }
        } else {
            $return['cities'] = array();
        }

        // Search in districts
        $use_district = $this->CI->pg_module->get_module_config('countries', 'use_district');
        if ($use_district) {
            $select_attrs = $this->attrs_district;
            if ($lang_id) {
                $select_attrs = array_diff($select_attrs, array('name'));
                $select_attrs[] = 'lang_' . $lang_id . ' as name';
            }
            $this->DB->select(implode(", ", $select_attrs))->from(DISTRICTS_TABLE);
            if ($country) {
                $this->DB->where('country_code', $country);
            }
            if ($region) {
                $this->DB->where('id_region', $region);
            }
            if ($city) {
                $this->DB->where('id_city', $city);
            }
            if ($where_str) {
                $this->DB->where('(' . $where_str . ')', null, false);
            }
            $this->DB->limit(50);
            if (is_array($order_by) && count($order_by) > 0) {
                foreach ($order_by as $field => $dir) {
                    if (in_array($field, $this->attrs_city)) {
                        $this->DB->order_by($field . " " . $dir);
                    }
                }
            }
            $results = $this->DB->get()->result_array();
            $return['districts'] = $results ? $results : array();

            if (!empty($return['districts'])) {

                $this->CI->load->helper('countries');
                $locations_ids = array();

                foreach ($return['districts'] as $key => $row_districts) {
                    $locations_ids[$key] = array(
                        'country' => $row_districts['country_code'],
                        'region' => $row_districts['id_region'],
                        'city' => $row_districts['id_city'],
                        'district' => $row_districts['id'],
                    );

                    $list_locations = districts_output_format($locations_ids);
                    $return['districts'][$key]['name'] = $list_locations[$key];
                }
            }
        } else {
            $return['districts'] = array();
        }

        return $return;
    }

    /**
     * Fill locations with parents
     * @param array $locations
     * @param string $attrs_country
     * @param string $attrs_region
     * @return array
     */
    private function fillParents(&$locations, $attrs_country, $attrs_region)
    {
        $country_codes = array_keys($locations['countries']);
        $region_codes = array_keys($locations['regions']);
        $req_country_codes = array();
        $req_region_ids = array();
        $missing_countries = array();
        $missing_regions = array();

        $set_country_code = function(&$location) use($country_codes, $locations, &$req_country_codes) {
            if (in_array($location['country_code'], $country_codes)) {
                $location['country'] = & $locations['countries'][$location['country_code']];
            } else {
                $req_country_codes[] = $location['country_code'];
            }
        };
        foreach ($locations['cities'] as &$city) {
            $set_country_code($city);
            if (in_array($city['id_region'], $region_codes)) {
                $city['region'] = &$locations['regions'][$city['id_region']];
            } else {
                $req_region_ids[] = $city['id_region'];
            }
        }
        foreach ($locations['regions'] as &$region) {
            $set_country_code($region);
        }
        if (!empty($req_country_codes)) {
            $this->DB->select(implode(', ', $attrs_country))
                ->from(COUNTRIES_TABLE)
                ->where_in('code', array_unique($req_country_codes));
            foreach ($this->DB->get()->result_array() as $country) {
                $missing_countries[$country['code']] = $country;
            }
        }
        if (!empty($req_region_ids)) {
            $this->DB->select(implode(', ', $attrs_region))
                ->from(REGIONS_TABLE)
                ->where_in('id', $req_region_ids);
            foreach ($this->DB->get()->result_array() as $missing_region) {
                $missing_regions[$missing_region['code']] = $missing_region;
            }
        }

        foreach ($locations['cities'] as &$city) {
            if (empty($city['country']) && !empty($missing_countries[$city['country_code']])) {
                $city['country'] = $missing_countries[$city['country_code']];
            }
            if (empty($city['region']) && !empty($missing_regions[$city['region_code']])) {
                $city['region'] = $missing_regions[$city['region_code']];
            }
        }
        foreach ($locations['regions'] as &$region) {
            if (empty($region['country']) && !empty($missing_countries[$region['country_code']])) {
                $region['country'] = $missing_countries[$region['country_code']];
            }
        }
        return $locations;
    }

    public function get_region_by_name($region_name, $country_code = null, $lang_id = null)
    {
        if (is_null($lang_id)) {
            $where_str = "`name` LIKE '%$region_name%'";
        } elseif (is_array($lang_id)) {
            foreach ($lang_id as $id) {
                $where_str[] = "`lang_$id` LIKE '%$region_name%'";
            }
            $where_str = implode(' OR ', $where_str);
        } else {
            $where_str = "`lang_$lang_id` LIKE '%$region_name%'";
        }
        if ($country_code) {
            $where_str = "($where_str) AND `country_code` = '$country_code'";
        }

        $results = $this->DB->select(implode(", ", $this->attrs_region))
                ->from(REGIONS_TABLE)
                ->where($where_str, null, false)
                ->limit(1)
                ->get()->result_array();
        if ($results) {
            return $results[0];
        } else {
            array();
        }
    }

    public function get_city_by_name($city_name, $region_id = null, $country_code = null, $lang_id = null)
    {
        if (is_null($lang_id)) {
            $where_str = "`name` LIKE '%$city_name%'";
        } elseif (is_array($lang_id)) {
            foreach ($lang_id as $id) {
                $where_str[] = "`lang_$id` LIKE '%$city_name%'";
            }
            $where_str = implode(' OR ', $where_str);
        } else {
            $where_str = "`lang_$lang_id` LIKE '%$city_name%'";
        }
        if ($region_id) {
            $where_str = "($where_str) AND `id_region` = '$region_id'";
        }
        if ($country_code) {
            $where_str = "($where_str) AND `country_code` = '$country_code'";
        }

        $results = $this->DB->select(implode(", ", $this->attrs_city))
                ->from(CITIES_TABLE)
                ->where($where_str, null, false)
                ->limit(1)
                ->get()->result_array();
        if ($results) {
            return $results[0];
        } else {
            array();
        }
    }

    public function install_country($country_code)
    {
        $country_data = $this->get_cache_country_by_code($country_code);
        if (empty($country_data)) {
            return false;
        }
        $insert_data = array(
            'code' => $country_code,
            'name' => $country_data["name"],
            'areainsqkm' => $country_data["areainsqkm"],
            'continent' => $country_data["continent"],
            'currency' => $country_data["currency"],
            'priority' => $this->get_country_max_priority() + 1,
        );

        foreach ($this->pg_language->languages as $id => $value) {
            $insert_data['lang_' . $id] = $country_data['name'];
        }

        $this->save_country($country_code, $insert_data, "add");
    }

    public function install_cities($country_code, $region_code, $languages = array())
    {
        /// set country installed
        /// set region installed
        /// get region data(new id and region code)
        /// while wget_cities !== true||false => wget_cities and install sities (use_infile_city_install)

        $country_data = $this->get_country($country_code);
        if (!$country_data) {
            $country_data = $this->get_cache_country_by_code($country_code);
            if (empty($country_data)) {
                return false;
            }
            $insert_data = array(
                'code' => $country_code,
                'name' => $country_data["name"],
                'areainsqkm' => $country_data["areainsqkm"],
                'continent' => $country_data["continent"],
                'currency' => $country_data["currency"],
                "priority" => $this->get_country_max_priority() + 1,
                "sorted" => 0,
            );
            if (is_array($languages) && count($languages) > 0) {
                foreach ($languages as $id => $value) {
                    $insert_data['lang_' . $id] = $country_data["name"];
                }
            }
            $this->save_country($country_code, $insert_data, "add");
        }

        $cache_region_data = $this->get_cache_region_by_code($country_code, $region_code);
        if (!$cache_region_data) {
            return false;
        }
        $region_server_id = $cache_region_data["id_region"];

        $region_data = $this->get_region_by_code($region_code, $country_code);
        if (!$region_data) {
            $insert_data = array(
                'country_code' => $country_code,
                'code' => $region_code,
                'name' => $cache_region_data["name"],
                "priority" => $this->get_region_max_priority($country_code) + 1,
            );
            if (is_array($languages) && count($languages) > 0) {
                foreach ($languages as $id => $value) {
                    $insert_data['lang_' . $id] = $cache_region_data['lang_' . $id];
                }
            }
            $id_region = $this->save_region(null, $insert_data);

            $region_data = $this->get_region($id_region);
        }

        $this->DB->where("id_region", $region_data["id"]);
        $this->DB->delete(CITIES_TABLE);

        $install_data = 'start';
        $max_itaration = 20;
        $itaration_counter = 0;

        if ($this->use_infile_city_install && is_array($languages) && count($languages) > 0) {
            $sql_query = "UPDATE " . CITIES_TABLE . " SET ";
            $sql_set = "";
            $sql_where = "";
            foreach ($languages as $id => $value) {
                $sql_set .= strlen($sql_set) > 0 ? ", lang_" . $id . " = name" : "lang_" . $id . " = name";
                $sql_where .= strlen($sql_where) ? " AND lang_" . $id . " = '' " : " lang_" . $id . " = '' ";
            }
            $languages_query = $sql_query . $sql_set . " WHERE " . $sql_where;

            if (isset($region_data["id"])) {
                $languages_query .= " AND id_region = " . $region_data["id"];
            }
        }

        while ($install_data !== true && $install_data !== false) {
            if ($itaration_counter > $max_itaration) {
                break;
            }
            $install_data = $this->wgetCities($country_code, $region_server_id, $region_data);

            $itaration_counter++;
            if ($install_data !== true && $install_data !== false) {
                if ($this->use_infile_city_install) {
                    if (file_exists($install_data["file"])) {
                        $sql_result = $this->DB->simple_query("LOAD DATA INFILE '" . $install_data["file"] . "' INTO TABLE " . CITIES_TABLE . "  FIELDS TERMINATED BY '\t';");
                        if ($sql_result === false) {
                            $this->use_infile_city_install = false;
                        }
                    }
                }

                if (!$this->use_infile_city_install) {
                    $counter = 0;
                    $data_count = count($install_data["data"]);
                    $start_sql = "INSERT INTO " . CITIES_TABLE . " (id_region, name, latitude, longitude, country_code, region_code";
                    if (is_array($languages) && count($languages) > 0) {
                        foreach ($languages as $id => $value) {
                            $start_sql .= ", lang_" . $id;
                        }
                    }
                    $start_sql .= ") VALUES ";

                    while ($counter < $data_count) {
                        unset($strings);
                        $temp_geo = array_slice($install_data["data"], $counter, $this->city_install_step);
                        foreach ($temp_geo as $incity) {
                            $incity["name"] = trim($incity["name"]);
                            $incity["lang_".$id] = trim($incity["lang_".$id]);
							$string = "(" . $this->DB->escape($region_data["id"]) . ", " . $this->DB->escape($incity["name"]) . ", " . $this->DB->escape($incity["latitude"]) . ", " . $this->DB->escape($incity["longitude"]) . ", " . $this->DB->escape($country_code) . ", " . $this->DB->escape($region_code) . "";
							if (is_array($languages) && count($languages) > 0){
								foreach ($languages as $id => $value){
									$string .= ", " . $this->DB->escape($incity["lang_".$id]) . "";
								}
							}
							$string .= ")";
                            $strings[] = $string;
                        }
                        $query = $start_sql . implode(", ", $strings);
                        $this->DB->query($query);
                        $counter = $counter + $this->city_install_step;
                    }
                }
            }
        }

        if ($this->use_infile_city_install && !empty($languages_query)) {
            $this->CI->db->query($languages_query);
        }
        return true;
    }

    public function save_country($country_code, $data, $type = "add", $langs = array())
    {
        if (is_array($langs) && count($langs) > 0) {
            foreach ($langs as $id => $value) {
                $data['lang_' . $id] = $value;
            }
        }
        if ($type == "add") {
            if ($country_code) {
                $data["code"] = $country_code;
            }
            $this->DB->insert(COUNTRIES_TABLE, $data);
        } else {
            $this->DB->where('code', $country_code);
            $this->DB->update(COUNTRIES_TABLE, $data);
        }
        return;
    }

    public function save_region($id_region, $data, $langs = array())
    {
        if (is_array($langs) && count($langs) > 0) {
            foreach ($langs as $id => $value) {
                $data['lang_' . $id] = $value;
            }
        }

        if (empty($id_region)) {
            $this->DB->insert(REGIONS_TABLE, $data);
            $id_region = $this->DB->insert_id();
        } else {
            $this->DB->where('id', $id_region);
            $this->DB->update(REGIONS_TABLE, $data);
        }
        return $id_region;
    }

    public function save_city($id_city, $data, $langs = array())
    {
        if (is_array($langs) && count($langs) > 0) {
            foreach ($langs as $id => $value) {
                $data['lang_' . $id] = $value;
            }
        }
        if (empty($id_city)) {
            $this->DB->insert(CITIES_TABLE, $data);
            $id_city = $this->DB->insert_id();
        } else {
            $this->DB->where('id', $id_city);
            $this->DB->update(CITIES_TABLE, $data);
        }
        return $id_city;
    }

    public function delete_country($country_code)
    {
        $this->DB->where('code', $country_code);
        $this->DB->delete(COUNTRIES_TABLE);

        $this->DB->where('country_code', $country_code);
        $this->DB->delete(REGIONS_TABLE);

        $this->DB->where('country_code', $country_code);
        $this->DB->delete(CITIES_TABLE);
        return;
    }

    public function delete_region($id_region)
    {
        $this->DB->where('id', $id_region);
        $this->DB->delete(REGIONS_TABLE);

        $this->DB->where('id_region', $id_region);
        $this->DB->delete(CITIES_TABLE);
        return;
    }

    public function delete_city($id_city)
    {
        $this->DB->where('id', $id_city);
        $this->DB->delete(CITIES_TABLE);
        return;
    }

    public function validate($type, $id, $data)
    {
        $return = array("errors" => array(), "data" => array());

        if ($type == "country") {
            if (empty($id) && empty($data["code"])) {
                $return["errors"][] = l('error_code_empty', 'countries');
            } elseif (isset($data["code"])) {
                $return["data"]["code"] = strtoupper(substr(strip_tags($data["code"]), 0, 2));

                if (empty($id) || $id != $return["data"]["code"]) {
                    $params["where"]["code"] = $return["data"]["code"];
                    $countries = $this->get_countries(array(), $params);
                    if (!empty($countries)) {
                        $return["errors"][] = l('error_code_already_exists', 'countries');
                    }
                }
            }
        }

        if ($type == "region") {

            if (isset($data["country_code"])) {
                $return["data"]["country_code"] = strtoupper(substr(strip_tags($data["country_code"]), 0, 2));
                if (empty($return["data"]["country_code"])) {
                    $return["errors"][] = l('error_code_empty', 'countries');
                }
            }

            if (isset($data["code"])) {
                $return["data"]["code"] = strip_tags($data["code"]);
                if (empty($return["data"]["code"])) {
                    $return["errors"][] = l('error_region_code_empty', 'countries');
                }
            }
        }

        if ($type == "city") {
            if (isset($data["country_code"])) {
                $return["data"]["country_code"] = strtoupper(substr(strip_tags($data["country_code"]), 0, 2));
                if (empty($return["data"]["country_code"])) {
                    $return["errors"][] = l('error_code_empty', 'countries');
                }
            }
            if (isset($data["region_code"])) {
                $return["data"]["region_code"] = strip_tags($data["region_code"]);
                if (empty($return["data"]["region_code"])) {
                    $return["errors"][] = l('error_region_code_empty', 'countries');
                }
            }
            if (isset($data["id_region"])) {
                $return["data"]["id_region"] = intval($data["id_region"]);
                if (empty($return["data"]["id_region"])) {
                    $return["errors"][] = l('error_region_empty', 'countries');
                }
            }
            if (isset($data["latitude"])) {
                $return["data"]["latitude"] = strval($data["latitude"]);
            }
            if (isset($data["longitude"])) {
                $return["data"]["longitude"] = strval($data["longitude"]);
            }
        }

        if (isset($data["name"])) {
            $return["data"]["name"] = strip_tags($data["name"]);
            if (empty($return["data"]["name"])) {
                $return["errors"][] = l('error_name_empty', 'countries');
            }
        }

        return $return;
    }

    public function get_country_max_priority()
    {
        $this->DB->select("MAX(priority) as max_priority")->from(COUNTRIES_TABLE);
        $results = $this->DB->get()->result_array();
        if (!empty($results) && is_array($results)) {
            return intval($results[0]["max_priority"]);
        }
        return 0;
    }

    public function get_region_max_priority($country_code)
    {
        $this->DB->select("MAX(priority) as max_priority")->from(REGIONS_TABLE)->where("country_code", $country_code);
        $results = $this->DB->get()->result_array();
        if (!empty($results) && is_array($results)) {
            return intval($results[0]["max_priority"]);
        }
        return 0;
    }

    /**
     * Return max priority of cities
     * 
     * @param string $country_code country code
     * @param string $region_id region identifier
     * @return integer
     */
    public function get_city_max_priority($country_code, $region_id)
    {
        $this->DB->select("MAX(priority) as max_priority")
            ->from(CITIES_TABLE)
            ->where("country_code", $country_code)
            ->where("id_region", $region_id);
        $results = $this->DB->get()->result_array();
        if (!empty($results) && is_array($results)) {
            return intval($results[0]["max_priority"]);
        }
        return 0;
    }

    /**
     * Save city object priority
     * 
     * @param string $city_id city identifier
     * @param integer $priority priority value
     * @return void
     */
    public function set_city_priority($city_id, $priority)
    {
        $data["priority"] = intval($priority);
        $data["sorted"] = 1;
        $this->DB->where("id", $city_id);
        $this->DB->update(CITIES_TABLE, $data);
    }

    /**
     * Save country object priority
     * 
     * @param string $country_code city identifier
     * @param integer $priority priority value
     * @return void
     */
    public function set_country_priority($country_code, $priority)
    {
        $data["priority"] = intval($priority);
        $data["sorted"] = 1;
        $this->DB->where("code", $country_code);
        $this->DB->update(COUNTRIES_TABLE, $data);
    }

    /**
     * Save region object priority
     * 
     * @param string $id_region region identifier
     * @param integer $priority priority value
     * @return void
     */
    public function set_region_priority($id_region, $priority)
    {
        $data["priority"] = intval($priority);
        $data["sorted"] = 1;
        $this->DB->where("id", $id_region);
        $this->DB->update(REGIONS_TABLE, $data);
    }

    /**
     * Save default countries object priority
     * 
     * @param array $countries_array
     * @return void
     */
    public function set_default_priority_to_countries($countries_array)
    {
        $data["priority"] = sizeof($countries_array) + 1;
        $data["sorted"] = 0;
        $this->DB->where_not_in("code", $countries_array);
        $this->DB->update(COUNTRIES_TABLE, $data);
    }

    /**
     * Save default regions object priority
     * 
     * @param string $country_code country code
     * @param array $regions_array
     * @return void
     */
    public function set_default_priority_to_regions($country_code, $regions_array)
    {
        $data["priority"] = sizeof($regions_array) + 1;
        $data["sorted"] = 0;
        $this->DB->where("country_code", $country_code);
        if ($data["priority"] > 1) {
            $this->DB->where_not_in("id", $regions_array);
        }
        $this->DB->update(REGIONS_TABLE, $data);
    }

    /**
     * Save default cities object priority
     * 
     * @param string $country_code country code
     * @param string $id_region region identifier
     * @param array $cities_array
     * @return void
     */
    public function set_default_priority_to_cities($country_code, $id_region, $cities_array)
    {
        $data["priority"] = sizeof($cities_array) + 1;
        $data["sorted"] = 0;
        $this->DB->where("id_region", $id_region);
        $this->DB->where("country_code", $country_code);
        if ($data["priority"] > 1) {
            $this->DB->where_not_in("id", $cities_array);
        }
        $this->DB->update(CITIES_TABLE, $data);
    }

    public function lang_dedicate_module_callback_add($lang_id = false)
    {
        if (!$lang_id) {
            return false;
        }
        $this->CI->load->dbforge();
        $fields["lang_" . $lang_id] = array(
            'type' => 'VARCHAR',
            'constraint' => '255',
            'null' => FALSE,
        );
        $default_lang_id = $this->CI->pg_language->get_default_lang_id();
        // Add contries column
        $table_query = $this->CI->db->get(COUNTRIES_TABLE);
        $exists_fields = $table_query->list_fields();
        $this->CI->dbforge->add_column(COUNTRIES_TABLE, $fields);
        // Set default names
        if (in_array("lang_" . $default_lang_id, $exists_fields)) {
            $this->CI->db->set('lang_' . $lang_id, 'lang_' . $default_lang_id, false);
        } else {
            $this->CI->db->set('lang_' . $lang_id, 'name', false);
        }
        $this->CI->db->update(COUNTRIES_TABLE);
        // Add regions column
        $table_query = $this->CI->db->get(REGIONS_TABLE);
        $exists_fields = $table_query->list_fields();
        $this->CI->dbforge->add_column(REGIONS_TABLE, $fields);
        // Set default name
        if (in_array("lang_" . $default_lang_id, $exists_fields)) {
            $this->CI->db->set('lang_' . $lang_id, 'lang_' . $default_lang_id, false);
        } else {
            $this->CI->db->set('lang_' . $lang_id, 'name', false);
        }
        $this->CI->db->update(REGIONS_TABLE);
        // Add cities column
        $table_query = $this->CI->db->get(CITIES_TABLE);
        $exists_fields = $table_query->list_fields();
        $this->CI->dbforge->add_column(CITIES_TABLE, $fields);
        // Set default names
        if (in_array("lang_" . $default_lang_id, $exists_fields)) {
            $this->CI->db->set('lang_' . $lang_id, 'lang_' . $default_lang_id, false);
        } else {
            $this->CI->db->set('lang_' . $lang_id, 'name', false);
        }
        $this->CI->db->update(CITIES_TABLE);

        // Add contries cache column
        $exists_fields = $this->CI->db->list_fields(CACHE_COUNTRIES_TABLE);
        $this->CI->dbforge->add_column(CACHE_COUNTRIES_TABLE, $fields);
        // Set default names
        if (in_array("lang_" . $default_lang_id, $exists_fields)) {
            $this->CI->db->set('lang_' . $lang_id, 'lang_' . $default_lang_id, false);
        } else {
            $this->CI->db->set('lang_' . $lang_id, 'name', false);
        }
        $this->CI->db->update(CACHE_COUNTRIES_TABLE);

        // Add regions cache column
        $exists_fields = $this->CI->db->list_fields(CACHE_REGIONS_TABLE);
        $this->CI->dbforge->add_column(CACHE_REGIONS_TABLE, $fields);
        // Set default name
        if (in_array("lang_" . $default_lang_id, $exists_fields)) {
            $this->CI->db->set('lang_' . $lang_id, 'lang_' . $default_lang_id, false);
        } else {
            $this->CI->db->set('lang_' . $lang_id, 'name', false);
        }
        $this->CI->db->update(CACHE_REGIONS_TABLE);
    }

    public function lang_dedicate_module_callback_delete($lang_id = false)
    {
        if (!$lang_id) {
            return false;
        }
        $field_name = "lang_" . $lang_id;
        $this->CI->load->dbforge();
        // Delete countries column
        $table_query = $this->CI->db->get(COUNTRIES_TABLE);
        if (in_array("lang_" . $lang_id, $table_query->list_fields())) {
            $this->CI->dbforge->drop_column(COUNTRIES_TABLE, $field_name);
        }
        // Delete regions column
        $table_query = $this->CI->db->get(REGIONS_TABLE);
        if (in_array("lang_" . $lang_id, $table_query->list_fields())) {
            $this->CI->dbforge->drop_column(REGIONS_TABLE, $field_name);
        }
        // Delete cities column
        $table_query = $this->CI->db->get(CITIES_TABLE);
        if (in_array("lang_" . $lang_id, $table_query->list_fields())) {
            $this->CI->dbforge->drop_column(CITIES_TABLE, $field_name);
        }
        // Delete countries cache column
        $fields_exists = $this->CI->db->list_fields(CACHE_COUNTRIES_TABLE);
        if (in_array("lang_" . $lang_id, $fields_exists)) {
            $this->CI->dbforge->drop_column(CACHE_COUNTRIES_TABLE, $field_name);
        }

        // Delete regions column
        $fields_exists = $this->CI->db->list_fields(CACHE_REGIONS_TABLE);
        if (in_array("lang_" . $lang_id, $fields_exists)) {
            $this->CI->dbforge->drop_column(CACHE_REGIONS_TABLE, $field_name);
        }
    }

    public static function getLocationText(array $country = null, array $region = null, array $city = null)
    {
        $location_text_arr = array();
        if (!empty($city['name'])) {
            $location_text_arr[] = $city['name'];
        }
        if (!empty($region['name'])) {
            $location_text_arr[] = $region['name'];
        }
        if (!empty($country['name'])) {
            $location_text_arr[] = $country['name'];
        }
        return implode(', ', $location_text_arr);
    }

}
