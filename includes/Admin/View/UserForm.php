<?php
namespace Redaxscript\Admin\View;

use Redaxscript\Admin\Html\Form as AdminForm;
use Redaxscript\Db;
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

class UserForm extends ViewAbstract implements ViewInterface
{
	/**
	 * render the view
	 *
	 * @since 3.0.0
	 *
	 * @param int|bool $userId identifier of the user
	 *
	 * @return string
	 */

	public function render(int $userId = null) : string
	{
		$output = Module\Hook::trigger('adminUserFormStart');
		$user = Db::forTablePrefix('users')->whereIdIs($userId)->findOne();
		$helperOption = new Helper\Option($this->_language);

		/* html element */

		$titleElement = new Html\Element();
		$titleElement
			->init('h2',
			[
				'class' => 'rs-admin-title-content',
			])
			->text($user->name ? $user->name : $this->_language->get('user_new'));
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
			->append($this->_renderList($user->id))
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
					'pattern' => '[a-zA-Z0-9]{1,30}',
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
				'value' => $user->description
			])
			->append('</li></ul></fieldset>')

			/* second tab */

			->append('<fieldset id="tab-2" class="rs-admin-js-set-tab rs-admin-set-tab"><ul><li>')
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
				->select($helperOption->getToggleArray(),
				[
					$user->id ? intval($user->status) : 1
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
		$output .= Module\Hook::trigger('adminUserFormEnd');
		return $output;
	}

	/**
	 * render the list
	 *
	 * @since 3.2.0
	 *
	 * @param int $userId identifier of the user
	 *
	 * @return string
	 */

	protected function _renderList(int $userId = null) : string
	{
		$tabRoute = $this->_registry->get('parameterRoute') . $this->_registry->get('fullRoute');

		/* html element */

		$element = new Html\Element();
		$listElement = $element
			->copy()
			->init('ul',
			[
				'class' => 'rs-admin-js-list-tab rs-admin-list-tab'
			]);
		$itemElement = $element->copy()->init('li');
		$linkElement = $element->copy()->init('a');

		/* collect item output */

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
		if (!$userId || $userId > 1)
		{
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
