<?php
namespace Redaxscript\Tests\Controller;

use Redaxscript\Config;
use Redaxscript\Controller;
use Redaxscript\Installer;
use Redaxscript\Language;
use Redaxscript\Registry;
use Redaxscript\Request;
use Redaxscript\Tests\TestCaseAbstract;

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

	protected $_configArray = array();

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
		$this->_config->set('dbPrefix', $this->_configArray['dbPrefix']);
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
	 * testValidateDatabase
	 *
	 * @since 3.0.0
	 *
	 * @param array $postArray
	 * @param string $expect
	 *
	 * @dataProvider providerValidateDatabase
	 */

	public function testValidateDatabase($postArray = array(), $expect = null)
	{
		/* setup */

		$controllerInstall = new Controller\Install($this->_registry, $this->_language, $this->_request, $this->_config);

		/* actual */

		$actual = $this->callMethod($controllerInstall, '_validateDatabase', array(
			$postArray
		));

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

	public function testValidateAccount($postArray = array(), $expect = null)
	{
		/* setup */

		$controllerInstall = new Controller\Install($this->_registry, $this->_language, $this->_request, $this->_config);

		/* actual */

		$actual = $this->callMethod($controllerInstall, '_validateAccount', array(
			$postArray
		));

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

	public function testInstall($installArray = array(), $expect = null)
	{
		/* setup */

		$controllerInstall = new Controller\Install($this->_registry, $this->_language, $this->_request, $this->_config);

		/* actual */

		$actual = $this->callMethod($controllerInstall, '_install', array(
			$installArray
		));

		/* compare */

		$this->assertEquals($expect, $actual);

		/* teardown */

		$installer = new Installer($this->_config);
		$installer->init();
		$installer->rawDrop();
	}
}
