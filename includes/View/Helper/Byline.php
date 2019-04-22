<?php
namespace Redaxscript\View\Helper;

use Redaxscript\Dater;
use Redaxscript\Html;
use Redaxscript\Module;
use Redaxscript\View\ViewAbstract;
use function array_replace_recursive;

/**
 * helper class to create the byline
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class Byline extends ViewAbstract
{
	/**
	 * options of the byline
	 *
	 * @var array
	 */

	protected $_optionArray =
	[
		'className' =>
		[
			'box' => 'rs-box-byline',
			'text' =>
			[
				'by' => 'rs-text-by',
				'author' => 'rs-text-author',
				'on' => 'rs-text-on',
				'date' => 'rs-text-date',
				'at' => 'rs-text-at',
				'time' => 'rs-text-time',
			]
		]
	];

	/**
	 * init the class
	 *
	 * @since 4.0.0
	 *
	 * @param array $optionArray options of the pagination
	 */

	public function init(array $optionArray = []) : void
	{
		$this->_optionArray = array_replace_recursive($this->_optionArray, $optionArray);
	}

	/**
	 * render the view
	 *
	 * @since 4.0.0
	 *
	 * @param int $date timestamp of the date
	 * @param string $author name of the author
	 *
	 * @return string
	 */

	public function render(int $date = null, string $author = null) : string
	{
		$output = Module\Hook::trigger('bylineStart');
		$dater = new Dater();
		$dater->init($date);

		/* html element */

		$element = new Html\Element();
		$boxElement = $element
			->copy()
			->init('div',
			[
				'class' => $this->_optionArray['className']['box']
			]);
		$textElement = $element
			->copy()
			->init('span');

		/* collect output */

		if ($author)
		{
			$boxElement->html(
				$textElement
					->copy()
					->addClass($this->_optionArray['className']['text']['by'])
					->text($this->_language->get('posted_by')) .
				$textElement
					->copy()
					->addClass($this->_optionArray['className']['text']['author'])
					->text($author)
			);
		}
		if ($author && $date)
		{
			$boxElement->append(
				$textElement
					->copy()
					->addClass($this->_optionArray['className']['text']['on'])
					->text($this->_language->get('on'))
			);
		}
		if ($date)
		{
			$boxElement->append(
				$textElement
					->copy()
					->addClass($this->_optionArray['className']['text']['date'])
					->text($dater->formatDate()) .
				$textElement
					->copy()
					->addClass($this->_optionArray['className']['text']['at'])
					->text($this->_language->get('at')) .
				$textElement
					->copy()
					->addClass($this->_optionArray['className']['text']['time'])
					->text($dater->formatTime())
			);
		}
		$output .= $boxElement . Module\Hook::trigger('bylineEnd');
		return $output;
	}
}
