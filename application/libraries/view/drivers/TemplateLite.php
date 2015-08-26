<?php

namespace Pg\Libraries\View\Drivers;

use Pg\Libraries\View\Drivers\IDriver;

require_once(BASEPATH . 'libraries/Template_lite.php');

class TemplateLite extends \CI_Template_lite implements IDriver
{

    // TODO: Переработать и перенести сюда содержимое Template_lite.php
    public function getTplExtension()
    {
        
    }

    public function setTplExtension($param)
    {
        
    }

    public function view($template, $module, array $theme)
    {
        parent::view($template, $theme['type'], $module);
    }

}
