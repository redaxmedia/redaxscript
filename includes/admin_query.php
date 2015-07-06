<?php

/**
 * admin process
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 */

function admin_process()
{
	$aliasValidator = new Redaxscript\Validator\Alias();
	$loginValidator = new Redaxscript\Validator\Login();
	$specialFilter = new Redaxscript\Filter\Special;

	/* clean post */

	switch (TABLE_PARAMETER)
	{
		/* categories */

		case 'categories':
			$parent = $r['parent'] = clean($_POST['parent'], 0);

		/* articles */

		case 'articles':
			$r['keywords'] = clean($_POST['keywords'], 5);
			$r['template'] = clean($_POST['template'], 0);
			$r['sibling'] = clean($_POST['sibling'], 0);

		/* extras */

		case 'extras':
			$title = $r['title'] = clean($_POST['title'], 5);
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
				$date = $r['date'] = clean($date, 5);
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
			$alias = clean($_POST['alias'], 2);
			$status = $r['status'] = clean($_POST['status'], 0);
			if (TABLE_PARAMETER != 'groups' && TABLE_PARAMETER != 'users' && GROUPS_EDIT == 1)
			{
				$access = array_map(array($specialFilter, 'sanitize'), $_POST['access']);
				$access = array_map('clean', $access);
				$access_string = implode(', ', $access);
				if ($access_string == '')
				{
					$access_string = null;
				}
				$access = $r['access'] = $access_string;
			}
			if (TABLE_PARAMETER != 'extras' && TABLE_PARAMETER != 'comments')
			{
				$r['description'] = clean($_POST['description'], 5);
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
			$status = $r['status'] = Redaxscript\Db::forTablePrefix('categories')->where('id', $category)->findOne()->status;
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
		$status = $r['status'] = Redaxscript\Db::forTablePrefix('articles')->where('id', $article)->findOne()->status;
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
			$$value = array_map(array($specialFilter, 'sanitize'), $_POST[$value]);
			$$value = array_map('clean', $$value);
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
		if ($_POST['user'])
		{
			$user = $r['user'] = clean($_POST['user'], 0);
		}
		else
		{
			$user = $r['user'] = Redaxscript\Db::forTablePrefix(TABLE_PARAMETER)->where('id', ID_PARAMETER)->findOne()->user;
		}
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
			$groups = array_map(array($specialFilter, 'sanitize'), $_POST['groups']);
			$groups = array_map('clean', $groups);
			$groups_string = implode(', ', $groups);
			if ($groups_string == '')
			{
				$groups_string = 0;
			}
			$groups = $r['groups'] = $groups_string;
		}
	}
	$r_keys = array_keys($r);
	$last = end($r_keys);

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
				$title_id = Redaxscript\Db::forTablePrefix(TABLE_PARAMETER)->where('id', ID_PARAMETER)->findOne()->title;
				$id_title = Redaxscript\Db::forTablePrefix(TABLE_PARAMETER)->where('title', $title)->findOne()->id;
			}
			if ($id_title && strcasecmp($title_id, $title) < 0)
			{
				$error = l('title_exists');
			}
			if (TABLE_PARAMETER == 'categories')
			{
				$opponent_id = Redaxscript\Db::forTablePrefix('articles')->where('alias', $alias)->findOne()->id;
			}
			if (TABLE_PARAMETER == 'articles')
			{
				$opponent_id = Redaxscript\Db::forTablePrefix('categories')->where('alias', $alias)->findOne()->id;
			}
			if ($opponent_id)
			{
				$error = l('alias_exists');
			}
			if (TABLE_PARAMETER != 'groups' && $aliasValidator->validate($alias, Redaxscript\Validator\Alias::MODE_GENERAL) == Redaxscript\Validator\ValidatorInterface::PASSED || $aliasValidator->validate($alias, Redaxscript\Validator\Alias::MODE_DEFAULT) == Redaxscript\Validator\ValidatorInterface::PASSED)
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
				$alias_id = Redaxscript\Db::forTablePrefix(TABLE_PARAMETER)->where('id', ID_PARAMETER)->findOne()->alias;
				$id_alias = Redaxscript\Db::forTablePrefix(TABLE_PARAMETER)->where('alias', $alias)->findOne()->id;
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
			$user_id = Redaxscript\Db::forTablePrefix(TABLE_PARAMETER)->where('id', ID_PARAMETER)->findOne()->user;
			$id_user = Redaxscript\Db::forTablePrefix(TABLE_PARAMETER)->where('user', $user)->findOne()->id;
		}
		if ($id_user && strcasecmp($user_id, $user) < 0)
		{
			$error = l('user_exists');
		}
		if ($loginValidator->validate($user) == Redaxscript\Validator\ValidatorInterface::FAILED)
		{
			$error = l('user_incorrect');
		}
		if ($password_check == 1)
		{
			if ($password == '')
			{
				$error = l('password_empty');
			}
			if ($password_confirm == 0 || $loginValidator->validate($password) == Redaxscript\Validator\ValidatorInterface::FAILED)
			{
				$error = l('password_incorrect');
			}
		}
	}

	/* validate last post */

	$emailValidator = new Redaxscript\Validator\Email();
	switch (TABLE_PARAMETER)
	{
		case 'comments':
			if ($author == '')
			{
				$error = l('author_empty');
			}
		case 'users':
			if ($emailValidator->validate($email) == Redaxscript\Validator\ValidatorInterface::FAILED)
			{
				$error = l('email_incorrect');
			}
	}
	$route = 'admin';

	/* handle error */

	if ($error)
	{
		if (ID_PARAMETER == '')
		{
			$route .= '/new/' . TABLE_PARAMETER;
		}
		else
		{
			$route .= '/edit/' . TABLE_PARAMETER . '/' . ID_PARAMETER;
		}
		notification(l('error_occurred'), $error, l('back'), $route);
		return;
	}

	/* handle success */

	else
	{
		if (TABLE_EDIT == 1 || TABLE_DELETE == 1)
		{
			$route .= '/view/' . TABLE_PARAMETER;
			if ($alias)
			{
				$route .= '#' . $alias;
			}
			else if ($user)
			{
				$route .= '#' . $user;
			}
		}
	}

	/* process */

	switch (true)
	{
		/* query new */

		case $_POST['new']:
			Redaxscript\Db::forTablePrefix(Redaxscript\Registry::get('tableParameter'))
				->create()
				->set($r)
				->save();
			notification(l('operation_completed'), '', l('continue'), $route);
			return;

		/* query edit */

		case $_POST['edit']:
			Redaxscript\Db::forTablePrefix(Redaxscript\Registry::get('tableParameter'))
				->whereIdIs(Redaxscript\Registry::get('idParameter'))
				->findOne()
				->set($r)
				->save();

			/* query categories */

			if (TABLE_PARAMETER == 'categories')
			{
				$categoryChildren = Redaxscript\Db::forTablePrefix(TABLE_PARAMETER)->where('parent', ID_PARAMETER);
				$categoryArray = array_merge($categoryChildren->findArrayFlat(), array(
					ID_PARAMETER
				));
				$articleChildren = Redaxscript\Db::forTablePrefix('articles')->whereIn('category', $categoryArray);
				$articleArray = $articleChildren->findArrayFlat();
				if (count($articleArray) > 0)
				{
					Redaxscript\Db::forTablePrefix('comments')
						->whereIn('article', $articleArray)
						->findMany()
						->set(array(
							'status' => $status,
							'access' => $access
						))
						->save();
				}
				$categoryChildren
					->findMany()
					->set(array(
						'status' => $status,
						'access' => $access
					))
					->save();
				$articleChildren
					->findMany()
					->set(array(
						'status' => $status,
						'access' => $access
					))
					->save();
			}

			/* query articles */

			if (TABLE_PARAMETER == 'articles')
			{
				if ($comments == 0)
				{
					$status = 0;
				}
				Redaxscript\Db::forTablePrefix('comments')
					->where('article', ID_PARAMETER)
					->findMany()
					->set(array(
						'status' => $status,
						'access' => $access
					))
					->save();
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
			notification(l('operation_completed'), '', l('continue'), $route);
			return;
	}
}

