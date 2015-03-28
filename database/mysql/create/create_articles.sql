CREATE TABLE IF NOT EXISTS articles (
	id int(10) NOT NULL AUTO_INCREMENT,
	title varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	alias varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	author varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	description varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	keywords varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	text longtext COLLATE utf8_unicode_ci,
	language char(3) COLLATE utf8_unicode_ci DEFAULT NULL,
	template varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	date datetime DEFAULT NULL,
	category int(10) DEFAULT NULL,
	headline int(1) DEFAULT NULL,
	infoline int(1) DEFAULT NULL,
	comments int(1) DEFAULT NULL,
	status int(1) DEFAULT NULL,
	rank int(10) DEFAULT NULL,
	access varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	PRIMARY KEY(id)
)
ENGINE = MyISAM
DEFAULT CHARSET = utf8
COLLATE = utf8_unicode_ci
AUTO_INCREMENT = 2;
