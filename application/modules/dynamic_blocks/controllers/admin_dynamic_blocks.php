<?php

namespace Pg\Modules\Dynamic_blocks\Controllers;

/**
 * Dynamic blocks module
 *
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */

/**
 * Dynamic blocks admin side controller
 * 
 * @package PG_Dating
 * @subpackage 	Dynamic blocks
 * @category	controllers
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
class Admin_Dynamic_blocks extends \Controller
{

    /**
     * Allow edit config settings
     * 
     * @var object
     */
    protected $allow_config_add = false;

    /**
     * Constructor
     * 
     * @return Admin_Dynamic_blocks
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Dynamic_blocks_model');
        $this->load->model('Menu_model');
        $this->Menu_model->set_menu_active_item('admin_menu', 'interface-items');
    }

    /**
     * Manage dynamic blocks areas
     * 
     * @param integer $page page of results
     * @return void
     */
    public function index($page = 1)
    {

        $areas_count = $this->Dynamic_blocks_model->get_areas_count();

        if (!$page) {
            $page = isset($_SESSION["areas_list"]["page"]) ? $_SESSION["areas_list"]["page"] : 1;
        }

        $items_on_page = $this->pg_module->get_module_config('start', 'admin_items_per_page');
        $this->load->helper('sort_order');
        $page = get_exists_page_number($page, $areas_count, $items_on_page);
        $_SESSION["areas_list"]["page"] = $page;

        if ($areas_count > 0) {
            $areas = $this->Dynamic_blocks_model->get_areas_list($page, $items_on_page);
            $this->template_lite->assign('areas', $areas);
        }
        $this->load->helper("navigation");
        $page_data = get_admin_pages_data(site_url() . "admin/dynamic_blocks/index/", $areas_count, $items_on_page, $page, 'briefPage');
        $this->template_lite->assign('page_data', $page_data);

        $this->template_lite->assign('allow_config_add', $this->allow_config_add);

        $this->Menu_model->set_menu_active_item('admin_dynblocks_menu', 'areas_list_item');
        $this->system_messages->set_data('header', l('admin_header_areas_list', 'dynamic_blocks'));
        $this->template_lite->view('list');
    }

    /**
     * Edit dynamic blocks area data
     * 
     * @param integer $id area identifier
     * @return void
     */
    public function edit($id = null)
    {
        $data = ($id) ? $this->Dynamic_blocks_model->get_area_by_id($id) : array();

        if ($this->input->post('btn_save')) {
            $post_data = array(
                "gid" => $this->input->post('gid', true),
                "name" => $this->input->post('name', true),
            );

            $validate_data = $this->Dynamic_blocks_model->validate_area($id, $post_data);
            if (!empty($validate_data["errors"])) {
                $this->system_messages->add_message('error', $validate_data["errors"]);
            } else {
                $id = $this->Dynamic_blocks_model->save_area($id, $validate_data["data"]);

                $this->system_messages->add_message('success', ($id) ? l('success_update_area', 'dynamic_blocks') : l('success_add_area', 'dynamic_blocks'));
                redirect(site_url() . "admin/dynamic_blocks/index/" . $_SESSION["areas_list"]["page"]);
            }
            $data = array_merge($data, $validate_data["data"]);
        }
        $this->template_lite->assign('data', $data);

        $this->Menu_model->set_menu_active_item('admin_dynblocks_menu', 'areas_list_item');
        $this->system_messages->set_data('header', l('admin_header_area_edit', 'dynamic_blocks'));
        $this->template_lite->view('edit_form');
    }

    /**
     * Manage dynamic blocks of area
     * 
     * @param integer $id_area area identifier
     * @return void
     */
    public function area_blocks($id_area)
    {
        $blocks = $this->Dynamic_blocks_model->get_blocks_list_by_id();
        $this->template_lite->assign('blocks', $blocks);

        if ($this->input->post('add_block')) {
            $id_block = intval($this->input->post('id_block'));
            if ($id_block) {
                $added_block_data = $blocks[$id_block];
                $attrs["id_area"] = $id_area;
                $attrs["id_block"] = $id_block;
                $attrs["cache_time"] = 0;
                $attrs["sorter"] = $this->Dynamic_blocks_model->get_area_blocks_count($id_area) + 1;

                if (!empty($added_block_data["params_data"])) {
                    foreach ($added_block_data["params_data"] as $param) {
                        $attrs["params"][$param["gid"]] = $param["default"];
                    }
                } else {
                    $attrs["params"] = array();
                }
                $attrs["params"] = serialize($attrs["params"]);
                $attrs["view_str"] = $added_block_data["views_data"][0]["gid"];

                $this->Dynamic_blocks_model->save_area_block(null, $attrs);
                $this->system_messages->add_message('success', l('success_add_area_block', 'dynamic_blocks'));
            }
        }

        $area_blocks = $this->Dynamic_blocks_model->get_area_blocks($id_area);
        if (!empty($area_blocks)) {
            foreach ($area_blocks as $key => $area_block) {
                $area_blocks[$key]["block_data"] = $blocks[$area_block["id_block"]];
            }
        }
        $this->template_lite->assign('area_blocks', $area_blocks);

        $area = $this->Dynamic_blocks_model->get_area_by_id($id_area);
        $this->template_lite->assign('area', $area);

        $this->template_lite->assign("current_lang_id", $this->pg_language->current_lang_id);
        $this->template_lite->assign("langs", $this->pg_language->languages);

        $presets_count = $this->Dynamic_blocks_model->get_presets_count();
        $this->template_lite->assign('presets_count', $presets_count);

        $page = isset($_SESSION["areas_list"]["page"]) ? $_SESSION["areas_list"]["page"] : 1;

        $this->system_messages->set_data('back_link', site_url() . "admin/dynamic_blocks/index/" . $page);
        $this->pg_theme->add_js('admin-multilevel-sorter.js');
        $this->Menu_model->set_menu_active_item('admin_dynblocks_menu', 'areas_list_item');
        $this->system_messages->set_data('header', l('admin_header_area_blocks_edit', 'dynamic_blocks') . ": " . $area["name"]);

        $this->load->plugin('fckeditor');
        $editor_params = get_editor_params();
        $editor_params['upload_url'] = site_url() . 'admin/start/wysiwyg_uploader/dynamic_blocks/';
        $this->template_lite->assign('editor_params', $editor_params);

        $this->template_lite->view('edit_block_list');
    }

    /**
     * Save area's dynamic block options
     * 
     * @param integer $id_area_block area block idetifier
     * @return void
     */
    public function ajax_save_area_block($id_area_block)
    {
        $post_data = array(
            "params" => $this->input->post('params'),
            "view_str" => $this->input->post('view_str', true),
            "cache_time" => intval($this->input->post('cache_time')),
        );
        $post_data["params"] = serialize($post_data["params"]);
        $this->Dynamic_blocks_model->save_area_block($id_area_block, $post_data);
        return;
    }

    /**
     * Remove dynamic block from area
     * 
     * @param integer $id_area_block area block identifier
     * @return void
     */
    public function ajax_delete_area_block($id_area_block)
    {
        $this->Dynamic_blocks_model->delete_area_block($id_area_block);
        return;
    }

    /**
     * Resort dynamic blocks in area
     * 
     * @param integer $id_area area identifier
     * @return void
     */
    public function save_area_block_sorter($id_area)
    {
        $sorter = $this->input->post("sorter");
        foreach ($sorter["parent_0"] as $item_str => $sort_index) {
            $id = intval(str_replace("item_", "", $item_str));
            $this->Dynamic_blocks_model->save_area_block_sorter($id, $sort_index);
        }
        return;
    }

    /**
     * Remove dynamic blocks area
     * 
     * @param integer $id area identifier
     * @return void
     */
    public function delete($id)
    {
        if (!empty($id)) {
            $this->Dynamic_blocks_model->delete_area($id);
            $this->system_messages->add_message('success', l('success_delete_area', 'dynamic_blocks'));
        }
        redirect(site_url() . "admin/dynamic_blocks/index/" . $_SESSION["areas_list"]["page"]);
    }

    /**
     * Manage dynamic blocks
     * 
     * @param integer $page page of results
     * @return void
     */
    public function blocks($page = 1)
    {
        $blocks_count = $this->Dynamic_blocks_model->get_blocks_count();

        if (!$page) {
            $page = isset($_SESSION["areas_list"]["page"]) ? $_SESSION["areas_list"]["page"] : 1;
        }

        $items_on_page = $this->pg_module->get_module_config('start', 'admin_items_per_page');
        $this->load->helper('sort_order');
        $page = get_exists_page_number($page, $blocks_count, $items_on_page);
        $_SESSION["block_list"]["page"] = $page;

        if ($blocks_count > 0) {
            $blocks = $this->Dynamic_blocks_model->get_blocks_list($page, $items_on_page);
            $this->template_lite->assign('blocks', $blocks);
        }
        $this->load->helper("navigation");
        $page_data = get_admin_pages_data(site_url() . "admin/dynamic_block/blocks/", $blocks_count, $items_on_page, $page, 'briefPage');
        $this->template_lite->assign('page_data', $page_data);

        $this->Menu_model->set_menu_active_item('admin_dynblocks_menu', 'blocks_list_item');
        $this->system_messages->set_data('header', l('admin_header_blocks_list', 'dynamic_blocks'));
        $this->template_lite->view('list_blocks');
    }

    /**
     * Edit dynamic block data
     * 
     * @param integer $id block identifier
     * @return void
     */
    public function edit_block($id = null)
    {
        $data = ($id) ? $this->Dynamic_blocks_model->get_block_by_id($id) : array();

        if ($this->input->post('btn_save')) {
            $post_data = array(
                "gid" => $this->input->post('gid', true),
                "module" => $this->input->post('module', true),
                "model" => $this->input->post('model', true),
                "method" => $this->input->post('method', true),
                "params" => $this->input->post('params', true),
                "views" => $this->input->post('views', true),
            );

            $langs_data = $this->input->post('langs', true);

            $validate_data = $this->Dynamic_blocks_model->validate_block($id, $post_data, $langs_data);

            if (!empty($validate_data["errors"])) {
                $this->system_messages->add_message('error', $validate_data["errors"]);
            } else {
                $this->Dynamic_blocks_model->save_block($id, $validate_data["data"], $validate_data["langs"]);

                $this->system_messages->add_message('success', ($id) ? l('success_update_block', 'dynamic_blocks') : l('success_add_block', 'dynamic_blocks'));
                redirect(site_url() . "admin/dynamic_blocks/blocks/" . $_SESSION["block_list"]["page"]);
            }
            $data = array_merge($data, $validate_data["data"]);
            $this->template_lite->assign('validate_lang', $validate_data["langs"]);
        }

        if (!empty($data)) {
            $data = $this->Dynamic_blocks_model->format_block($data, true);
        }
        $this->template_lite->assign('data', $data);

        $block_types = $this->pg_language->ds->get_reference(
            $this->Dynamic_blocks_model->module_gid, $this->Dynamic_blocks_model->block_types_gid, $this->pg_language->current_lang_id
        );
        $this->template_lite->assign("block_types", $block_types);

        ///// languages
        $this->template_lite->assign('languages', $this->pg_language->languages);
        $this->template_lite->assign('languages_count', count($this->pg_language->languages));
        $this->template_lite->assign('cur_lang', $this->pg_language->current_lang_id);

        $this->Menu_model->set_menu_active_item('admin_dynblocks_menu', 'areas_list_item');
        $this->system_messages->set_data('header', l('admin_header_block_edit', 'dynamic_blocks'));
        $this->template_lite->view('edit_block_form');
    }

    /**
     * Remove dynamic block data
     * 
     * @param integer $id block identifier
     * @return void
     */
    public function delete_block($id)
    {
        if (!empty($id)) {
            $this->Dynamic_blocks_model->delete_block($id);
            $this->system_messages->add_message('success', l('success_delete_block', 'dynamic_blocks'));
        }
        redirect(site_url() . "admin/dynamic_blocks/blocks/" . $_SESSION["block_list"]["page"]);
    }

    /**
     * Manage area layout
     * 
     * @param integer $id_area area identifier
     * @return void
     */
    public function area_layout($id_area)
    {
        $area = $this->Dynamic_blocks_model->get_area_by_id($id_area);
        $this->template_lite->assign('area', $area);

        $layout = array();

        $area_blocks = $this->Dynamic_blocks_model->get_area_blocks($id_area);
        if (!empty($area_blocks)) {
            $blocks_search = array();
            foreach ($area_blocks as $key => $area_block) {
                $blocks_search[] = $area_block["id_block"];
            }
            $params["where_in"]["id"] = array_unique($blocks_search);
            $blocks = $this->Dynamic_blocks_model->get_blocks_list_by_id($params);
            foreach ($area_blocks as $key => $area_block) {
                $area_blocks[$key]["block_data"] = $blocks[$area_block["id_block"]];
            }

            $row = array();
            $row_width = 0;

            foreach ($area_blocks as $block) {
                $row_width += $block["width"];
                $row[] = $block;
                if ($row_width < 100) {
                    continue;
                }

                if ($row_width > 100) {
                    array_pop($row);
                    $count = count($row);

                    $row[$count - 1]["width"] += ( 100 - ($row_width - $block["width"]) );
                    if ($row[$count - 1]["width"] == 40) {
                        $row[$count - 1]["width"] = 30;
                    }
                    $layout[] = $row;
                    $row = array($block);
                    $row_width = $block["width"];
                } else {
                    $layout[] = $row;
                    $row = array();
                    $row_width = 0;
                }
            }
            if ($row_width) {
                $count = count($row);
                $row[$count - 1]["width"] += 100 - $row_width;
                $layout[] = $row;
            }
        }
        $this->template_lite->assign('layout', $layout);

        $presets_count = $this->Dynamic_blocks_model->get_presets_count();
        $this->template_lite->assign('presets_count', $presets_count);

        $page = isset($_SESSION["areas_list"]["page"]) ? $_SESSION["areas_list"]["page"] : 1;

        $this->system_messages->set_data('back_link', site_url() . "admin/dynamic_blocks/index/" . $page);
        $this->Menu_model->set_menu_active_item('admin_dynblocks_menu', 'areas_list_item');
        $this->system_messages->set_data('header', l('admin_header_area_layout', 'dynamic_blocks') . ": " . $area["name"]);
        $this->template_lite->view('layout');
    }

    /**
     * Save area layout
     * 
     * @param integer $area_id area identifier
     * @return void
     */
    public function save_layout($area_id)
    {
        $data = $this->input->post("data", true);
        $sort_index = 1;
        foreach ($data as $block_id => $block_width) {
            $this->Dynamic_blocks_model->save_area_block_sorter($block_id, $sort_index, $block_width);
            $sort_index++;
        }
        $this->system_messages->add_message('success', l('success_update_area_layout', 'dynamic_blocks'));
        redirect(site_url() . "admin/dynamic_blocks/area_layout/" . $area_id);
    }

    /**
     * Manage area presets
     * 
     * @param integer $area_id area identifier
     * @param integer $page page of results
     * @return void
     */
    public function area_preset($area_id, $page = 1)
    {
        $area = $this->Dynamic_blocks_model->get_area_by_id($area_id);
        $this->template_lite->assign('area', $area);

        $presets_count = $this->Dynamic_blocks_model->get_presets_count();
        if ($presets_count > 0) {
            $items_on_page = $this->pg_module->get_module_config('dynamic_blocks', 'admin_presets_per_page');
            $this->load->helper('sort_order');
            $page = get_exists_page_number($page, $presets_count, $items_on_page);

            $presets = $this->Dynamic_blocks_model->get_presets_list($page, $items_on_page);
            $this->template_lite->assign('presets', $presets);

            $this->load->helper('navigation');

            $url = site_url() . "admin/dynamic_blocks/area_preset/" . $area_id . "/";
            $page_data = get_admin_pages_data($url, $presets_count, $items_on_page, $page, 'briefPage');
            $this->template_lite->assign('page_data', $page_data);
        }

        $area_page = isset($_SESSION["areas_list"]["page"]) ? $_SESSION["areas_list"]["page"] : 1;
        $url = site_url() . "admin/dynamic_blocks/index/" . $area_page;

        $this->system_messages->set_data('back_link', $url);
        $this->Menu_model->set_menu_active_item('admin_dynblocks_menu', 'areas_list_item');
        $this->system_messages->set_data('header', l('admin_header_area_preset', 'dynamic_blocks') . ": " . $area["name"]);
        $this->template_lite->view('preset');
    }

    /**
     * Set area presets
     * 
     * @param integer $area_id area identifier
     * @return void
     */
    public function set_preset($area_id, $preset_id)
    {
        $this->Dynamic_blocks_model->set_area_preset($area_id, $preset_id);
        $this->system_messages->add_message('success', l('success_apply_area_preset', 'dynamic_blocks'));
        redirect(site_url() . "admin/dynamic_blocks/area_preset/" . $area_id);
    }

}
