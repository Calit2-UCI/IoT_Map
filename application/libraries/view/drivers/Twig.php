<?php

namespace Pg\Libraries\View\Drivers;

use Pg\Libraries\View\Drivers\IDriver;

require_once(SITE_PHYSICAL_PATH . 'vendor/autoload.php');

class Twig implements IDriver
{

    private $twig;
    private $tpl_extension = 'twig';
    private $vars = array();
    private $cache_enabled = true;
    private $cache_dir = false;
    
    private function init()
    {
        $this->cache_dir = $this->cache_enabled ? (TEMPPATH . 'twig/compiled') : false;
    }

    public function getTplExtension()
    {
        return $this->tpl_extension;
    }

    public function setTplExtension($ext){
        $this->tpl_extension = $ext;
        return $this;
    }

    public function assign($key, $value)
    {
        $this->vars[$key] = $value;
    }
    
    public function view($resource_name, $module_gid, array $theme)
    {
        $this->init();
        $loader = new \Twig_Loader_Filesystem(SITE_PHYSICAL_PATH . $theme["theme_module_path"]);
        $this->twig = new \Twig_Environment($loader, array(
            'cache' => $this->cache_dir,
        ));
        echo $this->twig->render($resource_name . '.' . $this->tpl_extension, $this->vars);
    }

}
