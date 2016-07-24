<?php
namespace Redaxscript\Tests\Controller;

use Redaxscript\Tests\TestCaseAbstract;
use Redaxscript\Registry;
use Redaxscript\Language;
use Redaxscript\Request;
use Redaxscript\Config;
use Redaxscript\Controller;

/**
 * InstallControllerTest
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
	}


	/**
	 * tearDownAfterClass
	 *
	 * @since 3.0.0
	 */

	public static function tearDownAfterClass()
	{
	}

	/**
	 * providerRender
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerRender()
	{
		return $this->getProvider('tests/provider/Controller/install_process.json');
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
	 * providerInstall
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerValidate()
	{
		return $this->getProvider('tests/provider/Controller/install_validate.json');
	}

	/**
	 * providerInstall
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerWrite()
	{
		return $this->getProvider('tests/provider/Controller/install_write.json');
	}

	/**
	 * testRender
	 *
	 * @since 3.0.0
	 *
	 * @dataProvider providerRender
	 *
	 * @param array $postArray
	 * @param string $expect
	 */

	public function testRender($postArray = array(), $expect = null)
	{
		/* setup */
		// TODO: use vfsStream
		$this->_request->set('post', $postArray);
		$installController= new Controller\Install($this->_registry, $this->_language, $this->_request, $this->_config);

		/* actual */

		$actual = $installController->process();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testInstall
	 *
	 * @since 3.0.0
	 *
	 * @dataProvider providerInstall
	 *
	 * @param array $installArray
	 * @param string $expect
	 */

	public function testInstall($installArray = array(), $expect = null)
	{
		/* setup */

		$installInstall = new Controller\Install($this->_registry, $this->_language, $this->_request, $this->_config);

		/* actual */

		$actual = $this->callMethod($installInstall, '_install', array(
			$installArray
		));

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testInstall
	 *
	 * @since 3.0.0
	 *
	 * @dataProvider providerValidate
	 *
	 * @param array $postArray
	 * @param string $expect
	 */

	public function testValidate($postArray = array(), $expect = null)
	{
		/* setup */

		$installValidate = new Controller\Install($this->_registry, $this->_language, $this->_request, $this->_config);

		/* actual */

		$actual = $this->callMethod($installValidate, '_validate', array(
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
	 * @dataProvider providerWrite
	 *
	 * @param array $writeArray
	 * @param string $expect
	 */

	public function testWrite($writeArray = array(), $expect = null)
	{
		/* setup */
		// TODO: make this test work (vfsStream?)
//		$installWrite = new Controller\Install($this->_registry, $this->_language, $this->_request, $this->_config);

		/* actual */

//		$actual = $this->callMethod($installWrite, '_write', array(
//			$writeArray
//		));

		/* compare */

//		$this->assertEquals($expect, $actual);
	}
}
