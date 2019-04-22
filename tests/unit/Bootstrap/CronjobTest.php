<?php
namespace Redaxscript\Tests\Bootstrap;

use Redaxscript\Bootstrap;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * CronjobTest
 *
 * @since 3.1.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Bootstrap\BootstrapAbstract
 * @covers Redaxscript\Bootstrap\Cronjob
 *
 * @runTestsInSeparateProcesses
 */

class CronjobTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 3.1.0
	 */

	public function setUp() : void
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
	 * testCronjob
	 *
	 * @since 3.1.0
	 *
	 * @param array $registryArray
	 * @param array $sessionArray
	 * @param bool $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testCronjob(array $registryArray = [], array $sessionArray = [], bool $expect = null) : void
	{
		/* setup */

		$this->_registry->init($registryArray);
		$this->_request->setSession('nextUpdate', $sessionArray['nextUpdate']);
		new Bootstrap\Cronjob($this->_registry, $this->_request);

		/* actual */

		$actual = $this->_registry->get('cronUpdate');

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
