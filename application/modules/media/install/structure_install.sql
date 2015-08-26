DROP TABLE IF EXISTS `[prefix]media`;
CREATE TABLE IF NOT EXISTS `[prefix]media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_owner` int(11) NOT NULL,
  `id_parent` int(11) NOT NULL DEFAULT '0',
  `mediafile` varchar(100) NOT NULL,
  `upload_gid` varchar(50) NOT NULL,
  `mime` varchar(50) NOT NULL,
  `date_add` datetime NOT NULL,
  `permissions` tinyint(4) NOT NULL DEFAULT '4',
  `fname` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `media_video` varchar(100) NOT NULL,
  `media_video_image` varchar(100) NOT NULL,
  `media_video_data` text NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `comments_count` int(11) NOT NULL DEFAULT '0',
  `is_adult` tinyint(4) NOT NULL DEFAULT '0',
  `views` int(11) NOT NULL DEFAULT '0',
  `settings` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_parent` (`id_parent`),
  KEY `id_id_owner` (`id`,`id_owner`),
  KEY `mediafile` (`mediafile`(15)),
  KEY `media_video` (`media_video`(15))
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]album_types`;
CREATE TABLE IF NOT EXISTS `[prefix]album_types` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `gid` varchar(100) NOT NULL,
  `gid_upload_type` varchar(100) NOT NULL,
  `file_count` int(11) NOT NULL,
  `gid_upload_video` varchar(100) NOT NULL,
  `video_count` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]albums`;
CREATE TABLE IF NOT EXISTS `[prefix]albums` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_album_type` int(3) NOT NULL,
  `id_user` int(11) NOT NULL,
  `date_add` datetime NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `permissions` int(1) NOT NULL DEFAULT '4',
  `media_count` int(11) NOT NULL DEFAULT '0',
  `media_count_guest` int(11) NOT NULL DEFAULT '0',
  `media_count_user` int(11) NOT NULL DEFAULT '0',
  `is_default` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]media_album`;
CREATE TABLE IF NOT EXISTS `[prefix]media_album` (
  `media_id` int(11) NOT NULL,
  `album_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `is_adult` tinyint(4) NOT NULL DEFAULT '0',
  `permissions` tinyint(4) NOT NULL DEFAULT '4',
  `date_add` datetime NOT NULL,
  UNIQUE KEY `media_id` (`media_id`,`album_id`),
  KEY `status_permissions_album_id` (`status`,`permissions`,`album_id`),
  KEY `album_id` (`album_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `[prefix]album_types` (`id`, `gid`, `gid_upload_type`, `file_count`, `gid_upload_video`, `video_count`) VALUES (NULL, 'media_type', 'gallery_image', '0', 'gallery_video', '0');