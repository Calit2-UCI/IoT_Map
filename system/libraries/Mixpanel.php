<?php

/**
 * PG Mixpanel Library
 * 
 * @package 	PG_Core
 * @subpackage 	Libraries
 * @category 	libraries
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
require_once(dirname(__FILE__) . '/mixpanel/Mixpanel.php');

/**
 * PG Mixpanel Class.
 * Implements a Decorator design pattern.
 *
 * @package 	PG_Core
 * @subpackage 	Libraries
 * @category 	libraries
 * @copyright 	Copyright (c) Pilot Group Ltd
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
class Pg_Mixpanel
{

    const GO_TO_URL = 'DPC: Go to URL';
    const ADMIN_LOGIN = 'DPC: Admin login';
    const USER_LOGIN = 'DPC: User login';

    /**
     * Caching property
     * @var bool 
     */
    private $_is_enabled = null;

    /**
     * Mixpanel key
     * @var string 
     */
    private $_key = '';

    /**
     * Mixpanel options
     * @var array 
     */
    private $_options = array(
        'use_ssl' => false
    );

    /**
     * Call any method of the Mixpanel class
     * @param string $method_name
     * @param mixed $args
     * @return mixed
     */
    public function __get($var_name)
    {
        if (!$this->is_enabled()) {
            return false;
        }
        return Mixpanel::getInstance($this->_key, $this->_options)->$var_name;
    }

    /**
     * Call any method of the Mixpanel class
     * @param string $method_name
     * @param mixed $args
     * @return mixed
     */
    public function __call($method_name, $args)
    {
        if (!$this->is_enabled()) {
            return false;
        }
        return call_user_func_array(array(Mixpanel::getInstance($this->_key, $this->_options), $method_name), $args);
    }

    /**
     * Is mixpanel enabled
     * @return bool
     */
    public function is_enabled()
    {
        if (is_null($this->_is_enabled)) {
            if (empty($this->_key) || $_SERVER["HTTP_HOST"] == 'localhost') {
                $this->_is_enabled = false;
            } elseif (defined('MIXPANEL_ENABLED')) {
                $this->_is_enabled = (bool) MIXPANEL_ENABLED;
            } else {
                $this->_is_enabled = (bool) DEMO_MODE;
            }
        }
        return $this->_is_enabled;
    }

}
