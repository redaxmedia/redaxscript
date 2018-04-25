<?php
namespace Redaxscript\Tests\Navigation;

use Redaxscript\Navigation;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * LanguageTest
 *
 * @since 3.3.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class LanguageTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 3.3.0
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
	}

	/**
	 * tearDown
	 *
	 * @since 3.3.0
	 */

	public function tearDown()
	{
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawDrop();
	}

	/**
	 * providerRender
	 *
	 * @since 3.3.0
	 *
	 * @return array
	 */

	public function providerRender() : array
	{
		return $this->getProvider('tests/provider/Navigation/language_render.json');
	}

	/**
	 * testRender
	 *
	 * @since 3.3.0
	 *
	 * @param array $registryArray
	 * @param array $optionArray
	 * @param string $expect
	 *
	 * @dataProvider providerRender
	 */

	public function testRender(array $registryArray = [], array $optionArray = [], string $expect = null)
	{
		/* setup */

		$this->_registry->init($registryArray);
		$navigation = new Navigation\Language($this->_registry, $this->_language);
		$navigation->init($optionArray);

		/* actual */

		$actual = $navigation;

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
