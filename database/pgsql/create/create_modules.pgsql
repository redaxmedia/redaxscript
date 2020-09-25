CREATE TABLE IF NOT EXISTS /* %PREFIX% */modules (
	id SERIAL PRIMARY KEY,
	name varchar(255),
	alias varchar(255),
	author varchar(255),
	description varchar(255),
	version varchar(255),
	license varchar(255),
	status int DEFAULT 1,
	access varchar(255)
);
