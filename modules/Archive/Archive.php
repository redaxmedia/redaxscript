<?php
namespace Redaxscript\Modules\Archive;

use Redaxscript\Dater;
use Redaxscript\Db;
use Redaxscript\Html;
use Redaxscript\Model;
use Redaxscript\Module;
use function strtotime;

/**
 * generate a archive tree
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class Archive extends Module\Module
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
	 * array of the option
	 *
	 * @var array
	 */

	protected $_optionArray =
	[
		'className' =>
		[
			'title' => 'rs-title-content-sub rs-title-archive',
			'list' => 'rs-list-default rs-list-archive'
		]
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
		$dater = new Dater();

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

				$dater->init(strtotime($key));
				$month = $dater->getDateTime()->format('n') - 1;
				$output .= $titleElement->text($this->_language->get('_month', $month) . ' ' . $dater->getDateTime()->format('Y'));
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
		$dater = new Dater();
		$articles = Db::forTablePrefix('articles')
			->whereLanguageIs($language)
			->whereNull('access')
			->where('status', 1)
			->orderByDesc('date')
			->findMany();

		/* process article */

		foreach ($articles as $value)
		{
			$dater->init($value->date);
			$dateKey = $dater->getDateTime()->format('Y-m');
			$monthArray[$dateKey][] = $value;
		}
		return $monthArray;
	}
}
