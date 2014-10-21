<?php

/**
 * database connect
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Query
 * @author Henry Ruhs
 *
 * @param string $host
 * @param string $name
 * @param string $user
 * @param string $password
 */

function database_connect($host = '', $name = '', $user = '', $password = '')
{
	$database_connect = mysql_connect($host, $user, $password);
	$database_select = mysql_select_db($name);

	/* if established database connection */

	if ($database_connect && $database_select)
	{
		$query = 'SET NAMES \'utf8\'';
		mysql_query($query);
		$_SESSION[ROOT . '/db_connected'] = 1;
		$_SESSION[ROOT . '/db_error'] = '';
	}

	/* else handle error */

	else
	{
		$_SESSION[ROOT . '/db_connected'] = 0;
		$_SESSION[ROOT . '/db_error'] = mysql_error();
		$_SESSION[ROOT . '/logged_in'] = '';
	}
}

/**
 * shortcut
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Query
 * @author Henry Ruhs
 *
 * @param string $name
 * @return string
 */

function s($name = '')
{
	static $settings;

	/* query settings */

	if ($settings == '')
	{
		$query = 'SELECT name, value FROM ' . PREFIX . 'settings';
		$result = Redaxscript\Db::forPrefixTable('settings')->rawQuery($query)->findArray();
		if ($result)
		{
			foreach ($result as $r)
			{
				$settings[$r['name']] = $r['value'];
			}
		}
	}
	$output = $settings[$name];

	/* charset fallback */

	if (DB_CONNECTED == 0 && $name == 'charset')
	{
		$output = 'utf-8';
	}
	return $output;
}

/**
 * query table
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Query
 * @author Henry Ruhs
 *
 * @param string $input
 * @return string
 */

function query_table($input = '')
{
	static $table;

	/* fetch from cache */

	if ($table[$input])
	{
		$output = $table[$input];
	}

	/* else query */

	else
	{
		$category = Redaxscript\Db::forPrefixTable('categories')->where('alias', $input)->findOne()->id;
		if ($category)
		{
			$output = $table[$input] = 'categories';
		}
		else
		{
			$article = Redaxscript\Db::forPrefixTable('articles')->where('alias', $input)->findOne()->id;
			if ($article)
			{
				$output = $table[$input] = 'articles';
			}
		}
	}
	return $output;
}

/**
 * build route
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Query
 * @author Henry Ruhs
 *
 * @param string $table
 * @param integer $id
 * @return string
 */

function build_route($table = '', $id = '')
{
	static $route;

	/* fetch from cache */

	if ($route[$table . $id])
	{
		$output = $route[$table . $id];
	}

	/* else query */

	else if ($table && $id)
	{
		$query = 'SELECT p.alias, c.alias';
		if ($table != 'categories')
		{
			$query .= ', a.alias';
		}
		$query .= ' FROM ' . PREFIX . $table . ' AS';

		/* switch table */

		switch ($table)
		{
			case 'categories':
				$query .= ' c LEFT JOIN ' . PREFIX . 'categories AS p ON c.parent = p.id WHERE c.id = ' . $id;
				break;
			case 'articles':
				$query .= ' a LEFT JOIN ' . PREFIX . 'categories AS c ON a.category = c.id LEFT JOIN ' . PREFIX . 'categories AS p ON c.parent = p.id WHERE a.id = ' . $id;
				break;
			case 'comments':
				$query .= ' m LEFT JOIN ' . PREFIX . 'articles AS a ON m.article = a.id LEFT JOIN ' . PREFIX . 'categories AS c ON a.category = c.id LEFT JOIN ' . PREFIX . 'categories AS p ON c.parent = p.id WHERE m.id = ' . $id;
				break;
		}
		$result = mysql_query($query);

		/* collect output */

		if ($result)
		{
			$output = mysql_fetch_row($result);
		}
		if (is_array($output))
		{
			$output = array_filter($output);
			$output = implode('/', $output);
		}

		/* comment id */

		if ($table == 'comments' && $output)
		{
			$output .= '#comment-' . $id;
		}

		/* store in cache */

		if ($output)
		{
			$route[$table . $id] = $output;
		}
	}
	return $output;
}

/**
 * future update
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Query
 * @author Henry Ruhs
 *
 * @param string $table
 */

function future_update($table = '')
{
	$general_update_query = 'UPDATE ' . PREFIX . $table . ' SET status = 1 WHERE date < \'' . NOW . '\' && status = 2';
	if ($table == 'articles')
	{
		$general_select_query = 'SELECT id, date FROM ' . PREFIX . 'articles WHERE date < \'' . NOW . '\' && status = 2';
		$general_result = Redaxscript\Db::forPrefixTable('articles')->rawQuery($general_select_query)->findArray();
		if ($general_result)
		{
			foreach ($general_result as $r)
			{
				$comments_update_query = 'UPDATE ' . PREFIX . 'comments SET date = \'' . $r['date'] . '\', status = 1 WHERE article = ' . $r['id'] . ' && status = 2';
				Redaxscript\Db::forPrefixTable('users')->rawExecute($comments_update_query);
			}
		}
	}
	Redaxscript\Db::forPrefixTable('users')->rawExecute($general_update_query);
}
