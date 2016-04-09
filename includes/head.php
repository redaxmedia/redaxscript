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

function head($type = 'all')
{
	$output = Redaxscript\Hook::trigger('headStart');
	$lastTable = Redaxscript\Registry::get('lastTable');
	$lastParameter = Redaxscript\Registry::get('lastParameter');
	if ($lastTable)
	{
		/* fetch result */

		$result = Redaxscript\Db::forTablePrefix($lastTable)
			->where(array(
				'alias' => $lastParameter,
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

				if ($accessValidator->validate($access, Redaxscript\Registry::get('myGroups')) === Redaxscript\Validator\ValidatorInterface::PASSED)
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

	if (Redaxscript\Registry::get('metaTitle'))
	{
		$title = Redaxscript\Registry::get('metaTitle');
	}
	else if (!$title)
	{
		$title = Redaxscript\Db::getSetting('title');
	}

	/* prepare description */

	if (Redaxscript\Registry::get('metaDescription'))
	{
		$description = Redaxscript\Registry::get('metaDescription');
	}
	else if (!$description)
	{
		$description = Redaxscript\Db::getSetting('description');
	}

	/* prepare keywords */

	if (Redaxscript\Registry::get('metaKeywords'))
	{
		$keywords = Redaxscript\Registry::get('metaKeywords');
	}
	else if (!$keywords)
	{
		$keywords = Redaxscript\Db::getSetting('keywords');
	}

	/* prepare robots */

	if (Redaxscript\Registry::get('metaRobots'))
	{
		$robots = Redaxscript\Registry::get('metaRobots');
	}
	else if (Redaxscript\Registry::get('contentError') || Redaxscript\Registry::get('lastParameter') && $check_access == 0)
	{
		$robots = 0;
	}
	else
	{
		$robots = Redaxscript\Db::getSetting('robots');
	}

	/* collect meta output */

	if ($type == 'all' || $type == 'meta')
	{
		$output .= '<meta charset="' . Redaxscript\Db::getSetting('charset') . '" />' . PHP_EOL;
	}

	/* collect title */

	if (($type == 'all' || $type == 'title') && ($title || $description))
	{
		if ($title && $description)
		{
			$divider = Redaxscript\Db::getSetting('divider');
		}
		$output .= '<title>' . $title . $divider . $description . '</title>' . PHP_EOL;
	}

	/* collect meta */

	if ($type == 'all' || $type == 'meta')
	{
		/* collect refresh route */

		if (Redaxscript\Registry::get('refreshRoute'))
		{
			$output .= '<meta http-equiv="refresh" content="2; url=' . Redaxscript\Registry::get('refreshRoute') . '" />' . PHP_EOL;
		}

		/* collect author */

		if (Redaxscript\Db::getSetting('author'))
		{
			$output .= '<meta name="author" content="' . Redaxscript\Db::getSetting('author') . '" />' . PHP_EOL;
		}

		/* collect metadata */

		$output .= '<meta name="generator" content="' . Redaxscript\Language::get('name', '_package') . ' ' . Redaxscript\Language::get('version', '_package') . '" />' . PHP_EOL;
		if ($description)
		{
			$output .= '<meta name="description" content="' . $description . '" />' . PHP_EOL;
		}
		if ($keywords)
		{
			$output .= '<meta name="keywords" content="' . $keywords . '" />' . PHP_EOL;
		}
		$output .= '<meta name="robots" content="' . ($robots ? 'all' : 'none') . '" />' . PHP_EOL;
	}

	/* collect link */

	if ($type == 'all' || $type == 'link')
	{
		/* build canonical url */

		$canonical_url = Redaxscript\Registry::get('root') . Redaxscript\Registry::get('parameterRoute');

		/* article in category */

		$firstTable = Redaxscript\Registry::get('firstTable');
		$secondTable = Redaxscript\Registry::get('secondTable');
		$lastTable = Redaxscript\Registry::get('lastTable');
		$firstParameter = Redaxscript\Registry::get('firstParameter');
		$secondParameter = Redaxscript\Registry::get('secondParameter');
		$fullRoute = Redaxscript\Registry::get('fullRoute');
		if ($firstTable == 'categories' && $lastTable == 'articles')
		{
			if ($secondTable == 'categories')
			{
				$category = Redaxscript\Db::forTablePrefix($secondTable)->where('alias', $secondParameter)->findOne()->id;
			} else {
				$category = Redaxscript\Db::forTablePrefix($firstTable)->where('alias', $firstParameter)->findOne()->id;
			}

			/* total articles of category */

			$articles_total = Redaxscript\Db::forTablePrefix('articles')->where('category', $category)->count();
			if ($articles_total == 1)
			{
				$canonical_route = $firstParameter;
				if ($secondTable == 'categories')
				{
					$canonical_route .= '/' . $secondParameter;
				}
			}
		}

		/* extend canonical url */

		if ($canonical_route)
		{
			$canonical_url .= $canonical_route;
		} else {
			$canonical_url .= $fullRoute;
		}
		$output .= '<link href="' . $canonical_url . '" rel="canonical" />' . PHP_EOL;
	}
	$output .= Redaxscript\Hook::trigger('headEnd');
	echo $output;
}
