<?php

/**
 * breadcrumb
 *
 * @since 1.2.1
 * @deprecated 2.0
 *
 * @package Redaxscript
 * @category Breadcrumb
 * @author Henry Ruhs
 */

function breadcrumb()
{
	hook(__FUNCTION__ . '_start');

	/* build breadcrumb */

	$breadcrumb_array = build_breadcrumb();
	$breadcrumb_array_keys = array_keys($breadcrumb_array);
	$last = end($breadcrumb_array_keys);

	/* collect item output */

	foreach ($breadcrumb_array as $key => $value)
	{
		$title = $value['title'];
		$route = $value['route'];
		if ($title)
		{
			$output .= '<li>';
			if ($route)
			{
				$output .= anchor_element('internal', '', '', $title, $route);
			}
			else
			{
				$output .= $title;
			}
			$output .= '</li>';
			if ($last != $key)
			{
				$output .= '<li class="divider">' . s('divider') . '</li>';
			}
		}
	}

	/* collect list output */

	if ($output)
	{
		$output = '<ul class="list_breadcrumb">' . $output . '</ul>';
	}
	echo $output;
	hook(__FUNCTION__ . '_end');
}

/**
 * build breadcrumb
 *
 * @since 1.2.1
 * @deprecated 2.0
 *
 * @package Redaxscript
 * @category Breadcrumb
 * @author Henry Ruhs
 *
 * @return array
 */

function build_breadcrumb()
{
	static $breadcrumb;

	/* build breadcrumb */

	if ($breadcrumb == '')
	{
		$key = 0;

		/* if title constant */

		if (TITLE)
		{
			$breadcrumb[$key]['title'] = TITLE;
		}

		/* else if home */

		else if (FULL_ROUTE == '')
		{
			$breadcrumb[$key]['title'] = l('home');
		}

		/* else if administration */

		else if (FIRST_PARAMETER == 'admin')
		{
			$breadcrumb[$key]['title'] = l('administration');
			if (ADMIN_PARAMETER)
			{
				$breadcrumb[$key]['route'] = 'admin';
			}

			/* join admin title */

			if (l(ADMIN_PARAMETER))
			{
				$key++;
				$breadcrumb[$key]['title'] = l(ADMIN_PARAMETER);
				if (ADMIN_PARAMETER != LAST_PARAMETER)
				{
					$breadcrumb[$key]['route'] = FULL_ROUTE;
				}

				/* join table title */

				if (l(TABLE_PARAMETER))
				{
					$key++;
					$breadcrumb[$key]['title'] = l(TABLE_PARAMETER);
				}
			}
		}

		/* else if default alias */

		else if (check_alias(FIRST_PARAMETER, 1) == 1)
		{
			/* join default title */

			if (l(FIRST_PARAMETER))
			{
				$breadcrumb[$key]['title'] = l(FIRST_PARAMETER);
			}
		}

		/* handle error */

		else if (LAST_ID == '')
		{
			$breadcrumb[$key]['title'] = l('error');
		}

		/* query title from content */

		else if (FIRST_TABLE)
		{
			/* join first title */

			$breadcrumb[$key]['title'] = retrieve('title', FIRST_TABLE, 'alias', FIRST_PARAMETER);
			if (FIRST_PARAMETER != LAST_PARAMETER)
			{
				$breadcrumb[$key]['route'] = FIRST_PARAMETER;
			}

			/* join second title */

			if (SECOND_TABLE)
			{
				$key++;
				$breadcrumb[$key]['title'] = retrieve('title', SECOND_TABLE, 'alias', SECOND_PARAMETER);
				if (SECOND_PARAMETER != LAST_PARAMETER)
				{
					$breadcrumb[$key]['route'] = FIRST_PARAMETER . '/' . SECOND_PARAMETER;
				}

				/* join third title */

				if (THIRD_TABLE)
				{
					$key++;
					$breadcrumb[$key]['title'] = retrieve('title', THIRD_TABLE, 'alias', THIRD_PARAMETER);
				}
			}
		}
	}
	$output = $breadcrumb;
	return $output;
}
?>