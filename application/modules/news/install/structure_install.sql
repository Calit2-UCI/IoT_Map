DROP TABLE IF EXISTS `[prefix]news`;
CREATE TABLE IF NOT EXISTS `[prefix]news` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `gid` varchar(100) NOT NULL,
  `img` varchar(255) NOT NULL,
  `status` tinyint(3) NOT NULL,
  `id_lang` tinyint(3) NOT NULL,
  `news_type` enum('news','feed') NOT NULL,
  `date_add` datetime NOT NULL,
  `feed_link` varchar(255) NOT NULL,
  `feed_id` int(3) NOT NULL,
  `feed_unique_id` varchar(50) NOT NULL,
  `video` varchar(255) NOT NULL,
  `video_image` varchar(255) NOT NULL,
  `video_data` text NOT NULL,
  `set_to_subscribe` tinyint(3) NOT NULL DEFAULT '0',
  `comments_count` int(11) NOT NULL DEFAULT '0',
  `id_seo_settings` int(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gid` (`gid`),
  KEY `id_lang` (`id_lang`),
  KEY `feed_unique_id` (`feed_unique_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]news_feeds`;
CREATE TABLE IF NOT EXISTS `[prefix]news_feeds` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `link` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `site_link` varchar(255) NOT NULL,
  `encoding` varchar(10) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `id_lang` int(11) NOT NULL,
  `max_news` tinyint(4) NOT NULL,
  `date_add` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`status`,`id_lang`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `[prefix]news_feeds` (`id`, `link`, `title`, `description`, `site_link`, `encoding`, `status`, `id_lang`, `max_news`, `date_add`) VALUES
(3,	'http://www.yourtango.com/rss/dating',	'Dating | YourTango',	'',	'http://www.yourtango.com',	'UTF-8',	1,	1,	5,	'2013-10-25 11:43:28'),
(4,	'http://www.ayi.com/dating-blog/feed/',	'AYI.com Blog',	'Meet Singles Through Your Interests and Friends',	'http://www.ayi.com/dating-blog',	'UTF-8',	1,	1,	5,	'2013-10-25 12:35:32');
