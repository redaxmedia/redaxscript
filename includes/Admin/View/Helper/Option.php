<?php
namespace Redaxscript\Admin\View\Helper;

use DateTimeZone;
use Redaxscript\Db;
use Redaxscript\Filesystem;
use Redaxscript\Language;
use function function_exists;
use function mb_list_encodings;
use function resourcebundle_locales;
use function substr;

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
			$this->_language->get('none') => 6
		];
	}

	/**
	 * get the charset array
	 *
	 * @since 4.4.0
	 *
	 * @return array
	 */

	public function getCharsetArray() : array
	{
		return mb_list_encodings();
	}

	/**
	 * get the locale array
	 *
	 * @since 4.4.0
	 *
	 * @return array
	 */

	public function getLocaleArray() : array
	{
		return function_exists('resourcebundle_locales') ? resourcebundle_locales(null) : [];
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
			$languageArray[$this->_language->get('_language')[$value]] = $value;
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
	 * get the category array
	 *
	 * @since 4.0.0
	 *
	 * @return array
	 */

	public function getCategoryArray() : array
	{
		$query = Db::forTablePrefix('categories');
		return $this->_getContentArray($query);
	}

	/**
	 * get the article array
	 *
	 * @since 4.0.0
	 *
	 * @return array
	 */

	public function getArticleArray() : array
	{
		$query = Db::forTablePrefix('articles');
		return $this->_getContentArray($query);
	}

	/**
	 * get the sibling for article array
	 *
	 * @since 4.0.0
	 *
	 * @param int $articleId identifier of the article
	 *
	 * @return array
	 */

	public function getSiblingForArticleArray(int $articleId = null) : array
	{
		$query = Db::forTablePrefix('articles');
		if ($articleId)
		{
			$query->whereNotEqual('id', $articleId);
		}
		return $this->_getContentArray($query);
	}

	/**
	 * get the sibling for category array
	 *
	 * @since 4.0.0
	 *
	 * @param int $categoryId identifier of the category
	 *
	 * @return array
	 */

	public function getSiblingForCategoryArray(int $categoryId = null) : array
	{
		$query = Db::forTablePrefix('categories');
		if ($categoryId)
		{
			$query->whereNotEqual('id', $categoryId);
		}
		return $this->_getContentArray($query);
	}

	/**
	 * get the parent for category array
	 *
	 * @since 4.0.0
	 *
	 * @param int $categoryId identifier of the category
	 *
	 * @return array
	 */

	public function getParentForCategoryArray(int $categoryId = null) : array
	{
		$query = Db::forTablePrefix('categories')->whereNull('parent');
		if ($categoryId)
		{
			$query->whereNotEqual('id', $categoryId);
		}
		return $this->_getContentArray($query);
	}

	/**
	 * get the sibling for extra array
	 *
	 * @since 4.0.0
	 *
	 * @param int $extraId identifier of the extra
	 *
	 * @return array
	 */

	public function getSiblingForExtraArray(int $extraId = null) : array
	{
		$query = Db::forTablePrefix('extras');
		if ($extraId)
		{
			$query->whereNotEqual('id', $extraId);
		}
		return $this->_getContentArray($query);
	}

	/**
	 * get the article for comment array
	 *
	 * @since 4.0.0
	 *
	 * @return array
	 */

	public function getArticleForCommentArray() : array
	{
		$query = Db::forTablePrefix('articles')->where('comments', 1);
		return $this->_getContentArray($query);
	}

	/**
	 * get the content array
	 *
	 * @since 4.0.0
	 *
	 * @param object $query
	 *
	 * @return array
	 */

	protected function _getContentArray(object $query = null) : array
	{
		$contents = $query->orderByAsc('title')->findMany();
		$contentArray =
		[
			$this->_language->get('select') => 'null'
		];

		/* process contents */

		foreach ($contents as $value)
		{
			$contentKey = $value->title . ' (' . $value->id . ')';
			$contentArray[$contentKey] = $value->id;
		}
		return $contentArray;
	}

	/**
	 * get the group array
	 *
	 * @since 4.0.0
	 *
	 * @return array
	 */

	public function getGroupArray() : array
	{
		$access = Db::forTablePrefix('groups')->orderByAsc('name')->findMany();
		$accessArray = [];

		/* process access */

		foreach ($access as $value)
		{
			$accessArray[$value->name] = $value->id;
		}
		return $accessArray;
	}
}
