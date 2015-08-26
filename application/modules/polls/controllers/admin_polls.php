<?php
/**
* Polls admin side controller
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

Class Admin_Polls extends Controller {

	function __construct() {
		parent::Controller();
		$this->load->model('Menu_model');
		$this->Menu_model->set_menu_active_item('admin_menu', 'add_ons_items');
		$this->load->model('Polls_model');
	}

	public function index($filter = 'all', $order = 'question', $order_direction = 'ASC', $page = 1) {
		// Это изличшне но иногда бывают случаи когда переменные пустые
		if (!$filter)
			$filter = 'all';
		if (!$order)
			$order = 'question';
		if (!$order_direction)
			$order_direction = 'ASC';
		if (!$page)
			$page = 1;
		$page = $page < 0 ? 1 : $page;
		$page = floor($page);
		$attrs = $search_params = array();
		// Грузим настройки
		$current_settings = isset($_SESSION["polls_list"]) ? $_SESSION["polls_list"] : array();
		if (!isset($current_settings["filter"]))
			$current_settings["filter"] = $filter;
		if (!isset($current_settings["order"]))
			$current_settings["order"] = $order;
		if (!isset($current_settings["order_direction"]))
			$current_settings["order_direction"] = $order_direction;
		if (!isset($current_settings["page"]))
			$current_settings["page"] = $page;
		// Используем фильтрацию
		switch ($filter) {
			case 'all':
				break;
			case 'active':
				$attrs["where_sql"][] = ' ((use_expiration = 0 OR (use_expiration = 1 AND date_end >= \'' . date('Y-m-d H:i:s') . '\')) AND date_start < \'' . date('Y-m-d H:i:s') . '\') ';
				break;
			case 'feauture':
				$attrs["where_sql"][] = ' ((use_expiration = 0 OR (use_expiration = 1 AND date_end >= \'' . date('Y-m-d H:i:s') . '\')) AND date_start > \'' . date('Y-m-d H:i:s') . '\') ';
				break;
			case 'end':
				$attrs["where_sql"][] = ' (use_expiration = 1 AND date_end < \'' . date('Y-m-d H:i:s') . '\') ';
				break;
		}
		// Получаем счетчики
		$filter_data["all"] = $this->Polls_model->get_polls_count($search_params);
		$search_params["where_sql"][0] = ' ((use_expiration = 0 OR (use_expiration = 1 AND date_end >= \'' . date('Y-m-d H:i:s') . '\')) AND date_start < \'' . date('Y-m-d H:i:s') . '\') ';
		$filter_data["active"] = $this->Polls_model->get_polls_count($search_params);
		$search_params["where_sql"][0] = ' (use_expiration = 1 AND date_end < \'' . date('Y-m-d H:i:s') . '\') ';
		$filter_data["end"] = $this->Polls_model->get_polls_count($search_params);
		$search_params["where_sql"][0] = ' ((use_expiration = 0 OR (use_expiration = 1 AND date_end >= \'' . date('Y-m-d H:i:s') . '\')) AND date_start > \'' . date('Y-m-d H:i:s') . '\') ';
		$filter_data["feauture"] = $this->Polls_model->get_polls_count($search_params);
		// Формируем пагинацию
		$items_on_page = $this->pg_module->get_module_config('start', 'admin_items_per_page');
		$polls_count = $this->Polls_model->get_polls_count($attrs);
		$this->load->helper('sort_order');
		$page = get_exists_page_number($page, $polls_count, $items_on_page);
		$current_settings["page"] = $page;
		// Сохраняем настройки
		$_SESSION["polls_list"] = $current_settings;
		// Ссылки для сортировки ASC DESC
		$sort_links = array(
			"question" => site_url() . "admin/polls/index/" . $filter . "/question/" . (($order != 'question' xor $order_direction == 'DESC') ? 'ASC' : 'DESC'),
			"poll_type" => site_url() . "admin/polls/index/" . $filter . "/poll_type/" . (($order != 'poll_type' xor $order_direction == 'DESC') ? 'ASC' : 'DESC'),
			"date_end" => site_url() . "admin/polls/index/" . $filter . "/date_end/" . (($order != 'date_end' xor $order_direction == 'DESC') ? 'ASC' : 'DESC'),
			"date_start" => site_url() . "admin/polls/index/" . $filter . "/date_start/" . (($order != 'date_start' xor $order_direction == 'DESC') ? 'ASC' : 'DESC'),
			"language" => site_url() . "admin/polls/index/" . $filter . "/language/" . (($order != 'language' xor $order_direction == 'DESC') ? 'ASC' : 'DESC'),
		);
		$this->template_lite->assign('sort_links', $sort_links);
		// Получаем опросы
		$polls_count = $filter_data[$filter];
		if ($polls_count > 0) {
			$polls = $this->Polls_model->get_polls_list($page, $items_on_page, array($order => $order_direction), $attrs);			
			//Select user_type
			$this->load->model('Properties_model');
			$user_types = $this->Properties_model->get_property('user_type');
			foreach($polls as $key => $value){
				$polls[$key]["poll_type_val"] = $user_types["option"][$value["poll_type"]];
			}
			$this->template_lite->assign('polls', $polls);
		}
		
		$this->load->helper("navigation");
		$url = site_url() . "admin/polls/index/" . $filter . "/" . $order . "/" . $order_direction . "/" . $page;
		$page_data = get_admin_pages_data($url, $polls_count, $items_on_page, $page, 'briefPage');
		$page_data["date_format"] = $this->pg_date->get_format('date_time_literal', 'st');
		// Отображаем все
		$this->template_lite->assign('page_data', $page_data);
		$this->template_lite->assign('filter_data', $filter_data);
		$this->template_lite->assign('filter', $filter);
		$this->template_lite->assign('order', $order);
		$this->template_lite->assign('order_direction', $order_direction);
		$this->template_lite->assign('page', $page);
		$this->template_lite->assign('languages', $this->pg_language->languages);
		$this->template_lite->assign('cur_lang', $this->pg_language->current_lang_id);
		$this->system_messages->set_data('header', l('admin_header_polls_list', 'polls'));
		$this->template_lite->view('list_polls');
	}

	public function edit($id = 0) {
		// Получаем опрос и варианты ответов
		$poll_data = $this->Polls_model->get_poll_by_id($id);
		// Если сохранение - делаем все до основных конструкций
		if ($this->input->post('btn_save', true)) {
			$poll_id = isset($poll_data['id']) ? $poll_data['id'] : 0;
			$post_data = array(
				"question" => $this->input->post('question', true),
				"poll_type" => $this->input->post('poll_type', true),
				"answer_type" => $this->input->post('answer_type', true),
				"sorter" => $this->input->post('sorter', true),
				"show_results" => $this->input->post('show_results', true),
				"date_start" => date('Y-m-d H:i:s', strtotime($this->input->post('date_start', true))),
				"use_comments" => $this->input->post('use_comments', true),
				"language" => $this->input->post('language', true),
				"use_expiration" => $this->input->post('use_expiration', true),
				"date_end" => date('Y-m-d H:i:s', strtotime($this->input->post('date_end', true))),
			);
			$poll_data = $post_data;
			$validate_data = $this->Polls_model->validate_poll($post_data);
			if (!empty($validate_data["errors"])) {
				$this->system_messages->add_message('error', $validate_data["errors"]);
			} else {
				$save_data = $validate_data["data"];
				if ($poll_id)
					$save_data['id'] = $poll_id;
				$new_poll_id = $this->Polls_model->save_poll($save_data);
				$this->system_messages->add_message('success', $poll_id ? l('success_update_poll', 'polls') : l('success_add_poll', 'polls'));
				$url = !$poll_id ? site_url() . "admin/polls/answers/" . $new_poll_id : site_url() . "admin/polls/";
				redirect($url);
			}
		}
		// Формирования даты для визуально нормального вывода
		if (!$poll_data) {
			$poll_data['date_end'] = '00-00-0000';
			$poll_data['date_start'] = date('d-m-Y');
		} else {
			$poll_data['date_end'] = $poll_data['use_expiration'] ? date('d-m-Y', strtotime($poll_data['date_end'])) : '00-00-0000';
			$poll_data['date_start'] = date('d-m-Y', strtotime($poll_data['date_start']));
		}

		$max_results = 0;
		if (!empty($poll_data['results'])){
			foreach ($poll_data['results'] as $result){
				$max_results = $max_results + $result;
			}
		}
		
		//Select user_type
		$this->load->model('Properties_model');
		$user_types = $this->Properties_model->get_property('user_type');
		$this->template_lite->assign('user_types', $user_types);

		$this->template_lite->assign('max_results', $max_results);
		// Оторбражаем все
		$this->pg_theme->add_js('admin-polls.js', 'polls');
		$this->template_lite->assign('languages', $this->pg_language->languages);
		$this->template_lite->assign('languages_count', count($this->pg_language->languages));
		$this->template_lite->assign('cur_lang', $this->pg_language->current_lang_id);
		$this->template_lite->assign('poll_data', $poll_data);
		$this->system_messages->set_data('header', l('admin_header_polls_list', 'polls'));
		$this->template_lite->view('edit_poll');
	}

	public function answers($id = 0) {
		// Получаем опрос и варианты ответов
		$poll_data = $this->Polls_model->get_poll_by_id($id);
		// Если сохраняем
		if ($poll_data && $this->input->post('btn_save', true)) {
			$answers_count = is_array($poll_data['answers_colors']) ? count($poll_data['answers_colors']) : 0;
			$poll_id = $poll_data['id'];
			$post_data = array(
				'answers_languages' => $this->input->post('answer', true),
				'answers_colors' => $this->input->post('answers_colors', true),
			);
			$poll_data['answers_languages'] = $post_data['answers_languages'];
			$poll_data['answers_colors'] = $post_data['answers_colors'];
			// Берем максимальное число ответов
			$max_answers = $this->pg_module->get_module_config('polls', 'max_answers');
			$max_answers = $max_answers ? $max_answers : 10;
			if ($max_answers < count($poll_data['answers_colors'])) {
				$this->Polls_model->expand_tables(count($poll_data['answers_colors']));
			}
			$validate_data = $this->Polls_model->validate_answers($post_data, $poll_data['language']);
			if (!empty($validate_data["errors"])) {
				$this->system_messages->add_message('error', $validate_data["errors"]);
			} else {
				$save_data = $validate_data["data"];
				if ($poll_id)
					$save_data['id'] = $poll_id;
				if ($answers_count == 0)
					$save_data['status'] = 1;
				$this->Polls_model->save_poll($save_data);
				$this->system_messages->add_message('success', l('success_update_poll', 'polls'));
				$url = site_url() . "admin/polls";
				redirect($url);
			}
		}
		if (!is_array($poll_data['answers_colors']) || count($poll_data['answers_colors']) < 2) {
			$poll_data['answers_colors'] = array(
				1 => '',
				2 => '',
			);
		}
		$responds_count = 0;
		if ($poll_data['results']){
			foreach ($poll_data['results'] as $result){
				$responds_count = $responds_count + $result;
			}
		}

		$this->pg_theme->add_js('admin-polls.js', 'polls');
		$this->template_lite->assign('responds_count', $responds_count);
		$this->template_lite->assign('answers_count', count($poll_data['answers_colors']));

		$this->template_lite->assign('languages', $this->pg_language->languages);
		$this->template_lite->assign('languages_count', count($this->pg_language->languages));
		$this->template_lite->assign('cur_lang', $this->pg_language->current_lang_id);
		$this->template_lite->assign('poll_language', $poll_data['language']);

		$this->template_lite->assign('poll_data', $poll_data);
		$this->template_lite->assign('id', $id);

		$this->system_messages->set_data('header', l('admin_header_answers_list', 'polls'));
		$this->template_lite->view('edit_answers');
	}

	function activate($poll_id, $status = 0) {
		if (!empty($poll_id)) {
			$this->Polls_model->activate_polls($poll_id, $status);
			if ($status)
				$this->system_messages->add_message('success', l('success_activate_poll', 'polls'));
			else
				$this->system_messages->add_message('success', l('success_deactivate_poll', 'polls'));
		}
		$cur_set = $_SESSION["polls_list"];
		$url = site_url() . "admin/polls/index/" . (isset($cur_set["filter"]) ? $cur_set["filter"] : 'all') . "/" . (isset($cur_set["order"]) ? $cur_set["order"] : 'question') . "/" . (isset($cur_set["order_direction"]) ? $cur_set["order_direction"] : 'ASC') . "/" . (isset($cur_set["page"]) ? $cur_set["page"] : 1) . "";
		redirect($url);
	}

	function delete($poll_id) {
		if (!empty($poll_id)) {
			$this->Polls_model->delete_polls($poll_id);
			$this->system_messages->add_message('success', l('success_delete_poll', 'polls'));
		}
		$cur_set = $_SESSION["polls_list"];
		$url = site_url() . "admin/polls/index/" . (isset($cur_set["filter"]) ? $cur_set["filter"] : 'all') . "/" . (isset($cur_set["order"]) ? $cur_set["order"] : 'question') . "/" . (isset($cur_set["order_direction"]) ? $cur_set["order_direction"] : 'ASC') . "/" . (isset($cur_set["page"]) ? $cur_set["page"] : 1) . "";
		redirect($url);
	}

	public function results($poll_id = 0, $filter = 'all', $order = 'user_id', $order_direction = 'ASC', $page = 1) {
		// Получаем опрос и варианта ответов
		$poll_data = $this->Polls_model->get_poll_by_id($poll_id);
		//Select user_type
		$this->load->model('Properties_model');
		$user_types = $this->Properties_model->get_property('user_type');
		foreach($poll_data as $poll){
			$poll_data["poll_type_val"] = $user_types["option"][$poll["poll_type"]];
		}
		// Загружаем модель пользователей - Need Users module!!!
		$this->load->model('Users_model');
		// Это изличшне но иногда бывают случаи когда переменные пустые
		if (!$filter)
			$filter = 'all';
		if (!$order)
			$order = 'user_id';
		if (!$order_direction)
			$order_direction = 'ASC';
		if (!$page)
			$page = 1;
		$page = $page < 0 ? 1 : $page;
		$page = floor($page);
		$attrs = $search_params = array();
		// Загружаем настройки
		$current_settings = isset($_SESSION["results_list"]) ? $_SESSION["results_list"] : array();
		if (!isset($current_settings["filter"]))
			$current_settings["filter"] = $filter;
		if (!isset($current_settings["order"]))
			$current_settings["order"] = $order;
		if (!isset($current_settings["order_direction"]))
			$current_settings["order_direction"] = $order_direction;
		if (!isset($current_settings["page"]))
			$current_settings["page"] = $page;
		// Пошел процесс фильтрации
		$attrs['where']['poll_id'] = $search_params['where']['poll_id'] = $poll_id;
		switch ($filter) {
			case 'all':
				break;
			case 'authorized':
				$attrs["where_sql"][] = ' ( ' . RESPONSES_TABLE . '.user_id > 0 ) ';
				break;
			case 'not_authorized':
				$attrs["where_sql"][] = ' ( ' . RESPONSES_TABLE . '.user_id = 0 ) ';
				break;
			case $filter:
				foreach($user_types["option"] as $key => $value){
					if($filter == $value){
						$attrs["where"]["user_type"] = $key;
					}
				}
				break;
		}
		// Получаем счетчики
		$filter_data["all"] = $this->Polls_model->get_results_count($search_params);
		foreach($user_types["option"] as $key => $value){
			$search_params["where"]["user_type"] = $key;
			$filter_data[$value] = $this->Polls_model->get_results_count($search_params);
		}
		unset($search_params["where"]["user_type"]);
		$search_params["where_sql"][0] = ' ( ' . RESPONSES_TABLE . '.user_id > 0 ) ';
		$filter_data["authorized"] = $this->Polls_model->get_results_count($search_params);
		$search_params["where_sql"][0] = ' ( ' . RESPONSES_TABLE . '.user_id = 0 ) ';
		$filter_data["not_authorized"] = $this->Polls_model->get_results_count($search_params);
		// Используем пагинацию
		$items_on_page = $this->pg_module->get_module_config('start', 'admin_items_per_page');
		$this->load->helper('sort_order');
		$page = get_exists_page_number($page, $users_count, $items_on_page);
		$current_settings["page"] = $page;
		// Сохраняем настройки
		$_SESSION["results_list"] = $current_settings;
		// Ссылки для сортировки
		$sort_links = array(
			"user_id" => site_url() . "admin/polls/results/" . $poll_id . '/' . $filter . "/user_id/" . (($order != 'user_id' xor $order_direction == 'DESC') ? 'ASC' : 'DESC'),
			"date_add" => site_url() . "admin/polls/results/" . $poll_id . '/' . $filter . "/date_add/" . (($order != 'date_add' xor $order_direction == 'DESC') ? 'ASC' : 'DESC'),
			"ip" => site_url() . "admin/polls/results/" . $poll_id . '/' . $filter . "/ip/" . (($order != 'ip' xor $order_direction == 'DESC') ? 'ASC' : 'DESC'),
		);

		// Берем максимальное число ответов
		$max_answers = $this->pg_module->get_module_config('polls', 'max_answers');
		$max_answers = $max_answers ? $max_answers : 10;

		// Опять мегапортянка
		$answers_links = array();
		if(is_array($poll_data['answers_colors'])) {
			foreach ($poll_data['answers_colors'] as $id => $value) {
				$answers_links[$id] = site_url() . "admin/polls/results/" . $poll_id . '/' . $filter . "/" . 'answer_' . $id . "/" . (($order != 'answer_' . $id xor $order_direction == 'DESC') ? 'ASC' : 'DESC');
			}
		}

		// Запоминаем переменные
		$this->template_lite->assign('answers_links', $answers_links);

		$this->template_lite->assign('sort_links', $sort_links);
		// Получаем ответы пользователей
		$results_count = $filter_data[$filter];
		if ($results_count > 0) {
			$results = $this->Polls_model->get_results_list($page, $items_on_page, array($order => $order_direction), $attrs);
			$this->template_lite->assign('results_data', $results);
		}
		// Последние формирования
		$this->load->helper("navigation");
		$url = site_url() . "admin/polls/results/" . $poll_id . '/' . $filter . "/" . $order . "/" . $order_direction . "/" . $page;
		$page_data = get_admin_pages_data($url, $users_count, $items_on_page, $page, 'briefPage');
		$page_data["date_format"] = $this->pg_date->get_format('date_time_literal', 'st');
		// Отображаем все
		$this->template_lite->assign('page_data', $page_data);
		$this->template_lite->assign('filter_data', $filter_data);
		$this->template_lite->assign('user_types', $user_types["option"]);
		$this->template_lite->assign('filter', $filter);
		$this->template_lite->assign('order', $order);
		$this->template_lite->assign('order_direction', $order_direction);
		$this->template_lite->assign('page', $page);
		$this->template_lite->assign('poll', $poll_data);
		$this->template_lite->assign('languages', $this->pg_language->languages);
		$this->template_lite->assign('cur_lang', $this->pg_language->current_lang_id);
		$this->template_lite->assign('results', 1);
		$this->system_messages->set_data('header', l('link_results', 'polls'));
		$cur_set = $_SESSION["polls_list"];
		$url = site_url() . "admin/polls/index/" . (isset($cur_set["filter"]) ? $cur_set["filter"] : 'all') . "/" . (isset($cur_set["order"]) ? $cur_set["order"] : 'question') . "/" . (isset($cur_set["order_direction"]) ? $cur_set["order_direction"] : 'ASC') . "/" . (isset($cur_set["page"]) ? $cur_set["page"] : 1) . "";
		$this->system_messages->set_data('back_link', $url);
		$this->template_lite->view('list_results');
	}

}
