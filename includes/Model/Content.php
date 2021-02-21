<?php
namespace Redaxscript\Model;

/**
 * parent class to provide the content model
 *
 * @since 3.3.0
 *
 * @package Redaxscript
 * @category Model
 * @author Henry Ruhs
 */

class Content
{
	/**
	 * get the content table by alias
	 *
	 * @since 3.3.0
	 *
	 * @param string $contentAlias alias of the content
	 *
	 * @return string|null
	 */

	public function getTableByAlias(string $contentAlias = null) : ?string
	{
		if ($contentAlias)
		{
			$categoryModel = new Category();
			$category = $categoryModel->getByAlias($contentAlias);
			if ($category && $category->id)
			{
				return 'categories';
			}
			$articleModel = new Article();
			$article = $articleModel->getByAlias($contentAlias);
			if ($article && $article->id)
			{
				return 'articles';
			}
		}
		return null;
	}

	/**
	 * get the content by table and id
	 *
	 * @since 4.0.0
	 *
	 * @param string $table name of the table
	 * @param int $contentId identifier of the content
	 *
	 * @return object|null
	 */

	public function getByTableAndId(string $table = null, int $contentId = null) : ?object
	{
		if ($contentId)
		{
			if ($table === 'categories')
			{
				$categoryModel = new Category();
				return $categoryModel->getById($contentId);
			}
			if ($table === 'articles')
			{
				$articleModel = new Article();
				return $articleModel->getById($contentId);
			}
		}
		return null;
	}

	/**
	 * get the content route by table and id
	 *
	 * @since 3.3.0
	 *
	 * @param string $table name of the table
	 * @param int $contentId identifier of the content
	 *
	 * @return string|null
	 */

	public function getRouteByTableAndId(string $table = null, int $contentId = null) : ?string
	{
		if ($contentId)
		{
			if ($table === 'categories')
			{
				$categoryModel = new Category();
				return $categoryModel->getRouteById($contentId);
			}
			if ($table === 'articles')
			{
				$articleModel = new Article();
				return $articleModel->getRouteById($contentId);
			}
			if ($table === 'comments')
			{
				$commentModel = new Comment();
				return $commentModel->getRouteById($contentId);
			}
		}
		return null;
	}
}
