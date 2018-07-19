<?php
namespace Redaxscript\Tests\Admin\View;

use Redaxscript\Admin;
use Redaxscript\Db;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * ExtraFormTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Admin\View\ExtraForm
 * @covers Redaxscript\Admin\View\ViewAbstract
 */

class ExtraFormTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 3.1.0
	 */

	public function setUp()
	{
		parent::setUp();
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawCreate();
		Db::forTablePrefix('extras')
			->create()
			->set(
			[
				'title' => 'Extra One',
				'alias' => 'extra-one',
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
	 * testRender
	 *
	 * @since 3.0.0
	 *
	 * @param array $registryArray
	 * @param int $extraId
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testRender(array $registryArray = [], int $extraId = null, array $expectArray = [])
	{
		/* setup */

		$this->_registry->init($registryArray);
		$extraForm = new Admin\View\ExtraForm($this->_registry, $this->_language);

		/* actual */

		$actual = $extraForm->render($extraId);

		/* compare */

		$this->assertStringStartsWith($expectArray['start'], $actual);
		$this->assertStringEndsWith($expectArray['end'], $actual);
	}
}
