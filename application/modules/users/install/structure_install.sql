DROP TABLE IF EXISTS `[prefix]groups`;
CREATE TABLE IF NOT EXISTS `[prefix]groups` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `gid` varchar(50) NOT NULL,
  `is_default` tinyint(3) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gid` (`gid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `[prefix]users`;
CREATE TABLE IF NOT EXISTS `[prefix]users` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `email` varchar(50) NOT NULL,
  `nickname` varchar(20) NOT NULL,
  `user_type` varchar(50) NOT NULL,
  `looking_user_type` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `confirm` tinyint(3) NOT NULL,
  `approved` tinyint(3) NOT NULL,
  `activity` tinyint(3) NOT NULL,
  `confirm_code` varchar(10) NOT NULL DEFAULT '',
  `fname` varchar(100) NOT NULL,
  `sname` varchar(100) NOT NULL,
  `lang_id` int(3) NOT NULL,
  `user_open_id` varchar(100) NOT NULL,
  `user_logo` varchar(255) NOT NULL,
  `user_logo_moderation` varchar(100) NOT NULL,
  `logo_comments_count` int(11) NOT NULL DEFAULT '0',
  `group_id` int(3) NOT NULL,
  `account` float NOT NULL,
  `id_country` char(2) NOT NULL,
  `id_region` int(3) NOT NULL,
  `id_city` int(3) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` varchar(255) NOT NULL,
  `postal_code` varchar(12) NOT NULL,
  `birth_date` date NOT NULL,
  `age` tinyint(3) NOT NULL,
  `show_adult` tinyint(3) NOT NULL,
  `profile_completion` tinyint(3) NOT NULL,
  `views_count` int(11) NOT NULL,
  `age_min` tinyint(3) NOT NULL,
  `age_max` tinyint(3) NOT NULL,
  `search_field` text NOT NULL,
  `activated_end_date` datetime NOT NULL,
  `featured_end_date` datetime NOT NULL,
  `hide_on_site_end_date` datetime NOT NULL,
  `highlight_in_search_end_date` datetime NOT NULL,
  `up_in_search_end_date` datetime NOT NULL,
  `leader_bid` float NOT NULL,
  `leader_text` varchar(255) NOT NULL,
  `leader_write_date` datetime NOT NULL,
  `online_status` tinyint(3) NOT NULL DEFAULT '0',
  `site_status` tinyint(3) NOT NULL DEFAULT '0',
  `date_last_activity` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `id_seo_settings` int(3) NOT NULL,
  `lat` decimal(11,7) NOT NULL,
  `lon` decimal(11,7) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `email` (`email`),
  KEY `password` (`password`),
  KEY `user_open_id` (`user_open_id`),
  KEY `group_id` (`group_id`),
  KEY `online_status_date_last_activity` (`online_status`,`date_last_activity`),
  FULLTEXT `search_field` (`search_field`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]users_perfect_match`;
CREATE TABLE IF NOT EXISTS `[prefix]users_perfect_match` (
  `id_user` int(11) NOT NULL,
  `looking_user_type` varchar(50) DEFAULT NULL,
  `id_country` char(2) DEFAULT NULL,
  `id_region` int(3) DEFAULT NULL,
  `id_city` int(3) DEFAULT NULL,
  `age_min` tinyint(3) DEFAULT NULL,
  `age_max` tinyint(3) DEFAULT NULL,
  `full_criteria` text,
  PRIMARY KEY (`id_user`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]users_views`;
CREATE TABLE `[prefix]users_views` (
  `id_user` int(11) NOT NULL,
  `id_viewer` int(11) NOT NULL,
  `view_date` date NOT NULL,
  UNIQUE KEY `id_user_id_viewer_view_date` (`id_user`,`id_viewer`,`view_date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]users_viewers`;
CREATE TABLE `[prefix]users_viewers` (
  `id_user` int(11) NOT NULL,
  `id_viewer` int(11) NOT NULL,
  `view_date` datetime NOT NULL,
  UNIQUE KEY `id_user_id_viewer` (`id_user`,`id_viewer`),
  KEY `id_user_view_date` (`id_user`,`view_date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]users_statuses_callbacks`;
CREATE TABLE `[prefix]users_statuses_callbacks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(50) NOT NULL,
  `model` varchar(50) NOT NULL,
  `method` varchar(50) NOT NULL,
  `event_status` varchar(20) NOT NULL DEFAULT '',
  `date_add` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `event_status` (`event_status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]users_delete_callbacks`;
CREATE TABLE IF NOT EXISTS `[prefix]users_delete_callbacks` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `module` varchar(50) NOT NULL,
  `model` varchar(50) NOT NULL,
  `callback` varchar(255) NOT NULL,
  `callback_type` varchar(50) NOT NULL,
  `callback_gid` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]users_deleted`;
CREATE TABLE IF NOT EXISTS `[prefix]users_deleted` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `id_user` int(3) NOT NULL,
  `nickname` varchar(20) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `sname` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `data` text NOT NULL,
  `date_deleted` datetime NOT NULL,
  `status_deleted` tinyint(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
