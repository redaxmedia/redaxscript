<?php
namespace Redaxscript\Admin\View;

use Redaxscript\Admin\Html\Form as AdminForm;
use Redaxscript\Admin\View\Helper;
use Redaxscript\Db;
use Redaxscript\Html;
use Redaxscript\Hook;

/**
 * children class to generate the setting form
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
		$output = Hook::trigger('adminSettingFormStart');

		/* html elements */

		$titleElement = new Html\Element();
		$titleElement->init('h2', array(
			'class' => 'rs-admin-title-content',
		));
		$titleElement->text($this->_language->get('settings'));
		$formElement = new AdminForm($this->_registry, $this->_language);
		$formElement->init(array(
			'form' => array(
				'action' => $this->_registry->get('parameterRoute') . 'admin/update/settings',
				'class' => 'rs-admin-js-validate-form rs-admin-js-accordion rs-admin-form-default rs-admin-fn-clearfix'
			),
			'button' => array(
				'save' => array(
					'name' => 'update'
				)
			),
			'link' => array(
				'cancel' => array(
					'href' => $this->_registry->get('parameterRoute') . 'admin'
				)
			)
		));

		/* create the form */

		$formElement

			/* general set */

			->append('<fieldset class="rs-admin-js-set-accordion rs-admin-js-set-active rs-admin-set-accordion rs-admin-set-active">')
			->append('<legend class="rs-admin-js-title-accordion rs-admin-js-title-active rs-admin-title-accordion rs-admin-title-active">' . $this->_language->get('general') . '</legend>')
			->append('<ul class="rs-admin-js-box-accordion rs-admin-js-box-active rs-admin-box-accordion rs-admin-box-accordion rs-admin-box-active"><li>')
			->label($this->_language->get('language'), array(
				'for' => 'language'
			))
			->select(Helper\Option::getLanguageArray(), array(
				'id' => 'language',
				'name' => 'language',
				'value' => Db::getSetting('language')
			))
			->append('</li><li>')
			->label($this->_language->get('template'), array(
				'for' => 'template'
			))
			->select(Helper\Option::getTemplateArray(), array(
				'id' => 'template',
				'name' => 'template',
				'value' => Db::getSetting('template')
			))
			->append('</li></ul></fieldset>')

			/* metadata set */

			->append('<fieldset class="rs-admin-js-set-accordion rs-admin-set-accordion">')
			->append('<legend class="rs-admin-js-title-accordion rs-admin-title-accordion">' . $this->_language->get('metadata') . '</legend>')
			->append('<ul class="rs-admin-js-box-accordion rs-admin-box-accordion rs-admin-box-accordion"><li>')
			->label($this->_language->get('title'), array(
				'for' => 'title'
			))
			->text(array(
				'id' => 'title',
				'name' => 'title',
				'value' => Db::getSetting('title')
			))
			->append('</li><li>')
			->label($this->_language->get('author'), array(
				'for' => 'author'
			))
			->text(array(
				'id' => 'author',
				'name' => 'author',
				'value' => Db::getSetting('author')
			))
			->append('</li><li>')
			->label($this->_language->get('copyright'), array(
				'for' => 'copyright'
			))
			->text(array(
				'id' => 'copyright',
				'name' => 'copyright',
				'value' => Db::getSetting('copyright')
			))
			->append('</li><li>')
			->label($this->_language->get('description'), array(
				'for' => 'description'
			))
			->textarea(array(
				'class' => 'rs-admin-js-auto-resize rs-admin-field-textarea rs-admin-field-small',
				'id' => 'description',
				'name' => 'description',
				'value' => Db::getSetting('description')
			))
			->append('</li><li>')
			->label($this->_language->get('keywords'), array(
				'for' => 'keywords'
			))
			->textarea(array(
				'class' => 'rs-admin-js-auto-resize rs-admin-field-textarea rs-admin-field-small',
				'id' => 'keywords',
				'name' => 'keywords',
				'value' => Db::getSetting('keywords')
			))
			->append('</li><li>')
			->label($this->_language->get('robots'), array(
				'for' => 'robots'
			))
			->select(Helper\Option::getRobotArray(), array(
				'id' => 'robots',
				'name' => 'robots',
				'value' => Db::getSetting('robots')
			))
			->append('</li></ul></fieldset>')

			/* contact set */

			->append('<fieldset class="rs-admin-js-set-accordion rs-admin-set-accordion">')
			->append('<legend class="rs-admin-js-title-accordion rs-admin-title-accordion">' . $this->_language->get('contact') . '</legend>')
			->append('<ul class="rs-admin-js-box-accordion rs-admin-box-accordion rs-admin-box-accordion"><li>')
			->label($this->_language->get('email'), array(
				'for' => 'email'
			))
			->email(array(
				'id' => 'email',
				'name' => 'email',
				'value' => Db::getSetting('email')
			))
			->append('</li><li>')
			->label($this->_language->get('subject'), array(
				'for' => 'subject'
			))
			->text(array(
				'id' => 'subject',
				'name' => 'subject',
				'value' => Db::getSetting('subject')
			))
			->append('</li><li>')
			->label($this->_language->get('notification'), array(
				'for' => 'notification'
			))
			->select(Helper\Option::getToggleArray(), array(
				'id' => 'notification',
				'name' => 'notification',
				'value' => Db::getSetting('notification')
			))
			->append('</li></ul></fieldset>')

			/* formatting set */

			->append('<fieldset class="rs-admin-js-set-accordion rs-admin-set-accordion">')
			->append('<legend class="rs-admin-js-title-accordion rs-admin-title-accordion">' . $this->_language->get('formatting') . '</legend>')
			->append('<ul class="rs-admin-js-box-accordion rs-admin-box-accordion rs-admin-box-accordion"><li>')
			->label($this->_language->get('charset'), array(
				'for' => 'charset'
			))
			->text(array(
				'id' => 'charset',
				'name' => 'charset',
				'value' => Db::getSetting('charset')
			))
			->append('</li><li>')
			->label($this->_language->get('divider'), array(
				'for' => 'divider'
			))
			->text(array(
				'id' => 'divider',
				'name' => 'divider',
				'value' => Db::getSetting('divider')
			))
			->append('</li><li>')
			->label($this->_language->get('time'), array(
				'for' => 'time'
			))
			->select(Helper\Option::getTimeArray(), array(
				'id' => 'time',
				'name' => 'time',
				'value' => Db::getSetting('time')
			))
			->append('</li><li>')
			->label($this->_language->get('date'), array(
				'for' => 'date'
			))
			->select(Helper\Option::getDateArray(), array(
				'id' => 'date',
				'name' => 'date',
				'value' => Db::getSetting('date')
			))
			->append('</li></ul></fieldset>')

			/* contents set */

			->append('<fieldset class="rs-admin-js-set-accordion rs-admin-set-accordion">')
			->append('<legend class="rs-admin-js-title-accordion rs-admin-title-accordion">' . $this->_language->get('contents') . '</legend>')
			->append('<ul class="rs-admin-js-box-accordion rs-admin-box-accordion rs-admin-box-accordion"><li>')
			->label($this->_language->get('homepage'), array(
				'for' => 'homepage'
			))
			->select(Helper\Option::getContentArray('articles'), array(
				'id' => 'homepage',
				'name' => 'homepage',
				'value' => Db::getSetting('homepage')
			))
			->append('</li><li>')
			->label($this->_language->get('limit'), array(
				'for' => 'limit'
			))
			->number(array(
				'id' => 'limit',
				'name' => 'limit',
				'value' => Db::getSetting('limit')
			))
			->append('</li><li>')
			->label($this->_language->get('order'), array(
				'for' => 'order'
			))
			->select(Helper\Option::getOrderArray(), array(
				'id' => 'order',
				'name' => 'order',
				'value' => Db::getSetting('order')
			))
			->append('</li><li>')
			->label($this->_language->get('pagination'), array(
				'for' => 'pagination'
			))
			->select(Helper\Option::getToggleArray(), array(
				'id' => 'pagination',
				'name' => 'pagination',
				'value' => Db::getSetting('pagination')
			))
			->append('</li></ul></fieldset>')

			/* users set */

			->append('<fieldset class="rs-admin-js-set-accordion rs-admin-set-accordion">')
			->append('<legend class="rs-admin-js-title-accordion rs-admin-title-accordion">' . $this->_language->get('users') . '</legend>')
			->append('<ul class="rs-admin-js-box-accordion rs-admin-box-accordion rs-admin-box-accordion"><li>')
			->label($this->_language->get('registration'), array(
				'for' => 'registration'
			))
			->select(Helper\Option::getToggleArray(), array(
				'id' => 'registration',
				'name' => 'registration',
				'value' => Db::getSetting('registration')
			))
			->append('</li><li>')
			->label($this->_language->get('verification'), array(
				'for' => 'verification'
			))
			->select(Helper\Option::getToggleArray(), array(
				'id' => 'verification',
				'name' => 'verification',
				'value' => Db::getSetting('verification')
			))
			->append('</li><li>')
			->label($this->_language->get('recovery'), array(
				'for' => 'recovery'
			))
			->select(Helper\Option::getToggleArray(), array(
				'id' => 'recovery',
				'name' => 'recovery',
				'value' => Db::getSetting('recovery')
			))
			->append('</li></ul></fieldset>')

			/* security set */

			->append('<fieldset class="rs-admin-js-set-accordion rs-admin-set-accordion">')
			->append('<legend class="rs-admin-js-title-accordion rs-admin-title-accordion">' . $this->_language->get('security') . '</legend>')
			->append('<ul class="rs-admin-js-box-accordion rs-admin-box-accordion rs-admin-box-accordion"><li>')
			->label($this->_language->get('moderation'), array(
				'for' => 'moderation'
			))
			->select(Helper\Option::getToggleArray(), array(
				'id' => 'moderation',
				'name' => 'moderation',
				'value' => Db::getSetting('moderation')
			))
			->append('</li><li>')
			->label($this->_language->get('captcha'), array(
				'for' => 'captcha'
			))
			->select(Helper\Option::getCaptchaArray(), array(
				'id' => 'captcha',
				'name' => 'captcha',
				'value' => Db::getSetting('captcha')
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
