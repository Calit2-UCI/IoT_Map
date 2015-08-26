<?php

namespace Pg\Modules\Im\Models;

/**
 * IM contact list model
 *
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @author Dmitry Popenov
 * @version $Revision: 2 $ $Date: 2013-01-30 10:07:07 +0400 $
 * */
if (!defined('IM_CONTACT_LIST_TABLE')) {
    define('IM_CONTACT_LIST_TABLE', DB_PREFIX . 'im_contact_list');
}

class Im_contact_list_model extends \Model
{

    private $CI;
    private $DB;
    private $fields = array(
        'id_user',
        'id_contact',
        'site_status',
        'count_new',
        'date_update',
    );
    private $fields_str;

    public function __construct()
    {
        parent::__construct();
        $this->CI = &get_instance();
        $this->DB = &$this->CI->db;
        $this->fields_str = implode(', ', $this->fields);
    }

    public function _import_friendlist($user_id = null)
    {
        $this->CI->load->model('friendlist/models/Friendlist_model');
        $friendlist_count = $this->CI->Friendlist_model->get_list_count(null, 'accept');
        if ($friendlist_count) {
            // import by packs of N rows
            $rows_pack = 10000;
            $iterations = ceil($friendlist_count / $rows_pack);
            for ($i = 1; $i <= $iterations; $i++) {
                $friendlist = $this->CI->Friendlist_model->get_friendlist(null, $i, $rows_pack, null, '', false);
                $contact_list = array();
                foreach ($friendlist as $list) {
                    $contact_list[] = array(
                        'id_user' => $list['id_user'],
                        'id_contact' => $list['id_dest_user'],
                        'date_update' => $list['date_update'],
                    );
                }
                $this->DB->insert_batch(IM_CONTACT_LIST_TABLE, $contact_list, true);
            }
        }
    }

    public function callback_update_contact_list($event_status, $id_user, $id_contact_user)
    {
        switch ($event_status) {
            case 'remove':
            case 'block':
                $this->remove_contact($id_user, $id_contact_user);
                break;
            case 'accept':
                $this->add_contact($id_user, $id_contact_user);
                break;
        }
    }

    public function callback_update_contacts_statuses($event_status, $users_ids)
    {
        if (!empty($users_ids)) {
            $data_upd['site_status'] = intval($event_status);
            $params['where_in']['id_contact'] = $users_ids;
            $this->_save(array(), $data_upd, $params);
            return $this->DB->affected_rows();
        }
        return false;
    }

    private function _remove($params)
    {
        if (!empty($params["where"]) && is_array($params["where"])) {
            $this->DB->where($params["where"]);
        }

        if (!empty($params["where_in"]) && is_array($params["where_in"])) {
            foreach ($params["where_in"] as $field => $value) {
                $this->DB->where_in($field, $value);
            }
        }

        $this->DB->delete(IM_CONTACT_LIST_TABLE);
        return $this->DB->affected_rows();
    }

