DROP TABLE IF EXISTS `[prefix]mailbox`;
CREATE TABLE IF NOT EXISTS `[prefix]mailbox` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `id_pair` int(3) NOT NULL,
  `id_reply` int(3) NOT NULL DEFAULT '0',
  `id_user` int(3) NOT NULL,
  `id_from_user` int(3) NOT NULL,
  `id_to_user` int(3) NOT NULL,
  `subject` text NOT NULL,
  `message` text NOT NULL,
  `is_new` tinyint(3) NOT NULL DEFAULT '1',
  `date_add` datetime NOT NULL,
  `date_read` datetime NOT NULL,
  `date_trash` datetime NOT NULL,
  `id_thread` int(3) NOT NULL DEFAULT '0',
  `folder` enum('inbox','outbox','drafts','trash','spam') DEFAULT NULL,
  `from_folder` enum('inbox','outbox','drafts','trash','spam') DEFAULT NULL,
  `from_spam` enum('inbox','outbox','drafts','trash','spam') DEFAULT NULL,
  `attaches_count` tinyint(4) NOT NULL DEFAULT '0',
  `notified` tinyint(1) NOT NULL DEFAULT '0',
  `search_field` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user_folder_date_add` (`id_user`,`folder`,`date_add`),
  KEY `id_pair` (`id_pair`),
  KEY `id_user_id_thread` (`id_user`,`id_thread`),
  FULLTEXT `search_field` (`search_field`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]mailbox_attaches`;
CREATE TABLE IF NOT EXISTS `[prefix]mailbox_attaches` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `id_message` int(3) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `filesize` int(3) NOT NULL,
  `date_add` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_message` (`id_message`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]mailbox_services`;
CREATE TABLE IF NOT EXISTS `[prefix]mailbox_services` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `id_user` int(3) NOT NULL,
  `gid_service` varchar(50) NOT NULL,
  `service_data` text NOT NULL,
  `date_add` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_service` (`id_user`, `gid_service`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
