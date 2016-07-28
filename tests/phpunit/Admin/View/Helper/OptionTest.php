<?php
namespace Redaxscript\Tests\Admin\View\Helper;

use Redaxscript\Admin\View\Helper;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * OptionTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class OptionTest extends TestCaseAbstract
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
	 * @param array $expectArray
	 *
	 * @dataProvider providerOption
	 */

	public function testGetToggleArray($expectArray = [])
	{
		/* actual */

		$actualArray = Helper\Option::getToggleArray();

		/* compare */

		$this->assertEquals($expectArray['toggle'], $actualArray);
	}

	/**
	 * testGetVisibleArray
	 *
	 * @since 3.0.0
	 *
	 * @param array $expectArray
	 *
	 * @dataProvider providerOption
	 */

	public function testGetVisibleArray($expectArray = [])
	{
		/* actual */

		$actualArray = Helper\Option::getVisibleArray();

		/* compare */

		$this->assertEquals($expectArray['visible'], $actualArray);
	}

	/**
	 * testGetRobotArray
	 *
	 * @since 3.0.0
	 *
	 * @param array $expectArray
	 *
	 * @dataProvider providerOption
	 */

	public function testGetRobotArray($expectArray = [])
	{
		/* actual */

		$actualArray = Helper\Option::getRobotArray();

		/* compare */

		$this->assertEquals($expectArray['robot'], $actualArray);
	}

	/**
	 * testGetTimeArray
	 *
	 * @since 3.0.0
	 *
	 * @param array $expectArray
	 *
	 * @dataProvider providerOption
	 */

	public function testGetTimeArray($expectArray = [])
	{
		/* actual */

		$actualArray = Helper\Option::getTimeArray();

		/* compare */

		$this->assertEquals($expectArray['time'], $actualArray);
	}

	/**
	 * testGetDateArray
	 *
	 * @since 3.0.0
	 *
	 * @param array $expectArray
	 *
	 * @dataProvider providerOption
	 */

	public function testGetDateArray($expectArray = [])
	{
		/* actual */

		$actualArray = Helper\Option::getDateArray();

		/* compare */

		$this->assertEquals($expectArray['date'], $actualArray);
	}

	/**
	 * testGetOrderArray
	 *
	 * @since 3.0.0
	 *
	 * @param array $expectArray
	 *
	 * @dataProvider providerOption
	 */

	public function testGetOrderArray($expectArray = [])
	{
		/* actual */

		$actualArray = Helper\Option::getOrderArray();

		/* compare */

		$this->assertEquals($expectArray['order'], $actualArray);
	}

	/**
	 * testGetCaptchaArray
	 *
	 * @since 3.0.0
	 *
	 * @param array $expectArray
	 *
	 * @dataProvider providerOption
	 */

	public function testGetCaptchaArray($expectArray = [])
	{
		/* actual */

		$actualArray = Helper\Option::getCaptchaArray();

		/* compare */

		$this->assertEquals($expectArray['captcha'], $actualArray);
	}

	/**
	 * testGetPermissionArray
	 *
	 * @since 3.0.0
	 *
	 * @param array $expectArray
	 *
	 * @dataProvider providerOption
	 */

	public function testGetPermissionArray($expectArray = [])
	{
		/* actual */

		$actualArray = [
			'content' => Helper\Option::getPermissionArray(),
			'module' => Helper\Option::getPermissionArray('modules'),
			'setting' => Helper\Option::getPermissionArray('settings')
		];

		/* compare */

		$this->assertEquals($expectArray['permission']['content'], $actualArray['content']);
		$this->assertEquals($expectArray['permission']['module'], $actualArray['module']);
		$this->assertEquals($expectArray['permission']['setting'], $actualArray['setting']);
	}

	/**
	 * testGetLanguageArray
	 *
	 * @since 3.0.0
	 *
	 * @param array $expectArray
	 *
	 * @dataProvider providerOption
	 */

	public function testGetLanguageArray($expectArray = [])
	{
		/* actual */

		$actualArray = Helper\Option::getLanguageArray();

		/* compare */

		$this->assertTrue(!array_diff($expectArray['language'], $actualArray));
	}

	/**
	 * testGetTemplateArray
	 *
	 * @since 3.0.0
	 *
	 * @param array $expectArray
	 *
	 * @dataProvider providerOption
	 */

	public function testGetTemplateArray($expectArray = [])
	{
		/* actual */

		$actualArray = Helper\Option::getTemplateArray();

		/* compare */

		$this->assertTrue(!array_diff($expectArray['template'], $actualArray));
	}

	/**
	 * testGetContentArray
	 *
	 * @since 3.0.0
	 *
	 * @param array $expectArray
	 *
	 * @dataProvider providerOption
	 */

	public function testGetContentArray($expectArray = [])
	{
		/* actual */

		$actualArray = Helper\Option::getContentArray('articles');

		/* compare */

		$this->assertEquals($expectArray['content'], $actualArray);
	}

	/**
	 * testGetAccessArray
	 *
	 * @since 3.0.0
	 *
	 * @param array $expectArray
	 *
	 * @dataProvider providerOption
	 */

	public function testGetAccessArray($expectArray = [])
	{
		/* actual */

		$actualArray = Helper\Option::getAccessArray('groups');

		/* compare */

		$this->assertEquals($expectArray['access'], $actualArray);
	}
}
