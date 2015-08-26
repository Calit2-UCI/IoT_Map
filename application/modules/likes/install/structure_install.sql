DROP TABLE IF EXISTS `[prefix]likes`;
CREATE TABLE IF NOT EXISTS `[prefix]likes` (
  `id_like` varchar(20) NOT NULL,
  `id_user` int(10) NOT NULL,
  UNIQUE INDEX `like` (`id_user`, `id_like`),
  INDEX `id_like` (`id_like`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]likes_count`;
CREATE TABLE IF NOT EXISTS `[prefix]likes_count` (
  `id_like` varchar(20) NOT NULL,
  `count` int(10) NOT NULL DEFAULT '0',
  UNIQUE INDEX `id_like` (`id_like`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;