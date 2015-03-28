CREATE TABLE IF NOT EXISTS comments (
	id int(10) NOT NULL AUTO_INCREMENT,
	author varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	email varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	url varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	text longtext COLLATE utf8_unicode_ci,
	language char(3) COLLATE utf8_unicode_ci DEFAULT NULL,
	date datetime DEFAULT NULL,
	article int(10) DEFAULT NULL,
	status int(1) DEFAULT NULL,
	rank int(10) DEFAULT NULL,
	access varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	PRIMARY KEY(id)
)
ENGINE = MyISAM
DEFAULT CHARSET = utf8
COLLATE = utf8_unicode_ci
AUTO_INCREMENT = 1;
