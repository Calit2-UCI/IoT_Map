<?php

namespace Pg\Modules\Media\Models;

/**
 * Album types model
 *
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @author Mikhail Chernov <mchernov@pilotgroup.net>
 * @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: mchernov $
 * */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

define('MEDIA_ALBUM_TABLE', DB_PREFIX . 'media_album');

class Media_album_model extends \Model
{

    protected $CI;
    protected $DB;
    /* private $_fields = array(
      'media_id',
      'album_id',
      'status',
      'is_adult',
      'permissions',
      ); */
    private $_update_fields = array('status', 'is_adult', 'permissions');
    public $album_items_limit = 10000; // limit for SQL requests with IN

    public function __construct()
    {
        parent::__construct();
        $this->CI = &get_instance();
        $this->DB = &$this->CI->db;
    }

    public function get_albums_by_media_id($ids)
    {
        $ids = is_array($ids) ? $ids : (array) $ids;
        if (!$ids) {
            return array();
        }
        $result = $this->DB->select('album_id')->from(MEDIA_ALBUM_TABLE)->where_in("media_id", $ids)->get()->result_array();
        if (empty($result)) {
            return array();
        } else {
            return $this->format_albums_by_media($result);
        }
    }

    private function format_albums_by_media($data)
    {
        foreach ($data as $key => $value) {
            $data[$key] = $value['album_id'];
        }
        return $data;
    }

    public function get_media_ids_in_album($album_id)
    {
        $result = $this->DB->select('media_id')->from(MEDIA_ALBUM_TABLE)->where("album_id", $album_id)->get()->result_array();
        if (empty($result)) {
            return array();
        } else {
            return $this->format_media_ids_in_album($result);
        }
    }

    public function format_media_ids_in_album($data)
    {
        $return = array();
        foreach ($data as $key => $value) {
            $return[] = $value['media_id'];
        }
        return $return;
    }

    public function add_media_in_album($media_id, $album_id)
    {
        $result = array('status' => 0, 'error' => '');
        $this->CI->load->model('Media_model');
        $this->CI->load->model('media/models/Albums_model');
        $album = $this->CI->Albums_model->get_album_by_id($album_id);
        if ($album['media_count'] >= $this->album_items_limit) {
            $result['error'] = l('error_album_limit_achieved', 'media');
            return $result;
        }
        $media = $this->CI->Media_model->get_media_by_id($media_id, false);
        if ($media) {
            $data['media_id'] = $media_id;
            $data['album_id'] = $album_id;
            $data['date_add'] = date('Y-m-d H:i:s');
            $data['status'] = $media['status'];
            $data['is_adult'] = $media['is_adult'];
            $data['permissions'] = $media['permissions'];
            $this->DB->insert(MEDIA_ALBUM_TABLE, $data);
            $this->CI->Albums_model->update_albums_media_count($album_id);
            $result['status'] = 1;
            return $result;
        }
        $result['error'] = l('error_add_in_ablum', 'media');
        return $result;
    }

    public function delete_media_from_album($media_id, $album_id)
    {
        $this->DB->where("media_id", $media_id);
        $this->DB->where("album_id", $album_id);
        $this->DB->delete(MEDIA_ALBUM_TABLE);
        $this->CI->load->model('media/models/Albums_model');
        $this->CI->Albums_model->update_albums_media_count($album_id);
        return true;
    }

    public function delete_album($album_id)
    {
        $this->DB->where("album_id", $album_id)->delete(MEDIA_ALBUM_TABLE);
        return true;
    }

    public function delete_albums($albums_ids)
    {
        $this->DB->where_in("album_id", $albums_ids)->delete(MEDIA_ALBUM_TABLE);
        return true;
    }

    public function get_first_media_id_in_albums($album_ids, $params = array())
    {
        $medias = array();
        if (isset($params["where"]) && is_array($params["where"]) && count($params["where"])) {
            $this->DB->where($params["where"]);
        }
        if (isset($params["where_in"]) && is_array($params["where_in"])) {
            foreach ($params["where_in"] as $field => $value) {
                $this->DB->where_in($field, $value);
            }
        }
        if (isset($params["where_sql"]) && is_array($params["where_sql"])) {
            foreach ($params["where_sql"] as $value) {
                $this->DB->where($value);
            }
        }
        $this->DB->where_in('album_id', $album_ids)->group_by('album_id');
        $result = $this->DB->select('album_id, MAX(media_id) AS media_id')->from(MEDIA_ALBUM_TABLE)->get()->result_array();
        foreach ($result as $row) {
            $medias[$row['album_id']] = $row['media_id'];
        }
        foreach ($album_ids as $aid) {
            if (empty($medias[$aid])) {
                $medias[$aid] = 0;
            }
        }

        return $medias;
    }

    public function delete_media_from_all_albums($media_id, $update_albums_count = true)
    {
        $albums = $this->get_albums_by_media_id($media_id);
        if ($albums) {
            if (is_array($media_id)) {
                $this->DB->where_in("media_id", $media_id)->delete(MEDIA_ALBUM_TABLE);
            } else {
                $this->DB->where("media_id", $media_id)->delete(MEDIA_ALBUM_TABLE);
            }
            if ($update_albums_count) {
                $this->CI->load->model('media/models/Albums_model');
                $this->CI->Albums_model->update_albums_media_count($albums);
            }
        }
        return $albums;
    }

    public function get_albums_media_count($albums_ids = array(), $params = array())
    {
        $albums_ids = (array) $albums_ids;
        $this->DB->select('album_id, COUNT(media_id) AS cnt')->from(MEDIA_ALBUM_TABLE)->group_by('album_id');
        if ($albums_ids) {
            $this->DB->where_in('album_id', $albums_ids);
        }
        if (isset($params["where"]) && is_array($params["where"]) && count($params["where"])) {
            $this->DB->where($params["where"]);
        }
        if (isset($params["where_in"]) && is_array($params["where_in"])) {
            foreach ($params["where_in"] as $field => $value) {
                $this->DB->where_in($field, $value);
            }
        }
        if (isset($params["where_sql"]) && is_array($params["where_sql"])) {
            foreach ($params["where_sql"] as $value) {
                $this->DB->where($value);
            }
        }
        $result = $this->DB->get()->result_array();
        $return = array();
        foreach ($result as $row) {
            $return[$row['album_id']] = $row['cnt'];
        }
        foreach ($albums_ids as $aid) {
            if (!isset($return[$aid])) {
                $return[$aid] = 0;
            }
        }
        return $return;
    }

    public function get_album_media_count($album_id)
    {
        $this->DB->where('album_id', $album_id);
        return $this->DB->count_all_results(MEDIA_ALBUM_TABLE);
    }

    public function update_media_album_items($data, $media_ids)
    {
        $data = array_intersect_key($data, array_flip($this->_update_fields));
        $media_ids = (array) $media_ids;
        if ($data && $media_ids) {
            $albums_ids = $this->get_albums_by_media_id($media_ids);
            if ($albums_ids) {
                $this->DB->where_in('media_id', $media_ids)->update(MEDIA_ALBUM_TABLE, $data);
                $this->CI->load->model('media/models/Albums_model');
                $this->CI->Albums_model->update_albums_media_count($albums_ids);
                return $this->DB->affected_rows();
            }
        }
        return false;
    }

}
