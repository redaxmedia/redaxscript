<?php
namespace Redaxscript\Admin\View;

use Redaxscript\Admin;
use Redaxscript\Filesystem;
use Redaxscript\Html;
use Redaxscript\Module;
use function array_diff;
use function count;
use function in_array;

/**
 * children class to create the admin module table
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class ModuleTable extends ViewAbstract
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
		$output = Module\Hook::trigger('adminModuleTableStart');

		/* html element */

		$element = new Html\Element();
		$titleElement = $element
			->copy()
			->init('h2',
			[
				'class' => 'rs-admin-title-content',
			])
			->text($this->_language->get('modules'));

		/* collect output */

		$output .= $titleElement . $this->_renderTable();
		$output .= Module\Hook::trigger('adminModuleTableEnd');
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
			'name' => $this->_language->get('name'),
			'description' => $this->_language->get('description'),
			'version' => $this->_language->get('version')
		];
		$adminControl = new Helper\Control($this->_registry, $this->_language);
		$adminControl->init();
		$moduleModel = new Admin\Model\Module();
		$modules = $moduleModel->getAll();
		$modulesTotal = $modules->count();
		$modulesFilesystem = new Filesystem\Filesystem();
		$modulesFilesystem->init('modules');
		$modulesFilesystemArray = $modulesFilesystem->getSortArray();

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
				'class' => 'rs-admin-table-default rs-admin-table-module'
			]);
		$theadElement = $element->copy()->init('thead');
		$tbodyElement = $element->copy()->init('tbody');
		$tfootElement = $element->copy()->init('tfoot');
		$trElement = $element->copy()->init('tr');
		$thElement = $element->copy()->init('th');
		$tdElement = $element->copy()->init('td');
		$linkElement = $element
			->copy()
			->init('a',
			[
				'target' => '_blank'
			]);

		/* process table */

		foreach ($tableArray as $key => $value)
		{
			$outputHead .= $thElement->copy()->text($value);
			$outputFoot .= $tdElement->copy()->text($value);
		}

		/* process modules */

		if ($modulesTotal)
		{
			foreach ($modules as $key => $value)
			{
				$outputBody .= $trElement
					->copy()
					->attr('id', 'row-' . $value->id)
					->addClass(!$value->status ? 'rs-admin-is-disabled' : null)
					->addClass(!in_array($value->alias, $modulesFilesystemArray) ? 'rs-admin-is-corrupted' : null)
					->html(
						$tdElement->copy()->html(
							$linkElement
								->copy()
								->attr('href', $this->_language->get('_package')['service'] . '/docs/modules/' . $value->alias)
								->text($value->name) .
							$adminControl->render('modules', $value->id, $value->alias, $value->status)) .
						$tdElement->copy()->text($value->description) .
						$tdElement->copy()->text($value->version)
				);
				$modulesFilesystemArray = array_diff($modulesFilesystemArray,
				[
					$value->alias
				]);
			}
		}
		if ($modulesFilesystemArray)
		{
			foreach ($modulesFilesystemArray as $key => $value)
			{
				$outputBody .= $trElement
					->copy()
					->html(
						$tdElement
							->copy()
							->attr('colspan', count($tableArray))
							->html($value . $adminControl->render('modules', null, $value, null))
					);
			}
		}
		if (!$modulesTotal && !$modulesFilesystemArray)
		{
			$outputBody .= $trElement
				->copy()
				->html(
					$tdElement
						->copy()
						->attr('colspan', count($tableArray))
						->text($this->_language->get('module_no'))
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
