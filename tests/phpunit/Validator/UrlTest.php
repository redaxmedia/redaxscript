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
 */

class UrlTestAbstract extends TestCaseAbstract
{
	/**
	 * providerUrl
	 *
	 * @since 2.2.0
	 *
	 * @return array
	 */

	public function providerUrl()
	{
		return $this->getProvider('tests/provider/Validator/url.json');
	}

	/**
	 * testUrl
	 *
	 * @since 2.2.0
	 *
	 * @param string $url
	 * @param integer $expect
	 *
	 * @dataProvider providerUrl
	 */

	public function testUrl($url = null, $expect = null)
	{
		/* setup */

		$validator = new Validator\Url();

		/* actual */

		$actual = $validator->validate($url);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
