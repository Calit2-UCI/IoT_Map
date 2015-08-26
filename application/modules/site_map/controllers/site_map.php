<?php
/**
* Site map user side controller
* 
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

Class Site_map extends Controller
{
	/**
	 * link to CodeIgniter object
	 * @var object
	 */

	/**
	 * Constructor
	 */
	function __construct(){
		parent::Controller();
		$this->load->model("Site_map_model");
	}


	public function index(){
		$url_blocks = $this->Site_map_model->get_sitemap_links();
		$this->template_lite->assign("blocks", $url_blocks);
		
		$current_lang_id = $this->pg_language->current_lang_id;
		$settings = $this->pg_seo->get_settings('user', 'site_map', 'index');
		$title = !empty($settings['meta_'.$current_lang_id]['header']) ? $settings['meta_'.$current_lang_id]['header'] : '';
	
		//breadcrumbs
		$this->load->model('Menu_model');
		$this->Menu_model->breadcrumbs_set_active($title);
		
		$this->template_lite->view('sitemap');
	}

}
