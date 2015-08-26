<?php
/**
* Contact us api controller
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Alexander Batukhtin <abatukhtin@pilotgroup.net>
**/

Class Api_Contact_us extends Controller
{
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::Controller();
		$this->load->model('Contact_us_model');
	}

	/**
	 * Get reasons list
	 *
	 */
	public function get_reasons() {
		$reasons = $this->Contact_us_model->get_reason_list();
		$this->set_api_content('data', array('reasons' => $reasons));
	}


	/**
	 * Send contact us form
	 *
	 * @param int $id_reason
	 * @param string $user_name
	 * @param string $user_email
	 * @param string $subject
	 * @param string $message
	 *
	 * @todo Add antispam
	 */
	public function send_form() {
		$post_data = array(
			'id_reason' => $this->input->post('id_reason', true),
			'user_name' => $this->input->post('user_name', true),
			'user_email' => $this->input->post('user_email', true),
			'subject' => $this->input->post('subject', true),
			'message' => $this->input->post('message', true),
		);
		$validate_data = $this->Contact_us_model->validate_contact_form($post_data);

		if(!empty($validate_data['errors'])) {
			$this->set_api_content('errors', $validate_data['errors']);
			$this->set_api_content('data', array('post_data' => $post_data));
		} else {
			$return = $this->Contact_us_model->send_contact_form($validate_data['data']);
			if(!empty($return['errors'])) {
				$this->set_api_content('errors', $return['errors']);
			} else {
				$this->set_api_content('messages', l('success_send_form', 'contact_us'));
			}
		}
	}

}