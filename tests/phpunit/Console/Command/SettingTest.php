<?php
namespace Redaxscript\Tests\Console\Command;

use Redaxscript\Config;
use Redaxscript\Console\Command;
use Redaxscript\Request;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * SettingTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class SettingTest extends TestCaseAbstract
{
	/**
	 * instance of the config class
	 *
	 * @var object
	 */

	protected $_config;

	/**
	 * instance of the request class
	 *
	 * @var object
	 */

	protected $_request;

	/**
	 * setUp
	 *
	 * @since 3.0.0
	 */

	public function setUp()
	{
		$this->_config = Config::getInstance();
		$this->_request = Request::getInstance();
	}

	/**
	 * tearDown
	 *
	 * @since 3.0.0
	 */

	public function tearDown()
	{
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

		$settingCommand = new Command\Setting($this->_config, $this->_request);

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
		$settingCommand = new Command\Setting($this->_config, $this->_request);

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
		$settingCommand = new Command\Setting($this->_config, $this->_request);

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
		$settingCommand = new Command\Setting($this->_config, $this->_request);

		/* actual */

		$actual = $settingCommand->run('cli');

		/* compare */

		$this->assertFalse($actual);
	}
}
