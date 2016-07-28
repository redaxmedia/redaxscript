<?php
namespace Redaxscript\Admin\View;

use Redaxscript\Admin\Html\Form as AdminForm;
use Redaxscript\Admin\View\Helper;
use Redaxscript\Db;
use Redaxscript\Html;
use Redaxscript\Hook;

/**
 * children class to create the group form
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 */

class GroupForm extends ViewAbstract implements ViewInterface
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
		$titleElement->init('h2',
		[
			'class' => 'rs-admin-title-content',
		]);
		$titleElement->text($group->name ? $group->name : $this->_language->get('group_new'));
		$linkElement = new Html\Element();
		$linkElement->init('a');
		$itemElement = new Html\Element();
		$itemElement->init('li');
		$listElement = new Html\Element();
		$listElement->init('ul',
		[
			'class' => 'rs-admin-js-list-tab rs-admin-list-tab'
		]);
		$formElement = new AdminForm($this->_registry, $this->_language);
		$formElement->init(
		[
			'form' =>
			[
				'action' => $this->_registry->get('parameterRoute') . ($group->id ? 'admin/process/groups/' . $group->id : 'admin/process/groups'),
				'class' => 'rs-admin-js-tab rs-admin-js-validate-form rs-admin-form-default rs-admin-fn-clearfix'
			],
			'link' =>
			[
				'cancel' =>
				[
					'href' => $this->_registry->get('groupsEdit') && $this->_registry->get('groupsDelete') ? $this->_registry->get('parameterRoute') . 'admin/view/groups' : $this->_registry->get('parameterRoute') . 'admin'
				],
				'delete' =>
				[
					'href' => $group->id ? $this->_registry->get('parameterRoute') . 'admin/delete/groups/' . $group->id . '/' . $this->_registry->get('token') : null
				]
			]
		]);

		/* collect item output */

		$tabRoute = $this->_registry->get('parameterRoute') . $this->_registry->get('fullRoute');
		$outputItem = $itemElement
			->copy()
			->addClass('rs-admin-js-item-active rs-admin-item-active')
			->html($linkElement
				->copy()
				->attr('href', $tabRoute . '#tab-1')
				->text($this->_language->get('group'))
			);
		if (!$group->id || $group->id > 1)
		{
			$outputItem .= $itemElement
				->copy()
				->html($linkElement
					->copy()
					->attr('href', $tabRoute . '#tab-2')
					->text($this->_language->get('access'))
				);
			$outputItem .= $itemElement
				->copy()
				->html($linkElement
					->copy()
					->attr('href', $tabRoute . '#tab-3')
					->text($this->_language->get('customize'))
				);
		}
		$listElement->append($outputItem);

		/* create the form */

		$formElement
			->append($listElement)
			->append('<div class="rs-admin-js-box-tab rs-admin-box-tab rs-admin-box-tab">')

			/* first tab */

			->append('<fieldset id="tab-1" class="rs-admin-js-set-tab rs-admin-js-set-active rs-admin-set-tab rs-admin-set-active"><ul><li>')
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
				'value' => $group->name
			])
			->append('</li><li>')
			->label($this->_language->get('user'),
			[
				'for' => 'user'
			])
			->text(
			[
				'id' => 'alias',
				'name' => 'alias',
				'required' => 'required',
				'value' => $group->alias
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
				'value' => $group->description
			])
			->append('</li></ul></fieldset>');

			/* second tab */

		if (!$group->id || $group->id > 1)
		{
			$formElement
			->append('<fieldset id="tab-2" class="rs-admin-js-set-tab rs-admin-set-tab"><ul><li>')
			->label($this->_language->get('categories'),
			[
				'for' => 'categories'
			])
			->select(Helper\Option::getPermissionArray(),
			[
				'id' => 'categories',
				'name' => 'categories[]',
				'multiple' => 'multiple',
				'size' => count(Helper\Option::getPermissionArray()),
				'value' => $group->categories
			])
			->append('</li><li>')
			->label($this->_language->get('articles'),
			[
				'for' => 'articles'
			])
			->select(Helper\Option::getPermissionArray(),
			[
				'id' => 'articles',
				'name' => 'articles[]',
				'multiple' => 'multiple',
				'size' => count(Helper\Option::getPermissionArray()),
				'value' => $group->articles
			])
			->append('</li><li>')
			->label($this->_language->get('extras'),
			[
				'for' => 'extras'
			])
			->select(Helper\Option::getPermissionArray(),
			[
				'id' => 'extras',
				'name' => 'extras[]',
				'multiple' => 'multiple',
				'size' => count(Helper\Option::getPermissionArray()),
				'value' => $group->extras
			])
			->append('</li><li>')
			->label($this->_language->get('comments'),
			[
				'for' => 'comments'
			])
			->select(Helper\Option::getPermissionArray(),
			[
				'id' => 'comments',
				'name' => 'comments[]',
				'multiple' => 'multiple',
				'size' => count(Helper\Option::getPermissionArray()),
				'value' => $group->comments
			])
			->append('</li><li>')
			->label($this->_language->get('groups'),
			[
				'for' => 'groups'
			])
			->select(Helper\Option::getPermissionArray(),
			[
				'id' => 'groups',
				'name' => 'groups[]',
				'multiple' => 'multiple',
				'size' => count(Helper\Option::getPermissionArray()),
				'value' => $group->groups
			])
			->append('</li><li>')
			->label($this->_language->get('users'),
			[
				'for' => 'users'
			])
			->select(Helper\Option::getPermissionArray(),
			[
				'id' => 'users',
				'name' => 'users[]',
				'multiple' => 'multiple',
				'size' => count(Helper\Option::getPermissionArray()),
				'value' => $group->users
			])
			->append('</li><li>')
			->label($this->_language->get('modules'),
			[
				'for' => 'modules'
			])
			->select(Helper\Option::getPermissionArray('modules'),
			[
				'id' => 'modules',
				'name' => 'modules[]',
				'multiple' => 'multiple',
				'size' => count(Helper\Option::getPermissionArray('modules')),
				'value' => $group->modules
			])
			->append('</li><li>')
			->label($this->_language->get('settings'),
			[
				'for' => 'settings'
			])
			->select(Helper\Option::getPermissionArray('settings'),
			[
				'id' => 'settings',
				'name' => 'settings[]',
				'multiple' => 'multiple',
				'size' => count(Helper\Option::getPermissionArray('settings')),
				'value' => intval($group->settings)
			])
			->append('</li></ul></fieldset>')

			/* last tab */

			->append('<fieldset id="tab-3" class="rs-admin-js-set-tab rs-admin-set-tab"><ul><li>')
			->label($this->_language->get('filter'),
			[
				'for' => 'filter'
			])
			->select(Helper\Option::getToggleArray(),
			[
				'id' => 'filter',
				'name' => 'filter',
				'value' => $group->id ? $group->filter : 1
			])
			->append('</li><li>')
			->label($this->_language->get('status'),
			[
				'for' => 'status'
			])
			->select(Helper\Option::getToggleArray(),
			[
				'id' => 'status',
				'name' => 'status',
				'value' => $group->id ? intval($group->status) : 1
			])
			->append('</li></ul></fieldset>');
		}
		$formElement
			->append('</div>')
			->token()
			->cancel();
		if ($group->id)
		{
			if ($this->_registry->get('groupsDelete') && $group->id > 1)
			{
				$formElement->delete();
			}
			if ($this->_registry->get('groupsEdit'))
			{
				$formElement->save();
			}
		}
		else if ($this->_registry->get('groupsNew'))
		{
			$formElement->create();
		}

		/* collect output */

		$output .= $titleElement . $formElement;
		$output .= Hook::trigger('adminGroupFormEnd');
		return $output;
	}
}
