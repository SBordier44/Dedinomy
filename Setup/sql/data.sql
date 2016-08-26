CREATE TABLE IF NOT EXISTS `dn_banip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` text NOT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `dn_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nickname` text,
  `message` text,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `online` tinyint(1) NOT NULL DEFAULT '0',
  `ip` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
INSERT INTO `dn_messages` (`id`, `nickname`, `message`, `created`, `online`, `ip`) VALUES
  (15, 'pseudo', 'super site de fifou', '2015-12-11 15:49:08', 1, '88.165.30.155');
CREATE TABLE IF NOT EXISTS `dn_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sitename` text,
  `admin_perpage` int(11) NOT NULL DEFAULT '10',
  `form_caracters` int(11) NOT NULL DEFAULT '24',
  `recaptcha_status` tinyint(1) NOT NULL DEFAULT '1',
  `recaptcha_sitekey` text,
  `recaptcha_secret` text,
  `maintenance_msg` text,
  `maintenance_status` tinyint(1) NOT NULL DEFAULT '0',
  `moderated_msg` text,
  `published_msg` text,
  `dedi_autopublish` tinyint(1) NOT NULL DEFAULT '0',
  `dedi_displaydate` tinyint(1) NOT NULL DEFAULT '1',
  `dedi_displaylimit` int(11) NOT NULL DEFAULT '10',
  `api_key` text,
  `secret_key` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
INSERT INTO `dn_settings` (`id`, `sitename`, `admin_perpage`, `form_caracters`, `recaptcha_status`, `recaptcha_sitekey`, `recaptcha_secret`, `maintenance_msg`, `maintenance_status`, `moderated_msg`, `published_msg`, `dedi_autopublish`, `dedi_displaydate`, `dedi_displaylimit`, `api_key`, `secret_key`) VALUES
  (1, 'monsite', 10, 24, 0, '', '', 'maintenance en cours', 0, 'en validation', 'maintenant en ligne', 0, 0, 50, '', '');
CREATE TABLE IF NOT EXISTS `dn_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` text,
  `password` text,
  `grade` varchar(10) NOT NULL DEFAULT 'modo',
  `email` text,
  `notify_email` tinyint(1) NOT NULL DEFAULT '1',
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `last_access` datetime DEFAULT NULL,
  `remember_token` text,
  `reset_token` text,
  `reset_at` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;