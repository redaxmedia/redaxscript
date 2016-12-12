<?php
namespace Redaxscript\Tests\Console\Command;

use Redaxscript\Config;
use Redaxscript\Console\Command;
use Redaxscript\Language;
use Redaxscript\Registry;
use Redaxscript\Request;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * HelpTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class HelpTest extends TestCaseAbstract
{
	/**
	 * instance of the registry class
	 *
	 * @var object
	 */

	protected $_registry;

	/**
	 * instance of the request class
	 *
	 * @var object
	 */

	protected $_request;

	/**
	 * instance of the language class
	 *
	 * @var object
	 */

	protected $_language;

	/**
	 * instance of the config class
	 *
	 * @var object
	 */

	protected $_config;

	/**
	 * setUp
	 *
	 * @since 3.0.0
	 */

	public function setUp()
	{
		$this->_registry = Registry::getInstance();
		$this->_request = Request::getInstance();
		$this->_language = Language::getInstance();
		$this->_config = Config::getInstance();
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
	 * testRun
	 *
	 * @since 3.0.0
	 */

	public function testNoArgument()
	{
		/* setup */

		$helpCommand = new Command\Help($this->_registry, $this->_request, $this->_language, $this->_config);

		/* actual */

		$actual = $helpCommand->run('cli');

		/* compare */

		$this->assertString($actual);
	}

	/**
	 * testHelp
	 *
	 * @since 3.0.0
	 */

	public function testHelp()
	{
		/* setup */

		$this->_request->setServer('argv',
		[
			'console.php',
			'help',
			'help'
		]);
		$helpCommand = new Command\Help($this->_registry, $this->_request, $this->_language, $this->_config);

		/* expect and actual */

		$expect = $helpCommand->getHelp();
		$actual = $helpCommand->run('cli');

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
