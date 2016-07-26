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
	$aliasFilter = new Redaxscript\Filter\Alias();
	$emailFilter = new Redaxscript\Filter\Email();
	$urlFilter = new Redaxscript\Filter\Url();
	$htmlFilter = new Redaxscript\Filter\Html();
	$aliasValidator = new Redaxscript\Validator\Alias();
	$loginValidator = new Redaxscript\Validator\Login();
	$specialFilter = new Redaxscript\Filter\Special;
	$messenger = new Redaxscript\Admin\Messenger(Redaxscript\Registry::getInstance());
	$filter = Redaxscript\Registry::get('filter');
	$tableParameter = Redaxscript\Registry::get('tableParameter');
	$idParameter = Redaxscript\Registry::get('idParameter');

	/* clean post */

	switch ($tableParameter)
	{
		/* categories */

		case 'categories':
			$parent = $r['parent'] = $specialFilter->sanitize($_POST['parent']);

		/* articles */

		case 'articles':
			$r['keywords'] = $_POST['keywords'];
			$r['template'] = $specialFilter->sanitize($_POST['template']);

		/* extras */

		case 'extras':
			$title = $r['title'] = $_POST['title'];
			if ($tableParameter != 'categories')
			{
				$r['headline'] = $specialFilter->sanitize($_POST['headline']);
			}
			$r['sibling'] = $specialFilter->sanitize($_POST['sibling']);
			$author = $r['author'] = Redaxscript\Registry::get('myUser');

		/* comments */

		case 'comments':
			if ($tableParameter == 'comments')
			{
				$r['url'] = $urlFilter->sanitize($_POST['url']);
				$author = $r['author'] = $_POST['author'];
			}
			if ($tableParameter != 'categories')
			{
				$text = $r['text'] = $filter ? $htmlFilter->sanitize($_POST['text']) : $_POST['text'];
				$date = $r['date'] = $_POST['date'];
			}
			$rank = $r['rank'] = $specialFilter->sanitize($_POST['rank']);

		/* groups */

		case 'groups';
			if ($tableParameter != 'comments')
			{
				$alias = $r['alias'] = $aliasFilter->sanitize($_POST['alias']);
			}

		/* users */

		case 'users':
			if ($tableParameter != 'groups')
			{
				$language = $r['language'] = $specialFilter->sanitize($_POST['language']);
			}

		/* modules */

		case 'modules';
			$alias = $aliasFilter->sanitize($_POST['alias']);
			$status = $r['status'] = $specialFilter->sanitize($_POST['status']);
			if ($tableParameter != 'groups' && $tableParameter != 'users' && Redaxscript\Registry::get('groupsEdit'))
			{
				$access = array_map(array($specialFilter, 'sanitize'), $_POST['access']);
				$access_string = implode(', ', $access);
				if (!$access_string)
				{
					$access_string = null;
				}
				$access = $r['access'] = $access_string;
			}
			if ($tableParameter != 'extras' && $tableParameter != 'comments')
			{
				$r['description'] = $_POST['description'];
			}
			$token = $_POST['token'];
			break;
	}

	/* clean contents post */

	if ($tableParameter == 'articles')
	{
		$r['infoline'] = $specialFilter->sanitize($_POST['infoline']);
		$comments = $r['comments'] = $specialFilter->sanitize($_POST['comments']);
		if ($category && !$idParameter)
		{
			$status = $r['status'] = Redaxscript\Db::forTablePrefix('categories')->where('id', $category)->findOne()->status;
		}
	}
	if ($tableParameter == 'articles' || $tableParameter == 'extras')
	{
		$category = $r['category'] = $specialFilter->sanitize($_POST['category']);
	}
	if ($tableParameter == 'articles' || $tableParameter == 'extras' || $tableParameter == 'comments')
	{
		if ($date > Redaxscript\Registry::get('now'))
		{
			$status = $r['status'] = 2;
		}
		if (!$date)
		{
			$r['date'] = Redaxscript\Registry::get('now');
		}
	}
	if ($tableParameter == 'extras' || $tableParameter == 'comments')
	{
		$article = $r['article'] = $specialFilter->sanitize($_POST['article']);
	}
	if ($tableParameter == 'comments' && !$idParameter)
	{
		$status = $r['status'] = Redaxscript\Db::forTablePrefix('articles')->where('id', $article)->findOne()->status;
	}
	if ($tableParameter == 'comments' || $tableParameter == 'users')
	{
		$email = $r['email'] = $emailFilter->sanitize($_POST['email']);
	}

	/* clean groups post */

	if ($tableParameter == 'groups' && (!$idParameter || $idParameter > 1))
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
			$groups_string = implode(', ', $$value);
			if (!$groups_string)
			{
				$groups_string = 0;
			}
			$r[$value] = $groups_string;
		}
		$r['settings'] = $specialFilter->sanitize($_POST['settings']);
		$r['filter'] =  $specialFilter->sanitize($_POST['filter']);
	}
	if (($tableParameter == 'groups' || $tableParameter == 'users') && $idParameter == 1)
	{
		$status = $r['status'] = 1;
	}
	if ($tableParameter == 'groups' || $tableParameter == 'users' || $tableParameter == 'modules')
	{
		$name = $r['name'] = $specialFilter->sanitize($_POST['name']);
	}

	/* clean users post */

	if ($tableParameter == 'users')
	{
		if ($_POST['user'])
		{
			$user = $r['user'] = $specialFilter->sanitize($_POST['user']);
		}
		else
		{
			$user = $r['user'] = Redaxscript\Db::forTablePrefix($tableParameter)->where('id', $idParameter)->findOne()->user;
		}
		$password_check = $password_confirm = 1;
		if ($_POST['edit'] && !$_POST['password'] && !$_POST['password_confirm'] || $_POST['delete'])
		{
			$password_check = 0;
		}
		if ($_POST['password'] != $_POST['password_confirm'])
		{
			$password_confirm = 0;
		}
		$password = $specialFilter->sanitize($_POST['password']);
		if ($password_check == 1 && $password_confirm == 1)
		{
			$passwordHash = new Redaxscript\Hash(Redaxscript\Config::getInstance());
			$passwordHash->init($password);
			$r['password'] = $passwordHash->getHash();
		}
		if ($_POST['new'])
		{
			$r['first'] = $r['last'] = Redaxscript\Registry::get('now');
		}
		if (!$idParameter || $idParameter > 1)
		{
			$groups = array_map(array(
					$specialFilter,
					'sanitize'
			), $_POST['groups']);
			$groups_string = implode(', ', $groups);
			if (!$groups_string)
			{
				$groups_string = 0;
			}
			$groups = $r['groups'] = $groups_string;
		}
	}
	$r_keys = array_keys($r);
	$last = end($r_keys);

	/* validate post */

	switch ($tableParameter)
	{
		/* contents */

		case 'categories':
		case 'articles':
		case 'extras':
			if (!$title)
			{
				$error = Redaxscript\Language::get('title_empty');
			}
			if ($tableParameter == 'categories')
			{
				$opponent_id = Redaxscript\Db::forTablePrefix('articles')->where('alias', $alias)->findOne()->id;
			}
			if ($tableParameter == 'articles')
			{
				$opponent_id = Redaxscript\Db::forTablePrefix('categories')->where('alias', $alias)->findOne()->id;
			}
			if ($opponent_id)
			{
				$error = Redaxscript\Language::get('alias_exists');
			}
			if ($tableParameter != 'groups' && $aliasValidator->validate($alias, Redaxscript\Validator\Alias::MODE_GENERAL) == Redaxscript\Validator\ValidatorInterface::PASSED || $aliasValidator->validate($alias, Redaxscript\Validator\Alias::MODE_DEFAULT) == Redaxscript\Validator\ValidatorInterface::PASSED)
			{
				$error = Redaxscript\Language::get('alias_incorrect');
			}

		/* groups */

		case 'groups':
			if (!$alias)
			{
				$error = Redaxscript\Language::get('alias_empty');
			}
			else
			{
				$alias_id = Redaxscript\Db::forTablePrefix($tableParameter)->where('id', $idParameter)->findOne()->alias;
				$id_alias = Redaxscript\Db::forTablePrefix($tableParameter)->where('alias', $alias)->findOne()->id;
			}
			if ($id_alias && strcasecmp($alias_id, $alias) < 0)
			{
				$error = Redaxscript\Language::get('alias_exists');
			}
	}

	/* validate general post */

	switch ($tableParameter)
	{
		case 'articles':
		case 'extras':
		case 'comments':
			if (!$text)
			{
				$error = Redaxscript\Language::get('text_empty');
			}
			break;
		case 'groups':
		case 'users':
		case 'modules':
			if (!$name)
			{
				$error = Redaxscript\Language::get('name_empty');
			}
			break;
	}

	/* validate users post */

	if ($tableParameter == 'users')
	{
		if (!$user)
		{
			$error = Redaxscript\Language::get('user_incorrect');
		}
		else
		{
			$user_id = Redaxscript\Db::forTablePrefix($tableParameter)->where('id', $idParameter)->findOne()->user;
			$id_user = Redaxscript\Db::forTablePrefix($tableParameter)->where('user', $user)->findOne()->id;
		}
		if ($id_user && strcasecmp($user_id, $user) < 0)
		{
			$error = Redaxscript\Language::get('user_exists');
		}
		if ($loginValidator->validate($user) == Redaxscript\Validator\ValidatorInterface::FAILED)
		{
			$error = Redaxscript\Language::get('user_incorrect');
		}
		if ($password_check == 1)
		{
			if (!$password)
			{
				$error = Redaxscript\Language::get('password_empty');
			}
			if ($password_confirm == 0 || $loginValidator->validate($password) == Redaxscript\Validator\ValidatorInterface::FAILED)
			{
				$error = Redaxscript\Language::get('password_incorrect');
			}
		}
	}

	/* validate last post */

	$emailValidator = new Redaxscript\Validator\Email();
	switch ($tableParameter)
	{
		case 'comments':
			if (!$author)
			{
				$error = Redaxscript\Language::get('author_empty');
			}
		case 'users':
			if ($emailValidator->validate($email) == Redaxscript\Validator\ValidatorInterface::FAILED)
			{
				$error = Redaxscript\Language::get('email_incorrect');
			}
	}
	$route = 'admin';

	/* handle error */

	if ($error)
	{
		if (!$idParameter)
		{
			$route .= '/new/' . $tableParameter;
		}
		else
		{
			$route .= '/edit/' . $tableParameter . '/' . $idParameter;
		}

		/* show error */

		echo $messenger->setRoute(Redaxscript\Language::get('back'), $route)->error($error, Redaxscript\Language::get('error_occurred'));
		return;
	}

	/* handle success */

	else
	{
		if (Redaxscript\Registry::get('tableEdit') == 1 || Redaxscript\Registry::get('tableEdit') == 1)
		{
			$route .= '/view/' . $tableParameter;
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

	/* empty and select to null */

	foreach ($r as $key => $value)
	{
		if (!$value || $value == 'select')
		{
			$r[$key] = null;
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

			/* show success */

			echo $messenger->setRoute(Redaxscript\Language::get('continue'), $route)->doRedirect()->success(Redaxscript\Language::get('operation_completed'));

			return;

		/* query edit */

		case $_POST['edit']:
			Redaxscript\Db::forTablePrefix(Redaxscript\Registry::get('tableParameter'))
				->whereIdIs(Redaxscript\Registry::get('idParameter'))
				->findOne()
				->set($r)
				->save();

			/* query categories */

			if ($tableParameter == 'categories')
			{
				$categoryChildren = Redaxscript\Db::forTablePrefix($tableParameter)->where('parent', $idParameter);
				$categoryArray = array_merge($categoryChildren->findFlatArray(), array(
					$idParameter
				));
				$articleChildren = Redaxscript\Db::forTablePrefix('articles')->whereIn('category', $categoryArray);
				$articleArray = $articleChildren->findFlatArray();
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

			if ($tableParameter == 'articles')
			{
				if ($comments == 0)
				{
					$status = 0;
				}
				Redaxscript\Db::forTablePrefix('comments')
					->where('article', $idParameter)
					->findMany()
					->set(array(
						'status' => $status,
						'access' => $access
					))
					->save();
			}

			if ($tableParameter == 'users' && $idParameter == Redaxscript\Registry::get('myId'))
			{
				$auth = new Redaxscript\Auth(Redaxscript\Request::getInstance());
				$auth->init();
				$auth->setUser('name', $name);
				$auth->setUser('email', $email);
				$auth->setUser('language', $language);
				$auth->save();
				Redaxscript\Request::setSession('language', $language);
			}

			/* show success */

			echo $messenger->setRoute(Redaxscript\Language::get('continue'), $route)->doRedirect()->success(Redaxscript\Language::get('operation_completed'));
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
	$adminParameter = Redaxscript\Registry::get('adminParameter');
	$tableParameter = Redaxscript\Registry::get('tableParameter');
	$idParameter = Redaxscript\Registry::get('idParameter');

	/* retrieve rank */

	$rank_asc = Redaxscript\Db::forTablePrefix($tableParameter)->min('rank');
	$rank_desc = Redaxscript\Db::forTablePrefix($tableParameter)->max('rank');
	$rank_old = Redaxscript\Db::forTablePrefix($tableParameter)->where('id', $idParameter)->findOne()->rank;

	/* calculate new rank */

	$rank_new = 1;
	if ($adminParameter == 'up' && $rank_old > $rank_asc)
	{
		$rank_new = $rank_old - 1;
	}
	if ($adminParameter == 'down' && $rank_old < $rank_desc)
	{
		$rank_new = $rank_old + 1;
	}
	$id = Redaxscript\Db::forTablePrefix($tableParameter)->where('rank', $rank_new)->findOne()->id;

	/* query rank */

	Redaxscript\Db::forTablePrefix($tableParameter)->where('id', $id)->findOne()->set('rank', $rank_old)->save();
	Redaxscript\Db::forTablePrefix($tableParameter)->where('id', $idParameter)->findOne()->set('rank', $rank_new)->save();

	/* show success */

	$messenger = new Redaxscript\Admin\Messenger(Redaxscript\Registry::getInstance());
	echo $messenger->setRoute(Redaxscript\Language::get('continue'), 'admin/view/' . $tableParameter)->doRedirect()->success(Redaxscript\Language::get('operation_completed'));
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
	$tableParameter = Redaxscript\Registry::get('tableParameter');
	if ($tableParameter == 'categories' || $tableParameter == 'articles' || $tableParameter == 'extras' || $tableParameter == 'comments')
	{
		/* query general select */

		$result = Redaxscript\Db::forTablePrefix($tableParameter)->orderByAsc('rank')->findArray();

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
				if ($tableParameter == 'articles')
				{
					$parent = $category;
				}
				if ($tableParameter == 'comments')
				{
					$parent = $article;
				}
				if ($parent)
				{
					$select_array[$parent][$id] = null;
				}
				else
				{
					$select_array[][$id] = null;
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
			Redaxscript\Db::forTablePrefix($tableParameter)
				->where('id', $value)
				->findOne()
				->set('rank', ++$key)
				->save();
		}
	}

	/* show success */

	$messenger = new Redaxscript\Admin\Messenger(Redaxscript\Registry::getInstance());
	echo $messenger->setRoute(Redaxscript\Language::get('continue'), 'admin/view/' . $tableParameter)->doRedirect()->success(Redaxscript\Language::get('operation_completed'));
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

function admin_status($input)
{
	$tableParameter = Redaxscript\Registry::get('tableParameter');
	$idParameter = Redaxscript\Registry::get('idParameter');
	Redaxscript\Db::forTablePrefix($tableParameter)
		->where('id', $idParameter)
		->findMany()
		->set('status', $input)
		->save();

	/* query categories */

	if ($tableParameter == 'categories')
	{
		$categoryChildren = Redaxscript\Db::forTablePrefix($tableParameter)->where('parent', $idParameter);
		$categoryArray = array_merge($categoryChildren->findFlatArray(), array(
			$idParameter
		));
		$articleChildren = Redaxscript\Db::forTablePrefix('articles')->whereIn('category', $categoryArray);
		$articleArray = $articleChildren->findFlatArray();
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

	if ($tableParameter == 'articles')
	{
		Redaxscript\Db::forTablePrefix('comments')
			->where('article', $idParameter)
			->findMany()
			->set('status', $input)
			->save();
	}

	/* show success */

	$messenger = new Redaxscript\Admin\Messenger(Redaxscript\Registry::getInstance());
	echo $messenger->setRoute(Redaxscript\Language::get('continue'), 'admin/view/' . $tableParameter)->doRedirect()->success(Redaxscript\Language::get('operation_completed'));
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
	$adminParameter = Redaxscript\Registry::get('adminParameter');
	$tableParameter = Redaxscript\Registry::get('tableParameter');
	$aliasParameter = Redaxscript\Registry::get('aliasParameter');
	if ($tableParameter == 'modules')
	{
		/* install module */

		if (is_dir('modules/' . $aliasParameter))
		{
			$module = Redaxscript\Db::forTablePrefix('modules')->where('alias', $aliasParameter)->findOne()->id;
			if (($adminParameter == 'install' && !$module) || ($adminParameter == 'uninstall' && $module))
			{
				$object = 'Redaxscript\Modules\\' . $aliasParameter . '\\' . $aliasParameter;

				/* method exists */

				if (method_exists($object, $adminParameter))
				{
					call_user_func(array($object, $adminParameter));
				}
			}
		}
	}

	/* show success */

	$messenger = new Redaxscript\Admin\Messenger(Redaxscript\Registry::getInstance());
	echo $messenger->setRoute(Redaxscript\Language::get('continue'), 'admin/view/' . $tableParameter . '#' . $aliasParameter)->doRedirect()->success(Redaxscript\Language::get('operation_completed'));
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
	$tableParameter = Redaxscript\Registry::get('tableParameter');
	$idParameter = Redaxscript\Registry::get('idParameter');
	if ($tableParameter == 'categories' || $tableParameter == 'articles' || $tableParameter == 'extras' || $tableParameter == 'comments' || $tableParameter == 'groups' || $tableParameter == 'users')
	{
		Redaxscript\Db::forTablePrefix($tableParameter)
			->where('id', $idParameter)
			->findMany()
			->delete();
	}

	/* query categories */

	if ($tableParameter == 'categories')
	{
		$categoryChildren = Redaxscript\Db::forTablePrefix($tableParameter)->where('parent', $idParameter);
		$categoryArray = array_merge($categoryChildren->findFlatArray(), array(
			$idParameter
		));
		$articleChildren = Redaxscript\Db::forTablePrefix('articles')->whereIn('category', $categoryArray);
		$articleArray = $articleChildren->findFlatArray();
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

	if ($tableParameter == 'articles')
	{
		Redaxscript\Db::forTablePrefix('comments')
			->where('article', $idParameter)
			->findMany()
			->delete();

		/* reset extras */

		Redaxscript\Db::forTablePrefix('extras')
			->where('article', $idParameter)
			->findMany()
			->set('article', 0)
			->save();

		/* reset homepage */

		if ($idParameter == Redaxscript\Db::getSetting('homepage'))
		{
			Redaxscript\Db::forTablePrefix('settings')
				->where('name', 'homepage')
				->findOne()
				->set('value', 0)
				->save();
		}
	}

	/* handle exception */

	if ($tableParameter == 'users' && $idParameter == Redaxscript\Registry::get('myId'))
	{
		$logoutController = new Redaxscript\Controller\Logout(Redaxscript\Registry::getInstance(), Redaxscript\Language::getInstance(), Redaxscript\Request::getInstance());
		echo $logoutController->process();
	}

	/* handle success */

	else
	{
		$route = 'admin';
		if (Redaxscript\Registry::get('tableEdit') == 1 || Redaxscript\Registry::get('tableEdit') == 1)
		{
			$route .= '/view/' . $tableParameter;
		}

		/* show success */

		$messenger = new Redaxscript\Admin\Messenger(Redaxscript\Registry::getInstance());
		echo $messenger->setRoute(Redaxscript\Language::get('continue'), $route)->doRedirect()->success(Redaxscript\Language::get('operation_completed'));
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
	$tableParameter = Redaxscript\Registry::get('tableParameter');
	if ($tableParameter == 'settings')
	{
		$specialFilter = new Redaxscript\Filter\Special();
		$emailFilter = new Redaxscript\Filter\Email();

		/* clean post */

		$r['language'] = $specialFilter->sanitize($_POST['language']);
		$r['template'] = $specialFilter->sanitize($_POST['template']);
		$r['title'] = $_POST['title'];
		$r['author'] = $_POST['author'];
		$r['copyright'] = $_POST['copyright'];
		$r['description'] = $_POST['description'];
		$r['keywords'] = $_POST['keywords'];
		$r['robots'] = $specialFilter->sanitize($_POST['robots']);
		$r['email'] = $emailFilter->sanitize($_POST['email']);
		$r['subject'] = $_POST['subject'];
		$r['notification'] = $specialFilter->sanitize($_POST['notification']);
		$r['charset'] = !$r['charset'] ? 'utf-8' : $r['charset'];
		$r['divider'] = $_POST['divider'];
		$r['time'] = $_POST['time'];
		$r['date'] =  $_POST['date'];
		$r['homepage'] = $specialFilter->sanitize($_POST['homepage']);
		$r['limit'] = !$specialFilter->sanitize($_POST['limit']) ? 10 : $specialFilter->sanitize($_POST['limit']);
		$r['order'] = $specialFilter->sanitize($_POST['order']);
		$r['pagination'] = $specialFilter->sanitize($_POST['pagination']);
		$r['moderation'] = $specialFilter->sanitize($_POST['moderation']);
		$r['registration'] = $specialFilter->sanitize($_POST['registration']);
		$r['verification'] = $specialFilter->sanitize($_POST['verification']);
		$r['recovery'] = $specialFilter->sanitize($_POST['recovery']);
		$r['captcha'] = $specialFilter->sanitize($_POST['captcha']);

		/* update settings */

		foreach ($r as $key => $value)
		{
			Redaxscript\Db::forTablePrefix($tableParameter)
				->where('name', $key)
				->findOne()
				->set('value', $value)
				->save();
		}

		/* show success */

		$messenger = new Redaxscript\Admin\Messenger(Redaxscript\Registry::getInstance());
		echo $messenger->setRoute(Redaxscript\Language::get('continue'), 'admin/edit/settings')->doRedirect()->success(Redaxscript\Language::get('operation_completed'));
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
	if (Redaxscript\Registry::get('myId'))
	{
		Redaxscript\Db::forTablePrefix('users')
			->where('id', Redaxscript\Registry::get('myId'))
			->findOne()
			->set('last', Redaxscript\Registry::get('now'))
			->save();
	}
}
