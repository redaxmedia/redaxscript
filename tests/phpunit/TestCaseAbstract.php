<?php
namespace Redaxscript\Tests;

use PHPUnit;
use Redaxscript\Config;
use Redaxscript\Db;
use Redaxscript\Installer;
use Redaxscript\Language;
use Redaxscript\Registry;
use Redaxscript\Request;
use ReflectionClass;

/**
 * TestCaseAbstract
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

abstract class TestCaseAbstract extends PHPUnit\Framework\TestCase
{
	/**
	 * instance of the registry class
	 *
	 * @var Registry
	 */

	protected $_registry;

	/**
	 * instance of the request class
	 *
	 * @var Request
	 */

	protected $_request;

	/**
	 * instance of the language class
	 *
	 * @var Language
	 */

	protected $_language;

	/**
	 * instance of the config class
	 *
	 * @var Config
	 */

	protected $_config;

	/**
	 * setUp
	 *
	 * @since 3.1.0
	 */

	public function setUp()
	{
		Db::clearCache();
		$this->_registry = Registry::getInstance();
		$this->_request = Request::getInstance();
		$this->_language = Language::getInstance();
		$this->_config = Config::getInstance();
	}

	/**
	 * installerFactory
	 *
	 * @since 3.1.0
	 */

	public function installerFactory()
	{
		return new Installer($this->_registry, $this->_request, $this->_language, $this->_config);
	}

	/**
	 * getProvider
	 *
	 * @since 2.2.0
	 *
	 * @param string $url
	 *
	 * @return array
	 */

	public function getProvider($url = null)
	{
		$content = file_get_contents($url);
		return json_decode($content, true);
	}

	/**
	 * getProperty
	 *
	 * @since 3.0.0
	 *
	 * @param object $object
	 * @param string $property
	 *
	 * @return mixed
	 */

	public function getProperty($object = null, $property = null)
	{
		$reflectionObject = new ReflectionClass($object);
		$reflectionProperty = $reflectionObject->getProperty($property);
		$reflectionProperty->setAccessible(true);
		return $reflectionProperty->getValue($object);
	}

	/**
	 * callMethod
	 *
	 * @since 3.0.0
	 *
	 * @param object $object
	 * @param string $method
	 * @param array $argumentArray
	 *
	 * @return mixed
	 */

	public function callMethod($object = null, $method = null, $argumentArray = [])
	{
		$reflectionObject = new ReflectionClass($object);
		$reflectionMethod = $reflectionObject->getMethod($method);
		$reflectionMethod->setAccessible(true);
		return $reflectionMethod->invokeArgs($object, $argumentArray);
	}

	/**
	 * assertString
	 *
	 * @since 3.0.0
	 *
	 * @param string $actual
	 */

	public function assertString($actual = null)
	{
		$this->assertTrue(is_string($actual));
	}

	/**
	 * assertNumber
	 *
	 * @since 3.1.0
	 *
	 * @param integer $actual
	 */

	public function assertNumber($actual = null)
	{
		$this->assertTrue(is_numeric($actual));
	}

	/**
	 * assertObject
	 *
	 * @since 3.1.0
	 *
	 * @param integer $actual
	 */

	public function assertObject($actual = null)
	{
		$this->assertTrue(is_object($actual));
	}

	/**
	 * normalizeSeparator
	 *
	 * @since 3.2.0
	 *
	 * @param string $actual
	 *
	 * @return string
	 */

	public function normalizeSeparator($actual = null)
	{
		return str_replace(DIRECTORY_SEPARATOR, chr(47), $actual);
	}

	/**
	 * normalizeNewline
	 *
	 * @since 3.0.0
	 *
	 * @param string $actual
	 *
	 * @return string
	 */

	public function normalizeNewline($actual = null)
	{
		return str_replace(PHP_EOL, chr(10), $actual);
	}
}
