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
 * @since		Version 1.3
 * @filesource
 */


// ------------------------------------------------------------------------

/**
 * CI_BASE - For PHP 5
 *
 * This file contains some code used only when CodeIgniter is being
 * run under PHP 5.  It allows us to manage the CI super object more
 * gracefully than what is possible with PHP 4.
 *
 * @package		CodeIgniter
 * @subpackage	codeigniter
 * @category	front-controller
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/
 */

class CI_Base {

	private static $instance;
	public $api_content = array('data' => '', 'errors' => '', 'messages' => '', 'code' => '');

	public function CI_Base()
	{
		self::$instance =& $this;
	}

	public static function &get_instance()
	{
		return self::$instance;
	}

	public function get_api_content()
	{
		return json_encode($this->api_content);
	}

	public function set_api_content($type = 'data', $value = array() )
	{
		if('code' === mb_strtolower($type)){
			$this->load->library('Output');
			$this->output->set_status_header($value);
		}
		$this->api_content[$type] = $value;
	}
}

function &get_instance()
{
	return CI_Base::get_instance();
}



/* End of file Base5.php */
/* Location: ./system/codeigniter/Base5.php */