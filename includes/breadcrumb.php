<?php

/* breadcrumb */

function breadcrumb()
{
	hook(__FUNCTION__ . '_start');

	/* if title constant */

	if (TITLE)
	{
		$breadcrumb = '<li>' . TITLE . '</li>';
	}

	/* else if administration */

	else if (FIRST_PARAMETER == 'admin')
	{
		if (l(ADMIN_PARAMETER))
		{
			$title_admin = l(ADMIN_PARAMETER);
			if (l(TABLE_PARAMETER))
			{
				$title_table = l(TABLE_PARAMETER);
			}
		}

		/* join admin title */

		if ($title_admin)
		{
			$breadcrumb = '<li>';
			if (ADMIN_PARAMETER && LAST_PARAMETER == '')
			{
				$breadcrumb .= $title_admin;
			}
			else
			{
				$breadcrumb .= anchor_element('internal', '', '', $title_admin, FULL_STRING);
			}
			$breadcrumb .= '</li>';

			/* join table title */

			if ($title_table)
			{
				$breadcrumb .= '<li class="divider">' . s('divider') . '</li><li>';
				if (TABLE_PARAMETER == LAST_PARAMETER || TABLE_PARAMETER == THIRD_PARAMETER && TOKEN_PARAMETER == LAST_PARAMETER)
				{
					$breadcrumb .= $title_table;
				}
				else
				{
					$breadcrumb .= anchor_element('internal', '', '', $title_table, FULL_STRING);
				}
				$breadcrumb .= '</li>';
			}
		}
	}

	/* else if default alias */

	else if (check_alias(FIRST_PARAMETER, 1) == 1)
	{
		if (l(FIRST_PARAMETER))
		{
			$default_title = l(FIRST_PARAMETER);
		}

		/* join default title */

		if ($default_title)
		{
			$breadcrumb = '<li>';
			if (FIRST_PARAMETER == LAST_PARAMETER)
			{
				$breadcrumb .= $default_title;
			}
			else
			{
				$breadcrumb .= anchor_element('internal', '', '', $default_title, FIRST_PARAMETER);
			}
			$breadcrumb .= '</li>';
		}
	}

	/* query title from content */

	else if (FIRST_TABLE)
	{
		/* join first title */

		$first_title = retrieve('title', FIRST_TABLE, 'alias', FIRST_PARAMETER);
		$breadcrumb = '<li>';
		if (FIRST_PARAMETER == LAST_PARAMETER)
		{
			$breadcrumb .= $first_title;
		}
		else
		{
			$breadcrumb .= anchor_element('internal', '', '', $first_title, FIRST_PARAMETER);
		}
		$breadcrumb .= '</li>';
		if (SECOND_TABLE)
		{
			/* join second title */

			$second_title = retrieve('title', SECOND_TABLE, 'alias', SECOND_PARAMETER);
			$breadcrumb .= '<li class="divider">' . s('divider') . '</li><li>';
			if (SECOND_PARAMETER == LAST_PARAMETER)
			{
				$breadcrumb .= $second_title;
			}
			else
			{
				$breadcrumb .= anchor_element('internal', '', '', $second_title, FIRST_PARAMETER . '/' . SECOND_PARAMETER);
			}
			$breadcrumb .= '</li>';
			if (THIRD_TABLE)
			{
				/* join third title */

				$third_title = retrieve('title', THIRD_TABLE, 'alias', THIRD_PARAMETER);
				$breadcrumb .= '<li class="divider">' . s('divider') . '</li><li>';
				if (THIRD_PARAMETER == LAST_PARAMETER)
				{
					$breadcrumb .= $third_title;
				}
				else
				{
					$breadcrumb .= anchor_element('internal', '', '', $third_title, FIRST_PARAMETER . '/' . SECOND_PARAMETER . '/' . THIRD_PARAMETER);
				}
				$breadcrumb .= '</li>';
			}
		}
	}

	/* if empty full string */

	if (FULL_STRING == '')
	{
		$breadcrumb = '<li>' . l('home') . '</li>';
	}

	/* logged in */

	if (LOGGED_IN == TOKEN)
	{
		$breadcrumb_admin = '<li>';
		if (FIRST_PARAMETER == 'admin' && SECOND_TABLE == '' && SECOND_PARAMETER == '')
		{
			$breadcrumb_admin .= l('administration');
		}
		else
		{
			$breadcrumb_admin .= anchor_element('internal', '', '', l('administration'), 'admin');
		}
		$breadcrumb_admin .= '</li>';
		if ($breadcrumb)
		{
			$breadcrumb_admin .= '<li class="divider">' . s('divider') . '</li>';
		}
	}

	/* handle error */

	else if ($breadcrumb == '')
	{
		$breadcrumb = '<li>' . l('error') . '</li>';
	}

	/* collect output */

	$output = '<ul class="list_breadcrumb">' . $breadcrumb_admin . $breadcrumb . '</ul>';
	echo $output;
	hook(__FUNCTION__ . '_end');
	
	/* debug */

	echo '<pre>';
	var_dump(build_breadcrumb());
	echo '</pre>';
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