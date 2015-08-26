<?php

namespace Pg\Modules\Im\Models;

/**
 * IM messages model
 *
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @author Dmitry Popenov
 * @version $Revision: 2 $ $Date: 2013-01-30 10:07:07 +0400 $
 * */
if (!defined('IM_MESSAGES_TABLE')) {
    define('IM_MESSAGES_TABLE', DB_PREFIX . 'im_messages');
}

class Im_messages_model extends \Model
{

    private $CI;
    private $DB;
    private $fields = array(
        'id',
        'id_linked',
        'id_user',
        'id_contact',
        'text',
        'dir',
        'date_add',
        'is_read',
        'is_notified',
    );
    private $fields_str;
    private $moderation_type = 'im';

    public function __construct()
    {
        parent::__construct();
        $this->CI = &get_instance();
        $this->DB = &$this->CI->db;
        $this->fields_str = implode(', ', $this->fields);
    }

    private function _save($data, $id = 0)
    {
        $id = intval($id);
        if ($id) {
            $this->DB->where('id', $id)->update(IM_MESSAGES_TABLE, $data);
            $return = $this->DB->affected_rows();
        } else {
            $this->DB->insert(IM_MESSAGES_TABLE, $data);
            $return = $this->DB->insert_id();
        }
        return $return;
    }

    private function _get($params, $limit = null, $order_by = null, $formatted = true)
    {
        if (!empty($params["where"]) && is_array($params["where"])) {
            $this->DB->where($params["where"]);
        }
        if (!empty($params["where_in"]) && is_array($params["where_in"])) {
            foreach ($params["where_in"] as $field => $value) {
                $this->DB->where_in($field, $value);
            }
        }
        if (!empty($params["where_sql"]) && is_array($params["where_sql"])) {
            foreach ($params["where_sql"] as $value) {
                $this->DB->where($value, null, false);
            }
        }
        if (is_array($order_by) && count($order_by)) {
            foreach ($order_by as $field => $dir) {
                if (in_array($field, $this->fields)) {
                    $this->DB->order_by($field, $dir);
                }
            }
        }
        if ($limit) {
            $this->DB->limit($limit);
        }
        $result = $this->DB->select($this->fields_str)->from(IM_MESSAGES_TABLE)->get()->result_array();
        if ($formatted) {
            $result = $this->format($result);
        }
        return $result;
    }

    public function format($data)
    {
        $full_time_format = $this->CI->pg_date->get_format('date_time_literal', 'date');
        $time_format = $this->CI->pg_date->get_format('time_numeric', 'date');
        foreach ($data as &$msg) {
            if (isset($msg['date_add'])) {
                $time = strtotime($msg['date_add']);
                $msg['date_add_format'] = (time() - $time > 3600 * 12) ? date($full_time_format, $time) : date($time_format, $time);
            }
        }
        return $data;
    }

    private function _validate($data)
    {
        $return = array('errors' => array(), 'data' => array());
        foreach ($data as $field => $val) {
            if (!in_array($field, $this->fields)) {
                unset($data[$field]);
            }
        }
        if (empty($data['date_add'])) {
            $data['date_add'] = date("Y-m-d H:i:s");
        }
        $data['text'] = !empty($data['text']) ? trim(strip_tags($data['text'])) : '';
        $data['text'] = mb_substr($data['text'], 0, $this->pg_module->get_module_config('im', 'message_max_chars'), 'UTF-8');
        $data['id_user'] = !empty($data['id_user']) ? intval($data['id_user']) : 0;
        $data['id_contact'] = !empty($data['id_contact']) ? intval($data['id_contact']) : 0;
        if (!$data['id_user'] || !$data['id_contact'] || !$data['text']) {
            $return['errors'][] = 'empty fields';
        }

        if (!empty($data['text'])) {
            $this->CI->load->model('moderation/models/Moderation_badwords_model');
            $bw_count = $this->CI->Moderation_badwords_model->check_badwords($this->moderation_type, $data['text']);
            if ($bw_count) {
                $return['errors'][] = l('error_badwords_message', 'im');
            }
        }

        $return['data'] = $data;
        return $return;
    }

    public function add_message($id_user, $id_contact, $text)
    {
        $return = array('errors' => array(), 'data' => array());

        // output msg
        $data['id_user'] = $id_user;
        $data['id_contact'] = $id_contact;
        $data['dir'] = 'o';
        $data['text'] = $text;
        $validate = $this->_validate($data);
        if ($validate['errors']) {
            $return['errors'] = $validate['errors'];
            return $return;
        }
        
        $data = $validate['data'];
        
        // Network event
        if ($this->CI->pg_module->is_module_installed('network')) {
            $this->CI->load->model('network/models/Network_events_model');
            $this->CI->Network_events_model->emit('im.message', array(
                'id_user' => $data['id_user'],
                'id_contact' => $data['id_contact'],
                'text' => $data['text'],
            ));
        }

        $result = $this->saveMessage($data);
        
        $return = array_merge($return, $result);

        return $return;
    }
    
