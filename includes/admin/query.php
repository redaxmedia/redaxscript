<?php

/* admin process */

function admin_process()
{
	/* clean post */

	switch (TABLE_PARAMETER)
	{
		/* categories */

		case 'categories':
			$parent = $r['parent'] = clean($_POST['parent'], 0);

		/* articles */

		case 'articles':
			$r['keywords'] = clean($_POST['keywords'], 1);
			$r['template'] = clean($_POST['template'], 0);

		/* extras */

		case 'extras':
			$title = $r['title'] = clean($_POST['title'], 1);
			if (TABLE_PARAMETER != 'categories')
			{
				$r['headline'] = clean($_POST['headline'], 0);
			}

		/* comments */

		case 'comments':
			if (TABLE_PARAMETER == 'comments')
			{
				$r['url'] = clean($_POST['url'], 4);
			}
			$author = $r['author'] = clean($_POST['author'], 0);
			if (TABLE_PARAMETER != 'categories')
			{
				$text = $r['text'] = clean($_POST['text'], 1);
				$date = $_POST['year'] . '-' . $_POST['month'] . '-' . $_POST['day'] . ' ' . $_POST['hour'] . ':' . $_POST['minute'] . ':00';
				$date = $r['date'] = clean($date, 1);
			}
			$rank = $r['rank'] = clean($_POST['rank'], 0);

		/* groups */

		case 'groups';
			if (TABLE_PARAMETER != 'comments')
			{
				$alias = $r['alias'] = clean($_POST['alias'], 2);
			}

		/* users */

		case 'users':
			if (TABLE_PARAMETER != 'groups')
			{
				$language = $r['language'] = clean($_POST['language'], 0);
			}

		/* modules */

		case 'modules';
			$status = $r['status'] = clean($_POST['status'], 0);
			if (TABLE_PARAMETER != 'groups' && TABLE_PARAMETER != 'users' && GROUPS_EDIT == 1)
			{
				$access = array_map('clean_special', $_POST['access']);
				$access = array_map('clean_mysql', $access);
				$access_string = implode(', ', $access);
				if ($access_string == '')
				{
					$access_string = 0;
				}
				$access = $r['access'] = $access_string;
			}
			if (TABLE_PARAMETER != 'extras' && TABLE_PARAMETER != 'comments')
			{
				$r['description'] = clean($_POST['description'], 1);
			}
			$token = $_POST['token'];
			break;
	}

	/* clean contents post */

	if (TABLE_PARAMETER == 'articles')
	{
		$r['infoline'] = clean($_POST['infoline'], 0);
		$comments = $r['comments'] = clean($_POST['comments'], 0);
		if ($category && ID_PARAMETER == '')
		{
			$status = $r['status'] = retrieve('status', 'categories', 'id', $category);
		}
	}
	if (TABLE_PARAMETER == 'articles' || TABLE_PARAMETER == 'extras')
	{
		$category = $r['category'] = clean($_POST['category'], 0);
	}
	if (TABLE_PARAMETER == 'articles' || TABLE_PARAMETER == 'extras' || TABLE_PARAMETER == 'comments')
	{
		if ($date > NOW)
		{
			$status = $r['status'] = 2;
		}
		else
		{
			$date = $r['date'] = NOW;
		}
	}
	if (TABLE_PARAMETER == 'extras' || TABLE_PARAMETER == 'comments')
	{
		$article = $r['article'] = clean($_POST['article'], 0);
	}
	if (TABLE_PARAMETER == 'comments' && ID_PARAMETER == '')
	{
		$status = $r['status'] = retrieve('status', 'articles', 'id', $article);
	}
	if (TABLE_PARAMETER == 'comments' || TABLE_PARAMETER == 'users')
	{
		$email = $r['email'] = clean($_POST['email'], 3);
	}

	/* clean groups post */

	if (TABLE_PARAMETER == 'groups' && (ID_PARAMETER == '' || ID_PARAMETER > 1))
	{
		$groups_array = array(
			'categories',
			'articles',
			'extras',
			'comments',
			'groups',
			'users',
			'modules'
		);
		foreach ($groups_array as $value)
		{
			$$value = array_map('clean_special', $_POST[$value]);
			$$value = array_map('clean_mysql', $$value);
			$groups_string = implode(', ', $$value);
			if ($groups_string == '')
			{
				$groups_string = 0;
			}
			$r[$value] = $groups_string;
		}
		$r['settings'] = clean($_POST['settings'], 0);
		$r['filter'] = clean($_POST['filter'], 0);
	}
	if ((TABLE_PARAMETER == 'groups' || TABLE_PARAMETER == 'users') && ID_PARAMETER == 1)
	{
		$status = $r['status'] = 1;
	}
	if (TABLE_PARAMETER == 'groups' || TABLE_PARAMETER == 'users' || TABLE_PARAMETER == 'modules')
	{
		$name = $r['name'] = clean($_POST['name'], 0);
	}

	/* clean users post */

	if (TABLE_PARAMETER == 'users')
	{
		$user = $r['user'] = clean($_POST['user'], 0);
		$password_check = $password_confirm = 1;
		if ($_POST['edit'] && $_POST['password'] == '' && $_POST['password_confirm'] == '' || $_POST['delete'])
		{
			$password_check = 0;
		}
		if ($_POST['password'] != $_POST['password_confirm'])
		{
			$password_confirm = 0;
		}
		$password = clean($_POST['password'], 0);
		if ($password_check == 1 && $password_confirm == 1)
		{
			$r['password'] = sha1($password) . SALT;
		}
		if ($_POST['new'])
		{
			$r['first'] = $r['last'] = NOW;
		}
		if (ID_PARAMETER == '' || ID_PARAMETER > 1)
		{
			$groups = array_map('clean_special', $_POST['groups']);
			$groups = array_map('clean_mysql', $groups);
			$groups_string = implode(', ', $groups);
			if ($groups_string == '')
			{
				$groups_string = 0;
			}
			$groups = $r['groups'] = $groups_string;
		}
	}
	$last = end(array_keys($r));

	/* validate post */

	switch (TABLE_PARAMETER)
	{
		/* contents */

		case 'categories':
		case 'articles':
		case 'extras':
			if ($title == '')
			{
				$error = l('title_empty');
			}
			else
			{
				$title_id = retrieve('title', TABLE_PARAMETER, 'id', ID_PARAMETER);
				$id_title = retrieve('id', TABLE_PARAMETER, 'title', $title);
			}
			if ($id_title && strcasecmp($title_id, $title) < 0)
			{
				$error = l('title_exists');
			}
			if (TABLE_PARAMETER == 'categories')
			{
				$opponent_id = retrieve('id', 'articles', 'alias', $alias);
			}
			if (TABLE_PARAMETER == 'articles')
			{
				$opponent_id = retrieve('id', 'categories', 'alias', $alias);
			}
			if ($opponent_id)
			{
				$error = l('alias_exists');
			}
			if (TABLE_PARAMETER != 'groups' && check_alias($alias, 0) == 1 || check_alias($alias, 1) == 1)
			{
				$error = l('alias_incorrect');
			}

		/* groups */

		case 'groups':
			if ($alias == '')
			{
				$error = l('alias_empty');
			}
			else
			{
				$alias_id = retrieve('alias', TABLE_PARAMETER, 'id', ID_PARAMETER);
				$id_alias = retrieve('id', TABLE_PARAMETER, 'alias', $alias);
			}
			if ($id_alias && strcasecmp($alias_id, $alias) < 0)
			{
				$error = l('alias_exists');
			}
	}

	/* validate general post */

	switch (TABLE_PARAMETER)
	{
		case 'articles':
		case 'extras':
		case 'comments':
			if ($text == '')
			{
				$error = l('text_empty');
			}
			break;
		case 'groups':
		case 'users':
		case 'modules':
			if ($name == '')
			{
				$error = l('name_empty');
			}
			break;
	}

	/* validate users post */

	if (TABLE_PARAMETER == 'users')
	{
		if ($user == '')
		{
			$error = l('user_incorrect');
		}
		else
		{
			$user_id = retrieve('user', TABLE_PARAMETER, 'id', ID_PARAMETER);
			$id_user = retrieve('id', TABLE_PARAMETER, 'user', $user);
		}
		if ($id_user && strcasecmp($user_id, $user) < 0)
		{
			$error = l('user_exists');
		}
		if (check_login($user) == 0)
		{
			$error = l('user_incorrect');
		}
		if ($password_check == 1)
		{
			if ($password == '')
			{
				$error = l('password_empty');
			}
			if ($password_confirm == 0 || check_login($password) == 0)
			{
				$error = l('password_incorrect');
			}
		}
	}

	/* validate last post */

	switch (TABLE_PARAMETER)
	{
		case 'comments':
			if ($author == '')
			{
				$error = l('author_empty');
			}
		case 'users':
			if (check_email($email) == 0)
			{
				$error = l('email_incorrect');
			}
	}
	$string = 'admin';

	/* handle error */

	if ($error)
	{
		if (ID_PARAMETER == '')
		{
			$string .= '/new/' . TABLE_PARAMETER;
		}
		else
		{
			$string .= '/edit/' . TABLE_PARAMETER . '/' . ID_PARAMETER;
		}
		notification(l('error_occurred'), $error, l('back'), $string);
		return;
		break;
	}

	/* handle success */

	else
	{
		$edit = constant(strtoupper(TABLE_PARAMETER) . '_EDIT');
		$delete = constant(strtoupper(TABLE_PARAMETER) . '_DELETE');
		if ($edit == 1 || $delete == 1)
		{
			$string .= '/view/' . TABLE_PARAMETER;
		}
	}

	/* process */

	switch (true)
	{
		/* query new */

		case $_POST['new']:
			foreach ($r as $key => $value)
			{
				$key_string .= $key;
				$value_string .= '\'' . $value . '\'';
				if ($last != $key)
				{
					$key_string .= ', ';
					$value_string .= ', ';
				}
			}
			$general_insert_query = 'INSERT INTO ' . PREFIX . TABLE_PARAMETER . ' (' . $key_string . ') VALUES (' . $value_string . ')';
			mysql_query($general_insert_query);
			notification(l('operation_completed'), '', l('continue'), $string);
			return;
			break;

		/* query edit */

		case $_POST['edit']:
			foreach ($r as $key => $value)
			{
				$set_string .= $key . ' = \'' . $value . '\'';
				if ($last != $key)
				{
					$set_string .= ', ';
				}
			}
			$general_update_query = 'UPDATE ' . PREFIX . TABLE_PARAMETER . ' SET ' . $set_string . ' WHERE id = ' . ID_PARAMETER . ' LIMIT 1';

			/* categories */

			if (TABLE_PARAMETER == 'categories')
			{
				$categories_string = admin_children('categories', ID_PARAMETER, 0);
				$categories_children_string = admin_children('categories', ID_PARAMETER, 2);
				$categories_update_query = 'UPDATE ' . PREFIX . 'categories SET status = ' . $status . ', access = \'' . $access . '\' WHERE id IN (' . $categories_string . ')';
				$articles_update_query = 'UPDATE ' . PREFIX . 'articles SET status = ' . $status . ', access = \'' . $access . '\' WHERE category IN (' . $categories_string . ')';
				$comments_update_query = 'UPDATE ' . PREFIX . 'comments SET status = ' . $status . ', access = \'' . $access . '\' WHERE article IN (' . $categories_children_string . ')';
				mysql_query($categories_update_query);
				mysql_query($articles_update_query);
			}

			/* articles */

			if (TABLE_PARAMETER == 'articles')
			{
				if ($comments == 0)
				{
					$status = 0;
				}
				$comments_update_query = 'UPDATE ' . PREFIX . 'comments SET status = ' . $status . ', access = \'' . $access . '\' WHERE article = ' . ID_PARAMETER;
			}

			/* general */

			mysql_query($general_update_query);
			if ($comments_update_query)
			{
				mysql_query($comments_update_query);
			}
			if (USERS_EXCEPTION == 1)
			{
				$_SESSION[ROOT . '/my_name'] = $name;
				$_SESSION[ROOT . '/my_email'] = $email;
				if (file_exists('languages/' . $language . '.php'))
				{
					$_SESSION[ROOT . '/language'] = $language;
					$_SESSION[ROOT . '/language_selected'] = 1;
				}
			}
			notification(l('operation_completed'), '', l('continue'), $string);
			return;
			break;
	}
}

