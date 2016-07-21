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
	$file = new Redaxscript\Server\File($request);
	$root = new Redaxscript\Server\Root($request);
	$host = new Redaxscript\Server\Host($request);
	Redaxscript\Registry::set('file', $file->getOutput());
	Redaxscript\Registry::set('root', $root->getOutput());
	Redaxscript\Registry::set('host', $host->getOutput());

	/* session */

	session_start();

	/* prevent session hijacking */

	Redaxscript\Request::refreshSession();
	if (!Redaxscript\Request::getSession('regenerateId'))
	{
		session_regenerate_id();
		Redaxscript\Request::setSession('regenerateId', true);
	}

	/* database status */

	Redaxscript\Registry::set('dbStatus', Redaxscript\Db::getStatus());

	/* define token */

	$token = new Redaxscript\Server\Token($request);
	$auth = new Redaxscript\Auth($request);
	Redaxscript\Registry::set('token', $token->getOutput());
	if ($auth->getStatus())
	{
		Redaxscript\Registry::set('loggedIn', $token->getOutput());
	}

	/* setup charset */

	if (function_exists('ini_set') && Redaxscript\Registry::get('dbStatus') === 2)
	{
		ini_set('default_charset', Redaxscript\Db::getSetting('charset'));
	}

	/* define server */

	$driverArray = PDO::getAvailableDrivers();
	$moduleArray = function_exists('apache_get_modules') ? apache_get_modules() : array();
	Redaxscript\Registry::set('config', is_writable('config.php'));
	Redaxscript\Registry::set('file_permission_grant', 0);
	Redaxscript\Registry::set('phpVersion', phpversion());
	Redaxscript\Registry::set('pdoDriver', class_exists('PDO') ? 1 : 0);
	Redaxscript\Registry::set('sessionStatus', session_status() === PHP_SESSION_ACTIVE ? 1 : 0);
	Redaxscript\Registry::set('osServer', strtolower(php_uname('s')));
	Redaxscript\Registry::set('htaccess', file_exists('.htaccess'));
	Redaxscript\Registry::set('modDeflate', in_array('mod_deflate', $moduleArray) ? 1 : 0);
	Redaxscript\Registry::set('modRewrite', in_array('mod_rewrite', $moduleArray) ? 1 : 0);
	Redaxscript\Registry::set('pdoMysql', in_array('mysql', $driverArray) ? 1 : 0);
	Redaxscript\Registry::set('pdoSqlite', in_array('sqlite', $driverArray) ? 1 : 0);
	Redaxscript\Registry::set('pdoPgsql', in_array('pgsql', $driverArray) ? 1 : 0);

	/* define parameter */

	$parameter = new Redaxscript\Router\Parameter($request);
	$parameter->init();
	Redaxscript\Registry::set('firstParameter', $parameter->getFirst());
	Redaxscript\Registry::set('firstSubParameter', $parameter->getSub());
	Redaxscript\Registry::set('secondParameter', $parameter->getSecond());
	Redaxscript\Registry::set('secondSubParameter', $parameter->getSub());
	Redaxscript\Registry::set('thirdParameter', $parameter->getThird());
	Redaxscript\Registry::set('thirdSubParameter', $parameter->getSub());
	if (Redaxscript\Registry::get('loggedIn') == Redaxscript\Registry::get('token') && Redaxscript\Registry::get('firstParameter') == 'admin')
	{
		Redaxscript\Registry::set('adminParameter', $parameter->getAdmin());
		Redaxscript\Registry::set('tableParameter', $parameter->getTable());
		Redaxscript\Registry::set('idParameter', $parameter->getId());
		Redaxscript\Registry::set('aliasParameter', $parameter->getAlias());
	}
	Redaxscript\Registry::set('lastParameter', $parameter->getLast());
	Redaxscript\Registry::set('lastSubParameter', $parameter->getSub());
	Redaxscript\Registry::set('tokenParameter', $parameter->getToken());

	/* define routes */

	$router = new Redaxscript\Router\Resolver($request);
	$router->init();
	Redaxscript\Registry::set('liteRoute', $router->getLite());
	Redaxscript\Registry::set('fullRoute', $router->getFull());
	if (function_exists('apache_get_modules') && !in_array('mod_rewrite', apache_get_modules()) || !file_exists('.htaccess') || Redaxscript\Registry::get('file') == 'install.php')
	{
		Redaxscript\Registry::set('parameterRoute', '?p=');
		Redaxscript\Registry::set('languageRoute', '&amp;l=');
		Redaxscript\Registry::set('templateRoute', '&amp;t=');
	}
	else
	{
		Redaxscript\Registry::set('parameterRoute', '');
		Redaxscript\Registry::set('languageRoute', '.');
		Redaxscript\Registry::set('templateRoute', '.');
	}

	/* define tables */

	if (Redaxscript\Registry::get('dbStatus') === 2)
	{
		if (!Redaxscript\Registry::get('fullRoute') || (Redaxscript\Registry::get('firstParameter') == 'admin' && !Redaxscript\Registry::get('secondParameter')))
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
			Redaxscript\Registry::set('firstTable', $table);
			Redaxscript\Registry::set('lastTable', $table);
		}
		else
		{
			if (Redaxscript\Registry::get('firstParameter'))
			{
				Redaxscript\Registry::set('firstTable', query_table(Redaxscript\Registry::get('firstParameter')));
			}
			if (Redaxscript\Registry::get('firstTable'))
			{
				Redaxscript\Registry::set('secondTable', query_table(Redaxscript\Registry::get('secondParameter')));
			}
			if (Redaxscript\Registry::get('secondTable'))
			{
				Redaxscript\Registry::set('thirdTable', query_table(Redaxscript\Registry::get('thirdParameter')));
			}
			if (Redaxscript\Registry::get('lastParameter'))
			{
				Redaxscript\Registry::set('lastTable', query_table(Redaxscript\Registry::get('lastParameter')));
			}
			if (Redaxscript\Registry::get('lastTable'))
			{
				$id = Redaxscript\Db::forTablePrefix(Redaxscript\Registry::get('lastTable'))->where('alias', Redaxscript\Registry::get('lastParameter'))->findOne()->id;
			}
		}
	}

	/* define ids */

	$aliasValidator = new Redaxscript\Validator\Alias();
	if (Redaxscript\Registry::get('firstParameter') === 'admin' || $aliasValidator->validate(Redaxscript\Registry::get('firstParameter'), Redaxscript\Validator\Alias::MODE_DEFAULT) == Redaxscript\Validator\ValidatorInterface::FAILED)
	{
		if (Redaxscript\Registry::get('lastTable') == 'categories')
		{
			Redaxscript\Registry::set('categoryId', $id);
			Redaxscript\Registry::set('lastId', $id);
		}
		else if (Redaxscript\Registry::get('lastTable') == 'articles')
		{
			Redaxscript\Registry::set('articleId', $id);
			Redaxscript\Registry::set('lastId', $id);
		}
	}

	/* define content error */

	if (!Redaxscript\Registry::get('lastId') && $aliasValidator->validate(Redaxscript\Registry::get('firstParameter'), Redaxscript\Validator\Alias::MODE_DEFAULT) == Redaxscript\Validator\ValidatorInterface::FAILED)
	{
		Redaxscript\Registry::set('contentError', true);
	}
	else
	{
		Redaxscript\Registry::set('contentError', false);
	}

	/* define user */

	$browser = new Redaxscript\Client\Browser($request);
	$version = new Redaxscript\Client\Version($request);
	$engine = new Redaxscript\Client\Engine($request);
	$mobile = new Redaxscript\Client\Mobile($request);
	$tablet = new Redaxscript\Client\Tablet($request);
	$desktop = new Redaxscript\Client\Desktop($request);
	Redaxscript\Registry::set('myBrowser', $browser->getOutput());
	Redaxscript\Registry::set('myBrowserVersion', $version->getOutput());
	Redaxscript\Registry::set('myEngine', $engine->getOutput());
	Redaxscript\Registry::set('myMobile', $mobile->getOutput());
	Redaxscript\Registry::set('myTablet', $tablet->getOutput());
	if (!Redaxscript\Registry::get('myMobile') || !Redaxscript\Registry::get('myTablet'))
	{
		Redaxscript\Registry::set('myDesktop', $desktop->getOutput());
	}

	/* auth */

	Redaxscript\Request::refreshSession();
	$auth->init();
	if ($auth->getStatus())
	{
		Redaxscript\Registry::set('myId', $auth->getUser('id'));
		Redaxscript\Registry::set('myName', $auth->getUser('name'));
		Redaxscript\Registry::set('myUser', $auth->getUser('user'));
		Redaxscript\Registry::set('myEmail', $auth->getUser('email'));
		Redaxscript\Registry::set('myLanguage', $auth->getUser('language'));
		Redaxscript\Registry::set('myGroups', $auth->getUser('groups'));
		Redaxscript\Registry::set('categoriesNew', $auth->getPermissionNew('categories'));
		Redaxscript\Registry::set('categoriesEdit', $auth->getPermissionEdit('categories'));
		Redaxscript\Registry::set('categoriesDelete', $auth->getPermissionDelete('categories'));
		Redaxscript\Registry::set('articlesNew', $auth->getPermissionNew('articles'));
		Redaxscript\Registry::set('articlesEdit', $auth->getPermissionEdit('articles'));
		Redaxscript\Registry::set('articlesDelete', $auth->getPermissionDelete('articles'));
		Redaxscript\Registry::set('extrasNew', $auth->getPermissionNew('extras'));
		Redaxscript\Registry::set('extrasEdit', $auth->getPermissionEdit('extras'));
		Redaxscript\Registry::set('extrasDelete', $auth->getPermissionDelete('extras'));
		Redaxscript\Registry::set('commentsNew', $auth->getPermissionNew('comments'));
		Redaxscript\Registry::set('commentsEdit', $auth->getPermissionEdit('comments'));
		Redaxscript\Registry::set('commentsDelete', $auth->getPermissionDelete('comments'));
		Redaxscript\Registry::set('groupsNew', $auth->getPermissionNew('groups'));
		Redaxscript\Registry::set('groupsEdit', $auth->getPermissionEdit('groups'));
		Redaxscript\Registry::set('groupsDelete', $auth->getPermissionDelete('groups'));
		Redaxscript\Registry::set('usersNew', $auth->getPermissionNew('users'));
		Redaxscript\Registry::set('usersEdit', $auth->getPermissionEdit('users'));
		Redaxscript\Registry::set('usersDelete', $auth->getPermissionDelete('users'));
		Redaxscript\Registry::set('modulesInstall', $auth->getPermissionInstall('modules'));
		Redaxscript\Registry::set('modulesEdit', $auth->getPermissionEdit('modules'));
		Redaxscript\Registry::set('modulesUninstall', $auth->getPermissionUninstall('modules'));
		Redaxscript\Registry::set('settingsEdit', $auth->getPermissionEdit('settings'));
	}
	Redaxscript\Registry::set('filter', $auth->getFilter());

	/* define table access */

	$tableParameter = Redaxscript\Registry::get('tableParameter');
	Redaxscript\Registry::set('tableNew', Redaxscript\Registry::get($tableParameter . 'New'));
	Redaxscript\Registry::set('tableInstall', Redaxscript\Registry::get($tableParameter . 'Install'));
	Redaxscript\Registry::set('tableEdit', Redaxscript\Registry::get($tableParameter . 'Edit'));
	Redaxscript\Registry::set('tableDelete', Redaxscript\Registry::get($tableParameter . 'Delete'));
	Redaxscript\Registry::set('tableUninstall', Redaxscript\Registry::get($tableParameter . 'Uninstall'));

	/* define time */

	Redaxscript\Registry::set('now', date('Y-m-d H:i:s'));

	/* cron update */

	Redaxscript\Registry::set('cronUpdate', false);
	if (!Redaxscript\Request::getSession('timerUpdate') && Redaxscript\Registry::get('dbStatus') === 2 && function_exists('future_update'))
	{
		Redaxscript\Request::setSession('timerUpdate', date('Y-m-d H:i:s', strtotime('+1 minute')));
		Redaxscript\Registry::set('cronUpdate', true);
	}
	else if (Redaxscript\Request::getSession('timerUpdate') < Redaxscript\Registry::get('now'))
	{
		Redaxscript\Request::setSession('timerUpdate', false);
	}

	/* future update */

	if (Redaxscript\Registry::get('cronUpdate'))
	{
		Redaxscript\Hook::trigger('cronUpdate');
		future_update('categories');
		future_update('articles');
		future_update('comments');
		future_update('extras');
	}
}