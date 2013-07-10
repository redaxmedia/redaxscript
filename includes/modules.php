<?php

/**
 * modules include
 *
 * @since 1.2.1
 * @deprecated 2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 *
 * @return array
 */

function modules_include()
{
	static $modules_installed_array, $modules_directory_array;

	/* query installed modules */

	if ($modules_installed_array == '')
	{
		$query = 'SELECT alias, access FROM ' . PREFIX . 'modules WHERE status = 1';
		$result = mysql_query($query);
		if ($result)
		{
			while ($r = mysql_fetch_assoc($result))
			{
				$alias = $r['alias'];
				$access = $r['access'];
				$check_access = check_access($access, MY_GROUPS);

				/* if access granted */

				if ($check_access == 1)
				{
					$modules_installed_array[] = $alias;
				}
			}
		}
	}

	/* modules directory object */

	if ($modules_directory_array == '')
	{
		$modules_directory = New Redaxscript_Directory('modules');
		$modules_directory_array = $modules_directory->getOutput();
	}

	/* intersect modules diretory and modules installed */

	if ($modules_directory_array && $modules_installed_array)
	{
		$output = array_intersect($modules_directory_array, $modules_installed_array);
	}
	return $output;
}

/**
 * hook
 *
 * @since 1.2.1
 * @deprecated 2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 *
 * @param string $input
 * @return string
 */

function hook($input = '')
{
	global $hooks;
	static $modules_include;

	/* get modules include */

	if ($modules_include == '')
	{
		$modules_include = modules_include();
	}

	/* call hook functions */

	foreach ($modules_include as $value)
	{
		$function = $value . '_' . $input;
		if (function_exists($function))
		{
			$hooks[] = $function;
			$output .= call_user_func($function);
		}
	}
	return $output;
}
?>