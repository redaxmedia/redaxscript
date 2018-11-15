<?php
namespace Redaxscript\View\Helper;

use Redaxscript\Html;
use Redaxscript\Module;
use Redaxscript\View\ViewAbstract;
use function array_replace_recursive;
use function max;
use function min;
use function range;

/**
 * helper class to create the pagination
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class Pagination extends ViewAbstract
{
	/**
	 * options of the pagination
	 *
	 * @var array
	 */

	protected $_optionArray =
	[
		'className' =>
		[
			'list' => 'rs-list-pagination',
			'item' =>
			[
				'first' => 'rs-item-first',
				'previous' => 'rs-item-previous',
				'next' => 'rs-item-next',
				'last' => 'rs-item-last',
				'number' => 'rs-item-number',
				'active' => 'rs-item-active'
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

	public function init(array $optionArray = [])
	{
		$this->_optionArray = array_replace_recursive($this->_optionArray, $optionArray);
	}

	/**
	 * render the view
	 *
	 * @since 4.0.0
	 *
	 * @param string $route
	 * @param int $current
	 * @param int $total
	 * @param int $range
	 *
	 * @return string
	 */

	public function render(string $route = null, int $current = null, int $total = null, int $range = 0) : string
	{
		$output = Module\Hook::trigger('paginationStart');
		$outputItem = null;
		$numberArray = $current && $total ? $this->_getNumberArray($current, $total, $range) : [];
		$parameterRoute = $this->_registry->get('parameterRoute');

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
		$textElement = $element->copy()->init('span');

		/* first and previous */

		if ($current > 1)
		{
			$outputItem .= $itemElement
				->copy()
				->addClass($this->_optionArray['className']['item']['first'])
				->html(
					$linkElement
						->copy()
						->attr('href', $parameterRoute . $route)
						->text($this->_language->get('first'))
				);
			$outputItem .= $itemElement
				->copy()
				->addClass($this->_optionArray['className']['item']['previous'])
				->html(
					$linkElement
						->copy()
						->attr(
						[
							'href' => $parameterRoute . $route . '/' . ($current - 1),
							'rel' => 'prev'
						])
						->text($this->_language->get('previous'))
				);
		}

		/* process number */

		foreach ($numberArray as $value)
		{
			$outputItem .= $itemElement
				->copy()
				->addClass($this->_optionArray['className']['item']['number'])
				->addClass($value === $current ? $this->_optionArray['className']['item']['active'] : null)
				->html(
					$value === $current ? $textElement->text($value) : $linkElement
						->copy()
						->attr('href', $parameterRoute . $route . '/' . $value)
						->text($value)
				);
		}

		/* next and last */

		if ($current && $current < $total)
		{
			$outputItem .= $itemElement
				->copy()
				->addClass($this->_optionArray['className']['item']['next'])
				->html(
					$linkElement
						->copy()
						->attr(
						[
							'href' => $parameterRoute . $route . '/' . ($current + 1),
							'rel' => 'next'
						])
						->text($this->_language->get('next'))
				);
			$outputItem .= $itemElement
				->copy()
				->addClass($this->_optionArray['className']['item']['last'])
				->html(
					$linkElement
						->copy()
						->attr('href', $parameterRoute . $route . '/' . $total)
						->text($this->_language->get('last'))
				);
		}

		/* collect output */

		if ($outputItem)
		{
			$output .= $listElement->html($outputItem);
		}
		$output .= Module\Hook::trigger('paginationEnd');
		return $output;
	}

	/**
	 * get the number array
	 *
	 * @since 4.0.0
	 *
	 * @param int $current
	 * @param int $total
	 * @param int $range
	 *
	 * @return array
	 */

	public function _getNumberArray(int $current = null, int $total = null, int $range = 0) : array
	{
		$start = $current - $range;
		$end = $current + $range;

		/* start and end */

		for ($i = $start; $i <= $end; $i++)
		{
			if ($i < 1)
			{
				$end++;
			}
			if ($i > $total)
			{
				$start--;
			}
		}
		return range(max(1, $start), min($total, $end));
	}
}
