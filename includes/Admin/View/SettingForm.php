<?php
namespace Redaxscript\Admin\View;

use Redaxscript\Admin;
use Redaxscript\Html;
use Redaxscript\Module;
use Redaxscript\Validator;

/**
 * children class to create the setting form
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class SettingForm extends ViewAbstract
{
	/**
	 * render the view
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function render() : string
	{
		$output = Module\Hook::trigger('adminSettingFormStart');
		$settingModel = new Admin\Model\Setting();
		$helperOption = new Helper\Option($this->_language);
		$nameValidator = new Validator\Name();

		/* html element */

		$titleElement = new Html\Element();
		$titleElement
			->init('h2',
			[
				'class' => 'rs-admin-title-content',
			])
			->text($this->_language->get('settings'));
		$formElement = new Admin\Html\Form($this->_registry, $this->_language);
		$formElement->init(
		[
			'form' =>
			[
				'class' => 'rs-admin-js-validate rs-admin-component-accordion rs-admin-form-default rs-admin-form-setting'
			],
			'button' =>
			[
				'save' =>
				[
					'name' => self::class
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

			/* general */

			->radio(
			[
				'id' => self::class . '\General',
				'class' => 'rs-admin-fn-status-accordion',
				'name' => self::class . '\Accordion',
				'checked' => 'checked'
			])
			->label($this->_language->get('general'),
			[
				'class' => 'rs-admin-fn-toggle-accordion rs-admin-label-accordion',
				'for' => self::class . '\General'
			])
			->append('<ul class="rs-admin-fn-content-accordion rs-admin-box-accordion"><li>')
			->label($this->_language->get('language'),
			[
				'for' => 'language'
			])
			->select($helperOption->getLanguageArray(),
			[
				$settingModel->get('language')
			],
			[
				'id' => 'language',
				'name' => 'language'
			])
			->append('</li><li>')
			->label($this->_language->get('template'),
			[
				'for' => 'template'
			])
			->select($helperOption->getTemplateArray(),
			[
				$settingModel->get('template')
			],
			[
				'id' => 'template',
				'name' => 'template'
			])
			->append('</li></ul>')

			/* metadata */

			->radio(
			[
				'id' => self::class . '\Metadata',
				'class' => 'rs-admin-fn-status-accordion',
				'name' => self::class . '\Accordion'
			])
			->label($this->_language->get('metadata'),
			[
				'class' => 'rs-admin-fn-toggle-accordion rs-admin-label-accordion',
				'for' => self::class . '\Metadata'
			])
			->append('<ul class="rs-admin-fn-content-accordion rs-admin-box-accordion"><li>')
			->label($this->_language->get('title'),
			[
				'for' => 'title'
			])
			->text(
			[
				'id' => 'title',
				'name' => 'title',
				'value' => $settingModel->get('title')
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
				'pattern' => $nameValidator->getPattern(),
				'value' => $settingModel->get('author')
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
				'value' => $settingModel->get('copyright')
			])
			->append('</li><li>')
			->label($this->_language->get('description'),
			[
				'for' => 'description'
			])
			->textarea(
			[
				'class' => 'rs-admin-js-resize rs-admin-field-textarea rs-admin-field-small',
				'id' => 'description',
				'name' => 'description',
				'rows' => 1,
				'value' => $settingModel->get('description')
			])
			->append('</li><li>')
			->label($this->_language->get('keywords'),
			[
				'for' => 'keywords'
			])
			->textarea(
			[
				'class' => 'rs-admin-js-resize rs-admin-field-textarea rs-admin-field-small',
				'id' => 'keywords',
				'name' => 'keywords',
				'rows' => 1,
				'value' => $settingModel->get('keywords')
			])
			->append('</li><li>')
			->label($this->_language->get('robots'),
			[
				'for' => 'robots'
			])
			->select($helperOption->getRobotArray(),
			[
				$settingModel->get('robots')
			],
			[
				'id' => 'robots',
				'name' => 'robots'
			])
			->append('</li></ul>')

			/* contact */

			->radio(
			[
				'id' => self::class . '\Contact',
				'class' => 'rs-admin-fn-status-accordion',
				'name' => self::class . '\Accordion'
			])
			->label($this->_language->get('contact'),
			[
				'class' => 'rs-admin-fn-toggle-accordion rs-admin-label-accordion',
				'for' => self::class . '\Contact'
			])
			->append('<ul class="rs-admin-fn-content-accordion rs-admin-box-accordion"><li>')
			->label($this->_language->get('email'),
			[
				'for' => 'email'
			])
			->email(
			[
				'id' => 'email',
				'name' => 'email',
				'value' => $settingModel->get('email')
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
				'value' => $settingModel->get('subject')
			])
			->append('</li><li>')
			->label($this->_language->get('notification'),
			[
				'for' => 'notification'
			])
			->checkbox($settingModel->get('notification') ?
			[
				'id' => 'notification',
				'class' => 'rs-admin-fn-status-switch',
				'name' => 'notification',
				'checked' => 'checked'
			] :
			[
				'id' => 'notification',
				'class' => 'rs-admin-fn-status-switch',
				'name' => 'notification'
			])
			->label(null,
			[
				'class' => 'rs-admin-label-switch',
				'for' => 'notification',
				'data-on' => $this->_language->get('enable'),
				'data-off' => $this->_language->get('disable')
			])
			->append('</li></ul>')

			/* formatting */

			->radio(
			[
				'id' => self::class . '\Formatting',
				'class' => 'rs-admin-fn-status-accordion',
				'name' => self::class . '\Accordion'
			])
			->label($this->_language->get('formatting'),
			[
				'class' => 'rs-admin-fn-toggle-accordion rs-admin-label-accordion',
				'for' => self::class . '\Formatting'
			])
			->append('<ul class="rs-admin-fn-content-accordion rs-admin-box-accordion"><li>')
			->label($this->_language->get('charset'),
			[
				'for' => 'charset'
			])
			->text(
			[
				'id' => 'charset',
				'name' => 'charset',
				'value' => $settingModel->get('charset')
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
				'value' => $settingModel->get('divider')
			])
			->append('</li><li>')
			->label($this->_language->get('zone'),
			[
				'for' => 'zone'
			])
			->select($helperOption->getZoneArray(),
			[
				$settingModel->get('zone')
			],
			[
				'id' => 'zone',
				'name' => 'zone'
			])
			->append('</li><li>')
			->label($this->_language->get('time'),
			[
				'for' => 'time'
			])
			->select($helperOption->getTimeArray(),
			[
				$settingModel->get('time')
			],
			[
				'id' => 'time',
				'name' => 'time'
			])
			->append('</li><li>')
			->label($this->_language->get('date'),
			[
				'for' => 'date'
			])
			->select($helperOption->getDateArray(),
			[
				$settingModel->get('date')
			],
			[
				'id' => 'date',
				'name' => 'date'
			])
			->append('</li></ul>')

			/* contents */

			->radio(
			[
				'id' => self::class . '\Contents',
				'class' => 'rs-admin-fn-status-accordion',
				'name' => self::class . '\Accordion'
			])
			->label($this->_language->get('contents'),
			[
				'class' => 'rs-admin-fn-toggle-accordion rs-admin-label-accordion',
				'for' => self::class . '\Contents'
			])
			->append('<ul class="rs-admin-fn-content-accordion rs-admin-box-accordion"><li>')
			->label($this->_language->get('homepage'),
			[
				'for' => 'homepage'
			])
			->select($helperOption->getArticleArray(),
			[
				$settingModel->get('homepage')
			],
			[
				'id' => 'homepage',
				'name' => 'homepage'
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
				'value' => $settingModel->get('limit')
			])
			->append('</li><li>')
			->label($this->_language->get('order'),
			[
				'for' => 'order'
			])
			->select($helperOption->getOrderArray(),
			[
				$settingModel->get('order')
			],
			[
				'id' => 'order',
				'name' => 'order'
			])
			->append('</li><li>')
			->label($this->_language->get('pagination'),
			[
				'for' => 'pagination'
			])
			->checkbox($settingModel->get('pagination') ?
			[
				'id' => 'pagination',
				'class' => 'rs-admin-fn-status-switch',
				'name' => 'pagination',
				'checked' => 'checked'
			] :
			[
				'id' => 'pagination',
				'class' => 'rs-admin-fn-status-switch',
				'name' => 'pagination'
			])
			->label(null,
			[
				'class' => 'rs-admin-label-switch',
				'for' => 'pagination',
				'data-on' => $this->_language->get('enable'),
				'data-off' => $this->_language->get('disable')
			])
			->append('</li></ul>')

			/* users */

			->radio(
			[
				'id' => self::class . '\Users',
				'class' => 'rs-admin-fn-status-accordion',
				'name' => self::class . '\Accordion'
			])
			->label($this->_language->get('users'),
			[
				'class' => 'rs-admin-fn-toggle-accordion rs-admin-label-accordion',
				'for' => self::class . '\Users'
			])
			->append('<ul class="rs-admin-fn-content-accordion rs-admin-box-accordion"><li>')
			->label($this->_language->get('registration'),
			[
				'for' => 'registration'
			])
			->checkbox($settingModel->get('registration') ?
			[
				'id' => 'registration',
				'class' => 'rs-admin-fn-status-switch',
				'name' => 'registration',
				'checked' => 'checked'
			] :
			[
				'id' => 'registration',
				'class' => 'rs-admin-fn-status-switch',
				'name' => 'registration'
			])
			->label(null,
			[
				'class' => 'rs-admin-label-switch',
				'for' => 'registration',
				'data-on' => $this->_language->get('enable'),
				'data-off' => $this->_language->get('disable')
			])
			->append('</li><li>')
			->label($this->_language->get('verification'),
			[
				'for' => 'verification'
			])
			->checkbox($settingModel->get('verification') ?
			[
				'id' => 'verification',
				'class' => 'rs-admin-fn-status-switch',
				'name' => 'verification',
				'checked' => 'checked'
			] :
			[
				'id' => 'verification',
				'class' => 'rs-admin-fn-status-switch',
				'name' => 'verification'
			])
			->label(null,
			[
				'class' => 'rs-admin-label-switch',
				'for' => 'verification',
				'data-on' => $this->_language->get('enable'),
				'data-off' => $this->_language->get('disable')
			])
			->append('</li><li>')
			->label($this->_language->get('recovery'),
			[
				'for' => 'recovery'
			])
			->checkbox($settingModel->get('recovery') ?
			[
				'id' => 'recovery',
				'class' => 'rs-admin-fn-status-switch',
				'name' => 'recovery',
				'checked' => 'checked'
			] :
			[
				'id' => 'recovery',
				'class' => 'rs-admin-fn-status-switch',
				'name' => 'recovery'
			])
			->label(null,
			[
				'class' => 'rs-admin-label-switch',
				'for' => 'recovery',
				'data-on' => $this->_language->get('enable'),
				'data-off' => $this->_language->get('disable')
			])
			->append('</li></ul>')

			/* security */

			->radio(
			[
				'id' => self::class . '\Security',
				'class' => 'rs-admin-fn-status-accordion',
				'name' => self::class . '\Accordion'
			])
			->label($this->_language->get('security'),
			[
				'class' => 'rs-admin-fn-toggle-accordion rs-admin-label-accordion',
				'for' => self::class . '\Security'
			])
			->append('<ul class="rs-admin-fn-content-accordion rs-admin-box-accordion"><li>')
			->label($this->_language->get('moderation'),
			[
				'for' => 'moderation'
			])
			->checkbox($settingModel->get('moderation') ?
			[
				'id' => 'moderation',
				'class' => 'rs-admin-fn-status-switch',
				'name' => 'moderation',
				'checked' => 'checked'
			] :
			[
				'id' => 'moderation',
				'class' => 'rs-admin-fn-status-switch',
				'name' => 'moderation'
			])
			->label(null,
			[
				'class' => 'rs-admin-label-switch',
				'for' => 'moderation',
				'data-on' => $this->_language->get('enable'),
				'data-off' => $this->_language->get('disable')
			])
			->append('</li><li>')
			->label($this->_language->get('captcha'),
			[
				'for' => 'captcha'
			])
			->select($helperOption->getCaptchaArray(),
			[
				$settingModel->get('captcha')
			],
			[
				'id' => 'captcha',
				'name' => 'captcha'
			])
			->append('</li></ul>')
			->token()
			->append('<div class="rs-admin-wrapper-button">')
			->cancel()
			->save()
			->append('</div>');

		/* collect output */

		$output .= $titleElement . $formElement;
		$output .= Module\Hook::trigger('adminSettingFormEnd');
		return $output;
	}
}
