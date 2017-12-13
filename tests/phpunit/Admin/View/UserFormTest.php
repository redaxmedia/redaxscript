<?php
namespace Redaxscript\Tests\Admin\View;

use Redaxscript\Admin;
use Redaxscript\Db;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * UserFormTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class UserFormTest extends TestCaseAbstract
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
		Db::forTablePrefix('users')
			->create()
			->set(
			[
				'name' => 'User One',
				'user' => 'user-one'
			])
			->save();
		Db::forTablePrefix('users')
			->create()
			->set(
			[
				'name' => 'User Two',
				'user' => 'user-two'
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
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawDrop();
	}

	/**
	 * providerRender
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerRender() : array
	{
		return $this->getProvider('tests/provider/Admin/View/user_form_render.json');
	}

	/**
	 * testRender
	 *
	 * @since 3.0.0
	 *
	 * @param array $registryArray
	 * @param int $userId
	 * @param array $expectArray
	 *
	 * @dataProvider providerRender
	 */

	public function testRender(array $registryArray = [], int $userId = null, array $expectArray = [])
	{
		/* setup */

		$this->_registry->init($registryArray);
		$userForm = new Admin\View\UserForm($this->_registry, $this->_language);

		/* actual */

		$actual = $userForm->render($userId);

		/* compare */

		$this->assertStringStartsWith($expectArray['start'], $actual);
		$this->assertStringEndsWith($expectArray['end'], $actual);
	}
}
