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
		Db::forTablePrefix('extras')
			->create()
			->set(
			[
				'title' => 'Extra Three',
				'alias' => 'extra-three',
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
				'rank' => 4,
				'status' => 2,
				'date' => '2036-01-01 00:00:00'
			])
			->save();
		Db::forTablePrefix('extras')
			->create()
			->set(
			[
				'title' => 'Extra Five',
				'alias' => 'extra-five',
				'rank' => 5,
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
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawDrop();
	}

	/**
	 * providerExtraPublishDate
	 *
	 * @since 3.3.0
	 *
	 * @return array
	 */

	public function providerExtraPublishDate() : array
	{
		return $this->getProvider('tests/provider/Model/extra_publish_date.json');
	}

	/**
	 * testPublishByDate
	 *
	 * @since 3.3.0
	 *
	 * @param string $date
	 * @param int $expect
	 *
	 * @dataProvider providerExtraPublishDate
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
