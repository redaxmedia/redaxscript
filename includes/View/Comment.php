<?php
namespace Redaxscript\View;

use Redaxscript\Admin;
use Redaxscript\Html;
use Redaxscript\Model;
use Redaxscript\Module;
use Redaxscript\Validator;

/**
 * children class to create the comment
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class Comment extends ViewAbstract
{
	protected $_optionArray =
	[
		'tag' =>
		[
			'title' => 'h3',
			'box' => 'blockquote'
		],
		'className' =>
		[
			'title' => 'rs-title-comment',
			'box' => 'rs-quote-default'
		]
	];

	/**
	 * stringify the comment
	 *
	 * @since 4.0.0
	 *
	 * @return string
	 */

	public function __toString() : string
	{
		return $this->render();
	}

	/**
	 * init the class
	 *
	 * @since 4.0.0
	 *
	 * @param array $optionArray options of the comment
	 */

	public function init(array $optionArray = [])
	{
		if (is_array($optionArray))
		{
			$this->_optionArray = array_replace_recursive($this->_optionArray, $optionArray);
		}
	}

	/**
	 * render the view
	 *
	 * @since 4.0.0
	 *
	 * @param int $articleId identifier of the article
	 *
	 * @return string
	 */

	public function render(int $articleId = null) : string
	{
		if ($this->_registry->get('commentReplace'))
		{
			return Module\Hook::trigger('commentReplace');
		}
		$output = Module\Hook::trigger('commentStart');
		$accessValidator = new Validator\Access();
		$commentModel = new Model\Comment();
		$byline = new Helper\Byline($this->_registry, $this->_language);
		$byline->init();
		$adminDock = new Admin\View\Helper\Dock($this->_registry, $this->_language);
		$adminDock->init();
		$language = $this->_registry->get('language');
		$loggedIn = $this->_registry->get('loggedIn');
		$token = $this->_registry->get('token');
		$firstParameter = $this->_registry->get('firstParameter');
		$myGroups = $this->_registry->get('myGroups');

		/* html element */

		$element = new Html\Element();
		$titleElement = $element
			->copy()
			->init($this->_optionArray['tag']['title'],
			[
				'class' => $this->_optionArray['className']['title']
			]);
		$linkElement = $element->copy()->init('a',
			[
				'rel' => 'nofollow'
			]);
		$boxElement = $element
			->copy()
			->init($this->_optionArray['tag']['box'],
			[
				'class' => $this->_optionArray['className']['box']
			]);

		/* query comments */

		$comments = $articleId ? $commentModel->getByArticleAndLanguage($articleId, $language) : $commentModel->getByLanguage($language);

		/* process comments */

		foreach ($comments as $value)
		{
			if ($accessValidator->validate($value->access, $myGroups))
			{
				$output .= Module\Hook::trigger('commentFragmentStart', $value);
				$output .= $titleElement
					->attr('id', 'comment-' . $value->id)
					->html($value->url ? $linkElement
						->attr('href', $value->url)
						->text($value->author) : $value->author
					);
				$output .= $boxElement->text($value->text) . $byline->render($value->date) . Module\Hook::trigger('commentFragmentEnd', $value);

				/* admin dock */

				if ($loggedIn === $token && $firstParameter !== 'logout')
				{
					$output .= $adminDock->render('comments', $value->id);
				}
			}
		}
		$output .= Module\Hook::trigger('commentEnd');
		return $output;
	}
}