/**
 * admin move
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 */

function admin_move()
{
	/* retrieve rank */

	$rank_asc = Redaxscript\Db::forTablePrefix(TABLE_PARAMETER)->min('rank');
	$rank_desc = Redaxscript\Db::forTablePrefix(TABLE_PARAMETER)->max('rank');
	$rank_old = Redaxscript\Db::forTablePrefix(TABLE_PARAMETER)->where('id', ID_PARAMETER)->findOne()->rank;

	/* calculate new rank */

	$rank_new = 1;
	if (ADMIN_PARAMETER == 'up' && $rank_old > $rank_asc)
	{
		$rank_new = $rank_old - 1;
	}
	if (ADMIN_PARAMETER == 'down' && $rank_old < $rank_desc)
	{
		$rank_new = $rank_old + 1;
	}
	$id = Redaxscript\Db::forTablePrefix(TABLE_PARAMETER)->where('rank', $rank_new)->findOne()->id;

	/* query rank */

	Redaxscript\Db::forTablePrefix(TABLE_PARAMETER)->where('id', $id)->findOne()->set('rank', $rank_old)->save();
	Redaxscript\Db::forTablePrefix(TABLE_PARAMETER)->where('id', ID_PARAMETER)->findOne()->set('rank', $rank_new)->save();
	notification(l('operation_completed'), '', l('continue'), 'admin/view/' . TABLE_PARAMETER);
}

