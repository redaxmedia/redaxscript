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
 * children class to generate the user form
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 */

class UserForm implements ViewInterface
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
		$titleElement->init('h2', array(
			'class' => 'rs-admin-title-content',
		));
		$titleElement->text($user->name ? $user->name : Language::get('user_new'));
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
				'action' => Registry::get('parameterRoute') . ($user->id ? 'admin/process/users/' . $user->id : 'admin/process/users'),
				'class' => 'rs-admin-js-tab rs-admin-js-validate-form rs-admin-form-default'
			),
			'link' => array(
				'cancel' => array(
					'href' => Registry::get('parameterRoute') . 'admin/view/users'
				),
				'delete' => array(
					'href' => $user->id ? Registry::get('parameterRoute') . 'admin/delete/users/' . $user->id . '/' . Registry::get('token') : null
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
				->text(Language::get('user'))
			);
		$outputItem .= $itemElement
			->copy()
			->html($linkElement
				->copy()
				->attr('href', $tabRoute . '#tab-2')
				->text(Language::get('general'))
			);
		if (!$user->id || $user->id > 1)
		{
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
			->append('<div class="rs-admin-js-box-tab rs-admin-box-tab">')

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
				'value' => $user->name
			))
			->append('</li>');
		if (!$user->id)
		{
			$formElement
				->append('<li>')
				->label(Language::get('user'), array(
					'for' => 'user'
				))
				->text(array(
					'id' => 'user',
					'name' => 'user',
					'required' => 'required',
					'value' => $user->user
				))
				->append('</li>');
		}
		$formElement
			->append('<li>')
			->label(Language::get('password'), array(
				'for' => 'password'
			))
			->password(array(
				'id' => 'password',
				'name' => 'password'
			))
			->append('</li><li>')
			->label(Language::get('password_confirm'), array(
				'for' => 'password_confirm'
			))
			->password(array(
				'id' => 'password_confirm',
				'name' => 'password_confirm'
			))
			->append('</li><li>')
			->label(Language::get('email'), array(
				'for' => 'email'
			))
			->email(array(
				'id' => 'email',
				'name' => 'email',
				'required' => 'required',
				'value' => $user->email
			))
			->append('</li><li>')
			->label(Language::get('description'), array(
				'for' => 'description'
			))
			->textarea(array(
				'class' => 'rs-admin-js-auto-resize rs-admin-field-textarea rs-admin-field-small',
				'id' => 'description',
				'name' => 'description',
				'value' => $user->description
			))
			->append('</li></ul></fieldset>')

			/* second tab */

			->append('<fieldset id="tab-2" class="rs-admin-js-set-tab rs-admin-set-tab"><ul><li>')
			->label(Language::get('language'), array(
				'for' => 'language'
			))
			->select(Helper\Option::getLanguageArray(), array(
				'id' => 'language',
				'name' => 'language',
				'value' => $user->language
			))
			->append('</li></ul></fieldset>');

			/* last tab */

		if (!$user->id || $user->id > 1)
		{
			$formElement
				->append('<fieldset id="tab-3" class="rs-admin-js-set-tab rs-admin-set-tab"><ul><li>')
				->label(Language::get('status'), array(
					'for' => 'status'
				))
				->select(Helper\Option::getToggleArray(), array(
					'id' => 'status',
					'name' => 'status',
					'value' => $user->id ? intval($user->status) : 1
				))
				->append('</li><li>')
				->label(Language::get('groups'), array(
					'for' => 'groups'
				))
				->select(Helper\Option::getAccessArray('groups'), array(
					'id' => 'groups',
					'name' => 'groups[]',
					'multiple' => 'multiple',
					'size' => count(Helper\Option::getAccessArray('groups')),
					'value' => $user->groups
				))
				->append('</li></ul></fieldset>');
		}
		$formElement
			->append('</div>')
			->token()
			->cancel();
		if ($user->id > 1)
		{
			$formElement->delete();
		}
		if ($user->id)
		{
			$formElement->save();
		}
		else
		{
			$formElement->create();
		}

		/* collect output */

		$output .= $titleElement . $formElement;
		$output .= Hook::trigger('adminUserFormEnd');
		return $output;
	}
}
