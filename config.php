<?php
$dbUrl = parse_url(getenv('DATABASE_URL'));
return array(
	'dbType' => 'pgsql',
	'dbHost' => $dbUrl['host'],
	'dbName' => trim($dbUrl['path'], '/'),
	'dbUser' => $dbUrl['user'],
	'dbPassword' => $dbUrl['pass'],
	'dbPrefix' => '',
	'dbSalt' => 'b66e42b397a215cec2ecb3d7a5bdae1b6738abc9'
);
