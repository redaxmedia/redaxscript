<?php
namespace Redaxscript\Model;

use function array_column;
use function array_filter;
use function array_pop;
use function array_search;
use function implode;

/**
 * parent class to provide the category model
 *
 * @since 3.3.0
 *
 * @package Redaxscript
 * @category Model
 * @author Henry Ruhs
 */

class Category extends ContentAbstract
{
	/**
	 * name of the table
	 *
	 * @var string
	 */

	protected $_table = 'categories';

	/**
	 * get the category by alias
	 *
	 * @since 4.0.0
	 *
	 * @param string $categoryAlias alias of the category
	 *
	 * @return object|null
	 */

	public function getByAlias(string $categoryAlias = null) : ?object
	{
		$category = $this->query()->where('alias', $categoryAlias)->findOne();
		return $category ? : null;
	}

	/**
	 * get the category route by id
	 *
	 * @since 3.3.0
	 *
	 * @param int $categoryId identifier of the category
	 *
	 * @return string|null
	 */

	public function getRouteById(int $categoryId = null) : ?string
	{
		if ($categoryId)
		{
			$routeArray = $this
				->query()
				->tableAlias('category')
				->leftJoinPrefix('categories', 'category.parent = parent.id', 'parent')
				->select('parent.alias', 'parentAlias')
				->select('category.alias', 'categoryAlias')
				->select('category.id', 'categoryId')
				->findArray();

			/* handle route */

			$key = array_search($categoryId, array_column($routeArray, 'categoryId'));
			array_pop($routeArray[$key]);
			return implode('/', array_filter($routeArray[$key]));
		}
		return null;
	}
}
