<?php
namespace Redaxscript\Tests;

use PHPUnit_Framework_TestCase;
use ReflectionClass;

error_reporting(E_ERROR || E_PARSE);

/**
 * TestCaseAbstract
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

abstract class TestCaseAbstract extends PHPUnit_Framework_TestCase
{
	/**
	 * getProvider
	 *
	 * @since 2.2.0
	 *
	 * @param string $url
	 * @param boolean $assoc
	 *
	 * @return array
	 */

	public function getProvider($url = null, $assoc = true)
	{
		$content = file_get_contents($url);
		return json_decode($content, $assoc);
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
	 * normalizeEOL
	 *
	 * @since 3.0.0
	 *
	 * @param string $expect
	 *
	 * @return string
	 */

	public function normalizeEOL($expect = null)
	{
		return preg_replace('/[\n]/', PHP_EOL, $expect);
	}
}
