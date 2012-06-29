<?php

/* breadcrumbs list */

function breadcrumbs_list()
{
	hook(__FUNCTION__ . '_start');

	/* join administration */

	if (FIRST_PARAMETER == 'admin')
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
			$breadcrumbs = '<li>';
			if (ADMIN_PARAMETER && LAST_PARAMETER == '')
			{
				$breadcrumbs .= $title_admin;
			}
			else
			{
				$breadcrumbs .= anchor_element('internal', '', '', $title_admin, FULL_STRING);
			}
			$breadcrumbs .= '</li>';

			/* join table title */

			if ($title_table)
			{
				$breadcrumbs .= '<li class="divider">' . s('divider') . '</li><li>';
				if (TABLE_PARAMETER == LAST_PARAMETER || TABLE_PARAMETER == THIRD_PARAMETER && TOKEN_PARAMETER == LAST_PARAMETER)
				{
					$breadcrumbs .= $title_table;
				}
				else
				{
					$breadcrumbs .= anchor_element('internal', '', '', $title_table, FULL_STRING);
				}
				$breadcrumbs .= '</li>';
			}
		}
	}

	/* join default alias */

	else if (check_alias(FIRST_PARAMETER, 1) == 1)
	{
		if (l(FIRST_PARAMETER))
		{
			$default_title = l(FIRST_PARAMETER);
		}

		/* join default title */

		if ($default_title)
		{
			$breadcrumbs = '<li>';
			if (FIRST_PARAMETER == LAST_PARAMETER)
			{
				$breadcrumbs .= $default_title;
			}
			else
			{
				$breadcrumbs .= anchor_element('internal', '', '', $default_title, FIRST_PARAMETER);
			}
			$breadcrumbs .= '</li>';
		}
	}

	/* overwrite if title constant */

	if (TITLE)
	{
		$breadcrumbs = '<li>' . TITLE . '</li>';
	}

	/* query title from content */

	else if (FIRST_TABLE)
	{
		/* join first title */

		$first_title = retrieve('title', FIRST_TABLE, 'alias', FIRST_PARAMETER);
		$breadcrumbs = '<li>';
		if (FIRST_PARAMETER == LAST_PARAMETER)
		{
			$breadcrumbs .= $first_title;
		}
		else
		{
			$breadcrumbs .= anchor_element('internal', '', '', $first_title, FIRST_PARAMETER);
		}
		$breadcrumbs .= '</li>';
		if (SECOND_TABLE)
		{
			/* join second title */

			$second_title = retrieve('title', SECOND_TABLE, 'alias', SECOND_PARAMETER);
			$breadcrumbs .= '<li class="divider">' . s('divider') . '</li><li>';
			if (SECOND_PARAMETER == LAST_PARAMETER)
			{
				$breadcrumbs .= $second_title;	
			}
			else
			{
				$breadcrumbs .= anchor_element('internal', '', '', $second_title, FIRST_PARAMETER . '/' . SECOND_PARAMETER);
			}
			$breadcrumbs .= '</li>';
			if (THIRD_TABLE)
			{
				/* join third title */

				$third_title = retrieve('title', THIRD_TABLE, 'alias', THIRD_PARAMETER);
				$breadcrumbs .= '<li class="divider">' . s('divider') . '</li><li>';
				if (THIRD_PARAMETER == LAST_PARAMETER)
				{
					$breadcrumbs .= $third_title;	
				}
				else
				{
					$breadcrumbs .= anchor_element('internal', '', '', $third_title, FIRST_PARAMETER . '/' . SECOND_PARAMETER . '/' . THIRD_PARAMETER);
				}
				$breadcrumbs .= '</li>';
			}
		}
	}

	/* empty full string */

	else if (FULL_STRING == '')
	{
		$breadcrumbs = '<li>' . l('home') . '</li>';
	}

	/* logged in */

	if (LOGGED_IN == TOKEN)
	{
		$breadcrumbs_admin = '<li>';
		if (FIRST_PARAMETER == 'admin' && SECOND_TABLE == '' && SECOND_PARAMETER == '')
		{
			$breadcrumbs_admin .= l('administration');
		}
		else
		{
			$breadcrumbs_admin .= anchor_element('internal', '', '', l('administration'), 'admin');
		}
		$breadcrumbs_admin .= '</li>';
		if ($breadcrumbs)
		{
			$breadcrumbs_admin .= '<li class="divider">' . s('divider') . '</li>';
		}
	}

	/* handle error */

	else if ($breadcrumbs == '')
	{
		$breadcrumbs = '<li>' . l('error') . '</li>';
	}

	/* collect output */

	$output = '<ul class="list_breadcrumbs">' . $breadcrumbs_admin . $breadcrumbs . '</ul>';
	echo $output;
	hook(__FUNCTION__ . '_end');
}
?>