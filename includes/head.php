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

	/* administration */

	if (FIRST_PARAMETER == 'admin')
	{
		if (l(ADMIN_PARAMETER))
		{
			$breadcrumb = l(ADMIN_PARAMETER);
			if (l(TABLE_PARAMETER))
			{
				$breadcrumb .= ' - ' . l(TABLE_PARAMETER);
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
		$breadcrumb = $default_title;
	}

	/* overwrite if title constant */

	if (TITLE)
	{
		$breadcrumb = TITLE;
	}

	/* query title from content */

	else if (FIRST_TABLE)
	{
		/* join first title */

		$first_title = retrieve('title', FIRST_TABLE, 'alias', FIRST_PARAMETER);
		$breadcrumb = $first_title;
		if (SECOND_TABLE)
		{
			/* join second title */

			$second_title = retrieve('title', SECOND_TABLE, 'alias', SECOND_PARAMETER);
			$breadcrumb .= ' - ' . $second_title;
			if (THIRD_TABLE)
			{
				/* join third title */

				$third_title = retrieve('title', THIRD_TABLE, 'alias', THIRD_PARAMETER);
				$breadcrumb .= ' - ' . $third_title;
			}
		}
	}

	/* overwrite if home */

	else if (FULL_STRING == '')
	{
		$breadcrumb = l('home');
	}

	/* logged in */

	if (LOGGED_IN == TOKEN)
	{
		$breadcrumb_admin = l('administration');
		if ($breadcrumb)
		{
			$breadcrumb_admin .= ' - ';
		}
	}

	/* handle error */

	else if ($breadcrumb == '')
	{
		$breadcrumb = l('error');
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
	$output .= '<title>' . $title . $title_divider . $breadcrumb_admin . $breadcrumb . $description_divider . $description . '</title>' . PHP_EOL;
	$output .= '<meta http-equiv="content-type" content="text/html; charset=' . s('charset') . '" />' . PHP_EOL;

	/* refresh string */

	if (REFRESH_STRING)
	{
		$output .= '<meta http-equiv="refresh" content="2; url=' . REFRESH_STRING . '" />' . PHP_EOL;
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