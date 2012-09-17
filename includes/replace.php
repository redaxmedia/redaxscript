<?php

/* break up */

function break_up($input = '')
{
	$search = array(
		chr(13) . chr(10),
		chr(13),
		chr(10)
	);
	$replace = '<br />';
	$output = str_replace($search, $replace, $input);
	return $output;
}

/* truncate */

function truncate($input = '', $length = '', $end = '')
{
	$length -= mb_strlen($end);
	if (mb_strlen($input) > $length)
	{
		$output = mb_substr($input, 0, $length) . $end;
	}
	else
	{
		$output = $input;
	}
	return $output;
}

/* minify */

function minify($type = '', $input = '')
{
	/* replace comments */

	$output = preg_replace('/\/\*\.*.*?\.*\*\//', '', $input);

	/* replace newlines */

	if ($type == 'styles')
	{
		$output = preg_replace('/\s+/', ' ', $output);
	}
	else if ($type == 'scripts')
	{
		$output = preg_replace('/\t+/', '', $output);
		$output = preg_replace('/\r+/', PHP_EOL, $output);
		$output = preg_replace('/\n+/', PHP_EOL, $output);
	}

	/* general minify */

	$output = str_replace(array(
		' {',
		'{ '
	), '{', $output);
	$output = str_replace(array(
		' }',
		'} ',
		';}'
	), '}', $output);
	$output = str_replace(array(
		' :',
		': '
	), ':', $output);
	$output = str_replace(array(
		' ;',
		'; '
	), ';', $output);
	$output = str_replace(array(
		' ,',
		', '
	), ',', $output);

	/* additional minify for scripts */

	if ($type == 'scripts')
	{
		$output = str_replace(array(
			' (',
			'( '
		), '(', $output);
		$output = str_replace(array(
			' +',
			'+ '
		), '+', $output);
		$output = str_replace(array(
			' -',
			'- '
		), '-', $output);
		$output = str_replace(array(
			' =',
			'= '
		), '=', $output);
		$output = str_replace(array(
			' ||',
			'|| '
		), '||', $output);
		$output = str_replace(array(
			' &&',
			'&& '
		), '&&', $output);
	}
	$output = trim($output);
	return $output;
}

/* entity */

function entity($input = '')
{
	/* if mb convert econding */

	if (function_exists('mb_convert_encoding'))
	{
		$output = mb_convert_encoding($input, s('charset'), 'utf-8, latin1');
	}

	/* else fallback */

	else
	{
		$output = $input;
	}
	return $output;
}
?>