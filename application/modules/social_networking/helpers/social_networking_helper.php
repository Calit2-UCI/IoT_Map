<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class OAUTH_ALGORITHMS {

	const HMAC_SHA1 = 'HMAC-SHA1';
	const RSA_SHA1 = 'RSA-SHA1';

}

if (!function_exists('sign_hmac_sha1')) {

	function sign_hmac_sha1($method, $baseurl, $secret, array $parameters) {
		$data = $method . '&';
		$data .= urlencode($baseurl) . '&';
		$oauth = '';
		ksort($parameters);
		if (!array_key_exists('oauth_token_secret', $parameters))
			$parameters['oauth_token_secret'] = '';
		foreach ($parameters as $key => $value) {
			if (strtolower($key) != 'oauth_token_secret')
				$oauth .= "&{$key}={$value}";
		}
		$data .= urlencode(substr($oauth, 1));
		$secret .= '&' . $parameters['oauth_token_secret'];
		return base64_encode(hash_hmac('sha1', $data, $secret, true));
	}

}

if (!function_exists('sign_rsa_sha1')) {

	function sign_rsa_sha1($method, $baseurl, $certfile, array $parameters) {
		$fp = fopen($certfile, "r");
		$private = fread($fp, 8192);
		fclose($fp);
		$data = $method . '&';
		$data .= urlencode($baseurl) . '&';
		$oauth = '';
		ksort($parameters);
		foreach ($parameters as $key => $value)
			$oauth .= "&{$key}={$value}";
		$data .= urlencode(substr($oauth, 1));
		$keyid = openssl_get_privatekey($private);
		openssl_sign($data, $signature, $keyid);
		openssl_free_key($keyid);
		return base64_encode($signature);
	}

}

if (!function_exists('build_auth_string')) {

	function build_auth_string(array $authparams) {
		$header = "Authorization: OAuth ";
		$auth = '';
		foreach ($authparams AS $key => $value) {
			if ($key != 'oauth_token_secret')
				$auth .= ", {$key}=\"{$value}\"";
		}
		return $header . substr($auth, 2) . "\r\n";
	}

}

if (!function_exists('build_auth_array')) {

	function build_auth_array($baseurl, $key, $secret, $extra = array(), $method = 'GET', $algo = OAUTH_ALGORITHMS::RSA_SHA1) {
		$auth['oauth_consumer_key'] = $key;
		$auth['oauth_signature_method'] = $algo;
		$auth['oauth_timestamp'] = time();
		$auth['oauth_nonce'] = md5(uniqid(rand(), true));
		$auth['oauth_version'] = '1.0';
		$auth = array_merge($auth, $extra);
		$urlsegs = explode("?", $baseurl);
		$baseurl = $urlsegs[0];
		$signing = $auth;
		if (count($urlsegs) > 1) {
			preg_match_all("/([\w\-]+)\=([\w\d\-\%\.\$\+\*]+)\&?/", $urlsegs[1], $matches);
			$signing = $signing + array_combine($matches[1], $matches[2]);
		}
		if (strtoupper($algo) == OAUTH_ALGORITHMS::HMAC_SHA1)
			$auth['oauth_signature'] = sign_hmac_sha1($method, $baseurl, $secret, $signing);
		else if (strtoupper($algo) == OAUTH_ALGORITHMS::RSA_SHA1)
			$auth['oauth_signature'] = sign_rsa_sha1($method, $baseurl, $secret, $signing);
		$auth['oauth_signature'] = urlencode($auth['oauth_signature']);
		return $auth;
	}

}

if (!function_exists('get_auth_header')) {

	function get_auth_header($baseurl, $key, $secret, $extra = array(), $method = 'GET', $algo = OAUTH_ALGORITHMS::RSA_SHA1) {
		$auth = build_auth_array($baseurl, $key, $secret, $extra, $method, $algo);
		return build_auth_string($auth);
	}

}

if (!function_exists('show_social_networks_head')) {

	function show_social_networks_head() {
		$CI = & get_instance();
		$CI->load->model('social_networking/models/Social_networking_widgets_model');
		$header = $CI->Social_networking_widgets_model->get_header();
		return $header;
	}

}

if (!function_exists('show_social_networks_like')) {

	function show_social_networks_like() {
		$CI = & get_instance();
		$CI->load->model('social_networking/models/Social_networking_pages_model');
		$page_data = $CI->Social_networking_pages_model->get_pages_list(array('id' => 'desc'), array('where' => array('controller' => $CI->router->class, 'method' => $CI->router->method)));
		if (is_array($page_data) && count($page_data) > 0)
			foreach ($page_data as $id => $value) {
				$page_data = $value;
				break;
			}
		if ($page_data) {
			$CI->load->model('social_networking/models/Social_networking_widgets_model');
			$like = $CI->Social_networking_widgets_model->get_widgets('like', isset($page_data['data']['like']) ? $page_data['data']['like'] : array());
			$CI->template_lite->assign('like', $like);
			$CI->template_lite->view('like', 'user', 'social_networking');
		}
	}

}

if (!function_exists('show_social_networks_share')) {

	function show_social_networks_share() {
		$CI = & get_instance();
		$CI->load->model('social_networking/models/Social_networking_pages_model');
		$page_data = $CI->Social_networking_pages_model->get_pages_list(array('id' => 'desc'), array('where' => array('controller' => $CI->router->class, 'method' => $CI->router->method)));
		if (is_array($page_data) && count($page_data) > 0)
			foreach ($page_data as $id => $value) {
				$page_data = $value;
				break;
			}
		if ($page_data) {
			$CI->load->model('social_networking/models/Social_networking_widgets_model');
			$share = $CI->Social_networking_widgets_model->get_widgets('share', isset($page_data['data']['share']) ? $page_data['data']['share'] : array());
			$CI->template_lite->assign('share', $share);
			$CI->template_lite->view('share', 'user', 'social_networking');
		}
	}

}

if (!function_exists('show_social_networks_comments')) {

	function show_social_networks_comments() {
		$CI = & get_instance();
		$CI->load->model('social_networking/models/Social_networking_pages_model');
		$page_data = $CI->Social_networking_pages_model->get_pages_list(array('id' => 'desc'), array('where' => array('controller' => $CI->router->class, 'method' => $CI->router->method)));
		if (is_array($page_data) && count($page_data) > 0)
			foreach ($page_data as $id => $value) {
				$page_data = $value;
				break;
			}
		if ($page_data) {
			$CI->load->model('social_networking/models/Social_networking_widgets_model');
			$comments = $CI->Social_networking_widgets_model->get_widgets('comments', isset($page_data['data']['comments']) ? $page_data['data']['comments'] : array(), 'column');
			$CI->template_lite->assign('comments', $comments);
			$CI->template_lite->view('comments', 'user', 'social_networking');
		}
	}

}
