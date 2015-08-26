<?php

namespace Pg\Modules\Media\Models;

/**
 * Media model
 *
 * @package PG_Dating
 * @subpackage application
 *
 * @category	modules
 *
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @author Mikhail Chernov <mchernov@pilotgroup.net>
 *
 * @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: mchernov $
 * */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

define('MEDIA_TABLE', DB_PREFIX . 'media');

class Media_model extends \Model
{
    protected $CI;
    protected $DB;
    private $fields = array(
        'id',
        'type_id',
        'id_user',
        'id_owner',
        'id_parent',
        'mediafile',
        'upload_gid',
        'mime',
        'date_add',
        'permissions',
        'fname',
        'description',
        'media_video',
        'media_video_image',
        'media_video_data',
        'status',
        'comments_count',
        'is_adult',
        'views',
        'settings',
    );
    private $fields_str;
    private $fields_for_copy = array(
        'type_id',
        'id_owner',
        'mediafile',
        'upload_gid',
        'mime',
        'date_add',
        'permissions',
        'fname',
        'description',
        'media_video',
        'media_video_image',
        'media_video_data',
        'status',
        'is_adult',
        'settings',
    );
    /**
     * Ratings data properties
     *
     * @var array
     */
    protected $fields_ratings = array(
        "rating_count",
        "rating_sorter",
        "rating_value",
        "rating_type",
    );
    private $user_id = false;
    public $album_type_id = 0;
    public $file_config_gid = '';
    public $video_config_gid = '';
    public $audio_config_gid = '';
    public $wall_event_media_limit = 8;
    public $moderation_type = 'media_content';
    public $format_user = false;
    public $format_likes_count = false;
    private $cache = array(
        'parent_media_list_ids' => array(),
    );

    /**
     * Temporal path
     *
     * @var string
     */
    protected $temp_path = 'gallery_image';

    public function __construct()
    {
        parent::__construct();
        $this->CI = &get_instance();
        $this->DB = &$this->CI->db;

        $this->temp_path = TEMPPATH . $this->temp_path;

        if ($this->CI->pg_module->is_module_installed("ratings")) {
            $this->fields = array_merge($this->fields, $this->fields_ratings);
        }
        $this->fields_all = $this->fields;

        $this->fields_str = implode(', ', $this->fields);
        $this->initialize();
    }

    public function initialize($album_type_gid = 'media_type')
    {
        $this->CI->load->model('media/models/Album_types_model');
        $album_type = $this->CI->Album_types_model->get_album_type_by_gid($album_type_gid);
        $this->album_type_id = $album_type['id'];
        $this->file_config_gid = $album_type['gid_upload_type'];
        $this->video_config_gid = $album_type['gid_upload_video'];
        $this->audio_config_gid = 'gallery_audio';
        $this->user_id = $this->session->userdata('user_id');
    }

    public function get_media_by_id($media_id, $formatted = true, $format_owner = false, $format_user = false)
    {
        $result = $this->DB->select(implode(", ", $this->fields))->from(MEDIA_TABLE)->where("id", $media_id)->get()->result_array();
        if (empty($result)) {
            return array();
        } else {
            if ($formatted) {
                $result = $this->format_items(array(0 => $result[0]), $format_owner, $format_user);
            }

            return $result[0];
        }
    }

    public function get_media_by_user_id($id_user, $type = 'id_user')
    {
        if ($type === 'id_owner') {
            $this->DB->where(" id_user!='" . $id_user . "'", null, false);
        }
        $result = $this->DB->select(implode(", ", $this->fields))->from(MEDIA_TABLE)->where($type, $id_user)->get()->result_array();
        if (empty($result)) {
            return array();
        } else {
            return $result;
        }
    }

    public function copy_media($media_id)
    {
        if (!$media = $this->get_media_by_id($media_id, false)) {
            return false;
        }

        if ($media['id_parent']) {
            $media_id = $media['id_parent'];
            if (!$media = $this->get_media_by_id($media_id, false)) {
                return false;
            }
        }
        $media_attrs = array_intersect_key($media, array_flip($this->fields_for_copy));
        $media_attrs['id_user'] = $this->user_id;
        $media_attrs['id_parent'] = $media_id;
        $media_attrs['comments_count'] = 0;
        $media_attrs['views'] = 0;
        if ($media_attrs['permissions'] == 1) {
            $media_attrs['permissions'] = 0;
        }
        if ($media_attrs['permissions'] == 2) {
            $media_attrs['permissions'] = 1;
        }
        $return = $this->save_image(null, $media_attrs, "", false, false);

        return $return['errors'] ? 0 : $return['id'];
    }

    public function validate_image($data, $file_name = '')
    {
        $return = array('errors' => array(), 'data' => array(), 'form_error' => 0);

        if (isset($data["permissions"])) {
            $return["data"]["permissions"] = intval($data["permissions"]);

            if (empty($return["data"]["permissions"])) {
                $return["errors"][] = l('error_permissions_empty', 'media');
            }
        }

        if (!empty($data["fname"])) {
            $return["data"]["fname"] = trim(strip_tags($data["fname"]));
        } else {
            $return["data"]["fname"] = '';
        }

        if (isset($data["description"])) {
            $return["data"]["description"] = trim(strip_tags($data["description"]));
        }

        $this->CI->load->model('moderation/models/Moderation_badwords_model');
        $bw_count = $this->CI->Moderation_badwords_model->check_badwords($this->moderation_type, $return["data"]["fname"]);
        $bw_count = $bw_count || $this->CI->Moderation_badwords_model->check_badwords($this->moderation_type, $return["data"]["description"]);
        if ($bw_count) {
            $return["errors"][] = l('error_badwords_message', 'media');
            $return['form_error'] = 1;
        }

        if (!empty($file_name)) {
            if (isset($_FILES[$file_name]) && is_array($_FILES[$file_name]) && !($_FILES[$file_name]["error"])) {
                $this->CI->load->model("Uploads_model");
                $file_return = $this->CI->Uploads_model->validate_upload($this->file_config_gid, $file_name);
                if (!empty($file_return["error"])) {
                    $return["errors"][] = (is_array($file_return["error"])) ? implode("<br>", $file_return["error"]) : $file_return["error"];
                }
                $return["data"]['mime'] = $_FILES[$file_name]["type"];
            } elseif ($_FILES[$file_name]["error"]) {
                $return["errors"][] = $_FILES[$file_name]["error"];
            } else {
                $return["errors"][] = "empty file";
            }
        }

        return $return;
    }

    public function validate_video($data, $video_name = '')
    {
        $return = array("errors" => array(), "data" => array(), 'form_error' => 0);

        if (isset($data["permissions"])) {
            $return["data"]["permissions"] = strip_tags($data["permissions"]);

            if (empty($return["data"]["permissions"])) {
                $return["errors"][] = l('error_permissions_empty', 'media');
            }
        }

        if (isset($data["fname"])) {
            $return["data"]["fname"] = strip_tags($data["fname"]);

            if (empty($return["data"]["fname"])) {
                $return["errors"][] = l('error_fname_empty', 'media');
            }
        }

        if (isset($data["description"])) {
            $return["data"]["description"] = strip_tags($data["description"]);
        }

        if (!empty($return["data"]["fname"]) || !empty($return["data"]["description"])) {
            $this->CI->load->model('moderation/models/Moderation_badwords_model');
            $bw_count = $this->CI->Moderation_badwords_model->check_badwords($this->moderation_type, $return["data"]["fname"]);
            $bw_count = $bw_count || $this->CI->Moderation_badwords_model->check_badwords($this->moderation_type, $return["data"]["description"]);
            if ($bw_count) {
                $return["errors"][] = l('error_badwords_message', 'media');
                $return['form_error'] = 1;
            }
        }

        $embed_data = array();
        if (!empty($data["embed_code"])) {
            $this->load->library('VideoEmbed');
            $embed_data = $this->videoembed->get_video_data($data["embed_code"]);
            if ($embed_data !== false) {
                $embed_data["string_to_save"] = $this->videoembed->get_string_from_video_data($embed_data);
                $embed_data['upload_type'] = 'embed';
                $this->CI->load->model('uploads/models/Uploads_model');
                $return["data"]["media_video_image"] = $this->CI->Uploads_model->generate_filename('.jpg');
                $return["data"]["media_video_data"] = serialize(array('data' => $embed_data));
                $return["data"]["media_video"] = 'embed';
                $return["data"]["fname"] = $embed_data['service'];
            } else {
                $return["errors"][] = l('error_embed_wrong', 'media');
            }
        }

        if (!empty($video_name) && !$embed_data) {
            if (isset($_FILES[$video_name]) && is_array($_FILES[$video_name]) && !($_FILES[$video_name]["error"])) {
                $this->CI->load->model("Video_uploads_model");
                $video_return = $this->CI->Video_uploads_model->validate_upload($this->video_config_gid, $video_name);
                if (!empty($video_return["error"])) {
                    $return["errors"][] = (is_array($video_return["error"])) ? implode("<br>", $video_return["error"]) : $video_return["error"];
                }
                $return["data"]['mime'] = $_FILES[$video_name]["type"];
            } elseif (!empty($_FILES[$video_name]["error"])) {
                $return["errors"][] = $_FILES[$video_name]["error"];
            } else {
                $return["errors"][] = l('error_file_empty', 'media');
            }
        }

        return $return;
    }
    
