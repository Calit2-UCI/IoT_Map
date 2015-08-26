<?php
/**
* Themes install model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

class Themes_install_model extends Model
{
	var $CI;
	private $menu = array(
		'admin_menu' => array(
			'action' => 'none',
			'items' => array(
				'settings_items' => array(
					'action' => 'none',
					'items' => array(
						'interface-items' => array(
							'action'=>'none',
							'items' => array(
								'themes_menu_item' => array('action' => 'create', 'link' => 'admin/themes/installed_themes', 'status' => 1, 'sorter' => 3)
							)
						)
					)
				)
			)
		)
	);
	private $moderators_methods = array(
		array('module' => 'themes', 'method' => 'installed_themes', 'is_default' => 1),
		array('module' => 'themes', 'method' => 'enable_themes', 'is_default' => 0),
	);

	protected $dynamic_blocks = array(
        'logo_block' => array(
            'gid' => 'logo_block',
            'module' => 'themes',
            'model' => 'Themes_model',
            'method' => '_dynamic_block_get_logo_block',
            'views' => array(
				array(
					'gid' => 'default',
				),
			),
			'area' => array(
				array(
					'gid' => 'index-page',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => '50',
					'sorter' => '3',
					'cache_time' => '0',
				),
				array(
					'gid' => 'mediumturquoise',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => '50',
					'sorter' => '3',
					'cache_time' => '0',
				),
				array(
					'gid' => 'lavender',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => '50',
					'sorter' => '3',
					'cache_time' => '0',
				),
				array(
					'gid' => 'girls',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => '30',
					'sorter' => '2',
					'cache_time' => '0',
				),
				array(
					'gid' => 'girlfriends',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => '50',
					'sorter' => '2',
					'cache_time' => '0',
				),
				array(
					'gid' => 'jewish',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => '70',
					'sorter' => '2',
					'cache_time' => '0',
				),
				array(
					'gid' => 'lovers',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => '70',
					'sorter' => '2',
					'cache_time' => '0',
				),
				array(
					'gid' => 'neighbourhood',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => '50',
					'sorter' => '2',
					'cache_time' => '0',
				),
				array(
					'gid' => 'blackonwhite',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => '50',
					'sorter' => '3',
					'cache_time' => '0',
				),
				array(
					'gid' => 'deepblue',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => '70',
					'sorter' => '2',
					'cache_time' => '0',
				),
				array(
					'gid' => 'companions',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => '70',
					'sorter' => '2',
					'cache_time' => '0',
				),
				array(
					'gid' => 'community',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => '70',
					'sorter' => '2',
					'cache_time' => '0',
				),
				array(
					'gid' => 'christian',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => '50',
					'sorter' => '2',
					'cache_time' => '0',
				),
				array(
					'gid' => 'autumn_walk',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => '50',
					'sorter' => '3',
					'cache_time' => '0',
				),
				array(
					'gid' => 'social',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => '30',
					'sorter' => '1',
					'cache_time' => '0',
				),
                array(
					'gid' => 'persimmon_red',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => '50',
					'sorter' => '3',
					'cache_time' => '0',
				),
			),
			'presets' => array(
				array(
					'gid_preset' => 'index-page',
					'gid_area' => 'index-page',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => '50',
					'cache_time' => '0',
					'sorter' => '3',
				),
				array(
					'gid_preset' => 'mediumturquoise',
					'gid_area' => 'mediumturquoise',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => '50',
					'cache_time' => '0',
					'sorter' => '3',
				),
				array(
					'gid_preset' => 'lavender',
					'gid_area' => 'lavender',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => '50',
					'cache_time' => '0',
					'sorter' => '3',
				),
				array(
					'gid_preset' => 'girls',
					'gid_area' => 'girls',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => '30',
					'cache_time' => '0',
					'sorter' => '2',
				),
				array(
					'gid_preset' => 'girlfriends',
					'gid_area' => 'girlfriends',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => '50',
					'cache_time' => '0',
					'sorter' => '2',
				),
				array(
					'gid_preset' => 'jewish',
					'gid_area' => 'jewish',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => '70',
					'cache_time' => '0',
					'sorter' => '2',
				),
				array(
					'gid_preset' => 'lovers',
					'gid_area' => 'lovers',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => '70',
					'cache_time' => '0',
					'sorter' => '2',
				),
				array(
					'gid_preset' => 'neighbourhood',
					'gid_area' => 'neighbourhood',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => '50',
					'cache_time' => '0',
					'sorter' => '2',
				),
				array(
					'gid_preset' => 'blackonwhite',
					'gid_area' => 'blackonwhite',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => '50',
					'cache_time' => '0',
					'sorter' => '3',
				),
				array(
					'gid_preset' => 'deepblue',
					'gid_area' => 'deepblue',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => '70',
					'cache_time' => '0',
					'sorter' => '2',
				),
				array(
					'gid_preset' => 'companions',
					'gid_area' => 'companions',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => '70',
					'cache_time' => '0',
					'sorter' => '2',
				),
				array(
					'gid_preset' => 'community',
					'gid_area' => 'community',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => '70',
					'cache_time' => '0',
					'sorter' => '2',
				),
				array(
					'gid_preset' => 'christian',
					'gid_area' => 'christian',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => '50',
					'cache_time' => '0',
					'sorter' => '2',
				),
				array(
					'gid_preset' => 'autumn_walk',
					'gid_area' => 'autumn_walk',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => '50',
					'cache_time' => '0',
					'sorter' => '3',
				),
				array(
					'gid_preset' => 'social',
					'gid_area' => 'social',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => '30',
					'cache_time' => '0',
					'sorter' => '1',
				),
                array(
                    'gid_preset' => 'persimmon_red',
					'gid_area' => 'persimmon_red',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => '50',
					'sorter' => '3',
					'cache_time' => '0',
				),
			),
		),
	);

	/*private $dynamic_blocks = array(
		array(
			'gid' => 'logo_block',
			'module' => 'themes',
			'model' => 'Themes_model',
			'method' => '_dynamic_block_get_logo_block',
			'params' => array(),
			'views' => array(array('gid'=>'default')),
			'area' => array(
				array(
					'gid' => 'index-page',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => 50,
					'sorter' => 3,
					'cache_time' => 0,
				),
				array(
					'gid' => 'mediumturquoise',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => 50,
					'sorter' => 3,
					'cache_time' => 0,
				),
				array(
					'gid' => 'lavender',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => 50,
					'sorter' => 3,
					'cache_time' => 0,
				),
				array(
					'gid' => 'girls',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => 30,
					'sorter' => 2,
					'cache_time' => 0,
				),
				array(
					'gid' => 'girlfriends',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => 50,
					'sorter' => 2,
					'cache_time' => 0,
				),
				array(
					'gid' => 'jewish',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => 70,
					'sorter' => 2,
					'cache_time' => 0,
				),
				array(
					'gid' => 'lovers',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => 70,
					'sorter' => 2,
					'cache_time' => 0,
				),
				array(
					'gid' => 'neighbourhood',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => 50,
					'sorter' => 2,
					'cache_time' => 0,
				),
				array(
					'gid' => 'blackonwhite',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => 50,
					'sorter' => 3,
					'cache_time' => 0,
				),
				array(
					'gid' => 'deepblue',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => 70,
					'sorter' => 2,
					'cache_time' => 0,
				),
				array(
					'gid' => 'companions',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => 70,
					'sorter' => 2,
					'cache_time' => 0,
				),
				array(
					'gid' => 'community',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => 70,
					'sorter' => 2,
					'cache_time' => 0,
				),
				array(
					'gid' => 'christian',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => 50,
					'sorter' => 2,
					'cache_time' => 0,
				),
				array(
					'gid' => 'autumn_walk',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => 50,
					'sorter' => 3,
					'cache_time' => 0,
				),
			),
		),
	);*/
	
	/**
	 * Constructor
	 *
	 * @return Install object
	 */
	function Themes_install_model()
	{
		parent::Model();
		$this->CI = & get_instance();
	}

	function _validate_requirements(){
		$result = array('data'=>array(), 'result' => true);

		//check for GD library
		$good			= extension_loaded('gd');
		$result["data"][] = array(
			"name" => "GD library (works with graphics and images) is installed",
			"value" => $good?"Yes":"No",
			"result" => $good,
		);
		$result["result"] = $result["result"] && $good;

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
		$langs_file = $this->CI->Install_model->language_file_read('themes', 'menu', $langs_ids);

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

	/**
	 * Moderators module methods
	 */
	public function install_moderators() {
		// install moderators permissions
		$this->CI->load->model('moderators/models/Moderators_model');

		foreach($this->moderators_methods as $method){
			$this->CI->Moderators_model->save_method(null, $method);
		}
	}

	public function install_moderators_lang_update($langs_ids = null) {
		$langs_file = $this->CI->Install_model->language_file_read('themes', 'moderators', $langs_ids);

		// install moderators permissions
		$this->CI->load->model('moderators/models/Moderators_model');
		$params['where']['module'] = 'themes';
		$methods = $this->CI->Moderators_model->get_methods_lang_export($params);

		foreach($methods as $method){
			if(!empty($langs_file[$method['method']])){
				$this->CI->Moderators_model->save_method($method['id'], array(), $langs_file[$method['method']]);
			}
		}
	}

	public function install_moderators_lang_export($langs_ids) {
		$this->CI->load->model('moderators/models/Moderators_model');
		$params['where']['module'] = 'themes';
		$methods =  $this->CI->Moderators_model->get_methods_lang_export($params, $langs_ids);
		foreach($methods as $method){
			$return[$method['method']] = $method['langs'];
		}
		return array('moderators' => $return);
	}

	public function deinstall_moderators() {
		// delete moderation methods in moderators
		$this->CI->load->model('moderators/models/Moderators_model');
		$params['where']['module'] = 'themes';
		$this->CI->Moderators_model->delete_methods($params);
	}

	function _arbitrary_installing(){
	}

	function _arbitrary_deinstalling()
    {
        $this->CI->load->model('themes/models/Themes_model');
        $this->CI->Themes_model->clearAllSet();
	}

	/*
	* Dynamic blocks methods
	*
	*/
	public function install_dynamic_blocks() {
		$this->CI->load->model('Dynamic_blocks_model');
        $this->CI->Dynamic_blocks_model->installBatch($this->dynamic_blocks);
	}

	public function install_dynamic_blocks_lang_update($langs_ids = null) {
		$this->CI->load->model('Dynamic_blocks_model');
        return $this->CI->Dynamic_blocks_model->updateLangsByModuleBlocks($this->dynamic_blocks, $langs_ids);
	}

	public function install_dynamic_blocks_lang_export($langs_ids = null) {
		$this->CI->load->model('Dynamic_blocks_model');
        return array(
            'dynamic_blocks' => $this->CI->Dynamic_blocks_model->export_langs($this->dynamic_blocks, $langs_ids),
        );
	}

	public function deinstall_dynamic_blocks(){
		$this->CI->load->model('Dynamic_blocks_model');
		foreach($this->dynamic_blocks as $block) {
			$this->CI->Dynamic_blocks_model->delete_block_by_gid($block['gid']);
		}
	}
	
}