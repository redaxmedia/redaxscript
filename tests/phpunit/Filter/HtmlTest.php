<?php
namespace Redaxscript\Tests\Filter;

use Redaxscript\Filter;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * HtmlTest
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class HtmlTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 3.1.0
	 */

	public function setUp()
	{
		parent::setUp();
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawCreate();
		$installer->insertSettings(
		[
			'adminName' => 'Test',
			'adminUser' => 'test',
			'adminPassword' => 'test',
			'adminEmail' => 'test@test.com'
		]);
	}

	/**
	 * tearDown
	 *
	 * @since 3.1.0
	 */

	public function tearDown()
	{
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawDrop();
	}

	/**
	 * providerHtml
	 *
	 * @since 2.2.0
	 *
	 * @return array
	 */

	public function providerHtml() : array
	{
		return $this->getProvider('tests/provider/Filter/html.json');
	}

	/**
	 * testHtml
	 *
	 * @since 2.2.0
	 *
	 * @param string $html
	 * @param string $expect
	 *
	 * @dataProvider providerHtml
	 */

	public function testHtml(string $html = null, string $expect = null)
	{
		/* setup */

		$filter = new Filter\Html();

		/* actual */

		$actual = $filter->sanitize($html);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
