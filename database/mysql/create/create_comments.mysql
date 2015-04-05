CREATE TABLE IF NOT EXISTS /* {configPrefix} */comments (
	id int(10) NOT NULL AUTO_INCREMENT,
	author varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	email varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	url varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	text longtext COLLATE utf8_unicode_ci,
	language char(3) COLLATE utf8_unicode_ci DEFAULT NULL,
	date timestamp DEFAULT CURRENT_TIMESTAMP,
	article int(10) DEFAULT 0,
	status int(1) DEFAULT 1,
	rank int(10) DEFAULT NULL,
	access varchar(255) COLLATE utf8_unicode_ci DEFAULT 0,
	PRIMARY KEY(id)
)
ENGINE = MyISAM
DEFAULT CHARSET = utf8
COLLATE = utf8_unicode_ci
AUTO_INCREMENT = 1;
