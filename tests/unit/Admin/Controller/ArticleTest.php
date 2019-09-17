<?php
namespace Redaxscript\Tests\Admin\Controller;

use Redaxscript\Admin;
use Redaxscript\Db;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * ArticleTest
 *
 * @since 4.1.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Admin\Controller\Article
 * @covers Redaxscript\Admin\Controller\ControllerAbstract
 */

class ArticleTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 4.1.0
	 */

	public function setUp() : void
	{
		parent::setUp();
		$optionArray = $this->getOptionArray();
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawCreate();
		$installer->insertSettings($optionArray);
		Db::forTablePrefix('articles')
			->create()
			->set(
			[
				'title' => 'Article One',
				'alias' => 'article-one'
			])
			->save();
	}

	/**
	 * tearDown
	 *
	 * @since 4.1.0
	 */

	public function tearDown() : void
	{
		$this->dropDatabase();
	}

	/**
	 * testCreate
	 *
	 * @since 4.1.0
	 *
	 * @param array $registryArray
	 * @param array $postArray
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testCreate(array $registryArray = [], array $postArray = [], string $expect = null) : void
	{
		/* setup */

		$this->_registry->init($registryArray);
		$this->_request->set('post', $postArray);
		$articleController = new Admin\Controller\Article($this->_registry, $this->_request, $this->_language, $this->_config);

		/* actual */

		$actual = $articleController->process('create');

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testUpdate
	 *
	 * @since 4.1.0
	 *
	 * @param array $registryArray
	 * @param array $postArray
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testUpdate(array $registryArray = [], array $postArray = [], string $expect = null) : void
	{
		/* setup */

		$this->_registry->init($registryArray);
		$this->_request->set('post', $postArray);
		$articleController = new Admin\Controller\Article($this->_registry, $this->_request, $this->_language, $this->_config);

		/* actual */

		$actual = $articleController->process('update');

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testInvalid
	 *
	 * @since 4.1.0
	 *
	 * @param array $registryArray
	 * @param array $postArray
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testInvalid(array $registryArray = [], array $postArray = [], string $expect = null) : void
	{
		/* setup */

		$this->_registry->init($registryArray);
		$this->_request->set('post', $postArray);
		$articleController = new Admin\Controller\Article($this->_registry, $this->_request, $this->_language, $this->_config);

		/* actual */

		$actual = $articleController->process('invalid');

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
