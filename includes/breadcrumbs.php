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
			$breadcrumbs = '<li>' . anchor_element('internal', '', '', l(ADMIN_PARAMETER), FULL_STRING) . '</li>';
		}
		if (l(TABLE_PARAMETER))
		{
			$breadcrumbs .= '<li class="divider">' . s('divider') . '</li><li>' . anchor_element('internal', '', '', l(TABLE_PARAMETER), FULL_STRING) . '</li>';
		}
	}

	/* join default alias */

	else if (check_alias(FIRST_PARAMETER, 1) == 1)
	{
		if (l(FIRST_PARAMETER))
		{
			$default_title = l(FIRST_PARAMETER);
		}
		$breadcrumbs = '<li>' . anchor_element('internal', '', '', $default_title, FIRST_PARAMETER) . '</li>';
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
		$breadcrumbs = '<li>' . anchor_element('internal', '', '', $first_title, FIRST_PARAMETER) . '</li>';
		if (SECOND_TABLE)
		{
			/* join second title */

			$second_title = retrieve('title', SECOND_TABLE, 'alias', SECOND_PARAMETER);
			$breadcrumbs .= '<li class="divider">' . s('divider') . '</li><li>' . anchor_element('internal', '', '', $second_title, FIRST_PARAMETER . '/' . SECOND_PARAMETER) . '</li>';
			if (THIRD_TABLE)
			{
				/* join third title */

				$third_title = retrieve('title', THIRD_TABLE, 'alias', THIRD_PARAMETER);
				$breadcrumbs .= '<li class="divider">' . s('divider') . '</li><li>' . anchor_element('internal', '', '', $third_title, FIRST_PARAMETER . '/' . SECOND_PARAMETER . '/' . THIRD_PARAMETER) . '</li>';
			}
		}
	}

	/* empty full string */

	else if (FULL_STRING == '')
	{
		$breadcrumbs = '<li>' . anchor_element('', '', '', l('home'), ROOT) . '</li>';
	}

	/* logged in */

	if (LOGGED_IN == TOKEN)
	{
		$breadcrumbs_admin = '<li>' . anchor_element('internal', '', '', l('administration'), 'admin') . '</li>';
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