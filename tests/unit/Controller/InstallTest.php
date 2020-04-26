<?php
namespace Redaxscript\Tests\Controller;

use org\bovigo\vfs\vfsStream as Stream;
use org\bovigo\vfs\vfsStreamFile as StreamFile;
use org\bovigo\vfs\vfsStreamWrapper as StreamWrapper;
use Redaxscript\Controller;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * InstallTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 * @author Balázs Szilágyi
 *
 * @covers Redaxscript\Controller\ControllerAbstract
 * @covers Redaxscript\Controller\Install
 *
 * @requires OS Linux
 */

class InstallTest extends TestCaseAbstract
{
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
	}

	/**
	 * tearDown
	 *
	 * @since 3.1.0
	 */

	public function tearDown() : void
	{
		$this->dropDatabase();
	}

	/**
	 * testProcess
	 *
	 * @since 3.0.0
	 *
	 * @param array $postArray
	 * @param string $method
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testProcess(array $postArray = [], string $method = null, string $expect = null) : void
	{
		/* setup */

		$postArray['db-type'] = $postArray['db-type'] === '%CURRENT%' ? $this->_config->get('dbType') : $postArray['db-type'];
		$postArray['db-host'] = $postArray['db-host'] === '%CURRENT%' ? $this->_config->get('dbHost') : $postArray['db-host'];
		$postArray['db-name'] = $postArray['db-name'] === '%CURRENT%' ? $this->_config->get('dbName') : $postArray['db-name'];
		$postArray['db-user'] = $postArray['db-user'] === '%CURRENT%' ? $this->_config->get('dbUser') : $postArray['db-user'];
		$postArray['db-password'] = $postArray['db-password'] === '%CURRENT%' ? $this->_config->get('dbPassword') : $postArray['db-password'];
		$postArray['db-prefix'] = $postArray['db-prefix'] === '%CURRENT%' ? $this->_config->get('dbPrefix') : $postArray['db-prefix'];
		$this->_request->set('post', $postArray);
		$this->_config->init(Stream::url('root' . DIRECTORY_SEPARATOR . 'config.php'));
		if ($method)
		{
			$installController = $this
				->getMockBuilder('Redaxscript\Controller\Install')
				->setConstructorArgs(
				[
					$this->_registry,
					$this->_request,
					$this->_language,
					$this->_config
				])
				->setMethods(
				[
					$method
				])
				->getMock();

			/* override */

			$installController
				->expects($this->any())
				->method($method);
		}
		else
		{
			$installController = new Controller\Install($this->_registry, $this->_request, $this->_language, $this->_config);
		}

		/* actual */

		$actual = $installController->process();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testValidateDatabase
	 *
	 * @since 3.0.0
	 *
	 * @param array $postArray
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testValidateDatabase(array $postArray = [], array $expectArray = []) : void
	{
		/* setup */

		$installController = new Controller\Install($this->_registry, $this->_request, $this->_language, $this->_config);

		/* actual */

		$actualArray = $this->callMethod($installController, '_validateDatabase',
		[
			$postArray
		]);

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}

	/**
	 * testValidateAccount
	 *
	 * @since 3.0.0
	 *
	 * @param array $postArray
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testValidateAccount(array $postArray = [], array $expectArray = []) : void
	{
		/* setup */

		$installController = new Controller\Install($this->_registry, $this->_request, $this->_language, $this->_config);

		/* actual */

		$actualArray = $this->callMethod($installController, '_validateAccount',
		[
			$postArray
		]);

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}

	/**
	 * testInstall
	 *
	 * @since 3.0.0
	 *
	 * @param array $installArray
	 * @param bool $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testInstall(array $installArray = [], bool $expect = null) : void
	{
		/* setup */

		$installController = new Controller\Install($this->_registry, $this->_request, $this->_language, $this->_config);

		/* actual */

		$actual = $this->callMethod($installController, '_install',
		[
			$installArray
		]);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
