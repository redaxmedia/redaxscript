<?php
namespace Redaxscript\View;

use Redaxscript\Html;
use Redaxscript\Hook;

/**
 * children class to generate the install form
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
	 * @param array $optionArray options of the installation
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function render($optionArray = array())
	{
		$output = Hook::trigger('installFormStart');

		/* html elements */

		$titleElement = new Html\Element();
		$titleElement
			->init('h2', array(
				'class' => 'rs-title-content'
			))->text($this->_language->get('installation'));
		$formElement = new Html\Form($this->_registry, $this->_language);
		$formElement->init(array(
			'form' => array(
				'class' => 'rs-install-js-form rs-js-accordion rs-js-validate-form rs-form-default rs-install-form-default'
			),
			'button' => array(
				'submit' => array(
					'class' => 'rs-js-submit rs-button-default rs-button-large rs-button-full',
					'name' => get_class()
				)
			)
		));

		/* create the form */

		$formElement

			/* database set */

			->append('<fieldset class="rs-js-set-accordion rs-js-set-active rs-set-accordion rs-set-active">')
			->append('<legend class="rs-js-title-accordion rs-js-title-active rs-title-accordion rs-title-active">' . $this->_language->get('database_setup') . '</legend>')
			->append('<ul class="rs-js-box-accordion rs-js-box-active rs-box-accordion rs-box-active"><li>')
			->label($this->_language->get('type'), array(
				'for' => 'db-type'
			))
			->select(Helper\Option::getDatabaseArray(), array(
				'id' => 'db-type',
				'name' => 'db-type',
				'value' => $optionArray['dbType']
			))
			->append('</li><li>')
			->label($this->_language->get('host'), array(
				'for' => 'db-host'
			))
			->text(array(
				'data-sqlite' => uniqid() . '.sqlite',
				'data-mysql' => 'localhost',
				'data-pgsql' => 'localhost',
				'id' => 'db-host',
				'name' => 'db-host',
				'required' => 'required',
				'value' => $optionArray['dbHost']
			))
			->append('</li><li>')
			->label($this->_language->get('name'), array(
				'for' => 'db-name'
			))
			->text(array(
				'id' => 'db-name',
				'name' => 'db-name',
				'required' => 'required',
				'value' => $optionArray['dbName']
			))
			->append('</li><li>')
			->label($this->_language->get('user'), array(
				'for' => 'db-user'
			))
			->text(array(
				'id' => 'db-user',
				'name' => 'db-user',
				'required' => 'required',
				'value' => $optionArray['dbUser']
			))
			->append('</li><li>')
			->label($this->_language->get('password'), array(
				'for' => 'db-password'
			))
			->password(array(
				'id' => 'db-password',
				'name' => 'db-password',
				'value' => $optionArray['dbPassword']
			))
			->append('</li><li>')
			->label($this->_language->get('prefix'), array(
				'for' => 'db-prefix'
			))
			->text(array(
				'id' => 'db-prefix',
				'name' => 'db-prefix',
				'value' => $optionArray['dbPrefix']
			))
			->append('</li></ul></fieldset>')

			/* account set */

			->append('<fieldset class="rs-js-set-accordion rs-set-accordion">')
			->append('<legend class="rs-js-title-accordion rs-title-accordion">' . $this->_language->get('account_create') . '</legend>')
			->append('<ul class="rs-js-box-accordion rs-box-accordion"><li>')
			->label($this->_language->get('name'), array(
				'for' => 'name'
			))
			->text(array(
				'id' => 'admin-name',
				'name' => 'admin-name',
				'required' => 'required',
				'value' => $optionArray['adminName']
			))
			->append('</li><li>')
			->label($this->_language->get('name'), array(
				'for' => 'admin-user'
			))
			->text(array(
				'id' => 'admin-user',
				'name' => 'admin-user',
				'required' => 'required',
				'value' => $optionArray['adminUser']
			))
			->append('</li><li>')
			->label($this->_language->get('password'), array(
				'for' => 'admin_password'
			))
			->password(array(
				'id' => 'admin-password',
				'name' => 'admin-password',
				'required' => 'required',
				'value' => $optionArray['adminPassword']
			))
			->append('</li><li>')
			->label($this->_language->get('email'), array(
				'for' => 'admin-email'
			))
			->email(array(
				'id' => 'admin-email',
				'name' => 'admin-email',
				'required' => 'required',
				'value' => $optionArray['adminEmail']
			))
			->append('</li></ul></fieldset>')
			->hidden(array(
				'name' => 'db-salt',
				'value' => sha1(uniqid())
			))
			->token()
			->submit($this->_language->get('install'));

		/* collect output */

		$output .= $titleElement . $formElement;
		$output .= Hook::trigger('installFormEnd');
		return $output;
	}
}
