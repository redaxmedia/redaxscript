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
		$table = null;
		$categoryModel = new Category();
		if ($categoryModel->getByAlias($contentAlias)->id)
		{
			$table = 'categories';
		}
		else
		{
			$articleModel = new Article();
			if ($articleModel->getByAlias($contentAlias)->id)
			{
				$table = 'articles';
			}
		}
		return $table;
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
		$route = null;

		/* switch table */

		switch ($table)
		{
			case 'categories':
				$categoryModel = new Category();
				$route = $categoryModel->getRouteById($contentId);
				break;
			case 'articles':
				$articleModel = new Article();
				$route = $articleModel->getRouteById($contentId);
				break;
			case 'comments':
				$commentModel = new Comment();
				$route = $commentModel->getRouteById($contentId);
				break;
		}
		return $route;
	}
}
