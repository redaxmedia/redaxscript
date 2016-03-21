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
	 * tearDownAfterClass
	 *
	 * @since 3.0.0
	 */

	public static function tearDownAfterClass()
	{
		Db::forTablePrefix('users')->whereIdIs(1)->findOne()->set('groups', '1')->save();
	}

	/**
	 * providerGetPermission
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerGetPermission()
	{
		return $this->getProvider('tests/provider/auth_get_permission.json');
	}

	/**
	 * providerGetFilter
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerGetFilter()
	{
		return $this->getProvider('tests/provider/auth_get_filter.json');
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
	 * testGetPermission
	 *
	 * @since 3.0.0
	 *
	 * @param string $table
	 * @param string $groups
	 * @param boolean $expect
	 *
	 * @dataProvider providerGetPermission
	 */

	public function testGetPermission($table = null, $groups = null, $expect = null)
	{
		/* setup */

		Db::forTablePrefix('users')->whereIdIs(1)->findOne()->set('groups', $groups)->save();
		$auth = new Auth($this->_request);
		$auth->login(1);

		/* actual */

		$actual = $auth->getPermissionNew($table);

		/* compare */

		$this->assertEquals($actual, $expect);
	}

	/**
	 * testGetFilter
	 *
	 * @since 3.0.0
	 *
	 * @param string $groups
	 * @param boolean $expect
	 *
	 * @dataProvider providerGetFilter
	 */

	public function testGetFilter($groups = null, $expect = null)
	{
		/* setup */

		Db::forTablePrefix('users')->whereIdIs(1)->findOne()->set('groups', $groups)->save();
		$auth = new Auth($this->_request);
		$auth->login(1);

		/* actual */

		$actual = $auth->getFilter();

		/* compare */

		$this->assertEquals($actual, $expect);
	}
}