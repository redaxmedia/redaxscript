<?php
namespace Redaxscript\Modules\Contact;

use Redaxscript\Html;
use Redaxscript\Filter;
use Redaxscript\Mailer;
use Redaxscript\Messenger;
use Redaxscript\Model;
use Redaxscript\Module;
use Redaxscript\Validator;

/**
 * simple contact form
 *
 * @since 2.6.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class Contact extends Module\Module
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray =
	[
		'name' => 'Contact',
		'alias' => 'Contact',
		'author' => 'Redaxmedia',
		'description' => 'Simple contact form',
		'version' => '4.0.0'
	];

	/**
	 * routeHeader
	 *
	 * @since 3.3.0
	 */

	public function routeHeader()
	{
		if ($this->_request->getPost(get_class()) === 'submit')
		{
			$this->_request->set('routerBreak', true);
		}
	}

	/**
	 * routeContent
	 *
	 * @since 3.3.0
	 */

	public function routeContent()
	{
		if ($this->_request->getPost(get_class()) === 'submit')
		{
			echo $this->process();
		}
	}

	/**
	 * render
	 *
	 * @since 2.6.0
	 *
	 * @return string
	 */

	public function render() : string
	{
		$settingModel = new Model\Setting();
		$formElement = new Html\Form($this->_registry, $this->_language);
		$formElement->init(
		[
			'button' =>
			[
				'submit' =>
				[
					'name' => get_class()
				]
			]
		],
		[
			'captcha' => $settingModel->get('captcha') > 0
		]);

		/* create the form */

		$formElement
			->legend()
			->append('<ul><li>')
			->label('* ' . $this->_language->get('author'),
			[
				'for' => 'author'
			])
			->text(
			[
				'id' => 'author',
				'name' => 'author',
				'readonly' => $this->_registry->get('myName') ? 'readonly' : null,
				'required' => 'required',
				'value' => $this->_registry->get('myName')
			])
			->append('</li><li>')
			->label('* ' . $this->_language->get('email'),
			[
				'for' => 'email'
			])
			->email(
			[
				'id' => 'email',
				'name' => 'email',
				'readonly' => $this->_registry->get('myEmail') ? 'readonly' : null,
				'required' => 'required',
				'value' => $this->_registry->get('myEmail')
			])
			->append('</li><li>')
			->label($this->_language->get('url'),
			[
				'for' => 'url'
			])
			->url(
			[
				'id' => 'url',
				'name' => 'url'
			])
			->append('</li><li>')
			->label('* ' . $this->_language->get('message'),
			[
				'for' => 'text'
			])
			->textarea(
			[
				'id' => 'text',
				'name' => 'text',
				'required' => 'required'
			])
			->append('</li>');
		if ($settingModel->get('captcha') > 0)
		{
			$formElement
				->append('<li>')
				->captcha('task')
				->append('</li>');
		}
		$formElement->append('</ul>');
		if ($settingModel->get('captcha') > 0)
		{
			$formElement->captcha('solution');
		}
		$formElement
			->token()
			->submit()
			->reset();
		return $formElement->render();
	}

	/**
	 * process
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function process()
	{
		$postArray = $this->_normalizePost($this->_sanitizePost());
		$validateArray = $this->_validatePost($postArray);

		/* handle validate */

		if ($validateArray)
		{
			return $this->_error(
			[
				'message' => $validateArray
			]);
		}

		/* handle mail */

		$mailArray =
		[
			'author' => $postArray['author'],
			'email' => $postArray['email'],
			'url' => $postArray['url'],
			'text' => $postArray['text']
		];
		if ($this->_mail($mailArray))
		{
			return $this->_success(
			[
				'message' => $this->_language->get('message_sent', '_contact')
			]);
		}

		/* handle error */

		return $this->_error(
		[
			'message' => $this->_language->get('email_failed')
		]);
	}

	/**
	 * normalize the post
	 *
	 * @since 4.0.0
	 *
	 * @param array $postArray array of the post
	 *
	 * @return array
	 */

	protected function _normalizePost(array $postArray = [])
	{
		return array_map(function($value)
		{
			return $value === '' ? null : $value;
		}, $postArray);
	}

	/**
	 * sanitize the post
	 *
	 * @since 4.0.0
	 *
	 * @return array
	 */

	protected function _sanitizePost()
	{
		$specialFilter = new Filter\Special();
		$emailFilter = new Filter\Email();
		$urlFilter = new Filter\Url();
		$htmlFilter = new Filter\Html();

		/* sanitize post */

		return
		[
			'author' => $specialFilter->sanitize($this->_request->getPost('author')),
			'email' => $emailFilter->sanitize($this->_request->getPost('email')),
			'url' => $urlFilter->sanitize($this->_request->getPost('url')),
			'text' => nl2br($htmlFilter->sanitize($this->_request->getPost('text'))),
			'task' => $this->_request->getPost('task'),
			'solution' => $this->_request->getPost('solution')
		];
	}

	/**
	 * validate
	 *
	 * @since 3.0.0
	 *
	 * @param array $postArray array of the post
	 *
	 * @return array
	 */

	protected function _validatePost($postArray = [])
	{
		$emailValidator = new Validator\Email();
		$urlValidator = new Validator\Url();
		$captchaValidator = new Validator\Captcha();
		$settingModel = new Model\Setting();
		$validateArray = [];

		/* validate post */

		if (!$postArray['author'])
		{
			$validateArray[] = $this->_language->get('author_empty');
		}
		if (!$postArray['email'])
		{
			$validateArray[] = $this->_language->get('email_empty');
		}
		else if (!$emailValidator->validate($postArray['email']))
		{
			$validateArray['email'] = $this->_language->get('email_incorrect');
		}
		if ($postArray['url'] && !$urlValidator->validate($postArray['url']))
		{
			$validateArray[] = $this->_language->get('url_incorrect');
		}
		if (!$postArray['text'])
		{
			$validateArray[] = $this->_language->get('message_empty');
		}
		if ($settingModel->get('captcha') > 0 && !$captchaValidator->validate($postArray['task'], $postArray['solution']))
		{
			$validateArray[] = $this->_language->get('captcha_incorrect');
		}
		return $validateArray;
	}

	/**
	 * mail
	 *
	 * @since 3.0.0
	 *
	 * @param array $mailArray
	 *
	 * @return boolean
	 */

	protected function _mail($mailArray = [])
	{
		$settingModel = new Model\Setting();

		/* html element */

		$element = new Html\Element();
		$linkEmail = $element
			->copy()
			->init('a',
			[
				'href' => 'mailto:' . $mailArray['email']
			])
			->text($mailArray['email']);
		$linkUrl = $element
			->copy()
			->init('a',
			[
				'href' => $mailArray['url']
			])
			->text($mailArray['url'] ? $mailArray['url'] : $this->_language->get('none'));

		/* prepare mail */

		$toArray =
		[
			$settingModel->get('author') => $settingModel->get('email')
		];
		$fromArray =
		[
			$mailArray['author'] => $mailArray['email']
		];
		$subject = $this->_language->get('contact');
		$bodyArray =
		[
			$this->_language->get('author') . $this->_language->get('colon') . ' ' . $mailArray['author'],
			'<br />',
			$this->_language->get('email') . $this->_language->get('colon') . ' ' . $linkEmail,
			'<br />',
			$this->_language->get('url') . $this->_language->get('colon') . ' ' . $linkUrl,
			'<br />',
			$this->_language->get('message') . $this->_language->get('colon') . ' ' . $mailArray['text']
		];

		/* send mail */

		$mailer = new Mailer();
		$mailer->init($toArray, $fromArray, $subject, $bodyArray);
		return $mailer->send();
	}

	/**
	 * show the success
	 *
	 * @since 3.0.0
	 *
	 * @param array $successArray array of the success
	 *
	 * @return string
	 */

	protected function _success(array $successArray = [])
	{
		$messenger = new Messenger($this->_registry);
		return $messenger
			->setUrl($this->_language->get('home'), $this->_registry->get('root'))
			->doRedirect()
			->success($successArray['message'], $this->_language->get('operation_completed'));
	}

	/**
	 * show the error
	 *
	 * @since 3.0.0
	 *
	 * @param array $errorArray array of the error
	 *
	 * @return string
	 */

	protected function _error($errorArray = [])
	{
		$messenger = new Messenger($this->_registry);
		return $messenger
			->setUrl($this->_language->get('home'), $this->_registry->get('root'))
			->error($errorArray['message'], $this->_language->get('error_occurred'));
	}
}
