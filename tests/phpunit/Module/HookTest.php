<?php
namespace Redaxscript\Tests\Module;

use Redaxscript\Config;
use Redaxscript\Language;
use Redaxscript\Module;
use Redaxscript\Registry;
use Redaxscript\Request;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * HookTest
 *
 * @since 2.4.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class HookTest extends TestCaseAbstract
{
	/**
	 * testGetModuleArray
	 *
	 * @since 2.4.0
	 */

	public function testGetModuleArray()
	{
		/* setup */

		Module\Hook::construct(Registry::getInstance(), Request::getInstance(), Language::getInstance(), Config::getInstance());
		Module\Hook::init();

		/* actual */

		$actualArray = Module\Hook::getModuleArray();

		/* compare */

		$this->assertArrayHasKey('TestDummy', $actualArray);
	}

	/**
	 * testGetEventArray
	 *
	 * @since 2.4.0
	 */

	public function testGetEventArray()
	{
		/* setup */

		Module\Hook::construct(Registry::getInstance(), Request::getInstance(), Language::getInstance(), Config::getInstance());
		Module\Hook::init();
		Module\Hook::trigger('render');

		/* actual */

		$actualArray = Module\Hook::getEventArray();

		/* compare */

		$this->assertArrayHasKey('render', $actualArray);
	}

	/**
	 * testCollect
	 *
	 * @since 2.4.0
	 */

	public function testCollect()
	{
		/* setup */

		Module\Hook::construct(Registry::getInstance(), Request::getInstance(), Language::getInstance(), Config::getInstance());
		Module\Hook::init();

		/* actual */

		$actualArray = Module\Hook::collect('adminPanelNotification');

		/* compare */

		$this->assertEquals($actualArray['info']['Test dummy'][0], 'test');
	}

	/**
	 * testCollectInvalid
	 *
	 * @since 3.0.0
	 */

	public function testCollectInvalid()
	{
		/* setup */

		Module\Hook::construct(Registry::getInstance(), Request::getInstance(), Language::getInstance(), Config::getInstance());
		Module\Hook::init();

		/* actual */

		$actual = Module\Hook::collect('invalidMethod');

		/* compare */

		$this->assertEmpty($actual);
	}

	/**
	 * testTrigger
	 *
	 * @since 2.4.0
	 */

	public function testTrigger()
	{
		/* setup */

		Module\Hook::construct(Registry::getInstance(), Request::getInstance(), Language::getInstance(), Config::getInstance());
		Module\Hook::init();

		/* actual */

		$actual = Module\Hook::trigger('render');

		/* compare */

		$this->assertEquals(0, $actual);
	}

	/**
	 * testTriggerInvalid
	 *
	 * @since 2.4.0
	 */

	public function testTriggerInvalid()
	{
		/* setup */

		Module\Hook::construct(Registry::getInstance(), Request::getInstance(), Language::getInstance(), Config::getInstance());
		Module\Hook::init();

		/* actual */

		$actual = Module\Hook::trigger('invalidMethod');

		/* compare */

		$this->assertNull($actual);
	}
}
