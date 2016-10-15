<?php
namespace Redaxscript\Tests\Console\Command;

use Redaxscript\Config;
use Redaxscript\Console\Command;
use Redaxscript\Registry;
use Redaxscript\Request;
use Redaxscript\Tests\TestCaseAbstract;
use org\bovigo\vfs\vfsStream as Stream;
use org\bovigo\vfs\vfsStreamFile as StreamFile;
use org\bovigo\vfs\vfsStreamWrapper as StreamWrapper;

/**
 * ConfigTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class ConfigTest extends TestCaseAbstract
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
	 * array to restore config
	 *
	 * @var array
	 */

	protected $_configArray = [];

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
		$this->_configArray = $this->_config->get();
	}

	/**
	 * setUpBeforeClass
	 *
	 * @since 3.0.0
	 */

	public static function setUpBeforeClass()
	{
		Stream::setup('root');
		$file = new StreamFile('config.php');
		StreamWrapper::getRoot()->addChild($file);
	}

	/**
	 * tearDown
	 *
	 * @since 3.0.0
	 */

	public function tearDown()
	{
		$this->_request->setServer('argv', null);
		$this->_config->set('dbType', $this->_configArray['dbType']);
		$this->_config->set('dbPassword', $this->_configArray['dbPassword']);
	}

	/**
	 * testNoArgument
	 *
	 * @since 3.0.0
	 */

	public function testNoArgument()
	{
		/* setup */

		$configCommand = new Command\Config($this->_registry, $this->_request, $this->_config);

		/* expect and actual */

		$expect = $configCommand->getHelp();
		$actual = $configCommand->run('cli');

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

		$this->_config->set('dbPassword', 'test');
		$this->_request->setServer('argv',
		[
			'console.php',
			'config',
			'list'
		]);
		$configCommand = new Command\Config($this->_registry, $this->_request, $this->_config);

		/* actual */

		$actual = $configCommand->run('cli');

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

		$this->_config->init(Stream::url('root/config.php'));
		$this->_request->setServer('argv',
		[
			'console.php',
			'config',
			'set',
			'--db-type',
			'sqlite',
			'--db-host',
			'127.0.0.1'
		]);
		$configCommand = new Command\Config($this->_registry, $this->_request, $this->_config);

		/* actual */

		$actual = $configCommand->run('cli');

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

		$this->_config->init(Stream::url('root/config.php'));
		$this->_request->setServer('argv',
		[
			'console.php',
			'config',
			'set',
			'--no-interaction'
		]);
		$configCommand = new Command\Config($this->_registry, $this->_request, $this->_config);

		/* actual */

		$actual = $configCommand->run('cli');

		/* compare */

		$this->assertFalse($actual);
	}

	/**
	 * testParse
	 *
	 * @since 3.0.0
	 */

	public function testParse()
	{
		/* setup */

		$this->_config->init(Stream::url('root/config.php'));
		$this->_request->setServer('argv',
		[
			'console.php',
			'config',
			'parse',
			'--db-url',
			'mysql://root:test@127.0.0.1/test'
		]);
		$configCommand = new Command\Config($this->_registry, $this->_request, $this->_config);

		/* actual */

		$actual = $configCommand->run('cli');

		/* compare */

		$this->assertTrue($actual);
	}

	/**
	 * testParseEnv
	 *
	 * @since 3.0.0
	 */

	public function testParseEnv()
	{
		/* setup */

		$dbUrl = getenv('DB_URL');
		putenv('DB_URL=mysql://root:test@127.0.0.1/test');
		$this->_config->init(Stream::url('root/config.php'));
		$this->_request->setServer('argv',
		[
			'console.php',
			'config',
			'parse',
			'--db-url',
			'DB_URL',
			'--db-env'
		]);
		$configCommand = new Command\Config($this->_registry, $this->_request, $this->_config);

		/* restore */

		if ($dbUrl)
		{
			putenv('DB_URL=' . $dbUrl);
		}

		/* actual */

		$actual = $configCommand->run('cli');

		/* compare */

		$this->assertTrue($actual);
	}

	/**
	 * testParseInvalid
	 *
	 * @since 3.0.0
	 */

	public function testParseInvalid()
	{
		/* setup */

		$this->_config->init(Stream::url('root/config.php'));
		$this->_request->setServer('argv',
		[
			'console.php',
			'config',
			'parse',
			'--no-interaction'
		]);
		$configCommand = new Command\Config($this->_registry, $this->_request, $this->_config);

		/* actual */

		$actual = $configCommand->run('cli');

		/* compare */

		$this->assertFalse($actual);
	}

	/**
	 * testLock
	 *
	 * @since 3.0.0
	 */

	public function testLock()
	{
		/* setup */

		$this->_config->init(Stream::url('root/config.php'));
		$this->_request->setServer('argv',
		[
			'console.php',
			'config',
			'lock'
		]);
		$configCommand = new Command\Config($this->_registry, $this->_request, $this->_config);

		/* actual */

		$actual = $configCommand->run('cli');

		/* compare */

		$this->assertTrue($actual);
	}
}
