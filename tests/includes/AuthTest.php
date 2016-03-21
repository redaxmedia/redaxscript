<?php
namespace Redaxscript\Tests;

use Redaxscript\Auth;
use Redaxscript\Db;
use Redaxscript\Request;

/**
 * AuthTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class AuthTest extends TestCase
{
	/**
	 * instance of the request class
	 *
	 * @var object
	 */

	protected $_request;

	/**
	 * setUp
	 *
	 * @since 3.0.0
	 */

	protected function setUp()
	{
		$this->_request = Request::getInstance();
	}

	/**
	 * setUpBeforeClass
	 *
	 * @since 3.0.0
	 */

	public static function setUpBeforeClass()
	{
		Db::forTablePrefix('groups')
			->create()
			->set(array(
				'alias' => 'new',
				'categories' => '1',
				'articles' => '1',
				'extras' => '1',
				'comments' => '1',
				'groups' => '1',
				'users' => '1',
				'modules' => '1',
				'settings' => 1,
				'filter' => 0
			))->save();
		Db::forTablePrefix('groups')
			->create()
			->set(array(
				'alias' => 'edit',
				'categories' => '2',
				'articles' => '2',
				'extras' => '2',
				'comments' => '2',
				'groups' => '2',
				'users' => '2',
				'modules' => '2',
				'settings' => 1,
				'filter' => 0
			))->save();
		Db::forTablePrefix('groups')
			->create()
			->set(array(
				'alias' => 'delete',
				'categories' => '3',
				'articles' => '3',
				'extras' => '3',
				'comments' => '3',
				'groups' => '3',
				'users' => '3',
				'modules' => '3',
				'settings' => 1,
				'filter' => 0
			))->save();
	}

	/**
	 * tearDownAfterClass
	 *
	 * @since 3.0.0
	 */

	public static function tearDownAfterClass()
	{
		Db::forTablePrefix('groups')->where('alias', 'new')->deleteMany();
		Db::forTablePrefix('groups')->where('alias', 'edit')->deleteMany();
		Db::forTablePrefix('groups')->where('alias', 'delete')->deleteMany();
	}

	/**
	 * testInit
	 *
	 * @since 3.0.0
	 */

	public function testInit()
	{
		/* setup */

		$auth = new Auth($this->_request);
		$auth->init();

		/* actual */

		$actual = $auth;

		/* compare */

		$this->assertInstanceOf('Redaxscript\Auth', $actual);
	}

	/**
	 * testLogin
	 *
	 * @since 3.0.0
	 */

	public function testLogin()
	{
		/* setup */

		$auth = new Auth($this->_request);

		/* actual */

		$actual = $auth->login(1);

		/* compare */

		$this->assertTrue($actual);
	}

	/**
	 * testGetPermissionNewFor
	 *
	 * @since 3.0.0
	 */

	public function testGetPermissionNewFor()
	{
		/* setup */

		$auth = new Auth($this->_request);
		$auth->login(1);

		/* actual */

		$actual = $auth->getPermissionNew('articles');

		/* compare */

		$this->assertTrue($actual);
	}
}