    private function _save($data_ins = array(), $data_upd = array(), $params = array())
    {
        if (!empty($data_ins) && is_array($data_ins)) {
            $update_str = '';
            if (!empty($data_upd)) {
                $fields_upd = array();
                foreach ($data_upd as $field => $val) {
                    if ($field == 'count_new' && ($val[0] == '+' || $val[0] == '-')) {
                        $fields_upd[] = "`{$field}` = {$field} {$val[0]} " . intval($val);
                    } else {
                        $fields_upd[] = "`{$field}` = " . $this->DB->escape($val);
                    }
                }
                $update_str = implode(', ', $fields_upd);
            }
            if ($update_str) {
                $sql = $this->DB->insert_string(IM_CONTACT_LIST_TABLE, $data_ins) . " ON DUPLICATE KEY UPDATE {$update_str}";
            } else {
                $sql = $this->DB->insert_string(IM_CONTACT_LIST_TABLE, $data_ins);
                $sql = str_replace('INSERT INTO', 'INSERT IGNORE INTO', $sql);
            }
            $this->DB->query($sql);
        } elseif (!empty($data_upd) && is_array($data_upd)) {
            if (!empty($params["where"]) && is_array($params["where"])) {
                $this->DB->where($params["where"]);
            }
            if (!empty($params["where_in"]) && is_array($params["where_in"])) {
                foreach ($params["where_in"] as $field => $value) {
                    $this->DB->where_in($field, $value);
                }
            }
            $count_new = null;
            if (!empty($data_upd['count_new']) && ($data_upd['count_new'][0] == '+' || $data_upd['count_new'][0] == '-')) {
                $count_new = "count_new {$data_upd['count_new'][0]} " . intval($data_upd['count_new']);
                unset($data_upd['count_new']);
            }
            if ($count_new) {
                $this->DB->set('count_new', $count_new, false);
            }
            $this->DB->update(IM_CONTACT_LIST_TABLE, $data_upd);
        } else {
            return false;
        }

        return $this->DB->affected_rows();
    }

    private function _get($params, $page = null, $items_on_page = null, $order_by = null)
    {
        if (!empty($params["where"]) && is_array($params["where"])) {
            $this->DB->where($params["where"]);
        }

        if (!empty($params["where_in"]) && is_array($params["where_in"])) {
            foreach ($params["where_in"] as $field => $value) {
                $this->DB->where_in($field, $value);
            }
        }

        if (is_array($order_by) && count($order_by)) {
            foreach ($order_by as $field => $dir) {
                if (in_array($field, $this->fields)) {
                    $this->DB->order_by($field, $dir);
                }
            }
        }

        if (!is_null($page) && !is_null($items_on_page)) {
            $page = intval($page) > 0 ? intval($page) : 1;
            $this->DB->limit($items_on_page, $items_on_page * ($page - 1));
        }

        $result = $this->DB->select($this->fields_str)->from(IM_CONTACT_LIST_TABLE)->get()->result_array();

        return $result;
    }

    public function remove_contact($id_user, $id_contact)
    {
        $params['where']['id_user'] = $id_user;
        $params['where']['id_contact'] = $id_contact;
        return $this->_remove($params);
    }
	
	public function remove_all_contact($id_contact) {
		$params['where']['id_contact'] = $id_contact;
		return $this->_remove($params);
	}

    public function add_contact($id_user, $id_contact, $site_status = 0, $count_new = 0)
    {
        $data['id_user'] = $id_user;
        $data['id_contact'] = $id_contact;
        $data['site_status'] = $site_status;
        $data['count_new'] = $count_new;
        $data['date_update'] = date("Y-m-d H:i:s");
        return $this->_save($data);
    }

    public function update_contact($id_user, $id_contact, $site_status = null, $count_new = null, $change_date = true)
    {
        $data_ins['id_user'] = $id_user;
        $data_ins['id_contact'] = $id_contact;
        if (!is_null($site_status)) {
            $data_ins['site_status'] = $data_upd['site_status'] = $site_status;
        }
        if (!is_null($count_new)) {
            $data_ins['count_new'] = intval($count_new);
            $data_upd['count_new'] = $count_new;
        }
        $data_ins['date_update'] = date("Y-m-d H:i:s");
        if ($change_date) {
            $data_upd['date_update'] = $data_ins['date_update'];
        }
        $params['where']['id_user'] = $id_user;
        $params['where']['id_contact'] = $id_contact;

        return $this->_save($data_ins, $data_upd, $params);
    }

    public function get_contact_list($id_user, $page = null, $items_on_page = null, $formatted = true)
    {
        $params['where']['id_user'] = $id_user;
        //$order_by['count_new'] = 'DESC';
        $order_by['date_update'] = 'DESC';
        $list = $this->_get($params, $page, $items_on_page, $order_by);
        if ($formatted) {
            $list = $this->format_list($list);
        }
        return $list;
    }

