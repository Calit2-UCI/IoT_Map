<?php

namespace Pg\Libraries\View;

abstract class AView
{

    protected $back_link = null;
    protected $code = null;
    protected $debugging = false;
    protected $driver = null;
    protected $force_compile = false;
    protected $general_path = '';
    protected $headers = array();
    protected $help = null;
    protected $messages = array();
    protected $module = null;
    protected $module_path = '';
    protected $output = null;
    protected $output_formats = array();
    protected $redirect = array();
    protected $renderer = null;
    protected $template = null;
    protected $theme = null;
    protected $theme_settings = array();
    protected $theme_type = null;
    protected $vars = array();
    protected static $instance;

    protected function __construct($driver = null)
    {
        if ($driver) {
            $this->setDriver($driver);
        }
    }

    public function getBackLink()
    {
        return $this->back_link;
    }

    public function setBackLink($link)
    {
        $this->back_link = $link;
        return $this;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    public function getDebugging()
    {
        return $this->debugging;
    }

    public function setDebugging($debug)
    {
        $this->debugging = $debug;
        return $this;
    }

    public function getDriver()
    {
        return $this->driver;
    }

    public function setDriver(Drivers\IDriver $driver)
    {
        $this->driver = $driver;
        return $this;
    }

    public function getForceCompile()
    {
        return $this->force_compile;
    }

    public function setForceCompile($force_compile)
    {
        $this->force_compile = $force_compile;
        return $this;
    }

    public function getGeneralPath()
    {
        return $this->general_path;
    }

    public function setGeneralPath($path)
    {
        $this->general_path = $path;
        return $this;
    }

    public function getHeader()
    {
        return $this->headers;
    }

    public function setHeader($header, $level = 0)
    {
        return $this->setHeaders(array($level => $header));
    }

    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
        return $this;
    }

    public function addHeader($level, $value)
    {
        $this->headers[$level] = $value;
        return $this;
    }

    public function getHelp()
    {
        return $this->help;
    }

    public function setHelp($help)
    {
        $this->help = $help;
        return $this;
    }

    public function getMessages($type = null)
    {
        if (!$type) {
            return $this->messages;
        }
        if (isset($this->messages[$type])) {
            return $this->messages[$type];
        } else {
            return array();
        }
    }

    public function setMessages(array $messages)
    {
        $this->messages = $messages;
        return $this;
    }

    public function addMessage($type, $message)
    {
        $this->messages[$type][] = $message;
        return $this;
    }

    public function getModule()
    {
        return $this->module;
    }

    public function setModule($module)
    {
        $this->module = $module;
        return $this;
    }

    public function getModulePath()
    {
        return $this->module_path;
    }

    public function setModulePath($path)
    {
        $this->module_path = $path;
        return $this;
    }
    
    public function getOutput()
    {
        return $this->output;
    }

    public function setOutput($output)
    {
        $this->output = $output;
        return $this;
    }

    public function getFormat()
    {
        return $this->output_formats;
    }

    protected function shiftFormat()
    {
        if (count($this->output_formats) > 1) {
            return array_shift($this->output_formats);
        } else {
            return current($this->output_formats);
        }
    }

    public function setFormat($format)
    {
        array_unshift($this->output_formats, $format);
        return $this;
    }

    public function setRedirect($url, $method = 'location', $code = 302)
    {
        $this->redirect = array(
            'url' => $url,
            'method' => $method,
            'code' => $code,
        );
        return $this;
    }

    public function getRedirect()
    {
        return $this->redirect;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function setTemplate($tpl)
    {
        $this->template = $tpl;
        return $this;
    }

    public function getTheme()
    {
        return $this->theme;
    }

    public function setTheme($theme)
    {
        $this->theme = $theme;
        return $this;
    }

    public function getThemeType()
    {
        return $this->theme_type;
    }

    public function setThemeType($themeType)
    {
        $this->theme_type = $themeType;
        return $this;
    }
    
    public function getThemeSettings()
    {
        return $this->theme_settings;
    }
    
    public function setThemeSettings(array $settings)
    {
        $this->theme_settings = $settings;
        return $this;
    }

    public function getVars()
    {
        return $this->vars;
    }

    public function getVar($name)
    {
        if (isset($this->vars[$name])) {
            return $this->vars[$name];
        } else {
            return null;
        }
    }

    public function setVars(array $vars)
    {
        $this->vars = $vars;
        return $this;
    }

    public function setVar($name, $value)
    {
        $this->vars[$name] = $value;
        return $this;
    }

}
