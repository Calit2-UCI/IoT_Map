DROP TABLE IF EXISTS `[prefix]im_contact_list`;
CREATE TABLE `[prefix]im_contact_list` (
  `id_user` int(11) NOT NULL,
  `id_contact` int(11) NOT NULL,
  `site_status` tinyint(3) NOT NULL DEFAULT '0',
  `count_new` int(11) NOT NULL DEFAULT '0',
  `date_update` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `id_user_id_contact` (`id_user`,`id_contact`),
  KEY `id_contact` (`id_contact`),
  KEY `id_user_date_update_count_new` (`id_user`,`date_update`,`count_new`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]im_messages`;
CREATE TABLE `[prefix]im_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_linked` int(11) DEFAULT NULL,
  `id_user` int(11) NOT NULL,
  `id_contact` int(11) NOT NULL,
  `text` text NOT NULL,
  `dir` enum('i','o') NOT NULL,
  `date_add` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_read` tinyint(3) NOT NULL DEFAULT '0',
  `is_notified` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_linked` (`id_linked`),
  KEY `id_user_id_contact` (`id_user`,`id_contact`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]im`;
CREATE TABLE `[prefix]im` (
  `id_user` int(11) NOT NULL,
  `date_add` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_update` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_end` datetime NOT NULL,
  UNIQUE KEY `id_user` (`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
