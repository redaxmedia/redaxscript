<?php
namespace Redaxscript\Admin\View;

use Redaxscript\Admin;
use Redaxscript\Html;
use Redaxscript\Module;
use function count;

/**
 * children class to create the admin extra table
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class ExtraTable extends ViewAbstract
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
		$output = Module\Hook::trigger('adminExtraTableStart');
		$parameterRoute = $this->_registry->get('parameterRoute');
		$extrasNew = $this->_registry->get('extrasNew');

		/* html element */

		$element = new Html\Element();
		$titleElement = $element
			->copy()
			->init('h2',
			[
				'class' => 'rs-admin-title-content',
			])
			->text($this->_language->get('extras'));
		$linkElement = $element
			->copy()
			->init('a',
			[
				'class' => 'rs-admin-button-default rs-admin-button-create',
				'href' => $parameterRoute . 'admin/new/extras'
			])
			->text($this->_language->get('extra_new'));

		/* collect output */

		$output .= $titleElement;
		if ($extrasNew)
		{
			$output .= $linkElement;
		}
		$output .= $this->_renderTable();
		$output .= Module\Hook::trigger('adminExtraTableEnd');
		return $output;
	}

	/**
	 * render the table
	 *
	 * @since 4.0.0
	 *
	 * @return string|null
	 */

	protected function _renderTable() : ?string
	{
		$output = null;
		$outputHead = null;
		$outputBody = null;
		$outputFoot = null;
		$tableArray =
		[
			'title' => $this->_language->get('title'),
			'alias' => $this->_language->get('alias'),
			'rank' => $this->_language->get('rank')
		];
		$adminControl = new Helper\Control($this->_registry, $this->_language);
		$adminControl->init();
		$extraModel = new Admin\Model\Extra();
		$extras = $extraModel->getAllByOrder('rank');
		$extrasTotal = $extras->count();

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
				'class' => 'rs-admin-js-sort rs-admin-table-default rs-admin-table-extra'
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

		/* process extras */

		if ($extrasTotal)
		{
			foreach ($extras as $key => $value)
			{
				$outputBody .= $trElement
					->copy()
					->attr('id', 'row-' . $value->id)
					->addClass(!$value->status ? 'rs-admin-is-disabled' : null)
					->html(
						$tdElement->copy()->html($value->title . $adminControl->render('extras', $value->id, $value->alias, $value->status)) .
						$tdElement->copy()->text($value->alias) .
						$tdElement
							->copy()
							->addClass('rs-admin-js-move rs-admin-col-move')
							->addClass($extrasTotal > 1 ? 'rs-admin-is-active' : null)
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
						->text($this->_language->get('extra_no'))
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
