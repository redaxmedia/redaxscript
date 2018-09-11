<?php
namespace Redaxscript\Model;

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
		return $category ? $category : null;
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
		$route = null;
		$categoryArray = $this
			->query()
			->tableAlias('c')
			->leftJoinPrefix('categories', 'c.parent = p.id', 'p')
			->select('p.alias', 'parent_alias')
			->select('c.alias', 'category_alias')
			->where('c.id', $categoryId)
			->findArray();

		/* handle route */

		if (is_array($categoryArray[0]))
		{
			$route = implode('/', array_filter($categoryArray[0]));
		}
		return $route;
	}
}
