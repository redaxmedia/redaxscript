<?php
namespace Redaxscript\Tests\Console\Command;

use Redaxscript\Config;
use Redaxscript\Console\Command;
use Redaxscript\Language;
use Redaxscript\Registry;
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
	 * testNoArgument
	 *
	 * @since 3.0.0
	 */

	public function testNoArgument()
	{
		/* setup */

		$statusCommand = new Command\Status($this->_registry, $this->_request, $this->_language, $this->_config);

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

		$this->_request->setServer('argv',
		[
			'console.php',
			'status',
			'database'
		]);
		$statusCommand = new Command\Status($this->_registry, $this->_request, $this->_language, $this->_config);

		/* actual */

		$actual = $statusCommand->run('cli');

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

		$this->_registry->set('apacheModuleArray',
		[
			'mod_deflate',
			'mod_headers',
			'mod_rewrite'
		]);
		$this->_request->setServer('argv',
		[
			'console.php',
			'status',
			'system'
		]);
		$statusCommand = new Command\Status($this->_registry, $this->_request, $this->_language, $this->_config);

		/* actual */

		$actual = $statusCommand->run('cli');

		/* compare */

		$this->assertString($actual);
	}
}
