DROP TABLE IF EXISTS `[prefix]wall_events`;
CREATE TABLE `[prefix]wall_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_type_gid` varchar(25) NOT NULL,
  `id_wall` int(11) NOT NULL DEFAULT '0',
  `id_poster` int(11) NOT NULL DEFAULT '0',
  `id_object` int(11) NOT NULL DEFAULT '0',
  `date_add` datetime NOT NULL,
  `date_update` datetime NOT NULL,
  `data` text NOT NULL,
  `permissions` tinyint(3) NOT NULL DEFAULT '3',
  `status` tinyint(3) NOT NULL DEFAULT '1',
  `comments_count` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `event_type_gid` (`event_type_gid`),
  KEY `id_wall_event_type_gid_status` (`id_wall`,`event_type_gid`,`status`),
  KEY `id_wall_status_date_update` (`id_wall`,`status`,`date_update`),
  KEY `id_wall_status_id` (`id_wall`,`status`,`id`),
  KEY `status_permissions` (`status`,`permissions`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]wall_events_types`;
CREATE TABLE `[prefix]wall_events_types` (
  `gid` varchar(25) NOT NULL,
  `module` varchar(50) NOT NULL,
  `model` varchar(50) NOT NULL,
  `method_format_event` varchar(50) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `settings` text NOT NULL,
  `date_add` datetime NOT NULL,
  `date_update` datetime NOT NULL,
  UNIQUE KEY `gid` (`gid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]wall_events_permissions`;
CREATE TABLE `[prefix]wall_events_permissions` (
  `id_user` int(11) NOT NULL,
  `permissions` text NOT NULL,
  UNIQUE KEY `id_user` (`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;