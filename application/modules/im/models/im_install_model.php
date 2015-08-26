<?php

namespace Pg\Modules\Im\Models;

/**
 * IM install model
 *
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @author Dmitry Popenov
 * @version $Revision: 2 $ $Date: 2013-01-30 10:50:07 +0400 $
 * */
class Im_install_model extends \Model
{

    protected $CI;
    protected $menu = array(
        'admin_menu' => array(
            'action' => 'none',
            'name' => '',
            'items' => array(
                'other_items' => array(
                    'action' => 'none',
                    'name' => '',
                    'items' => array(
                        "add_ons_items" => array(
                            "action" => "none",
                            'name' => '',
                            "items" => array(
                                "im_menu_item" => array("action" => "create", "link" => "admin/im", "status" => 1, "sorter" => 4),
                            ),
                        ),
                    ),
                ),
            ),
        ),
        'admin_im_menu' => array(
            'action' => 'create',
            'name' => 'IM menu',
            'items' => array(
                'im_settings' => array('action' => 'create', 'link' => 'admin/im', 'status' => 1, "sorter" => 1),
            ),
        ),
    );
    protected $lang_services = array(
        'service' => array('im'),
        'template' => array('im_template'),
        'admin_param' => array(
            'im_template' => array('period'),
        ),
    );
    protected $moderation_types = array(
        array(
            "name" => "im",
            "mtype" => "-1",
            "module" => "im",
            "model" => "Im",
            "check_badwords" => "1",
            "method_get_list" => "",
            "method_set_status" => "",
            "method_delete_object" => "",
            "allow_to_decline" => "0",
            "template_list_row" => "",
        )
    );
    
    /**
     * Network events configuration
     * 
     * @var array
     */
    protected $network_event_handlers = array(
		array(
			'event' => 'im.message',
			'module' => 'im',
			'model' => 'Im_messages_model',
			'method' => 'handler_message',
		),
	);

    public function __construct()
    {
        parent::__construct();
        $this->CI = &get_instance();
    }

    public function install_menu()
    {
        $this->CI->load->helper('menu');

        foreach ($this->menu as $gid => $menu_data) {
            $this->menu[$gid]['id'] = linked_install_set_menu($gid, $menu_data['action'], $menu_data['name']);
            linked_install_process_menu_items($this->menu, 'create', $gid, 0, $this->menu[$gid]['items']);
        }
    }

    public function install_menu_lang_update($langs_ids = null)
    {
        if (empty($langs_ids)) {
            return false;
        }
        $langs_file = $this->CI->Install_model->language_file_read('im', 'menu', $langs_ids);

        if (!$langs_file) {
            log_message('info', 'Empty menu langs data');
            return false;
        }

        $this->CI->load->helper('menu');

        foreach ($this->menu as $gid => $menu_data) {
            linked_install_process_menu_items($this->menu, 'update', $gid, 0, $this->menu[$gid]['items'], $gid, $langs_file);
        }
        return true;
    }

    public function install_menu_lang_export($langs_ids)
    {
        if (empty($langs_ids)) {
            return false;
        }
        $this->CI->load->helper('menu');

        $return = array();
        foreach ($this->menu as $gid => $menu_data) {
            $temp = linked_install_process_menu_items($this->menu, 'export', $gid, 0, $this->menu[$gid]['items'], $gid, $langs_ids);
            $return = array_merge($return, $temp);
        }
        return array('menu' => $return);
    }

    public function deinstall_menu()
    {
        $this->CI->load->helper('menu');
        foreach ($this->menu as $gid => $menu_data) {
            if ($menu_data['action'] == 'create') {
                linked_install_set_menu($gid, 'delete');
            } else {
                linked_install_delete_menu_items($gid, $this->menu[$gid]['items']);
            }
        }
    }

    /**
     * Check system requirements of module
     */
    public function _validate_requirements()
    {
        $result = array("data" => array(), "result" => true);

        //check for Mbstring
        $good = function_exists("mb_substr");
        $result["data"][] = array(
            "name" => "Mbstring extension (required for feeds parsing) is installed",
            "value" => $good ? "Yes" : "No",
            "result" => $good,
        );
        $result["result"] = $result["result"] && $good;

        return $result;
    }

    public function install_users()
    {
        $this->CI->load->model('users/models/Users_statuses_model');
        $this->CI->Users_statuses_model->add_callback('im', 'im_contact_list_model', 'callback_update_contacts_statuses');
    }

    public function deinstall_users()
    {
        $this->CI->load->model('users/models/Users_statuses_model');
        $this->CI->Users_statuses_model->delete_callbacks_by_module('im');
    }

    public function install_friendlist()
    {
        $this->CI->load->model('friendlist/models/Friendlist_callbacks_model');
        $this->CI->Friendlist_callbacks_model->add_callback('im', 'im_contact_list_model', 'callback_update_contact_list');
        $this->CI->load->model('im/models/Im_contact_list_model');
        $this->CI->Im_contact_list_model->_import_friendlist();
    }

