<?php
/**
* Social networking widgets model
* 
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

class Social_networking_widgets_model extends Model {

	var $CI;
	var $DB;
	var $locales = array();
	var $locale = array();

	function Social_networking_widgets_model() {
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;
		if (count($this->locales) == 0) {
			if (@require(APPPATH . 'config/locales' . EXT)) {
				$this->locales = $locales;
			}
		}
		$lang = $this->CI->pg_language->get_lang_by_id($this->CI->pg_language->current_lang_id);
		$lang_code = isset($lang['code']) ? $lang['code'] : false;
		$locale = isset($this->locales[$lang_code]) ? $this->locales[$lang_code] : 'en_US';
		$this->locale = $locale;
	}

	public function get_widgets($widget = '', $settings = array(), $display_type = 'row') {
		$widgets_text = '';
		$this->CI->load->model('social_networking/models/Social_networking_services_model');
		$services = $this->CI->Social_networking_services_model->get_services_list(array('id' => 'ASC'), array('where' => array('status' => 1)));
		if (count($services) > 0) {
			foreach ($services as $id => $value) {
				if (isset($settings[$value['gid']])) {
					$service_gid = $value['gid'];
					$service_model = isset($value['gid']) ? $value['gid'] . '_widgets_model' : false;
					$service_file = $service_model ? APPPATH . 'modules/social_networking/models/widgets/' . $service_model . '.php' : false;
					if ($service_file && file_exists($service_file)) {
						include_once($service_file);
						$service = new $service_model();
						if (in_array($widget, $service->widget_types)) {
							$method = 'get_' . $widget;
							if (method_exists($service, 'get_header')) {
								$service->get_header($value, $locale, array('like', 'share', 'comments'));
							}
							if (method_exists($service, $method)) {
								
								$text = $service->$method();
								if ($display_type == 'row'){
									$widgets_text .= $text ? '<td>' . $text . '</td>' : '';
								} else{
									$widgets_text .= $text ? '<tr><td>' . $text . '</td></tr>' : '';
								}
							}
						}
					}
				}
			}
		}
		$widgets_text = $widgets_text ? '<table class="widgets">'.($display_type == 'row' ? '<tr>' : '').$widgets_text.($display_type == 'row' ? '</tr>' : '').'</table>' : '';
		return $widgets_text;
	}

	public function get_header() {
		$header_text = '';
		$this->CI->load->model('social_networking/models/Social_networking_services_model');
		$services = $this->CI->Social_networking_services_model->get_services_list(array('id' => 'ASC'), array('where' => array('status' => 1)));
		if (count($services) > 0)
			foreach ($services as $id => $value) {
				$service_gid = $value['gid'];
				$service_model = isset($value['gid']) ? $value['gid'] . '_widgets_model' : false;
				$service_file = $service_model ? APPPATH . 'modules/social_networking/models/widgets/' . $service_model . '.php' : false;
				if ($service_file && file_exists($service_file)) {
					include_once($service_file);
					$service = new $service_model();
					$method = 'get_header';
					if (method_exists($service, $method)) {
						$header_text .= $service->$method($value, $this->locale, array('like', 'share', 'comments'));
					}
				}
			}
		return $header_text;
	}

	public function get_widgets_actions($services = array()) {
		$actions = array();
		if (count($services) > 0)
			foreach ($services as $id => $value) {
				$service_gid = $value['gid'];
				$service_model = isset($value['gid']) ? $value['gid'] . '_widgets_model' : false;
				$service_file = $service_model ? APPPATH . 'modules/social_networking/models/widgets/' . $service_model . '.php' : false;
				if ($service_file && file_exists($service_file)) {
					include_once($service_file);
					$service = new $service_model();
					$actions[$id] = isset($service->widget_types) ? $service->widget_types : array();
				}
			}
		return $actions;
	}

}
