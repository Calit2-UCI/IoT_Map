<?php

/**
 * Content module
 *
 * @package 	PG_Dating
 * @copyright 	Copyright (c) 2000-2014 PilotGroup.NET Powered by PG Dating Pro
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */

/**
 * Content api controller
 *
 * @package 	PG_Dating
 * @subpackage 	Content
 * @category	controllers
 * @copyright 	Copyright (c) 2000-2014 PilotGroup.NET Powered by PG Dating Pro
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
Class Api_Content extends Controller
{
	/**
	 * Class constructor
	 * 
	 * @return Api_Content
	 */
	public function __construct(){
		parent::Controller();
		$this->load->model("Content_model");
	}

	/**
	 * Get page content
	 *
	 * @param string $gid page guid
	 * @return void
	 */
	public function get() {
		$gid = $this->input->post('gid', true);
		if(!$gid) {
			log_message('error', 'content API: Empty content gid');
			$this->set_api_content('errors', l('error_content_gid_invalid', 'content'));
			return false;
		}
		$page_data = $this->Content_model->get_page_by_gid($gid);
		$this->set_api_content('data', array('page_data' => $page_data));
	}

	/**
	 * Get content pages tree
	 *
	 * @return void
	 */
	public function tree() {
		$lang_id = $this->pg_language->current_lang_id;
		$parent_id = (int)$this->input->post('gid', true);
		$params = (array)$this->input->post('params', true);
		$page_data = $this->Content_model->get_pages_list($lang_id, $parent_id, $params);
		$this->set_api_content('data', array('page_data' => $page_data));
	}

}
