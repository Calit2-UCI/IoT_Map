<?php
/**
* Geomaps user side controller
* 
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

class Geomap extends Controller {

	/**
	 * Constructor
	 */
	function __construct(){
		parent::Controller();
		$this->load->model('Geomap_model');
	}
	
	public function index(){
		$this->example();
	}

	public function ajax_save_settings($user_id, $object_id, $gid=""){
		$errors = array();

		if(empty($user_id)){
			$errors[] = l('error_cant_save_settings', 'geomap');
		}else{
			$post_data = array(
				"zoom" => intval($this->input->post('zoom')),
				"view_type" => intval($this->input->post('view_type')),
				"lat" => $this->input->post('lat', true),
				"lon" => $this->input->post('lon', true),
			);

			$validate_data = $this->Geomap_settings_model->validate_settings($post_data);
			if(!empty($validate_data["errors"])){
				$errors = $validate_data["errors"];
			}else{
				$this->Geomap_settings_model->save_settings($gid, $user_id, $object_id, $gid, $validate_data["data"]);
			}
		}

		if(!empty($errors)){
			$return["errors"] = implode("<br>", $errors);
			$return["result"] = "error";
		}else{
			$return["result"] = "ok";
		}
		echo json_encode($return);
		return;
	}

	public function example(){
		
		$markers[] = array( 'gid' => 'england', 'lat' => '51.50874245880332', 'lon' => '0', 'html' => l('map_example_1_text', 'geomap'), "dragging" => true );
		$markers[] = array( 'gid' => 'france', 'lat' => '48.86471476180277', 'lon' => '2.3291015625', 'html' => l('map_example_2_text', 'geomap'), 'dragging' => true );
		$markers[] = array( 'gid' => 'italy', 'lat' => '43.731414', 'lon' => '10.404053', 'html' => l('map_example_3_text', 'geomap'), 'dragging' => true );
		$markers[] = array( 'gid' => 'germany', 'lat' => '47.562859', 'lon' => '10.750122', 'html' => l('map_example_4_text', 'geomap'), "dragging" => true );

		$current_language = $this->pg_language->get_lang_by_id($this->pg_language->current_lang_id);
		$view_settings = array(
			"gid" => "example",
			"width" => "660",
			"height" => "250", 
			"class" => "", 
			"zoom_listener" => "test1", 
			"type_listener" => "test2",
			"drag_listener" => "test3",
			"lang" => $current_language["code"],

		);
		$this->template_lite->assign('user_id', $this->session->userdata('user_id'));
		$this->template_lite->assign('map_settings', $view_settings);
		$this->template_lite->assign('markers', $markers);
		$this->template_lite->view('example');
	}
}
