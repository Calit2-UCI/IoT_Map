<?php
/**
* Social networking twitter service model
* 
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Twitter_service_model extends Model {

	public $CI;

	const SCHEME = 'https';
	const HOST = 'api.twitter.com';
	const AUTHORIZE_URI = '/oauth/authorize';
	const REQUEST_URI = '/oauth/request_token';
	const ACCESS_URI = '/oauth/access_token';

	private $_consumer = false;

	function __construct() {
		parent::__construct();
		$this->CI = & get_instance();
	}

	public function oauth($params) {
		if (!array_key_exists('method', $params))
			$params['method'] = 'GET';
		if (!array_key_exists('algorithm', $params))
			$params['algorithm'] = OAUTH_ALGORITHMS::HMAC_SHA1;
		$this->_consumer = $params;
	}

	public function get_request_token($callback) {
		$baseurl = self::SCHEME . '://' . self::HOST . self::REQUEST_URI;
		$auth = build_auth_array($baseurl, $this->_consumer['key'], $this->_consumer['secret'], array('oauth_callback' => urlencode($callback)), $this->_consumer['method'], $this->_consumer['algorithm']);
		$str = "";
		foreach ($auth as $key => $value)
			$str .= ",{$key}=\"{$value}\"";
		$str = 'Authorization: OAuth ' . substr($str, 1);
		$response = $this->_connect($baseurl, $str);
		parse_str($response, $resarray);
		$redirect = self::SCHEME . '://' . self::HOST . self::AUTHORIZE_URI . "?oauth_token={$resarray['oauth_token']}";
		if ($this->_consumer['algorithm'] == OAUTH_ALGORITHMS::RSA_SHA1)
			return $redirect;
		else
			return array('token_secret' => $resarray['oauth_token_secret'], 'redirect' => $redirect);
	}

	public function get_access_token($token = false, $secret = false, $verifier = false) {
		if ($token === false && isset($_GET['oauth_token']))
			$token = $_GET['oauth_token'];
		if ($verifier === false && isset($_GET['oauth_verifier']))
			$verifier = $_GET['oauth_verifier'];
		if ($token === false && $verifier === false) {
			$uri = $_SERVER['REQUEST_URI'];
			$uriparts = explode('?', $uri);
			$authfields = array();
			parse_str($uriparts[1], $authfields);
			$token = $authfields['oauth_token'];
			$verifier = $authfields['oauth_verifier'];
		}
		$tokenddata = array('oauth_token' => urlencode($token), 'oauth_verifier' => urlencode($verifier));
		if ($secret !== false)
			$tokenddata['oauth_token_secret'] = urlencode($secret);
		$baseurl = self::SCHEME . '://' . self::HOST . self::ACCESS_URI;
		$auth = get_auth_header($baseurl, $this->_consumer['key'], $this->_consumer['secret'], $tokenddata, $this->_consumer['method'], $this->_consumer['algorithm']);
		$response = $this->_connect($baseurl, $auth);
		parse_str($response, $oauth);
		return $oauth;
	}

	private function _connect($url, $auth) {
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_SSLVERSION, 3);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		//​curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
		curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, 'SSLv3');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array($auth));

		$response = curl_exec($ch);
		curl_close($ch);
		return $response;
	}

}
