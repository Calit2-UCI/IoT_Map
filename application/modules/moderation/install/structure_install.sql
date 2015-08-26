DROP TABLE IF EXISTS `[prefix]moderation_items`;
CREATE TABLE `[prefix]moderation_items` (
  `id` int(3) NOT NULL auto_increment,
  `id_type` int(3) NOT NULL,
  `id_object` int(3) NOT NULL,
  `date_add` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `id_type` (`id_type`,`id_object`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]moderation_type`;
CREATE TABLE `[prefix]moderation_type` (
  `id` int(3) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `mtype` tinyint(3) NOT NULL,
  `view_link` varchar(100) NOT NULL,
  `edit_link` varchar(100) NOT NULL,
  `module` varchar(50) NOT NULL,
  `model` varchar(100) NOT NULL,
  `method_get_list` varchar(50) NOT NULL,
  `method_set_status` varchar(50) NOT NULL,
  `allow_to_decline` enum('0','1') NOT NULL default '0',
  `method_delete_object` varchar(50) NOT NULL,
  `method_mark_adult` varchar(50) NOT NULL,
  `template_list_row` varchar(50) NOT NULL,
  `date_add` datetime NOT NULL,
  `check_badwords` tinyint(3) NOT NULL DEFAULT '1',
  PRIMARY KEY  (`id`),
  KEY `name` (`name`),
  KEY `mtype` (`mtype`),
  KEY `template_list_row` (`template_list_row`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]moderation_badwords`;
CREATE TABLE `[prefix]moderation_badwords` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `original` varchar(100) NOT NULL,
  `search` varchar(100) NOT NULL,
  `search_len` tinyint(3) NOT NULL,
  `search_ord` int(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `search` (`search`),
  KEY `search_ord` (`search_ord`)
) ENGINE = MYISAM DEFAULT CHARSET=utf8;

INSERT INTO `[prefix]moderation_badwords` VALUES(1, 'fuck', 'FUCK', 4, 70);
INSERT INTO `[prefix]moderation_badwords` VALUES(2, 'suck', 'SUCK', 4, 83);
INSERT INTO `[prefix]moderation_badwords` VALUES(3, 'bitch', 'BITCH', 5, 66);
INSERT INTO `[prefix]moderation_badwords` VALUES(4, 'idiot', 'IDIOT', 5, 73);