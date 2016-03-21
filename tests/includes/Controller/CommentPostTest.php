<?php
namespace Redaxscript\Tests;

use Redaxscript\Db;
use Redaxscript\Validator;
use Redaxscript\Controller;
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

class CommentPostTest extends TestCase
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
		Db::setSetting('moderation', 0);
		Db::forTablePrefix('comments')->deleteMany();
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
		return $this->getProvider('tests/provider/Controller/comment_post_process.json');
	}

	/**
	 * testProcess
	 *
	 * @since 3.0.0
	 *
	 * @param array $post
	 * @param array $hashArray
	 * @param array $settingsArray
	 * @param array $expect
	 *
	 * @dataProvider providerProcess
	 */

	public function testProcess($post = array(), $hashArray = array(), $settingsArray = array(), $expect = null)
	{
		/* setup */

		Db::setSetting('notification', $settingsArray['notification']);
		Db::setSetting('moderation', $settingsArray['moderation']);
		$this->_request->set('post', $post);
		$this->_request->setPost('solution', function_exists('password_verify') ? $hashArray[0] : $hashArray[1]);
		$commentPost = new Controller\CommentPost($this->_registry, $this->_language, $this->_request);

		/* actual */

		$actual = $commentPost->process();

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
