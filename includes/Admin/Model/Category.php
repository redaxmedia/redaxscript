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
	 * create the category by array
	 *
	 * @since 4.0.0
	 *
	 * @param array $createArray
	 *
	 * @return bool
	 */

	public function createByArray(array $createArray = []) : bool
	{
		return $this->query()
			->create()
			->set(
			[
				'title' => $createArray['title'],
				'alias' => $createArray['alias'],
				'author' => $createArray['author'],
				'description' => $createArray['description'],
				'keywords' => $createArray['keywords'],
				'robots' => $createArray['robots'],
				'language' => $createArray['language'],
				'template' => $createArray['template'],
				'sibling' => $createArray['sibling'],
				'parent' => $createArray['parent'],
				'status' => $createArray['status'],
				'rank' => $createArray['rank'],
				'access' => $createArray['access'],
				'date' => $createArray['date']
			])
			->save();
	}

	/**
	 * update the category by id and array
	 *
	 * @since 4.0.0
	 *
	 * @param int $categoryId identifier of the category
	 * @param array $updateArray
	 *
	 * @return bool
	 */

	public function updateByIdAndArray(int $categoryId = null, array $updateArray = []) : bool
	{
		return $this->query()
			->whereIdIs($categoryId)
			->findOne()
			->set(
			[
				'title' => $updateArray['title'],
				'alias' => $updateArray['alias'],
				'author' => $updateArray['author'],
				'description' => $updateArray['description'],
				'keywords' => $updateArray['keywords'],
				'robots' => $updateArray['robots'],
				'language' => $updateArray['language'],
				'template' => $updateArray['template'],
				'sibling' => $updateArray['sibling'],
				'parent' => $updateArray['parent'],
				'status' => $updateArray['status'],
				'rank' => $updateArray['rank'],
				'access' => $updateArray['access'],
				'date' => $updateArray['date']
			])
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
		return $this->query()
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
		return $this->query()
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
