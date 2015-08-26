<?php

use Pg\Modules\Memberships\Models\Memberships_model;

return array(
    array(
        'gid' => 'silver-membership',
        'user_type_disabled' => array(),
        'pay_type' => Memberships_model::PAYMENT_TYPE_ACCOUNT_AND_DIRECT,
        'price' => 15,
        'period_count' => 30,
        'period_type' => Memberships_model::PERIOD_TYPE_DAYS,
        'name' => array(
            'en' => 'Silver membership',
            'ru' => 'Серебряная карта',
        ),
        'description' => array(
            'en' => 'Silver membership gives you access to selected site services',
            'ru' => 'Серебряная карта дает доступ к избранным сервисам сайта',
        ),
        'services' => array(
            'users_featured_template' => null,
            'highlight_in_search_template' => null,
        ),
        'is_active' => true,
    ),
    array(
        'gid' => 'gold-membership',
        'user_type_disabled' => array(),
        'pay_type' => Memberships_model::PAYMENT_TYPE_ACCOUNT_AND_DIRECT,
        'price' => 25,
        'period_count' => 30,
        'period_type' => Memberships_model::PERIOD_TYPE_DAYS,
        'name' => array(
            'en' => 'Gold membership',
            'ru' => 'Золотая карта',
        ),
        'description' => array(
            'en' => 'Gold membership gives you access to selected site services',
            'ru' => 'Золотая карта дает доступ к избранным сервисам сайта',
        ),
        'services' => array(
            'users_featured_template' => null,
            'highlight_in_search_template' => null,
            'up_in_search_template' => null,
            'im_template' => null,
        ),
        'is_active' => true,
    ),
    array(
        'gid' => 'premium-membership',
        'user_type_disabled' => array(),
        'pay_type' => Memberships_model::PAYMENT_TYPE_ACCOUNT_AND_DIRECT,
        'price' => 50,
        'period_count' => 12,
        'period_type' => Memberships_model::PERIOD_TYPE_MONTHS,
        'name' => array(
            'en' => 'Premium membership',
            'ru' => 'Премиум-карта',
        ),
        'description' => array(
            'en' => 'Premium membership gives you access to premium site services for one year',
            'ru' => 'Премиум-карта дает вам доступ к избранным сервисам сайта на целый год',
        ),
        'services' => array(
            'users_featured_template' => null,
            'highlight_in_search_template' => null,
            'up_in_search_template' => null,
            'im_template' => null,
            'hide_on_site_template' => null,
        ),
        'is_active' => true,
    ),
);
