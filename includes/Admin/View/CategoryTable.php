<?php
namespace Redaxscript\Admin\View;

use Redaxscript\Admin;
use Redaxscript\Html;
use Redaxscript\Module;

/**
 * children class to create the admin category table
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class CategoryTable extends ViewAbstract
{
	/**
	 * render the view
	 *
	 * @since 4.0.0
	 *
	 * @return string
	 */

	public function render() : string
	{
		$output = Module\Hook::trigger('adminCategoryTableStart');
		$parameterRoute = $this->_registry->get('parameterRoute');
		$categoriesNew = $this->_registry->get('categoriesNew');

		/* html element */

		$element = new Html\Element();
		$titleElement = $element
			->copy()
			->init('h2',
			[
				'class' => 'rs-admin-title-content',
			])
			->text($this->_language->get('categories'));
		$linkElement = $element
			->copy()
			->init('a',
			[
				'class' => 'rs-admin-button-default rs-admin-button-create',
				'href' => $parameterRoute . 'admin/new/categories'
			])
			->text($this->_language->get('category_new'));

		/* collect output */

		$output .= $titleElement;
		if ($categoriesNew)
		{
			$output .= $linkElement;
		}
		$output .= $this->_renderTable();
		$output .= Module\Hook::trigger('adminCategoryTableEnd');
		return $output;
	}

	/**
	 * render the table
	 *
	 * @since 4.0.0
	 *
	 * @return string
	 */

	protected function _renderTable() : string
	{
		$output = null;
		$outputHead = null;
		$outputBody = null;
		$outputFoot = null;
		$tableArray =
		[
			'title' => $this->_language->get('title'),
			'alias' => $this->_language->get('alias'),
			'language' => $this->_language->get('language'),
			'rank' => $this->_language->get('rank')
		];
		$adminControl = new Helper\Control($this->_registry, $this->_language);
		$categoryModel = new Admin\Model\Category();
		$categories = $categoryModel->getAllByOrder('rank');
		$categoriesTotal = $categories->count();
		$parameterRoute = $this->_registry->get('parameterRoute');

		/* html element */

		$element = new Html\Element();
		$wrapperElement = $element
			->copy()
			->init('div',
			[
				'class' => 'rs-admin-wrapper-table'
			]);
		$tableElement = $element
			->copy()
			->init('table',
			[
				'class' => 'rs-admin-js-sort rs-admin-table-default'
			]);
		$linkElement = $element
			->copy()
			->init('a',
			[
				'class' => 'rs-admin-link-default'
			]);
		$theadElement = $element->copy()->init('thead');
		$tbodyElement = $element->copy()->init('tbody');
		$tfootElement = $element->copy()->init('tfoot');
		$trElement = $element->copy()->init('tr');
		$thElement = $element->copy()->init('th');
		$tdElement = $element->copy()->init('td');

		/* process table */

		foreach ($tableArray as $key => $value)
		{
			$outputHead .= $thElement->copy()->text($value);
			$outputFoot .= $tdElement->copy()->text($value);
		}

		/* process categories */

		if ($categoriesTotal)
		{
			foreach ($categories as $key => $value)
			{
				$outputBody .= $trElement
					->copy()
					->attr('id', 'row-' . $value->id)
					->addClass($value->parent ? 'rs-admin-is-children' : null)
					->addClass(!$value->status ? 'rs-admin-is-disabled' : null)
					->html(
						$tdElement->copy()->html(
							$linkElement
								->attr('href', $parameterRoute . $categoryModel->getRouteById($value->id))
								->text($value->title) .
							$adminControl->render('categories', $value->id, $value->alias, $value->status)
						) .
						$tdElement->copy()->text($value->alias) .
						$tdElement->copy()->text($value->language ? $this->_language->get($value->language, '_index') : $this->_language->get('all')) .
						$tdElement
							->copy()
							->addClass('rs-admin-col-move')
							->addClass($categoriesTotal > 1 && !$value->parent ? 'rs-admin-is-active' : null)
							->text($value->rank)
				);
			}
		}
		else
		{
			$outputBody .= $trElement
				->copy()
				->html(
					$tdElement
						->copy()
						->attr('colspan', count($tableArray))
						->text($this->_language->get('category_no'))
				);
		}

		/* collect output */

		$outputHead = $theadElement->html(
			$trElement->html($outputHead)
		);
		$outputBody = $tbodyElement->html($outputBody);
		$outputFoot = $tfootElement->html(
			$trElement->html($outputFoot)
		);
		$output .= $wrapperElement->copy()->html(
			$tableElement->html($outputHead . $outputBody . $outputFoot)
		);
		return $output;
	}
}
