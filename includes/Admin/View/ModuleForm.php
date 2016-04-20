<?php
namespace Redaxscript\Admin\View;

use Redaxscript\Admin\Html\Form as AdminForm;
use Redaxscript\Admin\View\Helper;
use Redaxscript\Db;
use Redaxscript\Directory;
use Redaxscript\Html;
use Redaxscript\Hook;
use Redaxscript\Language;
use Redaxscript\Registry;
use Redaxscript\Template;

/**
 * children class to generate the module form
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 */

class ModuleForm implements ViewInterface
{
	/**
	 * render the view
	 *
	 * @since 3.0.0
	 *
	 * @param integer $moduleId identifier of the module
	 *
	 * @return string
	 */

	public function render($moduleId = null)
	{
		$output = Hook::trigger('adminModuleFormStart');
		$module = Db::forTablePrefix('modules')->whereIdIs($moduleId)->findOne();

		/* html elements */

		$titleElement = new Html\Element();
		$titleElement->init('h2', array(
			'class' => 'rs-admin-title-content',
		));
		$titleElement->text($module->name);
		$linkElement = new Html\Element();
		$linkElement->init('a');
		$itemElement = new Html\Element();
		$itemElement->init('li');
		$listElement = new Html\Element();
		$listElement->init('ul', array(
			'class' => 'rs-admin-js-list-tab rs-admin-list-tab'
		));
		$formElement = new AdminForm(Registry::getInstance(), Language::getInstance());
		$formElement->init(array(
			'form' => array(
				'action' => Registry::get('parameterRoute') . ($module->id ? 'admin/process/modules/' . $module->id : 'admin/process/modules'),
				'class' => 'rs-admin-js-tab rs-admin-js-validate-form rs-admin-form-default'
			),
			'link' => array(
				'cancel' => array(
					'href' => Registry::get('modulesEdit') && Registry::get('modulesDelete') ? Registry::get('parameterRoute') . 'admin/view/modules' : Registry::get('parameterRoute') . 'admin'
				),
				'uninstall' => array(
					'href' => $module->alias ? Registry::get('parameterRoute') . 'admin/uninstall/modules/' . $module->alias . '/' . Registry::get('token') : null
				)
			)
		));

		/* documentation directory */

		$docDirectory = new Directory();
		$docDirectory->init('modules/' . $module->alias . '/docs');
		$docDirectoryArray = $docDirectory->getArray();

		/* collect item output */

		$tabCounter = 1;
		$tabRoute = Registry::get('parameterRoute') . Registry::get('fullRoute');
		$outputItem = $itemElement
			->copy()
			->addClass('rs-admin-js-item-active rs-admin-item-active')
			->html($linkElement
				->copy()
				->attr('href', $tabRoute . '#tab-' . $tabCounter++)
				->text(Language::get('module'))
			);

		/* process directory */

		foreach ($docDirectoryArray as $key => $value)
		{
			$outputItem .= $itemElement
				->copy()
				->html($linkElement
					->copy()
					->attr('href', $tabRoute . '#tab-' . $tabCounter++)
					->text(pathinfo($value, PATHINFO_FILENAME))
				);
		}

		/* collect item output */

		$outputItem .= $itemElement
			->copy()
			->html($linkElement
				->copy()
				->attr('href', $tabRoute . '#tab-' . $tabCounter++)
				->text(Language::get('customize'))
			);
		$listElement->append($outputItem);

		/* create the form */

		$tabCounter = 1;
		$formElement
			->append($listElement)
			->append('<div class="rs-admin-js-box-tab rs-admin-box-tab rs-admin-box-tab">')

			/* first tab */

			->append('<fieldset id="tab-' . $tabCounter++ . '" class="rs-admin-js-set-tab rs-admin-js-set-active rs-admin-set-tab rs-admin-set-active"><ul><li>')
			->label(Language::get('name'), array(
				'for' => 'name'
			))
			->text(array(
				'autofocus' => 'autofocus',
				'id' => 'name',
				'name' => 'name',
				'required' => 'required',
				'value' => $module->name
			))
			->append('</li><li>')
			->label(Language::get('description'), array(
				'for' => 'description'
			))
			->textarea(array(
				'class' => 'rs-admin-js-auto-resize rs-admin-field-textarea rs-admin-field-small',
				'id' => 'description',
				'name' => 'description',
				'value' => $module->description
			))
			->append('</li></ul></fieldset>');

			/* second tab */

			if ($docDirectoryArray)
			{
				/* process directory */

				foreach ($docDirectoryArray as $key => $value)
				{
					$formElement
						->append('<fieldset id="tab-' . $tabCounter++ . '" class="rs-admin-js-set-tab rs-admin-set-tab">')
						->append(Template::partial('modules/' . $module->alias . '/docs/' . $value))
						->append('</fieldset>');
				}
			}

			/* last tab */

		$formElement
			->append('<fieldset id="tab-' . $tabCounter++ . '" class="rs-admin-js-set-tab rs-admin-set-tab"><ul><li>')
			->label(Language::get('status'), array(
				'for' => 'status'
			))
			->select(Helper\Option::getToggleArray(), array(
				'id' => 'status',
				'name' => 'status',
				'value' => intval($module->status)
			))
			->append('</li>');
		if (Registry::get('groupsEdit'))
		{
			$formElement
				->append('<li>')
				->label(Language::get('access'), array(
					'for' => 'access'
				))
				->select(Helper\Option::getAccessArray('groups'), array(
					'id' => 'access',
					'name' => 'access[]',
					'multiple' => 'multiple',
					'size' => count(Helper\Option::getAccessArray('groups')),
					'value' => $module->access
				))
				->append('</li>');
		}
		$formElement
			->append('</ul></fieldset></div>')
			->token()
			->cancel()
			->uninstall()
			->save();

		/* collect output */

		$output .= $titleElement . $formElement;
		$output .= Hook::trigger('adminModuleFormEnd');
		return $output;
	}
}
