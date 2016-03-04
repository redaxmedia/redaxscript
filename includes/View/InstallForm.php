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
		$titleElement->init('h2', array(
			'class' => 'rs-title-content',
		));
		$titleElement->text(Language::get('installation'));
		$formElement = new Html\Form(Registry::getInstance(), Language::getInstance());
		$formElement->init(array(
			'form' => array(
				'class' => 'rs-js-validate-form rs-js-accordion rs-form-default rs-form-install'
			),
			'button' => array(
				'submit' => array(
					'class' => 'rs-js-submit rs-button-default rs-button-large',
					'name' => get_class()
				)
			)
		));

		/* create the form */

		$formElement

			/* database set */

			->append('<fieldset class="rs-js-set-accordion rs-js-set-active rs-set-accordion rs-set-active">')
			->append('<legend class="rs-js-title-accordion rs-js-title-active rs-title-accordion rs-title-active">' . Language::get('database_setup') . '</legend>')
			->append('<ul class="rs-js-box-accordion rs-js-box-active rs-box-accordion rs-box-accordion-default rs-box-active"><li>')
			->label(Language::get('type'), array(
				'for' => 'db_type'
			))
			->select(Helper\Option::getDatabaseArray(), array(
				'id' => 'db_type',
				'name' => 'db_type',
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
				'id' => 'db_host',
				'name' => 'db_host',
				'required' => 'required',
				'value' => $options['dbHost']
			))
			->append('</li><li>')
			->label(Language::get('name'), array(
				'for' => 'db_name'
			))
			->text(array(
				'id' => 'db_name',
				'name' => 'db_name',
				'required' => 'required',
				'value' => $options['dbName']
			))
			->append('</li><li>')
			->label(Language::get('user'), array(
				'for' => 'db_user'
			))
			->text(array(
				'id' => 'db_user',
				'name' => 'db_user',
				'required' => 'required',
				'value' => $options['dbUser']
			))
			->append('</li><li>')
			->label(Language::get('password'), array(
				'for' => 'db_password'
			))
			->password(array(
				'id' => 'db_password',
				'name' => 'db_password',
				'value' => $options['dbPassword']
			))
			->append('</li><li>')
			->label(Language::get('prefix'), array(
				'for' => 'db_prefix'
			))
			->password(array(
				'id' => 'db_prefix',
				'name' => 'db_prefix',
				'value' => $options['dbPrefix']
			))
			->append('</li></ul></fieldset>')

			/* account set */

			->append('<fieldset class="rs-js-set-accordion rs-set-accordion">')
			->append('<legend class="rs-js-title-accordion rs-title-accordion">' . Language::get('account_create') . '</legend>')
			->append('<ul class="rs-js-box-accordion rs-box-accordion rs-box-accordion-default"><li>')
			->label(Language::get('name'), array(
				'for' => 'name'
			))
			->text(array(
				'id' => 'admin_name',
				'name' => 'admin_name',
				'required' => 'required',
				'value' => $options['adminName']
			))
			->append('</li><li>')
			->label(Language::get('name'), array(
				'for' => 'admin_user'
			))
			->text(array(
				'id' => 'admin_user',
				'name' => 'admin_user',
				'required' => 'required',
				'value' => $options['adminUser']
			))
			->append('</li><li>')
			->label(Language::get('password'), array(
				'for' => 'admin_password'
			))
			->password(array(
				'id' => 'admin_password',
				'name' => 'admin_password',
				'required' => 'required',
				'value' => $options['adminPassword']
			))
			->append('</li><li>')
			->label(Language::get('email'), array(
				'for' => 'admin_email'
			))
			->email(array(
				'id' => 'admin_email',
				'name' => 'admin_email',
				'required' => 'required',
				'value' => $options['adminEmail']
			))
			->append('</li></ul></fieldset>')
			->hidden(array(
				'name' => 'db_salt',
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
