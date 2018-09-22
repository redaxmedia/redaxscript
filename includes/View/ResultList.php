<?php
namespace Redaxscript\View;

use Redaxscript\Dater;
use Redaxscript\Html;
use Redaxscript\Model;
use Redaxscript\Module;
use Redaxscript\Validator;

/**
 * children class to create the result list
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 * @author Balázs Szilágyi
 */

class ResultList extends ViewAbstract
{
	/**
	 * render the view
	 *
	 * @since 3.0.0
	 *
	 * @param array $resultArray array for the result
	 *
	 * @return string
	 */

	public function render(array $resultArray = []) : string
	{
		$output = Module\Hook::trigger('resultListStart');
		$accessValidator = new Validator\Access();
		$contentModel = new Model\Content();
		$dater = new Dater();
		$parameterRoute = $this->_registry->get('parameterRoute');

		/* html element */

		$element = new Html\Element();
		$titleElement = $element
			->copy()
			->init('h2',
			[
				'class' => 'rs-title-result'
			]);
		$listElement = $element
			->copy()
			->init('ol',
			[
				'class' => 'rs-list-result'
			]);
		$itemElement = $element->copy()->init('li');
		$linkElement = $element
			->copy()
			->init('a',
			[
				'class' => 'rs-link-result'
			]);
		$textElement = $element
			->copy()
			->init('span',
			[
				'class' => 'rs-text-result-date'
			]);

		/* process results */

		foreach ($resultArray as $table => $result)
		{
			$outputItem = null;
			if ($result)
			{
				/* collect item output */

				foreach ($result as $value)
				{
					if ($accessValidator->validate($result->access, $this->_registry->get('myGroups')))
					{
						$dater->init($value->date);
						$linkElement
							->attr('href', $parameterRoute . $contentModel->getRouteByTableAndId($table, $value->id))
							->text($value->title ? $value->title : $value->author);
						$textElement->text($dater->formatDate());
						$outputItem .= $itemElement->html($linkElement . $textElement);
					}
				}

				/* collect output */

				if ($outputItem)
				{
					$titleElement->text($this->_language->get($table));
					$listElement->html($outputItem);
					$output .= $titleElement . $listElement;
				}
			}
		}
		$output .= Module\Hook::trigger('resultListEnd');
		return $output;
	}
}
