<?php
namespace Redaxscript\Tests\Controller;

use Redaxscript\Config;
use Redaxscript\Controller;
use Redaxscript\Installer;
use Redaxscript\Language;
use Redaxscript\Registry;
use Redaxscript\Request;
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
 * @author Balázs Szilágyi
 */

class InstallTest extends TestCaseAbstract
{
	/**
	 * instance of the registry class
	 *
	 * @var object
	 */

	protected $_registry;

	/**
	 * instance of the language class
	 *
	 * @var object
	 */

	protected $_language;

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
		$this->_language = Language::getInstance();
		$this->_request = Request::getInstance();
		$this->_config = Config::getInstance();
		$this->_configArray = $this->_config->get();
		$this->_config->set('dbPrefix', 'controller_');
	}

	/**
	 * tearDown
	 *
	 * @since 3.0.0
	 */

	public function tearDown()
	{
		$installer = new Installer($this->_config);
		$installer->init();
		$installer->rawDrop();
		$this->_config->set('dbPrefix', $this->_configArray['dbPrefix']);
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
	 * providerProcess
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerProcess()
	{
		return $this->getProvider('tests/provider/Controller/install_process.json');
	}

	/**
	 * providerValidateDatabase
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerValidateDatabase()
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

	public function providerValidateAccount()
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

	public function providerInstall()
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

	public function testProcess($postArray = [], $expect = null)
	{
		/* setup */

		$postArray['db-type'] = $postArray['db-type'] === '%CURRENT%' ? $this->_config->get('dbType') :  $postArray['db-type'];
		$postArray['db-host'] = $postArray['db-host'] === '%CURRENT%' ? $this->_config->get('dbHost') : $postArray['db-host'];
		$postArray['db-name'] = $postArray['db-name'] === '%CURRENT%' ? $this->_config->get('dbName') : $postArray['db-name'];
		$postArray['db-user'] = $postArray['db-user'] === '%CURRENT%' ? $this->_config->get('dbUser') : $postArray['db-user'];
		$postArray['db-password'] = $postArray['db-password'] === '%CURRENT%' ? $this->_config->get('dbPassword') : $postArray['db-password'];
		$postArray['db-prefix'] = $postArray['db-prefix'] === '%CURRENT%' ? $this->_config->get('dbPrefix') : $postArray['db-prefix'];
		$this->_request->set('post', $postArray);
		$this->_config->init(Stream::url('root/config.php'));
		$controllerInstall = new Controller\Install($this->_registry, $this->_language, $this->_request, $this->_config);

		/* actual */

		$actual = $controllerInstall->process();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testValidateDatabase
	 *
	 * @since 3.0.0
	 *
	 * @param array $postArray
	 * @param string $expect
	 *
	 * @dataProvider providerValidateDatabase
	 */

	public function testValidateDatabase($postArray = [], $expect = null)
	{
		/* setup */

		$controllerInstall = new Controller\Install($this->_registry, $this->_language, $this->_request, $this->_config);

		/* actual */

		$actual = $this->callMethod($controllerInstall, '_validateDatabase',
		[
			$postArray
		]);

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testValidateAccount
	 *
	 * @since 3.0.0
	 *
	 * @param array $postArray
	 * @param string $expect
	 *
	 * @dataProvider providerValidateAccount
	 */

	public function testValidateAccount($postArray = [], $expect = null)
	{
		/* setup */

		$controllerInstall = new Controller\Install($this->_registry, $this->_language, $this->_request, $this->_config);

		/* actual */

		$actual = $this->callMethod($controllerInstall, '_validateAccount',
		[
			$postArray
		]);

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testInstall
	 *
	 * @since 3.0.0
	 *
	 * @param array $installArray
	 * @param string $expect
	 *
	 * @dataProvider providerInstall
	 */

	public function testInstall($installArray = [], $expect = null)
	{
		/* setup */

		$controllerInstall = new Controller\Install($this->_registry, $this->_language, $this->_request, $this->_config);

		/* actual */

		$actual = $this->callMethod($controllerInstall, '_install',
		[
			$installArray
		]);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
