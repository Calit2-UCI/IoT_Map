<?php
/**
* Cronjob install model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/
class Cronjob_install_model extends Model
{
	var $CI;
	private $menu = array(
		'admin_menu' => array(
			'action' => 'none',
			'items' => array(
				'settings_items' => array(
					'action' => 'none',
					'items' => array(
						'system-items' => array(
							'action'=>'none',
							'items' => array(
								'cronjob_menu_item' => array('action' => 'create', 'link' => 'admin/cronjob', 'status' => 1, 'sorter' => 1)
							)
						)
					)
				)
			)
		)
	);

	/**
	 * Constructor
	 *
	 * @return Install object
	 */
	function Cronjob_install_model()
	{
		parent::Model();
		$this->CI = & get_instance();
		//// load langs
		$this->CI->load->model('Install_model');
	}

	public function install_menu() {
		$this->CI->load->helper('menu');
		foreach($this->menu as $gid => $menu_data){
			$this->menu[$gid]['id'] = linked_install_set_menu($gid, $menu_data["action"], $menu_data["name"]);
			linked_install_process_menu_items($this->menu, 'create', $gid, 0, $this->menu[$gid]["items"]);
		}
	}

	public function install_menu_lang_update($langs_ids = null) {
		if(empty($langs_ids)) return false;
		$langs_file = $this->CI->Install_model->language_file_read('cronjob', 'menu', $langs_ids);

		if(!$langs_file) { log_message('info', 'Empty menu langs data'); return false; }

		$this->CI->load->model('Menu_model');
		$this->CI->load->helper('menu');

		foreach($this->menu as $gid => $menu_data){
			linked_install_process_menu_items($this->menu, 'update', $gid, 0, $this->menu[$gid]["items"], $gid, $langs_file);
		}
		return true;
	}

	public function install_menu_lang_export($langs_ids) {
		if(empty($langs_ids)) return false;
		$this->CI->load->model('Menu_model');
		$this->CI->load->helper('menu');

		$return = array();
		foreach($this->menu as $gid => $menu_data){
			$temp = linked_install_process_menu_items($this->menu, 'export', $gid, 0, $this->menu[$gid]["items"], $gid, $langs_ids);
			$return = array_merge($return, $temp);
		}
		return array( "menu" => $return );
	}

	public function deinstall_menu() {
		$this->CI->load->helper('menu');
		foreach($this->menu as $gid => $menu_data){
			if($menu_data['action'] == 'create'){
				linked_install_set_menu($gid, 'delete');
			}else{
				linked_install_delete_menu_items($gid, $this->menu[$gid]['items']);
			}
		}
	}

	function _arbitrary_installing(){
		///// add start cronjob ()
		$this->CI->load->model('Cronjob_model');
		$cron_data = array(
			"name" => "Clear trash",
			"module" => "start",
			"model" => "Start_model",
			"method" => "clear_trash_folder",
			"cron_tab" => "0 0 * * *",
			"status" => "1"
		);
		$this->CI->Cronjob_model->save_cron(null, $cron_data);
	}

	function _arbitrary_deinstalling(){

	}
}