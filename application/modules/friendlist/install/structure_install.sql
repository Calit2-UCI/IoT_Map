DROP TABLE IF EXISTS `[prefix]friendlist`;
CREATE TABLE `[prefix]friendlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_dest_user` int(11) NOT NULL,
  `comment` text NOT NULL,
  `status` tinyint(2) NOT NULL DEFAULT '0',
  `date_add` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_update` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `notified` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_user_id_dest_user` (`id_user`,`id_dest_user`),
  KEY `id_dest_user` (`id_dest_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]friendlist_callbacks`;
CREATE TABLE `[prefix]friendlist_callbacks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(50) NOT NULL,
  `model` varchar(50) NOT NULL,
  `method` varchar(50) NOT NULL,
  `event_status` varchar(20) NOT NULL DEFAULT '',
  `date_add` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `event_status` (`event_status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;