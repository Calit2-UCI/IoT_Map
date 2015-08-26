DROP TABLE IF EXISTS `[prefix]videos_config`;
CREATE TABLE IF NOT EXISTS `[prefix]videos_config` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `gid` varchar(100) NOT NULL,
  `max_size` int(3) NOT NULL,
  `file_formats` text NOT NULL,
  `upload_type` varchar(10) NOT NULL,
  `use_convert` tinyint(3) NOT NULL,
  `use_thumbs` tinyint(3) NOT NULL,
  `thumbs_settings` text NOT NULL,
  `default_img` varchar(255) NOT NULL,
  `local_settings` text NOT NULL,
  `youtube_settings` text NOT NULL,
  `module` varchar(25) NOT NULL,
  `model` varchar(100) NOT NULL,
  `method_status` varchar(100) NOT NULL,
  `date_add` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gid` (`gid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `[prefix]videos_process`;
CREATE TABLE IF NOT EXISTS `[prefix]videos_process` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_data` text NOT NULL,
  `id_object` int(3) NOT NULL,
  `video_upload_gid` varchar(100) NOT NULL,
  `status` varchar(20) NOT NULL,
  `date_add` datetime NOT NULL,
  `wait_counter` int(3) NOT NULL,  
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
