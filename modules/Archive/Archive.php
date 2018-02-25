<?php
namespace Redaxscript\Modules\Archive;

use Redaxscript\Db;
use Redaxscript\Html;
use Redaxscript\Model;

/**
 * generate a archive tree
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class Archive extends Config
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray =
	[
		'name' => 'Archive',
		'alias' => 'Archive',
		'author' => 'Redaxmedia',
		'description' => 'Generate a archive tree',
		'version' => '3.3.1'
	];

	/**
	 * render
	 *
	 * @since 2.2.0
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

		$monthArray = $this->_getArticleByMonthArray();

		/* process articles */

		if (!$monthArray)
		{
			$error = $this->_language->get('article_no') . $this->_language->get('point');
		}
		else
		{
			foreach ($monthArray as $key => $articles)
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

				$month = date('n', strtotime($key));
				$year = date('Y', strtotime($key));
				$output .= $titleElement->text($titleElement->text($this->_language->get($month - 1, '_month') . ' ' . $year));
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
	 * get article by month array
	 *
	 * @since 3.3.0
	 *
	 * @return array
	 */

	protected function _getArticleByMonthArray() : array
	{
		$monthArray = [];
		$articles = Db::forTablePrefix('articles')
			->where('status', 1)
			->whereLanguageIs($this->_registry->get('language'))
			->whereNull('access')
			->orderByDesc('date')
			->findMany();

		/* process article */

		foreach ($articles as $value)
		{
			$dateKey = date('Y-m', strtotime($value->date));
			$monthArray[$dateKey][] = $value;
		}
		return $monthArray;
	}
}
