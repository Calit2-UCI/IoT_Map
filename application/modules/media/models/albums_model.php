<?php

namespace Pg\Modules\Media\Models;

/**
 * Albums model
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

define('ALBUM_TABLE', DB_PREFIX . 'albums');

class Albums_model extends \Model
{

    protected $CI;
    protected $DB;
    private $_user_id;
    protected $fields = array(
        'id',
        'id_album_type',
        'id_user',
        'date_add',
        'name',
        'description',
        'permissions',
        'media_count',
        'media_count_guest',
        'media_count_user',
        'is_default'
    );
    private $_cache = array(
        'default_album' => array(),
    );
    public $format_user = false;

    public function __construct()
    {
        parent::__construct();
        $this->CI = &get_instance();
        $this->DB = &$this->CI->db;
        $this->_user_id = $this->session->userdata('user_id');
    }

    /**
     * Get objects list
     * albums - default return all object
     * @return array
     */
    public function get_albums_list($params = array(), $filter_object_ids = null, $order_by = null, $page = null, $items_on_page = null, $formatted = true, $lang_id = false)
    {
        $select_attrs = $this->fields;
        if ($lang_id) {
            foreach ($select_attrs as $key => $value) {
                if ($value == 'name') {
                    unset($select_attrs[$key]);
                }
            }
            $select_attrs[] = 'lang_' . $lang_id . ' as name';
        }

        $this->DB->select(implode(", ", $select_attrs))->from(ALBUM_TABLE);
        if (!isset($params['where']['is_default'])) {
            $params['where']['is_default'] = 0;
        }

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

        if (isset($params["where_not_in"]) && is_array($params["where_not_in"]) && count($params["where_not_in"])) {
            foreach ($params["where_not_in"] as $field => $value) {
                $this->DB->where_not_in($field, $value);
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
                $this->DB->order_by($field . " " . $dir);
            }
        }

        if (!is_null($page)) {
            $page = intval($page) ? intval($page) : 1;
            $this->DB->limit($items_on_page, $items_on_page * ($page - 1));
        }

        $result = $this->DB->get()->result_array();
        if ($formatted) {
            $result = $this->format_albums($result);
        }
        return $result;
    }

    function get_album_by_id($album_id, $lang_id = false, $languages = array())
    {
        $select_attrs = $this->fields;
        if ($lang_id) {
            foreach ($select_attrs as $key => $value) {
                if ($value == 'name') {
                    unset($select_attrs[$key]);
                }
            }
            $select_attrs[] = 'lang_' . $lang_id . ' as name';
        }
        if (is_array($languages) && count($languages) > 0) {
            foreach ($languages as $id => $value) {
                $select_attrs[] = 'lang_' . $id . ' as lang_' . $id;
            }
        }

        $result = $this->DB->select(implode(", ", $select_attrs))->from(ALBUM_TABLE)->where("id", $album_id)->get()->result_array();
        if (empty($result)) {
            return false;
        } else {
            $result = $this->format_albums(array(0 => $result[0]));
            return $result[0];
        }
    }

    public function format_albums($data)
    {
        if (empty($data) || !is_array($data)) {
            return array();
        }

        $this->CI->load->model('media/models/Media_album_model');
        $this->CI->load->model('Media_model');
        $media_ids = $user_ids = array();
        $first_media_albums = array();
        foreach ($data as $key => &$item) {
            $is_owner = ($item['id_user'] === $this->_user_id);
            $params = array();
            if ($is_owner) {
                $user_type = 'owner';
            } elseif ($this->session->userdata('auth_type') == 'user') {
                $user_type = 'user';
                $params['where']['permissions >='] = 3;
                if (!$this->session->userdata('show_adult')) {
                    $params['where']['is_adult'] = 0;
                }
            } else {
                $user_type = 'guest';
                $params['where']['permissions'] = 4;
            }
            $first_media_albums[$user_type]['ids'][$item['id']] = $item['id'];
            $first_media_albums[$user_type]['params'] = $params;
            $user_ids[$item['id_user']] = $item['id_user'];
        }

        foreach ($first_media_albums as $user_type => $items) {
            $media_ids += $this->CI->Media_album_model->get_first_media_id_in_albums($items['ids'], $items['params']);
        }

        foreach ($data as $key => &$item) {
            $item['media_id'] = !empty($media_ids[$item['id']]) ? $media_ids[$item['id']] : 0;
        }

        $this->CI->load->model('Uploads_model');
        $medias[0]['media']['mediafile'] = $this->CI->Uploads_model->format_default_upload($this->CI->Media_model->file_config_gid);
        if ($media_ids) {
            $medias_result = $this->CI->Media_model->get_media(null, null, null, array(), $media_ids);
            foreach ($medias_result as $m) {
                $medias[$m['id']] = $m;
            }
        }
        if ($this->format_user) {
            $this->CI->load->model('Users_model');
            if ($user_ids) {
                $users = $this->CI->Users_model->get_users_list_by_key(null, null, null, array(), $user_ids);
            }
        }

        foreach ($data as $key => &$item) {
            if ($this->format_user) {
                $item['user_info'] = !empty($users[$item['id_user']]) ? $users[$item['id_user']] : $this->CI->Users_model->format_default_user($item['id_user']);
            }
            $item['mediafile'] = !empty($medias[$item['media_id']]) ? $medias[$item['media_id']] : $medias[0];
        }
        return $data;
    }

    public function is_user_album_owner($album_id)
    {
        $this->DB->select("COUNT(*) AS cnt");
        $this->DB->from(ALBUM_TABLE);
        $this->DB->where('id', $album_id);
        $this->DB->where('id_user', $this->_user_id);
        $results = $this->DB->get()->result();
        if (!empty($results) && is_array($results)) {
            return intval($results[0]->cnt);
        }
        return 0;
    }

    public function is_user_can_add_to_album($album_id)
    {
        $result = array('status' => 0, 'error' => '');
        if (($album = $this->get_album_by_id($album_id)) && $this->_user_id) {
            $this->CI->load->model('media/models/Media_album_model');
            if ($album['media_count'] >= $this->CI->Media_album_model->album_items_limit) {
                $result['error'] = l('error_album_limit_achieved', 'media');
                return $result;
            }
            $result['status'] = intval($this->_user_id == $album['id_user'] || !$album['id_user']);
        }
        return $result;
    }

    public function validate_album($data)
    {
        $return = array("errors" => array(), "data" => array());

        if (isset($data["name"])) {
            $return["data"]["name"] = strip_tags($data["name"]);

            if (empty($return["data"]["name"])) {
                $return["errors"][] = l('error_name_empty', 'media');
            }
        }
        if (isset($data["description"])) {
            $return["data"]["description"] = strip_tags($data["description"]);
        }
        if (isset($data["permissions"])) {
            $return["data"]["permissions"] = intval($data["permissions"]);
            if (!$return["data"]["permissions"]) {
                $return["errors"][] = l('error_permissions_empty', 'media');
            }
        }
        return $return;
    }

    public function save($album_id = null, $attrs = array(), $langs = array())
    {
        if (is_array($langs) && count($langs) > 0) {
            foreach ($langs as $id => $value) {
                $attrs['lang_' . $id] = $value;
            }
        }
        if (is_null($album_id)) {
            $attrs['date_add'] = date("Y-m-d H:i:s");
            if (!isset($attrs['id_user'])) {
                $attrs["id_user"] = $this->_user_id;
            }
            $this->DB->insert(ALBUM_TABLE, $attrs);
            $album_id = $this->DB->insert_id();
        } else {
            $this->DB->where('id', $album_id);
            $this->DB->update(ALBUM_TABLE, $attrs);
        }
        return $album_id;
    }

    /*
     * Work like get_albums method, but return number of objects
     * necessary for pagination
     * banners - default return all object
     */

    public function get_albums_count($params = array(), $filter_object_ids = null)
    {
        $this->DB->select("COUNT(*) AS cnt");
        $this->DB->from(ALBUM_TABLE);
        if (!isset($params['where']['is_default'])) {
            $params['where']['is_default'] = 0;
        }

        if (isset($params["where"]) && is_array($params["where"]) && count($params["where"])) {
            foreach ($params["where"] as $field => $value) {
                $this->DB->where($field, $value);
            }
        }

        if (isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])) {
            $this->DB->where_in($field, $value);
        }

        if (isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])) {
            foreach ($params["where_sql"] as $value) {
                $this->DB->where($value);
            }
        }

        if (isset($filter_object_ids) && is_array($filter_object_ids) && count($filter_object_ids)) {
            $this->DB->where_in("id", $filter_object_ids);
        }

        $results = $this->DB->get()->result();
        if (!empty($results) && is_array($results)) {
            return intval($results[0]->cnt);
        }
        return false;
    }

    public function update_albums_media_count($albums_ids)
    {
        $this->CI->load->model('media/models/Media_album_model');
        //counts all
        $counts = $this->CI->Media_album_model->get_albums_media_count($albums_ids);
        //counts for guest
        $params = array();
        $params['where']['permissions'] = 4;
        $params['where']['status'] = 1;
        $counts_guest = $this->CI->Media_album_model->get_albums_media_count($albums_ids, $params);
        //counts for user
        $params = array();
        $params['where']['permissions >='] = 3;
        $params['where']['status'] = 1;
        $counts_user = $this->CI->Media_album_model->get_albums_media_count($albums_ids, $params);

        foreach ($counts as $album_id => $media_count) {
            $data['media_count'] = $media_count;
            $data['media_count_guest'] = $counts_guest[$album_id];
            $data['media_count_user'] = $counts_user[$album_id];
            $this->DB->set($data)->where('id', $album_id)->update(ALBUM_TABLE);
        }
    }

    public function delete_album($album_id)
    {
        $this->DB->where('id', $album_id)->delete(ALBUM_TABLE);
        $this->CI->load->model('media/models/Media_album_model');
        $this->CI->Media_album_model->delete_album($album_id);
    }

    public function delete_user_albums($id_user)
    {
        $params['where']['id_user'] = $id_user;
        $albums = $this->get_albums_list($params, null, null, null, null, false);
        $albums_ids = array();
        foreach ($albums as $album) {
            $albums_ids[] = $album['id'];
        }
        if ($albums_ids) {
            $this->DB->where('id_user', $id_user)->delete(ALBUM_TABLE);
            $this->CI->load->model('media/models/Media_album_model');
            $this->CI->Media_album_model->delete_albums($albums_ids);
        }
    }

    /**
     * Get default album
     * @param int $id_user
     * @return array
     */
    public function get_default($id_user)
    {
        if (empty($this->_cache['default_album'][$id_user])) {
            $result = $this->DB->select(implode(', ', $this->fields))
                            ->from(ALBUM_TABLE)
                            ->where('id_user', $id_user)
                            ->where('is_default', 1)
                            ->get()->result_array();
            if (empty($result)) {
                $this->_cache['default_album'][$id_user] = $this->_add_default_album($id_user);
            } else {
                $this->_cache['default_album'][$id_user] = array_shift($result);
            }
        }
        return $this->_cache['default_album'][$id_user];
    }

    /**
     * Create default album
     * @param int $id_user
     * @return array
     */
    private function _add_default_album($id_user)
    {
        $this->CI->load->model('Media_model');
        $album = array(
            'id_user' => $id_user,
            'id_album_type' => $this->CI->Media_model->album_type_id,
            'name' => 'favorites',
            'description' => '',
            'is_default' => 1
        );
        $album['id'] = $this->save(null, $album);
        return $album;
    }

    public function lang_dedicate_module_callback_add($lang_id = false)
    {
        if ($lang_id) {
            $this->CI->load->dbforge();
            $fields["lang_" . $lang_id] = array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => FALSE,
            );
            $default_lang_id = $this->CI->pg_language->get_default_lang_id();

            $table_query = $this->CI->db->get(ALBUM_TABLE);
            $exists_fields = $table_query->list_fields();
            $this->CI->dbforge->add_column(ALBUM_TABLE, $fields);
            // Set default names
            if (in_array("lang_" . $default_lang_id, $exists_fields)) {
                $this->CI->db->set('lang_' . $lang_id, 'lang_' . $default_lang_id, false);
            } else {
                $this->CI->db->set('lang_' . $lang_id, 'name', false);
            }
            $this->CI->db->update(ALBUM_TABLE);
        }
    }

    public function lang_dedicate_module_callback_delete($lang_id = false)
    {
        if ($lang_id) {
            $field_name = "lang_" . $lang_id;
            $this->CI->load->dbforge();

            $table_query = $this->CI->db->get(ALBUM_TABLE);
            if (in_array("lang_" . $lang_id, $table_query->list_fields())) {
                $this->CI->dbforge->drop_column(ALBUM_TABLE, $field_name);
            }
        }
    }

}
