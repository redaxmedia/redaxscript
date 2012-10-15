<?php

/* navigation list */

function navigation_list($table = '', $options = '')
{
	hook(__FUNCTION__ . '_start');

	/* define option variables */

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
			$wording_single = 'category';
			$query_parent = 'parent';
			break;
		case 'articles':
			$wording_single = 'article';
			$query_parent = 'category';
			break;
		case 'comments':
			$wording_single = 'comment';
			$query_parent = 'article';
			break;
	}

	/* query contents */

	$query = 'SELECT * FROM ' . PREFIX . $table . ' WHERE (language = \'' . LANGUAGE . '\' || language = \'\') && status = 1';

	/* setup parent */

	if ($query_parent)
	{
		if ($option_parent)
		{
			$query .= ' && ' . $query_parent . ' = ' . $option_parent;
		}
		else if ($table == 'categories')
		{
			$query .= ' && ' . $query_parent . ' = 0';
		}
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
		$error = l($wording_single . '_no') . l('point');
	}
	else if ($result)
	{
		/* collect output */

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

				/* build class string */

				if (LAST_PARAMETER == $alias && $table != 'comments')
				{
					$class_string = ' class="item_active"';
				}
				else
				{
					$class_string = '';
				}

				/* prepare metadata */

				if ($table == 'comments')
				{
					$description = $title = truncate($author . l('colon') . ' ' . $text, 80, '...');
				}
				if ($description == '')
				{
					$description = $title;
				}

				/* build string */

				if ($table == 'categories' && $parent == 0 || $table == 'articles' && $category == 0)
				{
					$string = $alias;
				}
				else
				{
					$string = build_string($table, $id);
				}

				/* collect item output */

				$output .= '<li' . $class_string . '>' . anchor_element('internal', '', '', $title, $string, $description);

				/* collect children list output */

				if ($table == 'categories' && $option_children == 1)
				{
					ob_start();
					navigation_list($table, array(
						'parent' => $id,
						'class' => 'list_children'
					));
					$output .= ob_get_clean();
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

	if ($error && $option_parent == '')
	{
		$output = '<ul' .$id_string . $class_string . '><li>' . $error . '</li></ul>';
	}

	/* else collect list output */

	else if ($output)
	{
		$output = '<ul' .$id_string . $class_string . '>' . $output . '</ul>';
	}
	echo $output;
	hook(__FUNCTION__ . '_end');
}

/* languages list */

function languages_list($options = '')
{
	hook(__FUNCTION__ . '_start');

	/* define option variables */

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
		$output .= '<li' . $class_string . '>' . anchor_element('internal', '', '', l($value), FULL_STRING . LANGUAGE_STRING . $value, '', 'rel="nofollow"') . '</li>';
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

	/* collect list output */

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

	/* define option variables */

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
		$output .= '<li' . $class_string . '>' . anchor_element('internal', '', '', $value, FULL_STRING . TEMPLATE_STRING . $value, '', 'rel="nofollow"') . '</li>';
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

	/* collect list output */

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
		$output = '<li class="item_logout">' . anchor_element('internal', '', '', l('logout'), 'logout', '', 'rel="nofollow"') . '</li>';
		$output .= '<li class="item_administration">' . anchor_element('internal', '', '', l('administration'), 'admin', '', 'rel="nofollow"') . '</li>';
	}
	else
	{
		$output = '<li class="item_login">' . anchor_element('internal', '', '', l('login'), 'login', '', 'rel="nofollow"') . '</li>';
		if (s('reminder') == 1)
		{
			$output .= '<li class="item_reminder">' . anchor_element('internal', '', '', l('reminder'), 'reminder', '', 'rel="nofollow"') . '</li>';
		}
		if (s('registration') == 1)
		{
			$output .= '<li class="item_registration">' . anchor_element('internal', '', '', l('registration'), 'registration', '', 'rel="nofollow"') . '</li>';
		}
	}
	$output = '<ul class="list_login">' . $output . '</ul>';
	echo $output;
}
?>