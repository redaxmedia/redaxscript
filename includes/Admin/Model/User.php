<?php
namespace Redaxscript\Admin\Model;

use Redaxscript\Model as BaseModel;

/**
 * parent class to provide the admin user model
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Model
 * @author Henry Ruhs
 */

class User extends BaseModel\User
{
	/**
	 * update the user by id and array
	 *
	 * @since 4.0.0
	 *
	 * @param int $userId identifier of the user
	 * @param array $updateArray array of the update
	 *
	 * @return bool
	 */

	public function updateByIdAndArray(int $userId = null, array $updateArray = []) : bool
	{
		return $this
			->query()
			->whereIdIs($userId)
			->findOne()
			->set($updateArray)
			->save();
	}

	/**
	 * update last by id
	 *
	 * @since 4.0.0
	 *
	 * @param int $userId id of the user
	 * @param int $date timestamp of the date
	 *
	 * @return bool
	 */

	public function updateLastById(int $userId = null, int $date = null) : bool
	{
		return $this
			->query()
			->where('id', $userId)
			->findOne()
			->set('last', $date)
			->save();
	}

	/**
	 * enable the user by id
	 *
	 * @since 4.0.0
	 *
	 * @param int $userId identifier of the user
	 *
	 * @return bool
	 */

	public function enableById(int $userId = null) : bool
	{
		return $this
			->query()
			->whereIdIs($userId)
			->findOne()
			->set('status', 1)
			->save();
	}

	/**
	 * disable the user by id
	 *
	 * @since 4.0.0
	 *
	 * @param int $userId identifier of the user
	 *
	 * @return bool
	 */

	public function disableById(int $userId = null) : bool
	{
		return $this
			->query()
			->whereIdIs($userId)
			->findOne()
			->set('status', 0)
			->save();
	}

	/**
	 * delete the user by id
	 *
	 * @since 4.0.0
	 *
	 * @param int $userId identifier of the user
	 *
	 * @return bool
	 */

	public function deleteById(int $userId = null) : bool
	{
		return $this->query()->whereIdIs($userId)->deleteMany();
	}
}
