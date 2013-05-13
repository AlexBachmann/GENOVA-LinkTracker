CREATE TABLE IF NOT EXISTS `#__lt_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(512) NOT NULL,
  `scheme` varchar(10) NOT NULL,
  `domain` varchar(512) NOT NULL,
  `path` varchar(512) DEFAULT NULL,
  `query` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`(200)),
  KEY `domain` (`domain`(255)),
  KEY `scheme` (`scheme`,`domain`(255))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__lt_clicks` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `url_id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `time` datetime NOT NULL,
  `ref` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `url` (`url_id`),
  KEY `user` (`user`),
  KEY `ref` (`ref`),
  KEY `time` (`time`),
  KEY `url_time` (`url_id`,`time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


