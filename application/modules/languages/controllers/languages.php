<?php
/**
* Languages user side controller
* 
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/


Class Languages extends Controller
{
	/**
	 * link to CodeIgniter object
	 * @var object
	 */

	/**
	 * Constructor
	 */
	function Languages()
	{
		parent::Controller();
	}

	function change_lang($lang_id){
		$lang_id = intval($lang_id);
		$this->session->set_userdata("lang_id", $lang_id);
		$old_code = $this->pg_language->languages[$this->pg_language->current_lang_id]["code"];
		$this->pg_language->current_lang_id = $lang_id;
		$code = $this->pg_language->languages[$lang_id]["code"];
		$_SERVER["HTTP_REFERER"] = str_replace("/".$old_code."/", "/".$code."/", $_SERVER["HTTP_REFERER"]);
		$site_url = str_replace("/".$code."/", "", site_url());


		if(strpos($_SERVER["HTTP_REFERER"], $site_url) !== false)
			redirect($_SERVER["HTTP_REFERER"]);
		else
			redirect();
	}

	function ajax_pages_save($module_gid, $gid, $lang_id=''){
		if(ADD_LANG_MODE){
//			$value = html_entity_decode($this->input->post("text", true));
			$value = $this->input->post("text", true);

			if(!empty($gid)){
				$this->pg_language->pages->set_string($module_gid, $gid, $value, $lang_id);
			}
			echo $value;
		}
		echo "";
	}

}