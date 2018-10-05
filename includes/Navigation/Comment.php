<?php
namespace Redaxscript\Navigation;

use Redaxscript\Html;
use Redaxscript\Model;
use Redaxscript\Module;
use Redaxscript\Validator;

/**
 * children class to create the comment navigation
 *
 * @since 3.3.0
 *
 * @package Redaxscript
 * @category Navigation
 * @author Henry Ruhs
 */

class Comment extends NavigationAbstract
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
			'list' => 'rs-list-comments'
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
		$output = Module\Hook::trigger('navigationCommentStart');
		$outputItem = null;
		$commentModel = new Model\Comment();
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

		/* query comments */

		$comments = $commentModel
			->query()
			->whereLanguageIs($this->_registry->get('language'))
			->where('status', 1)
			->orderBySetting($this->_optionArray['orderColumn'])
			->limit($this->_optionArray['limit'])
			->findMany();

		/* collect item output */

		foreach ($comments as $value)
		{
			if ($accessValidator->validate($value->access, $this->_registry->get('myGroups')))
			{
				$outputItem .= $itemElement
					->copy()
					->html($linkElement
						->copy()
						->attr(
						[
							'href' => $this->_registry->get('parameterRoute') . $commentModel->getRouteById($value->id)
						])
						->text($value->author . $this->_language->get('colon') . ' ' . $value->text)
					);
			}
		}

		/* collect output */

		if ($outputItem)
		{
			$output .= $listElement->html($outputItem);
		}
		$output .= Module\Hook::trigger('navigationCommentEnd');
		return $output;
	}
}
