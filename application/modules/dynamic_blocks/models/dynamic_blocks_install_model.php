<?php

namespace Pg\Modules\Dynamic_blocks\Models;

/**
 * Dynamic blocks module
 *
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */

/**
 * Dynamic blocks install model
 *
 * @subpackage 	Dynamic blocks
 * @category	models
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
class Dynamic_blocks_install_model extends \Model
{

    /**
     * Link to CodeIgniter object
     * 
     * @var object
     */
    protected $CI;

    /**
     * Menu configuration
     * 
     * @var array 
     */
    protected $menu = array(
        'admin_menu' => array(
            'action' => 'none',
            'items' => array(
                'settings_items' => array(
                    'action' => 'none',
                    'items' => array(
                        'interface-items' => array(
                            'action' => 'none',
                            'items' => array(
                                'dynblock_menu_item' => array('action' => 'create', 'link' => 'admin/dynamic_blocks', 'status' => 1, 'sorter' => 1)
                            )
                        )
                    )
                )
            )
        ),
        'admin_dynblocks_menu' => array(
            'action' => 'create',
            'name' => 'Admin mode - Interface - Dynamic blocks',
            'items' => array(
                'areas_list_item' => array('action' => 'create', 'link' => 'admin/dynamic_blocks', 'status' => 1),
                'blocks_list_item' => array('action' => 'create', 'link' => 'admin/dynamic_blocks/blocks', 'status' => 0)
            )
        )
    );

    /**
     * Ausers configuration
     * 
     * @var array
     */
    protected $moderators_methods = array(
        array('module' => 'dynamic_blocks', 'method' => 'index', 'is_default' => 1),
        array('module' => 'dynamic_blocks', 'method' => 'area_blocks', 'is_default' => 0),
    );

    /**
     * Dynamic blocks configuration
     * 
     * @var array
     */
    protected $dynamic_blocks = array(
        'html_code' => array(
            'gid' => 'html_code',
            'module' => 'dynamic_blocks',
            'model' => 'Dynamic_blocks_model',
            'method' => '_dynamic_block_get_html_code',
            'params' => array(
                'title' => array(
                    'type' => 'text',
                    'default' => '',
                    'gid' => 'title',
                ),
                'html' => array(
                    'type' => 'textarea',
                    'default' => '',
                    'gid' => 'html',
                ),
            ),
            'views' => array(
                array(
                    'gid' => 'default',
                ),
            ),
            'area' => array(
                array(
                    'gid' => 'mediumturquoise',
                    'params' => 'a:3:{s:8:"title_en";s:0:"";s:8:"title_ru";s:0:"";s:4:"html";s:541:"<div style="color:#333; float: right; line-height: 24px; text-shadow:  1px 1px 0 rgba(255,255,255,0.5);"><h1>Welcome to Dating Pro</h1><p style="margin-bottom: 12px;">Professional service for Online Dating and Networking. Thousands of members join our community from all over the world.</p><p style="margin-bottom: 12px;">Create a profile, post your photos, and soon you will be communicating with all these incredible people.</p><p style="margin-bottom: 12px;">We hope that our site is the place where you will find your lifemate!</p></div>";}',
                    'view_str' => 'default',
                    'width' => 30,
                    'sorter' => 9,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'lavender',
                    'params' => 'a:3:{s:8:"title_en";s:0:"";s:8:"title_ru";s:0:"";s:4:"html";s:541:"<div style="color:#333; float: right; line-height: 24px; text-shadow:  1px 1px 0 rgba(255,255,255,0.5);"><h1>Welcome to Dating Pro</h1><p style="margin-bottom: 12px;">Professional service for Online Dating and Networking. Thousands of members join our community from all over the world.</p><p style="margin-bottom: 12px;">Create a profile, post your photos, and soon you will be communicating with all these incredible people.</p><p style="margin-bottom: 12px;">We hope that our site is the place where you will find your lifemate!</p></div>";}',
                    'view_str' => 'login_form',
                    'width' => 30,
                    'sorter' => 9,
                    'cache_time' => 0,
                ),
            ),
            'presets' => array(
                array(
                    'gid_preset' => 'mediumturquoise',
                    'gid_area' => 'mediumturquoise',
                    'params' => 'a:3:{s:8:"title_en";s:0:"";s:8:"title_ru";s:0:"";s:4:"html";s:541:"<div style="color:#333; float: right; line-height: 24px; text-shadow:  1px 1px 0 rgba(255,255,255,0.5);"><h1>Welcome to Dating Pro</h1><p style="margin-bottom: 12px;">Professional service for Online Dating and Networking. Thousands of members join our community from all over the world.</p><p style="margin-bottom: 12px;">Create a profile, post your photos, and soon you will be communicating with all these incredible people.</p><p style="margin-bottom: 12px;">We hope that our site is the place where you will find your lifemate!</p></div>";}',
                    'view_str' => 'default',
                    'width' => 30,
                    'sorter' => 9,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'lavender',
                    'gid_area' => 'lavender',
                    'params' => 'a:3:{s:8:"title_en";s:0:"";s:8:"title_ru";s:0:"";s:4:"html";s:541:"<div style="color:#333; float: right; line-height: 24px; text-shadow:  1px 1px 0 rgba(255,255,255,0.5);"><h1>Welcome to Dating Pro</h1><p style="margin-bottom: 12px;">Professional service for Online Dating and Networking. Thousands of members join our community from all over the world.</p><p style="margin-bottom: 12px;">Create a profile, post your photos, and soon you will be communicating with all these incredible people.</p><p style="margin-bottom: 12px;">We hope that our site is the place where you will find your lifemate!</p></div>";}',
                    'view_str' => 'login_form',
                    'width' => 30,
                    'sorter' => 9,
                    'cache_time' => 0,
                ),
            ),
            array(
                array(
                    'gid' => 'mediumturquoise',
                    'params' => 'a:3:{s:8:"title_en";s:0:"";s:8:"title_ru";s:0:"";s:4:"html";s:541:"<div style="color:#333; float: right; line-height: 24px; text-shadow:  1px 1px 0 rgba(255,255,255,0.5);"><h1>Welcome to Dating Pro</h1><p style="margin-bottom: 12px;">Professional service for Online Dating and Networking. Thousands of members join our community from all over the world.</p><p style="margin-bottom: 12px;">Create a profile, post your photos, and soon you will be communicating with all these incredible people.</p><p style="margin-bottom: 12px;">We hope that our site is the place where you will find your lifemate!</p></div>";}',
                    'view_str' => 'default',
                    'width' => 30,
                    'sorter' => 9,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'lavender',
                    'params' => 'a:3:{s:8:"title_en";s:0:"";s:8:"title_ru";s:0:"";s:4:"html";s:541:"<div style="color:#333; float: right; line-height: 24px; text-shadow:  1px 1px 0 rgba(255,255,255,0.5);"><h1>Welcome to Dating Pro</h1><p style="margin-bottom: 12px;">Professional service for Online Dating and Networking. Thousands of members join our community from all over the world.</p><p style="margin-bottom: 12px;">Create a profile, post your photos, and soon you will be communicating with all these incredible people.</p><p style="margin-bottom: 12px;">We hope that our site is the place where you will find your lifemate!</p></div>";}',
                    'view_str' => 'login_form',
                    'width' => 30,
                    'sorter' => 9,
                    'cache_time' => 0,
                ),
            ),
        ),
        'rich_text' => array(
            'gid' => 'rich_text',
            'module' => 'dynamic_blocks',
            'model' => 'Dynamic_blocks_model',
            'method' => '_dynamic_block_get_rich_text',
            'params' => array(
                'title' => array(
                    'type' => 'text',
                    'default' => '',
                    'gid' => 'title',
                ),
                'html' => array(
                    'type' => 'wysiwyg',
                    'default' => '',
                    'gid' => 'html',
                ),
            ),
            'views' => array(
                array(
                    'gid' => 'default',
                ),
            ),
            "area" => array(
                array(
                    'gid' => 'index-page',
                    'params' => 'a:4:{s:8:"title_en";s:0:"";s:8:"title_en";s:0:"";s:7:"html_en";s:542:"<p><span style="font-size:24px">Welcome to Dating Pro</span></p><p>&nbsp;</p><span style="font-size:13px; line-height:1.6em">Professional service for Online Dating and Networking. Thousands of members join our community from all over the world.</span><p>&nbsp;</p><p><span style="line-height:1.6em">Create a profile, post your photos, and soon you will be communicating with all these incredible people.</span></p><p>&nbsp;</p><p><span style="line-height:1.6em">We hope that our site is the place where you will find your lifemate!</span></p>";s:7:"html_ru";s:0:"";}',
                    'view_str' => 'default',
                    'width' => 30,
                    'sorter' => 6,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'index-page',
                    'params' => 'a:4:{s:8:"title_en";s:16:"Want to join in?";s:8:"title_ru";s:42:"Хотите присоединиться?";s:7:"html_en";s:425:"<p><span style="font-size:16px">Just create a simple profile, post up your photos, and soon you&#39;ll be networking with our incredible personals. Online personals couldn&#39;t be easier! You can even upgrade your membership for email, instant messaging, and real-time chat. For real relationships, romance, friendships, networking and more, Dating Pro is the place for you. Get started today and find your match!</span></p>";s:7:"html_ru";s:691:"<p><span style="font-size:16px">Просто создайте свою анкету, добавьте фотографии и вы сможете общаться со всеми замечательными людьми на этом сайте. Что может быть проще! Вы можете получить особые привилегии для более эффективного общения на сайте. Реальные взаимотношения, общение, дружба и многое другое! Dating Pro - это твой сайт! Начни сегодня и сделай шаг вперед навстречу своей судьбе!</span></p>";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 11,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'girls',
                    'params' => 'a:4:{s:8:"title_en";s:16:"Want to join in?";s:8:"title_ru";s:42:"Хотите присоединиться?";s:7:"html_en";s:425:"<p><span style="font-size:16px">Just create a simple profile, post up your photos, and soon you&#39;ll be networking with our incredible personals. Online personals couldn&#39;t be easier! You can even upgrade your membership for email, instant messaging, and real-time chat. For real relationships, romance, friendships, networking and more, Dating Pro is the place for you. Get started today and find your match!</span></p>";s:7:"html_ru";s:691:"<p><span style="font-size:16px">Просто создайте свою анкету, добавьте фотографии и вы сможете общаться со всеми замечательными людьми на этом сайте. Что может быть проще! Вы можете получить особые привилегии для более эффективного общения на сайте. Реальные взаимотношения, общение, дружба и многое другое! Dating Pro - это твой сайт! Начни сегодня и сделай шаг вперед навстречу своей судьбе!</span></p>";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 9,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'jewish',
                    'params' => 'a:4:{s:8:"title_en";s:8:"About us";s:8:"title_ru";s:9:"О нас";s:7:"html_en";s:445:"<p><span style="line-height:1.6em">Want to join in? Just create a simple profile, post up your photos, and soon you&#39;ll be networking with our incredible personals. Online personals couldn&#39;t be easier! You can even upgrade your membership for email, instant messaging, and real-time chat. For real relationships, romance, friendships, networking and more, Dating Pro is the place for you. Get started today and find your match!</span></p>";s:7:"html_ru";s:698:"<p>Хотите присоединиться? Просто создайте свою анкету, добавьте фотографии и вы сможете общаться со всеми замечательными людьми на этом сайте. Что может быть проще! Вы можете получить особые привилегии для более эффективного общения на сайте. Реальные взаимотношения, общение, дружба и многое другое! Dating Pro - это твой сайт! Начни сегодня и сделай шаг вперед навстречу своей судьбе!</p>";}',
                    'view_str' => 'default',
                    'width' => 30,
                    'sorter' => 7,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'lovers',
                    'params' => 'a:4:{s:8:"title_en";s:8:"About Us";s:8:"title_ru";s:9:"О нас";s:7:"html_en";s:406:"<p>Want to join in? Just create a simple profile, post up your photos, and soon you&#39;ll be networking with our incredible personals. Online personals couldn&#39;t be easier! You can even upgrade your membership for email, instant messaging, and real-time chat. For real relationships, romance, friendships, networking and more, Dating Pro is the place for you. Get started today and find your match!</p>";s:7:"html_ru";s:698:"<p>Хотите присоединиться? Просто создайте свою анкету, добавьте фотографии и вы сможете общаться со всеми замечательными людьми на этом сайте. Что может быть проще! Вы можете получить особые привилегии для более эффективного общения на сайте. Реальные взаимотношения, общение, дружба и многое другое! Dating Pro - это твой сайт! Начни сегодня и сделай шаг вперед навстречу своей судьбе!</p>";}',
                    'view_str' => 'default',
                    'width' => 30,
                    'sorter' => 8,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'lovers',
                    'params' => 'a:4:{s:8:"title_en";s:77:"Welcome to Dating Pro, professional service for Online Dating and Networking.";s:8:"title_ru";s:144:"Добро пожаловать на Dating Pro, профессиональный сервис для онлайн знакомств и общения.";s:7:"html_en";s:687:"<p>Millions of members worldwide, looking for others to share their experiences with, are here in our community now! New singles are joining all the time, and many are making connections every day.</p><p>&nbsp;</p><p><span style="line-height:1.6em">Want to join in? Just create a simple profile, post up your photos, and soon you&#39;ll be networking with our incredible personals. Online personals couldn&#39;t be easier! You can even upgrade your membership for email, instant messaging, and real-time chat. For real relationships, romance, friendships, networking and more, Dating Pro is the place for you. Get started today and find your match! &nbsp; &nbsp;&nbsp;</span></p>";s:7:"html_ru";s:1130:"<p>Миллионы пользователей по всему миру ищут вас, чтобы поделиться своими впечатлениями и переживаниями! Новые люди присоединяются к нам постоянно, и вас ждет успех в поиске идеального партнера, который всегда будет с вами!.</p><p>Хотите присоединиться? Просто создайте свою анкету, добавьте фотографии и вы сможете общаться со всеми замечательными людьми на этом сайте. Что может быть проще! Вы можете получить особые привилегии для более эффективного общения на сайте. Реальные взаимотношения, общение, дружба и многое другое! Dating Pro - это твой сайт! Начни сегодня и сделай шаг вперед навстречу своей судьбе! &nbsp; &nbsp;&nbsp;</p>";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 11,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'neighbourhood',
                    'params' => 'a:4:{s:8:"title_en";s:77:"Welcome to Dating Pro, professional service for Online Dating and Networking.";s:8:"title_ru";s:152:"Добро пожаловать на Dating Pro, профессиональный сервис для онлайн знакомств и общения.";s:7:"html_en";s:633:"<p>Millions of members worldwide, looking for others to share their experiences with, are here in our community now! New singles are joining all the time, and many are making connections every day.</p><p><br />Want to join in? Just create a simple profile, post up your photos, and soon you&#39;ll be networking with our incredible personals. Online personals couldn&#39;t be easier! You can even upgrade your membership for email, instant messaging, and real-time chat. For real relationships, romance, friendships, networking and more, Dating Pro is the place for you. Get started today and find your match! &nbsp; &nbsp;&nbsp;</p>";s:7:"html_ru";s:1136:"<p>Миллионы пользователей по всему миру ищут вас, чтобы поделиться своими впечатлениями и переживаниями! Новые люди присоединяются к нам постоянно, и вас ждет успех в поиске идеального партнера, который всегда будет с вами!.</p><p><br />Хотите присоединиться? Просто создайте свою анкету, добавьте фотографии и вы сможете общаться со всеми замечательными людьми на этом сайте. Что может быть проще! Вы можете получить особые привилегии для более эффективного общения на сайте. Реальные взаимотношения, общение, дружба и многое другое! Dating Pro - это твой сайт! Начни сегодня и сделай шаг вперед навстречу своей судьбе! &nbsp; &nbsp;&nbsp;</p>";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 9,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'deepblue',
                    'params' => 'a:4:{s:8:"title_en";s:77:"Welcome to Dating Pro, professional service for Online Dating and Networking.";s:8:"title_ru";s:152:"Добро пожаловать на Dating Pro, профессиональный сервис для онлайн знакомств и общения.";s:7:"html_en";s:633:"<p>Millions of members worldwide, looking for others to share their experiences with, are here in our community now! New singles are joining all the time, and many are making connections every day.</p><p><br />Want to join in? Just create a simple profile, post up your photos, and soon you&#39;ll be networking with our incredible personals. Online personals couldn&#39;t be easier! You can even upgrade your membership for email, instant messaging, and real-time chat. For real relationships, romance, friendships, networking and more, Dating Pro is the place for you. Get started today and find your match! &nbsp; &nbsp;&nbsp;</p>";s:7:"html_ru";s:1136:"<p>Миллионы пользователей по всему миру ищут вас, чтобы поделиться своими впечатлениями и переживаниями! Новые люди присоединяются к нам постоянно, и вас ждет успех в поиске идеального партнера, который всегда будет с вами!.</p><p><br />Хотите присоединиться? Просто создайте свою анкету, добавьте фотографии и вы сможете общаться со всеми замечательными людьми на этом сайте. Что может быть проще! Вы можете получить особые привилегии для более эффективного общения на сайте. Реальные взаимотношения, общение, дружба и многое другое! Dating Pro - это твой сайт! Начни сегодня и сделай шаг вперед навстречу своей судьбе! &nbsp; &nbsp;&nbsp;</p>";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 9,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'companions',
                    'params' => 'a:4:{s:8:"title_en";s:77:"Welcome to Dating Pro, professional service for Online Dating and Networking.";s:8:"title_ru";s:152:"Добро пожаловать на Dating Pro, профессиональный сервис для онлайн знакомств и общения.";s:7:"html_en";s:633:"<p>Millions of members worldwide, looking for others to share their experiences with, are here in our community now! New singles are joining all the time, and many are making connections every day.</p><p><br />Want to join in? Just create a simple profile, post up your photos, and soon you&#39;ll be networking with our incredible personals. Online personals couldn&#39;t be easier! You can even upgrade your membership for email, instant messaging, and real-time chat. For real relationships, romance, friendships, networking and more, Dating Pro is the place for you. Get started today and find your match! &nbsp; &nbsp;&nbsp;</p>";s:7:"html_ru";s:1136:"<p>Миллионы пользователей по всему миру ищут вас, чтобы поделиться своими впечатлениями и переживаниями! Новые люди присоединяются к нам постоянно, и вас ждет успех в поиске идеального партнера, который всегда будет с вами!.</p><p><br />Хотите присоединиться? Просто создайте свою анкету, добавьте фотографии и вы сможете общаться со всеми замечательными людьми на этом сайте. Что может быть проще! Вы можете получить особые привилегии для более эффективного общения на сайте. Реальные взаимотношения, общение, дружба и многое другое! Dating Pro - это твой сайт! Начни сегодня и сделай шаг вперед навстречу своей судьбе! &nbsp; &nbsp;&nbsp;</p>";}',
                    'view_str' => 'default',
                    'width' => 30,
                    'sorter' => 10,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'autumn_walk',
                    'params' => 'a:4:{s:8:"title_en";s:16:"Want to join in?";s:8:"title_ru";s:42:"Хотите присоединиться?";s:7:"html_en";s:486:"<p style="background-color:rgba(255,255,255,0.8); padding:5px;"><span style="font-size:16px">Just create a simple profile, post up your photos, and soon you&#39;ll be networking with our incredible personals. Online personals couldn&#39;t be easier! You can even upgrade your membership for email, instant messaging, and real-time chat. For real relationships, romance, friendships, networking and more, Dating Pro is the place for you. Get started today and find your match!</span></p>";s:7:"html_ru";s:752:"<p style="background-color:rgba(255,255,255,0.8); padding:5px;"><span style="font-size:16px">Просто создайте свою анкету, добавьте фотографии и вы сможете общаться со всеми замечательными людьми на этом сайте. Что может быть проще! Вы можете получить особые привилегии для более эффективного общения на сайте. Реальные взаимотношения, общение, дружба и многое другое! Dating Pro - это твой сайт! Начни сегодня и сделай шаг вперед навстречу своей судьбе!</span></p>";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 10,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'persimmon_red',
                    'params' => 'a:4:{s:8:"title_en";s:77:"Welcome to Dating Pro, professional service for Online Dating and Networking.";s:8:"title_ru";s:152:"Добро пожаловать на Dating Pro, профессиональный сервис для онлайн знакомств и общения.";s:7:"html_en";s:254:"<p>Thousands of members join our community from all over the world.<p>Create a profile, post your photos, and soon you will be communicating with all these incredible people.</p><p>We hope that our site is the place where you will find your lifemate!</p>";s:7:"html_ru";s:1110:"<p>Миллионы пользователей по всему миру ищут вас, чтобы поделиться своими впечатлениями и переживаниями! Новые люди присоединяются к нам постоянно, и вас ждет успех в поиске идеального партнера, который всегда будет с вами!.</p><p>Хотите присоединиться? Просто создайте свою анкету, добавьте фотографии и вы сможете общаться со всеми замечательными людьми на этом сайте. Что может быть проще! Вы можете получить особые привилегии для более эффективного общения на сайте. Реальные взаимотношения, общение, дружба и многое другое! Dating Pro - это твой сайт! Начни сегодня и сделай шаг вперед навстречу своей судьбе!</p>";}',
                    'view_str' => 'default',
                    'width' => 30,
                    'sorter' => 6,
                    'cache_time' => 600,
                ),
                array(
                    'gid' => 'persimmon_red',
                    'params' => 'a:4:{s:8:"title_en";s:16:"Want to join in?";s:8:"title_ru";s:42:"Хотите присоединиться?";s:7:"html_en";s:425:"<p><span style="font-size:16px">Just create a simple profile, post up your photos, and soon you&#39;ll be networking with our incredible personals. Online personals couldn&#39;t be easier! You can even upgrade your membership for email, instant messaging, and real-time chat. For real relationships, romance, friendships, networking and more, Dating Pro is the place for you. Get started today and find your match!</span></p>";s:7:"html_ru";s:691:"<p><span style="font-size:16px">Просто создайте свою анкету, добавьте фотографии и вы сможете общаться со всеми замечательными людьми на этом сайте. Что может быть проще! Вы можете получить особые привилегии для более эффективного общения на сайте. Реальные взаимотношения, общение, дружба и многое другое! Dating Pro - это твой сайт! Начни сегодня и сделай шаг вперед навстречу своей судьбе!</span></p>";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 15,
                    'cache_time' => 600,
                ),
            ),
            "presets" => array(
                array(
                    'gid_preset' => 'index-page',
                    'gid_area' => 'index-page',
                    'params' => 'a:4:{s:8:"title_en";s:0:"";s:8:"title_en";s:0:"";s:7:"html_en";s:542:"<p><span style="font-size:24px">Welcome to Dating Pro</span></p><p>&nbsp;</p><span style="font-size:13px; line-height:1.6em">Professional service for Online Dating and Networking. Thousands of members join our community from all over the world.</span><p>&nbsp;</p><p><span style="line-height:1.6em">Create a profile, post your photos, and soon you will be communicating with all these incredible people.</span></p><p>&nbsp;</p><p><span style="line-height:1.6em">We hope that our site is the place where you will find your lifemate!</span></p>";s:7:"html_ru";s:0:"";}',
                    'view_str' => 'default',
                    'width' => 30,
                    'sorter' => 6,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'index-page',
                    'gid_area' => 'index-page',
                    'params' => 'a:4:{s:8:"title_en";s:16:"Want to join in?";s:8:"title_ru";s:42:"Хотите присоединиться?";s:7:"html_en";s:425:"<p><span style="font-size:16px">Just create a simple profile, post up your photos, and soon you&#39;ll be networking with our incredible personals. Online personals couldn&#39;t be easier! You can even upgrade your membership for email, instant messaging, and real-time chat. For real relationships, romance, friendships, networking and more, Dating Pro is the place for you. Get started today and find your match!</span></p>";s:7:"html_ru";s:691:"<p><span style="font-size:16px">Просто создайте свою анкету, добавьте фотографии и вы сможете общаться со всеми замечательными людьми на этом сайте. Что может быть проще! Вы можете получить особые привилегии для более эффективного общения на сайте. Реальные взаимотношения, общение, дружба и многое другое! Dating Pro - это твой сайт! Начни сегодня и сделай шаг вперед навстречу своей судьбе!</span></p>";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 11,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'girls',
                    'gid_area' => 'girls',
                    'params' => 'a:4:{s:8:"title_en";s:16:"Want to join in?";s:8:"title_ru";s:42:"Хотите присоединиться?";s:7:"html_en";s:425:"<p><span style="font-size:16px">Just create a simple profile, post up your photos, and soon you&#39;ll be networking with our incredible personals. Online personals couldn&#39;t be easier! You can even upgrade your membership for email, instant messaging, and real-time chat. For real relationships, romance, friendships, networking and more, Dating Pro is the place for you. Get started today and find your match!</span></p>";s:7:"html_ru";s:691:"<p><span style="font-size:16px">Просто создайте свою анкету, добавьте фотографии и вы сможете общаться со всеми замечательными людьми на этом сайте. Что может быть проще! Вы можете получить особые привилегии для более эффективного общения на сайте. Реальные взаимотношения, общение, дружба и многое другое! Dating Pro - это твой сайт! Начни сегодня и сделай шаг вперед навстречу своей судьбе!</span></p>";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 9,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'jewish',
                    'gid_area' => 'jewish',
                    'params' => 'a:4:{s:8:"title_en";s:8:"About us";s:8:"title_ru";s:9:"О нас";s:7:"html_en";s:445:"<p><span style="line-height:1.6em">Want to join in? Just create a simple profile, post up your photos, and soon you&#39;ll be networking with our incredible personals. Online personals couldn&#39;t be easier! You can even upgrade your membership for email, instant messaging, and real-time chat. For real relationships, romance, friendships, networking and more, Dating Pro is the place for you. Get started today and find your match!</span></p>";s:7:"html_ru";s:698:"<p>Хотите присоединиться? Просто создайте свою анкету, добавьте фотографии и вы сможете общаться со всеми замечательными людьми на этом сайте. Что может быть проще! Вы можете получить особые привилегии для более эффективного общения на сайте. Реальные взаимотношения, общение, дружба и многое другое! Dating Pro - это твой сайт! Начни сегодня и сделай шаг вперед навстречу своей судьбе!</p>";}',
                    'view_str' => 'default',
                    'width' => 30,
                    'sorter' => 7,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'lovers',
                    'gid_area' => 'lovers',
                    'params' => 'a:4:{s:8:"title_en";s:8:"About Us";s:8:"title_ru";s:9:"О нас";s:7:"html_en";s:406:"<p>Want to join in? Just create a simple profile, post up your photos, and soon you&#39;ll be networking with our incredible personals. Online personals couldn&#39;t be easier! You can even upgrade your membership for email, instant messaging, and real-time chat. For real relationships, romance, friendships, networking and more, Dating Pro is the place for you. Get started today and find your match!</p>";s:7:"html_ru";s:698:"<p>Хотите присоединиться? Просто создайте свою анкету, добавьте фотографии и вы сможете общаться со всеми замечательными людьми на этом сайте. Что может быть проще! Вы можете получить особые привилегии для более эффективного общения на сайте. Реальные взаимотношения, общение, дружба и многое другое! Dating Pro - это твой сайт! Начни сегодня и сделай шаг вперед навстречу своей судьбе!</p>";}',
                    'view_str' => 'default',
                    'width' => 30,
                    'sorter' => 8,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'lovers',
                    'gid_area' => 'lovers',
                    'params' => 'a:4:{s:8:"title_en";s:77:"Welcome to Dating Pro, professional service for Online Dating and Networking.";s:8:"title_ru";s:144:"Добро пожаловать на Dating Pro, профессиональный сервис для онлайн знакомств и общения.";s:7:"html_en";s:687:"<p>Millions of members worldwide, looking for others to share their experiences with, are here in our community now! New singles are joining all the time, and many are making connections every day.</p><p>&nbsp;</p><p><span style="line-height:1.6em">Want to join in? Just create a simple profile, post up your photos, and soon you&#39;ll be networking with our incredible personals. Online personals couldn&#39;t be easier! You can even upgrade your membership for email, instant messaging, and real-time chat. For real relationships, romance, friendships, networking and more, Dating Pro is the place for you. Get started today and find your match! &nbsp; &nbsp;&nbsp;</span></p>";s:7:"html_ru";s:1130:"<p>Миллионы пользователей по всему миру ищут вас, чтобы поделиться своими впечатлениями и переживаниями! Новые люди присоединяются к нам постоянно, и вас ждет успех в поиске идеального партнера, который всегда будет с вами!.</p><p>Хотите присоединиться? Просто создайте свою анкету, добавьте фотографии и вы сможете общаться со всеми замечательными людьми на этом сайте. Что может быть проще! Вы можете получить особые привилегии для более эффективного общения на сайте. Реальные взаимотношения, общение, дружба и многое другое! Dating Pro - это твой сайт! Начни сегодня и сделай шаг вперед навстречу своей судьбе! &nbsp; &nbsp;&nbsp;</p>";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 11,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'neighbourhood',
                    'gid_area' => 'neighbourhood',
                    'params' => 'a:4:{s:8:"title_en";s:77:"Welcome to Dating Pro, professional service for Online Dating and Networking.";s:8:"title_ru";s:152:"Добро пожаловать на Dating Pro, профессиональный сервис для онлайн знакомств и общения.";s:7:"html_en";s:633:"<p>Millions of members worldwide, looking for others to share their experiences with, are here in our community now! New singles are joining all the time, and many are making connections every day.</p><p><br />Want to join in? Just create a simple profile, post up your photos, and soon you&#39;ll be networking with our incredible personals. Online personals couldn&#39;t be easier! You can even upgrade your membership for email, instant messaging, and real-time chat. For real relationships, romance, friendships, networking and more, Dating Pro is the place for you. Get started today and find your match! &nbsp; &nbsp;&nbsp;</p>";s:7:"html_ru";s:1136:"<p>Миллионы пользователей по всему миру ищут вас, чтобы поделиться своими впечатлениями и переживаниями! Новые люди присоединяются к нам постоянно, и вас ждет успех в поиске идеального партнера, который всегда будет с вами!.</p><p><br />Хотите присоединиться? Просто создайте свою анкету, добавьте фотографии и вы сможете общаться со всеми замечательными людьми на этом сайте. Что может быть проще! Вы можете получить особые привилегии для более эффективного общения на сайте. Реальные взаимотношения, общение, дружба и многое другое! Dating Pro - это твой сайт! Начни сегодня и сделай шаг вперед навстречу своей судьбе! &nbsp; &nbsp;&nbsp;</p>";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 9,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'deepblue',
                    'gid_area' => 'deepblue',
                    'params' => 'a:4:{s:8:"title_en";s:77:"Welcome to Dating Pro, professional service for Online Dating and Networking.";s:8:"title_ru";s:152:"Добро пожаловать на Dating Pro, профессиональный сервис для онлайн знакомств и общения.";s:7:"html_en";s:633:"<p>Millions of members worldwide, looking for others to share their experiences with, are here in our community now! New singles are joining all the time, and many are making connections every day.</p><p><br />Want to join in? Just create a simple profile, post up your photos, and soon you&#39;ll be networking with our incredible personals. Online personals couldn&#39;t be easier! You can even upgrade your membership for email, instant messaging, and real-time chat. For real relationships, romance, friendships, networking and more, Dating Pro is the place for you. Get started today and find your match! &nbsp; &nbsp;&nbsp;</p>";s:7:"html_ru";s:1136:"<p>Миллионы пользователей по всему миру ищут вас, чтобы поделиться своими впечатлениями и переживаниями! Новые люди присоединяются к нам постоянно, и вас ждет успех в поиске идеального партнера, который всегда будет с вами!.</p><p><br />Хотите присоединиться? Просто создайте свою анкету, добавьте фотографии и вы сможете общаться со всеми замечательными людьми на этом сайте. Что может быть проще! Вы можете получить особые привилегии для более эффективного общения на сайте. Реальные взаимотношения, общение, дружба и многое другое! Dating Pro - это твой сайт! Начни сегодня и сделай шаг вперед навстречу своей судьбе! &nbsp; &nbsp;&nbsp;</p>";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 9,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'companions',
                    'gid_area' => 'companions',
                    'params' => 'a:4:{s:8:"title_en";s:77:"Welcome to Dating Pro, professional service for Online Dating and Networking.";s:8:"title_ru";s:152:"Добро пожаловать на Dating Pro, профессиональный сервис для онлайн знакомств и общения.";s:7:"html_en";s:633:"<p>Millions of members worldwide, looking for others to share their experiences with, are here in our community now! New singles are joining all the time, and many are making connections every day.</p><p><br />Want to join in? Just create a simple profile, post up your photos, and soon you&#39;ll be networking with our incredible personals. Online personals couldn&#39;t be easier! You can even upgrade your membership for email, instant messaging, and real-time chat. For real relationships, romance, friendships, networking and more, Dating Pro is the place for you. Get started today and find your match! &nbsp; &nbsp;&nbsp;</p>";s:7:"html_ru";s:1136:"<p>Миллионы пользователей по всему миру ищут вас, чтобы поделиться своими впечатлениями и переживаниями! Новые люди присоединяются к нам постоянно, и вас ждет успех в поиске идеального партнера, который всегда будет с вами!.</p><p><br />Хотите присоединиться? Просто создайте свою анкету, добавьте фотографии и вы сможете общаться со всеми замечательными людьми на этом сайте. Что может быть проще! Вы можете получить особые привилегии для более эффективного общения на сайте. Реальные взаимотношения, общение, дружба и многое другое! Dating Pro - это твой сайт! Начни сегодня и сделай шаг вперед навстречу своей судьбе! &nbsp; &nbsp;&nbsp;</p>";}',
                    'view_str' => 'default',
                    'width' => 30,
                    'sorter' => 10,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'autumn_walk',
                    'gid_area' => 'autumn_walk',
                    'params' => 'a:4:{s:8:"title_en";s:16:"Want to join in?";s:8:"title_ru";s:42:"Хотите присоединиться?";s:7:"html_en";s:486:"<p style="background-color:rgba(255,255,255,0.8); padding:5px;"><span style="font-size:16px">Just create a simple profile, post up your photos, and soon you&#39;ll be networking with our incredible personals. Online personals couldn&#39;t be easier! You can even upgrade your membership for email, instant messaging, and real-time chat. For real relationships, romance, friendships, networking and more, Dating Pro is the place for you. Get started today and find your match!</span></p>";s:7:"html_ru";s:752:"<p style="background-color:rgba(255,255,255,0.8); padding:5px;"><span style="font-size:16px">Просто создайте свою анкету, добавьте фотографии и вы сможете общаться со всеми замечательными людьми на этом сайте. Что может быть проще! Вы можете получить особые привилегии для более эффективного общения на сайте. Реальные взаимотношения, общение, дружба и многое другое! Dating Pro - это твой сайт! Начни сегодня и сделай шаг вперед навстречу своей судьбе!</span></p>";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 10,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'persimmon_red',
                    'gid_area' => 'persimmon_red',
                    'gid' => 'persimmon_red',
                    'params' => 'a:4:{s:8:"title_en";s:77:"Welcome to Dating Pro, professional service for Online Dating and Networking.";s:8:"title_ru";s:152:"Добро пожаловать на Dating Pro, профессиональный сервис для онлайн знакомств и общения.";s:7:"html_en";s:254:"<p>Thousands of members join our community from all over the world.<p>Create a profile, post your photos, and soon you will be communicating with all these incredible people.</p><p>We hope that our site is the place where you will find your lifemate!</p>";s:7:"html_ru";s:1110:"<p>Миллионы пользователей по всему миру ищут вас, чтобы поделиться своими впечатлениями и переживаниями! Новые люди присоединяются к нам постоянно, и вас ждет успех в поиске идеального партнера, который всегда будет с вами!.</p><p>Хотите присоединиться? Просто создайте свою анкету, добавьте фотографии и вы сможете общаться со всеми замечательными людьми на этом сайте. Что может быть проще! Вы можете получить особые привилегии для более эффективного общения на сайте. Реальные взаимотношения, общение, дружба и многое другое! Dating Pro - это твой сайт! Начни сегодня и сделай шаг вперед навстречу своей судьбе!</p>";}',
                    'view_str' => 'default',
                    'width' => 30,
                    'sorter' => 6,
                    'cache_time' => 600,
                ),
                array(
                    'gid_preset' => 'persimmon_red',
                    'gid_area' => 'persimmon_red',
                    'params' => 'a:4:{s:8:"title_en";s:16:"Want to join in?";s:8:"title_ru";s:42:"Хотите присоединиться?";s:7:"html_en";s:425:"<p><span style="font-size:16px">Just create a simple profile, post up your photos, and soon you&#39;ll be networking with our incredible personals. Online personals couldn&#39;t be easier! You can even upgrade your membership for email, instant messaging, and real-time chat. For real relationships, romance, friendships, networking and more, Dating Pro is the place for you. Get started today and find your match!</span></p>";s:7:"html_ru";s:691:"<p><span style="font-size:16px">Просто создайте свою анкету, добавьте фотографии и вы сможете общаться со всеми замечательными людьми на этом сайте. Что может быть проще! Вы можете получить особые привилегии для более эффективного общения на сайте. Реальные взаимотношения, общение, дружба и многое другое! Dating Pro - это твой сайт! Начни сегодня и сделай шаг вперед навстречу своей судьбе!</span></p>";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 15,
                    'cache_time' => 600,
                ),
            ),
        ),
        'empty_block' => array(
            'gid' => 'empty_block',
            'module' => 'dynamic_blocks',
            'model' => 'Dynamic_blocks_model',
            'method' => '_dynamic_block_get_empty_block',
            'params' => array(
                'height' => array(
                    'type' => 'int',
                    'default' => '100',
                    'gid' => 'height',
                ),
            ),
            'views' => array(
                array(
                    'gid' => 'default',
                ),
            ),
            "area" => array(
                array(
                    'gid' => 'index-page',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 1,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'index-page',
                    'params' => 'a:1:{s:6:"height";s:2:"30";}',
                    'view_str' => 'default',
                    'width' => 30,
                    'sorter' => 8,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'index-page',
                    'params' => 'a:1:{s:6:"height";s:2:"30";}',
                    'view_str' => 'default',
                    'width' => 30,
                    'sorter' => 7,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'index-page',
                    'params' => 'a:1:{s:6:"height";s:3:"175";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 5,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'index-page',
                    'params' => 'a:1:{s:6:"height";s:2:"30";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 9,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'index-page',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 12,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'mediumturquoise',
                    'params' => 'a:1:{s:6:"height";s:3:"100";}',
                    'view_str' => 'default',
                    'width' => 70,
                    'sorter' => 5,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'mediumturquoise',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 7,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'mediumturquoise',
                    'params' => 'a:1:{s:6:"height";s:2:"15";}',
                    'view_str' => 'default',
                    'width' => 70,
                    'sorter' => 8,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'mediumturquoise',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 10,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'mediumturquoise',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 12,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'mediumturquoise',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 1,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'lavender',
                    'params' => 'a:1:{s:6:"height";s:3:"100";}',
                    'view_str' => 'default',
                    'width' => 70,
                    'sorter' => 5,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'lavender',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 7,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'lavender',
                    'params' => 'a:1:{s:6:"height";s:2:"15";}',
                    'view_str' => 'default',
                    'width' => 70,
                    'sorter' => 8,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'lavender',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 10,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'lavender',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 12,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'lavender',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 1,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'girls',
                    'params' => 'a:1:{s:6:"height";s:2:"30";}',
                    'view_str' => 'default',
                    'width' => 30,
                    'sorter' => 5,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'girls',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 1,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'girlfriends',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 1,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'girls',
                    'params' => 'a:1:{s:6:"height";s:2:"30";}',
                    'view_str' => 'default',
                    'width' => 40,
                    'sorter' => 7,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'girls',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 10,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'girls',
                    'params' => 'a:1:{s:6:"height";s:3:"190";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 8,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'girls',
                    'params' => 'a:1:{s:6:"height";s:3:"175";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 4,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'jewish',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 4,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'lovers',
                    'params' => 'a:1:{s:6:"height";s:3:"340";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 4,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'lovers',
                    'params' => 'a:1:{s:6:"height";s:2:"40";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 6,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'neighbourhood',
                    'params' => 'a:1:{s:6:"height";s:3:"100";}',
                    'view_str' => 'default',
                    'width' => 70,
                    'sorter' => 6,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'neighbourhood',
                    'params' => 'a:1:{s:6:"height";s:3:"190";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 7,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'neighbourhood',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 1,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'neighbourhood',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 4,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'blackonwhite',
                    'params' => 'a:1:{s:6:"height";s:3:"270";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 5,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'blackonwhite',
                    'params' => 'a:1:{s:6:"height";s:2:"60";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 8,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'blackonwhite',
                    'params' => 'a:1:{s:6:"height";s:3:"100";}',
                    'view_str' => 'default',
                    'width' => 50,
                    'sorter' => 7,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'blackonwhite',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 1,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'deepblue',
                    'params' => 'a:1:{s:6:"height";s:3:"320";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 4,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'deepblue',
                    'params' => 'a:1:{s:6:"height";s:2:"40";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 6,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'deepblue',
                    'params' => 'a:1:{s:6:"height";s:2:"15";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 8,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'deepblue',
                    'params' => 'a:1:{s:6:"height";s:2:"15";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 10,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'companions',
                    'params' => 'a:1:{s:6:"height";s:3:"100";}',
                    'view_str' => 'default',
                    'width' => 70,
                    'sorter' => 5,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'companions',
                    'params' => 'a:1:{s:6:"height";s:3:"100";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 4,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'companions',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 1,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'companions',
                    'params' => 'a:1:{s:6:"height";s:3:"100";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 7,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'companions',
                    'params' => 'a:1:{s:6:"height";s:3:"100";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 11,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'christian',
                    'params' => 'a:1:{s:6:"height";s:3:"240";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 6,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'christian',
                    'params' => 'a:1:{s:6:"height";s:2:"50";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 8,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'christian',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 10,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'christian',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 1,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'christian',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 4,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'autumn_walk',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 1,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'autumn_walk',
                    'params' => 'a:1:{s:6:"height";s:3:"175";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 5,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'autumn_walk',
                    'params' => 'a:1:{s:6:"height";s:2:"30";}',
                    'view_str' => 'default',
                    'width' => 30,
                    'sorter' => 6,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'autumn_walk',
                    'params' => 'a:1:{s:6:"height";s:2:"30";}',
                    'view_str' => 'default',
                    'width' => 70,
                    'sorter' => 7,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'autumn_walk',
                    'params' => 'a:1:{s:6:"height";s:2:"30";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 8,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'autumn_walk',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 10,
                    'cache_time' => 0,
                ),
                array(
                    'gid' => 'persimmon_red',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 1,
                    'cache_time' => 600,
                ),
                array(
                    'gid' => 'persimmon_red',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 5,
                    'cache_time' => 600,
                ),
                array(
                    'gid' => 'persimmon_red',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 30,
                    'sorter' => 7,
                    'cache_time' => 600,
                ),
                array(
                    'gid' => 'persimmon_red',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 40,
                    'sorter' => 8,
                    'cache_time' => 600,
                ),
                array(
                    'gid' => 'persimmon_red',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 9,
                    'cache_time' => 600,
                ),
                array(
                    'gid' => 'persimmon_red',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 13,
                    'cache_time' => 600,
                ),
            ),
            "presets" => array(
                array(
                    'gid_preset' => 'index-page',
                    'gid_area' => 'index-page',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 1,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'index-page',
                    'gid_area' => 'index-page',
                    'params' => 'a:1:{s:6:"height";s:2:"30";}',
                    'view_str' => 'default',
                    'width' => 30,
                    'sorter' => 8,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'index-page',
                    'gid_area' => 'index-page',
                    'params' => 'a:1:{s:6:"height";s:2:"30";}',
                    'view_str' => 'default',
                    'width' => 30,
                    'sorter' => 7,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'index-page',
                    'gid_area' => 'index-page',
                    'params' => 'a:1:{s:6:"height";s:3:"175";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 5,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'index-page',
                    'gid_area' => 'index-page',
                    'params' => 'a:1:{s:6:"height";s:2:"30";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 9,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'index-page',
                    'gid_area' => 'index-page',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 12,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'mediumturquoise',
                    'gid_area' => 'mediumturquoise',
                    'params' => 'a:1:{s:6:"height";s:3:"100";}',
                    'view_str' => 'default',
                    'width' => 70,
                    'sorter' => 5,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'mediumturquoise',
                    'gid_area' => 'mediumturquoise',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 7,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'mediumturquoise',
                    'gid_area' => 'mediumturquoise',
                    'params' => 'a:1:{s:6:"height";s:2:"15";}',
                    'view_str' => 'default',
                    'width' => 70,
                    'sorter' => 8,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'mediumturquoise',
                    'gid_area' => 'mediumturquoise',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 10,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'mediumturquoise',
                    'gid_area' => 'mediumturquoise',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 12,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'mediumturquoise',
                    'gid_area' => 'mediumturquoise',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 1,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'lavender',
                    'gid_area' => 'lavender',
                    'params' => 'a:1:{s:6:"height";s:3:"100";}',
                    'view_str' => 'default',
                    'width' => 70,
                    'sorter' => 5,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'lavender',
                    'gid_area' => 'lavender',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 7,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'lavender',
                    'gid_area' => 'lavender',
                    'params' => 'a:1:{s:6:"height";s:2:"15";}',
                    'view_str' => 'default',
                    'width' => 70,
                    'sorter' => 8,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'lavender',
                    'gid_area' => 'lavender',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 10,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'lavender',
                    'gid_area' => 'lavender',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 12,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'lavender',
                    'gid_area' => 'lavender',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 1,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'girls',
                    'gid_area' => 'girls',
                    'params' => 'a:1:{s:6:"height";s:2:"30";}',
                    'view_str' => 'default',
                    'width' => 30,
                    'sorter' => 5,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'girls',
                    'gid_area' => 'girls',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 1,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'girlfriends',
                    'gid_area' => 'girlfriends',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 1,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'girls',
                    'gid_area' => 'girls',
                    'params' => 'a:1:{s:6:"height";s:2:"30";}',
                    'view_str' => 'default',
                    'width' => 40,
                    'sorter' => 7,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'girls',
                    'gid_area' => 'girls',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 10,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'girls',
                    'gid_area' => 'girls',
                    'params' => 'a:1:{s:6:"height";s:3:"190";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 8,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'girls',
                    'gid_area' => 'girls',
                    'params' => 'a:1:{s:6:"height";s:3:"175";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 4,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'jewish',
                    'gid_area' => 'jewish',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 4,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'lovers',
                    'gid_area' => 'lovers',
                    'params' => 'a:1:{s:6:"height";s:3:"340";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 4,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'lovers',
                    'gid_area' => 'lovers',
                    'params' => 'a:1:{s:6:"height";s:2:"40";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 6,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'neighbourhood',
                    'gid_area' => 'neighbourhood',
                    'params' => 'a:1:{s:6:"height";s:3:"100";}',
                    'view_str' => 'default',
                    'width' => 70,
                    'sorter' => 6,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'neighbourhood',
                    'gid_area' => 'neighbourhood',
                    'params' => 'a:1:{s:6:"height";s:3:"190";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 7,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'neighbourhood',
                    'gid_area' => 'neighbourhood',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 1,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'neighbourhood',
                    'gid_area' => 'neighbourhood',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 4,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'blackonwhite',
                    'gid_area' => 'blackonwhite',
                    'params' => 'a:1:{s:6:"height";s:3:"270";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 5,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'blackonwhite',
                    'gid_area' => 'blackonwhite',
                    'params' => 'a:1:{s:6:"height";s:2:"60";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 8,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'blackonwhite',
                    'gid_area' => 'blackonwhite',
                    'params' => 'a:1:{s:6:"height";s:3:"100";}',
                    'view_str' => 'default',
                    'width' => 50,
                    'sorter' => 7,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'blackonwhite',
                    'gid_area' => 'blackonwhite',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 1,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'deepblue',
                    'gid_area' => 'deepblue',
                    'params' => 'a:1:{s:6:"height";s:3:"320";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 4,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'deepblue',
                    'gid_area' => 'deepblue',
                    'params' => 'a:1:{s:6:"height";s:2:"40";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 6,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'deepblue',
                    'gid_area' => 'deepblue',
                    'params' => 'a:1:{s:6:"height";s:2:"15";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 8,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'deepblue',
                    'gid_area' => 'deepblue',
                    'params' => 'a:1:{s:6:"height";s:2:"15";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 10,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'companions',
                    'gid_area' => 'companions',
                    'params' => 'a:1:{s:6:"height";s:3:"100";}',
                    'view_str' => 'default',
                    'width' => 70,
                    'sorter' => 5,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'companions',
                    'gid_area' => 'companions',
                    'params' => 'a:1:{s:6:"height";s:3:"100";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 4,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'companions',
                    'gid_area' => 'companions',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 1,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'companions',
                    'gid_area' => 'companions',
                    'params' => 'a:1:{s:6:"height";s:3:"100";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 7,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'companions',
                    'gid_area' => 'companions',
                    'params' => 'a:1:{s:6:"height";s:3:"100";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 11,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'christian',
                    'gid_area' => 'christian',
                    'params' => 'a:1:{s:6:"height";s:3:"240";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 6,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'christian',
                    'gid_area' => 'christian',
                    'params' => 'a:1:{s:6:"height";s:2:"50";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 8,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'christian',
                    'gid_area' => 'christian',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 10,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'christian',
                    'gid_area' => 'christian',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 1,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'christian',
                    'gid_area' => 'christian',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 4,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'autumn_walk',
                    'gid_area' => 'autumn_walk',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 1,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'autumn_walk',
                    'gid_area' => 'autumn_walk',
                    'params' => 'a:1:{s:6:"height";s:3:"175";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 5,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'autumn_walk',
                    'gid_area' => 'autumn_walk',
                    'params' => 'a:1:{s:6:"height";s:2:"30";}',
                    'view_str' => 'default',
                    'width' => 30,
                    'sorter' => 6,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'autumn_walk',
                    'gid_area' => 'autumn_walk',
                    'params' => 'a:1:{s:6:"height";s:2:"30";}',
                    'view_str' => 'default',
                    'width' => 70,
                    'sorter' => 7,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'autumn_walk',
                    'gid_area' => 'autumn_walk',
                    'params' => 'a:1:{s:6:"height";s:2:"30";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 8,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'autumn_walk',
                    'gid_area' => 'autumn_walk',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 10,
                    'cache_time' => 0,
                ),
                array(
                    'gid_preset' => 'persimmon_red',
                    'gid_area' => 'persimmon_red',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 1,
                    'cache_time' => 600,
                ),
                array(
                    'gid_preset' => 'persimmon_red',
                    'gid_area' => 'persimmon_red',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 5,
                    'cache_time' => 600,
                ),
                array(
                    'gid_preset' => 'persimmon_red',
                    'gid_area' => 'persimmon_red',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 30,
                    'sorter' => 7,
                    'cache_time' => 600,
                ),
                array(
                    'gid_preset' => 'persimmon_red',
                    'gid_area' => 'persimmon_red',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 40,
                    'sorter' => 8,
                    'cache_time' => 600,
                ),
                array(
                    'gid_preset' => 'persimmon_red',
                    'gid_area' => 'persimmon_red',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 9,
                    'cache_time' => 600,
                ),
                array(
                    'gid_preset' => 'persimmon_red',
                    'gid_area' => 'persimmon_red',
                    'params' => 'a:1:{s:6:"height";s:1:"0";}',
                    'view_str' => 'default',
                    'width' => 100,
                    'sorter' => 13,
                    'cache_time' => 600,
                ),
            ),
        ),
    );

    /**
     * Themes events
     * 
     * @var array
     */
    protected $themes_events = array(
        'module' => 'dynamic_blocks',
        'model' => 'Dynamic_blocks_model',
        'activate_callback' => 'cache_all_clear',
    );

    /**
     * Constructor
     *
     * @return Dynamic_blocks_install_model
     */
    public function __construct()
    {
        parent::__construct();
        $this->CI = &get_instance();
        // load langs
        $this->CI->load->model('Install_model');
    }

    /**
     * Install data of menu module
     */
    public function install_menu()
    {
        $this->CI->load->helper('menu');
        foreach ($this->menu as $gid => $menu_data) {
            $this->menu[$gid]['id'] = linked_install_set_menu($gid, $menu_data["action"], $menu_data["name"]);
            linked_install_process_menu_items($this->menu, 'create', $gid, 0, $this->menu[$gid]["items"]);
        }
    }

    /**
     * Import languages of menu module
     * 
     * @param array $langs_ids languages identifiers
     * @return void
     */
    public function install_menu_lang_update($langs_ids = null)
    {
        if (empty($langs_ids)) {
            return false;
        }
        $langs_file = $this->CI->Install_model->language_file_read('dynamic_blocks', 'menu', $langs_ids);

        if (!$langs_file) {
            log_message('info', 'Empty menu langs data');
            return false;
        }

        $this->CI->load->helper('menu');

        foreach ($this->menu as $gid => $menu_data) {
            linked_install_process_menu_items($this->menu, 'update', $gid, 0, $this->menu[$gid]["items"], $gid, $langs_file);
        }
        return true;
    }

    /**
     * Export languages of menu module
     * 
     * @param array $langs_ids languages identifiers
     * @return array
     */
    public function install_menu_lang_export($langs_ids)
    {
        if (empty($langs_ids)) {
            return false;
        }
        $this->CI->load->helper('menu');

        $return = array();
        foreach ($this->menu as $gid => $menu_data) {
            $temp = linked_install_process_menu_items($this->menu, 'export', $gid, 0, $this->menu[$gid]["items"], $gid, $langs_ids);
            $return = array_merge($return, $temp);
        }
        return array("menu" => $return);
    }

    /**
     * Uninstall data of menu module
     * 
     * @return void
     */
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
     * Install data of ausers module
     * 
     * @return void
     */
    public function install_moderators()
    {
        // install moderators permissions
        $this->CI->load->model('Moderators_model');

        foreach ($this->moderators_methods as $method) {
            $this->CI->Moderators_model->save_method(null, $method);
        }
    }

    /**
     * Import languages of asuers module
     * 
     * @param array $langs_ids languages identifiers
     * @return array
     */
    public function install_moderators_lang_update($langs_ids = null)
    {
        $langs_file = $this->CI->Install_model->language_file_read('dynamic_blocks', 'moderators', $langs_ids);

        // install moderators permissions
        $this->CI->load->model('Moderators_model');
        $params['where']['module'] = 'dynamic_blocks';
        $methods = $this->CI->Moderators_model->get_methods_lang_export($params);

        foreach ($methods as $method) {
            if (!empty($langs_file[$method['method']])) {
                $this->CI->Moderators_model->save_method($method['id'], array(), $langs_file[$method['method']]);
            }
        }
    }

    /**
     * Export languages of ausers module
     * 
     * @param array $langs_ids languages identifiers
     * @return array
     */
    public function install_moderators_lang_export($langs_ids)
    {
        $this->CI->load->model('Moderators_model');
        $params['where']['module'] = 'dynamic_blocks';
        $methods = $this->CI->Moderators_model->get_methods_lang_export($params, $langs_ids);
        foreach ($methods as $method) {
            $return[$method['method']] = $method['langs'];
        }
        return array('moderators' => $return);
    }

    /**
     * Uninstall data of ausers module
     * 
     * @return void
     */
    public function deinstall_moderators()
    {
        // delete moderation methods in moderators
        $this->CI->load->model('Moderators_model');
        $params['where']['module'] = 'dynamic_blocks';
        $this->CI->Moderators_model->delete_methods($params);
    }

     /**
     * Install module data
     * 
     * @return void
     */
    public function _arbitrary_installing()
    {
        $this->CI->load->model('Dynamic_blocks_model');
        $this->CI->Dynamic_blocks_model->installBatch($this->dynamic_blocks);

        $lang_dm_data = array(
            'module' => 'dynamic_blocks',
            'model' => 'Dynamic_blocks_model',
            'method_add' => 'lang_dedicate_module_callback_add',
            'method_delete' => 'lang_dedicate_module_callback_delete'
        );
        $this->CI->pg_language->add_dedicate_modules_entry($lang_dm_data);
        foreach ($this->CI->pg_language->languages as $value) {
            $this->CI->Dynamic_blocks_model->lang_dedicate_module_callback_add($value['id']);
        }
    }

    /**
     * Import module languages
     * 
     * @param array $langs_ids languages identifiers
     * @return void
     */
    public function _arbitrary_lang_install($langs_ids = null)
    {
        $this->CI->load->model('Dynamic_blocks_model');
        return $this->CI->Dynamic_blocks_model->updateLangsByModuleBlocks($this->dynamic_blocks, $langs_ids);
    }

    /**
     * Export module languages
     * 
     * @param array $langs_ids languages identifiers
     * @return array
     */
    public function _arbitrary_lang_export($langs_ids = null)
    {
        $this->CI->load->model('Dynamic_blocks_model');
        return array(
            'dynamic_blocks' => $this->CI->Dynamic_blocks_model->export_langs($this->dynamic_blocks, $langs_ids),
        );
    }

    /**
     * Uninstall module data
     * 
     * @return array
     */
    public function _arbitrary_deinstalling()
    {
        $this->CI->load->model("Dynamic_blocks_model");
        foreach ((array) $this->dynamic_blocks as $block_data) {
            $this->CI->Dynamic_blocks_model->delete_block_by_gid($block_data["gid"]);
        }
        $lang_dm_data['where'] = array(
            'module' => 'dynamic_blocks',
        );
        $this->CI->pg_language->delete_dedicate_modules_entry($lang_dm_data);
    }

}
