<?php
namespace Redaxscript\Tests\Console\Command;

use Redaxscript\Console\Command;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * SettingTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Console\Command\CommandAbstract
 * @covers Redaxscript\Console\Command\Setting
 */

class SettingTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 3.1.0
	 */

	public function setUp() : void
	{
		parent::setUp();
		$optionArray = $this->getOptionArray();
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

	public function tearDown() : void
	{
		$this->dropDatabase();
		$this->_request->setServer('argv', null);
	}

	/**
	 * testNoArgument
	 *
	 * @since 3.0.0
	 */

	public function testNoArgument() : void
	{
		/* setup */

		$settingCommand = new Command\Setting($this->_registry, $this->_request, $this->_language, $this->_config);

		/* expect and actual */

		$expect = $settingCommand->getHelp();
		$actual = $settingCommand->run('cli');

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testList
	 *
	 * @since 3.0.0
	 */

	public function testList() : void
	{
		/* setup */

		$this->_request->setServer('argv',
		[
			'console.php',
			'setting',
			'list'
		]);
		$settingCommand = new Command\Setting($this->_registry, $this->_request, $this->_language, $this->_config);

		/* actual */

		$actual = $settingCommand->run('cli');

		/* compare */

		$this->assertIsString($actual);
	}

	/**
	 * testSet
	 *
	 * @since 3.0.0
	 */

	public function testSet() : void
	{
		/* setup */

		$this->_request->setServer('argv',
		[
			'console.php',
			'setting',
			'set',
			'--key',
			'copyright',
			'--value',
			'Redaxscript'
		]);
		$settingCommand = new Command\Setting($this->_registry, $this->_request, $this->_language, $this->_config);

		/* expect and actual */

		$expect = $settingCommand->success();
		$actual = $settingCommand->run('cli');

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testSetInvalid
	 *
	 * @since 3.0.0
	 */

	public function testSetInvalid() : void
	{
		/* setup */

		$this->_request->setServer('argv',
		[
			'console.php',
			'setting',
			'set',
			'--no-interaction'
		]);
		$settingCommand = new Command\Setting($this->_registry, $this->_request, $this->_language, $this->_config);

		/* expect and actual */

		$expect = $settingCommand->error();
		$actual = $settingCommand->run('cli');

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
