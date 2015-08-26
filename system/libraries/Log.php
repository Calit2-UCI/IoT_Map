<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

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
 * Logging Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Logging
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/general/errors.html
 */
class CI_Log {

	public $log_path;
	private $_threshold = 1;
	private $_date_fmt = 'Y-m-d H:i:s';
	private $_enabled = TRUE;
	private $_levels = array('ERROR' => '1', 'DEBUG' => '2', 'INFO' => '3', 'ALL' => '4');

	/**
	 * Constructor
	 *
	 * @access	public
	 */
	public function CI_Log() {
		$config = & get_config();

		$this->log_path = ($config['log_path'] != '') ? $config['log_path'] : BASEPATH . 'logs/';

		if (!is_dir($this->log_path) OR ! is_really_writable($this->log_path)) {
			$this->_enabled = FALSE;
		}

		if (is_numeric($config['log_threshold'])) {
			$this->_threshold = $config['log_threshold'];
		}

		if ($config['log_date_format'] != '') {
			$this->_date_fmt = $config['log_date_format'];
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Write Log File
	 *
	 * Generally this function will be called using the global log_message() function
	 *
	 * @access	public
	 * @param	string	the error level
	 * @param	string	the error message
	 * @param	bool	whether the error is a native PHP error
	 * @param	string	module
	 * @param	string	file
	 * @param	bool	Ignore treshhold level
	 * @return	bool
	 */
	public function write_log($level = 'error', $msg = '', $subdir = '', $file = 'log', $ignore_treshold = false) {
		if ($this->_enabled === false) {
			return false;
		}
		if(true === $subdir) {
			$subdir = '';
			$file = 'php_error';
		}
		
		$levelUp = strtoupper($level);
		if (!$ignore_treshold && (!isset($this->_levels[$levelUp]) || ($this->_levels[$levelUp] > $this->_threshold))) {
			return false;
		}

        if(DISPLAY_ERRORS){
            // Send to firebug
            $fb_file = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'fb' . EXT;
            if (!headers_sent() && file_exists($fb_file)) {
                include_once($fb_file);
                FB::send($msg, $levelUp);
            }
        }

		$dir = $this->log_path;
		if ($subdir) {
			$dir .= $subdir . DIRECTORY_SEPARATOR;
		}
		if (!is_dir($dir)) {
			mkdir($dir, 0777);
		}
		$full_name = $dir . $file . '-' . date('Y-m-d') . EXT;
		$message = '';
		if (!file_exists($full_name)) {
			$message .= "<" . "?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?" . ">\n\n";
		}
		if (!$fp = @fopen($full_name, FOPEN_WRITE_CREATE)) {
			return false;
		}

		$message .= $levelUp . ' ' . (($levelUp === 'INFO') ? ' -' : '-') . ' ' . date($this->_date_fmt) . ' --> ' . $msg . "\n";

		flock($fp, LOCK_EX);
		fwrite($fp, $message);
		flock($fp, LOCK_UN);
		fclose($fp);

		@chmod($full_name, FILE_WRITE_MODE);
		return true;
	}

	private function _get_files($dir, $file) {
		$files = array();
		foreach(new DirectoryIterator($dir) as $file_info) {
			if($file_info->isDot()) {
				continue;
			}
			if(0 === stripos($file_info->getBasename(EXT), $file)) {
				array_push($files, clone $file_info);
			}
		}
		usort($files, function($a, $b) {
			return ($a->getMTime() < $b->getMTime()) ? -1 : 1;
		});
		return $files;
	}

	public function read_log($subdir, $file, $lines_count) {
		$dir = $this->log_path;
		if ($subdir) {
			$dir .= $subdir . '/';
		}
		if (!is_dir($dir)) {
			return '';
		}

		$files = array_reverse($this->_get_files($dir, $file));
		$contents = '';
		foreach($files as $file) {
			$contents .= $this->tail($file->getRealPath(), $lines_count);
			if($this->_count_lines($contents) >= $lines_count) {
				break;
			}
		}
		return $contents;
	}

	private function _count_lines($str) {
		$lines_arr = preg_split('/\n|\r/',$str);
		return count($lines_arr);
	}
	
	private function tail($filepath, $lines = 1, $adaptive = true) {
		if(!is_file($filepath) || false === ($f = fopen($filepath, 'rb'))) {
			return '';
		}
		if (!$adaptive) {
			$buffer = 4096;
		} else {
			$buffer = ($lines < 2 ? 64 : ($lines < 10 ? 512 : 4096));
		}
		fseek($f, -1, SEEK_END);
		if (fread($f, 1) != "\n") {
			$lines -= 1;
		}
		$output = '';
		while (ftell($f) > 0 && $lines >= 0) {
			$seek = min(ftell($f), $buffer);
			fseek($f, -$seek, SEEK_CUR);
			$output = ($chunk = fread($f, $seek)) . $output;
			fseek($f, -mb_strlen($chunk, '8bit'), SEEK_CUR);
			$lines -= substr_count($chunk, "\n");
		}
		fclose($f);
		while ($lines++ < 0) {
			$output = substr($output, strpos($output, "\n") + 1);
		}
		return trim($output);
	}

}

// END Log Class

/* End of file Log.php */
/* Location: ./system/libraries/Log.php */
