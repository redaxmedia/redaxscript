CREATE TABLE IF NOT EXISTS /* %PREFIX% */groups (
	id SERIAL PRIMARY KEY,
	name varchar(255),
	alias varchar(255),
	description varchar(255),
	categories varchar(255),
	articles varchar(255),
	extras varchar(255),
	comments varchar(255),
	groups varchar(255),
	users varchar(255),
	modules varchar(255),
	settings int,
	filter int DEFAULT 1,
	status int DEFAULT 1
);
