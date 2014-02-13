<?php

/**
 * Redaxscript Autoloader Test
 *
 * @since 2.1.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Gary Aylward
 */

class Redaxscript_Autoloader_Test extends PHPUnit_Framework_TestCase
{
	/**
	 * testArray
	 *
	 * @since 2.1.0
	 */

	public function testArray()
	{
		/* result */

		$result = spl_autoload_functions();

		/* compare */

		$this->assertInternalType('array', $result);
	}
}
?>