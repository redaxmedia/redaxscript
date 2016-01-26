<?php
namespace Redaxscript\Tests\Admin\View\Helper;

use Redaxscript\Admin\View\Helper;
use Redaxscript\Tests\TestCase;

/**
 * OptionTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class OptionTest extends TestCase
{
	/**
	 * providerOption
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerOption()
	{
		return $this->getProvider('tests/provider/Admin/View/Helper/option.json');
	}

	/**
	 * testGetToggleArray
	 *
	 * @since 3.0.0
	 *
	 * @param array $expect
	 *
	 * @dataProvider providerOption
	 */

	public function testGetToggleArray($expect = array())
	{
		/* actual */

		$actual = Helper\Option::getToggleArray();

		/* compare */

		$this->assertEquals($expect['toggle'], $actual);
	}

	/**
	 * testGetStatusArray
	 *
	 * @since 3.0.0
	 *
	 * @param array $expect
	 *
	 * @dataProvider providerOption
	 */

	public function testGetStatusArray($expect = array())
	{
		/* actual */

		$actual = Helper\Option::getStatusArray();

		/* compare */

		$this->assertEquals($expect['status'], $actual);
	}

	/**
	 * testGetLanguageArray
	 *
	 * @since 3.0.0
	 *
	 * @param array $expect
	 *
	 * @dataProvider providerOption
	 */

	public function testGetLanguageArray($expect = array())
	{
		/* actual */

		$actual = Helper\Option::getLanguageArray();

		/* compare */

		$this->assertTrue(!array_diff($expect['language'], $actual));
	}

	/**
	 * testGetTemplateArray
	 *
	 * @since 3.0.0
	 *
	 * @param array $expect
	 *
	 * @dataProvider providerOption
	 */

	public function testGetTemplateArray($expect = array())
	{
		/* actual */

		$actual = Helper\Option::getTemplateArray();

		/* compare */

		$this->assertTrue(!array_diff($expect['template'], $actual));
	}

	/**
	 * testGetContentArray
	 *
	 * @since 3.0.0
	 *
	 * @param array $expect
	 *
	 * @dataProvider providerOption
	 */

	public function testGetContentArray($expect = array())
	{
		/* actual */

		$actual = Helper\Option::getContentArray('articles');

		/* compare */

		$this->assertEquals($expect['content'], $actual);
	}

	/**
	 * testGetAccessArray
	 *
	 * @since 3.0.0
	 *
	 * @param array $expect
	 *
	 * @dataProvider providerOption
	 */

	public function testGetAccessArray($expect = array())
	{
		/* actual */

		$actual = Helper\Option::getAccessArray('groups');

		/* compare */

		$this->assertEquals($expect['access'], $actual);
	}
}
