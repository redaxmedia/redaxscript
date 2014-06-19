<?php

/**
 * contents
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Contents
 * @author Henry Ruhs
 */

function contents()
{
	hook(__FUNCTION__ . '_start');

	/* query contents */

	$query = 'SELECT id, title, author, text, language, date, headline, infoline, comments, access FROM ' . PREFIX . 'articles WHERE status = 1';
	if (ARTICLE)
	{
		$query .= ' && id = ' . ARTICLE;
	}
	else if (CATEGORY)
	{
		$query .= ' && (language = \'' . LANGUAGE . '\' || language = \'\') && category = ' . CATEGORY . ' ORDER BY rank ' . s('order');
		$result = mysql_query($query);
		if ($result)
		{
			$num_rows = mysql_num_rows($result);
			$sub_maximum = ceil($num_rows / s('limit'));
			$sub_active = LAST_SUB_PARAMETER;

			/* if sub parameter */

			if (LAST_SUB_PARAMETER > $sub_maximum || LAST_SUB_PARAMETER == '')
			{
				$sub_active = 1;
			}
			else
			{
				$offset_string = ($sub_active - 1) * s('limit') . ', ';
			}
		}
		$query .= ' LIMIT ' . $offset_string . s('limit');
	}
	else
	{
		$query .= ' LIMIT 0';
	}
	$result = mysql_query($query);
	$num_rows_active = mysql_num_rows($result);

	/* handle error */

	if (DB_CONNECTED == 0)
	{
		$error = l('database_failed');
	}
	else if (CATEGORY && $num_rows == '')
	{
		$error = l('article_no');
	}
	else if ($result == '' || $num_rows_active == '' || CONTENT_ERROR)
	{
		$error = l('content_not_found');
	}

	/* collect output */

	else if ($result)
	{
		while ($r = mysql_fetch_assoc($result))
		{
			$access = $r['access'];
			$check_access = check_access($access, MY_GROUPS);

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
				if (LAST_TABLE == 'categories' || FULL_ROUTE == '' || check_alias(FIRST_PARAMETER, 1) == 1)
				{
					$route = build_route('articles', $id);
				}

				/* registry and parser object */

				$registry = Redaxscript_Registry::getInstance();
				$parser = new Redaxscript_Parser($registry, $text, $route);

				/* collect headline output */

				$output .= hook('article_start');
				if ($headline == 1)
				{
					$output .= '<h2 class="title_content">';
					if (LAST_TABLE == 'categories' || FULL_ROUTE == '' || check_alias(FIRST_PARAMETER, 1) == 1)
					{
						$output .= anchor_element('internal', '', '', $title, $route);
					}
					else
					{
						$output .= $title;
					}
					$output .= '</h2>';
				}

				/* collect box output */

				$output .= '<div class="box_content">' . $parser->getOutput();
				$output .= '</div>' . hook('article_end');

				/* prepend admin dock */

				if (LOGGED_IN == TOKEN && FIRST_PARAMETER != 'logout')
				{
					$output .= admin_dock('articles', $id);
				}

				/* infoline */

				if ($infoline == 1)
				{
					$output .= infoline('articles', $id, $author, $date);
				}
			}
			else
			{
				$counter++;
			}
		}

		/* handle access */

		if (LAST_TABLE == 'categories')
		{
			if ($num_rows_active == $counter)
			{
				$error = l('access_no');
			}
		}
		else if (LAST_TABLE == 'articles' && $counter == 1)
		{
			$error = l('access_no');
		}
	}

	/* handle error */

	if ($error)
	{
		notification(l('something_wrong'), $error);
	}
	else
	{
		echo $output;

		/* call comments as needed */

		if (ARTICLE)
		{
			/* comments replace */

			if ($comments == 1 && COMMENTS_REPLACE == 1)
			{
				hook('comments_replace');
			}

			/* else native comments */

			else if ($comments > 0)
			{
				$route = build_route('articles', ARTICLE);
				comments(ARTICLE, $route);

				/* comment form */

				if ($comments == 1 || (COMMENTS_NEW == 1 && $comments == 3))
				{
					comment_form(ARTICLE, $language, $access);
				}
			}
		}
	}

	/* call pagination as needed */

	if ($sub_maximum > 1 && s('pagination') == 1)
	{
		$route = build_route('categories', CATEGORY);
		pagination($sub_active, $sub_maximum, $route);
	}
	hook(__FUNCTION__ . '_end');
}

/**
 * extras
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Contents
 * @author Henry Ruhs
 *
 * @param integer|string $filter
 */

function extras($filter = '')
{
	if ($filter == '')
	{
		hook(__FUNCTION__ . '_start');
	}

	/* query extras */

	$query = 'SELECT id, title, text, category, article, headline, access FROM ' . PREFIX . 'extras WHERE (language = \'' . LANGUAGE . '\' || language = \'\')';
	if (is_numeric($filter))
	{
		$query .= ' && rank = ' . $filter;
	}
	else if ($filter)
	{
		$query .= ' && alias = \'' . $filter . '\'';
	}
	else
	{
		$query .= ' && status = 1';
	}
	$query .= ' ORDER BY rank';
	$result = mysql_query($query);

	/* collect output */

	if ($result)
	{
		while ($r = mysql_fetch_assoc($result))
		{
			$access = $r['access'];
			$check_access = check_access($access, MY_GROUPS);

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

				/* show if cagegory or article matched */

				if ($category == CATEGORY || $article == ARTICLE || ($category == 0 && $article == 0))
				{
					/* registry and parser object */

					$registry = Redaxscript_Registry::getInstance();
					$parser = new Redaxscript_Parser($registry, $text, $route);

					/* collect headline output */

					$output .= hook('extra_start');
					if ($headline == 1)
					{
						$output .= '<h3 class="title_extra">' . $title . '</h3>';
					}

					/* collect box output */

					$output .= '<div class="box_extra">' . $parser->getOutput() . '</div>' . hook('extra_end');

					/* prepend admin dock */

					if (LOGGED_IN == TOKEN && FIRST_PARAMETER != 'logout')
					{
						$output .= admin_dock('extras', $id);
					}
				}
			}
		}
	}
	echo $output;
	if ($filter == '')
	{
		hook(__FUNCTION__ . '_end');
	}
}

