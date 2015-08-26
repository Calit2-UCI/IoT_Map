<?php

/**
 * Install module
 *
 * @package 	PG_Core
 * @copyright 	Copyright (c) 2000-2014 PG Core
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */

/**
 * Backup Model
 *
 * @package 	PG_Core
 * @subpackage 	Install
 * @category 	models
 * @copyright 	Copyright (c) 2000-2014 PG Core
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
class Backup_model extends Model
{

    /**
     * Class constructor 
     * 
     * @return Backup_model
     */
    public function __construct()
    {
        parent::__construct();
        $this->CI = &get_instance();
    }

    public function generate_module_settings_install($module_gid)
    {
        $this->CI->load->library('zip');
        $this->CI->zip->add_dir('application/modules/' . $module_gid . '/install');

        //$lang_install_code = $this->CI->pg_language->generate_install_module_lang($module_gid);
        //$this->CI->zip->add_data('application/modules/'.$module_gid.'/install/lang.php', $lang_install_code);
        //lang back up 
        $this->CI->load->model("Install_model");
        $model = $this->CI->Install_model->load_install_model($module_gid);
        $config = $this->CI->Install_model->get_module_config($module_gid);
        $linked_modules = (array) $config['linked_modules']['install'];
        $installed_modules = array_keys($this->CI->Install_model->get_installed_modules());
        $langs_path = 'application/modules/' . $module_gid . '/langs/';

        // Add langs data to the zip file
        $available_langs = $this->Install_model->get_available_langs();
        $langs = array();
        foreach ($available_langs as $avail_lang) {
            $langs[] = $avail_lang['code'];
        }

        foreach ($this->CI->pg_language->languages as $lang_id => $lang_data) {
            if (!in_array($lang_data['code'], $langs)) {
                continue;
            }
            // pages
            $lang_install_code = $this->CI->pg_language->generate_install_module_lang($module_gid, $lang_id, "pages");
            if ($lang_install_code) {
                $this->CI->zip->add_data($langs_path . $lang_data['code'] . '/pages.php', $lang_install_code);
            }
            // ds
            $lang_install_code = $this->CI->pg_language->generate_install_module_lang($module_gid, $lang_id, 'ds');
            if ($lang_install_code) {
                $this->CI->zip->add_data($langs_path . $lang_data['code'] . '/ds.php', $lang_install_code);
            }
            // arbitrary
            if (method_exists($model, '_arbitrary_lang_export')) {
                $langs_data = $this->CI->Install_model->execute_method($model, '_arbitrary_lang_export', null, (array) $lang_id);
                foreach ($langs_data as $lmodule => $ldata) {
                    $lang_install_code = $this->CI->pg_language->generate_install_linked_lang($ldata, $lang_id);
                    $this->CI->zip->add_data($langs_path . $lang_data['code'] . '/' . $lmodule . '.php', $lang_install_code);
                }
            }
            // linked modules
            foreach ($linked_modules as $linked_module => $linked_method) {
                if (in_array($linked_module, $installed_modules)) {
                    $method_name = (is_array($linked_method) ? $linked_method['name'] : $linked_method) . '_lang_export';
                    if (method_exists($model, $method_name)) {
                        $langs_data = $this->CI->Install_model->execute_method($model, $method_name, null, (array) $lang_id);
                        foreach ($langs_data as $lmodule => $ldata) {
                            $lang_install_code = $this->CI->pg_language->generate_install_linked_lang($ldata, $lang_id);
                            $this->CI->zip->add_data($langs_path . $lang_data['code'] . '/' . $lmodule . '.php', $lang_install_code);
                        }
                    }
                }
            }
        }

        $perm_install_code = $this->CI->pg_module->generate_install_module_permissions($module_gid);
        $this->CI->zip->add_data('application/modules/' . $module_gid . '/install/permissions.php', $perm_install_code);

        $sett_install_code = $this->CI->pg_module->generate_install_module_settings($module_gid);
        $this->CI->zip->add_data('application/modules/' . $module_gid . '/install/settings.php', $sett_install_code);
        $this->CI->zip->download($module_gid . '_settings_backup_' . date('ymd') . '.zip');

        return;
    }

    public function generate_product_permissions_install()
    {
        $this->CI->load->library('zip');

        $modules = $this->CI->pg_module->return_modules();
        if (empty($modules)) {
            return false;
        }

        $this->CI->zip->add_dir('application/modules');
        foreach ($modules as $module) {
            $perm_install_code = $this->CI->pg_module->generate_install_module_permissions($module["module_gid"]);
            $this->CI->zip->add_data('application/modules/' . $module["module_gid"] . '/install/permissions.php', $perm_install_code);
        }

        $this->CI->zip->download('permissions_install.zip');

        return;
    }

    public function generate_module_files_backup($module_gid)
    {
        $this->CI->load->library('zip');
        $this->CI->load->model("Install_model");
        $module = $this->CI->Install_model->get_module_config($module_gid);
        foreach ($module["files"] as $file) {
            if ($file[0] == 'file') {
                $content = file_get_contents(SITE_PHYSICAL_PATH . $file[2]);
                $this->CI->zip->add_data($file[2], $content);
            } else {
                $this->CI->zip->add_dir($file[2]);
            }
        }
        $this->CI->zip->download($module_gid . '_backup_' . date('ymd') . '.zip');
    }

    public function generate_product_modules_files_backup()
    {
        $modules = $this->get_product_setup_modules();

        $this->CI->load->library('zip');
        
        $this->CI->load->model('Install_model');

        foreach ($modules as $module_gid) {
            $module = $this->CI->Install_model->get_module_config($module_gid);
            foreach ($module["files"] as $file) {
                if ($file[0] == 'file') {
                    $content = file_get_contents(SITE_PHYSICAL_PATH . $file[2]);
                    $this->CI->zip->add_data(module_gid . "/" . $file[2], $content);
                } else {
                    $this->CI->zip->add_dir($module_gid . "/" . $file[2]);
                }
            }
        }
        $this->CI->zip->download('modules_backup_' . date('ymd') . '.zip');
    }

}
