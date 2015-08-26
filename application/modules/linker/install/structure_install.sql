DROP TABLE IF EXISTS `[prefix]linker`;
CREATE TABLE IF NOT EXISTS `[prefix]linker` (
  `id` bigint(11) NOT NULL auto_increment,
  `id_type` int(3) NOT NULL,
  `id_link_1` int(3) NOT NULL,
  `id_link_2` int(3) NOT NULL,
  `date_add` datetime NOT NULL,
  `sorter` int(3) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `id_type` (`id_type`,`id_link_1`,`id_link_2`),
  KEY `date_add` (`date_add`),
  KEY `sorter` (`sorter`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]linker_types`;
CREATE TABLE IF NOT EXISTS `[prefix]linker_types` (
  `id` int(3) NOT NULL auto_increment,
  `gid` varchar(20) NOT NULL,
  `separated` tinyint(3) NOT NULL default '0',
  `lifetime` int(3) NOT NULL,
  `unique_type` varchar(10) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `gid` (`gid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;