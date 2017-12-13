<?php
namespace Redaxscript\Model;

use Redaxscript\Db;

/**
 * parent class to provide the user model
 *
 * @since 3.3.0
 *
 * @package Redaxscript
 * @category Model
 * @author Henry Ruhs
 */

class User
{
	/**
	 * create the user by array
	 *
	 * @since 3.3.0
	 *
	 * @param array $createArray
	 *
	 * @return bool
	 */

	public function createByArray(array $createArray = []) : bool
	{
		return Db::forTablePrefix('users')
			->create()
			->set(
			[
				'name' => $createArray['name'],
				'user' => $createArray['user'],
				'email' => $createArray['email'],
				'password' => $createArray['password'],
				'language' => $createArray['language'],
				'groups' => $createArray['groups'],
				'status' => $createArray['status']
			])
			->save();
	}

	/**
	 * reset the password by array
	 *
	 * @since 3.3.0
	 *
	 * @param array $resetArray
	 *
	 * @return bool
	 */

	public function resetPasswordByArray(array $resetArray) : bool
	{
		return Db::forTablePrefix('users')
			->whereIdIs($resetArray['id'])
			->where('status', 1)
			->findOne()
			->set('password', $resetArray['password'])
			->save();
	}
}
