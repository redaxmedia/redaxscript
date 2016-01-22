<?php
namespace Redaxscript\Admin\View\Helper;

use Redaxscript\Directory;
use Redaxscript\Language;

/**
 * abstract class to provide various options
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 */

class Option
{
	/**
	 * get the toggle
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public static function getToggle()
	{
		$toggleArray[Language::get('enable')] = 1;
		$toggleArray[Language::get('disable')] = 0;
		return $toggleArray;
	}

	/**
	 * get the status
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public static function getStatus()
	{
		$statusArray[Language::get('publish')] = 1;
		$statusArray[Language::get('unpublish')] = 0;
		return $statusArray;
	}

	/**
	 * get the language
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public static function getLanguage()
	{
		$directory = new Directory();
		$directory->init('languages');
		$directoryArray = $directory->getArray();

		/* process directory array */

		$languageArray[Language::get('select')] = null;
		foreach ($directoryArray as $value)
		{
			$value = substr($value, 0, 2);
			$languageArray[Language::get($value, '_index')] = $value;
		}
		return $languageArray;
	}

	/**
	 * get the template
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public static function getTemplate()
	{
		$directory = new Directory();
		$directory->init('templates', array(
			'admin',
			'install'
		));
		$directoryArray = $directory->getArray();

		/* process directory array */

		$templateArray[Language::get('select')] = null;
		$templateArray = array_merge($templateArray, $directoryArray);
		return $templateArray;
	}
}