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
		$this->_request->setServer('argv', null);
	}

	/**
	 * testNoArgument
	 *
	 * @since 3.0.0
	 */

	public function testNoArgument()
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

	public function testList()
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

		$this->assertString($actual);
	}

	/**
	 * testSet
	 *
	 * @since 3.0.0
	 */

	public function testSet()
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

		/* actual */

		$actual = $settingCommand->run('cli');

		/* compare */

		$this->assertTrue($actual);
	}

	/**
	 * testSetInvalid
	 *
	 * @since 3.0.0
	 */

	public function testSetInvalid()
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

		/* actual */

		$actual = $settingCommand->run('cli');

		/* compare */

		$this->assertFalse($actual);
	}
}
