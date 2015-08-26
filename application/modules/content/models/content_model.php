<?php

/**
 * Content module
 *
 * @package 	PG_Dating
 * @copyright 	Copyright (c) 2000-2014 PilotGroup.NET Powered by PG Dating Pro
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */

if (!defined('CONTENT_TABLE')) define('CONTENT_TABLE', DB_PREFIX.'content');

/**
 * Content main model
 *
 * @package 	PG_Dating
 * @subpackage 	Content
 * @category	models
 * @copyright 	Copyright (c) 2000-2014 PilotGroup.NET Powered by PG Dating Pro
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
class Content_model extends Model
{
	/**
	 * Link to CodeIgniter object
	 * 
	 * @var object
	 */
	public $CI;
	
	/**
	 * Link to database object
	 * 
	 * @var object
	 */
	public $DB;

	/**
	 * All properties of content object in data source
	 * 
	 * @var array
	 */
	public $fields_all = array(
		"id",
		"lang_id",
		"parent_id",
		"gid",
		//"title",
		//"content",
		'img',
		"sorter",
		"status",
		"date_created",
		"date_modified",
		"id_seo_settings",
	);

	/**
	 * Listing properties of content object in data source
	 * 
	 * @var array
	 */
	public $fields_list = array(
		"id",
		"lang_id",
		"parent_id",
		"gid",
		//"title",
		'img',
		"sorter",
		"status",
		"date_created",
		"date_modified"
	);

	/**
	 * Info page logo upload (GUID)
	 * 
	 * @var string
	 */
	public $upload_config_id = 'info-page-logo';

	/**
	 * Data of active item by language
	 * 
	 * @var integer
	 */
	public $current_active_item_id = 0;

	/**
	 * Buffer for generating tree
	 * 
	 * @var array
	 */
	public $temp_generate_raw_tree = array();
	
	/**
	 * Buffer for generatin tree item
	 * 
	 * @var array
	 */
	public $temp_generate_raw_items = array();

	/**
	 * Class constructor
	 * 
	 * @return Content_model
	 */
	public function __construct(){
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;
	}

	/**
	 * Return information pages as array
	 * 
	 * @param integer $lang_id language identifier
	 * @param integer $parent_id parent page identifier
	 * @param array $params filters data
	 * @return array
	 */
	public function get_pages_list($lang_id, $parent_id=0, $params=array()){
		$fields_list = $this->fields_list;
		$fields_list[] = 'title_'.$lang_id;
		$fields_list[] = 'annotation_'.$lang_id;
		
		$this->DB->select(implode(", ", $fields_list));
		$this->DB->from(CONTENT_TABLE);

		if(isset($params["where"]) && is_array($params["where"]) && count($params["where"])){
			foreach($params["where"] as $field=>$value){
				$this->DB->where($field, $value);
			}
		}

		if(isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])){
			foreach($params["where_in"] as $field=>$value){
				$this->DB->where_in($field, $value);
			}
		}

		if(isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])){
			foreach($params["where_sql"] as $value){
				$this->DB->where($value);
			}
		}

		$this->DB->order_by("parent_id ASC");
		$this->DB->order_by("sorter ASC");

		$this->temp_generate_raw_items = $this->temp_generate_raw_tree = array();
		$results = $this->DB->get()->result_array();

		if(!empty($results) && is_array($results)){
			$results = $this->format_pages($results, array($lang_id));
			
			$active_parent_id = array();
			foreach($results as $r){
				$r["active"] = $this->_is_active_item($r);
				if($r["active"]){
					$active_parent_id[] = $r["parent_id"];
				}
				$this->temp_generate_raw_items[$r["id"]] = $r;
			}

			if(!empty($active_parent_id)){
				$this->_set_active_chain($active_parent_id);
			}

			foreach($this->temp_generate_raw_items as $r){
				$this->temp_generate_raw_tree[$r["parent_id"]][] = $r;
			}

			$tree = $this->_generate_tree($parent_id);
			return $tree;
		}

		return array();
	}

	/**
	 * Return active information pages as array
	 * 
	 * @param integer $lang_id language identifier
	 * @param integer $parent_id parent page identifier
	 * @param array $params filters data
	 * @return array
	 */
	public function get_active_pages_list($lang_id, $parent_id=0, $params=array()){
		$params["where"]["status"] = 1;
		return $this->get_pages_list($lang_id, $parent_id, $params);
	}

	/**
	 * Return number of information pages
	 * 
	 * @param array $params filters data
	 * @return integer
	 */
	public function get_pages_count($params=array()){
		$this->DB->select("COUNT(*) AS cnt");
		$this->DB->from(CONTENT_TABLE);
		
		if(isset($params["where"]) && is_array($params["where"]) && count($params["where"])){
			foreach($params["where"] as $field=>$value){
				$this->DB->where($field, $value);
			}
		}

		if(isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])){
			foreach($params["where_in"] as $field=>$value){
				$this->DB->where_in($field, $value);
			}
		}

		if(isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])){
			foreach($params["where_sql"] as $value){
				$this->DB->where($value);
			}
		}

		$result = $this->DB->get()->result();
		if(!empty($result)){
			return intval($result[0]->cnt);
		}else{
			return 0;
		}
	}

	/**
	 * Return number of active information pages
	 * 
	 * @param array $params filters parameters
	 * @return integer
	 */
	public function get_active_pages_count($params=array()){
		$params["where"]["status"] = 1;
		return $this->get_pages_count($params);
	}

	/**
	 * Return page object by identifier
	 * 
	 * @param integer $page_id page identifier
	 * @param array $lang_ids language identifiers
	 * @return array
	 */
	public function get_page_by_id($page_id, $lang_ids=null){
		$page_data = array();
		
		if(empty($lang_ids)){
			$lang_ids = array($this->CI->pg_language->current_lang_id);
		}
		
		$fields_all = $this->fields_all;
		
		foreach($lang_ids as $lang_id){
			$fields_all[] = 'title_'.$lang_id;
			$fields_all[] = 'annotation_'.$lang_id;
			$fields_all[] = 'content_'.$lang_id;
		}
		
		$result = $this->DB->select(implode(", ", $fields_all))
						   ->from(CONTENT_TABLE)
						   ->where("id", $page_id)
						   ->get()
						   ->result_array();
		if(!empty($result)){
			reset($lang_ids);
			$page_data = $this->format_page($result[0], $lang_ids);
		}
		
		return $page_data;
	}

	/**
	 * Return page object by GUID
	 * 
	 * @param string $gid page GUID
	 * @param integer $lang_id language identifier
	 * @return array
	 */
	public function get_page_by_gid($gid, $lang_id=null){
		$page_data = array();

		if(empty($lang_id))	$lang_id = $this->CI->pg_language->current_lang_id;
		
		$fields_all = $this->fields_all;
		$fields_all[] = 'title_'.$lang_id;
		$fields_all[] = 'annotation_'.$lang_id;
		$fields_all[] = 'content_'.$lang_id;
		
		$result = $this->DB->select(implode(", ", $fields_all))
					->from(CONTENT_TABLE)
					->where("gid", $gid)
					->get()
					->result_array();
		if(!empty($result)){
			$page_data = $this->format_page($result[0], array($lang_id));
		}
		
		return $page_data;
	}
	
	/**
	 * Return guids of pages as array
	 * 
	 * @return array
	 */
	public function get_gid_list(){
		$page_data = array();
		$result = $this->DB->select("gid")->from(CONTENT_TABLE)->get()->result_array();
		foreach($result as $row){
			$page_data[] = $row['gid'];
		}
		return $page_data;
	}

	/**
	 * Save page object to data source
	 * 
	 * @param integer $page_id page identifier
	 * @param array $attrs page data
	 * @return integer
	 */
	public function save_page($page_id, $attrs){
		if (is_null($page_id)){
			if(empty($attrs["date_created"])) {
				$attrs["date_created"] = $attrs["date_modified"] = date("Y-m-d H:i:s");
			}
			if(!isset($attrs["status"])) {
				$attrs["status"] = 1;
			}
			if(!isset($attrs["sorter"]) && isset($attrs["lang_id"])){
				$sorter_params = array();
				$sorter_params["where"]["parent_id"] = isset($attrs["parent_id"])?$attrs["parent_id"]:0;
				$attrs["sorter"] = $this->get_pages_count($sorter_params)+1;
			}
			$this->DB->insert(CONTENT_TABLE, $attrs);
			$page_id = $this->DB->insert_id();
		}else{
			$attrs["date_modified"] = date("Y-m-d H:i:s");
			$this->DB->where('id', $page_id);
			$this->DB->update(CONTENT_TABLE, $attrs);
		}
		return $page_id;
	}
	
	/**
	 * Upload page logo to site
	 * 
	 * @param integer $page_id page identifier
	 * @param string $file_name file name
	 * @return void
	 */
	public function upload_logo($page_id, $file_name){
		if(empty($file_name) || empty($page_id) || !isset($_FILES[$file_name]) || 
			!is_array($_FILES[$file_name]) || !is_uploaded_file($_FILES[$file_name]["tmp_name"])) return;
	
		$is_uploads_install = $this->CI->pg_module->is_module_installed('uploads');
		if(!$is_uploads_install) return;
		
		$page_data = $this->get_page_by_id($page_id);
	
		$this->CI->load->model("Uploads_model");
		$img_return = $this->CI->Uploads_model->upload($this->upload_config_id, $page_data["prefix"], $file_name);
		if(!empty($img_return["errors"])) return;
		
		$img_data["img"] = $img_return["file"];
		$this->save_page($page_id, $img_data);
	}

	/**
	 * Upload page logo from local file
	 * 
	 * @param integer $page_id page identifier
	 * @param string $file_path file path
	 * @return void
	 */
	public function upload_local_logo($page_id, $file_path){
		$is_uploads_install = $this->CI->pg_module->is_module_installed('uploads');
		if(!$is_uploads_install) return;
		
		$page_data = $this->get_page_by_id($page_id);
	
		$this->CI->load->model("Uploads_model");
		$img_return = $this->CI->Uploads_model->upload_exist($this->upload_config_id, $page_data["prefix"], $file_path);
		if(!empty($img_return["errors"])) return;
		
		$img_data["img"] = $img_return["file"];
		$this->save_page($page_id, $img_data);
	}

	/**
     * Validate page data
     * 
     * @param integer $page_id page identifier
     * @param array $data page data
     * @return array
     */
    public function validate_page($page_id, $data)
    {
        $return = array("errors" => array(), "data" => array());

        if ($page_id) {
            $lang_ids = array_keys($this->CI->pg_language->languages);
            $page_data = $this->get_page_by_id($page_id, $lang_ids);
        } else {
            $page_data = array();
        }

        if (isset($data["lang_id"])) {
            $return["data"]["lang_id"] = $page_data['lang_id'] = intval($data["lang_id"]);
        } elseif (!$page_id) {
            $return["data"]["lang_id"] = $page_data['lang_id'] = $this->CI->pg_language->get_default_lang_id();
        }

		/*if(isset($data["title"])){
            $return["data"]["title"] = strip_tags($data["title"]);
            if(empty($return["data"]["title"]) ){
                $return["errors"][] = l('error_content_title_invalid', 'content');
            }
		}*/

        $default_lang_id = $page_data['lang_id'];
        if (isset($data['title_' . $default_lang_id])) {
            $return['data']['title_' . $default_lang_id] = trim(strip_tags($data['title_' . $default_lang_id]));
            if (empty($return['data']['title_' . $default_lang_id])) {
                $return['errors'][] = l('error_content_title_invalid', 'content');
            } else {
                foreach ($this->CI->pg_language->languages as $lid => $lang_data) {
                    if ($lid == $default_lang_id) {
                        continue;
                    }
                    if (!isset($data['title_' . $lid]) || empty($data['title_' . $lid])) {
                        $return['data']['title_' . $lid] = $return['data']['title_' . $default_lang_id];
                    } else {
                        $return['data']['title_' . $lid] = trim(strip_tags($data['title_' . $lid]));
                        if (empty($return['data']['title_' . $lid])) {
                            $return['errors'][] = l('error_content_title_invalid', 'content');
                            break;
                        }
                    }
                }
            }
        } elseif (!$page_id) {
            $return["errors"][] = l('error_content_title_invalid', 'content');
        }

        if (isset($data["gid"])) {
            $this->CI->config->load("reg_exps", TRUE);
            $reg_exp = $this->CI->config->item("not_literal", "reg_exps");
            $temp_gid = $return["data"]["gid"] = strtolower(trim(strip_tags($data["gid"])));
            if (!empty($temp_gid)) {
                $return["data"]["gid"] = preg_replace($reg_exp, '-', $return["data"]["gid"]);
                $return["data"]["gid"] = preg_replace("/[\-]{2,}/i", '-', $return["data"]["gid"]);
                $return["data"]["gid"] = trim($return["data"]["gid"], '-');
                if (empty($return["data"]["gid"])) {
                    $return["data"]["gid"] = md5($temp_gid);
                }

                $params = array();
                $params["where"]["gid"] = $return["data"]["gid"];
                if ($page_id) {
                    $params["where"]["id <>"] = $page_id;
                }
                $count = $this->get_pages_count($params);
                if ($count > 0) {
                    $return["errors"][] = l('error_gid_already_exists', 'content');
                }
            } else {
                $return["errors"][] = l('error_content_gid_invalid', 'content');
            }
        }

        if (isset($data['annotation_' . $default_lang_id])) {
            $return['data']['annotation_' . $default_lang_id] = trim($data['annotation_' . $default_lang_id]);
            if (!empty($return['data']['annotation_' . $default_lang_id])) {
                foreach ($this->CI->pg_language->languages as $lid => $lang_data) {
                    if ($lid == $default_lang_id) {
                        continue;
                    }
                    if (!isset($data['annotation_' . $lid]) || empty($data['annotation_' . $lid])) {
                        $return['data']['annotation_' . $lid] = $return['data']['annotation_' . $default_lang_id];
                    } else {
                        $return['data']['annotation_' . $lid] = trim($data['annotation_' . $lid]);
                    }
                }
            }
        }

        /* if(isset($data["content"])){
            $return["data"]["content"] = $data["content"];
        } */

        if (isset($data['content_' . $default_lang_id])) {
            $return['data']['content_' . $default_lang_id] = trim($data['content_' . $default_lang_id]);
            if (empty($return['data']['content_' . $default_lang_id])) {
                $return['errors'][] = l('error_content_content_invalid', 'content');
            } else {
                foreach ($this->CI->pg_language->languages as $lid => $lang_data) {
                    if ($lid == $default_lang_id) {
                        continue;
                    }
                    if (!isset($data['content_' . $lid]) || empty($data['content_' . $lid])) {
                        $return['data']['content_' . $lid] = $return['data']['content_' . $default_lang_id];
                    } else {
                        $return['data']['content_' . $lid] = trim($data['content_' . $lid]);
                        if (empty($return['data']['content_' . $lid])) {
                            $return['errors'][] = l('error_content_content_invalid', 'content');
                            break;
                        }
                    }
                }
            }
        } elseif (!$page_id) {
            $return["errors"][] = l('error_content_content_invalid', 'content');
        }

        if (isset($data["parent_id"])) {
            $return["data"]["parent_id"] = intval($data["parent_id"]);
        }

        if (isset($data["sorter"])) {
            $return["data"]["sorter"] = intval($data["sorter"]);
        }

        if (isset($data["status"])) {
            $return["data"]["status"] = intval($data["status"]);
        }

        if (isset($data["id_seo_settings"])) {
            $return["data"]["id_seo_settings"] = intval($data["id_seo_settings"]);
        }

        return $return;
    }

    /**
	 * Validate page logo object for uploading to site
	 * 
	 * @param string $file_name file name
	 * @return array
	 */
	public function validate_logo($file_name){
		$return = array('errors'=>array(), 'data'=>array());

		if(empty($file_name) || !isset($_FILES[$file_name]) || !is_array($_FILES[$file_name]) || 
			!is_uploaded_file($_FILES[$file_name]["tmp_name"])) return $return;
			
		$this->CI->load->model("Uploads_model");
		$img_return = $this->CI->Uploads_model->validate_upload($this->upload_config_id, $file_name);
		
		if(!empty($img_return["error"])){
			$return["errors"][] = implode("<br>", $img_return["error"]);
		}
		
		return $return;
	}

	/**
	 * Remove page object by identifier
	 * 
	 * @param integer $page_id page identifier
	 * @return void
	 */
	public function delete_page($page_id){
		$page_data = $this->get_page_by_id($page_id);
		if(!empty($page_data)){
			$this->DB->where('id', $page_id);
			$this->DB->delete(CONTENT_TABLE);
			$this->resort_pages($page_data["lang_id"], $page_data["parent_id"]);
			
			$data["parent_id"] = 0;
			$this->DB->where('parent_id', $page_id);
			$this->DB->update(CONTENT_TABLE, $data);
			
			if(!empty($page_data["img"]) && $this->CI->pg_module->is_module_installed('uploads')){
				$page_data = $this->format_page($page_data);
				$this->CI->load->model("Uploads_model");
				$this->CI->Uploads_model->delete_upload($this->upload_config_id, $page_data["prefix"], $page_data["img"]);
			}
		}
		return;
	}
	
	/**
	 * Remove page logo
	 * 
	 * @param integer $page_id page identifier
	 * @return void
	 */
	public function delete_logo($page_id){
		$page_data = $this->get_page_by_id($page_id);
		
		if(empty($page_data["img"])) return;
		
		$is_uploads_install = $this->CI->pg_module->is_module_installed('uploads');
		if(!$is_uploads_install) return;
		
		$page_data = $this->format_page($page_data);
		$this->CI->load->model("Uploads_model");
		$this->CI->Uploads_model->delete_upload($this->upload_config_id, $page_data["prefix"], $page_data["img"]);
	}

	/**
	 * Activate/deactivate page object
	 * 
	 * Available statuses: 1 - activate, 0 - deactivate
	 * 
	 * @param integer $page_id page identifier
	 * @param integer $status page status
	 * @return void
	 */
	public function activate_page($page_id, $status=1){
		$attrs["status"] = intval($status);
		$this->DB->where('id', $page_id);
		$this->DB->update(CONTENT_TABLE, $attrs);
	}

	/**
	 * Resort pages order
	 * 
	 * @param integer $lang_id language identifier
	 * @param integer $parent_id parent page identifier
	 * @return void
	 */
	public function resort_pages($lang_id, $parent_id=0){
		$results = $this->DB->select("id, sorter")
							->from(CONTENT_TABLE)
							//->where("lang_id", $lang_id)
							->where("parent_id", $parent_id)
							->order_by('sorter ASC')
							->get()
							->result_array();
		if(!empty($results)){
			$i = 1;
			foreach($results as $r){
				$data["sorter"] = $i;
				$this->DB->where('id', $r["id"]);
				$this->DB->update(CONTENT_TABLE, $data);
				$i++;
			}
		}
	}

	/**
	 * Make page as active
	 * 
	 * @param integer $page_id page identifier
	 * @return boolean
	 */
	public function set_page_active($page_id){
		if(!is_numeric($page_id)){
			$item = $this->get_page_by_id($page_id);
			$page_id = $item["id"];
		}
		if(!$page_id){
			return false;
		}
		$this->current_active_item_id = $page_id;
		return;
	}

	///// inner functions
	
	/**
	 * Check page is active
	 * 
	 * @param array $item page data
	 * @return boolean
	 */
	public function _is_active_item($item){
		if(!empty($this->current_active_item_id)){
			if($this->current_active_item_id == $item["id"]){
				return true;
			}
		}
		return false;
	}

	/**
	 * Generate chain to active page object
	 * 
	 * @param array $parent_ids parent page identifiers
	 * @return void
	 */
	public function _set_active_chain($parent_ids){
		foreach($parent_ids as $id){
			$parent_id = $id;
			do{
				$this->temp_generate_raw_items[$parent_id]["in_chain"] = true;
				$parent_id = $this->temp_generate_raw_items[$parent_id]["parent_id"];
			}while($parent_id > 0);
		}
	}

	/**
	 * Generate page tree
	 * 
	 * @param integer $parent_id root page identifier
	 * @return array
	 */
	public function _generate_tree($parent_id){

		if(empty($this->temp_generate_raw_tree) || empty($this->temp_generate_raw_tree[$parent_id])){
			return array();
		}

		$tree = array();
		foreach($this->temp_generate_raw_tree[$parent_id] as $subitem){
			if(isset($this->temp_generate_raw_tree[$subitem["id"]]) && !empty($this->temp_generate_raw_tree[$subitem["id"]])){
				$subitem["sub"] = $this->_generate_tree($subitem["id"]);
			}
			$tree[] = $subitem;
		}

		return $tree;
	}

	////// seo
	
	/**
	 * Return seo settings of content module
	 * 
	 * @param string $method method name
	 * @param integer $lang_id language identifier
	 * @return array
	 */
	public function get_seo_settings($method='', $lang_id=''){
		if(!empty($method)){
			return $this->_get_seo_settings($method, $lang_id);
		}else{
			$actions = array('index', 'view');
			$return = array();
			foreach($actions as $action){
				$return[$action] = $this->_get_seo_settings($action, $lang_id);
			}
			return $return;
		}
	}

	/**
	 * Return seo settings of content module (internal)
	 * 
	 * @param string $method method name
	 * @param integer $lang_id language identifier
	 * @return array
	 */
	public function _get_seo_settings($method, $lang_id=''){
		if($method == "index"){
			return array(
				'templates' => array(),
				'url_vars' => array(),
				'url_postfix' => array(),
				'optional' => array(),
			);
		}elseif($method == "view"){
			return array(
				'templates' => array('title', 'gid'),
				'url_vars' => array(
					'gid' => array('gid'=>'literal', 'id'=>'literal')
				),
				'url_postfix' => array(),
				'optional' => array(
					array('title' => 'literal'),
				),
			);
		}
	}

	/**
	 * Transform seo value of request query
	 * 
	 * @param string $var_name_from variable from name
	 * @param string $var_name_to variable to name
	 * @param mixed $value parameter value
	 * @return mixed
	 */
	public function request_seo_rewrite($var_name_from, $var_name_to, $value){
		$user_data = array();

		if($var_name_from == $var_name_to){
			return $value;
		}

		if($var_name_from == "gid" && $var_name_to == "id"){
			$lang_id = $this->CI->pg_language->current_lang_id;
			$page_data = $this->get_page_by_gid($value);
			if(empty($page_data)) show_404();
			return $page_data["id"];
		}

		if($var_name_from == "id" && $var_name_to == "gid"){
			$page_data = $this->get_page_by_id($value);
			if(empty($page_data)) show_404();
			return $page_data["gid"];
		}

		show_404();
	}

	/**
	 * Return data for xml sitemap
	 * 
	 * @return array
	 */
	public function get_sitemap_xml_urls(){
		$this->CI->load->helper('seo');
		
		$lang_canonical = true;
				
		$is_seo_install = $this->CI->pg_module->is_module_installed('seo');	
		if($is_seo_install){
			$lang_canonical = $this->CI->pg_module->get_module_config('seo', 'lang_canonical');
		}
		
		if($lang_canonical){
			$default_lang_id = $this->CI->pg_language->get_default_lang_id();
			$default_lang_code = $this->CI->pg_language->get_lang_code_by_id($default_lang_id);
			$langs[$default_lang_id] = $default_lang_code;
		}else{
			foreach($this->CI->pg_language->languages as $lang_id=>$lang_data){
				$langs[$lang_id] = $lang_data['code'];
			}
		}
		
		$return = array();
		
		$user_settings = $this->pg_seo->get_settings('user', 'content', 'index');
		if(!$user_settings['noindex']){
			foreach($langs as $lang_id=>$lang_code){
				$return[] = array(
					"url" => rewrite_link('content', 'index', array(), false, $lang_code, $lang_canonical),
					"priority" => 0.5
				);
			}
		}
		
		$user_settings = $this->pg_seo->get_settings('user', 'content', 'view');
		if(!$user_settings['noindex']){
			$languages = $this->CI->pg_language->languages;
			$fields_list = $this->fields_list;
			foreach($languages as $lang_id=>$lang_data){
				$fields_list[] = 'title_'.$lang_id;
			}
			
			$this->DB->select(implode(", ", $fields_list))->from(CONTENT_TABLE)->where('status', '1');
			$results = $this->DB->get()->result_array();
			if(!empty($results) && is_array($results)){
				foreach($results as $r){
					foreach($languages as $lang_id=>$lang_data){
						$r['title'] = $r['title_'.$lang_id];
						$lang_code = $this->CI->pg_language->get_lang_code_by_id($lang_id);
						$return[] = array(
							"url" => rewrite_link('content', 'view', $r, false, $lang_code, $lang_canonical),
							"priority" => 0.8
						);
					}
				}
			}
		}
		
		return $return;
	}

	/**
	 * Return data for sitemap page
	 * 
	 * @return array
	 */
	public function get_sitemap_urls(){
		$this->CI->load->helper('seo');

		$lang_id = $this->CI->pg_language->current_lang_id;
		$pages = $this->get_active_pages_list($lang_id, 0);
		$block = array();

		foreach($pages as $page){
			$sub = array();
			if(!empty($page["sub"])){
				foreach($page["sub"] as $sub_page){
					$sub[] = array(
						"name" => $sub_page["title"],
						"link" => rewrite_link('content', 'view', $sub_page),
						"clickable" => true
					);
				}
			}
			$block[] = array(
				"name" => $page["title"],
				"link" => rewrite_link('content', 'view', $page),
				"clickable" => true,
				"items" => $sub
			);
		}
		return $block;
	}

	////// banners callback method
	
	/**
	 * Return available pages for banner replacements
	 * 
	 * @return array
	 */
	public function _banner_available_pages(){
		$return[] = array("link"=>"content/index", "name"=> l('seo_tags_index_header', 'content'));
		$return[] = array("link"=>"content/view", "name"=> l('seo_tags_view_header', 'content'));
		return $return;
	}

	/**
	 * Widget of informations pages
	 * 
	 * Callback method for dynamic blocks module
	 * 
	 * @param array $params dynamic block parameter
	 * @param string $view dynamic block view
	 * @param integer $width block size
	 * @return string
	 */
	public function _dynamic_block_get_info_pages($params, $view, $width){
		$parent_id = 0;

		$data['section'] = array();
		$data['pages'] = array();
		if(!empty($params['keyword'])){
			$data['section'] = $this->get_page_by_gid($params['keyword']);
			if($data['section']){
				$parent_id = $data['section']['id'];
			}
		}
		if($params['show_subsections']){
			$this->load->helper('text');
			$data['pages'] = $this->get_active_pages_list($this->pg_language->current_lang_id, $parent_id, array('where'=>array('parent_id' => $parent_id)));
			if($params['trim_subsections_text']){
				foreach($data['pages'] as &$page){
					$page['short_content'] = word_limiter(trim(strip_tags($page['content'])), 50, '...');
				}
			}
		}

		if(empty($data['section']) && empty($data['pages'])) return '';

		$data['params'] = $params;
		$data['view'] = $view;
		$data['width'] = $width;

		$this->CI->template_lite->assign('dynamic_block_info_pages_data', $data);

		return $this->CI->template_lite->fetch('dynamic_block_info_pages', 'user', 'content');
	}
	
	/**
	 * Widget of information page
	 * 
	 * Callback method for dynamic blocks module
	 * 
	 * @param array $params dynamsic block parameter
	 * @param string $view dynamic block view
	 * @param integer $width block size
	 * @return string
	 */
	public function _dynamic_block_get_info_page($params, $view='default', $width=100){
		if(!isset($params['keyword'])) $params['keyword'] = '';
		$this->CI->template_lite->assign('block_keyword', $params['keyword']);
		$this->CI->template_lite->assign('block_view', $view);
		$this->CI->template_lite->assign('block_width', $width);
		return $this->CI->template_lite->fetch('dynamic_block_info_page', 'user', 'content');
	}
	
	/**
	 * Install content properties depended on language 
	 * 
	 * @param integer $lang_id language identifier
	 * @return void
	 */
	public function lang_dedicate_module_callback_add($lang_id=false){
		if(!$lang_id) return;
	
		$this->CI->load->dbforge();
		
		$fields = array();
		$fields['title_'.$lang_id] = array('type'=>'varchar(255)', 'null'=>FALSE);
		$this->CI->dbforge->add_column(CONTENT_TABLE, $fields);
		
		$fields = array();
		$fields['annotation_'.$lang_id] = array('type'=>'text', 'null'=>TRUE);
		$this->CI->dbforge->add_column(CONTENT_TABLE, $fields);
		
		$fields = array();
		$fields['content_'.$lang_id] = array('type'=>'text', 'null'=>TRUE);
		$this->CI->dbforge->add_column(CONTENT_TABLE, $fields);
		
		$current_lang_id = $this->CI->pg_language->get_default_lang_id();
		if($lang_id != $current_lang_id){
			$fields_exists = $this->CI->db->list_fields(CONTENT_TABLE);
			
			if(in_array('title_'.$current_lang_id, $fields_exists)){
				$this->CI->db->set('title_'.$lang_id, 'title_'.$current_lang_id, false);
				$this->CI->db->update(CONTENT_TABLE);
			}
			
			if(in_array('annotation_'.$current_lang_id, $fields_exists)){
				$this->CI->db->set('annotation_'.$lang_id, 'annotation_'.$current_lang_id, false);
				$this->CI->db->update(CONTENT_TABLE);
			}
			
			if(in_array('content_'.$current_lang_id, $fields_exists)){
				$this->CI->db->set('content_'.$lang_id, 'content_'.$current_lang_id, false);
				$this->CI->db->update(CONTENT_TABLE);
			}
		}
	}
	
	/**
	 * Uninstall content properties depended on language
	 * 
	 * @param integer $lang_id language identifier
	 * @return void
	 */
	public function lang_dedicate_module_callback_delete($lang_id=false){
		if(!$lang_id) return;
		
		$this->CI->load->dbforge();
		
		$fields_exists = $this->CI->db->list_fields(CONTENT_TABLE);
		
		$fields = array('title_'.$lang_id, 'annotation_'.$lang_id, 'content_'.$lang_id);
		foreach($fields as $field_name){
			if(!in_array($field_name, $fields_exists)) continue;
			$this->CI->dbforge->drop_column(CONTENT_TABLE, $field_name);
		}
	}
	
	/**
	 * Format info page data
	 * 
	 * @param array $data info page data
	 * @param array $langs_ids languages identifier
	 * @return array
	 */
	public function format_page($data, $langs_ids=array()){
		return array_shift($this->format_pages(array($data), $langs_ids));
	}
	
	/**
	 * Format info pages data
	 * 
	 * @param array $data info pages data
	 * @return array
	 */
	public function format_pages($data, $langs_ids=array()){
		$is_uploads_install = $this->CI->pg_module->is_module_installed('uploads');
		if($is_uploads_install){
			$this->CI->load->model('Uploads_model');
		}
		
		if(empty($langs_ids)){
			$langs_ids = array($this->CI->pg_language->current_lang_id);
		}
		
		foreach($data as $key=>$page){
			if(!empty($page["id"])){
				$page["prefix"] = $page["id"];
			}

			$lang_id = in_array($page['lang_id'], $langs_ids) ? $page['lang_id'] : current($langs_ids);
			$page['title'] = $page['title_'.$lang_id];
			$page['annotation'] = isset($page['annotation_'.$lang_id]) ? $page['annotation_'.$lang_id] : '';
			$page['content'] = isset($page['content_'.$lang_id]) ? $page['content_'.$lang_id] : '';

			if($is_uploads_install){
				if(!empty($page["img"])){
					$page["media"]["img"] = $this->CI->Uploads_model->format_upload(
						$this->upload_config_id, $page["prefix"], $page["img"]);
				}
			}
			
			$data[$key] = $page;
		}
		
		return $data;
	}
}