/**
 * infoline
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Contents
 * @author Henry Ruhs
 *
 * @param string $table
 * @param integer $id
 * @param string $author
 * @param string $date
 * @return string
 */

function infoline($table = '', $id = '', $author = '', $date = '')
{
	hook(__FUNCTION__ . '_start');
	$time = date(s('time'), strtotime($date));
	$date = date(s('date'), strtotime($date));
	if ($table == 'articles')
	{
		$comments_total = query_total('comments', 'article', $id);
	}

	/* collect output */

	$output = '<div class="box_infoline box_infoline_' . $table . '">';

	/* collect author output */

	if ($table == 'articles')
	{
		$output .= '<span class="infoline_posted_by">' . l('posted_by') . ' ' . $author . '</span>';
		$output .= '<span class="infoline_on"> ' . l('on') . ' </span>';
	}

	/* collect date and time output */

	$output .= '<span class="infoline_date">' . $date . '</span>';
	$output .= '<span class="infoline_at"> ' . l('at') . ' </span>';
	$output .= '<span class="infoline_time">' . $time . '</span>';

	/* collect comment output */

	if ($comments_total)
	{
		$output .= '<span class="divider">' . s('divider') . '</span><span class="infoline_total">' . $comments_total . ' ';
		if ($comments_total == 1)
		{
			$output .= l('comment');
		}
		else
		{
			$output .= l('comments');
		}
		$output .= '</span>';
	}
	$output .= '</div>';
	$output .= hook(__FUNCTION__ . '_end');
	return $output;
}

/**
 * pagination
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Contents
 * @author Henry Ruhs
 *
 * @param integer $sub_active
 * @param integer $sub_maximum
 * @param string $route
 */

function pagination($sub_active = '', $sub_maximum = '', $route = '')
{
	hook(__FUNCTION__ . '_start');
	$output .= '<ul class="list_pagination">';

	/* collect first and previous output */

	if ($sub_active > 1)
	{
		$first_route = $route;
		$previous_route = $route . '/' . ($sub_active - 1);
		$output .= '<li class="item_first">' . anchor_element('internal', '', '', l('first'), $first_route) . '</li>';
		$output .= '<li class="item_previous">' . anchor_element('internal', '', '', l('previous'), $previous_route, '', 'rel="previous"') . '</li>';
	}

	/* collect center output */

	$j = 2;
	if ($sub_active == 2 || $sub_active == $sub_maximum - 1)
	{
		$j++;
	}
	if ($sub_active == 1 || $sub_active == $sub_maximum)
	{
		$j = $j + 2;
	}
	for ($i = $sub_active - $j; $i < $sub_active + $j; $i++)
	{
		if ($i == $sub_active)
		{
			$j++;
			$output .= '<li class="item_number item_active"><span>' . $i . '</span></li>';
		}
		else if ($i > 0 && $i < $sub_maximum + 1)
		{
			$output .= '<li class="item_number">' . anchor_element('internal', '', '', $i, $route . '/' . $i) . '</li>';
		}
	}

	/* collect next and last output */

	if ($sub_active < $sub_maximum)
	{
		$next_route = $route . '/' . ($sub_active + 1);
		$last_route = $route . '/' . $sub_maximum;
		$output .= '<li class="item_next">' . anchor_element('internal', '', '', l('next'), $next_route, '', 'rel="next"') . '</li>';
		$output .= '<li class="item_last">' . anchor_element('internal', '', '', l('last'), $last_route) . '</li>';
	}
	$output .= '</ul>';
	echo $output;
	hook(__FUNCTION__ . '_end');
}

/**
 * notification
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Contents
 * @author Henry Ruhs
 *
 * @param string $title
 * @param string $text
 * @param string $action
 * @param string $route
 */

function notification($title = '', $text = '', $action = '', $route = '')
{
	hook(__FUNCTION__ . '_start');

	/* detect needed mode */

	if (LOGGED_IN == TOKEN && FIRST_PARAMETER == 'admin')
	{
		$suffix = '_admin';
	}
	else
	{
		$suffix = '_default';
	}

	/* collect output */

	if ($title)
	{
		$output = '<h2 class="title_content title_notification">' . $title . '</h2>';
	}
	$output .= '<div class="box_content box_notification">';

	/* collect text output */

	if ($text)
	{
		$output .= '<p class="text_notification">' . $text . l('point') . '</p>';
	}

	/* collect button output */

	if ($action && $route)
	{
		$output .= anchor_element('internal', '', 'js_forward_notification button' . $suffix, $action, $route);
	}
	$output .= '</div>';
	echo $output;
	hook(__FUNCTION__ . '_end');
}