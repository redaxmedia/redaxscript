<?php
namespace Redaxscript\Tests\Module;

use Redaxscript\Db;
use Redaxscript\Model;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * ExtraTest
 *
 * @since 3.3.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class ExtraTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 3.3.0
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
		Db::forTablePrefix('extras')
			->create()
			->set(
			[
				'title' => 'Extra One',
				'alias' => 'extra-one',
				'rank' => 1,
				'status' => 1
			])
			->save();
		Db::forTablePrefix('extras')
			->create()
			->set(
			[
				'title' => 'Extra Two',
				'alias' => 'extra-two',
				'rank' => 2,
				'status' => 1
			])
			->save();
		$extraThree = Db::forTablePrefix('extras')
			->create()
			->set(
			[
				'title' => 'Extra Three',
				'alias' => 'extra-three',
				'language' => 'en',
				'rank' => 3,
				'status' => 1
			])
			->save();
		Db::forTablePrefix('extras')
			->create()
			->set(
			[
				'title' => 'Extra Four',
				'alias' => 'extra-four',
				'language' => 'de',
				'sibling' => $extraThree->id,
				'rank' => 4
			])
			->save();
		Db::forTablePrefix('extras')
			->create()
			->set(
			[
				'title' => 'Extra Five',
				'alias' => 'extra-five',
				'language' => 'fr',
				'sibling' => $extraThree->id,
				'rank' => 5
			])
			->save();
		Db::forTablePrefix('extras')
			->create()
			->set(
			[
				'title' => 'Extra Six',
				'alias' => 'extra-six',
				'rank' => 6,
				'status' => 2,
				'date' => '2036-01-01 00:00:00'
			])
			->save();
		Db::forTablePrefix('extras')
			->create()
			->set(
			[
				'title' => 'Extra Seven',
				'alias' => 'extra-seven',
				'rank' => 7,
				'status' => 2,
				'date' => '2037-01-01 00:00:00'
			])
			->save();
	}

	/**
	 * tearDown
	 *
	 * @since 3.3.0
	 */

	public function tearDown()
	{
		$this->dropDatabase();
	}

	/**
	 * providerGetResult
	 *
	 * @since 4.0.0
	 *
	 * @return array
	 */

	public function providerGetResult() : array
	{
		return $this->getProvider('tests/provider/Model/extra_get_result.json');
	}

	/**
	 * providerGetResultAlias
	 *
	 * @since 4.0.0
	 *
	 * @return array
	 */

	public function providerGetResultAlias() : array
	{
		return $this->getProvider('tests/provider/Model/extra_get_result_alias.json');
	}

	/**
	 * providerPublishDate
	 *
	 * @since 3.3.0
	 *
	 * @return array
	 */

	public function providerPublishDate() : array
	{
		return $this->getProvider('tests/provider/Model/extra_publish_date.json');
	}

	/**
	 * testGetResultByLanguage
	 *
	 * @since 4.0.0
	 *
	 * @param string $language
	 * @param array $expectArray
	 *
	 * @dataProvider providerGetResult
	 */

	public function testGetResultByLanguage(string $language = null, array $expectArray = null)
	{
		/* setup */

		$extraModel = new Model\Extra();

		/* actual */

		$extraArray = $extraModel->getResultByLanguage($language);
		$actualArray = [];

		/* process extra */

		foreach ($extraArray as $key => $value)
		{
			$actualArray[] = $value->alias;
		}
		$this->assertEquals($expectArray, $actualArray);
	}

	/**
	 * testGetResultByAliasAndLanguage
	 *
	 * @since 4.0.0
	 *
	 * @param string $extraAlias
	 * @param string $language
	 * @param string $expect
	 *
	 * @dataProvider providerGetResultAlias
	 */

	public function testGetResultByAliasAndLanguage(string $extraAlias = null, string $language = null, string $expect = null)
	{
		/* setup */

		$extraModel = new Model\Extra();

		/* actual */

		$actualArray = $extraModel->getResultByAliasAndLanguage($extraAlias, $language);

		/* compare */

		$this->markTestSkipped('implement sibling handling in model');
		$this->assertEquals($expect, $actualArray['alias']);
	}

	/**
	 * testPublishByDate
	 *
	 * @since 3.3.0
	 *
	 * @param string $date
	 * @param int $expect
	 *
	 * @dataProvider providerPublishDate
	 */

	public function testPublishByDate(string $date = null, int $expect = null)
	{
		/* setup */

		$extraModel = new Model\Extra();

		/* actual */

		$actual = $extraModel->publishByDate($date);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