/* admin move */

function admin_move($input = '')
{
	/* retrieve rank */

	$rank_asc = query_plumb('rank', TABLE_PARAMETER, 'min');
	$rank_desc = query_plumb('rank', TABLE_PARAMETER, 'max');
	$rank_old = retrieve('rank', TABLE_PARAMETER, 'id', ID_PARAMETER);

	/* calculate new rank */

	$rank_new = 1;
	if ($rank_old > $rank_asc && $input == 'up')
	{
		$rank_new = $rank_old - 1;
	}
	if ($rank_old < $rank_desc && $input == 'down')
	{
		$rank_new = $rank_old + 1;
	}
	$id = retrieve('id', TABLE_PARAMETER, 'rank', $rank_new);

	/* query rank */

	$rank_old_update_query = 'UPDATE ' . PREFIX . TABLE_PARAMETER . ' SET rank = ' . $rank_old . ' WHERE id = ' . $id;
	$rank_new_update_query = 'UPDATE ' . PREFIX . TABLE_PARAMETER . ' SET rank = ' . $rank_new . ' WHERE id = ' . ID_PARAMETER;
	mysql_query($rank_old_update_query);
	mysql_query($rank_new_update_query);
	notification(l('operation_completed'), '', l('continue'), 'admin/view/' . TABLE_PARAMETER);
}

