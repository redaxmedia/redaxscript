<?php
namespace Redaxscript\Admin\View;

use Redaxscript\Admin\Html\Form as AdminForm;
use Redaxscript\Db;
use Redaxscript\Html;
use Redaxscript\Module;

/**
 * children class to create the setting form
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 */

class SettingForm extends ViewAbstract implements ViewInterface
{
	/**
	 * render the view
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function render()
	{
		$output = Module\Hook::trigger('adminSettingFormStart');

		/* html elements */

		$titleElement = new Html\Element();
		$titleElement->init('h2',
		[
			'class' => 'rs-admin-title-content',
		]);
		$titleElement->text($this->_language->get('settings'));
		$formElement = new AdminForm($this->_registry, $this->_language);
		$formElement->init(
		[
			'form' =>
			[
				'action' => $this->_registry->get('parameterRoute') . 'admin/update/settings',
				'class' => 'rs-admin-js-accordion rs-admin-js-validate-form rs-admin-component-accordion rs-admin-form-default rs-admin-fn-clearfix'
			],
			'button' =>
			[
				'save' =>
				[
					'name' => 'update'
				]
			],
			'link' =>
			[
				'cancel' =>
				[
					'href' => $this->_registry->get('parameterRoute') . 'admin'
				]
			]
		]);

		/* create the form */

		$formElement

			/* general set */

			->append('<fieldset class="rs-admin-js-set-accordion rs-admin-js-set-active rs-admin-set-accordion rs-admin-set-active">')
			->append('<legend class="rs-admin-js-title-accordion rs-admin-js-title-active rs-admin-title-accordion rs-admin-title-active">' . $this->_language->get('general') . '</legend>')
			->append('<ul class="rs-admin-js-box-accordion rs-admin-js-box-active rs-admin-box-accordion rs-admin-box-accordion rs-admin-box-active"><li>')
			->label($this->_language->get('language'),
			[
				'for' => 'language'
			])
			->select(Helper\Option::getLanguageArray(),
			[
				'id' => 'language',
				'name' => 'language',
				'value' => Db::getSetting('language')
			])
			->append('</li><li>')
			->label($this->_language->get('template'),
			[
				'for' => 'template'
			])
			->select(Helper\Option::getTemplateArray(),
			[
				'id' => 'template',
				'name' => 'template',
				'value' => Db::getSetting('template')
			])
			->append('</li></ul></fieldset>')

			/* metadata set */

			->append('<fieldset class="rs-admin-js-set-accordion rs-admin-set-accordion">')
			->append('<legend class="rs-admin-js-title-accordion rs-admin-title-accordion">' . $this->_language->get('metadata') . '</legend>')
			->append('<ul class="rs-admin-js-box-accordion rs-admin-box-accordion rs-admin-box-accordion"><li>')
			->label($this->_language->get('title'),
			[
				'for' => 'title'
			])
			->text(
			[
				'id' => 'title',
				'name' => 'title',
				'value' => Db::getSetting('title')
			])
			->append('</li><li>')
			->label($this->_language->get('author'),
			[
				'for' => 'author'
			])
			->text(
			[
				'id' => 'author',
				'name' => 'author',
				'value' => Db::getSetting('author')
			])
			->append('</li><li>')
			->label($this->_language->get('copyright'),
			[
				'for' => 'copyright'
			])
			->text(
			[
				'id' => 'copyright',
				'name' => 'copyright',
				'value' => Db::getSetting('copyright')
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
				'value' => Db::getSetting('description')
			])
			->append('</li><li>')
			->label($this->_language->get('keywords'),
			[
				'for' => 'keywords'
			])
			->textarea(
			[
				'class' => 'rs-admin-js-auto-resize rs-admin-field-textarea rs-admin-field-small',
				'id' => 'keywords',
				'name' => 'keywords',
				'rows' => 1,
				'value' => Db::getSetting('keywords')
			])
			->append('</li><li>')
			->label($this->_language->get('robots'),
			[
				'for' => 'robots'
			])
			->select(Helper\Option::getRobotArray(),
			[
				'id' => 'robots',
				'name' => 'robots',
				'value' => filter_var(Db::getSetting('robots'), FILTER_VALIDATE_INT)
			])
			->append('</li></ul></fieldset>')

			/* contact set */

			->append('<fieldset class="rs-admin-js-set-accordion rs-admin-set-accordion">')
			->append('<legend class="rs-admin-js-title-accordion rs-admin-title-accordion">' . $this->_language->get('contact') . '</legend>')
			->append('<ul class="rs-admin-js-box-accordion rs-admin-box-accordion rs-admin-box-accordion"><li>')
			->label($this->_language->get('email'),
			[
				'for' => 'email'
			])
			->email(
			[
				'id' => 'email',
				'name' => 'email',
				'value' => Db::getSetting('email')
			])
			->append('</li><li>')
			->label($this->_language->get('subject'),
			[
				'for' => 'subject'
			])
			->text(
			[
				'id' => 'subject',
				'name' => 'subject',
				'value' => Db::getSetting('subject')
			])
			->append('</li><li>')
			->label($this->_language->get('notification'),
			[
				'for' => 'notification'
			])
			->select(Helper\Option::getToggleArray(),
			[
				'id' => 'notification',
				'name' => 'notification',
				'value' => intval(Db::getSetting('notification'))
			])
			->append('</li></ul></fieldset>')

