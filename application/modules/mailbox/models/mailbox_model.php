<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * Mailbox Model
 *
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @author Mikhail Chernov <mchernov@pilotgroup.net>
 * @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: mchernov $
 * */
define('MAILBOX_TABLE', DB_PREFIX . 'mailbox');
define('MAILBOX_ATTACHES_TABLE', DB_PREFIX . 'mailbox_attaches');

class Mailbox_model extends Model
{

    /**
     * Link to CodeIgniter object
     * @var object
     */
    private $CI;

    /**
     * Link to database object
     * @var object
     */
    private $DB;

    /**
     * Message data
     * @var array
     */
    private $fields = array(
        'id',
        'id_pair',
        'id_reply',
        'id_user',
        'id_from_user',
        'id_to_user',
        'subject',
        'message',
        'is_new',
        'date_add',
        'date_read',
        'date_trash',
        'id_thread',
        'folder',
        'from_folder',
        'from_spam',
        'attaches_count',
    );

    /**
     * Attaches data
     * @var array
     */
    private $attaches = array(
        'id',
        'id_message',
        'filename',
        'filesize',
        'date_add',
    );

    /**
     * Format settings
     * @var array
     */
    private $format_settings = array(
        'use_format' => true,
        'get_user' => true,
        'get_sender' => true,
        'get_recipient' => true,
        'get_message' => true,
        'get_attaches' => false,
    );

    /**
     * Folders
     * @var array
     */
    private $folders = array('inbox', 'outbox', 'drafts', 'trash', 'spam');

    /**
     * Moderation type
     * @var string
     */
    private $moderation_type = 'mailbox';

    /**
     * Upload file GUID
     * @var string
     */
    private $file_config_id = 'mailbox-attach';
    public $services_buy_gids = array('read_message_service', 'send_message_service');
    public $charset = 'UTF-8';

    /**
     * Constructor
     *
     * @return Mailbox_model object
     */
    public function __construct()
    {
        parent::__construct();
        $this->CI = &get_instance();
        $this->DB = &$this->CI->db;
    }

    //// messages functions

    /**
     * Return message by identifier
     * @param integer $message_id message identifier
     * @return array
     */
    public function get_message_by_id($message_id)
    {
        $params['where']['id'] = $message_id;
        if ($msgs = $this->get_messages($params, null, null)) {
            //return $this->format_message($msgs[0]);
            return $msgs[0];
        } else {
            return array();
        }
    }

    /**
     * Save message data to datasource
     * @param integer $message_id message identifier
     * @param array $data message data
     * @return integer
     */
    public function save_message($message_id, $data)
    {
        if (!empty($data['id_reply']) && empty($data['id_thread'])) {
            $this->set_format_settings('use_format', false);
            $message = $this->get_message_by_id($data['id_reply']);
            $this->set_format_settings('use_format', true);

            if (!$message['id_thread']) {
                $data['id_thread'] = $this->CI->pg_module->get_module_config('mailbox', 'thread_counter') + 1;

                $this->DB->where('id', $data['id_reply']);
                $this->DB->update(MAILBOX_TABLE, array('id_thread' => $data['id_thread']));

                if ($message['id_pair']) {
                    $this->DB->where('id', $message['id_pair']);
                    $this->DB->update(MAILBOX_TABLE, array('id_thread' => $data['id_thread']));
                }

                $this->CI->pg_module->set_module_config('mailbox', 'thread_counter', $data['id_thread']);
            }
        }
        if (is_null($message_id)) {
            if (!isset($data['date_add'])) {
                $data['date_add'] = date('Y-m-d H:i:s');
            }
            if (!isset($data['is_new'])) {
                $data['is_new'] = 1;
            }
            $this->DB->insert(MAILBOX_TABLE, $data);
            $message_id = $this->DB->insert_id();
        }else {
            $this->DB->where('id', $message_id);
            $this->DB->update(MAILBOX_TABLE, $data);
        }

        $this->_update_fulltext_field($message_id);

        return $message_id;
    }

    /**
     * Save attachement data to datasource
     * @param integer $message_id message identifier
     * @param array $data attachement data
     * @return integer
     */
    public function save_attach($attach_id, $data)
    {
        if (is_null($attach_id)) {
            $data['date_add'] = date('Y-m-d H:i:s');
            $this->DB->insert(MAILBOX_ATTACHES_TABLE, $data);
            $attach_id = $this->DB->insert_id();
        } else {
            $this->DB->where('id', $attach_id);
            $this->DB->update(MAILBOX_ATTACHES_TABLE, $data);
        }
        return $attach_id;
    }

    /**
     * Remove attaches from data source
     * @param array $attach_ids attachements identifiers
     * @param integer $message_id message identifier
     */
    public function delete_attaches($attach_ids, $message_id)
    {
        $this->DB->where_in('id', $attach_ids);
        $result = $this->DB->delete(MAILBOX_ATTACHES_TABLE);
        if ($result) {
            $this->dec_attach($message_id, $result);
        }
    }

    /**
     * Remove attach from data source
     * @param array $attach_id attachements identifiers
     * @param integer $message_id message identifier
     */
    public function delete_attach($attach_id, $message_id)
    {
        $this->delete_attaches(array($attach_id), $message_id);
    }

    /**
     * Save message data to datasource
     * @param integer $message_id message identifier
     * @param array $data message data
     * @return integer
     */
    public function inc_attach($message_id, $count = 1)
    {
        $this->DB->set('attaches_count', 'attaches_count + ' . $count, FALSE);
        $this->DB->where('id', $message_id);
        $this->DB->update(MAILBOX_TABLE);
    }

    /**
     * Save message data to datasource
     * @param integer $message_id message identifier
     * @param array $data message data
     * @return integer
     */
    public function dec_attach($message_id, $count = 1)
    {
        $this->DB->set("attaches_count", 'attaches_count - ' . $count, FALSE);
        $this->DB->where("id", $message_id);
        $this->DB->update(MAILBOX_TABLE);
    }

