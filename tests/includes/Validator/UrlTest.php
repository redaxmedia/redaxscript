<?php
namespace Redaxscript\Tests\Validator;

use Redaxscript\Tests\TestCase;
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

class UrlTest extends TestCase
{
	/**
	 * providerValidatorUrl
	 *
	 * @since 2.2.0
	 *
	 * @return array
	 */

	public function providerValidatorUrl()
	{
		return $this->getProvider('tests/provider/validator_url.json');
	}

	/**
	 * testUrl
	 *
	 * @since 2.2.0
	 *
	 * @param string $url
	 * @param integer $expect
	 *
	 * @dataProvider providerValidatorUrl
	 */

	public function testUrl($url = null, $expect = null)
	{
		/* setup */

		$validator = new Validator\Url();

		/* result */

		$result = $validator->validate($url);

		/* compare */

		$this->assertEquals($expect, $result);
	}
}
