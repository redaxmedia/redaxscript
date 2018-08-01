<?php
namespace Redaxscript\Tests;

use Redaxscript\Auth;
use Redaxscript\Db;
use Redaxscript\Server;

/**
 * AuthTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Auth
 */

class AuthTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 3.1.0
	 */

	public function setUp()
	{
		parent::setUp();
		$optionArray =
		[
			'adminName' => 'Test',
			'adminUser' => 'test',
			'adminPassword' => 'test',
			'adminEmail' => 'test@test.com'
		];
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawCreate();
		$installer->insertUsers($optionArray);
		Db::forTablePrefix('users')
			->whereIdIs(1)
			->findOne()
			->set('language', 'en')
			->save();
		Db::forTablePrefix('groups')
			->create()
			->set(
			[
				'name' => 'Group One',
				'alias' => 'group-one',
				'description' => 'Unlimited access',
				'categories' => '[1,2,3]',
				'articles' => '[1,2,3]',
				'extras' => '[1,2,3]',
				'comments' => '[1,2,3]',
				'groups' => '[1,2,3]',
				'users' => '[1,2,3]',
				'modules' => '[1,2,3]',
				'settings' => 1,
				'filter' => 0
			])
			->save();
	}

	/**
	 * tearDown
	 *
	 * @since 3.1.0
	 */

	public function tearDown()
	{
		$this->dropDatabase();
	}

	/**
	 * testInit
	 *
	 * @since 3.0.0
	 */

	public function testInit()
	{
		/* setup */

		$root = new Server\Root($this->_request);
		$this->_request->setSession($root->getOutput() . '/auth',
		[
			'user' =>
			[
				'test' => 'test'
			],
			'permission' =>
			[
				'test' => 'test'
			]
		]);
		$auth = new Auth($this->_request);
		$auth->init();

		/* compare */

		$this->assertEquals('test', $auth->getUser('test'));
		$this->assertEquals('test', $auth->getPermission('test'));
	}

	/**
	 * testLogin
	 *
	 * @since 3.0.0
	 */

	public function testLoginAndLogout()
	{
		/* setup */

		$auth = new Auth($this->_request);
		$auth->logout();

		/* compare */

		$this->assertEquals(0, $auth->login());
		$this->assertEquals(1, $auth->login(1));
		$this->assertEquals(1, $auth->logout());
		$this->assertEquals(0, $auth->logout());
	}

	/**
	 * testGetUserInvalid
	 *
	 * @since 3.0.0
	 */

	public function testGetUserInvalid()
	{
		/* setup */

		$auth = new Auth($this->_request);
		$auth->login(1);

		/* actual */

		$actual = $auth->getUser('invalidKey');

		/* compare */

		$this->assertEquals(0, $actual);
	}

	/**
	 * testGetPermission
	 *
	 * @since 3.0.0
	 *
	 * @param string $method
	 * @param array $typeArray
	 * @param string $groups
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testGetPermission(string $method = null, $typeArray = [], string $groups = null)
	{
		/* setup */

		Db::forTablePrefix('users')->whereIdIs(1)->findOne()->set('groups', $groups)->save();
		$auth = new Auth($this->_request);
		$auth->login(1);

		/* process type */

		foreach ($typeArray as $key => $value)
		{
			$this->assertEquals($auth->$method($key), $value);
		}
	}

	/**
	 * testGetFilter
	 *
	 * @since 3.0.0
	 *
	 * @param string $groups
	 * @param bool $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testGetFilter(string $groups = null, bool $expect = null)
	{
		/* setup */

		Db::forTablePrefix('users')->whereIdIs(1)->findOne()->set('groups', $groups)->save();
		$auth = new Auth($this->_request);
		$auth->login(1);

		/* actual */

		$actual = $auth->getFilter();

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
