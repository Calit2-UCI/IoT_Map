DROP TABLE IF EXISTS `[prefix]file_uploads`;
CREATE TABLE IF NOT EXISTS `[prefix]file_uploads` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `gid` varchar(25) NOT NULL,
  `name` varchar(100) NOT NULL,
  `max_size` int(3) NOT NULL,
  `name_format` enum('generate','format') NOT NULL DEFAULT 'generate',
  `file_formats` text NOT NULL,
  `date_add` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gid` (`gid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
