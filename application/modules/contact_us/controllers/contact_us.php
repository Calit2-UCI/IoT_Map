<?php
/**
* Contact us user side controller
* 
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

Class Contact_us extends Controller
{
	/**
	 * link to CodeIgniter object
	 * @var object
	 */

	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::Controller();
		$this->load->model("Contact_us_model");
	}
	
	public function index(){
		$reasons = $this->Contact_us_model->get_reason_list();
		$this->template_lite->assign('reasons', $reasons);

		if($this->input->post('btn_save')){
			$post_data = array(
				"id_reason" => $this->input->post('id_reason', true),
				"user_name" => $this->input->post('user_name', true),
				"user_email" => $this->input->post('user_email', true),
				"subject" => $this->input->post('subject', true),
				"message" => $this->input->post('message', true),
				"captcha_code" => $this->input->post('captcha_code', true),
			);
			$validate_data = $this->Contact_us_model->validate_contact_form($post_data);

			if(!empty($validate_data["errors"])){
				$this->system_messages->add_message('error', $validate_data["errors"]);
				$data = $validate_data["data"];
			}else{
				$return = $this->Contact_us_model->send_contact_form($validate_data["data"]);
				
				if(!empty($return["errors"])){
					$this->system_messages->add_message('error', $return["errors"]);
				}else{
					$this->system_messages->add_message('success', l('success_send_form', 'contact_us'));
				}
				redirect(site_url()."contact_us");
			}
		}

		$this->load->plugin('captcha');
		$vals = array(
			'img_path' => TEMPPATH.'/captcha/',
			'img_url' => SITE_VIRTUAL_PATH. 'temp/captcha/',
			'font_path' => BASEPATH.'fonts/arial.ttf',
			'img_width' => '200',
			'img_height' => '30',
			'expiration' => 7200
		);
		
		$cap = create_captcha($vals);
		$data["captcha"] = $cap['image'];
		$_SESSION["captcha_word"] = $cap['word'];
		$this->template_lite->assign('data', $data);

		$this->load->model('Menu_model');
		$this->Menu_model->breadcrumbs_set_active(l('header_contact_us_form', 'contact_us'));

		$this->template_lite->view('form');
	}

}