<?php
namespace Redaxscript\Admin\Model;

use Redaxscript\Model as BaseModel;

/**
 * parent class to provide the admin category model
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Model
 * @author Henry Ruhs
 */

class Category extends BaseModel\Category
{
	/**
	 * is unique by id and alias
	 *
	 * @since 4.0.0
	 *
	 * @param int $categoryId identifier of the category
	 * @param string $categoryAlias alias of the category
	 *
	 * @return bool
	 */

	public function isUniqueByIdAndAlias(int $categoryId = null, string $categoryAlias = null) : bool
	{
		$articleModel = new Article();
		return !$articleModel->getByAlias($categoryAlias)->id && !$this->getByAlias($categoryAlias)->id || $this->getByAlias($categoryAlias)->id === $this->getById($categoryId)->id;
	}

	/**
	 * create the category by array
	 *
	 * @since 4.0.0
	 *
	 * @param array $createArray array of the create
	 *
	 * @return bool
	 */

	public function createByArray(array $createArray = []) : bool
	{
		return $this
			->query()
			->create()
			->set($createArray)
			->save();
	}

	/**
	 * update the category by id and array
	 *
	 * @since 4.0.0
	 *
	 * @param int $categoryId identifier of the category
	 * @param array $updateArray array of the update
	 *
	 * @return bool
	 */

	public function updateByIdAndArray(int $categoryId = null, array $updateArray = []) : bool
	{
		return $this
			->query()
			->whereIdIs($categoryId)
			->findOne()
			->set($updateArray)
			->save();
	}

	/**
	 * publish the category by id
	 *
	 * @since 4.0.0
	 *
	 * @param int $categoryId identifier of the category
	 *
	 * @return bool
	 */

	public function publishById(int $categoryId = null) : bool
	{
		return $this
			->query()
			->whereIdIs($categoryId)
			->findOne()
			->set('status', 1)
			->save();
	}

	/**
	 * unpublish the category by id
	 *
	 * @since 4.0.0
	 *
	 * @param int $categoryId identifier of the category
	 *
	 * @return bool
	 */

	public function unpublishById(int $categoryId = null) : bool
	{
		return $this
			->query()
			->whereIdIs($categoryId)
			->findOne()
			->set('status', 0)
			->save();
	}

	/**
	 * delete the category by id
	 *
	 * @since 4.0.0
	 *
	 * @param int $categoryId identifier of the category
	 *
	 * @return bool
	 */

	public function deleteById(int $categoryId = null) : bool
	{
		return $this->query()->whereIdIs($categoryId)->deleteMany();
	}
}
