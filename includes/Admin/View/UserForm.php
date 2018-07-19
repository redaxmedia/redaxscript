<?php
namespace Redaxscript\Admin\View;

use Redaxscript\Admin;
use Redaxscript\Html;
use Redaxscript\Module;

/**
 * children class to create the user form
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class UserForm extends ViewAbstract
{
	/**
	 * render the view
	 *
	 * @since 3.0.0
	 *
	 * @param int $userId identifier of the user
	 *
	 * @return string
	 */

	public function render(int $userId = null) : string
	{
		$output = Module\Hook::trigger('adminUserFormStart');
		$userModel = new Admin\Model\User();
		$user = $userModel->getById($userId);
		$helperOption = new Helper\Option($this->_language);

		/* html element */

		$titleElement = new Html\Element();
		$titleElement
			->init('h2',
			[
				'class' => 'rs-admin-title-content',
			])
			->text($user->name ? $user->name : $this->_language->get('user_new'));
		$formElement = new Admin\Html\Form($this->_registry, $this->_language);
		$formElement->init(
		[
			'form' =>
			[
				'class' => 'rs-admin-js-validate-form rs-admin-fn-tab rs-admin-component-tab rs-admin-form-default'
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
					'href' => $this->_registry->get('usersEdit') && $this->_registry->get('usersDelete') ? $this->_registry->get('parameterRoute') . 'admin/view/users' : $this->_registry->get('parameterRoute') . 'admin'
				],
				'delete' =>
				[
					'href' => $user->id ? $this->_registry->get('parameterRoute') . 'admin/delete/users/' . $user->id . '/' . $this->_registry->get('token') : null
				]
			]
		]);

		/* create the form */

		$formElement

			/* user */

			->radio(
			[
				'id' => get_class() . '\User',
				'class' => 'rs-admin-fn-status-tab',
				'name' => get_class() . '\Tab',
				'checked' => 'checked'
			])
			->label($this->_language->get('user'),
			[
				'class' => 'rs-admin-fn-toggle-tab rs-admin-label-tab',
				'for' => get_class() . '\User'
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
				'required' => 'required',
				'value' => $user->name
			])
			->append('</li>');
		if (!$user->id)
		{
			$formElement
				->append('<li>')
				->label($this->_language->get('user'),
				[
					'for' => 'user'
				])
				->text(
				[
					'id' => 'user',
					'name' => 'user',
					'pattern' => '[a-zA-Z0-9]{1,30}',
					'required' => 'required',
					'value' => $user->user
				])
				->append('</li>');
		}
		$formElement
			->append('<li>')
			->label($this->_language->get('description'),
			[
				'for' => 'description'
			])
			->textarea(
			[
				'class' => 'rs-admin-js-textarea rs-admin-field-textarea rs-admin-field-small',
				'id' => 'description',
				'name' => 'description',
				'rows' => 1,
				'value' => $user->description
			])
			->append('</li><li>')
			->label($this->_language->get('password'),
			[
				'for' => 'password'
			])
			->password(
			[
				'id' => 'password',
				'pattern' => '[a-zA-Z0-9]{1,30}',
				'name' => 'password'
			])
			->append('</li><li>')
			->label($this->_language->get('password_confirm'),
			[
				'for' => 'password_confirm'
			])
			->password(
			[
				'id' => 'password_confirm',
				'pattern' => '[a-zA-Z0-9]{1,30}',
				'name' => 'password_confirm'
			])
			->append('</li><li>')
			->label($this->_language->get('email'),
			[
				'for' => 'email'
			])
			->email(
			[
				'id' => 'email',
				'name' => 'email',
				'required' => 'required',
				'value' => $user->email
			])
			->append('</li></ul>')

			/* general */

			->radio(
			[
				'id' => get_class() . '\General',
				'class' => 'rs-admin-fn-status-tab',
				'name' => get_class() . '\Tab'
			])
			->label($this->_language->get('general'),
			[
				'class' => 'rs-admin-fn-toggle-tab rs-admin-label-tab',
				'for' => get_class() . '\General'
			])
			->append('<ul class="rs-admin-fn-content-tab rs-admin-box-tab"><li>')
			->label($this->_language->get('language'),
			[
				'for' => 'language'
			])
			->select($helperOption->getLanguageArray(),
			[
				$user->language
			],
			[
				'id' => 'language',
				'name' => 'language'
			])
			->append('</li></ul>');
		if (!$user->id || $user->id > 1)
		{
			$formElement

				/* customize */

				->radio(
				[
					'id' => get_class() . '\Customize',
					'class' => 'rs-admin-fn-status-tab',
					'name' => get_class() . '\Tab'
				])
				->label($this->_language->get('customize'),
				[
					'class' => 'rs-admin-fn-toggle-tab rs-admin-label-tab',
					'for' => get_class() . '\Customize'
				])
				->append('<ul class="rs-admin-fn-content-tab rs-admin-box-tab"><li>')
				->label($this->_language->get('status'),
				[
					'for' => 'status'
				])
				->select($helperOption->getToggleArray(),
				[
					$user->id ? (int)$user->status : 1
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
					->label($this->_language->get('groups'),
					[
						'for' => 'groups'
					])
					->select($helperOption->getAccessArray('groups'),
					[
						$user->groups
					],
					[
						'id' => 'groups',
						'name' => 'groups[]',
						'multiple' => 'multiple',
						'size' => count($helperOption->getAccessArray('groups'))
					])
					->append('</li>');
			}
			$formElement->append('</ul>');
		}
		$formElement
			->hidden(
			[
				'name' => 'id',
				'value' => $user->id
			])
			->token()
			->append('<div class="rs-admin-wrapper-button">')
			->cancel();
		if ($user->id)
		{
			if (($this->_registry->get('usersDelete') || $this->_registry->get('myId') === $user->id) && $user->id > 1)
			{
				$formElement->delete();
			}
			if ($this->_registry->get('usersEdit') || $this->_registry->get('myId') === $user->id)
			{
				$formElement->save();
			}
		}
		else if ($this->_registry->get('usersNew'))
		{
			$formElement->create();
		}
		$formElement->append('</div>');

		/* collect output */

		$output .= $titleElement . $formElement;
		$output .= Module\Hook::trigger('adminUserFormEnd');
		return $output;
	}
}
