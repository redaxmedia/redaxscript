<?php
namespace Redaxscript\Tests\Admin\View;

use Redaxscript\Admin;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * ModuleFormTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Admin\View\ModuleForm
 * @covers Redaxscript\Admin\View\ViewAbstract
 */

class ModuleFormTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 3.1.0
	 */

	public function setUp()
	{
		parent::setUp();
		$this->createDatabase();
		$this->installTestDummy();
	}

	/**
	 * tearDown
	 *
	 * @since 3.1.0
	 */

	public function tearDown()
	{
		$this->uninstallTestDummy();
		$this->dropDatabase();
	}

	/**
	 * testRender
	 *
	 * @since 3.0.0
	 *
	 * @param array $registryArray
	 * @param int $moduleId
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testRender(array $registryArray = [], int $moduleId = null, array $expectArray = [])
	{
		/* setup */

		$this->_registry->init($registryArray);
		$moduleForm = new Admin\View\ModuleForm($this->_registry, $this->_language);

		/* actual */

		$actual = $moduleForm->render($moduleId);

		/* compare */

		$this->assertStringStartsWith($expectArray['start'], $actual);
		$this->assertStringEndsWith($expectArray['end'], $actual);
	}
}
