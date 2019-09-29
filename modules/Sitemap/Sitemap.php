<?php
namespace Redaxscript\Modules\Sitemap;

use Redaxscript\Db;
use Redaxscript\Html;
use Redaxscript\Model;
use Redaxscript\Module;

/**
 * generate a sitemap tree
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class Sitemap extends Module\Module
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray =
	[
		'name' => 'Sitemap',
		'alias' => 'Sitemap',
		'author' => 'Redaxmedia',
		'description' => 'Generate a sitemap tree',
		'version' => '4.1.1'
	];

	/**
	 * array of the option
	 *
	 * @var array
	 */

	protected $_optionArray =
	[
		'className' =>
		[
			'title' => 'rs-title-content-sub rs-title-sitemap',
			'list' => 'rs-list-default rs-list-sitemap'
		]
	];

	/**
	 * render
	 *
	 * @since 3.3.0
	 *
	 * @return string
	 */

	public function render() : string
	{
		$output = null;
		$outputItem = null;
		$error = null;
		$articleModel = new Model\Article();

		/* html element */

		$element = new Html\Element();
		$titleElement = $element
			->copy()
			->init('h3',
			[
				'class' => $this->_optionArray['className']['title']
			]);
		$listElement = $element
			->copy()
			->init('ul',
			[
				'class' => $this->_optionArray['className']['list']
			]);
		$itemElement = $element->copy()->init('li');
		$linkElement = $element->copy()->init('a');

		/* query articles */

		$categoryArray = $this->_getCategoryArrayByLanguage($this->_registry->get('language'));

		/* process articles */

		if (!$categoryArray)
		{
			$error = $this->_language->get('article_no') . $this->_language->get('point');
		}
		else
		{
			foreach ($categoryArray as $key => $articles)
			{
				/* collect item output */

				foreach ($articles as $value)
				{
					$outputItem .= $itemElement
						->copy()
						->html($linkElement
							->copy()
							->attr('href', $this->_registry->get('parameterRoute') . $articleModel->getRouteById($value->id))
							->text($value->title)
						);
				}

				/* collect output */

				$categoryName = $key < 1 ? $this->_language->get('uncategorized') : Db::forTablePrefix('categories')->whereIdIs($key)->findOne()->title;
				$output .= $titleElement->text($categoryName);
				$output .= $listElement->html($outputItem);
				$outputItem = null;
			}
		}

		/* handle error */

		if ($error)
		{
			$output = $listElement->html(
				$itemElement->html($error)
			);
		}
		return $output;
	}

	/**
	 * get category array by language
	 *
	 * @since 3.3.0
	 *
	 * @param string $language
	 *
	 * @return array
	 */

	protected function _getCategoryArrayByLanguage(string $language = null) : array
	{
		$categoryArray = [];
		$articles = Db::forTablePrefix('articles')
			->whereLanguageIs($language)
			->whereNull('access')
			->where('status', 1)
			->orderByDesc('category')
			->findMany();

		/* process article */

		foreach ($articles as $value)
		{
			$categoryKey = $value->category ? : 0;
			$categoryArray[$categoryKey][] = $value;
		}
		return $categoryArray;
	}
}
