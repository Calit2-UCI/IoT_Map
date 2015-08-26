DROP TABLE IF EXISTS `[prefix]comments`;
CREATE TABLE `[prefix]comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gid` varchar(25) NOT NULL,
  `id_object` int(11) NOT NULL,
  `id_user` int(11) NOT NULL DEFAULT '0',
  `id_owner` int(11) NOT NULL DEFAULT '0',
  `user_name` varchar(50) NOT NULL DEFAULT '',
  `text` text NOT NULL,
  `likes` int(11) NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `gid_id_object` (`gid`,`id_object`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]comments_types`;
CREATE TABLE `[prefix]comments_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gid` varchar(25) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `module` varchar(50) NOT NULL DEFAULT '',
  `model` varchar(50) NOT NULL DEFAULT '',
  `method_count` varchar(50) NOT NULL DEFAULT '',
  `method_object` varchar(50) NOT NULL DEFAULT '',
  `settings` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `gid` (`gid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;