    function validate_audio($data, $audio_name = '') {
            $return = array('errors' => array(), 'data' => array(), 'form_error' => 0);

            if (isset($data["permissions"])) {
                $return["data"]["permissions"] = intval($data["permissions"]);

                if (empty($return["data"]["permissions"])) {
                    $return["errors"][] = l('error_permissions_empty', 'media');
                }
            }

            if (!empty($data["fname"])) {
                $return["data"]["fname"] = trim(strip_tags($data["fname"]));
            } else {
                $return["data"]["fname"] = '';
            }

            if (isset($data["description"])) {
                $return["data"]["description"] = trim(strip_tags($data["description"]));
            }

            if (!empty($audio_name)) {
                if (isset($_FILES[$audio_name]) && is_array($_FILES[$audio_name]) && !($_FILES[$audio_name]["error"])) {
                    $this->CI->load->model("Audio_uploads_model");
                    $audio_return = $this->CI->Audio_uploads_model->validate_upload($this->audio_config_gid, $audio_name);
                    if (!empty($audio_return["error"])) {
                        $return["errors"][] = (is_array($audio_return["error"])) ? implode("<br>", $audio_return["error"]) : $audio_return["error"];
                    }
                    $return["data"]['mime'] = $_FILES[$audio_name]["type"];
                } elseif ($_FILES[$audio_name]["error"]) {
                    $return["errors"][] = $_FILES[$audio_name]["error"];
                } else {
                    $return["errors"][] = "empty file";
                }
            } 

            return $return;
    }

    /**
     * Save image object
     *
     * @param array $attrs
     *
     * @return bool
     */
    public function save_image($id, $data, $file_name = "", $moderation = false, $indicator = true)
    {
        $return = array('errors' => '');

        if (!empty($id)) {
            $this->DB->where('id', $id);
            $this->DB->update(MEDIA_TABLE, $data);
            $this->CI->load->model('media/models/Media_album_model');
            $this->CI->Media_album_model->update_media_album_items($data, $id);
        } else {
            $data["date_add"] = date('Y-m-d H:i:s');
            $this->DB->insert(MEDIA_TABLE, $data);
            $id = $this->DB->insert_id();

            $mtype = $this->CI->Moderation_model->get_moderation_type($this->moderation_type);
			if($mtype['mtype'] > 0 && $indicator){
                $this->CI->load->model('menu/models/Indicators_model');
                $this->CI->Indicators_model->add('new_moderation_item', $id);
            }
        }
        $return['id'] = $id;

        if (!empty($file_name) && !empty($id)) {
            $this->CI->load->model('Uploads_model');
            if (isset($_FILES[$file_name]) && is_array($_FILES[$file_name]) && is_uploaded_file($_FILES[$file_name]["tmp_name"])) {
                $upload_method = 'upload';
            } elseif (file_exists($file_name)) {
                $upload_method = 'upload_exist';
            }
            $img_return = $this->CI->Uploads_model->$upload_method($this->file_config_gid, $data['id_owner'], $file_name);
            if (empty($img_return["errors"])) {
                $img_data["mediafile"] = $img_return["file"];
                $img_data["fname"] = (string) $img_return["file"];
                $upload = $this->CI->Uploads_model->format_upload($this->file_config_gid, $data['id_owner'], $img_return["file"]);
                $image_size = getimagesize($upload["file_path"]);
                $img_data["settings"]["width"] = $image_size[0];
                $img_data["settings"]["height"] = $image_size[1];
                $img_data["settings"] = serialize($img_data["settings"]);
                $this->save_image($id, $img_data);
                $return['file'] = $img_return["file"];

                //wall event
                if (!$moderation && !empty($data['status'])) {
                    $this->create_wall_event($id);
                }
            } else {
                $return['errors'] = $img_return["errors"];
            }
        }

        return $return;
    }

    /**
     * Save image object from local file
     *
     * @param integer $id         object identifier
     * @param string  $file_path  path to file
     * @param boolean $moderation use moderation
     *
     * @return bool
     */
    public function save_local_image($id, $data, $file_path = "", $moderation = false)
    {
        $return = array('errors' => '');

        if (!empty($id)) {
            $this->DB->where('id', $id);
            $this->DB->update(MEDIA_TABLE, $data);
            $this->CI->load->model('media/models/Media_album_model');
            $this->CI->Media_album_model->update_media_album_items($data, $id);
        } else {
            $data["date_add"] = date('Y-m-d H:i:s');
            $this->DB->insert(MEDIA_TABLE, $data);
            $id = $this->DB->insert_id();
        }
        $return['id'] = $id;

        $this->CI->load->model("Uploads_model");
        $img_return = $this->CI->Uploads_model->upload_exist($this->file_config_gid, $data['id_owner'], $file_path);
        if (empty($img_return["errors"])) {
            $img_data["mediafile"] = $img_return["file"];
            $img_data["fname"] = $img_return["file"];
            $upload = $this->CI->Uploads_model->format_upload($this->file_config_gid, $data['id_owner'], $img_return["file"]);
            $image_size = getimagesize($upload["file_path"]);
            $img_data["settings"]["width"] = $image_size[0];
            $img_data["settings"]["height"] = $image_size[1];
            $img_data["settings"] = serialize($img_data["settings"]);
            $this->save_image($id, $img_data);
            $return['file'] = $img_return["file"];

            //wall event
            if (!$moderation && !empty($data['status'])) {
                $this->create_wall_event($id);
            }
        } else {
            $return['errors'] = $img_return["errors"];
        }

        return $return;
    }

    public function update_children($id_parent, $data)
    {
        if (empty($id_parent)) {
            return false;
        }

        $result = false;
        $media_ids = $this->get_media_ids(null, null, null, array('where' => array('id_parent' => $id_parent)));
        if ($media_ids) {
            $this->DB->where('id_parent', $id_parent)->update(MEDIA_TABLE, $data);
            $result = $this->DB->affected_rows();

            /* update media albums */
            $this->CI->load->model('media/models/Media_album_model');
            $this->CI->Media_album_model->update_media_album_items($data, $media_ids);
            /* */
        }

        return $result;
    }

    public function update_children_permissions($id_media, $permissions = null)
    {
        if (is_null($permissions)) {
            if (!$media = $this->get_media_by_id($id_media)) {
                return false;
            }
            $permissions = $media['permissions'];
        }
        if ($permissions == 1) {
            $permissions = 0;
        }
        if ($permissions == 2) {
            $permissions = 1;
        }

        $result = false;
        $media_ids = $this->get_media_ids(null, null, null, array('where' => array('id_parent' => $id_media)));
        if ($media_ids) {
            $this->DB->set('permissions', $permissions)->where('id_parent', $id_media)->update(MEDIA_TABLE);
            $result = $this->DB->affected_rows();

            /* update media albums */
            $data['permissions'] = $permissions;
            $this->CI->load->model('media/models/Media_album_model');
            $this->CI->Media_album_model->update_media_album_items($data, $media_ids);
            /* */
        }

        return $result;
    }

