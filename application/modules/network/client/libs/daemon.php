<?php

/**
 * Php daemon.
 * @author abatukhtin
 */
class Daemon {

	const DEV_NULL = '/dev/null';
	private $_logs_dir=  './logs/';
	private $_omit_output = true;
	public $pid_file;
	public $err_log_file;
	public $stdout_file;
	public $stderr_file;
	public $pid;

	/**
	 * Constructor
	 * @param string $pid_file
	 * @param string $err_log_file
	 * @param string $stdout_file
	 * @param string $stderr_file
	 */
	public function __construct($dir, $omit_output = true) {
		// Without that directive php won't catch signals
		$this->omit_output($omit_output);
		$this->set_output_dir($dir);
	}

	public function omit_output($omit_output) {
		$this->_omit_output = (bool)$omit_output;
		return $this;
	}

	private function set_output_dir($dir) {
		$this->_logs_dir = $dir;
		$this->pid_file = $this->_logs_dir . 'daemon.pid';
		if($this->_omit_output) {
			$this->err_log_file = self::DEV_NULL;
			$this->stdout_file = self::DEV_NULL;
			$this->stderr_file = self::DEV_NULL;
		} else {
			$this->err_log_file = $this->_logs_dir . 'error.log';
			$this->stdout_file = $this->_logs_dir . 'stdout';
			$this->stderr_file = $this->_logs_dir . 'stderr';
		}
		return $dir;
	}

	/**
	 * Check file existence and try to create id if necessary
	 * @param string $path
	 * @return boolean
	 * @throws Exception 
	 */
	private function _check_file($path) {
		if (file_exists($path)) {
			if (!is_file($path)) {
				throw new Exception('err_create_file ' . $path);
			}
		} else {
			if (!file_exists(dirname($path)) && !mkdir(dirname($path), 0777, true)) {
				throw new Exception('err_create_file ' . $path);
			}
			if (!touch($path)) {
				throw new Exception('err_create_file ' . $path);
			}
			chmod($path, 0777);
		}
		return true;
	}

	/**
	 * Create child process and unbind it from console.
	 * Process will be stored in $this->pid_file
	 */
	private function _fork_process() {
		$this->_check_file($this->pid_file);
		// Create child process. Code after pcntl_fork() will be executed 
		// by two processes: parent and child.
		$child_pid = pcntl_fork();
		if ($child_pid) {
			// Parent goes here
			exit();
		} else {
			// Child goes here
		}
		// Make the child process the main
		posix_setsid();
		$this->pid = getmypid();
		file_put_contents($this->pid_file, $this->pid);
		return $this->pid;
	}

	/**
	 * Close STDIN, STDOUT, STDERR and redirect system output to a file.
	 * (IMPORTANT!) Using the constants after that most likely will fail.
	 * @global resource $STDIN
	 * @global resource $STDOUT
	 * @global resource $STDERR
	 */
	private function _override_std_output() {
		global $STDIN, $STDOUT, $STDERR;
		if(!$this->_omit_output) {
			$this->_check_file($this->err_log_file);
			$this->_check_file($this->stdout_file);
			$this->_check_file($this->err_log_file);
		}
		ini_set('error_log', $this->err_log_file);
        
        fclose(STDIN);
		fclose(STDOUT);
		fclose(STDERR);
        
        $STDIN = fopen(self::DEV_NULL, 'r');
		$STDOUT = fopen($this->stdout_file, 'a+');
		$STDERR = fopen($this->stderr_file, 'a+');
	}

	public function bind($signal, $cb) {
		pcntl_signal($signal, $cb);
	}

	/**
	 * Run the daemon
	 * @throws Exception
	 */
	public function run() {
		if ($this->is_active()) {
			throw new Exception('err_already_started');
		} else {
			$pid = $this->_fork_process();
			$this->_override_std_output();
			return $pid;
		}
	}

	/**
	 * Is daemon running
	 * @return boolean
	 */
	public function is_active() {
		if (is_file($this->pid_file)) {
			$pid = file_get_contents($this->pid_file);
			if (!$pid) {
				// File is empty
				unlink($this->pid_file);
			} elseif (posix_kill($pid, 0)) {
				// Process exists -> daemon is running
				return true;
			} else {
				// Pid file exists, but the process is not
				unlink($this->pid_file);
			}
		}
		return false;
	}

}
