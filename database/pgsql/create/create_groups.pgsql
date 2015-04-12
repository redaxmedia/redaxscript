CREATE TABLE IF NOT EXISTS /* {configPrefix} */groups (
	id SERIAL PRIMARY KEY,
	name varchar(255) DEFAULT NULL,
	alias varchar(255) DEFAULT NULL,
	description varchar(255) DEFAULT NULL,
	categories varchar(255) DEFAULT NULL,
	articles varchar(255) DEFAULT NULL,
	extras varchar(255) DEFAULT NULL,
	comments varchar(255) DEFAULT NULL,
	groups varchar(255) DEFAULT NULL,
	users varchar(255) DEFAULT NULL,
	modules varchar(255) DEFAULT NULL,
	settings integer DEFAULT NULL,
	filter integer DEFAULT 1,
	status integer DEFAULT 1
);
