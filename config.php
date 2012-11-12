<?php

/**
 * config database
 * 
 * @param string $name
 * @return string
 */

function d($name = '')
{
	$d['host'] = '';
	$d['name'] = '';
	$d['user'] = '';
	$d['password'] = '';
	$d['prefix'] = '';
	$d['salt'] = '';
	$output = $d[$name];
	return $output;
}
?>