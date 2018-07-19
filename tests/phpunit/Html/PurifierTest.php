<?php
namespace Redaxscript\Tests\Html;

use Redaxscript\Html;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * PurifierTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Html\Purifier
 */

class PurifierTest extends TestCaseAbstract
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
	 * testPurify
	 *
	 * @since 3.0.0
	 *
	 * @param string $html
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testPurify(string $html = null, string $expect = null)
	{
		/* setup */

		$purifier = new Html\Purifier();

		/* actual */

		$actual = $purifier->purify($html);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
