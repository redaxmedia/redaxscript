<?php
namespace Redaxscript\Tests\Model;

use Redaxscript\Db;
use Redaxscript\Model;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * UserTest
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Model\User
 */

class UserTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 4.0.0
	 */

	public function setUp() : void
	{
		parent::setUp();
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawCreate();
		Db::forTablePrefix('users')
			->create()
			->set(
			[
				'name' => 'Test One',
				'user' => 'testOne',
				'password' => 'testOne',
				'email' => 'test-one@redaxscript.com'
			])
			->save();
		Db::forTablePrefix('users')
			->create()
			->set(
			[
				'name' => 'Test Two',
				'user' => 'testTwo',
				'password' => 'testTwo',
				'email' => 'test-two@redaxscript.com'
			])
			->save();
	}

	/**
	 * tearDown
	 *
	 * @since 4.0.0
	 */

	public function tearDown() : void
	{
		$this->dropDatabase();
	}

	/**
	 * testGetByUser
	 *
	 * @since 4.0.0
	 *
	 * @param string $user
	 * @param int $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testGetByUser(string $user = null, int $expect = null) : void
	{
		/* setup */

		$userModel = new Model\User();

		/* actual */

		$actual = $userModel->getByUser($user)->id;

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testGetByUserOrEmail
	 *
	 * @since 4.0.0
	 *
	 * @param string $user
	 * @param string $email
	 * @param int $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testGetByUserOrEmail(string $user = null, string $email = null, int $expect = null) : void
	{
		/* setup */

		$userModel = new Model\User();

		/* actual */

		$actual = $userModel->getByUserOrEmail($user, $email)->id;

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testCreateByArray
	 *
	 * @since 4.0.0
	 *
	 * @param array $createArray
	 * @param bool $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testCreateByArray(array $createArray = [], bool $expect = null) : void
	{
		/* setup */

		$userModel = new Model\User();

		/* actual */

		$actual = $userModel->createByArray($createArray);

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testResetPasswordById
	 *
	 * @since 4.0.0
	 *
	 * @param int $userId
	 * @param string $password
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testResetPasswordById(int $userId = null, string $password = null, string $expect = null) : void
	{
		/* setup */

		$userModel = new Model\User();
		$userModel->resetPasswordById($userId, $password);

		/* actual */

		$actual = $userModel->query()->whereIdIs($userId)->findOne()->password;

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
