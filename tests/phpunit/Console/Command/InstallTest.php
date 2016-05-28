<?php
namespace Redaxscript\Tests\Console\Command;

use Redaxscript\Config;
use Redaxscript\Console\Command;
use Redaxscript\Request;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * InstallTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class InstallTest extends TestCaseAbstract
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
		$this->_config->set('dbPrefix', 'console_');
	}

	/**
	 * tearDown
	 *
	 * @since 3.0.0
	 */

	public function tearDown()
	{
		$this->_request->setServer('argv', null);
		$this->_config->set('dbPrefix', $this->_configArray['dbPrefix']);
	}

	/**
	 * testNoArgument
	 *
	 * @since 3.0.0
	 */

	public function testNoArgument()
	{
		/* setup */

		$installCommand = new Command\Install($this->_config, $this->_request);

		/* expect and actual */

		$expect = $installCommand->getHelp();
		$actual = $installCommand->run('cli');

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
			'install',
			'database',
			'--admin-name',
			'test',
			'--admin-user',
			'test',
			'--admin-password',
			'test',
			'--admin-email',
			'test@test.com'
		));
		$configCommand = new Command\Install($this->_config, $this->_request);

		/* actual */

		$actual = $configCommand->run('cli');

		/* compare */

		$this->assertTrue($actual);
	}
}
