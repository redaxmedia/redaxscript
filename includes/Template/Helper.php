<?php
namespace Redaxscript\Template;

use Redaxscript\Asset;
use Redaxscript\Registry;
use Redaxscript\Language;
use Redaxscript\Model;

/**
 * helper class to provide template helper
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Template
 * @author Henry Ruhs
 */

class Helper
{
	/**
	 * get the registry
	 *
	 * @since 2.6.0
	 *
	 * @param string $key
	 *
	 * @return string|array|bool
	 */

	public static function getRegistry($key = null)
	{
		$registry = Registry::getInstance();
		return $registry->get($key);
	}

	/**
	 * get the language
	 *
	 * @since 2.6.0
	 *
	 * @param string $key
	 * @param string $index
	 *
	 * @return string|array|bool
	 */

	public static function getLanguage($key = null, $index = null)
	{
		$language = Language::getInstance();
		return $language->get($key, $index);
	}

	/**
	 * get the setting
	 *
	 * @since 2.6.0
	 *
	 * @param string $key
	 *
	 * @return string|bool
	 */

	public static function getSetting($key = null)
	{
		$settingModel = new Model\Setting();
		return $settingModel->get($key);
	}

	/**
	 * get the title
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public static function getTitle()
	{
		$title = new Helper\Title(Registry::getInstance());
		return $title->process();
	}

	/**
	 * get the canonical
	 *
	 * @since 3.0.0
	 *
	 * @return string|bool
	 */

	public static function getCanonical()
	{
		$canonical = new Helper\Canonical(Registry::getInstance());
		return $canonical->process();
	}

	/**
	 * get the description
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public static function getDescription()
	{
		$description = new Helper\Description(Registry::getInstance());
		return $description->process();
	}

	/**
	 * get the keywords
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public static function getKeywords()
	{
		$keywords = new Helper\Keywords(Registry::getInstance());
		return $keywords->process();
	}

	/**
	 * get the robots
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public static function getRobots()
	{
		$robots = new Helper\Robots(Registry::getInstance());
		return $robots->process();
	}

	/**
	 * get the transport
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public static function getTransport() : array
	{
		$transport = new Asset\Transport(Registry::getInstance(), Language::getInstance());
		return $transport->getArray();
	}

	/**
	 * get the subset
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public static function getSubset()
	{
		$subset = new Helper\Subset(Registry::getInstance());
		return $subset->process();
	}

	/**
	 * get the direction
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public static function getDirection()
	{
		$direction = new Helper\Direction(Registry::getInstance());
		return $direction->process();
	}

	/**
	 * get the class
	 *
	 * @since 3.0.0
	 *
	 * @param string $prefix
	 *
	 * @return string
	 */

	public static function getClass(string $prefix = null)
	{
		$client = new Helper\Client(Registry::getInstance());
		return $client->process($prefix);
	}
}
