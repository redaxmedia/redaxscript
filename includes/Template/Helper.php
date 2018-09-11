<?php
namespace Redaxscript\Template;

use Redaxscript\Asset;
use Redaxscript\Language;
use Redaxscript\Model;
use Redaxscript\Registry;

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
	 * @return string|array|null
	 */

	public static function getRegistry(string $key = null)
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
	 * @return string|array|null
	 */

	public static function getLanguage(string $key = null, string $index = null)
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
	 * @return string|null
	 */

	public static function getSetting(string $key = null) : ?string
	{
		$settingModel = new Model\Setting();
		return $settingModel->get($key);
	}

	/**
	 * get the title
	 *
	 * @since 3.0.0
	 *
	 * @return string|null
	 */

	public static function getTitle() : ?string
	{
		$title = new Helper\Title(Registry::getInstance());
		return $title->process();
	}

	/**
	 * get the canonical
	 *
	 * @since 3.0.0
	 *
	 * @return string|null
	 */

	public static function getCanonical() : ?string
	{
		$canonical = new Helper\Canonical(Registry::getInstance());
		return $canonical->process();
	}

	/**
	 * get the description
	 *
	 * @since 3.0.0
	 *
	 * @return string|null
	 */

	public static function getDescription() : ?string
	{
		$description = new Helper\Description(Registry::getInstance());
		return $description->process();
	}

	/**
	 * get the keywords
	 *
	 * @since 3.0.0
	 *
	 * @return string|null
	 */

	public static function getKeywords() : ?string
	{
		$keywords = new Helper\Keywords(Registry::getInstance());
		return $keywords->process();
	}

	/**
	 * get the robots
	 *
	 * @since 3.0.0
	 *
	 * @return string|null
	 */

	public static function getRobots() : ?string
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
	 * @return string|null
	 */

	public static function getSubset() : ?string
	{
		$subset = new Helper\Subset(Registry::getInstance());
		return $subset->process();
	}

	/**
	 * get the direction
	 *
	 * @since 3.0.0
	 *
	 * @return string|null
	 */

	public static function getDirection() : ?string
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
	 * @return string|null
	 */

	public static function getClass(string $prefix = null) : ?string
	{
		$client = new Helper\Client(Registry::getInstance());
		return $client->process($prefix);
	}
}
