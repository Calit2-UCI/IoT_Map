DROP TABLE IF EXISTS `[prefix]notifications`;
CREATE TABLE IF NOT EXISTS `[prefix]notifications` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `gid` varchar(50) NOT NULL,
  `send_type` varchar(10) NOT NULL,
  `id_template_default` int(3) NOT NULL,
  `date_add` datetime NOT NULL,
  `date_update` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gid` (`gid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]notifications_sender`;
CREATE TABLE IF NOT EXISTS `[prefix]notifications_sender` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `content_type` varchar(10) NOT NULL,
  `send_counter` tinyint(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]notifications_templates`;
CREATE TABLE IF NOT EXISTS `[prefix]notifications_templates` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `gid` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `vars` text NOT NULL,
  `content_type` varchar(10) NOT NULL,
  `date_add` datetime NOT NULL,
  `date_update` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gid` (`gid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]notifications_templates_content`;
CREATE TABLE IF NOT EXISTS `[prefix]notifications_templates_content` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `id_template` int(3) NOT NULL,
  `id_lang` int(3) NOT NULL,
  `subject` text NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_template` (`id_template`,`id_lang`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;