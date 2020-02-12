<?php
namespace Redaxscript\Tests\Admin\View\Helper;

use Redaxscript\Admin\View\Helper;
use Redaxscript\Db;
use Redaxscript\Tests\TestCaseAbstract;
use function array_diff;

/**
 * OptionTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Admin\View\Helper\Option
 */

class OptionTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 3.1.0
	 */

	public function setUp() : void
	{
		parent::setUp();
		$optionArray = $this->getOptionArray();
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

	public function tearDown() : void
	{
		$this->dropDatabase();
	}

	/**
	 * testGetRobotArray
	 *
	 * @since 3.0.0
	 *
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testGetRobotArray(array $expectArray = []) : void
	{
		/* setup */

		$helperOption = new Helper\Option($this->_language);

		/* actual */

		$actualArray = $helperOption->getRobotArray();

		/* compare */

		$this->assertEquals($expectArray['robot'], $actualArray);
	}

	/**
	 * testGetZoneArray
	 *
	 * @since 4.0.0
	 */

	public function testGetZoneArray() : void
	{
		/* setup */

		$helperOption = new Helper\Option($this->_language);

		/* actual */

		$actualArray = $helperOption->getZoneArray();

		/* compare */

		$this->assertContains('UTC', $actualArray);
	}

	/**
	 * testGetTimeArray
	 *
	 * @since 3.0.0
	 *
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testGetTimeArray(array $expectArray = []) : void
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
	 * @dataProvider providerAutoloader
	 */

	public function testGetDateArray(array $expectArray = []) : void
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
	 * @dataProvider providerAutoloader
	 */

	public function testGetOrderArray(array $expectArray = []) : void
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
	 * @dataProvider providerAutoloader
	 */

	public function testGetCaptchaArray(array $expectArray = []) : void
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
	 * @dataProvider providerAutoloader
	 */

	public function testGetPermissionArray(array $expectArray = []) : void
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
	 * @dataProvider providerAutoloader
	 */

	public function testGetLanguageArray(array $expectArray = []) : void
	{
		/* setup */

		$helperOption = new Helper\Option($this->_language);

		/* actual */

		$actualArray = $helperOption->getLanguageArray();

		/* compare */

		$this->assertNotTrue(array_diff($expectArray['language'], $actualArray));
	}

	/**
	 * testGetTemplateArray
	 *
	 * @since 3.0.0
	 *
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testGetTemplateArray(array $expectArray = []) : void
	{
		/* setup */

		$helperOption = new Helper\Option($this->_language);

		/* actual */

		$actualArray = $helperOption->getTemplateArray();

		/* compare */

		$this->assertNotTrue(array_diff($expectArray['template'], $actualArray));
	}

	/**
	 * testGetGroupArray
	 *
	 * @since 3.0.0
	 *
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testGetGroupArray(array $expectArray = []) : void
	{
		/* setup */

		$helperOption = new Helper\Option($this->_language);

		/* actual */

		$actualArray = $helperOption->getGroupArray();

		/* compare */

		$this->assertEquals($expectArray['group'], $actualArray);
	}
}
