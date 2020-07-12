<?php
namespace Redaxscript\Admin\View;

use Redaxscript\Admin;
use Redaxscript\Html;
use Redaxscript\Module;
use Redaxscript\Validator;
use function count;
use function json_decode;

/**
 * children class to create the module form
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class ModuleForm extends ViewAbstract
{
	/**
	 * render the view
	 *
	 * @since 3.0.0
	 *
	 * @param int $moduleId identifier of the module
	 *
	 * @return string
	 */

	public function render(int $moduleId = null) : string
	{
		$output = Module\Hook::trigger('adminModuleFormStart');
		$moduleModel = new Admin\Model\Module();
		$module = $moduleModel->getById($moduleId);
		$helperOption = new Helper\Option($this->_language);
		$nameValidator = new Validator\Name();

		/* html element */

		$titleElement = new Html\Element();
		$titleElement
			->init('h2',
			[
				'class' => 'rs-admin-title-content',
			])
			->text($module->name);
		$formElement = new Admin\Html\Form($this->_registry, $this->_language);
		$formElement->init(
		[
			'form' =>
			[
				'class' => 'rs-admin-js-validate rs-admin-fn-tab rs-admin-component-tab rs-admin-form-default rs-admin-form-module'
			],
			'button' =>
			[
				'save' =>
				[
					'name' => self::class
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

		/* create the form */

		$formElement

			/* module */

			->radio(
			[
				'id' => self::class . '\Module',
				'class' => 'rs-admin-fn-status-tab',
				'name' => self::class . '\Tab',
				'checked' => 'checked'
			])
			->label($this->_language->get('module'),
			[
				'class' => 'rs-admin-fn-toggle-tab rs-admin-label-tab',
				'for' => self::class . '\Module'
			])
			->append('<ul class="rs-admin-fn-content-tab rs-admin-box-tab"><li>')
			->label($this->_language->get('name'),
			[
				'for' => 'name'
			])
			->text(
			[
				'autofocus' => 'autofocus',
				'id' => 'name',
				'name' => 'name',
				'pattern' => $nameValidator->getPattern(),
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
				'class' => 'rs-admin-js-resize rs-admin-field-textarea rs-admin-field-small',
				'id' => 'description',
				'name' => 'description',
				'rows' => 1,
				'value' => $module->description
			])
			->append('</li></ul>')

			/* customize */

			->radio(
			[
				'id' => self::class . '\Customize',
				'class' => 'rs-admin-fn-status-tab',
				'name' => self::class . '\Tab'
			])
			->label($this->_language->get('customize'),
			[
				'class' => 'rs-admin-fn-toggle-tab rs-admin-label-tab',
				'for' => self::class . '\Customize'
			])
			->append('<ul class="rs-admin-fn-content-tab rs-admin-box-tab"><li>')
			->label($this->_language->get('status'),
			[
				'for' => 'status'
			])
			->checkbox($module->status ?
			[
				'id' => 'status',
				'class' => 'rs-admin-fn-status-switch',
				'name' => 'status',
				'checked' => 'checked'
			] :
			[
				'id' => 'status',
				'class' => 'rs-admin-fn-status-switch',
				'name' => 'status'
			])
			->label(null,
			[
				'class' => 'rs-admin-label-switch',
				'for' => 'status',
				'data-on' => $this->_language->get('enable'),
				'data-off' => $this->_language->get('disable')
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
				->select($helperOption->getGroupArray(),
				(array)json_decode($module->access),
				[
					'id' => 'access',
					'name' => 'access[]',
					'multiple' => 'multiple',
					'size' => count($helperOption->getGroupArray())
				])
				->append('</li>');
		}
		$formElement
			->append('</ul>')
			->hidden(
			[
				'name' => 'id',
				'value' => $module->id
			])
			->token()
			->append('<div class="rs-admin-wrapper-button">')
			->cancel();
		if ($this->_registry->get('modulesUninstall'))
		{
			$formElement->uninstall();
		}
		if ($this->_registry->get('modulesEdit'))
		{
			$formElement->save();
		}
		$formElement->append('</div>');

		/* collect output */

		$output .= $titleElement . $formElement;
		$output .= Module\Hook::trigger('adminModuleFormEnd');
		return $output;
	}
}
