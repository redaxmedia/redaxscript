<?php
namespace Redaxscript\Tests\Console\Command;

use Redaxscript\Config;
use Redaxscript\Console\Command;
use Redaxscript\Directory;
use Redaxscript\Registry;
use Redaxscript\Request;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * RestoreTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @requires OS Linux
 */

class RestoreTest extends TestCaseAbstract
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
		$this->_config = Config::getInstance();
	}

	/**
	 * setUpBeforeClass
	 *
	 * @since 3.0.0
	 */

	public static function setUpBeforeClass()
	{
		$rootDirectory = new Directory();
		$rootDirectory->init('.');
		$rootDirectory->create('.restore');
		$rootDirectory->put('.restore/test.sql');
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
	 * tearDownAfterClass
	 *
	 * @since 3.0.0
	 */

	public static function tearDownAfterClass()
	{
		$rootDirectory = new Directory();
		$rootDirectory->init('.');
		$rootDirectory->remove('.restore');
	}

	/**
	 * testNoArgument
	 *
	 * @since 3.0.0
	 */

	public function testNoArgument()
	{
		/* setup */

		$restoreCommand = new Command\Restore($this->_registry, $this->_request, $this->_config);

		/* expect and actual */

		$expect = $restoreCommand->getHelp();
		$actual = $restoreCommand->run('cli');

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
			'restore',
			'database',
			'--directory',
			'.restore',
			'--file',
			'test.sql'
		]);
		$restoreCommand = new Command\Restore($this->_registry, $this->_request, $this->_config);

		/* actual */

		$actual = $restoreCommand->run('cli');

		/* compare */

		$this->assertTrue($actual);
	}

	/**
	 * testDatabaseInvalid
	 *
	 * @since 3.0.0
	 */

	public function testDatabaseInvalid()
	{
		/* setup */

		$this->_request->setServer('argv',
		[
			'console.php',
			'restore',
			'database',
			'--no-interaction'
		]);
		$restoreCommand = new Command\Restore($this->_registry, $this->_request, $this->_config);

		/* actual */

		$actual = $restoreCommand->run('cli');

		/* compare */

		$this->assertFalse($actual);
	}
}
