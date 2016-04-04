<?php
namespace Redaxscript\Tests;

use Redaxscript\Controller;
use Redaxscript\Language;
use Redaxscript\Registry;
use Redaxscript\Request;

/**
 * LogoutTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 * @author Balázs Szilágyi
 */

class LogoutTest extends TestCase
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
	 * setUp
	 *
	 * @since 3.0.0
	 */

	protected function setUp()
	{
		$this->_registry = Registry::getInstance();
		$this->_language = Language::getInstance();
		$this->_request = Request::getInstance();
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
		return $this->getProvider('tests/provider/Controller/logout_process.json');
	}

	/**
	 * testProcess
	 *
	 * @since 3.0.0
	 *
	 * @param string $expect
	 *
	 * @dataProvider providerProcess
	 */

	public function testProcess($expect = null)
	{
		/* setup */

		$logoutController = new Controller\Logout($this->_registry, $this->_language, $this->_request);

		/* actual */

		$actual = $logoutController->process();

		/* compare */

		$this->assertEquals($actual, $expect);
	}
}
