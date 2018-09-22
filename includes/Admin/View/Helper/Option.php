<?php
namespace Redaxscript\Admin\View\Helper;

use DateTimeZone;
use Redaxscript\Db;
use Redaxscript\Filesystem;
use Redaxscript\Language;

/**
 * helper class to create various options
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class Option
{
	/**
	 * instance of the language class
	 *
	 * @var Language
	 */

	protected $_language;

	/**
	 * constructor of the class
	 *
	 * @since 3.2.0
	 *
	 * @param Language $language instance of the language class
	 */

	public function __construct(Language $language)
	{
		$this->_language = $language;
	}

	/**
	 * get the toggle array
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function getToggleArray() : array
	{
		return
		[
			$this->_language->get('enable') => 1,
			$this->_language->get('disable') => 0
		];
	}

	/**
	 * get the visible array
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function getVisibleArray() : array
	{
		return
		[
			$this->_language->get('publish') => 1,
			$this->_language->get('unpublish') => 0
		];
	}

	/**
	 * get the robot array
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function getRobotArray() : array
	{
		return
		[
			$this->_language->get('select') => 'null',
			$this->_language->get('all') => 1,
			$this->_language->get('index') => 2,
			$this->_language->get('follow') => 3,
			$this->_language->get('index_no') => 4,
			$this->_language->get('follow_no') => 5,
			$this->_language->get('none') => 0
		];
	}

	/**
	 * get the zone array
	 *
	 * @since 4.0.0
	 *
	 * @return array
	 */

	public function getZoneArray() : array
	{
		return DateTimeZone::listIdentifiers();
	}

	/**
	 * get the time array
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function getTimeArray() : array
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

	public function getDateArray() : array
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

	public function getOrderArray() : array
	{
		return
		[
			$this->_language->get('ascending') => 'asc',
			$this->_language->get('descending') => 'desc'
		];
	}

	/**
	 * get the captcha array
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function getCaptchaArray() : array
	{
		return
		[
			$this->_language->get('random') => 1,
			$this->_language->get('addition') => 2,
			$this->_language->get('subtraction') => 3,
			$this->_language->get('disable') => 0
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

	public function getPermissionArray(string $table = null) : array
	{
		if ($table === 'modules')
		{
			return
			[
				$this->_language->get('install') => 1,
				$this->_language->get('edit') => 2,
				$this->_language->get('uninstall') => 3
			];
		}
		if ($table === 'settings')
		{
			return
			[
				$this->_language->get('none') => 1,
				$this->_language->get('edit') => 2,
			];
		}
		return
		[
			$this->_language->get('create') => 1,
			$this->_language->get('edit') => 2,
			$this->_language->get('delete') => 3
		];
	}

	/**
	 * get the language array
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function getLanguageArray() : array
	{
		$languageFilesystem = new Filesystem\Filesystem();
		$languageFilesystem->init('languages');
		$languageFilesystemArray = $languageFilesystem->getSortArray();
		$languageArray =
		[
			$this->_language->get('select') => 'null'
		];

		/* process filesystem */

		foreach ($languageFilesystemArray as $value)
		{
			$value = substr($value, 0, 2);
			$languageArray[$this->_language->get($value, '_index')] = $value;
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

	public function getTemplateArray() : array
	{
		$templateFilesystem = new Filesystem\Filesystem();
		$templateFilesystem->init('templates', false,
		[
			'admin',
			'console',
			'install'
		]);
		$templateFilesystemArray = $templateFilesystem->getSortArray();
		$templateArray =
		[
			$this->_language->get('select') => 'null'
		];

		/* process filesystem */

		foreach ($templateFilesystemArray as $value)
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

	public function getContentArray(string $table = null, array $excludeArray = []) : array
	{
		$query = Db::forTablePrefix($table);
		if ($excludeArray)
		{
			$query->whereNotIn('id', $excludeArray);
		}
		$contents = $query->orderByAsc('title')->findMany();
		$contentArray =
		[
			$this->_language->get('select') => 'null'
		];

		/* process contents */

		foreach ($contents as $value)
		{
			$contentKey = $value->title . ' (' . $value->id . ')';
			$contentArray[$contentKey] = (int)$value->id;
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

	public function getAccessArray(string $table = null) : array
	{
		$access = Db::forTablePrefix($table)->orderByAsc('name')->findMany();
		$accessArray = [];

		/* process access */

		foreach ($access as $value)
		{
			$accessArray[$value->name] = (int)$value->id;
		}
		return $accessArray;
	}
}
