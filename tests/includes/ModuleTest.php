<?php

/**
 * Redaxscript Module Test
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Test
 * @author Henry Ruhs
 */

class Redaxscript_Module_Test extends PHPUnit_Framework_TestCase
{
	/**
	 * testInstall
	 *
	 * @since 2.2.0
	 */

	public function testInstall()
	{
		/* setup */

		$module = new Redaxscript_Module(array(
			'name' => 'Test',
			'alias' => 'test',
		));
		$module->install();

		/* result */

		$result = Redaxscript_Db::forPrefixTable('modules')->where('alias', 'test')->findOne()->name;

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

		$module = new Redaxscript_Module(array(
			'alias' => 'test',
		));
		$module->uninstall();

		/* result */

		Redaxscript_Db::clearCache();
		$result = Redaxscript_Db::forPrefixTable('modules')->where('alias', 'test')->findOne();

		/* compare */

		$this->assertFalse(is_object($result));
	}
}
