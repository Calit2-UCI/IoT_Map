<?php

use Pg\Modules\Memberships\Models\Memberships_model;

/**
 * Memberships module
 *
 * @package 	PG_Dating
 * @copyright 	Copyright (c) 2000-2014 PG Dating Pro - php dating software
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
/**
 * Memberships helpers
 * 
 * @package 	PG_Dating
 * @subpackage 	Memberships
 * @category	helpers
 * @copyright 	Copyright (c) 2000-2014 PG Dating Pro - php dating software
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
if (!function_exists('memberships_list')) {

    /**
     * Block of memberships
     * 
     * Usage in template lite: {block name=memberships_list module=memberships}
     * @param array memberships
     * @param int id
     * @param string gid
     * @param boolean my
     * @param boolean hide_btn
     * @return string
     */
    function memberships_list($params = array())
    {
        $CI = &get_instance();
        if (!empty($params['memberships'])) {
            if (!empty($params['memberships']['id'])) {
                $params['memberships'] = array($params['memberships']);
            }
            if (empty($params['memberships']['user_type_disabled'])) {
                $memberships = $params['memberships'];
            } else {
                $memberships = array($params['memberships']);
            }
        } else {
            $where = array('is_active' => 1);

            if (!empty($params['template_gid'])) {
                $CI->load->model('Services_model');

                $where_s = array(
                    'where' => array(
                        'type' => 'membership',
                        'status' => 1,
                    ),
                    'where_in' => array(
                        'template_gid' => (array) $params['template_gid'],
                    ),
                );
                $services = $CI->Services_model->get_service_list($where_s);
                if (!empty($services)) {
                    $membership_ids = array();
                    foreach ($services as $service) {
                        $memberships_ids[] = $service['id_membership'];
                    }
                    $where['ids'] = array_unique($memberships_ids);
                }
            }

            if ($CI->session->userdata('auth_type') == 'user') {
                $where['user_type'] = $CI->session->userdata('user_type');
            }
            $CI->load->model('Memberships_model');
            $CI->Memberships_model->setFormatSettings('get_services', true);
            $memberships = $CI->Memberships_model->formatMemberships(
                $CI->Memberships_model->getMembershipsList($where, null, null, array('price' => 'ASC'))
            );

            $CI->Memberships_model->setFormatSettings('get_services', false);

            // Mark users memberships
            $user_id = $CI->session->userdata('user_id');
            $CI->load->model('memberships/models/Memberships_users_model');
            $user_memberships = $CI->Memberships_users_model->getUserMembershipsList(
                null, array('where' => array('id_user' => $user_id))
            );
            foreach ($user_memberships as $user_membership) {
                if (!isset($memberships[$user_membership['id_membership']])) {
                    $memberships[$user_membership['id_membership']] = $user_membership['membership_info'];
                }

                $memberships[$user_membership['id_membership']]['is_mine'] = true;
                $memberships[$user_membership['id_membership']]['left_str'] = $user_membership['left_str'];
                $memberships[$user_membership['id_membership']]['expired'] = $user_membership['date_expired'];
            }
        }
        if (!empty($memberships)) {
            $all_services = Memberships_model::getServicesByMemberships($memberships);
        } else {
            $all_services = array();
        }
        $CI->template_lite->assign(
            'block_memberships_date_format', $CI->pg_date->get_format('date_literal', 'st')
        );

        $CI->template_lite->assign('hide_buy_btn', !empty($params['hide_buy_btn']));
        $CI->template_lite->assign('duplicate_buttons', count($all_services) > 9);
        $CI->template_lite->assign('all_services', $all_services);
        $CI->template_lite->assign('block_memberships', $memberships);

        if (!empty($params['headline'])) {
            $CI->template_lite->assign('headline', true);
        }

        return $CI->template_lite->fetch('helper_memberships_list', 'user', Memberships_model::MODULE_GID);
    }

}
