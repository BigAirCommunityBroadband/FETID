/*required for latest phplib*/
CREATE TABLE IF NOT EXISTS `badauth_counts` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `address` varchar(40) NOT NULL,
  `ctime` datetime NOT NULL,
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `count` int(11) NOT NULL DEFAULT '0',
  `no_cookie` int(11) NOT NULL DEFAULT '0',
  `user_agent` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `address` (`address`,`username`),
  KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

