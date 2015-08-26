<?php

namespace Pg\Libraries\View\Drivers;

interface IDriver
{

    public function view($resource_name, $module, array $settings);

    public function assign($key, $value);

    //public function setTplPath($path);
    
    public function setTplExtension($ext);

    public function getTplExtension();
}
