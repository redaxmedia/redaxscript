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
	 * stringify the view
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function __toString()
	{
		return $this->render();
	}

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
			'class' => 'rs-js-list-tab rs-admin-list-tab'
		));
		$formElement = new AdminForm(Registry::getInstance(), Language::getInstance());
		$formElement->init(array(
			'form' => array(
				'action' => 'admin/process/groups',
				'class' => 'rs-js-tab rs-js-validate-form rs-admin-form-default'
			),
			'button' => array(
				'submit' => array(
					'name' => Registry::get('adminParameter')
				)
			)
		));
		$linkCancel = new Html\Element();
		$linkCancel
			->init('a', array(
				'class' => 'rs-js-cancel rs-admin-button-default rs-admin-button-cancel rs-admin-button-large',
				'href' => 'admin/view/groups'
			))
			->text(Language::get('cancel'));
		if ($group->id)
		{
			$linkDelete = new Html\Element();
			$linkDelete
				->init('a', array(
					'class' => 'rs-js-delete rs-js-confirm rs-admin-button-default rs-admin-button-delete rs-admin-button-large',
					'href' => 'admin/delete/groups/' . $group->id . '/' . Registry::get('token')
				))
				->text(Language::get('delete'));
		}

		/* collect item output */

		$tabRoute = Registry::get('rewriteRoute') . Registry::get('fullRoute');
		$outputItem = $itemElement
			->copy()
			->addClass('rs-js-item-active rs-item-active')
			->html($linkElement
				->copy()
				->attr('href', $tabRoute . '#tab-1')
				->text(Language::get('group'))
			);
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
		$listElement->append($outputItem);

		/* create the form */

		$formElement
			->append($listElement)
			->append('<div class="rs-js-box-tab rs-box-tab rs-admin-box-tab">')

			/* first tab */

			->append('<fieldset id="tab-1" class="rs-js-set-tab rs-js-set-active rs-set-tab rs-set-active"><ul><li>')
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
			->label(Language::get('description'), array(
				'for' => 'description'
			))
			->textarea(array(
				'class' => 'rs-js-auto-resize rs-admin-field-textarea rs-field-small',
				'id' => 'description',
				'name' => 'description',
				'value' => $group->description
			))
			->append('</li></ul></fieldset>')

			/* second tab */

			->append('<fieldset id="tab-2" class="rs-js-set-tab rs-set-tab"><ul><li>')
			->label(Language::get('categories'), array(
				'for' => 'categories'
			))
			->select(Helper\Option::getPermissionArray(), array(
				'id' => 'categories',
				'name' => 'categories',
				'multiple' => 'multiple',
				'size' => count(Helper\Option::getPermissionArray()),
				'value' => $group->categories
			))
			->label(Language::get('articles'), array(
				'for' => 'articles'
			))
			->select(Helper\Option::getPermissionArray(), array(
				'id' => 'articles',
				'name' => 'articles',
				'multiple' => 'multiple',
				'size' => count(Helper\Option::getPermissionArray()),
				'value' => $group->articles
			))
			->label(Language::get('extras'), array(
				'for' => 'extras'
			))
			->select(Helper\Option::getPermissionArray(), array(
				'id' => 'extras',
				'name' => 'extras',
				'multiple' => 'multiple',
				'size' => count(Helper\Option::getPermissionArray()),
				'value' => $group->extras
			))
			->label(Language::get('comments'), array(
				'for' => 'comments'
			))
			->select(Helper\Option::getPermissionArray(), array(
				'id' => 'comments',
				'name' => 'comments',
				'multiple' => 'multiple',
				'size' => count(Helper\Option::getPermissionArray()),
				'value' => $group->comments
			))
			->label(Language::get('groups'), array(
				'for' => 'groups'
			))
			->select(Helper\Option::getPermissionArray(), array(
				'id' => 'groups',
				'name' => 'groups',
				'multiple' => 'multiple',
				'size' => count(Helper\Option::getPermissionArray()),
				'value' => $group->groups
			))
			->label(Language::get('users'), array(
				'for' => 'users'
			))
			->select(Helper\Option::getPermissionArray(), array(
				'id' => 'users',
				'name' => 'users',
				'multiple' => 'multiple',
				'size' => count(Helper\Option::getPermissionArray()),
				'value' => $group->users
			))
			->label(Language::get('modules'), array(
				'for' => 'modules'
			))
			->select(Helper\Option::getPermissionArray('modules'), array(
				'id' => 'modules',
				'name' => 'modules',
				'multiple' => 'multiple',
				'size' => count(Helper\Option::getPermissionArray('modules')),
				'value' => $group->modules
			))
			->label(Language::get('settings'), array(
				'for' => 'settings'
			))
			->select(Helper\Option::getPermissionArray('settings'), array(
				'id' => 'settings',
				'name' => 'settings',
				'multiple' => 'multiple',
				'size' => count(Helper\Option::getPermissionArray('settings')),
				'value' => $group->settings
			))
			->append('</li></ul></fieldset>')

			/* last tab */

			->append('<fieldset id="tab-3" class="rs-js-set-tab rs-set-tab"><ul><li>')
			->label(Language::get('filter'), array(
				'for' => 'filter'
			))
			->select(Helper\Option::getToggleArray(), array(
				'id' => 'filter',
				'name' => 'filter',
				'value' => $group->filter
			))
			->label(Language::get('status'), array(
				'for' => 'status'
			))
			->select(Helper\Option::getToggleArray(), array(
				'id' => 'status',
				'name' => 'status',
				'value' => $group->status
			))
			->append('</li><li>')
			->append('</li></ul></fieldset></div>')
			->token()
			->append($linkCancel);
			if ($group->id)
			{
				$formElement
					->append($linkDelete)
					->submit(Language::get('save'));
			}
			else
			{
				$formElement->submit(Language::get('create'));
			}

		/* collect output */

		$output .= $titleElement . $formElement;
		$output .= Hook::trigger('adminGroupFormEnd');
		return $output;
	}
}
