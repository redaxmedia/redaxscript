<?php
namespace Redaxscript\Tests\Controller;

use Redaxscript\Controller;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * RecoverTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 * @author Balázs Szilágyi
 */

class RecoverTest extends TestCaseAbstract
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
		$installer->insertSettings(
		[
			'adminName' => 'Test',
			'adminUser' => 'test',
			'adminPassword' => 'test',
			'adminEmail' => 'test@test.com'
		]);
		$installer->insertUsers(
		[
			'adminName' => 'Test',
			'adminUser' => 'test',
			'adminPassword' => 'test',
			'adminEmail' => 'test@test.com'
		]);
		$setting = $this->settingFactory();
		$setting->set('captcha', 1);
	}

	/**
	 * tearDown
	 *
	 * @since 3.1.0
	 */

	public function tearDown()
	{
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawDrop();
	}

	/**
	 * providerProcess
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerProcess() : array
	{
		return $this->getProvider('tests/provider/Controller/recover_process.json');
	}

	/**
	 * providerProcessFailure
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerProcessFailure() : array
	{
		return $this->getProvider('tests/provider/Controller/recover_process_failure.json');
	}

	/**
	 * testProcess
	 *
	 * @since 3.0.0
	 *
	 * @param array $postArray
	 * @param array $hashArray
	 * @param string $expect
	 *
	 * @dataProvider providerProcess
	 */

	public function testProcess(array $postArray = [], array $hashArray = [], string $expect = null)
	{
		/* setup */

		$this->_request->set('post', $postArray);
		$this->_request->setPost('solution', function_exists('password_verify') ? $hashArray[0] : $hashArray[1]);
		$recoverController = new Controller\Recover($this->_registry, $this->_request, $this->_language);

		/* actual */

		$actual = $recoverController->process();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testProcessFailure
	 *
	 * @since 3.0.0
	 *
	 * @param array $postArray
	 * @param array $hashArray
	 * @param string $method
	 * @param string $expect
	 *
	 * @dataProvider providerProcessFailure
	 */

	public function testProcessFailure(array $postArray = [], array $hashArray = [], string $method = null, string $expect = null)
	{
		/* setup */

		$this->_request->set('post', $postArray);
		$this->_request->setPost('solution', function_exists('password_verify') ? $hashArray[0] : $hashArray[1]);
		$stub = $this
			->getMockBuilder('Redaxscript\Controller\Recover')
			->setConstructorArgs(
			[
				$this->_registry,
				$this->_request,
				$this->_language
			])
			->setMethods(
			[
				$method
			])
			->getMock();

		/* override */

		$stub
			->expects($this->any())
			->method($method)
			->will($this->returnValue(false));

		/* actual */

		$actual = $stub->process();

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