    /**
     * Save chat message 
     * 
     * @return integer
     */
    private function saveMessage($data){
        $o_msg_id = $this->_save($data);

        $this->CI->load->model('im/models/Im_contact_list_model');
        $this->CI->Im_contact_list_model->update_contact($data['id_user'], $data['id_contact']);

        // input msg
        if ($o_msg_id) {
            $save_data = $data;
            $save_data['id_user'] = $data['id_contact'];
            $save_data['id_contact'] = $data['id_user'];
            $save_data['id_linked'] = $o_msg_id;
            $save_data['dir'] = 'i';
            $i_msg_id = $this->_save($save_data);
            if ($i_msg_id) {
                $this->_save(array('id_linked' => $i_msg_id), $o_msg_id);
                $this->CI->Im_contact_list_model->update_contact($data['id_contact'], $data['id_user'], null, '+1');
            }
        }
        
        return array('o_msg_id'=>$o_msg_id, 'i_msg_id'=>$i_msg_id);
    }

    public function get_last_messages($id_user, $id_contact = null, $count = 20)
    {
        $params = array();
        $params['where']['id_user'] = intval($id_user);
        if (!empty($id_contact)) {
            $params['where']['id_contact'] = intval($id_contact);
            $order_by['id'] = 'DESC';
        } else {
            $params['where_sql'][] = 'date_add in (select max(date_add) from pg_im_messages group by id_contact)'
                    . ' group by id_contact';
        }

        $messages = $this->_get($params, $count, $order_by);
        // Set id_contact as array_key
        if (empty($id_contact)) {
            $sorted = array();
            foreach ($messages as $msg) {
                $sorted[$msg['id_contact']] = $msg;
            }
            $messages = $sorted;
        }

        return $messages;
    }

    public function get_history($id_user, $id_contact, $from_id, $count = 100)
    {
        $params['where']['id_user'] = intval($id_user);
        $params['where']['id_contact'] = intval($id_contact);
        $params['where']['id <'] = intval($from_id);
        $order_by['id'] = 'DESC';
        $messages = $this->_get($params, $count, $order_by);
        return $messages;
    }

    public function get_new_messages($id_user, $id_contact, $from_id)
    {
        $params['where']['id_user'] = intval($id_user);
        $params['where']['id_contact'] = intval($id_contact);
        $params['where']['id >'] = intval($from_id);
        $order_by['id'] = 'DESC';
        $messages = $this->_get($params, 1000, $order_by);
        return $messages;
    }

    public function check_is_read($id_user, $id_contact)
    {
        $id_user = intval($id_user);
        $id_contact = intval($id_contact);
        $this->DB->set('is_read', '1')->where('id_user', $id_user)->where('id_contact', $id_contact)->update(IM_MESSAGES_TABLE);
        $this->CI->load->model('im/models/Im_contact_list_model');
        $this->CI->Im_contact_list_model->update_contact($id_user, $id_contact, null, 0, false);
    }

    public function delete_messages($id_user, $id_contact)
    {
        $where['id_user'] = intval($id_user);
        $where['id_contact'] = intval($id_contact);
        $this->DB->where($where)->delete(IM_MESSAGES_TABLE);
        return $this->DB->affected_rows();
    }

    public function delete_message_by_id($id)
    {
        $this->DB->where('id', $id)->delete(IM_MESSAGES_TABLE);
        return $this->DB->affected_rows();
    }

    public function delete_message_by_user_id($user_id)
    {
        $this->DB->where('id_user', $user_id)->or_where('id_contact', $user_id)->delete(IM_MESSAGES_TABLE);

        $this->CI->load->model('im/models/Im_contact_list_model');
        $this->CI->Im_contact_list_model->remove_all_contact($user_id);
    }

    public function get_unread_count($id_user, $dir = null, $id_contact = null)
    {
        $this->DB->where('id_user', $id_user)->where('is_read', 0);
        if (!empty($id_contact)) {
            $this->DB->where('id_contact', $id_contact);
        }
        if (!empty($dir)) {
            $this->DB->where('dir', $dir);
        }
        $result = current($this->DB->select('COUNT(id) as cnt')
                        ->from(IM_MESSAGES_TABLE)->get()->result_array());
        return $result['cnt'];
    }

    /**
     * Get message from network
     * 
     * @param array $data message data
     * @return integer
     */
    public function handler_message($data)
    {
        $save_data = array(
            "id_user" => $data["id_user"], 
            "id_contact" => $data["id_contact"], 
            "dir" => "0",
            "text" => $data["text"],
        );
        
        return $this->saveMessage($save_data);
    }
}
