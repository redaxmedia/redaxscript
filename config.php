<?php

/* config database */

function d($name = '')
{
	$d['host'] = '';
	$d['name'] = '';
	$d['user'] = '';
	$d['password'] = '';
	$d['prefix'] = '';
	$output = $d[$name];
	return $output;
}
?>