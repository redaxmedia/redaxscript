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
		/* switch table */

		switch ($table)
		{
			case 'categories':
				$result = Redaxscript\Db::forTablePrefix('categories')
					->tableAlias('c')
					->leftJoinPrefix('categories', array('c.parent', '=', 'p.id'), 'p')
					->select('p.alias', 'parent_alias')
					->select('c.alias', 'category_alias')
					->where('c.id', $id)
					->findArray();
				break;
			case 'articles':
				$result = Redaxscript\Db::forTablePrefix('articles')
					->tableAlias('a')
					->leftJoinPrefix('categories', array('a.category', '=', 'c.id'), 'c')
					->leftJoinPrefix('categories', array('c.parent', '=', 'p.id'), 'p')
					->select('p.alias', 'parent_alias')
					->select('c.alias', 'category_alias')
					->select('a.alias', 'article_alias')
					->where('a.id', $id)
					->findArray();
				break;
			case 'comments':
				$result = Redaxscript\Db::forTablePrefix('comments')
					->tableAlias('m')
					->leftJoinPrefix('articles', 'm.article = a.id', 'a')
					->leftJoinPrefix('categories', 'a.category = c.id', 'c')
					->leftJoinPrefix('categories', 'c.parent = p.id', 'p')
					->select('p.alias', 'parent_alias')
					->select('c.alias', 'category_alias')
					->select('a.alias', 'article_alias')
					->where('m.id', $id)
					->findArray();
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
				Redaxscript\Db::rawExecute($comments_update_query);
			}
		}
	}
	Redaxscript\Db::rawExecute($general_update_query);
}
