<?php

/**
 * stubs
 *
 * stubbed functions required for unit testing
 * functions defined in alphabetical order for ease of searching
 * this file must be 'include_once' into each unit test file
 *
 * @since 2.1.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Gary Aylward
 */

/**
 * anchor_element
 *
 * @param string $type
 * @param string $id
 * @param string $class
 * @param string $name
 * @param string $value
 * @param string $title
 * @param string $code
 * @return string
 */
function anchor_element($type = '', $id = '', $class = '', $name = '', $value = '', $title = '', $code = '')
{
	$output = '<a>' . $name . '</a>';
	return $output;
}

/**
 * check_alias
 *
 * @param string $input
 * @param integer $mode
 * @return integer
 */

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

/**
 * hook
 *
 * @param string $input
 * @return null
 */

function hook($input = '')
{
	return null;
}

/**
 * l
 * @param string $name
 * @return string
 */

function l($name = '')
{
	return $name;
}

/**
 * retrieve
 *
 * @param string $column
 * @param string $table
 * @param string $field
 * @param string $value
 * @return string
 */

function retrieve($column = '', $table = '', $field = '', $value = '')
{
	return $value;
}

/**
 * s
 *
 * @param string $name
 * @return string
 */

function s($name = '')
{
	static $index = 0;

	if ($name === 'captcha')
	{
		/* captcha returns values to give plus, minus and random operator */
		switch ($index)
		{
			case 0:
				$index++;
				$name = 2;
				break;
			case 1:
				$index++;
				$name = 3;
				break;
			default:
				$name = 0;
				break;
		}
	}
	return $name;
}
?>