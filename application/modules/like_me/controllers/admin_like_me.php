<?php
/**
* Admin Like me controller
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Nikita Savanaev <nsavanaev@pilotgroup.net>
**/

class Admin_Like_me extends Controller 
{


	/**
	 * Controller
	 * @return Admin_Like_me
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('like_me/models/Like_me_model');
		$this->load->model('Menu_model');
        $this->load->library('PG_Output');
		$this->Menu_model->set_menu_active_item('admin_menu', 'add_ons_items');
	}

    /**
     * Settings module
     * @return string
     */
	
	public function index()
	{
		$data = $this->Like_me_model->getSettings();
        $langs = $this->pg_language->languages;
        $lang_id = $this->pg_language->current_lang_id;
        if ($this->input->post('btn_save')) {
			$post_data = array(
				'matches_per_page' => $this->input->post('matches_per_page', true),
				'play_local_used' => $this->input->post('play_local_used', true),
				'play_local_area' => $this->input->post('play_local_area', true),
				'play_more' => $this->input->post('play_more', true),
				'chat_message' => $this->input->post('chat_message', true),
				'chat_more' => $this->input->post('chat_more', true),
				/*"bonus_count" => $this->input->post('bonus_count', true),
				"bonus_likes" => $this->input->post('bonus_likes', true),
				"bonus_type" => $this->input->post('bonus_type', true),*/
			);
			$validate_data = $this->Like_me_model->validateSettings($post_data);
			if (!empty($validate_data["errors"])) {
                $this->pg_output->setOutputContent('errors', $validate_data['errors']);
			} else {
				$this->Like_me_model->setSettings($validate_data["data"]);
				$this->Like_me_model->setMessageFields($validate_data["ds"]);
				$this->system_messages->add_message('success', l('success_settings_save', 'like_me'));
                $this->pg_output->setOutputContent('redirect', site_url()."admin/like_me/index");
			}
		}
        $this->pg_output->setOutputData('data', $data);
        $this->pg_output->setOutputData('langs', $langs);
        $this->pg_output->setOutputData('current_lang_id', $lang_id);
        $this->pg_output->setOutputHeader(l('admin_header_settings', 'like_me'));
        $this->pg_output->setOutputContent('back_link', site_url()."admin/start/menu/add_ons_items");
        $this->pg_output->setOutputContent('template', 'settings');
	}   
	
}
