<?php
namespace Redaxscript\Tests\Console\Command;

use Redaxscript\Config;
use Redaxscript\Console\Command;
use Redaxscript\Request;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * UninstallTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class UninstallTest extends TestCaseAbstract
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

		$uninstallCommand = new Command\Uninstall($this->_config, $this->_request);

		/* expect and actual */

		$expect = $uninstallCommand->getHelp();
		$actual = $uninstallCommand->run('cli');

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
