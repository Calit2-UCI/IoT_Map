<?php

if ( ! function_exists('send_payment'))
{
	function send_payment($payment_type_gid, $id_user, $amount, $currency_gid, $system_gid, $payment_data=array(), $check_html_action=false){
		$return = array("errors" => array(), "info" => array());

		$CI = & get_instance();
		$CI->load->model('Payments_model');

		$for_validate = array(
			"payment_type_gid" => $payment_type_gid,
			"id_user" => $id_user,
			"amount" => $amount,
			"currency_gid" => $currency_gid,
			"system_gid" => $system_gid,
			"payment_data" => $payment_data
		);
		$validate_data = $CI->Payments_model->validate_payment(null, $for_validate);
		if(!empty($validate_data["errors"])){
			$return["errors"] = $validate_data["errors"];
			return $return;
		}

		$CI->load->model('payments/models/Payment_systems_model');

		if($check_html_action == "form"){
			$post_data = array(
				"payment_type_gid" => $validate_data['data']['payment_type_gid'],
				"id_user" => $validate_data['data']['id_user'],
				"amount" => $validate_data['data']['amount'],
				"currency_gid" => $validate_data['data']['currency_gid'],
				"system_gid" => $validate_data['data']['system_gid'],
				"payment_data" => $payment_data,
			);

			if($CI->Payment_systems_model->action_html($system_gid)){
				post_location_request(site_url()."payments/form", $post_data);
				return;
			}
			if($CI->Payment_systems_model->action_js($system_gid)){
				$payment_id = $CI->Payments_model->save_payment(null, $validate_data['data']);
				redirect(site_url().'payments/js/'.$payment_id);
				return;
			}
		}

		if($check_html_action == "validate" && $CI->Payment_systems_model->action_html($system_gid)){
			$validate = $CI->Payment_systems_model->action_validate($system_gid, $payment_data);
			if(!empty($validate["errors"])){
				$return = $validate;
				return $return;
			}else{
				$payment_data = $validate["data"];
			}
		}

		$for_validate = array(
			"payment_type_gid" => $payment_type_gid,
			"id_user" => $id_user,
			"amount" => $amount,
			"currency_gid" => $currency_gid,
			"system_gid" => $system_gid,
			"payment_data" => $payment_data
		);
		$validate_data = $CI->Payments_model->validate_payment(null, $for_validate);
		if(!empty($validate_data["errors"])){
			$return["errors"] = $validate_data["errors"];
		}else{
			$payment = $validate_data["data"];
			$id_payment = $CI->Payments_model->save_payment(null, $payment);
			$payment = $CI->Payments_model->get_payment_by_id($id_payment);
			$payment["id_payment"] = $payment["id"];
			$return = $CI->Payment_systems_model->action_request($payment["system_gid"], $payment);
		}

		return $return;
	}
}

if ( ! function_exists('receive_payment'))
{
	function receive_payment($system_gid, $request_data){
		$return = array("errors" => array(), "info" => array());

		$CI = & get_instance();
		$CI->load->model('payments/models/Payment_systems_model');

		$payment_data = $CI->Payment_systems_model->action_responce($system_gid, $request_data);

		$data = $payment_data["data"];
		$CI->load->model('Payments_model');
		$CI->Payments_model->change_payment_status($data["id_payment"], $data["status"]);

		return $payment_data;
	}
}

if(! function_exists('post_location_request')){
	function post_location_request($url, $data){
/*		$data_str = urlencode(http_build_query($data));
		header("Host: $host\r\n");
		header("POST $path HTTP/1.1\r\n");
		header("Content-type: application/x-www-form-urlencoded\r\n");
		header("Content-length: " . strlen($data_str) . "\r\n");
		header("Connection: close\r\n\r\n");
		header($data_str);
		exit();*/
		$CI = & get_instance();
		$params =  explode("&", urldecode(http_build_query($data)));
		$retHTML = '';
		if (!$CI->is_pjax){
			$retHTML .= "<html>\n<body onLoad=\"document.send_form.submit();\">\n";
		}
		$retHTML .= '<form method="post" name="send_form" id="send_form"  action="'.$url.'">';
		foreach ($params as $string) {
			list($key, $value) = explode("=", $string);
			$retHTML .= "<input type=\"hidden\" name=\"".$key."\" value=\"".addslashes($value)."\">\n";
		}
		if ($CI->is_pjax){
			$retHTML .= '</form><script>document.getElementById("send_form").submit();</script>';
		}else{
			$retHTML .= "</form>\n</body>\n</html>";
		}
		print $retHTML;
		exit();

	}

}

