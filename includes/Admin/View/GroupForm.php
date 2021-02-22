<?php
namespace Redaxscript\Admin\View;

use Redaxscript\Admin;
use Redaxscript\Html;
use Redaxscript\Module;
use Redaxscript\Validator;
use function count;
use function json_decode;

/**
 * children class to create the group form
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class GroupForm extends ViewAbstract
{
	/**
	 * render the view
	 *
	 * @since 3.0.0
	 *
	 * @param int $groupId identifier of the group
	 *
	 * @return string
	 */

	public function render(int $groupId = null) : string
	{
		$output = Module\Hook::trigger('adminGroupFormStart');
		$groupModel = new Admin\Model\Group();
		$group = $groupModel->getById($groupId);
		$aliasValidator = new Validator\Alias();
		$nameValidator = new Validator\Name();
		$helperOption = new Helper\Option($this->_language);

		/* html element */

		$titleElement = new Html\Element();
		$titleElement
			->init('h2',
			[
				'class' => 'rs-admin-title-content',
			])
			->text($group?->id ? $group?->name : $this->_language->get('group_new'));
		$formElement = new Admin\Html\Form($this->_registry, $this->_language);
		$formElement->init(
		[
			'form' =>
			[
				'class' => 'rs-admin-js-validate rs-admin-js-alias rs-admin-fn-tab rs-admin-component-tab rs-admin-form-default rs-admin-form-group'
			],
			'button' =>
			[
				'create' =>
				[
					'name' => self::class
				],
				'save' =>
				[
					'name' => self::class
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
					'href' => $group?->id ? $this->_registry->get('parameterRoute') . 'admin/delete/groups/' . $group?->id . '/' . $this->_registry->get('token') : null
				]
			]
		]);

		/* create the form */

		$formElement

			/* group */

			->radio(
			[
				'id' => self::class . '\Group',
				'class' => 'rs-admin-fn-status-tab',
				'name' => self::class . '\Tab',
				'checked' => 'checked'
			])
			->label($this->_language->get('group'),
			[
				'class' => 'rs-admin-fn-toggle-tab rs-admin-label-tab',
				'for' => self::class . '\Group'
			])
			->append('<ul class="rs-admin-fn-content-tab rs-admin-box-tab"><li>')
			->label($this->_language->get('name'),
			[
				'for' => 'name'
			])
			->text(
			[
				'autofocus' => 'autofocus',
				'class' => 'rs-admin-js-alias-input rs-admin-field-default rs-admin-field-text',
				'id' => 'name',
				'name' => 'name',
				'pattern' => $nameValidator->getPattern(),
				'required' => 'required',
				'value' => $group?->name
			])
			->append('</li><li>')
			->label($this->_language->get('alias'),
			[
				'for' => 'alias'
			])
			->text(
			[
				'class' => 'rs-admin-js-alias-output rs-admin-field-default rs-admin-field-text',
				'id' => 'alias',
				'name' => 'alias',
				'pattern' => $aliasValidator->getPattern(),
				'required' => 'required',
				'value' => $group?->alias
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
				'value' => $group?->description
			])
			->append('</li></ul>');
		if (!$group?->id || $group?->id > 1)
		{
			$formElement

				/* access */

				->radio(
				[
					'id' => self::class . '\Access',
					'class' => 'rs-admin-fn-status-tab',
					'name' => self::class . '\Tab'
				])
				->label($this->_language->get('access'),
				[
					'class' => 'rs-admin-fn-toggle-tab rs-admin-label-tab',
					'for' => self::class . '\Access'
				])
				->append('<ul class="rs-admin-fn-content-tab rs-admin-box-tab"><li>')
				->label($this->_language->get('categories'),
				[
					'for' => 'categories'
				])
				->select($helperOption->getPermissionArray('groups'),
				(array)json_decode($group?->categories),
				[
					'id' => 'categories',
					'name' => 'categories[]',
					'multiple' => 'multiple',
					'size' => count($helperOption->getPermissionArray('groups'))
				])
				->append('</li><li>')
				->label($this->_language->get('articles'),
				[
					'for' => 'articles'
				])
				->select($helperOption->getPermissionArray('groups'),
				(array)json_decode($group?->articles),
				[
					'id' => 'articles',
					'name' => 'articles[]',
					'multiple' => 'multiple',
					'size' => count($helperOption->getPermissionArray('groups'))
				])
				->append('</li><li>')
				->label($this->_language->get('extras'),
				[
					'for' => 'extras'
				])
				->select($helperOption->getPermissionArray('groups'),
				(array)json_decode($group?->extras),
				[
					'id' => 'extras',
					'name' => 'extras[]',
					'multiple' => 'multiple',
					'size' => count($helperOption->getPermissionArray('groups'))
				])
				->append('</li><li>')
				->label($this->_language->get('comments'),
				[
					'for' => 'comments'
				])
				->select($helperOption->getPermissionArray('groups'),
				(array)json_decode($group?->comments),
				[
					'id' => 'comments',
					'name' => 'comments[]',
					'multiple' => 'multiple',
					'size' => count($helperOption->getPermissionArray('groups'))
				])
				->append('</li><li>')
				->label($this->_language->get('groups'),
				[
					'for' => 'groups'
				])
				->select($helperOption->getPermissionArray('groups'),
				(array)json_decode($group?->groups),
				[
					'id' => 'groups',
					'name' => 'groups[]',
					'multiple' => 'multiple',
					'size' => count($helperOption->getPermissionArray('groups'))
				])
				->append('</li><li>')
				->label($this->_language->get('users'),
				[
					'for' => 'users'
				])
				->select($helperOption->getPermissionArray('groups'),
				(array)json_decode($group?->users),
				[
					'id' => 'users',
					'name' => 'users[]',
					'multiple' => 'multiple',
					'size' => count($helperOption->getPermissionArray('groups'))
				])
				->append('</li><li>')
				->label($this->_language->get('modules'),
				[
					'for' => 'modules'
				])
				->select($helperOption->getPermissionArray('modules'),
				(array)json_decode($group?->modules),
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
					$group?->settings
				],
				[
					'id' => 'settings',
					'name' => 'settings'
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
				->label($this->_language->get('filter'),
				[
					'for' => 'filter'
				])
				->checkbox(!$group?->id || $group?->filter ?
				[
					'id' => 'filter',
					'class' => 'rs-admin-fn-status-switch',
					'name' => 'filter',
					'checked' => 'checked'
				] :
				[
					'id' => 'filter',
					'class' => 'rs-admin-fn-status-switch',
					'name' => 'filter'
				])
				->label(null,
				[
					'class' => 'rs-admin-label-switch',
					'for' => 'filter',
					'data-on' => $this->_language->get('enable'),
					'data-off' => $this->_language->get('disable')
				])
				->append('</li><li>')
				->label($this->_language->get('status'),
				[
					'for' => 'status'
				])
				->checkbox(!$group?->id || $group?->status ?
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
				->append('</li></ul>');
		}
		if ($group?->id)
		{
			$formElement
				->hidden(
				[
					'name' => 'id',
					'value' => $group?->id
				]);
		}
		$formElement
			->token()
			->append('<div class="rs-admin-wrapper-button">')
			->cancel();
		if ($group?->id)
		{
			if ($this->_registry->get('groupsDelete') && $group?->id > 1)
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
		$formElement->append('</div>');

		/* collect output */

		$output .= $titleElement . $formElement;
		$output .= Module\Hook::trigger('adminGroupFormEnd');
		return $output;
	}
}
