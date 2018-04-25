<?php
namespace Redaxscript\Navigation;

use Redaxscript\Db;
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
		]
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
		$contentModel = new Model\Content();
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

		$query = Db::forTablePrefix('articles')
			->where('status', 1)
			->whereLanguageIs($this->_registry->get('language'))
			->limit($this->_optionArray['limit']);
		$articles = $this->_optionArray['order'] === 'asc' ? $query->orderByAsc('rank')->findMany() : $query->orderByDesc('rank')->findMany();

		/* collect item output */

		foreach ($articles as $value)
		{
			if ($accessValidator->validate($value->access, $this->_registry->get('myGroups')) === Validator\ValidatorInterface::PASSED)
			{
				$outputItem .= $itemElement
					->copy()
					->addClass(intval($this->_registry->get('articleId')) === intval($value->id) ? $this->_optionArray['className']['active'] : null)
					->html($linkElement
						->copy()
						->attr(
						[
							'href' => $this->_registry->get('parameterRoute') . $contentModel->getRouteByTableAndId('articles', $value->id)
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
