<?php
namespace Redaxscript\Tests\Controller;

use Redaxscript\Db;
use Redaxscript\Controller;
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
	 * setUp
	 *
	 * @since 3.1.0
	 */

	public function setUp()
	{
		parent::setUp();
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawCreate();
		$installer->insertSettings(
		[
			'adminName' => 'Test',
			'adminUser' => 'test',
			'adminPassword' => 'test',
			'adminEmail' => 'test@test.com'
		]);
		$categoryOne = Db::forTablePrefix('categories')->create();
		$categoryOne
			->set(
			[
				'title' => 'Category One',
				'alias' => 'category-one'
			])
			->save();
		Db::forTablePrefix('articles')
			->create()
			->set(
			[
				'title' => 'Article One',
				'alias' => 'article-one',
				'category' => $categoryOne->id

			])
			->save();
		$setting = $this->settingFactory();
		$setting->set('captcha', 1);
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
		return $this->getProvider('tests/provider/Controller/comment_process.json');
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
		return $this->getProvider('tests/provider/Controller/comment_process_failure.json');
	}

	/**
	 * testProcess
	 *
	 * @since 3.0.0
	 *
	 * @param array $postArray
	 * @param array $settingArray
	 * @param string $expect
	 *
	 * @dataProvider providerProcess
	 */

	public function testProcess(array $postArray = [], array $settingArray = [], string $expect = null)
	{
		/* setup */

		$this->_request->set('post', $postArray);
		$setting = $this->settingFactory();
		$setting->set('notification', $settingArray['notification']);
		$setting->set('moderation', $settingArray['moderation']);
		$commentController = new Controller\Comment($this->_registry, $this->_request, $this->_language);

		/* actual */

		$actual = $commentController->process();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testProcessFailure
	 *
	 * @since 3.0.0
	 *
	 * @param array $postArray
	 * @param array $settingArray
	 * @param string $method
	 * @param string $expect
	 *
	 * @dataProvider providerProcessFailure
	 */

	public function testProcessFailure(array $postArray = [], array $settingArray = [], string $method = null, string $expect = null)
	{
		/* setup */

		$this->_request->set('post', $postArray);
		$setting = $this->settingFactory();
		$setting->set('notification', $settingArray['notification']);
		$setting->set('moderation', $settingArray['moderation']);
		$stub = $this
			->getMockBuilder('Redaxscript\Controller\Comment')
			->setConstructorArgs(
			[
				$this->_registry,
				$this->_request,
				$this->_language
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
}
