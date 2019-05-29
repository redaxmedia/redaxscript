<?php
namespace Redaxscript\Admin\Model;

use Redaxscript\Model as BaseModel;

/**
 * parent class to provide the admin article model
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Model
 * @author Henry Ruhs
 */

class Article extends BaseModel\Article
{
	/**
	 * is unique by id and alias
	 *
	 * @since 4.0.0
	 *
	 * @param int $articleId identifier of the article
	 * @param string $articleAlias alias of the article
	 *
	 * @return bool
	 */

	public function isUniqueByIdAndAlias(int $articleId = null, string $articleAlias = null) : bool
	{
		$categoryModel = new Category();
		return !$categoryModel->getByAlias($articleAlias)->id && (!$this->getByAlias($articleAlias)->id || $this->getByAlias($articleAlias)->id === $this->getById($articleId)->id);
	}

	/**
	 * create the article by array
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
	 * update the article by id and array
	 *
	 * @since 4.0.0
	 *
	 * @param int $articleId identifier of the article
	 * @param array $updateArray array of the update
	 *
	 * @return bool
	 */

	public function updateByIdAndArray(int $articleId = null, array $updateArray = []) : bool
	{
		return $this
			->query()
			->whereIdIs($articleId)
			->findOne()
			->set($updateArray)
			->save();
	}

	/**
	 * publish the article by id
	 *
	 * @since 4.0.0
	 *
	 * @param int $articleId identifier of the article
	 *
	 * @return bool
	 */

	public function publishById(int $articleId = null) : bool
	{
		return (bool)$this
			->query()
			->whereAnyIs(
			[
				[
					'id' =>	$articleId
				],
				[
					'sibling' => $articleId
				]
			])
			->findMany()
			->set('status', 1)
			->save();
	}

	/**
	 * publish the article by category
	 *
	 * @since 4.0.0
	 *
	 * @param int $categoryId identifier of the category
	 *
	 * @return bool
	 */

	public function publishByCategory(int $categoryId = null) : bool
	{
		return (bool)$this
			->query()
			->where('category', $categoryId)
			->findMany()
			->set('status', 1)
			->save();
	}

	/**
	 * unpublish the article by id
	 *
	 * @since 4.0.0
	 *
	 * @param int $articleId identifier of the article
	 *
	 * @return bool
	 */

	public function unpublishById(int $articleId = null) : bool
	{
		return (bool)$this
			->query()
			->whereAnyIs(
			[
				[
					'id' =>	$articleId
				],
				[
					'sibling' => $articleId
				]
			])
			->findMany()
			->set('status', 0)
			->save();
	}

	/**
	 * unpublish the article by category
	 *
	 * @since 4.0.0
	 *
	 * @param int $categoryId identifier of the category
	 *
	 * @return bool
	 */

	public function unpublishByCategory(int $categoryId = null) : bool
	{
		return (bool)$this
			->query()
			->where('category', $categoryId)
			->findMany()
			->set('status', 0)
			->save();
	}

	/**
	 * delete the article by id
	 *
	 * @since 4.0.0
	 *
	 * @param int $articleId identifier of the article
	 *
	 * @return bool
	 */

	public function deleteById(int $articleId = null) : bool
	{
		return $this
			->query()
			->whereAnyIs(
			[
				[
					'id' =>	$articleId
				],
				[
					'sibling' => $articleId
				]
			])
			->deleteMany();
	}

	/**
	 * delete the article by category
	 *
	 * @since 4.0.0
	 *
	 * @param int $categoryId identifier of the category
	 *
	 * @return bool
	 */

	public function deleteByCategory(int $categoryId = null) : bool
	{
		return $this
			->query()
			->where('category', $categoryId)
			->deleteMany();
	}
}
