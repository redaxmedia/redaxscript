<?php
namespace Redaxscript\Tests\Console\Command;

use Redaxscript\Config;
use Redaxscript\Console\Command;
use Redaxscript\Request;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * BackupTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class BackupTest extends TestCaseAbstract
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

		$backupCommand = new Command\Backup($this->_config, $this->_request);

		/* expect and actual */

		$expect = $backupCommand->getHelp();
		$actual = $backupCommand->run('cli');

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
			'backup',
			'database',
			'--path',
			'/'
		));
		$backupCommand = new Command\Backup($this->_config, $this->_request);

		/* actual */

		$actual = $backupCommand->run('cli');

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

		$this->_request->setServer('argv', array(
			'console.php',
			'backup',
			'database',
			'--no-interaction'
		));
		$backupCommand = new Command\Backup($this->_config, $this->_request);

		/* actual */

		$actual = $backupCommand->run('cli');

		/* compare */

		$this->assertFalse($actual);
	}
}
