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
	 * create the article by array
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
				'text' => $createArray['text'],
				'language' => $createArray['language'],
				'template' => $createArray['template'],
				'sibling' => $createArray['sibling'],
				'category' => $createArray['category'],
				'headline' => $createArray['headline'],
				'byline' => $createArray['byline'],
				'comments' => $createArray['comments'],
				'status' => $createArray['status'],
				'rank' => $createArray['rank'],
				'access' => $createArray['access'],
				'date' => $createArray['date']
			])
			->save();
	}

	/**
	 * update the article by id and array
	 *
	 * @since 4.0.0
	 *
	 * @param int $articleId identifier of the article
	 * @param array $updateArray
	 *
	 * @return bool
	 */

	public function updateByIdAndArray(int $articleId = null, array $updateArray = []) : bool
	{
		return $this->query()
			->whereIdIs($articleId)
			->findOne()
			->set(
			[
				'title' => $updateArray['title'],
				'alias' => $updateArray['alias'],
				'author' => $updateArray['author'],
				'description' => $updateArray['description'],
				'keywords' => $updateArray['keywords'],
				'robots' => $updateArray['robots'],
				'text' => $updateArray['text'],
				'language' => $updateArray['language'],
				'template' => $updateArray['template'],
				'sibling' => $updateArray['sibling'],
				'category' => $updateArray['category'],
				'headline' => $updateArray['headline'],
				'byline' => $updateArray['byline'],
				'comments' => $updateArray['comments'],
				'status' => $updateArray['status'],
				'rank' => $updateArray['rank'],
				'access' => $updateArray['access'],
				'date' => $updateArray['date']
			])
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
		return $this->query()
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
		return $this->query()
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
