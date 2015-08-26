<?php

/**
 * Libraries
 *
 * @package 	PG_Core
 * @copyright 	Copyright (c) 2000-2014 PG Core
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * PG Output Model
 * 
 * @package 	PG_Core
 * @subpackage 	Libraries
 * @category	libraries
 * @copyright 	Copyright (c) 2000-2014 PG Core
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
class PG_Output extends CI_Output
{

    /**
     * Data
     * 
     * @var array
     */
    public $data = array();

    /**
     * Errors messages
     * 
     * @var array
     */
    public $errors = array();

    /**
     * Success messages
     * 
     * @var array
     */
    public $success = array();

    /**
     * Template
     * 
     * @var string
     */
    public $template = '';

    /**
     * Code
     * 
     * @var string
     */
    public $code = '';

    /**
     * Header
     * 
     * @var array
     */
    public $header = array();

    /**
     * Help
     * 
     * @var string
     */
    public $help = '';

    /**
     * Back link
     * 
     * @var string
     */
    public $back_link = '';

    /**
     * Redirect
     * 
     * @var string
     */
    public $redirect = '';

    /**
     * Output
     * 
     * @return PG_Output
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Return output content
     * 
     * @return string
     */
    public function getOutputContent()
    {
        $output_content = array();

        if (!empty($this->data)) {
            $output_content['data'] = $this->data;
        }

        if (!empty($this->errors)) {
            $output_content['errors'] = $this->errors;
        }

        if (!empty($this->success)) {
            $output_content['success'] = $this->success;
        }

        if (!empty($this->template)) {
            $output_content['template'] = $this->template;
        }

        if (!empty($this->code)) {
            $output_content['code'] = $this->code;
        }

        if (!empty($this->header)) {
            $output_content['header'] = $this->header;
        }

        if (!empty($this->help)) {
            $output_content['help'] = $this->help;
        }

        if (!empty($this->back_link)) {
            $output_content['back_link'] = $this->back_link;
        }

        if (!empty($this->redirect)) {
            $output_content['redirect'] = $this->redirect;
        }

        return $output_content;
    }

    /**
     * Set output content
     * 
     * @param string $type data type
     * @param array $value data value
     * @return void
     */
    public function setOutputContent($type = 'data', $value = array())
    {
        switch (strtolower($type)) {
            case 'data':
                $this->data = $value;
                break;
            case 'errors':
                $this->errors = $value;
                break;
            case 'success':
                $this->success = $value;
                break;
            case 'template':
                $this->template = $value;
                break;
            case 'code':
                parent::set_status_header($value);
                $this->code = $value;
                break;
            case 'header':
                $this->header = $value;
                break;
            case 'help':
                $this->help = $value;
                break;
            case 'back_link':
                $this->back_link = $value;
                break;
            case 'redirect':
                $this->redirect = $value;
                break;
        }
    }

    /**
     * Set output message
     * 
     * @param string $name data name
     * @param array $value data value
     * @return void
     */
    public function setOutputMessage($name, $value)
    {
        $this->messages[$name] = $value;
    }

    /**
     * Set output data
     * 
     * @param string $name data name
     * @param array $value data value
     * @return void
     */
    public function setOutputData($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * Set output header
     * 
     * @param array $value header value
     * @return void
     */
    public function setOutputHeader($value)
    {
        $this->header[0] = $value;
    }

    /**
     * Set output subheader
     * 
     * @param array $value subheader value
     * @return void
     */
    public function setOutputSubheader($value)
    {
        $this->header[1] = $value;
    }

    /**
     * Set output help
     * 
     * @param array $value help value
     * @return void
     */
    public function setOutputHelp($value)
    {
        $this->help = $value;
    }

    /**
     * Set output back link
     * 
     * @param array $value back link value
     * @return void
     */
    public function setOutputBackLink($value)
    {
        $this->back_link = $value;
    }

    /**
     * Set output template
     * 
     * @param array $value template value
     * @return void
     */
    public function setOutputTemplate($value)
    {
        $this->template = $value;
    }

    /**
     * Set output redirect
     * 
     * @param array $value template value
     * @return void
     */
    public function setOutputRedirect($value)
    {
        $this->redirect = $value;
    }

}
