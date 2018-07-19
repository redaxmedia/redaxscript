<?php
namespace Redaxscript\Tests\View\Helper;

use Redaxscript\Tests\TestCaseAbstract;
use Redaxscript\View\Helper\Byline;

/**
 * BylineTest
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\View\Helper\Byline
 */

class BylineTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 4.0.0
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
	 * @since 4.0.0
	 */

	public function tearDown()
	{
		$this->dropDatabase();
	}

	/**
	 * testRender
	 *
	 * @since 4.0.0
	 *
	 * @param string $author
	 * @param string $date
	 * @param array $optionArray
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testRender(string $author = null, string $date = null, array $optionArray = [], string $expect = null)
	{
		/* setup */

		$byline = new Byline($this->_registry, $this->_language);
		$byline->init($optionArray);

		/* actual */

		$actual = $byline->render($author, $date);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
