DROP TABLE IF EXISTS `[prefix]memberships`;
CREATE TABLE IF NOT EXISTS `[prefix]memberships` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `gid` varchar(50) NOT NULL,
  `user_type_disabled` text NULL, 
  `user_type_disabled_code` bigint(20) NOT NULL, 
  `pay_type` enum('account','account_and_direct','direct') NOT NULL,
  `price` decimal(13,2) NOT NULL,
  `period_count` smallint(5) NOT NULL,
  `period_type` enum('years','months','days','hours') NOT NULL,
  `prices` text NULL,
  `services` text NULL,
  `priority` smallint(5) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `date_created` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE `gid` (`gid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]memberships_users`;
CREATE TABLE IF NOT EXISTS `[prefix]memberships_users` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `id_user` int(3) NOT NULL,
  `id_membership` int(3) NOT NULL,
  `price` float NOT NULL,
  `membership` text NOT NULL,
  `services` text NULL,
  `services_count` smallint(5) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `date_activated` timestamp NOT NULL,
  `date_expired` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_user` (`id_user`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
