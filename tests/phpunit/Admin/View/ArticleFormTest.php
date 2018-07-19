<?php
namespace Redaxscript\Tests\Admin\View;

use Redaxscript\Admin;
use Redaxscript\Db;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * ArticleFormTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Admin\View\ArticleForm
 * @covers Redaxscript\Admin\View\ViewAbstract
 */

class ArticleFormTest extends TestCaseAbstract
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
	 * @since 3.1.0
	 */

	public function tearDown()
	{
		$this->dropDatabase();
	}

	/**
	 * testRender
	 *
	 * @since 3.0.0
	 *
	 * @param array $registryArray
	 * @param int $articleId
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testRender(array $registryArray = [], int $articleId = null, array $expectArray = [])
	{
		/* setup */

		$this->_registry->init($registryArray);
		$articleForm = new Admin\View\ArticleForm($this->_registry, $this->_language);

		/* actual */

		$actual = $articleForm->render($articleId);

		/* compare */

		$this->assertStringStartsWith($expectArray['start'], $actual);
		$this->assertStringEndsWith($expectArray['end'], $actual);
	}
}
