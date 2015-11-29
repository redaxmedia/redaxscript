<?php
namespace Redaxscript\Tests\View;

use Redaxscript\Tests\TestCase;
use Redaxscript\View;

/**
 * LoginTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class LoginTest extends TestCase
{
	/**
	 * providerRender
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerRender()
	{
		return $this->getProvider('tests/provider/View/login_render.json');
	}

	/**
	 * testRender
	 *
	 * @since 3.0.0
	 *
	 * @param string $expect
	 *
	 * @dataProvider providerRender
	 */

	public function testRender($expect = null)
	{
		/* setup */

		$login = new View\Login();

		/* actual */

		$actual = $login->render();

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
