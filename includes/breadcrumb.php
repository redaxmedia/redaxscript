<?php

/* breadcrumb list */

function breadcrumb()
{
	hook(__FUNCTION__ . '_start');

	/* administration */

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

	/* overwrite if default alias */

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

	/* overwrite if title constant */

	if (TITLE)
	{
		$breadcrumb = '<li>' . TITLE . '</li>';
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

	/* overwrite if home */

	else if (FULL_STRING == '')
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
}
?>