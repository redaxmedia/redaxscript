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
	 * create the user by array
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
				'name' => $createArray['name'],
				'user' => $createArray['user'],
				'description' => $createArray['description'],
				'password' => $createArray['password'],
				'email' => $createArray['email'],
				'language' => $createArray['language'],
				'status' => $createArray['status'],
				'groups' => $createArray['groups']
			])
			->save();
	}

	/**
	 * update the user by id and array
	 *
	 * @since 4.0.0
	 *
	 * @param int $userId identifier of the user
	 * @param array $updateArray
	 *
	 * @return bool
	 */

	public function updateByIdAndArray(int $userId = null, array $updateArray = []) : bool
	{
		return $this->query()
			->whereIdIs($userId)
			->findOne()
			->set(
			[
				'name' => $updateArray['name'],
				'user' => $updateArray['user'],
				'description' => $updateArray['description'],
				'password' => $updateArray['password'],
				'email' => $updateArray['email'],
				'language' => $updateArray['language'],
				'status' => $updateArray['status'],
				'groups' => $updateArray['groups']
			])
			->save();
	}

	/**
	 * update last by id
	 *
	 * @since 4.0.0
	 *
	 * @param string $userId id of the user
	 * @param string $date
	 *
	 * @return bool
	 */

	public function updateLastById(string $userId = null, string $date = null)
	{
		return $this->query()
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
		return $this->query()
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
		return $this->query()
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
