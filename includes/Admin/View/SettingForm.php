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
 * children class to generate the setting form
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 */

class SettingForm implements ViewInterface
{
	/**
	 * stringify the view
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function __toString()
	{
		return $this->render();
	}

	/**
	 * render the view
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function render()
	{
		$output = Hook::trigger('adminSettingFormStart');

		/* html elements */

		$titleElement = new Html\Element();
		$titleElement->init('h2', array(
			'class' => 'rs-admin-title-content',
		));
		$titleElement->text(Language::get('settings'));
		$formElement = new AdminForm(Registry::getInstance(), Language::getInstance());
		$formElement->init(array(
			'form' => array(
				'action' => Registry::get('rewriteRoute') . 'admin/update/settings',
				'class' => 'rs-js-validate-form rs-js-accordion rs-admin-form-default'
			),
			'button' => array(
				'save' => array(
					'name' => 'update'
				)
			),
			'link' => array(
				'cancel' => array(
					'href' => Registry::get('rewriteRoute') . 'admin'
				)
			)
		));

		/* create the form */

		$formElement

			/* general set */

			->append('<fieldset class="rs-js-set-accordion rs-js-set-active rs-admin-set-accordion rs-set-active">')
			->append('<legend class="rs-js-title-accordion rs-js-title-active rs-admin-title-accordion rs-admin-title-active">' . Language::get('general') . '</legend>')
			->append('<ul class="rs-js-box-accordion rs-js-box-active rs-box-accordion rs-admin-box-accordion rs-box-active"><li>')
			->label(Language::get('language'), array(
				'for' => 'language'
			))
			->select(Helper\Option::getLanguageArray(), array(
				'id' => 'language',
				'name' => 'language',
				'value' => Db::getSettings('language')
			))
			->append('</li><li>')
			->label(Language::get('template'), array(
				'for' => 'template'
			))
			->select(Helper\Option::getTemplateArray(), array(
				'id' => 'template',
				'name' => 'template',
				'value' => Db::getSettings('template')
			))
			->append('</li></ul></fieldset>')

			/* metadata set */

			->append('<fieldset class="rs-js-set-accordion rs-admin-set-accordion">')
			->append('<legend class="rs-js-title-accordion rs-admin-title-accordion">' . Language::get('metadata') . '</legend>')
			->append('<ul class="rs-js-box-accordion rs-box-accordion rs-admin-box-accordion"><li>')
			->label(Language::get('title'), array(
				'for' => 'title'
			))
			->text(array(
				'id' => 'title',
				'name' => 'title',
				'value' => Db::getSettings('title')
			))
			->append('</li><li>')
			->label(Language::get('author'), array(
				'for' => 'author'
			))
			->text(array(
				'id' => 'author',
				'name' => 'author',
				'value' => Db::getSettings('author')
			))
			->append('</li><li>')
			->label(Language::get('copyright'), array(
				'for' => 'copyright'
			))
			->text(array(
				'id' => 'copyright',
				'name' => 'copyright',
				'value' => Db::getSettings('copyright')
			))
			->append('</li><li>')
			->label(Language::get('description'), array(
				'for' => 'description'
			))
			->textarea(array(
				'class' => 'rs-js-auto-resize rs-admin-field-textarea rs-field-small',
				'id' => 'description',
				'name' => 'description',
				'value' => Db::getSettings('description')
			))
			->append('</li><li>')
			->label(Language::get('keywords'), array(
				'for' => 'keywords'
			))
			->textarea(array(
				'class' => 'rs-js-auto-resize rs-admin-field-textarea rs-field-small',
				'id' => 'keywords',
				'name' => 'keywords',
				'value' => Db::getSettings('keywords')
			))
			->append('</li><li>')
			->label(Language::get('robots'), array(
				'for' => 'robots'
			))
			->select(Helper\Option::getRobotArray(), array(
				'id' => 'robots',
				'name' => 'robots',
				'value' => Db::getSettings('robots')
			))
			->append('</li></ul></fieldset>')

			/* contact set */

			->append('<fieldset class="rs-js-set-accordion rs-admin-set-accordion">')
			->append('<legend class="rs-js-title-accordion rs-admin-title-accordion">' . Language::get('contact') . '</legend>')
			->append('<ul class="rs-js-box-accordion rs-box-accordion rs-admin-box-accordion"><li>')
			->label(Language::get('email'), array(
				'for' => 'email'
			))
			->email(array(
				'id' => 'email',
				'name' => 'email',
				'value' => Db::getSettings('email')
			))
			->append('</li><li>')
			->label(Language::get('subject'), array(
				'for' => 'subject'
			))
			->text(array(
				'id' => 'subject',
				'name' => 'subject',
				'value' => Db::getSettings('subject')
			))
			->append('</li><li>')
			->label(Language::get('notification'), array(
				'for' => 'notification'
			))
			->select(Helper\Option::getToggleArray(), array(
				'id' => 'notification',
				'name' => 'notification',
				'value' => Db::getSettings('notification')
			))
			->append('</li></ul></fieldset>')

