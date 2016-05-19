<?php
namespace Redaxscript\View;

use Redaxscript\Html;
use Redaxscript\Hook;
use Redaxscript\Language;
use Redaxscript\Registry;

/**
 * children class to generate the install form
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class InstallForm implements ViewInterface
{
	/**
	 * render the view
	 *
	 * @param array $options options of the installation
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function render($options = null)
	{
		$output = Hook::trigger('installFormStart');

		/* html elements */

		$titleElement = new Html\Element();
		$titleElement
			->init('h2', array(
				'class' => 'rs-title-content'
			))->text(Language::get('installation'));
		$formElement = new Html\Form(Registry::getInstance(), Language::getInstance());
		$formElement->init(array(
			'form' => array(
				'class' => 'rs-js-install rs-js-accordion rs-js-validate-form rs-form-default rs-form-install'
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
			->append('<legend class="rs-js-title-accordion rs-js-title-active rs-title-accordion rs-title-active">' . Language::get('database_setup') . '</legend>')
			->append('<ul class="rs-js-box-accordion rs-js-box-active rs-box-accordion rs-box-active"><li>')
			->label(Language::get('type'), array(
				'for' => 'db-type'
			))
			->select(Helper\Option::getDatabaseArray(), array(
				'id' => 'db-type',
				'name' => 'db-type',
				'value' => $options['dbType']
			))
			->append('</li><li>')
			->label(Language::get('host'), array(
				'for' => 'db_host'
			))
			->text(array(
				'data-sqlite' => uniqid() . '.sqlite',
				'data-mysql' => 'localhost',
				'data-pgsql' => 'localhost',
				'id' => 'db-host',
				'name' => 'db-host',
				'required' => 'required',
				'value' => $options['dbHost']
			))
			->append('</li><li>')
			->label(Language::get('name'), array(
				'for' => 'db_name'
			))
			->text(array(
				'id' => 'db-name',
				'name' => 'db-name',
				'required' => 'required',
				'value' => $options['dbName']
			))
			->append('</li><li>')
			->label(Language::get('user'), array(
				'for' => 'db_user'
			))
			->text(array(
				'id' => 'db-user',
				'name' => 'db-user',
				'required' => 'required',
				'value' => $options['dbUser']
			))
			->append('</li><li>')
			->label(Language::get('password'), array(
				'for' => 'db-password'
			))
			->password(array(
				'id' => 'db-password',
				'name' => 'db-password',
				'value' => $options['dbPassword']
			))
			->append('</li><li>')
			->label(Language::get('prefix'), array(
				'for' => 'db-prefix'
			))
			->text(array(
				'id' => 'db-prefix',
				'name' => 'db-prefix',
				'value' => $options['dbPrefix']
			))
			->append('</li></ul></fieldset>')

			/* account set */

			->append('<fieldset class="rs-js-set-accordion rs-set-accordion">')
			->append('<legend class="rs-js-title-accordion rs-title-accordion">' . Language::get('account_create') . '</legend>')
			->append('<ul class="rs-js-box-accordion rs-box-accordion"><li>')
			->label(Language::get('name'), array(
				'for' => 'name'
			))
			->text(array(
				'id' => 'admin-name',
				'name' => 'admin-name',
				'required' => 'required',
				'value' => $options['adminName']
			))
			->append('</li><li>')
			->label(Language::get('name'), array(
				'for' => 'admin-user'
			))
			->text(array(
				'id' => 'admin-user',
				'name' => 'admin-user',
				'required' => 'required',
				'value' => $options['adminUser']
			))
			->append('</li><li>')
			->label(Language::get('password'), array(
				'for' => 'admin_password'
			))
			->password(array(
				'id' => 'admin-password',
				'name' => 'admin-password',
				'required' => 'required',
				'value' => $options['adminPassword']
			))
			->append('</li><li>')
			->label(Language::get('email'), array(
				'for' => 'admin-email'
			))
			->email(array(
				'id' => 'admin-email',
				'name' => 'admin-email',
				'required' => 'required',
				'value' => $options['adminEmail']
			))
			->append('</li></ul></fieldset>')
			->hidden(array(
				'name' => 'db-salt',
				'value' => sha1(uniqid())
			))
			->token()
			->submit(Language::get('install'));

		/* collect output */

		$output .= $titleElement . $formElement;
		$output .= Hook::trigger('installFormEnd');
		return $output;
	}
}
