<?php
namespace Redaxscript\Tests\Console\Command;

use org\bovigo\vfs\vfsStream as Stream;
use org\bovigo\vfs\vfsStreamFile as StreamFile;
use org\bovigo\vfs\vfsStreamWrapper as StreamWrapper;
use Redaxscript\Console\Command;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * ConfigTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Console\Command\Config
 * @covers Redaxscript\Console\Command\CommandAbstract
 */

class ConfigTest extends TestCaseAbstract
{
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

	public function setUp() : void
	{
		parent::setUp();
		Stream::setup('root');
		$file = new StreamFile('config.php');
		StreamWrapper::getRoot()->addChild($file);
		$this->_configArray = $this->_config->getArray();
	}

	/**
	 * tearDown
	 *
	 * @since 3.1.0
	 */

	public function tearDown() : void
	{
		$this->_config->set('dbType', $this->_configArray['dbType']);
		$this->_config->set('dbHost', $this->_configArray['dbHost']);
		$this->_config->set('dbPrefix', $this->_configArray['dbPrefix']);
		$this->_config->set('dbName', $this->_configArray['dbName']);
		$this->_config->set('dbUser', $this->_configArray['dbUser']);
		$this->_config->set('dbPassword', $this->_configArray['dbPassword']);
		$this->_config->set('lock', $this->_configArray['lock']);
		$this->_request->setServer('argv', null);
	}

	/**
	 * testNoArgument
	 *
	 * @since 3.0.0
	 */

	public function testNoArgument() : void
	{
		/* setup */

		$configCommand = new Command\Config($this->_registry, $this->_request, $this->_language, $this->_config);

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

	public function testList() : void
	{
		/* setup */

		$this->_config->set('dbPassword', 'test');
		$this->_request->setServer('argv',
		[
			'console.php',
			'config',
			'list'
		]);
		$configCommand = new Command\Config($this->_registry, $this->_request, $this->_language, $this->_config);

		/* actual */

		$actual = $configCommand->run('cli');

		/* compare */

		$this->assertIsString($actual);
	}

	/**
	 * testSet
	 *
	 * @since 3.0.0
	 */

	public function testSet() : void
	{
		/* setup */

		$this->_config->init(Stream::url('root' . DIRECTORY_SEPARATOR . 'config.php'));
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
		$configCommand = new Command\Config($this->_registry, $this->_request, $this->_language, $this->_config);

		/* expect and actual */

		$expect = $configCommand->success();
		$actual = $configCommand->run('cli');

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testSetInvalid
	 *
	 * @since 3.0.0
	 */

	public function testSetInvalid() : void
	{
		/* setup */

		$this->_config->init(Stream::url('root' . DIRECTORY_SEPARATOR . 'config.php'));
		$this->_request->setServer('argv',
		[
			'console.php',
			'config',
			'set',
			'--no-interaction'
		]);
		$configCommand = new Command\Config($this->_registry, $this->_request, $this->_language, $this->_config);

		/* expect and actual */

		$expect = $configCommand->error();
		$actual = $configCommand->run('cli');

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testParse
	 *
	 * @since 3.0.0
	 */

	public function testParse() : void
	{
		/* setup */

		$this->_config->init(Stream::url('root' . DIRECTORY_SEPARATOR . 'config.php'));
		$this->_request->setServer('argv',
		[
			'console.php',
			'config',
			'parse',
			'--db-url',
			'$DB_URL'
		]);
		$configCommand = new Command\Config($this->_registry, $this->_request, $this->_language, $this->_config);

		/* expect and actual */

		$expect = $configCommand->success();
		$actual = $configCommand->run('cli');

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testParseInvalid
	 *
	 * @since 3.0.0
	 */

	public function testParseInvalid() : void
	{
		/* setup */

		$this->_config->init(Stream::url('root' . DIRECTORY_SEPARATOR . 'config.php'));
		$this->_request->setServer('argv',
		[
			'console.php',
			'config',
			'parse',
			'--no-interaction'
		]);
		$configCommand = new Command\Config($this->_registry, $this->_request, $this->_language, $this->_config);

		/* expect and actual */

		$expect = $configCommand->error();
		$actual = $configCommand->run('cli');

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testLock
	 *
	 * @since 3.0.0
	 */

	public function testLock() : void
	{
		/* setup */

		$this->_config->init(Stream::url('root' . DIRECTORY_SEPARATOR . 'config.php'));
		$this->_request->setServer('argv',
		[
			'console.php',
			'config',
			'lock'
		]);
		$configCommand = new Command\Config($this->_registry, $this->_request, $this->_language, $this->_config);

		/* expect and actual */

		$expect = $configCommand->success();
		$actual = $configCommand->run('cli');

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
