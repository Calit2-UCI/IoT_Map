<?php

namespace Pg\Libraries\View;

class Renderer
{
    // TODO: Подумать над переносом в базовый контроллер

    const FORMAT_HTML = 'html';
    const FORMAT_XML = 'xml';
    const FORMAT_JSON = 'json';

    private $output = '';
    private $view;

    public function __construct(\Pg\Libraries\View $view, $format)
    {
        $this->view = $view;
        switch ($format) {
            case self::FORMAT_XML:
                $this->output = $this->outputXml();
                break;
            case self::FORMAT_JSON:
                $this->output = $this->outputJson();
                break;
            case self::FORMAT_HTML:
                $this->output = $this->outputHtml();
                break;
            default:
                throw new \Exception('Wrong output format');
        }
    }

    private function outputXml()
    {
        $CI = &get_instance();
        $CI->load->library('array2xml');
        return $CI->array2xml->convert($this->view->aggregateOutputContent());
    }

    private function outputJson()
    {
        $force_json = filter_input(INPUT_POST, 'force_object', FILTER_VALIDATE_BOOLEAN);
        return json_encode(
            $this->view->aggregateOutputContent(), $force_json ? JSON_FORCE_OBJECT : null
        );
    }

    private function checkRedirect()
    {
        $redirect = $this->view->getRedirect();
        if ($redirect) {
            redirect($redirect['url'], $redirect['method'], $redirect['code']);
            return true;
        }
        return false;
    }

    private function outputHtml()
    {
        $CI = &get_instance();
        foreach ($this->view->getMessages() as $type => $messages) {
            $CI->system_messages->add_message($type, $messages);
        }
        $headers = $this->view->getHeader();
        if (isset($headers[0])) {
            $CI->system_messages->set_data('header', $headers[0]);
        }
        if (isset($headers[1])) {
            $CI->system_messages->set_data('subheader', $headers[1]);
        }
        $CI->system_messages->set_data('help', $this->view->getHelp());
        $CI->system_messages->set_data('back_link', $this->view->getBackLink());
        $this->checkRedirect();
        foreach ($this->view->getVars() as $key => $val) {
            $this->view->getDriver()->assign($key, $val);
        }
        return $this->view->getDriver()->view(
            $this->view->getTemplate(), 
            $this->view->getModule(), 
            $this->view->getThemeSettings()
        );
        /*return $this->view->getDriver()->view(
            $this->view->getTemplate(), 
            $this->view->getThemeType(), 
            $this->view->getModule(), 
            null, 
            false
        );*/
    }

    public function __toString()
    {
        return (string)$this->output;
    }

}
