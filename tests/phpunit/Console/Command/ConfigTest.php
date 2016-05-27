<?php
namespace Redaxscript\Tests\Console\Command;

use Redaxscript\Config;
use Redaxscript\Console\Command;
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
	 * array to restore config
	 *
	 * @var array
	 */

	protected $_configArray = array();

	/**
	 * setUp
	 *
	 * @since 3.0.0
	 */

	public function setUp()
	{
		$this->_config = Config::getInstance();
		$this->_request = Request::getInstance();
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
	}

	/**
	 * testNoArgument
	 *
	 * @since 3.0.0
	 */

	public function testNoArgument()
	{
		/* setup */

		$configCommand = new Command\Config($this->_config, $this->_request);

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

		$this->_request->setServer('argv', array(
			'console.php',
			'config',
			'list'
		));
		$configCommand = new Command\Config($this->_config, $this->_request);

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
		$this->_request->setServer('argv', array(
			'console.php',
			'config',
			'set',
			'--db-type',
			'sqlite',
			'--db-host',
			'127.0.0.1'
		));
		$configCommand = new Command\Config($this->_config, $this->_request);

		/* actual */

		$actual = $configCommand->run('cli');

		/* compare */

		$this->assertTrue($actual);
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
		$this->_request->setServer('argv', array(
			'console.php',
			'config',
			'parse',
			'--db-url',
			'mysql://root:test@127.0.0.1/test'
		));
		$configCommand = new Command\Config($this->_config, $this->_request);

		/* actual */

		$actual = $configCommand->run('cli');

		/* compare */

		$this->assertTrue($actual);
	}
}
