DROP TABLE IF EXISTS `[prefix]uploads`;
CREATE TABLE IF NOT EXISTS `[prefix]uploads` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `gid` varchar(25) NOT NULL,
  `name` varchar(100) NOT NULL,
  `min_height` int(3) NOT NULL,
  `min_width` int(3) NOT NULL,
  `max_height` int(3) NOT NULL,
  `max_width` int(3) NOT NULL,
  `max_size` int(3) NOT NULL,
  `name_format` enum('generate','format') NOT NULL DEFAULT 'generate',
  `file_formats` text NOT NULL,
  `default_img` varchar(100) NOT NULL,
  `date_add` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gid` (`gid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]uploads_thumb`;
CREATE TABLE IF NOT EXISTS `[prefix]uploads_thumb` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `config_id` int(3) NOT NULL,
  `prefix` varchar(20) NOT NULL,
  `width` int(3) NOT NULL,
  `height` int(3) NOT NULL,
  `effect` enum('grayscale','none') NOT NULL DEFAULT 'none',
  `watermark_id` int(3) NOT NULL,
  `crop_param` enum('resize','color','crop','extend','rotate','animate','static_height') DEFAULT NULL,
  `crop_color` varchar(6) NOT NULL,
  `animation` tinyint(1) NOT NULL,
  `delay` int(3) NOT NULL,
  `loops` int(3) NOT NULL,
  `disposal` int(3) NOT NULL,
  `transparent_color` varchar(6) NOT NULL,
  `rotation_angle` smallint(5) NOT NULL,
  `date_add` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `settings_id` (`config_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]uploads_watermark`;
CREATE TABLE IF NOT EXISTS `[prefix]uploads_watermark` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `gid` varchar(25) NOT NULL,
  `name` varchar(100) NOT NULL,
  `wm_type` enum('img','text') NOT NULL DEFAULT 'img',
  `img` varchar(100) NOT NULL,
  `font_text` varchar(100) NOT NULL,
  `font_color` varchar(6) NOT NULL,
  `shadow_color` varchar(6) NOT NULL,
  `shadow_distance` tinyint(3) NOT NULL,
  `font_face` varchar(20) NOT NULL,
  `font_size` int(11) NOT NULL,
  `position_hor` varchar(20) NOT NULL,
  `position_ver` varchar(20) NOT NULL,
  `alpha` int(3) NOT NULL,
  `date_add` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gid` (`gid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
