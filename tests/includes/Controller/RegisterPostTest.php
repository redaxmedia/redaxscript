<?php
namespace Redaxscript\Tests;

use Redaxscript\Controller;
use Redaxscript\Db;
use Redaxscript\Language;
use Redaxscript\Registry;
use Redaxscript\Request;

/**
 * RegisterPostTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 * @author Balázs Szilágyi
 */

class RegisterPostTest extends TestCase
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
	 * setUpBeforeClass
	 *
	 * @since 3.0.0
	 */

	public static function setUpBeforeClass()
	{
		Db::setSetting('captcha', 1);
		Db::setSetting('notification', 1);
	}

	/**
	 * tearDownAfterClass
	 *
	 * @since 3.0.0
	 */

	public static function tearDownAfterClass()
	{
		Db::setSetting('captcha', 0);
		Db::setSetting('notification', 0);
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
		return $this->getProvider('tests/provider/Controller/register_post_process.json');
	}

	/**
	 * testProcess
	 *
	 * @since 3.0.0
	 *
	 * @param array $postArray
	 * @param array $hashArray
	 * @param string $expect
	 *
	 * @dataProvider providerProcess
	 */

	public function testProcess($postArray = array(), $hashArray = array(), $expect = null)
	{
		/* setup */

		$this->_request->set('post', $postArray);
		$this->_request->setPost('solution', function_exists('password_verify') ? $hashArray[0] : $hashArray[1]);
		$registerPost = new Controller\RegisterPost($this->_registry, $this->_language, $this->_request);

		/* actual */

		$actual = $registerPost->process();

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
