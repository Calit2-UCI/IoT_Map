<?php
/**
* Banners api controller
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Alexander Batukhtin <abatukhtin@pilotgroup.net>
**/
class Api_Banners extends Controller {

	public function __construct()
	{
		parent::Controller();
		$this->load->model('Banners_model');
	}

	/**
	 * Go
	 *
	 * @param int $banner_id
	 */
	public function go()
	{
		$banner_id = $this->input->post('banner_id', true);
		$banner_id = (is_numeric($banner_id) && $banner_id > 0) ? intval($banner_id) : 0;
		if (!$banner_id || !($banner_obj = $this->Banners_model->get($banner_id))){
			log_message('error', 'banners API: Empty banner id');
			$this->set_api_content('error', l('api_error_empty_banner_id', 'banners'));
			return false;
		}

		// add info to statistic
		$this->load->model('banners/models/Banners_stat_model');
		$this->Banners_stat_model->add_hit($banner_id);

		$stat = $this->Banners_model->get_banner_overall_stat($banner_id);
		$this->Banners_model->save_banner_clicks($banner_id, $stat['stat_clicks'] + 1);
		$this->set_api_content('messages', l('banner_statistic_update_success', 'banners'));

		$url = isset($banner_obj['link']) ? $banner_obj['link'] : '';
		$this->set_api_content('data', array('url' => $url));
	}

	public function my() {

		if($this->session->userdata('auth_type') != 'user') {
			log_message('error', 'banners API: Wrong auth type ("' . $this->session->userdata('auth_type') . '")');
			$this->set_api_content('errors', l('api_error_wrong_auth_type', 'banners'));
			return false;
		}

		$user_id = $this->session->userdata('user_id');
		if(!$user_id) {
			log_message('error', 'banners API: Empty user id');
			$this->set_api_content('errors', l('api_error_empty_user_id', 'banners'));
			return false;
		}

		$params['where']['user_id'] = $user_id;
		$cnt_banners = $this->Banners_model->cnt_banners($params);

		$items_on_page = 10;
		$page = $this->input->post('page', true);
		$this->load->helper('sort_order');
		$page = get_exists_page_number($page, $cnt_banners, $items_on_page);

		$banners = $this->Banners_model->get_banners($page, $items_on_page, array('id' => 'DESC'), $params);
		// get place objects for banner
		if ($banners){
			$this->load->model('banners/models/Banner_place_model');
			foreach ($banners as $key => $banner){
				$banners[$key]['banner_place_obj'] = $banner['banner_place_id'] ? $this->Banner_place_model->get($banner['banner_place_id']) : null;
			}
			$this->set_api_content('data', array('banners' => $banners));
		} else {
			$this->set_api_content('messages', l('api_error_empty_list', 'banners'));
		}
	}

	/**
	 * Get places
	 *
	 * @param int $access
	 */
	public function get_places() {
		$access = $this->input->post('access');
		$this->load->model('banners/models/Banner_place_model');
		$this->set_api_content('data', array('places' => $this->Banner_place_model->get_all_places($access)));
	}

	/**
	 * Save banner
	 *
	 * @param string $name
	 * @param int $banner_place_id
	 * @param string $link
	 * @param string $alt_text
	 * @param date $expiration_date
	 */
	public function save(){
		if($this->session->userdata('auth_type') != 'user'){
			log_message('error', 'banners API: Wrong auth type ("' . $this->session->userdata('auth_type') . '")');
			$this->set_api_content('errors', l('api_error_wrong_auth_type', 'banners'));
			return false;
		}
		$post_data = array(
			'name' => $this->input->post('name', true),
			'banner_type' => 1,
			'banner_place_id' => $this->input->post('banner_place_id', true),
			'not_in_rotation' => 0,
			'status' => 0,
			'link' => $this->input->post('link', true),
			'alt_text' => $this->input->post('alt_text', true),
			'number_of_clicks' => 0,
			'number_of_views' => 0,
			'new_window' => 1,
			'expiration_date' => $this->input->post('expiration_date', true),
		);
		$validate_data = $this->Banners_model->validate_banner(null, $post_data, 'banner_image_file');
		if(!empty($validate_data['errors'])){
			$this->set_api_content('errors', $validate_data['errors']);
			$this->set_api_content('data', $validate_data['data']);
		} else {
			$banner_id = $this->Banners_model->save(null, $validate_data['data'], 'banner_image_file');
			$this->set_api_content('messages', l('success_update_banner_data', 'banners'));
			$this->set_api_content('data', array('banner_id' => $banner_id));
		}
	}

