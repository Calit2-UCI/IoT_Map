<?php

namespace Pg\Libraries\View\Drivers\Native;

class RenderTemplate
{
    private $contents = '';

    public function __construct($filename, array $vars = array())
    {
        if(!is_file($filename)) {
            throw new Exception('Wrong template name');
        }
        foreach($vars as $key => $val) {
            $this->$key = $val;
        }
        ob_start();
        include $filename;
        $this->contents = ob_get_clean();
        return $this->contents;
    }

    public function __toString()
    {
        return $this->contents;
    }

}
