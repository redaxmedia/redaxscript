<?php
$dbUrl = parse_url(getenv('DATABASE_URL'));
return array(
	'dbType' => 'pgsql',
	'dbHost' => $dbUrl['host'],
	'dbName' => trim($dbUrl['path'], '/'),
	'dbUser' => $dbUrl['user'],
	'dbPassword' => $dbUrl['pass'],
	'dbPrefix' => 'rs_',
	'dbSalt' => ''
);
