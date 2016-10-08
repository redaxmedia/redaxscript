CREATE TABLE IF NOT EXISTS /* %PREFIX% */extras (
	id SERIAL PRIMARY KEY,
	title varchar(255) DEFAULT NULL,
	alias varchar(255) DEFAULT NULL,
	author varchar(255) DEFAULT NULL,
	text text DEFAULT NULL,
	language char(3) DEFAULT NULL,
	sibling integer DEFAULT NULL,
	category integer DEFAULT NULL,
	article integer DEFAULT NULL,
	headline integer DEFAULT 1,
	status integer DEFAULT 1,
	rank integer DEFAULT NULL,
	access varchar(255) DEFAULT NULL,
	date timestamp DEFAULT CURRENT_TIMESTAMP
);
