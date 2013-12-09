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
	if (FIRST_PARAMETER == 'github-tracker' && SECOND_PARAMETER == 'get-contents' && (THIRD_PARAMETER == 'milestones' || THIRD_PARAMETER == 'issues'))
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
	/* define option variables */

	if (is_array($options))
	{
		foreach ($options as $key => $value)
		{
			$key = 'option_' . $key;
			$$key = $value;
		}
	}

	/* fallback */

	if ($option_limit_milestones == '')
	{
		$option_limit_milestones = s('limit');
	}
	if ($option_limit_issues == '')
	{
		$option_limit_issues = s('limit');
	}

	/* get contents */

	$contents = github_tracker_get_contents($type);

	/* decode contents */

	if ($contents)
	{
		$contents = json_decode($contents);
	}
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
		/* curl contents */

		$url = GITHUB_TRACKER_API_URL . '/' . GITHUB_TRACKER_USER . '/' . GITHUB_TRACKER_REPO . '/' . $type;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Redaxscript');
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		$output = curl_exec($ch);
		curl_close($ch);

		/* write cache file */

		if (is_writable($file_path))
		{
			file_put_contents($file_path, $output);
		}
	}
	return $output;
}
?>