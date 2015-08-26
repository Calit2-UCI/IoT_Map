<?php

/**
 * Media admin side controller
 *
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @author Mikhail Chernov <mchernov@pilotgroup.net>
 * @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: mchernov $
 * */
Class Admin_Media extends Controller
{
    /**
     * link to CodeIgniter object
     * @var object
     */

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Menu_model');
        $this->Menu_model->set_menu_active_item('admin_menu', 'media_menu_item');
        $this->load->model("Media_model");
    }

    public function index($param = 'photo', $page = 1)
    {
        if ($param == 'album') {
            $this->albums_list($page);
        } else {
            $this->media_list($param, $page);
        }
    }

    public function user_media($user_id = null, $param = 'photo', $page = 1)
    {
        if (!$user_id) {
            $url = site_url() . "admin/media/index/" . $param . "";
            redirect($url);
        }

        $where = array();
        switch ($param) {
            case 'photo' : $where['where']['upload_gid'] = $this->Media_model->file_config_gid;
                break;
            case 'video' : $where['where']['upload_gid'] = $this->Media_model->video_config_gid;
                break;
        }
        $where['where']['id_user'] = $user_id;

        $this->load->helper('sort_order');
        $items_on_page = $this->pg_module->get_module_config('media', 'items_per_page');
        $media_count = $this->Media_model->get_media_count($where);
        $page = get_exists_page_number($page, $media_count, $items_on_page);

        $order_by = array('date_add' => 'DESC');
        $this->Media_model->format_user = true;
        $media = $this->Media_model->get_media($page, $items_on_page, $order_by, $where);
        $this->template_lite->assign('media', $media);

        $this->load->helper("navigation");
        $page_data = get_admin_pages_data(site_url() . "admin/media/user_media/" . $user_id . '/' . $param . '/', $media_count, $items_on_page, $page, 'briefPage');
        $page_data["date_format"] = $this->pg_date->get_format('date_time_literal', 'st');
        $this->template_lite->assign('page_data', $page_data);

        $this->template_lite->assign('user_id', $user_id);
        $this->template_lite->assign('param', $param);
        $this->load->model('Field_editor_model');
        $this->load->model('Users_model');
        $this->Field_editor_model->initialize($this->Users_model->form_editor_type);
        $sections = $this->Field_editor_model->get_section_list();
        $sections_gids = array_keys($sections);
        $this->template_lite->assign('sections', $sections);

        $cur_set = $_SESSION["users_list"];
        $back_url = site_url() . "admin/users/index/{$cur_set["filter"]}/{$cur_set['user_type']}/{$cur_set["order"]}/{$cur_set["order_direction"]}/{$cur_set["page"]}";
        $this->template_lite->assign('back_url', $back_url);

        $this->system_messages->set_data('header', l('admin_header_users_edit', 'users'));
        $this->template_lite->view('user_media_list');
    }

    public function media_list($param = 'photo', $page = 1)
    {
        $where = array();
        switch ($param) {
            case 'photo' : $where['where']['upload_gid'] = $this->Media_model->file_config_gid;
                break;
            case 'video' : $where['where']['upload_gid'] = $this->Media_model->video_config_gid;
                break;
        }

        $this->load->helper('sort_order');
        $items_on_page = $this->pg_module->get_module_config('media', 'items_per_page');
        $media_count = $this->Media_model->get_media_count($where);
        $page = get_exists_page_number($page, $media_count, $items_on_page);

        $order_by = array('date_add' => 'DESC');
        $this->Media_model->format_user = true;
        $media = $this->Media_model->get_media($page, $items_on_page, $order_by, $where);
        $this->template_lite->assign('media', $media);

        $this->load->helper("navigation");
        $page_data = get_admin_pages_data(site_url() . "admin/media/index/" . $param . '/', $media_count, $items_on_page, $page, 'briefPage');
        $this->template_lite->assign('page_data', $page_data);

        $this->template_lite->assign('param', $param . "/" . $page);

        $this->Menu_model->set_menu_active_item('media_menu_item', $param . '_list_item');
        $this->system_messages->set_data('header', l('admin_header_media_list', 'media'));
        $this->template_lite->view('media_list');
    }

    public function albums_list($page)
    {
        $this->load->model('media/models/Albums_model');

        $this->load->helper('sort_order');
        $items_on_page = $this->pg_module->get_module_config('media', 'items_per_page');
        $albums_count = $this->Albums_model->get_albums_count();
        $page = get_exists_page_number($page, $albums_count, $items_on_page);

        $order_by = array('date_add' => 'DESC');
        $this->Albums_model->format_user = true;
        $lang_id = $this->pg_language->current_lang_id;
        $albums = $this->Albums_model->get_albums_list(null, null, null, $page, $items_on_page, true, $lang_id);
        $this->template_lite->assign('albums', $albums);

        $this->load->helper("navigation");
        $page_data = get_admin_pages_data(site_url() . 'admin/media/index/album/', $albums_count, $items_on_page, $page, 'briefPage');
        $page_data["date_format"] = $this->pg_date->get_format('date_time_literal', 'st');

        $this->template_lite->assign('page_data', $page_data);
        $this->Menu_model->set_menu_active_item('media_menu_item', 'album_list_item');
        $this->system_messages->set_data('header', l('admin_header_album_list', 'media'));
        $this->template_lite->view('albums_list');
    }

    public function ajax_view_media($media_id = null)
    {
        
    }

    public function ajax_view_album($album_id = null)
    {
        
    }

    public function delete_media($media_id = null, $message = true)
    {
        if (!empty($media_id)) {
            //	print_r($media_id);
            $result = $this->Media_model->delete_media($media_id);
            if (!empty($result['errors']) && $message) {
                $this->system_messages->add_message('error', $result['errors']);
            } elseif ($message) {
                $this->system_messages->add_message('success', l('success_delete_media', 'media'));
            }
        }
        if ($message) {
            redirect($_SERVER["HTTP_REFERER"]);
        } else {
            return;
        }
    }

    public function delete_album($album_id = null)
    {
        if (!empty($album_id)) {
            $this->load->model('media/models/Albums_model');
            $this->Albums_model->delete_album($album_id);
            $this->system_messages->add_message('success', l('success_delete_album', 'media'));
        }
        redirect($_SERVER["HTTP_REFERER"]);
    }

    public function common_albums($page = 1)
    {
        $this->load->model('media/models/Albums_model');

        $where['where']['id_user'] = 0;

        $this->load->helper('sort_order');
        $items_on_page = $this->pg_module->get_module_config('media', 'items_per_page');
        $albums_count = $this->Albums_model->get_albums_count($where);
        $page = get_exists_page_number($page, $albums_count, $items_on_page);

        $order_by = array('date_add' => 'DESC');
        $this->Albums_model->format_user = true;
        $lang_id = $this->pg_language->current_lang_id;
        $albums = $this->Albums_model->get_albums_list($where, null, null, $page, $items_on_page, true, $lang_id);
        $this->template_lite->assign('albums', $albums);

        $this->load->helper("navigation");
        $page_data = get_admin_pages_data(site_url() . 'admin/media/common_albums/', $albums_count, $items_on_page, $page, 'briefPage');
        $page_data["date_format"] = $this->pg_date->get_format('date_time_literal', 'st');

        $this->template_lite->assign('page_data', $page_data);
        $this->Menu_model->set_menu_active_item('media_menu_item', 'common_albums_item');
        $this->system_messages->set_data('header', l('admin_header_common_albums', 'media'));
        $this->template_lite->view('common_albums');
    }

    public function album_edit($album_id = null)
    {
        $this->load->model('media/models/Albums_model');
        $errors = false;
        $data = array();

        if (!empty($album_id)) {
            $data = $this->Albums_model->get_album_by_id($album_id, $this->pg_language->current_lang_id, $this->pg_language->languages);
        }

        foreach ($this->pg_language->languages as $lang_id => $lang_data) {
            $validate_lang[$lang_id] = $data['lang_' . $lang_id];
        }

        if (!empty($validate_lang)) {
            $this->template_lite->assign('validate_lang', $validate_lang);
        }

        $this->template_lite->assign('languages', $this->pg_language->languages);
        $this->template_lite->assign('languages_count', count($this->pg_language->languages));
        $this->template_lite->assign('cur_lang', $this->pg_language->current_lang_id);

        if ($this->input->post('btn_save')) {
            $post_data = array(
                "name" => $this->input->post("name", true),
                "description" => $this->input->post("description", true),
            );

            $langs = $this->input->post("langs", true);
            if ($post_data['name'] == '') {
                $post_data['name'] = $langs[$this->pg_language->current_lang_id];
            }

            foreach ($langs as $key => $value) {
                if ($value == '') {
                    $langs[$key] = $post_data['name'];
                }
            }

            $validate_data = $this->Albums_model->validate_album($post_data);
            $validate_data['data']["id_album_type"] = $this->Media_model->album_type_id;
            $validate_data['data']['id_user'] = 0;

            if (!empty($validate_data["errors"])) {
                $errors = $validate_data["errors"];
                $data = array_merge($data, $validate_data["data"]);
            } else {
                $this->Albums_model->save($album_id, $validate_data['data'], $langs);

                $this->system_messages->add_message('success', l('success_album_save', 'media'));
                redirect(site_url() . "admin/media/common_albums");
            }
        }

        $this->template_lite->assign('data', $data);

        if (!empty($errors)) {
            $this->system_messages->add_message('error', $errors);
        }

        $this->Menu_model->set_menu_active_item('media_menu_item', 'common_albums_item');
        $this->system_messages->set_data('header', l('admin_common_album_edit', 'media'));
        $this->template_lite->view('album_edit');
    }

    public function ajax_confirm_select()
    {
        return $this->template_lite->view('ajax_delete_select_block');
    }

    public function ajax_delete_media()
    {
        $media_id = $this->input->post("file_ids", true);
        if (!empty($media_id)) {
            foreach ($media_id as $object_id) {
                $this->delete_media($object_id, false);
            }
        }

        $this->system_messages->add_message('success', l('success_delete_media', 'media'));
    }

    public function mark_adult_media($file_id = null)
    {
        if (!empty($file_id)) {
            $this->Media_model->mark_adult($file_id);
            $this->system_messages->add_message('success', l('success_mark_adult', 'media'));
        }
        redirect($_SERVER["HTTP_REFERER"]);
    }

    public function unmark_adult_media($file_id = null)
    {
        if (!empty($file_id)) {
            $this->Media_model->unmark_adult($file_id);
            $this->system_messages->add_message('success', l('success_unmark_adult', 'media'));
        }
        redirect($_SERVER["HTTP_REFERER"]);
    }

    public function ajax_mark_adult_select()
    {
        $media_id = $this->input->post("file_ids", true);
        if (!empty($media_id)) {
            foreach ($media_id as $object_id) {
                $this->Media_model->mark_adult($object_id);
            }
            $this->system_messages->add_message('success', l('success_mark_adult', 'media'));
        }
    }

    public function ajax_unmark_adult_select()
    {
        $media_id = $this->input->post("file_ids", true);
        if (!empty($media_id)) {
            foreach ($media_id as $object_id) {
                $this->Media_model->unmark_adult($object_id);
            }
            $this->system_messages->add_message('success', l('success_unmark_adult', 'media'));
        }
    }

}