/* admin status */

function admin_status($input = '')
{
	$general_update_query = 'UPDATE ' . PREFIX . TABLE_PARAMETER . ' SET status = ' . $input . ' WHERE id = ' . ID_PARAMETER;

	/* query categories status */

	if (TABLE_PARAMETER == 'categories')
	{
		$categories_string = admin_children('categories', ID_PARAMETER, 0);
		$categories_children_string = admin_children('categories', ID_PARAMETER, 2);
		$categories_update_query = 'UPDATE ' . PREFIX . 'categories SET status = ' . $input . ' WHERE id IN (' . $categories_string . ')';
		$articles_update_query = 'UPDATE ' . PREFIX . 'articles SET status = ' . $input . ' WHERE category IN (' . $categories_string . ')';
		$comments_update_query = 'UPDATE ' . PREFIX . 'comments SET status = ' . $input . ' WHERE article IN (' . $categories_children_string . ')';
		mysql_query($categories_update_query);
		mysql_query($articles_update_query);
	}

	/* query articles status */

	if (TABLE_PARAMETER == 'articles')
	{
		$comments_update_query = 'UPDATE ' . PREFIX . 'comments SET status = ' . $input . ' WHERE article = ' . ID_PARAMETER;
	}

	/* query general status */

	mysql_query($general_update_query);
	if ($comments_update_query)
	{
		mysql_query($comments_update_query);
	}
	notification(l('operation_completed'), '', l('continue'), 'admin/view/' . TABLE_PARAMETER);
}

