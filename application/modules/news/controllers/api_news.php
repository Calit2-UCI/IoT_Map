<?php

/**
 * News module
 *
 * @package 	PG_Dating
 * @copyright 	Copyright (c) 2000-2014 PilotGroup.NET Powered by PG Dating Pro
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */

/**
 * News api controller
 *
 * @package 	PG_Dating
 * @subpackage 	News
 * @category	controollers
 * @copyright 	Copyright (c) 2000-2014 PilotGroup.NET Powered by PG Dating Pro
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
Class Api_News extends Controller
{

	/**
	 * Constructor
	 * 
	 * @return Api_News
	 */
	public function __construct(){
		parent::Controller();
		$this->load->model('News_model');
	}

	/**
	 * Get news list
	 *
	 * @param integer $page page of results (post)
	 * @return void
	 */
	public function news_list(){
		$attrs = array();
		//$attrs['where']['id_lang'] = $this->pg_language->current_lang_id;
		$attrs['where']['status'] = '1';
		$news_count = $this->News_model->get_news_count($attrs);
		$page = $this->input->post('page', true);
		$items_on_page = $this->pg_module->get_module_config('news', 'userside_items_per_page');
		$this->load->helper('sort_order');
		$page = get_exists_page_number($page, $news_count, $items_on_page);

		if ($news_count > 0){
			$data['news'] = $this->News_model->get_news_list( $page, $items_on_page, array('date_add' => 'DESC'), $attrs);
			$data['date_format'] = $this->pg_date->get_format('date_time_literal', 'st');
			$data['count'] = $news_count;
			$this->set_api_content('data', $data);
		} else {
			$this->set_api_content('messages', l('api_error_news_not_found', 'news'));
		}
	}

	/**
	 * Get news by id
	 *
	 * @param int $id
	 */
	public function view(){

		$id = (int)$this->input->post('news_id', true);
		if (!$id){
			log_message('error', 'dating news API: Empty news id');
			$this->set_api_content('error', l('api_error_empty_news_id', 'news'));
			return false;
		}
		$news = $this->News_model->get_news_by_id($id);
		if (!$news){
			log_message('error', 'dating news API: News with id "' . $id . '" not found');
			$this->set_api_content('error', l('api_error_news_not_found', 'news'));
			return false;
		}
		$news = $this->News_model->format_single_news($news);
		$date_format = $this->pg_date->get_format('date_time_literal', 'st');
		$this->set_api_content('data', array('news' => $news, 'date_format' => $date_format));
	}

}
