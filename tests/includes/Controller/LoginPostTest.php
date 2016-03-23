<?php
namespace Redaxscript\Tests;

use Redaxscript\Controller;
use Redaxscript\Db;
use Redaxscript\Language;
use Redaxscript\Registry;
use Redaxscript\Request;

/**
 * LoginPostTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 * @author Balázs Szilágyi
 */

class LoginPostTest extends TestCase
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
		Db::forTablePrefix('settings')->where('name', 'captcha')->findOne()->set('value', 1)->save();
		Db::forTablePrefix('settings')->where('name', 'notification')->findOne()->set('value', 1)->save();
	}

	/**
	 * tearDownAfterClass
	 *
	 * @since 3.0.0
	 */

	public static function tearDownAfterClass()
	{
		Db::forTablePrefix('settings')->where('name', 'captcha')->findOne()->set('value', 0)->save();
		Db::forTablePrefix('settings')->where('name', 'notification')->findOne()->set('value', 0)->save();
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
		return $this->getProvider('tests/provider/Controller/login_post_process.json');
	}

	/**
	 * testProcess
	 *
	 * @since 3.0.0
	 *
	 * @param array $post
	 * @param array $hashArray
	 * @param array $settings
	 * @param array $expect
	 *
	 * @dataProvider providerProcess
	 */

	public function testProcess($post = array(), $hashArray = array(), $settings = array(), $expect = null)
	{
		/* setup */

		$this->_request->set('post', $post);
		$this->_request->setPost('solution', function_exists('password_verify') ? $hashArray[0] : $hashArray[1]);

		$user = Db::forTablePrefix('users')->where('user', $post['user'])->findArray();
		if ($user->id)
		{
			Db::forTablePrefix('users')->where('name', $post['user'])->findOne()->set('status', $settings['status'])->save();
		}
		
		$registerPost = new Controller\LoginPost($this->_registry, $this->_language, $this->_request);

		/* actual */

		$actual = $registerPost->process();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

}