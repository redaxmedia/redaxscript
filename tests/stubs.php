<?php

/* anchor element */

function anchor_element($type = '', $id = '', $class = '', $name = '', $value = '', $title = '', $code = '')
{
	return '<a>' . $name . '</a>';
}

/* check alias */

function check_alias($input = '', $mode = '')
{
	if ($input == 'login')
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

/* retrieve */

function retrieve($column = '', $table = '', $field = '', $value = '')
{
	return $value;
}

/* s */

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

/* l */

function l($name = '')
{
	return $name;
}
?>