    public function get_contact_list_time($id_user)
    {
        $params['where']['id_user'] = $id_user;
        //$order_by['count_new'] = 'DESC';
        $order_by['date_update'] = 'DESC';
        $result = $this->_get($params, 1, 1, $order_by);
        $time = $result ? strtotime($result[0]['date_update']) : 0;
        return $time;
    }

    public function backend_get_contact_list($params = array())
    {
        $id_user = $this->CI->session->userdata('user_id');
        $this->CI->load->model('Im_model');
        $im_status = $this->CI->Im_model->im_status($id_user);
        if ($im_status['im_on'] && $im_status['im_service_access']) {
            $page = null;
            $formatted = (!empty($params['formatted']) && $params['formatted']) ? true : false;
            $loaded_contact_ids = (!empty($params['loaded_contact_ids'])) ? array_map('intval', explode(',', $params['loaded_contact_ids'])) : array();
            $result['list'] = $this->get_contact_list($id_user, $page, null, $formatted);
            if (is_null($page) || $page <= 1) {
                $result['time'] = $result['list'] ? strtotime($result['list'][0]['date_update']) : 0;
            } else {
                $result['time'] = $this->get_contact_list_time($id_user);
            }

            // format contacts if they are not in js object
            $ids_list = array();
            if (!$formatted) {
                foreach ($result['list'] as $l) {
                    $id_contact = intval($l['id_contact']);
                    if (!in_array($id_contact, $loaded_contact_ids)) {
                        $ids_list[$id_contact]['id_contact'] = $id_contact;
                    }
                }
            }
            if ($ids_list) {
                $formatted_list = $this->format_list($ids_list);
                foreach ($result['list'] as &$l) {
                    if (!empty($formatted_list[$l['id_contact']])) {
                        $l['contact_user'] = $formatted_list[$l['id_contact']]['contact_user'];
                    }
                }
            }
        }
        $result['im_status'] = $im_status;

        return $result;
    }

    public function format_list($list)
    {
        if (empty($list) || !is_array($list)) {
            return array();
        }
        $users_ids = array();
        foreach ($list as $l) {
            $users_ids[] = $l['id_contact'];
        }
        if ($users_ids) {
            $this->CI->load->helper('seo');
            $this->CI->load->model('Users_model');
            $users = $this->CI->Users_model->get_users_list_by_key(null, null, null, array(), $users_ids);
            $users[0] = $this->CI->Users_model->format_default_user(1);
            foreach ($list as &$l) {
                $user = !empty($users[$l['id_contact']]) ? $users[$l['id_contact']] : $users[0];
                $l['contact_user'] = array(
                    'id' => intval($user['id']),
                    'output_name' => $user['output_name'],
                    'age' => !empty($user['age']) ? intval($user['age']) : 0,
                    'location' => !empty($user['location']) ? $user['location'] : '',
                    'thumbs' => $user['media']['user_logo']['thumbs'],
                    'link' => rewrite_link('users', 'view', $user),
                );
            }
        }
        return $list;
    }

    public function check_new_messages($id_user)
    {
        $params['where']['id_user'] = intval($id_user);
        $params['where']['count_new >'] = 0;
        $new = $this->_get($params);
        $result['contacts'] = $new;
        $result['count_new'] = 0;
        foreach ($new as $contact) {
            $result['count_new'] += $contact['count_new'];
        }
        return $result;
    }

    public function backend_check_new_messages()
    {
        $id_user = $this->CI->session->userdata('user_id');
        $this->CI->load->model('Im_model');
        $im_status = $this->CI->Im_model->im_status($id_user);
        //if($im_status['im_on'] && $im_status['im_service_access']){
        $result = $this->check_new_messages($id_user);
        //}
        $result['im_status'] = $im_status;

        return $result;
    }

}
