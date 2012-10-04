<?php

/* contents */

function contents()
{
	hook(__FUNCTION__ . '_start');

	/* if empty full string */

	if (FULL_STRING == '' || check_alias(FULL_STRING, 1) == 1)
	{
		/* check for homepage */

		if (s('homepage') > 0)
		{
			$table = 'articles';
			$id = $article = s('homepage');
		}

		/* else check for category */

		else
		{
			$table = 'categories';
			$id = $category = 0;

			/* check order settings */

			if (s('order') == 'asc')
			{
				$function = 'min';
			}
			else if (s('order') == 'desc')
			{
				$function = 'max';
			}
			$rank = query_plumb('rank', 'categories', $function);

			/* if category is published */

			if ($rank)
			{
				$status = retrieve('status', 'categories', 'rank', $rank);
				if ($status == 1)
				{
					$id = $category = retrieve('id', 'categories', 'rank', $rank);
				}
			}
		}
	}

	/* if last table */

	else if (LAST_TABLE)
	{
		$table = LAST_TABLE;
		$id = retrieve('id', LAST_TABLE, 'alias', LAST_PARAMETER);
		if (LAST_TABLE == 'categories')
		{
			$category = $id;
		}
		else if (LAST_TABLE == 'articles')
		{
			$article = $id;
		}
	}

	/* query related to type */

	$query = 'SELECT id, title, author, text, language, date, headline, infoline, comments, access FROM ' . PREFIX . 'articles WHERE status = 1';
	if ($article)
	{
		$query .= ' && id = ' . $article;
	}
	else if ($category > -1)
	{
		$query .= ' && (language = \'' . LANGUAGE . '\' || language = \'\') && category = ' . $category . ' ORDER BY rank ' . s('order');
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
	$active_string = build_string($table, $id);

	/* handle error */

	if (DB_CONNECTED == 0)
	{
		$error = l('database_failed');
	}
	else if ($category > -1 && $num_rows == '')
	{
		$error = l('article_no');
	}
	else if ($result == '' || $num_rows_active == '' || FULL_STRING && $active_string != FULL_TOP_STRING && check_alias(FULL_STRING, 1) == 0)
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
				if (LAST_TABLE == 'categories' || FULL_STRING == '' || check_alias(FULL_STRING, 1) == 1)
				{
					$string = build_string('articles', $id);
					$position_break = strpos($text, '<break>');
				}

				/* collect headline output */

				$output .= hook('article_start');
				if ($headline == 1)
				{
					$output .= '<h2 class="title_content">';
					if (LAST_TABLE == 'categories' || FULL_STRING == '' || check_alias(FULL_STRING, 1) == 1)
					{
						$output .= anchor_element('internal', '', '', $title, $string);
					}
					else
					{
						$output .= $title;
					}
					$output .= '</h2>';
				}

				/* collect box output */

				$output .= '<div class="box_content">' . parser($text);
				if ($position_break > -1)
				{
					$output .= anchor_element('internal', '', 'link_read_more', l('read_more'), $string);
				}
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

		if ($article)
		{
			if ($comments > 0)
			{
				comments($article, $active_string);
			}

			/* comment form */

			if ($comments == 1 || (COMMENTS_NEW == 1 && $comments == 3))
			{
				comment_form($article, $language, $access);
			}
		}
	}

	/* call pagination as needed */

	if ($sub_maximum > 1 && s('pagination') == 1)
	{
		pagination($sub_active, $sub_maximum, $active_string);
	}
	hook(__FUNCTION__ . '_end');
}

/* extras */

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
		if (LAST_TABLE)
		{
			$active = retrieve('id', LAST_TABLE, 'alias', LAST_PARAMETER);
		}
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
				if ($category == $active || $article == $active || ($category == 0 && $article == 0))
				{
					/* collect headline output */

					if ($headline == 1)
					{
						$output .= '<h3 class="title_extra">' . $title . '</h3>';
					}

					/* collect box output */

					$output .= '<div class="box_extra">' . parser($text) . '</div>';

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

/* infoline */

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
	if ($table == 'articles')
	{
		$output .= '<span class="infoline_posted_by">' . l('posted_by') . ' ' . $author . '</span>';
		$output .= '<span class="infoline_on"> ' . l('on') . ' </span>';
	}
	$output .= '<span class="infoline_date">' . $date . '</span>';
	$output .= '<span class="infoline_at"> ' . l('at') . ' </span>';
	$output .= '<span class="infoline_time">' . $time . '</span>';
	if ($comments_total)
	{
		$output .= '<span class="divider">' . s('divider') . '</span>' . '<span class="infoline_total">' . $comments_total . ' ';
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
	return $output;
	hook(__FUNCTION__ . '_end');
}

/* pagination */

function pagination($sub_active = '', $sub_maximum = '', $string = '')
{
	hook(__FUNCTION__ . '_start');
	$output .= '<ul class="list_pagination">';

	/* collect first and previous output */

	if ($sub_active > 1)
	{
		$first_string = $string;
		$previous_string = $string . '/' . ($sub_active - 1);
		$output .= '<li class="item_first">' . anchor_element('internal', '', '', l('first'), $first_string) . '</li>';
		$output .= '<li class="item_previous">' . anchor_element('internal', '', '', l('previous'), $previous_string, '', 'rel="previous"') . '</li>';
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
			$output .= '<li class="item_number">' . anchor_element('internal', '', '', $i, $string . '/' . $i) . '</li>';
		}
	}

	/* collect next and last output */

	if ($sub_active < $sub_maximum)
	{
		$next_string = $string . '/' . ($sub_active + 1);
		$last_string = $string . '/' . $sub_maximum;
		$output .= '<li class="item_next">' . anchor_element('internal', '', '', l('next'), $next_string, '', 'rel="next"') . '</li>';
		$output .= '<li class="item_last">' . anchor_element('internal', '', '', l('last'), $last_string) . '</li>';
	}
	$output .= '</ul>';
	echo $output;
	hook(__FUNCTION__ . '_end');
}

/* notification */

function notification($title = '', $text = '', $action = '', $string = '')
{
	hook(__FUNCTION__ . '_start');

	/* handle suffix */

	if (FIRST_PARAMETER == 'admin')
	{
		$suffix = '_admin';
	}

	/* collect output */

	if ($title)
	{
		$output = '<h2 class="title_content title_notification">' . $title . '</h2>';
	}
	$output .= '<div class="box_content box_notification' . $suffix . '">';
	if ($text)
	{
		$output .= '<p class="text_notification">' . $text . l('point') . '</p>';
	}
	if ($action && $string)
	{
		/* handle protocol */

		if (check_protocol($string) == '')
		{
			$string = REWRITE_STRING . $string;
		}
		$output .= '<a class="js_forward_notification field_button' . $suffix . '" href="' . $string . '"><span><span>' . $action . '</span></span></a>';
	}
	$output .= '</div>';
	echo $output;
	hook(__FUNCTION__ . '_end');
}
?>