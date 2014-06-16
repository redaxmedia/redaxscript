<?php

/**
 * github tracker loader start
 *
 * @since 2.1.0
 * @deprecated 2.0.0
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
 * @deprecated 2.0.0
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
 * @deprecated 2.0.0
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
		$data = $contents;
	}

	/* collect milestones output */

	if ($data && $type == 'milestones')
	{
		foreach ($data as $value)
		{
			/* break if limit reached */

			if (++$milestones_counter > $option_limit_milestones)
			{
				break;
			}
			$total_issues = $value->closed_issues + $value->open_issues;

			/* collect milestones output */

			$output .= '<ul class="list_github_tracker_milestones">';
			$output .= '<li><h3 class="title_github_tracker_milestones">' . $value->title . '</h3></li>';
			$output .= '<li><span class="text_github_tracker_milestones_description">' . $value->description . '</span></li>';
			$output .= '<li><progress class="progress_github_tracker_milestones" value="' . $value->closed_issues . '" max="' . $total_issues . '"></progress></li>';
			$output .= '<li><span class="text_github_tracker_milestones_status">' . $value->closed_issues . ' ' . l('closed_issues', 'github_tracker') . s('divider') . $value->open_issues . ' ' . l('open_issues', 'github_tracker') . '</span></li>';
			$output .= '</ul>';
		}
	}

	/* collect issues output */

	if ($data && $type == 'issues')
	{
		$output = '<div class="wrapper_table_default"><table class="table table_default table_github_tracker_milestones">';
		$output .= '<thead><tr><th class="s3o6 column_first">' . l('issues', 'github_tracker') . '</th><th class="column_second">' . l('created', 'github_tracker') . '</th><th class="column_third">' . l('updated', 'github_tracker') . '</th><th class="column_last">' . l('milestones', 'github_tracker') . '</th></tr></thead>';
		$output .= '<tfoot><tr><td class="column_first">' . l('issues', 'github_tracker') . '</td><td class="column_second">' . l('created', 'github_tracker') . '</td><td class="column_third">' . l('updated', 'github_tracker') . '</td><td class="column_last">' . l('milestones', 'github_tracker') . '</td></tr></tfoot>';
		foreach ($data as $value)
		{
			/* break if limit reached */

			if (++$issues_counter > $option_limit_issues)
			{
				break;
			}

			/* collect issues output */

			$output .= '<tr>';
			$output .= '<td class="column_first">' . anchor_element('external', '', 'js_confirm link_github_tracker_issues', $value->title, $value->html_url) . '</td>';
			$output .= '<td class="column_second">' . date(s('date'), strtotime($value->created_at)) . '</td>';
			$output .= '<td class="column_third">' . date(s('date'), strtotime($value->updated_at)) . '</td>';
			$output .= '<td class="column_last">' . $value->milestone->title . '</td>';
			$output .= '</tr>';
		}
		$output .= '</tbody></table></div>';
	}
	echo $output;
}

/**
 * github tracker contents
 *
 * @since 2.1.0
 * @deprecated 2.0.0
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
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
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
