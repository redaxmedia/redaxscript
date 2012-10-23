<?php

/* config database */

function d($name = '')
{
	$d['host'] = 'localhost';
	$d['name'] = 'test';
	$d['user'] = 'root';
	$d['password'] = '';
	$d['prefix'] = '';
	$d['salt'] = '887e5761ea71e8e1c4ffb45a2a1458872e120596';
	$output = $d[$name];
	return $output;
}
?>