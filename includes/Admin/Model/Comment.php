<?php
namespace Redaxscript\Admin\Model;

use Redaxscript\Model as BaseModel;

/**
 * parent class to provide the admin comment model
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Model
 * @author Henry Ruhs
 */

class Comment extends BaseModel\Comment
{
	/**
	 * update the comment by id and array
	 *
	 * @since 4.0.0
	 *
	 * @param int $commentId identifier of the comment
	 * @param array $updateArray array of the update
	 *
	 * @return bool
	 */

	public function updateByIdAndArray(int $commentId = null, array $updateArray = []) : bool
	{
		return $this
			->query()
			->whereIdIs($commentId)
			->findOne()
			->set($updateArray)
			->save();
	}

	/**
	 * publish the comment by id
	 *
	 * @since 4.0.0
	 *
	 * @param int $commentId identifier of the comment
	 *
	 * @return bool
	 */

	public function publishById(int $commentId = null) : bool
	{
		return $this
			->query()
			->whereIdIs($commentId)
			->findOne()
			->set('status', 1)
			->save();
	}

	/**
	 * publish the comment by category
	 *
	 * @since 4.0.0
	 *
	 * @param int $categoryId identifier of the category
	 *
	 * @return bool
	 */

	public function publishByCategory(int $categoryId = null) : bool
	{
		$articleModel = new Article();
		$articleArray = $articleModel->query()->where('category', $categoryId)->findFlatArray();
		return (bool)$this
			->query()
			->whereIn('article', $articleArray)
			->findMany()
			->set('status', 1)
			->save();
	}

	/**
	 * publish the comment by article
	 *
	 * @since 4.0.0
	 *
	 * @param int $articleId identifier of the comment
	 *
	 * @return bool
	 */

	public function publishByArticle(int $articleId = null) : bool
	{
		return (bool)$this
			->query()
			->where('article', $articleId)
			->findMany()
			->set('status', 1)
			->save();
	}

	/**
	 * unpublish the comment by id
	 *
	 * @since 4.0.0
	 *
	 * @param int $commentId identifier of the comment
	 *
	 * @return bool
	 */

	public function unpublishById(int $commentId = null) : bool
	{
		return $this
			->query()
			->whereIdIs($commentId)
			->findOne()
			->set('status', 0)
			->save();
	}

	/**
	 * unpublish the comment by category
	 *
	 * @since 4.0.0
	 *
	 * @param int $categoryId identifier of the category
	 *
	 * @return bool
	 */

	public function unpublishByCategory(int $categoryId = null) : bool
	{
		$articleModel = new Article();
		$articleArray = $articleModel->query()->where('category', $categoryId)->findFlatArray();
		return (bool)$this
			->query()
			->whereIn('article', $articleArray)
			->findMany()
			->set('status', 0)
			->save();
	}

	/**
	 * unpublish the comment by article
	 *
	 * @since 4.0.0
	 *
	 * @param int $articleId identifier of the comment
	 *
	 * @return bool
	 */

	public function unpublishByArticle(int $articleId = null) : bool
	{
		return (bool)$this
			->query()
			->where('article', $articleId)
			->findMany()
			->set('status', 0)
			->save();
	}

	/**
	 * delete the comment by id
	 *
	 * @since 4.0.0
	 *
	 * @param int $commentId identifier of the comment
	 *
	 * @return bool
	 */

	public function deleteById(int $commentId = null) : bool
	{
		return $this->query()
			->whereIdIs($commentId)
			->deleteMany();
	}

	/**
	 * delete the comment by category
	 *
	 * @since 4.0.0
	 *
	 * @param int $categoryId identifier of the category
	 *
	 * @return bool
	 */

	public function deleteByCategory(int $categoryId = null) : bool
	{
		$articleModel = new Article();
		$articleArray = $articleModel->query()->where('category', $categoryId)->findFlatArray();
		return $this
			->query()
			->whereIn('article', $articleArray)
			->deleteMany();
	}

	/**
	 * delete the comment by article
	 *
	 * @since 4.0.0
	 *
	 * @param int $articleId identifier of the article
	 *
	 * @return bool
	 */

	public function deleteByArticle(int $articleId = null) : bool
	{
		return $this
			->query()
			->where('article', $articleId)
			->deleteMany();
	}
}