    /**
     * Save image object
     *
     * @param array $attrs
     *
     * @return bool
     */
    public function save_video($id, $data, $video_name = "", $create_event = false)
    {
        $return = array('errors' => '');

        if (!empty($data['media_video']) && $data['media_video'] == 'embed') {
            $this->CI->load->model("Video_uploads_model");
            $data = $this->CI->Video_uploads_model->upload_embed_video_image($this->video_config_gid, $data);
        }

        $this->CI->load->model('Moderation_model');

        if (!empty($id)) {
            $this->DB->where('id', $id);
            $this->DB->update(MEDIA_TABLE, $data);

            $mtype = $this->CI->Moderation_model->get_moderation_type($this->moderation_type);
            if($mtype['mtype'] > 0){
                $this->CI->load->model('media/models/Media_album_model');
                $this->CI->Media_album_model->update_media_album_items($data, $id);
            }
        } else {
            $data["date_add"] = date('Y-m-d H:i:s');
            $this->DB->insert(MEDIA_TABLE, $data);
            $id = $this->DB->insert_id();

            $mtype = $this->CI->Moderation_model->get_moderation_type($this->moderation_type);
            if($mtype['mtype'] > 0){
                $this->CI->load->model('menu/models/Indicators_model');
                $this->CI->Indicators_model->add('new_moderation_item', $id);
            }
        }
        $return['id'] = $id;
        if (!empty($video_name) && !empty($id) && isset($_FILES[$video_name]) && is_array($_FILES[$video_name]) && is_uploaded_file($_FILES[$video_name]["tmp_name"])) {
            $this->CI->load->model("Video_uploads_model");
            $video_return = $this->CI->Video_uploads_model->upload($this->video_config_gid, $data['id_owner'], $video_name, $id, $data, 'generate');

            if (empty($video_return["errors"])) {
                $video_data["fname"] = $video_return["file"];
                $this->save_video($id, $video_data);
                $return['file'] = $video_return["file"];
            } else {
                $return['errors'] = $video_return["errors"];
            }
        }

        if ($create_event) {
            $this->create_wall_event($id);
        }

        return $return;
    }
    
    public function save_audio($id, $data, $file_name = "", $create_event = false)
    {
        $return = array('errors' => '');
        $create_event = true;

        $this->CI->load->model('Moderation_model');

        if (!empty($id)) {
            $this->DB->where('id', $id);
            $this->DB->update(MEDIA_TABLE, $data);

            $mtype = $this->CI->Moderation_model->get_moderation_type($this->moderation_type);
            if($mtype['mtype'] > 0){
                $this->CI->load->model('media/models/Media_album_model');
                $this->CI->Media_album_model->update_media_album_items($data, $id);
            }
        } else {
            $data["date_add"] = date('Y-m-d H:i:s');
            $this->DB->insert(MEDIA_TABLE, $data);
            $id = $this->DB->insert_id();

            $mtype = $this->CI->Moderation_model->get_moderation_type($this->moderation_type);
            if($mtype['mtype'] > 0){
                $this->CI->load->model('menu/models/Indicators_model');
                $this->CI->Indicators_model->add('new_moderation_item', $id);
            }
        }
        $return['id'] = $id;

        if (!empty($file_name) && !empty($id)) {
            $this->CI->load->model("Audio_uploads_model");
            $audio_return = $this->CI->Audio_uploads_model->upload($this->audio_config_gid, $data['id_owner'], $file_name, $id, $data, 'generate');

            if (empty($audio_return["errors"])) {
                $audio_data["mediafile"] = $audio_return["file"];
                $audio_data["fname"] = (string) $audio_return["file"];
                $this->save_audio($id, $audio_data);
                $return['file'] = $audio_return["file"];

                //wall event
                if ($create_event) {
                    $this->create_wall_event($id);
                }
            } else {
                $return['errors'] = $audio_return["errors"];
            }
        }
        return $return;
    }

