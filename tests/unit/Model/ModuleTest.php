<?php
namespace Redaxscript\Tests\Model;

use Redaxscript\Model;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * ModuleTest
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Model\Module
 */

class ModuleTest extends TestCaseAbstract
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

		$moduleModel = new Model\Module();

		/* actual */

		$actual = $moduleModel->createByArray($createArray);

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testDeleteByAlias
	 *
	 * @since 4.0.0
	 *
	 * @param string $moduleAlias
	 * @param bool $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testDeleteByAlias(string $moduleAlias = null, bool $expect = null) : void
	{
		/* setup */

		$moduleModel = new Model\Module();

		/* actual */

		$actual = $moduleModel->deleteByAlias($moduleAlias);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
