<?php
namespace Redaxscript\Tests\Controller;

use Redaxscript\Db;
use Redaxscript\Controller;
use Redaxscript\Language;
use Redaxscript\Registry;
use Redaxscript\Request;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * CommentTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 * @author Balázs Szilágyi
 */

class CommentTest extends TestCaseAbstract
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

	public function setUp()
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
		return $this->getProvider('tests/provider/Controller/comment_process.json');
	}

	/**
	 * providerCreateFailure
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerCreateFailure()
	{
		return $this->getProvider('tests/provider/Controller/comment_create_failure.json');
	}

	/**
	 * providerMailFailure
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerMailFailure()
	{
		return $this->getProvider('tests/provider/Controller/comment_mail_failure.json');
	}

	/**
	 * testProcess
	 *
	 * @since 3.0.0
	 *
	 * @param array $postArray
	 * @param array $hashArray
	 * @param array $settingArray
	 * @param string $expect
	 *
	 * @dataProvider providerProcess
	 */

	public function testProcess($postArray = [], $hashArray = [], $settingArray = [], $expect = null)
	{
		/* setup */

		Db::setSetting('notification', $settingArray['notification']);
		Db::setSetting('moderation', $settingArray['moderation']);
		$this->_request->set('post', $postArray);
		$this->_request->setPost('solution', function_exists('password_verify') ? $hashArray[0] : $hashArray[1]);
		$commentController = new Controller\Comment($this->_registry, $this->_language, $this->_request);

		/* actual */

		$actual = $commentController->process();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testCreateFailure
	 *
	 * @since 3.0.0
	 *
	 * @param array $postArray
	 * @param array $hashArray
	 * @param string $expect
	 *
	 * @dataProvider providerCreateFailure
	 */

	public function testCreateFailure($postArray = [], $hashArray = [], $expect = null)
	{
		/* setup */

		$this->_request->set('post', $postArray);
		$this->_request->setPost('solution', function_exists('password_verify') ? $hashArray[0] : $hashArray[1]);
		$stub = $this
			->getMockBuilder('Redaxscript\Controller\Comment')
			->setConstructorArgs(
			[
				$this->_registry,
				$this->_language,
				$this->_request
			])
			->setMethods(
			[
				'_create'
			])
			->getMock();

		/* override */

		$stub
			->expects($this->any())
			->method('_create')
			->will($this->returnValue(false));

		/* actual */

		$actual = $stub->process();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testMailFailure
	 *
	 * @since 3.0.0
	 *
	 * @param array $postArray
	 * @param array $hashArray
	 * @param array $settingArray
	 * @param string $expect
	 *
	 * @dataProvider providerMailFailure
	 */

	public function testMailFailure($postArray = [], $hashArray = [], $settingArray = [], $expect = null)
	{
		/* setup */

		Db::setSetting('notification', $settingArray['notification']);
		Db::setSetting('moderation', $settingArray['moderation']);
		$this->_request->set('post', $postArray);
		$this->_request->setPost('solution', function_exists('password_verify') ? $hashArray[0] : $hashArray[1]);
		$stub = $this
			->getMockBuilder('Redaxscript\Controller\Comment')
			->setConstructorArgs(
			[
				$this->_registry,
				$this->_language,
				$this->_request
			])
			->setMethods(
			[
				'_mail'
			])
			->getMock();

		/* override */

		$stub
			->expects($this->any())
			->method('_mail')
			->will($this->returnValue(false));

		/* actual */

		$actual = $stub->process();

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
