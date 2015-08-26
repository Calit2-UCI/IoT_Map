<?php

/**
 * Cookie policy module
 *
 * @package 	PG_Dating
 * @copyright 	Copyright (c) 2000-2014 PG Dating Pro - php dating software
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */

/**
 * Cookie policy install model
 *
 * @package 	PG_Dating
 * @subpackage 	Cookie policy
 * @category	models
 * @copyright 	Copyright (c) 2000-2014 PG Dating Pro - php dating software
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
class Cookie_policy_install_model extends Model{
	/**
	 * Link to CodeIgniter object
	 * 
	 * @var object
	 */
	protected $CI;
	
	/**
	 * Content configuration
	 * 
	 * @var array
	 */
	protected $content = array(
		'title'		=> 'Privacy and security',
		'gid'		=> 'privacy-and-security',
		'parent_id'	=> '0',
		'status'	=> '1',
	);
	
	/**
	 * Class constructor
	 *
	 * @return Cookie_policy_install_model
	 */
	public function __construct(){
		parent::Model();
		$this->CI = & get_instance();
	}
	
	/**
	 * Install data of content module
	 * 
	 * @return void
	 */
	public function install_content(){		
		$this->CI->load->model("Content_model");
		foreach($this->CI->pg_language->languages as $lang_id=>$lang_data){
			$page_data = $this->content;
			$page_data['lang_id'] = $lang_id;
			$validate_data = $this->CI->Content_model->validate_page(null, $page_data);
			if(!empty($validate_data['errors'])) continue;
			$this->CI->Content_model->save_page(null, $validate_data['data']);
		}
		$this->CI->pg_module->set_module_config('cookie_policy', 'page_gid', $this->content['gid']);
	}
	
	/**
	 * Uninstall data of content module
	 * 
	 * @return void
	 */
	public function deinstall_content(){	
		$this->CI->pg_module->set_module_config('cookie_policy', 'page_gid', '');
	}
}
