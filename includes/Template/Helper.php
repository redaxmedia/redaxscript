<?php
namespace Redaxscript\Template;

use Redaxscript\Asset;
use Redaxscript\Registry;
use Redaxscript\Language;

/**
 * helper class to provide template helpers
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
	 * @return string
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

	public static function getTransport()
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

	public static function getClass($prefix = null)
	{
		$client = new Helper\Client(Registry::getInstance());
		$clientArray = $client->process();

		/* process client */

		foreach ($clientArray as $key => $value)
		{
			$clientArray[$key] = $prefix . $value;
		}
		return implode(' ', $clientArray);
	}
}
