<?php

/**
 * stubs
 *
 * Stubbed functions required for unit testing. Functions defined in alphabetical order for ease of searching
 *
 * @since 2.1.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Gary Aylward
 */

function anchor_element($type = '', $id = '', $class = '', $name = '', $value = '', $title = '', $code = '')
{
	$output = '<a>' . $name . '</a>';
	return $output;
}

function check_alias($input = '', $mode = '')
{
	if($input == 'login')
	{
		return 1;
	}
	else
	{
		return 0;
	}
}

function hook($input = '')
{
	return NULL;
}

function l($name = '')
{
	return $name;
}
	
function retrieve($column = '', $table = '', $field = '', $value = '')
{
	return $value;
}

function s($name = '')
{
	return $name;
}

?>