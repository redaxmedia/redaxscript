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
	}

	/* session start */

	session_start();

	/* define general */

	define('FILE', get_file());
	define('ROOT', get_root());
	define('TOKEN', get_token());

	/* prefix and salt */

	define('PREFIX', Redaxscript_Config::get('prefix'));
	define('SALT', Redaxscript_Config::get('salt'));

	/* database connect */

	database_connect(Redaxscript_Config::get('host'), Redaxscript_Config::get('name'), Redaxscript_Config::get('user'), Redaxscript_Config::get('password'));

	/* define session */

	define('DB_CONNECTED', $_SESSION[ROOT . '/db_connected']);
	define('DB_ERROR', $_SESSION[ROOT . '/db_error']);
	define('LOGGED_IN', $_SESSION[ROOT . '/logged_in']);
	define('ATTACK_BLOCKED', $_SESSION[ROOT . '/attack_blocked']);

	/* setup charset */

	if (function_exists('ini_set'))
	{
		ini_set('default_charset', s('charset'));
	}

	/* define parameter */

	define('FIRST_PARAMETER', get_parameter('first'));
	define('FIRST_SUB_PARAMETER', get_parameter('first_sub'));
	define('SECOND_PARAMETER', get_parameter('second'));
	define('SECOND_SUB_PARAMETER', get_parameter('second_sub'));
	define('THIRD_PARAMETER', get_parameter('third'));
	define('THIRD_SUB_PARAMETER', get_parameter('third_sub'));
	if (LOGGED_IN == TOKEN && FIRST_PARAMETER == 'admin')
	{
		define('ADMIN_PARAMETER', get_parameter('admin'));
		define('TABLE_PARAMETER', get_parameter('table'));
		define('ID_PARAMETER', get_parameter('id'));
		define('ALIAS_PARAMETER', get_parameter('alias'));
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
	define('LAST_PARAMETER', get_parameter('last'));
	define('LAST_SUB_PARAMETER', get_parameter('last_sub'));
	define('TOKEN_PARAMETER', get_parameter('token'));

	/* define routes */

	define('FULL_ROUTE', get_route(0));
	define('FULL_TOP_ROUTE', get_route(1));
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

	/* redirect to install */

	if (DB_CONNECTED == 0 && file_exists('install.php'))
	{
		define('REFRESH_ROUTE', ROOT . '/install.php');
	}

	/* define tables */

	if (FULL_ROUTE == '' || (FIRST_PARAMETER == 'admin' && SECOND_PARAMETER == ''))
	{
		/* check for homepage */

		if (s('homepage') > 0)
		{
			$table = 'articles';
			$id = s('homepage');
		}

		/* else fallback */

		else
		{
			$table = 'categories';
			$id = 0;

			/* check order */

			if (s('order') == 'asc')
			{
				$function = 'min';
			}
			else if (s('order') == 'desc')
			{
				$function = 'max';
			}
			$rank = query_plumb('rank', $table, $function);

			/* if category is published */

			if ($rank)
			{
				$status = retrieve('status', $table, 'rank', $rank);
				if ($status == 1)
				{
					$id = retrieve('id', $table, 'rank', $rank);
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
			$id = retrieve('id', LAST_TABLE, 'alias', LAST_PARAMETER);
		}
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

	if (LAST_ID == '' && check_alias(FIRST_PARAMETER, 1) == 0)
	{
		define('CONTENT_ERROR', 1);
	}
	else
	{
		define('CONTENT_ERROR', 0);
	}

	/* define user */

	define('MY_IP', get_user_ip());
	define('MY_BROWSER', get_user_agent(0));
	define('MY_BROWSER_VERSION', get_user_agent(1));
	define('MY_ENGINE', get_user_agent(2));
	define('MY_MOBILE', get_user_agent(4));
	define('MY_TABLET', get_user_agent(5));

	/* if mobile or tablet */

	if (MY_MOBILE || MY_TABLET)
	{
		define('MY_DESKTOP', '');
	}
	else
	{
		define('MY_DESKTOP', get_user_agent(3));
	}

	/* if logged in */

	if (LOGGED_IN == TOKEN)
	{
		define('MY_ID', $_SESSION[ROOT . '/my_id']);
		define('MY_NAME', $_SESSION[ROOT . '/my_name']);
		define('MY_USER', $_SESSION[ROOT . '/my_user']);
		define('MY_EMAIL', $_SESSION[ROOT . '/my_email']);
		define('MY_GROUPS', $_SESSION[ROOT . '/my_groups']);

		/* define access */

		$access_array = array(
			'categories',
			'articles',
			'extras',
			'comments',
			'groups',
			'users'
		);
		foreach ($access_array as $value)
		{
			define(strtoupper($value) . '_NEW', $_SESSION[ROOT . '/' . $value . '_new']);
			define(strtoupper($value) . '_EDIT', $_SESSION[ROOT . '/' . $value . '_edit']);
			define(strtoupper($value) . '_DELETE', $_SESSION[ROOT . '/' . $value . '_delete']);
			if (TABLE_PARAMETER == 'users' && ID_PARAMETER == MY_ID && $value == 'users')
			{
				define('USERS_EXCEPTION', 1);
			}
			else if ($value == 'users')
			{
				define('USERS_EXCEPTION', 0);
			}
		}
		define('MODULES_INSTALL', $_SESSION[ROOT . '/modules_install']);
		define('MODULES_EDIT', $_SESSION[ROOT . '/modules_edit']);
		define('MODULES_UNINSTALL', $_SESSION[ROOT . '/modules_uninstall']);
		define('SETTINGS_EDIT', $_SESSION[ROOT . '/settings_edit']);
		define('FILTER', $_SESSION[ROOT . '/filter']);
	}
	else
	{
		define('FILTER', 1);
	}

	/* define table access */

	define('TABLE_NEW', constant(strtoupper(TABLE_PARAMETER) . '_NEW'));
	define('TABLE_INSTALL', constant(strtoupper(TABLE_PARAMETER) . '_INSTALL'));
	define('TABLE_EDIT', constant(strtoupper(TABLE_PARAMETER) . '_EDIT'));
	define('TABLE_DELETE', constant(strtoupper(TABLE_PARAMETER) . '_DELETE'));
	define('TABLE_UNINSTALL', constant(strtoupper(TABLE_PARAMETER) . '_UNINSTALL'));

	/* define time */

	define('GMDATE', gmdate('D, d M Y H:i:s') . ' GMT');
	define('GMDATE_PLUS_WEEK', gmdate('D, d M Y H:i:s', strtotime('+1 week')) . ' GMT');
	define('GMDATE_PLUS_YEAR', gmdate('D, d M Y H:i:s', strtotime('+1 year')) . ' GMT');
	define('NOW', date('Y-m-d H:i:s'));
	define('DELAY', date('Y-m-d H:i:s', strtotime('+1 minute')));
	define('TODAY', date('Y-m-d'));

	/* future update */

	define('UPDATE', $_SESSION[ROOT . '/update']);
	if (UPDATE == '')
	{
		future_update('articles');
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