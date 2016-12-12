<?php
namespace Redaxscript\Tests\Detector;

use Redaxscript\Db;
use Redaxscript\Detector;
use Redaxscript\Registry;
use Redaxscript\Request;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * DetectorTest
 *
 * @since 2.1.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class DetectorTest extends TestCaseAbstract
{
	/**
	 * instance of the registry class
	 *
	 * @var object
	 */

	protected $_registry;

	/**
	 * instance of the request class
	 *
	 * @var object
	 */

	protected $_request;

	/**
	 * setUp
	 *
	 * @since 3.0.0
	 */

	public function setUp()
	{
		$this->_registry = Registry::getInstance();
		$this->_request = Request::getInstance();
	}

	/**
	 * setUpBeforeClass
	 *
	 * @since 3.0.0
	 */

	public static function setUpBeforeClass()
	{
		Db::forTablePrefix('articles')
			->create()
			->set(
			[
				'id' => 2,
				'title' => 'test',
				'alias' => 'test',
				'author' => 'test',
				'text' => 'test',
				'language' => 'de',
				'template' => 'wide',
				'date' => '2016-01-01 00:00:00'
			])
			->save();
	}

	/**
	 * tearDownAfterClass
	 *
	 * @since 3.0.0
	 */

	public static function tearDownAfterClass()
	{
		Db::setSetting('language', null);
		Db::setSetting('template', null);
		Db::forTablePrefix('articles')->whereIdIs(2)->deleteMany();
	}

	/**
	 * providerLanguage
	 *
	 * @since 2.1.0
	 *
	 * @return array
	 */

	public function providerLanguage()
	{
		return $this->getProvider('tests/provider/Detector/language.json');
	}

	/**
	 * providerTemplate
	 *
	 * @since 2.1.0
	 *
	 * @return array
	 */

	public function providerTemplate()
	{
		return $this->getProvider('tests/provider/Detector/template.json');
	}

	/**
	 * testLanguage
	 *
	 * @since 3.0.0
	 *
	 * @param array $queryArray
	 * @param array $sessionArray
	 * @param array $serverArray
	 * @param array $settingArray
	 * @param array $registryArray
	 * @param string $expect
	 *
	 * @dataProvider providerLanguage
	 */

	public function testLanguage($queryArray = [], $sessionArray = [], $serverArray = [], $settingArray = [], $registryArray = [], $expect = null)
	{
		/* setup */

		$this->_request->set('get', $queryArray);
		$this->_request->set('session', $sessionArray);
		$this->_request->set('server', $serverArray);
		Db::setSetting('language', $settingArray['language']);
		$this->_registry->init($registryArray);
		$detector = new Detector\Language($this->_registry, $this->_request);

		/* actual */

		$actual = $detector->getOutput();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testTemplate
	 *
	 * @since 3.0.0
	 *
	 * @param array $queryArray
	 * @param array $sessionArray
	 * @param array $settingArray
	 * @param array $registryArray
	 * @param string $expect
	 *
	 * @dataProvider providerTemplate
	 */

	public function testTemplate($queryArray = [], $sessionArray = [], $settingArray = [], $registryArray = [], $expect = null)
	{
		/* setup */

		$this->_request->set('get', $queryArray);
		$this->_request->set('session', $sessionArray);
		Db::setSetting('template', $settingArray['template']);
		$this->_registry->init($registryArray);
		$detector = new Detector\Template($this->_registry, $this->_request);

		/* actual */

		$actual = $detector->getOutput();

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}