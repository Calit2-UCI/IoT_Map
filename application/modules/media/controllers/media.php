<?php

/**
 * Media controller
 *
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @author Mikhail Chernov <mchernov@pilotgroup.net>
 * @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (РЎСЂ, 02 Р°РїСЂ 2010) $ $Author: mchernov $
 * */
Class Media extends Controller
{

    private $user_id = false;

    public function __construct()
    {
        parent::__construct();
        $this->load->model("Media_model");
        $this->user_id = intval($this->session->userdata('user_id'));
    }

    public function index($param = 'all', $index = true)
    {
        $param = trim(strip_tags($param));
        $order = trim(strip_tags($this->input->get_post('order', true)));
        $direction = trim(strip_tags($this->input->get_post('direction', true)));
        $media_sorter = array(
            "order" => $order ? $order : 'date_add',
            "direction" => $direction ? $direction : 'DESC',
            "links" => array(
                "date_add" => l('field_date_add', 'media'),
                "views" => l('field_views', 'media'),
                "comments_count" => l('field_comments_count', 'media'),
            )
        );
        $order_by = array($media_sorter['order'] => $media_sorter['direction']);
        $is_guest = $this->session->userdata("auth_type") != "user";

        $this->load->model('Menu_model');
        $this->load->helper('seo');
        $this->Menu_model->breadcrumbs_set_active(l('header_gallery', 'media'), rewrite_link('media', ''));
        $this->Menu_model->breadcrumbs_set_active(l($param, 'media'));

        $seo_data = array();

        if ($index) {
			$this->router->set_method($param);
            $seo_data['canonical'] = rewrite_link('media', $param, $seo_data, false, null, true);
        }

        $this->pg_seo->set_seo_data($seo_data);

        $this->template_lite->assign('gallery_param', $param);
        $this->template_lite->assign('media_sorter', $media_sorter);
        $this->template_lite->assign('order_by', $order_by);
        $this->template_lite->assign('albums', $this->Media_model->get_albums_select(0));
        $this->template_lite->assign("is_guest", $is_guest);
        $this->template_lite->view('gallery');
    }

    public function video()
    {
        $this->index('video', false);
    }

    public function photo()
    {
        $this->index('photo', false);
    }

    public function albums()
    {
        $this->index('albums', false);
    }

    public function favorites()
    {
        $this->index('favorites', false);
    }

    public function all()
    {
        $this->index('all', false);
    }

    public function ajax_get_gallery_list($param = 'all', $album_id = 0)
    {
        $album_id = intval($album_id);
        $param = trim(strip_tags($param));
        $order = trim(strip_tags($this->input->post('order')));
        $direction = trim(strip_tags($this->input->post('direction')));
        if (!$order) {
            $order = 'date_add';
        }
        $order_by[$order] = ($direction == 'asc') ? 'ASC' : 'DESC';
        $page = intval($this->input->post('page', true));

        if ($param == 'albums' && !$album_id) {
            $gallery = $this->Media_model->get_albums(0, $page);
        } else {
            $icons = $this->input->post('icons', true);
            $loaded_count = intval($this->input->post('loaded_count', true));
            $this->Media_model->format_likes_count = true;
            $gallery = $this->Media_model->get_gallery_list(count($icons), $param, $loaded_count, $album_id, $order_by);
            $this->Media_model->format_likes_count = false;
        }
        if (!empty($gallery['media'])) {
            foreach ($gallery['media'] as $i => $photo) {
                $gallery['media'][$i]['alt'] = l('text_media_photo', 'media', null, 'button', $photo);
            }
        }
        exit(json_encode($gallery));
    }

    private function _add_media_tpl($type)
    {
        $id_album = intval($this->input->post('id_album'));
        $this->load->model('media/models/Albums_model');
        $params['where']['id_user'] = $this->user_id;
        $user_albums = $this->Albums_model->get_albums_list($params);
        $this->template_lite->assign('id_album', $id_album);
        $this->template_lite->assign('user_albums', $user_albums);

        switch ($type) {
            case 'video':
                $this->load->model('Video_uploads_model');
                $media_config = $this->Video_uploads_model->get_config($this->Media_model->video_config_gid);
                $this->template_lite->assign('media_config', $media_config);
                $tpl = $this->template_lite->fetch('add_video');
                break;
            case 'image':
            default:
                $this->load->model('Uploads_model');
                $media_config = $this->Uploads_model->get_config($this->Media_model->file_config_gid);
                $this->template_lite->assign('media_config', $media_config);
                $tpl = $this->template_lite->fetch('add_photos');
                break;
        }
        return $tpl;
    }

    public function ajax_add_images()
    {
        exit($this->_add_media_tpl('image'));
    }

    public function ajax_add_video()
    {
        exit($this->_add_media_tpl('video'));
    }

    public function ajax_view_media($id, $user_id, $param = 'all', $album_id = 0)
    {
        $id = intval($id);
        $user_id = intval($user_id);
        $album_id = intval($album_id);
        $param = trim(strip_tags($param));
        $order = trim(strip_tags($this->input->post('order')));
        $direction = trim(strip_tags($this->input->post('direction')));
        if (!$order) {
            $order = 'date_add';
        }
        $order_by[$order] = ($direction == 'asc') ? 'ASC' : 'DESC';
        $filter_duplicate = intval($this->input->post('filter_duplicate', true));

        $media = $this->Media_model->get_media_by_id($id);

        $gallery_name = $this->input->post('gallery_name', true) ? trim(strip_tags($this->input->post('gallery_name', true))) : 'mediagallery';
        $is_access_permitted = $this->Media_model->is_access_permitted($id, $media);
        $selections = array();
        $this->load->model('Uploads_model');
        $upload_config = $this->Uploads_model->get_config($this->Media_model->file_config_gid);
        foreach ($upload_config['thumbs'] as $thumb_config) {
            $selections[$thumb_config['prefix']] = array(
                'width' => $thumb_config['width'],
                'height' => $thumb_config['height'],
            );
        }
        $this->template_lite->assign('selections', $selections);
        $this->template_lite->assign('gallery_name', $gallery_name);
        $this->template_lite->assign('media_id', $id);
        $this->template_lite->assign('param', $param);
        $this->template_lite->assign('album_id', $album_id);
        $this->template_lite->assign('rand', rand(0, 999999));
        exit($this->template_lite->fetch_final('view_media'));
    }

    public function ajax_edit_album($id)
    {
        $this->load->model('media/models/Albums_model');
        if (!$this->Albums_model->is_user_album_owner($id)) {
            exit(json_encode('error'));
        }
        $album = $this->Albums_model->get_album_by_id($id);
        $this->template_lite->assign('album', $album);
        exit($this->template_lite->fetch('edit_album'));
    }

    public function ajax_get_section_content()
    {
        $return = array('content' => '');
        $media_id = intval($this->input->post('id'));
        $section = trim($this->input->post('section'));
        $media = $this->Media_model->get_media_by_id($media_id);
        $is_access_permitted = $this->Media_model->is_access_permitted($media_id, $media);
        $is_user_media_user = ($media['id_user'] == $this->user_id);
        $is_user_media_owner = ($media['id_owner'] == $this->user_id);
        $this->template_lite->assign('is_user_media_owner', $is_user_media_owner);
        $this->template_lite->assign('is_user_media_user', $is_user_media_user);
        $this->template_lite->assign('is_access_permitted', $is_access_permitted);
        $this->template_lite->assign('media', $media);
        $this->_to_favorites($media);
        switch ($section) {
            case 'albums': {
                    $this->load->model('media/models/Media_album_model');
                    $this->load->model('media/models/Albums_model');
                    $albums = array();
                    if ($is_user_media_user) {
                        $media_in_album = $this->Media_album_model->get_albums_by_media_id($media_id);
                    } elseif ($is_access_permitted) {
                        $media_parent_id = $media['id_parent'] ? $media['id_parent'] : $media_id;
                        $parent_ids = $this->Media_model->get_parent_media_list_ids($media_parent_id);
                        $media_in_album = $this->Media_album_model->get_albums_by_media_id(array_merge($parent_ids, (array) $media_id, (array) $media_parent_id));
                    }
                    $param['where']['id_user'] = $this->user_id;
                    $albums['user'] = ($this->user_id) ? $this->Albums_model->get_albums_list($param) : array();
                    $param['where']['id_user'] = '0';
                    $albums['common'] = $this->Albums_model->get_albums_list($param, null, null, null, null, true, $this->pg_language->current_lang_id);

                    $this->template_lite->assign('media_albums', $albums);
                    $this->template_lite->assign('media_in_album', $media_in_album);

                    $return['content'] = $this->template_lite->fetch('section_albums');
                    break;
                }
        }
        exit(json_encode($return));
    }

    private function _to_favorites($media)
    {
        $this->load->model('media/models/Albums_model');
        $this->load->model('media/models/Media_album_model');
        $media_parent_id = $media['id_parent'] ? $media['id_parent'] : $media['id'];
        $parent_ids = $this->Media_model->get_parent_media_list_ids($media_parent_id);
        $albums = $this->Media_album_model->get_albums_by_media_id($parent_ids);
        $default_album = $this->Albums_model->get_default($this->user_id);
        $this->template_lite->assign('default_album', $default_album);
        $this->template_lite->assign('in_favorites', in_array($default_album['id'], $albums));
    }

    public function ajax_get_media_content($media_id, $gallery_param = 'all', $album_id = 0)
    {
        $return = array('content' => '', 'position_info' => '', 'media_type' => '', 'views_num' => '');
        $media_id = intval($media_id);
        $album_id = intval($album_id);
        $place = trim(strip_tags($this->input->post('place', true)));
        $gallery_param = trim(strip_tags($gallery_param));
        $without_position = intval($this->input->post('without_position'));

        $order = trim(strip_tags($this->input->post('order')));
        $direction = trim(strip_tags($this->input->post('direction')));
        if (!$order) {
            $order = 'date_add';
        }
        $order_by[$order] = ($direction == 'asc') ? 'ASC' : 'DESC';
        $filter_duplicate = ($place == 'site_gallery') ? 1 : intval($this->input->post('filter_duplicate', true));

        $media = $this->Media_model->get_media_by_id($media_id, true, true);
        $user_id = ($place == 'site_gallery') ? 0 : $media['id_user'];
        $is_user_media_owner = ($media['id_owner'] == $this->user_id);
        $is_user_media_user = ($media['id_user'] == $this->user_id);
        $is_access_permitted = $this->Media_model->is_access_permitted($media_id, $media);
        $date_formats['date_format'] = $this->pg_date->get_format('date_literal', 'st');
        $date_formats['date_time_format'] = $this->pg_date->get_format('date_time_literal', 'st');

        if ($is_access_permitted) {
            $this->Media_model->increment_media_views($media_id);
            $this->template_lite->assign('media', $media);
            $return['views_num'] = $media['views'] + 1;
        }

        $this->_to_favorites($media);

        $this->template_lite->assign('is_user_media_owner', $is_user_media_owner);
        $this->template_lite->assign('is_user_media_user', $is_user_media_user);
        $this->template_lite->assign('is_access_permitted', $is_access_permitted);
        $this->template_lite->assign('date_formats', $date_formats);

        $aviary_post_data = array(
            'type' => $media['type_id'],
            'id' => $media_id,
            'user_id' => $user_id,
        );
        $this->template_lite->assign('aviary_post_data', $aviary_post_data);

        $rand_param = intval($this->input->post('rand_param'));
        if ($rand_param) {
            $this->template_lite->assign('vers', '?' . $rand_param);
        }

        $return['content'] = $this->template_lite->fetch_final('media_content');
        if (!$without_position) {
            $return['position_info'] = $this->Media_model->get_media_position_info($media_id, $gallery_param, $album_id, $user_id, true, $order_by, $filter_duplicate);
        }
        $return['media_type'] = $media['upload_gid'];
        exit(json_encode($return));
    }

    public function ajax_add_media_in_album($media_id, $album_id)
    {
        $return = array('errors' => '', 'status' => 0);
        $media_id = intval($media_id);
        $album_id = intval($album_id);

        $media = $this->Media_model->get_media_by_id($media_id);
        if (!$this->Media_model->is_access_permitted($media_id, $media)) {
            exit(json_encode($return));
        }
        $this->load->model('media/models/Media_album_model');
        $this->load->model('media/models/Albums_model');

        $album = $this->Albums_model->get_album_by_id($album_id);
        if (!$album) {
            exit(json_encode($return));
        }
        $is_album_owner = ($album['id_user'] == $this->user_id);
        $is_common_album = ($album['id_user'] == 0);

        if ($media['id_owner'] == $this->user_id && $media['id_parent'] == 0) { //user is media owner && user
            if ($is_album_owner || $is_common_album) {
                $add_status = $this->Media_album_model->add_media_in_album($media_id, $album_id);
            }
        } elseif ($media['id_owner'] == $this->user_id && $media['id_parent'] != 0) { //user is media owner && !user
            if ($is_album_owner || $is_common_album) {
                $add_status = $this->Media_album_model->add_media_in_album($media['id_parent'], $album_id); //original media
            }
        } elseif ($media['id_owner'] != $this->user_id && $media['id_user'] == $this->user_id) { //user is media user && !owner
            if ($is_album_owner) {
                $add_status = $this->Media_album_model->add_media_in_album($media_id, $album_id);
            }
        } elseif ($media['id_owner'] != $this->user_id && $media['id_user'] != $this->user_id && $media['id_parent'] != 0) { //foreign media on foreign gallery
            if ($is_album_owner) {
                $param['where']['id_user'] = $this->user_id;
                $param['where']['id_parent'] = $media['id_parent'];
                $m = $this->Media_model->get_media(null, null, null, $param);
                if (count($m)) {
                    $add_status = $this->Media_album_model->add_media_in_album($m[0]['id'], $album_id);
                } else {
                    $new_media_id = $this->Media_model->copy_media($media_id);
                    $add_status = $this->Media_album_model->add_media_in_album($new_media_id, $album_id);
                }
            }
        } else {
            if ($is_album_owner) {
                $param['where']['id_user'] = $this->user_id;
                $param['where']['id_parent'] = $media_id;
                $m = $this->Media_model->get_media(null, null, null, $param);
                if (count($m)) {
                    $add_status = $this->Media_album_model->add_media_in_album($m[0]['id'], $album_id);
                } else {
                    $new_media_id = $this->Media_model->copy_media($media_id);
                    $add_status = $this->Media_album_model->add_media_in_album($new_media_id, $album_id);
                }
            }
        }
        if (!empty($add_status['status'])) {
            $return['status'] = 1;
        } else {
            $return['status'] = 0;
            $return['errors'] = !empty($add_status['error']) ? $add_status['error'] : l('error_add_in_ablum', 'media');
        }
        exit(json_encode($return));
    }

    public function ajax_delete_media_from_album($media_id, $album_id)
    {
        $return = array('errors' => '', 'status' => 0);
        $this->load->model('media/models/Albums_model');
        $album = $this->Albums_model->get_album_by_id($album_id);
        if (!$album) {
            exit(json_encode($return));
        }
        $is_album_owner = ($album['id_user'] == $this->user_id);
        $is_common_album = ($album['id_user'] == 0);

        if ($is_album_owner || $is_common_album) {
            $this->load->model('media/models/Media_album_model');
            $this->load->model('Media_model');
            $is_user_media_user = $this->Media_model->is_user_media_user($media_id);
            if ($is_user_media_user) {
                $this->Media_album_model->delete_media_from_album($media_id, $album_id);
            } else {
                $media = $this->Media_model->get_media_by_id($media_id);
                if (!$media) {
                    exit(json_encode($return));
                }
                if ($media['id_parent']) {
                    $media_id = $media['id_parent'];
                }
                $params['where']['id_parent'] = $media_id;
                $params['where']['id_user'] = $this->user_id;
                $media_list = $this->Media_model->get_media(null, null, null, $params);
                if (isset($media_list[0]['id'])) {
                    $this->Media_album_model->delete_media_from_album($media_list[0]['id'], $album_id);
                } else {
                    unset($params['where']['id_parent']);
                    $params['where']['id'] = $media_id;
                    $media_list = $this->Media_model->get_media(null, null, null, $params);
                    if (isset($media_list[0]['id'])) {
                        $this->Media_album_model->delete_media_from_album($media_list[0]['id'], $album_id);
                    }
                }
            }
        }
        $return['status'] = 1;
        exit(json_encode($return));
    }

    public function ajax_save_description($photo_id)
    {
        $return = array('errors' => '', 'status' => 0);

        $photo_id = intval($photo_id);
        if (!$this->Media_model->is_user_media_user($photo_id)) {
            exit(json_encode($return));
        }

        if ($_POST) {
            $post_data = array(
                "fname" => $this->input->post("fname", true),
                "description" => $this->input->post("description", true),
            );
            $validate_data = $this->Media_model->validate_image($post_data);
            if (empty($validate_data["errors"])) {
                $save_data = $this->Media_model->save_image($photo_id, $validate_data["data"]);
                $return["status"] = 1;
            } else {
                $return["errors"] = $validate_data["errors"];
                $return["status"] = 0;
            }
            if (!empty($save_data['errors'])) {
                $return["errors"] += $save_data['errors'];
                $return["status"] = 0;
            }
        }
        exit(json_encode($return));
    }

    public function ajax_save_album($mode = 'small', $album_id = null)
    {
        $return = array('errors' => '', 'status' => 0, 'data' => '');
        $this->load->model('media/models/Albums_model');
        if ($_POST) {
            if ($mode == 'small') {
                $post_data = array(
                    "name" => $this->input->post("name", true),
                    "permissions" => 1,
                );
            } else {
                $post_data = array(
                    "name" => $this->input->post("name", true),
                    "permissions" => $this->input->post("permissions", true),
                    "description" => $this->input->post("description", true),
                );
            }
            $validate_data = $this->Albums_model->validate_album($post_data);
            $validate_data['data']["id_album_type"] = $this->Media_model->album_type_id;
            $langs = array();
            foreach ($this->pg_language->languages as $lang_id => $lang_data) {
                $langs[$lang_id] = $validate_data['data']['name'];
            }
            if (empty($validate_data["errors"])) {
                $new_album_id = $this->Albums_model->save($album_id, $validate_data['data'], $langs);
                if ($new_album_id) {
                    $return['status'] = 1;
                    $return['data']['album_id'] = $new_album_id;
                    $return['data']['albums_select'] = $this->Media_model->get_albums_select($this->user_id);
                    $return['data']['id_user'] = $this->user_id;
                }
            } else {
                $return['errors'] = $validate_data["errors"];
            }
        } else {
            $return['errors'] = l('no_data_sended', 'media');
        }
        exit(json_encode($return));
    }

    public function ajax_save_permissions($photo_id)
    {
        $return = array('errors' => '', 'status' => 0);

        $photo_id = intval($photo_id);
        if (!$this->Media_model->is_user_media_owner($photo_id)) {
            exit(json_encode($return));
        }

        if ($_POST) {
            $post_data = array(
                "permissions" => $this->input->post("permissions", true),
            );
            $validate_data = $this->Media_model->validate_image($post_data);
            if (empty($validate_data["errors"])) {
                $save_data = $this->Media_model->save_image($photo_id, $validate_data["data"]);
                $this->Media_model->update_children_permissions($photo_id, $validate_data["data"]["permissions"]);
                $return["status"] = 1;
            } else {
                $return["errors"] = $validate_data["errors"];
                $return["status"] = 0;
            }
            if (!empty($save_data['errors'])) {
                $return["errors"] += $save_data['errors'];
                $return["status"] = 0;
            }
        }
        exit(json_encode($return));
    }

    public function save_image()
    {
        $return = array('errors' => array(), 'warnings' => array(), 'name' => '');

        if ($_POST) {
            $post_data = array(
                "permissions" => $this->input->post("permissions", true),
                "description" => $this->input->post("description", true),
            );
        }
        $validate_data = $this->Media_model->validate_image($post_data, 'multiUpload');
        $validate_data['data']["id_user"] = $this->user_id;
        $validate_data['data']["id_owner"] = $this->user_id;
        $validate_data['data']["type_id"] = $this->Media_model->album_type_id;
        $validate_data['data']["upload_gid"] = $this->Media_model->file_config_gid;

        if (empty($validate_data["errors"])) {
            $this->load->model('Moderation_model');
            $validate_data['data']['status'] = intval($this->Moderation_model->get_moderation_type_status($this->Media_model->moderation_type));
            $mtype = $this->Moderation_model->get_moderation_type($this->Media_model->moderation_type);
            $save_data = $this->Media_model->save_image(null, $validate_data["data"], 'multiUpload', intval($mtype['mtype']));
            $is_moderated = $this->Moderation_model->add_moderation_item($this->Media_model->moderation_type, $save_data['id']);
            if ($is_moderated) {
                $return['warnings'][] = l('file_uploaded_and_moderated', 'media');
            }

            $id_album = intval($this->input->post('album_id'));
            if ($id_album) {
                $this->load->model('media/models/Albums_model');
                $is_user_can_add = $this->Albums_model->is_user_can_add_to_album($id_album);
                if ($is_user_can_add['status']) {
                    $this->load->model('media/models/Media_album_model');
                    $add_status = $this->Media_album_model->add_media_in_album($save_data['id'], $id_album);
                }
            }
        }
        $return["form_error"] = $validate_data['form_error'];

        if (!empty($validate_data['errors'])) {
            $return['errors'] = $validate_data['errors'];
        }
        if (empty($save_data['errors'])) {
            if (!empty($data['file'])) {
                $return['name'] = $save_data['file'];
            }
        } else {
            $return['errors'][] = $save_data['errors'];
        }
        if (!empty($is_user_can_add['error'])) {
            $return['warnings'][] = $is_user_can_add['error'];
        }

        exit(json_encode($return));
    }

    public function save_video()
    {
        $return = array('errors' => array(), 'warnings' => array(), 'name' => '');
        //print_r($_POST);
        //exit;
        if ($_POST) {
            $post_data = array(
                "permissions" => $this->input->post("permissions", true),
                "description" => $this->input->post("description", true),
                "embed_code" => $this->input->post("embed_code", true),
            );
        }
        $validate_data = $this->Media_model->validate_video($post_data, 'videofile');
        $validate_data['data']["id_user"] = $this->user_id;
        $validate_data['data']["id_owner"] = $this->user_id;
        $validate_data['data']["type_id"] = $this->Media_model->album_type_id;
        $validate_data['data']["upload_gid"] = $this->Media_model->video_config_gid;
        if (empty($validate_data["errors"])) {
            $this->load->model('Moderation_model');
            $videofile = (!empty($validate_data['data']['media_video']) && $validate_data['data']['media_video'] === 'embed') ? '' : 'videofile';
            $validate_data['data']['status'] = intval($this->Moderation_model->get_moderation_type_status($this->Media_model->moderation_type));
            $mtype = $this->Moderation_model->get_moderation_type($this->Media_model->moderation_type);
            $create_event = !intval($mtype['mtype']);
            $save_data = $this->Media_model->save_video(null, $validate_data["data"], $videofile, $create_event);
            $is_moderated = $this->Moderation_model->add_moderation_item($this->Media_model->moderation_type, $save_data['id']);
            if ($is_moderated) {
                $return['warnings'][] = l('file_uploaded_and_moderated', 'media');
            }

            $id_album = intval($this->input->post('id_album'));
            if ($id_album) {
                $this->load->model('media/models/Albums_model');
                $is_user_can_add = $this->Albums_model->is_user_can_add_to_album($id_album);
                if ($is_user_can_add['status']) {
                    $this->load->model('media/models/Media_album_model');
                    $this->Media_album_model->add_media_in_album($save_data['id'], $id_album);
                }
            }
        }
        $return['form_error'] = $validate_data['form_error'];

        if (!empty($validate_data['errors'])) {
            $return['errors'] = $validate_data['errors'];
        }
        if (empty($save_data['errors'])) {
            if (!empty($data['file'])) {
                $return['name'] = $save_data['file'];
            }
        } else {
            $return['errors'][] = $save_data['errors'];
        }
        if (!empty($is_user_can_add['error'])) {
            $return['warnings'][] = $is_user_can_add['error'];
        }

        exit(json_encode($return));
    }

    public function ajax_get_list($user_id, $param = 'all', $page = 1, $album_id = 0)
    {
        $order = trim(strip_tags($this->input->post('order')));
        $direction = trim(strip_tags($this->input->post('direction')));
        if (!$order) {
            $order = 'date_add';
        }
        $order_by[$order] = ($direction == 'asc') ? 'ASC' : 'DESC';

        if ($param == 'albums' && !$album_id) {
            $albums = $this->Media_model->get_albums($user_id, $page);
            $albums['albums_select'] = $this->Media_model->get_albums_select($user_id);
            echo json_encode($albums);
        } else {
            echo json_encode($this->Media_model->get_list($user_id, $param, $page, $album_id, true, $order_by));
        }
    }

    public function ajax_delete_media($media_id)
    {
        $return = array('status' => 0, 'message' => '', 'errors' => array());
        $media_id = intval($media_id);
        if ($this->Media_model->is_user_media_user($media_id)) {
            $result = $this->Media_model->delete_media($media_id);
            if (!empty($result['errors'])) {
                $return['status'] = 0;
                $return['message'] = $result['errors'];
            } else {
                $return['status'] = 1;
                $return['message'] = l('success_delete_media', 'media');
            }
        }
        exit(json_encode($return));
    }

    public function ajax_delete_album($id_album = 0)
    {
        $return = array('status' => 0, 'message' => '');
        $id_album = intval($id_album);
        $this->load->model('media/models/Albums_model');
        if ($id_album && $this->Albums_model->is_user_album_owner($id_album)) {
            $this->Albums_model->delete_album($id_album);
            $return['status'] = 1;
            $return['message'] = l('success_delete_media', 'media');
            $return['data']['albums_select'] = $this->Media_model->get_albums_select($this->user_id);
            $return['data']['id_user'] = $this->user_id;
        }
        exit(json_encode($return));
    }

    /**
     * Recrop upload
     * @param integer $upload_id upload identifier
     * @param string $thumb_prefix thumb prefix
     */
    public function ajax_recrop($upload_id, $thumb_prefix = '')
    {
        $result = array('status' => 1, 'errors' => array(), 'msg' => array(), 'data' => array());
        $recrop_data['x1'] = intval($this->input->post('x1', true));
        $recrop_data['y1'] = intval($this->input->post('y1', true));
        $recrop_data['width'] = intval($this->input->post('width', true));
        $recrop_data['height'] = intval($this->input->post('height', true));
        if (!$thumb_prefix) {
            $thumb_prefix = trim(strip_tags($this->input->post('prefix', true)));
        }

        $media = $this->Media_model->get_media_by_id($upload_id);
        if ($media && $thumb_prefix && $media['id_owner'] == $this->user_id) {
            $this->load->model('Uploads_model');
            $this->Uploads_model->recrop_upload($this->Media_model->file_config_gid, $media['id_owner'], $media['mediafile'], $recrop_data, $thumb_prefix);
            $result['data']['img_url'] = $media['media']['mediafile']['thumbs'][$thumb_prefix];
            $result['data']['rand'] = rand(0, 999999);
            $result['msg'][] = l('image_update_success', 'media');
        } else {
            $result['status'] = 0;
            $result['errors'][] = 'access denied';
        }

        exit(json_encode($result));
    }

    public function ajax_get_recent_media()
    {
        $params['count'] = intval($this->input->post('count', true));
        $params['upload_gid'] = trim(strip_tags($this->input->post('upload_gid', true)));
        $data = $this->Media_model->get_recent_media($params['count'], $params['upload_gid']);
        if (!empty($data['media'])) {
            $media_count = 16 - count($data['media']);
            switch ($media_count) {
                case 13: $recent_thumb['name'] = 'middle';
                    $recent_thumb['width'] = '82px';
                    break;
                case 14: $recent_thumb['name'] = 'big';
                    $recent_thumb['width'] = '125px';
                    break;
                case 15: $recent_thumb['name'] = 'great';
                    $recent_thumb['width'] = '255px';
                    break;
                default: $recent_thumb['name'] = 'small';
                    $recent_thumb['width'] = '60px';
            }
            $this->template_lite->assign('recent_photos_data', $data);
            $this->template_lite->assign('recent_thumb', $recent_thumb);
            exit($this->template_lite->fetch('helper_recent_media', 'user', 'media'));
        }
        return false;
    }

    /**
     * Rotate upload
     * 
     * @param integer $upload_id upload identifier
     * @param integer/string $angle rotate angle
     * @return void
     */
    public function ajax_rotate($upload_id, $angle = 90)
    {
        $result = array('status' => 1, 'errors' => array(), 'msg' => array(), 'data' => array());

        $media = $this->Media_model->get_media_by_id($upload_id);
        if ($angle < 0) {
            $angle += 360;
        }

        if ($media && $media['id_owner'] == $this->user_id) {
            $this->load->model('Uploads_model');
            $this->Uploads_model->rotate_upload($this->Media_model->file_config_gid, $media['id_owner'], $media['mediafile'], $angle);
            $result['data']['img_url'] = $media['media']['mediafile']['file_url'];
            $result['data']['thumbs'] = $media['media']['mediafile']['thumbs'];
            $result['data']['rand'] = rand(0, 999999);
            $result['msg'][] = l('image_update_success', 'media');
        } else {
            $result['status'] = 0;
            $result['errors'][] = 'access denied';
        }

        exit(json_encode($result));
    }

}
