<?php
namespace Redaxscript\Tests\Module;

use Redaxscript\Db;
use Redaxscript\Model;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * GroupTest
 *
 * @since 3.3.0
 *
 * @package Redaxscript
 * @group Tests
 * @author Henry Ruhs
 */

class GroupTest extends TestCaseAbstract
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
		Db::forTablePrefix('groups')
			->create()
			->set(
			[
				'name' => 'Group One',
				'alias' => 'group-one'
			])
			->save();
		Db::forTablePrefix('groups')
			->create()
			->set(
			[
				'name' => 'Group Two',
				'alias' => 'group-two'
			])
			->save();
		Db::forTablePrefix('groups')
			->create()
			->set(
			[
				'name' => 'Group Three',
				'alias' => 'group-three'
			])
			->save();
	}

	/**
	 * tearDown
	 *
	 * @since 3.3.0
	 */

	public function tearDown()
	{
		$this->dropDatabase();
	}

	/**
	 * providerGroupGetId
	 *
	 * @since 3.3.0
	 *
	 * @return array
	 */

	public function providerGroupGetId() : array
	{
		return $this->getProvider('tests/provider/Model/group_get_id.json');
	}

	/**
	 * testGetIdByAlias
	 *
	 * @since 3.3.0
	 *
	 * @param string $alias
	 * @param int $expect
	 *
	 * @dataProvider providerGroupGetId
	 */

	public function testGetIdByAlias(string $alias = null, int $expect = null)
	{
		/* setup */

		$groupModel = new Model\Group();

		/* actual */

		$actual = $groupModel->getIdByAlias($alias);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