    /**
     * Get objects list
     * banners - default return all object
     *
     * @return array
     */
    public function get_media($page = 1, $items_on_page = 20, $order_by = null, $params = array(), $filter_object_ids = null, $format_items = true, $format_owner = false, $format_user = false, $safe_format = false)
    {
        $this->DB->select(implode(", ", $this->fields));
        $this->DB->from(MEDIA_TABLE);

        if (isset($params["where"]) && is_array($params["where"]) && count($params["where"])) {
            foreach ($params["where"] as $field => $value) {
                $this->DB->where($field, $value);
            }
        }

        if (isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])) {
            foreach ($params["where_in"] as $field => $value) {
                $this->DB->where_in($field, $value);
            }
        }

        if (isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])) {
            foreach ($params["where_sql"] as $value) {
                $this->DB->where($value);
            }
        }

        if (isset($params['limit']['count'])) {
            if (isset($params['limit']['from'])) {
                $this->DB->limit($params['limit']['count'], $params['limit']['from']);
            } else {
                $this->DB->limit($params['limit']['count']);
            }
        }

        if (isset($filter_object_ids) && is_array($filter_object_ids) && count($filter_object_ids)) {
            $filter_object_ids = array_slice($filter_object_ids, 0, 5000);
            $this->DB->where_in("id", $filter_object_ids);
        }

        if (is_array($order_by) && count($order_by) > 0) {
            foreach ($order_by as $field => $dir) {
                if (in_array($field, $this->fields)) {
                    $this->DB->order_by($field . " " . $dir);
                }
            }
        }

        if (!is_null($page)) {
            $page = intval($page) ? intval($page) : 1;
            $this->DB->limit($items_on_page, $items_on_page * ($page - 1));
        }
        $result = $this->DB->get()->result_array();

        if ($format_items) {
            $result = $this->format_items($result, $format_owner, $format_user, $safe_format);
        
        }
        
        return $result;
    }

    public function get_media_by_key($page = 1, $items_on_page = 20, $order_by = null, $params = array(), $filter_object_ids = null, $format_items = true, $format_owner = false, $format_user = false, $safe_format = false)
    {
        $media = $this->get_media($page, $items_on_page, $order_by, $params, $filter_object_ids, $format_items, $format_owner, $format_user, $safe_format);
        $media_by_key = array();
        foreach ($media as $m) {
            $media_by_key[$m['id']] = $m;
        }

        return $media_by_key;
    }

    /**
     * Get objects ids list
     * banners - default return all object
     *
     * @return array
     */
    public function get_media_ids($page = 1, $items_on_page = 20, $order_by = null, $params = array(), $filter_object_ids = null)
    {
        $objects = array();
        $this->DB->select('id');
        $this->DB->from(MEDIA_TABLE);

        if (isset($params["where"]) && is_array($params["where"]) && count($params["where"])) {
            foreach ($params["where"] as $field => $value) {
                $this->DB->where($field, $value);
            }
        }

        if (isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])) {
            foreach ($params["where_in"] as $field => $value) {
                $this->DB->where_in($field, $value);
            }
        }

        if (isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])) {
            foreach ($params["where_sql"] as $value) {
                $this->DB->where($value);
            }
        }

        if (isset($filter_object_ids) && is_array($filter_object_ids) && count($filter_object_ids)) {
            $this->DB->where_in("id", $filter_object_ids);
        }

        if (is_array($order_by) && count($order_by) > 0) {
            foreach ($order_by as $field => $dir) {
                if (in_array($field, $this->fields)) {
                    $this->DB->order_by($field . " " . $dir);
                }
            }
        }

        if (!is_null($page)) {
            $page = intval($page) ? intval($page) : 1;
            $this->DB->limit($items_on_page, $items_on_page * ($page - 1));
        }
        $results = $this->DB->get()->result_array();
        if (!empty($results) && is_array($results)) {
            foreach ($results as $result) {
                $objects[] = $result['id'];
            }
            //$objects = $this->format_items($objects);
        }

        return $objects;
    }

    public function format_items($data, $format_owner = false, $format_user = false, $safe_format = false)
    {
        if (empty($data) || !is_array($data)) {
            return array();
        }

        $init_format_user = $this->format_user;
        if ($this->format_user) {
            $format_owner = $format_user = true;
        }
        $this->format_user = $format_owner || $format_user;
        $this->CI->load->model('Uploads_model');
        $this->CI->load->model('Video_uploads_model');
        if ($this->CI->pg_module->is_module_installed('audio_uploads')) {
            $this->CI->load->model('Audio_uploads_model');
        }
        if ($this->format_user) {
            $this->CI->load->model('Users_model');
        }
        $users_ids = array();
        if ($this->format_likes_count) {
            $this->CI->load->model('Likes_model');
        }
   
        foreach ($data as $key => &$item) {
            if ($item['upload_gid'] == $this->file_config_gid) {
                if (!empty($item["mediafile"]) && $item['permissions'] > 0) {
                    $item["media"]["mediafile"] = $this->CI->Uploads_model->format_upload($item['upload_gid'], $item['id_owner'], $item['mediafile']);
                } else {
                    $item["media"]["mediafile"] = $this->CI->Uploads_model->format_default_upload($item['upload_gid']);
                }
                $item["media"]["mediafile"]['photo_title'] = str_replace('[id]', $item['id'], l('text_media_photo', 'media'));
                $item["media"]["mediafile"]['photo_alt'] = str_replace('[id]', $item['id'], l('text_media_photo', 'media'));
            } elseif ($item['upload_gid'] == $this->video_config_gid) {
                if (!empty($item["media_video_data"])) {
                    $item["media_video_data"] = unserialize($item["media_video_data"]);
                }

                if (!empty($item["media_video"])) {
                    if (!isset($item["media_video_data"]['data']['isHTML5'])) {
                        $item["media_video_data"]['data']['isHTML5'] = 0;
                    }
                    $media_video = ($item["media_video"] == 'embed') ? $item['media_video_data']['data'] : $item['media_video'];
                    $item["video_content"] = $this->CI->Video_uploads_model->format_upload($this->video_config_gid, $item['id_owner'], $media_video, $item['media_video_image'], $item['media_video_data']['data']['upload_type'], $item["media_video_data"]['data']['isHTML5']);
                }
            } elseif ($item['upload_gid'] == $this->audio_config_gid) {
                    $item["audio_content"] = $this->CI->Audio_uploads_model->format_upload($this->audio_config_gid, $item['id_owner'], $item['mediafile'], '', false, $item['id']);
            }
            if ($this->format_user) {
                if ($format_user) {
                    $users_ids[$item['id_user']] = $item['id_user'];
                }
                if ($format_owner) {
                    $users_ids[$item['id_owner']] = $item['id_owner'];
                }
            }
            if ($this->format_likes_count) {
                $likes_count = $this->CI->Likes_model->get_count('media' . $item['id']);
                $item["likes_count"] = intval($likes_count['media' . $item['id']]) > 0 ? $likes_count['media' . $item['id']] : 0;
                if ($this->user_id) {
                    $is_liked = count($this->CI->Likes_model->get_likes_by_user($this->user_id, 'media' . $item['id'])) > 0 ? 1 : 0;
                    $item["is_liked"] = $is_liked;
                }
            }
            $item["settings"] = $item["settings"] ? (array) unserialize($item["settings"]) : array();
            if (!empty($item['date_add'])) {
                $item['date_add_ts'] = strtotime($item['date_add']);
            }

            if (isset($item['rating_sorter'])) {
                $item['rating_sorter'] = round($item['rating_sorter'], 1);
            }
            if (isset($item['rating_value'])) {
                $item['rating_value'] = round($item['rating_value'], 1);
            }
        }

        if ($users_ids) {
            $users = $this->Users_model->get_users_list_by_key(null, null, null, array(), $users_ids, false);
            $users = $this->Users_model->format_users($users, $safe_format);
            foreach ($data as $key => &$item) {
                if ($this->format_user) {
                    if ($format_user) {
                        $item['user_info'] = !empty($users[$item['id_user']]) ? $users[$item['id_user']] : $this->Users_model->format_default_user($item['id_user']);
                    }
                    if ($format_owner) {
                        $item['owner_info'] = !empty($users[$item['id_owner']]) ? $users[$item['id_owner']] : $this->Users_model->format_default_user($item['id_owner']);
                        if(!empty($item['owner_info']['data'])){
                            $owner_data = unserialize($item['owner_info']['data']);
                            if($owner_data[0] == 'users_delete'){
                                $item['owner_info']['is_user_deleted'] = 1;
                            }
                        }
                    }
                }
            }
        }
        $this->format_user = $init_format_user;

        return $data;
    }

    /*
     * Work like get_media method, but return number of objects
     * necessary for pagination
     * banners - default return all object
     */

    public function get_media_count($params = array(), $filter_object_ids = null)
    {
        if (isset($params["where"]) && is_array($params["where"]) && count($params["where"])) {
            foreach ($params["where"] as $field => $value) {
                $this->DB->where($field, $value);
            }
        }

        if (isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])) {
            foreach ($params["where_in"] as $field => $value) {
                $this->DB->where_in($field, $value);
            }
        }

        if (isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])) {
            foreach ($params["where_sql"] as $value) {
                $this->DB->where($value);
            }
        }

        if (isset($filter_object_ids) && is_array($filter_object_ids) && count($filter_object_ids)) {
            $this->DB->where_in("id", $filter_object_ids);
        }

        if (isset($params['limit']['count'])) {
            if (isset($params['limit']['from'])) {
                $this->DB->limit($params['limit']['count'], $params['limit']['from']);
            } else {
                $this->DB->limit($params['limit']['count']);
            }

            return count($this->DB->select('*')->from(MEDIA_TABLE)->get()->result_array());
        } else {
            return $this->DB->count_all_results(MEDIA_TABLE);
        }
    }

    // video callback
    public function video_callback($id, $status, $data, $errors)
    {
        $media_data = $this->get_media_by_id($id);

        if (isset($data["video"])) {
            $update["media_video"] = $data["video"];
        }
        if (isset($data["image"])) {
            $update["media_video_image"] = $data["image"];
        }

        $update["media_video_data"] = $media_data["media_video_data"];

        if ($status == 'start') {
            $update["media_video_data"] = array();
        }

        if (!isset($update["media_video_data"]["data"])) {
            $update["media_video_data"]["data"] = array();
        }

        if (!empty($data)) {
            $update["media_video_data"]["data"] = array_merge($update["media_video_data"]["data"], $data);
        }

        $update["media_video_data"]["status"] = $status;
        $update["media_video_data"]["errors"] = $errors;
        $update["media_video_data"] = serialize($update["media_video_data"]);
        $this->save_video($id, $update);

        return;
    }

    public function delete_media($media_id)
    {
        $media = $this->get_media_by_id($media_id);
        if (!$media) {
            return;
        }

        $this->CI->load->model('media/models/Media_album_model');
        $this->CI->load->model('Moderation_model');

        $this->DB->where('id', $media['id'])->delete(MEDIA_TABLE);
        $this->CI->Media_album_model->delete_media_from_all_albums($media_id);
        $this->CI->Moderation_model->delete_moderation_item_by_obj($this->moderation_type, $media_id);

        if (!empty($media['mediafile'])) {
            $this->update_children($media['id'], array('mediafile' => ''));
            if ($media['id_user'] == $media['id_owner']) {
                $this->DB->where('mediafile', $media['mediafile']);
                $this->DB->delete(MEDIA_TABLE);
                $this->CI->load->model('Uploads_model');
                $this->CI->Uploads_model->delete_upload($this->file_config_gid, $media['id_owner'], $media['mediafile']);
            }
        }
        
        if (!empty($media['media_video'])) {
            $this->update_children($media['id'], array('media_video' => ''));
            if ($media['id_user'] == $media['id_owner']) {
                if ($media['media_video'] !== 'embed' && $media['upload_gid'] != 'gallery_audio') {
                    $this->DB->where('media_video', $media['media_video']);
                    $this->DB->delete(MEDIA_TABLE);
                    $this->CI->load->model('Video_uploads_model');
                    //$this->CI->Video_uploads_model->delete_upload($this->video_config_gid, $media['id_owner'], $media['media_video'], $media['media_video_data']['data']['upload_type']);
                    $this->CI->Video_uploads_model->delete_upload($this->video_config_gid, $media['id_owner'], $media['media_video'], $media['media_video_image']);
                } elseif ($media['media_video'] === 'embed') {
                    $this->CI->load->model('Video_uploads_model');
                    $this->CI->Video_uploads_model->delete_embed_video_image($this->video_config_gid, $media['id_owner'], $media['media_video_image']);
                }
            }
        } elseif ($media['upload_gid'] == 'gallery_audio') {
            $this->CI->load->model('Audio_uploads_model');
            $this->CI->Audio_uploads_model->delete_upload($this->audio_config_gid, $media['id_owner'], $media['mediafile']);
        }

        $children_ids = $this->get_parent_media_list_ids($media_id);

        if (!empty($children_ids) && is_array($children_ids)) {
            foreach ($children_ids as $children_id) {
                $this->delete_media($children_id);
            }
        }

        $this->CI->load->model('menu/models/Indicators_model');
        $this->CI->Indicators_model->delete('new_moderation_item', $media_id, true);

    }

    public function get_parent_media_list_ids($id)
    {
        if (empty($this->cache['parent_media_list_ids'][$id])) {
            $this->DB->select('id');
            $this->DB->from(MEDIA_TABLE);
            $this->DB->where('id_parent', $id);

            $results = $this->DB->get()->result_array();
            $objects = array();
            if (!empty($results) && is_array($results)) {
                foreach ($results as $result) {
                    $objects[] = $result['id'];
                }
            }
            $this->cache['parent_media_list_ids'][$id] = $objects;
        }

        return $this->cache['parent_media_list_ids'][$id];
    }

    // moderation functions
    public function _moder_get_list($object_ids)
    {
        $params["where_in"]["id"] = $object_ids;
        $media = $this->get_media(null, null, null, $params);

        if (!empty($media)) {
            foreach ($media as $m) {
                $return[$m["id"]] = $m;
            }

            return $return;
        } else {
            return array();
        }
    }

    public function _moder_set_status($object_id, $status)
    {
        $new_attrs = array();
        switch ($status) {
            case 0:
                $new_attrs['status'] = -1;
                break;
            case 1:
                $new_attrs['status'] = 1;
                break;
        }
        $this->save_image($object_id, $new_attrs);
        if ($status) {
            $this->create_wall_event($object_id);
        }
    }

    public function _moder_mark_adult($object_id)
    {
        $this->mark_adult($object_id);
    }

    /**
     * Mark content as adult
     *
     * @param
     *
     * @return
     */
    public function mark_adult($object_id)
    {
        $attrs['is_adult'] = 1;
        $this->save_image($object_id, $attrs);
        
        $this->CI->load->model('menu/models/Indicators_model');
        $this->CI->Indicators_model->delete('new_moderation_item', $media_id, true);
    }

    /**
     * Unmark content as adult
     *
     * @param
     *
     * @return
     */
    public function unmark_adult($object_id)
    {
        $attrs['is_adult'] = 0;
        $this->save_image($object_id, $attrs);

        $this->CI->load->model('menu/models/Indicators_model');
        $this->CI->Indicators_model->delete('new_moderation_item', $media_id, true);
    }

    public function comments_count_callback($count, $id = 0)
    {
        if ($id) {
            $this->DB->where('id', $id);
        }
        $data['comments_count'] = $count;
        $this->DB->update(MEDIA_TABLE, $data);
    }

    public function comments_object_callback($id = 0)
    {
        $return = array();
        $object = $this->get_media(null, null, null, null, (array) $id);
        if (!empty($object)) {
            foreach ($object as $media) {
                if ($media["upload_gid"] == "gallery_image") {
                    $return["body"] = "<img src='" . $media['media']['mediafile']['thumbs']['great'] . "'/>";
                } else {
                    $return["body"] = $media['video_content']['embed'];
                }
                $return["author"] = $media['user_info']['output_name'];
            }
        }

        return $return;
    }

    public function get_list($user_id, $param = 'all', $page = 1, $album_id = 0, $use_permissions = true, $order_by = array('date_add' => 'DESC'))
    {
        $return = array('content' => '', 'have_more' => 0);

        $where = array();
        if ($use_permissions) {
            $perm = $this->get_user_permissions($user_id);
            $where['where']['permissions >='] = $perm;
        }
        $show_adult = ($user_id && $user_id == $this->user_id) ? true : $this->session->userdata('show_adult');
        if (!$show_adult) {
            $where['where']['is_adult'] = 0;
        }
        switch ($param) {
            case 'photo' : $where['where']['upload_gid'] = $this->file_config_gid;
                break;
            case 'video' : $where['where']['upload_gid'] = $this->video_config_gid;
                break;
            case 'audio' : $where['where']['upload_gid'] = $this->audio_config_gid;
                break;
            case 'favorites' :
                $this->CI->load->model('media/models/Albums_model');
                $defaullt_album = $this->CI->Albums_model->get_default($user_id);
                $album_id = $defaullt_album['id'];
                break;
        }
        $where['where']['id_user'] = intval($user_id);
        if ($where['where']['id_user'] != $this->user_id) {
            $where['where']['status'] = 1;
        }
        $tpl_data = array();
        $media_ids = array();
        if ($album_id) {
            $this->CI->load->model('media/models/Media_album_model');
            $media_ids = $this->CI->Media_album_model->get_media_ids_in_album($album_id);

            $this->CI->load->model('media/models/Albums_model');
            $album = $this->CI->Albums_model->get_album_by_id($album_id);
            $tpl_data['album'] = $album;
        }
        $this->CI->load->helper('sort_order');
        $items_on_page = $this->CI->pg_module->get_module_config('media', 'items_per_page');
        $media_count = (!$album_id || $media_ids) ? $this->get_media_count($where, $media_ids) : 0;
        $exists_page = get_exists_page_number($page, $media_count, $items_on_page);
        $next_page = get_exists_page_number($exists_page + 1, $media_count, $items_on_page);
        if ($next_page > $exists_page) {
            $return['have_more'] = 1;
        }

        $media = (!$album_id || $media_ids) ? $this->get_media($exists_page, $items_on_page, $order_by, $where, $media_ids) : array();
        $tpl_data['media'] = $media;
        $tpl_data['media_count'] = $media_count;

        if ($this->CI->router->is_api_class) {
            return $tpl_data;
        } else {
            $this->CI->template_lite->assign($tpl_data);
            if ($where['where']['id_user'] == $this->user_id) {
                $return['content'] = trim($this->CI->template_lite->fetch_final('list', 'user', 'media'));
            } else {
                $return['content'] = trim($this->CI->template_lite->fetch_final('view_list', 'user', 'media'));
            }

            return $return;
        }
    }

    public function get_gallery_list($count, $param = 'all', $loaded_count = 0, $album_id = 0, $order_by = array('date_add' => 'DESC'), $params = array())
    {
        $return = array('content' => '', 'have_more' => 0);

        if (!$count) {
            return $return;
        }

        $params['where']['status'] = 1;
        $params['where']['id_parent'] = 0;
        $params['where']['permissions >='] = $this->get_user_permissions(0);

        if (!$this->session->userdata('show_adult')) {
            $params['where']['is_adult'] = 0;
        }
        switch ($param) {
            case 'photo' : $params['where']['upload_gid'] = $this->file_config_gid;
                break;
            case 'video' : $params['where']['upload_gid'] = $this->video_config_gid;
                break;
            case 'favorites':
                unset($params['where']['id_parent']);
                $this->CI->load->model('media/models/Albums_model');
                $album = $this->CI->Albums_model->get_default($this->user_id);
                $album_id = $album['id'];
                $params['where']['id_user'] = $this->user_id;
                $params['where']['id_owner <>'] = $this->user_id;
                break;
        }

        $media_ids = array();
        if ($album_id) {
            $this->CI->load->model('media/models/Media_album_model');
            $media_ids = $this->CI->Media_album_model->get_media_ids_in_album($album_id);
        }

        $params['limit']['count'] = $count + 1;
        $params['limit']['from'] = $loaded_count;
        if (!$album_id || $media_ids) {
            $media_count = $this->get_media_count($params, $media_ids);
            if ($media_count > $count) {
                $return['have_more'] = 1;
                --$media_count;
            }
        } else {
            $media_count = 0;
        }
        $params['limit']['count'] = $count;

        $return['media'] = (!$album_id || $media_ids) ? $this->get_media(null, null, $order_by, $params, $media_ids, true, false, true, true) : array();

        if (!$album_id && !$loaded_count && !$media_count) {
            $return['msg'] = l('no_media', 'media');
        }

        $return['media_count'] = $media_count;
        $return['requested_count'] = $count;

        return $return;
    }

    public function get_albums($id_user, $page = 1)
    {
        $return = array('content' => '', 'have_more' => 0);
        $this->CI->load->model('media/models/Albums_model');
        $params = array();
        $params["where"]["id_user"] = $id_user;
        $is_user_album_owner = ($id_user && $id_user == $this->user_id);
        $albums_count_field = 'media_count';
        if (!$is_user_album_owner) {
            $perm = $this->get_user_permissions($id_user);
            $params['where']['permissions >='] = $perm;
            $albums_count_field = $this->user_id ? 'media_count_user' : 'media_count_guest';
        }
        $this->CI->load->helper('sort_order');
        $items_on_page = $this->pg_module->get_module_config('media', 'items_per_page');
        $albums_count = $this->CI->Albums_model->get_albums_count($params);
        $exists_page = get_exists_page_number($page, $albums_count, $items_on_page);
        $next_page = get_exists_page_number($exists_page + 1, $albums_count, $items_on_page);
        if ($next_page > $exists_page) {
            $return['have_more'] = 1;
        }
        $lang_id = $this->CI->pg_language->current_lang_id;
        
        $albums = $this->CI->Albums_model->get_albums_list($params, null, null, $exists_page, $items_on_page, true, $lang_id);

        $this->CI->template_lite->assign('albums', $albums);
        $this->CI->template_lite->assign('albums_page', $exists_page);
        $this->CI->template_lite->assign('is_user_album_owner', $is_user_album_owner);
        $this->CI->template_lite->assign('albums_count_field', $albums_count_field);

        $return['content'] = $this->CI->template_lite->fetch('albums_list', 'user', 'media');

        return $return;
    }

    public function get_albums_select($id_user)
    {
        $this->CI->load->model('media/models/Albums_model');
        $params['where']['id_user'] = intval($id_user);
        $is_user_album_owner = ($id_user && $id_user == $this->user_id);
        $albums_count_field = 'media_count';
        if (!$is_user_album_owner) {
            $perm = $this->get_user_permissions($id_user);
            $params['where']['permissions >='] = $perm;
            $albums_count_field = $this->user_id ? 'media_count_user' : 'media_count_guest';
        }
        $lang_id = $this->CI->pg_language->current_lang_id;
        $albums_list = $this->CI->Albums_model->get_albums_list($params, null, null, null, null, false, $lang_id);
        $this->CI->template_lite->assign('is_user_album_owner', $is_user_album_owner);
        $this->CI->template_lite->assign('albums_list', $albums_list);
        $this->CI->template_lite->assign('albums_count_field', $albums_count_field);

        return $this->CI->template_lite->fetch('albums_select', 'user', 'media');
    }

    public function get_media_position_info($media_id, $param = 'all', $album_id = 0, $user_id = 0, $use_permissions = true, $order_by = array('date_add' => 'DESC'), $filter_duplicate = false)
    {
        $return = array('position' => 0, 'count' => 0);
        $where = array();
        if ($use_permissions) {
            $perm = $this->get_user_permissions($user_id);
            $where['where']['permissions >='] = $perm;
        }
        $show_adult = ($this->user_id && $user_id == $this->user_id) ? true : $this->session->userdata('show_adult');
        if (!$show_adult) {
            $where['where']['is_adult'] = 0;
        }
        switch ($param) {
            case 'photo' : $where['where']['upload_gid'] = $this->file_config_gid;
                break;
            case 'video' : $where['where']['upload_gid'] = $this->video_config_gid;
                break;
        }
        if ($user_id) {
            $where["where"]["id_user"] = $user_id;
        }
        if ($filter_duplicate) {
            $where['where']['id_parent'] = 0;
        }
        if ($where['where']['id_user'] != $this->user_id) {
            $where['where']['status'] = 1;
        }

        $media_ids = array();
        if ($album_id) {
            $this->CI->load->model('media/models/Media_album_model');
            $media_ids = $this->CI->Media_album_model->get_media_ids_in_album($album_id);
        }
        if (!$album_id || $media_ids) {
            $media_ids = $this->get_media_ids(null, null, $order_by, $where, $media_ids);
        }

        $return['position'] = array_search($media_id, $media_ids) + 1;
        $return['count'] = count($media_ids);
        $return['next'] = $return['position'] < $return['count'] ? $media_ids[$return['position']] : 0;
        $return['previous'] = $return['position'] > 1 ? $media_ids[$return['position'] - 2] : 0;
        $return['user_id'] = $user_id;

        $media_ids = array($return['next'], $return['previous']);
        $medias = $this->get_media_by_key(null, null, null, array(), $media_ids);
        $return['next_type'] = !empty($medias[$return['next']]) ? $medias[$return['next']]['upload_gid'] : null;
        $return['previous_type'] = !empty($medias[$return['previous']]) ? $medias[$return['previous']]['upload_gid'] : null;
        $return['next_image']['image'] = (!empty($medias[$return['next']]) && $return['next_type'] == $this->file_config_gid) ? $medias[$return['next']]['media']['mediafile']['file_url'] : null;
        $return['next_image']['thumb'] = (!empty($medias[$return['next']]) && $return['next_type'] == $this->file_config_gid) ? $medias[$return['next']]['media']['mediafile']['thumbs']['grand'] : null;
        $return['previous_image']['image'] = (!empty($medias[$return['previous']]) && $return['next_type'] == $this->file_config_gid) ? $medias[$return['previous']]['media']['mediafile']['file_url'] : null;
        $return['previous_image']['thumb'] = (!empty($medias[$return['previous']]) && $return['next_type'] == $this->file_config_gid) ? $medias[$return['previous']]['media']['mediafile']['thumbs']['grand'] : null;

        return $return;
    }

    public function get_media_type_by_id($media_id)
    {
        $media = $this->get_media_by_id($media_id);

        return $media['upload_gid'];
    }

    public function increment_media_views($media_id, $delta = '1')
    {
        $this->db->set('views', 'views + ' . $delta, false);
        $this->DB->where('id', $media_id);
        $this->DB->update(MEDIA_TABLE);
    }

    public function decrement_media_views($media_id, $delta = '1')
    {
        $this->db->set('views', 'views - ' . $delta, false);
        $this->DB->where('id', $media_id);
        $this->DB->update(MEDIA_TABLE);
    }

    public function is_user_media_owner($media_id)
    {
        $this->DB->select("COUNT(*) AS cnt");
        $this->DB->from(MEDIA_TABLE);
        $this->DB->where('id', $media_id);
        $this->DB->where('id_owner', $this->user_id);

        $results = $this->DB->get()->result();
        if (!empty($results) && is_array($results)) {
            return intval($results[0]->cnt);
        }

        return false;
    }

    public function is_user_media_user($media_id)
    {
        $this->DB->select("COUNT(*) AS cnt");
        $this->DB->from(MEDIA_TABLE);
        $this->DB->where('id', $media_id);
        $this->DB->where('id_user', $this->user_id);

        $results = $this->DB->get()->result();
        if (!empty($results) && is_array($results)) {
            return intval($results[0]->cnt);
        }

        return false;
    }

    public function is_access_permitted($media_id, $media = array())
    {
        if (!$media) {
            $media = $this->get_media_by_id($media_id);
        }
        if (!$media) {
            return false;
        }
        if ($this->user_id && $media['id_user'] === $this->user_id) {
            return true;
        }
        switch ($media['permissions']) {
            case 1: //only for me
                return false;
            case 2: // friends
                if ($this->pg_module->is_module_installed('friendlist')) {
                    $this->CI->load->model('Friendlist_model');

                    return $this->CI->Friendlist_model->is_friend($this->user_id, $media['id_owner']);
                }
                break;
            case 3: //registered
                if ($this->user_id) {
                    return true;
                }
                break;
            case 4: //all
                return true;
            default:
                return false;
        }

        return false;
    }

    public function get_user_permissions($id_owner)
    {
        if (!$this->user_id) {
            return 4;
        }
        $perm = 3;
        if (!$id_owner) {
            return $perm;
        }
        if ($id_owner == $this->user_id) {
            return 0;
        }
        if ($this->pg_module->is_module_installed('friendlist')) {
            $this->CI->load->model('Friendlist_model');
            $perm = $this->CI->Friendlist_model->is_friend($this->user_id, $id_owner) ? 2 : 3;
        }

        return $perm;
    }

    /**
     * Callback for reviews module
     *
     * @param string $action action name
     * @param array  $data   review data
     *
     * @return string
     */
    public function callback_reviews($action, $data)
    {
        $id = $data['id_object'];
        switch ($action) {
            case 'update':
                $media_data['review_type'] = $data['type_gid'];
                $media_data['review_sorter'] = $data['review_sorter'];
                $media_data['review_count'] = $data['review_count'];
                $media_data['review_data'] = serialize($data['review_data']);
                $this->save_image($id, $media_data);
                break;
            case 'get_object':
                if (empty($data)) {
                    return array();
                }
                $where['where_in']['id'] = (array) $data;
                $listings = $this->get_media(null, null, null, $where);
                $return = array();
                foreach ($listings as $listing) {
                    $return[$listing['id']] = $listing;
                }

                return $return;
                break;
        }
    }

    private function dynamic_block_get_users_media($type, $params, $view, $width, $gallery_params = array())
    {
        $data['rand'] = rand(1, 999999);
        $data['type'] = $type;
        $data['params'] = $params;
        $data['view'] = $view;
        $data['width'] = $width;
        if (!empty($params['title_' . $this->pg_language->current_lang_id])) {
            $data['title'] = $params['title_' . $this->pg_language->current_lang_id];
        } elseif (!empty($params['title_' . $this->pg_language->current_lang['code']])) {
            $data['title'] = $params['title_' . $this->pg_language->current_lang['code']];
        } else {
            $data['title'] = '';
        }
        $data['media'] = $this->get_gallery_list($params['count'], $type, 0, 0, array('date_add' => 'DESC'), $gallery_params);

        if (empty($data['media']['media'])) {
            return '';
        }
        $this->CI->template_lite->assign('dynamic_block_users_media_data', $data);

        return $this->CI->template_lite->fetch('dynamic_block_users_media', 'user', 'media');
    }

    public function _dynamic_block_get_users_photos($params, $view, $width)
    {
        return $this->dynamic_block_get_users_media('photo', $params, $view, $width);
    }

    public function _dynamic_block_get_users_videos($params, $view, $width)
    {
        $gallery_params['where']['media_video_image !='] = '';

        return $this->dynamic_block_get_users_media('video', $params, $view, $width, $gallery_params);
    }

    public function _format_wall_events($events)
    {
        $is_permissions_allowed = function ($media, $friends_ids, $user_id, $show_adult) {
            if (!$media['status']) {
                return false;
            }
            if ($media['is_adult'] && !$show_adult) {
                return false;
            }
            $perm = $user_id ? 3 : 4;
            if (isset($friends_ids[$media['id_owner']])) {
                $perm = 2;
            }
            if ($media['id_owner'] == $user_id) {
                $perm = 0;
            }
            if (intval($media['permissions']) < $perm) {
                return false;
            }

            return true;
        };

        $formatted_events = array();
        $media_ids = array();
        foreach ($events as $key => $e) {
            if (!is_array($e['data'])) {
                continue;
            }
            foreach ($e['data'] as $mkey => $media) {
                if (($this->get_media_count(array('where_in' => array('id' => $media['id'])))) != 0) {
                    $media_ids[$media['id']] = $media['id'];
                }
            }
        }
        if ($media_ids) {
            $medias = $this->get_media_by_key(null, null, null, array(), $media_ids);
            if ($medias) {
                $friends_ids = array();
                if ($this->user_id) {
                    if ($this->pg_module->is_module_installed('friendlist')) {
                        $this->CI->load->model('Friendlist_model');
                        $friends_ids = array_flip($this->CI->Friendlist_model->get_friendlist_users_ids($this->user_id));
                    }
                }
                foreach ($events as $key => $e) {
                    if (is_array($e['data'])) {
                        foreach ($e['data'] as $mkey => $media) {
                            if (!empty($medias[$media['id']]) && $is_permissions_allowed($medias[$media['id']], $friends_ids, $this->user_id, $this->session->userdata('show_adult'))) {
                                $e['data'][$mkey] = $medias[$media['id']];
                            } else {
                                unset($e['data'][$mkey]);
                            }
                        }
                    }
                    if ($e['data']) {
                        $e['media_count_all'] = $e['media_count'] = count($e['data']);
                        if ($e['media_count_all'] > $this->wall_event_media_limit + 4) {
                            $e['media_count'] = $this->wall_event_media_limit;
                            $e['media_count_more'] = $e['media_count_all'] - $e['media_count'];
                            $e['data'] = array_slice($e['data'], 0, $this->wall_event_media_limit);
                        }
                        $this->CI->template_lite->assign('event', $e);
                        $e['html'] = $this->CI->template_lite->fetch('wall_events_media', null, 'media');
                        $formatted_events[$key] = $e;
                    } else {
                        $e['html_delete'] = "<span class='spam_object_delete'>" . l("error_is_deleted_wall_events_object", "spam") . "</span>";
                        $formatted_events[$key] = $e;
                    }
                }
            }
        } else {
            foreach ($events as $key => $e) {
                $e['html_delete'] = "<span class='spam_object_delete'>" . l("error_is_deleted_wall_events_object", "spam") . "</span>";
                $formatted_events[$key] = $e;
            }
        }

        return $formatted_events;
    }

    private function create_wall_event($media_id)
    {
        $media = $this->get_media_by_id($media_id);
        if ($media && $media['id_owner'] == $media['id_user']) {
            $this->CI->load->helper('wall_events_default');
            $event_data['id'] = $media['id'];
            $event_data['id_user'] = $media['id_user'];
            $event_data['id_owner'] = $media['id_owner'];
            $event_data['description'] = $media['description'];
            $event_data['permissions'] = $media['permissions'];
            $event_data['is_adult'] = !empty($media['is_adult']);
            $event_data['upload_gid'] = $media['upload_gid'];
            $event_data['date_add'] = $media['date_add'];
            $e_gid = '';
            switch ($media['upload_gid']) {
                case $this->video_config_gid: $event_data['upload'] = $media['video_content'];
                    $e_gid = 'video_upload';
                    break;
                case $this->file_config_gid: $event_data['upload'] = $media['media']['mediafile'];
                    $e_gid = 'image_upload';
                    break;
                case $this->audio_config_gid: $event_data['upload'] = $media['media']['mediafile'];
                    $e_gid = 'audio_upload';
                    break;
            }
            unset($event_data['upload']['thumbs_data']);
            $event_result = add_wall_event($e_gid, $media['id_user'], $media['id_user'], $event_data, $media['id']);

            return $event_result;
        }

        return false;
    }

    /* SEO */

    public function get_seo_settings($method = '', $lang_id = '')
    {
        if (!empty($method)) {
            return $this->_get_seo_settings($method, $lang_id);
        } else {
            $actions = array('index', 'all', 'photo', 'video', 'albums');
            $return = array();
            foreach ($actions as $action) {
                $return[$action] = $this->get_seo_settings($action, $lang_id);
            }

            return $return;
        }
    }

    private function _get_seo_settings($method, $lang_id = '')
    {
        switch ($method) {
            case 'index':
                return array(
                    'templates'   => array(),
                    'url_vars'    => array(),
                    'url_postfix' => array(
                        'section-code' => array('section-code' => 'literal', 'section-name' => 'literal'),
                    ),
                    'optional' => array(),
                );
                break;
            case 'all':
                return array(
                    'templates'   => array(),
                    'url_vars'    => array(),
                    'url_postfix' => array(),
                    'optional'    => array(),
                );
                break;
            case 'photo':
                return array(
                    'templates'   => array(),
                    'url_vars'    => array(),
                    'url_postfix' => array(),
                    'optional'    => array(),
                );
                break;
            case 'video':
                return array(
                    'templates'   => array(),
                    'url_vars'    => array(),
                    'url_postfix' => array(),
                    'optional'    => array(),
                );
                break;
            case 'albums':
                return array(
                    'templates'   => array(),
                    'url_vars'    => array(),
                    'url_postfix' => array(),
                    'optional'    => array(),
                );
                break;
            case 'favorites':
                return array(
                    'templates'   => array(),
                    'url_vars'    => array(),
                    'url_postfix' => array(),
                    'optional'    => array(),
                );
                break;
        }
    }

    public function request_seo_rewrite($var_name_from, $var_name_to, $value)
    {
        if ($var_name_from == $var_name_to) {
            return $value;
        }

        show_404();
    }

    public function get_sitemap_xml_urls()
    {
        $this->CI->load->helper('seo');

        $lang_canonical = true;

        if ($this->CI->pg_module->is_module_installed('seo')) {
            $lang_canonical = $this->CI->pg_module->get_module_config('seo', 'lang_canonical');
        }

        if ($lang_canonical) {
            $default_lang_id = $this->CI->pg_language->get_default_lang_id();
            $default_lang_code = $this->CI->pg_language->get_lang_code_by_id($default_lang_id);
            $langs[$default_lang_id] = $default_lang_code;
        } else {
            foreach ($this->CI->pg_language->languages as $lang_id => $lang_data) {
                $langs[$lang_id] = $lang_data['code'];
            }
        }

        $return = array();
        $types = array('all' => 0.5, 'photo' => 0.5, 'video' => 0.5, 'albums' => 0.5);
        foreach ($types as $type => $priority) {
            $user_settings = $this->pg_seo->get_settings('user', 'media', $type);
            if ($user_settings['noindex']) {
                continue;
            }
            foreach ($langs as $lang_id => $lang_code) {
                $return[] = array(
                    "url"      => rewrite_link('media', $type, array(), false, $lang_code, $lang_canonical),
                    "priority" => $priority,
                );
            }
        }

        return $return;
    }

    public function get_sitemap_urls()
    {
        $this->CI->load->helper('seo');
        $auth = $this->CI->session->userdata("auth_type");

        $block[] = array(
            "name"      => l('header_gallery', 'media'),
            "link"      => rewrite_link('media', 'index'),
            "clickable" => true,
            "items"     => array(
                array(
                    "name"      => l('header_gallery_photo', 'media'),
                    "link"      => rewrite_link('media', 'photo'),
                    "clickable" => true,
                ),
                array(
                    "name"      => l('header_gallery_video', 'media'),
                    "link"      => rewrite_link('media', 'video'),
                    "clickable" => true,
                ),
                array(
                    "name"      => l('header_gallery_albums', 'media'),
                    "link"      => rewrite_link('media', 'albums'),
                    "clickable" => true,
                ),
            ),
        );

        return $block;
    }

    /**
     * Callback for spam module
     *
     * @param string  $action   action name
     * @param integer $user_ids user identifiers
     *
     * @return string
     */
    public function spam_callback($action, $data)
    {
        switch ($action) {
            case "delete":
                $this->delete_media((int) $data);

                return "removed";
                break;
            case 'get_content':
                if (empty($data)) {
                    return array();
                }
                $new_data = array();
                $return = array();
                foreach ($data as $id) {
                    if (($this->get_media_count(array('where_in' => array('id' => $id)))) == 0) {
                        $return[$id]["content"]["view"] = $return[$id]["content"]["list"] = "<span class='spam_object_delete'>" . l("error_is_deleted_media_object", "spam") . "</span>";
                        $return[$id]["user_content"] = l("author_unknown", "spam");
                    } else {
                        $new_data[] = $id;
                    }
                }
                $medias = $this->get_media(null, null, null, null, (array) $new_data, true, false, true);
                foreach ($medias as $media) {
                    if ($media["upload_gid"] == "gallery_image") {
                        $return[$media['id']]["content"]["list"] = "<img src='" . $media['media']['mediafile']['thumbs']['hgreat'] . "'/>";
                        $return[$media['id']]["content"]["view"] = "<img src='" . $media['media']['mediafile']['thumbs']['great'] . "'/>";
                    } else {
                        if (preg_match_all("/id=\"default_player_([0-9]+)\">/is", $event['html'], $match)) {
                            $return[$media['id']]["rand"] = $match[1];
                            foreach ($match[1] as $key => $rand) {
                                $media['video_content']['embed'] = str_replace($rand, $rand . "_" . $key, $media['video_content']['embed']);
                            }
                        }
                        $return[$media['id']]["content"]["list"] = $media['video_content']['embed'];
                        $return[$media['id']]["content"]["view"] = $media['video_content']['embed'];
                    }
                    $return[$media['id']]["user_content"] = $media['user_info']['output_name'];
                }

                return $return;
                break;
            case 'get_subpost':
                return array();
                break;
            case 'get_link':
                return array();
                break;
            case 'get_deletelink':
                if (empty($data)) {
                    return array();
                }
                $medias = $this->get_media(null, null, null, null, (array) $data);
                $return = array();
                foreach ($medias as $media) {
                    $return[$media['id']] = site_url() . 'admin/spam/delete_content/';
                }

                return $return;
                break;
            case 'get_object':
                if (empty($data)) {
                    return array();
                }
                $medias = $this->get_media_by_key(null, null, null, null, (array) $data);

                return $medias;
                break;
        }
    }

    public function get_recent_media($count = '16', $param = 'all')
    {
        return $this->get_gallery_list($count, $param);
    }

    public function callback_user_delete($id_user, $type)
    {
        $this->delete_media_by_user_id($id_user, $type);
    }

    private function delete_media_by_user_id($id_user, $type)
    {
        if ($type === 'gallery') {
            $media_data = $this->get_media_by_user_id($id_user);
            $attrs['permissions'] = 1;
            foreach ($media_data as $media) {
                $this->save_image($media['id'], $attrs);
            }
        } else {
            $media_data = $this->get_media_by_user_id($id_user, $type);
            foreach ($media_data as $media) {
                $this->delete_media($media['id']);
            }
        }
    }

    /**
     * Save file from aviary editor
     *
     * @param integer $photo_id       photo identifier
     * @param array   $data           photo data
     * @param string  $file_url       upload url
     * @param boolean $use_moderation need mnoderation
     *
     * @return array
     */
    public function save_aviary_file($photo_id, $data, $file_url, $use_moderation)
    {
        $photo = $this->get_media_by_id($photo_id);
        if (!$photo || !$photo['mediafile']) {
            return false;
        }

        if (function_exists('curl_init')) {
            $defaults = array(
                CURLOPT_URL            => $file_url,
                CURLOPT_HEADER         => 0,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT        => 4,
                CURLOPT_SSL_VERIFYPEER => false,
            );
            $ch = curl_init();
            curl_setopt_array($ch, $defaults);
            $image_data = curl_exec($ch);
            curl_close($ch);
        } else {
            $image_data = file_get_contents($file_url);
        }

        $path = $this->temp_path;
        if (!is_dir($path)) {
            @mkdir($path, 0777, true);
        }

        $filename = $photo['mediafile'];
        file_put_contents($path . '/' . $filename, $image_data);

        $this->CI->load->model('Uploads_model');
        $this->CI->Uploads_model->delete_upload($photo['upload_gid'], $photo["id_owner"], $photo["mediafile"]);

        $this->CI->Uploads_model->delete_path($photo['upload_gid'], $photo["id_owner"]);

        $data['id_owner'] = $photo['id_owner'];

        $return = $this->save_local_image($photo_id, $data, $path . '/' . $filename, $use_moderation);

        @unlink($path . '/' . $filename);

        return $return;
    }

    /**
     * Save photo after aviary editor
     *
     * @param string $url  photo url
     * @param array  $data request data
     *
     * @return void
     */
    public function save_aviary($url, $data)
    {
        if (empty($data['id'])) {
            show_404();
        }

        $data['id'] = intval($data['id']);
        if (!$data['id']) {
            show_404();
        }

        $use_moderation = !empty($data['user_id']);

        $save_data = array();
        $this->save_aviary_file($data['id'], $save_data, $url, $use_moderation);
    }

    /**
     * Install user fields of ratings
     *
     * @param array $fields fields data
     *
     * @return void
     */
    public function install_ratings_fields($fields = array())
    {
        if (empty($fields)) {
            return;
        }
        $this->CI->load->dbforge();
        $table_fields = $this->CI->db->list_fields(MEDIA_TABLE);
        foreach ((array) $fields as $field_name => $field_data) {
            if (!in_array($field_name, $table_fields)) {
                $this->CI->dbforge->add_column(MEDIA_TABLE, array($field_name => $field_data));
            }
        }
    }

    /**
     * Uninstall fields of ratings
     *
     * @param array $fields fields data
     *
     * @return void
     */
    public function deinstall_ratings_fields($fields = array())
    {
        if (empty($fields)) {
            return;
        }
        $this->CI->load->dbforge();
        $table_fields = $this->CI->db->list_fields(MEDIA_TABLE);
        foreach ($fields as $field_name) {
            if (in_array($field_name, $table_fields)) {
                $this->CI->dbforge->drop_column(MEDIA_TABLE, $field_name);
            }
        }
    }

    /**
     * Process event of ratings module
     *
     * @param string $action action name
     * @param array  $data   ratings data
     *
     * @return mixed
     */
    public function callback_ratings($action, $data)
    {
        switch ($action) {
            case 'update':
                $user_data['rating_type'] = $data['type_gid'];
                $user_data['rating_sorter'] = $data['rating_sorter'];
                $user_data['rating_value'] = $data['rating_value'];
                $user_data['rating_count'] = $data['rating_count'];
                $this->save_image($data['id_object'], $user_data);
            break;
            case 'get_object':
            /*
                if(empty($data)) return array();
                $users = $this->get_users_list_by_key(null, null, null, null, (array)$data);
                return $users;
            */
            break;
        }
    }

    public function get_rating_object_by_id($id, $formatted = false, $safe_format = false)
    {
        $result = $this->DB->select(implode(", ", $this->fields_all))
                        ->from(MEDIA_TABLE)
                        ->where("id", $id)
                        ->get()->result_array();
        if (empty($result)) {
            return false;
        } elseif ($formatted) {
            return $this->format_items($result, $safe_format);
        } else {
            return $result[0];
        }
    }
    
    public function addFriendsMenu()
    {
        //lang ds for friendlist
        $languages = $this->CI->pg_language->languages;
        foreach($languages as $language){            
            $ds_file = MODULEPATH . 'media/langs/' . $language['code'] . '/ds' . EXT;
            if (file_exists($ds_file)) {
                $install_lang = array();
                include $ds_file;
                if (!empty($install_lang) && is_array($install_lang)) {
                    foreach ($install_lang as $ref_gid => $value) {
                        $this->CI->pg_language->ds->set_module_reference('media', 'permissions', $value, $language["id"]);       
                    }
                }
            }                                   
        } 
    }
    
    public function deleteFriendsMenu()
    {
        //lang ds for friendlist
        $data = $this->CI->pg_language->ds->get_reference('media', 'permissions');
        unset($data['option'][2]);
        $this->CI->pg_language->ds->set_module_reference('media', 'permissions', $data);
    }
}
