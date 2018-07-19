<?php
namespace Redaxscript\Tests\Admin\View;

use Redaxscript\Admin;
use Redaxscript\Db;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * GroupFormTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Admin\View\GroupForm
 * @covers Redaxscript\Admin\View\ViewAbstract
 */

class GroupFormTest extends TestCaseAbstract
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
		Db::forTablePrefix('groups')
			->create()
			->set(
			[
				'name' => 'Group One',
				'alias' => 'group-one',
			])
			->save();
		Db::forTablePrefix('groups')
			->create()
			->set(
			[
				'name' => 'Group Two',
				'alias' => 'group-two',
			])
			->save();
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
	 * testRender
	 *
	 * @since 3.0.0
	 *
	 * @param array $registryArray
	 * @param int $groupId
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testRender(array $registryArray = [], int $groupId = null, array $expectArray = [])
	{
		/* setup */

		$this->_registry->init($registryArray);
		$groupForm = new Admin\View\GroupForm($this->_registry, $this->_language);

		/* actual */

		$actual = $groupForm->render($groupId);

		/* compare */

		$this->assertStringStartsWith($expectArray['start'], $actual);
		$this->assertStringEndsWith($expectArray['end'], $actual);
	}
}
