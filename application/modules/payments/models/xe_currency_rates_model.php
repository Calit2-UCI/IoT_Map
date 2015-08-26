<?php 

/**
* XE currency rates model (from xe.com)
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

if (!defined("BASEPATH")) exit("No direct script access allowed");

class XE_currency_rates_model extends Model{
	
	/**
	 * Link to CodeIgnitor object
	 * @var object
	 */
	private $CI;
	
	/**
	 * Xe url template
	 * @var string
	 */
	private $xe_url = "http://www.xe.com/currencyconverter/convert/?Amount=1&From=%s&To=%s";
	
	/**
	 * Class constructor
	 */
	public function __construct(){
		parent::Model();
		$this->CI = & get_instance();
	}
	
	/**
	 * Update exists currency rates
	 */
	public function update_rates($base_currency, $currencies, $use_curl=false){
		if(empty($currencies)) return array();
		
		$this->CI->load->library("simple_html_dom");
		
		$return = array();
		foreach($currencies as $need_currency){
			if($need_currency["gid"] == $base_currency) continue;
			
			$url_rate = sprintf($this->xe_url, $need_currency["gid"], $base_currency);
			
			if($use_curl){
				$curl = curl_init(); 
				curl_setopt($curl, CURLOPT_URL, $url_rate);  
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  
				curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
				$content = curl_exec($curl);  
				curl_close($curl);
			}else{
				$content = file_get_contents($url_rate);
			}
			$html= str_get_html($content);
			$cur_content = array();
			//Cnvrsn
			foreach($html->find(".uccMin table tr.uccRes") as $key_c => $tr){
				foreach($tr->find("td") as $key2 => $td){
					if($key2 != 2) continue;
					$tmp = explode("&nbsp;", $td->plaintext);
					$tmp[0] = floatval($tmp[0]);
					if($tmp[0] == 0) continue;
					$return[$need_currency["gid"]] = $tmp[0];
				}
			}
		}
			
		return $return;
	}
}
