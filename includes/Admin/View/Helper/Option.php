<?php
namespace Redaxscript\Admin\View\Helper;

use Redaxscript\Db;
use Redaxscript\Filesystem;
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

	public function getToggleArray()
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

	public function getVisibleArray()
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

	public function getRobotArray()
	{
		return
		[
			$this->_language->get('select') => 'select',
			$this->_language->get('all') => 1,
			$this->_language->get('index') => 2,
			$this->_language->get('follow') => 3,
			$this->_language->get('index_no') => 4,
			$this->_language->get('follow_no') => 5,
			$this->_language->get('none') => 0
		];
	}

	/**
	 * get the time array
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function getTimeArray()
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

	public function getDateArray()
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

	public function getOrderArray()
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

	public function getCaptchaArray()
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

	public function getPermissionArray($table = null)
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

	public function getLanguageArray()
	{
		$languageFilesystem = new Filesystem\Filesystem();
		$languageFilesystem->init('languages');
		$languageFilesystemArray = $languageFilesystem->getSortArray();
		$languageArray =
		[
			$this->_language->get('select') => 'select'
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

	public function getTemplateArray()
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
			$this->_language->get('select') => 'select'
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

	public function getContentArray($table = null, $excludeArray = [])
	{
		$query = Db::forTablePrefix($table);
		if ($excludeArray)
		{
			$query->whereNotIn('id', $excludeArray);
		}
		$content = $query->orderByAsc('title')->findMany();
		$contentArray =
		[
			$this->_language->get('select') => 'select'
		];

		/* process content */

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

	public function getAccessArray($table = null)
	{
		$access = Db::forTablePrefix($table)->orderByAsc('name')->findMany();
		$accessArray = [];

		/* process access */

		foreach ($access as $value)
		{
			$accessArray[$value->name] = intval($value->id);
		}
		return $accessArray;
	}
}