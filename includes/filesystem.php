<?php

/* read directory */

function read_directory($input = '', $ignore = '')
{
	$handle = opendir($input);
	while ($value = readdir($handle))
	{
		$output[] = $value;
	}
	if ($output)
	{
		if (is_array($ignore) == '')
		{
			$ignore = array(
				$ignore
			);
		}
		$ignore[] = '.';
		$ignore[] = '..';
		$output = array_diff($output, $ignore);
		sort($output);
	}
	return $output;
}

/* remove directory */

function remove_directory($input = '', $mode = '')
{
	$input_directory = read_directory($input);
	if (is_array($input_directory))
	{
		foreach ($input_directory as $value)
		{
			$string = $input . '/' . $value;
			if (is_dir($string))
			{
				remove_directory($string, 1);
			}
			else
			{
				unlink($string);
			}
		}
	}
	if ($mode == 1)
	{
		rmdir($input);
	}
}
?>