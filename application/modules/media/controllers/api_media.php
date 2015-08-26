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
Class Api_Media extends Controller
{

    private $user_id = false;

    public function __construct()
    {
        parent::__construct();
        $this->load->model("Media_model");
        $this->user_id = intval($this->session->userdata('user_id'));
    }

    public function get_media_content()
    {
        $data = array('content' => '', 'position_info' => '', 'media_type' => '');

        $media_id = filter_input(INPUT_POST, 'media_id', FILTER_VALIDATE_INT);
        $media = $this->Media_model->get_media_by_id($media_id, true, true);

        $data['is_user_media_owner'] = ($media['id_owner'] == $this->_user_id);
        $data['is_user_media_user'] = ($media['id_user'] == $this->_user_id);
        $data['date_formats']['date_format'] = $this->pg_date->get_format('date_literal', 'st');
        $data['date_formats']['date_time_format'] = $this->pg_date->get_format('date_time_literal', 'st');

        $data['is_access_permitted'] = $this->Media_model->is_access_permitted($media_id, $media);
        if ($data['is_access_permitted']) {
            $this->Media_model->increment_media_views($media_id);
            $data['media'] = $media;
        }

        if (!filter_input(INPUT_POST, 'without_position', FILTER_VALIDATE_BOOLEAN)) {
            $place = filter_input(INPUT_POST, 'place', FILTER_SANITIZE_STRING);
            $filter_duplicate = ($place == 'site_gallery') ? true : intval(filter_input(INPUT_POST, 'filter_duplicate', FILTER_VALIDATE_BOOLEAN));
            $user_id = ($place == 'site_gallery') ? 0 : $media['id_user'];
            $order = filter_input(INPUT_POST, 'order', FILTER_SANITIZE_STRING);
            if (!$order) {
                $order = 'date_add';
            }
            $direction = filter_input(INPUT_POST, 'direction', FILTER_SANITIZE_STRING);
            $order_by[$order] = ($direction == 'asc') ? 'ASC' : 'DESC';
            $gallery_param = filter_input(INPUT_POST, 'gallery_param', FILTER_SANITIZE_STRING) || 'all';
            $album_id = filter_input(INPUT_POST, 'album_id', FILTER_VALIDATE_INT) || 0;
            $data['position_info'] = $this->Media_model->get_media_position_info($media_id, $gallery_param, $album_id, $user_id, true, $order_by, $filter_duplicate);
        }
        $data['media_type'] = $media['upload_gid'];

        $this->set_api_content('data', $data);
    }

    public function get_list()
    {
        $user_id = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
        if (!$user_id) {
            $user_id = $this->user_id;
        }
        $param = filter_input(INPUT_POST, 'param', FILTER_SANITIZE_STRING);
        $page = filter_input(INPUT_POST, 'page', FILTER_VALIDATE_INT);
        $album_id = filter_input(INPUT_POST, 'album_id', FILTER_VALIDATE_INT);
        $order = filter_input(INPUT_POST, 'order', FILTER_SANITIZE_STRING);
        $direction = filter_input(INPUT_POST, 'direction', FILTER_SANITIZE_STRING);
        $get_likes = filter_input(INPUT_POST, 'get_likes', FILTER_VALIDATE_BOOLEAN);
        if (!$order) {
            $order = 'date_add';
        }
        $order_by[$order] = ('asc' === $direction) ? 'ASC' : 'DESC';
        if ('albums' === $param && !$album_id) {
            $albums = $this->Media_model->get_albums($user_id, $page);
            $albums['albums_select'] = $this->Media_model->get_albums_select($user_id);
            $this->set_api_content('data', $albums);
        } else {
            $list = $this->Media_model->get_list($user_id, $param, $page, $album_id, true, $order_by);
            if ($get_likes && $this->pg_module->is_module_installed('likes')) {
                $this->getLikes($list);
            }
            $this->set_api_content('data', $list);
        }
    }

    private function getLikes(&$media_list)
    {
        $like_ids = array();
        foreach ($media_list['media'] as $media) {
            $like_ids[] = 'media' . $media['id'];
        }
        $this->load->model('Likes_model');
        $likes = $this->Likes_model->get_count($like_ids);
        if ($this->user_id) {
            $my_likes = $this->Likes_model->get_likes_by_user($this->user_id);
        } else {
            $my_likes = array();
        }
        foreach ($media_list['media'] as &$media) {
            $like_id = 'media' . $media['id'];
            $media['likes'] = array(
                'id' => $like_id,
                'count' => isset($likes[$like_id]) ? $likes[$like_id] : 0,
                'has_mine' => in_array($like_id, $my_likes),
            );
        }
    }

    public function get_gallery_list()
    {
        $album_id = filter_input(INPUT_POST, 'album_id', FILTER_VALIDATE_INT);
        $param = filter_input(INPUT_POST, 'param', FILTER_SANITIZE_STRING);
        $order = filter_input(INPUT_POST, 'order', FILTER_SANITIZE_STRING);
        $direction = filter_input(INPUT_POST, 'direction', FILTER_SANITIZE_STRING);
        $page = filter_input(INPUT_POST, 'page', FILTER_VALIDATE_INT);
        if (!$order) {
            $order = 'date_add';
        }
        $order_by[$order] = ('asc' === $direction) ? 'ASC' : 'DESC';
        if ('albums' === $param && !$album_id) {
            $user_id = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
            if (!$user_id) {
                $user_id = 0;
            }
            $gallery = $this->Media_model->get_albums($user_id, $page);
        } else {
            $count = filter_input(INPUT_POST, 'count');
            $loaded_count = filter_input(INPUT_POST, 'loaded_count', FILTER_VALIDATE_INT);
            $gallery = $this->Media_model->get_gallery_list($count, $param, $loaded_count, $album_id, $order_by);
        }
        $this->set_api_content('data', $gallery);
    }

    public function add_media_in_album($media_id, $album_id)
    {
        if (!$this->Media_model->is_access_permitted($media_id)) {
            $this->set_api_content('data', array('status' => 0));
            return false;
        }
        $this->load->model('media/models/Media_album_model');
        $this->load->model('media/models/Albums_model');

        $media = $this->Media_model->get_media_by_id($media_id);
        $album = $this->Albums_model->get_album_by_id($album_id);
        if (!$album) {
            $this->set_api_content('data', array('status' => 0));
            return false;
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
            $this->set_api_content('data', array('status' => 1));
        } else {
            $this->set_api_content('data', array('status' => 0));
            $this->set_api_content('errors', !empty($add_status['error']) ? $add_status['error'] : l('error_add_in_ablum', 'media'));
        }
    }

    public function delete_media_from_album($media_id, $album_id)
    {
        $this->load->model('media/models/Albums_model');
        $album = $this->Albums_model->get_album_by_id($album_id);
        if (!$album) {
            $this->set_api_content('data', array('status' => 0));
            return false;
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
                    $this->set_api_content('data', array('status' => 0));
                    return false;
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
        $this->set_api_content('data', array('status' => 1));
    }

    public function save_description()
    {
        $photo_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if (!$this->Media_model->is_user_media_user($photo_id)) {
            $this->set_api_content('data', array('status' => 0));
            return false;
        }

        $post_data = array(
            "fname" => filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_STRING),
            "description" => filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING),
        );
        $validate_data = $this->Media_model->validate_image($post_data);
        $data['status'] = 0;
        if (empty($validate_data["errors"])) {
            $save_data = $this->Media_model->save_image($photo_id, $validate_data["data"]);
            $data['status'] = 1;
            $data['save_data'] = $save_data;
            if (!empty($save_data['errors'])) {
                $this->set_api_content('data', array('status' => 0));
                $this->set_api_content('errors', $save_data["errors"]);
            }
        } else {
            $this->set_api_content('errors', $validate_data["errors"]);
        }
        $this->set_api_content('data', $data);
    }

    public function save_album($mode = 'small', $album_id = null)
    {
        $return = array('errors' => '', 'data' => array('status' => 0));
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
            if (empty($validate_data["errors"])) {
                $new_album_id = $this->Albums_model->save($album_id, $validate_data['data']);
                if ($new_album_id) {
                    $return['data']['status'] = 1;
                    $return['data']['album_id'] = $new_album_id;
                }
            } else {
                $return['errors'] = $validate_data["errors"];
            }
        } else {
            $return['errors'] = l('no_data_sended', 'media');
        }
        $this->set_api_content('data', $return['data']);
        $this->set_api_content('errors', $return['errors']);
    }

    public function get_permissions_list()
    {
        $this->set_api_content('data', ld('permissions', 'media', $this->session->userdata("lang_id")));
    }

    public function save_permissions()
    {
        $return = array('errors' => '', 'data' => array('status' => 0));

        $photo_id = filter_input(INPUT_POST, 'photo_id', FILTER_VALIDATE_INT);
        if (!$this->Media_model->is_user_media_owner($photo_id)) {
            $this->set_api_content('data', array('status' => 0));
            return false;
        }

        $post_data = array(
            "permissions" => filter_input(INPUT_POST, 'permissions', FILTER_VALIDATE_INT)
        );

        $validate_data = $this->Media_model->validate_image($post_data);
        if (empty($validate_data["errors"])) {
            $save_data = $this->Media_model->save_image($photo_id, $validate_data["data"]);
            $this->Media_model->update_children_permissions($photo_id, $validate_data["data"]["permissions"]);
            $return['data']["status"] = 1;
        } else {
            $return["errors"] = $validate_data["errors"];
            $return['data']["status"] = 0;
        }
        if (!empty($save_data['errors'])) {
            $return["errors"] += $save_data['errors'];
            $return['data']["status"] = 0;
        }
        $this->set_api_content('data', $return['data']);
        $this->set_api_content('errors', $return['errors']);
    }

    public function save_image()
    {
        $post_data = array(
            'permissions' => filter_input(INPUT_POST, 'permissions', FILTER_VALIDATE_INT),
            'description' => filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING),
        );
        $validate_data = $this->Media_model->validate_image($post_data, 'multiUpload');
        $validate_data['data']['id_user'] = $this->user_id;
        $validate_data['data']['id_owner'] = $this->user_id;
        $validate_data['data']['type_id'] = $this->Media_model->album_type_id;
        $validate_data['data']['upload_gid'] = $this->Media_model->file_config_gid;

        if (empty($validate_data['errors'])) {
            $save_data = $this->Media_model->save_image(null, $validate_data['data'], 'multiUpload');
            $this->load->model('Moderation_model');
            $mstatus = intval($this->Moderation_model->get_moderation_type_status($this->Media_model->moderation_type));
            $this->Moderation_model->add_moderation_item($this->Media_model->moderation_type, $save_data['id']);
            $status_data['status'] = $mstatus;
            $this->Media_model->save_image($save_data['id'], $status_data);

            $id_album = filter_input(INPUT_POST, 'id_albums', FILTER_VALIDATE_INT);
            if ($id_album) {
                $this->load->model('media/models/Albums_model');
                $is_user_can_add = $this->Albums_model->is_user_can_add_to_album($id_album);
                if ($is_user_can_add['status']) {
                    $this->load->model('media/models/Media_album_model');
                    $this->Media_album_model->add_media_in_album($save_data['id'], $id_album);
                }
            }
        }
        $return['errors']['form_error'] = $validate_data['form_error'];
        if (!empty($validate_data['errors'])) {
            $return['errors'] = $validate_data['errors'];
        }
        if (empty($save_data['errors'])) {
            if (!empty($save_data['file'])) {
                $return['data']['name'] = $save_data['file'];
            }
        } else {
            $return['errors'][] = $save_data['errors'];
        }
        if (!empty($is_user_can_add['error'])) {
            $return['errors'][] = $is_user_can_add['error'];
        }

        $this->set_api_content('data', $return['data']);
        $this->set_api_content('errors', $return['errors']);
    }

    public function save_video()
    {
        if ($_POST) {
            $post_data = array(
                "permissions" => $this->input->post("permissions", true),
                "description" => $this->input->post("description", true),
            );
        }
        $validate_data = $this->Media_model->validate_video($post_data, 'videofile');
        $validate_data['data']["id_user"] = $this->user_id;
        $validate_data['data']["id_owner"] = $this->user_id;
        $validate_data['data']["type_id"] = $this->Media_model->album_type_id;
        $validate_data['data']["upload_gid"] = $this->Media_model->video_config_gid;
        if (empty($validate_data["errors"])) {
            $save_data = $this->Media_model->save_video(null, $validate_data["data"], 'videofile');

            $this->load->model('Moderation_model');
            $mstatus = intval($this->Moderation_model->get_moderation_type_status($this->Media_model->moderation_type));
            $this->Moderation_model->add_moderation_item($this->Media_model->moderation_type, $save_data['id']);
            $status_data['status'] = $mstatus;
            $this->Media_model->save_video($save_data['id'], $status_data);

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
        $return['errors']["form_error"] = $validate_data['form_error'];

        if (!empty($validate_data["errors"])) {
            $return["errors"] = $validate_data["errors"];
        }
        if (empty($save_data['errors'])) {
            if (!empty($save_data['file'])) {
                $return['data']["name"] = $save_data['file'];
            }
        } else {
            $return["errors"][] = $save_data['errors'];
        }
        if (!empty($is_user_can_add['error'])) {
            $return['errors'][] = $is_user_can_add['error'];
        }

        $this->set_api_content('data', $return['data']);
        $this->set_api_content('errors', $return['errors']);
    }

    public function delete_media()
    {
        $media_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $data = array('status' => 0);
        $messages = array();
        if (!empty($media_id) && $this->Media_model->is_user_media_user($media_id)) {
            $this->Media_model->delete_media($media_id);
            $data['status'] = 1;
            $messages[] = l('success_delete_media', 'media');
        }
        $this->set_api_content('data', $data);
        $this->set_api_content('messages', $messages);
    }

    public function delete_album($id_album = 0)
    {
        $data = array('status' => 0, 'message' => '');
        $id_album = intval($id_album);
        $this->load->model('media/models/Albums_model');
        if ($id_album && $this->Albums_model->is_user_album_owner($id_album)) {
            $this->Albums_model->delete_album($id_album);
            $data['status'] = 1;
            $data['message'] = l('success_delete_media', 'media');
            $data['albums_select'] = $this->Media_model->get_albums_select($this->user_id);
            $data['id_user'] = $this->user_id;
        }
        $this->set_api_content(array('data' => $data));
    }

    /**
     * Recrop upload
     * @param integer $upload_id upload identifier
     * @param string $thumb_prefix thumb prefix
     */
    public function recrop($upload_id, $thumb_prefix = '')
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

        $this->set_api_content($result);
    }

    public function get_media_count()
    {
        $user_ids = filter_input(INPUT_POST, 'user_ids', FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY);
        $params = array(
            'where_in' => array(
                'id_user' => $user_ids
            )
        );
        $media_list = $this->Media_model->get_media(1, 9999, null, $params, null, false);
        $permissions = array();
        $data = array();
        foreach ($media_list as $media) {
            if (empty($permissions[$media['id_user']])) {
                $permissions[$media['id_user']] = $this->Media_model->get_user_permissions($media['id_user']);
            }
            if ($media['permissions'] >= $permissions[$media['id_user']]) {
                if (isset($data[$media['id_user']])) {
                    $data[$media['id_user']] ++;
                } else {
                    $data[$media['id_user']] = 1;
                }
            }
        }
        $this->set_api_content('data', $data);
    }

}
