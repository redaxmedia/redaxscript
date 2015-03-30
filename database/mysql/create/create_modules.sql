CREATE TABLE IF NOT EXISTS /* {configPrefix} */modules (
	id int(10) NOT NULL AUTO_INCREMENT,
	name varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	alias varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	author varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	description varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	version varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	status int(1) DEFAULT NULL,
	access varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	PRIMARY KEY(id)
)
ENGINE = MyISAM
DEFAULT CHARSET = utf8
COLLATE = utf8_unicode_ci
AUTO_INCREMENT = 2;
