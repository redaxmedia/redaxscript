CREATE TABLE IF NOT EXISTS /* %PREFIX% */users (
	id SERIAL PRIMARY KEY,
	name varchar(255) DEFAULT NULL,
	"user" varchar(255) DEFAULT NULL,
	password varchar(255) DEFAULT NULL,
	email varchar(255) DEFAULT NULL,
	description varchar(255) DEFAULT NULL,
	language char(2) DEFAULT NULL,
	status integer DEFAULT 1,
	groups varchar(255) DEFAULT NULL,
	first timestamp with time zone DEFAULT NULL,
	last timestamp with time zone DEFAULT NULL
);
