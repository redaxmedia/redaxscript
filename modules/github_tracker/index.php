<?php

/**
 * github tracker loader start
 *
 * @since 2.1.0
 * @deprecated 2.1.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function github_tracker_loader_start()
{
	global $loader_modules_styles;
	$loader_modules_styles[] = 'modules/github_tracker/styles/github_tracker.css';
}

/**
 * github tracker render start
 *
 * @since 2.1.0
 * @deprecated 2.1.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function github_tracker_render_start()
{
	if (FIRST_PARAMETER == 'github-tracker' && SECOND_PARAMETER == 'get-contents' && (THIRD_PARAMETER == 'issues' || THIRD_PARAMETER == 'milestones'))
	{
		define('RENDER_BREAK', 1);
		github_tracker_get_contents(THIRD_PARAMETER);
	}
}

/**
 * github tracker
 *
 * @since 2.1.0
 * @deprecated 2.1.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 *
 * @param string $type
 * @param array $options
 */

function github_tracker($type = '', $options = '')
{
}

/**
 * github tracker contents
 *
 * @since 2.1.0
 * @deprecated 2.1.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 *
 * @param string $type
 * @return string
 */

function github_tracker_get_contents($type = '')
{
	/* define variables */

	$file_path = GITHUB_TRACKER_CACHE_PATH . '/' . $type . '.json';
	$file_size = filesize($file_path);
	$file_time = filectime($file_path);
	$file_age = time() - $file_time;
	$cache_expires = constant('GITHUB_TRACKER_CACHE_' . strtoupper($type) . '_EXPIRES');

	/* load contents from cache */

	if ($file_size && $file_age < $cache_expires)
	{
		$output = file_get_contents($file_path);
	}

	/* else request contents */

	else
	{
		/* get contents */

		$url = GITHUB_TRACKER_API_URL . '/' . GITHUB_TRACKER_REPO_URL . '/' . $type;
		$output = file_get_contents($url);

		/* write cache file if writable */

		if (is_writable($file_path))
		{
			$file = fopen($file_path, 'w+');
			fwrite($file, $output);
			fclose($file);
		}
	}
	return $output;
}
?>