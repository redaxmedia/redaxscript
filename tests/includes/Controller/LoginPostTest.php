<?php
namespace Redaxscript\Tests;

use Redaxscript\Config;
use Redaxscript\Controller;
use Redaxscript\Db;
use Redaxscript\Hash;
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
		Db::setSetting('captcha', 1);
		Db::setSetting('notification', 1);

		$passwordHash = new Hash(Config::getInstance());
		$passwordHash->init('test');
		Db::forTablePrefix('users')->whereIdIs(1)->findOne()->set('password', $passwordHash->getHash())->save();
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
		Db::forTablePrefix('users')->whereIdIs(1)->findOne()->set('password', 'test')->save();
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

		$user = Db::forTablePrefix('users')->where('user', $post['user'])->findOne();
		if ($user)
		{
			Db::forTablePrefix('users')->whereIdIs($settings['id'])->findOne()->set('status', $settings['status'])->save();
		}

		$loginPost = new Controller\LoginPost($this->_registry, $this->_language, $this->_request);

		/* actual */

		$actual = $loginPost->process();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

}