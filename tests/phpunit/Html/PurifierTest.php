<?php
namespace Redaxscript\Tests\Filter;

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
	 * providerPurifier
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerPurifier() : array
	{
		return $this->getProvider('tests/provider/Html/purifier.json');
	}

	/**
	 * testPurifier
	 *
	 * @since 3.0.0
	 *
	 * @param string $html
	 * @param string $expect
	 *
	 * @dataProvider providerPurifier
	 */

	public function testPurifier(string $html = null, string $expect = null)
	{
		/* setup */

		$purifier = new Html\Purifier();

		/* actual */

		$actual = $purifier->purify($html);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
