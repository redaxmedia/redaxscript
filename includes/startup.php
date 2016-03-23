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
	define('FILE', $file->getOutput());
	define('ROOT', $root->getOutput());

	/* session start */

	session_start();

	/* prevent session hijacking */

	if (!$_SESSION[ROOT . '/regenerate_id'])
	{
		session_regenerate_id();
		$_SESSION[ROOT . '/regenerate_id'] = 1;
	}

	/* database status */

	Redaxscript\Registry::set('dbStatus', Redaxscript\Db::getStatus());

	/* define token */

	$token = new Redaxscript\Server\Token($request);
	define('TOKEN', $token->getOutput());

	/* prefix and salt */

	define('PREFIX', Redaxscript\Config::get('dbPrefix'));
	define('SALT', Redaxscript\Config::get('dbSalt'));

	/* define session */

	define('LOGGED_IN', $_SESSION[ROOT . '/logged_in']);

	/* setup charset */

	if (function_exists('ini_set') && Redaxscript\Registry::get('dbStatus') === 2)
	{
		ini_set('default_charset', Redaxscript\Db::getSetting('charset'));
	}

	/* define parameter */

	$parameter = new Redaxscript\Router\Parameter($request);
	$parameter->init();
	define('FIRST_PARAMETER', $parameter->getFirst());
	define('FIRST_SUB_PARAMETER', $parameter->getSub());
	define('SECOND_PARAMETER', $parameter->getSecond());
	define('SECOND_SUB_PARAMETER', $parameter->getSub());
	define('THIRD_PARAMETER', $parameter->getThird());
	define('THIRD_SUB_PARAMETER', $parameter->getSub());
	if (LOGGED_IN == TOKEN && FIRST_PARAMETER == 'admin')
	{
		define('ADMIN_PARAMETER', $parameter->getAdmin());
		define('TABLE_PARAMETER', $parameter->getTable());
		define('ID_PARAMETER', $parameter->getId());
		define('ALIAS_PARAMETER', $parameter->getAlias());
	}
	else
	{
		undefine(array(
			'ADMIN_PARAMETER',
			'TABLE_PARAMETER',
			'ID_PARAMETER',
			'ALIAS_PARAMETER'
		));
	}
	define('LAST_PARAMETER', $parameter->getLast());
	define('LAST_SUB_PARAMETER', $parameter->getSub());
	define('TOKEN_PARAMETER', $parameter->getToken());

	/* define routes */

	$router = new Redaxscript\Router\Resolver($request);
	$router->init();
	define('LITE_ROUTE', $router->getLite());
	define('FULL_ROUTE', $router->getFull());
	if (function_exists('apache_get_modules') && in_array('mod_rewrite', apache_get_modules()) == '' || file_exists('.htaccess') == '' || FILE == 'install.php')
	{
		define('REWRITE_ROUTE', '?p=');
		define('LANGUAGE_ROUTE', '&amp;l=');
		define('TEMPLATE_ROUTE', '&amp;t=');
	}
	else
	{
		define('REWRITE_ROUTE', '');
		define('LANGUAGE_ROUTE', '.');
		define('TEMPLATE_ROUTE', '.');
	}

	/* define tables */

	if (Redaxscript\Registry::get('dbStatus') === 2)
	{
		if (FULL_ROUTE == '' || (FIRST_PARAMETER == 'admin' && SECOND_PARAMETER == ''))
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
			define('FIRST_TABLE', $table);
			define('SECOND_TABLE', '');
			define('THIRD_TABLE', '');
			define('LAST_TABLE', $table);
		}
		else
		{
			if (FIRST_PARAMETER)
			{
				define('FIRST_TABLE', query_table(FIRST_PARAMETER));
			}
			else
			{
				define('FIRST_TABLE', '');
			}
			if (FIRST_TABLE)
			{
				define('SECOND_TABLE', query_table(SECOND_PARAMETER));
			}
			else
			{
				define('SECOND_TABLE', '');
			}
			if (SECOND_TABLE)
			{
				define('THIRD_TABLE', query_table(THIRD_PARAMETER));
			}
			else
			{
				define('THIRD_TABLE', '');
			}
			if (LAST_PARAMETER)
			{
				define('LAST_TABLE', query_table(LAST_PARAMETER));
			}
			else
			{
				define('LAST_TABLE', '');
			}
			if (LAST_TABLE)
			{
				$id = Redaxscript\Db::forTablePrefix(LAST_TABLE)->where('alias', LAST_PARAMETER)->findOne()->id;
			}
		}
	}
	else
	{
		undefine(array(
			'FIRST_TABLE',
			'SECOND_TABLE',
			'THIRD_TABLE',
			'LAST_TABLE'
		));
	}

	/* define ids */

	if (LAST_TABLE == 'categories')
	{
		define('CATEGORY', $id);
		define('ARTICLE', '');
		define('LAST_ID', $id);
	}
	else if (LAST_TABLE == 'articles')
	{
		define('CATEGORY', '');
		define('ARTICLE', $id);
		define('LAST_ID', $id);
	}
	else
	{
		undefine(array(
			'CATEGORY',
			'ARTICLE',
			'LAST_ID'
		));
	}

	/* define content error */

	$aliasValidator = new Redaxscript\Validator\Alias();
	if (LAST_ID == '' && $aliasValidator->validate(FIRST_PARAMETER, Redaxscript\Validator\Alias::MODE_DEFAULT) == Redaxscript\Validator\ValidatorInterface::FAILED)
	{
		define('CONTENT_ERROR', 1);
	}
	else
	{
		define('CONTENT_ERROR', 0);
	}

	/* define user */

	$browser = new Redaxscript\Client\Browser($request);
	$version = new Redaxscript\Client\Version($request);
	$engine = new Redaxscript\Client\Engine($request);
	$mobile = new Redaxscript\Client\Mobile($request);
	$tablet = new Redaxscript\Client\Tablet($request);
	define('MY_BROWSER', $browser->getOutput());
	define('MY_BROWSER_VERSION', $version->getOutput());
	define('MY_ENGINE', $engine->getOutput());
	define('MY_MOBILE', $mobile->getOutput());
	define('MY_TABLET', $tablet->getOutput());

	/* mobile or tablet */

	if (MY_MOBILE || MY_TABLET)
	{
		define('MY_DESKTOP', '');
	}
	else
	{
		$desktop = new Redaxscript\Client\Desktop($request);
		define('MY_DESKTOP', $desktop->getOutput());
	}

	/* auth */

	Redaxscript\Request::refreshSession();
	$auth = new Redaxscript\Auth(Redaxscript\Request::getInstance());
	$auth->init();
	if ($auth->getStatus())
	{
		Redaxscript\Registry::set('myId', $auth->getUser('id'));
		Redaxscript\Registry::set('myName', $auth->getUser('name'));
		Redaxscript\Registry::set('myUser', $auth->getUser('user'));
		Redaxscript\Registry::set('myEmail', $auth->getUser('email'));
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
		if (TABLE_PARAMETER == 'users' && ID_PARAMETER == Redaxscript\Registry::get('myId'))
		{
			Redaxscript\Registry::set('usersException', 1);
		}
		else
		{
			Redaxscript\Registry::set('usersException', 0);
		}
		Redaxscript\Registry::set('modulesInstall', $auth->getPermissionInstall('modules'));
		Redaxscript\Registry::set('modulesEdit', $auth->getPermissionEdit('modules'));
		Redaxscript\Registry::set('modulesUninstall', $auth->getPermissionUninstall('modules'));
		Redaxscript\Registry::set('settingsEdit', $auth->getPermissionEdit('settings'));
		Redaxscript\Registry::set('filter', $auth->getFilter());
	}
	else
	{
		Redaxscript\Registry::set('filter', true);
	}

	/* define table access */

	define('TABLE_NEW', Redaxscript\Registry::get(TABLE_PARAMETER . 'New'));
	define('TABLE_INSTALL', Redaxscript\Registry::get(TABLE_PARAMETER . 'Install'));
	define('TABLE_EDIT', Redaxscript\Registry::get(TABLE_PARAMETER . 'Edit'));
	define('TABLE_DELETE', Redaxscript\Registry::get(TABLE_PARAMETER . 'Delete'));
	define('TABLE_UNINSTALL', Redaxscript\Registry::get(TABLE_PARAMETER . 'Uninstall'));

	/* define time */

	define('GMDATE', gmdate('D, d M Y H:i:s') . ' GMT');
	define('GMDATE_PLUS_WEEK', gmdate('D, d M Y H:i:s', strtotime('+1 week')) . ' GMT');
	define('GMDATE_PLUS_YEAR', gmdate('D, d M Y H:i:s', strtotime('+1 year')) . ' GMT');
	define('NOW', date('Y-m-d H:i:s'));
	Redaxscript\Registry::set('now', NOW);
	define('DELAY', date('Y-m-d H:i:s', strtotime('+1 minute')));
	define('TODAY', date('Y-m-d'));

	/* future update */

	define('UPDATE', $_SESSION[ROOT . '/update']);
	if (UPDATE == '' && Redaxscript\Registry::get('dbStatus') === 2 && function_exists('future_update'))
	{
		future_update('articles');
		future_update('comments');
		future_update('extras');
		$_SESSION[ROOT . '/update'] = DELAY;
	}
	else if (UPDATE < NOW)
	{
		$_SESSION[ROOT . '/update'] = '';
	}
}

/**
 * undefine
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Startup
 * @author Henry Ruhs
 *
 * @param string $input
 */

function undefine($input = '')
{
	if ($input)
	{
		foreach ($input as $value)
		{
			define($value, '');
		}
	}
}

/**
 * migrate constants
 *
 * @since 2.1.0
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Migrate
 * @author Henry Ruhs
 * @author Gary Aylward
 *
 * @return array
 */

function migrate_constants()
{
	/* get user constants */

	$constants = get_defined_constants(true);
	$constants_user = $constants['user'];

	/* process constants user */

	foreach ($constants_user as $key => $value)
	{
		/* transform to camelcase */

		$key = mb_convert_case($key, MB_CASE_TITLE);
		$key[0] = strtolower($key[0]);

		/* remove underline */

		$key = str_replace('_', '', $key);

		/* store in array */

		$output[$key] = $value;
	}
	$output = array_merge($output, Redaxscript\Registry::get());
	return $output;
}