<?php

namespace Pg\Modules\Network\Models;

/**
 * Network events model
 *
 * @package PG_Dating
 * @subpackage application
 * @category modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * */
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

define('NET_EVENTS_HANDLERS', DB_PREFIX . 'net_events_handlers');

/**
 * Network model
 */
class Network_events_model extends Network_model {

	private $_cache = array();
	private $_events_to_skip = array();
	public $fields = array(
		'id',
		'event',
		'module',
		'model',
		'method',
	);

	public function __construct() {
		parent::__construct();
		$this->DB->memcache_tables(array(NET_EVENTS_HANDLERS));
		$this->CI->load->model('network/models/Network_users_model');
	}

	/**
	 * Add event handler
	 * @param array $handler
	 * @return int
	 */
	public function add_handler(array $handler) {
		$this->DB->insert(NET_EVENTS_HANDLERS, $handler);
		return $this->DB->insert_id();
	}

	/**
	 * Delete event handler
	 * @param int|string $val
	 * @return bool
	 * @throws Exception
	 */
	public function delete($val) {
		if (is_int($val)) {
			$field = 'id';
		} elseif (is_string($val)) {
			$field = 'event';
		} else {
			throw new Exception('Wrong type');
		}
		return $this->DB->where($field, $val)
						->delete(NET_EVENTS_HANDLERS);
	}

	/**
	 * Get event handlers
	 * @param array|string $event
	 * @return array
	 */
	public function get($event = null) {
		$this->DB->select($this->fields)
				->from(NET_EVENTS_HANDLERS);
		if (!empty($event)) {
			if (is_array($event)) {
				$this->DB->where_in('event', $event);
			} else {
				$this->DB->where('event', $event);
			}
		}
		return $this->DB->get()->result_array();
	}

	/**
	 * Get event handlers via cache
	 * @param type $event
	 * @return boolean
	 */
	public function get_cache($event = null) {
		if (empty($this->_cache['handlers'])) {
			$this->_cache['handlers'] = $this->get();
		}
		if (empty($event)) {
			return $this->_cache['handlers'];
		} elseif (!empty($this->_cache['handlers'][$event])) {
			return $this->_cache['handlers'][$event];
		} else {
			$handler = $this->get($event);
			if ($handler) {
				$this->_cache['handlers'][$event] = $handler;
				return $handler;
			} else {
				return false;
			}
		}
	}

	/**
	 * Get events list via cache
	 * @return array
	 */
	public function get_events() {
		if(empty($this->_cache['events'])) {
			$handlers = $this->get_cache();
			$this->_cache['events'] = array();
			foreach($handlers as $handler) {
				if(!in_array($handler['event'], $this->_cache['events'])) {
					$this->_cache['events'][] = $handler['event'];
				}
			}
		}
		return array_unique($this->_cache['events']);
	}

	/**
	 * Get connection data
	 * @return array
	 */
	public function get_connection_data() {
		if (empty($this->_fast_server) || empty($this->_key) || empty($this->_domain)) {
			$this->get_auth_data();
			if (empty($this->_fast_server) || empty($this->_key) || empty($this->_domain)) {
				return array();
			}
		}
		return array(
			'key' => $this->_key,
			'domain' => $this->_domain,
			'url' => $this->_fast_server,
		);
	}

	/**
	 * Prohibit the processing of the next action.
	 * @param string $event
	 */
	private function _forbid_emiting($event) {
		$this->_events_to_skip[$event] = true;
		return $this;
	}

	/**
	 * Check whether the actions processing is allowed or not.
	 * If not, allow it.
	 * @param string $event
	 * @return boolean
	 */
	private function _is_emiting_allowed($event) {
		if (!empty($this->_events_to_skip[$event])) {
			$this->_events_to_skip[$event] = false;
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Execut event handler
	 * @param array $handler
	 * @param array $data
	 * @return mixed
	 */
	private function _exec($handler, $data)	{
        $model = $handler['model'];
        if ($handler['module']."_model" == strtolower($handler['model'])) {
            $model_path = $handler['model'];
        } else {
            $model_path = $handler['module'] . '/models/' . $handler['model'];
        }     
		$this->CI->load->model($model_path);      
		if (method_exists($this->CI->$model, $handler['method'])) {
			$this->_forbid_emiting($handler['event']);
			$this->CI->$model->$handler['method']($data);            
		} else {
			echo date('H:i:s') .  ': error ' . '$CI->' . $model 
					. '->' . $handler['method'] . '(' . serialize($data) . ')' . PHP_EOL;
		}
	}

	/**
	 * Prepare incoming data
	 * @param array $data
	 * @return array
	 */
	private function _prepare_incoming_data($data) 
    {
		if(!empty($data['id_user'])) {
			// Replace net id with local id
			$data['id_user'] = $this->CI->Network_users_model->get_local_id_by_net($data['id_user']);
		}
		if(!empty($data['id_to'])) {
			// Replace net id with local id
			$data['id_to'] = $this->CI->Network_users_model->get_local_id_by_net($data['id_to']);
		}
        if(!empty($data['id_contact'])) {
			// Replace net id with local id
			$data['id_contact'] = $this->CI->Network_users_model->get_local_id_by_net($data['id_contact']);
		}
		return $data;
	}

	/**
	 * Prepare outcoming data
	 * @param array $data
	 * @return array
	 */
	private function _prepare_outcoming_data($data) {
		if(!empty($data['id_user'])) {
			// Replace local id with net id
			$net_id = $this->CI->Network_users_model->get_net_id_by_local($data['id_user']);
			if(empty($net_id)) {
				return array();
			}
			$data['id_user'] = $net_id;
		}
		if(!empty($data['id_to'])) {
			// Replace local id with net id
			$net_id = $this->CI->Network_users_model->get_net_id_by_local($data['id_to']);
			if(empty($net_id)) {
				return array();
			}
			$data['id_to'] = $net_id;
		}
        if(!empty($data['id_contact'])) {
			// Replace local id with net id
			$net_id = $this->CI->Network_users_model->get_net_id_by_local($data['id_contact']);
			if(empty($net_id)) {
				return array();
			}
			$data['id_contact'] = $net_id;
		}
		return $data;
	}

	/**
	 * Handle event
	 * @param string $event
	 * @param array $raw_data
	 */
	public function handle($event, $raw_data) 
    {
        echo date('H:i:s') .  ': model handle: ' . $event . PHP_EOL;
        $data = $this->_prepare_incoming_data($raw_data);
		foreach ($this->get_cache($event) as $handler) {
        	$this->_exec($handler, $data);
		}
	}

	/**
	 * Emit event
	 * @param string $event
	 * @param array $raw_data
	 * @return boolean
	 */
	public function emit($event, $raw_data) 
    {
		if (!$this->_is_emiting_allowed($event)) {
			return false;
		}
		$prepared_data = $this->_prepare_outcoming_data($raw_data);
		if(empty($prepared_data)) {
			return false;
		}
		$event_data = array(
			'event' => $event,
			'data' => $prepared_data,
		);
		$file = $this->get_events_dir() . $event . substr(time(), -5) . rand(11111, 99999);
		$fp = fopen($file, 'w');
		fwrite($fp, json_encode($event_data));
		fclose($fp);
        chmod($file, 0777);
		return true;
	}

}
