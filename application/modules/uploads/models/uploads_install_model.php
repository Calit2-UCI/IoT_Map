<?php
/**
* Uploads install model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

class Uploads_install_model extends Model
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
								'uploads_menu_item' => array('action' => 'create', 'link' => 'admin/uploads', 'status' => 1, 'sorter' => 2)
							)
						)
					)
				)
			)
		),
		'admin_uploads_menu' => array(
			'action' => 'create',
			'name' => 'Uploads section menu',
			'items' => array(
				'configs_list_item' => array('action' => 'create', 'link' => 'admin/uploads', 'status' => 1),
				'watermarks_list_item' => array('action' => 'create', 'link' => 'admin/uploads/watermarks', 'status' => 1)
			)
		)
	);

	/**
	 * Constructor
	 *
	 * @return Install object
	 */
	function Uploads_install_model()
	{
		parent::Model();
		$this->CI = & get_instance();
	}

	function _validate_requirements(){
		$result = array('data'=>array(), 'result' => true);

		//check for GD library
		$is_gd_loaded = extension_loaded('gd');
		$result["data"][] = array(
			"name" => "GD library (works with graphics and images) is installed",
			"value" => $is_gd_loaded  ? "Yes" : "No",
			"result" => $is_gd_loaded ,
		);
		$result["result"] = $result["result"] && $is_gd_loaded;

		return $result;
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
		$langs_file = $this->CI->Install_model->language_file_read('uploads', 'menu', $langs_ids);

		if(!$langs_file) { log_message('info', 'Empty menu langs data'); return false; }

		$this->CI->load->helper('menu');

		foreach($this->menu as $gid => $menu_data){
			linked_install_process_menu_items($this->menu, 'update', $gid, 0, $this->menu[$gid]["items"], $gid, $langs_file);
		}
		return true;
	}

	public function install_menu_lang_export($langs_ids) {
		if(empty($langs_ids)) return false;
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
		$this->CI->load->model('uploads/models/Uploads_config_model');
		$data = array(
			"name" => "Text watermark",
			"gid" => "text-wm",
			"position_hor" => 'right',
			"position_ver" => 'bottom',
			"alpha" => '100',
			"wm_type" => "text",
			"img" => '',
			"font_size" => '30',
			"font_color" => '241424',
			"font_face" => 'avant',
			"font_text" => 'pilotgroup',
			"shadow_color" => 'f5f5f5',
			"shadow_distance" => '1',
		);
		$this->CI->Uploads_config_model->save_watermark(null, $data);

		$data = array(
			"name" => "Image watermark",
			"gid" => "image-wm",
			"position_hor" => 'right',
			"position_ver" => 'bottom',
			"alpha" => '100',
			"wm_type" => "img",
			"img" => 'wm_image-wm.png',
			"font_size" => '16',
			"font_color" => 'ffffff',
			"font_face" => 'arial',
			"font_text" => 'Test string',
			"shadow_color" => '777777',
			"shadow_distance" => '3',
		);
		$this->CI->Uploads_config_model->save_watermark(null, $data);
	}

	function _arbitrary_deinstalling(){
		$this->CI->load->model('uploads/models/Uploads_config_model');

		$wm = $this->CI->Uploads_config_model->get_watermark_by_gid('text-wm');
		$this->CI->Uploads_config_model->delete_watermark($wm["id"]);

		$wm = $this->CI->Uploads_config_model->get_watermark_by_gid('image-wm');
		$this->CI->Uploads_config_model->delete_watermark($wm["id"]);
	}
}