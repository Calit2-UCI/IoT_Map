DROP TABLE IF EXISTS `[prefix]field_editor_fields`;
CREATE TABLE IF NOT EXISTS `[prefix]field_editor_fields` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `gid` varchar(25) NOT NULL,
  `section_gid` varchar(20) NOT NULL,
  `editor_type_gid` varchar(20) NOT NULL,
  `field_type` enum('select','textarea','text','checkbox', 'multiselect', 'range') NOT NULL,
  `fts` TINYINT(3) NOT NULL DEFAULT '0',
  `settings_data` text NOT NULL,
  `sorter` int(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `section_gid` (`section_gid`),
  KEY `gid` (`gid`),
  KEY `section_gid_2` (`section_gid`,`sorter`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]field_editor_forms`;
CREATE TABLE IF NOT EXISTS `[prefix]field_editor_forms` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `gid` varchar(20) NOT NULL,
  `editor_type_gid` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `field_data` text NOT NULL,
  `is_system` TINYINT(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `gid` (`gid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]field_editor_saved_searches`;
CREATE TABLE IF NOT EXISTS `[prefix]field_editor_saved_searches` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `id_user` int(3) NOT NULL,
  `editor_type_gid` varchar(20) NOT NULL,
  `form_gid` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `criteria` text NOT NULL,
  `date_add` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`,`editor_type_gid`,`form_gid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]field_editor_sections`;
CREATE TABLE IF NOT EXISTS `[prefix]field_editor_sections` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `gid` varchar(20) NOT NULL,
  `editor_type_gid` varchar(20) NOT NULL,
  `sorter` int(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gid` (`gid`),
  KEY `editor_type_gid` (`editor_type_gid`),
  KEY `sorter` (`sorter`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