if (!function_exists('admin_home_payments_block')) {

	function admin_home_payments_block() {
		$CI = & get_instance();

		$auth_type = $CI->session->userdata("auth_type");
		if($auth_type != "admin") return '';

		$user_type = $CI->session->userdata("user_type");

		$show = true;
		if($user_type == 'moderator'){
			$show = false;
			$CI->load->model('Moderators_model');
			$methods = $CI->Moderators_model->get_module_methods('payments');
			if(is_array($methods) && !in_array('index', $methods)){
				$show = true;
			}else{
				$permission_data = $CI->session->userdata("permission_data");
				if(isset($permission_data['payments']['index']) && $permission_data['payments']['index'] == 1 ){
					$show = true;
				}
			}
		}

		if(!$show){
			return '';
		}

		$CI->load->model('Payments_model');
		$stat_payments = array(
			"all" => $CI->Payments_model->get_payment_count(),
			"wait" => $CI->Payments_model->get_payment_count(array("where"=>array('status'=>0))),
			"approve" => $CI->Payments_model->get_payment_count(array("where"=>array('status'=>1))),
			"decline" => $CI->Payments_model->get_payment_count(array("where"=>array('status'=>-1))),
		);

		$CI->load->model("payments/models/Payment_currency_model");
		$CI->template_lite->assign('currency', $CI->Payment_currency_model->default_currency);

		$CI->template_lite->assign("stat_payments", $stat_payments);
		return $CI->template_lite->fetch('helper_admin_home_block', 'admin', 'payments');
	}

}

if (!function_exists('currency_format')) {

	/**
	 * Returns formatted currency string
	 *
	 * @param int $params['cur_id'] currency id
	 * @param string $params['cur_gid'] currency gid
	 * @param int $params['value'] amount
	 * @param string $params['template'] [abbr][value|dec_part:2|dec_sep:.|gr_sep: ])
	 * @param bool $params['no_tags']
	 * @return string
	 */
	function currency_format($params = array()) {
		//{block name=currency_format module=payments value=30 cur_gid=RUR}
		$CI = & get_instance();
		$CI->load->model('payments/models/Payment_currency_model');
		$pattern_value = '/\[value\|([^]]*)\]/';

		$default_cur = $CI->Payment_currency_model->default_currency;

		// Get specified or default currency
		if(!empty($params['cur_gid'])){
			if($params['cur_gid'] != $default_cur['gid']){
				$cur = $CI->Payment_currency_model->get_currency_by_gid($params['cur_gid']);
				if($cur['per_base'] && (float)$default_cur['per_base'] && empty($params['use_gid'])){
					if(!empty($params['value'])) $params['value'] *= $cur['per_base']/$default_cur['per_base'];
				}else{
					$default_cur = $cur;
				}
			}
		}elseif(!empty($params['cur_id'])){
			if($params['cur_id'] != $default_cur['id']){
				$cur = $CI->Payment_currency_model->get_currency_by_id($params['cur_id']);
				if($cur['per_base'] && $default_cur['per_base']){
					if(!empty($params['value'])) $params['value'] *= $cur['per_base']/$default_cur['per_base'];
				}else{
					$default_cur = $cur;
				}
			}
		}

		if(!empty($params['template'])) {
			$template = $params['template'];
		}else{
			$template = $default_cur['template'];
		}

		if(isset($params['value'])) {
			$matches = array();
			// Parse the number format
			preg_match($pattern_value, $template, $matches);
			$value_params = explode('|', $matches[1]);
			foreach($value_params as $param) {
				$param_arr = explode(':', $param);
				$number_arr[$param_arr[0]] = $param_arr[1];
			}
			// Format number
			if('-' == $number_arr['dec_part'] || '–' == $number_arr['dec_part']) {
				$value = number_format($params['value'], 0, $number_arr['dec_sep'], $number_arr['gr_sep']);
				$value .= $number_arr['dec_sep'] . '–';
			} else {
				$value = number_format($params['value'], (int)$number_arr['dec_part'], $number_arr['dec_sep'], $number_arr['gr_sep']);
			}
		} else {
            $value = '';
        }

		if(!empty($params['disable_abbr'])){
			$default_cur['abbr'] = '';
			$default_cur['gid'] = '';
		}

		$str = preg_replace(array ($pattern_value, '/(\[abbr\])/', '/(\[gid\])/', '/\s/'),
							array ($value, $default_cur['abbr'], $default_cur['gid'], ' '),
							$template);

		if(empty($params['no_tags']) || false == $params['no_tags']) {
			return '<span dir="ltr">' . $str . '</span>';
		} else {
			return $str;
		}
	}
}


if (!function_exists('user_payments_history')) {
	function user_payments_history($params = array()) {
		$CI = & get_instance();
		$CI->load->model('payments/models/Payment_currency_model');
		if($CI->session->userdata('auth_type') != 'user'){
			return false;
		}
		$page = !empty($params['page']) ? $params['page'] : 1;
		$base_url = !empty($params['base_url']) ? $params['base_url'] : '';
		$user_id = $CI->session->userdata('user_id');
		$CI->load->model('Payments_model');
		$params['where']['id_user'] = $user_id;
		$payments_count = $CI->Payments_model->get_payment_count($params);

		$items_on_page = $CI->pg_module->get_module_config('payments', 'items_per_page');
		$CI->load->helper('sort_order');
		$page = get_exists_page_number($page, $payments_count, $items_on_page);

		$payments = $CI->Payments_model->get_payment_list($page, $items_on_page, array('date_add' => 'DESC'), $params);
		$CI->template_lite->assign('payments_helper_payments', $payments);

		$CI->load->helper('navigation');
		$page_data = get_user_pages_data($base_url, $payments_count, $items_on_page, $page, 'briefPage');
		$page_data['date_format'] = $CI->pg_date->get_format('date_time_literal', 'st');
		$CI->template_lite->assign('payments_helper_page_data', $page_data);

		return $CI->template_lite->fetch('helper_statistic', 'user', 'payments');
	}
}


