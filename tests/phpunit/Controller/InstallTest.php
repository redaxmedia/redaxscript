<?php
namespace Redaxscript\Tests\Controller;

use Redaxscript\Controller;
use Redaxscript\Tests\TestCaseAbstract;
use org\bovigo\vfs\vfsStream as Stream;
use org\bovigo\vfs\vfsStreamFile as StreamFile;
use org\bovigo\vfs\vfsStreamWrapper as StreamWrapper;

/**
 * InstallTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 *
 * @author Henry Ruhs
 * @author Balázs Szilágyi
 */

class InstallTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 3.0.0
	 */

	public function setUp()
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

	public function tearDown()
	{
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawDrop();
	}

	/**
	 * providerProcess
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerProcess() : array
	{
		return $this->getProvider('tests/provider/Controller/install_process.json');
	}

	/**
	 * providerProcessFailure
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerProcessFailure() : array
	{
		return $this->getProvider('tests/provider/Controller/install_process_failure.json');
	}

	/**
	 * providerValidateDatabase
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerValidateDatabase() : array
	{
		return $this->getProvider('tests/provider/Controller/install_validate_database.json');
	}

	/**
	 * providerValidateAccount
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerValidateAccount() : array
	{
		return $this->getProvider('tests/provider/Controller/install_validate_account.json');
	}

	/**
	 * providerInstall
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerInstall() : array
	{
		return $this->getProvider('tests/provider/Controller/install_install.json');
	}

	/**
	 * testProcess
	 *
	 * @since 3.0.0
	 *
	 * @param array $postArray
	 * @param string $expect
	 *
	 * @dataProvider providerProcess
	 */

	public function testProcess(array $postArray = [], string $expect = null)
	{
		/* setup */

		$postArray['db-type'] = $postArray['db-type'] === '%CURRENT%' ? $this->_config->get('dbType') : $postArray['db-type'];
		$postArray['db-host'] = $postArray['db-host'] === '%CURRENT%' ? $this->_config->get('dbHost') : $postArray['db-host'];
		$postArray['db-name'] = $postArray['db-name'] === '%CURRENT%' ? $this->_config->get('dbName') : $postArray['db-name'];
		$postArray['db-user'] = $postArray['db-user'] === '%CURRENT%' ? $this->_config->get('dbUser') : $postArray['db-user'];
		$postArray['db-password'] = $postArray['db-password'] === '%CURRENT%' ? $this->_config->get('dbPassword') : $postArray['db-password'];
		$postArray['db-prefix'] = $postArray['db-prefix'] === '%CURRENT%' ? $this->_config->get('dbPrefix') : $postArray['db-prefix'];
		$this->_request->set('post', $postArray);
		$this->_config->init(Stream::url('root/config.php'));
		$controllerInstall = new Controller\Install($this->_registry, $this->_request, $this->_language, $this->_config);

		/* actual */

		$actual = $controllerInstall->process();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testProcessFailure
	 *
	 * @since 3.0.0
	 *
	 * @param array $postArray
	 * @param string $method
	 * @param string $expect
	 *
	 * @dataProvider providerProcessFailure
	 */

	public function testProcessFailure(array $postArray = [], string $method = null, string $expect = null)
	{
		/* setup */

		$postArray['db-type'] = $this->_config->get('dbType');
		$postArray['db-host'] = $this->_config->get('dbHost');
		$postArray['db-name'] = $this->_config->get('dbName');
		$postArray['db-user'] = $this->_config->get('dbUser');
		$postArray['db-password'] = $this->_config->get('dbPassword');
		$postArray['db-prefix'] = $this->_config->get('dbPrefix');
		$this->_request->set('post', $postArray);
		$this->_config->init(Stream::url('root/config.php'));
		$stub = $this
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

		$stub
			->expects($this->any())
			->method($method)
			->will($this->returnValue(false));

		/* actual */

		$actual = $stub->process();

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
	 * @dataProvider providerValidateDatabase
	 */

	public function testValidateDatabase(array $postArray = [], array $expectArray = [])
	{
		/* setup */

		$controllerInstall = new Controller\Install($this->_registry, $this->_request, $this->_language, $this->_config);

		/* actual */

		$actualArray = $this->callMethod($controllerInstall, '_validateDatabase',
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
	 * @dataProvider providerValidateAccount
	 */

	public function testValidateAccount(array $postArray = [], array $expectArray = [])
	{
		/* setup */

		$controllerInstall = new Controller\Install($this->_registry, $this->_request, $this->_language, $this->_config);

		/* actual */

		$actualArray = $this->callMethod($controllerInstall, '_validateAccount',
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
	 * @dataProvider providerInstall
	 */

	public function testInstall(array $installArray = [], bool $expect = null)
	{
		/* setup */

		$controllerInstall = new Controller\Install($this->_registry, $this->_request, $this->_language, $this->_config);

		/* actual */

		$actual = $this->callMethod($controllerInstall, '_install',
		[
			$installArray
		]);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
