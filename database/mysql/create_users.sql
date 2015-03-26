CREATE TABLE IF NOT EXISTS users (
	id int(10) NOT NULL AUTO_INCREMENT,
	name varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	user varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	password varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	email varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	description varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	language char(3) COLLATE utf8_unicode_ci DEFAULT NULL,
	first datetime DEFAULT NULL,
	last datetime DEFAULT NULL,
	status int(1) DEFAULT NULL,
	groups varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	PRIMARY KEY(id)
)
ENGINE = MyISAM
DEFAULT CHARSET = utf8
COLLATE = utf8_unicode_ci
AUTO_INCREMENT = 2;
