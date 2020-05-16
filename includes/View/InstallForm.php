<?php
namespace Redaxscript\View;

use Redaxscript\Html;
use Redaxscript\Module;
use Redaxscript\Validator;

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
	 * @param array $installArray
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function render(array $installArray = []) : string
	{
		$output = Module\Hook::trigger('installFormStart');
		$nameValidator = new Validator\Name();
		$userValidator = new Validator\User();
		$passwordValidator = new Validator\Password();

		/* html element */

		$titleElement = new Html\Element();
		$titleElement
			->init('h2',
			[
				'class' => 'rs-title-content'
			])
			->text($this->_language->get('installation'));
		$formElement = new Html\Form($this->_registry, $this->_language);
		$formElement->init(
		[
			'form' =>
			[
				'class' => 'rs-js-validate rs-install-js-behavior rs-component-accordion rs-form-default rs-install-form-default'
			],
			'button' =>
			[
				'submit' =>
				[
					'class' => 'rs-js-submit rs-button-default rs-is-full',
					'name' => self::class
				]
			]
		]);

		/* create the form */

		$formElement

			/* database */

			->radio(
			[
				'id' => self::class . '\Database',
				'class' => 'rs-fn-status-accordion',
				'name' => self::class . '\Accordion',
				'checked' => 'checked'
			])
			->label($this->_language->get('database_setup'),
			[
				'class' => 'rs-fn-toggle-accordion rs-label-accordion',
				'for' => self::class . '\Database'
			])
			->append('<ul class="rs-fn-content-accordion rs-box-accordion"><li>');
		if ($this->_registry->get('driverArray'))
		{
			$formElement
				->append('</li><li>')
				->label($this->_language->get('type'),
				[
					'for' => 'db-type'
				])
				->select($this->_registry->get('driverArray'),
				[
					$installArray['dbType']
				],
				[
					'id' => 'db-type',
					'name' => 'db-type'
				])
				->append('</li><li>');
		}
		$formElement
			->append('<li>')
			->label($this->_language->get('host'),
			[
				'for' => 'db-host'
			])
			->text(
			[
				'id' => 'db-host',
				'name' => 'db-host',
				'required' => 'required',
				'value' => $installArray['dbHost']
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
				'value' => $installArray['dbName']
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
				'value' => $installArray['dbUser']
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
				'value' => $installArray['dbPassword']
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
				'value' => $installArray['dbPrefix']
			])
			->append('</li></ul>')

			/* account */

			->radio(
			[
				'id' => self::class . '\Account',
				'class' => 'rs-fn-status-accordion',
				'name' => self::class . '\Accordion'
			])
			->label($this->_language->get('account_create'),
			[
				'class' => 'rs-fn-toggle-accordion rs-label-accordion',
				'for' => self::class . '\Account'
			])
			->append('<ul class="rs-fn-content-accordion rs-box-accordion"><li>')
			->label($this->_language->get('name'),
			[
				'for' => 'name'
			])
			->text(
			[
				'id' => 'admin-name',
				'name' => 'admin-name',
				'pattern' => $nameValidator->getPattern(),
				'required' => 'required',
				'value' => $installArray['adminName']
			])
			->append('</li><li>')
			->label($this->_language->get('user'),
			[
				'for' => 'admin-user'
			])
			->text(
			[
				'id' => 'admin-user',
				'name' => 'admin-user',
				'pattern' => $userValidator->getPattern(),
				'required' => 'required',
				'value' => $installArray['adminUser']
			])
			->append('</li><li>')
			->label($this->_language->get('password'),
			[
				'for' => 'admin-password'
			])
			->password(
			[
				'id' => 'admin-password',
				'name' => 'admin-password',
				'pattern' => $passwordValidator->getPattern(),
				'required' => 'required',
				'value' => $installArray['adminPassword']
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
				'value' => $installArray['adminEmail']
			])
			->append('</li></ul>')
			->hidden(
			[
				'name' => 'refresh-connection',
				'value' => 1
			])
			->token()
			->submit($this->_language->get('install'));

		/* collect output */

		$output .= $titleElement . $formElement;
		$output .= Module\Hook::trigger('installFormEnd');
		return $output;
	}
}
