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
	 * testGetVisibleArray
	 *
	 * @since 3.0.0
	 *
	 * @param array $expect
	 *
	 * @dataProvider providerOption
	 */

	public function testGetVisibleArray($expect = array())
	{
		/* actual */

		$actual = Helper\Option::getVisibleArray();

		/* compare */

		$this->assertEquals($expect['visible'], $actual);
	}

	/**
	 * testGetRobotArray
	 *
	 * @since 3.0.0
	 *
	 * @param array $expect
	 *
	 * @dataProvider providerOption
	 */

	public function testGetRobotArray($expect = array())
	{
		/* actual */

		$actual = Helper\Option::getRobotArray();

		/* compare */

		$this->assertEquals($expect['robot'], $actual);
	}

	/**
	 * testGetTimeArray
	 *
	 * @since 3.0.0
	 *
	 * @param array $expect
	 *
	 * @dataProvider providerOption
	 */

	public function testGetTimeArray($expect = array())
	{
		/* actual */

		$actual = Helper\Option::getTimeArray();

		/* compare */

		$this->assertEquals($expect['time'], $actual);
	}

	/**
	 * testGetDateArray
	 *
	 * @since 3.0.0
	 *
	 * @param array $expect
	 *
	 * @dataProvider providerOption
	 */

	public function testGetDateArray($expect = array())
	{
		/* actual */

		$actual = Helper\Option::getDateArray();

		/* compare */

		$this->assertEquals($expect['date'], $actual);
	}

	/**
	 * testGetOrderArray
	 *
	 * @since 3.0.0
	 *
	 * @param array $expect
	 *
	 * @dataProvider providerOption
	 */

	public function testGetOrderArray($expect = array())
	{
		/* actual */

		$actual = Helper\Option::getOrderArray();

		/* compare */

		$this->assertEquals($expect['order'], $actual);
	}

	/**
	 * testGetCaptchaArray
	 *
	 * @since 3.0.0
	 *
	 * @param array $expect
	 *
	 * @dataProvider providerOption
	 */

	public function testGetCaptchaArray($expect = array())
	{
		/* actual */

		$actual = Helper\Option::getCaptchaArray();

		/* compare */

		$this->assertEquals($expect['captcha'], $actual);
	}

	/**
	 * testGetPermissionArray
	 *
	 * @since 3.0.0
	 *
	 * @param array $expect
	 *
	 * @dataProvider providerOption
	 */

	public function testGetPermissionArray($expect = array())
	{
		/* actual */

		$actual['content'] = Helper\Option::getPermissionArray();
		$actual['module'] = Helper\Option::getPermissionArray('modules');
		$actual['setting'] = Helper\Option::getPermissionArray('settings');

		/* compare */

		$this->assertEquals($expect['permission']['content'], $actual['content']);
		$this->assertEquals($expect['permission']['module'], $actual['module']);
		$this->assertEquals($expect['permission']['setting'], $actual['setting']);
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
