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
	 * @param array $createArray array of the create
	 *
	 * @return bool
	 */

	public function createByArray(array $createArray = []) : bool
	{
		return $this->query()
			->create()
			->set($createArray)
			->save();
	}

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
		return $this->query()
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
