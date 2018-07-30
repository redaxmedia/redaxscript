CREATE TABLE IF NOT EXISTS /* %PREFIX% */users (
	id SERIAL PRIMARY KEY,
	name varchar(255),
	"user" varchar(255),
	description varchar(255),
	password varchar(255),
	email varchar(255),
	language char(2),
	status int DEFAULT 1,
	groups varchar(255),
	first int,
	last int
);
