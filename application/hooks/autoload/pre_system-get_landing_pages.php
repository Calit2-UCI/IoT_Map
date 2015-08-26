<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('get_landing_pages')) {
	function get_landing_pages() {
		if (!INSTALL_MODULE_DONE){
			return;
		}
        
		$landing_pages_links_file = SITE_PHYSICAL_PATH . APPLICATION_FOLDER . 'config/landings_module_routes.php';
		
        include_once($landing_pages_links_file);
        
        $config =& load_class('Config');
        
        $URI = & load_class('URI');
        $URI->_fetch_uri_string();
        
        $uri_string = $URI->uri_string();
        
        $uri_string = rtrim($uri_string, '/') . '/';
        
        $langs = $config->item('langs_route');

        foreach ($langs as $lang) {
            $uri_string = preg_replace('#^/'.preg_quote($lang, '#').'/#i', '/', $uri_string);
        }
      
        if ($uri_string == '/admin' && strpos($uri_string, '/admin/') == 0) {
            return;
        }
        
        $uri_string = rtrim($uri_string, '/');
        
        $uri_string = preg_replace('#/index$#i', '', $uri_string);
        
        if (empty($uri_string)) {
            $uri_string = "/start";
        }

        if (isset($landing_data[$uri_string])) {
            $value = $landing_data[$uri_string];
            if (substr($value, -4, 4) == '.php') {
                include LIBPATH.'Landing.php';
                $CI = new Pg\Libraries\Landing();
                include SITE_PHYSICAL_PATH . UPLOAD_DIR . 'landings/' . $value;
            } else {
                echo file_get_contents($value.'.html');
            }
            exit;
        }
	}
}
