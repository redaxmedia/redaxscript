CREATE TABLE IF NOT EXISTS /* %PREFIX% */comments (
	id SERIAL PRIMARY KEY,
	author varchar(255),
	email varchar(255),
	url varchar(255),
	text text,
	language char(2),
	article int,
	status int DEFAULT 1,
	rank int,
	access varchar(255),
	date int
);
