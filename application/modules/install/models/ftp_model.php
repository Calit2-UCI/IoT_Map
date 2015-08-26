<?php

/**
 * Install module
 *
 * @package 	PG_Core
 * @copyright 	Copyright (c) 2000-2014 PG Core
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */

/**
 * Ftp Model
 *
 * @package 	PG_Core
 * @subpackage 	Install
 * @category 	models
 * @copyright 	Copyright (c) 2000-2014 PG Core
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
class Ftp_model extends Model
{

    private $ftp_conn = false;

    public function ftp()
    {
        return extension_loaded('ftp');
    }

    public function ftp_login($host, $login, $password)
    {
        $errors = array();
        $this->ftp_conn = @ftp_connect($host);
        if (!$this->ftp_conn) {
            $errors[] = "Couldn't connect to " . $host;
            return $errors;
        }

        $login_result = @ftp_login($this->ftp_conn, $login, $password);
        if (!$login_result) {
            $errors[] = "FTP connection has failed (Host: " . $host . ", User: " . $login . ")";
            return $errors;
        }

        $this->ftp_script_dir();
        return true;
    }

    public function ftp_rename($old_file, $new_file)
    {
        if (!$this->ftp_conn) {
            return false;
        }
        return @ftp_rename($this->ftp_conn, $old_file, $new_file);
    }

    public function ftp_mkdir($dir)
    {
        if (!$this->ftp_conn) {
            return false;
        }
        return @ftp_mkdir($this->ftp_conn, $dir);
    }

    public function ftp_mkdir_rec($dir)
    {
        if (!$this->ftp_conn) {
            return false;
        }
        if ($this->ftp_is_dir($dir) || @ftp_mkdir($this->ftp_conn, $dir)) {
            return true;
        }
        if (!$this->ftp_mkdir_rec(dirname($dir))) {
            return false;
        }
        return ftp_mkdir($this->ftp_conn, $dir);
    }

    public function ftp_is_dir($dir)
    {
        $orig_rid = ftp_pwd($this->ftp_conn);
        if (@ftp_chdir($this->ftp_conn, $dir)) {
            ftp_chdir($this->ftp_conn, $orig_rid);
            return true;
        } else {
            return false;
        }
    }

    public function ftp_chmod($mode, $file)
    {
        if (!$this->ftp_conn) {
            return false;
        }
        return @ftp_chmod($this->ftp_conn, $mode, $file);
    }

    public function ftp_delete($file)
    {
        if (!$this->ftp_conn) {
            return false;
        }
        return @ftp_delete($this->ftp_conn, $file);
    }

    public function ftp_logout()
    {
        if ($this->ftp_conn) {
            @ftp_close($this->ftp_conn);
        }
    }

    public function ftp_script_dir()
    {
        $script_dir = dirname($_SERVER["SCRIPT_FILENAME"]);
        $path_arr = explode('/', $script_dir);
        $count = count($path_arr);
        for ($i = 0; $i < $count; $i++) {
            $script_dir = implode('/', $path_arr);
            if (@ftp_chdir($this->ftp_conn, $script_dir)) {
                return $script_dir;
            } else {
                unset($path_arr[0]);
                $path_arr = array_values($path_arr);
            }
        }
        return false;
    }

}
