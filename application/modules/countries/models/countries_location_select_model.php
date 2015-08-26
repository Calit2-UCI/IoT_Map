<?php

namespace Pg\Modules\Countries\Models;

class Countries_location_select_model extends \Model
{

    protected $CI;
    private $list;
    private $tpl_prefix = 'helper_location_select_';
    private $tpl_suffix = '.tpl';

    public function __construct()
    {
        parent::__construct();
        $this->CI = get_instance();
    }

    /**
     * Get currently selected template
     * @return string
     */
    public function getCurrentTpl()
    {
        $tpl = $this->CI->pg_module->get_module_config('countries', 'location_select_tpl');
        if (empty($tpl)) {
            return current($this->getTplList());
        } else {
            return $tpl;
        }
    }

    /**
     * Get filename of the currently selected template
     * @return string
     */
    public function getCurrentTplFile()
    {
        return $this->tpl_prefix . $this->getCurrentTpl() . $this->tpl_suffix;
    }

    /**
     * Update the list of the available templates
     * @return array
     */
    private function updateTplList()
    {
        $this->list = array();
        $theme_data = $this->CI->pg_theme->format_theme_settings('countries');
        $dir = new \DirectoryIterator(SITE_PHYSICAL_PATH . $theme_data['theme_module_path']);
        foreach ($dir as $file_info) {
            if (!$file_info->isFile()) {
                continue;
            }
            $file_name = $file_info->getBasename($this->tpl_suffix);
            if (stripos($file_name, $this->tpl_prefix) === 0) {
                $this->list[] = str_ireplace(array($this->tpl_prefix, $this->tpl_suffix), '', $file_name);
            }
        }
        return $this->list;
    }

    /**
     * Update the list of the available templates
     * @param boolean $force_update
     * @return array
     */
    public function getTplList($force_update = false)
    {
        if (empty($this->list) || $force_update) {
            $this->updateTplList();
        }
        return $this->list;
    }

}
