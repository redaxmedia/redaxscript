<?php

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
		$category = Redaxscript\Db::forTablePrefix('categories')->where('alias', $input)->findOne()->id;
		if ($category)
		{
			$output = $table[$input] = 'categories';
		}
		else
		{
			$article = Redaxscript\Db::forTablePrefix('articles')->where('alias', $input)->findOne()->id;
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
	if ($table && $id)
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
				$result = Redaxscript\Db::forTablePrefix('categories')
					->tableAlias('c')
					->joinPrefix('categories', array('c.parent', '=', 'p.id'), 'p')
					->select('p.alias', 'parent_alias')
					->select('c.alias', 'category_alias')
				    ->where('c.id', $id)
					->findArray();
				break;
			case 'articles':
				$query .= ' a LEFT JOIN ' . PREFIX . 'categories AS c ON a.category = c.id LEFT JOIN ' . PREFIX . 'categories AS p ON c.parent = p.id WHERE a.id = ' . $id;
				$result = Redaxscript\Db::forTablePrefix()->rawQuery($query)->findArray();
				break;
			case 'comments':
				$query .= ' m LEFT JOIN ' . PREFIX . 'articles AS a ON m.article = a.id LEFT JOIN ' . PREFIX . 'categories AS c ON a.category = c.id LEFT JOIN ' . PREFIX . 'categories AS p ON c.parent = p.id WHERE m.id = ' . $id;
				//$result = Redaxscript\Db::forTablePrefix()->rawQuery($query)->findArray();
				break;
		}

		/* collect output */

		if ($result)
		{
			$output = $result[0];
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
		$general_result = Redaxscript\Db::forTablePrefix('articles')->rawQuery($general_select_query)->findArray();
		if ($general_result)
		{
			foreach ($general_result as $r)
			{
				$comments_update_query = 'UPDATE ' . PREFIX . 'comments SET date = \'' . $r['date'] . '\', status = 1 WHERE article = ' . $r['id'] . ' && status = 2';
				Redaxscript\Db::forTablePrefix('users')->rawExecute($comments_update_query);
			}
		}
	}
	Redaxscript\Db::forTablePrefix('users')->rawExecute($general_update_query);
}
