<?php
namespace Redaxscript\Tests\Head;

use Redaxscript\Db;
use Redaxscript\Head;
use Redaxscript\Registry;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * TitleTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Balázs Szilágyi
 */

class TitleTest extends TestCaseAbstract
{
	/**
	 * instance of the registry class
	 *
	 * @var object
	 */

	protected $_registry;

	/**
	 * array to restore setting
	 *
	 * @var array
	 */

	protected $_settingArray;

	/**
	 * setUp
	 *
	 * @since 3.0.0
	 */

	public function setUp()
	{
		$this->_registry = Registry::getInstance();
		$this->_settingArray =
		[
			'title' => Db::getSetting('title'),
			'description' => Db::getSetting('description'),
			'divider' => Db::getSetting('divider')
		];
	}

	/**
	 * tearDown
	 *
	 * @since 3.0.0
	 */

	public function tearDown()
	{
		Db::setSetting('title', $this->_settingArray['title']);
		Db::setSetting('description', $this->_settingArray['description']);
		Db::setSetting('divider', $this->_settingArray['divider']);
	}

	/**
	 * providerRender
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerRender()
	{
		return $this->getProvider('tests/provider/Head/title_render.json');
	}

	/**
	 * testRender
	 *
	 * @since 3.0.0
	 *
	 * @param array $registryArray
	 * @param array $settingArray
	 * @param string $expect
	 *
	 * @dataProvider providerRender
	 */

	public function testRender($registryArray = [], $settingArray = [], $expect = null)
	{
		/* setup */

		Db::setSetting('title', $settingArray['title']);
		Db::setSetting('description', $settingArray['description']);
		Db::setSetting('divider', $settingArray['divider']);
		$this->_registry->init($registryArray);
		$title = new Head\Title($this->_registry);

		/* actual */

		$actual = $title;

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
