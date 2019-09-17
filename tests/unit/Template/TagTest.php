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

	public function setUp() : void
	{
		parent::setUp();
		$optionArray = $this->getOptionArray();
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawCreate();
		$installer->insertSettings($optionArray);
		$categoryOne = Db::forTablePrefix('categories')->create();
		$categoryOne
			->set(
			[
				'title' => 'Category One',
				'alias' => 'category-one'
			])
			->save();
		$articleOne = Db::forTablePrefix('articles')->create();
		$articleOne
			->set(
			[
				'title' => 'Article One',
				'alias' => 'article-one',
				'category' => $categoryOne->id,
				'comments' => 1
			])
			->save();
		Db::forTablePrefix('articles')
			->create()
			->set(
			[
				'title' => 'Article Two',
				'alias' => 'article-two',
				'category' => $categoryOne->id
			])
			->save();
		Db::forTablePrefix('comments')
			->create()
			->set(
			[
				'author' => 'Comment One',
				'text' => 'comment-one',
				'article' => $articleOne->id
			])
			->save();
		Db::forTablePrefix('comments')
			->create()
			->set(
			[
				'author' => 'Comment Two',
				'text' => 'comment-two',
				'article' => $articleOne->id
			])
			->save();
	}

	/**
	 * tearDown
	 *
	 * @since 3.1.0
	 */

	public function tearDown() : void
	{
		$this->dropDatabase();
	}

	/**
	 * testBase
	 *
	 * @since 3.0.0
	 */

	public function testBase() : void
	{
		/* actual */

		$actual = Template\Tag::base();

		/* compare */

		$this->assertIsString($actual);
	}

	/**
	 * testTitle
	 *
	 * @since 3.0.0
	 */

	public function testTitle() : void
	{
		/* actual */

		$actual = Template\Tag::title('test');

		/* compare */

		$this->assertIsString($actual);
	}

	/**
	 * testLink
	 *
	 * @since 3.0.0
	 */

	public function testLink() : void
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

	public function testMeta() : void
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

	public function testScript() : void
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

	public function testStyle() : void
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

	public function testBreadcrumb() : void
	{
		/* actual */

		$actual = Template\Tag::breadcrumb();

		/* compare */

		$this->assertIsString($actual);
	}

	/**
	 * testPartial
	 *
	 * @since 2.3.0
	 */

	public function testPartial() : void
	{
		/* setup */

		Stream::setup('root');
		$file = new StreamFile('partial.phtml');
		StreamWrapper::getRoot()->addChild($file);

		/* actual */

		$actual = Template\Tag::partial(Stream::url('root' . DIRECTORY_SEPARATOR . 'partial.phtml'));

		/* compare */

		$this->assertIsString($actual);
	}

	/**
	 * testPaginationArticles
	 *
	 * @since 4.0.0
	 */

	public function testPaginationArticles() : void
	{
		/* setup */

		$settingModel = new Model\Setting();
		$settingModel->set('limit', 1);

		/* actual */

		$actual = Template\Tag::pagination('articles', 1);

		/* compare */

		$this->assertIsString($actual);
	}

	/**
	 * testPaginationComments
	 *
	 * @since 4.0.0
	 */

	public function testPaginationComments() : void
	{
		/* setup */

		$settingModel = new Model\Setting();
		$settingModel->set('limit', 1);

		/* actual */

		$actual = Template\Tag::pagination('comments', 1);

		/* compare */

		$this->assertIsString($actual);
	}

	/**
	 * testPaginationInvalid
	 *
	 * @since 4.0.0
	 */

	public function testPaginationInvalid() : void
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

	public function testNavigationCategories() : void
	{
		/* actual */

		$actual = Template\Tag::navigation('categories');

		/* compare */

		$this->assertIsString($actual);
	}

	/**
	 * testNavigationArticles
	 *
	 * @since 3.3.1
	 */

	public function testNavigationArticles() : void
	{
		/* actual */

		$actual = Template\Tag::navigation('articles');

		/* compare */

		$this->assertIsString($actual);
	}

	/**
	 * testNavigationComments
	 *
	 * @since 3.3.1
	 */

	public function testNavigationComments() : void
	{
		/* actual */

		$actual = Template\Tag::navigation('comments');

		/* compare */

		$this->assertIsString($actual);
	}

	/**
	 * testNavigationLanguages
	 *
	 * @since 3.3.1
	 */

	public function testNavigationLanguages() : void
	{
		/* actual */

		$actual = Template\Tag::navigation('languages');

		/* compare */

		$this->assertIsString($actual);
	}

	/**
	 * testNavigationTemplates
	 *
	 * @since 3.3.1
	 */

	public function testNavigationTemplates() : void
	{
		/* actual */

		$actual = Template\Tag::navigation('templates');

		/* compare */

		$this->assertIsString($actual);
	}

	/**
	 * testNavigationTemplates
	 *
	 * @since 3.3.1
	 */

	public function testNavigationInvalid() : void
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

	public function testConsole() : void
	{
		/* setup */

		$this->_request->setPost('argv', 'help');

		/* actual */

		$actual = Template\Tag::console();

		/* compare */

		$this->assertIsString($actual);
	}

	/**
	 * testConsoleInvalid
	 *
	 * @since 3.0.0
	 */

	public function testConsoleInvalid() : void
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

	public function testConsoleForm() : void
	{
		/* actual */

		$actual = Template\Tag::consoleForm();

		/* compare */

		$this->assertIsString($actual);
	}

	/**
	 * testCommentForm
	 *
	 * @since 4.0.0
	 */

	public function testCommentForm() : void
	{
		/* actual */

		$actual = Template\Tag::commentForm(1);

		/* compare */

		$this->assertIsString($actual);
	}

	/**
	 * testSearchForm
	 *
	 * @since 3.0.0
	 */

	public function testSearchForm() : void
	{
		/* actual */

		$actual = Template\Tag::searchForm();

		/* compare */

		$this->assertIsString($actual);
	}

}
