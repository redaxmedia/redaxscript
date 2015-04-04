CREATE TABLE IF NOT EXISTS /* {configPrefix} */groups (
	id int(10) NOT NULL AUTO_INCREMENT,
	name varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	alias varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	description varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	categories varchar(255) COLLATE utf8_unicode_ci DEFAULT 0,
	articles varchar(255) COLLATE utf8_unicode_ci DEFAULT 0,
	extras varchar(255) COLLATE utf8_unicode_ci DEFAULT 0,
	comments varchar(255) COLLATE utf8_unicode_ci DEFAULT 0,
	groups varchar(255) COLLATE utf8_unicode_ci DEFAULT 0,
	users varchar(255) COLLATE utf8_unicode_ci DEFAULT 0,
	modules varchar(255) COLLATE utf8_unicode_ci DEFAULT 0,
	settings int(1) DEFAULT 0,
	filter int(1) DEFAULT 1,
	status int(1) DEFAULT 1,
	PRIMARY KEY(id)
)
ENGINE = MyISAM
DEFAULT CHARSET = utf8
COLLATE = utf8_unicode_ci
AUTO_INCREMENT = 1;
