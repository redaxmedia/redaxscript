<?php
namespace Redaxscript\Model;

/**
 * parent class to provide the user model
 *
 * @since 3.3.0
 *
 * @package Redaxscript
 * @category Model
 * @author Henry Ruhs
 */

class User extends ModelAbstract
{
	/**
	 * name of the table
	 *
	 * @var string
	 */

	protected $_table = 'users';

	/**
	 * get the user by user
	 *
	 * @since 4.0.0
	 *
	 * @param string $user name of the user
	 *
	 * @return object
	 */

	public function getByUser(string $user = null)
	{
		return $this->query()->where('user', $user)->findOne();
	}

	/**
	 * get the user by user or email
	 *
	 * @since 4.0.0
	 *
	 * @param string $user name of the user
	 * @param string $email email of the user
	 *
	 * @return object
	 */

	public function getByUserOrEmail(string $user = null, string $email = null)
	{
		return $this
			->query()
			->whereAnyIs(
			[
				[
					'user' => $user
				],
				[
					'email' => $email
				]
			])
			->where('status', 1)
			->findOne();
	}

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
		return $this->query()
			->create()
			->set($createArray)
			->save();
	}

	/**
	 * reset the password by id
	 *
	 * @since 3.3.0
	 *
	 * @param int $userId identifier of the user
	 * @param string $password
	 *
	 * @return bool
	 */

	public function resetPasswordById(int $userId = null, string $password = null) : bool
	{
		return $this->query()
			->whereIdIs($userId)
			->findOne()
			->set('password', $password)
			->save();
	}
}
