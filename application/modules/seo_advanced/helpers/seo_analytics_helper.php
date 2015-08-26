<?php

/**
 * Seo advanced module
 *
 * @package 	PG_Core
 * @copyright 	Copyright (c) 2000-2014 PG Core
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */

/**
 * Seo analytics
 * 
 * @package 	PG_Core
 * @subpackage 	Seo_advanced
 * @category	helpers
 * @copyright 	Copyright (c) 2000-2014 PG Core
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
class Seo_analytics_helper {
	/**
	 * alexa_rank
	 *
	 * @access public
	 * @return int
	 */
	public static function alexa_rank ($url)
	{
		$url = 'http://data.alexa.com/data?cli=10&dat=snbamz&url=' . urlencode($url);
		$v = Seo_analytics_helper::file_get_contents_curl($url);
		preg_match('/<popularity url="(.*?)" TEXT="([0-9]+)"/si', $v, $r);
		return isset($r[2]) ? $r[2] : '0';
	}

	/**
	 * backlinks for most popular search engines
	 *
	 * @param mixed $engine
	 * @access public
	 * @return int
	 */
	public static function backlinks ($uri, $engine) {
		switch ($engine) {
		//google
		case 'google':
			$url = 'http://www.google.com/search?hl=en&lr=&ie=UTF-8&q=link%3A'.urlencode($uri).'&filter=0';
			$v = Seo_analytics_helper::file_get_contents_curl($url);
			preg_match('/<div class="sd" id="resultStats">about (.*?) results?<\/div>/sim',$v,$r);
			return isset($r[1]) ? $r[1] : '0';
		break;
		//yahoo
		case 'yahoo':
		  	$url = 'http://search.yahoo.com/search?p=%22http%3A%2F%2F'.urlencode($uri).'%22+%22http%3A%2F%2Fwww.'.urlencode($uri).'%22+-site%3A'.urlencode($uri).'+-site%3Awww.'.urlencode($uri);
			$v = Seo_analytics_helper::file_get_contents_curl($url);
			preg_match('/<div id="pg"(.*?)<span>(.*?) results?<\/span><\/div>/si',$v,$r);
			return isset($r[2]) ? $r[2] : '0';
		break;
		//msn
		case 'msn':
			$url = 'http://search.msn.com/results.aspx?q=link%3A'.urlencode($uri);
			$v = Seo_analytics_helper::file_get_contents_curl($url);
			$preg_match = preg_match('/of about ([0-9,]+) results/si', $v, $r);
			if (empty($preg_match)) $preg_match = preg_match('/of ([0-9,]+) results/si', $v, $r);
			return ($r[1]) ? str_replace(',', '', $r[1]) : '0';
		break;
		//altavista
		case 'altavista':
			$url = 'http://www.altavista.com/web/results?q=link%3A'.urlencode($uri);
			$v = Seo_analytics_helper::file_get_contents_curl($url);
			preg_match('/found ([0-9,]+) results/si', $v, $r);
			return ($r[1]) ? str_replace(',', '', $r[1]) : '0';
		break;
		//alltheweb
		case 'alltheweb':
			$url = 'http://www.alltheweb.com/search?q=link%3A'.urlencode($uri);
			$v = Seo_analytics_helper::file_get_contents_curl($url);
			preg_match('/<span class="ofSoMany">([0-9,]+)</span>/si', $v, $r);
			return ($r[1]) ? str_replace(',', '', $r[1]) : '0';
		break;
		case 'alexa':
			$url = 'http://data.alexa.com/data?cli=10&dat=snbamz&url='.urlencode($uri);
			$v = Seo_analytics_helper::file_get_contents_curl($url);
			preg_match('/<linksin NUM="([0-9]+)"/si', $v, $r);
			return isset($r[1]) ? $r[1] : '0';
		break;
		}
	}

	/**
	 * get_technorati_rank
	 *
	 * @param mixed $url
	 * @param mixed $apikey
	 * @access public
	 * @return void
	 */
	function get_technorati_rank($uri)
	{
		$url = 'http://technorati.com/blogs/'.urlencode($uri);
		$v = Seo_analytics_helper::file_get_contents_curl($url);
		preg_match('/Rank: (.*) /isU', $v, $r);

		return isset($r[1]) ? $r[1] : 0;
	}

	/**
	 * get_technorati_authority
	 *
	 * @param mixed $uri
	 * @access public
	 * @return int
	 */
	function get_technorati_authority($uri)
	{
		$url = 'http://technorati.com/blogs/'.urlencode($uri);
		$v = Seo_analytics_helper::file_get_contents_curl($url);
		preg_match('/Authority: (.*)<\/strong>/isU', $v, $r);

		return isset($r[1]) ? trim($r[1]) : 0;
	}

	/**
	 * dmoz_listed
	 * function to check whether an url is listed in DMOZ(ODP), return 1 or 0
	 *
	 * @param mixed $url
	 * @access public
	 * @return bool
	 */
	function dmoz_listed($url)
	{
		$dmozurl = 'http://search.dmoz.org/cgi-bin/search?search=u:'.urlencode($url);
		$data = Seo_analytics_helper::file_get_contents_curl($dmozurl);
		$pos=strpos($data, 'Open Directory Categories');
		return ($pos == 0) ? false : true;
	}

	/**
	 * google_listed
	 * check site in google directory
	 *
	 * @param mixed $uri
	 * @access public
	 * @return bool
	 */
	function google_listed($uri)
	{
		$listed = false;
		$url = 'http://www.google.com/search?q='.urlencode($uri).'&hl=en&cat=gwd%2FTop';
		$data = Seo_analytics_helper::file_get_contents_curl($url);
		preg_match('/<a href="(.{1,75})" class=l/', $data, $result);
		if (isset($result[1]) and FALSE !== strpos($result[1], $uri))
		{
			$listed = true;
		}

		return $listed;
	}

	/**
	 * google_indexed
	 *
	 * @param mixed $uri
	 * @access public
	 * @return str
	 */
	function google_indexed($uri)
	{
		$url = 'http://www.google.com/search?hl=en&lr=&ie=UTF-8&q=site%3A'.urlencode($uri).'&filter=0';
		$v = Seo_analytics_helper::file_get_contents_curl($url);
		if(preg_match('/id="resultStats">about (.*?) results?/si',$v,$r)){
			return intval(str_replace(array(',', ' '), '', $r[1]));
		}
		return '0';
	}

	/**
	 * yahoo_indexed
	 *
	 * @param mixed $uri
	 * @access public
	 * @return str
	 */
	function yahoo_indexed($uri)
	{
		$url = 'http://search.yahoo.com/search?p=site%3A'.urlencode($uri);
		$v = Seo_analytics_helper::file_get_contents_curl($url);
		preg_match('/<div id="pg"(.*?)<span>(.*?) results?<\/span><\/div>/si',$v,$r);
		return isset($r[2]) ? $r[2] : '0';
	}
	
	/**
	 * yandex_indexed
	 *
	 * @param mixed $uri
	 * @access public
	 * @return str
	 */
	function yandex_indexed($uri)
	{
		if(!@function_exists("curl_init"))  return '-';

		$agent = 'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.2.1) Gecko/20021204';
		$url = 'http://yandex.ru/yandsearch?text=&site='.$uri.'&ras=1&site_manually=true';
		//$url = 'http://yandex.ru/yandsearch?text=url%3A'.urlencode($uri);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
		curl_setopt($ch, CURLOPT_USERAGENT, $agent);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
		curl_setopt($ch, CURLOPT_TIMEOUT, 120);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
		$v = @curl_exec($ch);
		curl_close($ch);

		if(!$v || preg_match('/captcha/ism', $v)) return 'не определено';
		
		/*iconv('utf-8', 'window-1251', $v);	
		if(preg_match('/Нашёлся<br>(\d+)\sответ(а|ов)/ism', $v, $r)){
			return($r[1]);
		}
		if(preg_match('/Нашёлся<br>(\d+)\&nbsp\;тыс\.\sответ(а|ов)/ism', $v, $r)){
			return(1000*$r[1]);
		}
		if(preg_match('/Нашёлся<br>(\d+)\&nbsp\;млн\.\sответ(а|ов)/ism', $v, $r)){
			return(1000000*$r[1]);
		}*/
		
		iconv('utf-8', 'window-1251', $v);	
		if(preg_match('/Страницы: (\d+)\&nbsp\;млн\./ism', $v, $r)){
			return(1000000*$r[1]);
		}
		if(preg_match('/Страницы: (\d+)\&nbsp\;тыс\./ism', $v, $r)){
			return(1000*$r[1]);
		}
		if(preg_match('/Страницы: (\d+)/ism', $v, $r)){
			return($r[1]);
		}
		
		return(0);
	}
	
	/**
	 * Return yandex TIC
	 * @param mixed $uri
	 * @access public
	 * @return str
	 */
	function yandex_TIC($uri)
	{
		$url = 'http://bar-navig.yandex.ru/u?ver=2&show=32&url=http%3A%2F%2F'.$uri;
		$v = Seo_analytics_helper::file_get_contents_curl($url);
		if(preg_match('/value=\"(.\d*)\"/si', $v, $r)){
            return $r[1];
		}
		return '';
	}
	
	/**
	 * file_get_contents_curl
	 *
	 * @param mixed $url
	 * @access public
	 * @return void
	 */
	function file_get_contents_curl($url)
	{
		$CI = &get_instance();
		$CI->load->library('Snoopy');
		$CI->snoopy->fetch($url);
		return $CI->snoopy->results;
	}

	function prepare_url($url)
	{
		if (!strpos($url, '.'))
		{
			return false;
		}
		$url = strtolower(trim(trim($url), '/'));
		$url = explode('.', $url);
		// remove www from begin
		if ($url[0] === 'www')
		{
			unset($url[0]);
		}
		$url = implode('.', $url);
		// remove http and https
		$url = str_replace('http://', '', $url);
		$url = str_replace('https://', '', $url);

		return $url;
	}
}
