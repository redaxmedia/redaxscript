<?php

/* config database */

function d($name = '')
{
	$d['host'] = 'localhost';
	$d['name'] = 'test';
	$d['user'] = 'root';
	$d['password'] = '';
	$d['prefix'] = 'test2_';
	$d['salt'] = 'f7fe11a8c8c6fc4458d84babd3720c7314a91c25';
	$output = $d[$name];
	return $output;
}
?>