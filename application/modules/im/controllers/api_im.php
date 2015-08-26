<?php

/**
 * IM API controller
 *
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @author Dmitry Popenov
 * @version $Revision: 2 $ $Date: 2013-01-30 10:07:07 +0400 $
 * */
class Api_Im extends Class_im
{

    public function check_new_messages()
    {
        $this->set_api_content('data', $this->_check_new_messages());
    }

    public function get_contact_list()
    {
        $with_messages = filter_input(INPUT_POST, 'with_messages', FILTER_VALIDATE_BOOLEAN);
        $this->set_api_content('data', $this->_get_contact_list($with_messages));
    }

    public function set_site_status()
    {
        $this->set_api_content('data', $this->_set_site_status());
    }

    public function get_messages()
    {
        $data = $this->_get_messages();
        if (!$data['im_status']['im_on']) {
            $this->set_api_content('code', 503);
        }
        $this->set_api_content('data', $data);
    }

    public function post_message()
    {
        $this->set_api_content('data', $this->_post_message());
    }

    public function get_history()
    {
        $this->set_api_content('data', $this->_get_history());
    }

    public function clear_history()
    {
        $this->set_api_content('data', $this->_clear_history());
    }

    public function get_im_status()
    {
        $this->set_api_content('data', $this->_get_im_status());
    }

}
