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
		'version' => '4.0.0'
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
		$error = null;
		$articleModel = new Model\Article();

		/* html element */

		$element = new Html\Element();
		$titleElement = $element
			->copy()
			->init('h3',
			[
				'class' => $this->_configArray['className']['title']
			]);
		$listElement = $element
			->copy()
			->init('ul',
			[
				'class' => $this->_configArray['className']['list']
			]);
		$itemElement = $element->copy()->init('li');
		$linkElement = $element->copy()->init('a');

		/* query articles */

		$monthArray = $this->_getMonthArrayByLanguage($this->_registry->get('language'));

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
	 * get month array by language
	 *
	 * @since 3.3.0
	 *
	 * @param string $language
	 *
	 * @return array
	 */

	protected function _getMonthArrayByLanguage(string $language = null) : array
	{
		$monthArray = [];
		$articles = Db::forTablePrefix('articles')
			->where('status', 1)
			->whereLanguageIs($language)
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