/**
 * admin sort
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 */

function admin_sort()
{
	if (TABLE_PARAMETER == 'categories' || TABLE_PARAMETER == 'articles' || TABLE_PARAMETER == 'extras' || TABLE_PARAMETER == 'comments')
	{
		/* query general select */

		$result = Redaxscript\Db::forTablePrefix(TABLE_PARAMETER)->orderByAsc('rank')->findArray();

		/* build select array */

		if ($result)
		{
			foreach ($result as $r)
			{
				if ($r)
				{
					foreach ($r as $key => $value)
					{
						$$key = stripslashes($value);
					}
				}
				if (TABLE_PARAMETER == 'articles')
				{
					$parent = $category;
				}
				if (TABLE_PARAMETER == 'comments')
				{
					$parent = $article;
				}
				if ($parent)
				{
					$select_array[$parent][$id] = '';
				}
				else
				{
					$select_array[][$id] = '';
				}
			}
		}

		/* build update array */

		foreach ($select_array as $key => $value)
		{
			if (is_array($value))
			{
				foreach ($value as $key_sub => $value_sub)
				{
					$update_array[] = $key_sub;
				}
			}
			else
			{
				$update_array[] = $key;
			}
		}

		/* query general update */

		foreach ($update_array as $key => $value)
		{
			Redaxscript\Db::forTablePrefix(TABLE_PARAMETER)
				->where('id', $value)
				->findOne()
				->set('rank', ++$key)
				->save();
		}
	}
	notification(l('operation_completed'), '', l('continue'), 'admin/view/' . TABLE_PARAMETER);
}

/**
 * admin status
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 *
 * @param string $input
 */

function admin_status($input = '')
{
	Redaxscript\Db::forTablePrefix(TABLE_PARAMETER)
		->where('id', ID_PARAMETER)
		->findMany()
		->set('status', $input)
		->save();

	/* query categories */

	if (TABLE_PARAMETER == 'categories')
	{
		$categoryChildren = Redaxscript\Db::forTablePrefix(TABLE_PARAMETER)->where('parent', ID_PARAMETER);
		$categoryArray = array_merge($categoryChildren->findArrayFlat(), array(
			ID_PARAMETER
		));
		$articleChildren = Redaxscript\Db::forTablePrefix('articles')->whereIn('category', $categoryArray);
		$articleArray = $articleChildren->findArrayFlat();
		if (count($articleArray) > 0)
		{
			Redaxscript\Db::forTablePrefix('comments')
				->whereIn('article', $articleArray)
				->findMany()
				->set('status', $input)
				->save();
		}
		$categoryChildren->findMany()->set('status', $input)->save();
		$articleChildren->findMany()->set('status', $input)->save();
	}

	/* query articles */

	if (TABLE_PARAMETER == 'articles')
	{
		Redaxscript\Db::forTablePrefix('comments')
			->where('article', ID_PARAMETER)
			->findMany()
			->set('status', $input)
			->save();
	}
	notification(l('operation_completed'), '', l('continue'), 'admin/view/' . TABLE_PARAMETER);
}

/**
 * admin install
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 */

function admin_install()
{
	if (TABLE_PARAMETER == 'modules')
	{
		/* install module */

		if (is_dir('modules/' . ALIAS_PARAMETER))
		{
			$module = Redaxscript\Db::forTablePrefix('modules')->where('alias', ALIAS_PARAMETER)->findOne()->id;
			if ((ADMIN_PARAMETER == 'install' && $module == '') || (ADMIN_PARAMETER == 'uninstall' && $module))
			{
				include_once('modules/' . ALIAS_PARAMETER . '/install.php');
				include_once('modules/' . ALIAS_PARAMETER . '/index.php');
				$function = ALIAS_PARAMETER . '_' . ADMIN_PARAMETER;
				$object = 'Redaxscript\Modules\\' . ALIAS_PARAMETER . '\\' . ALIAS_PARAMETER;
				$method = ADMIN_PARAMETER;

				/* method exists */

				if (method_exists($object, $method))
				{
					call_user_func(array($object, $method));
				}

				/* function exists */

				else if (function_exists($function))
				{
					call_user_func($function);
				}
			}
		}
	}
	notification(l('operation_completed'), '', l('continue'), 'admin/view/' . TABLE_PARAMETER . '#' . ALIAS_PARAMETER);
}

