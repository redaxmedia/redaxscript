<?php
namespace Redaxscript\Tests\Console\Command;

use Redaxscript\Console\Command;
use Redaxscript\Model;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * MigrateTest
 *
 * @since 4.4.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Console\Command\CommandAbstract
 * @covers Redaxscript\Console\Command\Migrate
 */

class MigrateTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 4.4.0
	 */

	public function setUp() : void
	{
		parent::setUp();
		$optionArray = $this->getOptionArray();
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawCreate();
		$installer->insertSettings($optionArray);
	}

	/**
	 * tearDown
	 *
	 * @since 4.4.0
	 */

	public function tearDown() : void
	{
		$this->dropDatabase();
		$this->_request->setServer('argv', null);
	}

	/**
	 * testNoArgument
	 *
	 * @since 4.4.0
	 */

	public function testNoArgument() : void
	{
		/* setup */

		$migrateCommand = new Command\Migrate($this->_registry, $this->_request, $this->_language, $this->_config);

		/* expect and actual */

		$expect = $migrateCommand->getHelp();
		$actual = $migrateCommand->run('cli');

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testDatabase
	 *
	 * @since 4.4.0
	 *
	 * @param string $version
	 * @param bool $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testDatabase(string $version = null, bool $expect = null) : void
	{
		/* setup */

		$settingModel = new Model\Setting();
		$settingModel->set('version', $version);
		$this->_request->setServer('argv',
		[
			'console.php',
			'migrate',
			'database'
		]);
		$migrateCommand = new Command\Migrate($this->_registry, $this->_request, $this->_language, $this->_config);

		/* actual */

		$actual = $migrateCommand->run('cli');

		/* compare */

		$expect ? $this->assertEquals($migrateCommand->success(), $actual) : $this->assertEquals($migrateCommand->error(), $actual);
	}
}
