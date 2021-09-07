CREATE TABLE `recup_mdp` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `code` char(32) COLLATE utf8_unicode_ci NOT NULL,
  `mail` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;