    /**
     * Send message
     * @param integer $message_id message identifier
     * @return string
     */
    public function send_message($message_id)
    {
        $user_id = $this->CI->session->userdata('user_id');

        $this->CI->load->model('mailbox/models/Mailbox_service_model');
        $mailbox_service = $this->CI->Mailbox_service_model->get_service_by_user_service($user_id, 'send_message_service');
        if ($mailbox_service['service_data']['message_count']) {
            $mailbox_service['service_data']['message_count'] --;
            $save_data = array('service_data' => $mailbox_service['service_data']);
            $validate_data = $this->CI->Mailbox_service_model->validate_service($mailbox_service['id'], $save_data);
            $this->CI->Mailbox_service_model->save_service($mailbox_service['id'], $validate_data['data']);
        } else {
            $return = $this->service_available_send_message($user_id);
            if (!$return['available']) {
                return false;
            }
        }

        $this->set_format_settings('use_format', false);
        $message = $this->get_message_by_id($message_id);
        $this->set_format_settings('use_format', true);

        // Network event
        if ($this->CI->pg_module->is_module_installed('network')) {
            $this->CI->load->model('network/models/Network_events_model');
            $this->CI->Network_events_model->emit('mail', array(
                'id_user' => $message['id_user'],
                'id_to' => $message['id_to_user'],
                'subject' => $message['subject'],
                'message' => $message['message'],
            ));
        }

        $message['id_pair'] = $message_id;
        $message['id_user'] = $message['id_to_user'];
        $message['is_new'] = 1;
        $message['attaches_count'] = 0;

        /* if($this->pg_module->is_module_installed('users_lists')){
          $this->CI->load->model('Users_lists_model');
          $recipient_blacklist = $this->CI->Users_lists_model->get_blacklist_users_ids($message['id_to_user']);
          if(in_array($user_id, $recipient_blacklist)){
          $message['folder'] = 'spam';
          $message['from_spam'] = 'inbox';
          $message['notified'] = 0;
          }else{
          $message['folder'] = 'inbox';
          $message['notified'] = 1;
          }
          }else */if ($this->pg_module->is_module_installed('blacklist')) {
            $this->CI->load->model('Blacklist_model');
            $recipient_blacklist = $this->CI->Blacklist_model->get_list_users_ids($message['id_to_user']);
            if (in_array($user_id, $recipient_blacklist)) {
                $message['folder'] = 'spam';
                $message['from_spam'] = 'inbox';
                $message['notified'] = 0;
            } else {
                $message['folder'] = 'inbox';
                $message['notified'] = 1;
            }
        } else {
            $message['folder'] = 'inbox';
            $message['notified'] = 1;
        }

        unset($message['id']);

        $new_id = $this->save_message(null, $message);

        $this->CI->load->model('File_uploads_model');

        $attaches = $this->_get_attaches(array($message_id));
        if (!empty($attaches[$message_id])) {
            foreach ($attaches[$message_id] as $attach) {
                $this->upload_attach_exists(null, $attach['path'], $new_id);
            }
        }

        $message = $this->format_message($message);
        $recipient = $this->Users_model->get_user_by_id($message['recipient']['id']);
        $mail_data = array(
            'fname' => $recipient['fname'],
            'sname' => $recipient['sname'],
            'sender' => $message['sender']['output_name'],
            'subject' => $message['subject'],
        );

        $this->load->model('Notifications_model');
        $this->CI->Notifications_model->send_notification($recipient['email'], 'mailbox_new_message', $mail_data, '', $recipient['lang_id']);

        $this->save_message($message_id, array('id_pair' => $new_id, 'folder' => 'outbox', 'notified' => 0));

        return true;
    }

    /**
     * Validate message data
     * @param integer $message_id message identifier
     * @param array $data message data
     * @return array
     */
    public function validate_message($message_id, $data)
    {
        $return = array('errors' => array(), 'data' => array());

        if (isset($data['id_pair'])) {
            $return['data']['id_pair'] = intval($data['id_pair']);
        }

        if (isset($data['id_user'])) {
            $return['data']['id_user'] = intval($data['id_user']);
        } elseif (!$message_id) {
            $return['errors'][] = l('error_empty_user', 'mailbox');
        }

        if (isset($data['id_from_user'])) {
            $return['data']['id_from_user'] = intval($data['id_from_user']);
        }

        if (isset($data['id_to_user'])) {
            $return['data']['id_to_user'] = intval($data['id_to_user']);
            if ($return['data']['id_to_user']) {
                $user_id = $this->CI->session->userdata('user_id');
                if ($return['data']['id_to_user'] == $user_id) {
                    $return['errors'][] = l('error_invalid_recipient', 'mailbox');
                }
            }
        }

        if (isset($data['id_reply'])) {
            $return['data']['id_reply'] = intval($data['id_reply']);
        }

        if (isset($data['subject'])) {
            $return['data']['subject'] = trim(strip_tags($data['subject']));
            if (empty($return['data']['subject'])) {
                $return['errors'][] = l('error_empty_subject', 'mailbox');
            } else {
                $this->CI->load->model('moderation/models/Moderation_badwords_model');
                $bw_count = $this->CI->Moderation_badwords_model->check_badwords($this->moderation_type, $return['data']['subject']);
                if ($bw_count) {
                    $return['errors'][] = l('error_badwords_subject', 'mailbox');
                }
            }
        } elseif (!$message_id) {
            $return['errors'][] = l('error_empty_subject', 'mailbox');
        }

        if (isset($data['message'])) {
            $return['data']['message'] = trim(strip_tags($data['message']));
        }

        if (isset($data['is_new'])) {
            $return['data']['is_new'] = $data['is_new'] ? 1 : 0;
        }

        if (isset($data['date_add'])) {
            $value = strtotime($data['date_add']);
            if ($value > 0) {
                $return['data']['date_add'] = date('Y-m-d', $value);
            } else {
                $return['data']['date_add'] = '0000-00-00 00:00:00';
            }
        }

        if (isset($data['date_read'])) {
            $value = strtotime($data['date_read']);
            if ($value > 0) {
                $return['data']['date_read'] = date('Y-m-d', $value);
            } else {
                $return['data']['date_read'] = '0000-00-00 00:00:00';
            }
        }

        if (isset($data['date_trash'])) {
            $value = strtotime($data['date_trash']);
            if ($value > 0) {
                $return['data']['date_trash'] = date('Y-m-d', $value);
            } else {
                $return['data']['date_trash'] = '0000-00-00 00:00:00';
            }
        }

        if (isset($data['id_thread'])) {
            $return['data']['id_thread'] = intval($data['id_thread']);
        }

        if (isset($data['folder']) && in_array($data['folder'], $this->folders)) {
            $return['data']['folders'] = $data['folder'];
        }

        if (isset($data['from_folder']) && in_array($data['from_folder'], $this->folders)) {
            $return['data']['from_folder'] = $data['from_folder'];
        }

        if (isset($data['from_spam']) && in_array($data['from_spam'], $this->folders)) {
            $return['data']['from_spam'] = $data['from_spam'];
        }

        if (isset($data['search_field'])) {
            $return['data']['search_field'] = trim(strip_tags($data['search_field']));
        }

        return $return;
    }

