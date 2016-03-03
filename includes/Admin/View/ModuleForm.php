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
			'class' => 'rs-js-list-tab rs-admin-list-tab'
		));
		$formElement = new AdminForm(Registry::getInstance(), Language::getInstance());
		$formElement->init(array(
			'form' => array(
				'action' => Registry::get('rewriteRoute') . ($module->id ? 'admin/process/modules/' . $module->id : 'admin/process/modules'),
				'class' => 'rs-js-tab rs-js-validate-form rs-admin-form-default'
			),
			'link' => array(
				'cancel' => array(
					'href' => Registry::get('rewriteRoute') . 'admin/view/modules'
				),
				'uninstall' => array(
					'href' => $module->alias ? Registry::get('rewriteRoute') . 'admin/uninstall/modules/' . $module->alias . '/' . Registry::get('token') : null
				)
			)
		));

		/* documentation directory */

		$docDirectory = new Directory();
		$docDirectory->init('modules/' . $module->alias . '/docs');
		$docDirectoryArray = $docDirectory->getArray();

		/* collect item output */

		$tabCounter = 1;
		$tabRoute = Registry::get('rewriteRoute') . Registry::get('fullRoute');
		$outputItem = $itemElement
			->copy()
			->addClass('rs-js-item-active rs-item-active')
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
			->append('<div class="rs-js-box-tab rs-box-tab rs-admin-box-tab">')

			/* first tab */

			->append('<fieldset id="tab-' . $tabCounter++ . '" class="rs-js-set-tab rs-js-set-active rs-set-tab rs-set-active"><ul><li>')
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
				'class' => 'rs-js-auto-resize rs-admin-field-textarea rs-field-small',
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
						->append('<fieldset id="tab-' . $tabCounter++ . '" class="rs-js-set-tab rs-set-tab">')
						->append(Template::partial('modules/' . $module->alias . '/docs/' . $value))
						->append('</fieldset>');
				}
			}

			/* last tab */

		$formElement
			->append('<fieldset id="tab-' . $tabCounter++ . '" class="rs-js-set-tab rs-set-tab"><ul><li>')
			->label(Language::get('status'), array(
				'for' => 'status'
			))
			->select(Helper\Option::getToggleArray(), array(
				'id' => 'status',
				'name' => 'status',
				'value' => intval($module->status)
			))
			->append('</li><li>')
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
			->append('</li></ul></fieldset></div>')
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
