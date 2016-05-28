<?php
namespace Redaxscript\Tests\Console;

use Redaxscript\Config;
use Redaxscript\Console;
use Redaxscript\Request;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * ConsoleTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class ConsoleTest extends TestCaseAbstract
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
		$this->_request->setPost('argv', null);
	}

	/**
	 * testCli
	 *
	 * @since 3.0.0
	 */

	public function testCli()
	{
		/* setup */

		$this->_request->setServer('argv', array(
			'console.php',
			'help'
		));
		$console = new Console\Console($this->_config, $this->_request);

		/* actual */

		$actual = $console->init('cli');

		/* compare */

		$this->assertString($actual);
	}

	/**
	 * testTemplate
	 *
	 * @since 3.0.0
	 */

	public function testTemplate()
	{
		/* setup */

		$this->_request->setPost('argv', 'help');
		$console = new Console\Console($this->_config, $this->_request);

		/* actual */

		$actual = $console->init('template');

		/* compare */

		$this->assertString($actual);
	}

	/**
	 * testInvalid
	 *
	 * @since 3.0.0
	 */

	public function testInvalid()
	{
		/* setup */

		$console = new Console\Console($this->_config, $this->_request);

		/* actual */

		$actual = $console->init();

		/* compare */

		$this->assertFalse($actual);
	}
}