/* admin install */

function admin_install($input = '')
{
	if (TABLE_PARAMETER == 'modules')
	{
		/* install module */

		if (file_exists('modules/' . ALIAS_PARAMETER . '/install.php'))
		{
			$module = retrieve('id', 'modules', 'alias', ALIAS_PARAMETER);
			if (($input == 'install' && $module == '') || ($input == 'uninstall' && $module))
			{
				include_once('modules/' . ALIAS_PARAMETER . '/install.php');
				call_user_func(ALIAS_PARAMETER . '_' . $input);
			}
		}
	}
	notification(l('operation_completed'), '', l('continue'), 'admin/view/' . TABLE_PARAMETER);
}

/* admin delete */

function admin_delete()
{
	/* query general */

	$general_delete_query = 'DELETE FROM ' . PREFIX . TABLE_PARAMETER . ' WHERE id = ' . ID_PARAMETER . ' LIMIT 1';
	if (TABLE_PARAMETER == 'categories' || TABLE_PARAMETER == 'articles' || TABLE_PARAMETER == 'extras' || TABLE_PARAMETER == 'comments')
	{
		$rank_desc = query_plumb('rank', TABLE_PARAMETER, 'max');
		$rank_old = retrieve('rank', TABLE_PARAMETER, 'id', ID_PARAMETER);
		if ($rank_old > 1 && $rank_old < $rank_desc)
		{
			for ($rank_old; $rank_old - 1 < $rank_desc; $rank_old++)
			{
				$general_update_query = 'UPDATE ' . PREFIX . TABLE_PARAMETER . ' SET rank = ' . ($rank_old - 1) . ' WHERE rank = ' . $rank_old;
				mysql_query($general_update_query);
			}
		}
	}

	/* query categories */

	if (TABLE_PARAMETER == 'categories')
	{
		$categories_string = admin_children('categories', ID_PARAMETER, 0);
		$categories_children_string = admin_children('categories', ID_PARAMETER, 2);
		$categories_delete_query = 'DELETE FROM ' . PREFIX . 'categories WHERE id IN (' . $categories_string . ')';
		$articles_delete_query = 'DELETE FROM ' . PREFIX . 'articles WHERE category IN (' . $categories_string . ')';
		$extras_update_query = 'UPDATE ' . PREFIX . '.extras SET category = 0 WHERE category IN (' . $categories_string . ')';
		$comments_delete_query = 'DELETE FROM ' . PREFIX . 'comments WHERE article IN (' . $categories_children_string . ')';
		mysql_query($categories_delete_query);
		mysql_query($articles_delete_query);
	}

	/* query articles */

	if (TABLE_PARAMETER == 'articles')
	{
		$extras_update_query = 'UPDATE ' . PREFIX . 'extras SET article = 0 WHERE article = ' . ID_PARAMETER;
		$comments_delete_query = 'DELETE FROM ' . PREFIX . 'comments WHERE article = ' . ID_PARAMETER;
		if (ID_PARAMETER == s('homepage'))
		{
			$homepage_update_query = 'UPDATE ' . PREFIX . 'settings SET value = 0 WHERE name = \'homepage\' LIMIT 1';
			mysql_query($homepage_update_query);
		}
	}

	/* query general */

	mysql_query($general_delete_query);
	if ($extras_update_query)
	{
		mysql_query($extras_update_query);
	}
	if ($comments_delete_query)
	{
		mysql_query($comments_delete_query);
	}

	/* handle exception */

	if (USERS_EXCEPTION == 1)
	{
		logout();
	}

	/* handle success */

	else
	{
		$string = 'admin';		$edit = constant(strtoupper(TABLE_PARAMETER) . '_EDIT');
		$delete = constant(strtoupper(TABLE_PARAMETER) . '_DELETE');
		if ($edit == 1 || $delete == 1)
		{
			$string .= '/view/' . TABLE_PARAMETER;
		}
		notification(l('operation_completed'), '', l('continue'), $string);
	}
}

