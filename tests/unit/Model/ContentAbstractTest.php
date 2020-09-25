<?php
namespace Redaxscript\Tests\Model;

use Redaxscript\Db;
use Redaxscript\Model;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * ContentAbstractTest
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Model\ContentAbstract
 */

class ContentAbstractTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 4.0.0
	 */

	public function setUp() : void
	{
		parent::setUp();
		$optionArray = $this->getOptionArray();
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawCreate();
		$installer->insertSettings($optionArray);
		$articleOne = Db::forTablePrefix('articles')->create();
		$articleOne
			->set(
			[
				'title' => 'Article One',
				'alias' => 'article-one',
				'language' => 'en',
				'rank' => 1
			])
			->save();
		Db::forTablePrefix('articles')
			->create()
			->set(
			[
				'title' => 'Article One Sister',
				'alias' => 'article-one-sister',
				'language' => 'de',
				'sibling' => $articleOne->id,
				'rank' => 2
			])
			->save();
		Db::forTablePrefix('articles')
			->create()
			->set(
			[
				'title' => 'Article One Brother',
				'alias' => 'article-one-brother',
				'language' => 'fr',
				'sibling' => $articleOne->id,
				'rank' => 3
			])
			->save();
		$articleTwo = Db::forTablePrefix('articles')->create();
		$articleTwo
			->set(
			[
				'title' => 'Article Two',
				'alias' => 'article-two',
				'language' => 'en',
				'rank' => 4
			])
			->save();
		Db::forTablePrefix('articles')
			->create()
			->set(
			[
				'title' => 'Article Two Sister',
				'alias' => 'article-two-sister',
				'language' => 'de',
				'sibling' => $articleTwo->id,
				'rank' => 5
			])
			->save();
		Db::forTablePrefix('articles')
			->create()
			->set(
			[
				'title' => 'Article Three',
				'alias' => 'article-three',
				'language' => 'fr',
				'status' => 2,
				'rank' => 6,
				'date' => 1456786800
			])
			->save();
	}

	/**
	 * tearDown
	 *
	 * @since 4.0.0
	 */

	public function tearDown() : void
	{
		$this->dropDatabase();
	}

	/**
	 * testGetAllByOrder
	 *
	 * @since 4.0.0
	 *
	 * @param string $orderColumn
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testGetAllByOrder(string $orderColumn = null, array $expectArray = []) : void
	{
		/* setup */

		$articleModel = new Model\Article();

		/* actual */

		$actualArray = [];
		$actualObject = $articleModel->getAllByOrder($orderColumn);

		/* process articles */

		foreach ($actualObject as $value)
		{
			$actualArray[] = $value->alias;
		}

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}

	/**
	 * testGetByLanguageAndOrder
	 *
	 * @since 4.0.0
	 *
	 * @param string $language
	 * @param string $orderColumn
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testGetByLanguageAndOrder(string $language = null, string $orderColumn = null, array $expectArray = []) : void
	{
		/* setup */

		$articleModel = new Model\Article();

		/* actual */

		$actualArray = [];
		$actualObject = $articleModel->getByLanguageAndOrder($language, $orderColumn);

		/* process articles */

		foreach ($actualObject as $value)
		{
			$actualArray[] = $value->alias;
		}

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}

	/**
	 * testGetByLanguageAndOrder
	 *
	 * @since 4.0.0
	 *
	 * @param int $contentId
	 * @param string $language
	 * @param string $orderColumn
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testGetSiblingByIdAndLanguageAndOrder(int $contentId = null, string $language = null, string $orderColumn = null, array $expectArray = []) : void
	{
		/* setup */

		$articleModel = new Model\Article();

		/* actual */

		$actualArray = [];
		$actualObject = $articleModel->getSiblingByIdAndLanguageAndOrder($contentId, $language, $orderColumn);

		/* process articles */

		foreach ($actualObject as $value)
		{
			$actualArray[] = $value->alias;
		}

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}

	/**
	 * testGetSiblingArrayById
	 *
	 * @since 4.0.0
	 *
	 * @param int $contentId
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testGetSiblingArrayById(int $contentId = null, array $expectArray = []) : void
	{
		/* setup */

		$articleModel = new Model\Article();

		/* actual */

		$actualArray = $articleModel->getSiblingArrayById($contentId);

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}

	/**
	 * testPublishByDate
	 *
	 * @since 4.0.0
	 *
	 * @param int $date
	 * @param bool $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testPublishByDate(int $date = null, bool $expect = null) : void
	{
		/* setup */

		$articleModel = new Model\Article();

		/* actual */

		$actual = $articleModel->publishByDate($date);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
