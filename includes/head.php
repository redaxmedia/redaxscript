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
 *
 * @param string $type
 */

function head($type = '')
{
	$output = Redaxscript\Hook::trigger('headStart');
	if (LAST_TABLE)
	{
		/* fetch result */

		$result = Redaxscript\Db::forTablePrefix(LAST_TABLE)
			->where(array(
				'alias' => LAST_PARAMETER,
				'status' => 1
			))->findArray();

		/* process result */

		if ($result)
		{
			$accessValidator = new Redaxscript\Validator\Access();
			foreach ($result as $r)
			{
				$access = $r['access'];

				/* access granted */

				if ($accessValidator->validate($access, MY_GROUPS) === Redaxscript\Validator\ValidatorInterface::PASSED)
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

	/* undefine */

	undefine(array(
		'REFRESH_ROUTE',
		'DESCRIPTION',
		'KEYWORDS',
		'ROBOTS',
		'TITLE'
	));

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
		$robots = 0;
	}
	else
	{
		$robots = s('robots');
	}

	/* collect meta output */

	if ($type == '' || $type == 'base')
	{
		$output .= '<base href="' . ROOT . '/" />' . PHP_EOL;
	}
	if ($type == '' || $type == 'meta')
	{
		$output .= '<meta charset="' . s('charset') . '" />' . PHP_EOL;
	}

	/* collect title */

	if (($type == '' || $type == 'title') && ($title || $description))
	{
		if ($title && $description)
		{
			$divider = s('divider');
		}
		$output .= '<title>' . $title . $divider . $description . '</title>' . PHP_EOL;
	}

	/* collect meta */

	if ($type == '' || $type == 'meta') {
		/* collect refresh route */

		if (REFRESH_ROUTE) {
			$output .= '<meta http-equiv="refresh" content="2; url=' . REFRESH_ROUTE . '" />' . PHP_EOL;
		}

		/* collect author */

		if (s('author')) {
			$output .= '<meta name="author" content="' . s('author') . '" />' . PHP_EOL;
		}

		/* collect metadata */

		$output .= '<meta name="generator" content="' . Redaxscript\Language::get('name', '_package') . ' ' . Redaxscript\Language::get('version', '_package') . '" />' . PHP_EOL;
		if ($description) {
			$output .= '<meta name="description" content="' . $description . '" />' . PHP_EOL;
		}
		if ($keywords) {
			$output .= '<meta name="keywords" content="' . $keywords . '" />' . PHP_EOL;
		}
		$output .= '<meta name="robots" content="' . ($robots ? 'all' : 'none') . '" />' . PHP_EOL;
	}

	/* collect link */

	if ($type == '' || $type == 'link')
	{
		/* build canonical url */

		$canonical_url = ROOT . '/' . REWRITE_ROUTE;

		/* article in category */

		if (FIRST_TABLE == 'categories' && LAST_TABLE == 'articles') {
			if (SECOND_TABLE == 'categories') {
				$category = Redaxscript\Db::forTablePrefix(SECOND_TABLE)->where('alias', SECOND_PARAMETER)->findOne()->id;
			} else {
				$category = Redaxscript\Db::forTablePrefix(FIRST_TABLE)->where('alias', FIRST_PARAMETER)->findOne()->id;
			}

			/* total articles of category */

			$articles_total = Redaxscript\Db::forTablePrefix('articles')->where('category', $category)->count();
			if ($articles_total == 1) {
				$canonical_route = FIRST_PARAMETER;
				if (SECOND_TABLE == 'categories') {
					$canonical_route .= '/' . SECOND_PARAMETER;
				}
			}
		}

		/* extend canonical url */

		if ($canonical_route) {
			$canonical_url .= $canonical_route;
		} else {
			$canonical_url .= FULL_ROUTE;
		}
		$output .= '<link href="' . $canonical_url . '" rel="canonical" />' . PHP_EOL;
	}
	$output .= Redaxscript\Hook::trigger('headEnd');
	echo $output;
}
