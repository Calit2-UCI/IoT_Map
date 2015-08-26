<?php

namespace Pg\Libraries;

use Pg\Libraries\View\Renderer;

class View extends View\AView
{

    const MSG_ERROR = 'error';
    const MSG_INFO = 'info';
    const MSG_SUCCESS = 'success';

    public $output;

    protected function __construct()
    {
        parent::__construct();
        $this->init();
    }

    public static function &getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function init()
    {
        $CI = get_instance();
        $this->setTheme($CI->pg_theme);
        $this->setModule($CI->router->class);
        $this->setThemeType($CI->router->is_admin_class ? 'admin' : 'user');
        $this->setGeneralPath(APPPATH . 'views/');
        $this->setThemeSettings($CI->pg_theme->format_theme_settings($this->getModule(), $this->getThemeType()));
        //var_export($this->view->getThemeSettings());
        $this->setForceCompile(defined('TPL_FORCE_COMPILE') && TPL_FORCE_COMPILE);
        $this->setDebugging(defined('TPL_DEBUGGING') && TPL_DEBUGGING);
        $this->setFormat($this->determineOutputFormat());

        $this->assign('site_url', site_url());
        $this->assign('site_root', '/' . SITE_SUBFOLDER);
        $this->assign('base_url', base_url());
        $this->assign('general_path_relative', APPPATH_VIRTUAL . 'views/');
        $this->assign('js_folder', APPLICATION_FOLDER . 'js/');

        log_message('debug', 'View initialized');
    }

    public function getMessageTypes()
    {
        return array(
            self::MSG_ERROR,
            self::MSG_INFO,
            self::MSG_SUCCESS,
        );
    }

    private function determineOutputFormat()
    {
        // TODO: В отдельный класс
        $format = 'html';
        $CI = get_instance();
        if ($this->isApiClass()) {
            $format = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
            if ($format === 'xml') {
                if (!$CI->pg_module->get_module_config('get_token', 'use_xml')) {
                    $format = 'json';
                }
            } else {
                $format = 'json';
            }
        } else {
            $accept = filter_input(INPUT_SERVER, 'HTTP_ACCEPT', FILTER_SANITIZE_STRING);
            if (strpos($accept, 'text/xml') !== false) {
                $format = 'xml';
            } elseif (strpos($accept, 'application/json') !== false) {
                $format = 'json';
            }
        }
        return $format;
    }

    public function getTemplate()
    {
        if ($this->template) {
            return $this->template;
        } else {
            $CI = get_instance();
            return $CI->router->fetch_method();
        }
    }

    private function isApiClass()
    {
        $CI = &get_instance();
        return (bool) $CI->router->is_api_class;
    }

    public function aggregateOutputContent()
    {
        $output_content = array(
            'template' => $this->getTemplate(),
            'data' => $this->getVars(),
            'back_link' => $this->getBackLink(),
            'code' => $this->getCode(),
            'header' => $this->getHeader(),
            'help' => $this->getHelp(),
            'redirect' => $this->getRedirect(),
        );
        foreach ($this->getMessageTypes() as $msg_type) {
            $output_content[$msg_type] = $this->getMessages($msg_type);
        }
        return $output_content;
    }

    public function assign($name, $value = null)
    {
        if (is_array($name)) {
            foreach ($name as $name => $val) {
                $this->assign($name, $val);
            }
        } else {
            $this->setVar($name, $value);
        }
        return $this;
    }

    private function setOptions($resource_name = '', $theme_type = 'user', $module = null)
    {
        if ($resource_name) {
            $this->setTemplate($resource_name);
        } else {
            $this->setTemplate('');
        }
        if ($theme_type) {
            $this->setThemeType($theme_type);
        } else {
            $CI = get_instance();
            $this->setThemeType($CI->router->is_admin_class ? 'admin' : 'user');
        }
        if ($module) {
            $this->setModule($module);
        } else {
            $CI = get_instance();
            $this->setModule($CI->router->class);
        }
    }

    public function getContents()
    {
        if ($this->getTemplate()) {
            $format = Renderer::FORMAT_HTML;
        } else {
            $format = $this->shiftFormat();
        }
        $this->setOutput((string) new Renderer(self::getInstance(), $format));
        return $this->output;
    }

    public function fetch($resource_name = '', $theme_type = null, $module = null)
    {
        $this->setOptions($resource_name, $theme_type, $module);
        $this->getContents();
        return $this->getOutput();
    }

    public function fetchFinal($resource_name = '', $theme_type = null, $module = null)
    {
        $this->setOptions($resource_name, $theme_type, $module);
        $this->getContents();
        load_class('Hooks')->_call_hook('pre_view');
        // TBD: echo $this->driver->getDebugInfo();
        return $this->getOutput();
    }

    public function render($resource_name = '', $theme_type = null, $module = null)
    {
        echo $this->fetchFinal($resource_name, $theme_type, $module, true);
    }

}