    public function deinstall_friendlist()
    {
        $this->CI->load->model('friendlist/models/Friendlist_callbacks_model');
        $this->CI->Friendlist_callbacks_model->delete_callbacks_by_module('im');
    }

    public function install_services()
    {
        // add service type and service
        // create service template and service
        $this->CI->load->model('Services_model');
        $template_data = array(
            'gid' => 'im_template',
            'callback_module' => 'im',
            'callback_model' => 'Im_model',
            'callback_buy_method' => 'service_buy_im',
            'callback_activate_method' => 'service_activate_im',
            'callback_validate_method' => 'service_validate_im',
            'price_type' => 1,
            'data_admin' => array('period' => 'int'),
            'data_user' => '',
            'date_add' => date('Y-m-d H:i:s'),
            'moveable' => 0,
            'alert_activate' => 0,
            'is_membership' => 1,
            'data_membership' => array(),
        );
        $validated_tpl = $this->CI->Services_model->validate_template(null, $template_data);
        if (empty($validated_tpl['errors'])) {
            $this->CI->Services_model->save_template(null, $validated_tpl['data']);
        }

        $service_data = array(
            'gid' => 'im',
            'template_gid' => 'im_template',
            'pay_type' => 2,
            'status' => 1,
            'price' => 10,
            'type' => 'tariff',
            'data_admin' => array('period' => '30'),
            'date_add' => date('Y-m-d H:i:s')
        );
        $validated_srvs = $this->CI->Services_model->validate_service(null, $service_data);
        if (empty($validated_srvs['errors'])) {
            $this->CI->Services_model->save_service(null, $validated_srvs['data']);
        }
    }

    public function install_services_lang_update($langs_ids = null)
    {
        if (!is_array($langs_ids)) {
            $langs_ids = (array) $langs_ids;
        }
        $this->CI->load->model('Services_model');
        $langs_file = $this->CI->Install_model->language_file_read('im', 'services', $langs_ids);
        $this->CI->Services_model->update_langs($this->lang_services, $langs_file);
        return true;
    }

    public function install_services_lang_export($langs_ids = null)
    {
        if (!is_array($langs_ids)) {
            $langs_ids = (array) $langs_ids;
        }
        $this->CI->load->model('Services_model');
        return array('services' => $this->CI->Services_model->export_langs($this->lang_services, $langs_ids));
    }

    public function deinstall_services()
    {
        $this->CI->load->model("Services_model");
        $this->CI->Services_model->delete_template_by_gid('im_template');
        $this->CI->Services_model->delete_service_by_gid('im');
    }

    public function install_moderation()
    {
        $this->CI->load->model('moderation/models/Moderation_type_model');
        foreach ($this->moderation_types as $mtype) {
            $mtype['date_add'] = date("Y-m-d H:i:s");
            $this->CI->Moderation_type_model->save_type(null, $mtype);
        }
    }

    public function install_moderation_lang_update($langs_ids = null)
    {
        if (!is_array($langs_ids)) {
            $langs_ids = (array) $langs_ids;
        }
        $langs_file = $this->CI->Install_model->language_file_read('im', 'moderation', $langs_ids);

        if (!$langs_file) {
            log_message('info', 'Empty moderation langs data');
            return false;
        }
        $this->CI->load->model('moderation/models/Moderation_type_model');
        $this->CI->Moderation_type_model->update_langs($this->moderation_types, $langs_file);
    }

    public function install_moderation_lang_export($langs_ids = null)
    {
        if (!is_array($langs_ids)) {
            $langs_ids = (array) $langs_ids;
        }
        $this->CI->load->model('moderation/models/Moderation_type_model');
        return array('moderation' => $this->CI->Moderation_type_model->export_langs($this->moderation_types, $langs_ids));
    }

    public function deinstall_moderation()
    {
        $this->CI->load->model('moderation/models/Moderation_type_model');
        foreach ($this->moderation_types as $mtype) {
            $type = $this->CI->Moderation_type_model->get_type_by_name($mtype["name"]);
            $this->CI->Moderation_type_model->delete_type($type['id']);
        }
    }

    /**
     * Install network events handler
     * 
     * @return void
     */
    public function install_network() {
		$this->CI->load->model('network/models/Network_events_model');
		foreach ($this->network_event_handlers as $handler) {
			$this->CI->Network_events_model->add_handler($handler);
		}
	}

    /**
     * Uninstall network events handler
     * 
     * @return void
     */
	public function deinstall_network() {
		$this->CI->load->model('network/models/Network_events_model');
		foreach ($this->network_event_handlers as $handler) {
			$this->CI->Network_events_model->delete($handler['event']);
		}
	}

    public function _arbitrary_installing()
    {
        
    }

    public function _arbitrary_deinstalling()
    {
        
    }

}