/**
 * admin delete
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 */

function admin_delete()
{
	if (TABLE_PARAMETER == 'categories' || TABLE_PARAMETER == 'articles' || TABLE_PARAMETER == 'extras' || TABLE_PARAMETER == 'comments' || TABLE_PARAMETER == 'groups' || TABLE_PARAMETER == 'users')
	{
		Redaxscript\Db::forTablePrefix(TABLE_PARAMETER)
			->where('id', ID_PARAMETER)
			->findMany()
			->delete();
	}

	/* query categories */

	if (TABLE_PARAMETER == 'categories')
	{
		$categoryChildren = Redaxscript\Db::forTablePrefix(TABLE_PARAMETER)->where('parent', ID_PARAMETER);
		$categoryArray = array_merge($categoryChildren->findArrayFlat(), array(
			ID_PARAMETER
		));
		$articleChildren = Redaxscript\Db::forTablePrefix('articles')->whereIn('category', $categoryArray);
		$articleArray = $articleChildren->findArrayFlat();
		if (count($articleArray) > 0)
		{
			Redaxscript\Db::forTablePrefix('comments')
				->whereIn('article', $articleArray)
				->findMany()
				->delete();
		}
		$categoryChildren->findMany()->delete();
		$articleChildren->findMany()->delete();

		/* reset extras */

		Redaxscript\Db::forTablePrefix('extras')
			->whereIn('category', $categoryArray)
			->findMany()
			->set('category', 0)
			->save();
	}

	/* query articles */

	if (TABLE_PARAMETER == 'articles')
	{
		Redaxscript\Db::forTablePrefix('comments')
			->where('article', ID_PARAMETER)
			->findMany()
			->delete();

		/* reset extras */

		Redaxscript\Db::forTablePrefix('extras')
			->where('article', ID_PARAMETER)
			->findMany()
			->set('article', 0)
			->save();

		/* reset homepage */

		if (ID_PARAMETER == s('homepage'))
		{
			Redaxscript\Db::forTablePrefix('settings')
				->where('name', 'homepage')
				->findOne()
				->set('value', 0)
				->save();
		}
	}

	/* handle exception */

	if (USERS_EXCEPTION == 1)
	{
		logout();
	}

	/* handle success */

	else
	{
		$route = 'admin';
		if (TABLE_EDIT == 1 || TABLE_DELETE == 1)
		{
			$route .= '/view/' . TABLE_PARAMETER;
		}
		notification(l('operation_completed'), '', l('continue'), $route);
	}
}

/**
 * admin update
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 */

function admin_update()
{
	if (TABLE_PARAMETER == 'settings')
	{
		/* clean post */

		$r['language'] = clean($_POST['language'], 0);
		$r['template'] = clean($_POST['template'], 0);
		$r['title'] = clean($_POST['title'], 5);
		$r['author'] = clean($_POST['author'], 5);
		$r['copyright'] = clean($_POST['copyright'], 5);
		$r['description'] = clean($_POST['description'], 5);
		$r['keywords'] = clean($_POST['keywords'], 5);
		$r['robots'] = clean($_POST['robots'], 0);
		$r['email'] = clean($_POST['email'], 3);
		$r['subject'] = clean($_POST['subject'], 5);
		$r['notification'] = clean($_POST['notification'], 0);
		$r['charset'] = clean($_POST['charset'], 5) == '' ? 'utf-8' : clean($_POST['charset'], 5);
		$r['divider'] = clean($_POST['divider'], 5);
		$r['time'] = clean($_POST['time'], 5);
		$r['date'] = clean($_POST['date'], 5);
		$r['homepage'] = clean($_POST['homepage'], 0);
		$r['limit'] = clean($_POST['limit'], 0) == '' ? 10 : clean($_POST['limit'], 0);
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
			Redaxscript\Db::forTablePrefix(TABLE_PARAMETER)
				->where('name', $key)
				->findOne()
				->set('value', $value)
				->save();
		}
		notification(l('operation_completed'), '', l('continue'), 'admin/edit/settings');
	}
}

/**
 * admin last update
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 */

function admin_last_update()
{
	if (MY_ID)
	{
		Redaxscript\Db::forTablePrefix('users')
			->where('id', MY_ID)
			->findOne()
			->set('last', NOW)
			->save();
	}
}