<?php
namespace Redaxscript\Admin\View\Helper;

use Redaxscript\Db;
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
	 * get the toggle array
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public static function getToggleArray()
	{
		$toggleArray[Language::get('enable')] = 1;
		$toggleArray[Language::get('disable')] = 0;
		return $toggleArray;
	}

	/**
	 * get the status array
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public static function getStatusArray()
	{
		$statusArray[Language::get('publish')] = 1;
		$statusArray[Language::get('unpublish')] = 0;
		return $statusArray;
	}

	/**
	 * get the language array
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public static function getLanguageArray()
	{
		$languageDirectory = new Directory();
		$languageDirectory->init('languages');
		$languageDirectoryArray = $languageDirectory->getArray();

		/* process directory */

		$languageArray[Language::get('select')] = null;
		foreach ($languageDirectoryArray as $value)
		{
			$value = substr($value, 0, 2);
			$languageArray[Language::get($value, '_index')] = $value;
		}
		return $languageArray;
	}

	/**
	 * get the template array
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public static function getTemplateArray()
	{
		$templateDirectory = new Directory();
		$templateDirectory->init('templates', array(
			'admin',
			'install'
		));
		$templateDirectoryArray = $templateDirectory->getArray();

		/* process directory */

		$templateArray[Language::get('select')] = null;
		$templateArray = array_merge($templateArray, $templateDirectoryArray);
		return $templateArray;
	}

	/**
	 * get the content array
	 *
	 * @since 3.0.0
	 *
	 * @param string $table name of the table
	 *
	 * @return array
	 */

	public static function getContentArray($table = null)
	{
		$content = Db::forTablePrefix($table)->orderByAsc('title')->findMany();

		/* process content */

		$contentArray[Language::get('select')] = null;
		foreach ($content as $value)
		{
			$contentArray[$value->title . ' (' . $value->id . ')'] = $value->id;
		}
		return $contentArray;
	}

	/**
	 * get the access array
	 *
	 * @since 3.0.0
	 *
	 * @param string $table name of the table
	 *
	 * @return array
	 */

	public static function getAccessArray($table = null)
	{
		$access = Db::forTablePrefix($table)->orderByAsc('name')->findMany();

		/* process access */

		$accessArray[Language::get('all')] = null;
		foreach ($access as $value)
		{
			$accessArray[$value->name] = $value->id;
		}
		return $accessArray;
	}
}