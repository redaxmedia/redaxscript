<?php

/**
 * feed list
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function feed_list()
{
	$articles_total = query_total('articles', 'access', 0);
	$comments_total = query_total('comments', 'access', 0);

	/* collect output */

	if ($articles_total > 0)
	{
		$output = '<li>' . anchor_element('internal', '', '', l('feed_articles', '_feed_generator'), 'feed/articles', '', 'rel="nofollow"') . '</li>';
	}
	if ($comments_total > 0)
	{
		$output .= '<li>' . anchor_element('internal', '', '', l('feed_comments', '_feed_generator'), 'feed/comments', '', 'rel="nofollow"') . '</li>';
	}
	if ($articles_total > 0 || $comments_total > 0)
	{
		$output = '<ul class="list_feed">' . $output . '</ul>';
	}
	echo $output;
}

/**
 * feed generator render start
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function feed_generator_render_start()
{
	if (FIRST_PARAMETER == 'feed' && (SECOND_PARAMETER == 'articles' || SECOND_PARAMETER == 'comments'))
	{
		Redaxscript\Registry::set('renderBreak', true);
		header('content-type: application/atom+xml');
		feed_generator(SECOND_PARAMETER);
	}
}

/**
 * feed generator
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 *
 * param string $table
 */

function feed_generator($table = '')
{
	if ($_GET['l'])
	{
		$language = Redaxscript\Registry::get('language');
		$language_route = LANGUAGE_ROUTE;
	}

	/* query table contents */

	$query = 'SELECT * FROM ' . PREFIX . $table . ' WHERE (language = \'' . $language . '\' || language = \'all\') && status = 1 && access = 0 ORDER BY rank ' . s('order') . ' LIMIT ' . s('limit');
	$result = Redaxscript\Db::forTablePrefix($table)->rawQuery($query)->findArray();
	if ($result)
	{
		/* define variables */

		$title = s('title');
		$description = s('description');
		$author = s('author');
		$email = s('email');
		$copyright = s('copyright');
		$route = ROOT . '/' . REWRITE_ROUTE . FULL_ROUTE . $language_route . $language;

		/* collect feed header output */

		$output = '<?xml version="1.0" encoding="' . s('charset') . '"?>';
		$output .= '<feed xmlns="http://www.w3.org/2005/Atom">';
		$output .= '<id>' . $route . '</id>';
		if ($title)
		{
			$output .= '<title type="text">' . $title . '</title>';
		}
		if ($description)
		{
			$output .= '<subtitle type="text">' . $description . '</subtitle>';
		}
		$output .= '<link type="application/atom+xml" href="' . $route . '" rel="self" />';
		$output .= '<updated>' . date('c', strtotime(NOW)) . '</updated>';
		if ($author || $email)
		{
			$output .= '<author>';
			if ($author)
			{
				$output .= '<name>' . $author . '</name>';
			}
			if ($email)
			{
				$output .= '<email>' . $email . '</email>';
			}
			$output .= '</author>';
		}
		if ($copyright)
		{
			$output .= '<rights>' . $copyright . '</rights>';
		}
		$output .= '<generator>' . l('name', '_package') . ' ' . l('version', '_package') . '</generator>';

		/* collect feed body output */

		foreach ($result as $r)
		{
			if ($r)
			{
				foreach ($r as $key => $value)
				{
					$$key = stripslashes($value);
				}
			}

			/* define variables */

			$date = date('c', strtotime($date));
			$text = htmlspecialchars(strip_tags($text));
			if ($table == 'comments')
			{
				$title = $author;
			}

			/* build route */

			$route = ROOT . '/' . REWRITE_ROUTE;
			if ($table == 'articles' && $category == 0)
			{
				$route .= $alias;
			}
			else
			{
				$route .= build_route($table, $id);
			}
			$route .= $language_route;

			/* collect entry output */

			$output .= '<entry>';
			$output .= '<id>' . $route . '</id>';
			$output .= '<title type="text">' . $title . '</title>';
			$output .= '<link href="' . $route . '" />';
			$output .= '<updated>' . $date . '</updated>';
			if ($description)
			{
				$output .= '<summary type="text">' . $description . '</summary>';
			}
			$output .= '<content type="html">' . $text . '</content>';
			$output .= '</entry>';
		}
		$output .= '</feed>';
	}
	echo $output;
}
