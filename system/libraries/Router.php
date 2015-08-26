<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Router Class
 *
 * Parses URIs and determines routing
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @author		ExpressionEngine Dev Team
 * @category	Libraries
 * @link		http://codeigniter.com/user_guide/general/routing.html
 */
class CI_Router {

	public $config;
	public $routes 		= array();
	public $error_routes	= array();
	public $class			= '';
	public $method			= 'index';
	public $lang			= '';
	public $directory		= '';
	public $uri_protocol 	= 'auto';
	public $default_controller;
	public $scaffolding_request = FALSE; // Must be set to FALSE

	public $is_admin_class	= FALSE;
	public $is_api_class	= FALSE;
	
	/**
	 * Constructor
	 *
	 * Runs the route mapping function.
	 */
	public function CI_Router()
	{
		$this->config =& load_class('Config');
		$this->uri =& load_class('URI');
		$this->_set_routing();
		log_message('debug', "Router Class Initialized");
	}

	// --------------------------------------------------------------------

	/**
	 * Set the route mapping
	 *
	 * This function determines what should be served based on the URI request,
	 * as well as any "routes" that have been set in the routing config file.
	 *
	 * @access	private
	 * @return	void
	 */
	public function _set_routing()
	{
		// Are query strings enabled in the config file?
		// If so, we're done since segment based URIs are not used with query strings.

		if ($this->config->item('enable_query_strings') === TRUE AND isset($_GET[$this->config->item('controller_trigger')]))
		{
			$this->set_class(trim($this->uri->_filter_uri($_GET[$this->config->item('controller_trigger')])));

			if (isset($_GET[$this->config->item('function_trigger')]))
			{
				$this->set_method(trim($this->uri->_filter_uri($_GET[$this->config->item('function_trigger')])));
			}

			return;
		}

		// Load the routes.php file.
//		@include(APPPATH.'config/routes'.EXT);
		global $route; // defined in Codeigniter.php
		$this->routes = ( ! isset($route) OR ! is_array($route)) ? array() : $route;
		unset($route);

		// Set the default controller so we can display it in the event
		// the URI doesn't correlated to a valid controller.
		$this->default_controller = ( ! isset($this->routes['default_controller']) OR $this->routes['default_controller'] == '') ? FALSE : strtolower($this->routes['default_controller']);

		// Fetch the complete URI string
		$this->uri->_fetch_uri_string();

		// Compile the segments into an array
		$this->uri->_explode_segments();
		// Looking for lang code
		$this->_parse_lang_route();
		
		// Is there a URI string? If not, the default controller specified in the "routes" file will be shown.
		if (0 == count($this->uri->segments))
		{
			if ($this->default_controller === FALSE)
			{
				show_error("Unable to determine what should be displayed. A default route has not been specified in the routing file.");
			}

			// Turn the default route into an array.  We explode it in the event that
			// the controller is located in a subfolder
			$segments = $this->_validate_request(explode('/', $this->default_controller));

			// Set the class and method
			$this->set_class($segments[0]);
			$this->set_method((isset($segments[1]) && !empty($segments[1])) ? $segments[1] : 'index');

			// Assign the segments to the URI class
			$this->uri->rsegments = $segments;

			// re-index the routed segments array so it starts with 1 rather than 0
			$this->uri->_reindex_segments();

			log_message('debug', "No URI present. Default controller set.");
			return;
		}
		unset($this->routes['default_controller']);

		// Do we need to remove the URL suffix?
		$this->uri->_remove_url_suffix();

		// Parse any custom routing that may exist
		$this->_parse_routes();

		// Re-index the segment array so that it starts with 1 rather than 0
		$this->uri->_reindex_segments();
	}

	// --------------------------------------------------------------------

