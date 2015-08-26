<?php 

/**
* Currencies model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

if (!defined("BASEPATH")) exit("No direct script access allowed");

class Yahoo_currency_rates_model extends Model{
	
	/**
	 * Link to CodeIgnitor object
	 * @var object
	 */
	private $CI;
	
	/**
	 * Service base url
	 * @var string
	 */
	private $yql_base_url = "http://query.yahooapis.com/v1/public/yql?q=%s&format=json&env=http://datatables.org/alltables.env";  
	
	/**
	 * YQL base query
	 * @var string
	 */
	private $yql_base_query = "select * from yahoo.finance.xchange where pair IN('%s')";  
	
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
		
		$pairs = array();
		foreach($currencies as $need_currency){
			if($need_currency["gid"] != $base_currency){
				$pairs[] = $need_currency["gid"].$base_currency;
			}
		}
		
		if(empty($pairs)) return array();
		
		$yql_query_url = sprintf($this->yql_base_url, urlencode(sprintf($this->yql_base_query, implode("','", $pairs))));
		if($use_curl){
			$session = curl_init($yql_query_url);  
			curl_setopt($session, CURLOPT_RETURNTRANSFER,true);      
			$json = curl_exec($session);      
		}else{
			$json = file_get_contents($yql_query_url);
		}
		$results = json_decode($json);  
		
		if($results->error) throw new Exception($results->error);
		
		$return = array();
		foreach($results->query->results->rate as $result){
			$currency_gid = preg_replace("/".preg_quote($base_currency, "/")."$/i", "", $result->id);
			$return[$currency_gid] = $result->Rate;
		}	
	
		return $return;
	}
}
