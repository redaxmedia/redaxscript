<?php
namespace Redaxscript\Tests\Admin\View\Helper;

use Redaxscript\Admin\View\Helper;
use Redaxscript\Db;
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
	 * setUp
	 *
	 * @since 3.1.0
	 */

	public function setUp()
	{
		parent::setUp();
		$optionArray =
		[
			'adminName' => 'Test',
			'adminUser' => 'test',
			'adminPassword' => 'test',
			'adminEmail' => 'test@test.com'
		];
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawCreate();
		$installer->insertSettings($optionArray);
		Db::forTablePrefix('articles')
			->create()
			->set(
			[
				'title' => 'Article One',
				'alias' => 'article-one'
			])
			->save();
		Db::forTablePrefix('articles')
			->create()
			->set(
			[
				'title' => 'Article Two',
				'alias' => 'article-two'
			])
			->save();
		Db::forTablePrefix('articles')
			->create()
			->set(
			[
				'title' => 'Article Three',
				'alias' => 'article-three'
			])
			->save();
		Db::forTablePrefix('groups')
			->create()
			->set(
			[
				'name' => 'Group One',
				'alias' => 'group-one'
			])
			->save();
		Db::forTablePrefix('groups')
			->create()
			->set(
			[
				'name' => 'Group Two',
				'alias' => 'group-two'
			])
			->save();
	}

	/**
	 * tearDown
	 *
	 * @since 3.1.0
	 */

	public function tearDown()
	{
		$this->dropDatabase();
	}

	/**
	 * providerOption
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerOption() : array
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

	public function testGetToggleArray(array $expectArray = [])
	{
		/* setup */

		$helperOption = new Helper\Option($this->_language);

		/* actual */

		$actualArray = $helperOption->getToggleArray();

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

	public function testGetVisibleArray(array $expectArray = [])
	{
		/* setup */

		$helperOption = new Helper\Option($this->_language);

		/* actual */

		$actualArray = $helperOption->getVisibleArray();

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

	public function testGetRobotArray(array $expectArray = [])
	{
		/* setup */

		$helperOption = new Helper\Option($this->_language);

		/* actual */

		$actualArray = $helperOption->getRobotArray();

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

	public function testGetTimeArray(array $expectArray = [])
	{
		/* setup */

		$helperOption = new Helper\Option($this->_language);

		/* actual */

		$actualArray = $helperOption->getTimeArray();

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

	public function testGetDateArray(array $expectArray = [])
	{
		/* setup */

		$helperOption = new Helper\Option($this->_language);

		/* actual */

		$actualArray = $helperOption->getDateArray();

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

	public function testGetOrderArray(array $expectArray = [])
	{
		/* setup */

		$helperOption = new Helper\Option($this->_language);

		/* actual */

		$actualArray = $helperOption->getOrderArray();

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

	public function testGetCaptchaArray(array $expectArray = [])
	{
		/* setup */

		$helperOption = new Helper\Option($this->_language);

		/* actual */

		$actualArray = $helperOption->getCaptchaArray();

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

	public function testGetPermissionArray(array $expectArray = [])
	{
		/* setup */

		$helperOption = new Helper\Option($this->_language);

		/* actual */

		$actualArray =
		[
			'content' => $helperOption->getPermissionArray(),
			'module' => $helperOption->getPermissionArray('modules'),
			'setting' => $helperOption->getPermissionArray('settings')
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

	public function testGetLanguageArray(array $expectArray = [])
	{
		/* setup */

		$helperOption = new Helper\Option($this->_language);

		/* actual */

		$actualArray = $helperOption->getLanguageArray();

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

	public function testGetTemplateArray(array $expectArray = [])
	{
		/* setup */

		$helperOption = new Helper\Option($this->_language);

		/* actual */

		$actualArray = $helperOption->getTemplateArray();

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

	public function testGetContentArray(array $expectArray = [])
	{
		/* setup */

		$helperOption = new Helper\Option($this->_language);

		/* actual */

		$actualArray = $helperOption->getContentArray('articles',
		[
			3,
		]);

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

	public function testGetAccessArray(array $expectArray = [])
	{
		/* setup */

		$helperOption = new Helper\Option($this->_language);

		/* actual */

		$actualArray = $helperOption->getAccessArray('groups');

		/* compare */

		$this->assertEquals($expectArray['access'], $actualArray);
	}
}