if (!function_exists('currency')) {

	/**
	 * Returns formatted currency string
	 *
	 * @param int $params['cur_id'] currency id
	 * @param string $params['cur_gid'] currency gid
	 * @param int $params['value'] amount
	 * @param string $params['template'] [abbr][value|dec_part:2|dec_sep:.|gr_sep: ])
	 * @return string
	 */
	function currency($params = array()) {
		$CI = & get_instance();
		$CI->load->model('payments/models/Payment_currency_model');
		$pattern_value = '/\[value\|([^]]*)\]/';

		$default_cur = $CI->Payment_currency_model->default_currency;

		// Get specified or default currency
		if(!empty($params['cur_gid'])){
			if($params['cur_gid'] != $default_cur['gid']){
				$cur = $CI->Payment_currency_model->get_currency_by_gid($params['cur_gid']);
				if($cur['per_base'] && (float)$default_cur['per_base'] && empty($params['use_gid'])){
					if(!empty($params['value'])) $params['value'] *= $cur['per_base']/$default_cur['per_base'];
				}else{
					$default_cur = $cur;
				}
			}
		}elseif(!empty($params['cur_id'])){
			if($params['cur_id'] != $default_cur['id']){
				$cur = $CI->Payment_currency_model->get_currency_by_id($params['cur_id']);
				if($cur['per_base'] && $default_cur['per_base']){
					if(!empty($params['value'])) $params['value'] *= $cur['per_base']/$default_cur['per_base'];
				}else{
					$default_cur = $cur;
				}
			}
		}

		if(!empty($params['template'])) {
			$template = $params['template'];
		}else{
			$template = $default_cur['template'];
		}

		if(!empty($params['disable_abbr'])){
			$default_cur['abbr'] = '';
			$default_cur['gid'] = '';
		}

		$str = preg_replace(array ($pattern_value, '/(\[abbr\])/', '/(\[gid\])/', '/\s/'),
							array ($params['value'], $default_cur['abbr'], $default_cur['gid'], ' '),
							$template
		);

		return '<span dir="ltr">' . $str . '</span>';
	}
}

if (!function_exists('currency_format_regexp')) {

	/**
	 * Returns formatted currency regexp string
	 *
	 * @return string
	 */
	function currency_format_regexp($params = array()) {
		$CI = & get_instance();
		$CI->load->model('payments/models/Payment_currency_model');
		$pattern_value = '/\[value\|([^]]*)\]/';
		$value = '';

		$default_cur = $CI->Payment_currency_model->default_currency;

		if(!empty($params['template'])) {
			$template = $params['template'];
		}else{
			$template = $default_cur['template'];
		}

		$matches = array();
		// Parse the number format
		preg_match($pattern_value, $template, $matches);
		$value_params = explode('|', $matches[1]);
		foreach($value_params as $param) {
			$param_arr = explode(':', $param);
			$number_arr[$param_arr[0]] = $param_arr[1];
		}
		$CI->template_lite->assign('pattern_value', $pattern_value);

		// Format number
		if('-' == $number_arr['dec_part'] || '–' == $number_arr['dec_part']){
			$value = 'number_format(value, 0, \''.$number_arr['dec_sep'].'\', \''.$number_arr['gr_sep'].'\') + \''.$number_arr['dec_sep'].'–\'';
		}else{
			$value = 'number_format(value, '.((int)$number_arr['dec_part']).', \''.$number_arr['dec_sep'].'\', \''.$number_arr['gr_sep'].'\')';
		}
		$CI->template_lite->assign('value', $value);

		$template = preg_replace(array('/(\[abbr\])/', '/(\[gid\])/', '/\s/'),
							array($default_cur['abbr'], $default_cur['gid'], ' '),
							$template);
		$CI->template_lite->assign('template', $template);

		return $CI->template_lite->fetch('helper_currency_regexp', 'user', 'payments');
	}
}

if (!function_exists('site_currency_select')){
	/**
	 * Returns currency selector
	 *
	 * @return string
	 */
	function site_currency_select(){
		$CI = & get_instance();
		$CI->load->model('payments/models/Payment_currency_model');
		$currencies = $CI->Payment_currency_model->get_currency_list();
		$CI->template_lite->assign("currencies", $currencies);
		return $CI->template_lite->fetch("helper_currency_select", "user", "payments");
	}
}
