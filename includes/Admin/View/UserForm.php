<?php
namespace Redaxscript\Admin\View;

use Redaxscript\Admin\Html\Form as AdminForm;
use Redaxscript\Admin\View\Helper;
use Redaxscript\Db;
use Redaxscript\Html;
use Redaxscript\Hook;

/**
 * children class to create the user form
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 */

class UserForm extends ViewAbstract implements ViewInterface
{
	/**
	 * render the view
	 *
	 * @since 3.0.0
	 *
	 * @param integer $userId identifier of the user
	 *
	 * @return string
	 */

	public function render($userId = null)
	{
		$output = Hook::trigger('adminUserFormStart');
		$user = Db::forTablePrefix('users')->whereIdIs($userId)->findOne();

		/* html elements */

		$titleElement = new Html\Element();
		$titleElement->init('h2',
		[
			'class' => 'rs-admin-title-content',
		]);
		$titleElement->text($user->name ? $user->name : $this->_language->get('user_new'));
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
				'action' => $this->_registry->get('parameterRoute') . ($user->id ? 'admin/process/users/' . $user->id : 'admin/process/users'),
				'class' => 'rs-admin-js-tab rs-admin-js-validate-form rs-admin-form-default rs-admin-fn-clearfix'
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

		/* collect item output */

		$tabRoute = $this->_registry->get('parameterRoute') . $this->_registry->get('fullRoute');
		$outputItem = $itemElement
			->copy()
			->addClass('rs-admin-js-item-active rs-admin-item-active')
			->html($linkElement
				->copy()
				->attr('href', $tabRoute . '#tab-1')
				->text($this->_language->get('user'))
			);
		$outputItem .= $itemElement
			->copy()
			->html($linkElement
				->copy()
				->attr('href', $tabRoute . '#tab-2')
				->text($this->_language->get('general'))
			);
		if (!$user->id || $user->id > 1)
		{
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
			->append('<div class="rs-admin-js-box-tab rs-admin-box-tab">')

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
					'required' => 'required',
					'value' => $user->user
				])
				->append('</li>');
		}
		$formElement
			->append('<li>')
			->label($this->_language->get('password'),
			[
				'for' => 'password'
			])
			->password(
			[
				'id' => 'password',
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
				'value' => $user->description
			])
			->append('</li></ul></fieldset>')

			/* second tab */

			->append('<fieldset id="tab-2" class="rs-admin-js-set-tab rs-admin-set-tab"><ul><li>')
			->label($this->_language->get('language'),
			[
				'for' => 'language'
			])
			->select(Helper\Option::getLanguageArray(),
			[
				'id' => 'language',
				'name' => 'language',
				'value' => $user->language
			])
			->append('</li></ul></fieldset>');

			/* last tab */

		if (!$user->id || $user->id > 1)
		{
			$formElement
				->append('<fieldset id="tab-3" class="rs-admin-js-set-tab rs-admin-set-tab"><ul><li>')
				->label($this->_language->get('status'),
				[
					'for' => 'status'
				])
				->select(Helper\Option::getToggleArray(),
				[
					'id' => 'status',
					'name' => 'status',
					'value' => $user->id ? intval($user->status) : 1
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
					->select(Helper\Option::getAccessArray('groups'),
					[
						'id' => 'groups',
						'name' => 'groups[]',
						'multiple' => 'multiple',
						'size' => count(Helper\Option::getAccessArray('groups')),
						'value' => $user->groups
					])
					->append('</li>');
			}
			$formElement->append('</ul></fieldset>');
		}
		$formElement
			->append('</div>')
			->token()
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

		/* collect output */

		$output .= $titleElement . $formElement;
		$output .= Hook::trigger('adminUserFormEnd');
		return $output;
	}
}
