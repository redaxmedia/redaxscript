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

	public function setUp() : void
	{
		parent::setUp();
		$optionArray = $this->getOptionArray();
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawCreate();
		$installer->insertSettings($optionArray);
		$installer->rawMigrate();
	}

	/**
	 * tearDown
	 *
	 * @since 4.0.0
	 */

	public function tearDown() : void
	{
		$this->dropDatabase();
	}

	/**
	 * testRender
	 *
	 * @since 4.0.0
	 *
	 * @param int $date
	 * @param string $author
	 * @param array $optionArray
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testRender(int $date = null, string $author = null,  array $optionArray = [], string $expect = null) : void
	{
		/* setup */

		$byline = new Byline($this->_registry, $this->_language);
		$byline->init($optionArray);

		/* actual */

		$actual = $byline->render($date, $author);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
