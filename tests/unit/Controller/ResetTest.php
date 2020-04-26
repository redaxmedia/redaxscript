<?php
namespace Redaxscript\Tests\Controller;

use Redaxscript\Controller;
use Redaxscript\Db;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * ResetTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 * @author Balázs Szilágyi
 *
 * @covers Redaxscript\Controller\ControllerAbstract
 * @covers Redaxscript\Controller\Reset
 *
 * @requires OS Linux
 */

class ResetTest extends TestCaseAbstract
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
		$installer->insertUsers($optionArray);
		Db::forTablePrefix('users')
			->whereIdIs(1)
			->findOne()
			->set('password', 'test')
			->save();
		$setting = $this->settingFactory();
		$setting->set('captcha', 1);
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
	 * testProcess
	 *
	 * @since 3.0.0
	 *
	 * @param array $postArray
	 * @param string $method
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testProcess(array $postArray = [], string $method = null, string $expect = null) : void
	{
		/* setup */

		$this->_request->set('post', $postArray);
		if ($method)
		{
			$resetController = $this
				->getMockBuilder('Redaxscript\Controller\Reset')
				->setConstructorArgs(
				[
					$this->_registry,
					$this->_request,
					$this->_language,
					$this->_config
				])
				->setMethods(
				[
					$method
				])
				->getMock();

			/* override */

			$resetController
				->expects($this->any())
				->method($method);
		}
		else
		{
			$resetController = new Controller\Reset($this->_registry, $this->_request, $this->_language, $this->_config);
		}

		/* actual */

		$actual = $resetController->process();

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
