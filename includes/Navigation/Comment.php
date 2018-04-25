<?php
namespace Redaxscript\Navigation;

use Redaxscript\Db;
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
		$output = Module\Hook::trigger('navigationCommentStart');
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

		$query = Db::forTablePrefix('comments')
			->where('status', 1)
			->whereLanguageIs($this->_registry->get('language'))
			->limit($this->_optionArray['limit']);
		$comments = $this->_optionArray['order'] === 'asc' ? $query->orderByAsc('rank')->findMany() : $query->orderByDesc('rank')->findMany();

		/* collect item output */

		foreach ($comments as $value)
		{
			if ($accessValidator->validate($value->access, $this->_registry->get('myGroups')) === Validator\ValidatorInterface::PASSED)
			{
				$outputItem .= $itemElement
					->copy()
					->html($linkElement
						->copy()
						->attr(
						[
							'href' => $this->_registry->get('parameterRoute') . $contentModel->getRouteByTableAndId('comments', $value->id)
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
