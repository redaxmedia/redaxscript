<?php
namespace Redaxscript\Tests\Console\Command;

use Redaxscript\Config;
use Redaxscript\Console\Command;
use Redaxscript\Language;
use Redaxscript\Registry;
use Redaxscript\Request;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * CacheTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class CacheTest extends TestCaseAbstract
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

		$cacheCommand = new Command\Cache($this->_registry, $this->_request, $this->_language, $this->_config);

		/* expect and actual */

		$expect = $cacheCommand->getHelp();
		$actual = $cacheCommand->run('cli');

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testClear
	 *
	 * @since 3.0.0
	 */

	public function testClear()
	{
		/* setup */

		$this->_request->setServer('argv',
		[
			'console.php',
			'cache',
			'clear',
			'--directory',
			'cache',
			'--extension',
			'css',
			'--bundle',
			'base.min.css'
		]);
		$cacheCommand = new Command\Cache($this->_registry, $this->_request, $this->_language, $this->_config);

		/* actual */

		$actual = $cacheCommand->run('cli');

		/* compare */

		$this->assertTrue($actual);
	}

	/**
	 * testClearInvalid
	 *
	 * @since 3.0.0
	 */

	public function testClearInvalid()
	{
		/* setup */

		$this->_request->setServer('argv',
		[
			'console.php',
			'cache',
			'clear-invalid',
			'--directory',
			'cache',
			'--extension',
			'js',
			'--lifetime',
			'3600'
		]);
		$cacheCommand = new Command\Cache($this->_registry, $this->_request, $this->_language, $this->_config);

		/* actual */

		$actual = $cacheCommand->run('cli');

		/* compare */

		$this->assertTrue($actual);
	}
}
