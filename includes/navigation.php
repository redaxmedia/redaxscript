<?php

/* navigation list */

function navigation_list($table = '', $options = '')
{
	hook(__FUNCTION__ . '_start');

	/* setup option variables */

	if ($options)
	{
		foreach ($options as $key => $value)
		{
			$key = 'option_' . $key;
			$$key = $value;
		}
	}

	/* switch table */

	switch ($table)
	{
		case 'categories':
			$single = 'category';
			break;
		case 'articles':
			$single = 'article';
			break;
		case 'comments':
			$single = 'comment';
			break;
	}

	/* query contents */

	$query = 'SELECT * FROM ' . PREFIX . $table . ' WHERE (language = \'' . LANGUAGE . '\' || language = \'\') && status = 1';

	/* parent categories only */

	if ($table == 'categories')
	{
		$query .= ' && parent = 0';
	}

	/* setup query filter */

	if ($table == 'categories' || $table == 'articles')
	{
		/* setup filter alias option */

		if ($option_filter_alias)
		{
			$query .= ' && alias IN (' . $option_filter_alias . ')';
		}

		/* setup filter rank option */

		if ($option_filter_rank)
		{
			$query .= ' && rank IN (' . $option_filter_rank . ')';
		}
	}

	/* setup order option */

	$query .= ' ORDER BY rank ';
	if ($option_order)
	{
		$query .= $option_order;
	}
	else
	{
		$query .= s('order');
	}

	/* setup limit option */

	$query .= ' LIMIT ';
	if ($option_limit)
	{
		$query .= $option_limit;
	}
	else
	{
		$query .= s('limit');
	}

	/* query result */

	$result = mysql_query($query);
	$num_rows = mysql_num_rows($result);
	if ($result == '' || $num_rows == '')
	{
		$error = l($single . '_no') . l('point');
	}
	else if ($result)
	{
		/* collect output */

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
				if (LAST_PARAMETER == $alias && $table != 'comments')
				{
					$class_string = ' class="item_active"';
				}
				else
				{
					$class_string = '';
				}
				if ($table == 'comments')
				{
					$description = $title = $author;
				}
				if ($description == '')
				{
					$description = $title;
				}
				if ($table == 'categories' || $table == 'articles' && $category == 0)
				{
					$string = $alias;
				}
				else
				{
					$string = build_string($table, $id);
				}

				/* collect children output */

				$output .= '<li' . $class_string . '>' . anchor_element('internal', '', '', $title, $string, $description);
				if ($table == 'categories')
				{
					$parent_string = $alias . '/';
					$output .= children_list('categories', $id, $parent_string, 2);
				}
				$output .= '</li>';
			}
			else
			{
				$counter++;
			}
		}

		/* handle access */

		if ($num_rows == $counter)
		{
			$error = l('access_no') . l('point');
		}
	}

	/* setup id option */

	if ($option_id)
	{
		$id_string = ' id="' . $option_id . '"';
	}

	/* setup class option */

	if ($option_class)
	{
		$class_string = ' class="' . $option_class . '"';
	}
	else
	{
		$class_string = ' class="list_' . $table . '"';
	}

	/* handle error */

	if ($error)
	{
		$output = '<ul' .$id_string . $class_string . '><li>' . $error . '</li></ul>';
	}

	/* else collect premature output */

	else if ($output)
	{
		$output = '<ul' .$id_string . $class_string . '>' . $output . '</ul>';
	}
	echo $output;
	hook(__FUNCTION__ . '_end');
}

/* children list */

function children_list($table = '', $parent = '', $string = '', $mode = '')
{
	/* query contents */

	$query = 'SELECT id, title, alias, description, access FROM ' . PREFIX . $table . ' WHERE (language = \'' . LANGUAGE . '\' || language = \'\') && status = 1';
	if ($table == 'categories')
	{
		$query .= ' && parent = ' . $parent;
	}
	else if ($table == 'articles')
	{
		$query .= ' && category = ' . $parent;
	}
	$query .= ' ORDER BY rank ASC';
	$result = mysql_query($query);
	$num_rows = mysql_num_rows($result);
	if ($result)
	{
		/* collect output */

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
				if (LAST_PARAMETER == $alias && $mode == 2)
				{
					$class_string = ' class="item_active"';
				}
				else
				{
					$class_string = '';
				}
				if ($description == '')
				{
					$description = $title;
				}

				/* collect children output */

				$output .= '<li' . $class_string . '>' . anchor_element('internal', '', '', $title, $string . $alias, $description);
				if ($table == 'categories' && $mode == 1)
				{
					$output .= children_list('articles', $id, $string . $alias . '/', $mode);
				}
				$output .= '</li>';
			}
			else
			{
				$counter++;
			}
		}
		if ($num_rows > $counter)
		{
			$output = '<ul class="list_children">' . $output . '</ul>';
		}
	}
	return $output;
}