/* admin update */

function admin_update()
{
	if (TABLE_PARAMETER == 'settings')
	{
		/* clean post */

		$r['language'] = clean($_POST['language'], 0);
		$r['template'] = clean($_POST['template'], 0);
		$r['title'] = clean($_POST['title'], 1);
		$r['author'] = clean($_POST['author'], 0);
		$r['copyright'] = clean($_POST['copyright'], 1);
		$r['description'] = clean($_POST['description'], 1);
		$r['keywords'] = clean($_POST['keywords'], 1);
		$r['robots'] = clean($_POST['robots'], 0);
		$r['email'] = clean($_POST['email'], 3);
		$r['subject'] = clean($_POST['subject'], 1);
		$r['notification'] = clean($_POST['notification'], 0);
		$r['charset'] = clean($_POST['charset'], 1) == '' ? 'utf-8' : clean($_POST['charset'], 1);
		$r['divider'] = clean($_POST['divider'], 1);
		$r['time'] = clean($_POST['time'], 1);
		$r['date'] = clean($_POST['date'], 1);
		$r['homepage'] = clean($_POST['homepage'], 0);
		$r['limit'] = clean($_POST['limit'], 0) == '' ? 5 : clean($_POST['limit'], 0);
		$r['order'] = clean($_POST['order'], 0);
		$r['pagination'] = clean($_POST['pagination'], 0);
		$r['moderation'] = clean($_POST['moderation'], 0);
		$r['registration'] = clean($_POST['registration'], 0);
		$r['verification'] = clean($_POST['verification'], 0);
		$r['reminder'] = clean($_POST['reminder'], 0);
		$r['captcha'] = clean($_POST['captcha'], 0);
		$r['blocker'] = clean($_POST['blocker'], 0);

		/* update settings */

		foreach ($r as $key => $value)
		{
			$query = 'UPDATE ' . PREFIX . 'settings SET value = \'' . $value . '\' WHERE name = \'' . $key . '\' LIMIT 1';
			mysql_query($query);
		}
		notification(l('operation_completed'), '', l('continue'), 'admin/edit/settings');
	}
}

