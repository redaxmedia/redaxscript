CREATE TABLE IF NOT EXISTS /* {configPrefix} */comments (
	id SERIAL PRIMARY KEY,
	author varchar(255) DEFAULT NULL,
	email varchar(255) DEFAULT NULL,
	url varchar(255) DEFAULT NULL,
	text text,
	language char(3) DEFAULT NULL,
	date timestamp DEFAULT CURRENT_TIMESTAMP,
	article integer DEFAULT 0,
	status integer DEFAULT 1,
	rank integer DEFAULT NULL,
	access varchar(255) DEFAULT 0
);
