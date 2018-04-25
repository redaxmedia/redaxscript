<?php
namespace Redaxscript\Admin\View;

use Redaxscript\Admin\Html\Form as AdminForm;
use Redaxscript\Db;
use Redaxscript\Filesystem;
use Redaxscript\Html;
use Redaxscript\Module;

/**
 * children class to create the module form
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class ModuleForm extends ViewAbstract implements ViewInterface
{
	/**
	 * render the view
	 *
	 * @since 3.0.0
	 *
	 * @param int|bool $moduleId identifier of the module
	 *
	 * @return string
	 */

	public function render(int $moduleId = null) : string
	{
		$output = Module\Hook::trigger('adminModuleFormStart');
		$module = Db::forTablePrefix('modules')->whereIdIs($moduleId)->findOne();
		$helperOption = new Helper\Option($this->_language);

		/* html element */

		$titleElement = new Html\Element();
		$titleElement
			->init('h2',
			[
				'class' => 'rs-admin-title-content',
			])
			->text($module->name);
		$formElement = new AdminForm($this->_registry, $this->_language);
		$formElement->init(
		[
			'form' =>
			[
				'class' => 'rs-admin-js-tab rs-admin-js-validate-form rs-admin-component-tab rs-admin-form-default rs-admin-fn-clearfix'
			],
			'button' =>
			[
				'save' =>
				[
					'name' => get_class()
				]
			],
			'link' =>
			[
				'cancel' =>
				[
					'href' => $this->_registry->get('modulesEdit') && $this->_registry->get('modulesUninstall') ? $this->_registry->get('parameterRoute') . 'admin/view/modules' : $this->_registry->get('parameterRoute') . 'admin'
				],
				'uninstall' =>
				[
					'href' => $module->alias ? $this->_registry->get('parameterRoute') . 'admin/uninstall/modules/' . $module->alias . '/' . $this->_registry->get('token') : null
				]
			]
		]);

		/* docs filesystem */

		$docsFilesystem = new Filesystem\File();
		$docsFilesystem->init('modules' . DIRECTORY_SEPARATOR . $module->alias . DIRECTORY_SEPARATOR . 'docs');
		$docsFilesystemArray = $docsFilesystem->getSortArray();

		/* create the form */

		$tabCounter = 1;
		$formElement
			->append($this->_renderList($docsFilesystemArray))
			->append('<div class="rs-admin-js-box-tab rs-admin-box-tab rs-admin-box-tab">')

			/* first tab */

			->append('<fieldset id="tab-' . $tabCounter++ . '" class="rs-admin-js-set-tab rs-admin-js-set-active rs-admin-set-tab rs-admin-set-active"><ul><li>')
			->label($this->_language->get('name'),
			[
				'for' => 'name'
			])
			->text(
			[
				'autofocus' => 'autofocus',
				'id' => 'name',
				'name' => 'name',
				'required' => 'required',
				'value' => $module->name
			])
			->append('</li><li>')
			->label($this->_language->get('description'),
			[
				'for' => 'description'
			])
			->textarea(
			[
				'class' => 'rs-admin-js-auto-resize rs-admin-field-textarea rs-admin-field-small',
				'id' => 'description',
				'name' => 'description',
				'rows' => 1,
				'value' => $module->description
			])
			->append('</li></ul></fieldset>');

			/* docs tab */

			if (is_array($docsFilesystemArray))
			{
				foreach ($docsFilesystemArray as $file)
				{
					$formElement
						->append('<fieldset id="tab-' . $tabCounter++ . '" class="rs-admin-js-set-tab rs-admin-set-tab">')
						->append($docsFilesystem->renderFile($file))
						->append('</fieldset>');
				}
			}

		/* last tab */

		$formElement
			->append('<fieldset id="tab-' . $tabCounter++ . '" class="rs-admin-js-set-tab rs-admin-set-tab"><ul><li>')
			->label($this->_language->get('status'),
			[
				'for' => 'status'
			])
			->select($helperOption->getToggleArray(),
			[
				intval($module->status)
			],
			[
				'id' => 'status',
				'name' => 'status'
			])
			->append('</li>');
		if ($this->_registry->get('groupsEdit'))
		{
			$formElement
				->append('<li>')
				->label($this->_language->get('access'),
				[
					'for' => 'access'
				])
				->select($helperOption->getAccessArray('groups'),
				[
					$module->access
				],
				[
					'id' => 'access',
					'name' => 'access[]',
					'multiple' => 'multiple',
					'size' => count($helperOption->getAccessArray('groups'))
				])
				->append('</li>');
		}
		$formElement
			->append('</ul></fieldset></div>')
			->token()
			->cancel();
		if ($this->_registry->get('modulesUninstall'))
		{
			$formElement->uninstall();
		}
		if ($this->_registry->get('modulesEdit'))
		{
			$formElement->save();
		}

		/* collect output */

		$output .= $titleElement . $formElement;
		$output .= Module\Hook::trigger('adminModuleFormEnd');
		return $output;
	}

	/**
	 * render the list
	 *
	 * @since 3.2.0
	 *
	 * @param array $docsFilesystemArray
	 *
	 * @return string
	 */

	protected function _renderList(array $docsFilesystemArray = []) : string
	{
		$tabRoute = $this->_registry->get('parameterRoute') . $this->_registry->get('fullRoute');
		$tabCounter = 1;

		/* html element */

		$element = new Html\Element();
		$listElement = $element
			->copy()
			->init('ul',
			[
				'class' => 'rs-admin-js-list-tab rs-admin-list-tab'
			]);
		$itemElement = $element->copy()->init('li');
		$linkElement = $element->copy()->init('a');

		/* collect item output */

		$outputItem = $itemElement
			->copy()
			->addClass('rs-admin-js-item-active rs-admin-item-active')
			->html($linkElement
				->copy()
				->attr('href', $tabRoute . '#tab-' . $tabCounter++)
				->text($this->_language->get('module'))
			);

		/* process filesystem */

		if (is_array($docsFilesystemArray))
		{
			foreach ($docsFilesystemArray as $file)
			{
				$outputItem .= $itemElement
					->copy()
					->html($linkElement
						->copy()
						->attr('href', $tabRoute . '#tab-' . $tabCounter++)
						->text(pathinfo($file, PATHINFO_FILENAME))
					);
			}
		}

		/* collect item output */

		$outputItem .= $itemElement
			->copy()
			->html($linkElement
				->copy()
				->attr('href', $tabRoute . '#tab-' . $tabCounter++)
				->text($this->_language->get('customize'))
			);
		return $listElement->html($outputItem)->render();
	}
}
