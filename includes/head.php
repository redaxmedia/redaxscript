<?php

/**
 * head
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Head
 * @author Henry Ruhs
 */

function head()
{
	$output = Redaxscript\Hook::trigger(__FUNCTION__ . '_start');
	if (LAST_TABLE)
	{
		/* query contents */

		$query = 'SELECT title, description, keywords, access FROM ' . PREFIX . LAST_TABLE . ' WHERE alias = \'' . LAST_PARAMETER . '\' && status = 1';
		$result = mysql_query($query);
		if ($result)
		{
			$accessValidator = new Redaxscript\Validator\Access();
			while ($r = mysql_fetch_assoc($result))
			{
				$access = $r['access'];
				$check_access = $accessValidator->validate($access, MY_GROUPS);

				/* if access granted */

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

	/* prepare title */

	if (TITLE)
	{
		$title = TITLE;
	}
	else if ($title == '')
	{
		$title = s('title');
	}

	/* prepare description */

	if (DESCRIPTION)
	{
		$description = DESCRIPTION;
	}
	else if ($description == '')
	{
		$description = s('description');
	}

	/* prepare keywords */

	if (KEYWORDS)
	{
		$keywords = KEYWORDS;
	}
	else if ($keywords == '')
	{
		$keywords = s('keywords');
	}

	/* prepare robots */

	if (ROBOTS)
	{
		$robots = ROBOTS;
	}
	else if (CONTENT_ERROR || LAST_PARAMETER && $check_access == 0)
	{
		$robots = 'none';
	}
	else
	{
		$robots = s('robots');
	}

	/* collect meta output */

	$output .= '<base href="' . ROOT . '/" />' . PHP_EOL;
	$output .= '<meta charset="' . s('charset') . '" />' . PHP_EOL;

	/* collect title output */

	if ($title || $description)
	{
		if ($title && $description)
		{
			$divider = s('divider');
		}
		$output .= '<title>' . truncate($title . $divider . $description, 80) . '</title>' . PHP_EOL;
	}

	/* collect refresh route */

	if (REFRESH_ROUTE)
	{
		$output .= '<meta http-equiv="refresh" content="2; url=' . REFRESH_ROUTE . '" />' . PHP_EOL;
	}

	/* collect author */

	if (s('author'))
	{
		$output .= '<meta name="author" content="' . s('author') . '" />' . PHP_EOL;
	}

	/* collect metadata */

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

	/* build canonical url */

	$canonical_url = ROOT . '/' . REWRITE_ROUTE;

	/* if article in category */

	if (FIRST_TABLE == 'categories' && LAST_TABLE == 'articles')
	{
		if (SECOND_TABLE == 'categories')
		{
			$category = retrieve('id', SECOND_TABLE, 'alias', SECOND_PARAMETER);
		}
		else
		{
			$category = retrieve('id', FIRST_TABLE, 'alias', FIRST_PARAMETER);
		}

		/* total articles of category */

		$articles_total = query_total('articles', 'category', $category);
		if ($articles_total == 1)
		{
			$canonical_route = FIRST_PARAMETER;
			if (SECOND_TABLE == 'categories')
			{
				$canonical_route .= '/' . SECOND_PARAMETER;
			}
		}
	}

	/* extend canonical url */

	if ($canonical_route)
	{
		$canonical_url .= $canonical_route;
	}
	else
	{
		$canonical_url .= FULL_ROUTE;
	}
	$output .= '<link href="' . $canonical_url . '" rel="canonical" />' . PHP_EOL;
	$output .= Redaxscript\Hook::trigger(__FUNCTION__ . '_end');
	echo $output;
}