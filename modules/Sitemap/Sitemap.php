<?php
namespace Redaxscript\Modules\Sitemap;

use Redaxscript\Db;
use Redaxscript\Html;
use Redaxscript\Model;

/**
 * generate a sitemap
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class Sitemap extends Config
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
		'version' => '3.3.0'
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
		$articleModel = new Model\Article();

		/* html elements */

		$titleElement = new Html\Element();
		$titleElement->init('h3',
		[
			'class' => $this->_configArray['className']['title']
		]);
		$linkElement = new Html\Element();
		$linkElement->init('a');
		$itemElement = new Html\Element();
		$itemElement->init('li');
		$listElement = new Html\Element();
		$listElement->init('ul',
		[
			'class' => $this->_configArray['className']['list']
		]);

		/* query articles */

		$categoryArray = $this->_getArticleByCategoryArray();

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
	 * get article by category array
	 *
	 * @since 3.3.0
	 *
	 * @return array
	 */

	protected function _getArticleByCategoryArray() : array
	{
		$categoryArray = [];
		$articles = Db::forTablePrefix('articles')
			->where('status', 1)
			->whereLanguageIs($this->_registry->get('language'))
			->whereNull('access')
			->orderByDesc('category')
			->findMany();

		/* process article */

		foreach ($articles as $value)
		{
			$categoryKey = $value->category ? $value->category : 0;
			$categoryArray[$categoryKey][] = $value;
		}
		return $categoryArray;
	}
}
