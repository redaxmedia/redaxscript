<?php
namespace Redaxscript\Tests\Model;

use Redaxscript\Db;
use Redaxscript\Model;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * ExtraTest
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Model\Extra
 */

class ExtraTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 4.0.0
	 */

	public function setUp()
	{
		parent::setUp();
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawCreate();
		Db::forTablePrefix('extras')
			->create()
			->set(
			[
				'title' => 'Extra One',
				'alias' => 'extra-one'
			])
			->save();
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
	 * testGetByAlias
	 *
	 * @since 4.0.0
	 *
	 * @param string $extraAlias
	 * @param int $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testGetByAlias(string $extraAlias = null, int $expect = null)
	{
		/* setup */

		$extraModel = new Model\Extra();

		/* actual */

		$actual = $extraModel->getByAlias($extraAlias)->id;

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