/* admin children */

function admin_children($table = '', $id = '', $mode = '')
{
	if ($table == 'categories')
	{
		$categories_query = 'SELECT id FROM ' . PREFIX . 'categories WHERE parent = ' . $id;
		$categories_result = mysql_query($categories_query);
		$categories_num_rows = mysql_fetch_assoc($categories_result);
		if ($categories_result)
		{
			$i = 0;
			while ($c = mysql_fetch_assoc($categories_result))
			{
				$categories_children_string .= $c['id'];
				if ($i++ > $categories_num_rows)
				{
					$categories_children_string .= ', ';
				}
			}
		}
		$categories_string = $id;
		if ($categories_children_string)
		{
			$categories_string .= ', ' . $categories_children_string;
		}

		/* mode zero */

		if ($mode == 0)
		{
			$output = $categories_string;
		}

		/* mode one */

		if ($mode == 1)
		{
			$output = $categories_children_string;
		}

		/* mode two */

		if ($mode == 2)
		{
			$articles_query = 'SELECT id FROM ' . PREFIX . 'articles WHERE category IN (' . $categories_string . ')';
			$articles_result = mysql_query($articles_query);
			$articles_num_rows = mysql_num_rows($articles_result);
			if ($articles_result)
			{
				while ($a = mysql_fetch_assoc($articles_result))
				{
					$categories_children_string .= $a['id'];
					if ($i++ > $articles_num_rows)
					{
						$categories_children_string .= ', ';
					}
				}
			}
			$output = $categories_children_string;
		}
		return $output;
	}
}

/* admin last update */

function admin_last_update()
{
	$query = 'UPDATE ' . PREFIX . 'users SET last = \'' . NOW . '\' WHERE id = ' . MY_ID;
	mysql_query($query);
}
?>