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
		return array(
			Language::get('enable') => 1,
			Language::get('disable') => 0
		);
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
		return array(
			Language::get('publish') => 1,
			Language::get('unpublish') => 0
		);
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
		return array(
			Language::get('all') => 1,
			Language::get('index') => 2,
			Language::get('follow') => 3,
			Language::get('index_no') => 4,
			Language::get('follow_no') => 5,
			Language::get('none') => 0
		);
	}

	/**
	 * get the time array
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public static function getTimeArray()
	{
		return array(
			'13:37' => 'H:i',
			'01:37' => 'h:i'
		);
	}

	/**
	 * get the date array
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public static function getDateArray()
	{
		return array(
			'31.12.2020' => 'd.m.Y',
			'12.31.2020' => 'm.d.Y',
			'2020.12.31' => 'Y.m.d'
		);
	}

	/**
	 * get the order array
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public static function getOrderArray()
	{
		return array(
			Language::get('ascending') => 'asc',
			Language::get('descending') => 'desc'
		);
	}

	/**
	 * get the captcha array
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public static function getCaptchaArray()
	{
		return array(
			Language::get('random') => 1,
			Language::get('addition') => 2,
			Language::get('subtraction') => 3,
			Language::get('disable') => 0
		);
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
			return array(
				Language::get('install') => 1,
				Language::get('edit') => 2,
				Language::get('uninstall') => 3
			);
		}
		if ($table === 'settings')
		{
			return array(
				Language::get('none') => 1,
				Language::get('edit') => 2,
			);
		}
		return array(
			Language::get('create') => 1,
			Language::get('edit') => 2,
			Language::get('delete') => 3
		);
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