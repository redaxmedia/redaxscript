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
	 * get the visible array
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public static function getVisibleArray()
	{
		$statusArray[Language::get('publish')] = 1;
		$statusArray[Language::get('unpublish')] = 0;
		return $statusArray;

	}

	/**
	 * get the robot array
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public static function getRobotArray()
	{
		$statusArray[Language::get('index')] = 1;
		$statusArray[Language::get('index_no')] = 0;
		return $statusArray;
	}

	/**
	 * get the permission array
	 *
	 * @since 3.0.0
	 *
	 * @param string $table name of the table
	 *
	 * @return array
	 */

	public static function getPermissionArray($table = null)
	{
		if ($table === 'modules')
		{
			$permissionArray[Language::get('install')] = 1;
			$permissionArray[Language::get('edit')] = 2;
			$permissionArray[Language::get('uninstall')] = 3;
		}
		else if ($table === 'settings')
		{
			$permissionArray[Language::get('none')] = 1;
			$permissionArray[Language::get('edit')] = 2;
		}
		else
		{
			$permissionArray[Language::get('create')] = 1;
			$permissionArray[Language::get('edit')] = 2;
			$permissionArray[Language::get('delete')] = 3;
		}
		return $permissionArray;
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

		$languageArray[Language::get('select')] = 'select';
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

		$templateArray[Language::get('select')] = 'select';
		foreach ($templateDirectoryArray as $value)
		{
			$templateArray[$value] = $value;
		}
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

		$contentArray[Language::get('select')] = 'select';
		foreach ($content as $value)
		{
			$contentArray[$value->title . ' (' . $value->id . ')'] = intval($value->id);
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

		foreach ($access as $value)
		{
			$accessArray[$value->name] = intval($value->id);
		}
		return $accessArray;
	}
}