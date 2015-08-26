<?php 

/**
 * Widgets user side controller
 * 
 * @package PG_RealEstate
 * @subpackage Widgets
 * @category	controllers
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @author Katya Kashkova <katya@pilotgroup.net>
 * @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
 **/
class Widgets extends Controller{
	
	/**
	 * Constructor
	 * @return Widgets
	 */
	public function __construct(){
		parent::Controller();
		$this->load->model('Widgets_model');
	}
	
	/**
	 * Return widget code
	 * @return string
	 */
	public function index($widget_gid){
		$this->Widgets_model->set_format_settings('get_content', true);
		$widget = $this->Widgets_model->get_widget_by_gid($widget_gid, filter_input(INPUT_SERVER, 'HTTP_REFERER'));
		$this->Widgets_model->set_format_settings('get_content', false);
		if(!$widget['content']) exit;
		$this->template_lite->assign('widget', $widget);
		echo $this->template_lite->fetch('widget', 'user', 'widgets');
	}
	
	/*
	 * Return widget code by ajax
	 * @return string
	 */
	public function ajax_index($widget_gid){
		$this->Widgets_model->set_format_settings('get_content', true);
		$widget = $this->Widgets_model->get_widget_by_gid($widget_gid);
		$this->Widgets_model->set_format_settings('get_content', false);
		$this->template_lite->assign('widget', $widget);
		echo $this->template_lite->fetch('ajax_widget', 'user', 'widgets');
	}
}
