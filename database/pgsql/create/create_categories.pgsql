CREATE TABLE IF NOT EXISTS /* %PREFIX% */categories (
	id SERIAL PRIMARY KEY,
	title varchar(255),
	alias varchar(255),
	author varchar(255),
	description varchar(255),
	keywords varchar(255),
	robots int,
	language char(2),
	template varchar(255),
	sibling int,
	parent int,
	status int DEFAULT 1,
	rank int,
	access varchar(255),
	date int
);
