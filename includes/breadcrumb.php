<?php

/* breadcrumb */

function breadcrumb()
{
	hook(__FUNCTION__ . '_start');

	/* build breadcrumb */

	$breadcrumb_array = build_breadcrumb();
	$last = end(array_keys($breadcrumb_array ));

	/* collect item output */

	foreach ($breadcrumb_array as $key => $value)
	{
		$title = $value['title'];
		$string = $value['string'];
		if ($title)
		{
			$output .= '<li>';
			if ($string)
			{
				$output .= anchor_element('internal', '', '', $title, $string);
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

/* build breadcrumb */

function build_breadcrumb()
{
	static $breadcrumb;

	if ($breadcrumb == '')
	{
		$key = 0;

		/* if title constant */

		if (TITLE)
		{
			$breadcrumb[$key]['title'] = TITLE;
		}

		/* else if home */

		else if (FULL_STRING == '')
		{
			$breadcrumb[$key]['title'] = l('home');
		}

		/* else if administration */

		else if (FIRST_PARAMETER == 'admin')
		{
			$breadcrumb[$key]['title'] = l('administration');
			if (ADMIN_PARAMETER)
			{
				$breadcrumb[$key]['string'] = 'admin';
			}

			/* join admin title */

			if (l(ADMIN_PARAMETER))
			{
				$key++;
				$breadcrumb[$key]['title'] = l(ADMIN_PARAMETER);
				if (ADMIN_PARAMETER != LAST_PARAMETER)
				{
					$breadcrumb[$key]['string'] = FULL_STRING;
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
				$breadcrumb[$key]['string'] = FIRST_PARAMETER;
			}

			/* join second title */

			if (SECOND_TABLE)
			{
				$key++;
				$breadcrumb[$key]['title'] = retrieve('title', SECOND_TABLE, 'alias', SECOND_PARAMETER);
				if (SECOND_PARAMETER != LAST_PARAMETER)
				{
					$breadcrumb[$key]['string'] = FIRST_PARAMETER . '/' . SECOND_PARAMETER;
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