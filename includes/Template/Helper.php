<?php
namespace Redaxscript\Template;

use Redaxscript\Asset;
use Redaxscript\Header;
use Redaxscript\Language;
use Redaxscript\Model;
use Redaxscript\Registry;
use function base64_encode;
use function file_get_contents;
use function is_file;

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
	 *
	 * @return string|array|null
	 */

	public static function getLanguage(string $key = null)
	{
		$language = Language::getInstance();
		return $language->get($key);
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
		$title = new Helper\Title(Registry::getInstance(), Language::getInstance());
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
		$canonical = new Helper\Canonical(Registry::getInstance(), Language::getInstance());
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
		$description = new Helper\Description(Registry::getInstance(), Language::getInstance());
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
		$keywords = new Helper\Keywords(Registry::getInstance(), Language::getInstance());
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
		$robots = new Helper\Robots(Registry::getInstance(), Language::getInstance());
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
	 * get the direction
	 *
	 * @since 3.0.0
	 *
	 * @return string|null
	 */

	public static function getDirection() : ?string
	{
		$direction = new Helper\Direction(Registry::getInstance(), Language::getInstance());
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
		$client = new Helper\Client(Registry::getInstance(), Language::getInstance());
		return $client->process($prefix);
	}

	/**
	 * get the response code
	 *
	 * @since 4.0.0
	 *
	 * @return int
	 */

	public static function getResponseCode() : int
	{
		return Header::responseCode();
	}

	/**
	 * get the icon
	 *
	 * @since 4.3.0
	 *
	 * @param string $path
	 * @param string $type
	 *
	 * @return string
	 */

	public static function getIcon(string $path = null, string $type = 'image/svg+xml') : string
	{
		if (is_file($path))
		{
			return 'data:' . $type . ';base64,' . base64_encode(file_get_contents($path));
		}
		return 'data:,';
	}

	/**
	 * get the content
	 *
	 * @since 4.0.0
	 *
	 * @param string|array $path
	 *
	 * @return string|null
	 */

	public static function getContent($path = null) : ?string
	{
		$output = null;

		/* process file */

		foreach ((array)$path as $value)
		{
			$output .= file_get_contents($value);
		}
		return $output;
	}
}
