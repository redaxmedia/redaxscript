CREATE TABLE IF NOT EXISTS /* %PREFIX% */categories (
	id SERIAL PRIMARY KEY,
	title varchar(255) DEFAULT NULL,
	alias varchar(255) DEFAULT NULL,
	author varchar(255) DEFAULT NULL,
	description varchar(255) DEFAULT NULL,
	keywords varchar(255) DEFAULT NULL,
	robots integer DEFAULT NULL,
	language char(2) DEFAULT NULL,
	template varchar(255) DEFAULT NULL,
	sibling integer DEFAULT NULL,
	parent integer DEFAULT NULL,
	status integer DEFAULT 1,
	rank integer DEFAULT NULL,
	access varchar(255) DEFAULT NULL,
	date timestamp DEFAULT CURRENT_TIMESTAMP
);
