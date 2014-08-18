<?php
namespace Redaxscript\Tests;
use Redaxscript\Db;
use Redaxscript\Module;

/**
 * ModuleTest
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class ModuleTest extends TestCase
{
	/**
	 * testInstall
	 *
	 * @since 2.2.0
	 */

	public function testInstall()
	{
		/* setup */

		$module = new Module(array(
			'name' => 'Test',
			'alias' => 'test',
		));
		$module->install();

		/* result */

		$result = Db::forPrefixTable('modules')->where('alias', 'test')->findOne()->name;

		/* compare */

		$this->assertEquals('Test', $result);
	}

	/**
	 * testUninstall
	 *
	 * @since 2.2.0
	 */

	public function testUninstall()
	{
		/* setup */

		$module = new Module(array(
			'alias' => 'test',
		));
		$module->uninstall();

		/* result */

		Db::clearCache();
		$result = Db::forPrefixTable('modules')->where('alias', 'test')->findOne();

		/* compare */

		$this->assertFalse(is_object($result));
	}
}