    /**
     * Validate draft data
     * @param integer $message_id message identifier
     * @param array $data message data
     * @return array
     */
    public function validate_draft($message_id, $data)
    {
        $return = array('errors' => array(), 'data' => array());

        if (isset($data['id_pair'])) {
            $return['data']['id_pair'] = intval($data['id_pair']);
        }

        if (isset($data['id_from_user'])) {
            $return['data']['id_from_user'] = intval($data['id_from_user']);
        }

        if (isset($data['id_to_user'])) {
            $return['data']['id_to_user'] = intval($data['id_to_user']);
        }

        if (isset($data['id_user'])) {
            $return['data']['id_user'] = intval($data['id_user']);
            if (isset($return['data']['id_to_user'])) {
                $user_id = $this->CI->session->userdata('user_id');
                if ($return['data']['id_to_user'] == $user_id) {
                    $return['data']['id_to_user'] = 0;
                }
            }
        }

        if (isset($data['id_reply'])) {
            $return['data']['id_reply'] = intval($data['id_reply']);
        }

        if (isset($data['subject'])) {
            $return['data']['subject'] = trim(strip_tags($data['subject']));
        }

        if (isset($data['message'])) {
            $return['data']['message'] = trim(strip_tags($data['message']));
        }

        if (isset($data['date_add'])) {
            $value = strtotime($data['date_add']);
            if ($value > 0) {
                $return['data']['date_add'] = date('Y-m-d', $value);
            } else {
                $return['data']['date_add'] = '0000-00-00 00:00:00';
            }
        }

        if (isset($data['is_new'])) {
            $return['data']['is_new'] = $data['is_new'] ? 1 : 0;
        }

        if (isset($data['id_thread'])) {
            $return['data']['id_thread'] = intval($data['id_thread']);
        }

        if (isset($data['folder']) && in_array($data['folder'], $this->folders)) {
            $return['data']['folder'] = $data['folder'];
        }

        return $return;
    }

    /**
     * Validate send data
     */
    public function validate_send($message_id)
    {
        $return = array('errors' => array(), 'data' => array());

        $message = $this->get_message_by_id($message_id);

        if (empty($message['id_to_user'])) {
            $return['errors'][] = l('error_empty_recipient', 'mailbox');
        } else {
            $this->CI->load->model('Users_model');
            $user = $this->CI->Users_model->get_user_by_id($message['id_to_user']);
            if (empty($user)) {
                $return['errors'][] = l('error_invalid_recipient', 'mailbox');
            }
        }

        $this->CI->load->model('moderation/models/Moderation_badwords_model');

        $message['subject'] = trim(strip_tags($message['subject']));
        if (empty($message['subject'])) {
            //$return['errors'][] = l('error_empty_subject', 'mailbox');
        } else {
            $bw_count = $this->CI->Moderation_badwords_model->check_badwords($this->moderation_type, $message['subject']);
            if ($bw_count) {
                $return['errors'][] = l('error_badwords_message', 'mailbox');
            }
        }

        $message['message'] = trim(strip_tags($message['message']));
        if (empty($message['message'])) {
            $return['errors'][] = l('error_empty_message', 'mailbox');
        } else {
            $bw_count = $this->CI->Moderation_badwords_model->check_badwords($this->moderation_type, $message['message']);
            if ($bw_count) {
                $return['errors'][] = l('error_badwords_message', 'mailbox');
            }
        }

        return $return;
    }

    public function validate_messages_ids($ids)
    {
        return array_unique(array_map('intval', $ids));
    }

    public function delete_message($id)
    {
        $this->delete_messages(array($id));
    }

    public function delete_messages($ids)
    {
        if (is_array($ids) && count($ids)) {
            $this->DB->where_in("id", $ids);
            $this->DB->delete(MAILBOX_TABLE);
            $this->delete_attaches_by_messages($ids);
        }
    }

    public function delete_attaches_by_messages($ids)
    {
        if (is_array($ids) && count($ids)) {
            $this->DB->where_in("id_message", $ids);
            $this->DB->delete(MAILBOX_ATTACHES_TABLE);
        }
    }

    /**
     * Remove thread
     * @param integer $thread_id thread identifier
     */
    public function delete_thread($user_id, $thread_id)
    {
        $this->DB->where_in("user_id", $user_id);
        $this->DB->where_in("thread_id", $thread_id);
        $this->DB->delete(MAILBOX_TABLE);
    }

    public function move_messages_in_folder($ids, $folder = 'trash')
    {
        if (is_array($ids) && count($ids)) {
            $this->DB->where_in('id', $ids);
            if ($folder == 'trash') {
                $this->DB->set('from_folder', 'folder', false);
                $this->DB->set('date_trash', date('Y-m-d H:i:s'));
            }
            if ($folder == 'spam') {
                $this->DB->set('from_spam', 'folder', false);
            }
            //$this->DB->set('is_new', 0);
            $this->DB->set('folder', $folder);
            $this->DB->update(MAILBOX_TABLE);
        }
    }

    public function unmark_spam_messages($ids)
    {
        if (is_array($ids) && count($ids)) {
            $this->DB->where_in('id', $ids);
            $this->DB->set('folder', 'from_spam', false);
            $this->DB->update(MAILBOX_TABLE);
        }
    }

    public function untrash_messages($ids)
    {
        if (is_array($ids) && count($ids)) {
            $this->DB->where_in('id', $ids);
            $this->DB->set('folder', 'from_folder', false);
            $this->DB->update(MAILBOX_TABLE);
        }
    }

