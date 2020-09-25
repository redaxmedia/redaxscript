<?php
namespace Redaxscript\Tests\Admin\Controller;

use Redaxscript\Admin;
use Redaxscript\Db;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * ExtraTest
 *
 * @since 4.1.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Admin\Controller\Extra
 * @covers Redaxscript\Admin\Controller\ControllerAbstract
 */

class ExtraTest extends TestCaseAbstract
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
		Db::forTablePrefix('extras')
			->create()
			->set(
			[
				'title' => 'Extra One',
				'alias' => 'extra-one'
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
		$extraController = new Admin\Controller\Extra($this->_registry, $this->_request, $this->_language, $this->_config);

		/* actual */

		$actual = $extraController->process('create');

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
		$extraController = new Admin\Controller\Extra($this->_registry, $this->_request, $this->_language, $this->_config);

		/* actual */

		$actual = $extraController->process('update');

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
		$extraController = new Admin\Controller\Extra($this->_registry, $this->_request, $this->_language, $this->_config);

		/* actual */

		$actual = $extraController->process('invalid');

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
