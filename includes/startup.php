<?php

/**
 * startup
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Startup
 * @author Henry Ruhs
 */

function startup()
{
	/* ini set */

	if (function_exists('ini_set'))
	{
		if (error_reporting() == 0)
		{
			ini_set('display_startup_errors', 0);
			ini_set('display_errors', 0);
		}
		ini_set('session.use_trans_sid', 0);
		ini_set('url_rewriter.tags', 0);
		ini_set('mbstring.substitute_character', 0);
	}

	/* define general */

	$request = Redaxscript\Request::getInstance();
	$registry = Redaxscript\Registry::getInstance();
	$file = new Redaxscript\Server\File($request);
	$root = new Redaxscript\Server\Root($request);
	$host = new Redaxscript\Server\Host($request);
	$registry->set('file', $file->getOutput());
	$registry->set('root', $root->getOutput());
	$registry->set('host', $host->getOutput());

	/* session */

	session_start();

	/* prevent session hijacking */

	$request->refreshSession();
	if (!$request->getSession('regenerateId'))
	{
		session_regenerate_id();
		$request->setSession('regenerateId', true);
	}

	/* database status */

	$registry->set('dbStatus', Redaxscript\Db::getStatus());

	/* define token */

	$token = new Redaxscript\Server\Token($request);
	$auth = new Redaxscript\Auth($request);
	$registry->set('token', $token->getOutput());
	if ($auth->getStatus())
	{
		$registry->set('loggedIn', $token->getOutput());
	}

	/* setup charset */

	if (function_exists('ini_set') && $registry->get('dbStatus') === 2)
	{
		ini_set('default_charset', Redaxscript\Db::getSetting('charset'));
	}

	/* define status */

	$pdoDriverArray = PDO::getAvailableDrivers();
	$fallbackModuleArray =
	[
		'mod_deflate',
		'mod_headers',
		'mod_rewrite'
	];
	$apacheModuleArray = function_exists('apache_get_modules') ? apache_get_modules() : $fallbackModuleArray;
	$registry->set('phpOs', strtolower(php_uname('s')));
	$registry->set('phpVersion', phpversion());
	$registry->set('pdoDriverArray', $pdoDriverArray);
	$registry->set('apacheModuleArray', $apacheModuleArray);
	$registry->set('sessionStatus', session_status());

	/* define parameter */

	$parameter = new Redaxscript\Router\Parameter($request);
	$parameter->init();
	$registry->set('firstParameter', $parameter->getFirst());
	$registry->set('firstSubParameter', $parameter->getSub());
	$registry->set('secondParameter', $parameter->getSecond());
	$registry->set('secondSubParameter', $parameter->getSub());
	$registry->set('thirdParameter', $parameter->getThird());
	$registry->set('thirdSubParameter', $parameter->getSub());
	if ($registry->get('loggedIn') == $registry->get('token') && $registry->get('firstParameter') == 'admin')
	{
		$registry->set('adminParameter', $parameter->getAdmin());
		$registry->set('tableParameter', $parameter->getTable());
		$registry->set('idParameter', $parameter->getId());
		$registry->set('aliasParameter', $parameter->getAlias());
	}
	$registry->set('lastParameter', $parameter->getLast());
	$registry->set('lastSubParameter', $parameter->getSub());
	$registry->set('tokenParameter', $parameter->getToken());

	/* define routes */

	$resolver = new Redaxscript\Router\Resolver($request);
	$resolver->init();
	$registry->set('liteRoute', $resolver->getLite());
	$registry->set('fullRoute', $resolver->getFull());
	if (!in_array('mod_rewrite', $registry->get('apacheModuleArray')) || !file_exists('.htaccess') || $registry->get('file') == 'install.php')
	{
		$registry->set('parameterRoute', '?p=');
		$registry->set('languageRoute', '&amp;l=');
		$registry->set('templateRoute', '&amp;t=');
	}
	else
	{
		$registry->set('parameterRoute', '');
		$registry->set('languageRoute', '.');
		$registry->set('templateRoute', '.');
	}

	/* define tables */

	if ($registry->get('dbStatus') === 2)
	{
		if (!$registry->get('fullRoute') || ($registry->get('firstParameter') == 'admin' && !$registry->get('secondParameter')))
		{
			/* check for homepage */

			if (Redaxscript\Db::getSetting('homepage') > 0)
			{
				$table = 'articles';
				$id = Redaxscript\Db::getSetting('homepage');
			}

			/* else fallback */

			else
			{
				$table = 'categories';
				$id = 0;

				/* check order */

				if (Redaxscript\Db::getSetting('order') == 'asc')
				{
					$rank = Redaxscript\Db::forTablePrefix($table)->min('rank');
				}
				else if (Redaxscript\Db::getSetting('order') == 'desc')
				{
					$rank = Redaxscript\Db::forTablePrefix($table)->max('rank');
				}

				/* category is published */

				if ($rank)
				{
					$status = Redaxscript\Db::forTablePrefix($table)->where('rank', $rank)->findOne()->status;
					if ($status == 1)
					{
						$id = Redaxscript\Db::forTablePrefix($table)->where('rank', $rank)->findOne()->id;
					}
				}
			}
			$registry->set('firstTable', $table);
			$registry->set('lastTable', $table);
		}
		else
		{
			if ($registry->get('firstParameter'))
			{
				$registry->set('firstTable', query_table($registry->get('firstParameter')));
			}
			if ($registry->get('firstTable'))
			{
				$registry->set('secondTable', query_table($registry->get('secondParameter')));
			}
			if ($registry->get('secondTable'))
			{
				$registry->set('thirdTable', query_table($registry->get('thirdParameter')));
			}
			if ($registry->get('lastParameter'))
			{
				$registry->set('lastTable', query_table($registry->get('lastParameter')));
			}
			if ($registry->get('lastTable'))
			{
				$id = Redaxscript\Db::forTablePrefix($registry->get('lastTable'))->where('alias', $registry->get('lastParameter'))->findOne()->id;
			}
		}
	}

	/* define ids */

	$aliasValidator = new Redaxscript\Validator\Alias();
	if ($registry->get('firstParameter') === 'admin' || $aliasValidator->validate($registry->get('firstParameter'), Redaxscript\Validator\Alias::MODE_DEFAULT) == Redaxscript\Validator\ValidatorInterface::FAILED)
	{
		if ($registry->get('lastTable') == 'categories')
		{
			$registry->set('categoryId', $id);
			$registry->set('lastId', $id);
		}
		else if ($registry->get('lastTable') == 'articles')
		{
			$registry->set('articleId', $id);
			$registry->set('lastId', $id);
		}
	}

	/* define content error */

	if (!$registry->get('lastId') && $aliasValidator->validate($registry->get('firstParameter'), Redaxscript\Validator\Alias::MODE_DEFAULT) == Redaxscript\Validator\ValidatorInterface::FAILED)
	{
		$registry->set('contentError', true);
	}
	else
	{
		$registry->set('contentError', false);
	}

	/* define user */

	$browser = new Redaxscript\Client\Browser($request);
	$version = new Redaxscript\Client\Version($request);
	$engine = new Redaxscript\Client\Engine($request);
	$mobile = new Redaxscript\Client\Mobile($request);
	$tablet = new Redaxscript\Client\Tablet($request);
	$desktop = new Redaxscript\Client\Desktop($request);
	$registry->set('myBrowser', $browser->getOutput());
	$registry->set('myBrowserVersion', $version->getOutput());
	$registry->set('myEngine', $engine->getOutput());
	$registry->set('myMobile', $mobile->getOutput());
	$registry->set('myTablet', $tablet->getOutput());
	if (!$registry->get('myMobile') || !$registry->get('myTablet'))
	{
		$registry->set('myDesktop', $desktop->getOutput());
	}

	/* auth */

	Redaxscript\Request::refreshSession();
	$auth->init();
	if ($auth->getStatus())
	{
		$registry->set('myId', $auth->getUser('id'));
		$registry->set('myName', $auth->getUser('name'));
		$registry->set('myUser', $auth->getUser('user'));
		$registry->set('myEmail', $auth->getUser('email'));
		$registry->set('myLanguage', $auth->getUser('language'));
		$registry->set('myGroups', $auth->getUser('groups'));
		$registry->set('categoriesNew', $auth->getPermissionNew('categories'));
		$registry->set('categoriesEdit', $auth->getPermissionEdit('categories'));
		$registry->set('categoriesDelete', $auth->getPermissionDelete('categories'));
		$registry->set('articlesNew', $auth->getPermissionNew('articles'));
		$registry->set('articlesEdit', $auth->getPermissionEdit('articles'));
		$registry->set('articlesDelete', $auth->getPermissionDelete('articles'));
		$registry->set('extrasNew', $auth->getPermissionNew('extras'));
		$registry->set('extrasEdit', $auth->getPermissionEdit('extras'));
		$registry->set('extrasDelete', $auth->getPermissionDelete('extras'));
		$registry->set('commentsNew', $auth->getPermissionNew('comments'));
		$registry->set('commentsEdit', $auth->getPermissionEdit('comments'));
		$registry->set('commentsDelete', $auth->getPermissionDelete('comments'));
		$registry->set('groupsNew', $auth->getPermissionNew('groups'));
		$registry->set('groupsEdit', $auth->getPermissionEdit('groups'));
		$registry->set('groupsDelete', $auth->getPermissionDelete('groups'));
		$registry->set('usersNew', $auth->getPermissionNew('users'));
		$registry->set('usersEdit', $auth->getPermissionEdit('users'));
		$registry->set('usersDelete', $auth->getPermissionDelete('users'));
		$registry->set('modulesInstall', $auth->getPermissionInstall('modules'));
		$registry->set('modulesEdit', $auth->getPermissionEdit('modules'));
		$registry->set('modulesUninstall', $auth->getPermissionUninstall('modules'));
		$registry->set('settingsEdit', $auth->getPermissionEdit('settings'));
	}
	$registry->set('filter', $auth->getFilter());

	/* define table access */

	$tableParameter = $registry->get('tableParameter');
	$registry->set('tableNew', $registry->get($tableParameter . 'New'));
	$registry->set('tableInstall', $registry->get($tableParameter . 'Install'));
	$registry->set('tableEdit', $registry->get($tableParameter . 'Edit'));
	$registry->set('tableDelete', $registry->get($tableParameter . 'Delete'));
	$registry->set('tableUninstall', $registry->get($tableParameter . 'Uninstall'));

	/* define time */

	$registry->set('now', date('Y-m-d H:i:s'));

	/* cron update */

	$registry->set('cronUpdate', false);
	if (!Redaxscript\Request::getSession('timerUpdate') && $registry->get('dbStatus') === 2 && function_exists('future_update'))
	{
		Redaxscript\Request::setSession('timerUpdate', date('Y-m-d H:i:s', strtotime('+1 minute')));
		$registry->set('cronUpdate', true);
	}
	else if (Redaxscript\Request::getSession('timerUpdate') < $registry->get('now'))
	{
		Redaxscript\Request::setSession('timerUpdate', false);
	}

	/* future update */

	if ($registry->get('cronUpdate'))
	{
		Redaxscript\Module\Hook::trigger('cronUpdate');
		future_update('categories');
		future_update('articles');
		future_update('comments');
		future_update('extras');
	}

	/* cache */

	$registry->set('noCache', false);
	$filterBoolean = new Redaxscript\Filter\Boolean();
	$noCache = $filterBoolean->sanitize($request->getQuery('no-cache'));
	if ($registry->get('loggedIn') == $registry->get('token') || $noCache)
	{
		$registry->set('noCache', true);
	}
}