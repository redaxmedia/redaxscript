CREATE TABLE IF NOT EXISTS /* %PREFIX% */comments (
	id SERIAL PRIMARY KEY,
	author varchar(255) DEFAULT NULL,
	email varchar(255) DEFAULT NULL,
	url varchar(255) DEFAULT NULL,
	text text DEFAULT NULL,
	language char(3) DEFAULT NULL,
	article integer DEFAULT NULL,
	status integer DEFAULT 1,
	rank integer DEFAULT NULL,
	access varchar(255) DEFAULT NULL,
	date timestamp DEFAULT CURRENT_TIMESTAMP
);
