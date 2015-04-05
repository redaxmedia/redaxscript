CREATE TABLE IF NOT EXISTS /* {configPrefix} */groups (
	id SERIAL PRIMARY KEY,
	name varchar(255) DEFAULT NULL,
	alias varchar(255) DEFAULT NULL,
	description varchar(255) DEFAULT NULL,
	categories varchar(255) DEFAULT 0,
	articles varchar(255) DEFAULT 0,
	extras varchar(255) DEFAULT 0,
	comments varchar(255) DEFAULT 0,
	groups varchar(255) DEFAULT 0,
	users varchar(255) DEFAULT 0,
	modules varchar(255) DEFAULT 0,
	settings integer DEFAULT 0,
	filter integer DEFAULT 1,
	status integer DEFAULT 1
);
