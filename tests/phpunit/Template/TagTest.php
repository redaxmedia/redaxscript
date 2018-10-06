<?php
namespace Redaxscript\Tests\Template;

use org\bovigo\vfs\vfsStream as Stream;
use org\bovigo\vfs\vfsStreamFile as StreamFile;
use org\bovigo\vfs\vfsStreamWrapper as StreamWrapper;
use Redaxscript\Db;
use Redaxscript\Model;
use Redaxscript\Template;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * TagTest
 *
 * @since 2.3.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Template\Tag
 */

class TagTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 3.1.0
	 */

	public function setUp()
	{
		parent::setUp();
		$optionArray =
		[
			'adminName' => 'Test',
			'adminUser' => 'test',
			'adminPassword' => 'test',
			'adminEmail' => 'test@test.com'
		];
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawCreate();
		$installer->insertSettings($optionArray);
		Db::forTablePrefix('categories')
			->create()
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
				'category' => 1,
				'comments' => 1
			])
			->save();
		Db::forTablePrefix('articles')
			->create()
			->set(
			[
				'title' => 'Article Two',
				'alias' => 'article-two',
				'category' => 1
			])
			->save();
		Db::forTablePrefix('comments')
			->create()
			->set(
			[
				'author' => 'Comment One',
				'text' => 'Comment One',
				'article' => 1
			])
			->save();
		Db::forTablePrefix('comments')
			->create()
			->set(
			[
				'author' => 'Comment Two',
				'text' => 'Comment Two',
				'article' => 1
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
	 * testBase
	 *
	 * @since 3.0.0
	 */

	public function testBase()
	{
		/* actual */

		$actual = Template\Tag::base();

		/* compare */

		$this->assertString($actual);
	}

	/**
	 * testTitle
	 *
	 * @since 3.0.0
	 */

	public function testTitle()
	{
		/* actual */

		$actual = Template\Tag::title('test');

		/* compare */

		$this->assertString($actual);
	}

	/**
	 * testLink
	 *
	 * @since 3.0.0
	 */

	public function testLink()
	{
		/* actual */

		$actual = Template\Tag::link();

		/* compare */

		$this->assertInstanceOf('Redaxscript\Head\Link', $actual);
	}

	/**
	 * testMeta
	 *
	 * @since 3.0.0
	 */

	public function testMeta()
	{
		/* actual */

		$actual = Template\Tag::meta();

		/* compare */

		$this->assertInstanceOf('Redaxscript\Head\Meta', $actual);
	}

	/**
	 * testScript
	 *
	 * @since 3.0.0
	 */

	public function testScript()
	{
		/* actual */

		$actual = Template\Tag::script();

		/* compare */

		$this->assertInstanceOf('Redaxscript\Head\Script', $actual);
	}

	/**
	 * testStyle
	 *
	 * @since 3.0.0
	 */

	public function testStyle()
	{
		/* actual */

		$actual = Template\Tag::style();

		/* compare */

		$this->assertInstanceOf('Redaxscript\Head\Style', $actual);
	}

	/**
	 * testBreadcrumb
	 *
	 * @since 2.3.0
	 */

	public function testBreadcrumb()
	{
		/* actual */

		$actual = Template\Tag::breadcrumb();

		/* compare */

		$this->assertString($actual);
	}

	/**
	 * testPartial
	 *
	 * @since 2.3.0
	 */

	public function testPartial()
	{
		/* setup */

		Stream::setup('root');
		$file = new StreamFile('partial.phtml');
		StreamWrapper::getRoot()->addChild($file);

		/* actual */

		$actual = Template\Tag::partial(Stream::url('root' . DIRECTORY_SEPARATOR . 'partial.phtml'));

		/* compare */

		$this->assertString($actual);
	}

	/**
	 * testPaginationArticles
	 *
	 * @since 4.0.0
	 */

	public function testPaginationArticles()
	{
		/* setup */

		$settingModel = new Model\Setting();
		$settingModel->set('limit', 1);

		/* actual */

		$actual = Template\Tag::pagination('articles', 1);

		/* compare */

		$this->assertString($actual);
	}

	/**
	 * testPaginationComments
	 *
	 * @since 4.0.0
	 */

	public function testPaginationComments()
	{
		/* setup */

		$settingModel = new Model\Setting();
		$settingModel->set('limit', 1);

		/* actual */

		$actual = Template\Tag::pagination('comments', 1);

		/* compare */

		$this->assertString($actual);
	}

	/**
	 * testPaginationInvalid
	 *
	 * @since 4.0.0
	 */

	public function testPaginationInvalid()
	{
		/* actual */

		$actual = Template\Tag::pagination('invalid', 1);

		/* compare */

		$this->assertNull($actual);
	}

	/**
	 * testNavigationCategories
	 *
	 * @since 3.3.1
	 */

	public function testNavigationCategories()
	{
		/* actual */

		$actual = Template\Tag::navigation('categories');

		/* compare */

		$this->assertString($actual);
	}

	/**
	 * testNavigationArticles
	 *
	 * @since 3.3.1
	 */

	public function testNavigationArticles()
	{
		/* actual */

		$actual = Template\Tag::navigation('articles');

		/* compare */

		$this->assertString($actual);
	}

	/**
	 * testNavigationComments
	 *
	 * @since 3.3.1
	 */

	public function testNavigationComments()
	{
		/* actual */

		$actual = Template\Tag::navigation('comments');

		/* compare */

		$this->assertString($actual);
	}

	/**
	 * testNavigationLanguages
	 *
	 * @since 3.3.1
	 */

	public function testNavigationLanguages()
	{
		/* actual */

		$actual = Template\Tag::navigation('languages');

		/* compare */

		$this->assertString($actual);
	}

	/**
	 * testNavigationTemplates
	 *
	 * @since 3.3.1
	 */

	public function testNavigationTemplates()
	{
		/* actual */

		$actual = Template\Tag::navigation('templates');

		/* compare */

		$this->assertString($actual);
	}

	/**
	 * testNavigationTemplates
	 *
	 * @since 3.3.1
	 */

	public function testNavigationInvalid()
	{
		/* actual */

		$actual = Template\Tag::navigation('invalid');

		/* compare */

		$this->assertNull($actual);
	}

	/**
	 * testConsole
	 *
	 * @since 3.0.0
	 */

	public function testConsole()
	{
		/* setup */

		$this->_request->setPost('argv', 'help');

		/* actual */

		$actual = Template\Tag::console();

		/* compare */

		$this->assertString($actual);
	}

	/**
	 * testConsoleInvalid
	 *
	 * @since 3.0.0
	 */

	public function testConsoleInvalid()
	{
		/* setup */

		$this->_request->setPost('argv', 'invalidCommand');

		/* actual */

		$actual = Template\Tag::console();

		/* compare */

		$this->assertNull($actual);
	}

	/**
	 * testConsoleForm
	 *
	 * @since 3.0.0
	 */

	public function testConsoleForm()
	{
		/* actual */

		$actual = Template\Tag::consoleForm();

		/* compare */

		$this->assertString($actual);
	}

	/**
	 * testCommentForm
	 *
	 * @since 4.0.0
	 */

	public function testCommentForm()
	{
		/* actual */

		$actual = Template\Tag::commentForm(1);

		/* compare */

		$this->assertString($actual);
	}

	/**
	 * testSearchForm
	 *
	 * @since 3.0.0
	 */

	public function testSearchForm()
	{
		/* actual */

		$actual = Template\Tag::searchForm();

		/* compare */

		$this->assertString($actual);
	}

}
