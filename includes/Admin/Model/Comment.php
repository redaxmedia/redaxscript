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
	 * create the comment by array
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
				'author' => $createArray['author'],
				'email' => $createArray['email'],
				'url' => $createArray['url'],
				'text' => $createArray['text'],
				'language' => $createArray['language'],
				'article' => $createArray['article'],
				'status' => $createArray['status'],
				'rank' => $createArray['rank'],
				'access' => $createArray['access'],
				'date' => $createArray['date']
			])
			->save();
	}

	/**
	 * update the comment by id and array
	 *
	 * @since 4.0.0
	 *
	 * @param int $commentId identifier of the comment
	 * @param array $updateArray
	 *
	 * @return bool
	 */

	public function updateByIdAndArray(int $commentId = null, array $updateArray = []) : bool
	{
		return $this->query()
			->whereIdIs($commentId)
			->findOne()
			->set(
			[
				'author' => $updateArray['author'],
				'email' => $updateArray['email'],
				'url' => $updateArray['url'],
				'text' => $updateArray['text'],
				'language' => $updateArray['language'],
				'article' => $updateArray['article'],
				'status' => $updateArray['status'],
				'rank' => $updateArray['rank'],
				'access' => $updateArray['access'],
				'date' => $updateArray['date']
			])
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
		return $this->query()
			->whereIdIs($commentId)
			->findOne()
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
		return $this->query()
			->whereIdIs($commentId)
			->findOne()
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
		return $this->query()->whereIdIs($commentId)->deleteMany();
	}
}
