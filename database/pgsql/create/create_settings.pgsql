CREATE TABLE IF NOT EXISTS /* {configPrefix} */settings (
	id SERIAL PRIMARY KEY,
	name varchar(255) DEFAULT NULL,
	value varchar(255) DEFAULT NULL
);
