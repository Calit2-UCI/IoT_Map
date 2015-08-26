DROP TABLE IF EXISTS `[prefix]spam_alerts`;
CREATE TABLE IF NOT EXISTS `[prefix]spam_alerts` (
  `id` bigint(11) NOT NULL auto_increment,
  `id_type` int(3) NOT NULL,
  `id_object` INT(11) NOT NULL,
  `id_poster` INT(11) NOT NULL,
  `id_reason` SMALLINT(5) NOT NULL,
  `message` text NULL,
  `mark` tinyint(1) NOT NULL,
  `date_add` datetime NOT NULL,
  `spam_status` enum('none', 'banned', 'unbanned', 'removed') NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `id_type` (`id_type`, `id_object`, `id_poster`, `id_reason`),
  KEY `date_add` (`date_add`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]spam_types`;
CREATE TABLE IF NOT EXISTS `[prefix]spam_types` (
  `id` int(3) NOT NULL auto_increment,
  `gid` varchar(20) NOT NULL,
  `form_type` enum('none', 'checkbox', 'select_text') NOT NULL,
  `send_mail` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `module` varchar(20) NOT NULL,
  `model` varchar(20) NOT NULL,
  `callback` varchar(255) NOT NULL,
  `obj_count` INT(11) NOT NULL,
  `obj_need_approve` INT(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `gid` (`gid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
