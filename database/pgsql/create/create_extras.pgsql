CREATE TABLE IF NOT EXISTS /* %PREFIX% */extras (
	id SERIAL PRIMARY KEY,
	title varchar(255),
	alias varchar(255),
	author varchar(255),
	text text,
	language char(2),
	sibling int,
	category int,
	article int,
	headline int DEFAULT 1,
	status int DEFAULT 1,
	rank int,
	access varchar(255),
	date int
);
