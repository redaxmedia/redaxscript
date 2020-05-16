<?php
namespace Redaxscript\View;

use Redaxscript\Admin;
use Redaxscript\Html;
use Redaxscript\Model;
use Redaxscript\Module;
use Redaxscript\Validator;
use function array_replace_recursive;

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
	/**
	 * options of the comment
	 *
	 * @var array
	 */

	protected $_optionArray =
	[
		'tag' =>
		[
			'title' => 'h3',
			'box' => 'div'
		],
		'className' =>
		[
			'title' => 'rs-title-comment',
			'box' => 'rs-box-comment'
		],
		'orderColumn' => 'rank'
	];

	/**
	 * init the class
	 *
	 * @since 4.0.0
	 *
	 * @param array $optionArray options of the comment
	 *
	 * @return self
	 */

	public function init(array $optionArray = []) : self
	{
		$this->_optionArray = array_replace_recursive($this->_optionArray, $optionArray);
		return $this;
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
		$byline = new Helper\Byline($this->_registry, $this->_language);
		$byline->init();
		$adminDock = new Admin\View\Helper\Dock($this->_registry, $this->_language);
		$adminDock->init();
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
		$linkElement = $element
			->copy()
			->init('a',
			[
				'rel' => 'nofollow'
			]);
		$boxElement = $element
			->copy()
			->init($this->_optionArray['tag']['box'],
			[
				'class' => $this->_optionArray['className']['box']
			]);
		$comments = $this->queryComments($articleId);

		/* process comments */

		foreach ($comments as $value)
		{
			if ($accessValidator->validate($value->access, $myGroups))
			{
				$output .= Module\Hook::trigger('commentFragmentStart', (array)$value);
				$output .= $titleElement
					->attr('id', 'comment-' . $value->id)
					->html($value->url ? $linkElement
						->attr('href', $value->url)
						->text($value->author) : $value->author
					);
				$output .= $boxElement->text($value->text) . $byline->render($value->date) . Module\Hook::trigger('commentFragmentEnd', (array)$value);

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

	/**
	 * query the comments
	 *
	 * @since 4.0.0
	 *
	 * @param int $articleId identifier of the article
	 *
	 * @return object|null
	 */

	public function queryComments(int $articleId = null) : ?object
	{
		$commentModel = new Model\Comment();
		$settingModel = new Model\Setting();
		$lastSubParameter = $this->_registry->get('lastSubParameter');
		$language = $this->_registry->get('language');

		/* query comments */

		if ($articleId)
		{
			$limitStep = $settingModel->get('pagination') ? $lastSubParameter - 1 : null;
			return $commentModel->getByArticleAndLanguageAndOrderAndStep($articleId, $language, $this->_optionArray['orderColumn'], $limitStep);
		}
		return $commentModel->getByLanguageAndOrder($language, $this->_optionArray['orderColumn']);
	}
}
