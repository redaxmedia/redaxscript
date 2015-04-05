CREATE TABLE IF NOT EXISTS /* {configPrefix} */modules (
	id SERIAL PRIMARY KEY,
	name varchar(255) DEFAULT NULL,
	alias varchar(255) DEFAULT NULL,
	author varchar(255) DEFAULT NULL,
	description varchar(255) DEFAULT NULL,
	version varchar(255) DEFAULT NULL,
	status integer DEFAULT 1,
	access varchar(255) DEFAULT 0
);
