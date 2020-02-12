<?php
namespace Redaxscript\Tests\View;

use Redaxscript\Db;
use Redaxscript\Tests\TestCaseAbstract;
use Redaxscript\View;

/**
 * ExtraTest
 *
 * @since 4.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\View\Extra
 * @covers Redaxscript\View\ViewAbstract
 */

class ExtraTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 4.2.0
	 */

	public function setUp() : void
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
		Db::forTablePrefix('extras')
			->create()
			->set(
			[
				'title' => 'Extra Two',
				'alias' => 'extra-two'
			])
			->save();
		Db::forTablePrefix('extras')
			->create()
			->set(
			[
				'title' => 'Extra Three',
				'alias' => 'extra-three',
				'access' => '[1]'
			])
			->save();
		Db::forTablePrefix('extras')
			->create()
			->set(
			[
				'title' => 'Extra Four',
				'alias' => 'extra-four',
				'category' => 1
			])
			->save();
		Db::forTablePrefix('extras')
			->create()
			->set(
			[
				'title' => 'Extra Five',
				'alias' => 'extra-five',
				'article' => 1
			])
			->save();
	}

	/**
	 * tearDown
	 *
	 * @since 4.2.0
	 */

	public function tearDown() : void
	{
		$this->dropDatabase();
	}

	/**
	 * testRender
	 *
	 * @since 4.2.0
	 *
	 * @param array $registryArray
	 * @param array $optionArray
	 * @param int $extraId
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testRender(array $registryArray = [], array $optionArray = [], int $extraId = null, string $expect = null) : void
	{
		/* setup */

		$this->_registry->init($registryArray);
		$extra = new View\Extra($this->_registry, $this->_request, $this->_language, $this->_config);
		$extra->init($optionArray);

		/* actual */

		$actual = $extra->render($extraId);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
