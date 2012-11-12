<?php

/**
 * read directory
 * 
 * @param string $input
 * @param string|array $ignore
 */

function read_directory($input = '', $ignore = '')
{
	$handle = opendir($input);
	while ($value = readdir($handle))
	{
		$output[] = $value;
	}

	/* collect output */

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

/**
 * remove directory
 * 
 * @param string $input
 * @param integer $mode
 */

function remove_directory($input = '', $mode = '')
{
	$input_directory = read_directory($input);

	/* delete file and directory */

	if (is_array($input_directory))
	{
		foreach ($input_directory as $value)
		{
			$route = $input . '/' . $value;
			if (is_dir($route))
			{
				remove_directory($route, 1);
			}
			else
			{
				unlink($route);
			}
		}
	}

	/* delete directory itself */

	if ($mode == 1)
	{
		rmdir($input);
	}
}
?>