	/**
	 * Activate banner
	 *
	 * @param int $banner_id
	 * @param type $used_position
	 */
	public function activate(){
		if($this->session->userdata('auth_type') != 'user'){
			log_message('error', 'banners API: Wrong auth type ("' . $this->session->userdata('auth_type') . '")');
			$this->set_api_content('errors', l('api_error_wrong_auth_type', 'banners'));
			return false;
		}

		$banner_id = $this->input->post('banner_id', true);
		$banner_id = (is_numeric($banner_id) && $banner_id > 0) ? intval($banner_id) : 0;
		if (!$banner_id){
			$this->set_api_content('error', l('api_error_empty_banner_id', 'banners'));
			return false;
		}

		if ($this->pg_module->is_module_installed('services')){
			$this->load->model('Services_model');
			$service_data = $this->Services_model->get_service_by_gid('banner_service');
			if(empty($service_data) || !$service_data['status']){
				$this->set_api_content('errors', l('error_empty_service_activate_service', 'banners'));
				return false;
			}
		} else {
			$this->set_api_content('errors', l('error_empty_service_activate_service', 'banners'));
			return false;
		}

		$banner = $this->Banners_model->get($banner_id);
		// check banner's existence

		$info = $this->Banners_model->get_user_activate_info($banner_id);

		$this->load->model('banners/models/Banner_place_model');
		$place = $this->Banner_place_model->get($banner['banner_place_id']);
		$place['places_in_rotation'] = intval($place['places_in_rotation']);

		$this->load->model('banners/models/Banner_group_model');
		$groups = $this->Banner_group_model->get_all_groups_key_id();

		$gids= array_keys($groups);
		$fill_places = $this->Banner_group_model->get_fill_positions($gids, $banner['banner_place_id'], $banner['id']);
		foreach($groups as $k => $group){
			$groups[$k]['free_positions'] = $place['places_in_rotation'];
			if(!empty($fill_places[$group['id']])){
				$groups[$k]['free_positions'] = $groups[$k]['free_positions'] - $fill_places[$group['id']];
				if($groups[$k]['free_positions'] < 0) $groups[$k]['free_positions'] = 0;
			}
		}

		$used_position = $this->input->post('used_position', true);
		$validate = array('positions' => array(), 'sum' => 0);
		if($used_position) {
			foreach($used_position as $group_id => $used_position){
				$used_position = intval($used_position);
				if($used_position > $groups[$group_id]['free_positions'] ){
					$used_position = $groups[$group_id]['free_positions'];
				}
				if($used_position < 0){
					$used_position = 0;
				}
				if($used_position){
					$validate['positions'][$group_id] = $used_position;
					$validate['sum'] += $used_position * $groups[$group_id]['price'];
				}
			}
		}
		if($validate['sum'] <= 0){
			$this->set_api_content('errors', l('error_empty_activate_banner_sum', 'banners'));
			return false;
		}else{
			$this->Banners_model->set_user_activate_info($banner_id, $validate);
			$this->load->helper('payments');
			post_location_request(site_url() . 'services/form/banner_service', array('price' => $validate['sum'], 'id_banner_payment' => $banner_id));
		}
		foreach($groups as $k => $group){
			$groups[$k]['user_positions'] = !empty($info['positions'][$group['id']]) ? $info['positions'][$group['id']] : 0;
		}
		$this->set_api_content('data', array('service_data' => $service_data, 'groups' => $groups));
	}

