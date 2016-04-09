<?php
namespace Redaxscript\Admin\View;

use Redaxscript\Admin\Html\Form as AdminForm;
use Redaxscript\Admin\View\Helper;
use Redaxscript\Db;
use Redaxscript\Html;
use Redaxscript\Hook;
use Redaxscript\Language;
use Redaxscript\Registry;

/**
 * children class to generate the group form
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 */

class GroupForm implements ViewInterface
{
	/**
	 * render the view
	 *
	 * @since 3.0.0
	 *
	 * @param integer $groupId identifier of the group
	 *
	 * @return string
	 */

	public function render($groupId = null)
	{
		$output = Hook::trigger('adminGroupFormStart');
		$group = Db::forTablePrefix('groups')->whereIdIs($groupId)->findOne();

		/* html elements */

		$titleElement = new Html\Element();
		$titleElement->init('h2', array(
			'class' => 'rs-admin-title-content',
		));
		$titleElement->text($group->name ? $group->name : Language::get('group_new'));
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
				'action' => Registry::get('parameterRoute') . ($group->id ? 'admin/process/groups/' . $group->id : 'admin/process/groups'),
				'class' => 'rs-admin-js-tab rs-admin-js-validate-form rs-admin-form-default'
			),
			'link' => array(
				'cancel' => array(
					'href' => Registry::get('parameterRoute') . 'admin/view/groups'
				),
				'delete' => array(
					'href' => $group->id ? Registry::get('parameterRoute') . 'admin/delete/groups/' . $group->id . '/' . Registry::get('token') : null
				)
			)
		));

		/* collect item output */

		$tabRoute = Registry::get('parameterRoute') . Registry::get('fullRoute');
		$outputItem = $itemElement
			->copy()
			->addClass('rs-admin-js-item-active rs-admin-item-active')
			->html($linkElement
				->copy()
				->attr('href', $tabRoute . '#tab-1')
				->text(Language::get('group'))
			);
		if (!$group->id || $group->id > 1)
		{
			$outputItem .= $itemElement
				->copy()
				->html($linkElement
					->copy()
					->attr('href', $tabRoute . '#tab-2')
					->text(Language::get('access'))
				);
			$outputItem .= $itemElement
				->copy()
				->html($linkElement
					->copy()
					->attr('href', $tabRoute . '#tab-3')
					->text(Language::get('customize'))
				);
		}
		$listElement->append($outputItem);

		/* create the form */

		$formElement
			->append($listElement)
			->append('<div class="rs-admin-js-box-tab rs-admin-box-tab rs-admin-box-tab">')

			/* first tab */

			->append('<fieldset id="tab-1" class="rs-admin-js-set-tab rs-admin-js-set-active rs-admin-set-tab rs-admin-set-active"><ul><li>')
			->label(Language::get('name'), array(
				'for' => 'name'
			))
			->text(array(
				'autofocus' => 'autofocus',
				'id' => 'name',
				'name' => 'name',
				'required' => 'required',
				'value' => $group->name
			))
			->append('</li><li>')
			->label(Language::get('user'), array(
				'for' => 'user'
			))
			->text(array(
				'id' => 'alias',
				'name' => 'alias',
				'required' => 'required',
				'value' => $group->alias
			))
			->append('</li><li>')
			->label(Language::get('description'), array(
				'for' => 'description'
			))
			->textarea(array(
				'class' => 'rs-admin-js-auto-resize rs-admin-field-textarea rs-admin-field-small',
				'id' => 'description',
				'name' => 'description',
				'value' => $group->description
			))
			->append('</li></ul></fieldset>');

			/* second tab */

		if (!$group->id || $group->id > 1)
		{
			$formElement
			->append('<fieldset id="tab-2" class="rs-admin-js-set-tab rs-admin-set-tab"><ul><li>')
			->label(Language::get('categories'), array(
				'for' => 'categories'
			))
			->select(Helper\Option::getPermissionArray(), array(
				'id' => 'categories',
				'name' => 'categories[]',
				'multiple' => 'multiple',
				'size' => count(Helper\Option::getPermissionArray()),
				'value' => $group->categories
			))
			->append('</li><li>')
			->label(Language::get('articles'), array(
				'for' => 'articles'
			))
			->select(Helper\Option::getPermissionArray(), array(
				'id' => 'articles',
				'name' => 'articles[]',
				'multiple' => 'multiple',
				'size' => count(Helper\Option::getPermissionArray()),
				'value' => $group->articles
			))
			->append('</li><li>')
			->label(Language::get('extras'), array(
				'for' => 'extras'
			))
			->select(Helper\Option::getPermissionArray(), array(
				'id' => 'extras',
				'name' => 'extras[]',
				'multiple' => 'multiple',
				'size' => count(Helper\Option::getPermissionArray()),
				'value' => $group->extras
			))
			->append('</li><li>')
			->label(Language::get('comments'), array(
				'for' => 'comments'
			))
			->select(Helper\Option::getPermissionArray(), array(
				'id' => 'comments',
				'name' => 'comments[]',
				'multiple' => 'multiple',
				'size' => count(Helper\Option::getPermissionArray()),
				'value' => $group->comments
			))
			->append('</li><li>')
			->label(Language::get('groups'), array(
				'for' => 'groups'
			))
			->select(Helper\Option::getPermissionArray(), array(
				'id' => 'groups',
				'name' => 'groups[]',
				'multiple' => 'multiple',
				'size' => count(Helper\Option::getPermissionArray()),
				'value' => $group->groups
			))
			->append('</li><li>')
			->label(Language::get('users'), array(
				'for' => 'users'
			))
			->select(Helper\Option::getPermissionArray(), array(
				'id' => 'users',
				'name' => 'users[]',
				'multiple' => 'multiple',
				'size' => count(Helper\Option::getPermissionArray()),
				'value' => $group->users
			))
			->append('</li><li>')
			->label(Language::get('modules'), array(
				'for' => 'modules'
			))
			->select(Helper\Option::getPermissionArray('modules'), array(
				'id' => 'modules',
				'name' => 'modules[]',
				'multiple' => 'multiple',
				'size' => count(Helper\Option::getPermissionArray('modules')),
				'value' => $group->modules
			))
			->append('</li><li>')
			->label(Language::get('settings'), array(
				'for' => 'settings'
			))
			->select(Helper\Option::getPermissionArray('settings'), array(
				'id' => 'settings',
				'name' => 'settings[]',
				'multiple' => 'multiple',
				'size' => count(Helper\Option::getPermissionArray('settings')),
				'value' => intval($group->settings)
			))
			->append('</li></ul></fieldset>')

			/* last tab */

			->append('<fieldset id="tab-3" class="rs-admin-js-set-tab rs-admin-set-tab"><ul><li>')
			->label(Language::get('filter'), array(
				'for' => 'filter'
			))
			->select(Helper\Option::getToggleArray(), array(
				'id' => 'filter',
				'name' => 'filter',
				'value' => $group->id ? $group->filter : 1
			))
			->append('</li><li>')
			->label(Language::get('status'), array(
				'for' => 'status'
			))
			->select(Helper\Option::getToggleArray(), array(
				'id' => 'status',
				'name' => 'status',
				'value' => $group->id ? intval($group->status) : 1
			))
			->append('</li></ul></fieldset>');
		}
		$formElement
			->append('</div>')
			->token()
			->cancel();
		if ($group->id > 1)
		{
			$formElement->delete();
		}
		if ($group->id)
		{
			$formElement->save();
		}
		else
		{
			$formElement->create();
		}

		/* collect output */

		$output .= $titleElement . $formElement;
		$output .= Hook::trigger('adminGroupFormEnd');
		return $output;
	}
}
