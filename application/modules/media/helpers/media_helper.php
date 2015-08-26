<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

if (!function_exists('media_block')) {

    function media_block($params)
    {
        $CI = &get_instance();

        $location_base_url = !empty($params['location_base_url']) ? $params['location_base_url'] : '';
        $param = $params['param'] ? $params['param'] : 'all';
        $page = $params['page'] ? $params['page'] : 1;
        if (!empty($params['user_id'])) {
            $user_id = $params['user_id'];
            $is_owner = false;
        } else {
            $user_id = $CI->session->userdata('user_id');
            $is_owner = $user_id ? true : false;
        }

        $CI->load->model('Media_model');

        $media_sorter = array(
            "order" => !empty($params['order']) ? $params['order'] : 'date_add',
            "direction" => !empty($params['direction']) ? $params['direction'] : 'DESC',
            "links" => array(
                "date_add" => l('field_date_add', 'media'),
                "views" => l('field_views', 'media'),
                "comments_count" => l('field_comments_count', 'media'),
            )
        );
        $order_by = array($media_sorter['order'] => $media_sorter['direction']);

        $CI->template_lite->assign('is_owner', $is_owner);
        $CI->template_lite->assign('media_sorter', $media_sorter);
        $CI->template_lite->assign('profile_section', 'gallery');
        $CI->template_lite->assign('gallery_param', $param);
        $CI->template_lite->assign('page', $page);
        if ($param == 'albums' && !$album_id) {
            $albums = $CI->Media_model->get_albums($user_id, $page);
            $albums['albums_select'] = $CI->Media_model->get_albums_select($user_id);
            $CI->template_lite->assign('content', $albums);
        } else {
            $CI->template_lite->assign('content', $CI->Media_model->get_list($user_id, $param, $page, 0, true, $order_by));
        }
        $CI->template_lite->assign('albums', $CI->Media_model->get_albums_select($user_id));
        $CI->template_lite->assign('id_user', $user_id);

        if (is_callable($location_base_url)) {
            $media_filters = array(
                'all' => array('link' => $location_base_url('all', l('all', 'media')), 'name' => l('all', 'media')),
                'photo' => array('link' => $location_base_url('photo', l('photo', 'media')), 'name' => l('photo', 'media')),
                'video' => array('link' => $location_base_url('video', l('video', 'media')), 'name' => l('video', 'media')),
                'albums' => array('link' => $location_base_url('albums', l('albums', 'media')), 'name' => l('albums', 'media')),
                'favorites' => array('link' => $location_base_url('favorites', l('favorites', 'media')), 'name' => l('favorites', 'media')),
            );
        } else {
            $media_filters = array(
                'all' => array('link' => $location_base_url . '/all', 'name' => l('all', 'media')),
                'photo' => array('link' => $location_base_url . '/photo', 'name' => l('photo', 'media')),
                'video' => array('link' => $location_base_url . '/video', 'name' => l('video', 'media')),
                'albums' => array('link' => $location_base_url . '/albums', 'name' => l('albums', 'media')),
                'favorites' => array('link' => $location_base_url . '/favorites', 'name' => l('favorites', 'media')),
            );
        }

        if (!isset($params['filters'])) {
            $params['filters'] = $media_filters;
        }
        $CI->template_lite->assign('media_filters', $params['filters']);
        $CI->template_lite->view('user_gallery', 'user', 'media');
    }

}

if (!function_exists('media_carousel')) {

    function media_carousel($params)
    {
        $CI = &get_instance();

        $data['header'] = !empty($params['header']) ? $params['header'] : '';
        $data['media'] = $params['media'];
        $data['carousel']['media_count'] = count($params['media']);
        $data['rand'] = $data['carousel']['rand'] = rand(1, 999999);
        $data['carousel']['visible'] = !empty($params['visible']) ? intval($params['visible']) : 3;
        $data['carousel']['scroll'] = (!empty($params['scroll']) && $params['scroll'] != 'auto') ? intval($params['scroll']) : 'auto';
        $data['carousel']['class'] = !empty($params['class']) ? $params['class'] : '';
        $data['carousel']['thumb_name'] = !empty($params['thumb_name']) ? $params['thumb_name'] : 'middle';
        if (!$data['carousel']['scroll']) {
            $data['carousel']['scroll'] = 1;
        }

        $CI->template_lite->assign('media_carousel_data', $data);
        return $CI->template_lite->fetch('helper_media_carousel', 'user', 'media');
    }

}

if (!function_exists('recent_photos_block')) {

    function recent_media_block($params)
    {
        $CI = &get_instance();
        $CI->load->model('Media_model');
        $data = $CI->Media_model->get_recent_media($params['count'], $params['upload_gid']);
        if (!empty($data['media'])) {
            $media_count = 16 - count($data['media']);
            switch ($media_count) {
                case 13: $recent_thumb['name'] = 'middle';
                    $recent_thumb['width'] = '82px';
                    break;
                case 14: $recent_thumb['name'] = 'big';
                    $recent_thumb['width'] = '125px';
                    break;
                case 15: $recent_thumb['name'] = 'great';
                    $recent_thumb['width'] = '255px';
                    break;
                default: $recent_thumb['name'] = 'small';
                    $recent_thumb['width'] = '60px';
            }
            $CI->template_lite->assign('recent_photos_data', $data);
            $CI->template_lite->assign('recent_thumb', $recent_thumb);
            return $CI->template_lite->fetch('helper_recent_media', 'user', 'media');
        }
        return false;
    }

}

