<?php

/* anchor element */

function anchor_element($type = '', $id = '', $class = '', $name = '', $value = '', $title = '', $code = '')
{
	return '<a>' . $name . '</a>';
}

/* check alias */

function check_alias($input = '', $mode = '')
{
	$aliasDefault = array(
		'admin',
		'loader',
		'login',
		'logout',
		'password_reset',
		'scripts',
		'styles',
		'registration',
		'reminder'
	);

	/* check if default */

	if (in_array($input, $aliasDefault))
	{
		return 1;
	}
	else
	{
		return 0;
	}
}

/* hook */

function hook($input = '')
{
	return null;
}

/* shortcut */

function s($name = '')
{
	static $index = 0;

	/* switch */

	if ($name === 'captcha')
	{
		switch ($index)
		{
			case 0:
				$index++;
				$output = 2;
				break;
			case 1:
				$index++;
				$output = 3;
				break;
			default:
				$output = 0;
				break;
		}
	}
	else if ($name === 'charset')
	{
		$output = 'utf-8';
	}
	else
	{
		$output = $name;
	}
	return $output;
}