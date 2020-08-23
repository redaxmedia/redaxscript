<?php
namespace Redaxscript\Tests\Admin\Controller;

use Redaxscript\Admin;
use Redaxscript\Db;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * GroupTest
 *
 * @since 4.1.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Admin\Controller\Group
 * @covers Redaxscript\Admin\Controller\ControllerAbstract
 */

class GroupTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 4.1.0
	 */

	public function setUp() : void
	{
		parent::setUp();
		$optionArray = $this->getOptionArray();
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawCreate();
		$installer->insertSettings($optionArray);
		$installer->rawMigrate();
		Db::forTablePrefix('groups')
			->create()
			->set(
			[
				'name' => 'Group One',
				'alias' => 'group-one'
			])
			->save();
	}

	/**
	 * tearDown
	 *
	 * @since 4.1.0
	 */

	public function tearDown() : void
	{
		$this->dropDatabase();
	}

	/**
	 * testCreate
	 *
	 * @since 4.1.0
	 *
	 * @param array $registryArray
	 * @param array $postArray
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testCreate(array $registryArray = [], array $postArray = [], string $expect = null) : void
	{
		/* setup */

		$this->_registry->init($registryArray);
		$this->_request->set('post', $postArray);
		$groupController = new Admin\Controller\Group($this->_registry, $this->_request, $this->_language, $this->_config);

		/* actual */

		$actual = $groupController->process('create');

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testUpdate
	 *
	 * @since 4.1.0
	 *
	 * @param array $registryArray
	 * @param array $postArray
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testUpdate(array $registryArray = [], array $postArray = [], string $expect = null) : void
	{
		/* setup */

		$this->_registry->init($registryArray);
		$this->_request->set('post', $postArray);
		$groupController = new Admin\Controller\Group($this->_registry, $this->_request, $this->_language, $this->_config);

		/* actual */

		$actual = $groupController->process('update');

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testInvalid
	 *
	 * @since 4.1.0
	 *
	 * @param array $registryArray
	 * @param array $postArray
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testInvalid(array $registryArray = [], array $postArray = [], string $expect = null) : void
	{
		/* setup */

		$this->_registry->init($registryArray);
		$this->_request->set('post', $postArray);
		$groupController = new Admin\Controller\Group($this->_registry, $this->_request, $this->_language, $this->_config);

		/* actual */

		$actual = $groupController->process('invalid');

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
