<?php
namespace Redaxscript\Tests\Console\Command;

use Redaxscript\Config;
use Redaxscript\Console\Command;
use Redaxscript\Request;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * StatusTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class StatusTest extends TestCaseAbstract
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

		$statusCommand = new Command\Status($this->_config, $this->_request);

		/* expect and actual */

		$expect = $statusCommand->getHelp();
		$actual = $statusCommand->run('cli');

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testDatabase
	 *
	 * @since 3.0.0
	 */

	public function testDatabase()
	{
		/* setup */

		$this->_request->setServer('argv', array(
			'console.php',
			'status',
			'database'
		));
		$configCommand = new Command\Status($this->_config, $this->_request);

		/* actual */

		$actual = $configCommand->run('cli');

		/* compare */

		$this->assertString($actual);
	}

	/**
	 * testSystem
	 *
	 * @since 3.0.0
	 */

	public function testSystem()
	{
		/* setup */

		$this->_request->setServer('argv', array(
			'console.php',
			'status',
			'system'
		));
		$configCommand = new Command\Status($this->_config, $this->_request);

		/* actual */

		$actual = $configCommand->run('cli');

		/* compare */

		$this->assertString($actual);
	}
}
