<?php
namespace Redaxscript\Admin\View\Helper;

use Redaxscript\Db;
use Redaxscript\Directory;
use Redaxscript\Language;

/**
 * helper class to provide various options
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
		return
		[
			Language::get('enable') => 1,
			Language::get('disable') => 0
		];
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
		return
		[
			Language::get('publish') => 1,
			Language::get('unpublish') => 0
		];
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
		return
		[
			Language::get('select') => 'select',
			Language::get('all') => 1,
			Language::get('index') => 2,
			Language::get('follow') => 3,
			Language::get('index_no') => 4,
			Language::get('follow_no') => 5,
			Language::get('none') => 0
		];
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
		return
		[
			'24h' => 'H:i',
			'12h' => 'h:i a'
		];
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
		return
		[
			'DD.MM.YYYY' => 'd.m.Y',
			'MM.DD.YYYY' => 'm.d.Y',
			'YYYY.MM.DD' => 'Y.m.d'
		];
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
		return
		[
			Language::get('ascending') => 'asc',
			Language::get('descending') => 'desc'
		];
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
		return
		[
			Language::get('random') => 1,
			Language::get('addition') => 2,
			Language::get('subtraction') => 3,
			Language::get('disable') => 0
		];
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
			return
			[
				Language::get('install') => 1,
				Language::get('edit') => 2,
				Language::get('uninstall') => 3
			];
		}
		if ($table === 'settings')
		{
			return
			[
				Language::get('none') => 1,
				Language::get('edit') => 2,
			];
		}
		return
		[
			Language::get('create') => 1,
			Language::get('edit') => 2,
			Language::get('delete') => 3
		];
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
		$templateDirectory->init('templates',
		[
			'admin',
			'console',
			'install'
		]);
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
	 * @param array $excludeArray array of the exclude
	 *
	 * @return array
	 */

	public static function getContentArray($table = null, $excludeArray = [])
	{
		$query = Db::forTablePrefix($table);
		if ($excludeArray)
		{
			$query->whereNotIn('id', $excludeArray);
		}
		$content = $query->orderByAsc('title')->findMany();

		/* process content */

		$contentArray[Language::get('select')] = 'select';
		foreach ($content as $value)
		{
			$contentKey = $value->title . ' (' . $value->id . ')';
			$contentArray[$contentKey] = intval($value->id);
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