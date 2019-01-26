<?php
namespace Redaxscript\Tests\Model;

use Redaxscript\Db;
use Redaxscript\Model;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * GroupTest
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Model\Group
 */

class GroupTest extends TestCaseAbstract
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
		Db::forTablePrefix('groups')
			->create()
			->set(
			[
				'name' => 'Group One',
				'alias' => 'group-one'
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
	 * @param string $groupAlias
	 * @param int $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testGetByAlias(string $groupAlias = null, int $expect = null)
	{
		/* setup */

		$groupModel = new Model\Group();

		/* actual */

		$actual = $groupModel->getByAlias($groupAlias)->id;

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