/* languages list */

function languages_list($options = '')
{
	hook(__FUNCTION__ . '_start');

	/* setup option variables */

	if ($options)
	{
		foreach ($options as $key => $value)
		{
			$key = 'option_' . $key;
			$$key = $value;
		}
	}

	/* read languages directory */

	$languages_directory = read_directory('languages', 'misc.php');
	foreach ($languages_directory as $value)
	{
		$value = substr($value, 0, 2);
		$class_string = ' class="language_' . $value;
		if ($value == LANGUAGE)
		{
			$class_string .= ' item_active';
		}
		$class_string .= '"';
		$output .= '<li' . $class_string . '>' . anchor_element('internal', '', '', l($value), FULL_STRING . LANGUAGE_STRING . $value, '', 'nofollow') . '</li>';
	}

	/* setup id option */

	if ($option_id)
	{
		$id_string = ' id="' . $option_id . '"';
	}

	/* setup class option */

	if ($option_class)
	{
		$class_string = ' class="' . $option_class . '"';
	}
	else
	{
		$class_string = ' class="list_languages"';
	}

	/* collect premature output */

	if ($output)
	{
		$output = '<ul' .$id_string . $class_string . '>' . $output . '</ul>';
	}
	echo $output;
	hook(__FUNCTION__ . '_end');
}

/* templates list */

function templates_list($options = '')
{
	hook(__FUNCTION__ . '_start');

	/* setup option variables */

	if ($options)
	{
		foreach ($options as $key => $value)
		{
			$key = 'option_' . $key;
			$$key = $value;
		}
	}

	/* read templates directory */

	$templates_directory = read_directory('templates', array('admin', 'install'));
	foreach ($templates_directory as $value)
	{
		$class_string = ' class="template_' . $value;
		if ($value == TEMPLATE)
		{
			$class_string .= ' item_active';
		}
		$class_string .= '"';
		$output .= '<li' . $class_string . '>' . anchor_element('internal', '', '', $value, FULL_STRING . TEMPLATE_STRING . $value, '', 'nofollow') . '</li>';
	}

	/* setup id option */

	if ($option_id)
	{
		$id_string = ' id="' . $option_id . '"';
	}

	/* setup class option */

	if ($option_class)
	{
		$class_string = ' class="' . $option_class . '"';
	}
	else
	{
		$class_string = ' class="list_templates"';
	}

	/* collect premature output */

	if ($output)
	{
		$output = '<ul' .$id_string . $class_string . '>' . $output . '</ul>';
	}
	echo $output;
	hook(__FUNCTION__ . '_end');
}

/* login list */

function login_list()
{
	if (LOGGED_IN == TOKEN && FIRST_PARAMETER != 'logout')
	{
		$output = '<li class="item_logout">' . anchor_element('internal', '', '', l('logout'), 'logout', '', 'nofollow') . '</li>';
		$output .= '<li class="item_administration">' . anchor_element('internal', '', '', l('administration'), 'admin', '', 'nofollow') . '</li>';
	}
	else
	{
		$output = '<li class="item_login">' . anchor_element('internal', '', '', l('login'), 'login', '', 'nofollow') . '</li>';
		if (s('reminder') == 1)
		{
			$output .= '<li class="item_reminder">' . anchor_element('internal', '', '', l('reminder'), 'reminder', '', 'nofollow') . '</li>';
		}
		if (s('registration') == 1)
		{
			$output .= '<li class="item_registration">' . anchor_element('internal', '', '', l('registration'), 'registration', '', 'nofollow') . '</li>';
		}
	}
	$output = '<ul class="list_login">' . $output . '</ul>';
	echo $output;
}
?>