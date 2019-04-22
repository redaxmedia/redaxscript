<?php
namespace Redaxscript\Tests\Validator;

use Redaxscript\Tests\TestCaseAbstract;
use Redaxscript\Validator;

/**
 * UrlTest
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 * @author Sven Weingartner
 *
 * @covers Redaxscript\Validator\Url
 */

class UrlTest extends TestCaseAbstract
{
	/**
	 * testUrl
	 *
	 * @since 2.2.0
	 *
	 * @param string $url
	 * @param bool $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testUrl(string $url = null, bool $expect = null) : void
	{
		/* setup */

		$validator = new Validator\Url();

		/* actual */

		$actual = $validator->validate($url);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
