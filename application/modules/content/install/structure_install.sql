DROP TABLE IF EXISTS `[prefix]content`;
CREATE TABLE IF NOT EXISTS `[prefix]content` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `lang_id` int(3) NOT NULL,
  `parent_id` int(3) NOT NULL,
  `gid` varchar(50) NOT NULL,
  `img` varchar(255) NOT NULL,
  `sorter` int(3) NOT NULL,
  `status` tinyint(3) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `id_seo_settings` int(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `lang_id` (`lang_id`),
  KEY `parent_id` (`parent_id`),
  KEY `gid` (`gid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]content_promo`;
CREATE TABLE `[prefix]content_promo` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `id_lang` int(3) NOT NULL,
  `content_type` char(1) NOT NULL,
  `promo_text` text NOT NULL,
  `promo_image` varchar(255) NOT NULL,
  `promo_flash` varchar(255) NOT NULL,
  `block_width` int(3) NOT NULL,
  `block_width_unit` varchar(4) NOT NULL,
  `block_height` int(3) NOT NULL,
  `block_height_unit` varchar(4) NOT NULL,
  `block_align_hor` varchar(10) NOT NULL,
  `block_align_ver` varchar(10) NOT NULL,
  `block_image_repeat` varchar(20) NOT NULL,
  `promo_video` varchar(255) NOT NULL,
  `promo_video_image` varchar(255) NOT NULL,
  `promo_video_data` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_lang` (`id_lang`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