	/**
	 * Delete banner
	 *
	 * @param int $banner_id
	 */
	public function delete(){

		if($this->session->userdata('auth_type') != 'user'){
			log_message('error', 'banners API: Wrong auth type ("' . $this->session->userdata('auth_type') . '")');
			$this->set_api_content('errors', l('api_wrong_auth_type', 'banners'));
			return false;
		}

		$banner_id = $this->input->post('banner_id', true);
		$banner_id = (is_numeric($banner_id) && $banner_id > 0) ? intval($banner_id) : 0;

		if (!$banner_id){
			$this->set_api_content('error', l('api_error_empty_banner_id', 'banners'));
			return false;
		}
		$banner = $this->Banners_model->get($banner_id);
		if(!$banner) {
			$this->set_api_content('error', l('api_error_wrong_banner_id', 'banners'));
			return false;
		}

		if($this->session->userdata('user_id') != $banner['user_id']){
			log_message('error', 'banners API: The user is not own this banner');
			$this->set_api_content('errors', l('api_error_not_owner', 'banners'));
			return false;
		}
		$this->Banners_model->delete($banner_id);
		$this->set_api_content('messages', l('success_delete_banner', 'banners'));
		$this->set_api_content('data', array('banner_id' => $banner_id));
	}

	/**
	 * Banner statistic
	 *
	 * @param int $banner_id
	 * @param bool $update Update statistic before return
	 * @param type $yeaar
	 * @param type $month
	 * @param type $day
	 */
	public function statistic(){
		$banner_id = $this->input->post('banner_id', true);
		$banner_id = (is_numeric($banner_id) && $banner_id > 0) ? intval($banner_id) : 0;
		if (!$banner_id){
			$this->set_api_content('error', l('api_error_empty_banner_id', 'banners'));
			return false;
		}

		if($this->session->userdata('auth_type') != 'user'){
			log_message('error', 'banners API: Wrong auth type ("' . $this->session->userdata('auth_type') . '")');
			$this->set_api_content('errors', l('api_wrong_auth_type', 'banners'));
			return false;
		}

		$banner = $this->Banners_model->get($banner_id);
		if($this->session->userdata('user_id') != $banner['user_id']){
			log_message('error', 'banners API: The user is not own this banner');
			$this->set_api_content('errors', l('api_error_not_owner', 'banners'));
			return false;
		}

		if($this->input->post('update', true)) {
			$this->update_statistic();
		}

		$year = $this->input->post('year', true);
		$month = $this->input->post('month', true);
		$day = $this->input->post('day', true);
		if(!$year || !$month || !$day){
			$year = date('Y');
			$month = intval(date('m'));
			$day = intval(date('d'));
		}

		$banner_data = $this->Banners_model->get($banner_id);

		$this->load->model('banners/models/Banners_stat_model');
		$statistic = $this->Banners_stat_model->get_day_statistic($banner_id, $year, $month, $day);

		$this->set_api_content('data', array(
			'banner_data' => $banner_data,
			'statistic' => $statistic)
		);
	}

	/**
	 * Updates banners statistic
	 *
	 * @return type
	 */
	public function update_statistic(){
		$this->load->model('banners/models/Banners_stat_model');
		$date = date('Y-m-d');
		$this->Banners_stat_model->update_file_statistic();
		$this->Banners_stat_model->update_day_statistic($date);
		$this->Banners_stat_model->update_week_statistic($date);
		$this->Banners_stat_model->update_month_statistic($date);
		$this->Banners_stat_model->update_year_statistic($date);
		$this->set_api_content('messages', l('banner_statistic_update_success', 'banners'));
		$this->set_api_content('data', true);
		return;
	}
}
