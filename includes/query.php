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
 * @param string $alias
 * @return string
 */

function query_table($alias)
{
	static $table;

	/* load from cache */

	if ($table[$alias])
	{
		$output = $table[$alias];
	}

	/* else query */

	else if (strlen($alias))
	{
		$category = Redaxscript\Db::forTablePrefix('categories')->where('alias', $alias)->findOne()->id;
		if ($category)
		{
			$output = $table[$alias] = 'categories';
		}
		else
		{
			$article = Redaxscript\Db::forTablePrefix('articles')->where('alias', $alias)->findOne()->id;
			if ($article)
			{
				$output = $table[$alias] = 'articles';
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

function build_route($table, $id)
{
	if (is_string($table) && is_numeric($id))
	{
		/* switch table */

		switch ($table)
		{
			case 'categories':
				$result = Redaxscript\Db::forTablePrefix('categories')
					->tableAlias('c')
					->leftJoinPrefix('categories', 'c.parent = p.id', 'p')
					->select('p.alias', 'parent_alias')
					->select('c.alias', 'category_alias')
					->where('c.id', $id)
					->findArray();
				break;
			case 'articles':
				$result = Redaxscript\Db::forTablePrefix('articles')
					->tableAlias('a')
					->leftJoinPrefix('categories', 'a.category = c.id', 'c')
					->leftJoinPrefix('categories', 'c.parent = p.id', 'p')
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

function future_update($table)
{
	$registry = Redaxscript\Registry::getInstance();
	Redaxscript\Db::forTablePrefix($table)
		->where('status', 2)
		->whereLt('date', $registry->get('now'))
		->findMany()
		->set('status', 1)
		->save();
}
