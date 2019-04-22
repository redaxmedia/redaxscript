<?php
namespace Redaxscript\Tests\Detector;

use Redaxscript\Db;
use Redaxscript\Detector;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * DetectorTest
 *
 * @since 2.1.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Detector\DetectorAbstract
 * @covers Redaxscript\Detector\Language
 * @covers Redaxscript\Detector\Template
 */

class DetectorTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 3.1.0
	 */

	public function setUp() : void
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
		Db::forTablePrefix('categories')
			->create()
			->set(
			[
				'title' => 'Category One',
				'alias' => 'category-one'
			])
			->save();
		Db::forTablePrefix('articles')
			->create()
			->set(
			[
				'title' => 'Article One',
				'alias' => 'article-one',
				'language' => 'de',
				'template' => 'wide'
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
	 * testLanguage
	 *
	 * @since 3.0.0
	 *
	 * @param array $registryArray
	 * @param array $queryArray
	 * @param array $sessionArray
	 * @param array $serverArray
	 * @param array $settingArray
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testLanguage(array $registryArray = [], array $queryArray = [], array $sessionArray = [], array $serverArray = [], array $settingArray = [], string $expect = null) : void
	{
		/* setup */

		$this->_registry->init($registryArray);
		$this->_request->set('get', $queryArray);
		$this->_request->set('session', $sessionArray);
		$this->_request->set('server', $serverArray);
		$setting = $this->settingFactory();
		$setting->set('language', $settingArray['language']);
		$detector = new Detector\Language($this->_registry, $this->_request);

		/* actual */

		$actual = $detector->getOutput();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testTemplate
	 *
	 * @since 3.0.0
	 *
	 * @param array $registryArray
	 * @param array $queryArray
	 * @param array $sessionArray
	 * @param array $settingArray
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testTemplate(array $registryArray = [], array $queryArray = [], array $sessionArray = [], array $settingArray = [], string $expect = null) : void
	{
		/* setup */

		$this->_registry->init($registryArray);
		$this->_request->set('get', $queryArray);
		$this->_request->set('session', $sessionArray);
		$setting = $this->settingFactory();
		$setting->set('template', $settingArray['template']);
		$detector = new Detector\Template($this->_registry, $this->_request);

		/* actual */

		$actual = $detector->getOutput();

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}