    public function get_messages($params = array(), $page = 1, $items_on_page = 20, $order_by = null, $filter_object_ids = null)
    {
        $objects = array();
        $this->DB->select(implode(", ", $this->fields));
        $this->DB->from(MAILBOX_TABLE);

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
                $this->DB->where($value, null, false);
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
            $objects = $this->format_messages($results);
        }
        return $objects;
    }

    /**
     * Return number of new messages for user
     * @param integer $id_user user identifier
     * @return integer
     */
    public function get_new_messages_count($id_user, $folder = 'inbox')
    {
        $this->DB->select('COUNT(*) AS cnt')
                ->from(MAILBOX_TABLE)
                ->where('id_user', $id_user)
                ->where('is_new', 1)
                ->where('folder', $folder);

        $results = $this->DB->get()->result_array();
        if (!empty($results) && is_array($results)) {
            return intval($results[0]["cnt"]);
        }
        return 0;
    }

    /**
     * Return number of messages
     * @param array $params
     * @return array
     */
    public function get_messages_count($params = array())
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
                $this->DB->where($value, null, false);
            }
        }

        return $this->DB->count_all_results(MAILBOX_TABLE);
    }

    /**
     * Change format settings
     * @param string $name parameter name
     * @param mixed $value parameter value
     */
    public function set_format_settings($name, $value = false)
    {
        if (!is_array($name)) {
            $name = array($name => $value);
        }
        foreach ($name as $key => $item) {
            $this->format_settings[$key] = $item;
        }
    }

    /**
     * Format message data
     * @param array $data
     * @return array
     */
    public function format_messages($data)
    {
        if (!$this->format_settings['use_format']) {
            return $data;
        }

        if (!$this->CI->pg_module->is_module_installed('file_uploads')) {
            $this->format_settings['get_attaches'] = false;
        }

        $attach_ids = $user_ids = array();

        foreach ($data as $key => $item) {
            //get user
            if ($this->format_settings['get_user']) {
                if (!empty($item['id_user'])) {
                    $user_ids[] = $item['id_user'];
                }
            }
            //get sender
            if ($this->format_settings['get_sender']) {
                if (!empty($item['id_from_user'])) {
                    $user_ids[] = $item['id_from_user'];
                }
            }
            //get recipient
            if ($this->format_settings['get_recipient']) {
                if (!empty($item['id_to_user'])) {
                    $user_ids[] = $item['id_to_user'];
                }
            }

            //get attachements
            if ($this->format_settings['get_attaches']) {
                if ($data[$key]['attaches_count']) {
                    $attach_ids[] = $item['id'];
                }
            }

            //get message
            if ($this->format_settings['get_message']) {
                if (!empty($item['message'])) {
                    $data[$key]['message'] = nl2br($item['message']);
                }
            }

            //get subject
            if (empty($item['subject']) && !empty($item['message']) && function_exists('mb_convert_encoding')) {
                $data[$key]['subject'] = mb_convert_encoding($data[$key]['message'], $this->charset, 'auto');
                $data[$key]['subject'] = strip_tags($data[$key]['subject']);
                $this->CI->load->library('trackback');
                $data[$key]['subject'] = $this->CI->trackback->limit_characters($data[$key]['subject'], 50);
            }
        }

        if (!empty($user_ids)) {
            $this->CI->load->model('Users_model');
            $users = $this->CI->Users_model->get_users_list_by_key(null, null, null, array(), array_unique($user_ids), true, true);
            if ($this->format_settings['get_user']) {
                foreach ($data as $key => $message) {
                    if (!$message['id_user']) {
                        continue;
                    }
                    $data[$key]['user'] = (isset($users[$message['id_user']])) ? $users[$message['id_user']] :
                            $this->CI->Users_model->format_default_user($message['id_user'], '', 'mailbox');
                }
            }
            if ($this->format_settings['get_sender']) {
                foreach ($data as $key => $message) {
                    if (!$message['id_from_user']) {
                        continue;
                    }
                    $data[$key]['sender'] = (isset($users[$message['id_from_user']])) ? $users[$message['id_from_user']] :
                            $this->CI->Users_model->format_default_user($message['id_from_user'], '', 'mailbox');
                }
            }
            if ($this->format_settings['get_recipient']) {
                foreach ($data as $key => $message) {
                    if (!$message['id_to_user']) {
                        continue;
                    }
                    $data[$key]['recipient'] = (isset($users[$message['id_to_user']])) ? $users[$message['id_to_user']] :
                            $this->CI->Users_model->format_default_user($message['id_to_user'], '', 'mailbox');
                }
            }
        }

        if (!empty($attach_ids)) {
            //get attachements
            if ($this->format_settings['get_attaches']) {
                $attaches = $this->_get_attaches($attach_ids);
                foreach ($data as $key => $message) {
                    $data[$key]['attaches'] = isset($attaches[$message['id']]) ? $attaches[$message['id']] : array();
                }
            }
        }

        return $data;
    }

    /**
     * Format message
     * @param array $data message data
     * @return array
     */
    public function format_message($data)
    {
        $return = $this->format_messages(array($data));
        return $return[0];
    }

    public function get_seo_settings($method = '', $lang_id = '')
    {
        if (!empty($method)) {
            return $this->_get_seo_settings($method, $lang_id);
        } else {
            $actions = array('write', 'inbox', 'outbox', 'drafts', 'trash', 'spam', 'index');
            $return = array();
            foreach ($actions as $action) {
                $return[$action] = $this->_get_seo_settings($action, $lang_id);
            }
            return $return;
        }
    }

    private function _get_seo_settings($method, $lang_id = '')
    {
        switch ($method) {
            case 'index':
            case 'inbox':
                return array(
                    'templates' => array(),
                    'url_vars' => array(),
                    'url_postfix' => array(
                        'page' => array('page' => 'numeric'),
                    ),
                    'optional' => array(),
                );
                break;
            case 'write':
                return array(
                    'templates' => array(),
                    'url_vars' => array(),
                    'url_postfix' => array(
                        'id' => array('id' => 'numeric'),
                    ),
                    'optional' => array(),
                );
                break;
            case 'outbox':
                return array(
                    'templates' => array(),
                    'url_vars' => array(),
                    'url_postfix' => array(
                        'page' => array('page' => 'numeric'),
                    ),
                    'optional' => array(),
                );
                break;
            case 'drafts':
                return array(
                    'templates' => array(),
                    'url_vars' => array(),
                    'url_postfix' => array(
                        'page' => array('page' => 'numeric'),
                    ),
                    'optional' => array(),
                );
                break;
            case 'trash':
                return array(
                    'templates' => array(),
                    'url_vars' => array(),
                    'url_postfix' => array(
                        'page' => array('page' => 'numeric'),
                    ),
                    'optional' => array(),
                );
                break;
            case 'spam':
                return array(
                    'templates' => array(),
                    'url_vars' => array(),
                    'url_postfix' => array(
                        'page' => array('page' => 'numeric'),
                    ),
                    'optional' => array(),
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
        $return = array();
        return $return;
    }

    public function get_sitemap_urls()
    {
        $this->CI->load->helper('seo');
        $auth = $this->CI->session->userdata("auth_type");

        $block[] = array(
            "name" => l('header_mailbox', 'mailbox'),
            "link" => rewrite_link('mailbox', 'index'),
            "clickable" => ($auth == "user"),
            "items" => array(
                array(
                    "name" => l('header_mailbox_write', 'mailbox'),
                    "link" => rewrite_link('mailbox', 'write'),
                    "clickable" => ($auth == "user"),
                ),
                array(
                    "name" => l('header_mailbox_outbox', 'mailbox'),
                    "link" => rewrite_link('mailbox', 'outbox'),
                    "clickable" => ($auth == "user"),
                ),
                array(
                    "name" => l('header_mailbox_drafts', 'mailbox'),
                    "link" => rewrite_link('mailbox', 'drafts'),
                    "clickable" => ($auth == "user"),
                ),
                array(
                    "name" => l('header_mailbox_trash', 'mailbox'),
                    "link" => rewrite_link('mailbox', 'trash'),
                    "clickable" => ($auth == "user"),
                ),
                array(
                    "name" => l('header_mailbox_spam', 'mailbox'),
                    "link" => rewrite_link('mailbox', 'spam'),
                    "clickable" => ($auth == "user"),
                ),
            )
        );
        return $block;
    }

    /**
     * Return attachements list
     * @param integer $message_id message identifier
     * @return array
     */
    private function _get_attaches($message_ids)
    {
        $this->DB->select(implode(", ", $this->attaches));
        $this->DB->from(MAILBOX_ATTACHES_TABLE);
        $this->DB->where_in('id_message', $message_ids);
        $results = $this->DB->get()->result_array();
        if (!empty($results) && is_array($results)) {
            $this->CI->load->model('File_uploads_model');
            $data = array();
            foreach ($results as $key => $result) {
                $format = $this->CI->File_uploads_model->format_upload($this->file_config_id, $result['id_message'] . '/', $result['filename']);

                $result['path'] = $format['file_path'];
                $result['link'] = $format['file_url'];
                $result['name'] = $result['filename'];
                $result['size'] = $result['filesize'];

                $i = 1;
                while ($result['size'] > 1000) {
                    $result['size'] /= 1024;
                    $i++;
                }
                $result['size'] = ceil($result['size']) . ' ' . l('text_filesize_' . $i, 'mailbox');
                $data[$result['id_message']][] = $result;
            }
            return $data;
        }
        return array();
    }

    /**
     * Return attachements list
     * @param integer $message_id message identifier
     * @return array
     */
    private function _get_attaches_count($message_ids)
    {
        $this->DB->select('COUNT(*) AS cnt');
        $this->DB->from(MAILBOX_ATTACHES_TABLE);
        $this->DB->where_in('id_message', (array) $message_ids);
        $results = $this->DB->get()->result_array();
        if (!empty($results) && is_array($results)) {
            return $results[0]['cnt'];
        }
        return array();
    }

    /**
     * Save attachement
     * @param integer $attach_id attachement identifier
     * @param integer $message_id message identifier
     * @param string $name upload name
     * @return void
     */
    public function upload_attach($attach_id, $filename, $message_id)
    {
        $this->CI->load->model('File_uploads_model');
        $file_return = $this->CI->File_uploads_model->upload($this->file_config_id, $message_id . '/', $filename);
        $format = $this->CI->File_uploads_model->format_upload($this->file_config_id, $message_id . '/', $file_return['file']);
        $save_data['id_message'] = $message_id;
        $save_data['filename'] = $file_return['file'];
        $save_data['filesize'] = filesize($format['file_path']);
        $attach_id = $this->save_attach(null, $save_data);
        $this->inc_attach($message_id);
        $return = $save_data;
        $return['id'] = $attach_id;
        $return['format'] = $format;
        return $return;
    }

    /**
     * Copy attachement
     * @param integer $attach_id attachement identifier
     * @param integer $message_id message identifier
     * @param string $name upload name
     * @return void
     */
    public function upload_attach_exists($attach_id, $filepath, $message_id)
    {
        $this->CI->load->model('File_uploads_model');
        $file_return = $this->CI->File_uploads_model->upload_exist($this->file_config_id, $message_id . '/', $filepath);
        $format = $this->CI->File_uploads_model->format_upload($this->file_config_id, $message_id . '/', $file_return['file']);
        $save_data['id_message'] = $message_id;
        $save_data['filename'] = $file_return['file'];
        $save_data['filesize'] = filesize($format['file_path']);
        $attach_id = $this->save_attach(null, $save_data);
        $this->inc_attach($message_id);
        $return = $save_data;
        $return['id'] = $attach_id;
        $return['format'] = $format;
        return $return;
    }

    /**
     * Validate attachement
     * @param integer $attach_id attachment identifier
     * @param integer $message_id message identifier
     * @param string $name upload name
     * @return array
     */
    public function validate_attach($attach_id, $filename, $message_id)
    {
        $return = array('errors' => array(), 'data' => array());

        $this->CI->load->model('File_uploads_model');

        if ($message_id) {
            $attach_count = $this->_get_attaches_count(array($message_id));
        } else {
            $attach_count = 0;
        }

        $validation_upload = $this->CI->File_uploads_model->validate_upload($this->file_config_id, $filename);
        $attach_limit = $this->CI->pg_module->get_module_config('mailbox', 'attach_limit');
        if ($attach_count + 1 > $attach_limit) {
            if (!isset($validation_upload['error']) || empty($validation_upload['error'])) {
                $validation_upload['error'] = array();
            } else {
                $validation_upload['error'] = (array) $validation_upload['error'];
            }
            $validation_upload['error'][] = l('error_max_attaches_reached', 'mailbox');
        }
        if (isset($validation_upload['error']) && !empty($validation_upload['error'])) {
            $return['errors'] = (array) $validation_upload['error'];
        } else {
            $return['data'] = $validation_upload['data'];
        }

        return $return;
    }

    /**
     * Return attachment settings
     * @result array
     */
    public function get_attach_settings()
    {
        $this->CI->load->model('File_uploads_model');
        $file_type_config = $this->CI->File_uploads_model->get_config($this->file_config_id);
        return $file_type_config;
    }

    /**
     * Available access mailbox service
     * @param integer $id_user user identifier
     * @return array
     */
    public function service_available_access_mailbox($id_user)
    {
        $return['available'] = 0;
        $return['content'] = '';
        $return['content_buy_block'] = false;

        if ($this->CI->pg_module->is_module_installed('services')) {
            $this->CI->load->model('Services_model');
            $services_params = array();
            $services_params['where']['gid'] = 'access_mailbox_service';
            $services_params['where']['status'] = 1;
            $services_count = $this->CI->Services_model->get_service_count($services_params);
            if ($services_count) {
                $return['available'] = 0;
                $return['content_buy_block'] = true;
            } else {
                $return['content'] = l('service_not_found', 'services');
                $return['available'] = 1;
            }
        } else {
            $return['content'] = l('service_not_found', 'services');
            $return['available'] = 1;
        }

        return $return;
    }

    /**
     * Validate access mailbox service
     * @param ineteger $user_id user identifier
     * @param array $data user service data
     * @param array $service_data service data
     * @param float $price service price
     */
    public function service_validate_access_mailbox($user_id, $data, $service_data = array(), $price = '')
    {
        $return = array('errors' => array(), 'data' => $data);
        return $return;
    }

    /**
     * Buy access mailbox service
     * @param integer $id_user user identifier
     * @param float $price service price
     * @param array $service service data
     * @param array $template template data
     * @param integer $user_package_id package identifier
     * @param integer $count number of services
     */
    public function service_buy_access_mailbox($id_user, $price, $service, $template, $payment_data, $users_package_id = 0, $count = 1)
    {
        $this->service_set_access_mailbox($id_user);
    }

    /**
     * Save access mailbox service
     * @param integer $user_id user identifier
     */
    public function service_set_access_mailbox($user_id)
    {
        $this->CI->load->model('mailbox/models/Mailbox_service_model');
        $mailbox_service = $this->CI->Mailbox_service_model->get_service_by_user_service($user_id, 'access_mailbox_service');
        if ($mailbox_service) {
            return;
        }
        $save_data = array('id_user' => $user_id, 'gid_service' => 'access_mailbox_service');
        $validate_data = $this->CI->Mailbox_service_model->validate_service($mailbox_service['id'], $save_data);
        $this->CI->Mailbox_service_model->save_service($mailbox_service['id'], $validate_data['data']);
    }

    /**
     * Available read message service
     * @param integer $id_user user identifier
     * @return array
     */
    public function service_available_read_message($id_user)
    {
        $return['available'] = 0;
        $return['content'] = '';
        $return['content_buy_block'] = false;

        if ($this->CI->pg_module->is_module_installed('services')) {
            $this->CI->load->model('Services_model');
            $services_params = array();
            $services_params['where']['gid'] = 'read_message_service';
            $services_params['where']['status'] = 1;
            $services_count = $this->CI->Services_model->get_service_count($services_params);
            if ($services_count) {
                $return['available'] = 0;
                $return['content_buy_block'] = true;
            } else {
                $return['content'] = l('service_not_found', 'services');
                $return['available'] = 1;
            }
        } else {
            $return['content'] = l('service_not_found', 'services');
            $return['available'] = 1;
        }

        return $return;
    }

    /**
     * Validate read message service
     * @param ineteger $user_id user identifier
     * @param array $data user service data
     * @param array $service_data service data
     * @param float $price service price
     */
    public function service_validate_read_message($user_id, $data, $service_data = array(), $price = '')
    {
        $return = array('errors' => array(), 'data' => $data);
        return $return;
    }

    /**
     * Buy read message service
     * @param integer $id_user user identifier
     * @param float $price service price
     * @param array $service service data
     * @param array $template template data
     * @param integer $user_package_id package identifier
     * @param integer $count number of services
     */
    public function service_buy_read_message($id_user, $price, $service, $template, $payment_data, $users_package_id = 0, $count = 1)
    {
        $service_data = array(
            'id_user' => $id_user,
            'service_gid' => $service['gid'],
            'template_gid' => $template['gid'],
            'service' => $service,
            'template' => $template,
            'payment_data' => $payment_data,
            'id_users_package' => $users_package_id,
            'id_users_membership' => !empty($payment_data['id_users_membership']) ? (int) $payment_data['id_users_membership'] : 0,
            'status' => 1,
            'count' => $count,
        );
        $this->CI->load->model('services/models/Services_users_model');
        return $this->CI->Services_users_model->save_service(null, $service_data);
    }

    /**
     * Activate read message service
     * @param integer $id_user user identifier
     * @param integer $id_user_service user service identifier
     * @param integer $message_id message identifier
     */
    public function service_activate_read_message($id_user, $id_user_service)
    {
        $id_user_session = $this->CI->session->userdata('user_id');
        $id_user_service = intval($id_user_service);
        $return = array('status' => 0, 'message' => '');

        $this->CI->load->model('services/models/Services_users_model');
        $user_service = $this->CI->Services_users_model->get_user_service_by_id($id_user_session, $id_user_service);
        if ($id_user_session != $id_user || empty($user_service) || !$user_service["status"] || $user_service['count'] < 1) {
            $return['status'] = 0;
            $return['message'] = l('error_service_activating', 'services');
        } else {
            $this->service_set_read_message($id_user, $user_service['service']['data_admin']['message_count']);
            $user_service['count'] --;
            if ($user_service['count'] < 1) {
                $user_service['status'] = 0;
            }
            $this->CI->Services_users_model->save_service($id_user_service, $user_service);
            $return['status'] = 1;
            $return['message'] = l('success_service_activating', 'services');

            // send notification
            $this->CI->load->model('Users_model');
            $user = $this->CI->Users_model->get_user_by_id($id_user_session);

            $mail_data = array(
                'fname' => $user['fname'],
                'sname' => $user['sname'],
                'name' => l('service_string_name_read_message', 'mailbox'),
            );

            //$this->CI->load->model("Notifications_model");
            //$this->CI->Notifications_model->send_notification($user['email'], 'mailbox_service_enabled', $mail_data);
        }
        return $return;
    }

    /**
     * Save read message service
     * @param integer $user_id user identifier
     */
    public function service_set_read_message($user_id, $message_count)
    {
        $this->CI->load->model('mailbox/models/Mailbox_service_model');

        $mailbox_service = $this->CI->Mailbox_service_model->get_service_by_user_service($user_id, 'read_message_service');
        if ($mailbox_service) {
            $save_data = array('service_data' => $mailbox_service['service_data']);
            $save_data['service_data']['message_count'] += $message_count;
        } else {
            $save_data = array('id_user' => $user_id, 'gid_service' => 'read_message_service');
            $save_data['service_data'] = array('message_count' => $message_count);
        }
        $validate_data = $this->CI->Mailbox_service_model->validate_service($mailbox_service['id'], $save_data);
        $this->CI->Mailbox_service_model->save_service($mailbox_service['id'], $validate_data['data']);
    }

    /**
     * Check availability of send message service
     * @param integer $id_user user identifier
     * @return array
     */
    public function service_available_send_message($id_user)
    {
        $return['available'] = 0;
        $return['content'] = '';
        $return['content_buy_block'] = false;

        if ($this->CI->pg_module->is_module_installed('services')) {
            $this->CI->load->model('Services_model');
            $services_params = array();
            $services_params['where']['gid'] = 'send_message_service';
            $services_params['where']['status'] = 1;
            $services_count = $this->CI->Services_model->get_service_count($services_params);
            if ($services_count) {
                $return['available'] = 0;
                $return['content_buy_block'] = true;
            } else {
                $return['content'] = l('service_not_found', 'services');
                $return['available'] = 1;
            }
        } else {
            $return['content'] = l('service_not_found', 'services');
            $return['available'] = 1;
        }

        return $return;
    }

    /**
     * Validate send message service
     * @param integer $user_id user identifier
     * @param array $data user data
     * @param array $service_data service data
     * @param float $price service price
     * @return array
     */
    public function service_validate_send_message($user_id, $data, $service_data = array(), $price = '')
    {
        $return = array('errors' => array(), 'data' => $data);
        return $return;
    }

    /**
     * Buy send message service
     * @param integer $id_user user identifier
     * @param array $service service data
     * @param array $template template data
     * @param array $payment_data user payment data
     * @param integer $user_package_id package identifier
     * @return integer
     */
    public function service_buy_send_message($id_user, $price, $service, $template, $payment_data, $users_package_id = 0, $count = 1)
    {
        $service_data = array(
            'id_user' => $id_user,
            'service_gid' => $service['gid'],
            'template_gid' => $template['gid'],
            'service' => $service,
            'template' => $template,
            'payment_data' => $payment_data,
            'id_users_package' => $users_package_id,
            'id_users_membership' => !empty($payment_data['id_users_membership']) ? (int) $payment_data['id_users_membership'] : 0,
            'status' => 1,
            'count' => $count,
        );
        $this->CI->load->model('services/models/Services_users_model');
        return $this->CI->Services_users_model->save_service(null, $service_data);
    }

    /**
     * Activate send message service
     * @param integer $id_user user identifier
     * @param integer $id_user_service user service identifier
     * @return array
     */
    public function service_activate_send_message($id_user, $id_user_service)
    {
        $id_user_session = $this->CI->session->userdata('user_id');
        $id_user_service = intval($id_user_service);
        $return = array('status' => 0, 'message' => '');

        $this->CI->load->model('services/models/Services_users_model');
        $user_service = $this->CI->Services_users_model->get_user_service_by_id($id_user_session, $id_user_service);
        if ($id_user_session != $id_user || empty($user_service) || !$user_service["status"] || $user_service['count'] < 1) {
            $return['status'] = 0;
            $return['message'] = l('error_service_activating', 'services');
        } else {
            $this->service_set_send_message($id_user, $user_service['service']['data_admin']['message_count']);
            $user_service['count'] --;
            if ($user_service['count'] < 1) {
                $user_service["status"] = 0;
            }
            $this->CI->Services_users_model->save_service($id_user_service, $user_service);
            $return['status'] = 1;
            $return['message'] = l('success_service_activating', 'services');

            // send notification
            $this->CI->load->model('Users_model');
            $user = $this->CI->Users_model->get_user_by_id($id_user_session);

            $mail_data = array(
                'fname' => $user['fname'],
                'sname' => $user['sname'],
                'name' => l('service_string_name_send_message', 'mailbox'),
            );

            //$this->CI->load->model("Notifications_model");
            //$this->CI->Notifications_model->send_notification($user['email'], 'mailbox_service_enabled', $mail_data);
        }
        return $return;
    }

    /**
     * Save send message service
     * @param integer $user_id user identifier
     * @paran integer $message_count number of available actions
     */
    public function service_set_send_message($user_id, $message_count)
    {
        $this->CI->load->model('mailbox/models/Mailbox_service_model');

        $mailbox_service = $this->CI->Mailbox_service_model->get_service_by_user_service($user_id, 'send_message_service');
        if ($mailbox_service) {
            $save_data = array('service_data' => $mailbox_service['service_data']);
            $save_data['service_data']['message_count'] += $message_count;
        } else {
            $save_data = array('id_user' => $user_id, 'gid_service' => 'send_message_service');
            $save_data['service_data'] = array('message_count' => $message_count);
        }

        $validate_data = $this->CI->Mailbox_service_model->validate_service($mailbox_service['id'], $save_data);
        $this->CI->Mailbox_service_model->save_service($mailbox_service['id'], $validate_data['data']);
    }

    /**
     * Return backend notifications
     */
    public function backend_get_request_notifications()
    {
        $user_id = $this->CI->session->userdata('user_id');
        $params['where']['id_user'] = $user_id;
        $params['where']['is_new'] = 1;
        $params['where']['folder'] = 'inbox';
        $params['where']['notified'] = 1;
        $messages = $this->get_messages($params);
        $this->CI->load->helper('seo_helper');
        $result = array('notifications' => array(), 'inbox_new_message' => 0);
        foreach ($messages as $message) {
            $link = site_url() . 'mailbox/view/' . $message['id'];
            $result['notifications'][] = array(
                'title' => l('notify_title', 'mailbox'),
                'text' => str_replace('[sender]', $message['sender']['output_name'], l('notify_text', 'mailbox')),
                'id' => $message['id'],
                'comment' => '',
                'user_id' => $message['user']['id'],
                'user_name' => $message['sender']['output_name'],
                'user_icon' => $message['sender']['media']['user_logo']['thumbs']['small'],
                'user_link' => $link,
                'more' => '<a href="' . $link . '">' . l('link_message_show', 'mailbox') . '</a>',
            );
        }
        $this->DB->set('notified', '0')->where($params['where'])->update(MAILBOX_TABLE);

        $result['inbox_new_message'] = $this->get_new_messages_count($user_id, 'inbox');

        return $result;
    }

    /**
     * Clear trash
     */
    public function mailbox_trash_cron()
    {
        $trash_period = intval($this->CI->pg_module->get_module_config('mailbox', 'trash_period'));
        $params = array();
        $params['where']['folder'] = 'trash';
        $params['where_sql'][] = 'DATE_ADD(date_trash, INTERVAL ' . $trash_period . ' DAY) < ' . $this->DB->escape(date('Y-m-d H:i:s'));
        $this->set_format_settings('use_format', false);
        $results = $this->get_messages($params, null, null);
        $this->set_format_settings('use_format', true);
        if (empty($results)) {
            return;
        }
        foreach ($results as $i => $result) {
            $results[$i] = $result['id'];
        }
        $this->delete_messages($results);
    }

    /**
     * Update data of fulltext search
     * @param integer $message_id message identifier
     */
    private function _update_fulltext_field($message_id)
    {
        $all_langs = $this->CI->pg_language->return_langs();
        $default_lang = $this->CI->pg_language->get_default_lang_id();

        $message = $this->get_message_by_id($message_id);

        $current_lang = $return['object_lang_id'] = $message['user']['lang_id'];
        $langs[$current_lang] = $all_langs[$current_lang];
        if ($current_lang != $default_lang) {
            $langs[$default_lang] = $all_langs[$default_lang];
        };

        $hide_user_names = $this->CI->pg_module->get_module_config('users', 'hide_user_names');

        if ($message['folder'] == 'inbox' || $message['folder'] == 'spam') {
            $content = $message['sender']['output_name'];
            if (!$hide_user_names) {
                $content = $message['sender']['nickname'] . ' ' . $content;
            }
        }else {
            $content = $message['recipient']['output_name'];
            if (!$hide_user_names) {
                $content = $message['recipient']['nickname'] . ' ' . $content;
            }
        }

        $content .= ' ' . $message['subject'];

        $validate_data = $this->validate_message($message_id, array('search_field' => $content));
        if (!empty($validate_data['errors'])) {
            return '';
        }
        $this->DB->where('id', $message_id);
        $this->DB->update(MAILBOX_TABLE, $validate_data['data']);
    }

    /**
     * Return criteria of fulltext search
     * @param string $text search keyword
     * @param string $mode search mode
     */
    public function return_fulltext_criteria($text, $mode = null)
    {
        $text = $this->DB->escape($text);
        $fields = $this->settings['fulltext_field'];
        $mode = ($mode ? " IN " . $mode : "");
        $return = array('where_sql' => array("MATCH (search_field) AGAINST (" . $text . $mode . ")"));
        return $return;
    }

    /**
     * Check messages owner is user
     * @param $message_ids set of messages
     * @param integer $user_id user identifier
     */
    public function is_user_messages($message_ids, $user_id)
    {
        if (empty($message_ids)) {
            return true;
        }
        $this->DB->select('COUNT(*) AS cnt')
                ->where_in('id', (array) $message_ids)
                ->where('id_user', $user_id);
        return $this->DB->count_all_results(MAILBOX_TABLE) == count((array) $message_ids);
    }

    /**
     * Availables banners places (callback method)
     * @return array
     */
    public function _banner_available_pages()
    {
        $return[] = array('link' => 'mailbox/inbox', 'name' => l('header_mailbox_inbox', 'mailbox'));
        $return[] = array('link' => 'mailbox/outbox', 'name' => l('header_mailbox_outbox', 'mailbox'));
        $return[] = array('link' => 'mailbox/drafts', 'name' => l('header_mailbox_drafts', 'mailbox'));
        $return[] = array('link' => 'mailbox/spam', 'name' => l('header_mailbox_spam', 'mailbox'));
        $return[] = array('link' => 'mailbox/trash', 'name' => l('header_mailbox_trash', 'mailbox'));
        $return[] = array('link' => 'mailbox/write', 'name' => l('header_mailbox_write', 'mailbox'));
        $return[] = array('link' => 'mailbox/edit', 'name' => l('header_mailbox_edit', 'mailbox'));
        $return[] = array('link' => 'mailbox/view', 'name' => l('header_mailbox_view', 'mailbox'));
        return $return;
    }

    public function handler_mail($data)
    {
        $message_data = array(
            'id_user' => $data['id_to'],
            'id_from_user' => $data['id_user'],
            'id_to_user' => $data['id_user'],
            'subject' => $data['subject'],
            'message' => $data['message'],
            'is_new' => 1,
            'folder' => 'inbox',
        );
        return $this->send_message($this->save_message(null, $message_data));
    }

	/**
     *  Module category action
     *  @return array
     */
    public function moduleCategoryAction()
    {
        $action = array(
            'name' => l('btn_send', 'mailbox'),
            'helper' => 'send_message_button',
        );
        return $action;
    }

}
