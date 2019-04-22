<?php
namespace Redaxscript\Tests\Admin\View\Helper;

use Redaxscript\Admin\View\Helper;
use Redaxscript\Module;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * NotificationTest
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Admin\View\Helper\Notification
 */

class NotificationTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 4.0.0
	 */

	public function setUp() : void
	{
		parent::setUp();
		$this->createDatabase();
		$this->installTestDummy();
	}

	/**
	 * tearDown
	 *
	 * @since 4.0.0
	 */

	public function tearDown() : void
	{
		$this->uninstallTestDummy();
		$this->dropDatabase();
	}

	/**
	 * testRender
	 *
	 * @since 4.0.0
	 *
	 * @param array $optionArray
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testRender(array $optionArray = [], string $expect = null) : void
	{
		/* setup */

		Module\Hook::construct($this->_registry, $this->_request, $this->_language, $this->_config);
		Module\Hook::init();
		$adminNotification = new Helper\Notification($this->_language);
		$adminNotification->init($optionArray);

		/* actual */

		$actual = $adminNotification->render();

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
