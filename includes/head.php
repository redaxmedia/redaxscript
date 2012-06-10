<?php

/* head */

function head()
{
	hook(__FUNCTION__ . '_start');
	if (LAST_TABLE)
	{
		/* query contents */

		$query = 'SELECT description, keywords, access FROM ' . PREFIX . LAST_TABLE . ' WHERE alias = \'' . LAST_PARAMETER . '\' && status = 1';
		$result = mysql_query($query);
		if ($result)
		{
			while ($r = mysql_fetch_assoc($result))
			{
				$access = $r['access'];
				$check_access = check_access($access, MY_GROUPS);
				if ($check_access == 1)
				{
					if ($r)
					{
						foreach ($r as $key => $value)
						{
							$$key = stripslashes($value);
						}
					}
				}
			}
		}
	}

	/* build meta strings */

	$title = s('title');
	if ($title)
	{
		$title_divider = ' - ';
	}
	if (DESCRIPTION)
	{
		$description = DESCRIPTION;
	}
	else if ($description == '')
	{
		$description = s('description');
	}
	if ($description)
	{
		$description_divider = ' - ';
	}
	if (KEYWORDS)
	{
		$keywords = KEYWORDS;
	}
	else if ($keywords == '')
	{
		$keywords = s('keywords');
	}

	/* join administration */

	if (FIRST_PARAMETER == 'admin')
	{
		if (l(ADMIN_PARAMETER))
		{
			$breadcrumbs = l(ADMIN_PARAMETER);
		}
		if (l(TABLE_PARAMETER))
		{
			$breadcrumbs .= ' - ' . l(TABLE_PARAMETER);
		}
	}

	/* join default alias */

	else if (check_alias(FIRST_PARAMETER, 1) == 1)
	{
		if (l(FIRST_PARAMETER))
		{
			$default_title = l(FIRST_PARAMETER);
		}
		$breadcrumbs = $default_title;
	}

	/* overwrite if title constant */

	if (TITLE)
	{
		$breadcrumbs = TITLE;
	}

	/* query title */

	else if (FIRST_TABLE)
	{
		/* join first title */

		$first_title = retrieve('title', FIRST_TABLE, 'alias', FIRST_PARAMETER);
		$breadcrumbs = $first_title;
		if (SECOND_TABLE)
		{
			/* join second title */

			$second_title = retrieve('title', SECOND_TABLE, 'alias', SECOND_PARAMETER);
			$breadcrumbs .= ' - ' . $second_title;
			if (THIRD_TABLE)
			{
				/* join third title */

				$third_title = retrieve('title', THIRD_TABLE, 'alias', THIRD_PARAMETER);
				$breadcrumbs .= ' - ' . $third_title;
			}
		}
	}

	/* empty full string */

	else if (FULL_STRING == '')
	{
		$breadcrumbs = l('home');
	}

	/* logged in */

	if (LOGGED_IN == TOKEN)
	{
		$breadcrumbs_admin = l('administration');
		if ($breadcrumbs)
		{
			$breadcrumbs_admin .= ' - ';
		}
	}

	/* handle error */

	else if ($breadcrumbs == '')
	{
		$breadcrumbs = l('error');
	}

	/* overwrite robots */

	if (ROBOTS)
	{
		$robots = ROBOTS;
	}
	else if (DB_CONNECTED == 0 || FIRST_PARAMETER && FIRST_TABLE == '' || SECOND_PARAMETER && SECOND_TABLE == '' || THIRD_PARAMETER && THIRD_TABLE == '' || LAST_TABLE && $check_access == 0)
	{
		$robots = 'none';
	}
	else
	{
		$robots = s('robots');
	}

	/* collect output */

	$output = '<base href="' . ROOT . '/" />' . PHP_EOL;
	$output .= '<title>' . $title . $title_divider . $breadcrumbs_admin . $breadcrumbs . $description_divider . $description . '</title>' . PHP_EOL;
	$output .= '<meta http-equiv="content-type" content="text/html; charset=' . s('charset') . '" />' . PHP_EOL;

	/* refresh string */

	if (REFRESH_STRING)
	{
		$output .= '<meta http-equiv="refresh" content="2; url=' . REWRITE_STRING . REFRESH_STRING . '" />' . PHP_EOL;
	}
	if (s('author'))
	{
		$output .= '<meta name="author" content="' . s('author') . '" />' . PHP_EOL;
	}
	if (s('copyright'))
	{
		$output .= '<meta name="copyright" content="' . s('copyright') . '" />' . PHP_EOL;
	}
	$output .= '<meta name="generator" content="' . l('redaxscript') . ' ' . l('redaxscript_version') . '" />' . PHP_EOL;
	if ($description)
	{
		$output .= '<meta name="description" content="' . $description . '" />' . PHP_EOL;
	}
	if ($keywords)
	{
		$output .= '<meta name="keywords" content="' . $keywords . '" />' . PHP_EOL;
	}
	$output .= '<meta name="robots" content="' . $robots . '" />' . PHP_EOL;

	/* canonical url */

	if (LOGGED_IN != TOKEN)
	{
		$output .= '<link href="' . ROOT . '/' . REWRITE_STRING . FULL_STRING . '" rel="canonical" />' . PHP_EOL;
	}
	echo $output;
	hook(__FUNCTION__ . '_end');
}
?>