<?php
namespace Redaxscript\Admin\View;

use Redaxscript\Admin\Html\Form as AdminForm;
use Redaxscript\Db;
use Redaxscript\Html;
use Redaxscript\Module;

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
	 * @param int|bool $groupId identifier of the group
	 *
	 * @return string
	 */

	public function render(int $groupId = null) : string
	{
		$output = Module\Hook::trigger('adminGroupFormStart');
		$group = Db::forTablePrefix('groups')->whereIdIs($groupId)->findOne();
		$helperOption = new Helper\Option($this->_language);

		/* html elements */

		$titleElement = new Html\Element();
		$titleElement->init('h2',
		[
			'class' => 'rs-admin-title-content',
		]);
		$titleElement->text($group->name ? $group->name : $this->_language->get('group_new'));
		$formElement = new AdminForm($this->_registry, $this->_language);
		$formElement->init(
		[
			'form' =>
			[
				'class' => 'rs-admin-js-tab rs-admin-js-validate-form rs-admin-component-tab rs-admin-form-default rs-admin-fn-clearfix'
			],
			'button' =>
			[
				'create' =>
				[
					'name' => get_class()
				],
				'save' =>
				[
					'name' => get_class()
				]
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

		/* create the form */

		$formElement
			->append($this->_renderList($group->id))
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
				'pattern' => '[a-zA-Z0-9-]+',
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
				'rows' => 1,
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
			->select($helperOption->getPermissionArray(),
			[
				$group->categories
			],
			[
				'id' => 'categories',
				'name' => 'categories[]',
				'multiple' => 'multiple',
				'size' => count($helperOption->getPermissionArray())
			])
			->append('</li><li>')
			->label($this->_language->get('articles'),
			[
				'for' => 'articles'
			])
			->select($helperOption->getPermissionArray(),
			[
				$group->articles
			],
			[
				'id' => 'articles',
				'name' => 'articles[]',
				'multiple' => 'multiple',
				'size' => count($helperOption->getPermissionArray())
			])
			->append('</li><li>')
			->label($this->_language->get('extras'),
			[
				'for' => 'extras'
			])
			->select($helperOption->getPermissionArray(),
			[
				$group->extras
			],
			[
				'id' => 'extras',
				'name' => 'extras[]',
				'multiple' => 'multiple',
				'size' => count($helperOption->getPermissionArray())
			])
			->append('</li><li>')
			->label($this->_language->get('comments'),
			[
				'for' => 'comments'
			])
			->select($helperOption->getPermissionArray(),
			[
				$group->comments
			],
			[
				'id' => 'comments',
				'name' => 'comments[]',
				'multiple' => 'multiple',
				'size' => count($helperOption->getPermissionArray())
			])
			->append('</li><li>')
			->label($this->_language->get('groups'),
			[
				'for' => 'groups'
			])
			->select($helperOption->getPermissionArray(),
			[
				$group->groups
			],
			[
				'id' => 'groups',
				'name' => 'groups[]',
				'multiple' => 'multiple',
				'size' => count($helperOption->getPermissionArray())
			])
			->append('</li><li>')
			->label($this->_language->get('users'),
			[
				'for' => 'users'
			])
			->select($helperOption->getPermissionArray(),
			[
				$group->users
			],
			[
				'id' => 'users',
				'name' => 'users[]',
				'multiple' => 'multiple',
				'size' => count($helperOption->getPermissionArray())
			])
			->append('</li><li>')
			->label($this->_language->get('modules'),
			[
				'for' => 'modules'
			])
			->select($helperOption->getPermissionArray('modules'),
			[
				$group->modules
			],
			[
				'id' => 'modules',
				'name' => 'modules[]',
				'multiple' => 'multiple',
				'size' => count($helperOption->getPermissionArray('modules'))
			])
			->append('</li><li>')
			->label($this->_language->get('settings'),
			[
				'for' => 'settings'
			])
			->select($helperOption->getPermissionArray('settings'),
			[
				intval($group->settings)
			],
			[
				'id' => 'settings',
				'name' => 'settings'
			])
			->append('</li></ul></fieldset>')

			/* last tab */

			->append('<fieldset id="tab-3" class="rs-admin-js-set-tab rs-admin-set-tab"><ul><li>')
			->label($this->_language->get('filter'),
			[
				'for' => 'filter'
			])
			->select($helperOption->getToggleArray(),
			[
				$group->id ? $group->filter : 1
			],
			[
				'id' => 'filter',
				'name' => 'filter'
			])
			->append('</li><li>')
			->label($this->_language->get('status'),
			[
				'for' => 'status'
			])
			->select($helperOption->getToggleArray(),
			[
				$group->id ? intval($group->status) : 1
			],
			[
				'id' => 'status',
				'name' => 'status'
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
		$output .= Module\Hook::trigger('adminGroupFormEnd');
		return $output;
	}

	/**
	 * render the list
	 *
	 * @since 3.2.0
	 *
	 * @param int $groupId identifier of the group
	 *
	 * @return string
	 */

	protected function _renderList(int $groupId = null) : string
	{
		$tabRoute = $this->_registry->get('parameterRoute') . $this->_registry->get('fullRoute');

		/* html elements */

		$linkElement = new Html\Element();
		$linkElement->init('a');
		$itemElement = new Html\Element();
		$itemElement->init('li');
		$listElement = new Html\Element();
		$listElement->init('ul',
		[
			'class' => 'rs-admin-js-list-tab rs-admin-list-tab'
		]);

		/* collect item output */

		$outputItem = $itemElement
			->copy()
			->addClass('rs-admin-js-item-active rs-admin-item-active')
			->html($linkElement
				->copy()
				->attr('href', $tabRoute . '#tab-1')
				->text($this->_language->get('group'))
			);
		if (!$groupId || $groupId > 1)
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
		return $listElement->html($outputItem)->render();
	}
}
