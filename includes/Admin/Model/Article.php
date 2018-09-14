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
		return !$categoryModel->getByAlias($articleAlias)->id && !$this->getByAlias($articleAlias)->id || $this->getByAlias($articleAlias)->id === $this->getById($articleId)->id;
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
		return $this
			->query()
			->whereIdIs($articleId)
			->findOne()
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
		return $this
			->query()
			->whereIdIs($articleId)
			->findOne()
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
		return $this->query()->whereIdIs($articleId)->deleteMany();
	}
}
