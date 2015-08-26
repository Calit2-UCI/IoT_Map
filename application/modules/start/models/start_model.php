<?php

namespace Pg\Modules\Start\Models;

/**
 * Start model
 *
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

if (!defined('WYSIWYG_UPLOAD_PATH')) {
    define('WYSIWYG_UPLOAD_PATH', FRONTEND_PATH . 'wysiwyg/');
}
if (!defined('WYSIWYG_UPLOAD_URL')) {
    define('WYSIWYG_UPLOAD_URL', FRONTEND_URL . 'wysiwyg/');
}

class Start_model extends \Model
{

    public $CI;
    public $wysiwyg_upload_config = array(
        'allowed_types' => 'gif|jpg|png|jpeg|bmp|tiff|flv|swf',
        'max_size' => '10000',
        'max_width' => '5000',
        'max_height' => '5000',
        'encrypt_name' => true,
        'max_filename' => 12
    );

    public function __construct()
    {
        parent::__construct();
        $this->CI = &get_instance();
    }

    public function clear_trash_folder()
    {
        $result = false;
        if ($handle = opendir(SITE_PHYSICAL_PATH . TRASH_FOLDER)) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    @unlink(SITE_PHYSICAL_PATH . TRASH_FOLDER . $file);
                }
            }
            closedir($handle);
            $result = true;
        }
        return $result;
    }

    // banners callback method
    public function _banner_available_pages()
    {
        $return[] = array("link" => "start/index", "name" => l('header_start_page', 'start'));
        $return[] = array("link" => "start/homepage", "name" => l('header_homepage', 'start'));
        return $return;
    }

    public function _dynamic_block_get_stat_block()
    {
        $stat = array();
        if ($this->CI->pg_module->is_module_installed('users')) {
            $this->CI->load->model("Users_model");
            $user_types_count = $this->CI->Users_model->get_active_users_types_count();
            $this->CI->load->model('Properties_model');
            $user_types = $this->CI->Properties_model->get_property('user_type');

            foreach ($user_types_count as $user_type_count) {
                if (isset($user_types['option'][$user_type_count['user_type']])) {
                    $stat[] = array(
                        'object_name' => $user_types['option'][$user_type_count['user_type']],
                        'count' => $user_type_count['count']
                    );
                }
            }
        }
        if (count($stat)) {
            $this->CI->template_lite->assign('stat', $stat);
            return $this->CI->template_lite->fetch('stat_block', 'user', 'start');
        } else {
            return false;
        }
    }

    public function _dynamic_block_get_search_form()
    {
        $this->CI->load->helper('start');
        $html = main_search_form('user', 'index', true, array('hide_popup' => true));
        return $html;
    }

    public function backend_ping_request()
    {
        return array('status' => 'pong');
    }

    public function do_wysiwyg_upload($module = '', $id = 0, $upload_config_gid = '', $field = 'upload')
    {
        $wysiwyg_upload_config = array();
        if ($upload_config_gid && $module) {
            $model = $module . '_model';
            if ($this->CI->load->model($model, $model, false, true, true)) {
                $wysiwyg_upload_config = !empty($this->CI->{$model}->{$upload_config_gid}) ? $this->CI->{$model}->{$upload_config_gid} : array();
            }
        }
        if (!$wysiwyg_upload_config) {
            $wysiwyg_upload_config = $this->wysiwyg_upload_config;
        }

        $result['error'] = false;
        $result['is_uploaded'] = false;
        $upload = array();

        $subdir = '';
        if ($module) {
            $subdir .= $module . '/';
            if ($id) {
                $subdir .= $id . '/';
            }
        }

        $path = WYSIWYG_UPLOAD_PATH . $subdir;
        $url = WYSIWYG_UPLOAD_URL . $subdir;
        if (!is_dir($path)) {
            @mkdir($path, 0777, TRUE);
        }

        $wysiwyg_upload_config['upload_path'] = $path;
        $this->CI->load->library('upload', $wysiwyg_upload_config);
        if ($this->upload->do_upload($field)) {
            $upload = $this->upload->data();
        } else {
            if ($this->upload->file_temp) {
                $result['error'] = $this->upload->display_errors();
                return $result;
            }
        }

        if ($upload) {
            $result['is_uploaded'] = true;
            $result['img_name'] = $upload['raw_name'];
            $result['img_ext'] = $upload['file_ext'];
            $result['img_full_name'] = $upload['file_name'];
            $result['upload_url'] = $url . $upload['file_name'];
        }

        return $result;
    }

    public function clear_wysiwyg_uploads_dir($dir = '', $id = 0)
    {
        $subdir = '';
        if ($dir) {
            $subdir .= $dir . '/';
            if ($id) {
                $subdir .= $id . '/';
            }
        }
        $path = WYSIWYG_UPLOAD_PATH . $subdir;

        $result = false;
        if ($handle = opendir($path)) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    @unlink($path . $file);
                }
            }
            closedir($handle);
            if ($path != WYSIWYG_UPLOAD_PATH) {
                @rmdir($path);
            }
            $result = true;
        }
        return $result;
    }

}