			/* formatting set */

			->append('<fieldset class="rs-js-set-accordion rs-admin-set-accordion">')
			->append('<legend class="rs-js-title-accordion rs-admin-title-accordion">' . Language::get('formatting') . '</legend>')
			->append('<ul class="rs-js-box-accordion rs-box-accordion rs-admin-box-accordion"><li>')
			->label(Language::get('charset'), array(
				'for' => 'charset'
			))
			->text(array(
				'id' => 'charset',
				'name' => 'charset',
				'value' => Db::getSettings('charset')
			))
			->append('</li><li>')
			->label(Language::get('divider'), array(
				'for' => 'divider'
			))
			->text(array(
				'id' => 'divider',
				'name' => 'divider',
				'value' => Db::getSettings('divider')
			))
			->append('</li><li>')
			->label(Language::get('time'), array(
				'for' => 'time'
			))
			->select(Helper\Option::getTimeArray(), array(
				'id' => 'time',
				'name' => 'time',
				'value' => Db::getSettings('time')
			))
			->append('</li><li>')
			->label(Language::get('date'), array(
				'for' => 'date'
			))
			->select(Helper\Option::getDateArray(), array(
				'id' => 'date',
				'name' => 'date',
				'value' => Db::getSettings('date')
			))
			->append('</li></ul></fieldset>')

			/* contents set */

			->append('<fieldset class="rs-js-set-accordion rs-admin-set-accordion">')
			->append('<legend class="rs-js-title-accordion rs-admin-title-accordion">' . Language::get('contents') . '</legend>')
			->append('<ul class="rs-js-box-accordion rs-box-accordion rs-admin-box-accordion"><li>')
			->label(Language::get('homepage'), array(
				'for' => 'homepage'
			))
			->select(Helper\Option::getContentArray('articles'), array(
				'id' => 'homepage',
				'name' => 'homepage',
				'value' => Db::getSettings('homepage')
			))
			->append('</li><li>')
			->label(Language::get('limit'), array(
				'for' => 'limit'
			))
			->number(array(
				'id' => 'limit',
				'name' => 'limit',
				'value' => Db::getSettings('limit')
			))
			->append('</li><li>')
			->label(Language::get('order'), array(
				'for' => 'order'
			))
			->select(Helper\Option::getOrderArray(), array(
				'id' => 'order',
				'name' => 'order',
				'value' => Db::getSettings('order')
			))
			->append('</li><li>')
			->label(Language::get('pagination'), array(
				'for' => 'pagination'
			))
			->select(Helper\Option::getToggleArray(), array(
				'id' => 'pagination',
				'name' => 'pagination',
				'value' => Db::getSettings('pagination')
			))
			->append('</li></ul></fieldset>')

			/* users set */

			->append('<fieldset class="rs-js-set-accordion rs-admin-set-accordion">')
			->append('<legend class="rs-js-title-accordion rs-admin-title-accordion">' . Language::get('users') . '</legend>')
			->append('<ul class="rs-js-box-accordion rs-box-accordion rs-admin-box-accordion"><li>')
			->label(Language::get('registration'), array(
				'for' => 'registration'
			))
			->select(Helper\Option::getToggleArray(), array(
				'id' => 'registration',
				'name' => 'registration',
				'value' => Db::getSettings('registration')
			))
			->append('</li><li>')
			->label(Language::get('verification'), array(
				'for' => 'verification'
			))
			->select(Helper\Option::getToggleArray(), array(
				'id' => 'verification',
				'name' => 'verification',
				'value' => Db::getSettings('verification')
			))
			->append('</li><li>')
			->label(Language::get('recovery'), array(
				'for' => 'recovery'
			))
			->select(Helper\Option::getToggleArray(), array(
				'id' => 'recovery',
				'name' => 'recovery',
				'value' => Db::getSettings('recovery')
			))
			->append('</li></ul></fieldset>')

			/* security set */

			->append('<fieldset class="rs-js-set-accordion rs-admin-set-accordion">')
			->append('<legend class="rs-js-title-accordion rs-admin-title-accordion">' . Language::get('security') . '</legend>')
			->append('<ul class="rs-js-box-accordion rs-box-accordion rs-admin-box-accordion"><li>')
			->label(Language::get('moderation'), array(
				'for' => 'moderation'
			))
			->select(Helper\Option::getToggleArray(), array(
				'id' => 'moderation',
				'name' => 'moderation',
				'value' => Db::getSettings('moderation')
			))
			->append('</li><li>')
			->label(Language::get('captcha'), array(
				'for' => 'captcha'
			))
			->select(Helper\Option::getCaptchaArray(), array(
				'id' => 'captcha',
				'name' => 'captcha',
				'value' => Db::getSettings('captcha')
			))
			->append('</li></ul></fieldset>')
			->token()
			->cancel()
			->save();

		/* collect output */

		$output .= $titleElement . $formElement;
		$output .= Hook::trigger('adminSettingFormEnd');
		return $output;
	}
}
