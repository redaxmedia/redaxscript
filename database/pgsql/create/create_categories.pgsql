CREATE TABLE IF NOT EXISTS /* {configPrefix} */categories (
	id SERIAL PRIMARY KEY,
	title varchar(255) DEFAULT NULL,
	alias varchar(255) DEFAULT NULL,
	author varchar(255) DEFAULT NULL,
	description varchar(255) DEFAULT NULL,
	keywords varchar(255) DEFAULT NULL,
	language char(3) DEFAULT NULL,
	template varchar(255) DEFAULT NULL,
	date timestamp DEFAULT CURRENT_TIMESTAMP,
	sibling integer DEFAULT 0,
	parent integer DEFAULT 0,
	status integer DEFAULT 1,
	rank integer DEFAULT NULL,
	access varchar(255) DEFAULT 0
);
