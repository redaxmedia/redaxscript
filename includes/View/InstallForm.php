<?php
namespace Redaxscript\View;

use Redaxscript\Html;
use Redaxscript\Hook;

/**
 * children class to create the install form
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class InstallForm extends ViewAbstract
{
	/**
	 * render the view
	 *
	 * @param array $optionArray options of the form
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function render($optionArray = [])
	{
		$output = Hook::trigger('installFormStart');

		/* html elements */

		$titleElement = new Html\Element();
		$titleElement
			->init('h2',
			[
				'class' => 'rs-title-content'
			])->text($this->_language->get('installation', '_installation'));
		$formElement = new Html\Form($this->_registry, $this->_language);
		$formElement->init(
		[
			'form' =>
			[
				'class' => 'rs-install-js-form rs-js-accordion rs-js-validate-form rs-form-default rs-install-form-default'
			],
			'button' =>
			[
				'submit' =>
				[
					'class' => 'rs-js-submit rs-button-default rs-button-large rs-button-full',
					'name' => get_class()
				]
			]
		]);

		/* create the form */

		$formElement

			/* database set */

			->append('<fieldset class="rs-js-set-accordion rs-js-set-active rs-set-accordion rs-set-active">')
			->append('<legend class="rs-js-title-accordion rs-js-title-active rs-title-accordion rs-title-active">' . $this->_language->get('database_setup') . '</legend>')
			->append('<ul class="rs-js-box-accordion rs-js-box-active rs-box-accordion rs-box-active"><li>')
			->label($this->_language->get('type'),
			[
				'for' => 'db-type'
			])
			->select(Helper\Option::getDatabaseArray(),
			[
				'id' => 'db-type',
				'name' => 'db-type',
				'value' => $optionArray['dbType']
			])
			->append('</li><li>')
			->label($this->_language->get('host'),
			[
				'for' => 'db-host'
			])
			->text(
			[
				'data-sqlite' => uniqid() . '.sqlite',
				'data-mysql' => 'localhost',
				'data-pgsql' => 'localhost',
				'id' => 'db-host',
				'name' => 'db-host',
				'required' => 'required',
				'value' => $optionArray['dbHost']
			])
			->append('</li><li>')
			->label($this->_language->get('name'),
			[
				'for' => 'db-name'
			])
			->text(
			[
				'id' => 'db-name',
				'name' => 'db-name',
				'required' => 'required',
				'value' => $optionArray['dbName']
			])
			->append('</li><li>')
			->label($this->_language->get('user'),
			[
				'for' => 'db-user'
			])
			->text(
			[
				'id' => 'db-user',
				'name' => 'db-user',
				'required' => 'required',
				'value' => $optionArray['dbUser']
			])
			->append('</li><li>')
			->label($this->_language->get('password'),
			[
				'for' => 'db-password'
			])
			->password(
			[
				'id' => 'db-password',
				'name' => 'db-password',
				'value' => $optionArray['dbPassword']
			])
			->append('</li><li>')
			->label($this->_language->get('prefix'),
			[
				'for' => 'db-prefix'
			])
			->text(
			[
				'id' => 'db-prefix',
				'name' => 'db-prefix',
				'value' => $optionArray['dbPrefix']
			])
			->append('</li></ul></fieldset>')

			/* account set */

			->append('<fieldset class="rs-js-set-accordion rs-set-accordion">')
			->append('<legend class="rs-js-title-accordion rs-title-accordion">' . $this->_language->get('account_create') . '</legend>')
			->append('<ul class="rs-js-box-accordion rs-box-accordion"><li>')
			->label($this->_language->get('name'),
			[
				'for' => 'name'
			])
			->text(
			[
				'id' => 'admin-name',
				'name' => 'admin-name',
				'required' => 'required',
				'value' => $optionArray['adminName']
			])
			->append('</li><li>')
			->label($this->_language->get('name'),
			[
				'for' => 'admin-user'
			])
			->text(
			[
				'id' => 'admin-user',
				'name' => 'admin-user',
				'required' => 'required',
				'value' => $optionArray['adminUser']
			])
			->append('</li><li>')
			->label($this->_language->get('password'),
			[
				'for' => 'admin_password'
			])
			->password(
			[
				'id' => 'admin-password',
				'name' => 'admin-password',
				'required' => 'required',
				'value' => $optionArray['adminPassword']
			])
			->append('</li><li>')
			->label($this->_language->get('email'),
			[
				'for' => 'admin-email'
			])
			->email(
			[
				'id' => 'admin-email',
				'name' => 'admin-email',
				'required' => 'required',
				'value' => $optionArray['adminEmail']
			])
			->append('</li></ul></fieldset>')
			->hidden(
			[
				'name' => 'db-salt',
				'value' => sha1(uniqid())
			])
			->hidden(
			[
				'name' => 'refresh-connection',
				'value' => 1
			])
			->token()
			->submit($this->_language->get('install'));

		/* collect output */

		$output .= $titleElement . $formElement;
		$output .= Hook::trigger('installFormEnd');
		return $output;
	}
}
