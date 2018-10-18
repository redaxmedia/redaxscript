<?php
namespace Redaxscript\Navigation;

use Redaxscript\Html;
use Redaxscript\Model;
use Redaxscript\Module;
use Redaxscript\Validator;

/**
 * children class to create the article navigation
 *
 * @since 3.3.0
 *
 * @package Redaxscript
 * @category Navigation
 * @author Henry Ruhs
 */

class Article extends NavigationAbstract
{
	/**
	 * options of the navigation
	 *
	 * @var array
	 */

	protected $_optionArray =
	[
		'className' =>
		[
			'list' => 'rs-list-articles',
			'active' => 'rs-item-active'
		],
		'orderColumn' => 'rank'
	];

	/**
	 * render the view
	 *
	 * @since 3.3.0
	 *
	 * @return string
	 */

	public function render() : string
	{
		$output = Module\Hook::trigger('navigationArticleStart');
		$outputItem = null;
		$articleModel = new Model\Article();
		$accessValidator = new Validator\Access();

		/* html element */

		$element = new Html\Element();
		$listElement = $element
			->copy()
			->init('ul',
			[
				'class' => $this->_optionArray['className']['list']
			]);
		$itemElement = $element->copy()->init('li');
		$linkElement = $element->copy()->init('a');

		/* query articles */

		$articles = $articleModel
			->query()
			->whereLanguageIs($this->_registry->get('language'))
			->where('status', 1)
			->orderBySetting($this->_optionArray['orderColumn'])
			->limit($this->_optionArray['limit'])
			->findMany();

		/* collect item output */

		foreach ($articles as $value)
		{
			if ($accessValidator->validate($value->access, $this->_registry->get('myGroups')))
			{
				$outputItem .= $itemElement
					->copy()
					->addClass($this->_registry->get('articleId') === (int)$value->id ? $this->_optionArray['className']['active'] : null)
					->html($linkElement
						->copy()
						->attr(
						[
							'href' => $this->_registry->get('parameterRoute') . $articleModel->getRouteById($value->id)
						])
						->text($value->title)
					);
			}
		}

		/* collect output */

		if ($outputItem)
		{
			$output .= $listElement->html($outputItem);
		}
		$output .= Module\Hook::trigger('navigationArticleEnd');
		return $output;
	}
}
