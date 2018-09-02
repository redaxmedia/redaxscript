<?php
namespace Redaxscript\Modules\Contact;

use Redaxscript\Controller\ControllerAbstract;
use Redaxscript\Html;
use Redaxscript\Filter;
use Redaxscript\Mailer;
use Redaxscript\Model;
use Redaxscript\Validator;

/**
 * children class to process the contact request
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class Controller extends ControllerAbstract
{
	/**
	 * process
	 *
	 * @since 4.0.0
	 *
	 * @return string
	 */

	public function process() : string
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
				'route' => $this->_registry->get('liteRoute'),
				'timeout' => 2,
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
	 * sanitize the post
	 *
	 * @since 4.0.0
	 *
	 * @return array
	 */

	protected function _sanitizePost() : array
	{
		$numberFilter = new Filter\Number();
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
			'task' => $numberFilter->sanitize($this->_request->getPost('task')),
			'solution' => $this->_request->getPost('solution')
		];
	}

	/**
	 * validate
	 *
	 * @since 4.0.0
	 *
	 * @param array $postArray array of the post
	 *
	 * @return array
	 */

	protected function _validatePost($postArray = []) : array
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
	 * @since 4.0.0
	 *
	 * @param array $mailArray
	 *
	 * @return bool
	 */

	protected function _mail($mailArray = []) : bool
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
}
