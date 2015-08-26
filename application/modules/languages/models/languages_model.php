<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Languages_model extends Model {
	private $CI;

	function __construct()
	{
		parent::Model();
		$this->CI = & get_instance();
	}

	/*public function lang_dedicate_module_callback_add($lang_id = false) {
		$this->update_route_langs();
	}

	public function lang_dedicate_module_callback_delete($lang_id = false) {
		$this->update_route_langs();
	}*/

	/**
	 * Update or create file config/langs_route.php
	 *
	 * @return boolean
	 */
	/*private function update_route_langs() {

		$langs = $this->CI->pg_language->get_langs();
		if (0 == count($langs)) {
			return false;
		}

		$file_content = "<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');\n\n";
		$file_content .= "\$config['langs_route'] = array(";
		foreach($langs as $lang) {
			$file_content .= "'" . $lang['code'] . "', ";
		}
		$file_content = substr_replace($file_content, '', -2);
		$file_content .= ');';

		$file = APPPATH . 'config/langs_route' . EXT;
		try {
			$handle = fopen($file, 'w');
			fwrite($handle, $file_content);
			fclose($handle);
		} catch (Exception $e) {
			log_message('error','Error while updating langs_route' . EXT .
					'(' . $e->getMessage() . ') in ' . $e->getFile());
			throw $e;
		}
		return true;
	}*/
}