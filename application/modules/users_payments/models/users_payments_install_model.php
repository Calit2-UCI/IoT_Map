<?php
/**
* Users payments install model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Alexander Batukhtin <abatukhtin@pilotgroup.net>
* @version $Revision: 1 $ $Date: 2012-09-12 15:24:47 +0300 (Ср, 12 сент 2012) $ $Author: abatukhtin $
**/

class Users_payments_install_model extends Model
{
	private $CI;
	private $payment_types = array(
		array('gid' => 'account', 'callback_module' => 'users_payments', 'callback_model' => 'Users_payments_model', 'callback_method' => 'update_user_account')
	);
	private $notifications = array(
		'notifications' => array(
			array('gid' => 'users_update_account', 'send_type' => 'simple')
		),
		'templates' => array(
			array('gid' => 'users_update_account', 'name' => 'Add funds on account', 'vars' => array('account', 'received', 'email', 'fname', 'sname', 'nickname'), 'content_type' => 'text')
		),
	);

	/**
	 * Constructor
	 *
	 * @return Install object
	 */
	function __construct()
	{
		parent::Model();
		$this->CI = & get_instance();
		$this->CI->load->model('Install_model');
	}

	public function install_payments () {
		// add account payment type
		$this->CI->load->model("Payments_model");
		foreach($this->payment_types as $payment_type) {
			$data = array(
				'gid' => $payment_type['gid'],
				'callback_module' => $payment_type['callback_module'],
				'callback_model' => $payment_type['callback_model'],
				'callback_method' => $payment_type['callback_method'],
			);
			$this->CI->Payments_model->save_payment_type(null, $data);
		}
	}

	public function install_payments_lang_update($langs_ids = null) {
		if(empty($langs_ids)) return false;
		$langs_file = $this->CI->Install_model->language_file_read('users_payments', 'payments', $langs_ids);
		if(!$langs_file) { log_message('info', 'Empty payments langs data'); return false; }
		$this->CI->load->model('Payments_model');
		$this->CI->Payments_model->update_langs($this->payment_types, $langs_file, $langs_ids);
	}

	public function install_payments_lang_export($langs_ids = null) {
		$this->CI->load->model('Payments_model');
		return $this->CI->Payments_model->export_langs($this->payment_types, $langs_ids);
	}

	public function deinstall_payments () {
		$this->CI->load->model('Payments_model');
		foreach($this->payment_types as $payment_type) {
			$this->CI->Payments_model->delete_payment_type_by_gid($payment_type['gid']);
		}
	}

	public function install_notifications() {
		// add notification
		$this->CI->load->model('Notifications_model');
		$this->CI->load->model('notifications/models/Templates_model');

		foreach($this->notifications['templates'] as $tpl){
			$template_data = array(
				'gid' => $tpl['gid'],
				'name' => $tpl['name'],
				'vars' => serialize($tpl['vars']),
				'content_type' => $tpl['content_type'],
				'date_add' =>  date('Y-m-d H:i:s'),
				'date_update' => date('Y-m-d H:i:s'),
			);
			$tpl_ids[$tpl['gid']] = $this->CI->Templates_model->save_template(null, $template_data);
		}

		foreach($this->notifications['notifications'] as $notification) {
			$notification_data = array(
				'gid' => $notification['gid'],
				'send_type' => $notification['send_type'],
				'id_template_default' => $tpl_ids[$notification['gid']],
				'date_add' => date("Y-m-d H:i:s"),
				'date_update' => date("Y-m-d H:i:s"),
			);
			$this->CI->Notifications_model->save_notification(null, $notification_data);
		}

	}

	public function install_notifications_lang_update($langs_ids = null) {
		if(empty($langs_ids)) return false;
		$this->CI->load->model('Notifications_model');

		$langs_file = $this->CI->Install_model->language_file_read('users_payments', 'notifications', $langs_ids);

		if(!$langs_file) { log_message('info', 'Empty notifications langs data'); return false; }

		$this->CI->Notifications_model->update_langs($this->notifications, $langs_file, $langs_ids);
		return true;
	}

	public function install_notifications_lang_export($langs_ids = null) {
		$this->CI->load->model('Notifications_model');
		$langs = $this->CI->Notifications_model->export_langs($this->notifications, $langs_ids);
		return array('notifications' => $langs);
	}

	public function deinstall_notifications() {
		$this->CI->load->model('Notifications_model');
		$this->CI->load->model('notifications/models/Templates_model');
		foreach($this->notifications['templates'] as $tpl){
			$this->CI->Templates_model->delete_template_by_gid($tpl['gid']);
		}
		foreach($this->notifications['notifications'] as $ntf) {
			$this->CI->Notifications_model->delete_notification_by_gid($ntf['gid']);
		}
	}

	function _arbitrary_installing(){
	}

	function _arbitrary_deinstalling(){
	}

}