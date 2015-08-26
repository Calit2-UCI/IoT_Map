<?php

/**
 * Contact us user side controller
 * 
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @author Nikita Savanaev <nsavanev@pilotgroup.net>
 * */
Class Themes extends Controller {

	/**
	 * Constructor
	 */
	function __construct() {
		parent::Controller();
	}

	public function change_color_scheme($theme, $scheme = '') {
		$theme_data = $this->pg_theme->get_theme_data($theme);
		if (empty($this->session->userdata['auth_type']) || ($theme_data['type'] === $this->session->userdata['auth_type'] || 'admin' === $this->session->userdata['auth_type'])) {
			if (!$scheme) {
				$theme_base_data = $this->pg_theme->get_theme_base_data($theme);
				$scheme = !empty($theme_base_data[$theme]['scheme']) ? $theme_base_data[$theme]['scheme'] : '';
			}
			$_SESSION['change_color_scheme'] = true;
			$_SESSION['preview_theme'] = $theme;
			$_SESSION['preview_scheme'] = $scheme;
		} else {
			unset($_SESSION['change_color_scheme']);
		}
		redirect(filter_input(INPUT_SERVER, 'HTTP_REFERER'), 'hard');
	}

}