			/* formatting set */

			->append('<fieldset class="rs-admin-js-set-accordion rs-admin-set-accordion">')
			->append('<legend class="rs-admin-js-title-accordion rs-admin-title-accordion">' . $this->_language->get('formatting') . '</legend>')
			->append('<ul class="rs-admin-js-box-accordion rs-admin-box-accordion rs-admin-box-accordion"><li>')
			->label($this->_language->get('charset'),
			[
				'for' => 'charset'
			])
			->text(
			[
				'id' => 'charset',
				'name' => 'charset',
				'value' => Db::getSetting('charset')
			])
			->append('</li><li>')
			->label($this->_language->get('divider'),
			[
				'for' => 'divider'
			])
			->text(
			[
				'id' => 'divider',
				'name' => 'divider',
				'value' => Db::getSetting('divider')
			])
			->append('</li><li>')
			->label($this->_language->get('time'),
			[
				'for' => 'time'
			])
			->select(Helper\Option::getTimeArray(),
			[
				'id' => 'time',
				'name' => 'time',
				'value' => Db::getSetting('time')
			])
			->append('</li><li>')
			->label($this->_language->get('date'),
			[
				'for' => 'date'
			])
			->select(Helper\Option::getDateArray(),
			[
				'id' => 'date',
				'name' => 'date',
				'value' => Db::getSetting('date')
			])
			->append('</li></ul></fieldset>')

			/* contents set */

			->append('<fieldset class="rs-admin-js-set-accordion rs-admin-set-accordion">')
			->append('<legend class="rs-admin-js-title-accordion rs-admin-title-accordion">' . $this->_language->get('contents') . '</legend>')
			->append('<ul class="rs-admin-js-box-accordion rs-admin-box-accordion rs-admin-box-accordion"><li>')
			->label($this->_language->get('homepage'),
			[
				'for' => 'homepage'
			])
			->select(Helper\Option::getContentArray('articles'),
			[
				'id' => 'homepage',
				'name' => 'homepage',
				'value' => Db::getSetting('homepage')
			])
			->append('</li><li>')
			->label($this->_language->get('limit'),
			[
				'for' => 'limit'
			])
			->number(
			[
				'id' => 'limit',
				'name' => 'limit',
				'value' => Db::getSetting('limit')
			])
			->append('</li><li>')
			->label($this->_language->get('order'),
			[
				'for' => 'order'
			])
			->select(Helper\Option::getOrderArray(),
			[
				'id' => 'order',
				'name' => 'order',
				'value' => Db::getSetting('order')
			])
			->append('</li><li>')
			->label($this->_language->get('pagination'),
			[
				'for' => 'pagination'
			])
			->select(Helper\Option::getToggleArray(),
			[
				'id' => 'pagination',
				'name' => 'pagination',
				'value' => intval(Db::getSetting('pagination'))
			])
			->append('</li></ul></fieldset>')

			/* users set */

			->append('<fieldset class="rs-admin-js-set-accordion rs-admin-set-accordion">')
			->append('<legend class="rs-admin-js-title-accordion rs-admin-title-accordion">' . $this->_language->get('users') . '</legend>')
			->append('<ul class="rs-admin-js-box-accordion rs-admin-box-accordion rs-admin-box-accordion"><li>')
			->label($this->_language->get('registration'),
			[
				'for' => 'registration'
			])
			->select(Helper\Option::getToggleArray(),
			[
				'id' => 'registration',
				'name' => 'registration',
				'value' => intval(Db::getSetting('registration'))
			])
			->append('</li><li>')
			->label($this->_language->get('verification'),
			[
				'for' => 'verification'
			])
			->select(Helper\Option::getToggleArray(),
			[
				'id' => 'verification',
				'name' => 'verification',
				'value' => intval(Db::getSetting('verification'))
			])
			->append('</li><li>')
			->label($this->_language->get('recovery'),
			[
				'for' => 'recovery'
			])
			->select(Helper\Option::getToggleArray(),
			[
				'id' => 'recovery',
				'name' => 'recovery',
				'value' => intval(Db::getSetting('recovery'))
			])
			->append('</li></ul></fieldset>')

			/* security set */

			->append('<fieldset class="rs-admin-js-set-accordion rs-admin-set-accordion">')
			->append('<legend class="rs-admin-js-title-accordion rs-admin-title-accordion">' . $this->_language->get('security') . '</legend>')
			->append('<ul class="rs-admin-js-box-accordion rs-admin-box-accordion rs-admin-box-accordion"><li>')
			->label($this->_language->get('moderation'),
			[
				'for' => 'moderation'
			])
			->select(Helper\Option::getToggleArray(),
			[
				'id' => 'moderation',
				'name' => 'moderation',
				'value' => intval(Db::getSetting('moderation'))
			])
			->append('</li><li>')
			->label($this->_language->get('captcha'),
			[
				'for' => 'captcha'
			])
			->select(Helper\Option::getCaptchaArray(),
			[
				'id' => 'captcha',
				'name' => 'captcha',
				'value' => intval(Db::getSetting('captcha'))
			])
			->append('</li></ul></fieldset>')
			->token()
			->cancel()
			->save();

		/* collect output */

		$output .= $titleElement . $formElement;
		$output .= Module\Hook::trigger('adminSettingFormEnd');
		return $output;
	}
}