	/**
	 * Set the Route
	 *
	 * This function takes an array of URI segments as
	 * input, and sets the current class/method
	 *
	 * @access	private
	 * @param	array
	 * @param	bool
	 * @return	void
	 */
	public function _set_request($segments = array())
	{
		$segments = $this->_validate_request($segments);

		if (count($segments) == 0)
		{
			return;
		}

		$this->set_class($segments[0]);

		if (isset($segments[1]))
		{
			// A scaffolding request. No funny business with the URL
			if (isset($this->routes['scaffolding_trigger']) && $this->routes['scaffolding_trigger'] == $segments[1] AND $segments[1] != '_ci_scaffolding')
			{
				$this->scaffolding_request = TRUE;
				unset($this->routes['scaffolding_trigger']);
			}
			else
			{
				// A standard method request
				$this->set_method($segments[1]);
			}
		}
		else
		{
			// This lets the "routed" segment array identify that the default
			// index method is being used.
			$segments[1] = 'index';
		}

		$this->_create_get($segments);

		// Update our "routed" segment array to contain the segments.
		// Note: If there is no custom routing, this array will be
		// identical to $this->uri->segments
		$this->uri->rsegments = $segments;
	}

	public function _create_get($segments)
	{
		$last_segment = end($segments);
		$url = parse_url($last_segment);
		if (isset($url['query']) and $url['query'])
		{
			parse_str($url['query'], $_GET);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Validates the supplied segments.  Attempts to determine the path to
	 * the controller.
	 *
	 * @access	private
	 * @param	array
	 * @return	array
	 */
	public function _validate_request($segments)
	{
		if ($segments[0] == "admin") {
			$segments = array_slice($segments, 1);

			if (count($segments) == 0) {
				show_404();
			}
			if (file_exists(MODULEPATH.$segments[0].'/controllers/'."admin_".$segments[0].EXT))
			{
				$this->is_admin_class = TRUE;
				return $segments;
			}
		}
                
		if ($segments[0] == "api") {
			$segments = array_slice($segments, 1);

			if (count($segments) == 0) {
				show_404();
			}
			if (file_exists(MODULEPATH.$segments[0].'/controllers/'."api_".$segments[0].EXT))
			{
				$this->is_api_class = TRUE;
				return $segments;
			}
		}
		
		// Does the requested controller exist in the root folder?
		if (file_exists(MODULEPATH.$segments[0].'/controllers/'.$segments[0].EXT))
		{
			return $segments;
		}

		// Is the controller in a sub-folder?
		if (is_dir(MODULEPATH.$segments[0].'/controllers/'.$segments[0]))
		{
			// Set the directory and remove it from the segment array
			$this->set_directory($segments[0]);
			$segments = array_slice($segments, 1);

			if (count($segments) > 0)
			{
				// Does the requested controller exist in the sub-folder?
				if ( ! file_exists(MODULEPATH.$segments[0].'/controllers/'.$this->fetch_directory().$segments[0].EXT))
				{
					show_404($this->fetch_directory().$segments[0]);
				}
			}
			else
			{
				$this->set_class($this->default_controller);
				$this->set_method('index');
				// Does the default controller exist in the sub-folder?
				if ( ! file_exists(MODULEPATH.$this->default_controller.'/controllers/'.$this->fetch_directory().$this->default_controller.EXT))
				{
					$this->directory = '';
					return array();
				}

			}

			return $segments;
		}

		// Can't find the requested controller...
		$this->class = 'error';
//		show_404($segments[0]);
	}

	// --------------------------------------------------------------------

	/**
	 *  Parse Routes
	 *
	 * This function matches any routes that may exist in
	 * the config/routes.php file against the URI to
	 * determine if the class/method need to be remapped.
	 *
	 * @access	private
	 * @return	void
	 */
	public function _parse_routes()
	{
		// Do we even have any custom routing to deal with?
		// There is a default scaffolding trigger, so we'll look just for 1
		if (count($this->routes) == 1)
		{
			$this->_set_request($this->uri->segments);
			return;
		}
		
		// Turn the segment array into a URI string
		$uri = $this->uri->_unfilter_uri(implode('/', $this->uri->segments));

		// Is there a literal match?  If so we're done
		if (isset($this->routes[$uri]))
		{
			$this->_set_request(explode('/', $this->routes[$uri]));
			return;
		}

		// Loop through the route array looking for wild-cards
		foreach ($this->routes as $key => $val)
		{
			// Convert wild-cards to RegEx
			$key = str_replace(':any', '.+', str_replace(':num', '[0-9]+', $key));
			// Does the RegEx match?
			if (preg_match('#^'.trim($key, '/').'$#iu', $uri))
			{
				// Do we have a back-reference?
				if (strpos($val, '$') !== FALSE AND strpos($key, '(') !== FALSE)
				{
					$val = preg_replace('#^'.trim($key, '/').'$#iu', $val, $uri);
				}
				$this->_set_request(explode('/', $val));
				return;
			}
		}

		// If we got this far it means we didn't encounter a
		// matching route so we'll set the site default route
		$this->_set_request($this->uri->segments);
	}

	// --------------------------------------------------------------------

	/**
	 * Set the class name
	 *
	 * @access	public
	 * @param	string
	 * @return	void
	 */
	public function set_class($class)
	{
		$this->class = $class;
	}

	// --------------------------------------------------------------------

	/**
	 * Fetch the current class
	 *
	 * @access	public
	 * @return	string
	 */
	public function fetch_class($check_for_admin_code = false)
	{
		if(!$check_for_admin_code){
			return $this->class;
		} elseif ($this->is_admin_class){
			return 'admin_'.$this->class;
		} elseif ($this->is_api_class){
			return 'api_'.$this->class;
		} else {
			return $this->class;
		}
	}
	
	/**
	 * Fetch the source class
	 *
	 * @access	public
	 * @return	string
	 */
	public function fetch_custom_class()
	{
		if (!defined('CUSTOM_MODE')) {
			define('CUSTOM_MODE', '');
		}
        
        if (CUSTOM_MODE) {
            $custom_mode = trim(CUSTOM_MODE, '_') . '_';
        } else {
            $custom_mode =  '';
        }
        
		if ($this->is_admin_class){
			return $custom_mode.'admin_'.$this->class;
		} elseif ($this->is_api_class){
			return $custom_mode.'api_'.$this->class;
		} else {
			return $custom_mode.$this->class;
		}
	}

	// --------------------------------------------------------------------

	/**
	 *  Set the method name
	 *
	 * @access	public
	 * @param	string
	 * @return	void
	 */
	public function set_method($method)
	{
		$sp = strpos($method, '?');
		if ($sp !== false) {
			$method = substr($method, 0, $sp);
		}
		$this->method = $method;
	}

	// --------------------------------------------------------------------

	/**
	 *  Fetch the current method
	 *
	 * @access	public
	 * @return	string
	 */
	public function fetch_method()
	{
		if ($this->method == $this->fetch_class())
		{
			return 'index';
		}

		return $this->method;
	}

	// --------------------------------------------------------------------
	
	/**
	 * Set the current lang
	 * 
	 * @param string $lang
	 */
	public function set_lang($lang)
	{
		$this->lang = $lang;
	}

	// --------------------------------------------------------------------
	
	/**
	 * Fetch the current lang
	 * 
	 * @return string
	 */
	public function fetch_lang()
	{
		return $this->lang;
	}

	// --------------------------------------------------------------------

	/**
	 * Looking for lang code within URI segments
	 * 
	 */
	private function _parse_lang_route()
	{
		$langs = $this->config->item('langs_route');
		foreach($this->uri->segments as $key => $segment) {
			if (in_array($segment, $langs)) {
				if(empty($this->lang)) {
					$this->set_lang($segment);
				}
				unset($this->uri->segments[$key]);
			}
			break;
		}
		$this->uri->segments = array_values($this->uri->segments);
		
		return true;
	}

	// --------------------------------------------------------------------

	/**
	 *  Set the directory name
	 *
	 * @access	public
	 * @param	string
	 * @return	void
	 */
	public function set_directory($dir)
	{
		$this->directory = $dir.'/';
	}

	// --------------------------------------------------------------------

	/**
	 *  Fetch the sub-directory (if any) that contains the requested controller class
	 *
	 * @access	public
	 * @return	string
	 */
	public function fetch_directory()
	{
		return $this->directory;
	}

}
// END Router Class

/* End of file Router.php */
/* Location: ./system/libraries/Router.php */
